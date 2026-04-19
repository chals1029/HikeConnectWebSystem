<style>
    /* ============================================================
       HikeConnect — New Section Styles
       Prefixed with ns- to avoid conflicts with existing styles
       ============================================================ */

    /* ── Shared ─────────────────────────────────────────────── */
    .ns-page-header { display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px; margin-bottom:24px; }
    .ns-page-header h2 { font-size:28px; font-weight:800; color:var(--text); }
    .ns-back-btn { display:inline-flex; align-items:center; gap:8px; background:var(--panel); border:1px solid var(--line); border-radius:10px; padding:10px 16px; font-size:13px; font-weight:600; color:var(--text); cursor:pointer; margin-bottom:16px; transition:all .2s ease; }
    .ns-back-btn:hover { background:var(--brand-soft); border-color:var(--brand); color:var(--brand-dark); }
    .ns-action-btn { display:inline-flex; align-items:center; gap:6px; background:var(--brand-dark); color:#fff; border:none; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s ease; }
    .ns-action-btn:hover { background:var(--brand); transform:translateY(-2px); box-shadow:0 6px 20px rgba(16,185,129,.3); }

    /* ── Mountain Detail ────────────────────────────────────── */
    .ns-detail-hero { height:300px; border-radius:var(--radius-lg); overflow:hidden; position:relative; background-size:cover; background-position:center; margin-bottom:24px; }
    .ns-detail-hero-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(0,0,0,.7) 0%,rgba(0,0,0,.15) 60%,transparent 100%); display:flex; flex-direction:column; justify-content:flex-end; padding:28px; gap:8px; }
    .ns-detail-hero-overlay h2 { font-size:36px; font-weight:800; color:#fff; line-height:1.1; }
    .ns-status-pill { display:inline-flex; align-items:center; gap:4px; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700; width:fit-content; }
    .ns-status-pill.open { background:rgba(16,185,129,.9); color:#fff; }
    .ns-status-pill.closed { background:rgba(239,68,68,.9); color:#fff; }
    .ns-diff-pill { display:inline-block; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:700; background:rgba(255,255,255,.2); backdrop-filter:blur(8px); color:#fff; width:fit-content; }

    .ns-detail-layout { display:grid; grid-template-columns:1.6fr 1fr; gap:20px; }
    .ns-detail-main { display:flex; flex-direction:column; gap:20px; }
    .ns-detail-side { display:flex; flex-direction:column; gap:16px; }

    .ns-detail-spotlight { padding:24px; border:none; overflow:hidden; background:
        radial-gradient(circle at top left, rgba(16,185,129,.14), transparent 34%),
        linear-gradient(180deg, rgba(255,255,255,.98), rgba(244,250,246,.98));
        box-shadow:0 10px 34px rgba(6,95,70,.08), 0 24px 50px rgba(15,23,42,.06);
    }
    .ns-detail-spotlight-head { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-bottom:20px; }
    .ns-detail-spotlight-copy { display:flex; flex-direction:column; gap:10px; max-width:42rem; }
    .ns-detail-kicker { display:inline-flex; align-items:center; gap:6px; width:fit-content; padding:6px 10px; border-radius:999px; background:rgba(6,95,70,.08); color:var(--brand-dark); font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .ns-detail-spotlight-copy h3 { font-size:28px; line-height:1.05; color:var(--text); }
    .ns-detail-spotlight-copy p { color:var(--muted); font-size:14px; line-height:1.7; }
    .ns-detail-inline-meta { display:flex; flex-wrap:wrap; gap:16px; color:var(--muted); font-size:12px; }
    .ns-detail-inline-meta span { display:inline-flex; align-items:center; }
    .ns-detail-inline-meta strong { color:var(--text); }

    .ns-detail-tabs { display:flex; gap:8px; padding:6px; border-radius:999px; background:rgba(255,255,255,.92); box-shadow:inset 0 0 0 1px rgba(6,95,70,.08); }
    .ns-detail-tab { border:none; border-radius:999px; padding:10px 16px; background:transparent; color:var(--muted); font-size:13px; font-weight:700; cursor:pointer; transition:all .2s ease; }
    .ns-detail-tab.active { background:linear-gradient(135deg,var(--brand-dark),var(--brand)); color:#fff; box-shadow:0 10px 24px rgba(6,95,70,.24); }
    .ns-detail-tab:hover:not(.active) { color:var(--text); background:rgba(6,95,70,.06); }

    .ns-detail-panel { display:none; }
    .ns-detail-panel.active { display:block; }

    .ns-spotlight-overview { display:grid; grid-template-columns:minmax(0, 1.2fr) minmax(18rem, .9fr); gap:18px; }
    .ns-spotlight-media { display:grid; grid-template-columns:1.35fr 1fr; gap:14px; }
    .ns-media-card { position:relative; overflow:hidden; border-radius:18px; min-height:220px; background:#d9efe5; }
    .ns-media-card img { width:100%; height:100%; object-fit:cover; display:block; }
    .ns-media-card figcaption { position:absolute; inset:auto 14px 14px 14px; padding:12px 14px; border-radius:14px; background:rgba(15,23,42,.6); backdrop-filter:blur(10px); display:flex; flex-direction:column; gap:2px; }
    .ns-media-card figcaption strong { color:#fff; font-size:13px; }
    .ns-media-card figcaption span { color:rgba(255,255,255,.74); font-size:11px; }
    .ns-media-card--primary { min-height:460px; }
    .ns-media-card--secondary { min-height:220px; }

    .ns-route-preview { border-radius:18px; padding:16px; background:
        linear-gradient(180deg, rgba(221,244,232,.95), rgba(211,235,223,.95));
        border:1px solid rgba(6,95,70,.08);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.6);
        min-height:220px;
        display:flex;
        flex-direction:column;
        gap:14px;
    }
    .ns-route-preview-head { display:flex; flex-direction:column; gap:2px; }
    .ns-route-preview-kicker { font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; color:var(--brand-dark); }
    .ns-route-preview-head strong { font-size:16px; color:var(--text); }
    .ns-route-map-art { position:relative; min-height:118px; border-radius:14px; overflow:hidden; background:
        radial-gradient(circle at 18% 24%, rgba(16,185,129,.24), transparent 30%),
        linear-gradient(135deg, rgba(255,255,255,.78), rgba(196,224,208,.65));
    }
    .ns-route-map-art::before {
        content:'';
        position:absolute;
        inset:0;
        background:
            repeating-radial-gradient(circle at 50% 50%, rgba(15,23,42,.05) 0 1px, transparent 1px 14px),
            repeating-linear-gradient(160deg, rgba(15,23,42,.04) 0 1px, transparent 1px 18px);
        opacity:.42;
        pointer-events:none;
    }
    .ns-route-map-art svg { position:absolute; inset:0; width:100%; height:100%; z-index:1; }
    .ns-route-preview-outline { stroke:#facc15; stroke-width:12; stroke-linecap:round; stroke-linejoin:round; opacity:.96; filter:drop-shadow(0 3px 12px rgba(250,204,21,.28)); }
    .ns-route-preview-line { stroke:#0ea5e9; stroke-width:6; stroke-linecap:round; stroke-linejoin:round; filter:drop-shadow(0 2px 10px rgba(14,165,233,.24)); }
    .ns-route-preview-node { stroke:#ffffff; stroke-width:4; }
    .ns-route-preview-node--start { fill:#2563eb; }
    .ns-route-preview-node--mid { fill:#f59e0b; }
    .ns-route-preview-node--end { fill:#16a34a; }
    .ns-route-markers { display:grid; grid-template-columns:repeat(3, 1fr); gap:10px; }
    .ns-route-marker { padding:10px 12px; border-radius:14px; background:rgba(255,255,255,.72); }
    .ns-route-marker strong { display:block; font-size:12px; color:var(--text); }
    .ns-route-marker span { font-size:11px; color:var(--muted); }

    .ns-spotlight-side { display:flex; flex-direction:column; gap:16px; }
    .ns-spotlight-metrics { display:grid; grid-template-columns:repeat(3, 1fr); gap:12px; }
    .ns-spotlight-metric { padding:14px; border-radius:16px; background:#fff; border:1px solid rgba(6,95,70,.08); box-shadow:0 10px 20px rgba(15,23,42,.04); }
    .ns-spotlight-metric span { display:block; font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
    .ns-spotlight-metric strong { font-size:22px; color:var(--text); line-height:1; }
    .ns-spotlight-story { padding:18px; border-radius:18px; background:#fff; border:1px solid rgba(6,95,70,.08); display:flex; flex-direction:column; gap:14px; }
    .ns-spotlight-story p { font-size:14px; color:var(--muted); line-height:1.75; }
    .ns-highlight-list { display:grid; gap:10px; }
    .ns-highlight-item { display:flex; gap:10px; align-items:flex-start; padding:12px 14px; border-radius:14px; background:rgba(6,95,70,.05); }
    .ns-highlight-item iconify-icon { color:var(--brand); font-size:18px; margin-top:1px; }
    .ns-highlight-item span { font-size:13px; color:var(--text); line-height:1.55; }

    .ns-top-sights { padding:18px; border-radius:18px; background:linear-gradient(180deg,#fdfefd,#f5faf7); border:1px solid rgba(6,95,70,.08); }
    .ns-top-sights-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px; }
    .ns-top-sights-head h4 { font-size:18px; color:var(--text); }
    .ns-top-sights-list { display:flex; flex-direction:column; gap:10px; }
    .ns-top-sight { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; padding:12px 0; border-bottom:1px solid rgba(6,95,70,.08); }
    .ns-top-sight:last-child { border-bottom:none; padding-bottom:0; }
    .ns-top-sight-copy strong { display:block; font-size:14px; color:var(--text); margin-bottom:2px; }
    .ns-top-sight-copy span { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--brand-dark); margin-bottom:6px; }
    .ns-top-sight-copy p { font-size:12px; color:var(--muted); line-height:1.55; }
    .ns-top-sight iconify-icon { color:var(--muted); font-size:16px; margin-top:2px; }

    .ns-conditions-layout { display:grid; grid-template-columns:minmax(0, .88fr) minmax(18rem, 1fr); gap:18px; }
    .ns-forecast-card, .ns-condition-card { background:#fff; border:1px solid rgba(6,95,70,.08); border-radius:18px; padding:18px; box-shadow:0 12px 28px rgba(15,23,42,.04); }
    .ns-panel-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px; }
    .ns-panel-head h4 { font-size:18px; color:var(--text); }
    .ns-panel-head span { font-size:12px; color:var(--muted); }
    .ns-panel-empty { padding:18px; border-radius:16px; background:#f8faf8; color:var(--muted); font-size:13px; text-align:center; }
    .ns-forecast-list { display:flex; flex-direction:column; gap:10px; }
    .ns-forecast-row { display:grid; grid-template-columns:auto 1fr auto; align-items:center; gap:12px; padding:12px 14px; border-radius:14px; background:linear-gradient(180deg, #f8fbf9, #eef7f2); }
    .ns-forecast-row strong { font-size:13px; color:var(--text); }
    .ns-forecast-row span { font-size:12px; color:var(--muted); }
    .ns-forecast-range { position:relative; height:6px; border-radius:999px; background:rgba(15,23,42,.08); overflow:hidden; }
    .ns-forecast-range i { position:absolute; top:0; bottom:0; border-radius:999px; background:linear-gradient(90deg,#f59e0b,#f97316); }

    .ns-condition-card { background:linear-gradient(180deg, rgba(239,246,255,.75), rgba(250,245,255,.85)); }
    .ns-condition-hero { display:flex; align-items:center; justify-content:space-between; gap:18px; margin-bottom:14px; }
    .ns-condition-score { width:108px; height:108px; border-radius:50%; display:grid; place-items:center; background:radial-gradient(circle at top, #0f3d2f, #0b2d23); color:#fff; box-shadow:0 18px 30px rgba(6,95,70,.22); }
    .ns-condition-score strong { font-size:26px; line-height:1; }
    .ns-condition-score span { font-size:11px; color:rgba(255,255,255,.74); }
    .ns-condition-legend { display:grid; gap:10px; flex:1; }
    .ns-condition-legend span { display:flex; align-items:center; padding:10px 12px; border-radius:12px; background:rgba(255,255,255,.72); color:var(--text); font-size:12px; font-weight:600; }
    .ns-condition-card p { font-size:14px; color:var(--muted); line-height:1.7; margin-bottom:12px; }
    .ns-condition-tips { display:grid; gap:10px; }
    .ns-condition-tip { display:flex; align-items:flex-start; gap:10px; padding:12px 14px; border-radius:14px; background:rgba(255,255,255,.72); }
    .ns-condition-tip iconify-icon { color:var(--brand); font-size:16px; margin-top:2px; }
    .ns-condition-tip span { font-size:12px; color:var(--text); line-height:1.6; }

    .ns-info-tiles { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
    .ns-tile { background:var(--panel); border:none; border-radius:var(--radius-md); padding:16px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:6px; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 10px 24px rgba(6,95,70,0.06); }
    .ns-tile-icon { font-size:24px; }
    .ns-tile-label, .ns-tile span { font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
    .ns-tile strong { font-size:14px; color:var(--text); }

    .ns-jumpoff { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:12px; }
    .ns-jumpoff-map { border-radius:var(--radius-md); overflow:hidden; position:relative; min-height:220px; }
    .ns-trail-map-badge { position:absolute; top:12px; left:12px; z-index:3; display:inline-flex; align-items:center; gap:6px; padding:10px 14px; border-radius:999px; background:rgba(6,95,70,.92); color:#fff; font-size:12px; font-weight:800; box-shadow:0 10px 24px rgba(6,95,70,.24); }
    .ns-jumpoff-map-info { position:absolute; bottom:12px; left:12px; background:rgba(0,0,0,.65); backdrop-filter:blur(10px); border-radius:10px; padding:10px 14px; display:flex; flex-direction:column; gap:2px; z-index:3; pointer-events:none; }
    .ns-jumpoff-map-info strong { font-size:13px; color:#fff; }
    .ns-jumpoff-map-info span { color:rgba(255,255,255,.7) !important; }
    .ns-jumpoff-info { display:flex; flex-direction:column; gap:14px; }
    .ns-jumpoff-row { display:flex; flex-direction:column; gap:4px; }
    .ns-jumpoff-row strong { font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; }
    .ns-jumpoff-row span { font-size:14px; color:var(--text); line-height:1.6; }
    .ns-sim-cta { border:none; border-radius:18px; padding:14px 16px; background:linear-gradient(135deg,#0f172a,#123c33 55%,#10b981); color:#fff; display:flex; align-items:center; gap:12px; cursor:pointer; text-align:left; box-shadow:0 14px 32px rgba(6,95,70,.22); transition:transform .2s ease, box-shadow .2s ease; }
    .ns-sim-cta:hover { transform:translateY(-2px); box-shadow:0 20px 36px rgba(6,95,70,.28); }
    .ns-sim-cta iconify-icon { font-size:22px; flex-shrink:0; }
    .ns-sim-cta span { display:flex; flex-direction:column; gap:2px; }
    .ns-sim-cta strong { font-size:14px; color:#fff; }
    .ns-sim-cta small { font-size:12px; color:rgba(255,255,255,.72); }

    .ns-trail-sim-overlay { position:fixed; inset:0; z-index:1200; display:flex; align-items:stretch; justify-content:center; padding:18px; }
    .ns-trail-sim-backdrop { position:absolute; inset:0; background:rgba(15,23,42,.72); backdrop-filter:blur(8px); }
    .ns-trail-sim-shell { position:relative; z-index:1; width:min(100%, 1400px); min-height:calc(100vh - 36px); border-radius:28px; overflow:hidden; background:#0b1720; box-shadow:0 30px 80px rgba(2,6,23,.4); display:flex; flex-direction:column; }
    .ns-trail-sim-topbar { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; padding:22px 72px 16px 92px; background:linear-gradient(180deg,rgba(5,150,105,.18),rgba(11,23,32,.06)); }
    .ns-trail-sim-copy { display:flex; flex-direction:column; gap:6px; max-width:40rem; }
    .ns-trail-sim-kicker { display:inline-flex; align-items:center; width:fit-content; padding:6px 10px; border-radius:999px; background:rgba(16,185,129,.18); color:#a7f3d0; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .ns-trail-sim-copy h3 { font-size:30px; line-height:1.05; color:#f8fafc; margin:0; }
    .ns-trail-sim-close,
    .ns-trail-sim-replay { border:none; cursor:pointer; transition:transform .2s ease, background .2s ease, box-shadow .2s ease; }
    .ns-trail-sim-close { position:absolute; top:16px; left:16px; width:48px; height:48px; border-radius:999px; background:rgba(255,255,255,.94); color:#0f172a; display:grid; place-items:center; box-shadow:0 16px 36px rgba(15,23,42,.25); z-index:2; }
    .ns-trail-sim-close:hover { transform:translateY(-2px); background:#fff; }
    .ns-trail-sim-close iconify-icon { font-size:22px; }
    .ns-trail-sim-replay { display:inline-flex; align-items:center; gap:8px; padding:12px 18px; border-radius:999px; background:rgba(255,255,255,.12); color:#f8fafc; font-size:13px; font-weight:700; box-shadow:inset 0 0 0 1px rgba(255,255,255,.1); }
    .ns-trail-sim-replay:hover { transform:translateY(-2px); background:rgba(255,255,255,.18); }
    .ns-trail-sim-map-shell { position:relative; flex:1; min-height:520px; background:
        radial-gradient(circle at top left, rgba(16,185,129,.28), transparent 24%),
        radial-gradient(circle at top right, rgba(59,130,246,.18), transparent 20%),
        linear-gradient(180deg,#17312a 0%, #0d1f17 26%, #09131d 100%);
    }
    .ns-trail-sim-map { width:100%; height:100%; min-height:520px; }
    .ns-trail-sim-map > * { width:100%; height:100%; display:block; }
    .ns-trail-sim-status { position:absolute; inset:auto auto 24px 24px; max-width:min(28rem, calc(100% - 48px)); padding:12px 16px; border-radius:16px; background:rgba(15,23,42,.76); color:#e2e8f0; font-size:13px; line-height:1.6; box-shadow:0 16px 40px rgba(2,6,23,.28); backdrop-filter:blur(10px); }
    .ns-trail-sim-status.is-error { background:rgba(127,29,29,.85); color:#fee2e2; }
    .ns-trail-sim-hud { position:absolute; right:24px; bottom:24px; display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:12px; width:min(460px, calc(100% - 48px)); }
    .ns-trail-sim-pill { padding:14px 16px; border-radius:18px; background:rgba(15,23,42,.72); color:#f8fafc; backdrop-filter:blur(12px); box-shadow:0 14px 30px rgba(2,6,23,.24); }
    .ns-trail-sim-pill span { display:block; font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#86efac; margin-bottom:6px; }
    .ns-trail-sim-pill strong { display:block; font-size:14px; line-height:1.45; color:#f8fafc; }
    body.ns-no-scroll { overflow:hidden; }

    .ns-gear-tags { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
    .ns-gear-tag { background:var(--brand-soft); color:var(--brand-dark); padding:8px 14px; border-radius:999px; font-size:12px; font-weight:600; border:1px solid rgba(16,185,129,.15); }

    .ns-detail-guides { display:flex; flex-direction:column; gap:10px; }
    .ns-detail-guide-mini { display:flex; align-items:center; gap:10px; padding:10px; border:none; border-radius:10px; transition:all .2s ease; cursor:pointer; box-shadow:0 1px 3px rgba(6,95,70,0.05), 0 4px 10px rgba(6,95,70,0.06); background:var(--panel); }
    .ns-detail-guide-mini:hover { border-color:var(--brand); background:var(--brand-soft); }
    .ns-detail-guide-mini .ns-guide-avatar { width:36px; height:36px; font-size:12px; }
    .ns-detail-guide-mini h5 { font-size:13px; font-weight:700; color:var(--text); }
    .ns-detail-guide-mini span { font-size:11px; color:var(--muted); }

    .ns-book-cta { width:100%; padding:14px; border:none; border-radius:var(--radius-md); background:linear-gradient(135deg,var(--brand-dark),var(--brand)); color:#fff; font-size:14px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .3s ease; box-shadow:0 6px 20px rgba(6,95,70,.25); }
    .ns-book-cta:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(6,95,70,.35); }

    /* Mountain card status badge */
    .thumb-status { position:absolute; top:10px; left:10px; border-radius:999px; padding:4px 10px; font-size:11px; font-weight:700; z-index:2; display:inline-flex; align-items:center; gap:3px; }
    .thumb-status.open { background:rgba(16,185,129,.9); color:#fff; }
    .thumb-status.closed { background:rgba(239,68,68,.9); color:#fff; }

    /* ── Tour Guides ────────────────────────────────────────── */
    .ns-filter-bar { display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
    .ns-search-box { flex:1; min-width:200px; display:flex; align-items:center; gap:10px; background:var(--panel); border:none; border-radius:12px; padding:12px 16px; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 10px 24px rgba(6,95,70,0.06); }
    .ns-search-box input { border:none; outline:none; width:100%; background:transparent; font:inherit; font-size:14px; color:var(--text); }
    .ns-search-box svg { color:var(--muted); flex-shrink:0; }
    .ns-form-select { background:var(--panel); border:1px solid var(--line); border-radius:12px; padding:12px 16px; font:inherit; font-size:14px; color:var(--text); cursor:pointer; appearance:none; min-width:160px; }

    .ns-guides-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
    .ns-guide-card { background:var(--panel); border:none; border-radius:var(--radius-lg); padding:20px; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 12px 28px rgba(6,95,70,0.08); transition:all .25s ease; }
    .ns-guide-card:hover { transform:translateY(-4px); box-shadow:0 2px 4px rgba(6,95,70,0.06), 0 8px 20px rgba(6,95,70,0.1), 0 20px 40px rgba(6,95,70,0.1); border:none; }
    .ns-guide-top { display:flex; align-items:center; gap:12px; margin-bottom:16px; flex-wrap:wrap; }
    .ns-guide-avatar { width:48px; height:48px; border-radius:50%; display:grid; place-items:center; color:#fff; font-size:15px; font-weight:800; flex-shrink:0; }
    .ns-guide-meta { flex:1; min-width:0; }
    .ns-guide-meta h4 { font-size:15px; font-weight:700; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .ns-guide-spec { font-size:12px; color:var(--muted); }
    .ns-avail-badge { padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .ns-avail-badge.available { background:#e9fbf2; color:#065f46; }
    .ns-avail-badge.on-hike { background:#fff7ed; color:#b45309; }
    .ns-avail-badge.unavailable { background:#fef2f2; color:#b91c1c; }
    .ns-avail-badge.off-duty { background:#f1f5f9; color:#64748b; }

    .ns-guide-details { display:flex; flex-direction:column; gap:8px; margin-bottom:16px; padding-top:12px; border-top:1px solid var(--line); }
    .ns-guide-row { display:flex; justify-content:space-between; align-items:center; font-size:13px; }
    .ns-guide-row span { color:var(--muted); }
    .ns-guide-row strong { color:var(--text); }

    .ns-guide-book-btn { width:100%; padding:10px; border:1px solid var(--brand); background:var(--brand-soft); color:var(--brand-dark); border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s ease; }
    .ns-guide-book-btn:hover:not(:disabled) { background:var(--brand); color:#fff; }
    .ns-guide-book-btn:disabled { opacity:.5; cursor:not-allowed; border-color:var(--line); background:var(--bg); color:var(--muted); }

    /* ── Book a Hike ────────────────────────────────────────── */
    .ns-booking-layout { display:grid; grid-template-columns:1.4fr 1fr; gap:20px; align-items:start; }
    .ns-booking-form-card { }
    .ns-booking-form-card h3 { margin-bottom:20px; }
    .ns-form-group { margin-bottom:16px; }
    .ns-form-label { display:block; font-size:13px; font-weight:600; color:var(--text); margin-bottom:6px; }
    .ns-form-input, .ns-form-textarea { width:100%; padding:12px 14px; border:1px solid var(--line); border-radius:10px; font:inherit; font-size:14px; background:var(--bg); color:var(--text); outline:none; transition:border-color .2s; }
    .ns-form-input:focus, .ns-form-textarea:focus, .ns-form-select:focus { border-color:var(--brand); box-shadow:0 0 0 3px rgba(16,185,129,.1); }
    .ns-form-textarea { resize:vertical; min-height:80px; }
    .ns-form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .ns-submit-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:14px; border:none; border-radius:12px; background:linear-gradient(135deg,var(--brand-dark),var(--brand)); color:#fff; font-size:14px; font-weight:700; cursor:pointer; transition:all .3s ease; box-shadow:0 6px 20px rgba(6,95,70,.25); }
    .ns-submit-btn:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(6,95,70,.35); }

    .ns-booking-preview { position:sticky; top:24px; }
    .ns-booking-preview h3 { margin-bottom:16px; }
    .ns-preview-empty { text-align:center; padding:32px 16px; color:var(--muted); }
    .ns-preview-empty p { font-size:13px; margin-top:12px; }
    .ns-preview-filled { display:flex; flex-direction:column; gap:12px; }
    .ns-preview-row { display:flex; justify-content:space-between; align-items:center; font-size:13px; padding:8px 0; border-bottom:1px solid var(--line); }
    .ns-preview-row:last-child { border-bottom:none; }
    .ns-preview-row span { color:var(--muted); }
    .ns-preview-row strong { color:var(--text); }
    .ns-status-legend { margin-top:20px; padding-top:16px; border-top:1px solid var(--line); }
    .ns-legend-items { display:flex; flex-wrap:wrap; gap:10px; }
    .ns-legend-item { display:flex; align-items:center; gap:5px; font-size:12px; color:var(--muted); }
    .ns-dot { width:8px; height:8px; border-radius:50%; }
    .ns-dot.pending { background:#f59e0b; }
    .ns-dot.approved { background:#10b981; }
    .ns-dot.rejected { background:#ef4444; }
    .ns-dot.completed { background:#3b82f6; }
    .ns-dot.cancelled { background:#94a3b8; }

    .ns-booking-success { position:absolute; inset:0; background:var(--bg); display:flex; align-items:center; justify-content:center; z-index:10; }
    .ns-success-card { text-align:center; max-width:400px; }
    .ns-success-icon { font-size:64px; margin-bottom:16px; }
    .ns-success-card h3 { font-size:24px; margin-bottom:8px; color:var(--text); }
    .ns-success-card p { color:var(--muted); margin-bottom:20px; line-height:1.6; }
    .ns-success-actions { display:flex; flex-direction:column; gap:0; }

    /* ── My Bookings ────────────────────────────────────────── */
    .ns-tabs { display:flex; gap:4px; margin-bottom:20px; background:var(--panel); padding:4px; border-radius:12px; border:none; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 10px 24px rgba(6,95,70,0.06); }
    .ns-tab { flex:1; padding:10px; border:none; background:transparent; border-radius:8px; font-size:13px; font-weight:600; color:var(--muted); cursor:pointer; transition:all .2s ease; }
    .ns-tab.active { background:var(--panel); color:var(--text); box-shadow:0 2px 4px rgba(0,0,0,.05); }
    .ns-tab:hover:not(.active) { color:var(--text); }

    .ns-bookings-list { display:flex; flex-direction:column; gap:12px; }
    .ns-booking-card { display:flex; justify-content:space-between; align-items:center; background:var(--panel); border:none; border-radius:var(--radius-lg); padding:20px; transition:all .2s ease; flex-wrap:wrap; gap:12px; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 12px 28px rgba(6,95,70,0.08); }
    .ns-booking-card:hover { box-shadow:0 2px 4px rgba(6,95,70,0.06), 0 8px 20px rgba(6,95,70,0.1), 0 20px 40px rgba(6,95,70,0.1); }
    .ns-booking-card.hidden { display:none; }
    .ns-booking-left { display:flex; align-items:center; gap:16px; flex:1; min-width:0; }
    .ns-booking-mountain-icon { width:48px; height:48px; border-radius:14px; display:grid; place-items:center; flex-shrink:0; }
    .ns-booking-info { min-width:0; }
    .ns-booking-info h4 { font-size:16px; font-weight:700; color:var(--text); margin-bottom:6px; }
    .ns-booking-meta { display:flex; flex-wrap:wrap; gap:12px; font-size:12px; color:var(--muted); }
    .ns-booking-right { display:flex; align-items:center; gap:10px; flex-shrink:0; }
    .ns-booking-status { padding:5px 12px; border-radius:999px; font-size:11px; font-weight:700; }
    .ns-booking-status.approved { background:#e9fbf2; color:#065f46; }
    .ns-booking-status.pending { background:#fff7ed; color:#b45309; }
    .ns-booking-status.completed { background:#eff6ff; color:#1d4ed8; }
    .ns-booking-status.cancelled { background:#f1f5f9; color:#64748b; }
    .ns-booking-status.rejected { background:#fef2f2; color:#b91c1c; }
    .ns-cancel-btn { padding:6px 12px; border:1px solid var(--danger); color:var(--danger); background:transparent; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; transition:all .2s ease; }
    .ns-cancel-btn:hover { background:var(--danger); color:#fff; }
    .ns-booking-feedback { flex:1 1 100%; padding-top:4px; }
    .ns-completed-feedback-grid { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:14px; }
    .ns-feedback-panel { border:1px solid var(--line); border-radius:18px; background:var(--panel); padding:18px; box-shadow:0 10px 24px rgba(15,23,42,.05); }
    .ns-feedback-panel-head { display:flex; justify-content:space-between; align-items:flex-start; gap:14px; margin-bottom:14px; }
    .ns-feedback-panel-head h5 { font-size:16px; color:var(--text); margin:4px 0 6px; }
    .ns-feedback-panel-head p { font-size:12px; color:var(--muted); line-height:1.6; margin:0; max-width:30rem; }
    .ns-feedback-kicker { display:inline-flex; align-items:center; padding:5px 10px; border-radius:999px; background:rgba(6,95,70,.08); color:var(--brand-dark); font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .ns-feedback-state { display:inline-flex; align-items:center; padding:6px 10px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .ns-feedback-state.submitted { background:#e9fbf2; color:#065f46; }
    .ns-feedback-state.pending { background:#f8fafc; color:#64748b; }
    .ns-inline-feedback-form { display:flex; flex-direction:column; gap:12px; }
    .ns-inline-rating { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .ns-inline-rating-btn { width:38px; height:38px; border:1px solid var(--line); background:var(--bg); border-radius:10px; color:var(--text); font-size:14px; font-weight:700; cursor:pointer; transition:all .18s ease; display:grid; place-items:center; }
    .ns-inline-rating-btn:hover { border-color:var(--brand); color:var(--brand-dark); transform:translateY(-1px); }
    .ns-inline-rating-btn.active { background:linear-gradient(135deg,var(--brand-dark),var(--brand)); color:#fff; border-color:transparent; box-shadow:0 10px 18px rgba(6,95,70,.18); }
    .ns-inline-rating-label { font-size:12px; color:var(--muted); font-weight:700; }
    .ns-inline-feedback-actions { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .ns-inline-feedback-message { font-size:12px; color:var(--muted); line-height:1.5; }
    .ns-inline-feedback-message.is-error { color:#b91c1c; }
    .ns-feedback-panel .ns-form-textarea { background:var(--bg); color:var(--text); border-color:var(--line); }
    .ns-feedback-panel .ns-form-textarea::placeholder { color:var(--muted); }
    .ns-feedback-panel .ns-form-textarea:focus { border-color:var(--brand); box-shadow:0 0 0 3px rgba(16,185,129,.1); }

    /* ── Track Location ─────────────────────────────────────── */
    #view-track-location { padding-bottom: 2rem; }
    .ns-tracker-layout { display:grid; grid-template-columns:2fr 1fr; gap:20px; align-items:start; }
    .ns-tracker-map-area { display:flex; flex-direction:column; gap:16px; min-width:0; }
    .ns-tracker-map-shell {
        width:100%;
        min-height:380px;
        border-radius:var(--radius-lg);
        overflow:hidden;
        border:1px solid var(--line);
        background:var(--panel);
        box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06);
    }
    .ns-tracker-gmap { width:100%; min-height:380px; height:min(52vh, 480px); }
    .ns-tracker-gmap--placeholder {
        display:flex;
        align-items:center;
        justify-content:center;
        min-height:380px;
        height:auto;
        padding:24px;
        background:linear-gradient(160deg, var(--brand-soft) 0%, var(--panel) 45%);
    }
    .ns-tracker-gmap__placeholder-inner { max-width:28rem; text-align:center; }
    .ns-tracker-gmap__title { font-size:16px; font-weight:700; color:var(--text); margin:12px 0 8px; }
    .ns-tracker-gmap__text { font-size:13px; color:var(--muted); line-height:1.55; margin:0; }
    .ns-tracker-gmap__text code { font-size:12px; background:var(--bg); padding:2px 6px; border-radius:6px; }
    .ns-map-display { position:relative; height:360px; border-radius:var(--radius-lg); overflow:hidden; background:linear-gradient(135deg,#0f2419,#132b21,#16331e,#1a3a22); border:1px solid var(--line); display:flex; align-items:center; justify-content:center; }
    .ns-map-grid-lines { position:absolute; inset:0; background-image:linear-gradient(rgba(16,185,129,.08) 1px,transparent 1px),linear-gradient(90deg,rgba(16,185,129,.08) 1px,transparent 1px); background-size:40px 40px; pointer-events:none; }
    .ns-map-center-dot { width:18px; height:18px; border-radius:50%; background:var(--brand); box-shadow:0 0 0 6px rgba(16,185,129,.3),0 0 0 12px rgba(16,185,129,.1); animation:pulse-dot 2s ease-in-out infinite; z-index:2; }
    @keyframes pulse-dot { 0%,100% { box-shadow:0 0 0 6px rgba(16,185,129,.3),0 0 0 12px rgba(16,185,129,.1); } 50% { box-shadow:0 0 0 10px rgba(16,185,129,.2),0 0 0 20px rgba(16,185,129,.05); } }
    .ns-map-overlay-info { position:absolute; bottom:16px; left:16px; background:rgba(0,0,0,.65); backdrop-filter:blur(10px); border-radius:10px; padding:10px 14px; display:flex; flex-direction:column; gap:4px; z-index:3; }
    .ns-map-label { font-size:12px; font-weight:700; color:var(--brand); }
    .ns-map-coords { font-size:11px; color:rgba(255,255,255,.7); font-family:monospace; }

    .ns-tracker-controls { }
    .ns-tracker-stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:12px; margin-top:16px; margin-bottom:8px; }
    .ns-tracker-stat { background:var(--panel); border:none; border-radius:var(--radius-md); padding:14px; text-align:center; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 10px 24px rgba(6,95,70,0.06); }
    .ns-tracker-stat span { display:block; font-size:11px; color:var(--muted); margin-bottom:4px; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
    .ns-tracker-stat strong { font-size:18px; color:var(--text); }
    .ns-tracker-status-text { color:var(--brand) !important; }

    .ns-tracker-sidebar { display:flex; flex-direction:column; gap:16px; }
    .ns-safety-list { display:flex; flex-direction:column; gap:14px; }
    .ns-safety-item { padding-bottom:14px; border-bottom:1px solid var(--line); }
    .ns-safety-item:last-child { padding-bottom:0; border-bottom:none; }
    .ns-safety-item strong { display:block; font-size:13px; color:var(--text); margin-bottom:4px; }
    .ns-safety-item p { font-size:12px; color:var(--muted); line-height:1.5; }

    .ns-signal-bars { display:flex; align-items:flex-end; gap:3px; justify-content:center; height:24px; }
    .ns-bar { width:6px; border-radius:2px; background:var(--line); }
    .ns-bar:nth-child(1) { height:6px; }
    .ns-bar:nth-child(2) { height:12px; }
    .ns-bar:nth-child(3) { height:18px; }
    .ns-bar:nth-child(4) { height:24px; }
    .ns-bar.active { background:var(--brand); }

    /* ── What to Bring ──────────────────────────────────────── */
    .ns-progress-wrap { display:flex; flex-direction:column; align-items:flex-end; gap:6px; min-width:200px; }
    .ns-progress-wrap span { font-size:12px; font-weight:600; color:var(--muted); }
    .ns-progress-bar { width:100%; height:8px; border-radius:99px; background:var(--line); overflow:hidden; }
    .ns-progress-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,var(--brand-dark),var(--brand)); transition:width .4s ease; }

    .ns-checklist-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; }
    .ns-checklist-category h3 { margin-bottom:14px; font-size:16px; }
    .ns-check-item { display:flex; align-items:center; gap:10px; padding:10px 12px; border:none; border-radius:10px; cursor:pointer; transition:all .2s ease; margin-bottom:8px; font-size:14px; color:var(--text); background:var(--panel); box-shadow:0 1px 2px rgba(6,95,70,0.03), 0 3px 8px rgba(6,95,70,0.05); }
    .ns-check-item:hover { border-color:var(--brand); background:var(--brand-soft); }
    .ns-check-item:has(input:checked) { background:var(--brand-soft); border-color:rgba(16,185,129,.3); }
    .ns-check-item:has(input:checked) span { text-decoration:line-through; color:var(--muted); }
    .ns-checkbox { width:18px; height:18px; accent-color:var(--brand); cursor:pointer; flex-shrink:0; }

    /* ── Hiking History ─────────────────────────────────────── */
    .ns-history-stats { display:flex; gap:20px; }
    .ns-mini-stat { text-align:center; }
    .ns-mini-stat strong { display:block; font-size:22px; font-weight:800; color:var(--text); }
    .ns-mini-stat span { font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; font-weight:600; }

    .ns-history-timeline { position:relative; padding-left:32px; }
    .ns-history-timeline::before { content:''; position:absolute; left:11px; top:0; bottom:0; width:2px; background:var(--line); }
    .ns-history-item { position:relative; margin-bottom:20px; }
    .ns-history-dot { position:absolute; left:-32px; top:20px; width:24px; height:24px; border-radius:50%; border:3px solid var(--panel); z-index:1; }
    .ns-history-dot.completed-dot { background:var(--brand); }
    .ns-history-card { transition:all .2s ease; }
    .ns-history-card:hover { border-color:var(--brand); box-shadow:0 4px 16px rgba(0,0,0,.06); }
    .ns-history-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; flex-wrap:wrap; gap:8px; }
    .ns-history-top h4 { font-size:17px; font-weight:700; color:var(--text); }
    .ns-history-date { font-size:12px; color:var(--muted); }
    .ns-history-meta { display:flex; flex-wrap:wrap; gap:12px; font-size:12px; color:var(--muted); margin-bottom:10px; }
    .ns-history-rating { display:flex; align-items:center; flex-wrap:wrap; gap:4px; }

    /* ── Community Posts Enhancement ─────────────────────────── */
    .ns-post-creator { background:var(--panel); border:none; border-radius:var(--radius-lg); padding:20px; margin-bottom:20px; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 12px 28px rgba(6,95,70,0.08); }
    .ns-post-creator h3 { font-size:16px; margin-bottom:14px; }
    .ns-post-input { width:100%; padding:12px; border:1px solid var(--line); border-radius:10px; font:inherit; font-size:14px; background:var(--bg); color:var(--text); outline:none; resize:vertical; min-height:80px; margin-bottom:12px; transition:border-color .2s; }
    .ns-post-input:focus { border-color:var(--brand); }
    .ns-post-controls { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
    .ns-post-actions-row { display:flex; gap:8px; }
    .ns-post-action { display:flex; align-items:center; gap:5px; padding:7px 12px; border:1px solid var(--line); border-radius:8px; background:transparent; font-size:12px; font-weight:600; color:var(--muted); cursor:pointer; transition:all .2s; }
    .ns-post-action:hover { border-color:var(--brand); color:var(--brand-dark); background:var(--brand-soft); }
    .ns-post-submit { padding:8px 18px; border:none; border-radius:8px; background:var(--brand-dark); color:#fff; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; }
    .ns-post-submit:hover { background:var(--brand); }

    .ns-community-feed { display:flex; flex-direction:column; gap:16px; }
    .ns-post-card { background:var(--panel); border:none; border-radius:var(--radius-lg); overflow:hidden; transition:all .2s ease; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 12px 28px rgba(6,95,70,0.08); }
    .ns-post-card:hover { box-shadow:0 2px 4px rgba(6,95,70,0.06), 0 8px 20px rgba(6,95,70,0.1), 0 20px 40px rgba(6,95,70,0.1); }
    .ns-post-header { display:flex; align-items:center; gap:10px; padding:16px 16px 0; }
    .ns-post-avatar { width:36px; height:36px; border-radius:50%; display:grid; place-items:center; color:#fff; font-size:13px; font-weight:700; flex-shrink:0; }
    .ns-post-user-info { flex:1; }
    .ns-post-user-info strong { display:block; font-size:14px; color:var(--text); }
    .ns-post-user-info span { font-size:11px; color:var(--muted); }
    .ns-post-body { padding:12px 16px; font-size:14px; color:var(--text); line-height:1.6; }
    .ns-post-mountain-tag { display:inline-flex; align-items:center; gap:4px; background:var(--brand-soft); color:var(--brand-dark); padding:3px 10px; border-radius:999px; font-size:11px; font-weight:600; margin-top:6px; }
    .ns-post-image { width:100%; max-height:280px; object-fit:cover; }
    .ns-post-image-placeholder { width:100%; height:200px; background:linear-gradient(135deg,var(--brand-soft),rgba(16,185,129,.05)); display:flex; align-items:center; justify-content:center; color:var(--muted); font-size:13px; }
    .ns-post-footer { display:flex; gap:16px; padding:12px 16px; border-top:1px solid var(--line); }
    .ns-post-footer button { display:flex; align-items:center; gap:5px; border:none; background:transparent; font-size:12px; font-weight:600; color:var(--muted); cursor:pointer; padding:4px 8px; border-radius:6px; transition:all .2s; }
    .ns-post-footer button:hover { background:var(--brand-soft); color:var(--brand-dark); }

    /* ── Reviews Enhancement (Feedback Form) ─────────────────── */
    .ns-feedback-form { background:transparent; padding:0; margin-top:20px; border:none; display:flex; flex-direction:column; gap:16px; box-shadow:none; }
    .ns-feedback-form h3 { font-size:18px; margin-bottom:10px; display:none; }

    .ns-feedback-card { background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; padding:20px 24px; box-shadow:0 1px 3px rgba(0,0,0,.04); transition:all .2s; }
    
    .ns-step-progress { display:flex; gap:6px; margin-bottom:20px; }
    .ns-step-progress span { height:4px; width:28px; border-radius:3px; background:#e2e8f0; }
    .ns-step-progress span.step-active { background:#334155; }
    
    .ns-rating-group { margin-bottom:0; }
    .ns-rating-label { display:block; font-size:15px; font-weight:500; color:#1e293b; margin-bottom:14px; }
    
    .ns-star-rating { display:flex; gap:8px; align-items:center; }
    .ns-num-btn { width:42px; height:42px; border:1px solid #e2e8f0; background:#ffffff; border-radius:6px; font-size:15px; cursor:pointer; transition:all .15s; display:grid; place-items:center; color:#475569; font-weight:500; }
    .ns-num-btn:hover { border-color:#cbd5e1; }
    .ns-num-btn.active { background:#2d3748; border-color:#2d3748; color:#ffffff; }
    
    .ns-num-label { font-size:14px; font-weight:500; color:#64748b; margin-left:8px; display:flex; align-items:center; gap:6px; }

    .ns-form-textarea { width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px; height:80px; font:inherit; font-size:14px; color:#1e293b; resize:vertical; transition:border-color .2s; font-family:inherit; }
    .ns-form-textarea::placeholder { color:#94a3b8; }
    .ns-form-textarea:focus { border-color:#10b981; outline:none; }
    
    .ns-feedback-submit { justify-content:center; display:inline-flex; width:auto; border-radius:10px; background:#2d3748; color:#ffffff; border:none; padding:10px 18px; font-weight:600; font-size:13px; cursor:pointer; }
    .ns-feedback-submit:hover { background:#1e293b; }
    .ns-feedback-submit:disabled { opacity:.7; cursor:wait; }

    /* ── Responsive ─────────────────────────────────────────── */
    @media (max-width: 1050px) {
        .ns-detail-layout { grid-template-columns:1fr; }
        .ns-detail-spotlight-head { flex-direction:column; }
        .ns-spotlight-overview { grid-template-columns:1fr; }
        .ns-conditions-layout { grid-template-columns:1fr; }
        .ns-info-tiles { grid-template-columns:repeat(2,1fr); }
        .ns-jumpoff { grid-template-columns:1fr; }
        .ns-guides-grid { grid-template-columns:repeat(2,1fr); }
        .ns-booking-layout { grid-template-columns:1fr; }
        .ns-completed-feedback-grid { grid-template-columns:1fr; }
        .ns-tracker-layout { grid-template-columns:1fr; }
        .ns-checklist-grid { grid-template-columns:1fr; }
        .ns-history-stats { gap:12px; }
        .ns-form-row-2 { grid-template-columns:1fr; }
        .ns-trail-sim-overlay { padding:12px; }
        .ns-trail-sim-shell { min-height:calc(100vh - 24px); }
        .ns-trail-sim-topbar { padding:22px 22px 16px 78px; flex-direction:column; align-items:flex-start; }
        .ns-trail-sim-replay { width:100%; justify-content:center; }
        .ns-trail-sim-hud { width:calc(100% - 32px); right:16px; bottom:16px; }
    }

    @media (max-width: 640px) {
        .ns-guides-grid { grid-template-columns:1fr; }
        .ns-spotlight-media { grid-template-columns:1fr; }
        .ns-media-card--primary { min-height:280px; }
        .ns-route-markers { grid-template-columns:1fr; }
        .ns-spotlight-metrics { grid-template-columns:1fr; }
        .ns-detail-tabs { width:100%; justify-content:space-between; }
        .ns-detail-tab { flex:1; text-align:center; padding-left:10px; padding-right:10px; }
        .ns-condition-hero { flex-direction:column; align-items:flex-start; }
        .ns-info-tiles { grid-template-columns:1fr 1fr; }
        .ns-tracker-stats { grid-template-columns:1fr; }
        .ns-page-header { flex-direction:column; align-items:stretch; }
        .ns-history-stats { justify-content:space-around; }
        .ns-booking-card { flex-direction:column; align-items:flex-start; }
        .ns-booking-right { width:100%; justify-content:flex-end; }
        .ns-sim-cta { padding:13px 14px; }
        .ns-trail-sim-overlay { padding:8px; }
        .ns-trail-sim-shell { border-radius:18px; min-height:calc(100dvh - 16px); }
        .ns-trail-sim-topbar { gap:12px; padding:14px 14px 12px 64px; }
        .ns-trail-sim-copy { gap:4px; max-width:none; }
        .ns-trail-sim-kicker { padding:5px 8px; font-size:10px; }
        .ns-trail-sim-copy h3 { font-size:19px; line-height:1.06; max-width:11rem; }
        .ns-trail-sim-close { top:10px; left:10px; width:40px; height:40px; }
        .ns-trail-sim-close iconify-icon { font-size:18px; }
        .ns-trail-sim-replay { width:auto; align-self:flex-start; padding:10px 14px; font-size:12px; }
        .ns-trail-sim-map-shell,
        .ns-trail-sim-map { min-height:calc(100dvh - 130px); }
        .ns-trail-sim-status { left:16px; bottom:16px; right:16px; max-width:none; }
        .ns-trail-sim-hud { grid-template-columns:repeat(2, minmax(0, 1fr)); gap:8px; width:calc(100% - 24px); right:12px; bottom:12px; }
        .ns-trail-sim-pill { padding:10px 12px; border-radius:14px; }
        .ns-trail-sim-pill span { font-size:10px; margin-bottom:4px; }
        .ns-trail-sim-pill strong { font-size:12px; line-height:1.35; }
        .ns-trail-sim-status { left:12px; right:12px; bottom:12px; padding:10px 12px; border-radius:14px; font-size:12px; }
    }

    @media (max-width: 380px) {
        .ns-trail-sim-topbar { padding-right:12px; }
        .ns-trail-sim-copy h3 { font-size:18px; max-width:10rem; }
        .ns-trail-sim-hud { grid-template-columns:1fr; }
    }
</style>
