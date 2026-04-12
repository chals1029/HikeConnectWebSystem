{{-- ============================================================
     HikeConnect — New Hiker Dashboard Sections
     All sections follow view-section pattern for SPA navigation
     ============================================================ --}}

{{-- ==================== MOUNTAIN DETAIL ==================== --}}
@php
    $d = $trailMountain;
    $detailGuides = $d
        ? $guides->filter(fn ($g) => $g->status === 'available' && (! $g->mountain_id || $g->mountain_id === $d->id))
        : collect();
@endphp
<div class="view-section" id="view-mountain-detail">
    <button class="ns-back-btn" onclick="showView('#mountain-overview')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Mountains
    </button>

    <div class="ns-detail-hero" id="detail-hero" style="{{ $d ? 'background-image: url(\''.e(asset($d->image_path)).'\');' : 'background:linear-gradient(135deg,#0f172a,#334155);' }}">
        <div class="ns-detail-hero-overlay">
            @php $st = $d?->status ?? 'open'; @endphp
            <span class="ns-status-pill {{ $st }}" id="detail-status"><iconify-icon icon="lucide:circle" style="vertical-align:text-bottom; margin-right:2px; font-size:10px;"></iconify-icon> {{ $st === 'open' ? 'Open' : 'Closed' }}</span>
            <h2 id="detail-name">{{ $d?->name ?? 'Mountain details' }}</h2>
            <span class="ns-diff-pill" id="detail-diff">{{ $d?->difficulty ?? '—' }}</span>
        </div>
    </div>

    <div class="ns-detail-layout">
        <div class="ns-detail-main">
            {{-- About --}}
            <div class="card">
                <h3>About This Mountain</h3>
                <p id="detail-full-desc" style="font-size:14px;color:var(--muted);line-height:1.8;">{{ $d?->full_description ?? '' }}</p>
            </div>

            {{-- Info Tiles --}}
            <div class="ns-info-tiles">
                <div class="ns-tile">
                    <div class="ns-tile-icon"><iconify-icon icon="lucide:mountain" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon></div>
                    <span class="ns-tile-label">Elevation</span>
                    <strong id="detail-elevation">{{ $d?->elevation_label ?? '—' }}</strong>
                </div>
                <div class="ns-tile">
                    <div class="ns-tile-icon"><iconify-icon icon="lucide:clock" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon></div>
                    <span class="ns-tile-label">Duration</span>
                    <strong id="detail-duration">{{ $d?->duration_label ?? '—' }}</strong>
                </div>
                <div class="ns-tile">
                    <div class="ns-tile-icon"><iconify-icon icon="lucide:footprints" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon></div>
                    <span class="ns-tile-label">Trail Type</span>
                    <strong id="detail-trail-type">{{ $d?->trail_type_label ?? '—' }}</strong>
                </div>
                <div class="ns-tile">
                    <div class="ns-tile-icon">☀️</div>
                    <span class="ns-tile-label">Best Time</span>
                    <strong id="detail-best-time">{{ $d?->best_time_label ?? '—' }}</strong>
                </div>
            </div>

            {{-- Jump-off Point --}}
            <div class="card">
                <h3><iconify-icon icon="lucide:map-pin" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> Jump-off Point</h3>
                <div class="ns-jumpoff">
                    <div class="ns-jumpoff-map">
                        <div id="detail-jumpoff-gmap" style="width:100%;height:100%;min-height:220px;border-radius:12px;"></div>
                        <div class="ns-jumpoff-map-info">
                            <strong id="detail-jumpoff-name">{{ $d?->jumpoff_name ?? '—' }}</strong>
                            <span id="detail-jumpoff-address" style="font-size:12px;color:var(--muted);">{{ $d?->jumpoff_address ?? '' }}</span>
                        </div>
                    </div>
                    <div class="ns-jumpoff-info">
                        <div class="ns-jumpoff-row">
                            <strong>Meeting Time</strong>
                            <span id="detail-meeting-time">{{ $d?->jumpoff_meeting_time ?? '—' }}</span>
                        </div>
                        <div class="ns-jumpoff-row">
                            <strong>Guide Notes</strong>
                            <span id="detail-jumpoff-notes">{{ $d?->jumpoff_notes ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recommended Gear --}}
            <div class="card">
                <h3><iconify-icon icon="lucide:briefcase" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> What to Bring</h3>
                <div class="ns-gear-tags" id="detail-gear-tags">
                    @if($d && ! empty($d->gear))
                        @foreach($d->gear as $tag)
                        <span class="ns-gear-tag">{{ $tag }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="ns-detail-side">
            {{-- Available Guides --}}
            <div class="card">
                <h3 style="margin-bottom:16px;">Available Tour Guides</h3>
                <div class="ns-detail-guides" id="detail-guides-list">
                    @forelse($detailGuides as $g)
                    <div class="ns-detail-guide-mini" onclick="bookWithGuide('{{ $g->id }}')">
                        <div class="ns-guide-avatar" style="background:{{ $g->avatar_gradient }};">{{ $g->initials }}</div>
                        <div><h5>{{ $g->full_name }}</h5><span>{{ $g->mountain?->name ?? 'All Mountains' }}</span></div>
                    </div>
                    @empty
                    <p style="color:var(--muted);font-size:13px;">{{ $d ? 'No available guides for this mountain right now.' : 'Add mountains in the database, then open a trail from the overview.' }}</p>
                    @endforelse
                </div>
            </div>
            <button class="ns-book-cta" onclick="bookFromDetail()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Book This Mountain
            </button>
        </div>
    </div>
</div>

{{-- ==================== TOUR GUIDES ======================== --}}
<div class="view-section" id="view-tour-guides">
    <div class="ns-page-header">
        <div>
            <h2>Tour Guides</h2>
            <p style="color:var(--muted);font-size:14px;margin-top:4px;">Find experienced local guides for a safe and memorable hike.</p>
        </div>
    </div>

    <div class="ns-filter-bar">
        <label class="ns-search-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
            <input type="text" placeholder="Search by name or mountain..." id="guide-search-input" oninput="filterGuides()">
        </label>
        <select id="guide-status-filter" class="ns-form-select" onchange="filterGuides()">
            <option value="all">All Status</option>
            <option value="available">Available</option>
            <option value="on-hike">On Hike</option>
            <option value="unavailable">Unavailable</option>
            <option value="off-duty">Off Duty</option>
        </select>
    </div>

    <div class="ns-guides-grid" id="guides-grid">
        @foreach($guides as $g)
        <div class="ns-guide-card" data-guide="{{ $g->id }}" data-status="{{ $g->status }}" data-mountain="{{ $g->mountain?->slug ?? 'all' }}">
            <div class="ns-guide-top">
                <div class="ns-guide-avatar" style="background:{{ $g->avatar_gradient }};">{{ strtoupper(substr($g->first_name,0,1).substr($g->last_name,0,1)) }}</div>
                <div class="ns-guide-meta">
                    <h4>{{ $g->full_name }}</h4>
                    <span class="ns-guide-spec">{{ $g->specialty }}</span>
                </div>
                <span class="ns-avail-badge {{ $g->status }}">{{ str_replace('-', ' ', ucfirst($g->status)) }}</span>
            </div>
            <div class="ns-guide-details">
                <div class="ns-guide-row"><span><iconify-icon icon="lucide:smartphone" style="margin-right:4px; vertical-align: text-bottom;"></iconify-icon> Contact</span><strong>{{ $g->phone }}</strong></div>
                <div class="ns-guide-row"><span><iconify-icon icon="lucide:star" style="margin-right:4px; vertical-align: text-bottom;"></iconify-icon> Experience</span><strong>{{ $g->experience_years }} years</strong></div>
                <div class="ns-guide-row"><span><iconify-icon icon="lucide:mountain" style="margin-right:4px; vertical-align: text-bottom;"></iconify-icon> Mountain</span><strong>{{ $g->mountain?->name ?? 'All Mountains' }}</strong></div>
            </div>
            @if($g->status === 'available')
            <button type="button" class="ns-guide-book-btn" onclick="bookWithGuide({{ $g->id }})">Book with {{ $g->first_name }}</button>
            @elseif($g->status === 'on-hike')
            <button type="button" class="ns-guide-book-btn" disabled>Currently on Hike</button>
            @elseif($g->status === 'unavailable')
            <button type="button" class="ns-guide-book-btn" disabled>Unavailable</button>
            @elseif($g->status === 'off-duty')
            <button type="button" class="ns-guide-book-btn" disabled>Off Duty</button>
            @else
            <button type="button" class="ns-guide-book-btn" disabled>Unavailable</button>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- ==================== BOOK A HIKE ======================== --}}
