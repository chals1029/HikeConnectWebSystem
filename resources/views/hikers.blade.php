<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HikeConnect | Hiker Side</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @vite(['resources/css/hikers.css', 'resources/js/app.js'])
</head>
<body>
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
                    <input type="text" id="hiker-global-search" placeholder="Search hiker side..." aria-label="Search hiker side">
                </div>

                <div class="menu-title">Menu</div>
                <a href="#" class="menu-item active">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span class="menu-text">Home</span>
                </a>
                <a href="#achievements" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path><circle cx="12" cy="8" r="6"></circle></svg>
                    <span class="menu-text">Achievements</span>
                    <span class="menu-badge" id="menu-achievements-badge" style="{{ $stats['badges'] > 0 ? '' : 'display:none;' }}">{{ $stats['badges'] }}</span>
                </a>
                <a href="#mountain-overview" class="menu-item">
                    <svg viewBox="0 0 24 24"><path d="m8 3 4 8 5-5 5 15H2L8 3z"></path></svg>
                    <span class="menu-text">Mountains / Trails</span>
                    @if($mountains->count() > 0)<span class="menu-badge">{{ $mountains->count() }}</span>@endif
                </a>
                <a href="#tour-guides" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="menu-text">Tour Guides</span>
                </a>
                <a href="#bookings" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><path d="M16 2v4"></path><path d="M8 2v4"></path><path d="M3 10h18"></path></svg>
                    <span class="menu-text">My Bookings</span>
                </a>
                <a href="#book-hike" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span class="menu-text">Book a Hike</span>
                </a>
                <a href="#hiking-history" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span class="menu-text">Hiking History</span>
                </a>
                <a href="#leaderboard" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path></svg>
                    <span class="menu-text">Leaderboard</span>
                    @if(($myRank ?? null) && $myRank <= 3)<span class="menu-badge" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#78350f;">#{{ $myRank }}</span>@elseif($myRank)<span class="menu-badge">#{{ $myRank }}</span>@endif
                </a>
                <a href="#track-location" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span class="menu-text">Track Location</span>
                </a>
                <a href="#what-to-bring" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                    <span class="menu-text">What to Bring</span>
                </a>
                <a href="#community-chat" class="menu-item">
                    <svg viewBox="0 0 24 24"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"></path></svg>
                    <span class="menu-text">Community Groups</span>
                    @if($communityPostTotal > 0)<span class="menu-badge">{{ $communityPostTotal }}</span>@endif
                </a>
                <a href="#settings" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span class="menu-text">Account Settings</span>
                </a>
                <a href="#safety-alerts" class="menu-item">
                    <svg viewBox="0 0 24 24"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <span class="menu-text">Safety Alerts</span>
                    @php $hikerSafetyBadge = $hikerSosAlerts->where('status', \App\Models\SosAlert::STATUS_OPEN)->count() + $safetyMountains->count(); @endphp
                    @if($hikerSafetyBadge > 0)<span class="menu-badge" style="background:#fee2e2;color:#991b1b;">{{ $hikerSafetyBadge }}</span>@endif
                </a>

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
                        <div class="avatar" id="sidebar-user-avatar" style="{{ $user->profile_picture_url ? 'padding:0;' : '' }}">
                            @if($user->profile_picture_url)
                                <img src="{{ $user->profile_picture_url }}" alt="" width="40" height="40" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
                            @else
                                {{ substr($user->first_name ?? 'U', 0, 1) }}{{ substr($user->last_name ?? 'S', 0, 1) }}
                            @endif
                        </div>
                        <div class="profile-info">
                            <div class="profile-name">{{ $user->full_name ?? 'User' }}</div>
                            <div class="profile-role">{{ $user->email ?? 'user@example.com' }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                        <div class="profile-logout" onclick="document.getElementById('logout-form').submit();" style="cursor: pointer;">
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

            <div class="view-section active" id="view-dashboard">
                @php
                    $champion       = $leaderboard->first();
                    $previewLeaders = $leaderboard->take(5);
                    $upcomingDate   = $upcoming
                        ? ($upcoming->hike_on->isToday()
                            ? 'Today'
                            : ($upcoming->hike_on->isTomorrow() ? 'Tomorrow' : $upcoming->hike_on->format('M j, Y')))
                        : null;
                    $hikerInitials = strtoupper(
                        substr((string) ($user->first_name ?? 'H'), 0, 1).
                        substr((string) ($user->last_name ?? 'C'), 0, 1)
                    );
                    $favoriteMountain = $completedHistory->first()?->mountain;
                @endphp

                {{-- HERO --}}
                <section class="hc-hero">
                    <div class="hc-hero-row">
                        <div>
                            <span class="hc-hero-eyebrow">
                                <iconify-icon icon="lucide:mountain"></iconify-icon>
                                Hiker journal &middot; {{ now()->format('l, M j, Y') }}
                            </span>
                            <h1>Welcome back, <span class="hc-hero-name" id="dashboard-welcome-first-name">{{ $user->first_name ?? 'Hiker' }}</span></h1>
                            <p>The trails are calling. Here's your next adventure, your stats, and how you stack up against the community.</p>
                        </div>
                        <div class="hc-hero-cta">
                            @if($upcoming)
                                <a href="#trail-plan" class="hc-chip is-live">
                                    <strong>{{ $upcomingDate }}</strong>
                                    {{ $upcoming->mountain->name }}
                                </a>
                            @else
                                <a href="#book-hike" class="hc-chip is-amber">
                                    <iconify-icon icon="lucide:map"></iconify-icon>
                                    <strong>Book</strong> your next hike
                                </a>
                            @endif
                            <a href="#leaderboard" class="hc-chip">
                                <iconify-icon icon="lucide:trophy"></iconify-icon>
                                @if($myRank)
                                    Ranked <strong>#{{ $myRank }}</strong> of {{ $totalHikers }}
                                @else
                                    Leaderboard
                                @endif
                            </a>
                            <a href="#achievements" class="hc-chip">
                                <iconify-icon icon="lucide:award"></iconify-icon>
                                <strong>{{ $stats['badges'] }}</strong> {{ Str::plural('badge', $stats['badges']) }}
                            </a>
                        </div>
                    </div>
                </section>

                {{-- TODAY ON THE TRAILS --}}
                <section class="hc-today" aria-label="Today on the trails">
                    <div class="hc-today-info">
                        <div class="hc-today-title">
                            <iconify-icon icon="lucide:mountain-snow"></iconify-icon>
                            Today on the trails
                        </div>
                        <h3 class="hc-today-h3">
                            @if($upcoming)
                                {{ $upcomingDate }} &middot; {{ $upcoming->mountain->name }}
                            @elseif($stats['hikes_completed'] > 0)
                                {{ $stats['hikes_completed'] }} {{ Str::plural('summit', $stats['hikes_completed']) }} conquered
                            @else
                                Your first summit awaits
                            @endif
                        </h3>
                        <p class="hc-today-sub">
                            @if($upcoming)
                                Meet at {{ $upcoming->mountain->jumpoff_meeting_time }} &middot; {{ $upcoming->mountain->location }}
                            @elseif($favoriteMountain)
                                Last climbed {{ $favoriteMountain->name }} &middot; {{ $stats['total_hours'] }}h logged so far
                            @else
                                Book a tour guide and start logging your first adventure today.
                            @endif
                        </p>
                    </div>
                    <div class="hc-today-stat">
                        <div class="v live">{{ $stats['hikes_completed'] }}</div>
                        <div class="l">Summits</div>
                    </div>
                    <div class="hc-today-stat">
                        <div class="v">{{ number_format($stats['total_elevation']) }}<small style="font-size:14px;color:var(--muted);font-weight:700;">m</small></div>
                        <div class="l">Elevation gained</div>
                    </div>
                </section>

                {{-- STAT TILES --}}
                <div class="hc-stats">
                    <div class="hc-stat tone-forest">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:flag-triangle-right"></iconify-icon></div>
                            @if($myRank)
                                <span class="hc-stat-trend"><iconify-icon icon="lucide:trophy"></iconify-icon> #{{ $myRank }}</span>
                            @else
                                <span class="hc-stat-trend is-flat"><iconify-icon icon="lucide:dot"></iconify-icon> New</span>
                            @endif
                        </div>
                        <h4 class="hc-stat-label">Hikes completed</h4>
                        <div class="hc-stat-value">{{ $stats['hikes_completed'] }}</div>
                        <div class="hc-stat-foot">
                            <span>{{ $bookings->whereIn('status', ['pending','approved'])->count() }} upcoming</span>
                            <strong>Total</strong>
                        </div>
                    </div>

                    <div class="hc-stat tone-peak">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:clock"></iconify-icon></div>
                            <span class="hc-stat-trend is-flat"><iconify-icon icon="lucide:hourglass"></iconify-icon> Logged</span>
                        </div>
                        <h4 class="hc-stat-label">Hours on trail</h4>
                        <div class="hc-stat-value">{{ $stats['total_hours'] }}<span class="hc-stat-suffix">h</span></div>
                        <div class="hc-stat-foot"><span>Across all summits</span><strong>Time</strong></div>
                    </div>

                    <div class="hc-stat tone-amber">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:bar-chart-3"></iconify-icon></div>
                            <span class="hc-stat-trend is-flat"><iconify-icon icon="lucide:trending-up"></iconify-icon> Climb</span>
                        </div>
                        <h4 class="hc-stat-label">Elevation gained</h4>
                        <div class="hc-stat-value">{{ number_format($stats['total_elevation']) }}<span class="hc-stat-suffix">m</span></div>
                        <div class="hc-stat-foot"><span>Vertical lifetime</span><strong>Peaks</strong></div>
                    </div>

                    <div class="hc-stat tone-sunrise">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:award"></iconify-icon></div>
                            <span class="hc-stat-trend"><iconify-icon icon="lucide:medal"></iconify-icon> Earned</span>
                        </div>
                        <h4 class="hc-stat-label">Badges claimed</h4>
                        <div class="hc-stat-value"><a href="#achievements" id="stat-badges-count-link" style="color:inherit;text-decoration:none;">{{ $stats['badges'] }}</a></div>
                        <div class="hc-stat-foot"><span>Tap to view all →</span><strong>Trophies</strong></div>
                    </div>
                </div>

                {{-- UPCOMING + LEADERBOARD PREVIEW --}}
                <div class="hc-row-2">
                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:calendar-clock"></iconify-icon> Next adventure</h3>
                            @if($upcoming)
                                <a href="#trail-plan">View trail plan →</a>
                            @else
                                <a href="#book-hike">Book a hike →</a>
                            @endif
                        </div>
                        @if($upcoming)
                            <div class="hc-nexthike">
                                <div class="hc-nexthike-eyebrow">
                                    <iconify-icon icon="lucide:calendar-check"></iconify-icon>
                                    {{ $upcomingDate }}
                                </div>
                                <h3>{{ $upcoming->mountain->name }}</h3>
                                <div class="hc-nexthike-meta">
                                    <span><iconify-icon icon="lucide:clock"></iconify-icon> {{ $upcoming->mountain->jumpoff_meeting_time }}</span>
                                    <span><iconify-icon icon="lucide:map-pin"></iconify-icon> {{ $upcoming->mountain->location }}</span>
                                    @if($upcoming->mountain->difficulty)
                                        <span><iconify-icon icon="lucide:trending-up"></iconify-icon> {{ ucfirst($upcoming->mountain->difficulty) }}</span>
                                    @endif
                                </div>
                                @if($upcoming->tourGuide)
                                    <div class="hc-nexthike-booker">
                                        Guided by <strong>{{ $upcoming->tourGuide->full_name }}</strong>
                                        @if($upcoming->tourGuide->specialty) &middot; {{ $upcoming->tourGuide->specialty }} @endif
                                    </div>
                                @endif
                                <div class="hc-nexthike-note">
                                    <iconify-icon icon="lucide:sun" style="color:#f59e0b;vertical-align:text-bottom;margin-right:4px;"></iconify-icon>
                                    Clear skies expected — perfect conditions for the climb.
                                </div>
                                <a href="#trail-plan" class="hc-nexthike-cta">
                                    Open trail plan
                                    <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                                </a>
                            </div>
                        @else
                            <div class="hc-nexthike">
                                <div class="hc-nexthike-eyebrow">
                                    <iconify-icon icon="lucide:sparkles"></iconify-icon>
                                    Ready when you are
                                </div>
                                <h3>No upcoming hike</h3>
                                <div class="hc-nexthike-meta">
                                    <span><iconify-icon icon="lucide:mountain"></iconify-icon> {{ $mountains->count() }} {{ Str::plural('mountain', $mountains->count()) }} mapped</span>
                                    <span><iconify-icon icon="lucide:users"></iconify-icon> {{ $guides->count() }} {{ Str::plural('guide', $guides->count()) }} available</span>
                                </div>
                                <div class="hc-nexthike-note">
                                    <iconify-icon icon="lucide:info" style="color:var(--hc-forest);vertical-align:text-bottom;margin-right:4px;"></iconify-icon>
                                    Pick a trail and a tour guide to schedule your first adventure.
                                </div>
                                <a href="#book-hike" class="hc-nexthike-cta">
                                    Book a hike
                                    <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:trophy"></iconify-icon> Leaderboard</h3>
                            <a href="#leaderboard">See full board →</a>
                        </div>
                        @if($leaderboard->isEmpty())
                            <div class="hc-empty"><iconify-icon icon="lucide:users"></iconify-icon> No hikers ranked yet.</div>
                        @else
                            <div class="hc-lb-preview">
                                @foreach($previewLeaders as $row)
                                    @php
                                        $rank = $row['rank'];
                                        $isMe = $row['id'] === $user->id;
                                        $rowCls = $isMe ? 'is-me' : '';
                                        if ($rank === 1) $rowCls .= ' is-top1';
                                        elseif ($rank === 2) $rowCls .= ' is-top2';
                                        elseif ($rank === 3) $rowCls .= ' is-top3';
                                    @endphp
                                    <div class="hc-lb-row {{ trim($rowCls) }}">
                                        <div class="hc-lb-rank">{{ $rank }}</div>
                                        <div class="hc-lb-avatar" style="{{ $row['profile_picture'] ? 'background-image:url('.$row['profile_picture'].')' : '' }}">
                                            {{ $row['profile_picture'] ? '' : $row['initials'] }}
                                        </div>
                                        <div class="hc-lb-info">
                                            <div class="hc-lb-name">
                                                {{ $row['full_name'] }}
                                                @if($isMe)<span class="me-pill">You</span>@endif
                                            </div>
                                            <div class="hc-lb-meta">
                                                <span><iconify-icon icon="lucide:clock"></iconify-icon> {{ $row['total_hours'] }}h</span>
                                                <span><iconify-icon icon="lucide:trending-up"></iconify-icon> {{ number_format($row['total_elevation']) }}m</span>
                                            </div>
                                        </div>
                                        <div class="hc-lb-count">
                                            {{ $row['hikes_completed'] }}
                                            <small>{{ Str::plural('hike', $row['hikes_completed']) }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($myRank && $myRank > 5)
                                <div style="margin-top:12px;padding-top:12px;border-top:1px dashed var(--line);">
                                    <div class="hc-lb-row is-me">
                                        <div class="hc-lb-rank">{{ $myRank }}</div>
                                        <div class="hc-lb-avatar" style="{{ $myLeaderRow['profile_picture'] ? 'background-image:url('.$myLeaderRow['profile_picture'].')' : '' }}">
                                            {{ $myLeaderRow['profile_picture'] ? '' : $myLeaderRow['initials'] }}
                                        </div>
                                        <div class="hc-lb-info">
                                            <div class="hc-lb-name">
                                                {{ $myLeaderRow['full_name'] }}
                                                <span class="me-pill">You</span>
                                            </div>
                                            <div class="hc-lb-meta">
                                                <span><iconify-icon icon="lucide:clock"></iconify-icon> {{ $myLeaderRow['total_hours'] }}h</span>
                                                <span><iconify-icon icon="lucide:trending-up"></iconify-icon> {{ number_format($myLeaderRow['total_elevation']) }}m</span>
                                            </div>
                                        </div>
                                        <div class="hc-lb-count">
                                            {{ $myLeaderRow['hikes_completed'] }}
                                            <small>{{ Str::plural('hike', $myLeaderRow['hikes_completed']) }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- COMMUNITY PULSE --}}
                <div class="hc-panel">
                    <div class="hc-panel-head">
                        <h3><iconify-icon icon="lucide:waypoints"></iconify-icon> Recent community activity</h3>
                        <a href="#community-chat">View all →</a>
                    </div>
                    @if($communityPosts->isEmpty())
                        <div class="hc-empty"><iconify-icon icon="lucide:message-square"></iconify-icon> No community posts yet. Share your first adventure in Community Chat.</div>
                    @else
                        <div class="hc-feed">
                            @foreach($communityPosts->take(5) as $post)
                                <div class="hc-feed-row">
                                    <span class="hc-feed-tag t-create">
                                        <iconify-icon icon="lucide:message-square" style="vertical-align:text-bottom;margin-right:3px;"></iconify-icon>
                                        Post
                                    </span>
                                    <div class="hc-feed-body">
                                        <div class="hc-feed-desc">
                                            <strong>{{ $post->author_name }}</strong>
                                            @if($post->mountain) &middot; <span style="color:var(--muted);">{{ $post->mountain->name }}</span>@endif
                                        </div>
                                        <div class="hc-feed-meta">{{ \Illuminate\Support\Str::limit($post->body, 100) }}</div>
                                    </div>
                                    <div class="hc-feed-time">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="view-section" id="view-achievements">
                <header class="dashboard-header">
                    <h2>Achievements & badges</h2>
                    <p>Complete hikes and community goals, then claim your badges here. Eligible achievements show a <strong>Claim badge</strong> button.</p>
                </header>
                <div class="achievements-grid">
                    @forelse($achievementsUi as $row)
                    <article class="achievement-card {{ $row['claimed'] ? 'is-claimed' : ($row['eligible'] ? 'is-eligible' : 'is-locked') }}" data-achievement-id="{{ $row['id'] }}">
                        <div class="achievement-badge-ring">
                            @if($row['badge_icon'])
                                <iconify-icon icon="{{ $row['badge_icon'] }}" class="achievement-icon" style="font-size:36px;color:var(--brand-dark);"></iconify-icon>
                            @else
                                <iconify-icon icon="lucide:award" class="achievement-icon" style="font-size:36px;color:var(--brand-dark);"></iconify-icon>
                            @endif
                        </div>
                        <h3 class="achievement-title">{{ $row['name'] }}</h3>
                        <p class="achievement-desc">{{ $row['description'] }}</p>
                        <div class="achievement-status">
                            @if($row['claimed'])
                                <span class="achievement-pill claimed"><iconify-icon icon="lucide:check" style="vertical-align:text-bottom;"></iconify-icon> Claimed</span>
                            @elseif($row['eligible'])
                                <span class="achievement-pill ready">Ready to claim</span>
                                <button type="button" class="achievement-claim-btn mountain-cta" data-claim-achievement="{{ $row['id'] }}">Claim badge</button>
                            @else
                                <span class="achievement-pill locked">In progress</span>
                            @endif
                        </div>
                    </article>
                    @empty
                    <p style="color:var(--muted);padding:16px;">No achievements configured yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="view-section" id="view-mountain-overview">
                <section class="mountain-browser" id="mountain-overview">
                    <div class="mountain-browser-head">
                        <div class="mountain-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#5a9064" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 19h18"></path>
                                <path d="M6.5 19 11 8l2.5 4.2L16 7l4.2 12"></path>
                            </svg>
                        </div>
                        <h2>The Mountains</h2>
                    </div>

                    <div class="mountain-controls">
                        <label class="mountain-search">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#7a8c7f" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>
                            <input type="text" id="mountain-search-input" placeholder="Search mountains, trails, or locations..." aria-label="Search mountains" oninput="filterMountains()">
                        </label>
                        <label class="difficulty-select">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7a8c7f" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 5h18l-7 8v5l-4 2v-7z"></path>
                            </svg>
                            <select id="mountain-difficulty-filter" aria-label="Mountain difficulty" onchange="filterMountains()">
                                <option>All Difficulties</option>
                                @foreach($mountainDifficulties as $diff)
                                <option>{{ $diff }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    <div class="mountain-cards">
                        @foreach($mountains as $idx => $m)
                        <article class="mountain-card {{ $idx === 0 ? 'selected' : '' }}" data-mountain="{{ $m->slug }}">
                            <div class="mountain-thumb" style="background-image: url('{{ asset($m->image_path) }}');">
                                <span class="thumb-status {{ $m->status }}"><iconify-icon icon="lucide:circle" style="vertical-align:text-bottom; margin-right:2px; font-size:10px;"></iconify-icon> {{ $m->status === 'open' ? 'Open' : 'Closed' }}</span>
                                @if($m->hasSafetyWarning())
                                    <span class="thumb-chip" style="left:10px;right:auto;top:44px;background:{{ $m->safety_status === \App\Models\Mountain::SAFETY_BAD_WEATHER ? '#dbeafe' : '#fee2e2' }};color:{{ $m->safety_status === \App\Models\Mountain::SAFETY_BAD_WEATHER ? '#1d4ed8' : '#991b1b' }};">
                                        <iconify-icon icon="lucide:triangle-alert" style="vertical-align:text-bottom;margin-right:2px;"></iconify-icon>
                                        {{ $m->safety_status_label }}
                                    </span>
                                @endif
                                <span class="thumb-chip weather-chip">--°C</span>
                                <span class="thumb-difficulty">{{ $m->difficulty }}</span>
                            </div>
                            <div class="mountain-body">
                                <div class="mountain-top-line">
                                    <h3 class="mountain-name">{{ $m->name }}</h3>
                                    <span class="mountain-rate">{{ number_format($m->rating, 1) }}</span>
                                </div>
                                <p class="mountain-desc">{{ $m->short_description }}</p>
                                <p class="mountain-loc">{{ $m->location }}</p>
                                <button class="mountain-cta" type="button" onclick="openMountainDetail('{{ $m->slug }}')">View Details <iconify-icon icon="lucide:arrow-right" style="vertical-align:text-bottom; margin-left:2px;"></iconify-icon></button>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- ============== LEADERBOARD ============== --}}
            <div class="view-section" id="view-leaderboard">
                @php
                    $champ        = $leaderboard->first();
                    $second       = $leaderboard->get(1);
                    $third        = $leaderboard->get(2);
                    $topTen       = $leaderboard->take(10);
                    $maxHikes     = max(1, (int) ($leaderboard->max('hikes_completed') ?? 1));
                    $myRow        = $myLeaderRow;
                    $rankAbove    = ($myRank && $myRank > 1) ? $leaderboard->get($myRank - 2) : null;
                    $hikesToBeat  = ($rankAbove && $myRow)
                        ? max(0, ((int) $rankAbove['hikes_completed']) - ((int) $myRow['hikes_completed']) + 1)
                        : 0;
                @endphp

                {{-- HERO --}}
                <section class="hc-hero">
                    <div class="hc-hero-row">
                        <div>
                            <span class="hc-hero-eyebrow">
                                <iconify-icon icon="lucide:trophy"></iconify-icon>
                                Hall of Summits &middot; updated {{ now()->format('M j, Y') }}
                            </span>
                            <h1>The <span class="hc-hero-name">leaderboard</span></h1>
                            <p>Every completed hike earns its place. Keep climbing — your rank moves with each summit you bag.</p>
                        </div>
                        <div class="hc-hero-cta">
                            <a class="hc-chip is-live">
                                <strong>{{ $totalHikers }}</strong>
                                {{ Str::plural('hiker', $totalHikers) }} ranked
                            </a>
                            @if($myRank)
                                <a class="hc-chip is-amber">
                                    <iconify-icon icon="lucide:medal"></iconify-icon>
                                    You're <strong>#{{ $myRank }}</strong>
                                </a>
                            @endif
                            @if($champ)
                                <a class="hc-chip">
                                    <iconify-icon icon="lucide:flag"></iconify-icon>
                                    Top: <strong>{{ $champ['hikes_completed'] }}</strong> {{ Str::plural('hike', $champ['hikes_completed']) }}
                                </a>
                            @endif
                        </div>
                    </div>
                </section>

                @if(! $champ || (int) $champ['hikes_completed'] === 0)
                    {{-- No completed hikes yet --}}
                    <div class="hc-panel">
                        <div class="hc-empty" style="padding:48px 24px;">
                            <iconify-icon icon="lucide:trophy"></iconify-icon>
                            <strong style="font-size:16px;color:var(--text);display:block;margin-bottom:6px;">No summits logged yet</strong>
                            Be the first to complete a hike and claim the top spot of the Hall of Summits.
                            <div style="margin-top:14px;">
                                <a href="#book-hike" class="hc-nexthike-cta" style="display:inline-flex;">
                                    <iconify-icon icon="lucide:map"></iconify-icon> Book a hike
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- CHAMPION CARD (TOP 1) --}}
                    <section class="hc-champ" aria-label="Top hiker">
                        <div class="hc-champ-avatar {{ $champ['profile_picture'] ? 'has-img' : '' }}" style="{{ $champ['profile_picture'] ? 'background-image:url('.$champ['profile_picture'].')' : '' }}">
                            <span class="hc-champ-crown"><iconify-icon icon="lucide:crown"></iconify-icon></span>
                            {!! $champ['profile_picture'] ? '' : $champ['initials'] !!}
                        </div>
                        <div>
                            <span class="hc-champ-eyebrow">
                                <iconify-icon icon="lucide:trophy"></iconify-icon>
                                Top hiker &middot; #1 of {{ $totalHikers }}
                            </span>
                            <h2 class="hc-champ-name">{{ $champ['full_name'] }}</h2>
                            <p class="hc-champ-tag">
                                Conquered <strong>{{ $champ['hikes_completed'] }}</strong> {{ Str::plural('summit', $champ['hikes_completed']) }} —
                                {{ $champ['total_hours'] }}h of trail time and {{ number_format($champ['total_elevation']) }}m of vertical climb.
                            </p>
                            <div class="hc-champ-stats">
                                <div class="ks">
                                    <div class="v">{{ $champ['hikes_completed'] }}</div>
                                    <div class="l">Hikes</div>
                                </div>
                                <div class="ks">
                                    <div class="v">{{ $champ['total_hours'] }}<small style="font-size:14px;font-weight:700;">h</small></div>
                                    <div class="l">Hours</div>
                                </div>
                                <div class="ks">
                                    <div class="v">{{ number_format($champ['total_elevation']) }}<small style="font-size:14px;font-weight:700;">m</small></div>
                                    <div class="l">Elevation</div>
                                </div>
                            </div>
                            @if($champ['joined_at'])
                                <span class="hc-champ-meta">
                                    <iconify-icon icon="lucide:calendar"></iconify-icon>
                                    Hiking since {{ \Illuminate\Support\Carbon::parse($champ['joined_at'])->format('M Y') }}
                                </span>
                            @endif
                        </div>
                    </section>

                    {{-- PODIUM (2 - 1 - 3) --}}
                    @if($second || $third)
                        <section class="hc-podium" aria-label="Top 3 hikers">
                            {{-- 2nd --}}
                            @if($second)
                                <div class="hc-podium-card is-2">
                                    <div class="hc-podium-medal">2</div>
                                    <div class="hc-podium-avatar" style="{{ $second['profile_picture'] ? 'background-image:url('.$second['profile_picture'].')' : '' }}">
                                        {{ $second['profile_picture'] ? '' : $second['initials'] }}
                                    </div>
                                    <div class="hc-podium-name">{{ $second['full_name'] }}</div>
                                    <div class="hc-podium-sub">Silver summit</div>
                                    <div class="hc-podium-count">{{ $second['hikes_completed'] }} <small>{{ Str::plural('hike', $second['hikes_completed']) }}</small></div>
                                    <div class="hc-podium-base"></div>
                                </div>
                            @else
                                <div class="hc-podium-card is-2" style="opacity:0.55;">
                                    <div class="hc-podium-medal">2</div>
                                    <div class="hc-podium-avatar">??</div>
                                    <div class="hc-podium-name">Open spot</div>
                                    <div class="hc-podium-sub">Silver summit</div>
                                    <div class="hc-podium-count">—</div>
                                    <div class="hc-podium-base"></div>
                                </div>
                            @endif

                            {{-- 1st (center, taller) --}}
                            <div class="hc-podium-card is-1">
                                <div class="hc-podium-medal">1</div>
                                <div class="hc-podium-avatar" style="{{ $champ['profile_picture'] ? 'background-image:url('.$champ['profile_picture'].')' : '' }}">
                                    {{ $champ['profile_picture'] ? '' : $champ['initials'] }}
                                </div>
                                <div class="hc-podium-name">{{ $champ['full_name'] }}</div>
                                <div class="hc-podium-sub"><iconify-icon icon="lucide:crown" style="vertical-align:text-bottom;"></iconify-icon> Champion</div>
                                <div class="hc-podium-count">{{ $champ['hikes_completed'] }} <small>{{ Str::plural('hike', $champ['hikes_completed']) }}</small></div>
                                <div class="hc-podium-base"></div>
                            </div>

                            {{-- 3rd --}}
                            @if($third)
                                <div class="hc-podium-card is-3">
                                    <div class="hc-podium-medal">3</div>
                                    <div class="hc-podium-avatar" style="{{ $third['profile_picture'] ? 'background-image:url('.$third['profile_picture'].')' : '' }}">
                                        {{ $third['profile_picture'] ? '' : $third['initials'] }}
                                    </div>
                                    <div class="hc-podium-name">{{ $third['full_name'] }}</div>
                                    <div class="hc-podium-sub">Bronze summit</div>
                                    <div class="hc-podium-count">{{ $third['hikes_completed'] }} <small>{{ Str::plural('hike', $third['hikes_completed']) }}</small></div>
                                    <div class="hc-podium-base"></div>
                                </div>
                            @else
                                <div class="hc-podium-card is-3" style="opacity:0.55;">
                                    <div class="hc-podium-medal">3</div>
                                    <div class="hc-podium-avatar">??</div>
                                    <div class="hc-podium-name">Open spot</div>
                                    <div class="hc-podium-sub">Bronze summit</div>
                                    <div class="hc-podium-count">—</div>
                                    <div class="hc-podium-base"></div>
                                </div>
                            @endif
                        </section>
                    @endif

                    {{-- YOUR RANK CARD --}}
                    @if($myRank && $myRow)
                        <section class="hc-yourrank" aria-label="Your rank">
                            <div class="hc-yourrank-rank">
                                <small style="display:block;margin-bottom:4px;">Rank</small>
                                #{{ $myRank }}
                            </div>
                            <div class="hc-yourrank-info">
                                <div class="hc-yourrank-eyebrow">Your standing</div>
                                <h4>
                                    @if($myRank === 1)
                                        You're the champion! Stay on top.
                                    @elseif($myRank <= 3)
                                        On the podium &mdash; one more push for #1.
                                    @elseif($hikesToBeat > 0)
                                        {{ $hikesToBeat }} more {{ Str::plural('hike', $hikesToBeat) }} to climb to #{{ max(1, $myRank - 1) }}
                                    @elseif(($myRow['hikes_completed'] ?? 0) === 0)
                                        Your first summit will put you on the board.
                                    @else
                                        Keep climbing — every summit moves you up.
                                    @endif
                                </h4>
                                <p>
                                    Ranked out of {{ $totalHikers }} {{ Str::plural('hiker', $totalHikers) }} &middot;
                                    {{ $myRow['hikes_completed'] }} {{ Str::plural('summit', $myRow['hikes_completed']) }} logged
                                </p>
                            </div>
                            <div class="hc-yourrank-stats">
                                <div class="ks">
                                    <div class="v">{{ $myRow['hikes_completed'] }}</div>
                                    <div class="l">Hikes</div>
                                </div>
                                <div class="ks">
                                    <div class="v">{{ $myRow['total_hours'] }}h</div>
                                    <div class="l">Hours</div>
                                </div>
                                <div class="ks">
                                    <div class="v">{{ number_format($myRow['total_elevation']) }}m</div>
                                    <div class="l">Climbed</div>
                                </div>
                            </div>
                        </section>
                    @endif

                    {{-- TOP 10 LIST --}}
                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:list-ordered"></iconify-icon> Top 10 hikers</h3>
                            <span class="hc-pill">By summits</span>
                        </div>
                        <div class="hc-lb-list">
                            @foreach($topTen as $row)
                                @php
                                    $rank = $row['rank'];
                                    $isMe = $row['id'] === $user->id;
                                    $rowCls = $isMe ? 'is-me' : '';
                                    if ($rank === 1) $rowCls .= ' is-top1';
                                    elseif ($rank === 2) $rowCls .= ' is-top2';
                                    elseif ($rank === 3) $rowCls .= ' is-top3';
                                    $pct = max(8, round(($row['hikes_completed'] / $maxHikes) * 100));
                                @endphp
                                <div class="hc-lb-row {{ trim($rowCls) }}">
                                    <div class="hc-lb-rank">{{ $rank }}</div>
                                    <div class="hc-lb-avatar" style="{{ $row['profile_picture'] ? 'background-image:url('.$row['profile_picture'].')' : '' }}">
                                        {{ $row['profile_picture'] ? '' : $row['initials'] }}
                                    </div>
                                    <div class="hc-lb-info">
                                        <div class="hc-lb-name">
                                            {{ $row['full_name'] }}
                                            @if($isMe)<span class="me-pill">You</span>@endif
                                            @if($rank === 1)<iconify-icon icon="lucide:crown" style="color:#f59e0b;font-size:14px;vertical-align:text-bottom;" title="Champion"></iconify-icon>@endif
                                        </div>
                                        <div class="hc-lb-meta">
                                            <span><iconify-icon icon="lucide:clock"></iconify-icon> {{ $row['total_hours'] }}h on trail</span>
                                            <span><iconify-icon icon="lucide:trending-up"></iconify-icon> {{ number_format($row['total_elevation']) }}m climbed</span>
                                            @if($row['joined_at'])
                                                <span><iconify-icon icon="lucide:calendar"></iconify-icon> Hiking since {{ \Illuminate\Support\Carbon::parse($row['joined_at'])->format('M Y') }}</span>
                                            @endif
                                        </div>
                                        <div class="hc-rank-meter" style="margin-top:6px;"><span style="width:{{ $pct }}%;"></span></div>
                                    </div>
                                    <div class="hc-lb-count">
                                        {{ $row['hikes_completed'] }}
                                        <small>{{ Str::plural('hike', $row['hikes_completed']) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($myRank && $myRank > 10 && $myRow)
                            <div style="margin-top:14px;padding-top:14px;border-top:1px dashed var(--line);">
                                <div style="font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">Your position</div>
                                <div class="hc-lb-row is-me">
                                    <div class="hc-lb-rank">{{ $myRank }}</div>
                                    <div class="hc-lb-avatar" style="{{ $myRow['profile_picture'] ? 'background-image:url('.$myRow['profile_picture'].')' : '' }}">
                                        {{ $myRow['profile_picture'] ? '' : $myRow['initials'] }}
                                    </div>
                                    <div class="hc-lb-info">
                                        <div class="hc-lb-name">
                                            {{ $myRow['full_name'] }}
                                            <span class="me-pill">You</span>
                                        </div>
                                        <div class="hc-lb-meta">
                                            <span><iconify-icon icon="lucide:clock"></iconify-icon> {{ $myRow['total_hours'] }}h on trail</span>
                                            <span><iconify-icon icon="lucide:trending-up"></iconify-icon> {{ number_format($myRow['total_elevation']) }}m climbed</span>
                                        </div>
                                    </div>
                                    <div class="hc-lb-count">
                                        {{ $myRow['hikes_completed'] }}
                                        <small>{{ Str::plural('hike', $myRow['hikes_completed']) }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- HOW RANKING WORKS --}}
                    <div class="hc-panel" style="margin-top:18px;">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:info"></iconify-icon> How ranking works</h3>
                            <span class="hc-pill">Fair play</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;">
                            <div style="padding:14px;border-radius:14px;background:var(--bg);border:1px solid var(--line);">
                                <div style="font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--hc-forest);margin-bottom:6px;display:inline-flex;align-items:center;gap:6px;">
                                    <iconify-icon icon="lucide:flag-triangle-right"></iconify-icon> 1. Summits
                                </div>
                                <div style="font-size:13px;color:var(--text);font-weight:600;">Every completed hike counts as one summit. Most summits = highest rank.</div>
                            </div>
                            <div style="padding:14px;border-radius:14px;background:var(--bg);border:1px solid var(--line);">
                                <div style="font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--hc-forest);margin-bottom:6px;display:inline-flex;align-items:center;gap:6px;">
                                    <iconify-icon icon="lucide:clock"></iconify-icon> 2. Hours
                                </div>
                                <div style="font-size:13px;color:var(--text);font-weight:600;">Tied on summits? More hours logged on trail wins the tiebreaker.</div>
                            </div>
                            <div style="padding:14px;border-radius:14px;background:var(--bg);border:1px solid var(--line);">
                                <div style="font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--hc-forest);margin-bottom:6px;display:inline-flex;align-items:center;gap:6px;">
                                    <iconify-icon icon="lucide:trending-up"></iconify-icon> 3. Elevation
                                </div>
                                <div style="font-size:13px;color:var(--text);font-weight:600;">Still tied? Total vertical climb across all summits decides the order.</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="view-section" id="view-trail-plan">
                <section class="grid">
                    <article class="card">
                        <h3>Suggested Hike Flow</h3>
                        <div class="trail-list">
                            @forelse($trailMountain?->trail_plan ?? [] as $step)
                            <div class="trail-step">
                                <strong>{{ $step['title'] ?? '' }}</strong>
                                <span>{{ $step['body'] ?? '' }}</span>
                            </div>
                            @empty
                            <p style="color:var(--muted);font-size:14px;">Trail steps will appear here once mountains are configured.</p>
                            @endforelse
                        </div>
                    </article>

                    <aside class="card">
                        <h3>Mountain Gear Checklist</h3>
                        <ul class="gear-list">
                            @forelse($trailMountain?->trail_gear_list ?? [] as $line)
                            <li>{{ $line }}</li>
                            @empty
                            <li style="color:var(--muted);">Add gear items to this mountain in the database.</li>
                            @endforelse
                        </ul>
                    </aside>
                </section>
            </div>

            <div class="view-section" id="view-community-chat">
                {{-- Post Creator --}}
                <div class="ns-post-creator">
                    <h3>Share Your Adventure</h3>
                    <label class="ns-form-label" style="display:block;margin-bottom:8px;font-size:13px;color:var(--muted);">Tag mountain (optional)</label>
                    <select id="community-mountain" class="ns-form-select" style="max-width:420px;margin-bottom:12px;">
                        <option value="">— None —</option>
                        @foreach($mountains as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                    <textarea class="ns-post-input" placeholder="Share your hiking experience, photos, or trail updates with the community..."></textarea>
                    <div class="ns-post-controls">
                        <div class="ns-post-actions-row">
                            <button type="button" class="ns-post-action">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                Photo
                            </button>
                            <button type="button" class="ns-post-action">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m8 3 4 8 5-5 5 15H2L8 3z"></path></svg>
                                Tag Mountain
                            </button>
                        </div>
                        <button type="button" class="ns-post-submit" onclick="createPost()">Post</button>
                    </div>
                </div>

                {{-- Community Feed --}}
                <div class="ns-community-feed">
                    @forelse($communityPosts as $post)
                    <div class="ns-post-card">
                        <div class="ns-post-header">
                            <div class="ns-post-avatar" style="background:{{ $post->avatar_gradient ?? 'linear-gradient(135deg,#065f46,#10b981)' }};">{{ $post->author_initials }}</div>
                            <div class="ns-post-user-info">
                                <strong>{{ $post->author_name }}</strong>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="ns-post-body">
                            {{ $post->body }}
                            @if($post->mountain)
                            <div class="ns-post-mountain-tag"><iconify-icon icon="lucide:mountain" style="margin-right:2px; vertical-align: text-bottom;"></iconify-icon> {{ $post->mountain->name }}</div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p style="color:var(--muted);padding:16px;">No community posts yet. Be the first to share an update.</p>
                    @endforelse
                </div>
            </div>

            @php
                $maskEmail = $user->email;
                if (str_contains($maskEmail, '@')) {
                    [$local, $domain] = explode('@', $maskEmail, 2);
                    $maskEmail = (strlen($local) <= 2 ? $local : substr($local, 0, 2).str_repeat('•', max(0, strlen($local) - 2))).'@'.$domain;
                }
            @endphp
            <div class="view-section" id="view-settings">
                <section>
                    <article class="card">
                        <h3>Account Settings</h3>
                        <p style="color: var(--muted); margin-bottom: 24px; font-size: 14px;">Manage your profile details and preferences.</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <!-- Profile Picture Upload -->
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div id="settings-avatar-preview" style="width: 80px; height: 80px; border-radius: 50%; background: var(--bg); display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--line); color: var(--muted);">
                                    @if($user->profile_picture_url)
                                        <img src="{{ $user->profile_picture_url }}" alt="" width="80" height="80" style="width:100%;height:100%;object-fit:cover;display:block;">
                                    @else
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    @endif
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <label style="cursor: pointer; background: var(--brand-soft); color: var(--brand-dark); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-block; width: max-content; border: 1px solid var(--brand-soft);">
                                        Upload new picture
                                        <input type="file" id="profile-picture-input" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp" style="display: none;">
                                    </label>
                                    <span id="profile-picture-status" style="font-size: 12px; color: var(--muted);">JPG, PNG, GIF or WebP. Max 2MB.</span>
                                </div>
                            </div>

                            <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                Account role
                                <input type="text" value="{{ $user->role_label }}" readonly tabindex="-1" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--bg); color: var(--text); outline: none;">
                            </label>
                            <p style="font-size: 12px; color: var(--muted); margin: -12px 0 0;">Tour guide and admin roles are assigned by HikeConnect staff.</p>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                    First name
                                    <input type="text" id="settings-first-name" value="{{ $user->first_name }}" autocomplete="given-name" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--panel); color: var(--text); outline: none;">
                                </label>
                                <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                    Last name
                                    <input type="text" id="settings-last-name" value="{{ $user->last_name }}" autocomplete="family-name" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--panel); color: var(--text); outline: none;">
                                </label>
                            </div>
                            
                            <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                Email Address
                                <input type="email" value="{{ $user->email }}" readonly tabindex="-1" placeholder="Your email" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--bg); color: var(--text); outline: none;">
                            </label>

                            <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                Phone
                                <input type="text" id="settings-phone" value="{{ $user->phone ?? '' }}" autocomplete="tel" placeholder="+63…" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--panel); color: var(--text); outline: none;">
                            </label>

                            <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                Bio
                                <textarea id="settings-bio" placeholder="Tell others about your hiking style…" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--panel); color: var(--text); outline: none; min-height: 100px; resize: vertical; font-family: inherit; line-height: 1.5;">{{ $user->bio ?? '' }}</textarea>
                            </label>

                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <span style="font-size: 13px; font-weight: 600; color: var(--text);">Password</span>
                                <button type="button" id="settings-change-pw-toggle" style="padding: 10px 16px; background: var(--bg); color: var(--text); border: 1px solid var(--line); border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px; width: max-content;">Change password</button>
                                <div id="settings-password-panel" style="display: none; flex-direction: column; gap: 12px; border: 1px solid var(--line); border-radius: 12px; padding: 16px; background: var(--bg);">
                                    <p style="font-size: 13px; color: var(--muted); margin: 0; line-height: 1.5;">We’ll email a 6-digit code to <strong>{{ $maskEmail }}</strong> (no need to type your email). Then enter the code and your new password.</p>
                                    <button type="button" id="settings-pw-send-code" class="mountain-cta" style="width: max-content; padding: 10px 18px;">Send code to my email</button>
                                    <span id="settings-pw-send-status" style="font-size: 12px; color: var(--muted); min-height: 16px;"></span>
                                    <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                        Code from email
                                        <input type="text" id="settings-pw-code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="000000" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; letter-spacing: 0.2em; background: var(--panel); color: var(--text); outline: none;">
                                    </label>
                                    <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                        New password
                                        <input type="password" id="settings-pw-new" autocomplete="new-password" minlength="8" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--panel); color: var(--text); outline: none;">
                                    </label>
                                    <label style="display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text);">
                                        Confirm new password
                                        <input type="password" id="settings-pw-new-confirmation" autocomplete="new-password" minlength="8" style="padding: 12px 14px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: var(--panel); color: var(--text); outline: none;">
                                    </label>
                                    <span id="settings-pw-form-status" style="font-size: 12px; color: var(--muted); min-height: 16px;"></span>
                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                        <button type="button" id="settings-pw-submit" class="mountain-cta" style="padding: 10px 18px;">Update password</button>
                                        <button type="button" id="settings-pw-cancel" style="padding: 10px 16px; background: var(--panel); color: var(--text); border: 1px solid var(--line); border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">Cancel</button>
                                    </div>
                                </div>
                            </div>

                            <span id="settings-profile-status" style="font-size: 13px; color: var(--muted); min-height: 18px;"></span>
                            <button type="button" id="settings-save-profile" class="mountain-cta" style="margin-top: 0; width: auto; align-self: flex-start; padding: 12px 24px;">Save profile</button>
                        </div>
                    </article>
                </section>
            </div>

            {{-- === Include New Sections === --}}
            @include('hikers._new-sections')
        </main>
    </div>


    {{-- === Include New Section Styles === --}}
    @include('hikers._new-styles')

    <style>
        .hc-review-overlay[hidden] { display: none !important; }
        .hc-review-overlay {
            position: fixed;
            inset: 0;
            z-index: 12000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(7, 17, 15, 0.56);
            backdrop-filter: blur(4px);
        }
        .hc-review-modal {
            width: min(440px, 96vw);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: #1f222a;
            color: #f5f5f5;
            box-shadow: 0 20px 48px rgba(0, 0, 0, 0.4);
            padding: 1.25rem 1.25rem 1rem;
            position: relative;
        }
        .hc-review-close {
            position: absolute;
            top: .5rem;
            right: .55rem;
            border: none;
            background: transparent;
            color: #9ca3af;
            font-size: 1.25rem;
            cursor: pointer;
            line-height: 1;
        }
        .hc-review-close:hover { color: #fff; }
        .hc-review-title {
            margin: 0 0 .35rem;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: -0.02em;
        }
        .hc-review-sub {
            margin: 0 0 1.05rem;
            text-align: center;
            color: #d1d5db;
            font-size: 1.125rem;
        }
        .hc-review-choices {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .hc-review-choice {
            width: 78px;
            height: 78px;
            border-radius: 999px;
            border: 2px solid transparent;
            background: #d1d5db;
            color: #3f3f46;
            font-size: 2.3rem;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .hc-review-choice:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.35);
        }
        .hc-review-choice.is-selected {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }
        .hc-review-feedback {
            min-height: 1rem;
            margin: 0 0 .7rem;
            text-align: center;
            color: #a7f3d0;
            font-size: .82rem;
        }
        .hc-review-footer {
            display: flex;
            align-items: center;
            gap: .6rem;
            color: #d1d5db;
            font-size: .82rem;
        }
        .hc-review-footer input { accent-color: #10b981; }
    </style>

    <div id="hiker-experience-review" class="hc-review-overlay" hidden aria-hidden="true">
        <div class="hc-review-modal" role="dialog" aria-modal="true" aria-labelledby="hc-review-title">
            <button type="button" class="hc-review-close" id="hc-review-close" aria-label="Close">×</button>
            <h2 id="hc-review-title" class="hc-review-title">How's the call go?</h2>
            <p class="hc-review-sub">Tell us about your call experience.</p>
            <div class="hc-review-choices" role="group" aria-label="Experience rating">
                <button type="button" class="hc-review-choice" data-review-score="bad" aria-label="Bad experience">☹</button>
                <button type="button" class="hc-review-choice" data-review-score="okay" aria-label="Okay experience">😐</button>
                <button type="button" class="hc-review-choice" data-review-score="great" aria-label="Great experience">😄</button>
            </div>
            <p id="hc-review-feedback" class="hc-review-feedback"></p>
            <label class="hc-review-footer">
                <input type="checkbox" id="hc-review-hide">
                <span>Don't show again</span>
            </label>
        </div>
    </div>

    <div class="tg-toast" id="hcHikerToast" role="status" aria-live="polite" aria-atomic="true"></div>

    @php
        $__hikerBootstrap = [
            'csrf' => csrf_token(),
            'userId' => $user->id,
            'hasGoogleMapsKey' => filled(config('services.google_maps.key')),
            'mapsFallbackCenter' => ['lat' => 12.8797, 'lng' => 121.7740],
            'weather' => ($weatherLat !== null && $weatherLng !== null)
                ? ['lat' => $weatherLat, 'lng' => $weatherLng]
                : null,
            'jumpoffMarkers' => $jumpoffMarkers,
            'defaultJumpoff' => $defaultJumpoff,
            'activeMountainId' => $upcoming?->mountain_id ?? $trailMountain?->id,
            'activeBookingId' => $upcoming?->id,
            'routes' => [
                'storeBooking' => url('/hikers/bookings'),
                'storeReview' => url('/hikers/reviews'),
                'storeGuideReview' => url('/hikers/guide-reviews'),
                'storeCommunityPost' => url('/hikers/community-posts'),
                'triggerSos' => url('/hikers/sos'),
                'cancelBookingPrefix' => url('/hikers/bookings'),
                'checkInScanPrefix' => url('/hikers/bookings'),
                'checkOutScanPrefix' => url('/hikers/bookings'),
                'updateProfilePicture' => url('/hikers/profile/picture'),
                'updateProfile' => url('/hikers/profile'),
                'sendPasswordChangeCode' => url('/hikers/profile/password/send-code'),
                'updatePasswordWithCode' => url('/hikers/profile/password'),
                'achievementClaimBase' => url('/hikers/achievements'),
                'storeExperienceFeedback' => url('/hikers/experience-feedback'),
            ],
            'hasSubmittedExperienceFeedback' => (bool) $hasSubmittedExperienceFeedback,
        ];
    @endphp
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <!-- Interactive Logic -->
    <script>
        // ============================================================
        // HikeConnect — Complete Dashboard Logic
        // ============================================================

        window.HIKER_BOOTSTRAP = @json($__hikerBootstrap);

        const hcToastEl = document.getElementById('hcHikerToast');
        function hcToast(msg, isErr) {
            if (!hcToastEl || !msg) return;
            hcToastEl.textContent = msg;
            hcToastEl.classList.toggle('error', !!isErr);
            hcToastEl.setAttribute('aria-live', isErr ? 'assertive' : 'polite');
            hcToastEl.classList.add('show');
            clearTimeout(hcToast._t);
            const ms = isErr ? 4200 : 2800;
            hcToast._t = setTimeout(function() { hcToastEl.classList.remove('show'); }, ms);
        }

        const mountainData = @json($mountainData);
        const guideData = @json($guideData);
        const hasGoogleMapsKey = @json(filled(config('services.google_maps.key')));

        let currentMountain = @json($trailMountain?->slug);
        let googleMapsReadyResolve = null;
        const googleMapsReadyPromise = hasGoogleMapsKey
            ? new Promise((resolve) => {
                googleMapsReadyResolve = resolve;
            })
            : Promise.resolve(false);
        const trailSimulationState = {
            map: null,
            mountainId: null,
            animationBound: false,
        };

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function buildBookingPriceReceiptClient(mountain, hikersCount) {
            if (!mountain || !mountain.pricing) return null;
            const headCount = Number.isFinite(Number(hikersCount)) ? Math.max(1, Number(hikersCount)) : 1;
            const p = mountain.pricing;
            const lines = [
                { label: 'Registration fee', amount: (Number(p.registrationFeePerPerson || 0) * headCount) },
                { label: 'Environmental fee', amount: (Number(p.environmentalFeePerPerson || 0) * headCount) },
                { label: 'Local trail fee', amount: (Number(p.localFeePerPerson || 0) * headCount) },
                { label: 'Guide fee (per person)', amount: (Number(p.guideFeePerPerson || 0) * headCount) },
                { label: 'Guide fee (per group)', amount: Number(p.guideFeePerGroup || 0) },
            ];
            const total = lines.reduce((sum, line) => sum + (Number(line.amount) || 0), 0);
            return { lines, total, sourceNote: p.sourceNote || '', lastVerifiedOn: p.lastVerifiedOn || '' };
        }

        function formatPhpCurrency(value) {
            if (!Number.isFinite(Number(value))) return '—';
            return 'PHP ' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function formatDayLabel(dateString, index) {
            if (!dateString) return index === 0 ? 'Today' : 'Day ' + (index + 1);
            if (index === 0) return 'Today';
            if (index === 1) return 'Tomorrow';
            const date = new Date(dateString + 'T00:00:00');
            return date.toLocaleDateString(undefined, { weekday: 'long' });
        }

        function renderTrailPreview(trailMap, mountain, experience = null) {
            const pathEl = document.getElementById('detail-route-preview-line');
            const outlineEl = document.getElementById('detail-route-preview-outline');
            const startEl = document.getElementById('detail-route-preview-start');
            const midEl = document.getElementById('detail-route-preview-mid');
            const endEl = document.getElementById('detail-route-preview-end');
            if (!pathEl || !outlineEl || !startEl || !midEl || !endEl) return;

            const jumpOffPoint = mountain && mountain.jumpoff
                ? { lat: Number(mountain.jumpoff.lat), lng: Number(mountain.jumpoff.lng) }
                : null;
            const summitPoint = mountain && mountain.summit
                ? { lat: Number(mountain.summit.lat), lng: Number(mountain.summit.lng) }
                : null;
            const routeEndPoint = experience && experience.routeEnd
                ? { lat: Number(experience.routeEnd.lat), lng: Number(experience.routeEnd.lng) }
                : null;
            const routeEndIsDistinct = Number.isFinite(routeEndPoint?.lat)
                && Number.isFinite(routeEndPoint?.lng)
                && Number.isFinite(summitPoint?.lat)
                && Number.isFinite(summitPoint?.lng)
                && (Math.abs(routeEndPoint.lat - summitPoint.lat) + Math.abs(routeEndPoint.lng - summitPoint.lng) > 0.0005);
            const rawPath = trailMap && Array.isArray(trailMap.path) ? trailMap.path : [];
            let points = rawPath
                .map((point) => ({
                    lat: Number(point.lat),
                    lng: Number(point.lng),
                }))
                .filter((point) => Number.isFinite(point.lat) && Number.isFinite(point.lng));

            if (routeEndIsDistinct && points.length > 1) {
                let summitIndex = 0;
                let summitDistance = Infinity;
                points.forEach((point, index) => {
                    const score = Math.abs(point.lat - summitPoint.lat) + Math.abs(point.lng - summitPoint.lng);
                    if (score < summitDistance) {
                        summitDistance = score;
                        summitIndex = index;
                    }
                });
                points = points.slice(0, summitIndex + 1);
            }

            if (points.length < 2) {
                pathEl.setAttribute('d', '');
                outlineEl.setAttribute('d', '');
                return;
            }

            const width = 260;
            const height = 160;
            const padding = 16;
            const effectiveJumpOffPoint = jumpOffPoint ?? points[0];
            const effectiveSummitPoint = summitPoint ?? points[points.length - 1];
            const effectiveRouteEndPoint = routeEndIsDistinct ? effectiveSummitPoint : (routeEndPoint ?? points[points.length - 1]);
            const framePoints = [...points, effectiveJumpOffPoint, effectiveSummitPoint, effectiveRouteEndPoint].filter((point) => Number.isFinite(point.lat) && Number.isFinite(point.lng));
            const minLat = Math.min(...framePoints.map((point) => point.lat));
            const maxLat = Math.max(...framePoints.map((point) => point.lat));
            const minLng = Math.min(...framePoints.map((point) => point.lng));
            const maxLng = Math.max(...framePoints.map((point) => point.lng));
            const lngSpan = Math.max(0.0001, maxLng - minLng);
            const latSpan = Math.max(0.0001, maxLat - minLat);
            const usableWidth = width - padding * 2;
            const usableHeight = height - padding * 2;

            const projectPoint = (point) => {
                const x = padding + ((point.lng - minLng) / lngSpan) * usableWidth;
                const y = padding + ((maxLat - point.lat) / latSpan) * usableHeight;
                return { x, y };
            };
            const projected = points.map(projectPoint);

            const d = projected.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x.toFixed(1)} ${point.y.toFixed(1)}`).join(' ');
            const summitIsDistinct = Number.isFinite(effectiveSummitPoint.lat)
                && Number.isFinite(effectiveSummitPoint.lng)
                && (Math.abs(effectiveSummitPoint.lat - effectiveJumpOffPoint.lat) + Math.abs(effectiveSummitPoint.lng - effectiveJumpOffPoint.lng) > 0.0005)
                && (Math.abs(effectiveSummitPoint.lat - effectiveRouteEndPoint.lat) + Math.abs(effectiveSummitPoint.lng - effectiveRouteEndPoint.lng) > 0.0005);
            const midPoint = summitIsDistinct
                ? projectPoint(effectiveSummitPoint)
                : projected[Math.floor(projected.length / 2)];
            const startPoint = projectPoint(effectiveJumpOffPoint);
            const endPoint = projectPoint(effectiveRouteEndPoint);

            pathEl.setAttribute('d', d);
            outlineEl.setAttribute('d', d);

            startEl.setAttribute('cx', startPoint.x.toFixed(1));
            startEl.setAttribute('cy', startPoint.y.toFixed(1));
            midEl.setAttribute('cx', midPoint.x.toFixed(1));
            midEl.setAttribute('cy', midPoint.y.toFixed(1));
            endEl.setAttribute('cx', endPoint.x.toFixed(1));
            endEl.setAttribute('cy', endPoint.y.toFixed(1));
            midEl.style.fill = routeEndIsDistinct ? '#dc2626' : '';
            endEl.style.display = routeEndIsDistinct ? 'none' : '';
        }

        function toFinitePoint(point) {
            if (!point) return null;
            const lat = Number(point.lat);
            const lng = Number(point.lng);
            if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;
            return { lat, lng };
        }

        function haversineDistanceMeters(a, b) {
            if (!a || !b) return 0;
            const toRadians = (value) => value * (Math.PI / 180);
            const earthRadius = 6371000;
            const dLat = toRadians(b.lat - a.lat);
            const dLng = toRadians(b.lng - a.lng);
            const lat1 = toRadians(a.lat);
            const lat2 = toRadians(b.lat);
            const sinLat = Math.sin(dLat / 2);
            const sinLng = Math.sin(dLng / 2);
            const root = sinLat * sinLat + Math.cos(lat1) * Math.cos(lat2) * sinLng * sinLng;
            return 2 * earthRadius * Math.asin(Math.min(1, Math.sqrt(root)));
        }

        function computeTrailDistanceKm(points) {
            return points.reduce((total, point, index) => {
                if (index === 0) return total;
                return total + haversineDistanceMeters(points[index - 1], point);
            }, 0) / 1000;
        }

        function computeHeadingDegrees(from, to) {
            if (!from || !to) return 0;
            const toRadians = (value) => value * (Math.PI / 180);
            const toDegrees = (value) => value * (180 / Math.PI);
            const lat1 = toRadians(from.lat);
            const lat2 = toRadians(to.lat);
            const deltaLng = toRadians(to.lng - from.lng);
            const y = Math.sin(deltaLng) * Math.cos(lat2);
            const x = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(deltaLng);
            return (toDegrees(Math.atan2(y, x)) + 360) % 360;
        }

        function getVisibleTrailPath(mountain, experience = null) {
            const summitPoint = toFinitePoint(mountain && mountain.summit);
            const routeEndPoint = experience && experience.routeEnd ? toFinitePoint(experience.routeEnd) : summitPoint;
            const routeEndIsDistinct = summitPoint
                && routeEndPoint
                && (Math.abs(routeEndPoint.lat - summitPoint.lat) + Math.abs(routeEndPoint.lng - summitPoint.lng) > 0.0005);
            const rawPath = experience && experience.trailMap && Array.isArray(experience.trailMap.path)
                ? experience.trailMap.path
                : [];
            let points = rawPath.map(toFinitePoint).filter(Boolean);

            if (routeEndIsDistinct && points.length > 1) {
                let summitIndex = 0;
                let summitDistance = Infinity;
                points.forEach((point, index) => {
                    const score = Math.abs(point.lat - summitPoint.lat) + Math.abs(point.lng - summitPoint.lng);
                    if (score < summitDistance) {
                        summitDistance = score;
                        summitIndex = index;
                    }
                });
                points = points.slice(0, summitIndex + 1);
            }

            return points;
        }

        function getSimulationTrailPath(mountain, experience = null) {
            const jumpOff = toFinitePoint(mountain && mountain.jumpoff);
            const summit = toFinitePoint(mountain && mountain.summit);
            const points = getVisibleTrailPath(mountain, experience).slice();

            if (jumpOff && (!points.length || haversineDistanceMeters(jumpOff, points[0]) > 90)) {
                points.unshift(jumpOff);
            }

            if (summit && (!points.length || haversineDistanceMeters(points[points.length - 1], summit) > 90)) {
                points.push(summit);
            }

            return points;
        }

        function setTrailSimulationStatus(message, isError = false) {
            const statusEl = document.getElementById('detail-trail-simulation-status');
            if (!statusEl) return;
            statusEl.textContent = message || '';
            statusEl.classList.toggle('is-error', Boolean(isError));
            statusEl.hidden = !message;
        }

        function updateTrailSimulationMeta(mountain, experience, trailPath) {
            const titleEl = document.getElementById('detail-trail-simulation-title');
            const sourceEl = document.getElementById('detail-trail-simulation-source');
            const jumpOffEl = document.getElementById('detail-trail-simulation-jumpoff');
            const summitEl = document.getElementById('detail-trail-simulation-summit');
            const distanceEl = document.getElementById('detail-trail-simulation-distance');
            const distanceKm = trailPath.length > 1
                ? computeTrailDistanceKm(trailPath)
                : Number(experience && experience.distanceKm);
            const safeDistance = Number.isFinite(distanceKm) && distanceKm > 0 ? `${distanceKm.toFixed(1)} km` : '-- km';

            if (titleEl) titleEl.textContent = `${mountain.name} flyover`;
            if (sourceEl) sourceEl.textContent = experience && experience.trailMap && experience.trailMap.sourceLabel ? experience.trailMap.sourceLabel : 'Saved trail line';
            if (jumpOffEl) jumpOffEl.textContent = mountain.jumpoff?.name || 'Jump-off';
            if (summitEl) summitEl.textContent = `${mountain.name} summit`;
            if (distanceEl) distanceEl.textContent = safeDistance;
        }

        function syncTrailSimulationLauncher(mountain) {
            const button = document.getElementById('detail-trail-simulation-btn');
            if (!button) return;
            const experience = mountain && mountain.experience && mountain.experience.enabled ? mountain.experience : null;
            const trailPath = mountain && experience ? getSimulationTrailPath(mountain, experience) : [];
            const canOpen = hasGoogleMapsKey && trailPath.length > 1;
            button.hidden = !canOpen;
        }

        async function ensureGoogleMapsReady(timeoutMs = 12000) {
            if (!hasGoogleMapsKey) return false;
            if (typeof google !== 'undefined' && google.maps && typeof google.maps.importLibrary === 'function') {
                return true;
            }

            return Promise.race([
                googleMapsReadyPromise.then(() => Boolean(typeof google !== 'undefined' && google.maps && typeof google.maps.importLibrary === 'function')),
                new Promise((resolve) => setTimeout(() => resolve(false), timeoutMs)),
            ]);
        }

        function clearTrailSimulationMap() {
            const host = document.getElementById('detail-trail-simulation-map');
            if (trailSimulationState.map && typeof trailSimulationState.map.stopCameraAnimation === 'function') {
                try {
                    trailSimulationState.map.stopCameraAnimation();
                } catch (error) {
                    console.warn('Could not stop 3D trail animation.', error);
                }
            }

            trailSimulationState.map = null;
            trailSimulationState.mountainId = null;
            trailSimulationState.animationBound = false;

            if (host) {
                host.replaceChildren();
            }
        }

        function getTrailSimulationCamera(trailPath, jumpOff, summit) {
            const framePoints = [...trailPath, jumpOff, summit].filter(Boolean);
            const latitudes = framePoints.map((point) => point.lat);
            const longitudes = framePoints.map((point) => point.lng);
            const minLat = Math.min(...latitudes);
            const maxLat = Math.max(...latitudes);
            const minLng = Math.min(...longitudes);
            const maxLng = Math.max(...longitudes);
            const center = {
                lat: (minLat + maxLat) / 2,
                lng: (minLng + maxLng) / 2,
            };
            const northSouth = haversineDistanceMeters({ lat: minLat, lng: center.lng }, { lat: maxLat, lng: center.lng });
            const eastWest = haversineDistanceMeters({ lat: center.lat, lng: minLng }, { lat: center.lat, lng: maxLng });
            const spanMeters = Math.max(northSouth, eastWest, 500);
            const heading = computeHeadingDegrees(jumpOff || trailPath[0], summit || trailPath[trailPath.length - 1]);
            const focusCenter = summit
                ? {
                    lat: center.lat + ((summit.lat - center.lat) * 0.32),
                    lng: center.lng + ((summit.lng - center.lng) * 0.32),
                }
                : center;

            return {
                center,
                focusCenter,
                heading,
                overviewRange: Math.max(2600, Math.min(12000, spanMeters * 3.15)),
                flyRange: Math.max(1800, Math.min(8500, spanMeters * 2.15)),
                orbitRange: Math.max(1500, Math.min(7200, spanMeters * 1.9)),
            };
        }

        async function buildTrailSimulation(mountain) {
            const overlay = document.getElementById('detail-trail-simulation-overlay');
            const host = document.getElementById('detail-trail-simulation-map');
            if (!overlay || !host || !mountain) return;

            const experience = mountain.experience && mountain.experience.enabled ? mountain.experience : null;
            const trailPath = experience ? getSimulationTrailPath(mountain, experience) : [];
            if (trailPath.length < 2) {
                setTrailSimulationStatus('This mountain does not have enough saved trail points yet for a 3D flyover.', true);
                return;
            }

            updateTrailSimulationMeta(mountain, experience, trailPath);
            setTrailSimulationStatus('Loading Google 3D terrain...');

            const apiReady = await ensureGoogleMapsReady();
            if (!apiReady) {
                setTrailSimulationStatus('Google Maps 3D could not load. Check the Maps key or reload the page, then try again.', true);
                return;
            }

            try {
                const {
                    Map3DElement,
                    MapMode,
                    AltitudeMode,
                    Marker3DElement,
                    Polyline3DElement,
                } = await google.maps.importLibrary('maps3d');
                const { PinElement } = await google.maps.importLibrary('marker');

                if (!Map3DElement || !Marker3DElement || !Polyline3DElement || !PinElement) {
                    setTrailSimulationStatus('This browser does not expose the Google 3D map elements needed for the flyover.', true);
                    return;
                }

                clearTrailSimulationMap();

                const jumpOff = toFinitePoint(mountain.jumpoff) || trailPath[0];
                const summit = toFinitePoint(mountain.summit) || trailPath[trailPath.length - 1];
                const camera = getTrailSimulationCamera(trailPath, jumpOff, summit);
                const map = new Map3DElement({
                    center: { lat: camera.center.lat, lng: camera.center.lng, altitude: 220 },
                    range: camera.overviewRange,
                    tilt: 58,
                    heading: camera.heading,
                    mode: MapMode && MapMode.SATELLITE ? MapMode.SATELLITE : 'SATELLITE',
                    gestureHandling: 'GREEDY',
                    defaultUIHidden: false,
                });
                map.style.width = '100%';
                map.style.height = '100%';

                const polyline = new Polyline3DElement({
                    path: trailPath,
                    strokeColor: '#4ade80',
                    outerColor: '#dcfce7',
                    strokeWidth: 7,
                    outerWidth: 0.9,
                    altitudeMode: AltitudeMode && AltitudeMode.CLAMP_TO_GROUND ? AltitudeMode.CLAMP_TO_GROUND : 'CLAMP_TO_GROUND',
                    drawsOccludedSegments: true,
                    geodesic: true,
                });
                map.append(polyline);

                const jumpOffMarker = new Marker3DElement({
                    position: jumpOff,
                });
                jumpOffMarker.append(new PinElement({
                    background: '#2563eb',
                    borderColor: '#ffffff',
                    glyphColor: '#ffffff',
                    scale: 1.05,
                }));
                map.append(jumpOffMarker);

                const summitMarker = new Marker3DElement({
                    position: summit,
                });
                summitMarker.append(new PinElement({
                    background: '#dc2626',
                    borderColor: '#ffffff',
                    glyphColor: '#ffffff',
                    scale: 1.1,
                }));
                map.append(summitMarker);

                host.replaceChildren(map);

                trailSimulationState.map = map;
                trailSimulationState.mountainId = currentMountain;
                trailSimulationState.animationBound = true;

                const flyToCamera = {
                    center: { lat: camera.focusCenter.lat, lng: camera.focusCenter.lng, altitude: 220 },
                    range: camera.flyRange,
                    tilt: 60,
                    heading: camera.heading,
                };
                const orbitCamera = {
                    center: { lat: camera.focusCenter.lat, lng: camera.focusCenter.lng, altitude: 180 },
                    range: camera.orbitRange,
                    tilt: 62,
                    heading: (camera.heading + 18) % 360,
                };

                requestAnimationFrame(() => {
                    setTrailSimulationStatus('Flying over the saved trail route...');
                    map.addEventListener('gmp-animationend', () => {
                        try {
                            map.flyCameraAround({
                                camera: orbitCamera,
                                durationMillis: 9000,
                                repeatCount: 1,
                            });
                            setTrailSimulationStatus('Orbiting the summit. Tap the map any time to stop the flyover.');
                        } catch (error) {
                            console.warn('Could not start summit orbit.', error);
                            setTrailSimulationStatus('');
                        }
                    }, { once: true });

                    map.addEventListener('gmp-click', () => {
                        if (trailSimulationState.map && typeof trailSimulationState.map.stopCameraAnimation === 'function') {
                            try {
                                trailSimulationState.map.stopCameraAnimation();
                            } catch (error) {
                                console.warn('Could not stop 3D trail animation.', error);
                            }
                        }
                        setTrailSimulationStatus('Flyover paused. Use Replay flyover to start again.');
                    });

                    map.flyCameraTo({
                        endCamera: flyToCamera,
                        durationMillis: 6500,
                    });
                });
            } catch (error) {
                console.error('Could not build trail simulation.', error);
                setTrailSimulationStatus('The 3D flyover could not start on this browser or API setup yet.', true);
            }
        }

        window.openTrailSimulation = async function() {
            const mountain = currentMountain ? mountainData[currentMountain] : null;
            if (!mountain) return;

            const overlay = document.getElementById('detail-trail-simulation-overlay');
            if (!overlay) return;

            overlay.hidden = false;
            overlay.setAttribute('aria-hidden', 'false');
            document.body.classList.add('ns-no-scroll');
            await buildTrailSimulation(mountain);
        };

        window.replayTrailSimulation = function() {
            const mountain = currentMountain ? mountainData[currentMountain] : null;
            if (!mountain) return;
            buildTrailSimulation(mountain);
        };

        window.closeTrailSimulation = function() {
            const overlay = document.getElementById('detail-trail-simulation-overlay');
            if (overlay) {
                overlay.hidden = true;
                overlay.setAttribute('aria-hidden', 'true');
            }
            document.body.classList.remove('ns-no-scroll');
            clearTrailSimulationMap();
            setTrailSimulationStatus('Preparing 3D trail view...');
        };

        window.selectDetailTab = function(tabName) {
            document.querySelectorAll('[data-detail-tab]').forEach((button) => {
                const active = button.getAttribute('data-detail-tab') === tabName;
                button.classList.toggle('active', active);
                button.setAttribute('aria-selected', active ? 'true' : 'false');
            });

            document.querySelectorAll('[data-detail-panel]').forEach((panel) => {
                const active = panel.getAttribute('data-detail-panel') === tabName;
                panel.classList.toggle('active', active);
                panel.hidden = !active;
            });
        };

        function renderMountainWeather(mountain) {
            const forecastList = document.getElementById('detail-forecast-list');
            const tempEl = document.getElementById('detail-condition-temp');
            const updatedEl = document.getElementById('detail-weather-updated');
            if (!forecastList || !tempEl || !updatedEl) return;

            forecastList.innerHTML = `
                <div class="ns-forecast-row">
                    <strong>Loading</strong>
                    <div class="ns-forecast-range"><i style="left:0%;width:35%;"></i></div>
                    <span>--° / --°</span>
                </div>
            `;
            tempEl.textContent = '--°';
            updatedEl.textContent = 'Weekly outlook';

            const weather = mountain && mountain.weather ? mountain.weather : null;
            if (!weather || weather.lat == null || weather.lng == null) return;

            const url = `https://api.open-meteo.com/v1/forecast?latitude=${encodeURIComponent(weather.lat)}&longitude=${encodeURIComponent(weather.lng)}&current_weather=true&daily=temperature_2m_max,temperature_2m_min&forecast_days=7&timezone=auto`;
            fetch(url)
                .then((response) => response.json())
                .then((data) => {
                    const current = data && data.current_weather ? data.current_weather : null;
                    if (current && typeof current.temperature === 'number') {
                        tempEl.textContent = `${Math.round(current.temperature)}°`;
                    }

                    const daily = data && data.daily ? data.daily : null;
                    if (!daily || !Array.isArray(daily.time) || !Array.isArray(daily.temperature_2m_max) || !Array.isArray(daily.temperature_2m_min)) {
                        forecastList.innerHTML = '<div class="ns-panel-empty">Forecast unavailable right now.</div>';
                        return;
                    }

                    const highs = daily.temperature_2m_max.map((value) => Number(value));
                    const lows = daily.temperature_2m_min.map((value) => Number(value));
                    const globalMin = Math.min(...lows);
                    const globalMax = Math.max(...highs);
                    const span = Math.max(1, globalMax - globalMin);

                    forecastList.innerHTML = daily.time.map((dateString, index) => {
                        const low = lows[index];
                        const high = highs[index];
                        const left = ((low - globalMin) / span) * 100;
                        const width = Math.max(10, ((high - low) / span) * 100);

                        return `
                            <div class="ns-forecast-row">
                                <strong>${escapeHtml(formatDayLabel(dateString, index))}</strong>
                                <div class="ns-forecast-range"><i style="left:${left}%;width:${width}%;"></i></div>
                                <span>${Math.round(low)}° / ${Math.round(high)}°</span>
                            </div>
                        `;
                    }).join('');

                    updatedEl.textContent = '7-day outlook';
                })
                .catch(() => {
                    forecastList.innerHTML = '<div class="ns-panel-empty">Forecast unavailable right now.</div>';
                    updatedEl.textContent = 'Weather feed unavailable';
                });
        }

        function renderMountainSpotlight(mountain) {
            const spotlightEl = document.getElementById('detail-spotlight');
            if (!spotlightEl) return;

            const experience = mountain && mountain.experience ? mountain.experience : null;
            if (!experience || !experience.enabled) {
                spotlightEl.hidden = true;
                return;
            }

            spotlightEl.hidden = false;
            document.getElementById('detail-spotlight-title').textContent = `${mountain.name} trail`;
            document.getElementById('detail-spotlight-subtitle').textContent = experience.subtitle || mountain.description || '';
            document.getElementById('detail-meta-rating').textContent = Number(mountain.reviews?.average ?? mountain.rating ?? 0).toFixed(1);
            document.getElementById('detail-meta-region').textContent = experience.region || mountain.location || 'Batangas';

            const gallery = Array.isArray(experience.gallery) ? experience.gallery : [];
            const primary = gallery[0] || {};
            const secondary = gallery[1] || primary;
            const primaryImage = document.getElementById('detail-gallery-primary');
            const secondaryImage = document.getElementById('detail-gallery-secondary');

            if (primaryImage && primary.image) primaryImage.src = primary.image;
            if (secondaryImage && secondary.image) secondaryImage.src = secondary.image;
            document.getElementById('detail-gallery-primary-label').textContent = primary.label || 'Primary view';
            document.getElementById('detail-gallery-primary-accent').textContent = primary.accent || 'Featured scene';
            document.getElementById('detail-gallery-secondary-label').textContent = secondary.label || 'Secondary view';
            document.getElementById('detail-gallery-secondary-accent').textContent = secondary.accent || 'Trail scene';

            document.getElementById('detail-spotlight-distance').textContent = `${experience.distanceKm ?? '--'} km`;
            document.getElementById('detail-spotlight-elevation').textContent = `${experience.elevationGainM ?? '--'} m`;
            document.getElementById('detail-spotlight-route').textContent = experience.routeType || '--';
            document.getElementById('detail-spotlight-story').textContent = mountain.description || experience.subtitle || '';

            const highlightList = document.getElementById('detail-highlight-list');
            highlightList.innerHTML = (experience.highlights || []).map((highlight) => `
                <div class="ns-highlight-item">
                    <iconify-icon icon="lucide:check-circle-2"></iconify-icon>
                    <span>${escapeHtml(highlight)}</span>
                </div>
            `).join('');

            const topSights = document.getElementById('detail-top-sights');
            topSights.innerHTML = (experience.topSights || []).map((sight) => `
                <div class="ns-top-sight">
                    <div class="ns-top-sight-copy">
                        <strong>${escapeHtml(sight.name)}</strong>
                        <span>${escapeHtml(sight.type)}</span>
                        <p>${escapeHtml(sight.description)}</p>
                    </div>
                    <iconify-icon icon="lucide:chevron-right"></iconify-icon>
                </div>
            `).join('');

            const routeMarkers = document.getElementById('detail-route-markers');
            routeMarkers.innerHTML = (experience.routeMarkers || []).map((marker) => `
                <div class="ns-route-marker">
                    <strong>${escapeHtml(marker.name)}</strong>
                    <span>${escapeHtml(marker.detail)}</span>
                </div>
            `).join('');
            renderTrailPreview(experience.trailMap || null, mountain, experience);

            const conditions = experience.conditions || {};
            document.getElementById('detail-condition-crowd').textContent = `Crowd: ${conditions.crowdLabel || '--'}`;
            document.getElementById('detail-condition-shade').textContent = `Shade: ${conditions.shadeLabel || '--'}`;
            document.getElementById('detail-condition-surface').textContent = `Surface: ${conditions.surfaceLabel || '--'}`;
            document.getElementById('detail-condition-summary').textContent = conditions.summary || 'Trail notes unavailable.';
            document.getElementById('detail-condition-badge').textContent = 'Trail guidance';
            document.getElementById('detail-condition-tips').innerHTML = (conditions.tips || []).map((tip) => `
                <div class="ns-condition-tip">
                    <iconify-icon icon="lucide:leaf"></iconify-icon>
                    <span>${escapeHtml(tip)}</span>
                </div>
            `).join('');
            renderMountainWeather(mountain);
            window.selectDetailTab('overview');
        }

        function initExperienceReviewPopup() {
            const overlay = document.getElementById('hiker-experience-review');
            if (!overlay) return;
            const params = new URLSearchParams(window.location.search);
            const isFirstLogin = params.get('first_login') === '1';
            if (isFirstLogin) {
                params.delete('first_login');
                const next = window.location.pathname
                    + (params.toString() ? '?' + params.toString() : '')
                    + window.location.hash;
                if (window.history && window.history.replaceState) {
                    window.history.replaceState({}, '', next);
                }
                return;
            }

            const hideKey = 'hikeconnect_experience_review_hide';
            const scoreKey = 'hikeconnect_experience_review_last_score';
            const closeBtn = document.getElementById('hc-review-close');
            const hideToggle = document.getElementById('hc-review-hide');
            const feedback = document.getElementById('hc-review-feedback');
            const choices = overlay.querySelectorAll('.hc-review-choice');
            const endpoint = window.HIKER_BOOTSTRAP?.routes?.storeExperienceFeedback;
            const hasSubmittedExperienceFeedback = !!window.HIKER_BOOTSTRAP?.hasSubmittedExperienceFeedback;

            if (hasSubmittedExperienceFeedback) return;
            if (localStorage.getItem(hideKey) === '1') return;

            const closePopup = () => {
                if (hideToggle && hideToggle.checked) {
                    localStorage.setItem(hideKey, '1');
                }
                overlay.hidden = true;
                overlay.setAttribute('aria-hidden', 'true');
            };

            setTimeout(() => {
                overlay.hidden = false;
                overlay.setAttribute('aria-hidden', 'false');
            }, 700);

            if (closeBtn) {
                closeBtn.addEventListener('click', closePopup);
            }

            overlay.addEventListener('click', (event) => {
                if (event.target === overlay) closePopup();
            });

            const sendFeedback = (score) => {
                if (!endpoint) {
                    return Promise.resolve(false);
                }

                const fd = new FormData();
                fd.append('_token', window.HIKER_BOOTSTRAP.csrf);
                fd.append('score', score);
                fd.append('dont_show_again', hideToggle && hideToggle.checked ? '1' : '0');
                fd.append('context', 'hiker_dashboard_login');

                return fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: fd,
                })
                    .then((response) => response.ok)
                    .catch(() => false);
            };

            choices.forEach((button) => {
                button.addEventListener('click', async () => {
                    choices.forEach((b) => b.classList.remove('is-selected'));
                    button.classList.add('is-selected');
                    const score = button.getAttribute('data-review-score') || 'okay';
                    localStorage.setItem(scoreKey, score);
                    if (feedback) feedback.textContent = 'Saving your feedback...';
                    const saved = await sendFeedback(score);
                    if (feedback) feedback.textContent = saved
                        ? 'Thanks! Your experience was recorded.'
                        : 'Saved locally. We will retry next login.';
                    if (saved) {
                        localStorage.setItem(hideKey, '1');
                    }
                    setTimeout(closePopup, 700);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initExperienceReviewPopup();
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    const overlay = document.getElementById('detail-trail-simulation-overlay');
                    if (overlay && !overlay.hidden) {
                        closeTrailSimulation();
                    }
                }
            });

            const wx = window.HIKER_BOOTSTRAP.weather;
            if (wx && wx.lat != null && wx.lng != null) {
                fetch(`https://api.open-meteo.com/v1/forecast?latitude=${wx.lat}&longitude=${wx.lng}&current_weather=true`)
                    .then(r => r.json())
                    .then(d => { if (d && d.current_weather) { const t = Math.round(d.current_weather.temperature); document.querySelectorAll('.weather-chip').forEach(c => c.textContent = t + '°C'); } })
                    .catch(() => {});
            }

            // Section navigation
            const menuLinks = document.querySelectorAll('.menu-item');
            const sections = {
                'home': document.getElementById('view-dashboard'),
                'achievements': document.getElementById('view-achievements'),
                'mountain-overview': document.getElementById('view-mountain-overview'),
                'mountain-detail': document.getElementById('view-mountain-detail'),
                'tour-guides': document.getElementById('view-tour-guides'),
                'book-hike': document.getElementById('view-book-hike'),
                'bookings': document.getElementById('view-bookings'),
                'track-location': document.getElementById('view-track-location'),
                'what-to-bring': document.getElementById('view-what-to-bring'),
                'hiking-history': document.getElementById('view-hiking-history'),
                'leaderboard': document.getElementById('view-leaderboard'),
                'trail-plan': document.getElementById('view-trail-plan'),
                'community-chat': document.getElementById('view-community-chat'),
                'settings': document.getElementById('view-settings'),
                'safety-alerts': document.getElementById('view-safety-alerts')
            };

            window.showView = function(targetId) {
                Object.values(sections).forEach(s => { if (s) s.classList.remove('active'); });
                menuLinks.forEach(l => l.classList.remove('active'));
                const rawId = targetId.replace('#', '') || 'home';
                let sec = sections[rawId];
                if (!sec) sec = sections['home'];
                if (sec) sec.classList.add('active');
                menuLinks.forEach(l => {
                    const h = l.getAttribute('href');
                    if (h === targetId || h === '#' + rawId) l.classList.add('active');
                    else if (rawId === 'home' && h === '#') l.classList.add('active');
                });
                document.querySelector('.layout').classList.remove('mobile-open');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                // Hide booking success if navigating away
                const bs = document.getElementById('booking-success');
                if (bs) bs.style.display = 'none';
                // Google Maps was hidden in a display:none section — reflow tiles when Track Location is shown
                if (rawId === 'track-location' && typeof google !== 'undefined' && google.maps && window.hikeTracker && window.hikeTracker.map) {
                    setTimeout(function() {
                        google.maps.event.trigger(window.hikeTracker.map, 'resize');
                        const fb = window.HIKER_BOOTSTRAP.mapsFallbackCenter;
                        const livePosition = window.hikeTracker.userMarker?.getPosition?.();
                        const c = livePosition
                            ? { lat: livePosition.lat(), lng: livePosition.lng() }
                            : fb;
                        if (c && c.lat != null && c.lng != null) {
                            window.hikeTracker.map.setCenter({ lat: c.lat, lng: c.lng });
                        }
                    }, 150);
                }
                if (rawId === 'mountain-detail' && typeof google !== 'undefined' && google.maps && window.mountainDetailMap) {
                    setTimeout(function() {
                        google.maps.event.trigger(window.mountainDetailMap, 'resize');
                        if (window.mountainDetailBounds) {
                            window.mountainDetailMap.fitBounds(window.mountainDetailBounds, { top: 30, bottom: 30, left: 30, right: 30 });
                        }
                    }, 150);
                }
            };

            menuLinks.forEach(link => {
                link.addEventListener('click', e => {
                    const href = link.getAttribute('href');
                    if (href && (href.startsWith('#') || href === '#')) {
                        e.preventDefault();
                        showView(href === '#' ? '#home' : href);
                    }
                });
            });

            function initGlobalSearch() {
                const input = document.getElementById('hiker-global-search');
                if (!input) return;

                const itemSelector = [
                    '.stat-card', '.dashboard-card', '.achievement-card', '.mountain-card', '.ns-guide-card',
                    '.ns-booking-card', '.ns-post-card', '.ns-checklist-category', '.ns-history-item', '.safety-alert-card',
                    '.menu-item', '.group-item'
                ].join(',');

                function resetSectionFilters() {
                    document.querySelectorAll(itemSelector).forEach((item) => {
                        item.style.display = '';
                    });
                }

                input.addEventListener('input', () => {
                    const query = input.value.trim().toLowerCase();
                    resetSectionFilters();
                    if (!query) return;

                    const matches = Object.entries(sections)
                        .filter(([, section]) => section)
                        .map(([key, section]) => {
                            const menu = Array.from(menuLinks).find((link) => {
                                const href = link.getAttribute('href') || '';
                                return (key === 'home' && href === '#') || href === '#' + key;
                            });
                            const haystack = ((menu?.textContent || '') + ' ' + section.textContent).toLowerCase();

                            return { key, section, score: haystack.includes(query) ? haystack.indexOf(query) : -1 };
                        })
                        .filter((result) => result.score >= 0)
                        .sort((a, b) => a.score - b.score);

                    if (!matches.length) return;

                    showView('#' + matches[0].key);
                    const activeSection = matches[0].section;
                    activeSection.querySelectorAll(itemSelector).forEach((item) => {
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(query) ? '' : 'none';
                    });
                });
            }

            initGlobalSearch();

            document.querySelectorAll('[data-detail-tab]').forEach((button) => {
                button.addEventListener('click', () => window.selectDetailTab(button.getAttribute('data-detail-tab')));
            });

            const initialHash = (window.location.hash || '').replace(/^#/, '');
            if (initialHash === 'mountain-detail' && currentMountain && mountainData[currentMountain]) {
                openMountainDetail(currentMountain);
            } else if (initialHash && sections[initialHash]) {
                showView('#' + initialHash);
            }

            // Theme toggle
            const lightBtn = document.getElementById('mode-light');
            const darkBtn = document.getElementById('mode-dark');
            const root = document.documentElement;
            const savedTheme = localStorage.getItem('hike_theme') || 'light';
            if (savedTheme === 'dark') { root.setAttribute('data-theme', 'dark'); darkBtn.classList.add('active'); lightBtn.classList.remove('active'); }
            lightBtn.addEventListener('click', () => { root.setAttribute('data-theme', 'light'); localStorage.setItem('hike_theme', 'light'); lightBtn.classList.add('active'); darkBtn.classList.remove('active'); });
            darkBtn.addEventListener('click', () => { root.setAttribute('data-theme', 'dark'); localStorage.setItem('hike_theme', 'dark'); darkBtn.classList.add('active'); lightBtn.classList.remove('active'); });

            // Restore checklist from localStorage
            restoreChecklist();

            // Set min date for booking
            const dateInput = document.getElementById('book-date');
            if (dateInput) { dateInput.min = new Date().toISOString().split('T')[0]; }

            const picInput = document.getElementById('profile-picture-input');
            const picStatus = document.getElementById('profile-picture-status');
            if (picInput && window.HIKER_BOOTSTRAP && window.HIKER_BOOTSTRAP.routes.updateProfilePicture) {
                picInput.addEventListener('change', () => {
                    const file = picInput.files && picInput.files[0];
                    if (!file) return;
                    if (file.size > 2048 * 1024) {
                        if (picStatus) picStatus.textContent = 'File is too large (max 2MB).';
                        picInput.value = '';
                        return;
                    }
                    if (picStatus) picStatus.textContent = 'Uploading…';
                    const fd = new FormData();
                    fd.append('profile_picture', file);
                    fd.append('_token', window.HIKER_BOOTSTRAP.csrf);
                    fetch(window.HIKER_BOOTSTRAP.routes.updateProfilePicture, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: fd,
                    }).then(r => r.json().then(d => ({ ok: r.ok, d }))).then(({ ok, d }) => {
                        if (ok && d.success && d.url) {
                            const bust = d.url + (d.url.indexOf('?') === -1 ? '?' : '&') + 't=' + Date.now();
                            const imgHtml = '<img src="' + bust + '" alt="" width="80" height="80" style="width:100%;height:100%;object-fit:cover;display:block;">';
                            const prev = document.getElementById('settings-avatar-preview');
                            if (prev) prev.innerHTML = imgHtml;
                            const side = document.getElementById('sidebar-user-avatar');
                            if (side) {
                                side.style.padding = '0';
                                side.innerHTML = '<img src="' + bust + '" alt="" width="40" height="40" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">';
                            }
                            if (picStatus) picStatus.textContent = 'Picture saved.';
                        } else {
                            const msg = (d.errors && d.errors.profile_picture && d.errors.profile_picture[0]) || d.message || 'Upload failed.';
                            if (picStatus) picStatus.textContent = msg;
                        }
                        picInput.value = '';
                    }).catch(() => {
                        if (picStatus) picStatus.textContent = 'Upload failed.';
                        picInput.value = '';
                    });
                });
            }

            const pwPanel = document.getElementById('settings-password-panel');
            const pwToggle = document.getElementById('settings-change-pw-toggle');
            const pwSend = document.getElementById('settings-pw-send-code');
            const pwSendStatus = document.getElementById('settings-pw-send-status');
            const pwSubmit = document.getElementById('settings-pw-submit');
            const pwCancel = document.getElementById('settings-pw-cancel');
            const pwFormStatus = document.getElementById('settings-pw-form-status');
            function resetPasswordPanel() {
                const c = document.getElementById('settings-pw-code');
                const a = document.getElementById('settings-pw-new');
                const b = document.getElementById('settings-pw-new-confirmation');
                if (c) c.value = '';
                if (a) a.value = '';
                if (b) b.value = '';
                if (pwSendStatus) pwSendStatus.textContent = '';
                if (pwFormStatus) pwFormStatus.textContent = '';
            }
            if (pwToggle && pwPanel) {
                pwToggle.addEventListener('click', () => {
                    const open = pwPanel.style.display !== 'none';
                    pwPanel.style.display = open ? 'none' : 'flex';
                    if (!open) resetPasswordPanel();
                });
            }
            if (pwCancel && pwPanel) {
                pwCancel.addEventListener('click', () => {
                    pwPanel.style.display = 'none';
                    resetPasswordPanel();
                });
            }
            if (pwSend && window.HIKER_BOOTSTRAP && window.HIKER_BOOTSTRAP.routes.sendPasswordChangeCode) {
                pwSend.addEventListener('click', () => {
                    if (pwSendStatus) pwSendStatus.textContent = 'Sending…';
                    fetch(window.HIKER_BOOTSTRAP.routes.sendPasswordChangeCode, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({}),
                    }).then(r => r.json().then(d => ({ ok: r.ok, d }))).then(({ ok, d }) => {
                        if (ok && d.success) {
                            if (pwSendStatus) pwSendStatus.textContent = d.message || 'Code sent.';
                        } else {
                            if (pwSendStatus) pwSendStatus.textContent = d.message || 'Could not send code.';
                        }
                    }).catch(() => { if (pwSendStatus) pwSendStatus.textContent = 'Request failed.'; });
                });
            }
            if (pwSubmit && window.HIKER_BOOTSTRAP && window.HIKER_BOOTSTRAP.routes.updatePasswordWithCode) {
                pwSubmit.addEventListener('click', () => {
                    const code = (document.getElementById('settings-pw-code') || {}).value || '';
                    const password = (document.getElementById('settings-pw-new') || {}).value || '';
                    const password_confirmation = (document.getElementById('settings-pw-new-confirmation') || {}).value || '';
                    if (pwFormStatus) pwFormStatus.textContent = '';
                    fetch(window.HIKER_BOOTSTRAP.routes.updatePasswordWithCode, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ code: code.trim(), password, password_confirmation }),
                    }).then(r => r.json().then(d => ({ ok: r.ok, d }))).then(({ ok, d }) => {
                        if (ok && d.success) {
                            if (pwFormStatus) pwFormStatus.textContent = 'Password updated.';
                            resetPasswordPanel();
                            if (pwPanel) pwPanel.style.display = 'none';
                        } else {
                            let msg = d.message || 'Could not update password.';
                            if (d.errors) {
                                const e = d.errors;
                                msg = (e.code && e.code[0]) || (e.password && e.password[0]) || msg;
                            }
                            if (pwFormStatus) pwFormStatus.textContent = msg;
                        }
                    }).catch(() => { if (pwFormStatus) pwFormStatus.textContent = 'Request failed.'; });
                });
            }

            const saveProfileBtn = document.getElementById('settings-save-profile');
            const profileStatus = document.getElementById('settings-profile-status');
            if (saveProfileBtn && window.HIKER_BOOTSTRAP && window.HIKER_BOOTSTRAP.routes.updateProfile) {
                saveProfileBtn.addEventListener('click', () => {
                    const first_name = (document.getElementById('settings-first-name') || {}).value || '';
                    const last_name = (document.getElementById('settings-last-name') || {}).value || '';
                    const phone = (document.getElementById('settings-phone') || {}).value || '';
                    const bio = (document.getElementById('settings-bio') || {}).value || '';
                    if (profileStatus) profileStatus.textContent = 'Saving…';
                    fetch(window.HIKER_BOOTSTRAP.routes.updateProfile, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ first_name, last_name, phone: phone || null, bio: bio || null }),
                    }).then(r => r.json().then(d => ({ ok: r.ok, d }))).then(({ ok, d }) => {
                        if (ok && d.success) {
                            if (profileStatus) profileStatus.textContent = 'Profile saved.';
                            const w = document.getElementById('dashboard-welcome-first-name');
                            if (w && first_name.trim()) w.textContent = first_name.trim();
                            const sideName = document.querySelector('.profile-name');
                            if (sideName && d.full_name) sideName.textContent = d.full_name;
                        } else {
                            let msg = 'Could not save.';
                            if (d.errors) {
                                const e = d.errors;
                                msg = (e.first_name && e.first_name[0]) || (e.last_name && e.last_name[0]) || (e.phone && e.phone[0]) || (e.bio && e.bio[0]) || msg;
                            }
                            if (profileStatus) profileStatus.textContent = msg;
                        }
                    }).catch(() => { if (profileStatus) profileStatus.textContent = 'Request failed.'; });
                });
            }

            const achGrid = document.querySelector('.achievements-grid');
            if (achGrid && window.HIKER_BOOTSTRAP && window.HIKER_BOOTSTRAP.routes.achievementClaimBase) {
                achGrid.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-claim-achievement]');
                    if (!btn) return;
                    const id = btn.getAttribute('data-claim-achievement');
                    const base = window.HIKER_BOOTSTRAP.routes.achievementClaimBase;
                    btn.disabled = true;
                    fetch(base + '/' + id + '/claim', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: '{}',
                    }).then(r => r.json().then(d => ({ ok: r.ok, d }))).then(({ ok, d }) => {
                        btn.disabled = false;
                        if (ok && d.success) {
                            const card = document.querySelector('.achievement-card[data-achievement-id="' + id + '"]');
                            if (card) {
                                card.classList.remove('is-eligible', 'is-locked');
                                card.classList.add('is-claimed');
                                const st = card.querySelector('.achievement-status');
                                if (st) {
                                    st.innerHTML = '<span class="achievement-pill claimed"><iconify-icon icon="lucide:check" style="vertical-align:text-bottom;"></iconify-icon> Claimed</span>';
                                }
                            }
                            const n = typeof d.badges_count === 'number' ? d.badges_count : 0;
                            const link = document.getElementById('stat-badges-count-link');
                            if (link) link.textContent = String(n);
                            const mb = document.getElementById('menu-achievements-badge');
                            if (mb) {
                                mb.textContent = String(n);
                                mb.style.display = n > 0 ? '' : 'none';
                            }
                        } else {
                            alert(d.message || 'Could not claim this badge.');
                        }
                    }).catch(() => {
                        btn.disabled = false;
                        alert('Request failed.');
                    });
                });
            }
        });

        // ── Mountain Detail ──────────────────────────────────────
        window.openMountainDetail = function(id) {
            const m = mountainData[id];
            if (!m) return;
            closeTrailSimulation();
            currentMountain = id;
            document.getElementById('detail-hero').style.backgroundImage = `url('${m.image}')`;
            document.getElementById('detail-name').textContent = m.name;
            document.getElementById('detail-status').innerHTML = `<iconify-icon icon="lucide:circle" style="vertical-align:text-bottom; margin-right:2px; font-size:10px;"></iconify-icon> ${m.status === 'open' ? 'Open' : 'Closed'}`;
            document.getElementById('detail-status').className = 'ns-status-pill ' + m.status;
            document.getElementById('detail-diff').textContent = m.difficulty;
            document.getElementById('detail-full-desc').textContent = m.description || '';
            const safetyBox = document.getElementById('detail-safety-alert');
            const safetyTitle = document.getElementById('detail-safety-title');
            const safetyNote = document.getElementById('detail-safety-note');
            const safetyStatus = m.safetyStatus || 'open';
            if (safetyBox && safetyTitle && safetyNote) {
                if (safetyStatus !== 'open') {
                    safetyBox.style.display = '';
                    safetyTitle.textContent = m.safetyStatusLabel || 'Trail Safety Alert';
                    safetyNote.textContent = m.safetyNote || 'Please check trail conditions before booking or starting your hike.';
                } else {
                    safetyBox.style.display = 'none';
                }
            }
            document.getElementById('detail-elevation').textContent = m.elevation;
            document.getElementById('detail-duration').textContent = m.duration;
            document.getElementById('detail-trail-type').textContent = m.trailType;
            document.getElementById('detail-best-time').textContent = m.bestTime;
            document.getElementById('detail-jumpoff-name').textContent = m.jumpoff.name;
            document.getElementById('detail-jumpoff-address').textContent = m.jumpoff.address;
            document.getElementById('detail-meeting-time').textContent = m.jumpoff.meetingTime;
            document.getElementById('detail-jumpoff-notes').textContent = m.jumpoff.notes;
            const trailBadge = document.getElementById('detail-trail-badge');
            const trailSource = document.getElementById('detail-trail-source');
            const experience = m.experience && m.experience.enabled ? m.experience : null;
            const trailMap = m.experience && m.experience.trailMap ? m.experience.trailMap : null;
            if (trailBadge) {
                if (trailMap && Array.isArray(trailMap.path) && trailMap.path.length > 1) {
                    trailBadge.hidden = false;
                    trailBadge.textContent = trailMap.label || `${m.name} Trail`;
                } else {
                    trailBadge.hidden = true;
                }
            }
            if (trailSource) {
                trailSource.textContent = trailMap && trailMap.sourceLabel
                    ? trailMap.sourceLabel
                    : 'Trail data preview coming soon.';
            }
            syncTrailSimulationLauncher(m);

            // Gear tags
            const gearEl = document.getElementById('detail-gear-tags');
            gearEl.innerHTML = (m.gear || []).map(g => `<span class="ns-gear-tag">${g}</span>`).join('');
            renderMountainSpotlight(m);

            // Google Maps for Jump-off Point and trail line
            if (typeof google !== 'undefined' && google.maps) {
                const mapEl = document.getElementById('detail-jumpoff-gmap');
                const jumpoffPos = { lat: m.jumpoff.lat, lng: m.jumpoff.lng };
                const summitPos = { lat: m.summit.lat, lng: m.summit.lng };
                const routeEndPos = experience && experience.routeEnd
                    ? { lat: Number(experience.routeEnd.lat), lng: Number(experience.routeEnd.lng) }
                    : summitPos;
                const routeEndIsDistinct = Math.abs(routeEndPos.lat - summitPos.lat) + Math.abs(routeEndPos.lng - summitPos.lng) > 0.0005;
                const trailPath = trailMap && Array.isArray(trailMap.path)
                    ? trailMap.path.map((point) => ({ lat: Number(point.lat), lng: Number(point.lng) }))
                    : [];
                const visibleTrailPath = (() => {
                    if (!routeEndIsDistinct || trailPath.length < 2) {
                        return trailPath;
                    }

                    let summitIndex = 0;
                    let summitDistance = Infinity;
                    trailPath.forEach((point, index) => {
                        const score = Math.abs(point.lat - summitPos.lat) + Math.abs(point.lng - summitPos.lng);
                        if (score < summitDistance) {
                            summitDistance = score;
                            summitIndex = index;
                        }
                    });

                    return trailPath.slice(0, summitIndex + 1);
                })();

                const map = new google.maps.Map(mapEl, {
                    center: jumpoffPos,
                    zoom: 14,
                    mapTypeId: 'satellite',
                    disableDefaultUI: false,
                    zoomControl: true,
                    mapTypeControl: true,
                    streetViewControl: false,
                    fullscreenControl: true,
                    scaleControl: true,
                });

                if (visibleTrailPath.length > 1) {
                    new google.maps.Polyline({
                        path: visibleTrailPath,
                        geodesic: true,
                        strokeColor: '#facc15',
                        strokeOpacity: 1,
                        strokeWeight: 8,
                        zIndex: 1,
                        map,
                    });

                    new google.maps.Polyline({
                        path: visibleTrailPath,
                        geodesic: true,
                        strokeColor: '#0ea5e9',
                        strokeOpacity: 0.96,
                        strokeWeight: 4,
                        zIndex: 2,
                        map,
                    });
                }

                // Jump-off marker
                new google.maps.Marker({
                    position: jumpoffPos,
                    map,
                    title: `${m.jumpoff.name} (Jump-off)`,
                    zIndex: 3,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 9,
                        fillColor: routeEndIsDistinct ? '#2563eb' : '#f59e0b',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 3,
                    }
                });

                // Summit marker
                new google.maps.Marker({
                    position: summitPos,
                    map,
                    title: `${m.name} Summit (${m.elevation})`,
                    zIndex: 4,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 9,
                        fillColor: '#dc2626',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 3,
                    }
                });

                // Auto-fit to show the visible trail, jump-off, and summit
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(jumpoffPos);
                bounds.extend(summitPos);
                visibleTrailPath.forEach((point) => bounds.extend(point));
                window.mountainDetailMap = map;
                window.mountainDetailBounds = bounds;
                map.fitBounds(bounds, { top: 30, bottom: 30, left: 30, right: 30 });
            }

            // Guides for this mountain
            const guidesEl = document.getElementById('detail-guides-list');
            guidesEl.innerHTML = '';
            Object.entries(guideData).forEach(([gid, g]) => {
                if (g.mountainId === id && g.status === 'available') {
                    guidesEl.innerHTML += `<div class="ns-detail-guide-mini" onclick="bookWithGuide('${gid}')">
                        <div class="ns-guide-avatar" style="background:${g.gradient};">${g.initials}</div>
                        <div><h5>${g.name}</h5><span>${g.mountain}</span></div>
                    </div>`;
                }
            });
            if (!guidesEl.innerHTML) guidesEl.innerHTML = '<p style="color:var(--muted);font-size:13px;">No available guides for this mountain right now.</p>';

            showView('#mountain-detail');
        };

        window.bookFromDetail = function() {
            if (currentMountain) {
                const sel = document.getElementById('book-mountain');
                if (sel) sel.value = currentMountain;
                updateGuideOptions();
                showView('#book-hike');
                updateBookingPreview();
            }
        };

        // ── Dynamic Guide Dropdown (filters by selected mountain) ──
        window.updateGuideOptions = function() {
            const mountainId = document.getElementById('book-mountain')?.value;
            const guideSel = document.getElementById('book-guide');
            if (!guideSel) return;
            guideSel.innerHTML = '';

            if (!mountainId) {
                guideSel.innerHTML = '<option value="">Select a mountain first...</option>';
                return;
            }

            // Find available guides for this mountain (or guides assigned to "all" mountains)
            let options = '<option value="">Choose a guide...</option>';
            let found = 0;
            Object.entries(guideData).forEach(([id, g]) => {
                const matchesMountain = g.mountainId === mountainId || g.mountainId === 'all';
                if (matchesMountain && g.status === 'available') {
                    options += `<option value="${id}">${g.name} — ${g.spec} (Available)</option>`;
                    found++;
                }
            });

            if (found === 0) {
                options += '<option value="" disabled>No available guides for this mountain</option>';
            }

            guideSel.innerHTML = options;
        };

        // ── Tour Guide Filtering ──────────────────────────────────
        window.filterGuides = function() {
            const search = (document.getElementById('guide-search-input')?.value || '').toLowerCase();
            const status = document.getElementById('guide-status-filter')?.value || 'all';
            document.querySelectorAll('.ns-guide-card').forEach(card => {
                const name = card.querySelector('h4')?.textContent.toLowerCase() || '';
                const mountain = card.dataset.mountain || '';
                const cardStatus = card.dataset.status || '';
                const matchSearch = name.includes(search) || mountain.includes(search);
                const matchStatus = status === 'all' || cardStatus === status;
                card.style.display = (matchSearch && matchStatus) ? '' : 'none';
            });
        };

        window.bookWithGuide = function(guideId) {
            const g = guideData[String(guideId)];
            if (!g) return;
            const mountainSel = document.getElementById('book-mountain');
            if (mountainSel && g.mountainId && g.mountainId !== 'all') mountainSel.value = g.mountainId;
            updateGuideOptions();
            const guideSel = document.getElementById('book-guide');
            if (guideSel) guideSel.value = String(guideId);
            showView('#book-hike');
            updateBookingPreview();
        };

        // ── Booking Form ──────────────────────────────────────────
        window.updateBookingPreview = function() {
            const mountain = document.getElementById('book-mountain')?.value;
            const date = document.getElementById('book-date')?.value;
            const hikers = document.getElementById('book-hikers')?.value;
            const guide = document.getElementById('book-guide')?.value;
            const preview = document.getElementById('booking-preview-content');
            if (!preview) return;

            if (mountain || date || guide) {
                const mData = mountainData[mountain];
                const gData = guideData[String(guide)];
                const safetyWarning = mData && mData.safetyStatus && mData.safetyStatus !== 'open'
                    ? `<div class="ns-preview-row" style="background:#fef2f2;border-color:#fecaca;"><span style="color:#991b1b;">Trail safety</span><strong style="color:#991b1b;">${escapeHtml(mData.safetyStatusLabel || 'Safety Alert')}</strong></div>
                       <div style="font-size:12px;color:#7f1d1d;line-height:1.5;margin-top:6px;">${escapeHtml(mData.safetyNote || 'Please check trail conditions before booking or starting your hike.')}</div>`
                    : '';
                preview.innerHTML = `<div class="ns-preview-filled">
                    <div class="ns-preview-row"><span>Mountain</span><strong>${mData ? mData.name : '—'}</strong></div>
                    <div class="ns-preview-row"><span>Date</span><strong>${date || '—'}</strong></div>
                    <div class="ns-preview-row"><span>Hikers</span><strong>${hikers || '1'}</strong></div>
                    <div class="ns-preview-row"><span>Guide</span><strong>${gData ? gData.name : '—'}</strong></div>
                    <div class="ns-preview-row"><span>Jump-off</span><strong>${mData ? mData.jumpoff.name : '—'}</strong></div>
                    ${(() => {
                        const receipt = buildBookingPriceReceiptClient(mData, hikers || '1');
                        if (!receipt) return '<div class="ns-preview-row"><span>Expected Price</span><strong>—</strong></div>';
                        const linesHtml = receipt.lines
                            .filter((line) => Number(line.amount) > 0)
                            .map((line) => `<div class="ns-preview-row"><span>${escapeHtml(line.label)}</span><strong>${formatPhpCurrency(line.amount)}</strong></div>`)
                            .join('');
                        const sourceHtml = receipt.sourceNote
                            ? `<div style="font-size:11px;color:var(--muted);line-height:1.45;margin-top:6px;">${escapeHtml(receipt.sourceNote)}${receipt.lastVerifiedOn ? ` (Verified: ${escapeHtml(receipt.lastVerifiedOn)})` : ''}</div>`
                            : '';
                        return `${linesHtml}<div class="ns-preview-row"><span>Expected Total</span><strong>${formatPhpCurrency(receipt.total)}</strong></div>${sourceHtml}`;
                    })()}
                    ${safetyWarning}
                    <div class="ns-preview-row"><span>Status</span><strong style="color:#f59e0b;"><iconify-icon icon="lucide:circle" style="vertical-align:text-bottom; margin-right:2px; font-size:10px;"></iconify-icon> Pending</strong></div>
                </div>`;
            } else {
                preview.innerHTML = '<div class="ns-preview-empty"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--line)" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><p>Fill out the form to see a preview of your booking.</p></div>';
            }
        };

        window.submitBooking = function(e) {
            e.preventDefault();
            const fd = new FormData(document.getElementById('booking-form'));
            fd.append('_token', window.HIKER_BOOTSTRAP.csrf);
            const btn = document.getElementById('booking-submit-btn');
            if (btn) { btn.disabled = true; }
            fetch(window.HIKER_BOOTSTRAP.routes.storeBooking, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            }).then(function(r) {
                return r.text().then(function(text) {
                    var data = {};
                    try { data = text ? JSON.parse(text) : {}; } catch (err) { data = { message: 'Server error. Please try again.' }; }
                    return { status: r.status, body: data };
                });
            }).then(function(res) {
                if (btn) { btn.disabled = false; }
                if (res.status === 200 && res.body.success) {
                    var okMsg = res.body.message || 'Booking request sent! Pending guide approval.';
                    hcToast(okMsg, false);
                    var bs = document.getElementById('booking-success');
                    if (bs) bs.style.display = 'flex';
                } else if (res.body.errors) {
                    hcToast(Object.values(res.body.errors).flat().join(' · '), true);
                } else {
                    hcToast(res.body.message || 'Could not create booking.', true);
                }
            }).catch(function() {
                if (btn) { btn.disabled = false; }
                hcToast('Could not create booking. Check your connection and try again.', true);
            });
        };

        window.resetBookingForm = function() {
            document.getElementById('booking-form')?.reset();
            document.getElementById('booking-success').style.display = 'none';
            updateBookingPreview();
        };

        const bookingQrState = {
            bookingId: null,
            action: null,
            stream: null,
        };

        // ── My Bookings Tabs ──────────────────────────────────────
        window.filterBookings = function(type, btn) {
            document.querySelectorAll('.ns-tab').forEach(t => t.classList.remove('active'));
            if (btn) btn.classList.add('active');
            document.querySelectorAll('.ns-booking-card').forEach(card => {
                if (type === 'all') { card.classList.remove('hidden'); }
                else { card.classList.toggle('hidden', card.dataset.bookingType !== type); }
            });
        };

        window.cancelBooking = function(btn) {
            if (!confirm('Are you sure you want to cancel this booking?')) return;
            const card = btn.closest('.ns-booking-card');
            const id = card?.dataset?.bookingId;
            if (!id) return;
            const fd = new FormData();
            fd.append('_token', window.HIKER_BOOTSTRAP.csrf);
            fetch(window.HIKER_BOOTSTRAP.routes.cancelBookingPrefix + '/' + id + '/cancel', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            }).then(r => r.text().then(t => {
                var d = {};
                try { d = t ? JSON.parse(t) : {}; } catch (e) { d = {}; }
                return { ok: r.ok, d: d };
            })).then(function(res) {
                if (res.d.success) {
                    hcToast(res.d.message || 'Booking cancelled.', false);
                    const status = card.querySelector('.ns-booking-status');
                    status.textContent = 'Cancelled';
                    status.className = 'ns-booking-status cancelled';
                    card.dataset.bookingType = 'cancelled';
                    btn.remove();
                } else {
                    hcToast(res.d.message || 'Could not cancel this booking.', true);
                }
            }).catch(function() { hcToast('Could not cancel. Try again.', true); });
        };

        window.openBookingQrScan = function(btn, action) {
            const card = btn?.closest('.ns-booking-card');
            const bookingId = card?.dataset?.bookingId;
            if (!bookingId) return;
            bookingQrState.bookingId = bookingId;
            bookingQrState.action = action;

            const modal = document.getElementById('booking-qr-modal');
            const title = document.getElementById('booking-qr-modal-title');
            const subtitle = document.getElementById('booking-qr-modal-subtitle');
            const feedback = document.getElementById('booking-qr-feedback');
            const scannerWrap = document.getElementById('booking-qr-scanner-wrap');
            const cameraStatus = document.getElementById('booking-qr-camera-status');
            if (title) title.textContent = action === 'checkout' ? 'Scan Check-out QR' : 'Scan Check-in QR';
            if (subtitle) subtitle.textContent = action === 'checkout'
                ? 'Scan the posted check-out QR after your hike.'
                : 'Scan the posted check-in QR at the jump-off point.';
            if (feedback) feedback.textContent = '';
            if (scannerWrap) scannerWrap.style.display = 'block';
            if (cameraStatus) cameraStatus.textContent = 'Opening camera...';
            if (modal) modal.style.display = 'flex';
            startBookingQrCamera();
        };

        window.closeBookingQrScan = function() {
            stopBookingQrCamera();
            bookingQrState.bookingId = null;
            bookingQrState.action = null;
            const modal = document.getElementById('booking-qr-modal');
            if (modal) modal.style.display = 'none';
        };

        window.startBookingQrCamera = async function() {
            const video = document.getElementById('booking-qr-video');
            const cameraStatus = document.getElementById('booking-qr-camera-status');
            if (!video) return;

            if (!navigator.mediaDevices?.getUserMedia) {
                if (cameraStatus) cameraStatus.textContent = 'Camera is not supported on this browser.';
                return;
            }

            try {
                stopBookingQrCamera();
                bookingQrState.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false,
                });
                video.srcObject = bookingQrState.stream;
                if (cameraStatus) cameraStatus.textContent = 'Point camera at QR then tap Take Photo.';
            } catch (_err) {
                if (cameraStatus) cameraStatus.textContent = 'Unable to open camera.';
            }
        };

        window.stopBookingQrCamera = function() {
            if (bookingQrState.stream) {
                bookingQrState.stream.getTracks().forEach((track) => track.stop());
                bookingQrState.stream = null;
            }
            const video = document.getElementById('booking-qr-video');
            if (video) {
                video.srcObject = null;
            }
        };

        async function decodeQrFromCanvas(canvas) {
            if (typeof window.jsQR !== 'function') return null;
            const ctx = canvas.getContext('2d', { willReadFrequently: true });
            if (!ctx) return null;
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const result = window.jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 'dontInvert',
            });
            return result?.data || null;
        }

        window.captureBookingQrPhoto = async function() {
            const video = document.getElementById('booking-qr-video');
            const canvas = document.getElementById('booking-qr-canvas');
            const cameraStatus = document.getElementById('booking-qr-camera-status');
            const feedback = document.getElementById('booking-qr-feedback');
            if (!video || !canvas) return;

            if (!bookingQrState.stream) {
                if (feedback) feedback.textContent = 'Camera is not ready yet.';
                return;
            }

            const width = video.videoWidth;
            const height = video.videoHeight;
            if (!width || !height) {
                if (feedback) feedback.textContent = 'Camera not ready. Try again in a second.';
                return;
            }

            if (cameraStatus) cameraStatus.textContent = 'Capturing photo...';
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d', { willReadFrequently: true });
            if (!ctx) return;
            ctx.drawImage(video, 0, 0, width, height);

            const rawPayload = await decodeQrFromCanvas(canvas);
            if (!rawPayload) {
                if (feedback) feedback.textContent = 'No QR code found in photo. Try moving closer and retake.';
                if (cameraStatus) cameraStatus.textContent = 'QR not detected. Retake photo.';
                return;
            }
            if (cameraStatus) cameraStatus.textContent = 'QR captured. Validating...';
            submitBookingQrScan(rawPayload, true);
        };

        function parseBookingQrPayloadClient(rawPayload) {
            const payload = String(rawPayload || '').trim();
            if (!payload) return null;
            let decoded = null;
            try {
                decoded = JSON.parse(payload);
            } catch (_jsonErr) {
                try {
                    decoded = JSON.parse(atob(payload));
                } catch (_base64Err) {
                    try {
                        const b64url = payload.replace(/-/g, '+').replace(/_/g, '/');
                        decoded = JSON.parse(atob(b64url));
                    } catch (_base64UrlErr) {
                        decoded = null;
                    }
                }
            }

            if (!decoded || typeof decoded !== 'object') {
                try {
                    const maybeUrl = payload.startsWith('http://') || payload.startsWith('https://')
                        ? new URL(payload)
                        : null;
                    const params = maybeUrl ? maybeUrl.searchParams : new URLSearchParams(payload);
                    if (params.has('mountain_id') || params.has('mountainId') || params.has('action') || params.has('type')) {
                        decoded = {
                            mountain_id: params.get('mountain_id') || params.get('mountainId'),
                            action: params.get('action') || params.get('type'),
                        };
                    }
                } catch (_queryErr) {
                    decoded = null;
                }
            }

            if (!decoded || typeof decoded !== 'object') return null;

            const mountainRaw = decoded?.mountain_id ?? decoded?.mountainId;
            const hasMountain = mountainRaw !== undefined && mountainRaw !== null && String(mountainRaw).trim() !== '';
            const mountainId = hasMountain ? Number(mountainRaw) : null;
            let action = String(decoded?.action ?? decoded?.type ?? '')
                .toLowerCase()
                .replace(/[\s_-]+/g, '');
            if (action === 'checkinqr') action = 'checkin';
            if (action === 'checkoutqr') action = 'checkout';
            if (hasMountain && (!Number.isInteger(mountainId) || mountainId < 1)) return null;
            if (action !== 'checkin' && action !== 'checkout') return null;
            return { mountain_id: mountainId, action };
        }

        window.submitBookingQrScan = function(payload, isAutoScan = false) {
            if (!bookingQrState.bookingId || !bookingQrState.action) {
                return;
            }
            if (!payload) {
                const feedback = document.getElementById('booking-qr-feedback');
                if (feedback) feedback.textContent = 'No QR payload detected. Keep the QR in front of the camera.';
                return;
            }
            const parsedPayload = parseBookingQrPayloadClient(payload);
            const feedback = document.getElementById('booking-qr-feedback');
            if (!parsedPayload) {
                if (feedback) feedback.textContent = 'Invalid QR payload. Use JSON, base64 JSON, or URL/query with mountain_id and action.';
                return;
            }
            if (parsedPayload.action !== bookingQrState.action) {
                if (feedback) feedback.textContent = `Wrong QR type scanned. Expected ${bookingQrState.action}.`;
                return;
            }
            if (parsedPayload.mountain_id !== null) {
                const bookingCard = document.querySelector(`.ns-booking-card[data-booking-id="${bookingQrState.bookingId}"]`);
                const expectedMountainId = Number(bookingCard?.dataset?.mountainId || 0);
                if (!Number.isInteger(expectedMountainId) || expectedMountainId < 1) {
                    if (feedback) feedback.textContent = 'Could not verify booking mountain. Please refresh and try again.';
                    return;
                }
                if (parsedPayload.mountain_id !== expectedMountainId) {
                    if (feedback) feedback.textContent = 'Scanned QR is for a different mountain jump-off point.';
                    return;
                }
            }
            if (feedback) feedback.textContent = 'Submitting scan...';

            const endpointBase = bookingQrState.action === 'checkout'
                ? window.HIKER_BOOTSTRAP.routes.checkOutScanPrefix
                : window.HIKER_BOOTSTRAP.routes.checkInScanPrefix;
            const endpoint = `${endpointBase}/${bookingQrState.bookingId}/${bookingQrState.action === 'checkout' ? 'check-out-scan' : 'check-in-scan'}`;
            const fd = new FormData();
            fd.append('_token', window.HIKER_BOOTSTRAP.csrf);
            fd.append('qr_payload', payload);

            fetch(endpoint, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            }).then(async r => ({ status: r.status, body: await r.json() }))
                .then(res => {
                    if (res.status >= 200 && res.status < 300 && res.body.success) {
                        const booking = res.body.booking || {};
                        const card = document.querySelector(`.ns-booking-card[data-booking-id="${bookingQrState.bookingId}"]`);
                        if (card) {
                            const status = card.querySelector('.ns-booking-status');
                            const cancelBtn = card.querySelector('.ns-cancel-btn');
                            const right = card.querySelector('.ns-booking-right');
                            const meta = card.querySelector('.ns-booking-qr-meta');
                            if (status) {
                                if (booking.status === 'in_progress') {
                                    status.textContent = 'In Progress';
                                    status.className = 'ns-booking-status approved';
                                } else if (booking.status === 'completed') {
                                    status.textContent = 'Completed';
                                    status.className = 'ns-booking-status completed';
                                }
                            }
                            if (cancelBtn) cancelBtn.remove();
                            card.querySelectorAll('button[onclick*="openBookingQrScan"]').forEach(el => el.remove());
                            if (right && booking.status === 'in_progress') {
                                const checkOutBtn = document.createElement('button');
                                checkOutBtn.type = 'button';
                                checkOutBtn.className = 'ns-action-btn';
                                checkOutBtn.textContent = 'Check out';
                                checkOutBtn.setAttribute('onclick', "openBookingQrScan(this, 'checkout')");
                                right.appendChild(checkOutBtn);
                            }
                            if (card && booking.status === 'completed') {
                                card.dataset.bookingType = 'past';
                            }
                            if (meta) {
                                const inAt = booking.checked_in_at ? new Date(booking.checked_in_at).toLocaleString() : '';
                                const outAt = booking.checked_out_at ? new Date(booking.checked_out_at).toLocaleString() : '';
                                meta.innerHTML = `${inAt ? `<span><strong style="color:var(--text);">Checked in:</strong> ${escapeHtml(inAt)}</span>` : ''}${outAt ? `<span><strong style="color:var(--text);">Checked out:</strong> ${escapeHtml(outAt)}</span>` : ''}`;
                            }
                        }
                        if (!isAutoScan) {
                            alert(res.body.message || 'Scan accepted.');
                        }
                        closeBookingQrScan();
                    } else {
                        const msg = res.body?.message || Object.values(res.body?.errors || {}).flat().join('\n') || 'Scan failed.';
                        if (feedback) feedback.textContent = msg;
                    }
                })
                .catch(() => {
                    if (feedback) feedback.textContent = 'Could not submit QR scan.';
                });
        };

        // ── Track Location (live GPS + trail polyline) ────────────
        function haversine(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        window.hikeTracker = {
            map: null,
            userMarker: null,
            polyline: null,
            path: [],
            watchId: null,
            isTracking: false,
            hasLiveFix: false,
            lastFix: null,
        };
        const MAX_TRACKING_ACCURACY_M = 500;
        const LAST_TRACK_KEY = 'hike_last_track_' + String(window.HIKER_BOOTSTRAP?.userId || '0');

        function saveLastTrackFix(fix) {
            if (!fix) return;
            try {
                localStorage.setItem(LAST_TRACK_KEY, JSON.stringify(fix));
            } catch (_) {}
        }

        function loadLastTrackFix() {
            try {
                const raw = localStorage.getItem(LAST_TRACK_KEY);
                if (!raw) return null;
                const parsed = JSON.parse(raw);
                if (!parsed || typeof parsed !== 'object') return null;
                const lat = Number(parsed.lat);
                const lng = Number(parsed.lng);
                if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;
                return {
                    lat,
                    lng,
                    accuracy_m: Number.isFinite(Number(parsed.accuracy_m)) ? Number(parsed.accuracy_m) : null,
                    at: Number.isFinite(Number(parsed.at)) ? Number(parsed.at) : Date.now(),
                };
            } catch (_) {
                return null;
            }
        }

        function hikeTrailLengthKm(path) {
            if (!path || path.length < 2) return 0;
            let sum = 0;
            for (let i = 1; i < path.length; i++) {
                sum += haversine(path[i-1].lat, path[i-1].lng, path[i].lat, path[i].lng);
            }
            return sum;
        }

        function hikeAppendTrackPoint(lat, lng) {
            const path = window.hikeTracker.path;
            const minMeters = 14;
            if (path.length === 0) {
                path.push({ lat, lng });
                return true;
            }
            const last = path[path.length - 1];
            const distM = haversine(lat, lng, last.lat, last.lng) * 1000;
            if (distM >= minMeters) {
                path.push({ lat, lng });
                return true;
            }
            return false;
        }

        function ensureHikeTrackerMap() {
            if (window.hikeTracker.map) return true;
            const mapEl = document.getElementById('tracker-gmap');
            if (!mapEl || typeof google === 'undefined' || !google.maps || !window.HIKER_BOOTSTRAP) return false;
            const dj = window.HIKER_BOOTSTRAP.defaultJumpoff;
            const fallbackCenter = (dj && dj.lat != null && dj.lng != null)
                ? { lat: Number(dj.lat), lng: Number(dj.lng) }
                : (window.HIKER_BOOTSTRAP.mapsFallbackCenter || { lat: 12.8797, lng: 121.7740 });
            const map = new google.maps.Map(mapEl, {
                center: fallbackCenter,
                zoom: (dj && dj.lat != null && dj.lng != null) ? 10 : 6,
                mapTypeId: 'satellite',
                disableDefaultUI: false,
                zoomControl: true,
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true,
            });
            window.hikeTracker.map = map;
            (window.HIKER_BOOTSTRAP.jumpoffMarkers || []).forEach(j => {
                new google.maps.Marker({
                    position: { lat: j.lat, lng: j.lng },
                    map,
                    title: j.title + ' (jump-off, not your location)',
                    icon: { url: 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png' }
                });
            });

            const savedFix = loadLastTrackFix();
            if (savedFix) {
                window.hikeTracker.lastFix = savedFix;
                window.hikeTracker.userMarker = new google.maps.Marker({
                    position: { lat: savedFix.lat, lng: savedFix.lng },
                    map,
                    title: 'Last saved location',
                    icon: { url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png' },
                });
                map.setCenter({ lat: savedFix.lat, lng: savedFix.lng });
                if (map.getZoom() < 14) map.setZoom(14);
                const lastEl = document.getElementById('tracker-last-fix');
                const statusEl = document.getElementById('tracker-status');
                if (lastEl) lastEl.textContent = new Date(savedFix.at).toLocaleTimeString();
                if (statusEl && !window.hikeTracker.isTracking) {
                    statusEl.textContent = 'Showing last saved track';
                }
            }
            return true;
        }

        window.toggleLiveTracking = function() {
            if (window.hikeTracker.isTracking) {
                stopLiveTracking();
            } else {
                startLiveTracking();
            }
        };

        // ── Push hiker location to backend (throttled) ─────────────
        window.hikeTracker = window.hikeTracker || {};
        let _hikerPushAt = 0;
        function pushHikerLocationToServer(payload) {
            const now = Date.now();
            if (now - _hikerPushAt < 15000) return; // throttle: 15s
            _hikerPushAt = now;
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                const body = new FormData();
                body.append('lat', payload.lat);
                body.append('lng', payload.lng);
                if (payload.accuracy_m != null && !isNaN(payload.accuracy_m)) body.append('accuracy_m', payload.accuracy_m);
                if (payload.altitude_m != null && !isNaN(payload.altitude_m)) body.append('altitude_m', payload.altitude_m);
                if (payload.speed_mps != null && !isNaN(payload.speed_mps)) body.append('speed_mps', payload.speed_mps);
                const ctxMid = window.HIKER_BOOTSTRAP?.activeMountainId;
                const ctxBid = window.HIKER_BOOTSTRAP?.activeBookingId;
                if (ctxMid) body.append('mountain_id', ctxMid);
                if (ctxBid) body.append('hike_booking_id', ctxBid);
                fetch('{{ route('hikers.location.record') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body,
                }).catch(() => {});
            } catch(_) {}
        }

        window.triggerEmergencySos = function() {
            const btn = document.getElementById('sos-btn');
            const statusEl = document.getElementById('sos-status');
            const messageEl = document.getElementById('sos-message');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const lastFix = window.hikeTracker?.lastFix || null;

            if (!csrf) {
                if (statusEl) statusEl.textContent = 'Could not verify your session. Please refresh and try again.';
                return;
            }

            const confirmed = window.confirm('Send Emergency SOS to Admin and your assigned Tour Guide? Use this only if you need urgent help.');
            if (!confirmed) return;

            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.75';
                btn.style.cursor = 'wait';
            }
            if (statusEl) {
                statusEl.style.color = 'var(--muted)';
                statusEl.textContent = lastFix ? 'Sending SOS with your last GPS fix...' : 'Sending SOS. If GPS is available, allow location access for better accuracy.';
            }

            const send = (coords = null) => {
                const body = new FormData();
                const point = coords || lastFix;
                if (point) {
                    body.append('lat', point.lat);
                    body.append('lng', point.lng);
                    if (point.accuracy_m != null && !isNaN(point.accuracy_m)) body.append('accuracy_m', point.accuracy_m);
                }
                const ctxMid = window.HIKER_BOOTSTRAP?.activeMountainId;
                const ctxBid = window.HIKER_BOOTSTRAP?.activeBookingId;
                if (ctxMid) body.append('mountain_id', ctxMid);
                if (ctxBid) body.append('hike_booking_id', ctxBid);
                if (messageEl && messageEl.value.trim()) body.append('message', messageEl.value.trim());

                fetch(window.HIKER_BOOTSTRAP?.routes?.triggerSos || '/hikers/sos', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body,
                })
                    .then(async (r) => {
                        const data = await r.json().catch(() => ({}));
                        if (!r.ok || !data.success) {
                            throw new Error(data.message || 'Could not send SOS. Please try again.');
                        }
                        if (statusEl) {
                            statusEl.style.color = data.email_sent === false ? '#b45309' : '#047857';
                            statusEl.textContent = data.message || 'SOS sent to Admin and your Tour Guide.';
                        }
                    })
                    .catch((err) => {
                        if (statusEl) {
                            statusEl.style.color = '#b91c1c';
                            statusEl.textContent = err.message || 'Could not send SOS. Try again or call local rescue directly.';
                        }
                    })
                    .finally(() => {
                        if (btn) {
                            btn.disabled = false;
                            btn.style.opacity = '';
                            btn.style.cursor = '';
                        }
                    });
            };

            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => send({
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude,
                        accuracy_m: pos.coords.accuracy,
                    }),
                    () => send(lastFix),
                    { enableHighAccuracy: true, maximumAge: 8000, timeout: 10000 }
                );
            } else {
                send(lastFix);
            }
        };

        function startLiveTracking() {
            const label = document.getElementById('track-btn-label');
            const clearBtn = document.getElementById('track-clear-btn');
            const distEl = document.getElementById('tracker-distance');
            const altEl = document.getElementById('tracker-altitude');
            const statusEl = document.getElementById('tracker-status');
            const accEl = document.getElementById('tracker-accuracy');
            const trailEl = document.getElementById('tracker-trail-km');
            const lastEl = document.getElementById('tracker-last-fix');

            if (!('geolocation' in navigator)) {
                if (statusEl) statusEl.textContent = 'Geolocation not supported';
                return;
            }
            if (!ensureHikeTrackerMap()) {
                if (statusEl) {
                    statusEl.textContent = !document.getElementById('tracker-gmap')
                        ? 'Add GOOGLE_MAPS_API_KEY to .env and run php artisan config:clear'
                        : 'Map failed to load — check API key and billing in Google Cloud';
                }
                if (distEl) distEl.textContent = '—';
                return;
            }

            const continuing = window.hikeTracker.path.length > 0 && window.hikeTracker.polyline;
            if (!continuing) {
                window.hikeTracker.path = [];
                if (window.hikeTracker.polyline) {
                    window.hikeTracker.polyline.setMap(null);
                    window.hikeTracker.polyline = null;
                }
                if (window.hikeTracker.userMarker) {
                    window.hikeTracker.userMarker.setMap(null);
                    window.hikeTracker.userMarker = null;
                }
                window.hikeTracker.hasLiveFix = false;
            }

            window.hikeTracker.isTracking = true;
            const btn = document.getElementById('track-btn');
            if (btn) btn.setAttribute('aria-pressed', 'true');
            if (label) label.textContent = 'Stop tracking';
            if (clearBtn) clearBtn.style.display = '';
            if (statusEl) statusEl.textContent = 'Waiting for GPS fix';
            if (distEl) distEl.textContent = '-- km';
            if (trailEl) trailEl.textContent = '-- km';
            if (altEl) altEl.textContent = '-- m';
            if (accEl) accEl.textContent = '--';
            if (lastEl) lastEl.textContent = '--';

            const dj = window.HIKER_BOOTSTRAP.defaultJumpoff;
            const jm = (window.HIKER_BOOTSTRAP.jumpoffMarkers || [])[0];
            const jumpoffRef = (dj && dj.lat != null && dj.lng != null)
                ? dj
                : (jm ? { lat: jm.lat, lng: jm.lng } : null);
            const geoOpts = { enableHighAccuracy: true, maximumAge: 4000, timeout: 25000 };

            const onPos = (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const acc = pos.coords.accuracy;
                const map = window.hikeTracker.map;
                window.hikeTracker.lastFix = {
                    lat,
                    lng,
                    accuracy_m: acc,
                    at: Date.now(),
                };
                saveLastTrackFix(window.hikeTracker.lastFix);

                if (altEl) {
                    altEl.textContent = (pos.coords.altitude != null && !isNaN(pos.coords.altitude))
                        ? Math.round(pos.coords.altitude) + ' m' : '—';
                }
                if (accEl) {
                    accEl.textContent = (acc != null && !isNaN(acc)) ? ('±' + Math.round(acc) + ' m') : '—';
                }
                if (lastEl) lastEl.textContent = new Date().toLocaleTimeString();

                if (acc != null && !isNaN(acc) && acc > MAX_TRACKING_ACCURACY_M) {
                    if (statusEl) {
                        statusEl.textContent = 'Low accuracy - move outdoors and wait';
                    }
                    return;
                }

                if (jumpoffRef && jumpoffRef.lat != null && jumpoffRef.lng != null && distEl) {
                    const d = haversine(lat, lng, jumpoffRef.lat, jumpoffRef.lng);
                    distEl.textContent = d.toFixed(2) + ' km';
                    if (statusEl) {
                        statusEl.textContent = d < 1 ? 'Near jump-off' : d < 5 ? 'On trail' : 'Away from jump-off';
                    }
                } else if (distEl) {
                    distEl.textContent = '—';
                }

                hikeAppendTrackPoint(lat, lng);
                window.hikeTracker.hasLiveFix = true;

                if (!window.hikeTracker.polyline && window.hikeTracker.path.length > 0) {
                    window.hikeTracker.polyline = new google.maps.Polyline({
                        path: window.hikeTracker.path,
                        geodesic: true,
                        strokeColor: '#2563eb',
                        strokeOpacity: 0.92,
                        strokeWeight: 4,
                        map,
                    });
                } else if (window.hikeTracker.polyline) {
                    window.hikeTracker.polyline.setPath(window.hikeTracker.path);
                }

                if (!window.hikeTracker.userMarker) {
                    window.hikeTracker.userMarker = new google.maps.Marker({
                        position: { lat, lng },
                        map,
                        title: 'You (live)',
                        icon: { url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png' },
                    });
                } else {
                    window.hikeTracker.userMarker.setPosition({ lat, lng });
                }
                map.setCenter({ lat, lng });
                if (map.getZoom() < 16) map.setZoom(16);

                const tKm = hikeTrailLengthKm(window.hikeTracker.path);
                if (trailEl) {
                    trailEl.textContent = window.hikeTracker.path.length < 2
                        ? '0 m'
                        : (tKm < 0.1 ? (tKm * 1000).toFixed(0) + ' m' : tKm.toFixed(2) + ' km');
                }

                pushHikerLocationToServer({
                    lat, lng,
                    accuracy_m: pos.coords.accuracy,
                    altitude_m: pos.coords.altitude,
                    speed_mps: pos.coords.speed,
                });
            };

            const onErr = (err) => {
                if (statusEl) {
                    const base = err.code === 1 ? 'Location permission denied' : (err.code === 2 ? 'Signal lost (position unavailable)' : 'GPS timeout');
                    statusEl.textContent = window.hikeTracker.lastFix
                        ? `${base} - showing last saved track`
                        : base;
                }
                if (window.hikeTracker.lastFix) {
                    pushHikerLocationToServer(window.hikeTracker.lastFix);
                    const lastEl = document.getElementById('tracker-last-fix');
                    if (lastEl) lastEl.textContent = new Date(window.hikeTracker.lastFix.at || Date.now()).toLocaleTimeString();
                    return;
                }
                stopLiveTracking();
            };

            window.hikeTracker.watchId = navigator.geolocation.watchPosition(onPos, onErr, geoOpts);
        }

        function stopLiveTracking() {
            if (window.hikeTracker.watchId != null) {
                navigator.geolocation.clearWatch(window.hikeTracker.watchId);
                window.hikeTracker.watchId = null;
            }
            window.hikeTracker.isTracking = false;
            const btn = document.getElementById('track-btn');
            const label = document.getElementById('track-btn-label');
            if (btn) btn.setAttribute('aria-pressed', 'false');
            if (label) label.textContent = 'Start live tracking';
            const statusEl = document.getElementById('tracker-status');
            if (statusEl && !/permission denied|unavailable|timeout/i.test(statusEl.textContent)) {
                statusEl.textContent = 'Paused';
            }
        }

        window.clearHikeTrail = function() {
            window.hikeTracker.path = [];
            window.hikeTracker.hasLiveFix = false;
            if (window.hikeTracker.polyline) {
                window.hikeTracker.polyline.setPath([]);
            }
            if (window.hikeTracker.userMarker) {
                window.hikeTracker.userMarker.setMap(null);
                window.hikeTracker.userMarker = null;
            }
            const trailEl = document.getElementById('tracker-trail-km');
            if (trailEl) trailEl.textContent = '-- km';
            const clearBtn = document.getElementById('track-clear-btn');
            if (clearBtn) {
                clearBtn.style.display = (window.hikeTracker.isTracking || window.hikeTracker.path.length > 0) ? '' : 'none';
            }
        };

        // ── What to Bring Checklist ───────────────────────────────
        window.updateChecklist = function() {
            const checks = document.querySelectorAll('.ns-checkbox');
            let total = checks.length, checked = 0;
            const state = {};
            checks.forEach(cb => { if (cb.checked) checked++; state[cb.dataset.item] = cb.checked; });
            document.getElementById('checklist-progress-text').textContent = `${checked} / ${total} packed`;
            document.getElementById('checklist-progress-bar').style.width = (total > 0 ? (checked/total)*100 : 0) + '%';
            const key = 'hike_checklist_' + window.HIKER_BOOTSTRAP.userId;
            localStorage.setItem(key, JSON.stringify(state));
        };

        function restoreChecklist() {
            try {
                const key = 'hike_checklist_' + window.HIKER_BOOTSTRAP.userId;
                const saved = JSON.parse(localStorage.getItem(key) || '{}');
                document.querySelectorAll('.ns-checkbox').forEach(cb => {
                    if (saved[cb.dataset.item]) cb.checked = true;
                });
                updateChecklist();
            } catch(e) {}
        }

        // ── Completed Hike Feedback ───────────────────────────────
        function feedbackSummaryText(kind, review) {
            const rating = Number(review?.rating || 0);
            if (!rating) {
                return kind === 'guide'
                    ? 'Save one review for this guide on this hike.'
                    : 'Save one review for this hike.';
            }

            const body = String(review?.body || '').trim();
            const label = kind === 'guide' ? 'Guide feedback saved' : 'Mountain feedback saved';
            if (!body) {
                return `${label} with a ${rating}/5 rating.`;
            }

            const shortened = body.length > 110 ? `${body.slice(0, 107)}...` : body;

            return `${label} with a ${rating}/5 rating. "${shortened}"`;
        }

        function updateFeedbackPanelState(panel, kind, review) {
            if (!panel) return;

            const rating = Number(review?.rating || 0);
            const hasReview = rating >= 1 && rating <= 5;
            const form = panel.querySelector('[data-feedback-form]');
            const ratingInput = form?.querySelector('input[name="rating"]');
            const textarea = form?.querySelector('textarea[name="body"]');
            const state = panel.querySelector('[data-feedback-state]');
            const message = panel.querySelector('[data-feedback-message]');
            const submit = panel.querySelector('[data-feedback-submit]');

            if (ratingInput && hasReview) {
                ratingInput.value = String(rating);
            }

            if (textarea && review && typeof review.body !== 'undefined') {
                textarea.value = review.body || '';
            }

            form?.querySelectorAll('.ns-inline-rating-btn').forEach((button) => {
                const buttonValue = Number(button.dataset.value || 0);
                button.classList.toggle('active', buttonValue === Number(ratingInput?.value || 5));
            });

            if (state) {
                state.textContent = hasReview ? 'Submitted' : 'Not yet';
                state.classList.toggle('submitted', hasReview);
                state.classList.toggle('pending', !hasReview);
            }

            if (message) {
                message.textContent = feedbackSummaryText(kind, review);
                message.classList.remove('is-error');
            }

            if (submit) {
                submit.disabled = false;
                submit.textContent = hasReview
                    ? `Update ${kind === 'guide' ? 'guide' : 'mountain'} feedback`
                    : `Save ${kind === 'guide' ? 'guide' : 'mountain'} feedback`;
            }
        }

        function syncCompletedFeedback(kind, bookingId, review) {
            document
                .querySelectorAll(`[data-feedback-panel="${kind}"][data-booking-id="${bookingId}"]`)
                .forEach((panel) => updateFeedbackPanelState(panel, kind, review));
        }

        function extractFeedbackError(payload, fallback) {
            if (payload?.errors) {
                const message = Object.values(payload.errors).flat()[0];
                if (typeof message === 'string' && message.trim() !== '') {
                    return message;
                }
            }

            if (typeof payload?.message === 'string' && payload.message.trim() !== '') {
                return payload.message;
            }

            return fallback;
        }

        function syncFeedbackDropdownMode() {
            const isMobile = window.matchMedia('(max-width: 640px)').matches;
            document.querySelectorAll('[data-feedback-dropdown]').forEach((dropdown) => {
                if (isMobile) {
                    dropdown.removeAttribute('open');
                } else {
                    dropdown.setAttribute('open', 'open');
                }
            });
        }

        syncFeedbackDropdownMode();
        window.addEventListener('resize', syncFeedbackDropdownMode);

        window.setInlineFeedbackRating = function(button, value) {
            const form = button.closest('[data-feedback-form]');
            if (!form) return;
            const input = form.querySelector('input[name="rating"]');
            if (!input) return;

            input.value = String(value);
            form.querySelectorAll('.ns-inline-rating-btn').forEach((item) => {
                item.classList.toggle('active', Number(item.dataset.value || 0) === value);
            });
        };

        window.submitCompletedFeedback = function(e) {
            e.preventDefault();

            const form = e.target;
            const kind = form.dataset.feedbackType;
            const bookingId = form.dataset.bookingId;
            const submit = form.querySelector('[data-feedback-submit]');
            const message = form.querySelector('[data-feedback-message]');
            const url = kind === 'guide'
                ? window.HIKER_BOOTSTRAP.routes.storeGuideReview
                : window.HIKER_BOOTSTRAP.routes.storeReview;
            const fd = new FormData(form);

            fd.append('_token', window.HIKER_BOOTSTRAP.csrf);

            if (submit) {
                submit.disabled = true;
                submit.textContent = 'Saving...';
            }
            if (message) {
                message.textContent = 'Saving feedback...';
                message.classList.remove('is-error');
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            })
                .then(async (response) => {
                    const payload = await response.json().catch(() => ({}));
                    return { ok: response.ok, payload };
                })
                .then(({ ok, payload }) => {
                    if (!ok || !payload.success) {
                        throw new Error(extractFeedbackError(payload, 'Could not save feedback.'));
                    }

                    const review = {
                        rating: Number(payload.review?.rating || form.querySelector('input[name="rating"]')?.value || 5),
                        body: String(payload.review?.body || form.querySelector('textarea[name="body"]')?.value || ''),
                    };

                    syncCompletedFeedback(kind, bookingId, review);
                })
                .catch((error) => {
                    if (submit) {
                        const wasSubmitted = form
                            .closest('[data-feedback-panel]')
                            ?.querySelector('[data-feedback-state]')
                            ?.classList
                            .contains('submitted');
                        submit.disabled = false;
                        submit.textContent = wasSubmitted
                            ? `Update ${kind === 'guide' ? 'guide' : 'mountain'} feedback`
                            : `Save ${kind === 'guide' ? 'guide' : 'mountain'} feedback`;
                    }
                    if (message) {
                        message.textContent = error.message || 'Could not save feedback.';
                        message.classList.add('is-error');
                    }
                });
        };

        window.filterMountains = function() {
            const query = (document.getElementById('mountain-search-input')?.value || '').toLowerCase();
            const difficulty = document.getElementById('mountain-difficulty-filter')?.value || 'All Difficulties';
            document.querySelectorAll('.mountain-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                const diffElem = card.querySelector('.thumb-difficulty');
                const cardDiff = diffElem ? diffElem.textContent.trim() : '';
                const matchQuery = text.includes(query);
                const matchDiff = difficulty === 'All Difficulties' || cardDiff === difficulty;
                card.style.display = matchQuery && matchDiff ? '' : 'none';
            });
        };
        // ── Community Post ────────────────────────────────────────
        window.createPost = function() {
            const textarea = document.querySelector('.ns-post-input');
            if (!textarea || !textarea.value.trim()) { alert('Please write something before posting.'); return; }
            const fd = new FormData();
            fd.append('body', textarea.value.trim());
            fd.append('_token', window.HIKER_BOOTSTRAP.csrf);
            const ms = document.getElementById('community-mountain');
            if (ms && ms.value) fd.append('mountain_id', ms.value);
            fetch(window.HIKER_BOOTSTRAP.routes.storeCommunityPost, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    textarea.value = '';
                    if (ms) ms.value = '';
                    window.location.reload();
                } else {
                    alert('Could not post.');
                }
            }).catch(() => alert('Could not post.'));
        };
    </script>

    {{-- Google Maps JavaScript API --}}
    <script>
        // Initialize default map for tracker page on load
        function initTrackerDefaultMap() {
            if (typeof googleMapsReadyResolve === 'function') {
                googleMapsReadyResolve(true);
                googleMapsReadyResolve = null;
            }
            if (typeof ensureHikeTrackerMap !== 'function') return;
            if (ensureHikeTrackerMap() && window.hikeTracker.map) {
                setTimeout(function() {
                    google.maps.event.trigger(window.hikeTracker.map, 'resize');
                }, 0);
            }
        }
    </script>
    @if (filled(config('services.google_maps.key')))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ e(config('services.google_maps.key')) }}&v=beta&callback=initTrackerDefaultMap" async defer></script>
    @endif
</body>
