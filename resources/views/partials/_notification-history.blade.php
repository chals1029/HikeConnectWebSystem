{{-- Notification history tab. Include inside <main> in any dashboard view. --}}
<div class="view-section" id="view-notifications">
    <header class="dashboard-header">
        <h2>Notifications</h2>
        <p>Booking updates, safety advisories, and SOS alerts you've received.</p>
    </header>
    <div class="hc-notif-toolbar">
        <button type="button" data-hc-notif-refresh>
            <iconify-icon icon="lucide:refresh-cw" style="vertical-align:text-bottom;margin-right:4px;"></iconify-icon>
            Refresh
        </button>
        <button type="button" data-hc-notif-mark-all>
            <iconify-icon icon="lucide:check-check" style="vertical-align:text-bottom;margin-right:4px;"></iconify-icon>
            Mark all as read
        </button>
    </div>
    <div data-hc-notif-list></div>
    <div class="hc-notif-empty" data-hc-notif-empty>You have no notifications yet.</div>
    <button type="button" class="hc-notif-loadmore" data-hc-notif-loadmore style="display:none;">
        Load more
    </button>
</div>