<div class="view-section" id="view-book-hike">
    <div class="ns-page-header">
        <div>
            <h2>Book a Hike</h2>
            <p style="color:var(--muted);font-size:14px;margin-top:4px;">Fill out the form to send a booking request.</p>
        </div>
    </div>

    <div class="ns-booking-layout">
        <div class="card ns-booking-form-card">
            <h3>Booking Details</h3>
            <form id="booking-form" onsubmit="submitBooking(event)">
                <div class="ns-form-group">
                    <label class="ns-form-label">Selected Mountain *</label>
                    <select id="book-mountain" name="mountain" class="ns-form-select" required onchange="updateGuideOptions(); updateBookingPreview();">
                        <option value="">Choose a mountain...</option>
                        @foreach($mountains as $m)
                        <option value="{{ $m->slug }}">{{ $m->name }} — {{ $m->location }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ns-form-row-2">
                    <div class="ns-form-group">
                        <label class="ns-form-label">Hike Date *</label>
                        <input type="date" id="book-date" name="hike_on" class="ns-form-input" required onchange="updateBookingPreview()">
                    </div>
                    <div class="ns-form-group">
                        <label class="ns-form-label">Number of Hikers *</label>
                        <input type="number" id="book-hikers" name="hikers_count" class="ns-form-input" min="1" max="20" value="1" required onchange="updateBookingPreview()">
                    </div>
                </div>
                <div class="ns-form-group">
                    <label class="ns-form-label">Preferred Tour Guide *</label>
                    <select id="book-guide" name="tour_guide_id" class="ns-form-select" required onchange="updateBookingPreview()">
                        <option value="">Select a mountain first...</option>
                    </select>
                    <small style="color:var(--muted);font-size:11px;margin-top:4px;display:block;">Only available guides for the selected mountain are shown.</small>
                </div>
                <div class="ns-form-group">
                    <label class="ns-form-label">Notes (optional)</label>
                    <textarea id="book-notes" name="notes" class="ns-form-textarea" rows="3" placeholder="Any special requests or requirements..."></textarea>
                </div>
                <button type="submit" class="ns-submit-btn" id="booking-submit-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"></path><path d="M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
                    Submit Booking Request
                </button>
            </form>
        </div>

        <div class="ns-booking-preview card">
            <h3>Booking Summary</h3>
            <div id="booking-preview-content">
                <div class="ns-preview-empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--line)" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <p>Fill out the form to see a preview of your booking.</p>
                </div>
            </div>
            <div class="ns-status-legend">
                <h4 style="font-size:12px;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">Status Guide</h4>
                <div class="ns-legend-items">
                    <span class="ns-legend-item"><span class="ns-dot pending"></span>Pending</span>
                    <span class="ns-legend-item"><span class="ns-dot approved"></span>Approved</span>
                    <span class="ns-legend-item"><span class="ns-dot rejected"></span>Rejected</span>
                    <span class="ns-legend-item"><span class="ns-dot completed"></span>Completed</span>
                    <span class="ns-legend-item"><span class="ns-dot cancelled"></span>Cancelled</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message (hidden by default) --}}
    <div class="ns-booking-success" id="booking-success" style="display:none;">
        <div class="ns-success-card">
            <div class="ns-success-icon"><iconify-icon icon="lucide:check-circle" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon></div>
            <h3>Booking Request Sent!</h3>
            <p>Your booking is now <strong>Pending</strong> approval. You'll be notified once your guide confirms.</p>
            <div class="ns-success-actions">
                <button class="ns-submit-btn" onclick="showView('#bookings')">View My Bookings</button>
                <button class="ns-back-btn" onclick="resetBookingForm()" style="margin-top:8px;">Book Another Hike</button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MY BOOKINGS ======================== --}}
