{{--
    Notification bell + dropdown (5 most recent) and the matching
    "Notifications" view-section that holds the full history.

    Drop this partial into any dashboard layout that already has the standard
    .layout / .sidebar / .menu-item structure. The bell mounts into the
    sidebar-top header; the view-section renders inside <main> when a user
    clicks the bell or the "Notifications" sidebar link.

    Usage:
        @include('partials._notification-bell', ['homeHash' => 'home', 'notificationsHash' => 'notifications'])

    Both keys are optional.
--}}
@php
    $bellHash = $notificationsHash ?? 'notifications';
@endphp

<a href="#{{ $bellHash }}" class="hc-bell" data-hc-bell title="Notifications" aria-label="Notifications">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
    </svg>
    <span class="hc-bell-badge" data-hc-bell-badge hidden>0</span>
    <div class="hc-bell-pop" data-hc-bell-pop role="dialog" aria-label="Recent notifications">
        <div class="hc-bell-pop-head">
            <strong>Notifications</strong>
            <button type="button" class="hc-bell-mark-all" data-hc-bell-mark-all>Mark all read</button>
        </div>
        <div class="hc-bell-list" data-hc-bell-list>
            <div class="hc-bell-empty" data-hc-bell-empty>You're all caught up.</div>
        </div>
        <a href="#{{ $bellHash }}" class="hc-bell-foot" data-hc-bell-link>See all notifications →</a>
    </div>
</a>

<style>
    .hc-bell {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px; height: 36px;
        margin-left: auto;
        flex-shrink: 0;
        border: 1px solid var(--line, #e5eee8);
        border-radius: 10px;
        color: var(--muted, #5f6d64);
        background: var(--panel, #fff);
        cursor: pointer;
        text-decoration: none;
        transition: color .2s ease, background .2s ease, border-color .2s ease;
    }
    .hc-bell:hover { color: var(--text, #122018); border-color: var(--brand-dark, #065f46); background: var(--brand-soft, #e8fbf4); }
    .hc-bell.is-open { color: var(--text, #122018); border-color: var(--brand-dark, #065f46); background: var(--brand-soft, #e8fbf4); }
    .hc-bell-badge {
        position: absolute;
        top: -6px; right: -6px;
        min-width: 18px; height: 18px;
        padding: 0 4px;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        line-height: 1;
        border: 2px solid var(--panel, #fff);
    }
    .hc-bell-pop {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 320px;
        max-width: calc(100vw - 24px);
        background: var(--panel, #fff);
        border: 1px solid var(--line, #e5eee8);
        border-radius: 14px;
        box-shadow: 0 24px 48px rgba(15, 23, 21, 0.18);
        padding: 10px;
        display: none;
        z-index: 1500;
        text-align: left;
    }
    .hc-bell.is-open .hc-bell-pop { display: block; }
    .hc-bell-pop-head { display: flex; align-items: center; justify-content: space-between; padding: 4px 6px 8px; border-bottom: 1px solid var(--line, #e5eee8); }
    .hc-bell-pop-head strong { font-size: 13px; color: var(--text, #122018); }
    .hc-bell-mark-all { background: transparent; border: none; color: var(--brand-dark, #065f46); font-size: 11px; font-weight: 700; cursor: pointer; padding: 4px 6px; border-radius: 6px; }
    .hc-bell-mark-all:hover { background: var(--brand-soft, #e8fbf4); }
    .hc-bell-list { max-height: 320px; overflow-y: auto; padding: 4px 0; -webkit-overflow-scrolling: touch; }
    .hc-bell-item {
        display: flex;
        gap: 10px;
        padding: 10px;
        border-radius: 10px;
        text-decoration: none;
        color: inherit;
        transition: background .15s ease;
        cursor: pointer;
    }
    .hc-bell-item:hover { background: var(--bg, #f6fbf8); }
    .hc-bell-item.is-unread { background: rgba(16, 185, 129, 0.06); }
    .hc-bell-item-icon { width: 32px; height: 32px; flex-shrink: 0; border-radius: 8px; display: grid; place-items: center; background: var(--brand-soft, #e8fbf4); color: var(--brand-dark, #065f46); }
    .hc-bell-item-icon iconify-icon { font-size: 16px; }
    .hc-bell-item-body { flex: 1; min-width: 0; }
    .hc-bell-item-title { font-size: 12.5px; font-weight: 700; color: var(--text, #122018); margin-bottom: 2px; line-height: 1.35; }
    .hc-bell-item-text { font-size: 12px; color: var(--muted, #5f6d64); line-height: 1.45; word-wrap: break-word; }
    .hc-bell-item-time { font-size: 10px; color: var(--muted, #5f6d64); margin-top: 4px; }
    .hc-bell-empty { text-align: center; padding: 18px 8px; font-size: 12px; color: var(--muted, #5f6d64); }
    .hc-bell-foot { display: block; text-align: center; font-size: 12px; font-weight: 700; padding: 8px 6px 4px; color: var(--brand-dark, #065f46); text-decoration: none; border-top: 1px solid var(--line, #e5eee8); margin-top: 4px; }
    .hc-bell-foot:hover { background: var(--brand-soft, #e8fbf4); border-radius: 8px; }

    /* Notifications view-section */
    #view-notifications .hc-notif-card {
        background: var(--panel, #fff);
        border: 1px solid var(--line, #e5eee8);
        border-radius: 16px;
        padding: 16px;
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 10px;
        transition: box-shadow .2s ease;
    }
    #view-notifications .hc-notif-card.is-unread { border-color: rgba(16, 185, 129, 0.4); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08); }
    #view-notifications .hc-notif-icon { width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; display: grid; place-items: center; background: var(--brand-soft, #e8fbf4); color: var(--brand-dark, #065f46); }
    #view-notifications .hc-notif-body h4 { font-size: 14px; font-weight: 800; color: var(--text, #122018); margin-bottom: 4px; }
    #view-notifications .hc-notif-body p { font-size: 13px; color: var(--muted, #5f6d64); line-height: 1.5; }
    #view-notifications .hc-notif-meta { font-size: 11px; color: var(--muted, #5f6d64); margin-top: 6px; }
    #view-notifications .hc-notif-empty { text-align: center; padding: 40px 16px; color: var(--muted, #5f6d64); font-size: 14px; }
    #view-notifications .hc-notif-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 12px; flex-wrap: wrap; }
    #view-notifications .hc-notif-toolbar button { background: var(--panel, #fff); color: var(--brand-dark, #065f46); border: 1px solid var(--line, #e5eee8); border-radius: 10px; padding: 8px 14px; font-size: 12px; font-weight: 700; cursor: pointer; }
    #view-notifications .hc-notif-toolbar button:hover { background: var(--brand-soft, #e8fbf4); }
    #view-notifications .hc-notif-loadmore { width: 100%; margin-top: 8px; padding: 12px; background: var(--panel, #fff); border: 1px dashed var(--line, #e5eee8); border-radius: 12px; color: var(--brand-dark, #065f46); font-weight: 700; cursor: pointer; }
    #view-notifications .hc-notif-loadmore:hover { background: var(--brand-soft, #e8fbf4); }

    @media (max-width: 640px) {
        .hc-bell-pop { width: 280px; right: -10px; }
    }
</style>
