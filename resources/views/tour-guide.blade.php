<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HikeConnect | Tour Guide</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @vite(['resources/css/tour-guide.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $statusOptions = [
            'available' => 'Available',
            'on-hike' => 'On a hike',
            'unavailable' => 'Unavailable',
            'off-duty' => 'Off duty',
        ];
        $avatarUrl = $user->profile_picture_url;
    @endphp

    <div class="layout">
        <div class="mobile-overlay" onclick="document.querySelector('.layout').classList.remove('mobile-open')"></div>

        <aside class="sidebar-wrapper">
            <div class="sidebar">
                <div class="sidebar-top">
                    <a href="{{ route('home') }}" class="brand" aria-label="HikeConnect Home">
                        <img src="{{ asset('images/HikeConnect-Logo.png') }}" class="brand-logo" alt="">
                        <span class="brand-name"><span class="brand-name__hike">Hike</span><span class="brand-name__connect">Connect</span></span>
                    </a>
                    <button class="sidebar-toggle" onclick="document.querySelector('.layout').classList.toggle('collapsed')" aria-label="Toggle Sidebar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="search-box">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Search bookings..." aria-label="Search">
                </div>

                <div class="menu-title">Menu</div>
                <a href="#" class="menu-item active">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span class="menu-text">Overview</span>
                </a>
                <a href="#bookings" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><path d="M16 2v4"></path><path d="M8 2v4"></path><path d="M3 10h18"></path></svg>
                    <span class="menu-text">Bookings</span>
                    @if($pending->count() > 0)<span class="menu-badge">{{ $pending->count() }}</span>@endif
                </a>
                <a href="#hikers" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="menu-text">Hikers</span>
                </a>
                <a href="#reviews" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <span class="menu-text">Reviews</span>
                    @if($stats['rating_count'] > 0)<span class="menu-badge">{{ $stats['rating_count'] }}</span>@endif
                </a>
                <a href="#profile" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="menu-text">My Profile</span>
                </a>
                <a href="#settings" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span class="menu-text">Account Settings</span>
                </a>

                <div class="group-title">Assigned</div>
                <div class="group-list">
                    @if($guide->mountain)
                        <div class="group-item"><span class="dot green"></span> <span class="group-item-text">{{ $guide->mountain->name }}</span></div>
                    @else
                        <div class="group-item"><span class="dot blue"></span> <span class="group-item-text">All Mountains</span></div>
                    @endif
                </div>

                <div class="sidebar-footer">
                    <div class="mode-toggle">
                        <button class="mode-pill active" id="mode-light">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                            <span>Light</span>
                        </button>
                        <button class="mode-pill" id="mode-dark">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                            <span>Dark</span>
                        </button>
                    </div>

                    <div class="profile">
                        <div class="avatar" id="sidebar-user-avatar" style="{{ $avatarUrl ? 'padding:0;' : '' }}">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="" width="40" height="40" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
                            @else
                                {{ substr($user->first_name ?? 'T', 0, 1) }}{{ substr($user->last_name ?? 'G', 0, 1) }}
                            @endif
                        </div>
                        <div class="profile-info">
                            <div class="profile-name">{{ $user->full_name }}</div>
                            <div class="profile-role">Tour Guide</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">
                            @csrf
                        </form>
                        <div class="profile-logout" onclick="document.getElementById('logout-form').submit();" style="cursor:pointer;" title="Log out">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="content">
            <div class="mobile-header">
                <a href="{{ route('home') }}" class="brand" aria-label="HikeConnect Home">
                    <img src="{{ asset('images/HikeConnect-Logo.png') }}" class="brand-logo" alt="">
                    <span class="brand-name"><span class="brand-name__hike">Hike</span><span class="brand-name__connect">Connect</span></span>
                </a>
                <button class="mobile-toggle" onclick="document.querySelector('.layout').classList.toggle('mobile-open')" aria-label="Open Menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- ============== OVERVIEW ============== --}}
            <div class="view-section active" id="view-dashboard">
                <header class="dashboard-header">
                    <div class="tg-topbar">
                        <div>
                            <h2>Welcome back, {{ $guide->first_name }}! <iconify-icon icon="lucide:compass" style="color:#10b981;font-size:24px;vertical-align:text-bottom;margin-left:4px;"></iconify-icon></h2>
                            <p>{{ $guide->specialty }} &middot; {{ $guide->experience_years }} {{ Str::plural('year', $guide->experience_years) }} guiding @if($guide->mountain) &middot; {{ $guide->mountain->name }} @endif</p>
                        </div>
                        <form id="availability-form" class="tg-availability" data-status="{{ $guide->status }}">
                            @csrf
                            <span class="tg-availability-dot"></span>
                            <span class="tg-availability-label">Status</span>
                            <select id="tgAvail" name="status">
                                @foreach ($statusOptions as $val => $label)
                                    <option value="{{ $val }}" @selected($guide->status === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </header>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><path d="M16 2v4"></path><path d="M8 2v4"></path><path d="M3 10h18"></path></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Total Bookings</h4>
                            <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Pending Approvals</h4>
                            <div class="stat-value"><a href="#bookings" style="color:inherit;text-decoration:none;">{{ $stats['pending'] }}</a></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Completed Hikes</h4>
                            <div class="stat-value">{{ $stats['completed'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Average Rating</h4>
                            <div class="stat-value">{{ $stats['rating'] !== null ? $stats['rating'].' / 5' : '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-main-grid">
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h3>Pending Approvals</h3>
                            <a href="#bookings">View all</a>
                        </div>
                        @include('tour-guide-partials.bookings-table', ['rows' => $pending->take(5), 'showActions' => 'pending'])
                    </div>

                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h3>Next Hike</h3>
                        </div>
                        @if ($upcoming)
                            <div class="upcoming-hike">
                                <span class="upcoming-hike-date">{{ $upcoming->hike_on->format('D, M j, Y') }}</span>
                                <h4>{{ $upcoming->mountain?->name ?? 'Mountain' }}</h4>
                                <div class="upcoming-hike-meta">
                                    <span><iconify-icon icon="lucide:users"></iconify-icon> {{ $upcoming->hikers_count }} {{ Str::plural('hiker', $upcoming->hikers_count) }}</span>
                                    <span><iconify-icon icon="lucide:circle-dot"></iconify-icon> {{ ucfirst($upcoming->status) }}</span>
                                </div>
                                <p class="tg-upcoming-meta" style="margin-bottom:14px;">
                                    Booked by <strong>{{ $upcoming->user?->full_name ?? 'a hiker' }}</strong>@if($upcoming->user?->phone) &middot; {{ $upcoming->user->phone }}@endif
                                </p>
                                @if ($upcoming->notes)
                                    <div class="tg-row-note" style="margin-top:0;margin-bottom:14px;">{{ $upcoming->notes }}</div>
                                @endif
                                <a href="#bookings" class="upcoming-btn">Manage in Bookings →</a>
                            </div>
                        @else
                            <div class="tg-empty">No upcoming hikes scheduled. New booking requests will appear here.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ============== BOOKINGS ============== --}}
            <div class="view-section" id="view-bookings">
                <header class="dashboard-header">
                    <h2>Bookings</h2>
                    <p>Approve, complete, or cancel hike requests assigned to you.</p>
                </header>

                <div class="dashboard-card" style="margin-bottom:18px;">
                    <div class="dashboard-card-header">
                        <h3>Pending</h3>
                        <span class="tg-pill">{{ $pending->count() }} waiting</span>
                    </div>
                    @include('tour-guide-partials.bookings-table', ['rows' => $pending, 'showActions' => 'pending'])
                </div>

                <div class="dashboard-card" style="margin-bottom:18px;">
                    <div class="dashboard-card-header">
                        <h3>Approved</h3>
                        <span class="tg-pill">{{ $approved->count() }} upcoming</span>
                    </div>
                    @include('tour-guide-partials.bookings-table', ['rows' => $approved, 'showActions' => 'approved'])
                </div>

                <div class="dashboard-card" style="margin-bottom:18px;">
                    <div class="dashboard-card-header">
                        <h3>Completed</h3>
                        <span class="tg-pill">{{ $completed->count() }} finished</span>
                    </div>
                    @include('tour-guide-partials.bookings-table', ['rows' => $completed, 'showActions' => 'none'])
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h3>Cancelled / Rejected</h3>
                        <span class="tg-pill">{{ $cancelled->count() }}</span>
                    </div>
                    @include('tour-guide-partials.bookings-table', ['rows' => $cancelled, 'showActions' => 'none'])
                </div>
            </div>

            {{-- ============== HIKERS ============== --}}
            <div class="view-section" id="view-hikers">
                <header class="dashboard-header">
                    <h2>Hikers You've Guided</h2>
                    <p>People who completed at least one hike with you.</p>
                </header>

                @php
                    $hikersList = $completed
                        ->groupBy('user_id')
                        ->map(function ($items) {
                            $first = $items->first();
                            return [
                                'user' => $first->user,
                                'count' => $items->count(),
                                'last_hike' => $items->max('hike_on'),
                                'mountains' => $items->pluck('mountain.name')->filter()->unique()->values(),
                            ];
                        })
                        ->values();
                @endphp

                <div class="dashboard-card">
                    @if ($hikersList->isEmpty())
                        <div class="tg-empty">No completed hikes yet.</div>
                    @else
                        <div class="tg-table-wrap">
                            <table class="tg-table">
                                <thead>
                                    <tr>
                                        <th>Hiker</th>
                                        <th>Email</th>
                                        <th>Hikes</th>
                                        <th>Mountains</th>
                                        <th>Last Hike</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hikersList as $h)
                                        <tr>
                                            <td>
                                                <div class="who">
                                                    <div class="tg-mini-avatar" style="{{ $h['user']?->profile_picture_url ? 'background-image:url('.$h['user']->profile_picture_url.')' : '' }}">
                                                        {{ strtoupper(substr($h['user']?->first_name ?? '?', 0, 1).substr($h['user']?->last_name ?? '', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="tg-who-name">{{ $h['user']?->full_name ?? 'Unknown' }}</div>
                                                        <div class="tg-who-sub">{{ $h['user']?->phone }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $h['user']?->email }}</td>
                                            <td><span class="tg-pill">{{ $h['count'] }}</span></td>
                                            <td>{{ $h['mountains']->join(', ') }}</td>
                                            <td>{{ optional($h['last_hike'])->format('M j, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== REVIEWS ============== --}}
            <div class="view-section" id="view-reviews">
                <header class="dashboard-header">
                    <h2>My Reviews</h2>
                    <p>Hiker feedback after completed hikes.</p>
                </header>

                <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));margin-bottom:18px;">
                    <div class="stat-card">
                        <div class="stat-icon purple"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></div>
                        <div class="stat-info">
                            <h4>Average Rating</h4>
                            <div class="stat-value">{{ $stats['rating'] !== null ? $stats['rating'].' / 5' : '—' }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></div>
                        <div class="stat-info">
                            <h4>Total Reviews</h4>
                            <div class="stat-value">{{ $stats['rating_count'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    @if ($guideReviews->isEmpty())
                        <div class="tg-empty">No reviews yet. Hikers can rate you after completing a hike.</div>
                    @else
                        <div style="display:grid;gap:12px;">
                            @foreach ($guideReviews as $r)
                                <div class="tg-review">
                                    <div class="tg-review-head">
                                        <div>
                                            <span class="tg-review-name">{{ $r->user?->full_name ?? 'Hiker' }}</span>
                                            <span class="tg-review-mtn">&middot; {{ $r->mountain?->name }}</span>
                                        </div>
                                        <div class="tg-review-stars">
                                            {{ str_repeat('★', (int) $r->rating) }}<span class="dim">{{ str_repeat('★', 5 - (int) $r->rating) }}</span>
                                        </div>
                                    </div>
                                    @if ($r->review_text)
                                        <p class="tg-review-body">{{ $r->review_text }}</p>
                                    @endif
                                    <div class="tg-review-date">{{ $r->updated_at?->format('M j, Y') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== PROFILE ============== --}}
            <div class="view-section" id="view-profile">
                <header class="dashboard-header">
                    <h2>My Profile</h2>
                    <p>Your public information shown to hikers when they book a guide.</p>
                </header>

                <div class="dashboard-card">
                    <div class="tg-profile-head">
                        <div class="tg-profile-avatar" id="avatar-preview" style="{{ $avatarUrl ? 'background-image:url('.$avatarUrl.')' : '' }}">
                            @if(! $avatarUrl) {{ $guide->initials }} @endif
                        </div>
                        <div>
                            <form id="picture-form" enctype="multipart/form-data">
                                @csrf
                                <label class="tg-btn" style="cursor:pointer;">
                                    <iconify-icon icon="lucide:camera"></iconify-icon>
                                    Change photo
                                    <input type="file" name="profile_picture" id="picture-input" accept="image/*" style="display:none;">
                                </label>
                            </form>
                            <div class="tg-profile-meta-hint">JPG, PNG, GIF or WEBP. Max 2MB.</div>
                        </div>
                    </div>

                    <div class="tg-row-note" style="margin-bottom:16px;">
                        <iconify-icon icon="lucide:info" style="vertical-align:text-bottom;"></iconify-icon>
                        Your name, email, phone, specialty, experience and assigned mountain are managed by an administrator. You can update your photo and bio here.
                    </div>

                    <form id="profile-form" class="tg-form">
                        @csrf
                        <div class="tg-form-row">
                            <div>
                                <label>First name</label>
                                <input value="{{ $user->first_name }}" disabled>
                            </div>
                            <div>
                                <label>Last name</label>
                                <input value="{{ $user->last_name }}" disabled>
                            </div>
                        </div>
                        <div class="tg-form-row">
                            <div>
                                <label>Phone</label>
                                <input value="{{ $user->phone }}" disabled>
                            </div>
                            <div>
                                <label>Email</label>
                                <input value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                        <div class="tg-form-row">
                            <div>
                                <label>Specialty</label>
                                <input value="{{ $guide->specialty }}" disabled>
                            </div>
                            <div>
                                <label>Years of experience</label>
                                <input value="{{ $guide->experience_years }}" disabled>
                            </div>
                        </div>
                        <div>
                            <label>Primary mountain</label>
                            <input value="{{ $guide->mountain?->name ?? 'All mountains' }}" disabled>
                        </div>
                        <div>
                            <label>About / Bio</label>
                            <textarea name="bio" placeholder="Share your guiding background, certifications, and signature trails…">{{ $guide->bio }}</textarea>
                        </div>
                        <div style="display:flex;gap:10px;">
                            <button type="submit" class="tg-btn primary">
                                <iconify-icon icon="lucide:save"></iconify-icon>
                                Save bio
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============== SETTINGS (placeholder) ============== --}}
            <div class="view-section" id="view-settings">
                <header class="dashboard-header">
                    <h2>Account Settings</h2>
                    <p>Update your account email, password, and preferences.</p>
                </header>
                <div class="dashboard-card">
                    <div class="tg-empty">More settings coming soon. For now, edit your guiding info under <a href="#profile" class="js-go-profile" style="color:var(--brand-dark);font-weight:700;">My Profile</a>.</div>
                </div>
            </div>
        </main>
    </div>

    <div class="tg-toast" id="tgToast"></div>

    <script>
        (function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const toastEl = document.getElementById('tgToast');
            function toast(msg, isErr) {
                toastEl.textContent = msg;
                toastEl.classList.toggle('error', !!isErr);
                toastEl.classList.add('show');
                clearTimeout(toast._t);
                toast._t = setTimeout(() => toastEl.classList.remove('show'), 2400);
            }

            // ====== Section navigation (mirrors hikers.blade.php pattern) ======
            const menuLinks = document.querySelectorAll('.menu-item');
            const sections = {
                'home':     document.getElementById('view-dashboard'),
                'bookings': document.getElementById('view-bookings'),
                'hikers':   document.getElementById('view-hikers'),
                'reviews':  document.getElementById('view-reviews'),
                'profile':  document.getElementById('view-profile'),
                'settings': document.getElementById('view-settings'),
            };

            function showView(targetId) {
                Object.values(sections).forEach(s => { if (s) s.classList.remove('active'); });
                menuLinks.forEach(l => l.classList.remove('active'));
                const rawId = (targetId || '').replace('#', '') || 'home';
                let sec = sections[rawId] || sections['home'];
                if (sec) sec.classList.add('active');
                menuLinks.forEach(l => {
                    const h = l.getAttribute('href');
                    if (h === '#' + rawId) l.classList.add('active');
                    else if (rawId === 'home' && h === '#') l.classList.add('active');
                });
                document.querySelector('.layout').classList.remove('mobile-open');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                if (history.replaceState) history.replaceState(null, '', '#' + rawId);
            }
            window.showView = showView;

            menuLinks.forEach(link => {
                link.addEventListener('click', e => {
                    const href = link.getAttribute('href');
                    if (href && (href.startsWith('#') || href === '#')) {
                        e.preventDefault();
                        showView(href === '#' ? '#home' : href);
                    }
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(a => {
                if (a.classList.contains('menu-item')) return;
                a.addEventListener('click', e => {
                    const href = a.getAttribute('href');
                    if (sections[href.replace('#','')]) {
                        e.preventDefault();
                        showView(href);
                    }
                });
            });

            const initialHash = (window.location.hash || '').replace(/^#/, '');
            if (initialHash && sections[initialHash]) showView('#' + initialHash);

            // ====== Theme toggle (light/dark) ======
            const root = document.documentElement;
            const lightBtn = document.getElementById('mode-light');
            const darkBtn = document.getElementById('mode-dark');
            function applyTheme(mode) {
                if (mode === 'dark') {
                    root.setAttribute('data-theme', 'dark');
                    lightBtn?.classList.remove('active'); darkBtn?.classList.add('active');
                } else {
                    root.removeAttribute('data-theme');
                    darkBtn?.classList.remove('active'); lightBtn?.classList.add('active');
                }
                try { localStorage.setItem('hc-theme', mode); } catch (_) {}
            }
            const savedTheme = (() => { try { return localStorage.getItem('hc-theme'); } catch (_) { return null; } })();
            applyTheme(savedTheme === 'dark' ? 'dark' : 'light');
            lightBtn?.addEventListener('click', () => applyTheme('light'));
            darkBtn?.addEventListener('click', () => applyTheme('dark'));

            // ====== Booking row actions ======
            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('[data-booking-action]');
                if (!btn) return;
                const id = btn.dataset.bookingId;
                const action = btn.dataset.bookingAction;
                const url = {
                    approve:  `{{ url('tour-guide/bookings') }}/${id}/approve`,
                    reject:   `{{ url('tour-guide/bookings') }}/${id}/reject`,
                    complete: `{{ url('tour-guide/bookings') }}/${id}/complete`,
                }[action];
                if (!url) return;

                if (action === 'reject' && !confirm('Reject and cancel this booking?')) return;
                if (action === 'complete' && !confirm('Mark this hike as completed?')) return;

                btn.disabled = true;
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                    });
                    const data = await res.json().catch(() => ({}));
                    if (res.ok && data.success) {
                        toast('Booking ' + (action === 'approve' ? 'approved' : action === 'reject' ? 'rejected' : 'completed'));
                        setTimeout(() => location.reload(), 600);
                    } else {
                        toast(data.message || 'Action failed', true);
                        btn.disabled = false;
                    }
                } catch (err) {
                    toast('Network error', true);
                    btn.disabled = false;
                }
            });

            // ====== Availability switcher ======
            const availForm = document.getElementById('availability-form');
            const availSelect = document.getElementById('tgAvail');
            availSelect?.addEventListener('change', async () => {
                const fd = new FormData();
                fd.append('status', availSelect.value);
                const res = await fetch(@json(route('tour-guide.availability')), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await res.json().catch(() => ({}));
                if (data.success) {
                    availForm.setAttribute('data-status', data.status);
                    toast('Status updated to ' + data.status_label);
                } else {
                    toast('Could not update status', true);
                }
            });

            // ====== Profile save ======
            const profileForm = document.getElementById('profile-form');
            profileForm?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(profileForm);
                const res = await fetch(@json(route('tour-guide.profile.update')), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await res.json().catch(() => ({}));
                if (res.ok && data.success) toast('Profile updated');
                else toast(data.message || 'Could not save profile', true);
            });

            // ====== Picture upload ======
            const picInput = document.getElementById('picture-input');
            const avatarPreview = document.getElementById('avatar-preview');
            const sidebarAvatar = document.getElementById('sidebar-user-avatar');
            picInput?.addEventListener('change', async () => {
                if (!picInput.files[0]) return;
                const fd = new FormData();
                fd.append('profile_picture', picInput.files[0]);
                const res = await fetch(@json(route('tour-guide.profile.picture')), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await res.json().catch(() => ({}));
                if (res.ok && data.success && data.url) {
                    avatarPreview.style.backgroundImage = `url(${data.url})`;
                    avatarPreview.textContent = '';
                    if (sidebarAvatar) {
                        sidebarAvatar.innerHTML = `<img src="${data.url}" alt="" width="40" height="40" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">`;
                        sidebarAvatar.style.padding = '0';
                    }
                    toast('Photo updated');
                } else {
                    toast(data.message || 'Upload failed', true);
                }
            });
        })();
    </script>
</body>
</html>
