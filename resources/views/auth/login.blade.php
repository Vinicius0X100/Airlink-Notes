<x-layouts.app title="Iniciar sessão">
    <style>
        .auth-wrap { display:flex; justify-content:center; padding: 22px 0 0; }
        .auth-card { width: 100%; max-width: 460px; padding: 0; overflow: hidden; }
        .auth-head { padding: 26px 22px 0; display:flex; align-items:center; justify-content:center; text-align:center; }
        .auth-logos { display:flex; align-items:center; justify-content:center; gap: 12px; }
        .auth-logos img { width: 44px; height: 44px; border-radius: 12px; background: #fff; }
        .auth-title { font-size: 16px; font-weight: 800; letter-spacing: -0.02em; }
        .auth-sub { margin-top: 4px; color: rgba(29,29,31,0.70); font-size: 13px; }
        .auth-body { padding: 18px 22px 22px; }
        .step { transition: opacity 220ms ease, transform 220ms ease; }
        .step.hidden { opacity: 0; transform: translateY(8px); pointer-events: none; position: absolute; inset: 0; }
        .step-shell { position: relative; min-height: 280px; }
        .actions { display:flex; justify-content:space-between; align-items:center; gap: 10px; }
        .link { background: transparent; border-color: transparent; box-shadow: none; padding: 10px 10px; color: rgba(29,29,31,0.72); }
        .link:hover { box-shadow: none; transform: none; background: rgba(0,0,0,0.04); border-color: rgba(0,0,0,0.06); }
        .divider { height: 1px; background: rgba(0,0,0,0.08); margin: 14px 0; }
        .spinner { width: 14px; height: 14px; border-radius: 999px; border: 2px solid rgba(255,255,255,0.35); border-top-color: rgba(255,255,255,0.95); display: none; animation: spin 700ms linear infinite; }
        button.primary .spinner { border-color: rgba(255,255,255,0.35); border-top-color: rgba(255,255,255,0.95); }
        .btn-loading { display: inline-flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-loading.loading .spinner { display: inline-block; }
        .btn-loading.loading .btn-text { opacity: 0.92; }
        .otp-group { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
        .otp-digit { width: 44px; height: 52px; text-align: center; font-size: 22px; font-weight: 700; letter-spacing: 0.02em; border-radius: 12px; }
        .otp-helper { text-align: center; margin-top: 8px; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>

    <div class="auth-wrap">
        <div class="card auth-card">
            <div class="auth-head">
                <div>
                    <div class="auth-logos">
                        <img src="/brand/airlink/SacratechID-Icon.png" alt="Sacratech iD">
                        <img src="/brand/airlink/airlink-icon-black.png" alt="Airlink">
                    </div>
                    <div style="height:10px;"></div>
                    <div class="auth-title">Sacratech iD</div>
                    <div class="auth-sub">Entre para continuar no Airlink Notes</div>
                </div>
            </div>

            <div class="auth-body">
                <div class="step-shell">
                    <div id="step-email" class="step">
                        <div class="divider"></div>
                        <div style="font-size:22px; font-weight:800; letter-spacing:-0.03em;">Digite seu email</div>
                        <div class="muted" style="margin-top:6px;">Vamos verificar sua conta primeiro</div>

                        <div style="height:16px"></div>

                        <label for="email">Email</label>
                        <input id="email" type="email" autocomplete="email" inputmode="email">

                        <div style="height:16px"></div>

                        <div class="actions">
                            <a href="/"><button class="link" type="button">Voltar</button></a>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <a href="https://account-id.sacratech.com" target="_blank" rel="noopener noreferrer"><button class="link" type="button">Criar conta</button></a>
                                <button class="primary btn-loading" id="btn-email" type="button" style="min-width:160px;">
                                    <span class="spinner" aria-hidden="true"></span>
                                    <span class="btn-text">Continuar</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="step-password" class="step hidden">
                        <div class="divider"></div>
                        <div style="font-size:22px; font-weight:800; letter-spacing:-0.03em;">Digite sua senha</div>
                        <div class="muted" id="hint-password" style="margin-top:6px;"></div>

                        <div style="height:16px"></div>

                        <label for="password">Senha</label>
                        <input id="password" type="password" autocomplete="current-password">

                        <div style="height:16px"></div>

                        <div class="actions">
                            <button class="link" id="btn-back-email" type="button">Trocar email</button>
                            <button class="primary btn-loading" id="btn-password" type="button" style="min-width:160px;">
                                <span class="spinner" aria-hidden="true"></span>
                                <span class="btn-text">Entrar</span>
                            </button>
                        </div>
                    </div>

                    <div id="step-otp" class="step hidden">
                        <div class="divider"></div>
                        <div style="font-size:22px; font-weight:800; letter-spacing:-0.03em;">Verificação em 2 fatores</div>
                        <div class="muted" style="margin-top:6px;">Digite o código do seu app autenticador (Google, Microsoft, Authy, 1Password...)</div>

                        <div style="height:16px"></div>

                        <label for="otp-1">Código</label>
                        <div class="otp-group" id="otp-group">
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
                            <button class="link" id="btn-back-password" type="button">Voltar</button>
                            <button class="primary btn-loading" id="btn-verify" type="button" style="min-width:160px;">
                                <span class="spinner" aria-hidden="true"></span>
                                <span class="btn-text">Verificar</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="height:12px"></div>

                <div class="error" id="err" style="display:none"></div>
            </div>
        </div>
    </div>

    <script>
        const emailEl = document.getElementById('email');
        const passEl = document.getElementById('password');
        const otpEl = document.getElementById('otp');
        const otpDigits = Array.from(document.querySelectorAll('.otp-digit'));

        const btnEmail = document.getElementById('btn-email');
        const btnPassword = document.getElementById('btn-password');
        const btnVerify = document.getElementById('btn-verify');

        const btnBackEmail = document.getElementById('btn-back-email');
        const btnBackPassword = document.getElementById('btn-back-password');

        const hintPasswordEl = document.getElementById('hint-password');
        const errEl = document.getElementById('err');

        const stepEmail = document.getElementById('step-email');
        const stepPassword = document.getElementById('step-password');
        const stepOtp = document.getElementById('step-otp');

        const state = {
            email: '',
            nome: null,
            twoFactorActive: false,
            challengeId: null,
        };

        function setError(msg) {
            errEl.textContent = msg || '';
            errEl.style.display = msg ? 'block' : 'none';
        }

        function setStep(step) {
            setError('');
            stepEmail.classList.toggle('hidden', step !== 'email');
            stepPassword.classList.toggle('hidden', step !== 'password');
            stepOtp.classList.toggle('hidden', step !== 'otp');

            if (step === 'email') setTimeout(() => emailEl && emailEl.focus(), 30);
            if (step === 'password') setTimeout(() => passEl && passEl.focus(), 30);
            if (step === 'otp') setTimeout(() => otpDigits[0] && otpDigits[0].focus(), 30);
        }

        function setLoading(button, loading) {
            button.classList.toggle('loading', loading);
            button.disabled = loading;
        }

        async function restoreIfPossible() {
            try {
                const existingToken = window.Airlink.getToken();
                if (existingToken) {
                    try {
                        await window.Airlink.api('/me');
                        location.href = '/notes';
                        return;
                    } catch (e) {
                        if (e && (e.status === 401 || e.status === 419)) {
                            window.Airlink.clearToken();
                        } else {
                            window.Airlink.clearToken();
                            return;
                        }
                    }
                }

                if (!document.cookie.includes('airlink_notes_uid=')) return;

                const payload = await window.Airlink.api('/session/restore', { method: 'POST' });
                if (payload && payload.ok && payload.token) {
                    window.Airlink.setToken(payload.token);
                    try {
                        await window.Airlink.api('/me');
                        location.href = '/notes';
                    } catch (e) {
                        window.Airlink.clearToken();
                        document.cookie = 'airlink_notes_uid=; Max-Age=0; Path=/';
                    }
                }
            } catch (e) {
                if (e && (e.status === 401 || e.status === 419)) {
                    window.Airlink.clearToken();
                    document.cookie = 'airlink_notes_uid=; Max-Age=0; Path=/';
                }
            }
        }

        function syncOtpFromDigits() {
            otpEl.value = otpDigits.map((el) => (el.value || '').replace(/\D/g, '')).join('');
        }

        function clearOtpDigits() {
            otpDigits.forEach((el) => { el.value = ''; });
            syncOtpFromDigits();
        }

        async function submitEmail() {
            const email = (emailEl.value || '').trim();
            if (!email) { setError('Informe seu email.'); return; }

            setLoading(btnEmail, true);
            try {
                const payload = await window.Airlink.api('/login/check-email', {
                    method: 'POST',
                    body: { email },
                });

                if (!payload.exists) {
                    setError('Conta não encontrada. Redirecionando para criar sua Sacratech iD…');
                    setTimeout(() => { location.href = window.Airlink.SACRATECH_ID_URL; }, 900);
                    return;
                }

                if (payload.inactive) {
                    setError('Usuário inativo.');
                    return;
                }

                state.email = email;
                state.nome = payload.nome || null;
                state.twoFactorActive = !!payload.two_factor_active;
                hintPasswordEl.textContent = state.nome ? `Olá, ${state.nome}.` : state.email;
                setStep('password');
            } catch (e) {
                setError(e.message || 'Falha ao verificar o email.');
            } finally {
                setLoading(btnEmail, false);
            }
        }

        async function submitPassword() {
            if (!state.email) { setStep('email'); return; }
            const password = passEl.value || '';
            if (!password) { setError('Informe sua senha.'); return; }

            setLoading(btnPassword, true);
            try {
                const payload = await window.Airlink.api('/login', {
                    method: 'POST',
                    body: { email: state.email, password },
                });

                if (payload.requires_2fa) {
                    state.challengeId = payload.challenge_id;
                    window.Airlink.setChallenge(payload.challenge_id, state.email);
                    setStep('otp');
                    return;
                }

                window.Airlink.setToken(payload.token);
                window.Airlink.clearChallenge();
                location.href = '/notes';
            } catch (e) {
                setError(e.message || 'Credenciais inválidas.');
            } finally {
                setLoading(btnPassword, false);
            }
        }

        async function submitOtp() {
            syncOtpFromDigits();
            const otp = (otpEl.value || '').trim();
            if (otp.length !== 6) { setError('Informe o código completo.'); return; }

            const challenge = window.Airlink.getChallenge();
            const challengeId = state.challengeId || (challenge && challenge.challenge_id) || null;
            const email = state.email || (challenge && challenge.email) || '';

            if (!challengeId || !email) { setError('Sessão de verificação expirada. Volte e entre novamente.'); return; }

            setLoading(btnVerify, true);
            try {
                const payload = await window.Airlink.api('/verify-2fa', {
                    method: 'POST',
                    body: {
                        email,
                        otp,
                        challenge_id: challengeId,
                    },
                });
                window.Airlink.setToken(payload.token);
                window.Airlink.clearChallenge();
                location.href = '/notes';
            } catch (e) {
                setError(e.message || 'Falha ao validar.');
            } finally {
                setLoading(btnVerify, false);
            }
        }

        btnEmail.addEventListener('click', submitEmail);
        btnPassword.addEventListener('click', submitPassword);
        btnVerify.addEventListener('click', submitOtp);

        btnBackEmail.addEventListener('click', () => {
            state.email = '';
            state.nome = null;
            state.twoFactorActive = false;
            state.challengeId = null;
            passEl.value = '';
            setStep('email');
        });

        btnBackPassword.addEventListener('click', () => {
            clearOtpDigits();
            state.challengeId = null;
            window.Airlink.clearChallenge();
            setStep('password');
        });

        emailEl.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); submitEmail(); } });
        passEl.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); submitPassword(); } });

        otpDigits.forEach((el, idx) => {
            el.addEventListener('input', (e) => {
                const only = (e.target.value || '').replace(/\D/g, '');
                e.target.value = only ? only.slice(-1) : '';
                syncOtpFromDigits();
                if (e.target.value && idx < otpDigits.length - 1) {
                    otpDigits[idx + 1].focus();
                }
            });

            el.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitOtp();
                    return;
                }
                if (e.key === 'Backspace' && !el.value && idx > 0) {
                    otpDigits[idx - 1].focus();
                }
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

        (async () => {
            await restoreIfPossible();
            if (window.Airlink.getToken()) {
                try {
                    await window.Airlink.api('/me');
                    location.href = '/notes';
                    return;
                } catch (_) {
                    window.Airlink.clearToken();
                }
            }
            const challenge = window.Airlink.getChallenge();
            if (challenge && challenge.challenge_id && challenge.email) {
                state.email = challenge.email;
                state.challengeId = challenge.challenge_id;
                clearOtpDigits();
                setStep('otp');
                return;
            }
            setStep('email');
        })();
    </script>
</x-layouts.app>

