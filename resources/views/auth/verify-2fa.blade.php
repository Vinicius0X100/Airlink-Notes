<x-layouts.app title="Verificar 2FA">
    <style>
        .auth-wrap { display:flex; justify-content:center; padding: 22px 0 0; }
        .auth-card { width: 100%; max-width: 460px; padding: 0; overflow: hidden; }
        .auth-head { padding: 22px 22px 0; display:flex; align-items:center; justify-content:space-between; gap: 10px; }
        .auth-logos { display:flex; align-items:center; gap: 10px; }
        .auth-logos img { width: 28px; height: 28px; border-radius: 8px; }
        .auth-title { font-size: 16px; font-weight: 800; letter-spacing: -0.02em; }
        .auth-sub { margin-top: 4px; color: rgba(29,29,31,0.70); font-size: 13px; }
        .auth-body { padding: 18px 22px 22px; }
        .actions { display:flex; justify-content:space-between; align-items:center; gap: 10px; }
        .link { background: transparent; border-color: transparent; box-shadow: none; padding: 10px 10px; color: rgba(29,29,31,0.72); }
        .link:hover { box-shadow: none; transform: none; background: rgba(0,0,0,0.04); border-color: rgba(0,0,0,0.06); }
        .divider { height: 1px; background: rgba(0,0,0,0.08); margin: 14px 0; }
        .otp-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
        .otp-digit { width: 44px; height: 52px; text-align: center; font-size: 22px; font-weight: 700; border-radius: 12px; }
        .otp-helper { text-align: center; margin-top: 8px; }
    </style>

    <div class="auth-wrap">
        <div class="card auth-card">
            <div class="auth-head">
                <div>
                    <div class="auth-logos">
                        <img src="/brand/airlink/SacratechID-Icon.png" alt="Sacratech iD">
                        <img src="/brand/airlink/airlink-icon-black.png" alt="Airlink">
                        <span class="auth-title">Sacratech iD</span>
                    </div>
                    <div class="auth-sub">Verificação para continuar no Airlink Notes</div>
                </div>
                <a href="/login"><button class="link" type="button">Trocar conta</button></a>
            </div>

            <div class="auth-body">
                <div class="divider"></div>
                <div style="font-size:22px; font-weight:800; letter-spacing:-0.03em;">Verificação em 2 fatores</div>
                <div class="muted" style="margin-top:6px;">Digite o código de 6 dígitos do seu app autenticador (Google, Microsoft, Authy, 1Password...)</div>

                <div style="height:16px"></div>

                <label for="email">Email</label>
                <input id="email" type="email" autocomplete="email">

                <div style="height:12px"></div>

                <label for="otp-1">Código</label>
                <div class="otp-group">
                    <input id="otp-1" class="otp-digit" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1">
                    <input id="otp-2" class="otp-digit" type="text" inputmode="numeric" maxlength="1">
                    <input id="otp-3" class="otp-digit" type="text" inputmode="numeric" maxlength="1">
                    <input id="otp-4" class="otp-digit" type="text" inputmode="numeric" maxlength="1">
                    <input id="otp-5" class="otp-digit" type="text" inputmode="numeric" maxlength="1">
                    <input id="otp-6" class="otp-digit" type="text" inputmode="numeric" maxlength="1">
                </div>
                <input id="otp" type="hidden">
                <div class="muted otp-helper">Digite os 6 dígitos em sequência</div>

                <div style="height:16px"></div>

                <div class="actions">
                    <button id="btn-back" class="link" type="button">Voltar</button>
                    <button class="primary" id="btn-verify" type="button" style="min-width:160px;">Validar</button>
                </div>

                <div style="height:12px"></div>
                <div class="error" id="err" style="display:none"></div>
            </div>
        </div>
    </div>

    <script>
        const emailEl = document.getElementById('email');
        const otpEl = document.getElementById('otp');
        const otpDigits = Array.from(document.querySelectorAll('.otp-digit'));
        const btnVerify = document.getElementById('btn-verify');
        const btnBack = document.getElementById('btn-back');
        const errEl = document.getElementById('err');

        function setError(msg) {
            errEl.textContent = msg;
            errEl.style.display = msg ? 'block' : 'none';
        }

        function syncOtpFromDigits() {
            otpEl.value = otpDigits.map((el) => (el.value || '').replace(/\D/g, '')).join('');
        }

        btnBack.addEventListener('click', () => {
            location.href = '/login';
        });

        btnVerify.addEventListener('click', async () => {
            setError('');
            btnVerify.disabled = true;
            try {
                const challenge = window.Airlink.getChallenge();
                syncOtpFromDigits();
                const otp = (otpEl.value || '').trim();
                if (otp.length !== 6) {
                    setError('Informe o código completo.');
                    return;
                }

                const payload = await window.Airlink.api('/verify-2fa', {
                    method: 'POST',
                    body: {
                        email: (emailEl.value || challenge.email || '').trim(),
                        otp,
                        challenge_id: challenge.challenge_id || null,
                    },
                });

                window.Airlink.setToken(payload.token);
                window.Airlink.clearChallenge();
                location.href = '/notes';
            } catch (e) {
                setError(e.message || 'Falha ao validar.');
            } finally {
                btnVerify.disabled = false;
            }
        });

        (() => {
            const challenge = window.Airlink.getChallenge();
            if (challenge.email) emailEl.value = challenge.email;
            if (otpDigits[0]) otpDigits[0].focus();
        })();

        otpDigits.forEach((el, idx) => {
            el.addEventListener('input', (e) => {
                const only = (e.target.value || '').replace(/\D/g, '');
                e.target.value = only ? only.slice(-1) : '';
                syncOtpFromDigits();
                if (e.target.value && idx < otpDigits.length - 1) otpDigits[idx + 1].focus();
            });
            el.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btnVerify.click();
                    return;
                }
                if (e.key === 'Backspace' && !el.value && idx > 0) otpDigits[idx - 1].focus();
                if (e.key === 'ArrowLeft' && idx > 0) otpDigits[idx - 1].focus();
                if (e.key === 'ArrowRight' && idx < otpDigits.length - 1) otpDigits[idx + 1].focus();
            });
        });

        if (otpDigits[0]) {
            otpDigits[0].addEventListener('paste', (e) => {
                const text = (e.clipboardData?.getData('text') || '').replace(/\D/g, '');
                if (!text) return;
                e.preventDefault();
                otpDigits.forEach((el, idx) => { el.value = text[idx] || ''; });
                syncOtpFromDigits();
                const last = Math.min(text.length, otpDigits.length) - 1;
                if (last >= 0) otpDigits[last].focus();
            });
        }
    </script>
</x-layouts.app>

