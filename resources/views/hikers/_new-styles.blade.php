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

    .ns-info-tiles { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
    .ns-tile { background:var(--panel); border:none; border-radius:var(--radius-md); padding:16px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:6px; box-shadow:0 1px 2px rgba(6,95,70,0.04), 0 4px 12px rgba(6,95,70,0.06), 0 10px 24px rgba(6,95,70,0.06); }
    .ns-tile-icon { font-size:24px; }
    .ns-tile-label, .ns-tile span { font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
    .ns-tile strong { font-size:14px; color:var(--text); }

    .ns-jumpoff { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:12px; }
    .ns-jumpoff-map { border-radius:var(--radius-md); overflow:hidden; position:relative; min-height:220px; }
    .ns-jumpoff-map-info { position:absolute; bottom:12px; left:12px; background:rgba(0,0,0,.65); backdrop-filter:blur(10px); border-radius:10px; padding:10px 14px; display:flex; flex-direction:column; gap:2px; z-index:3; pointer-events:none; }
    .ns-jumpoff-map-info strong { font-size:13px; color:#fff; }
    .ns-jumpoff-map-info span { color:rgba(255,255,255,.7) !important; }
    .ns-jumpoff-info { display:flex; flex-direction:column; gap:14px; }
    .ns-jumpoff-row { display:flex; flex-direction:column; gap:4px; }
    .ns-jumpoff-row strong { font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; }
    .ns-jumpoff-row span { font-size:14px; color:var(--text); line-height:1.6; }

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
    .ns-feedback-btn { padding:6px 12px; border:1px solid var(--brand); color:var(--brand-dark); background:var(--brand-soft); border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; transition:all .2s ease; }
    .ns-feedback-btn:hover { background:var(--brand); color:#fff; }

    /* ── Track Location ─────────────────────────────────────── */
    .ns-tracker-layout { display:grid; grid-template-columns:2fr 1fr; gap:20px; }
    .ns-tracker-map-area { display:flex; flex-direction:column; gap:16px; }
    .ns-map-display { position:relative; height:360px; border-radius:var(--radius-lg); overflow:hidden; background:linear-gradient(135deg,#0f2419,#132b21,#16331e,#1a3a22); border:1px solid var(--line); display:flex; align-items:center; justify-content:center; }
    .ns-map-grid-lines { position:absolute; inset:0; background-image:linear-gradient(rgba(16,185,129,.08) 1px,transparent 1px),linear-gradient(90deg,rgba(16,185,129,.08) 1px,transparent 1px); background-size:40px 40px; pointer-events:none; }
    .ns-map-center-dot { width:18px; height:18px; border-radius:50%; background:var(--brand); box-shadow:0 0 0 6px rgba(16,185,129,.3),0 0 0 12px rgba(16,185,129,.1); animation:pulse-dot 2s ease-in-out infinite; z-index:2; }
    @keyframes pulse-dot { 0%,100% { box-shadow:0 0 0 6px rgba(16,185,129,.3),0 0 0 12px rgba(16,185,129,.1); } 50% { box-shadow:0 0 0 10px rgba(16,185,129,.2),0 0 0 20px rgba(16,185,129,.05); } }
    .ns-map-overlay-info { position:absolute; bottom:16px; left:16px; background:rgba(0,0,0,.65); backdrop-filter:blur(10px); border-radius:10px; padding:10px 14px; display:flex; flex-direction:column; gap:4px; z-index:3; }
    .ns-map-label { font-size:12px; font-weight:700; color:var(--brand); }
    .ns-map-coords { font-size:11px; color:rgba(255,255,255,.7); font-family:monospace; }

    .ns-tracker-controls { }
    .ns-tracker-stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:12px; margin-top:16px; }
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
    
    .ns-feedback-submit { justify-content:center; display:inline-flex; width:auto; border-radius:6px; background:#2d3748; color:#ffffff; border:none; padding:10px 24px; font-weight:500; font-size:14px; cursor:pointer; }
    .ns-feedback-submit:hover { background:#1e293b; }

    /* ── Responsive ─────────────────────────────────────────── */
    @media (max-width: 1050px) {
        .ns-detail-layout { grid-template-columns:1fr; }
        .ns-info-tiles { grid-template-columns:repeat(2,1fr); }
        .ns-jumpoff { grid-template-columns:1fr; }
        .ns-guides-grid { grid-template-columns:repeat(2,1fr); }
        .ns-booking-layout { grid-template-columns:1fr; }
        .ns-tracker-layout { grid-template-columns:1fr; }
        .ns-checklist-grid { grid-template-columns:1fr; }
        .ns-history-stats { gap:12px; }
        .ns-form-row-2 { grid-template-columns:1fr; }
    }

    @media (max-width: 640px) {
        .ns-guides-grid { grid-template-columns:1fr; }
        .ns-info-tiles { grid-template-columns:1fr 1fr; }
        .ns-tracker-stats { grid-template-columns:1fr; }
        .ns-page-header { flex-direction:column; align-items:stretch; }
        .ns-history-stats { justify-content:space-around; }
        .ns-booking-card { flex-direction:column; align-items:flex-start; }
        .ns-booking-right { width:100%; justify-content:flex-end; }
    }
</style>
