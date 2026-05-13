{{--
    JS controller for the notification bell + history view-section.

    Expects:
        - .hc-bell element rendered by partials._notification-bell
        - #view-notifications view-section in the page (provided by partials._notification-history)

    The script polls the bell endpoint every 60s while the dashboard is
    visible so unread counts update without a full page reload.
--}}
<script>
(function () {
    const bell = document.querySelector('[data-hc-bell]');
    if (!bell) return;

    const badge = bell.querySelector('[data-hc-bell-badge]');
    const list = bell.querySelector('[data-hc-bell-list]');
    const empty = bell.querySelector('[data-hc-bell-empty]');
    const markAllBtn = bell.querySelector('[data-hc-bell-mark-all]');
    const popLink = bell.querySelector('[data-hc-bell-link]');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const ENDPOINTS = {
        bell: @json(route('notifications.bell')),
        index: @json(route('notifications.index')),
        markAll: @json(route('notifications.mark-all-read')),
        markRead: @json(url('/notifications')) + '/{id}/read',
    };

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function renderBellItems(items) {
        if (!list) return;
        list.querySelectorAll('.hc-bell-item').forEach(el => el.remove());
        if (!items || items.length === 0) {
            if (empty) empty.style.display = '';
            return;
        }
        if (empty) empty.style.display = 'none';
        items.forEach(item => {
            const a = document.createElement('a');
            a.className = 'hc-bell-item' + (item.is_unread ? ' is-unread' : '');
            a.href = item.link || '#';
            a.dataset.id = item.id;
            a.innerHTML =
                '<div class="hc-bell-item-icon"><iconify-icon icon="' + escapeHtml(item.icon || 'lucide:bell') + '"></iconify-icon></div>' +
                '<div class="hc-bell-item-body">' +
                    '<div class="hc-bell-item-title">' + escapeHtml(item.title) + '</div>' +
                    (item.body ? '<div class="hc-bell-item-text">' + escapeHtml(item.body) + '</div>' : '') +
                    '<div class="hc-bell-item-time">' + escapeHtml(item.created_at_human || '') + '</div>' +
                '</div>';
            a.addEventListener('click', function (ev) {
                if (item.is_unread) markRead(item.id);
                if (!item.link) ev.preventDefault();
            });
            list.insertBefore(a, empty);
        });
    }

    function setBadge(count) {
        if (!badge) return;
        const n = Number(count) || 0;
        if (n > 0) {
            badge.hidden = false;
            badge.textContent = n > 99 ? '99+' : String(n);
        } else {
            badge.hidden = true;
        }
    }

    function refreshBell() {
        fetch(ENDPOINTS.bell, { headers: { Accept: 'application/json' }, credentials: 'same-origin' })
            .then(r => r.ok ? r.json() : Promise.reject(r.status))
            .then(data => {
                if (!data?.success) return;
                setBadge(data.unread_count);
                renderBellItems(data.items || []);
            })
            .catch(() => {});
    }

    function markRead(id) {
        fetch(ENDPOINTS.markRead.replace('{id}', encodeURIComponent(id)), {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(r => r.json())
            .then(d => { if (d?.success) setBadge(d.unread_count); refreshBell(); })
            .catch(() => {});
    }

    function markAll() {
        fetch(ENDPOINTS.markAll, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(r => r.json())
            .then(d => { if (d?.success) { setBadge(0); refreshBell(); refreshHistory(); } })
            .catch(() => {});
    }

    if (markAllBtn) markAllBtn.addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); markAll(); });

    bell.addEventListener('click', function (e) {
        // The bell is itself a link to #notifications. Only toggle the popup
        // when the user taps the bell area — clicking an item or the footer
        // link should keep its native navigation.
        const item = e.target.closest('.hc-bell-item, .hc-bell-foot, .hc-bell-mark-all');
        if (item) return;
        e.preventDefault();
        bell.classList.toggle('is-open');
        if (bell.classList.contains('is-open')) refreshBell();
    });

    document.addEventListener('click', function (e) {
        if (!bell.contains(e.target)) bell.classList.remove('is-open');
    });

    // ─────────────────── History view-section ───────────────────
    const historyView = document.getElementById('view-notifications');
    let historyState = { page: 0, lastPage: 1 };

    function renderHistoryItems(items, append) {
        const grid = historyView?.querySelector('[data-hc-notif-list]');
        const emptyMsg = historyView?.querySelector('[data-hc-notif-empty]');
        if (!grid) return;
        if (!append) grid.innerHTML = '';

        if (!items || items.length === 0) {
            if (!append && emptyMsg) emptyMsg.style.display = '';
            return;
        }
        if (emptyMsg) emptyMsg.style.display = 'none';

        items.forEach(n => {
            const card = document.createElement('div');
            card.className = 'hc-notif-card' + (n.is_unread ? ' is-unread' : '');
            card.dataset.id = n.id;
            card.innerHTML =
                '<div class="hc-notif-icon"><iconify-icon icon="' + escapeHtml(n.icon || 'lucide:bell') + '"></iconify-icon></div>' +
                '<div class="hc-notif-body">' +
                    '<h4>' + escapeHtml(n.title) + '</h4>' +
                    (n.body ? '<p>' + escapeHtml(n.body) + '</p>' : '') +
                    '<div class="hc-notif-meta">' +
                        escapeHtml(n.created_at_human || '') +
                        (n.link ? ' · <a href="' + escapeHtml(n.link) + '" style="color:var(--brand-dark,#065f46);font-weight:700;text-decoration:none;">Open</a>' : '') +
                    '</div>' +
                '</div>';
            card.addEventListener('click', function (e) {
                if (e.target.closest('a')) return;
                if (n.is_unread) markRead(n.id);
                card.classList.remove('is-unread');
            });
            grid.appendChild(card);
        });
    }

    function refreshHistory(append) {
        if (!historyView) return;
        const page = append ? historyState.page + 1 : 1;
        fetch(ENDPOINTS.index + '?page=' + page, { headers: { Accept: 'application/json' }, credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                if (!data?.success) return;
                historyState = {
                    page: data.pagination?.current_page || 1,
                    lastPage: data.pagination?.last_page || 1,
                };
                renderHistoryItems(data.items, append);
                const loadMore = historyView.querySelector('[data-hc-notif-loadmore]');
                if (loadMore) loadMore.style.display = data.pagination?.has_more ? '' : 'none';
            })
            .catch(() => {});
    }

    if (historyView) {
        const refreshBtn = historyView.querySelector('[data-hc-notif-refresh]');
        if (refreshBtn) refreshBtn.addEventListener('click', () => refreshHistory(false));
        const markAllInHistory = historyView.querySelector('[data-hc-notif-mark-all]');
        if (markAllInHistory) markAllInHistory.addEventListener('click', markAll);
        const loadMoreBtn = historyView.querySelector('[data-hc-notif-loadmore]');
        if (loadMoreBtn) loadMoreBtn.addEventListener('click', () => refreshHistory(true));
    }

    // Refresh history when its tab becomes visible (hash change).
    function maybeRefreshOnRoute() {
        if ((location.hash || '').replace(/^#/, '') === 'notifications') refreshHistory(false);
    }
    window.addEventListener('hashchange', maybeRefreshOnRoute);

    // Initial load + soft polling.
    refreshBell();
    maybeRefreshOnRoute();
    setInterval(function () {
        if (document.visibilityState === 'visible') refreshBell();
    }, 60_000);
})();
</script>
