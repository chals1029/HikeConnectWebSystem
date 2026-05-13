<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HikeConnect | Admin</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $avatarUrl = $user->profile_picture_url;
        $statusOptions = [
            'available' => 'Available',
            'on-hike' => 'On a hike',
            'unavailable' => 'Unavailable',
            'off-duty' => 'Off duty',
        ];
        $mountainMapsPayload = $mountainMaps;
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
                    @include('partials._notification-bell')
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
                    <input type="text" placeholder="Search admin side..." aria-label="Search admin side" id="adm-search">
                </div>

                <div class="menu-title">Menu</div>
                <a href="#" class="menu-item active">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span class="menu-text">Overview</span>
                </a>
                <a href="#live-map" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11a3 3 0 1 0 6 0 3 3 0 0 0-6 0z"></path><path d="M17.657 16.657L13.414 20.9a2 2 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"></path></svg>
                    <span class="menu-text">Live Map</span>
                    @if($stats['live_hikers'] > 0)<span class="menu-badge">{{ $stats['live_hikers'] }}</span>@endif
                </a>
                <a href="#safety" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M12 8v4"></path><path d="M12 16h.01"></path></svg>
                    <span class="menu-text">Safety Center</span>
                    @if(($stats['open_sos_alerts'] ?? 0) > 0)<span class="menu-badge" style="background:#fee2e2;color:#991b1b;">{{ $stats['open_sos_alerts'] }}</span>@endif
                </a>
                <a href="#security" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 4v6c0 5-3.4 8.6-8 10-4.6-1.4-8-5-8-10V7l8-4z"></path><path d="M9 12l2 2 4-4"></path></svg>
                    <span class="menu-text">Security Monitor</span>
                    @if(($security['risk_level'] ?? 'low') === 'high')<span class="menu-badge" style="background:#fee2e2;color:#991b1b;">Risk</span>@endif
                </a>
                <a href="#analytics" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M7 14l4-4 4 4 5-5"></path><circle cx="7" cy="14" r="1.6" fill="currentColor" stroke="none"></circle><circle cx="11" cy="10" r="1.6" fill="currentColor" stroke="none"></circle><circle cx="15" cy="14" r="1.6" fill="currentColor" stroke="none"></circle><circle cx="20" cy="9" r="1.6" fill="currentColor" stroke="none"></circle></svg>
                    <span class="menu-text">Analytics</span>
                </a>
                <a href="#guides" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="menu-text">Tour Guides</span>
                </a>
                <a href="#mountains" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"></path></svg>
                    <span class="menu-text">Mountains</span>
                </a>
                <a href="#hikers" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="menu-text">Hikers</span>
                </a>
                <a href="#admins" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span class="menu-text">Admins</span>
                </a>
                <a href="#audit" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span class="menu-text">Audit Logs</span>
                </a>
                <a href="#notifications" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <span class="menu-text">Notifications</span>
                </a>
                <a href="#health" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    <span class="menu-text">System Health</span>
                </a>

                <div class="group-title">Snapshot</div>
                <div class="group-list">
                    <div class="group-item"><span class="dot green"></span> <span class="group-item-text">{{ $stats['total_hikers'] }} hikers</span></div>
                    <div class="group-item"><span class="dot blue"></span> <span class="group-item-text">{{ $stats['total_guides'] }} guides</span></div>
                    <div class="group-item"><span class="dot orange"></span> <span class="group-item-text">{{ $stats['pending_bookings'] }} pending</span></div>
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
                                {{ substr($user->first_name ?? 'A', 0, 1) }}{{ substr($user->last_name ?? 'D', 0, 1) }}
                            @endif
                        </div>
                        <div class="profile-info">
                            <div class="profile-name">{{ $user->full_name }}</div>
                            <div class="profile-role">Administrator</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">@csrf</form>
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

            {{-- Flash feedback: toast (see HC_ADMIN_BOOT + initAdminFlash in page script) --}}

            {{-- ============== OVERVIEW ============== --}}
            <div class="view-section active" id="view-dashboard">
                @php
                    $sm        = collect($analytics['signups_30d']);
                    $smTotal   = (int) $sm->sum('count');
                    $smMax     = max(1, (int) $sm->max('count'));
                    $smRecent7 = (int) $sm->slice(-7)->sum('count');
                    $smPrev7   = (int) $sm->slice(-14, 7)->sum('count');
                    $smDelta   = $smPrev7 > 0 ? round((($smRecent7 - $smPrev7) / $smPrev7) * 100) : ($smRecent7 > 0 ? 100 : 0);

                    $bm        = collect($analytics['bookings_30d']);
                    $bmTotal   = (int) $bm->sum('count');
                    $bmMax     = max(1, (int) $bm->max('count'));
                    $bmRecent7 = (int) $bm->slice(-7)->sum('count');
                    $bmPrev7   = (int) $bm->slice(-14, 7)->sum('count');
                    $bmDelta   = $bmPrev7 > 0 ? round((($bmRecent7 - $bmPrev7) / $bmPrev7) * 100) : ($bmRecent7 > 0 ? 100 : 0);

                    $mountainsActive = collect($mountainMaps)->filter(fn ($m) => ($m['active_count'] ?? 0) > 0)->count();
                    $maxBookingsRank = max(1, (int) ($analytics['top_mountains']->max('bookings_count') ?? 1));
                    $maxGuideRank    = max(1, (int) ($analytics['top_guides']->max('bookings_count') ?? 1));

                    // Build SVG sparkline path strings from the 30d series.
                    $sparkPath = function ($series, $w = 100, $h = 32) {
                        $vals = array_map(fn ($d) => (float) ($d['count'] ?? 0), $series->all());
                        if (empty($vals)) return ['line' => '', 'area' => ''];
                        $max = max(1, max($vals));
                        $n   = count($vals);
                        $pts = [];
                        foreach ($vals as $i => $v) {
                            $x = $n > 1 ? ($i / ($n - 1)) * $w : $w / 2;
                            $y = $h - ($v / $max) * ($h - 4) - 2;
                            $pts[] = round($x, 2).','.round($y, 2);
                        }
                        $line = 'M '.implode(' L ', $pts);
                        $area = $line.' L '.$w.','.$h.' L 0,'.$h.' Z';
                        return ['line' => $line, 'area' => $area];
                    };
                    $smSpark = $sparkPath($sm);
                    $bmSpark = $sparkPath($bm);

                    // Categorize an audit-log action for the activity feed tag.
                    $logTone = function ($action) {
                        $a = strtolower((string) $action);
                        return match (true) {
                            str_contains($a, 'create') || str_contains($a, 'register') || str_contains($a, 'signup') => 'create',
                            str_contains($a, 'approve') || str_contains($a, 'complete') => 'approve',
                            str_contains($a, 'cancel') || str_contains($a, 'reject') => 'cancel',
                            str_contains($a, 'delete') || str_contains($a, 'remove')  => 'delete',
                            str_contains($a, 'login')  || str_contains($a, 'logout')  => 'login',
                            str_contains($a, 'update') || str_contains($a, 'edit') || str_contains($a, 'change') => 'update',
                            default => 'update',
                        };
                    };
                @endphp

                {{-- HERO --}}
                <section class="hc-hero">
                    <div class="hc-hero-row">
                        <div>
                            <span class="hc-hero-eyebrow">
                                <iconify-icon icon="lucide:shield-check"></iconify-icon>
                                Admin Console &middot; {{ now()->format('l, M j, Y') }}
                            </span>
                            <h1>Welcome back, <span class="hc-hero-name">{{ $user->first_name }}</span></h1>
                            <p>Mission control for every hiker, guide, and trail. Here's how the trails are flowing right now.</p>
                        </div>
                        <div class="hc-hero-cta">
                            <a href="#live-map" class="hc-chip is-live" data-section="live-map">
                                <strong>{{ $stats['live_hikers'] }}</strong>
                                {{ Str::plural('hiker', $stats['live_hikers']) }} on the move
                            </a>
                            @if($stats['pending_bookings'] > 0)
                                <a href="#audit" class="hc-chip is-amber">
                                    <iconify-icon icon="lucide:clock-alert"></iconify-icon>
                                    <strong>{{ $stats['pending_bookings'] }}</strong>
                                    pending bookings
                                </a>
                            @endif
                            <a href="#health" class="hc-chip">
                                <iconify-icon icon="lucide:activity"></iconify-icon>
                                System health
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
                            @if($mountainsActive > 0)
                                {{ $mountainsActive }} of {{ $stats['total_mountains'] }} mountains are active
                            @else
                                All quiet on the mountains
                            @endif
                        </h3>
                        <p class="hc-today-sub">
                            @if($stats['live_hikers'] > 0)
                                {{ $stats['live_hikers'] }} {{ Str::plural('hiker', $stats['live_hikers']) }} broadcasting live GPS &middot; {{ $stats['pending_bookings'] }} {{ Str::plural('request', $stats['pending_bookings']) }} awaiting approval
                            @else
                                No GPS pings yet today &middot; {{ $stats['pending_bookings'] }} {{ Str::plural('request', $stats['pending_bookings']) }} awaiting approval
                            @endif
                        </p>
                    </div>
                    <div class="hc-today-stat">
                        <div class="v live">{{ $stats['live_hikers'] }}</div>
                        <div class="l">Live now</div>
                    </div>
                    <div class="hc-today-stat">
                        <div class="v">{{ $stats['avg_mountain_rating'] ? number_format($stats['avg_mountain_rating'], 1) : '—' }}<small style="font-size:14px;color:var(--muted);font-weight:700;"> / 5</small></div>
                        <div class="l">Avg trail rating</div>
                    </div>
                </section>

                {{-- STAT TILES --}}
                <div class="hc-stats">
                    <div class="hc-stat tone-forest">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:users"></iconify-icon></div>
                            @if($smRecent7 > 0 || $smPrev7 > 0)
                                <span class="hc-stat-trend {{ $smDelta >= 0 ? '' : 'is-down' }}">
                                    <iconify-icon icon="{{ $smDelta >= 0 ? 'lucide:trending-up' : 'lucide:trending-down' }}"></iconify-icon>
                                    {{ abs($smDelta) }}%
                                </span>
                            @endif
                        </div>
                        <h4 class="hc-stat-label">Total Hikers</h4>
                        <div class="hc-stat-value">{{ number_format($stats['total_hikers']) }}</div>
                        <div class="hc-stat-foot"><span>+{{ $smRecent7 }} this week</span><strong>{{ $smTotal }} / 30d</strong></div>
                        @if(!empty($smSpark['line']))
                            <svg class="hc-spark" viewBox="0 0 100 32" preserveAspectRatio="none">
                                <path class="hc-spark-area" d="{{ $smSpark['area'] }}" fill="#10b981"/>
                                <path class="hc-spark-line" d="{{ $smSpark['line'] }}" stroke="#065f46"/>
                            </svg>
                        @endif
                    </div>

                    <div class="hc-stat tone-leaf">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:compass"></iconify-icon></div>
                            <span class="hc-stat-trend is-flat"><iconify-icon icon="lucide:user-check"></iconify-icon> Active</span>
                        </div>
                        <h4 class="hc-stat-label">Tour Guides</h4>
                        <div class="hc-stat-value">{{ $stats['total_guides'] }}</div>
                        <div class="hc-stat-foot"><span>{{ $stats['total_admins'] }} {{ Str::plural('admin', $stats['total_admins']) }} too</span><strong>Team</strong></div>
                    </div>

                    <div class="hc-stat tone-peak">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:mountain"></iconify-icon></div>
                            <span class="hc-stat-trend is-flat"><iconify-icon icon="lucide:flag"></iconify-icon> Mapped</span>
                        </div>
                        <h4 class="hc-stat-label">Mountains</h4>
                        <div class="hc-stat-value">{{ $stats['total_mountains'] }}</div>
                        <div class="hc-stat-foot"><span>{{ $mountainsActive }} active right now</span><strong>Trails</strong></div>
                    </div>

                    <div class="hc-stat tone-amber">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:calendar-check-2"></iconify-icon></div>
                            @if($bmRecent7 > 0 || $bmPrev7 > 0)
                                <span class="hc-stat-trend {{ $bmDelta >= 0 ? '' : 'is-down' }}">
                                    <iconify-icon icon="{{ $bmDelta >= 0 ? 'lucide:trending-up' : 'lucide:trending-down' }}"></iconify-icon>
                                    {{ abs($bmDelta) }}%
                                </span>
                            @endif
                        </div>
                        <h4 class="hc-stat-label">Bookings</h4>
                        <div class="hc-stat-value">{{ number_format($stats['total_bookings']) }}</div>
                        <div class="hc-stat-foot"><span>{{ $stats['pending_bookings'] }} pending &middot; {{ $stats['approved_bookings'] }} approved</span><strong>{{ $bmTotal }} / 30d</strong></div>
                        @if(!empty($bmSpark['line']))
                            <svg class="hc-spark" viewBox="0 0 100 32" preserveAspectRatio="none">
                                <path class="hc-spark-area" d="{{ $bmSpark['area'] }}" fill="#fb923c"/>
                                <path class="hc-spark-line" d="{{ $bmSpark['line'] }}" stroke="#c2410c"/>
                            </svg>
                        @endif
                    </div>

                    <div class="hc-stat tone-sunrise">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:radio-tower"></iconify-icon></div>
                            <span class="hc-stat-trend"><iconify-icon icon="lucide:dot"></iconify-icon> Live</span>
                        </div>
                        <h4 class="hc-stat-label">Live hikers</h4>
                        <div class="hc-stat-value"><a href="#live-map">{{ $stats['live_hikers'] }}</a></div>
                        <div class="hc-stat-foot"><span>tracking GPS now</span><strong>Map →</strong></div>
                    </div>

                    <div class="hc-stat tone-stone">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:star"></iconify-icon></div>
                            <span class="hc-stat-trend is-flat">{{ $stats['reviews_count'] }} {{ Str::plural('review', $stats['reviews_count']) }}</span>
                        </div>
                        <h4 class="hc-stat-label">Trail rating</h4>
                        <div class="hc-stat-value">{{ $stats['avg_mountain_rating'] ? number_format($stats['avg_mountain_rating'], 1) : '—' }}<span class="hc-stat-suffix">/ 5</span></div>
                        <div class="hc-stat-foot"><span>across all mountains</span><strong>Avg</strong></div>
                    </div>

                    <div class="hc-stat tone-forest">
                        <div class="hc-stat-head">
                            <div class="hc-stat-icon"><iconify-icon icon="lucide:messages-square"></iconify-icon></div>
                            <span class="hc-stat-trend is-flat">{{ $stats['experience_total'] }} {{ Str::plural('vote', $stats['experience_total']) }}</span>
                        </div>
                        <h4 class="hc-stat-label">User experience</h4>
                        <div class="hc-stat-value">{{ $stats['experience_positive_pct'] }}<span class="hc-stat-suffix">%</span></div>
                        <div class="hc-stat-foot"><span>positive (okay + great)</span><strong>Emoji feedback</strong></div>
                    </div>
                </div>

                {{-- TREND LINE CHARTS --}}
                <div class="hc-row-2">
                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:user-plus-2"></iconify-icon> New sign-ups · last 30 days</h3>
                            <span class="hc-pill">{{ $smTotal }} total</span>
                        </div>
                        @if($sm->isEmpty())
                            <div class="hc-empty"><iconify-icon icon="lucide:users-round"></iconify-icon> No sign-ups in the last 30 days.</div>
                        @else
                            <x-hc-line-chart
                                :series="$sm->all()"
                                color-stroke="#065f46"
                                color-fill="#10b981"
                                accent="#34d399"
                                noun="signup"
                            />
                        @endif
                        <div class="hc-legend-inline">
                            <span class="item"><span class="swatch" style="background:#10b981;"></span> Sign-ups</span>
                            <span class="item">Last 7 days <strong>{{ $smRecent7 }}</strong></span>
                            <span class="item">Prev 7 days <strong>{{ $smPrev7 }}</strong></span>
                        </div>
                    </div>

                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:calendar-days"></iconify-icon> Bookings · last 30 days</h3>
                            <span class="hc-pill">{{ $bmTotal }} total</span>
                        </div>
                        @if($bm->isEmpty())
                            <div class="hc-empty"><iconify-icon icon="lucide:calendar-x"></iconify-icon> No bookings in the last 30 days.</div>
                        @else
                            <x-hc-line-chart
                                :series="$bm->all()"
                                color-stroke="#c2410c"
                                color-fill="#fb923c"
                                accent="#f59e0b"
                                noun="booking"
                            />
                        @endif
                        <div class="hc-legend-inline">
                            <span class="item"><span class="swatch" style="background:#fb923c;"></span> Bookings</span>
                            <span class="item">Last 7 days <strong>{{ $bmRecent7 }}</strong></span>
                            <span class="item">Prev 7 days <strong>{{ $bmPrev7 }}</strong></span>
                        </div>
                    </div>
                </div>

                {{-- TOP RANKINGS --}}
                <div class="hc-row-2">
                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:trophy"></iconify-icon> Top mountains</h3>
                            <span class="hc-pill">By bookings</span>
                        </div>
                        @if($analytics['top_mountains']->isEmpty())
                            <div class="hc-empty"><iconify-icon icon="lucide:mountain"></iconify-icon> No bookings yet.</div>
                        @else
                            <div class="hc-rank-list">
                                @foreach($analytics['top_mountains'] as $i => $m)
                                    <div class="hc-rank-row {{ $i === 0 ? 'is-top1' : ($i === 1 ? 'is-top2' : ($i === 2 ? 'is-top3' : '')) }}">
                                        <div class="hc-rank-num">{{ $i + 1 }}</div>
                                        <div class="hc-rank-info">
                                            <div class="hc-rank-name">{{ $m->name }}</div>
                                            <div class="hc-rank-sub">
                                                <iconify-icon icon="lucide:map-pin"></iconify-icon>
                                                {{ $m->location }}
                                            </div>
                                            <div class="hc-rank-meter"><span style="width:{{ max(6, round(($m->bookings_count / $maxBookingsRank) * 100)) }}%;"></span></div>
                                        </div>
                                        <div class="hc-rank-count">{{ $m->bookings_count }} <small>hikes</small></div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:medal"></iconify-icon> Top tour guides</h3>
                            <span class="hc-pill">By bookings</span>
                        </div>
                        @if($analytics['top_guides']->isEmpty())
                            <div class="hc-empty"><iconify-icon icon="lucide:user-round-search"></iconify-icon> No guides yet.</div>
                        @else
                            <div class="hc-rank-list">
                                @foreach($analytics['top_guides'] as $i => $g)
                                    <div class="hc-rank-row {{ $i === 0 ? 'is-top1' : ($i === 1 ? 'is-top2' : ($i === 2 ? 'is-top3' : '')) }}">
                                        <div class="hc-rank-num">{{ $i + 1 }}</div>
                                        <div class="hc-rank-info" style="display:flex;align-items:center;gap:10px;">
                                            <div class="hc-rank-mini" style="{{ $g->profile_picture_url ? 'background-image:url('.$g->profile_picture_url.')' : '' }}">{{ $g->initials }}</div>
                                            <div style="min-width:0;flex:1;">
                                                <div class="hc-rank-name">{{ $g->full_name }}</div>
                                                <div class="hc-rank-sub">
                                                    <iconify-icon icon="lucide:compass"></iconify-icon>
                                                    {{ $g->specialty }}
                                                </div>
                                                <div class="hc-rank-meter"><span style="width:{{ max(6, round(($g->bookings_count / $maxGuideRank) * 100)) }}%;"></span></div>
                                            </div>
                                        </div>
                                        <div class="hc-rank-count">{{ $g->bookings_count }} <small>hikes</small></div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- RECENT ACTIVITY --}}
                <div class="hc-panel">
                    <div class="hc-panel-head">
                        <h3><iconify-icon icon="lucide:waypoints"></iconify-icon> Recent activity</h3>
                        <a href="#audit">View all →</a>
                    </div>
                    @if($recentLogs->isEmpty())
                        <div class="hc-empty"><iconify-icon icon="lucide:clipboard-list"></iconify-icon> No activity yet.</div>
                    @else
                        <div class="hc-feed">
                            @foreach($recentLogs->take(12) as $log)
                                <div class="hc-feed-row">
                                    <span class="hc-feed-tag t-{{ $logTone($log->action) }}">{{ Str::limit($log->action, 24, '…') }}</span>
                                    <div class="hc-feed-body">
                                        <div class="hc-feed-desc">{{ $log->description }}</div>
                                        <div class="hc-feed-meta">
                                            @if($log->actor)by <strong>{{ $log->actor->full_name }}</strong>@endif
                                            @if($log->user && $log->user_id !== $log->actor_id) &middot; about <strong>{{ $log->user->full_name }}</strong>@endif
                                        </div>
                                    </div>
                                    <div class="hc-feed-time" title="{{ $log->created_at }}">{{ $log->created_at?->diffForHumans() }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== ANALYTICS ============== --}}
            <div class="view-section" id="view-analytics">
                @php
                    $bookingsPipeline = [
                        ['label' => 'All requests',  'sub' => 'every booking ever',                     'value' => $stats['total_bookings'],     'class' => 'f-1'],
                        ['label' => 'Pending',       'sub' => 'awaiting tour-guide approval',           'value' => $stats['pending_bookings'],   'class' => 'f-2'],
                        ['label' => 'Approved',      'sub' => 'confirmed and on the schedule',         'value' => $stats['approved_bookings'],  'class' => 'f-3'],
                        ['label' => 'Completed',     'sub' => 'hikers who summited with us',          'value' => $stats['completed_bookings'], 'class' => 'f-4'],
                    ];
                    $convApproved  = $stats['total_bookings'] > 0 ? round(($stats['approved_bookings']  / $stats['total_bookings']) * 100) : 0;
                    $convCompleted = $stats['total_bookings'] > 0 ? round(($stats['completed_bookings'] / $stats['total_bookings']) * 100) : 0;

                    $statusSlices = array_values(array_filter([
                        ['label' => 'Completed', 'value' => $stats['completed_bookings'], 'color' => '#10b981'],
                        ['label' => 'Approved',  'value' => $stats['approved_bookings'],  'color' => '#0ea5e9'],
                        ['label' => 'Pending',   'value' => $stats['pending_bookings'],   'color' => '#f59e0b'],
                        ['label' => 'Cancelled', 'value' => $stats['cancelled_bookings'], 'color' => '#ef4444'],
                    ], fn ($s) => $s['value'] > 0));

                    $rolesSlices = array_values(array_filter([
                        ['label' => 'Hikers',      'value' => $stats['total_hikers'],   'color' => '#10b981'],
                        ['label' => 'Tour Guides', 'value' => $stats['total_guides'],   'color' => '#0ea5e9'],
                        ['label' => 'Admins',      'value' => $stats['total_admins'],   'color' => '#f59e0b'],
                    ], fn ($s) => $s['value'] > 0));

                    // Top mountains share-of-bookings + "Other"
                    $topMountainsForChart = $analytics['top_mountains']->take(5);
                    $topMountainsSum      = (int) $topMountainsForChart->sum('bookings_count');
                    $otherMountainsBookings = max(0, $stats['total_bookings'] - $topMountainsSum);
                    $mountainPalette = ['#065f46', '#10b981', '#34d399', '#0ea5e9', '#f59e0b'];
                    $mountainSlices  = [];
                    foreach ($topMountainsForChart as $i => $m) {
                        if ($m->bookings_count > 0) {
                            $mountainSlices[] = ['label' => $m->name, 'value' => $m->bookings_count, 'color' => $mountainPalette[$i] ?? '#94a3b8'];
                        }
                    }
                    if ($otherMountainsBookings > 0) {
                        $mountainSlices[] = ['label' => 'Other mountains', 'value' => $otherMountainsBookings, 'color' => '#94a3b8'];
                    }

                    $maxMountainBookings = max(1, (int) ($analytics['top_mountains']->max('bookings_count') ?? 1));
                    $maxGuideBookings    = max(1, (int) ($analytics['top_guides']->max('bookings_count') ?? 1));
                @endphp

                <header class="dashboard-header">
                    <h2><iconify-icon icon="lucide:bar-chart-3" style="color:#10b981;font-size:24px;vertical-align:text-bottom;margin-right:6px;"></iconify-icon>Analytics</h2>
                    <p>Trends, conversion, and breakdowns across the whole HikeConnect platform · last 30 days unless noted.</p>
                </header>

                {{-- KPI RIBBON --}}
                <div class="hc-kpi-ribbon">
                    <div class="kpi">
                        <div class="l">Bookings · 30d</div>
                        <div class="v">{{ number_format($bmTotal) }}</div>
                        <div class="h">{{ $bmRecent7 }} in the last 7 days</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Sign-ups · 30d</div>
                        <div class="v">{{ number_format($smTotal) }}</div>
                        <div class="h">{{ $smRecent7 }} in the last 7 days</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Approval rate</div>
                        <div class="v">{{ $convApproved }}<small>%</small></div>
                        <div class="h">{{ $stats['approved_bookings'] }} approved of {{ $stats['total_bookings'] }}</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Completion rate</div>
                        <div class="v">{{ $convCompleted }}<small>%</small></div>
                        <div class="h">{{ $stats['completed_bookings'] }} summited successfully</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Avg trail rating</div>
                        <div class="v">{{ $stats['avg_mountain_rating'] ? number_format($stats['avg_mountain_rating'], 1) : '—' }}<small> / 5</small></div>
                        <div class="h">{{ $stats['reviews_count'] }} {{ Str::plural('review', $stats['reviews_count']) }}</div>
                    </div>
                </div>

                <div class="hc-panel" style="margin-bottom:22px;">
                    <div class="hc-panel-head">
                        <h3><iconify-icon icon="lucide:messages-square"></iconify-icon> User experience feedback (emoji)</h3>
                        <span class="hc-pill">{{ $stats['experience_total'] }} {{ Str::plural('vote', $stats['experience_total']) }}</span>
                    </div>
                    <div class="hc-kpi-ribbon" style="margin-top:0;">
                        <div class="kpi">
                            <div class="l">Great 😄</div>
                            <div class="v">{{ $stats['experience_great'] }}</div>
                            <div class="h">Positive reactions</div>
                        </div>
                        <div class="kpi">
                            <div class="l">Okay 😐</div>
                            <div class="v">{{ $stats['experience_okay'] }}</div>
                            <div class="h">Neutral reactions</div>
                        </div>
                        <div class="kpi">
                            <div class="l">Bad ☹</div>
                            <div class="v">{{ $stats['experience_bad'] }}</div>
                            <div class="h">Negative reactions</div>
                        </div>
                        <div class="kpi">
                            <div class="l">System sentiment</div>
                            <div class="v">{{ $stats['experience_positive_pct'] }}<small>%</small></div>
                            <div class="h">Okay + Great share</div>
                        </div>
                    </div>
                    <div class="dashboard-card" style="margin-top:12px;border:1px solid #d1fae5;background:linear-gradient(180deg,#ecfdf5,var(--panel));">
                        <strong style="display:block;color:#065f46;margin-bottom:6px;">Experience insight</strong>
                        <span style="color:#14532d;">{{ $stats['experience_insight'] }}</span>
                    </div>
                </div>

                {{-- COMBINED TREND LINE CHART (sign-ups vs bookings) --}}
                <div class="hc-panel" style="margin-bottom:22px;">
                    <div class="hc-panel-head">
                        <h3><iconify-icon icon="lucide:line-chart"></iconify-icon> Trend · sign-ups vs bookings</h3>
                        <span class="hc-pill">Last 30 days</span>
                    </div>

                    @php
                        $combined = collect($sm)->map(function ($d) use ($bm) {
                            $match = $bm->firstWhere('date', $d['date']);
                            return [
                                'date' => $d['date'],
                                'count' => (int) $d['count'],
                                'count2' => (int) ($match['count'] ?? 0),
                            ];
                        })->all();
                    @endphp

                    @if(empty($combined))
                        <div class="hc-empty"><iconify-icon icon="lucide:line-chart"></iconify-icon> Not enough activity yet to draw a trend.</div>
                    @else
                        @php
                            $combinedW = 1000;
                            $combinedH = 320;
                            $cPadL = 36; $cPadR = 12; $cPadT = 16; $cPadB = 32;
                            $cInnerW = $combinedW - $cPadL - $cPadR;
                            $cInnerH = $combinedH - $cPadT - $cPadB;
                            $cMax = max(1, max(collect($combined)->max('count'), collect($combined)->max('count2')));
                            $cYMax = max(4, (int) (ceil($cMax / 4) * 4));
                            $cN = count($combined);

                            $ptsA = []; $ptsB = [];
                            foreach ($combined as $i => $d) {
                                $x = $cN > 1 ? $cPadL + ($i / ($cN - 1)) * $cInnerW : $cPadL + $cInnerW / 2;
                                $ya = $cPadT + ($cInnerH * (1 - ($d['count']  / $cYMax)));
                                $yb = $cPadT + ($cInnerH * (1 - ($d['count2'] / $cYMax)));
                                $ptsA[] = ['x' => round($x, 2), 'y' => round($ya, 2), 'v' => $d['count'],  'date' => $d['date']];
                                $ptsB[] = ['x' => round($x, 2), 'y' => round($yb, 2), 'v' => $d['count2'], 'date' => $d['date']];
                            }
                            $smoothPath = function ($points) {
                                $n = count($points);
                                if ($n === 0) return '';
                                $line = 'M ' . $points[0]['x'] . ' ' . $points[0]['y'];
                                for ($i = 1; $i < $n; $i++) {
                                    $p0 = $points[max(0, $i - 2)];
                                    $p1 = $points[$i - 1];
                                    $p2 = $points[$i];
                                    $p3 = $points[min($n - 1, $i + 1)];
                                    $t = 0.18;
                                    $cp1x = $p1['x'] + ($p2['x'] - $p0['x']) * $t;
                                    $cp1y = $p1['y'] + ($p2['y'] - $p0['y']) * $t;
                                    $cp2x = $p2['x'] - ($p3['x'] - $p1['x']) * $t;
                                    $cp2y = $p2['y'] - ($p3['y'] - $p1['y']) * $t;
                                    $line .= sprintf(' C %s %s %s %s %s %s', round($cp1x, 2), round($cp1y, 2), round($cp2x, 2), round($cp2y, 2), $p2['x'], $p2['y']);
                                }
                                return $line;
                            };
                            $lineA = $smoothPath($ptsA);
                            $lineB = $smoothPath($ptsB);
                            $areaA = $lineA . ' L ' . end($ptsA)['x'] . ' ' . ($cPadT + $cInnerH) . ' L ' . $ptsA[0]['x'] . ' ' . ($cPadT + $cInnerH) . ' Z';
                            $areaB = $lineB . ' L ' . end($ptsB)['x'] . ' ' . ($cPadT + $cInnerH) . ' L ' . $ptsB[0]['x'] . ' ' . ($cPadT + $cInnerH) . ' Z';

                            $cTickCount = $cN > 6 ? 6 : max(1, $cN - 1);
                            $cTicks = [];
                            for ($i = 0; $i <= $cTickCount; $i++) {
                                $idx = (int) round(($i / $cTickCount) * ($cN - 1));
                                if (! in_array($idx, $cTicks, true)) $cTicks[] = $idx;
                            }
                        @endphp

                        <div class="hc-line-wrap" style="height:300px;">
                            <svg class="hc-line-svg" viewBox="0 0 {{ $combinedW }} {{ $combinedH }}" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="hc-combined-fillA" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#10b981" stop-opacity="0.45"/>
                                        <stop offset="100%" stop-color="#10b981" stop-opacity="0.02"/>
                                    </linearGradient>
                                    <linearGradient id="hc-combined-fillB" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#fb923c" stop-opacity="0.40"/>
                                        <stop offset="100%" stop-color="#fb923c" stop-opacity="0.02"/>
                                    </linearGradient>
                                </defs>
                                <g class="hc-line-grid">
                                    @for($i = 0; $i <= 4; $i++)
                                        @php $gy = $cPadT + ($cInnerH * (1 - $i / 4)); @endphp
                                        <line x1="{{ $cPadL }}" y1="{{ round($gy, 2) }}" x2="{{ $combinedW - $cPadR }}" y2="{{ round($gy, 2) }}"/>
                                    @endfor
                                </g>
                                <g class="hc-line-axis">
                                    @for($i = 0; $i <= 4; $i++)
                                        @php $gy = $cPadT + ($cInnerH * (1 - $i / 4)); @endphp
                                        <text x="{{ $cPadL - 6 }}" y="{{ round($gy + 3, 2) }}" text-anchor="end">{{ (int) round(($cYMax * $i) / 4) }}</text>
                                    @endfor
                                    @foreach($cTicks as $i)
                                        @php $p = $ptsA[$i] ?? null; @endphp
                                        @if($p)
                                            <text x="{{ $p['x'] }}" y="{{ $combinedH - 10 }}" text-anchor="middle">{{ \Carbon\Carbon::parse($p['date'])->format('M j') }}</text>
                                        @endif
                                    @endforeach
                                </g>
                                <path class="hc-line-area" d="{{ $areaB }}" fill="url(#hc-combined-fillB)"/>
                                <path class="hc-line-stroke" d="{{ $lineB }}" stroke="#c2410c"/>
                                <path class="hc-line-area" d="{{ $areaA }}" fill="url(#hc-combined-fillA)"/>
                                <path class="hc-line-stroke" d="{{ $lineA }}" stroke="#065f46"/>

                                <line class="hc-line-cursor" x1="0" y1="{{ $cPadT }}" x2="0" y2="{{ $cPadT + $cInnerH }}" data-cursor></line>

                                @foreach($ptsA as $i => $p)
                                    <circle class="hc-line-dot" cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="{{ $cN > 20 ? 0 : 3 }}" stroke="#065f46" data-tip="{{ \Carbon\Carbon::parse($p['date'])->format('M j') }} · {{ $p['v'] }} sign-ups · {{ $ptsB[$i]['v'] }} bookings"/>
                                @endforeach

                                <rect class="hc-line-hover" data-hover
                                      x="{{ $cPadL }}" y="{{ $cPadT }}"
                                      width="{{ $cInnerW }}" height="{{ $cInnerH }}"
                                      data-points='@json($ptsA)'
                                      data-points2='@json($ptsB)'
                                      data-stroke="#065f46"
                                      data-stroke2="#c2410c"
                                      data-noun-a="signup"
                                      data-noun-b="booking"/>
                            </svg>
                            <div class="hc-line-tip" data-tip-el></div>
                        </div>

                        <div class="hc-legend-inline">
                            <span class="item"><span class="swatch" style="background:#10b981;"></span> Sign-ups · <strong>{{ $smTotal }}</strong></span>
                            <span class="item"><span class="swatch" style="background:#fb923c;"></span> Bookings · <strong>{{ $bmTotal }}</strong></span>
                            <span class="item">Peak in window: <strong>{{ $cYMax }}</strong>/day</span>
                        </div>
                    @endif
                </div>

                {{-- DONUT GRID: bookings, roles, mountains --}}
                <div class="hc-row-3">
                    <div class="hc-panel hc-donut-card">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:pie-chart"></iconify-icon> Bookings by status</h3>
                            <span class="hc-pill">All time</span>
                        </div>
                        @if(empty($statusSlices))
                            <div class="hc-empty"><iconify-icon icon="lucide:calendar-x"></iconify-icon> No bookings yet.</div>
                        @else
                            <x-hc-donut :slices="$statusSlices" center-label="BOOKINGS"/>
                        @endif
                    </div>

                    <div class="hc-panel hc-donut-card">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:users-round"></iconify-icon> Users by role</h3>
                            <span class="hc-pill">{{ number_format($stats['total_hikers'] + $stats['total_guides'] + $stats['total_admins']) }} accounts</span>
                        </div>
                        @if(empty($rolesSlices))
                            <div class="hc-empty"><iconify-icon icon="lucide:user-x"></iconify-icon> No users yet.</div>
                        @else
                            <x-hc-donut :slices="$rolesSlices" center-label="USERS"/>
                        @endif
                    </div>

                    <div class="hc-panel hc-donut-card">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:mountain"></iconify-icon> Mountain market share</h3>
                            <span class="hc-pill">By bookings</span>
                        </div>
                        @if(empty($mountainSlices))
                            <div class="hc-empty"><iconify-icon icon="lucide:mountain-off"></iconify-icon> No bookings to split yet.</div>
                        @else
                            <x-hc-donut :slices="$mountainSlices" center-label="MOUNTAINS"/>
                        @endif
                    </div>
                </div>

                {{-- FUNNEL + HORIZONTAL TOP MOUNTAINS --}}
                <div class="hc-row-2" style="margin-top:22px;">
                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:filter"></iconify-icon> Booking pipeline</h3>
                            <span class="hc-pill">{{ $convCompleted }}% completion</span>
                        </div>
                        <div class="hc-funnel">
                            @foreach($bookingsPipeline as $row)
                                @php $pct = $stats['total_bookings'] > 0 ? round(($row['value'] / $stats['total_bookings']) * 100) : 0; @endphp
                                <div class="hc-funnel-row {{ $row['class'] }}">
                                    <div class="step">{{ $loop->iteration }}</div>
                                    <div>
                                        <div class="lbl">{{ $row['label'] }}<small>{{ $row['sub'] }}</small></div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div class="v">{{ number_format($row['value']) }}</div>
                                        <div style="font-size:11px;font-weight:700;opacity:0.78;">{{ $pct }}% of total</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="hc-panel">
                        <div class="hc-panel-head">
                            <h3><iconify-icon icon="lucide:bar-chart-horizontal"></iconify-icon> Top mountains</h3>
                            <span class="hc-pill">By bookings</span>
                        </div>
                        @if($analytics['top_mountains']->isEmpty())
                            <div class="hc-empty"><iconify-icon icon="lucide:mountain"></iconify-icon> No bookings yet.</div>
                        @else
                            <div class="hc-hbar-list">
                                @foreach($analytics['top_mountains'] as $i => $m)
                                    <div class="hc-hbar-row {{ $i === 0 ? 'is-top1' : ($i === 1 ? 'is-top2' : ($i === 2 ? 'is-top3' : '')) }}">
                                        <div class="hc-hbar-rank">{{ $i + 1 }}</div>
                                        <div class="hc-hbar-body">
                                            <div class="hc-hbar-head">
                                                <span>{{ $m->name }} <span class="sub">· {{ $m->location }}</span></span>
                                                <span class="v">{{ $m->bookings_count }}</span>
                                            </div>
                                            <div class="hc-hbar-track"><span style="width:{{ max(6, round(($m->bookings_count / $maxMountainBookings) * 100)) }}%;"></span></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TOP GUIDES horizontal bars --}}
                <div class="hc-panel" style="margin-top:22px;">
                    <div class="hc-panel-head">
                        <h3><iconify-icon icon="lucide:medal"></iconify-icon> Top tour guides</h3>
                        <span class="hc-pill">By bookings · all time</span>
                    </div>
                    @if($analytics['top_guides']->isEmpty())
                        <div class="hc-empty"><iconify-icon icon="lucide:user-round-search"></iconify-icon> No guide stats yet.</div>
                    @else
                        <div class="hc-hbar-list">
                            @foreach($analytics['top_guides'] as $i => $g)
                                <div class="hc-hbar-row {{ $i === 0 ? 'is-top1' : ($i === 1 ? 'is-top2' : ($i === 2 ? 'is-top3' : '')) }}">
                                    <div class="hc-hbar-rank">{{ $i + 1 }}</div>
                                    <div class="hc-hbar-body">
                                        <div class="hc-hbar-head">
                                            <span style="display:inline-flex;align-items:center;gap:8px;min-width:0;">
                                                <span class="hc-rank-mini" style="{{ $g->profile_picture_url ? 'background-image:url('.$g->profile_picture_url.')' : '' }}">{{ $g->initials }}</span>
                                                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $g->full_name }} <span class="sub">· {{ $g->specialty }}</span></span>
                                            </span>
                                            <span class="v">{{ $g->bookings_count }}</span>
                                        </div>
                                        <div class="hc-hbar-track amber"><span style="width:{{ max(6, round(($g->bookings_count / $maxGuideBookings) * 100)) }}%;"></span></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== LIVE MAP (per mountain) ============== --}}
            <div class="view-section" id="view-live-map">
                <header class="dashboard-header">
                    <h2>Live Map</h2>
                    <p>Real-time positions of every hiker active on each mountain today. <span class="adm-src-tag adm-src-live">LIVE</span> pins are fresh GPS pings from the hiker app, <span class="adm-src-tag adm-src-stale">LOST</span> pins are gray and show the last known location after the signal dropped, and <span class="adm-src-tag adm-src-sim">SIM</span> pins fall back to the trail dataset when a hiker has not opened Track Location yet. Refreshes every 10 seconds.</p>
                </header>

                @if(empty($googleMapsKey))
                    <div class="dashboard-card" style="margin-bottom:14px;border:1px solid #fed7aa;background:#fff7ed;">
                        <strong style="color:#9a3412;">Google Maps key missing.</strong>
                        <span class="tg-who-sub">Add <code>GOOGLE_MAPS_API_KEY</code> to <code>.env</code> and run <code>php artisan config:clear</code> to enable accurate per-mountain live tracking.</span>
                    </div>
                @endif

                <div class="adm-mountains" id="adm-mountains" data-mountains='@json($mountainMapsPayload)'>
                    @foreach($mountainMaps as $m)
                        <article class="dashboard-card adm-mountain-card" data-mountain-id="{{ $m['id'] }}">
                            <div class="tg-card-title">
                                <div>
                                    <h3 style="margin-bottom:4px;">{{ $m['name'] }}</h3>
                                    <div class="tg-who-sub">{{ $m['location'] ?? '' }} &middot; {{ ucfirst($m['difficulty'] ?? '') }}</div>
                                </div>
                                <div class="adm-mountain-pills">
                                    <span class="tg-pill adm-pill-active" data-role="active-count">{{ $m['active_count'] }} active</span>
                                    <span class="tg-pill adm-pill-live" data-role="live-count">{{ $m['live_count'] }} live</span>
                                    <span class="tg-pill adm-pill-stale" data-role="stale-count">{{ $m['stale_count'] }} lost</span>
                                    <span class="tg-pill adm-pill-sim" data-role="sim-count">{{ $m['simulated_count'] }} sim</span>
                                </div>
                            </div>

                            <div class="adm-mountain-grid">
                                <div class="adm-mountain-map" id="adm-map-{{ $m['slug'] }}" data-slug="{{ $m['slug'] }}"></div>
                                <div class="adm-mountain-side">
                                    <div class="adm-trail-source" data-role="trail-source">
                                        <iconify-icon icon="lucide:route"></iconify-icon>
                                        <span>{{ $m['trail_label'] }}</span>
                                    </div>
                                    <div class="adm-map-side" data-role="hiker-list" data-slug="{{ $m['slug'] }}">
                                        @forelse($m['hikers'] as $h)
                                            <div class="adm-map-row" data-uid="{{ $h['user_id'] }}" data-lat="{{ $h['lat'] }}" data-lng="{{ $h['lng'] }}" data-slug="{{ $m['slug'] }}">
                                                <div class="tg-mini-avatar" style="{{ $h['avatar'] ? 'background-image:url('.$h['avatar'].');background-size:cover;background-position:center;' : '' }}">{{ $h['avatar'] ? '' : $h['initials'] }}</div>
                                                <div style="flex:1;min-width:0;">
                                                    <div class="tg-who-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $h['name'] }}</div>
                                                    <div class="tg-who-sub">
                                                        @if($h['source'] === 'live')
                                                            <span class="adm-src-tag adm-src-live">LIVE</span> {{ \Illuminate\Support\Carbon::parse($h['recorded_at'])->diffForHumans() }}
                                                        @elseif($h['source'] === 'stale')
                                                            <span class="adm-src-tag adm-src-stale">Lost</span> last seen {{ $h['last_seen_minutes'] }}m ago
                                                        @else
                                                            <span class="adm-src-tag adm-src-sim">Sim</span> ~{{ $h['progress_pct'] }}% along trail
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="tg-empty">No hikers on {{ $m['name'] }} right now.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- ============== TOUR GUIDES ============== --}}
            <div class="view-section" id="view-guides">
                <header class="dashboard-header">
                    <div class="tg-topbar">
                        <div>
                            <h2>Tour Guides</h2>
                            <p>Create, update, and remove tour-guide accounts. Bio and profile photo are managed by the guide.</p>
                        </div>
                        <button class="tg-btn primary" type="button" data-open-modal="adm-modal-guide-create">
                            <iconify-icon icon="lucide:plus"></iconify-icon> New tour guide
                        </button>
                    </div>
                </header>

                <div class="dashboard-card">
                    @if($guides->isEmpty())
                        <div class="tg-empty">No tour guides yet. Create one to get started.</div>
                    @else
                        <div class="tg-table-wrap">
                            <table class="tg-table">
                                <thead>
                                    <tr><th>Guide</th><th>Email / Phone</th><th>Specialty</th><th>Mountain</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($guides as $g)
                                        <tr>
                                            <td>
                                                <div class="who">
                                                    <div class="tg-mini-avatar" style="{{ $g->profile_picture_url ? 'background-image:url('.$g->profile_picture_url.')' : '' }}">{{ $g->initials }}</div>
                                                    <div>
                                                        <div class="tg-who-name">{{ $g->full_name }}</div>
                                                        <div class="tg-who-sub">{{ $g->experience_years }} yrs experience</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="tg-who-name">{{ $g->email }}</div>
                                                <div class="tg-who-sub">{{ $g->phone }}</div>
                                            </td>
                                            <td>{{ $g->specialty }}</td>
                                            <td>{{ $g->mountain?->name ?? '—' }}</td>
                                            <td><span class="tg-status {{ $g->status }}">{{ $g->status_label }}</span></td>
                                            <td style="text-align:right;">
                                                <div class="tg-row-actions">
                                                    @php
                                                        $guidePayload = [
                                                            'id' => $g->id,
                                                            'first_name' => $g->first_name,
                                                            'last_name' => $g->last_name,
                                                            'email' => $g->email,
                                                            'phone' => $g->phone,
                                                            'specialty' => $g->specialty,
                                                            'experience_years' => $g->experience_years,
                                                            'mountain_id' => $g->mountain_id,
                                                            'status' => $g->status,
                                                        ];
                                                    @endphp
                                                    <button class="tg-btn" type="button"
                                                        data-open-modal="adm-modal-guide-edit"
                                                        data-guide='@json($guidePayload)'>
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon> Edit
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.tour-guides.destroy', $g) }}" style="display:inline;" onsubmit="return confirm('Delete this tour guide and the linked user account? This cannot be undone.');">
                                                        @csrf @method('DELETE')
                                                        <button class="tg-btn danger" type="submit"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== MOUNTAINS ============== --}}
            <div class="view-section" id="view-mountains">
                <header class="dashboard-header">
                    <div class="tg-topbar">
                        <div>
                            <h2>Mountains</h2>
                            <p>Add a mountain so it appears in the hiker app. Set jump-off and summit coordinates, weather tracking, gear, and pricing.</p>
                        </div>
                        <button class="tg-btn primary" type="button" data-open-modal="adm-modal-mountain-create">
                            <iconify-icon icon="lucide:plus"></iconify-icon> Add mountain
                        </button>
                    </div>
                </header>

                <div class="dashboard-card">
                    @if($mountains->isEmpty())
                        <div class="tg-empty">No mountains yet. Add your first one to get started.</div>
                    @else
                        <div class="tg-table-wrap">
                            <table class="tg-table">
                                <thead>
                                    <tr>
                                        <th>Mountain</th>
                                        <th>Difficulty</th>
                                        <th>Elevation</th>
                                        <th>Jump-off / Summit</th>
                                        <th>Status</th>
                                        <th style="text-align:right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mountains as $m)
                                        @php
                                            $mountainPayload = [
                                                'id' => $m->id,
                                                'slug' => $m->slug,
                                                'name' => $m->name,
                                                'location' => $m->location,
                                                'difficulty' => $m->difficulty,
                                                'short_description' => $m->short_description,
                                                'full_description' => $m->full_description,
                                                'elevation_label' => $m->elevation_label,
                                                'elevation_meters' => $m->elevation_meters,
                                                'duration_label' => $m->duration_label,
                                                'trail_type_label' => $m->trail_type_label,
                                                'best_time_label' => $m->best_time_label,
                                                'rating' => $m->rating,
                                                'status' => $m->status,
                                                'jumpoff_name' => $m->jumpoff_name,
                                                'jumpoff_address' => $m->jumpoff_address,
                                                'jumpoff_meeting_time' => $m->jumpoff_meeting_time,
                                                'jumpoff_notes' => $m->jumpoff_notes,
                                                'jumpoff_lat' => $m->jumpoff_lat,
                                                'jumpoff_lng' => $m->jumpoff_lng,
                                                'summit_lat' => $m->summit_lat,
                                                'summit_lng' => $m->summit_lng,
                                                'open_meteo_lat' => $m->open_meteo_lat,
                                                'open_meteo_lng' => $m->open_meteo_lng,
                                                'enable_weather' => ! is_null($m->open_meteo_lat) && ! is_null($m->open_meteo_lng),
                                                'emergency_contact' => $m->emergency_contact,
                                                'gear_csv' => is_array($m->gear) ? implode("\n", $m->gear) : '',
                                                'registration_fee_per_person' => $m->registration_fee_per_person,
                                                'environmental_fee_per_person' => $m->environmental_fee_per_person,
                                                'local_fee_per_person' => $m->local_fee_per_person,
                                                'guide_fee_per_person' => $m->guide_fee_per_person,
                                                'guide_fee_per_group' => $m->guide_fee_per_group,
                                                'image_url' => asset($m->image_path),
                                            ];
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="display:flex;align-items:center;gap:10px;">
                                                    <div style="width:42px;height:42px;border-radius:10px;background-image:url('{{ asset($m->image_path) }}');background-size:cover;background-position:center;flex-shrink:0;border:1px solid var(--line);"></div>
                                                    <div>
                                                        <div style="font-weight:800;color:var(--text);">{{ $m->name }}</div>
                                                        <div style="font-size:12px;color:var(--muted);">{{ $m->location }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $m->difficulty }}</td>
                                            <td>{{ $m->elevation_label }}</td>
                                            <td>
                                                <div style="font-size:12px;color:var(--muted);line-height:1.5;">
                                                    <div><strong style="color:var(--text);">Jump-off:</strong> {{ number_format((float) $m->jumpoff_lat, 5) }}, {{ number_format((float) $m->jumpoff_lng, 5) }}</div>
                                                    <div><strong style="color:var(--text);">Summit:</strong> {{ number_format((float) $m->summit_lat, 5) }}, {{ number_format((float) $m->summit_lng, 5) }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $safety = $m->safety_status ?? \App\Models\Mountain::SAFETY_OPEN;
                                                @endphp
                                                <span style="display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:800;text-transform:uppercase;background:{{ $m->status === 'open' ? '#dcfce7' : '#fee2e2' }};color:{{ $m->status === 'open' ? '#166534' : '#991b1b' }};">{{ ucfirst($m->status) }}</span>
                                                <div style="font-size:11px;color:var(--muted);margin-top:4px;">Safety: {{ $m->safety_status_label }}</div>
                                            </td>
                                            <td style="text-align:right;">
                                                <div class="tg-row-actions" style="justify-content:flex-end;flex-wrap:wrap;gap:6px;">
                                                    <button class="tg-btn" type="button"
                                                        data-open-modal="adm-modal-mountain-edit"
                                                        data-mountain='@json($mountainPayload)'>
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon> Edit
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.mountains.destroy', $m) }}" style="display:inline;" onsubmit="return confirm('Delete {{ $m->name }}? This cannot be undone, and only works if no bookings reference it.');">
                                                        @csrf @method('DELETE')
                                                        <button class="tg-btn danger" type="submit"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== HIKERS ============== --}}
            <div class="view-section" id="view-hikers">
                <header class="dashboard-header">
                    <h2>Hikers</h2>
                    <p>All registered hiker accounts. Click a row to view their booking history and audit log.</p>
                </header>

                <div class="dashboard-card">
                    @if($hikers->isEmpty())
                        <div class="tg-empty">No hikers yet.</div>
                    @else
                        <div class="tg-table-wrap">
                            <table class="tg-table">
                                <thead><tr><th>Hiker</th><th>Email</th><th>Phone</th><th style="text-align:right;">Bookings</th><th>Joined</th><th></th></tr></thead>
                                <tbody>
                                    @foreach($hikers as $h)
                                        <tr>
                                            <td>
                                                <div class="who">
                                                    <div class="tg-mini-avatar" style="{{ $h->profile_picture_url ? 'background-image:url('.$h->profile_picture_url.')' : '' }}">
                                                        {{ strtoupper(substr($h->first_name, 0, 1).substr($h->last_name, 0, 1)) }}
                                                    </div>
                                                    <div><div class="tg-who-name">{{ $h->full_name }}</div></div>
                                                </div>
                                            </td>
                                            <td>{{ $h->email }}</td>
                                            <td>{{ $h->phone ?? '—' }}</td>
                                            <td style="text-align:right;"><span class="tg-pill">{{ $h->hike_bookings_count }}</span></td>
                                            <td><div class="tg-who-sub">{{ $h->created_at?->format('M j, Y') }}</div></td>
                                            <td style="text-align:right;">
                                                <div class="tg-row-actions">
                                                    <button class="tg-btn" type="button" data-view-hiker="{{ $h->id }}">
                                                        <iconify-icon icon="lucide:eye"></iconify-icon> View
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== ADMINS ============== --}}
            <div class="view-section" id="view-admins">
                <header class="dashboard-header">
                    <div class="tg-topbar">
                        <div>
                            <h2>Administrators</h2>
                            <p>Create new admin accounts. There must always be at least one admin.</p>
                        </div>
                        <button class="tg-btn primary" type="button" data-open-modal="adm-modal-admin-create">
                            <iconify-icon icon="lucide:plus"></iconify-icon> New admin
                        </button>
                    </div>
                </header>

                <div class="dashboard-card">
                    <div class="tg-table-wrap">
                        <table class="tg-table">
                            <thead><tr><th>Admin</th><th>Email</th><th>Phone</th><th>Joined</th><th style="text-align:right;">Actions</th></tr></thead>
                            <tbody>
                                @foreach($admins as $a)
                                    <tr>
                                        <td>
                                            <div class="who">
                                                <div class="tg-mini-avatar" style="{{ $a->profile_picture_url ? 'background-image:url('.$a->profile_picture_url.')' : '' }}">
                                                    {{ strtoupper(substr($a->first_name, 0, 1).substr($a->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="tg-who-name">{{ $a->full_name }}</div>
                                                    @if($a->id === Auth::id())<div class="tg-who-sub">You</div>@endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $a->email }}</td>
                                        <td>{{ $a->phone ?? '—' }}</td>
                                        <td><div class="tg-who-sub">{{ $a->created_at?->format('M j, Y') }}</div></td>
                                        <td style="text-align:right;">
                                            <div class="tg-row-actions">
                                                @if($a->id !== Auth::id())
                                                    <form method="POST" action="{{ route('admin.admins.destroy', $a) }}" style="display:inline;" onsubmit="return confirm('Remove this admin account?');">
                                                        @csrf @method('DELETE')
                                                        <button class="tg-btn danger" type="submit"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                                                    </form>
                                                @else
                                                    <span class="tg-who-sub">—</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ============== SAFETY COMMAND CENTER ============== --}}
            <div class="view-section" id="view-safety">
                <header class="dashboard-header">
                    <h2>Safety Command Center</h2>
                    <p>Emergency SOS alerts from hikers. Open incidents stay at the top so admins can acknowledge, resolve, or mark false alarms quickly.</p>
                </header>

                @php
                    $openSos = $sosAlerts->where('status', \App\Models\SosAlert::STATUS_OPEN)->count();
                    $ackSos = $sosAlerts->where('status', \App\Models\SosAlert::STATUS_ACKNOWLEDGED)->count();
                    $closedSos = $sosAlerts->whereIn('status', [\App\Models\SosAlert::STATUS_RESOLVED, \App\Models\SosAlert::STATUS_FALSE_ALARM])->count();
                @endphp

                <div class="hc-kpi-ribbon">
                    <div class="kpi">
                        <div class="l">Open SOS</div>
                        <div class="v" style="color:#b91c1c;">{{ $openSos }}</div>
                        <div class="h">Need immediate attention</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Acknowledged</div>
                        <div class="v">{{ $ackSos }}</div>
                        <div class="h">Responder aware</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Closed</div>
                        <div class="v">{{ $closedSos }}</div>
                        <div class="h">Resolved or false alarm</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Live Map</div>
                        <div class="v">{{ $stats['live_hikers'] }}</div>
                        <div class="h">Currently visible hikers</div>
                    </div>
                </div>

                <div class="hc-panel">
                    <div class="hc-panel-head">
                        <h3><iconify-icon icon="lucide:siren"></iconify-icon> SOS incidents</h3>
                        <span class="hc-pill">{{ $sosAlerts->count() }} recent</span>
                    </div>

                    @if($sosAlerts->isEmpty())
                        <div class="hc-empty"><iconify-icon icon="lucide:shield-check"></iconify-icon> No SOS alerts yet.</div>
                    @else
                        <div class="hc-feed" style="max-height:none;">
                            @foreach($sosAlerts as $alert)
                                @php
                                    $tone = match($alert->status) {
                                        \App\Models\SosAlert::STATUS_OPEN => 't-cancel',
                                        \App\Models\SosAlert::STATUS_ACKNOWLEDGED => 't-update',
                                        \App\Models\SosAlert::STATUS_FALSE_ALARM => 't-login',
                                        default => 't-approve',
                                    };
                                    $mountain = $alert->mountain ?? $alert->hikeBooking?->mountain;
                                    $mapUrl = ($alert->lat !== null && $alert->lng !== null)
                                        ? 'https://www.google.com/maps?q='.$alert->lat.','.$alert->lng
                                        : null;
                                @endphp
                                <div class="hc-feed-row adm-sos-row" style="grid-template-columns:auto 1fr auto;align-items:stretch;border-color:{{ $alert->status === \App\Models\SosAlert::STATUS_OPEN ? 'rgba(239,68,68,0.35)' : 'transparent' }};">
                                    <span class="hc-feed-tag {{ $tone }}">{{ str_replace('_', ' ', $alert->status) }}</span>
                                    <div class="hc-feed-body">
                                        <div class="hc-feed-desc">
                                            <strong>{{ $alert->user?->full_name ?? 'Unknown hiker' }}</strong>
                                            triggered SOS
                                            @if($mountain) at <strong>{{ $mountain->name }}</strong>@endif
                                        </div>
                                        <div class="hc-feed-meta" style="line-height:1.7;">
                                            <span><strong>Guide:</strong> {{ $alert->tourGuide?->full_name ?? 'Unassigned' }}</span>
                                            @if($alert->hikeBooking)
                                                &middot; <span><strong>Booking:</strong> #{{ $alert->hikeBooking->id }} on {{ $alert->hikeBooking->hike_on?->format('M j, Y') }}</span>
                                            @endif
                                            @if($alert->lat !== null && $alert->lng !== null)
                                                &middot; <span><strong>GPS:</strong> {{ number_format($alert->lat, 5) }}, {{ number_format($alert->lng, 5) }}</span>
                                                @if($alert->accuracy_m !== null) <span>(±{{ round($alert->accuracy_m) }}m)</span>@endif
                                            @endif
                                            @if($alert->message)
                                                <div style="margin-top:6px;color:var(--text);">{{ $alert->message }}</div>
                                            @endif
                                            @if($alert->acknowledgedBy)
                                                <div>Acknowledged by {{ $alert->acknowledgedBy->full_name }} {{ $alert->acknowledged_at?->diffForHumans() }}</div>
                                            @endif
                                            @if($alert->resolvedBy)
                                                <div>Closed by {{ $alert->resolvedBy->full_name }} {{ $alert->resolved_at?->diffForHumans() }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;min-width:170px;">
                                        <div class="hc-feed-time">{{ $alert->created_at?->diffForHumans() }}</div>
                                        @if($mapUrl)
                                            <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="tg-btn" style="text-decoration:none;">Open map</a>
                                        @endif
                                        @if(! $alert->isClosed())
                                            <div class="tg-row-actions" style="justify-content:flex-end;flex-wrap:wrap;">
                                                @if($alert->status === \App\Models\SosAlert::STATUS_OPEN)
                                                    <form method="POST" action="{{ route('admin.sos-alerts.update', $alert) }}">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ \App\Models\SosAlert::STATUS_ACKNOWLEDGED }}">
                                                        <button type="submit" class="tg-btn">Acknowledge</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.sos-alerts.update', $alert) }}">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ \App\Models\SosAlert::STATUS_RESOLVED }}">
                                                    <button type="submit" class="tg-btn">Resolve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.sos-alerts.update', $alert) }}" onsubmit="return confirm('Mark this SOS as a false alarm?');">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ \App\Models\SosAlert::STATUS_FALSE_ALARM }}">
                                                    <button type="submit" class="tg-btn danger">False alarm</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="hc-panel" style="margin-top:18px;">
                    <div class="hc-panel-head">
                        <h3><iconify-icon icon="lucide:triangle-alert"></iconify-icon> Trail safety status</h3>
                        <span class="hc-pill">Shown to hikers</span>
                    </div>
                    <div class="hc-hbar-list">
                        @foreach($mountains as $mountain)
                            @php
                                $safety = $mountain->safety_status ?? \App\Models\Mountain::SAFETY_OPEN;
                                $safetyTone = match($safety) {
                                    \App\Models\Mountain::SAFETY_OPEN => '#047857',
                                    \App\Models\Mountain::SAFETY_CAUTION => '#b45309',
                                    \App\Models\Mountain::SAFETY_BAD_WEATHER => '#0369a1',
                                    default => '#b91c1c',
                                };
                            @endphp
                            <form method="POST" action="{{ route('admin.mountains.safety.update', $mountain) }}" class="adm-sos-row" style="display:grid;grid-template-columns:1.2fr 180px 1.4fr auto;gap:10px;align-items:center;padding:12px;border:1px solid var(--line);border-radius:14px;background:var(--bg);">
                                @csrf @method('PATCH')
                                <div>
                                    <div style="font-weight:800;color:var(--text);">{{ $mountain->name }}</div>
                                    <div style="font-size:12px;color:var(--muted);">{{ $mountain->location }}</div>
                                </div>
                                <select name="safety_status" style="padding:10px 12px;border:1px solid var(--line);border-radius:10px;background:var(--panel);color:{{ $safetyTone }};font-weight:800;">
                                    @foreach(\App\Models\Mountain::SAFETY_STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($safety === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <input name="safety_note" value="{{ $mountain->safety_note }}" placeholder="Optional note shown to hikers..." maxlength="1000" style="padding:10px 12px;border:1px solid var(--line);border-radius:10px;background:var(--panel);color:var(--text);">
                                <button type="submit" class="tg-btn">Save</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ============== SECURITY MONITOR ============== --}}
            <div class="view-section" id="view-security">
                @php
                    $riskTone = match($security['risk_level'] ?? 'low') {
                        'high' => ['bg' => '#fee2e2', 'fg' => '#991b1b', 'label' => 'High risk'],
                        'medium' => ['bg' => '#fef3c7', 'fg' => '#92400e', 'label' => 'Medium risk'],
                        default => ['bg' => '#dcfce7', 'fg' => '#166534', 'label' => 'Low risk'],
                    };
                @endphp
                <header class="dashboard-header">
                    <h2>Security Monitor</h2>
                    <p>Tracks suspicious traffic patterns, potential DDoS spikes, and payloads that look like SQL injection/XSS from recent audit activity.</p>
                </header>

                <div class="hc-kpi-ribbon" style="margin-bottom:18px;">
                    <div class="kpi">
                        <div class="l">Risk score</div>
                        <div class="v">{{ $security['risk_score'] }}<small>/100</small></div>
                        <div class="h"><span style="display:inline-block;padding:2px 8px;border-radius:999px;background:{{ $riskTone['bg'] }};color:{{ $riskTone['fg'] }};font-weight:800;">{{ $riskTone['label'] }}</span></div>
                    </div>
                    <div class="kpi">
                        <div class="l">Events ({{ $security['window_hours'] }}h)</div>
                        <div class="v">{{ number_format($security['events_24h']) }}</div>
                        <div class="h">Audit events captured</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Unique IPs</div>
                        <div class="v">{{ number_format($security['unique_ips_24h']) }}</div>
                        <div class="h">Distinct clients in last 24h</div>
                    </div>
                    <div class="kpi">
                        <div class="l">Burst IPs (10m)</div>
                        <div class="v">{{ number_format($security['burst_ip_count']) }}</div>
                        <div class="h">Potential DDoS-like spikes</div>
                    </div>
                    <div class="kpi">
                        <div class="l">SQLi/XSS hits</div>
                        <div class="v">{{ number_format($security['suspicious_payload_hits']) }}</div>
                        <div class="h">{{ $security['sql_injection_hits'] }} SQLi-pattern matches</div>
                    </div>
                </div>

                <div class="adm-grid-2">
                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Top IP activity (24h)</h3></div>
                        <div class="tg-table-wrap">
                            <table class="tg-table">
                                <thead><tr><th>IP Address</th><th>Requests</th></tr></thead>
                                <tbody>
                                    @forelse($security['top_ips'] as $row)
                                        <tr class="adm-security-row">
                                            <td><code>{{ $row['ip'] }}</code></td>
                                            <td>{{ number_format($row['count']) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="tg-empty">No IP activity data yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Potential DDoS burst IPs (10m)</h3></div>
                        <div class="tg-table-wrap">
                            <table class="tg-table">
                                <thead><tr><th>IP Address</th><th>Events in 10m</th></tr></thead>
                                <tbody>
                                    @forelse($security['burst_ips'] as $row)
                                        <tr class="adm-security-row">
                                            <td><code>{{ $row['ip'] }}</code></td>
                                            <td><strong style="color:#b91c1c;">{{ number_format($row['count_10m']) }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="tg-empty">No burst traffic detected.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card" style="margin-top:18px;">
                    <div class="tg-card-title"><h3>Suspicious payload events</h3></div>
                    <div class="adm-log-list" style="max-height:none;">
                        @forelse($security['suspicious_payloads'] as $event)
                            <div class="adm-log-row adm-security-row">
                                <span class="adm-log-action" style="background:#fee2e2;color:#991b1b;">{{ $event['action'] }}</span>
                                <div>
                                    <div class="adm-log-desc">{{ \Illuminate\Support\Str::limit($event['description'], 180) }}</div>
                                    <div class="adm-log-meta"><code>{{ $event['ip'] }}</code></div>
                                </div>
                                <div class="adm-log-time" title="{{ $event['created_at'] }}">{{ $event['created_at'] ? \Illuminate\Support\Carbon::parse($event['created_at'])->format('M j, H:i') : '—' }}</div>
                            </div>
                        @empty
                            <div class="tg-empty">No suspicious payload signatures detected in the last {{ $security['window_hours'] }} hours.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ============== AUDIT LOG ============== --}}
            <div class="view-section" id="view-audit">
                <header class="dashboard-header">
                    <h2>Audit Logs</h2>
                    <p>Every important action across the system. Useful for hiker history, security review, and incident response.</p>
                </header>

                <div class="dashboard-card" style="margin-bottom:14px;">
                    <div class="tg-form" style="grid-template-columns:1fr 1fr auto;display:grid;align-items:end;gap:12px;">
                        <div>
                            <label>Filter by action</label>
                            <input id="adm-audit-action" placeholder="e.g. booking.created, user.login">
                        </div>
                        <div>
                            <label>Filter by user id</label>
                            <input id="adm-audit-user" placeholder="numeric user id">
                        </div>
                        <div>
                            <button class="tg-btn primary" type="button" id="adm-audit-search-btn"><iconify-icon icon="lucide:search"></iconify-icon> Search</button>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="adm-log-list" id="adm-audit-list" style="max-height:none;">
                        @forelse($auditLogs as $log)
                            <div class="adm-log-row">
                                <span class="adm-log-action">{{ $log->action }}</span>
                                <div>
                                    <div class="adm-log-desc">{{ $log->description }}</div>
                                    <div class="adm-log-meta">
                                        @if($log->actor)by <strong>{{ $log->actor->full_name }}</strong> ({{ $log->actor->email }})@endif
                                        @if($log->user && $log->user_id !== $log->actor_id) &middot; about <strong>{{ $log->user->full_name }}</strong>@endif
                                        @if($log->ip_address) &middot; {{ $log->ip_address }}@endif
                                    </div>
                                </div>
                                <div class="adm-log-time" title="{{ $log->created_at }}">{{ $log->created_at?->format('M j, H:i') }}</div>
                            </div>
                        @empty
                            <div class="tg-empty">No audit logs yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ============== SYSTEM HEALTH ============== --}}
            <div class="view-section" id="view-health">
                <header class="dashboard-header">
                    <h2>System Health</h2>
                    <p>Quick environment, database, and storage diagnostics.</p>
                </header>

                <div class="adm-grid-2">
                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Application</h3></div>
                        <div class="adm-kv">
                            <div class="k">PHP version</div><div class="v">{{ $health['php_version'] }}</div>
                            <div class="k">Laravel</div><div class="v">{{ $health['laravel_version'] }}</div>
                            <div class="k">Environment</div><div class="v">{{ $health['environment'] }}</div>
                            <div class="k">Debug mode</div><div class="v {{ $health['debug'] ? 'bad' : 'ok' }}">{{ $health['debug'] ? 'ON' : 'OFF' }}</div>
                            <div class="k">Timezone</div><div class="v">{{ $health['timezone'] }}</div>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Database</h3></div>
                        <div class="adm-kv">
                            <div class="k">Driver</div><div class="v">{{ $health['database']['driver'] }}</div>
                            <div class="k">Database</div><div class="v">{{ $health['database']['database_name'] }}</div>
                            <div class="k">Connection</div><div class="v {{ $health['database']['connection_ok'] ? 'ok' : 'bad' }}">{{ $health['database']['connection_ok'] ? 'OK' : 'FAILED' }}</div>
                            @if($health['database']['error'])
                                <div class="k">Error</div><div class="v bad">{{ $health['database']['error'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="adm-grid-2" style="margin-top:18px;">
                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Tables</h3></div>
                        <div class="adm-kv">
                            @foreach($health['database']['tables'] as $tname => $count)
                                <div class="k">{{ $tname }}</div>
                                <div class="v">{{ $count === null ? '—' : number_format($count) }}</div>
                            @endforeach
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Storage & Disk</h3></div>
                        <div class="adm-kv">
                            <div class="k">Storage path</div><div class="v" style="word-break:break-all;">{{ $health['storage']['path'] }}</div>
                            <div class="k">Storage writable</div><div class="v {{ $health['storage']['writable'] ? 'ok' : 'bad' }}">{{ $health['storage']['writable'] ? 'YES' : 'NO' }}</div>
                            <div class="k">Log file size</div><div class="v">{{ $health['storage']['log_size_kb'] }} KB</div>
                            @if($health['disk']['total_gb'])
                                <div class="k">Disk free</div><div class="v">{{ $health['disk']['free_gb'] }} GB / {{ $health['disk']['total_gb'] }} GB</div>
                            @endif
                        </div>
                        @if($health['disk']['used_pct'] !== null)
                            <div style="margin-top:14px;">
                                <div class="tg-who-sub" style="margin-bottom:6px;">Disk used: {{ $health['disk']['used_pct'] }}%</div>
                                <div class="adm-meter"><span style="width:{{ min(100, $health['disk']['used_pct']) }}%;"></span></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @include('partials._notification-history')
        </main>
    </div>

    {{-- ============== MODALS ============== --}}

    {{-- Create tour guide --}}
    <div class="adm-modal-back" id="adm-modal-guide-create">
        <div class="adm-modal">
            <div class="adm-modal-head">
                <h3>New Tour Guide</h3>
                <button type="button" class="adm-modal-close" data-close-modal><iconify-icon icon="lucide:x" style="font-size:20px;"></iconify-icon></button>
            </div>
            <form method="POST" action="{{ route('admin.tour-guides.store') }}" id="adm-guide-create-form">
                @csrf
                <div class="adm-modal-body">
                    <div class="tg-form">
                        <div class="tg-form-row">
                            <div><label>First name</label><input name="first_name" value="{{ old('first_name') }}" required></div>
                            <div><label>Last name</label><input name="last_name" value="{{ old('last_name') }}" required></div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                            <div><label>Phone</label><input name="phone" value="{{ old('phone') }}" required placeholder="09XX XXX XXXX"></div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Temporary password</label><input type="text" name="password" required minlength="8" placeholder="Min 8 chars" autocomplete="new-password"></div>
                            <div><label>Status</label>
                                <select name="status" required>
                                    @foreach($statusOptions as $val => $label)
                                        <option value="{{ $val }}" @selected(old('status', 'available') === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Specialty</label><input name="specialty" value="{{ old('specialty') }}" required placeholder="Day Hikes Specialist"></div>
                            <div><label>Years experience</label><input name="experience_years" type="number" min="0" max="60" value="{{ old('experience_years') }}" required></div>
                        </div>
                        <div>
                            <label>Primary mountain</label>
                            <select name="mountain_id">
                                <option value="">All mountains</option>
                                @foreach($mountains as $m)
                                    <option value="{{ $m->id }}" @selected((string) old('mountain_id') === (string) $m->id)>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="adm-modal-foot">
                    <button type="button" class="tg-btn" data-close-modal>Cancel</button>
                    <button type="submit" class="tg-btn primary"><iconify-icon icon="lucide:user-plus"></iconify-icon> Create guide</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit tour guide --}}
    <div class="adm-modal-back" id="adm-modal-guide-edit">
        <div class="adm-modal">
            <div class="adm-modal-head">
                <h3>Edit Tour Guide</h3>
                <button type="button" class="adm-modal-close" data-close-modal><iconify-icon icon="lucide:x" style="font-size:20px;"></iconify-icon></button>
            </div>
            <form method="POST" id="adm-guide-edit-form">
                @csrf @method('PUT')
                <div class="adm-modal-body">
                    <div class="tg-form">
                        <div class="tg-form-row">
                            <div><label>First name</label><input name="first_name" required></div>
                            <div><label>Last name</label><input name="last_name" required></div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Email</label><input type="email" name="email" required></div>
                            <div><label>Phone</label><input name="phone" required></div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Specialty</label><input name="specialty" required></div>
                            <div><label>Years experience</label><input name="experience_years" type="number" min="0" max="60" required></div>
                        </div>
                        <div class="tg-form-row">
                            <div>
                                <label>Status</label>
                                <select name="status" required>
                                    @foreach($statusOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Primary mountain</label>
                                <select name="mountain_id">
                                    <option value="">All mountains</option>
                                    @foreach($mountains as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label>Reset password (optional)</label>
                            <input type="text" name="reset_password" minlength="8" placeholder="Leave blank to keep current password">
                        </div>
                    </div>
                </div>
                <div class="adm-modal-foot">
                    <button type="button" class="tg-btn" data-close-modal>Cancel</button>
                    <button type="submit" class="tg-btn primary"><iconify-icon icon="lucide:save"></iconify-icon> Save changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Create mountain --}}
    <div class="adm-modal-back" id="adm-modal-mountain-create">
        <div class="adm-modal" style="max-width:920px;">
            <div class="adm-modal-head">
                <h3>Add Mountain</h3>
                <button type="button" class="adm-modal-close" data-close-modal><iconify-icon icon="lucide:x" style="font-size:20px;"></iconify-icon></button>
            </div>
            <form method="POST" action="{{ route('admin.mountains.store') }}" enctype="multipart/form-data" id="adm-mountain-create-form">
                @csrf
                @include('partials.admin._mountain-form-fields', ['isEdit' => false])
                <div class="adm-modal-foot">
                    <button type="button" class="tg-btn" data-close-modal>Cancel</button>
                    <button type="submit" class="tg-btn primary"><iconify-icon icon="lucide:plus"></iconify-icon> Create mountain</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit mountain --}}
    <div class="adm-modal-back" id="adm-modal-mountain-edit">
        <div class="adm-modal" style="max-width:920px;">
            <div class="adm-modal-head">
                <h3>Edit Mountain</h3>
                <button type="button" class="adm-modal-close" data-close-modal><iconify-icon icon="lucide:x" style="font-size:20px;"></iconify-icon></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="adm-mountain-edit-form">
                @csrf @method('PUT')
                @include('partials.admin._mountain-form-fields', ['isEdit' => true])
                <div class="adm-modal-foot">
                    <button type="button" class="tg-btn" data-close-modal>Cancel</button>
                    <button type="submit" class="tg-btn primary"><iconify-icon icon="lucide:save"></iconify-icon> Save changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Create admin --}}
    <div class="adm-modal-back" id="adm-modal-admin-create">
        <div class="adm-modal">
            <div class="adm-modal-head">
                <h3>New Administrator</h3>
                <button type="button" class="adm-modal-close" data-close-modal><iconify-icon icon="lucide:x" style="font-size:20px;"></iconify-icon></button>
            </div>
            <form method="POST" action="{{ route('admin.admins.store') }}">
                @csrf
                <div class="adm-modal-body">
                    <div class="tg-form">
                        <div class="tg-form-row">
                            <div><label>First name</label><input name="first_name" required></div>
                            <div><label>Last name</label><input name="last_name" required></div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Email</label><input type="email" name="email" required></div>
                            <div><label>Phone</label><input name="phone" required></div>
                        </div>
                        <div>
                            <label>Temporary password</label>
                            <input type="text" name="password" required minlength="8">
                        </div>
                    </div>
                </div>
                <div class="adm-modal-foot">
                    <button type="button" class="tg-btn" data-close-modal>Cancel</button>
                    <button type="submit" class="tg-btn primary"><iconify-icon icon="lucide:shield-plus"></iconify-icon> Create admin</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View hiker --}}
    <div class="adm-modal-back" id="adm-modal-hiker-view">
        <div class="adm-modal">
            <div class="adm-modal-head">
                <h3 id="adm-hiker-name">Hiker</h3>
                <button type="button" class="adm-modal-close" data-close-modal><iconify-icon icon="lucide:x" style="font-size:20px;"></iconify-icon></button>
            </div>
            <div class="adm-modal-body">
                <div id="adm-hiker-meta" class="adm-kv" style="margin-bottom:18px;"></div>

                <div class="tg-card-title"><h3>Booking history</h3></div>
                <div class="tg-table-wrap" style="margin-bottom:18px;">
                    <table class="tg-table">
                        <thead><tr><th>Mountain</th><th>Guide</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody id="adm-hiker-bookings"></tbody>
                    </table>
                </div>

                <div class="tg-card-title"><h3>Audit log</h3></div>
                <div class="adm-log-list" id="adm-hiker-logs" style="max-height:300px;"></div>
            </div>
            <div class="adm-modal-foot">
                <button type="button" class="tg-btn" data-close-modal>Close</button>
            </div>
        </div>
    </div>

    <div class="tg-toast" id="tgToast" role="status" aria-live="polite" aria-atomic="true"></div>

    <script>
    window.HC_ADMIN_BOOT = {
        googleMapsKey: @json($googleMapsKey),
        liveLocationsUrl: @json(route('admin.live-locations')),
        liveWindowSeconds: 180,
        pollIntervalMs: 10000,
        flashSuccess: @json(session('admin_status')),
        flashErrors: @json($errors->any() ? $errors->all() : []),
        openModal: @json(session('admin_open_modal')),
    };
    </script>
    <script>
    (function() {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const toastEl = document.getElementById('tgToast');
        function toast(msg, isErr) {
            toastEl.textContent = msg;
            toastEl.classList.toggle('error', !!isErr);
            toastEl.setAttribute('aria-live', isErr ? 'assertive' : 'polite');
            toastEl.classList.add('show');
            clearTimeout(toast._t);
            const ms = isErr ? 4200 : 2800;
            toast._t = setTimeout(() => toastEl.classList.remove('show'), ms);
        }

        // ====== Section navigation ======
        const menuLinks = document.querySelectorAll('.menu-item');
        const sections = {
            'home':      document.getElementById('view-dashboard'),
            'live-map':  document.getElementById('view-live-map'),
            'safety':    document.getElementById('view-safety'),
            'security':  document.getElementById('view-security'),
            'analytics': document.getElementById('view-analytics'),
            'guides':    document.getElementById('view-guides'),
            'mountains': document.getElementById('view-mountains'),
            'hikers':    document.getElementById('view-hikers'),
            'admins':    document.getElementById('view-admins'),
            'audit':     document.getElementById('view-audit'),
            'health':    document.getElementById('view-health'),
            'notifications': document.getElementById('view-notifications'),
        };
        function showView(targetId) {
            Object.values(sections).forEach(s => { if (s) s.classList.remove('active'); });
            menuLinks.forEach(l => l.classList.remove('active'));
            const rawId = (targetId || '').replace('#', '') || 'home';
            const sec = sections[rawId] || sections['home'];
            if (sec) sec.classList.add('active');
            menuLinks.forEach(l => {
                const h = l.getAttribute('href');
                if (h === '#' + rawId) l.classList.add('active');
                else if (rawId === 'home' && h === '#') l.classList.add('active');
            });
            document.querySelector('.layout').classList.remove('mobile-open');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            if (history.replaceState) history.replaceState(null, '', '#' + rawId);
            if (rawId === 'live-map' && typeof window.__hcAdminRefreshMaps === 'function') {
                setTimeout(() => { try { window.__hcAdminRefreshMaps(); } catch(_) {} }, 120);
            }
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

        (function initAdminFlash() {
            const boot = window.HC_ADMIN_BOOT || {};
            if (boot.flashSuccess) toast(boot.flashSuccess, false);
            if (boot.flashErrors && boot.flashErrors.length) {
                const msg = boot.flashErrors.join(' · ');
                toast(msg.length > 280 ? msg.slice(0, 277) + '…' : msg, true);
            }
            if (boot.openModal === 'guide-create') {
                showView('#guides');
                document.getElementById('adm-modal-guide-create')?.classList.add('show');
            }
            if (boot.openModal === 'mountain-create') {
                showView('#mountains');
                document.getElementById('adm-modal-mountain-create')?.classList.add('show');
            }
            if (boot.openModal === 'mountain-edit') {
                showView('#mountains');
            }
        })();

        function initGlobalSearch() {
            const input = document.getElementById('adm-search');
            if (!input) return;

            const itemSelector = [
                '.stat-card', '.dashboard-card', '.tg-table tbody tr', '.adm-log-row',
                '.adm-mountain-card', '.adm-map-row', '.adm-sos-row', '.adm-kv > div', '.menu-item', '.group-item', '.adm-security-row'
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

        // ====== Theme ======
        const root = document.documentElement;
        const lightBtn = document.getElementById('mode-light');
        const darkBtn = document.getElementById('mode-dark');
        function applyTheme(mode) {
            if (mode === 'dark') { root.setAttribute('data-theme', 'dark'); lightBtn?.classList.remove('active'); darkBtn?.classList.add('active'); }
            else { root.removeAttribute('data-theme'); darkBtn?.classList.remove('active'); lightBtn?.classList.add('active'); }
            try { localStorage.setItem('hc-theme', mode); } catch(_) {}
        }
        const savedTheme = (() => { try { return localStorage.getItem('hc-theme'); } catch(_) { return null; } })();
        applyTheme(savedTheme === 'dark' ? 'dark' : 'light');
        lightBtn?.addEventListener('click', () => applyTheme('light'));
        darkBtn?.addEventListener('click', () => applyTheme('dark'));

        // ====== Modal helpers ======
        // ---- Mountain form helpers ----
        function setIfPresent(form, name, value) {
            const el = form.querySelector(`[name="${name}"]`);
            if (!el) return;
            if (el.type === 'checkbox') {
                el.checked = !!value;
            } else {
                el.value = (value === null || value === undefined) ? '' : value;
            }
        }
        function syncMountainWeatherToggle(form) {
            const toggle = form.querySelector('[data-role="weather-toggle"]');
            const coords = form.querySelector('[data-role="weather-coords"]');
            if (!toggle || !coords) return;
            coords.style.display = toggle.checked ? '' : 'none';
        }
        function bindMountainImagePreview(form) {
            const input = form.querySelector('[data-role="image-input"]');
            const preview = form.querySelector('[data-role="image-preview"]');
            if (!input || !preview || input.dataset.bound === '1') return;
            input.dataset.bound = '1';
            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    preview.style.backgroundImage = `url('${ev.target.result}')`;
                };
                reader.readAsDataURL(file);
            });
        }
        function bindMountainWeatherToggle(form) {
            const toggle = form.querySelector('[data-role="weather-toggle"]');
            if (!toggle || toggle.dataset.bound === '1') return;
            toggle.dataset.bound = '1';
            toggle.addEventListener('change', () => syncMountainWeatherToggle(form));
        }
        function resetMountainForm(form) {
            form.reset();
            const preview = form.querySelector('[data-role="image-preview"]');
            if (preview) preview.style.backgroundImage = '';
            bindMountainImagePreview(form);
            bindMountainWeatherToggle(form);
            syncMountainWeatherToggle(form);
        }
        function populateMountainForm(form, data) {
            form.action = `{{ url('admin/mountains') }}/${data.id}`;
            const fields = [
                'name', 'location', 'difficulty', 'short_description', 'full_description',
                'elevation_label', 'elevation_meters', 'duration_label', 'trail_type_label',
                'best_time_label', 'rating', 'status',
                'jumpoff_name', 'jumpoff_address', 'jumpoff_meeting_time', 'jumpoff_notes',
                'jumpoff_lat', 'jumpoff_lng', 'summit_lat', 'summit_lng',
                'open_meteo_lat', 'open_meteo_lng', 'emergency_contact', 'gear_csv',
                'registration_fee_per_person', 'environmental_fee_per_person', 'local_fee_per_person',
                'guide_fee_per_person', 'guide_fee_per_group',
            ];
            fields.forEach((f) => setIfPresent(form, f, data[f]));
            const weatherToggle = form.querySelector('[data-role="weather-toggle"]');
            if (weatherToggle) weatherToggle.checked = !!data.enable_weather;
            const preview = form.querySelector('[data-role="image-preview"]');
            if (preview && data.image_url) preview.style.backgroundImage = `url('${data.image_url}')`;
            const imageInput = form.querySelector('[data-role="image-input"]');
            if (imageInput) imageInput.value = '';
            bindMountainImagePreview(form);
            bindMountainWeatherToggle(form);
            syncMountainWeatherToggle(form);
        }
        document.addEventListener('click', (e) => {
            const opener = e.target.closest('[data-open-modal]');
            if (opener) {
                const id = opener.getAttribute('data-open-modal');
                const back = document.getElementById(id);
                if (!back) return;
                back.classList.add('show');
                if (id === 'adm-modal-guide-edit') {
                    const data = JSON.parse(opener.getAttribute('data-guide') || '{}');
                    const form = document.getElementById('adm-guide-edit-form');
                    form.action = `{{ url('admin/tour-guides') }}/${data.id}`;
                    form.querySelector('[name="first_name"]').value = data.first_name || '';
                    form.querySelector('[name="last_name"]').value = data.last_name || '';
                    form.querySelector('[name="email"]').value = data.email || '';
                    form.querySelector('[name="phone"]').value = data.phone || '';
                    form.querySelector('[name="specialty"]').value = data.specialty || '';
                    form.querySelector('[name="experience_years"]').value = data.experience_years || 0;
                    form.querySelector('[name="status"]').value = data.status || 'available';
                    form.querySelector('[name="mountain_id"]').value = data.mountain_id || '';
                    form.querySelector('[name="reset_password"]').value = '';
                }
                if (id === 'adm-modal-mountain-create') {
                    const form = document.getElementById('adm-mountain-create-form');
                    if (form) resetMountainForm(form);
                }
                if (id === 'adm-modal-mountain-edit') {
                    const data = JSON.parse(opener.getAttribute('data-mountain') || '{}');
                    const form = document.getElementById('adm-mountain-edit-form');
                    if (form) populateMountainForm(form, data);
                }
            }
            const closer = e.target.closest('[data-close-modal]');
            if (closer) {
                closer.closest('.adm-modal-back')?.classList.remove('show');
            }
            if (e.target.classList.contains('adm-modal-back')) {
                e.target.classList.remove('show');
            }
        });

        // ====== Hiker view modal ======
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('[data-view-hiker]');
            if (!btn) return;
            const id = btn.getAttribute('data-view-hiker');
            try {
                const res = await fetch(`{{ url('admin/hikers') }}/${id}`, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (!data.success) { toast('Could not load hiker', true); return; }
                const h = data.hiker;
                document.getElementById('adm-hiker-name').textContent = h.full_name;
                const meta = document.getElementById('adm-hiker-meta');
                meta.innerHTML = `
                    <div class="k">Email</div><div class="v">${h.email}</div>
                    <div class="k">Phone</div><div class="v">${h.phone || '—'}</div>
                    <div class="k">Joined</div><div class="v">${h.created_at ? new Date(h.created_at).toLocaleDateString() : '—'}</div>
                    <div class="k">Bookings</div><div class="v">${h.bookings.length}</div>
                    <div class="k">Location pings</div><div class="v">${data.locations.length}</div>
                `;
                const tbody = document.getElementById('adm-hiker-bookings');
                tbody.innerHTML = h.bookings.length ? h.bookings.map(b => `
                    <tr>
                        <td>${b.mountain || '—'}</td>
                        <td>${b.guide || '—'}</td>
                        <td>${b.date || '—'}</td>
                        <td><span class="tg-status ${b.status}">${b.status}</span></td>
                    </tr>
                `).join('') : '<tr><td colspan="4"><div class="tg-empty" style="margin:0;">No bookings yet.</div></td></tr>';

                const logs = document.getElementById('adm-hiker-logs');
                logs.innerHTML = data.logs.length ? data.logs.map(l => `
                    <div class="adm-log-row">
                        <span class="adm-log-action">${l.action}</span>
                        <div>
                            <div class="adm-log-desc">${l.description}</div>
                            <div class="adm-log-meta">${l.actor ? 'by <strong>'+l.actor+'</strong>' : ''}</div>
                        </div>
                        <div class="adm-log-time">${new Date(l.created_at).toLocaleString()}</div>
                    </div>
                `).join('') : '<div class="tg-empty">No audit entries.</div>';
                document.getElementById('adm-modal-hiker-view').classList.add('show');
            } catch (err) { toast('Network error', true); }
        });

        // ====== Audit search ======
        const auditList = document.getElementById('adm-audit-list');
        const auditAction = document.getElementById('adm-audit-action');
        const auditUser = document.getElementById('adm-audit-user');
        document.getElementById('adm-audit-search-btn')?.addEventListener('click', async () => {
            const params = new URLSearchParams();
            if (auditAction.value) params.set('action', auditAction.value);
            if (auditUser.value) params.set('user_id', auditUser.value);
            const res = await fetch(`{{ route('admin.audit-logs') }}?${params.toString()}`, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!data.success) return;
            auditList.innerHTML = data.logs.length ? data.logs.map(l => `
                <div class="adm-log-row">
                    <span class="adm-log-action">${l.action}</span>
                    <div>
                        <div class="adm-log-desc">${l.description}</div>
                        <div class="adm-log-meta">
                            ${l.actor ? 'by <strong>'+l.actor+'</strong> ('+l.actor_email+')' : ''}
                            ${l.user && l.user !== l.actor ? ' &middot; about <strong>'+l.user+'</strong>' : ''}
                            ${l.ip_address ? ' &middot; '+l.ip_address : ''}
                        </div>
                    </div>
                    <div class="adm-log-time">${new Date(l.created_at).toLocaleString()}</div>
                </div>
            `).join('') : '<div class="tg-empty">No matches.</div>';
        });

        // ====== Live maps (Google Maps, one per mountain) ======
        const HC_ADMIN_BOOT = window.HC_ADMIN_BOOT || {};
        const mountainsContainer = document.getElementById('adm-mountains');
        let initialMountains = [];
        try {
            initialMountains = mountainsContainer
                ? JSON.parse(mountainsContainer.getAttribute('data-mountains') || '[]')
                : [];
        } catch (_) { initialMountains = []; }

        // mapState: { [slug]: { map, trailLine, trailOutline, jumpoff, summit, markers: {uid: marker}, mountain, infoWindow } }
        const mapState = {};
        let googleMapsReadyPromise = null;
        let googleMapsLoadFailed = false;

        function loadGoogleMaps() {
            if (window.google && window.google.maps && window.google.maps.Polyline) {
                return Promise.resolve();
            }
            if (googleMapsLoadFailed) {
                return Promise.reject(new Error('google-maps-load-failed'));
            }
            if (googleMapsReadyPromise) return googleMapsReadyPromise;

            const key = HC_ADMIN_BOOT.googleMapsKey;
            if (!key) {
                googleMapsLoadFailed = true;
                return Promise.reject(new Error('no-google-maps-key'));
            }

            googleMapsReadyPromise = new Promise((resolve, reject) => {
                const cbName = '__hcAdminGmReady_' + Math.random().toString(36).slice(2);
                window[cbName] = () => {
                    delete window[cbName];
                    resolve();
                };
                const s = document.createElement('script');
                s.async = true;
                s.defer = true;
                s.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(key)
                    + '&v=weekly&libraries=geometry&callback=' + cbName;
                s.onerror = () => {
                    googleMapsLoadFailed = true;
                    googleMapsReadyPromise = null;
                    reject(new Error('google-maps-script-error'));
                };
                document.head.appendChild(s);
            });
            return googleMapsReadyPromise;
        }

        function sourceColor(source) {
            if (source === 'live') return '#10b981';
            if (source === 'stale') return '#9ca3af';
            return '#f59e0b';
        }

        function makeFallbackAvatarDataUri(initials, ringColor) {
            const safeInitials = String(initials || 'HC').slice(0, 2).toUpperCase();
            const svg = `
                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 72 72">
                    <circle cx="36" cy="36" r="34" fill="${ringColor}" />
                    <circle cx="36" cy="36" r="29" fill="#0f172a" />
                    <text x="36" y="42" text-anchor="middle" fill="#ffffff" font-size="22" font-family="Arial, sans-serif" font-weight="700">${safeInitials}</text>
                </svg>
            `;
            return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
        }

        function profileIconForHiker(h) {
            const ring = sourceColor(h.source);
            const url = (h.avatar && String(h.avatar).trim() !== '')
                ? String(h.avatar)
                : makeFallbackAvatarDataUri(h.initials, ring);
            return {
                url,
                scaledSize: new google.maps.Size(44, 44),
                size: new google.maps.Size(44, 44),
                anchor: new google.maps.Point(22, 22),
            };
        }

        function spreadOverlappingPositions(hikers) {
            const groups = new Map();
            const toKey = (lat, lng) => `${lat.toFixed(6)},${lng.toFixed(6)}`;

            hikers.forEach((h) => {
                const lat = safeNum(h.lat);
                const lng = safeNum(h.lng);
                if (lat == null || lng == null) return;
                const key = toKey(lat, lng);
                if (!groups.has(key)) groups.set(key, []);
                groups.get(key).push(h);
            });

            groups.forEach((group) => {
                if (group.length <= 1) return;
                const baseLat = Number(group[0].lat);
                const baseLng = Number(group[0].lng);
                const radius = 0.00018; // ~20m visual spread

                group.forEach((hiker, i) => {
                    const angle = (Math.PI * 2 * i) / group.length;
                    hiker.__displayLat = baseLat + Math.sin(angle) * radius;
                    hiker.__displayLng = baseLng + Math.cos(angle) * radius;
                });
            });
        }

        function dotIcon(color, scale) {
            return {
                path: google.maps.SymbolPath.CIRCLE,
                scale: scale || 9,
                fillColor: color,
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 3,
            };
        }

        function safeNum(v) {
            const n = Number(v);
            return Number.isFinite(n) ? n : null;
        }

        function buildHikerInfoHtml(h, mountainName) {
            const tag = h.source === 'live'
                ? '<span class="adm-src-tag adm-src-live">LIVE</span>'
                : h.source === 'stale'
                    ? '<span class="adm-src-tag adm-src-stale">Signal lost</span>'
                    : '<span class="adm-src-tag adm-src-sim">Simulated</span>';

            let meta = '';
            if (h.source === 'live') {
                const t = h.recorded_at ? new Date(h.recorded_at).toLocaleTimeString() : '';
                const acc = h.accuracy_m != null ? ' &middot; ±' + Math.round(Number(h.accuracy_m)) + ' m' : '';
                meta = 'Last ping ' + (t || 'just now') + acc;
            } else if (h.source === 'stale') {
                const minutes = h.last_seen_minutes != null ? h.last_seen_minutes : null;
                const t = h.recorded_at ? new Date(h.recorded_at).toLocaleTimeString() : '';
                meta = 'Last seen ' + (minutes != null ? minutes + ' min ago' : '—') + (t ? ' (' + t + ')' : '');
            } else {
                meta = '~' + (h.progress_pct != null ? h.progress_pct : 0) + '% along trail (no GPS)';
            }

            return '<div style="min-width:200px;font-family:Manrope,system-ui,sans-serif;">'
                + '<div style="font-weight:700;font-size:14px;color:#0f172a;">' + (h.name || 'Hiker') + '</div>'
                + (mountainName ? '<div style="font-size:11px;color:#64748b;margin-top:2px;">' + mountainName + '</div>' : '')
                + '<div style="margin-top:6px;">' + tag + '</div>'
                + '<div style="margin-top:6px;font-size:12px;color:#334155;">' + meta + '</div>'
                + (h.note ? '<div style="margin-top:6px;font-size:11px;color:#94a3b8;font-style:italic;">' + h.note + '</div>' : '')
                + '</div>';
        }

        function initMountainMap(m) {
            if (!window.google || !window.google.maps) return;
            const el = document.getElementById('adm-map-' + m.slug);
            if (!el || mapState[m.slug]) return;

            const trail = Array.isArray(m.trail)
                ? m.trail
                    .map(p => ({ lat: safeNum(p.lat), lng: safeNum(p.lng) }))
                    .filter(p => p.lat != null && p.lng != null)
                : [];

            const jumpoff = { lat: safeNum(m.jumpoff && m.jumpoff.lat), lng: safeNum(m.jumpoff && m.jumpoff.lng) };
            const summit = { lat: safeNum(m.summit && m.summit.lat), lng: safeNum(m.summit && m.summit.lng) };

            const map = new google.maps.Map(el, {
                center: jumpoff.lat != null ? jumpoff : { lat: 14.5995, lng: 120.9842 },
                zoom: 14,
                mapTypeId: trail.length > 1 ? 'terrain' : 'hybrid',
                streetViewControl: false,
                mapTypeControl: true,
                fullscreenControl: true,
                zoomControl: true,
                gestureHandling: 'greedy',
                scaleControl: true,
            });

            // Trail "fence" — same yellow outline + blue inner styling we use
            // on the mountain detail page so admins see the exact route line.
            let trailOutline = null;
            let trailLine = null;
            if (trail.length > 1) {
                trailOutline = new google.maps.Polyline({
                    path: trail,
                    geodesic: true,
                    strokeColor: '#facc15',
                    strokeOpacity: 1,
                    strokeWeight: 8,
                    zIndex: 1,
                    map,
                });
                trailLine = new google.maps.Polyline({
                    path: trail,
                    geodesic: true,
                    strokeColor: '#0ea5e9',
                    strokeOpacity: 0.96,
                    strokeWeight: 4,
                    zIndex: 2,
                    map,
                });
            }

            const jumpoffMarker = (jumpoff.lat != null) ? new google.maps.Marker({
                position: jumpoff,
                map,
                title: (m.jumpoff && m.jumpoff.label) ? m.jumpoff.label + ' (Jump-off)' : 'Jump-off',
                zIndex: 3,
                icon: dotIcon('#2563eb', 9),
            }) : null;

            const summitMarker = (summit.lat != null) ? new google.maps.Marker({
                position: summit,
                map,
                title: (m.summit && m.summit.label) ? m.summit.label : (m.name + ' Summit'),
                zIndex: 4,
                icon: dotIcon('#dc2626', 9),
            }) : null;

            const bounds = new google.maps.LatLngBounds();
            trail.forEach(p => bounds.extend(p));
            if (jumpoff.lat != null) bounds.extend(jumpoff);
            if (summit.lat != null) bounds.extend(summit);
            if (!bounds.isEmpty()) {
                map.fitBounds(bounds, { top: 30, bottom: 30, left: 30, right: 30 });
            }

            const infoWindow = new google.maps.InfoWindow();

            mapState[m.slug] = {
                map,
                trailOutline,
                trailLine,
                jumpoff: jumpoffMarker,
                summit: summitMarker,
                markers: {},
                infoWindow,
                mountain: m,
            };

            renderHikersFor(m);
        }

        function renderHikersFor(m) {
            const state = mapState[m.slug];
            if (!state) return;
            state.mountain = m;
            spreadOverlappingPositions(m.hikers || []);

            const seen = new Set();
            (m.hikers || []).forEach(h => {
                const lat = safeNum(h.__displayLat ?? h.lat);
                const lng = safeNum(h.__displayLng ?? h.lng);
                if (lat == null || lng == null) return;
                seen.add(h.user_id);

                const icon = profileIconForHiker(h);
                const title = (h.name || 'Hiker') + ' · ' + (h.source || '');
                let marker = state.markers[h.user_id];
                if (marker) {
                    marker.setPosition({ lat, lng });
                    marker.setIcon(icon);
                    marker.setOpacity(h.source === 'stale' ? 0.92 : 1);
                    marker.setTitle(title);
                    marker.setZIndex(h.source === 'live' ? 50 : (h.source === 'stale' ? 30 : 10));
                    marker._hikerData = h;
                } else {
                    marker = new google.maps.Marker({
                        position: { lat, lng },
                        map: state.map,
                        title,
                        icon,
                        opacity: h.source === 'stale' ? 0.92 : 1,
                        zIndex: h.source === 'live' ? 50 : (h.source === 'stale' ? 30 : 10),
                    });
                    marker._hikerData = h;
                    marker.addListener('click', () => {
                        const data = marker._hikerData || h;
                        state.infoWindow.setContent(buildHikerInfoHtml(data, m.name));
                        state.infoWindow.open({ anchor: marker, map: state.map });
                    });
                    state.markers[h.user_id] = marker;
                }
            });

            Object.keys(state.markers).forEach(uid => {
                if (!seen.has(parseInt(uid, 10))) {
                    state.markers[uid].setMap(null);
                    delete state.markers[uid];
                }
            });
        }

        function updateMountainCard(m) {
            const card = document.querySelector('.adm-mountain-card[data-mountain-id="' + m.id + '"]');
            if (!card) return;
            const setText = (sel, txt) => {
                const el = card.querySelector(sel);
                if (el) el.textContent = txt;
            };
            setText('[data-role="active-count"]', (m.active_count || 0) + ' active');
            setText('[data-role="live-count"]', (m.live_count || 0) + ' live');
            setText('[data-role="stale-count"]', (m.stale_count || 0) + ' lost');
            setText('[data-role="sim-count"]', (m.simulated_count || 0) + ' sim');
            setText('[data-role="trail-source"] span', m.trail_label || '');

            const list = card.querySelector('[data-role="hiker-list"]');
            if (!list) return;
            if (!m.hikers || m.hikers.length === 0) {
                list.innerHTML = '<div class="tg-empty">No hikers on ' + m.name + ' right now.</div>';
                return;
            }
            list.innerHTML = m.hikers.map(h => {
                let meta = '';
                if (h.source === 'live') {
                    meta = '<span class="adm-src-tag adm-src-live">LIVE</span> ' + (h.recorded_at ? new Date(h.recorded_at).toLocaleTimeString() : 'just now');
                } else if (h.source === 'stale') {
                    const mins = h.last_seen_minutes != null ? h.last_seen_minutes : '—';
                    meta = '<span class="adm-src-tag adm-src-stale">Lost</span> last seen ' + mins + 'm ago';
                } else {
                    meta = '<span class="adm-src-tag adm-src-sim">Sim</span> ~' + (h.progress_pct != null ? h.progress_pct : 0) + '% along trail';
                }
                return '<div class="adm-map-row" data-uid="' + h.user_id + '" data-lat="' + h.lat + '" data-lng="' + h.lng + '" data-slug="' + m.slug + '">'
                    + '<div class="tg-mini-avatar" style="' + (h.avatar ? 'background-image:url(' + h.avatar + ');background-size:cover;background-position:center;' : '') + '">' + (h.avatar ? '' : (h.initials || '??')) + '</div>'
                    + '<div style="flex:1;min-width:0;">'
                    +     '<div class="tg-who-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + (h.name || 'Hiker') + '</div>'
                    +     '<div class="tg-who-sub">' + meta + '</div>'
                    + '</div>'
                    + '</div>';
            }).join('');
        }

        function bootstrapAllMountains() {
            initialMountains.forEach(m => {
                initMountainMap(m);
                updateMountainCard(m);
            });
        }

        async function refreshLive() {
            try {
                const res = await fetch(HC_ADMIN_BOOT.liveLocationsUrl, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                const data = await res.json();
                if (!data || !data.success || !Array.isArray(data.mountains)) return;
                data.mountains.forEach(m => {
                    if (!mapState[m.slug]) {
                        initMountainMap(m);
                    } else {
                        renderHikersFor(m);
                    }
                    updateMountainCard(m);
                });
            } catch (_) { /* network errors are ignored — next tick will retry */ }
        }

        loadGoogleMaps()
            .then(() => {
                bootstrapAllMountains();
                setInterval(refreshLive, HC_ADMIN_BOOT.pollIntervalMs || 10000);
            })
            .catch((err) => {
                document.querySelectorAll('.adm-mountain-map').forEach(el => {
                    el.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;padding:24px;text-align:center;color:#9a3412;background:#fff7ed;border-radius:14px;">'
                        + '<div>'
                        +     '<iconify-icon icon="lucide:map-pin-off" style="font-size:36px;"></iconify-icon>'
                        +     '<div style="font-weight:700;margin-top:8px;">Map unavailable</div>'
                        +     '<div style="font-size:12px;color:#92400e;margin-top:4px;">'
                        +         (err && err.message === 'no-google-maps-key'
                                    ? 'Add GOOGLE_MAPS_API_KEY to .env, then run php artisan config:clear.'
                                    : 'Could not load Google Maps. Check the API key, billing, and HTTP referrer rules.')
                        +     '</div>'
                        + '</div>'
                        + '</div>';
                });
                // Even without maps, keep refreshing the side list so admins
                // still see the live/stale/sim breakdown.
                setInterval(async () => {
                    try {
                        const res = await fetch(HC_ADMIN_BOOT.liveLocationsUrl, {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        const data = await res.json();
                        if (data && data.success && Array.isArray(data.mountains)) {
                            data.mountains.forEach(updateMountainCard);
                        }
                    } catch (_) {}
                }, HC_ADMIN_BOOT.pollIntervalMs || 10000);
            });

        document.addEventListener('click', (e) => {
            const row = e.target.closest('.adm-map-row');
            if (!row) return;
            const slug = row.getAttribute('data-slug') || row.closest('[data-slug]')?.getAttribute('data-slug');
            const state = slug ? mapState[slug] : null;
            if (!state || !state.map) return;
            const lat = parseFloat(row.getAttribute('data-lat'));
            const lng = parseFloat(row.getAttribute('data-lng'));
            const uid = row.getAttribute('data-uid');
            if (Number.isFinite(lat) && Number.isFinite(lng)) {
                state.map.panTo({ lat, lng });
                if (state.map.getZoom() < 15) state.map.setZoom(15);
            }
            const marker = state.markers[uid];
            if (marker) {
                state.infoWindow.setContent(buildHikerInfoHtml(marker._hikerData || {}, state.mountain && state.mountain.name));
                state.infoWindow.open({ anchor: marker, map: state.map });
            }
        });

        // Re-trigger Google Maps sizing when the live-map section becomes visible.
        function refreshAllMapSizes() {
            if (!window.google || !window.google.maps) return;
            Object.values(mapState).forEach(s => {
                if (!s.map) return;
                google.maps.event.trigger(s.map, 'resize');
                if (s.mountain) {
                    const bounds = new google.maps.LatLngBounds();
                    (s.mountain.trail || []).forEach(p => {
                        const lat = safeNum(p.lat), lng = safeNum(p.lng);
                        if (lat != null && lng != null) bounds.extend({ lat, lng });
                    });
                    const j = s.mountain.jumpoff, sm = s.mountain.summit;
                    if (j && safeNum(j.lat) != null) bounds.extend({ lat: Number(j.lat), lng: Number(j.lng) });
                    if (sm && safeNum(sm.lat) != null) bounds.extend({ lat: Number(sm.lat), lng: Number(sm.lng) });
                    if (!bounds.isEmpty()) s.map.fitBounds(bounds, { top: 30, bottom: 30, left: 30, right: 30 });
                }
            });
        }
        window.__hcAdminRefreshMaps = refreshAllMapSizes;
        window.addEventListener('hashchange', () => {
            if (location.hash === '#live-map') {
                setTimeout(refreshAllMapSizes, 60);
            }
        });

        // ====== Line-chart hover tooltips ======
        function nearestPointIndex(points, x) {
            let best = 0, bestDx = Infinity;
            for (let i = 0; i < points.length; i++) {
                const dx = Math.abs(points[i].x - x);
                if (dx < bestDx) { bestDx = dx; best = i; }
            }
            return best;
        }
        function fmtDate(iso) {
            try {
                const d = new Date(iso + 'T00:00:00');
                return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
            } catch (_) { return iso; }
        }
        function initLineCharts() {
            document.querySelectorAll('.hc-line-wrap').forEach((wrap) => {
                if (wrap.__hcInit) return;
                wrap.__hcInit = true;
                const svg     = wrap.querySelector('svg.hc-line-svg');
                const hover   = wrap.querySelector('[data-hover]');
                const cursor  = wrap.querySelector('[data-cursor]');
                const tipEl   = wrap.querySelector('[data-tip-el]');
                if (!svg || !hover || !tipEl) return;

                let pointsA = [], pointsB = null;
                try { pointsA = JSON.parse(hover.getAttribute('data-points') || '[]'); } catch(_) {}
                try { pointsB = JSON.parse(hover.getAttribute('data-points2') || 'null'); } catch(_) {}
                const strokeA = hover.getAttribute('data-stroke')   || '#065f46';
                const strokeB = hover.getAttribute('data-stroke2')  || null;
                const nounA   = hover.getAttribute('data-noun-a') || hover.getAttribute('data-noun') || 'item';
                const nounB   = hover.getAttribute('data-noun-b') || null;
                if (!pointsA.length) return;

                let activeDots = [];
                function setActive(idx) {
                    activeDots.forEach(d => d.remove());
                    activeDots = [];
                    if (idx == null) return;
                    const series = [{ pts: pointsA, stroke: strokeA }];
                    if (pointsB && pointsB.length) series.push({ pts: pointsB, stroke: strokeB });
                    series.forEach(({ pts, stroke }) => {
                        if (!pts[idx]) return;
                        const c = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        c.setAttribute('cx', pts[idx].x);
                        c.setAttribute('cy', pts[idx].y);
                        c.setAttribute('r', '5');
                        c.setAttribute('fill', '#fff');
                        c.setAttribute('stroke', stroke);
                        c.setAttribute('stroke-width', '2.4');
                        c.style.filter = 'drop-shadow(0 4px 6px rgba(6,95,70,0.25))';
                        svg.appendChild(c);
                        activeDots.push(c);
                    });
                }

                hover.addEventListener('pointermove', (e) => {
                    const rect = svg.getBoundingClientRect();
                    const vb   = svg.viewBox.baseVal;
                    const xVB  = ((e.clientX - rect.left) / rect.width) * vb.width;
                    const idx  = nearestPointIndex(pointsA, xVB);
                    const pA   = pointsA[idx];
                    if (!pA) return;
                    if (cursor) {
                        cursor.setAttribute('x1', pA.x);
                        cursor.setAttribute('x2', pA.x);
                        cursor.style.opacity = '1';
                    }
                    setActive(idx);

                    let html = '<span class="lbl">' + fmtDate(pA.date) + '</span>';
                    if (pointsB && pointsB[idx]) {
                        html += '<br><span style="color:#86efac;">●</span> ' + pA.v + ' ' + (pA.v === 1 ? nounA : nounA + 's');
                        html += '<br><span style="color:#fdba74;">●</span> ' + pointsB[idx].v + ' ' + (pointsB[idx].v === 1 ? nounB : nounB + 's');
                    } else {
                        html += '<strong>' + pA.v + '</strong> ' + (pA.v === 1 ? nounA : nounA + 's');
                    }
                    tipEl.innerHTML = html;
                    const ratioX = pA.x / vb.width;
                    const ratioY = pA.y / vb.height;
                    tipEl.style.left = (ratioX * rect.width) + 'px';
                    tipEl.style.top  = (ratioY * rect.height) + 'px';
                    tipEl.style.opacity = '1';
                });
                hover.addEventListener('pointerleave', () => {
                    if (cursor) cursor.style.opacity = '0';
                    tipEl.style.opacity = '0';
                    setActive(null);
                });
            });
        }
        initLineCharts();
        window.__hcAdminInitLineCharts = initLineCharts;
    })();
    </script>

    @include('partials._notification-bell-script')
</body>
</html>