<div class="view-section" id="view-bookings">
    <div class="ns-page-header">
        <div>
            <h2>My Bookings</h2>
            <p style="color:var(--muted);font-size:14px;margin-top:4px;">Manage your current and past booking requests.</p>
        </div>
        <button class="ns-action-btn" onclick="showView('#book-hike')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Booking
        </button>
    </div>

    <div class="ns-tabs">
        <button class="ns-tab active" onclick="filterBookings('all', this)">All</button>
        <button class="ns-tab" onclick="filterBookings('upcoming', this)">Upcoming</button>
        <button class="ns-tab" onclick="filterBookings('past', this)">Past</button>
        <button class="ns-tab" onclick="filterBookings('cancelled', this)">Cancelled</button>
    </div>

    <div class="ns-bookings-list" id="bookings-list">
        @forelse($bookings as $booking)
        @php
            $stClass = match($booking->status) {
                'approved' => 'approved',
                'pending' => 'pending',
                'completed' => 'completed',
                'cancelled' => 'cancelled',
                'rejected' => 'rejected',
                default => 'pending',
            };
        @endphp
        <div class="ns-booking-card" data-booking-type="{{ $booking->ui_tab }}" data-booking-id="{{ $booking->id }}">
            <div class="ns-booking-left">
                <div class="ns-booking-mountain-icon" style="background:linear-gradient(135deg,#065f46,#10b981);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="m8 3 4 8 5-5 5 15H2L8 3z"></path></svg>
                </div>
                <div class="ns-booking-info">
                    <h4>{{ $booking->mountain->name }}</h4>
                    <div class="ns-booking-meta">
                        <span><iconify-icon icon="lucide:user" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> {{ $booking->tourGuide->full_name }}</span>
                        <span><iconify-icon icon="lucide:calendar" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> {{ $booking->hike_on->format('F j, Y') }}</span>
                        <span><iconify-icon icon="lucide:map-pin" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> {{ $booking->mountain->jumpoff_name }}</span>
                        <span><iconify-icon icon="lucide:footprints" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> {{ $booking->hikers_count }} {{ $booking->hikers_count === 1 ? 'hiker' : 'hikers' }}</span>
                    </div>
                </div>
            </div>
            <div class="ns-booking-right">
                <span class="ns-booking-status {{ $stClass }}">{{ ucfirst($booking->status) }}</span>
                @if($booking->ui_tab === 'upcoming' && in_array($booking->status, ['pending', 'approved'], true))
                <button type="button" class="ns-cancel-btn" onclick="cancelBooking(this)">Cancel</button>
                @endif
                @if($booking->status === 'completed')
                <button type="button" class="ns-feedback-btn" onclick="showView('#reviews')">Leave Feedback</button>
                @endif
            </div>
        </div>
        @empty
        <p style="padding:16px;color:var(--muted);">No bookings yet. Create one from Book a Hike.</p>
        @endforelse
    </div>
