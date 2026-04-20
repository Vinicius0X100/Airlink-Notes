<!doctype html>
<html lang="pt-BR">
@props([
    'title' => 'Airlink Notes',
    'navbarTitle' => 'Airlink Notes',
    'navbarShowTitle' => true,
])
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/brand/airlink/airlink-notes-logo.png">
    <script>
        window.Airlink = {
            SACRATECH_ID_URL: 'https://account-id.sacratech.com',
            tokenKey: 'airlink_notes_token',
            challengeKey: 'airlink_notes_2fa_challenge',
            emailKey: 'airlink_notes_email',
            getToken() { return localStorage.getItem(this.tokenKey); },
            setToken(token) { localStorage.setItem(this.tokenKey, token); },
            clearToken() { localStorage.removeItem(this.tokenKey); },
            setChallenge(challengeId, email) {
                localStorage.setItem(this.challengeKey, challengeId);
                localStorage.setItem(this.emailKey, email);
            },
            getChallenge() {
                return {
                    challenge_id: localStorage.getItem(this.challengeKey),
                    email: localStorage.getItem(this.emailKey),
                };
            },
            clearChallenge() {
                localStorage.removeItem(this.challengeKey);
                localStorage.removeItem(this.emailKey);
            },
            async api(path, { method = 'GET', body = null } = {}) {
                const headers = { 'Accept': 'application/json' };
                if (body !== null) headers['Content-Type'] = 'application/json';
                const token = this.getToken();
                if (token) headers['Authorization'] = 'Bearer ' + token;
                const res = await fetch('/api' + path, {
                    method,
                    headers,
                    body: body !== null ? JSON.stringify(body) : null,
                });
                let payload = null;
                const text = await res.text();
                try { payload = text ? JSON.parse(text) : null; } catch (_) { payload = null; }
                if (!res.ok) {
                    let message = payload && payload.message ? payload.message : 'Erro';
                    if (payload && payload.errors && typeof payload.errors === 'object') {
                        for (const key of Object.keys(payload.errors)) {
                            const arr = payload.errors[key];
                            if (Array.isArray(arr) && arr.length > 0) {
                                message = String(arr[0]);
                                break;
                            }
                        }
                    }
                    const err = new Error(message);
                    err.status = res.status;
                    err.payload = payload;
                    throw err;
                }
                return payload;
            },
            setNavbarUser(user) {
                const wrap = document.getElementById('nav-user');
                const nameEl = document.getElementById('nav-user-name');
                if (!wrap || !nameEl) return;

                const nome = user && user.nome ? String(user.nome) : '';
                const sobrenome = user && user.sobrenome ? String(user.sobrenome) : '';
                const full = (nome + ' ' + sobrenome).trim() || (user && user.email ? String(user.email) : 'Conta');

                nameEl.textContent = full;

                wrap.style.display = 'inline-flex';
                wrap.dataset.user = JSON.stringify(user || {});

                const tagBtn = document.getElementById('nav-tag-create');
                if (tagBtn) tagBtn.style.display = 'inline-flex';
            },
            async logoutAndRedirect() {
                try { await this.api('/logout', { method: 'POST' }); } catch (_) {}
                this.clearToken();
                this.clearChallenge();
                location.href = '/login';
            },
        };
    </script>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root { color-scheme: light; }
        body { margin: 0; font-family: "Inter", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji", "Segoe UI Emoji"; background: #ffffff; color: #1d1d1f; }
        a { color: inherit; text-decoration: none; }
        .container { max-width: 1120px; margin: 0 auto; padding: 28px 20px 0; min-height: calc(100vh - 120px); }
        .shell { max-width: 1120px; margin: 0 auto; }
        .card { background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(0, 0, 0, 0.06); border-radius: 22px; padding: 22px; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08); }
        .row { display: flex; gap: 16px; }
        .col { flex: 1; }
        label { display: block; font-size: 13px; font-weight: 500; color: rgba(29, 29, 31, 0.72); margin-bottom: 8px; }
        input, textarea { width: 100%; box-sizing: border-box; border: 1px solid rgba(0, 0, 0, 0.14); background: rgba(255, 255, 255, 0.95); color: #1d1d1f; padding: 12px 14px; border-radius: 14px; outline: none; transition: border-color 160ms ease, box-shadow 160ms ease; }
        input:focus, textarea:focus { border-color: rgba(0, 113, 227, 0.65); box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.12); }
        textarea { min-height: 220px; resize: vertical; }
        button { border: 1px solid rgba(0, 0, 0, 0.12); background: rgba(255, 255, 255, 0.9); color: #1d1d1f; padding: 12px 14px; border-radius: 14px; cursor: pointer; font-weight: 600; transition: background 160ms ease, border-color 160ms ease; }
        button:hover { background: rgba(255, 255, 255, 0.96); border-color: rgba(0, 0, 0, 0.16); }
        button:active { background: rgba(245, 245, 247, 0.95); }
        button.primary { background: #0071e3; border-color: #0071e3; color: #ffffff; }
        button.primary:hover { background: #0a7be6; border-color: #0a7be6; }
        button.danger { background: #b42318; border-color: #b42318; color: #ffffff; }
        button.danger:hover { background: #c42519; border-color: #c42519; }
        button:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; transform: none; }
        .muted { color: rgba(29, 29, 31, 0.72); font-size: 13px; }
        .error { color: #b42318; font-size: 13px; }
        .topbar { position: sticky; top: 0; z-index: 30; background: rgba(255, 255, 255, 0.72); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(0, 0, 0, 0.06); font-family: "Inter", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; }
        .topbar-inner { width: 100%; max-width: none; margin: 0; padding: 10px 18px; display: flex; justify-content: space-between; align-items: center; gap: 14px; box-sizing: border-box; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; letter-spacing: -0.02em; min-width: 0; }
        .brand img { height: 20px; width: auto; }
        .topbar-right { display: inline-flex; align-items: center; gap: 10px; min-width: 0; }
        .nav-user { position: relative; display: none; align-items: center; gap: 10px; flex: 0 0 auto; min-width: 0; }
        .nav-user-btn { height: 34px; border-radius: 12px; border: 1px solid transparent; background: transparent; padding: 0 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; min-width: 0; transition: background 140ms ease, border-color 140ms ease; }
        .nav-user-btn:hover { background: rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.08); }
        .nav-avatar { width: 16px; height: 16px; display: inline-flex; align-items: center; justify-content: center; color: rgba(29, 29, 31, 0.66); font-weight: 750; font-size: 12px; flex: 0 0 auto; }
        .nav-user-name { font-weight: 650; font-size: 13px; max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .nav-action { height: 34px; border-radius: 12px; border: 1px solid transparent; background: transparent; padding: 0 10px; cursor: pointer; display: none; align-items: center; justify-content: center; gap: 8px; font-weight: 650; letter-spacing: -0.01em; transition: background 140ms ease, border-color 140ms ease; }
        .nav-action:hover { background: rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.08); }
        .nav-action svg { width: 16px; height: 16px; }
        .nav-menu { position: absolute; right: 0; top: calc(100% + 10px); min-width: 220px; max-width: min(320px, calc(100vw - 24px)); background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(0, 0, 0, 0.10); border-radius: 20px; padding: 8px; backdrop-filter: blur(18px); box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12); z-index: 80; opacity: 0; transform: translateY(-6px) scale(0.98); pointer-events: none; transition: opacity 160ms ease, transform 180ms ease; transform-origin: top right; }
        .nav-menu.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
        .nav-menu button { width: 100%; text-align: left; border: 1px solid transparent; background: transparent; padding: 10px 10px; border-radius: 14px; font-weight: 650; display: flex; align-items: center; gap: 10px; }
        .nav-menu button:hover { background: rgba(0, 0, 0, 0.05); border-color: rgba(0, 0, 0, 0.08); }
        .nav-menu button[data-variant="danger"] { color: #b42318; }
        .nav-menu button[data-variant="danger"]:hover { background: rgba(180, 35, 24, 0.08); border-color: rgba(180, 35, 24, 0.14); }
        .nav-menu .nav-mi { width: 16px; height: 16px; flex: 0 0 auto; color: rgba(29, 29, 31, 0.64); }
        .nav-menu button[data-variant="danger"] .nav-mi { color: #b42318; }
        @media (max-width: 560px) {
            .topbar-inner { padding: 10px 12px; }
            .nav-user-name { display: none; }
            .nav-action span { display: none; }
            .nav-action { padding: 0; width: 34px; }
            .nav-user-btn { padding-right: 6px; }
        }
        input, select, textarea, button { font-family: inherit; }
        .modal input[type="color"] { -webkit-appearance: none; appearance: none; width: 100%; height: 44px; padding: 0; border-radius: 14px; border: 1px solid rgba(0,0,0,0.14); background: rgba(255,255,255,0.95); overflow: hidden; }
        .modal input[type="color"]::-webkit-color-swatch-wrapper { padding: 6px; }
        .modal input[type="color"]::-webkit-color-swatch { border: 0; border-radius: 10px; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.28); display: none; align-items: center; justify-content: center; z-index: 90; }
        .modal { width: 560px; max-width: calc(100vw - 24px); background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(0, 0, 0, 0.10); border-radius: 22px; padding: 0; backdrop-filter: blur(18px); overflow: hidden; }
        .modal-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px; border-bottom: 1px solid rgba(0, 0, 0, 0.08); }
        .modal-title { font-weight: 780; letter-spacing: -0.02em; }
        .modal-body { padding: 14px 16px 16px; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px; }
        .modal-btn { height: 40px; padding: 0 14px; border-radius: 12px; border: 1px solid rgba(0, 0, 0, 0.12); background: rgba(255, 255, 255, 0.70); font-weight: 700; letter-spacing: -0.01em; transition: background 140ms ease, border-color 140ms ease; }
        .modal-btn:hover { background: rgba(255, 255, 255, 0.92); border-color: rgba(0, 0, 0, 0.16); }
        .modal-btn[data-variant="primary"] { background: rgba(0, 113, 227, 0.95); border-color: rgba(0, 113, 227, 0.90); color: #fff; }
        .modal-btn[data-variant="primary"]:hover { background: rgba(0, 113, 227, 1); border-color: rgba(0, 113, 227, 1); }
        .modal label { display: block; font-size: 12px; font-weight: 750; letter-spacing: 0.06em; text-transform: uppercase; color: rgba(29, 29, 31, 0.55); margin-bottom: 8px; }
        .modal input[type="text"], .modal input[type="password"] { width: 100%; box-sizing: border-box; border: 1px solid rgba(0,0,0,0.14); background: rgba(255,255,255,0.92); padding: 12px 14px; border-radius: 14px; outline: none; font-size: 14px; }
        .modal input[type="text"]:focus, .modal input[type="password"]:focus { border-color: rgba(0, 113, 227, 0.40); }
        .settings-layout { display: grid; grid-template-columns: 190px 1fr; min-height: 360px; }
        .settings-nav { background: rgba(245, 245, 247, 0.70); border-right: 1px solid rgba(0, 0, 0, 0.08); padding: 10px; }
        .settings-tab { width: 100%; border: 1px solid transparent; background: transparent; text-align: left; padding: 10px 10px; border-radius: 14px; font-weight: 700; letter-spacing: -0.01em; color: rgba(29, 29, 31, 0.78); }
        .settings-tab:hover { background: rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.06); }
        .settings-tab[data-active="true"] { background: rgba(0, 0, 0, 0.06); border-color: rgba(0, 0, 0, 0.08); }
        .settings-content { padding: 14px 14px; }
        .settings-panel { display: none; }
        .settings-panel[data-active="true"] { display: block; }
        .setting-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.06); }
        .setting-row:last-child { border-bottom: 0; }
        .setting-title { font-weight: 750; letter-spacing: -0.02em; }
        .setting-sub { margin-top: 4px; color: rgba(29,29,31,0.62); font-size: 12px; line-height: 1.4; }
        .toggle { width: 44px; height: 28px; border-radius: 999px; border: 1px solid rgba(0,0,0,0.14); background: rgba(0,0,0,0.06); position: relative; cursor: pointer; flex: 0 0 auto; }
        .toggle:after { content: ""; width: 22px; height: 22px; border-radius: 999px; background: #fff; border: 1px solid rgba(0,0,0,0.10); position: absolute; top: 2px; left: 2px; transition: transform 160ms ease; }
        .toggle[data-on="true"] { background: rgba(0, 113, 227, 0.95); border-color: rgba(0, 113, 227, 0.60); }
        .toggle[data-on="true"]:after { transform: translateX(16px); }
        .pin-grid { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 8px; }
        .pin-box { height: 46px; text-align: center; font-size: 20px; font-weight: 800; border-radius: 12px; }
        @media (max-width: 720px) { .settings-layout { grid-template-columns: 1fr; } .settings-nav { border-right: 0; border-bottom: 1px solid rgba(0,0,0,0.08); } }
        .modal-section { border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 18px; padding: 14px; background: rgba(245, 245, 247, 0.55); }
        .modal-section-title { font-weight: 750; letter-spacing: -0.02em; }
        .pill { font-size: 12px; padding: 7px 10px; border-radius: 999px; border: 1px solid rgba(0, 0, 0, 0.10); color: rgba(29, 29, 31, 0.72); background: rgba(255, 255, 255, 0.65); }
        .footer { background: #f5f5f7; border-top: 1px solid rgba(0, 0, 0, 0.08); font-family: "Inter", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; }
        .footer-inner { max-width: 1120px; margin: 0 auto; padding: 18px 20px 26px; color: rgba(29, 29, 31, 0.64); font-size: 12px; line-height: 1.45; }
        .footer a { color: rgba(29, 29, 31, 0.72); text-decoration: none; }
        .footer a:hover { text-decoration: underline; text-underline-offset: 4px; }
        .footer-top { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
        .footer-logos { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .footer-logos img { height: 18px; width: auto; }
        .footer-links { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .footer-links a { color: rgba(29, 29, 31, 0.72); }
        .footer-links a:hover { text-decoration: underline; text-underline-offset: 4px; }
        .footer-divider { height: 1px; background: rgba(0, 0, 0, 0.08); margin: 14px 0 12px; }
        .fade-in { animation: fadeIn 240ms ease-out both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        @media (prefers-reduced-motion: reduce) { .fade-in { animation: none; } button { transition: none; } input, textarea { transition: none; } }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="/">
                <img src="/brand/airlink/airlink-icon-black.png" alt="Airlink Notes">
                @if ($navbarShowTitle)
                    <span>{{ $navbarTitle }}</span>
                @endif
            </a>
            <div class="topbar-right">
                <button id="nav-tag-create" class="nav-action" type="button" aria-label="Nova tag" title="Nova tag">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20.59 13.41 12 22l-9-9V2h11l6.59 6.59a2 2 0 0 1 0 2.82z"></path>
                        <path d="M7 7h.01"></path>
                        <path d="M12 12h6"></path>
                        <path d="M15 9v6"></path>
                    </svg>
                    <span>Nova tag</span>
                </button>
                <div id="nav-user" class="nav-user">
                    <button id="nav-user-btn" class="nav-user-btn" type="button" aria-label="Menu do usuário">
                        <span class="nav-avatar" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                            </svg>
                        </span>
                        <span id="nav-user-name" class="nav-user-name">Conta</span>
                    </button>
                    <div id="nav-user-menu" class="nav-menu" aria-hidden="true">
                    <button id="nav-menu-settings" type="button">
                        <svg class="nav-mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 15.5A3.5 3.5 0 1 0 12 8.5a3.5 3.5 0 0 0 0 7z"></path>
                            <path d="M19.4 15a1.8 1.8 0 0 0 .35 1.95l.06.06a2.2 2.2 0 0 1-1.55 3.76 2.2 2.2 0 0 1-1.55-.64l-.06-.06A1.8 1.8 0 0 0 15 19.4a1.8 1.8 0 0 0-1 .3 1.8 1.8 0 0 0-.95 1.55V21a2.2 2.2 0 0 1-4.4 0v-.08a1.8 1.8 0 0 0-.95-1.55 1.8 1.8 0 0 0-1-.3 1.8 1.8 0 0 0-1.65.67l-.06.06a2.2 2.2 0 0 1-3.1-3.1l.06-.06A1.8 1.8 0 0 0 4.6 15a1.8 1.8 0 0 0-.3-1 1.8 1.8 0 0 0-1.55-.95H2.8a2.2 2.2 0 0 1 0-4.4h.08a1.8 1.8 0 0 0 1.55-.95 1.8 1.8 0 0 0 .3-1 1.8 1.8 0 0 0-.67-1.65l-.06-.06a2.2 2.2 0 0 1 3.1-3.1l.06.06A1.8 1.8 0 0 0 9 4.6a1.8 1.8 0 0 0 1-.3 1.8 1.8 0 0 0 .95-1.55V2.8a2.2 2.2 0 0 1 4.4 0v.08a1.8 1.8 0 0 0 .95 1.55 1.8 1.8 0 0 0 1 .3 1.8 1.8 0 0 0 1.65-.67l.06-.06a2.2 2.2 0 0 1 3.1 3.1l-.06.06A1.8 1.8 0 0 0 19.4 9c.2.32.3.68.3 1s-.1.68-.3 1a1.8 1.8 0 0 0-.35 1.95"></path>
                        </svg>
                        <span>Configurações</span>
                    </button>
                    <button id="nav-menu-sacratech" type="button">
                        <svg class="nav-mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 3h7v7"></path>
                            <path d="M10 14 21 3"></path>
                            <path d="M21 14v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"></path>
                        </svg>
                        <span>Gerenciar Sacratech iD</span>
                    </button>
                    <button id="nav-menu-logout" type="button" data-variant="danger">
                        <svg class="nav-mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="M16 17l5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>
                        <span>Sair</span>
                    </button>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="shell fade-in">
            {{ $slot }}
        </div>
    </div>

    <div id="settings-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="settings-modal-title">
            <div class="modal-head">
                <div id="settings-modal-title" class="modal-title">Configurações</div>
                <button id="settings-modal-close" class="modal-btn" type="button">Fechar</button>
            </div>
            <div class="settings-layout">
                <div class="settings-nav" aria-label="Seções">
                    <button class="settings-tab" type="button" data-settings-tab="general" data-active="true">Geral</button>
                    <button class="settings-tab" type="button" data-settings-tab="security" data-active="false">Segurança</button>
                    <button class="settings-tab" type="button" data-settings-tab="account" data-active="false">Conta</button>
                    <button class="settings-tab" type="button" data-settings-tab="about" data-active="false">Sobre</button>
                </div>
                <div class="settings-content">
                    <div id="settings-panel-general" class="settings-panel" data-active="true">
                        <div class="setting-row">
                            <div style="min-width:0;">
                                <div class="setting-title">Reduzir animações</div>
                                <div class="setting-sub">Deixa a interface mais estática e leve.</div>
                            </div>
                            <button id="pref-reduce-motion" class="toggle" type="button" data-on="false" aria-label="Reduzir animações"></button>
                        </div>
                        <div class="setting-row">
                            <div style="min-width:0;">
                                <div class="setting-title">Lista compacta</div>
                                <div class="setting-sub">Mostra mais itens na lateral com menos espaçamento.</div>
                            </div>
                            <button id="pref-compact-list" class="toggle" type="button" data-on="false" aria-label="Lista compacta"></button>
                        </div>
                    </div>

                    <div id="settings-panel-security" class="settings-panel" data-active="false">
                        <div class="modal-section">
                            <div class="modal-section-title">Pasta Oculta (PIN)</div>
                            <div class="muted" style="margin-top:6px;">Altere seu PIN de 6 dígitos usando sua senha da conta.</div>
                            <div style="height:12px;"></div>
                            <button id="settings-pin-show" class="modal-btn" type="button">Alterar PIN</button>
                            <div id="settings-pin-form" style="display:none; margin-top:12px;">
                                <label for="settings-password">Senha</label>
                                <input id="settings-password" type="password" autocomplete="current-password">
                                <div style="height:10px;"></div>
                                <label>Novo PIN</label>
                                <div class="pin-grid" id="settings-pin-grid"></div>
                                <div style="height:10px;"></div>
                                <label>Confirmar PIN</label>
                                <div class="pin-grid" id="settings-pin-confirm-grid"></div>
                                <div class="error" id="settings-error" style="display:none; margin-top:10px;"></div>
                                <div class="modal-actions">
                                    <button id="settings-save" class="modal-btn" data-variant="primary" type="button">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="settings-panel-account" class="settings-panel" data-active="false">
                        <div class="modal-section">
                            <div class="modal-section-title">Sacratech iD</div>
                            <div class="muted" style="margin-top:6px;">Gerencie sua conta Sacratech iD, segurança e acesso.</div>
                            <div class="modal-actions">
                                <button id="settings-open-sacratech" class="modal-btn" type="button">Abrir Sacratech iD</button>
                            </div>
                        </div>
                    </div>

                    <div id="settings-panel-about" class="settings-panel" data-active="false">
                        <div class="modal-section">
                            <div class="modal-section-title">Airlink Notes</div>
                            <div class="muted" style="margin-top:6px;">Bloco de notas pessoal, organizado e seguro.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="tag-create-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="tag-create-title">
            <div class="modal-head">
                <div id="tag-create-title" class="modal-title">Nova tag</div>
                <button id="tag-create-close" class="modal-btn" type="button">Fechar</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <label for="tag-create-name">Título</label>
                    <input id="tag-create-name" type="text" maxlength="40" autocomplete="off">
                    <div style="height:10px;"></div>
                    <label for="tag-create-color">Cor</label>
                    <input id="tag-create-color" type="color" value="#0071E3">
                    <div class="error" id="tag-create-error" style="display:none; margin-top:10px;"></div>
                    <div class="modal-actions">
                        <button id="tag-create-save" class="modal-btn" data-variant="primary" type="button">Criar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const navUser = document.getElementById('nav-user');
            const navBtn = document.getElementById('nav-user-btn');
            const navMenu = document.getElementById('nav-user-menu');
            const btnSettings = document.getElementById('nav-menu-settings');
            const btnSacratech = document.getElementById('nav-menu-sacratech');
            const btnLogout = document.getElementById('nav-menu-logout');
            const btnTagCreate = document.getElementById('nav-tag-create');

            const settingsModal = document.getElementById('settings-modal');
            const settingsClose = document.getElementById('settings-modal-close');
            const settingsSave = document.getElementById('settings-save');
            const settingsPassword = document.getElementById('settings-password');
            const settingsError = document.getElementById('settings-error');
            const settingsPinShow = document.getElementById('settings-pin-show');
            const settingsPinForm = document.getElementById('settings-pin-form');
            const settingsPinGrid = document.getElementById('settings-pin-grid');
            const settingsPinConfirmGrid = document.getElementById('settings-pin-confirm-grid');
            const settingsOpenSacratech = document.getElementById('settings-open-sacratech');
            const settingsTabs = Array.from(document.querySelectorAll('[data-settings-tab]'));
            const panelGeneral = document.getElementById('settings-panel-general');
            const panelSecurity = document.getElementById('settings-panel-security');
            const panelAccount = document.getElementById('settings-panel-account');
            const panelAbout = document.getElementById('settings-panel-about');
            const prefReduceMotion = document.getElementById('pref-reduce-motion');
            const prefCompactList = document.getElementById('pref-compact-list');

            const tagCreateModal = document.getElementById('tag-create-modal');
            const tagCreateClose = document.getElementById('tag-create-close');
            const tagCreateSave = document.getElementById('tag-create-save');
            const tagCreateName = document.getElementById('tag-create-name');
            const tagCreateColor = document.getElementById('tag-create-color');
            const tagCreateError = document.getElementById('tag-create-error');

            function setSettingsError(msg) {
                settingsError.textContent = msg || '';
                settingsError.style.display = msg ? 'block' : 'none';
            }

            function setTagCreateError(msg) {
                tagCreateError.textContent = msg || '';
                tagCreateError.style.display = msg ? 'block' : 'none';
            }

            function openMenu() {
                navMenu.setAttribute('aria-hidden', 'false');
                navMenu.classList.add('open');
            }

            function closeMenu() {
                navMenu.setAttribute('aria-hidden', 'true');
                navMenu.classList.remove('open');
            }

            function makePinInputs(container) {
                const inputs = [];
                container.innerHTML = '';
                for (let i = 0; i < 6; i += 1) {
                    const inp = document.createElement('input');
                    inp.type = 'password';
                    inp.inputMode = 'numeric';
                    inp.maxLength = 1;
                    inp.className = 'pin-box';
                    inp.autocomplete = 'one-time-code';
                    inp.addEventListener('input', () => {
                        inp.value = (inp.value || '').replace(/\D/g, '').slice(0, 1);
                        if (inp.value && i < 5) inputs[i + 1].focus();
                    });
                    inp.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace' && !inp.value && i > 0) inputs[i - 1].focus();
                    });
                    inputs.push(inp);
                    container.appendChild(inp);
                }
                if (inputs[0]) {
                    inputs[0].addEventListener('paste', (e) => {
                        const txt = (e.clipboardData ? e.clipboardData.getData('text') : '') || '';
                        const digits = txt.replace(/\D/g, '').slice(0, 6);
                        if (digits.length !== 6) return;
                        e.preventDefault();
                        for (let i = 0; i < 6; i += 1) inputs[i].value = digits[i] || '';
                        inputs[5].focus();
                    });
                }
                return inputs;
            }

            const settingsPinInputs = settingsPinGrid ? makePinInputs(settingsPinGrid) : [];
            const settingsPinConfirmInputs = settingsPinConfirmGrid ? makePinInputs(settingsPinConfirmGrid) : [];

            function pinValue(inputs) {
                return inputs.map((i) => (i.value || '').replace(/\D/g, '')).join('');
            }

            function clearPinInputs(inputs) {
                for (const i of inputs) i.value = '';
            }

            function setSettingsTab(tab) {
                for (const btn of settingsTabs) {
                    const isActive = btn.getAttribute('data-settings-tab') === tab;
                    btn.setAttribute('data-active', isActive ? 'true' : 'false');
                }

                const setPanel = (panel, isActive) => {
                    if (!panel) return;
                    panel.setAttribute('data-active', isActive ? 'true' : 'false');
                };

                setPanel(panelGeneral, tab === 'general');
                setPanel(panelSecurity, tab === 'security');
                setPanel(panelAccount, tab === 'account');
                setPanel(panelAbout, tab === 'about');
            }

            function getPref(key, fallback = false) {
                const v = localStorage.getItem('airlink.pref.' + key);
                if (v === null) return fallback;
                return v === '1';
            }

            function setPref(key, on) {
                localStorage.setItem('airlink.pref.' + key, on ? '1' : '0');
                window.dispatchEvent(new CustomEvent('airlink:prefs-updated'));
            }

            function setToggle(el, on) {
                if (!el) return;
                el.setAttribute('data-on', on ? 'true' : 'false');
            }

            function openSettings() {
                setSettingsError('');
                if (settingsPassword) settingsPassword.value = '';
                clearPinInputs(settingsPinInputs);
                clearPinInputs(settingsPinConfirmInputs);
                if (settingsPinForm) settingsPinForm.style.display = 'none';
                setSettingsTab('general');
                setToggle(prefReduceMotion, getPref('reduce_motion', false));
                setToggle(prefCompactList, getPref('compact_list', false));
                settingsModal.style.display = 'flex';
                settingsModal.setAttribute('aria-hidden', 'false');
            }

            function closeSettings() {
                settingsModal.style.display = 'none';
                settingsModal.setAttribute('aria-hidden', 'true');
                setSettingsError('');
            }

            function openTagModal() {
                tagCreateName.value = '';
                tagCreateColor.value = '#0071E3';
                setTagCreateError('');
                tagCreateModal.style.display = 'flex';
                tagCreateModal.setAttribute('aria-hidden', 'false');
                setTimeout(() => tagCreateName.focus(), 0);
            }

            function closeTagModal() {
                tagCreateModal.style.display = 'none';
                tagCreateModal.setAttribute('aria-hidden', 'true');
                setTagCreateError('');
            }

            async function saveTag() {
                setTagCreateError('');
                const name = (tagCreateName.value || '').trim();
                const color = (tagCreateColor.value || '').trim();
                if (!name) { setTagCreateError('Informe um título.'); return; }
                if (!/^#([0-9a-fA-F]{6})$/.test(color)) { setTagCreateError('Cor inválida.'); return; }
                tagCreateSave.disabled = true;
                try {
                    await window.Airlink.api('/tags', { method: 'POST', body: { name, color } });
                    window.dispatchEvent(new CustomEvent('airlink:tags-updated'));
                    closeTagModal();
                } catch (e) {
                    setTagCreateError(e.message || 'Falha ao criar tag.');
                } finally {
                    tagCreateSave.disabled = false;
                }
            }

            async function saveSettings() {
                setSettingsError('');
                const password = (settingsPassword.value || '').trim();
                const pin = pinValue(settingsPinInputs);
                const pinConfirm = pinValue(settingsPinConfirmInputs);

                if (!password) { setSettingsError('Informe sua senha.'); return; }
                if (pin.length !== 6) { setSettingsError('Informe 6 dígitos no PIN.'); return; }
                if (pin !== pinConfirm) { setSettingsError('Os PINs não conferem.'); return; }

                settingsSave.disabled = true;
                try {
                    await window.Airlink.api('/vault/pin/change', {
                        method: 'POST',
                        body: { password, pin, pin_confirmation: pinConfirm },
                    });
                    closeSettings();
                } catch (e) {
                    setSettingsError(e.message || 'Falha ao salvar.');
                } finally {
                    settingsSave.disabled = false;
                }
            }

            for (const btn of settingsTabs) {
                btn.addEventListener('click', () => {
                    const tab = btn.getAttribute('data-settings-tab') || 'general';
                    setSettingsTab(tab);
                });
            }

            if (settingsPinShow) settingsPinShow.addEventListener('click', () => {
                if (!settingsPinForm) return;
                settingsPinForm.style.display = settingsPinForm.style.display === 'none' ? 'block' : 'none';
                setSettingsError('');
                clearPinInputs(settingsPinInputs);
                clearPinInputs(settingsPinConfirmInputs);
                if (settingsPassword) settingsPassword.value = '';
                setTimeout(() => { if (settingsPassword) settingsPassword.focus(); }, 0);
            });

            if (settingsOpenSacratech) settingsOpenSacratech.addEventListener('click', () => {
                window.open(window.Airlink.SACRATECH_ID_URL, '_blank', 'noopener');
            });

            if (prefReduceMotion) prefReduceMotion.addEventListener('click', () => {
                const next = prefReduceMotion.getAttribute('data-on') !== 'true';
                setToggle(prefReduceMotion, next);
                setPref('reduce_motion', next);
            });

            if (prefCompactList) prefCompactList.addEventListener('click', () => {
                const next = prefCompactList.getAttribute('data-on') !== 'true';
                setToggle(prefCompactList, next);
                setPref('compact_list', next);
            });

            if (navBtn) {
                navBtn.addEventListener('click', () => {
                    if (navMenu.classList.contains('open')) closeMenu();
                    else openMenu();
                });
            }

            if (btnSettings) btnSettings.addEventListener('click', () => { closeMenu(); openSettings(); });
            if (btnSacratech) btnSacratech.addEventListener('click', () => {
                closeMenu();
                window.open(window.Airlink.SACRATECH_ID_URL, '_blank', 'noopener');
            });
            if (btnLogout) btnLogout.addEventListener('click', () => { closeMenu(); window.Airlink.logoutAndRedirect(); });
            if (btnTagCreate) btnTagCreate.addEventListener('click', openTagModal);

            if (settingsClose) settingsClose.addEventListener('click', closeSettings);
            if (settingsSave) settingsSave.addEventListener('click', saveSettings);
            if (settingsModal) settingsModal.addEventListener('click', (e) => { if (e.target === settingsModal) closeSettings(); });

            if (tagCreateClose) tagCreateClose.addEventListener('click', closeTagModal);
            if (tagCreateSave) tagCreateSave.addEventListener('click', saveTag);
            if (tagCreateModal) tagCreateModal.addEventListener('click', (e) => { if (e.target === tagCreateModal) closeTagModal(); });
            if (tagCreateName) tagCreateName.addEventListener('keydown', (e) => { if (e.key === 'Enter') saveTag(); });

            document.addEventListener('mousedown', (e) => {
                if (!navMenu || !navMenu.classList.contains('open')) return;
                if (navMenu.contains(e.target) || navBtn.contains(e.target)) return;
                if (navUser && navUser.contains(e.target)) return;
                closeMenu();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                closeMenu();
                closeSettings();
                closeTagModal();
            });
        })();
    </script>

    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-logos">
                    <img src="/brand/airlink/airlink-icon-black.png" alt="Airlink">
                    <img src="/brand/airlink/Sacratech_preto.png" alt="Sacratech Softwares">
                </div>
                <div class="footer-links">
                    <a href="https://account-id.sacratech.com" target="_blank" rel="noopener noreferrer">Conta Sacratech iD para entrar</a>
                    <a href="https://sacratech.com" target="_blank" rel="noopener noreferrer">sacratech.com</a>
                </div>
            </div>

            <div class="footer-divider"></div>

            <div>Airlink, Airlink Notes e todos os serviços Airlink são de propriedade da Sacratech Softwares.</div>
            <div style="margin-top:6px;">© {{ date('Y') }} Sacratech Softwares LTDA. Todos os direitos reservados.</div>
        </div>
    </footer>

</body>
</html>
