<x-layouts.app title="Airlink Notes" navbar-title="Airlink Notes">
    <script>
        (async () => {
            const token = window.Airlink.getToken();
            if (!token) return;
            try {
                await window.Airlink.api('/me');
                location.href = '/notes';
            } catch (_) {
                window.Airlink.clearToken();
            }
        })();
    </script>
    <style>
        .container { max-width: none; margin: 0; padding: 0; min-height: auto; }
        .shell { max-width: none; margin: 0; }
        .landing { min-height: calc(100vh - 64px); }
        .hero { position: relative; overflow: hidden; border-bottom: 1px solid rgba(0, 0, 0, 0.06); min-height: calc(100vh - 64px); display: flex; align-items: flex-start; }
        .hero-bg { position: absolute; inset: -120px; background: radial-gradient(900px 500px at 15% 25%, rgba(0, 113, 227, 0.22), transparent 60%), radial-gradient(900px 500px at 70% 10%, rgba(255, 45, 85, 0.18), transparent 55%), radial-gradient(900px 500px at 70% 80%, rgba(52, 199, 89, 0.16), transparent 60%), linear-gradient(180deg, rgba(245, 245, 247, 0.92), rgba(255, 255, 255, 0.95)); filter: saturate(1.05); }
        .hero-inner { position: relative; width: 100%; padding: 72px 18px 44px; max-width: 1120px; margin: 0 auto; display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 34px; align-items: center; }
        .hero-left { max-width: 720px; }
        .hero-badge { display: inline-flex; align-items: center; gap: 10px; }
        .hero-badge img { width: 44px; height: 44px; border-radius: 14px; background: #fff; border: 1px solid rgba(0,0,0,0.08); }
        .hero-title { font-size: 52px; font-weight: 850; letter-spacing: -0.05em; line-height: 1.02; margin-top: 16px; }
        .hero-sub { margin-top: 12px; font-size: 15px; line-height: 1.7; color: rgba(29, 29, 31, 0.72); max-width: 680px; }
        .hero-cta { margin-top: 18px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .hero-meta { margin-top: 14px; color: rgba(29,29,31,0.60); font-size: 12px; }
        .hero-card { background: rgba(255, 255, 255, 0.82); border: 1px solid rgba(0,0,0,0.10); border-radius: 24px; padding: 18px; box-shadow: none; backdrop-filter: blur(16px); }
        .hero-card-top { display:flex; align-items:center; justify-content:space-between; gap: 10px; }
        .hero-card-title { font-weight: 820; letter-spacing: -0.02em; }
        .hero-card-body { margin-top: 10px; color: rgba(29,29,31,0.72); font-size: 13px; line-height: 1.55; }
        .hero-chip { display:inline-flex; align-items:center; gap: 8px; font-size: 12px; padding: 6px 10px; border-radius: 999px; border: 1px solid rgba(0,0,0,0.10); background: rgba(255,255,255,0.65); color: rgba(29,29,31,0.72); }
        .sections { max-width: 1120px; margin: 0 auto; padding: 28px 18px 54px; }
        .grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 16px; }
        .card { grid-column: span 4; background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(0,0,0,0.08); border-radius: 22px; padding: 18px; box-shadow: none; }
        .card h3 { margin: 0; font-size: 16px; font-weight: 820; letter-spacing: -0.02em; }
        .card p { margin: 8px 0 0; color: rgba(29,29,31,0.70); font-size: 13px; line-height: 1.6; }
        .reveal { opacity: 0; transform: translateY(14px); transition: opacity 480ms ease, transform 520ms ease; }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .wide { grid-column: span 12; padding: 22px; border-radius: 26px; background: linear-gradient(180deg, rgba(245,245,247,0.65), rgba(255,255,255,0.95)); border: 1px solid rgba(0,0,0,0.08); box-shadow: none; }
        .wide h2 { margin: 0; font-size: 22px; font-weight: 850; letter-spacing: -0.03em; }
        .wide p { margin: 8px 0 0; color: rgba(29,29,31,0.70); line-height: 1.65; }
        @media (max-width: 980px) { .hero-inner { grid-template-columns: 1fr; } .hero-title { font-size: 44px; } .card { grid-column: span 12; } }
    </style>

    <div class="landing">
        <section class="hero">
            <div class="hero-bg" aria-hidden="true"></div>
            <div class="hero-inner">
                <div class="reveal hero-left">
                    <div class="hero-badge">
                        <img src="/brand/airlink/airlink-notes-logo.png" alt="Airlink Notes">
                        <span class="hero-chip">Airlink Notes</span>
                    </div>
                    <div class="hero-title">Seu bloco de notas pessoal.<br>Organizado e seguro.</div>
                    <div class="hero-sub">
                        Capture ideias, registre decisões e mantenha tudo acessível. Pastas, tags e cores ajudam você a encontrar o que importa com rapidez.
                    </div>
                    <div class="hero-cta">
                        <a href="/login"><button class="primary" type="button" style="min-width:190px;">Entrar</button></a>
                        <a href="https://account-id.sacratech.com" target="_blank" rel="noopener noreferrer"><button type="button" style="min-width:190px;">Criar Sacratech iD</button></a>
                    </div>
                    <div class="hero-meta">Sacratech iD • 2FA • Pasta Oculta (PIN) • Autosave</div>
                </div>

                <div class="hero-card reveal">
                    <div class="hero-card-top">
                        <div class="hero-card-title">Reunião — pauta</div>
                        <span class="hero-chip">Tag: Projeto</span>
                    </div>
                    <div class="hero-card-body">
                        - Objetivos da semana<br>
                        - Pendências do time<br>
                        - Próximos passos<br><br>
                        Decisão: priorizar entregas de maior impacto e registrar aprendizados.
                    </div>
                    <div style="height:12px;"></div>
                    <div style="display:flex; gap:10px; align-items:center;">
                        <div style="height:8px; width:8px; border-radius:999px; background:#0071e3;"></div>
                        <div class="hero-meta">Salvando automaticamente…</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="sections">
            <div class="grid">
                <div class="wide reveal">
                    <h2>Feito para organização leve</h2>
                    <p>Tags bonitas na lista lateral, pastas com cores e uma experiência fluida para arrastar, soltar e priorizar.</p>
                </div>
                <div class="card reveal">
                    <h3>Tags e cores</h3>
                    <p>Destaque notas importantes com tags coloridas e veja a tag no topo da nota.</p>
                </div>
                <div class="card reveal">
                    <h3>Pastas com identidade</h3>
                    <p>Escolha cores para as pastas e reconheça rapidamente onde cada nota está.</p>
                </div>
                <div class="card reveal">
                    <h3>Segurança em primeiro lugar</h3>
                    <p>Autenticação com Sacratech iD e opção de Pasta Oculta protegida por PIN.</p>
                </div>
            </div>
        </section>
    </div>

    <script>
        (async () => {
            const token = window.Airlink.getToken();
            if (token) {
                try {
                    await window.Airlink.api('/me');
                    location.href = '/notes';
                    return;
                } catch (_) {
                    window.Airlink.clearToken();
                }
            }

            const els = Array.from(document.querySelectorAll('.reveal'));
            const io = new IntersectionObserver((entries) => {
                for (const e of entries) {
                    if (e.isIntersecting) e.target.classList.add('in');
                }
            }, { threshold: 0.18 });
            els.forEach((el) => io.observe(el));
        })();
    </script>
</x-layouts.app>