</div>

{{-- ==================== TRACK MY LOCATION ================== --}}
<div class="view-section" id="view-track-location">
    <div class="ns-page-header">
        <div>
            <h2>Track My Location</h2>
            <p style="color:var(--muted);font-size:14px;margin-top:4px;">Start <strong>live tracking</strong> while you hike: your position updates on the map and a trail line shows where you’ve been. Stop when you’re done to save battery.</p>
        </div>
    </div>

    <div class="ns-tracker-layout">
        <div class="ns-tracker-map-area">
            <div id="tracker-gmap" style="width:100%;height:380px;border-radius:16px;overflow:hidden;"></div>
            <div class="ns-tracker-controls">
                <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                    <button type="button" class="ns-submit-btn" onclick="toggleLiveTracking()" id="track-btn" aria-pressed="false">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span id="track-btn-label">Start live tracking</span>
                    </button>
                    <button type="button" class="ns-action-btn" onclick="clearHikeTrail()" id="track-clear-btn" title="Clear the blue trail from the map" style="display:none;">Clear trail</button>
                </div>
                <p id="tracker-hint" style="font-size:12px;color:var(--muted);margin:8px 0 0;">Uses GPS. For best results allow location access and keep the tab open. Tracking uses more battery while active.</p>
                <div class="ns-tracker-stats">
                    <div class="ns-tracker-stat">
                        <span>From jump-off</span>
                        <strong id="tracker-distance">-- km</strong>
                    </div>
                    <div class="ns-tracker-stat">
                        <span>Trail length</span>
                        <strong id="tracker-trail-km">-- km</strong>
                    </div>
                    <div class="ns-tracker-stat">
                        <span>Altitude</span>
                        <strong id="tracker-altitude">-- m</strong>
                    </div>
                    <div class="ns-tracker-stat">
                        <span>GPS accuracy</span>
                        <strong id="tracker-accuracy">--</strong>
                    </div>
                    <div class="ns-tracker-stat">
                        <span>Last update</span>
                        <strong id="tracker-last-fix">--</strong>
                    </div>
                    <div class="ns-tracker-stat">
                        <span>Status</span>
                        <strong id="tracker-status" class="ns-tracker-status-text">Ready</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="ns-tracker-sidebar">
            <div class="card">
                <h3><iconify-icon icon="lucide:shield" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> Safety Info</h3>
                <div class="ns-safety-list">
                    <div class="ns-safety-item">
                        <strong>Stay on marked trails</strong>
                        <p>Follow established paths and trail markers at all times.</p>
                    </div>
                    <div class="ns-safety-item">
                        <strong>Share your location</strong>
                        <p>Let your guide know your position if you fall behind.</p>
                    </div>
                    <div class="ns-safety-item">
                        <strong>Emergency Contact</strong>
                        <p>Local Rescue: <strong>{{ $safetyEmergency !== '' ? $safetyEmergency : '—' }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="card">
                <h3><iconify-icon icon="lucide:signal" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> Signal Status</h3>
                <div style="text-align:center;padding:16px 0;">
                    <div class="ns-signal-bars">
                        <span class="ns-bar active"></span>
                        <span class="ns-bar active"></span>
                        <span class="ns-bar active"></span>
                        <span class="ns-bar"></span>
                    </div>
                    <p style="font-size:12px;color:var(--muted);margin-top:8px;">Good Signal</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== WHAT TO BRING ====================== --}}
