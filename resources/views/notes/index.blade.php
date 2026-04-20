<x-layouts.app title="Notas" navbarTitle="Notas" :navbarShowTitle="true">
    <style>
        .notes-page, .notes-page button, .notes-page input, .notes-page select, .notes-page textarea, .ctx, .ctx button { font-family: "Inter", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; }
        .container { max-width: none; padding: 0; min-height: calc(100vh - 64px); }
        .shell { max-width: none; }
        .notes-page button { box-shadow: none; transform: none; }
        .notes-page button:hover { box-shadow: none; transform: none; }
        .notes-surface { background: #ffffff; border: 0; border-radius: 0; box-shadow: none; overflow: hidden; height: calc(100vh - 64px); }
        .notes-grid { display: grid; grid-template-columns: 320px 1fr; height: 100%; }
        .notes-sidebar { background: #f5f5f7; border-right: 1px solid rgba(0, 0, 0, 0.10); display: flex; flex-direction: column; min-width: 0; }
        .notes-main { background: #ffffff; display: flex; flex-direction: column; min-width: 0; }
        .notes-pad { padding: 14px 14px; }
        .notes-divider { height: 1px; background: rgba(0, 0, 0, 0.08); margin: 10px 14px; }
        .notes-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 14px 10px; }
        .notes-title { font-weight: 750; letter-spacing: -0.02em; }
        .icon-btn { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; padding: 0; border-radius: 12px; border: 1px solid transparent; background: transparent; transition: background 140ms ease, border-color 140ms ease, color 140ms ease; }
        .icon-btn:hover { background: rgba(0, 0, 0, 0.05); border-color: rgba(0, 0, 0, 0.08); }
        .icon-btn:active { background: rgba(0, 0, 0, 0.08); }
        .icon-btn[data-variant="danger"] { color: #b42318; }
        .icon-btn[data-variant="danger"]:hover { background: rgba(180, 35, 24, 0.08); border-color: rgba(180, 35, 24, 0.14); }
        .icon { width: 18px; height: 18px; stroke-width: 1.75; }
        .subhead { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .subhead-title { font-size: 12px; font-weight: 750; letter-spacing: 0.06em; text-transform: uppercase; color: rgba(29, 29, 31, 0.55); }
        .list { overflow: auto; display: flex; flex-direction: column; gap: 6px; padding: 6px 10px 12px; }
        .list.anim { opacity: 0.98; }
        .list-item { display: flex; align-items: center; gap: 10px; padding: 10px 10px; border-radius: 14px; border: 1px solid transparent; background: rgba(255, 255, 255, 0.0); text-align: left; cursor: pointer; transition: background 140ms ease, border-color 140ms ease, transform 160ms ease, opacity 160ms ease; }
        .list-item:hover { background: rgba(255, 255, 255, 0.65); border-color: rgba(0, 0, 0, 0.06); }
        .list-item[aria-selected="true"] { background: rgba(255, 255, 255, 0.92); border-color: rgba(0, 0, 0, 0.10); }
        .list-item .icon { color: rgba(29, 29, 31, 0.62); }
        .list-item-title { font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .list-item-sub { font-size: 12px; color: rgba(29, 29, 31, 0.62); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
        .list-item-meta { font-size: 12px; color: rgba(29, 29, 31, 0.52); margin-top: 4px; }
        .list-item-col { flex: 1; min-width: 0; }
        .list-item-end { margin-left: auto; display: inline-flex; align-items: center; gap: 8px; }
        .list-item.drop-target { background: rgba(0, 113, 227, 0.14); border-color: rgba(0, 113, 227, 0.22); outline: 2px solid rgba(0, 113, 227, 0.18); }
        .list-section { padding: 10px 10px 6px; display: flex; align-items: center; gap: 10px; color: rgba(29, 29, 31, 0.55); font-size: 12px; font-weight: 780; letter-spacing: 0.06em; text-transform: uppercase; }
        .list-section:after { content: ""; height: 1px; flex: 1; background: rgba(0, 0, 0, 0.08); }
        .pin-btn { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: 1px solid transparent; background: transparent; opacity: 0; transition: opacity 120ms ease, background 140ms ease, border-color 140ms ease; }
        .pin-btn .mi { width: 16px; height: 16px; }
        .list-item:hover .pin-btn { opacity: 1; }
        .pin-btn:hover { background: rgba(0, 0, 0, 0.05); border-color: rgba(0, 0, 0, 0.08); }
        .pin-btn[data-pinned="true"] { opacity: 1; background: rgba(0, 0, 0, 0.05); border-color: rgba(0, 0, 0, 0.08); }
        .more { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: 1px solid transparent; background: transparent; opacity: 0; transition: opacity 120ms ease, background 140ms ease, border-color 140ms ease; }
        .list-item:hover .more { opacity: 1; }
        .more:hover { background: rgba(0, 0, 0, 0.05); border-color: rgba(0, 0, 0, 0.08); }
        .more:active { background: rgba(0, 0, 0, 0.08); }
        .folder-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.03em; padding: 2px 8px; border-radius: 999px; border: 1px solid rgba(0, 0, 0, 0.10); background: rgba(255, 255, 255, 0.65); color: rgba(29, 29, 31, 0.70); margin-right: 6px; }
        .tag-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 750; letter-spacing: 0.02em; padding: 2px 8px; border-radius: 999px; border: 1px solid rgba(0, 0, 0, 0.10); background: rgba(255, 255, 255, 0.65); color: rgba(29, 29, 31, 0.72); margin-right: 6px; }
        .tag { font-size: 12px; padding: 7px 10px; border-radius: 999px; border: 1px solid rgba(0, 0, 0, 0.10); background: rgba(255, 255, 255, 0.65); color: rgba(29, 29, 31, 0.72); }
        .toolbar { position: sticky; top: 0; z-index: 10; display: flex; gap: 8px; flex-wrap: wrap; padding: 10px 14px; border-top: 1px solid rgba(0, 0, 0, 0.06); border-bottom: 1px solid rgba(0, 0, 0, 0.08); background: rgba(255, 255, 255, 0.86); backdrop-filter: blur(16px); }
        .tool { display: inline-flex; align-items: center; gap: 8px; height: 38px; padding: 0 12px; border-radius: 12px; border: 1px solid rgba(0, 0, 0, 0.10); background: rgba(255, 255, 255, 0.72); font-weight: 650; transition: background 140ms ease, border-color 140ms ease; }
        .tool .icon { width: 17px; height: 17px; }
        .tool:hover { background: rgba(0, 0, 0, 0.04); border-color: rgba(0, 0, 0, 0.16); }
        .tool[data-active="true"] { background: rgba(0, 0, 0, 0.06); border-color: rgba(0, 0, 0, 0.18); }
        .tool select { border: 0; background: transparent; outline: none; font-weight: 650; color: inherit; }
        .tool:disabled { opacity: 0.55; }
        .editor-wrap { position: relative; flex: 1; overflow: auto; padding: 18px 22px; }
        .editor { max-width: none; margin: 0; }
        .editor-title { font-size: 28px; font-weight: 780; letter-spacing: -0.03em; line-height: 1.15; outline: none; padding: 4px 2px; }
        .editor-body { font-size: 15px; line-height: 1.6; outline: none; padding: 10px 2px 40px; }
        .editor-body p, .editor-body div, .editor-body blockquote, .editor-body pre, .editor-body ul, .editor-body ol { margin: 0 0 12px; }
        .editor-body ul, .editor-body ol { padding-left: 22px; }
        .editor-body blockquote { border-left: 3px solid rgba(0, 0, 0, 0.14); padding-left: 12px; color: rgba(29, 29, 31, 0.78); }
        .editor-body pre { background: rgba(0, 0, 0, 0.04); padding: 12px 12px; border-radius: 14px; overflow: auto; }
        .editor-body code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 0.95em; }
        .editor-body mark { background: rgba(255, 214, 10, 0.45); padding: 0 2px; border-radius: 4px; }
        .editor [contenteditable="true"][data-placeholder]:empty:before { content: attr(data-placeholder); color: rgba(29, 29, 31, 0.42); }
        .editor-body span[data-font="serif"] { font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif; }
        .editor-body span[data-font="mono"] { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .empty { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 22px; pointer-events: none; }
        .empty-card { max-width: 520px; width: 100%; background: rgba(255, 255, 255, 0.76); border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 22px; padding: 18px 18px; backdrop-filter: blur(14px); }
        .empty-title { font-weight: 750; letter-spacing: -0.02em; }
        .empty-sub { margin-top: 6px; color: rgba(29, 29, 31, 0.68); font-size: 13px; line-height: 1.45; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.28); display: none; align-items: center; justify-content: center; z-index: 60; }
        .modal { width: 440px; max-width: calc(100vw - 24px); background: rgba(255, 255, 255, 0.86); border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 22px; padding: 16px 16px; backdrop-filter: blur(18px); }
        .modal-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .modal-title { font-weight: 780; letter-spacing: -0.02em; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px; }
        .modal-btn { height: 40px; padding: 0 14px; border-radius: 12px; border: 1px solid rgba(0, 0, 0, 0.12); background: rgba(255, 255, 255, 0.70); font-weight: 700; letter-spacing: -0.01em; transition: background 140ms ease, border-color 140ms ease, transform 120ms ease; }
        .modal-btn:hover { background: rgba(255, 255, 255, 0.92); border-color: rgba(0, 0, 0, 0.16); }
        .modal-btn:active { transform: translateY(1px); }
        .modal-btn[data-variant="primary"] { background: rgba(0, 113, 227, 0.95); border-color: rgba(0, 113, 227, 0.90); color: #fff; }
        .modal-btn[data-variant="primary"]:hover { background: rgba(0, 113, 227, 1); border-color: rgba(0, 113, 227, 1); }
        .modal-btn[data-variant="danger"] { color: #b42318; border-color: rgba(180, 35, 24, 0.22); }
        .modal-btn[data-variant="danger"]:hover { background: rgba(180, 35, 24, 0.08); border-color: rgba(180, 35, 24, 0.26); }
        .modal input[type="color"] { -webkit-appearance: none; appearance: none; width: 100%; height: 44px; padding: 0; border-radius: 14px; border: 1px solid rgba(0,0,0,0.14); background: rgba(255,255,255,0.95); overflow: hidden; }
        .modal input[type="color"]::-webkit-color-swatch-wrapper { padding: 6px; }
        .modal input[type="color"]::-webkit-color-swatch { border: 0; border-radius: 10px; }
        .tag-list { display: flex; flex-direction: column; gap: 6px; margin-top: 8px; max-height: 260px; overflow: auto; padding: 6px; border: 1px solid rgba(0, 0, 0, 0.10); border-radius: 18px; background: rgba(245, 245, 247, 0.55); }
        .tag-option { width: 100%; display: flex; align-items: center; gap: 10px; text-align: left; border: 1px solid transparent; background: rgba(255, 255, 255, 0.75); padding: 10px 10px; border-radius: 14px; font-weight: 700; letter-spacing: -0.01em; transition: background 140ms ease, border-color 140ms ease; }
        .tag-option:hover { background: rgba(255, 255, 255, 0.92); border-color: rgba(0, 0, 0, 0.08); }
        .tag-option[aria-selected="true"] { border-color: rgba(0, 113, 227, 0.35); background: rgba(0, 113, 227, 0.10); }
        .tag-dot { width: 12px; height: 12px; border-radius: 999px; border: 1px solid rgba(0,0,0,0.12); flex: 0 0 auto; }
        .tag-option .tag-pill { margin-right: 0; margin-left: auto; }
        .ctx { position: fixed; z-index: 70; min-width: 240px; background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(0, 0, 0, 0.10); border-radius: 18px; padding: 8px; backdrop-filter: blur(18px); display: none; }
        .ctx button { width: 100%; display: flex; align-items: center; gap: 10px; text-align: left; border: 1px solid transparent; background: transparent; padding: 10px 10px; border-radius: 14px; font-weight: 700; letter-spacing: -0.01em; }
        .ctx .mi { width: 16px; height: 16px; color: rgba(29, 29, 31, 0.64); flex: 0 0 auto; }
        .ctx button:hover { background: rgba(0, 0, 0, 0.05); border-color: rgba(0, 0, 0, 0.08); }
        .ctx button[data-variant="danger"] { color: #b42318; }
        .ctx button[data-variant="danger"] .mi { color: #b42318; }
        .ctx button[data-variant="danger"]:hover { background: rgba(180, 35, 24, 0.08); border-color: rgba(180, 35, 24, 0.14); }
        @media (max-width: 980px) { .notes-grid { grid-template-columns: 1fr; } .notes-sidebar { border-right: 0; border-bottom: 1px solid rgba(0, 0, 0, 0.08); } .notes-surface { height: auto; } }
    </style>

    <div class="notes-page notes-surface">
        <div class="notes-grid">
            <aside class="notes-sidebar">
                <div class="notes-top">
                    <div class="notes-title">Notas</div>
                    <div style="display:flex; gap:8px;">
                        <button id="btn-new-note" class="icon-btn" type="button" title="Nova nota" aria-label="Nova nota">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14"></path><path d="M5 12h14"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="notes-pad">
                    <div class="subhead">
                        <div class="subhead-title">Pastas</div>
                        <div style="display:flex; gap:8px;">
                            <button id="btn-new-folder" class="icon-btn" type="button" title="Nova pasta" aria-label="Nova pasta">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 10v8"></path><path d="M8 14h8"></path>
                                    <path d="M3 6a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6z"></path>
                                </svg>
                            </button>
                            <button id="btn-rename-folder" class="icon-btn" type="button" title="Renomear pasta" aria-label="Renomear pasta" disabled>
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                </svg>
                            </button>
                            <button id="btn-delete-folder" class="icon-btn" data-variant="danger" type="button" title="Excluir pasta" aria-label="Excluir pasta" disabled>
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6z"></path>
                                    <path d="M10 10l4 4"></path><path d="M14 10l-4 4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="folder-list" class="list" style="padding-top:0;"></div>

                <div class="notes-divider"></div>

                <div class="notes-pad" style="padding-top:0;">
                    <input id="search" type="text" placeholder="Buscar notas..." autocomplete="off">
                </div>
                <div id="note-list" class="list" style="padding-top:0; flex:1;"></div>
            </aside>

            <main class="notes-main">
                <div class="notes-top" style="border-bottom: 1px solid rgba(0, 0, 0, 0.06);">
                    <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                        <div class="tag" id="meta">—</div>
                        <div class="tag" id="tag-indicator" style="display:none;"></div>
                        <div class="muted" id="status" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">—</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <button id="btn-delete-note" class="icon-btn" data-variant="danger" type="button" title="Excluir nota" aria-label="Excluir nota" disabled>
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M8 6V4h8v2"></path>
                                <path d="M19 6l-1 14H6L5 6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="toolbar">
                    <button class="tool" type="button" data-cmd="bold" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 4h8a4 4 0 0 1 0 8H6z"></path><path d="M6 12h9a4 4 0 0 1 0 8H6z"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-cmd="italic" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 4h-9"></path><path d="M14 20H5"></path><path d="M15 4 9 20"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-cmd="underline" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 4v6a6 6 0 0 0 12 0V4"></path><path d="M4 20h16"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-wrap="mark" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 11 6 6"></path><path d="m10 12 2-2 4 4-2 2"></path><path d="M12 3l9 9-3 3-9-9z"></path><path d="M2 22l4-1-3-3z"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-block="blockquote" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21c3 0 7-1 7-8V5H3v6h4"></path>
                            <path d="M14 21c3 0 7-1 7-8V5h-7v6h4"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-cmd="insertUnorderedList" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 6h13"></path><path d="M8 12h13"></path><path d="M8 18h13"></path><path d="M3 6h.01"></path><path d="M3 12h.01"></path><path d="M3 18h.01"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-cmd="insertOrderedList" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 6h11"></path><path d="M10 12h11"></path><path d="M10 18h11"></path>
                            <path d="M4 6h1v4"></path><path d="M4 10h2"></path>
                            <path d="M4 14h1"></path><path d="M5 14v4"></path><path d="M4 18h2"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-block="pre" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m16 18 6-6-6-6"></path><path d="m8 6-6 6 6 6"></path>
                        </svg>
                    </button>
                    <button class="tool" type="button" data-cmd="removeFormat" disabled>
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v17"></path><path d="M5 7h14"></path><path d="M7 21h10"></path>
                        </svg>
                    </button>
                    <div class="tool" style="padding-right:10px;">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19V5"></path><path d="M4 5h16"></path><path d="M10 5v14"></path>
                        </svg>
                        <select id="font-select" disabled>
                            <option value="">Fonte</option>
                            <option value="serif">Serif</option>
                            <option value="mono">Mono</option>
                        </select>
                    </div>
                </div>

                <div class="editor-wrap" id="editor-wrap">
                    <div class="editor">
                        <div id="editor-title" class="editor-title" contenteditable="false" data-placeholder="Título"></div>
                        <div id="editor-body" class="editor-body" contenteditable="false" data-placeholder="Clique e comece a escrever…"></div>
                    </div>

                    <div class="empty" id="empty">
                        <div class="empty-card">
                            <div class="empty-title">Suas notas, do seu jeito.</div>
                            <div class="empty-sub">Crie pastas, escreva com formatação rica e deixe o primeiro texto virar o título automaticamente. Clique aqui ou use “+” para começar.</div>
                            <div class="error" id="err" style="display:none; margin-top:10px;"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div id="folder-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="folder-modal-title">
            <div class="modal-head">
                <div id="folder-modal-title" class="modal-title">Nova pasta</div>
                <button id="folder-modal-close" class="icon-btn" type="button" aria-label="Fechar">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div style="height:12px;"></div>
            <label for="folder-modal-input">Nome</label>
            <input id="folder-modal-input" type="text" autocomplete="off" maxlength="120">
            <div class="error" id="folder-modal-error" style="display:none; margin-top:8px;"></div>
            <div class="modal-actions">
                <button id="folder-modal-cancel" class="modal-btn" type="button">Cancelar</button>
                <button id="folder-modal-save" class="modal-btn" data-variant="primary" type="button">Salvar</button>
            </div>
        </div>
    </div>

    <div id="vault-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="vault-modal-title">
            <div class="modal-head">
                <div id="vault-modal-title" class="modal-title">Pasta Oculta</div>
                <button id="vault-modal-close" class="icon-btn" type="button" aria-label="Fechar">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div style="height:10px;"></div>

            <div id="vault-mode-create">
                <div class="muted">Crie um PIN de 6 dígitos para acessar a pasta Oculta.</div>
                <div style="height:12px;"></div>
                <label for="vault-pin">PIN</label>
                <input id="vault-pin" type="password" inputmode="numeric" autocomplete="one-time-code" maxlength="6">
                <div style="height:10px;"></div>
                <label for="vault-pin-confirm">Confirmar PIN</label>
                <input id="vault-pin-confirm" type="password" inputmode="numeric" maxlength="6">
            </div>

            <div id="vault-mode-enter" style="display:none;">
                <div class="muted">Digite seu PIN de 6 dígitos para continuar.</div>
                <div style="height:12px;"></div>
                <label for="vault-pin-enter">PIN</label>
                <input id="vault-pin-enter" type="password" inputmode="numeric" autocomplete="one-time-code" maxlength="6">
            </div>

            <div class="error" id="vault-modal-error" style="display:none; margin-top:10px;"></div>
            <div class="modal-actions">
                <button id="vault-modal-cancel" class="modal-btn" type="button">Cancelar</button>
                <button id="vault-modal-save" class="modal-btn" data-variant="primary" type="button">Continuar</button>
            </div>
        </div>
    </div>

    <div id="folder-color-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="folder-color-title">
            <div class="modal-head">
                <div id="folder-color-title" class="modal-title">Cor da pasta</div>
                <button id="folder-color-close" class="icon-btn" type="button" aria-label="Fechar">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div style="height:12px;"></div>
            <label for="folder-color-input">Cor</label>
            <input id="folder-color-input" type="color" value="#0071E3">
            <div class="error" id="folder-color-error" style="display:none; margin-top:8px;"></div>
            <div class="modal-actions">
                <button id="folder-color-cancel" class="modal-btn" type="button">Cancelar</button>
                <button id="folder-color-save" class="modal-btn" data-variant="primary" type="button">Salvar</button>
            </div>
        </div>
    </div>

    <div id="tag-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="tag-modal-title">
            <div class="modal-head">
                <div id="tag-modal-title" class="modal-title">Tag</div>
                <button id="tag-modal-close" class="icon-btn" type="button" aria-label="Fechar">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div style="height:12px;"></div>
            <label>Escolha uma tag</label>
            <div id="tag-list" class="tag-list" role="listbox" aria-label="Tags"></div>
            <div class="error" id="tag-modal-error" style="display:none; margin-top:8px;"></div>
            <div class="modal-actions">
                <button id="tag-modal-cancel" class="modal-btn" type="button">Cancelar</button>
                <button id="tag-modal-save" class="modal-btn" data-variant="primary" type="button">Aplicar</button>
            </div>
        </div>
    </div>

    <div id="onboarding" class="modal-overlay" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="onboarding-title">
            <div class="modal-head">
                <div id="onboarding-title" class="modal-title">Bem-vindo ao Airlink Notes</div>
                <button id="onboarding-close" class="icon-btn" type="button" aria-label="Fechar">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path><path d="M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="onboarding-step-1">
                <div style="height:10px;"></div>
                <div class="muted">Quatro pontos rápidos para você começar.</div>
                <div style="height:12px;"></div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div class="modal-section"><div class="modal-section-title">Notas rápidas</div><div class="muted" style="margin-top:4px;">Escreva instantaneamente e deixe o autosave cuidar do resto.</div></div>
                    <div class="modal-section"><div class="modal-section-title">Organização</div><div class="muted" style="margin-top:4px;">Pastas, cores e tags para manter tudo no lugar.</div></div>
                    <div class="modal-section"><div class="modal-section-title">Multidispositivo</div><div class="muted" style="margin-top:4px;">Sua conta e suas notas sempre acessíveis.</div></div>
                    <div class="modal-section"><div class="modal-section-title">Segurança</div><div class="muted" style="margin-top:4px;">Proteção é prioridade, incluindo opções como Pasta Oculta.</div></div>
                </div>
                <div class="modal-actions">
                    <button id="onboarding-next" class="modal-btn" data-variant="primary" type="button">Continuar</button>
                </div>
            </div>
            <div id="onboarding-step-2" style="display:none;">
                <div style="height:10px;"></div>
                <div class="muted">Personalize seu espaço para se organizar melhor.</div>
                <div style="height:12px;"></div>
                <div class="modal-section">
                    <div class="modal-section-title">Tags e cores</div>
                    <div class="muted" style="margin-top:6px;">Crie tags no navbar, escolha cores e destaque suas notas e pastas na lateral.</div>
                </div>
                <div class="modal-actions">
                    <button id="onboarding-done" class="modal-btn" data-variant="primary" type="button">Começar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="ctx" class="ctx" aria-hidden="true"></div>

    <script>
        const folderListEl = document.getElementById('folder-list');
        const noteListEl = document.getElementById('note-list');
        const searchEl = document.getElementById('search');
        const metaEl = document.getElementById('meta');
        const tagIndicatorEl = document.getElementById('tag-indicator');
        const statusEl = document.getElementById('status');
        const errEl = document.getElementById('err');
        const emptyEl = document.getElementById('empty');
        const editorWrapEl = document.getElementById('editor-wrap');
        const titleEl = document.getElementById('editor-title');
        const bodyEl = document.getElementById('editor-body');

        const btnNewNote = document.getElementById('btn-new-note');
        const btnNewFolder = document.getElementById('btn-new-folder');
        const btnRenameFolder = document.getElementById('btn-rename-folder');
        const btnDeleteFolder = document.getElementById('btn-delete-folder');
        const btnDeleteNote = document.getElementById('btn-delete-note');

        const folderModalEl = document.getElementById('folder-modal');
        const folderModalTitleEl = document.getElementById('folder-modal-title');
        const folderModalCloseEl = document.getElementById('folder-modal-close');
        const folderModalCancelEl = document.getElementById('folder-modal-cancel');
        const folderModalSaveEl = document.getElementById('folder-modal-save');
        const folderModalInputEl = document.getElementById('folder-modal-input');
        const folderModalErrorEl = document.getElementById('folder-modal-error');

        const vaultModalEl = document.getElementById('vault-modal');
        const vaultModalTitleEl = document.getElementById('vault-modal-title');
        const vaultModalCloseEl = document.getElementById('vault-modal-close');
        const vaultModalCancelEl = document.getElementById('vault-modal-cancel');
        const vaultModalSaveEl = document.getElementById('vault-modal-save');
        const vaultModeCreateEl = document.getElementById('vault-mode-create');
        const vaultModeEnterEl = document.getElementById('vault-mode-enter');
        const vaultPinEl = document.getElementById('vault-pin');
        const vaultPinConfirmEl = document.getElementById('vault-pin-confirm');
        const vaultPinEnterEl = document.getElementById('vault-pin-enter');
        const vaultModalErrorEl = document.getElementById('vault-modal-error');

        const folderColorModalEl = document.getElementById('folder-color-modal');
        const folderColorCloseEl = document.getElementById('folder-color-close');
        const folderColorCancelEl = document.getElementById('folder-color-cancel');
        const folderColorSaveEl = document.getElementById('folder-color-save');
        const folderColorInputEl = document.getElementById('folder-color-input');
        const folderColorErrorEl = document.getElementById('folder-color-error');

        const tagModalEl = document.getElementById('tag-modal');
        const tagModalCloseEl = document.getElementById('tag-modal-close');
        const tagModalCancelEl = document.getElementById('tag-modal-cancel');
        const tagModalSaveEl = document.getElementById('tag-modal-save');
        const tagListEl = document.getElementById('tag-list');
        const tagModalErrorEl = document.getElementById('tag-modal-error');

        const onboardingEl = document.getElementById('onboarding');
        const onboardingCloseEl = document.getElementById('onboarding-close');
        const onboardingStep1El = document.getElementById('onboarding-step-1');
        const onboardingStep2El = document.getElementById('onboarding-step-2');
        const onboardingNextEl = document.getElementById('onboarding-next');
        const onboardingDoneEl = document.getElementById('onboarding-done');

        const ctxEl = document.getElementById('ctx');

        const toolbarEl = document.querySelector('.toolbar');
        const fontSelectEl = document.getElementById('font-select');

        let folders = [];
        const folderNameById = new Map();
        const folderColorById = new Map();
        let notes = [];
        const notesById = new Map();
        let selectedFolderId = null;
        let selectedNoteId = null;
        let dropTargetEl = null;

        let tags = [];
        const tagById = new Map();
        let selectedTagForModalNoteId = null;
        let selectedTagIdForModal = null;

        let draggingNoteId = null;
        let orderDirty = false;
        let reorderRaf = 0;

        let autosaveTimer = null;
        let createTimer = null;
        let lastSavedVersion = null;
        let creatingNotePromise = null;
        const CREATE_NOTE_AFTER_MS = 200;
        const AUTOSAVE_AFTER_IDLE_MS = 3000;
        let saveInFlight = false;
        let pendingSave = false;
        let lastInputAt = 0;
        let lastSavedContent = null;

        let folderModalMode = 'create';
        let folderModalFolderId = null;

        let view = 'notes';
        let hiddenNotes = [];
        let selectedHiddenId = null;

        let vaultHasPin = null;
        let vaultPin = null;
        let vaultPinSetAt = 0;
        const VAULT_PIN_TTL_MS = 10 * 60 * 1000;
        let vaultModalMode = 'enter';
        let vaultModalResolve = null;

        function setError(msg) {
            errEl.textContent = msg || '';
            errEl.style.display = msg ? 'block' : 'none';
        }

        function setStatus(msg) {
            statusEl.textContent = msg || '—';
        }

        function debounce(fn, ms) {
            if (autosaveTimer) clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(fn, ms);
        }

        function formatDate(iso) {
            if (!iso) return '—';
            try { return new Date(iso).toLocaleString('pt-BR'); } catch (_) { return iso; }
        }

        function escapeHtml(s) {
            return String(s)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function stripHtml(html) {
            const div = document.createElement('div');
            div.innerHTML = html || '';
            const text = (div.textContent || div.innerText || '').replace(/\s+/g, ' ').trim();
            return text;
        }

        function animateListUpdate() {
            noteListEl.classList.add('anim');
            setTimeout(() => noteListEl.classList.remove('anim'), 180);
        }

        function setDropTarget(el) {
            if (dropTargetEl && dropTargetEl !== el) {
                dropTargetEl.classList.remove('drop-target');
            }
            dropTargetEl = el;
            if (dropTargetEl) {
                dropTargetEl.classList.add('drop-target');
            }
        }

        function clearDropTarget(el = null) {
            if (!dropTargetEl) return;
            if (el !== null && dropTargetEl !== el) return;
            dropTargetEl.classList.remove('drop-target');
            dropTargetEl = null;
        }

        function setFolderColorError(msg) {
            folderColorErrorEl.textContent = msg || '';
            folderColorErrorEl.style.display = msg ? 'block' : 'none';
        }

        let folderColorFolderId = null;

        function openFolderColorModal(folder) {
            folderColorFolderId = folder ? folder.id : null;
            folderColorInputEl.value = (folder && folder.color) ? String(folder.color) : '#0071E3';
            setFolderColorError('');
            folderColorModalEl.style.display = 'flex';
            folderColorModalEl.setAttribute('aria-hidden', 'false');
        }

        function closeFolderColorModal() {
            folderColorModalEl.style.display = 'none';
            folderColorModalEl.setAttribute('aria-hidden', 'true');
            setFolderColorError('');
            folderColorFolderId = null;
        }

        async function submitFolderColorModal() {
            setFolderColorError('');
            const folderId = folderColorFolderId;
            if (!folderId) return;
            const color = (folderColorInputEl.value || '').trim();
            if (!/^#([0-9a-fA-F]{6})$/.test(color)) {
                setFolderColorError('Cor inválida.');
                return;
            }
            folderColorSaveEl.disabled = true;
            try {
                const updated = await window.Airlink.api('/folders/' + folderId, { method: 'PUT', body: { color } });
                const idx = folders.findIndex(x => x.id === folderId);
                if (idx >= 0) folders[idx] = updated;
                folderNameById.set(folderId, updated.name || '');
                folderColorById.set(folderId, updated.color || null);
                renderFolders();
                renderNotes();
                closeFolderColorModal();
            } catch (e) {
                setFolderColorError(e.message || 'Falha ao salvar.');
            } finally {
                folderColorSaveEl.disabled = false;
            }
        }

        function setTagModalError(msg) {
            tagModalErrorEl.textContent = msg || '';
            tagModalErrorEl.style.display = msg ? 'block' : 'none';
        }

        function openTagModal(noteId) {
            selectedTagForModalNoteId = noteId;
            setTagModalError('');
            const n = notesById.get(noteId);
            selectedTagIdForModal = n && n.tag_id ? n.tag_id : null;
            renderTagList();
            tagModalEl.style.display = 'flex';
            tagModalEl.setAttribute('aria-hidden', 'false');
        }

        function closeTagModal() {
            tagModalEl.style.display = 'none';
            tagModalEl.setAttribute('aria-hidden', 'true');
            setTagModalError('');
            selectedTagForModalNoteId = null;
            selectedTagIdForModal = null;
        }

        async function submitTagModal() {
            setTagModalError('');
            const noteId = selectedTagForModalNoteId;
            if (!noteId) return;
            const tagId = selectedTagIdForModal;
            tagModalSaveEl.disabled = true;
            try {
                const updated = await window.Airlink.api('/notes/' + noteId, { method: 'PUT', body: { tag_id: tagId } });
                notesById.set(updated.id, updated);
                const idx = notes.findIndex(x => x.id === updated.id);
                if (idx >= 0) notes[idx] = updated;
                renderNotes();
                renderEditor();
                closeTagModal();
            } catch (e) {
                setTagModalError(e.message || 'Falha ao aplicar.');
            } finally {
                tagModalSaveEl.disabled = false;
            }
        }

        function getSelectedFolder() {
            return folders.find(f => f.id === selectedFolderId) || null;
        }

        function getSelectedNote() {
            if (selectedNoteId === null) return null;
            return notesById.get(selectedNoteId) || null;
        }

        function getSelectedHidden() {
            return hiddenNotes.find(n => n.id === selectedHiddenId) || null;
        }

        function setFolderModalError(msg) {
            folderModalErrorEl.textContent = msg || '';
            folderModalErrorEl.style.display = msg ? 'block' : 'none';
        }

        function openFolderModal({ mode, initialName = '' }) {
            folderModalMode = mode;
            folderModalFolderId = mode === 'rename' ? selectedFolderId : null;
            folderModalTitleEl.textContent = mode === 'rename' ? 'Renomear pasta' : 'Nova pasta';
            setFolderModalError('');
            folderModalInputEl.value = initialName;
            folderModalEl.style.display = 'flex';
            folderModalEl.setAttribute('aria-hidden', 'false');
            setTimeout(() => folderModalInputEl.focus(), 0);
        }

        function closeFolderModal() {
            folderModalEl.style.display = 'none';
            folderModalEl.setAttribute('aria-hidden', 'true');
            folderModalInputEl.value = '';
            setFolderModalError('');
        }

        async function submitFolderModal() {
            const name = (folderModalInputEl.value || '').trim();
            if (!name) {
                setFolderModalError('Informe um nome.');
                return;
            }

            folderModalSaveEl.disabled = true;
            folderModalCancelEl.disabled = true;
            folderModalCloseEl.disabled = true;

            try {
                if (folderModalMode === 'rename') {
                    const folderId = folderModalFolderId;
                    if (!folderId) {
                        closeFolderModal();
                        return;
                    }
                    const updated = await window.Airlink.api('/folders/' + folderId, { method: 'PUT', body: { name } });
                    const idx = folders.findIndex(x => x.id === folderId);
                    if (idx >= 0) folders[idx] = updated;
                    renderFolders();
                    closeFolderModal();
                    return;
                }

                const folder = await window.Airlink.api('/folders', { method: 'POST', body: { name } });
                folders.unshift(folder);
                closeFolderModal();
                await selectFolder(folder.id);
            } catch (e) {
                setFolderModalError(e.message || 'Falha ao salvar.');
            } finally {
                folderModalSaveEl.disabled = false;
                folderModalCancelEl.disabled = false;
                folderModalCloseEl.disabled = false;
            }
        }

        function setVaultModalError(msg) {
            vaultModalErrorEl.textContent = msg || '';
            vaultModalErrorEl.style.display = msg ? 'block' : 'none';
        }

        function getVaultPin() {
            if (!vaultPin) return null;
            if ((Date.now() - vaultPinSetAt) > VAULT_PIN_TTL_MS) {
                vaultPin = null;
                vaultPinSetAt = 0;
                return null;
            }
            return vaultPin;
        }

        function openVaultModal(mode) {
            vaultModalMode = mode;
            setVaultModalError('');

            vaultModeCreateEl.style.display = mode === 'create' ? 'block' : 'none';
            vaultModeEnterEl.style.display = mode === 'enter' ? 'block' : 'none';
            vaultModalTitleEl.textContent = mode === 'create' ? 'Criar PIN' : 'Digite o PIN';

            vaultPinEl.value = '';
            vaultPinConfirmEl.value = '';
            vaultPinEnterEl.value = '';

            vaultModalEl.style.display = 'flex';
            vaultModalEl.setAttribute('aria-hidden', 'false');

            setTimeout(() => {
                if (mode === 'create') vaultPinEl.focus();
                else vaultPinEnterEl.focus();
            }, 0);

            return new Promise((resolve) => {
                vaultModalResolve = resolve;
            });
        }

        function closeVaultModal(result = null) {
            vaultModalEl.style.display = 'none';
            vaultModalEl.setAttribute('aria-hidden', 'true');
            setVaultModalError('');
            vaultPinEl.value = '';
            vaultPinConfirmEl.value = '';
            vaultPinEnterEl.value = '';
            const resolve = vaultModalResolve;
            vaultModalResolve = null;
            if (resolve) resolve(result);
        }

        async function requireVaultPin({ allowCreate } = { allowCreate: true }) {
            const cached = getVaultPin();
            if (cached) return cached;

            if (vaultHasPin === null) {
                try {
                    const st = await window.Airlink.api('/vault');
                    vaultHasPin = !!(st && st.has_pin);
                } catch (_) {
                    vaultHasPin = false;
                }
            }

            if (!vaultHasPin) {
                if (!allowCreate) return null;
                const pin = await openVaultModal('create');
                return pin;
            }

            const pin = await openVaultModal('enter');
            return pin;
        }

        async function submitVaultModal() {
            setVaultModalError('');
            vaultModalSaveEl.disabled = true;
            vaultModalCancelEl.disabled = true;
            vaultModalCloseEl.disabled = true;

            try {
                if (vaultModalMode === 'create') {
                    const pin = (vaultPinEl.value || '').replace(/\D/g, '');
                    const confirmPin = (vaultPinConfirmEl.value || '').replace(/\D/g, '');
                    if (pin.length !== 6) {
                        setVaultModalError('Informe 6 dígitos.');
                        return;
                    }
                    if (pin !== confirmPin) {
                        setVaultModalError('Os PINs não conferem.');
                        return;
                    }

                    await window.Airlink.api('/vault/pin', { method: 'POST', body: { pin, pin_confirmation: confirmPin } });
                    vaultHasPin = true;
                    vaultPin = pin;
                    vaultPinSetAt = Date.now();
                    closeVaultModal(pin);
                    return;
                }

                const pin = (vaultPinEnterEl.value || '').replace(/\D/g, '');
                if (pin.length !== 6) {
                    setVaultModalError('Informe 6 dígitos.');
                    return;
                }

                await window.Airlink.api('/vault/notes', { method: 'POST', body: { pin } });
                vaultPin = pin;
                vaultPinSetAt = Date.now();
                closeVaultModal(pin);
            } catch (e) {
                setVaultModalError(e.message || 'Falha ao validar o PIN.');
            } finally {
                vaultModalSaveEl.disabled = false;
                vaultModalCancelEl.disabled = false;
                vaultModalCloseEl.disabled = false;
            }
        }

        function closeCtx() {
            ctxEl.style.display = 'none';
            ctxEl.setAttribute('aria-hidden', 'true');
            ctxEl.innerHTML = '';
        }

        function openCtx(items, x, y) {
            ctxEl.innerHTML = '';

            for (const item of items) {
                const btn = document.createElement('button');
                btn.type = 'button';
                const icon = item.icon ? item.icon : '';
                btn.innerHTML = `${icon}<span>${escapeHtml(item.label)}</span>`;
                if (item.variant) btn.setAttribute('data-variant', item.variant);
                btn.addEventListener('click', async () => {
                    closeCtx();
                    await item.onClick();
                });
                ctxEl.appendChild(btn);
            }

            ctxEl.style.display = 'block';
            ctxEl.setAttribute('aria-hidden', 'false');

            const rect = ctxEl.getBoundingClientRect();
            const maxLeft = window.innerWidth - rect.width - 10;
            const maxTop = window.innerHeight - rect.height - 10;
            const left = Math.max(10, Math.min(x, maxLeft));
            const top = Math.max(10, Math.min(y, maxTop));

            ctxEl.style.left = left + 'px';
            ctxEl.style.top = top + 'px';
        }

        function setEditorEnabled(enabled) {
            titleEl.setAttribute('contenteditable', enabled ? 'true' : 'false');
            bodyEl.setAttribute('contenteditable', enabled ? 'true' : 'false');
            btnDeleteNote.disabled = !enabled;
            fontSelectEl.disabled = !enabled;
            for (const btn of toolbarEl.querySelectorAll('button.tool')) btn.disabled = !enabled;
        }

        function parseContent(html) {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html || '';
            const firstH1 = wrapper.querySelector('h1');
            const title = firstH1 ? (firstH1.textContent || '').trim() : '';
            if (firstH1) firstH1.remove();
            return { title, body: wrapper.innerHTML.trim() };
        }

        function buildContentHtml() {
            const t = (titleEl.textContent || '').replace(/\s+/g, ' ').trim();
            const bodyHtml = (bodyEl.innerHTML || '').trim();
            const titleHtml = t ? `<h1>${escapeHtml(t)}</h1>` : '';
            return (titleHtml + bodyHtml).trim();
        }

        function tagPillHtml(tagId) {
            if (!tagId) return '';
            const tag = tagById.get(tagId);
            if (!tag) return '';
            const color = tag.color ? String(tag.color) : '#9AA0A6';
            const bg = color + '22';
            return `<span class="tag-pill" style="background:${bg}; border-color:${color}33; color:${color};">${escapeHtml(tag.name || '')}</span>`;
        }

        function reorderNotesLocal(fromId, toId) {
            if (view !== 'notes') return;
            if (fromId === toId) return;
            const fromIdx = notes.findIndex(n => n.id === fromId);
            const toIdx = notes.findIndex(n => n.id === toId);
            if (fromIdx < 0 || toIdx < 0) return;
            const [moved] = notes.splice(fromIdx, 1);
            notes.splice(toIdx, 0, moved);
            orderDirty = true;
        }

        function scheduleNotesRender() {
            if (reorderRaf) return;
            reorderRaf = requestAnimationFrame(() => {
                reorderRaf = 0;
                renderNotes();
            });
        }

        async function persistOrderIfDirty() {
            if (!orderDirty) return;
            orderDirty = false;
            try {
                await window.Airlink.api('/notes/reorder', {
                    method: 'POST',
                    body: { ordered_ids: notes.map(n => n.id) },
                });
            } catch (e) {
                setError(e.message || 'Falha ao salvar ordem.');
            }
        }

        function renderFolders() {
            folderListEl.innerHTML = '';

            const allBtn = document.createElement('button');
            allBtn.type = 'button';
            allBtn.className = 'list-item';
            allBtn.setAttribute('aria-selected', selectedFolderId === null ? 'true' : 'false');
            allBtn.innerHTML = `
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16"></path><path d="M4 12h16"></path><path d="M4 18h16"></path>
                </svg>
                <div class="list-item-col">
                    <div class="list-item-title">Todas as notas</div>
                </div>
            `;
            allBtn.addEventListener('click', () => selectFolder(null));
            allBtn.addEventListener('dragenter', (e) => { e.preventDefault(); setDropTarget(allBtn); });
            allBtn.addEventListener('dragover', (e) => { e.preventDefault(); setDropTarget(allBtn); });
            allBtn.addEventListener('dragleave', (e) => {
                if (!e.relatedTarget || !allBtn.contains(e.relatedTarget)) clearDropTarget(allBtn);
            });
            allBtn.addEventListener('drop', (e) => {
                e.preventDefault();
                clearDropTarget(allBtn);
                const noteId = e.dataTransfer ? e.dataTransfer.getData('text/note-id') : '';
                if (noteId) moveNoteToFolder(noteId, null);
            });
            folderListEl.appendChild(allBtn);

            for (const f of folders) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'list-item';
                btn.setAttribute('aria-selected', f.id === selectedFolderId ? 'true' : 'false');
                const colorStyle = f.color ? `style="color:${String(f.color)}"` : '';
                btn.innerHTML = `
                    <svg class="icon" ${colorStyle} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6z"></path>
                    </svg>
                    <div class="list-item-col">
                        <div class="list-item-title">${escapeHtml(f.name)}</div>
                    </div>
                    <div class="list-item-end">
                        <span class="more" data-folder-more="${f.id}" aria-label="Opções da pasta">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 12h.01"></path><path d="M19 12h.01"></path><path d="M5 12h.01"></path>
                            </svg>
                        </span>
                    </div>
                `;
                btn.addEventListener('click', (e) => {
                    const more = e.target && e.target.closest ? e.target.closest('[data-folder-more]') : null;
                    if (more) {
                        e.preventDefault();
                        e.stopPropagation();
                        openFolderMenu(f, e.clientX, e.clientY);
                        return;
                    }
                    selectFolder(f.id);
                });
                btn.addEventListener('dragenter', (e) => { e.preventDefault(); setDropTarget(btn); });
                btn.addEventListener('dragover', (e) => { e.preventDefault(); setDropTarget(btn); });
                btn.addEventListener('dragleave', (e) => {
                    if (!e.relatedTarget || !btn.contains(e.relatedTarget)) clearDropTarget(btn);
                });
                btn.addEventListener('drop', (e) => {
                    e.preventDefault();
                    clearDropTarget(btn);
                    const noteId = e.dataTransfer ? e.dataTransfer.getData('text/note-id') : '';
                    if (noteId) moveNoteToFolder(noteId, f.id);
                });
                folderListEl.appendChild(btn);
            }

            const folderSelected = selectedFolderId !== null;
            btnRenameFolder.disabled = !folderSelected;
            btnDeleteFolder.disabled = !folderSelected;

            const hiddenBtn = document.createElement('button');
            hiddenBtn.type = 'button';
            hiddenBtn.className = 'list-item';
            hiddenBtn.setAttribute('aria-selected', 'false');
            hiddenBtn.innerHTML = `
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 17a2 2 0 0 0 2-2v-2a2 2 0 0 0-4 0v2a2 2 0 0 0 2 2z"></path>
                    <path d="M6 11V9a6 6 0 1 1 12 0v2"></path>
                    <path d="M5 11h14v10H5z"></path>
                </svg>
                <div class="list-item-col">
                    <div class="list-item-title">Ocultas</div>
                </div>
            `;
            hiddenBtn.addEventListener('click', openHidden);
            folderListEl.appendChild(hiddenBtn);
        }

        function renderNotes() {
            const q = (searchEl.value || '').trim().toLowerCase();
            const scrollTop = noteListEl.scrollTop;
            noteListEl.innerHTML = '';

            const list = view === 'hidden' ? hiddenNotes : notes;
            const selectedId = view === 'hidden' ? selectedHiddenId : selectedNoteId;

            const filtered = list.filter(n => {
                const title = (n.title || '').toLowerCase();
                const contentText = stripHtml(n.content || '').toLowerCase();
                return !q || title.includes(q) || contentText.includes(q);
            });

            const makeRow = (n) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'list-item';
                btn.setAttribute('aria-selected', n.id === selectedId ? 'true' : 'false');

                const derived = (n.title || '').trim() ? n.title : stripHtml(n.content || '').split(' ').slice(0, 12).join(' ');
                const title = derived || 'Sem título';
                const preview = stripHtml(n.content || '').slice(0, 90);
                const folderPill = (view === 'notes' && selectedFolderId === null && n.folder_id)
                    ? (folderNameById.get(n.folder_id) ? `<span class="folder-pill">${escapeHtml(folderNameById.get(n.folder_id))}</span>` : '')
                    : '';
                const tagPill = view === 'notes' ? tagPillHtml(n.tag_id) : '';
                const meta = tagPill + folderPill + escapeHtml(formatDate(n.updated_at));

                const icon = view === 'hidden'
                    ? `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17a2 2 0 0 0 2-2v-2a2 2 0 0 0-4 0v2a2 2 0 0 0 2 2z"></path><path d="M6 11V9a6 6 0 1 1 12 0v2"></path><path d="M5 11h14v10H5z"></path></svg>`
                    : `<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"></path><path d="M8 8h8"></path><path d="M8 12h8"></path><path d="M8 16h5"></path></svg>`;

                const pinBtn = view === 'notes'
                    ? `<button class="pin-btn" type="button" data-pin="true" data-pinned="${n.is_pinned ? 'true' : 'false'}" aria-label="${n.is_pinned ? 'Desafixar' : 'Fixar'}">
                            <svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 9V4h-4v5"></path>
                                <path d="M5 9h14"></path>
                                <path d="M12 9v12"></path>
                            </svg>
                        </button>`
                    : '';

                const end = view === 'notes'
                    ? `<div class="list-item-end">${pinBtn}</div>`
                    : '';

                btn.innerHTML = `
                    ${icon}
                    <div class="list-item-col">
                        <div class="list-item-title">${escapeHtml(title)}</div>
                        <div class="list-item-sub">${escapeHtml(preview || '—')}</div>
                        <div class="list-item-meta">${meta}</div>
                    </div>
                    ${end}
                `;

                if (view === 'hidden') {
                    btn.addEventListener('click', () => selectHidden(n.id));
                    return btn;
                }

                btn.draggable = true;
                btn.dataset.noteId = String(n.id);
                btn.addEventListener('click', (e) => {
                    const pin = e.target && e.target.closest ? e.target.closest('.pin-btn') : null;
                    if (pin) {
                        e.preventDefault();
                        e.stopPropagation();
                        togglePin(n);
                        return;
                    }
                    selectNote(n.id);
                });
                btn.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                    openNoteMenu(n, e.clientX, e.clientY);
                });
                btn.addEventListener('dragstart', (e) => {
                    if (!e.dataTransfer) return;
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/note-id', String(n.id));
                    draggingNoteId = n.id;
                    orderDirty = false;
                });
                btn.addEventListener('dragenter', (e) => {
                    if (draggingNoteId === null) return;
                    if (draggingNoteId === n.id) return;
                    e.preventDefault();
                    reorderNotesLocal(draggingNoteId, n.id);
                    scheduleNotesRender();
                });
                btn.addEventListener('dragover', (e) => {
                    if (draggingNoteId === null) return;
                    if (draggingNoteId === n.id) return;
                    e.preventDefault();
                });
                btn.addEventListener('dragend', async () => {
                    draggingNoteId = null;
                    await persistOrderIfDirty();
                });

                return btn;
            };

            const appendSection = (label) => {
                const el = document.createElement('div');
                el.className = 'list-section';
                el.textContent = label;
                noteListEl.appendChild(el);
            };

            if (view === 'notes') {
                const pinned = filtered.filter(n => !!n.is_pinned);
                const regular = filtered.filter(n => !n.is_pinned);
                if (pinned.length > 0) {
                    appendSection('Fixadas');
                    for (const n of pinned) noteListEl.appendChild(makeRow(n));
                }
                if (pinned.length > 0 && regular.length > 0) appendSection('Outras');
                for (const n of regular) noteListEl.appendChild(makeRow(n));
            } else {
                for (const n of filtered) noteListEl.appendChild(makeRow(n));
            }

            noteListEl.scrollTop = scrollTop;
        }

        function renderEditor() {
            if (view === 'hidden') {
                const n = getSelectedHidden();
                titleEl.textContent = '';
                bodyEl.innerHTML = '';
                metaEl.textContent = 'Ocultas';
                tagIndicatorEl.style.display = 'none';
                lastSavedVersion = null;
                setEditorEnabled(false);
                btnDeleteNote.disabled = true;

                if (!n) {
                    setStatus('Selecione uma nota oculta.');
                    emptyEl.style.display = 'none';
                    return;
                }

                const parsed = parseContent(n.content || '');
                titleEl.textContent = parsed.title || (n.title || '');
                bodyEl.innerHTML = parsed.body || '';
                setStatus(`Atualizado em ${formatDate(n.updated_at)}`);
                emptyEl.style.display = 'none';
                return;
            }

            const n = getSelectedNote();
            if (!n) {
                titleEl.textContent = '';
                bodyEl.innerHTML = '';
                metaEl.textContent = '—';
                tagIndicatorEl.style.display = 'none';
                lastSavedVersion = null;
                setEditorEnabled(true);
                btnDeleteNote.disabled = true;
                setStatus('Comece a escrever…');
                emptyEl.style.display = 'none';
                return;
            }

            const parsed = parseContent(n.content || '');
            titleEl.textContent = parsed.title || (n.title || '');
            bodyEl.innerHTML = parsed.body || '';

            metaEl.textContent = `v${n.version}`;
            const tag = n.tag_id ? tagById.get(n.tag_id) : null;
            if (tag) {
                const color = tag.color ? String(tag.color) : '#9AA0A6';
                tagIndicatorEl.style.display = 'inline-flex';
                tagIndicatorEl.textContent = tag.name || 'Tag';
                tagIndicatorEl.style.borderColor = color + '55';
                tagIndicatorEl.style.background = color + '22';
                tagIndicatorEl.style.color = color;
            } else {
                tagIndicatorEl.style.display = 'none';
            }
            lastSavedVersion = n.version;
            setEditorEnabled(true);
            btnDeleteNote.disabled = false;
            setStatus(`Atualizado em ${formatDate(n.updated_at)}`);
            setError('');
            emptyEl.style.display = 'none';
        }

        function renderTagList() {
            if (!tagListEl) return;

            tagListEl.innerHTML = '';

            const makeOption = (tag) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'tag-option';

                const selected = (tag && tag.id) ? (selectedTagIdForModal === tag.id) : (selectedTagIdForModal === null);
                btn.setAttribute('aria-selected', selected ? 'true' : 'false');

                if (!tag) {
                    btn.innerHTML = `
                        <span class="tag-dot" style="background: rgba(0,0,0,0.08);"></span>
                        <span>Sem tag</span>
                    `;
                    btn.addEventListener('click', () => {
                        selectedTagIdForModal = null;
                        renderTagList();
                    });
                    return btn;
                }

                const color = tag.color ? String(tag.color) : '#9AA0A6';
                const pill = tagPillHtml(tag.id);
                btn.innerHTML = `
                    <span class="tag-dot" style="background:${color}; border-color:${color}55;"></span>
                    <span>${escapeHtml(tag.name || '')}</span>
                    ${pill}
                `;
                btn.addEventListener('click', () => {
                    selectedTagIdForModal = tag.id;
                    renderTagList();
                });
                return btn;
            };

            tagListEl.appendChild(makeOption(null));

            for (const t of tags) {
                tagListEl.appendChild(makeOption(t));
            }
        }

        async function loadTags() {
            const payload = await window.Airlink.api('/tags');
            tags = payload && payload.data ? payload.data : [];
            tagById.clear();
            for (const t of tags) tagById.set(t.id, t);
            renderTagList();
            renderNotes();
            renderEditor();
        }

        async function loadFolders() {
            folders = await window.Airlink.api('/folders');
            if (!Array.isArray(folders)) folders = [];
            folderNameById.clear();
            folderColorById.clear();
            for (const f of folders) {
                if (f && typeof f.id !== 'undefined') {
                    folderNameById.set(f.id, f.name || '');
                    folderColorById.set(f.id, f.color || null);
                }
            }
            renderFolders();
        }

        async function loadNotes({ keepSelection = true } = {}) {
            view = 'notes';
            hiddenNotes = [];
            selectedHiddenId = null;
            btnNewNote.disabled = false;
            const qs = new URLSearchParams();
            qs.set('per_page', '200');
            if (selectedFolderId !== null) qs.set('folder_id', String(selectedFolderId));
            const page = await window.Airlink.api('/notes?' + qs.toString());
            notes = (page && page.data) ? page.data : [];
            for (const n of notes) {
                notesById.set(n.id, n);
            }
            if (!keepSelection) selectedNoteId = null;
            if (keepSelection && selectedNoteId && !notes.some(n => n.id === selectedNoteId)) selectedNoteId = null;
            renderNotes();
            renderEditor();
        }

        async function selectFolder(folderId) {
            view = 'notes';
            selectedHiddenId = null;
            btnNewNote.disabled = false;
            selectedFolderId = folderId;
            selectedNoteId = null;
            renderFolders();
            await loadNotes({ keepSelection: false });
        }

        async function selectNote(noteId) {
            view = 'notes';
            selectedHiddenId = null;
            btnNewNote.disabled = false;
            selectedNoteId = noteId;
            renderNotes();
            try {
                const note = await window.Airlink.api('/notes/' + noteId);
                notesById.set(note.id, note);
                const idx = notes.findIndex(n => n.id === noteId);
                if (idx >= 0) notes[idx] = note;
                else notes.unshift(note);
                renderNotes();
                renderEditor();
                lastSavedContent = (note.content || '').toString();
            } catch (e) {
                setError(e.message || 'Falha ao carregar a nota.');
            }
        }

        function selectHidden(hiddenId) {
            view = 'hidden';
            selectedHiddenId = hiddenId;
            selectedNoteId = null;
            selectedFolderId = null;
            btnNewNote.disabled = true;
            renderNotes();
            renderEditor();
        }

        async function openHidden() {
            closeCtx();
            const pin = await requireVaultPin({ allowCreate: true });
            if (!pin) return;

            try {
                const payload = await window.Airlink.api('/vault/notes', { method: 'POST', body: { pin } });
                hiddenNotes = payload && payload.data ? payload.data : [];
                view = 'hidden';
                selectedHiddenId = null;
                selectedNoteId = null;
                selectedFolderId = null;
                btnNewNote.disabled = true;
                renderFolders();
                renderNotes();
                renderEditor();
                setStatus('Ocultas');
            } catch (e) {
                vaultPin = null;
                vaultPinSetAt = 0;
                setError(e.message || 'Falha ao abrir as notas ocultas.');
            }
        }

        async function moveNoteToFolder(noteId, folderId) {
            if (view !== 'notes') return;
            const id = parseInt(String(noteId), 10);
            if (!Number.isFinite(id) || id <= 0) return;

            try {
                await window.Airlink.api('/notes/' + id, { method: 'PUT', body: { folder_id: folderId } });
                const idx = notes.findIndex(n => n.id === id);
                if (idx >= 0) {
                    notes[idx].folder_id = folderId;
                    if (selectedFolderId !== null && folderId !== selectedFolderId) {
                        notes.splice(idx, 1);
                    }
                }
                renderNotes();
            } catch (e) {
                setError(e.message || 'Falha ao mover a nota.');
            }
        }

        function openFolderMenu(folder, x, y) {
            openCtx([
                {
                    label: 'Renomear',
                    icon: `<svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>`,
                    onClick: async () => {
                        selectedFolderId = folder.id;
                        renderFolders();
                        openFolderModal({ mode: 'rename', initialName: folder.name });
                    },
                },
                {
                    label: 'Cor',
                    icon: `<svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v10"></path><path d="M8 7h8"></path><path d="M5 21h14"></path><path d="M7 13h10"></path></svg>`,
                    onClick: async () => {
                        closeCtx();
                        openFolderColorModal(folder);
                    },
                },
                {
                    label: 'Excluir',
                    variant: 'danger',
                    icon: `<svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path></svg>`,
                    onClick: async () => {
                        selectedFolderId = folder.id;
                        renderFolders();
                        await deleteSelectedFolder();
                    },
                },
            ], x, y);
        }

        async function togglePin(note) {
            const prev = !!note.is_pinned;
            const next = !prev;

            note.is_pinned = next;
            notesById.set(note.id, note);
            animateListUpdate();
            renderNotes();

            try {
                const updated = await window.Airlink.api('/notes/' + note.id, { method: 'PUT', body: { is_pinned: next } });
                note.is_pinned = !!updated.is_pinned;
                note.updated_at = updated.updated_at;
                notesById.set(note.id, note);
                animateListUpdate();
                renderNotes();
            } catch (e) {
                note.is_pinned = prev;
                notesById.set(note.id, note);
                animateListUpdate();
                renderNotes();
                setError(e.message || 'Falha ao fixar.');
            }
        }

        async function deleteNote(note) {
            const ok = confirm('Excluir esta nota?');
            if (!ok) return;
            await window.Airlink.api('/notes/' + note.id, { method: 'DELETE' });
            notes = notes.filter(x => x.id !== note.id);
            notesById.delete(note.id);
            if (selectedNoteId === note.id) selectedNoteId = null;
            renderNotes();
            renderEditor();
        }

        async function hideNote(note) {
            const pin = await requireVaultPin({ allowCreate: true });
            if (!pin) return;

            await window.Airlink.api('/vault/hide-note/' + note.id, { method: 'POST', body: { pin } });
            notes = notes.filter(x => x.id !== note.id);
            notesById.delete(note.id);
            if (selectedNoteId === note.id) selectedNoteId = null;
            renderNotes();
            renderEditor();
        }

        function openNoteMenu(note, x, y) {
            if (view !== 'notes') return;

            openCtx([
                {
                    label: note.is_pinned ? 'Desafixar' : 'Fixar',
                    icon: `<svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 9l7-7"></path><path d="M3 21l6-6"></path><path d="M7 17l4-4"></path><path d="M3 3l18 18"></path></svg>`,
                    onClick: async () => {
                        try { await togglePin(note); } catch (e) { setError(e.message || 'Falha ao fixar.'); }
                    },
                },
                {
                    label: 'Tag',
                    icon: `<svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41 12 22l-9-9V2h11l6.59 6.59a2 2 0 0 1 0 2.82z"></path><path d="M7 7h.01"></path></svg>`,
                    onClick: async () => {
                        closeCtx();
                        openTagModal(note.id);
                    },
                },
                {
                    label: 'Enviar para Ocultas',
                    icon: `<svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 17a2 2 0 0 0 2-2v-2a2 2 0 0 0-4 0v2a2 2 0 0 0 2 2z"></path><path d="M6 11V9a6 6 0 1 1 12 0v2"></path><path d="M5 11h14v10H5z"></path></svg>`,
                    onClick: async () => {
                        try { await hideNote(note); } catch (e) { setError(e.message || 'Falha ao ocultar.'); }
                    },
                },
                {
                    label: 'Excluir',
                    variant: 'danger',
                    icon: `<svg class="mi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M19 6l-1 14H6L5 6"></path></svg>`,
                    onClick: async () => {
                        try { await deleteNote(note); } catch (e) { setError(e.message || 'Falha ao excluir.'); }
                    },
                },
            ], x, y);
        }

        async function renameSelectedFolder() {
            const f = getSelectedFolder();
            if (!f) return;
            openFolderModal({ mode: 'rename', initialName: f.name });
        }

        async function deleteSelectedFolder() {
            const f = getSelectedFolder();
            if (!f) return;
            setError('');
            const ok = confirm(`Excluir a pasta "${f.name}"? As notas dentro serão movidas para "Todas as notas".`);
            if (!ok) return;
            btnDeleteFolder.disabled = true;
            try {
                await window.Airlink.api('/folders/' + f.id, { method: 'DELETE' });
                folders = folders.filter(x => x.id !== f.id);
                await selectFolder(null);
            } catch (e) {
                setError(e.message || 'Falha ao excluir pasta.');
            } finally {
                btnDeleteFolder.disabled = selectedFolderId === null;
                renderFolders();
            }
        }

        async function createNote() {
            setError('');
            btnNewNote.disabled = true;
            try {
                const body = { title: null, content: '' };
                if (selectedFolderId !== null) body.folder_id = selectedFolderId;
                const note = await window.Airlink.api('/notes', { method: 'POST', body });
                notesById.set(note.id, note);
                notes.unshift(note);
                selectedNoteId = note.id;
                renderNotes();
                renderEditor();
                lastSavedContent = (note.content || '').toString();
                titleEl.focus();
            } catch (e) {
                setError(e.message || 'Falha ao criar nota.');
            } finally {
                btnNewNote.disabled = false;
            }
        }

        async function deleteSelectedNote() {
            const n = getSelectedNote();
            if (!n) return;
            setError('');
            const ok = confirm('Excluir esta nota?');
            if (!ok) return;
            btnDeleteNote.disabled = true;
            try {
                await window.Airlink.api('/notes/' + n.id, { method: 'DELETE' });
                notes = notes.filter(x => x.id !== n.id);
                notesById.delete(n.id);
                selectedNoteId = null;
                renderNotes();
                renderEditor();
            } catch (e) {
                setError(e.message || 'Falha ao excluir nota.');
            } finally {
                btnDeleteNote.disabled = !getSelectedNote();
            }
        }

        async function ensureNoteExists() {
            if (selectedNoteId !== null) return getSelectedNote();

            const content = String(buildContentHtml() || '');
            const title = (titleEl.textContent || '').replace(/\s+/g, ' ').trim();
            const hasAnyText = (title !== '') || (stripHtml(content) !== '');
            if (!hasAnyText) return null;

            if (creatingNotePromise) return creatingNotePromise;

            const body = { title: null, content };
            if (selectedFolderId !== null) body.folder_id = selectedFolderId;

            creatingNotePromise = (async () => {
                const note = await window.Airlink.api('/notes', { method: 'POST', body });
                notesById.set(note.id, note);
                notes.unshift(note);
                selectedNoteId = note.id;
                renderNotes();
                metaEl.textContent = `v${note.version}`;
                lastSavedVersion = note.version;
                setStatus(`Atualizado em ${formatDate(note.updated_at)}`);
                setEditorEnabled(true);
                lastSavedContent = (note.content || '').toString();
                return note;
            })().finally(() => {
                creatingNotePromise = null;
            });

            return creatingNotePromise;
        }

        function focusBodyEnd() {
            bodyEl.focus();
            const sel = window.getSelection();
            if (!sel) return;
            const range = document.createRange();
            range.selectNodeContents(bodyEl);
            range.collapse(false);
            sel.removeAllRanges();
            sel.addRange(range);
        }

        function wrapSelection(tag, attrs = {}) {
            const sel = window.getSelection();
            if (!sel || sel.rangeCount === 0) return;
            const range = sel.getRangeAt(0);
            if (range.collapsed) return;

            const el = document.createElement(tag);
            for (const [k, v] of Object.entries(attrs)) el.setAttribute(k, v);
            try {
                range.surroundContents(el);
            } catch (_) {
                const frag = range.extractContents();
                el.appendChild(frag);
                range.insertNode(el);
                const r = document.createRange();
                r.selectNodeContents(el);
                r.collapse(false);
                sel.removeAllRanges();
                sel.addRange(r);
            }
        }

        function applyBlock(tag) {
            bodyEl.focus();
            document.execCommand('formatBlock', false, tag);
        }

        function applyCmd(cmd) {
            bodyEl.focus();
            document.execCommand(cmd, false);
        }

        async function autosave() {
            if (view !== 'notes') return;
            const content = String(buildContentHtml() || '');
            const title = (titleEl.textContent || '').replace(/\s+/g, ' ').trim();
            if (title === '' && stripHtml(content) === '') return;

            let n = getSelectedNote();

            if (!n) {
                try {
                    n = await ensureNoteExists();
                } catch (e) {
                    setStatus('Falha ao criar.');
                    setError(e.message || 'Falha ao criar nota.');
                    return;
                }
                if (!n) return;
            }

            if (lastSavedContent !== null && content === lastSavedContent) return;
            if (saveInFlight) {
                pendingSave = true;
                return;
            }

            saveInFlight = true;
            pendingSave = false;
            const snapshotContent = content;

            try {
                setStatus('Salvando…');
                const payload = await window.Airlink.api(`/notes/${n.id}/autosave`, { method: 'POST', body: { content: snapshotContent } });
                n.content = snapshotContent;
                n.title = title || null;
                n.version = payload.version;
                n.updated_at = payload.updated_at;
                metaEl.textContent = `v${n.version}`;
                lastSavedVersion = n.version;
                setStatus(`Salvo em ${formatDate(n.updated_at)}`);
                const active = document.activeElement === titleEl || document.activeElement === bodyEl;
                if (!active) renderNotes();
                lastSavedContent = snapshotContent;
            } catch (e) {
                setStatus('Falha ao salvar.');
                setError(e.message || 'Falha no autosave.');
            } finally {
                saveInFlight = false;
                const now = Date.now();
                const current = String(buildContentHtml() || '');
                if ((pendingSave || (lastSavedContent !== null && current !== lastSavedContent)) && view === 'notes') {
                    const remaining = Math.max(0, AUTOSAVE_AFTER_IDLE_MS - (now - lastInputAt));
                    debounce(autosave, remaining);
                }
            }
        }

        function scheduleAutosave() {
            if (view !== 'notes') return;
            setError('');
            const content = String(buildContentHtml() || '');
            const title = (titleEl.textContent || '').replace(/\s+/g, ' ').trim();
            if (title === '' && stripHtml(content) === '') {
                if (createTimer) clearTimeout(createTimer);
                if (autosaveTimer) clearTimeout(autosaveTimer);
                return;
            }
            lastInputAt = Date.now();
            pendingSave = true;
            if (selectedNoteId === null) {
                if (createTimer) clearTimeout(createTimer);
                createTimer = setTimeout(() => {
                    ensureNoteExists().catch((e) => {
                        setStatus('Falha ao criar.');
                        setError(e.message || 'Falha ao criar nota.');
                    });
                }, CREATE_NOTE_AFTER_MS);
            }
            debounce(autosave, AUTOSAVE_AFTER_IDLE_MS);
        }

        btnRenameFolder.addEventListener('click', renameSelectedFolder);
        btnDeleteFolder.addEventListener('click', deleteSelectedFolder);
        btnNewNote.addEventListener('click', createNote);
        btnDeleteNote.addEventListener('click', deleteSelectedNote);
        searchEl.addEventListener('input', renderNotes);

        btnNewFolder.addEventListener('click', () => openFolderModal({ mode: 'create' }));

        folderModalEl.addEventListener('click', (e) => {
            if (e.target === folderModalEl) closeFolderModal();
        });
        folderModalCloseEl.addEventListener('click', closeFolderModal);
        folderModalCancelEl.addEventListener('click', closeFolderModal);
        folderModalSaveEl.addEventListener('click', submitFolderModal);
        folderModalInputEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') submitFolderModal();
            if (e.key === 'Escape') closeFolderModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            if (folderModalEl.getAttribute('aria-hidden') === 'false') closeFolderModal();
            if (vaultModalEl.getAttribute('aria-hidden') === 'false') closeVaultModal(null);
            if (folderColorModalEl.getAttribute('aria-hidden') === 'false') closeFolderColorModal();
            if (tagModalEl.getAttribute('aria-hidden') === 'false') closeTagModal();
            closeCtx();
        });

        vaultModalEl.addEventListener('click', (e) => {
            if (e.target === vaultModalEl) closeVaultModal(null);
        });
        vaultModalCloseEl.addEventListener('click', () => closeVaultModal(null));
        vaultModalCancelEl.addEventListener('click', () => closeVaultModal(null));
        vaultModalSaveEl.addEventListener('click', submitVaultModal);
        vaultPinEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') submitVaultModal();
            if (e.key === 'Escape') closeVaultModal(null);
        });
        vaultPinConfirmEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') submitVaultModal();
            if (e.key === 'Escape') closeVaultModal(null);
        });
        vaultPinEnterEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') submitVaultModal();
            if (e.key === 'Escape') closeVaultModal(null);
        });

        folderColorModalEl.addEventListener('click', (e) => {
            if (e.target === folderColorModalEl) closeFolderColorModal();
        });
        folderColorCloseEl.addEventListener('click', closeFolderColorModal);
        folderColorCancelEl.addEventListener('click', closeFolderColorModal);
        folderColorSaveEl.addEventListener('click', submitFolderColorModal);

        tagModalEl.addEventListener('click', (e) => {
            if (e.target === tagModalEl) closeTagModal();
        });
        tagModalCloseEl.addEventListener('click', closeTagModal);
        tagModalCancelEl.addEventListener('click', closeTagModal);
        tagModalSaveEl.addEventListener('click', submitTagModal);

        onboardingCloseEl.addEventListener('click', () => {});
        onboardingNextEl.addEventListener('click', () => {
            onboardingStep1El.style.display = 'none';
            onboardingStep2El.style.display = 'block';
        });
        onboardingDoneEl.addEventListener('click', async () => {
            try { await window.Airlink.api('/onboarding/complete', { method: 'POST' }); } catch (_) {}
            onboardingEl.style.display = 'none';
            onboardingEl.setAttribute('aria-hidden', 'true');
        });

        document.addEventListener('mousedown', (e) => {
            if (ctxEl.getAttribute('aria-hidden') === 'true') return;
            if (ctxEl.contains(e.target)) return;
            closeCtx();
        });

        editorWrapEl.addEventListener('click', (e) => {
            if (titleEl.contains(e.target) || bodyEl.contains(e.target)) return;
            if (!(titleEl.textContent || '').trim()) {
                titleEl.focus();
                return;
            }
            bodyEl.focus();
        });

        bodyEl.addEventListener('focus', () => {
            if (!(titleEl.textContent || '').trim()) titleEl.focus();
        });

        titleEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (!bodyEl.innerHTML.trim()) bodyEl.innerHTML = '<div><br></div>';
                focusBodyEnd();
            }
        });

        titleEl.addEventListener('input', scheduleAutosave);
        bodyEl.addEventListener('input', scheduleAutosave);

        function updateToolbarState() {
            if (document.activeElement !== bodyEl) return;
            const buttons = toolbarEl.querySelectorAll('button.tool[data-cmd]');
            for (const btn of buttons) {
                const cmd = btn.getAttribute('data-cmd');
                if (!cmd) continue;
                try {
                    const active = document.queryCommandState(cmd);
                    btn.setAttribute('data-active', active ? 'true' : 'false');
                } catch (_) {
                    btn.setAttribute('data-active', 'false');
                }
            }
        }

        document.addEventListener('selectionchange', updateToolbarState);
        bodyEl.addEventListener('keyup', updateToolbarState);
        bodyEl.addEventListener('mouseup', updateToolbarState);

        toolbarEl.addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if (!btn || btn.disabled) return;
            const cmd = btn.getAttribute('data-cmd');
            const wrap = btn.getAttribute('data-wrap');
            const block = btn.getAttribute('data-block');
            if (cmd) applyCmd(cmd);
            if (block) applyBlock(block);
            if (wrap === 'mark') { bodyEl.focus(); wrapSelection('mark'); }
            scheduleAutosave();
            updateToolbarState();
        });

        fontSelectEl.addEventListener('change', () => {
            const v = fontSelectEl.value;
            if (!v) return;
            bodyEl.focus();
            wrapSelection('span', { 'data-font': v });
            fontSelectEl.value = '';
            scheduleAutosave();
        });

        document.addEventListener('keydown', (e) => {
            if (folderModalEl.getAttribute('aria-hidden') === 'false') return;
            if (vaultModalEl.getAttribute('aria-hidden') === 'false') return;
            if (folderColorModalEl.getAttribute('aria-hidden') === 'false') return;
            if (tagModalEl.getAttribute('aria-hidden') === 'false') return;
            if (onboardingEl.getAttribute('aria-hidden') === 'false') return;
            if (e.metaKey || e.ctrlKey || e.altKey) return;
            if (e.key.length !== 1) return;
            const tag = (document.activeElement && document.activeElement.tagName) ? document.activeElement.tagName.toLowerCase() : '';
            if (tag === 'input' || tag === 'textarea' || tag === 'select' || document.activeElement === titleEl || document.activeElement === bodyEl) return;
            if (!(titleEl.textContent || '').trim()) {
                titleEl.focus();
                document.execCommand('insertText', false, e.key);
                e.preventDefault();
                return;
            }
            bodyEl.focus();
            document.execCommand('insertText', false, e.key);
            e.preventDefault();
        });

        (async () => {
            try {
                const payload = await window.Airlink.api('/me');
                if (payload && payload.user) window.Airlink.setNavbarUser(payload.user);
                await loadFolders();
                await loadTags();
                await loadNotes({ keepSelection: false });
                setStatus('Comece a escrever…');
                setTimeout(() => titleEl.focus(), 0);
                if (payload && payload.onboarding_completed === false) {
                    onboardingStep1El.style.display = 'block';
                    onboardingStep2El.style.display = 'none';
                    onboardingEl.style.display = 'flex';
                    onboardingEl.setAttribute('aria-hidden', 'false');
                }
                window.addEventListener('airlink:tags-updated', () => { loadTags().catch(() => {}); });
            } catch (_) {
                window.Airlink.clearToken();
                location.href = '/login';
            }
        })();
    </script>
</x-layouts.app>