<div class="view-section" id="view-what-to-bring">
    <div class="ns-page-header">
        <div>
            <h2>What to Bring</h2>
            <p style="color:var(--muted);font-size:14px;margin-top:4px;">Your essential hiking checklist. Check items off as you pack!</p>
        </div>
        <div class="ns-progress-wrap">
            <span id="checklist-progress-text">0 / {{ $packingItems->count() }} packed</span>
            <div class="ns-progress-bar"><div class="ns-progress-fill" id="checklist-progress-bar" style="width:0%"></div></div>
        </div>
    </div>

    <div class="ns-checklist-grid">
        @foreach($packingItems->groupBy('category') as $category => $items)
        <div class="card ns-checklist-category">
            <h3>{{ $category }}</h3>
            @foreach($items as $item)
            <label class="ns-check-item"><input type="checkbox" class="ns-checkbox" data-item="{{ $item->slug }}" onchange="updateChecklist()"><span>{{ $item->label }}</span></label>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

{{-- ==================== HIKING HISTORY ===================== --}}
<div class="view-section" id="view-hiking-history">
    <div class="ns-page-header">
        <div>
            <h2>Hiking History</h2>
            <p style="color:var(--muted);font-size:14px;margin-top:4px;">Your completed mountain adventures.</p>
        </div>
        <div class="ns-history-stats">
            <div class="ns-mini-stat"><strong>{{ $completedHistory->count() }}</strong><span>Hikes</span></div>
            <div class="ns-mini-stat"><strong>{{ number_format($completedHistory->sum(fn ($b) => $b->mountain->elevation_meters)) }}</strong><span>MASL Total</span></div>
            <div class="ns-mini-stat"><strong>{{ (int) round($completedHistory->sum(fn ($b) => $b->duration_hours ?? 4)) }}</strong><span>Hours</span></div>
        </div>
    </div>

    <div class="ns-history-timeline">
        @forelse($completedHistory as $booking)
        <div class="ns-history-item">
            <div class="ns-history-dot completed-dot"></div>
            <div class="ns-history-card card">
                <div class="ns-history-top">
                    <div>
                        <h4>{{ $booking->mountain->name }}</h4>
                        <span class="ns-history-date">{{ $booking->hike_on->format('F j, Y') }}</span>
                    </div>
                    <span class="ns-booking-status completed">Completed</span>
                </div>
                <div class="ns-history-meta">
                    <span><iconify-icon icon="lucide:user" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> Guide: {{ $booking->tourGuide->full_name }}</span>
                    <span><iconify-icon icon="lucide:clock" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> Duration: {{ $booking->duration_hours ?? '—' }} hours</span>
                    <span><iconify-icon icon="lucide:mountain" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> {{ $booking->mountain->elevation_label }}</span>
                </div>
                @if($booking->rating)
                <div class="ns-history-rating">
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                        <iconify-icon icon="lucide:star" style="vertical-align:text-bottom; color:{{ $i <= $booking->rating ? '#f59e0b' : '#cbd5e1' }};"></iconify-icon>
                        @endfor
                    </span>
                    @if($booking->review_text)<span style="color:var(--muted);font-size:12px;margin-left:6px;">"{{ $booking->review_text }}"</span>@endif
                </div>
                @endif
            </div>
        </div>
        @empty
        <p style="padding:16px;color:var(--muted);">Complete a hike to build your history here.</p>
        @endforelse
    </div>
</div>
