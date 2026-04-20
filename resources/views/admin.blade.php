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
    {{-- Leaflet for live map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                    <input type="text" placeholder="Search..." aria-label="Search" id="adm-search">
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
                <a href="#guides" class="menu-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="menu-text">Tour Guides</span>
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

            @if(session('admin_status'))
                <div class="dashboard-card" style="margin-bottom:16px;border:1px solid #d1fae5;background:linear-gradient(180deg,#ecfdf5,var(--panel));">
                    <strong style="color:#047857;">{{ session('admin_status') }}</strong>
                </div>
            @endif
            @if(isset($errors) && $errors->any())
                <div class="dashboard-card" style="margin-bottom:16px;border:1px solid #fee2e2;background:#fef2f2;">
                    @foreach($errors->all() as $err)
                        <div style="color:#991b1b;font-weight:600;">{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            {{-- ============== OVERVIEW ============== --}}
            <div class="view-section active" id="view-dashboard">
                <header class="dashboard-header">
                    <div class="tg-topbar">
                        <div>
                            <h2>Welcome, {{ $user->first_name }}! <iconify-icon icon="lucide:shield" style="color:#10b981;font-size:24px;vertical-align:text-bottom;margin-left:4px;"></iconify-icon></h2>
                            <p>Operational overview of HikeConnect &middot; {{ now()->format('l, M j, Y') }}</p>
                        </div>
                    </div>
                </header>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon green"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></div>
                        <div class="stat-info">
                            <h4>Total Hikers</h4>
                            <div class="stat-value">{{ $stats['total_hikers'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                        <div class="stat-info">
                            <h4>Tour Guides</h4>
                            <div class="stat-value">{{ $stats['total_guides'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 4H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><path d="M16 2v4"></path><path d="M8 2v4"></path><path d="M3 10h18"></path></svg></div>
                        <div class="stat-info">
                            <h4>Total Bookings</h4>
                            <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple"><svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 11a3 3 0 1 0 6 0 3 3 0 0 0-6 0z"></path><path d="M17.657 16.657L13.414 20.9a2 2 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"></path></svg></div>
                        <div class="stat-info">
                            <h4>Live Hikers</h4>
                            <div class="stat-value"><a href="#live-map" style="color:inherit;text-decoration:none;">{{ $stats['live_hikers'] }}</a></div>
                        </div>
                    </div>
                </div>

                <div class="adm-grid-2" style="margin-top:18px;">
                    <div class="dashboard-card adm-chart-card">
                        <div class="tg-card-title">
                            <h3>New sign-ups (last 30 days)</h3>
                            <span class="tg-pill">{{ collect($analytics['signups_30d'])->sum('count') }} total</span>
                        </div>
                        @php
                            $sm = collect($analytics['signups_30d']);
                            $smMax = max(1, (int) $sm->max('count'));
                        @endphp
                        @if($sm->isEmpty())
                            <div class="tg-empty">No sign-ups in the last 30 days.</div>
                        @else
                            <div class="adm-bar-track" aria-label="Sign-ups bar chart">
                                @foreach($sm as $d)
                                    <div class="adm-bar" style="height: {{ max(2, (int)($d['count'] / $smMax * 100)) }}%;" title="{{ $d['date'] }}: {{ $d['count'] }}"></div>
                                @endforeach
                            </div>
                            <div class="adm-bar-axis">
                                @foreach($sm as $i => $d)
                                    @if($i % max(1, (int) floor($sm->count() / 6)) === 0 || $i === $sm->count() - 1)
                                        <span>{{ \Carbon\Carbon::parse($d['date'])->format('M j') }}</span>
                                    @else
                                        <span></span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="dashboard-card adm-chart-card">
                        <div class="tg-card-title">
                            <h3>Bookings (last 30 days)</h3>
                            <span class="tg-pill">{{ collect($analytics['bookings_30d'])->sum('count') }} total</span>
                        </div>
                        @php
                            $bm = collect($analytics['bookings_30d']);
                            $bmMax = max(1, (int) $bm->max('count'));
                        @endphp
                        @if($bm->isEmpty())
                            <div class="tg-empty">No bookings in the last 30 days.</div>
                        @else
                            <div class="adm-bar-track" aria-label="Bookings bar chart">
                                @foreach($bm as $d)
                                    <div class="adm-bar" style="height: {{ max(2, (int)($d['count'] / $bmMax * 100)) }}%;" title="{{ $d['date'] }}: {{ $d['count'] }}"></div>
                                @endforeach
                            </div>
                            <div class="adm-bar-axis">
                                @foreach($bm as $i => $d)
                                    @if($i % max(1, (int) floor($bm->count() / 6)) === 0 || $i === $bm->count() - 1)
                                        <span>{{ \Carbon\Carbon::parse($d['date'])->format('M j') }}</span>
                                    @else
                                        <span></span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="adm-grid-2" style="margin-top:18px;">
                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Top Mountains</h3></div>
                        @if($analytics['top_mountains']->isEmpty())
                            <div class="tg-empty">No bookings yet.</div>
                        @else
                            <div class="tg-table-wrap">
                                <table class="tg-table">
                                    <thead><tr><th>Mountain</th><th>Location</th><th style="text-align:right;">Bookings</th></tr></thead>
                                    <tbody>
                                    @foreach($analytics['top_mountains'] as $m)
                                        <tr>
                                            <td><div class="tg-who-name">{{ $m->name }}</div></td>
                                            <td><div class="tg-who-sub">{{ $m->location }}</div></td>
                                            <td style="text-align:right;"><span class="tg-pill">{{ $m->bookings_count }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="dashboard-card">
                        <div class="tg-card-title"><h3>Top Tour Guides</h3></div>
                        @if($analytics['top_guides']->isEmpty())
                            <div class="tg-empty">No guides yet.</div>
                        @else
                            <div class="tg-table-wrap">
                                <table class="tg-table">
                                    <thead><tr><th>Guide</th><th>Specialty</th><th style="text-align:right;">Bookings</th></tr></thead>
                                    <tbody>
                                    @foreach($analytics['top_guides'] as $g)
                                        <tr>
                                            <td>
                                                <div class="who">
                                                    <div class="tg-mini-avatar" style="{{ $g->profile_picture_url ? 'background-image:url('.$g->profile_picture_url.')' : '' }}">{{ $g->initials }}</div>
                                                    <div><div class="tg-who-name">{{ $g->full_name }}</div></div>
                                                </div>
                                            </td>
                                            <td><div class="tg-who-sub">{{ $g->specialty }}</div></td>
                                            <td style="text-align:right;"><span class="tg-pill">{{ $g->bookings_count }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="dashboard-card" style="margin-top:18px;">
                    <div class="tg-card-title">
                        <h3>Recent Activity</h3>
                        <a href="#audit" class="tg-pill" style="text-decoration:none;">View all</a>
                    </div>
                    @if($recentLogs->isEmpty())
                        <div class="tg-empty">No activity yet.</div>
                    @else
                        <div class="adm-log-list" style="max-height:380px;">
                            @foreach($recentLogs as $log)
                                <div class="adm-log-row">
                                    <span class="adm-log-action">{{ $log->action }}</span>
                                    <div>
                                        <div class="adm-log-desc">{{ $log->description }}</div>
                                        <div class="adm-log-meta">
                                            @if($log->actor)by <strong>{{ $log->actor->full_name }}</strong>@endif
                                            @if($log->user && $log->user_id !== $log->actor_id) &middot; about <strong>{{ $log->user->full_name }}</strong>@endif
                                        </div>
                                    </div>
                                    <div class="adm-log-time" title="{{ $log->created_at }}">{{ $log->created_at?->diffForHumans() }}</div>
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
                    <p>Real-time positions of every hiker active on each mountain today. <strong>GPS</strong> markers show live phone pings; <strong>simulated</strong> markers fall back to the trail dataset when no recent ping is available. Refreshes every 15 seconds.</p>
                </header>

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
                                    <span class="tg-pill adm-pill-gps" data-role="gps-count">{{ $m['gps_count'] }} GPS</span>
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
                                                <div class="tg-mini-avatar" style="{{ $h['avatar'] ? 'background-image:url('.$h['avatar'].')' : '' }}">{{ $h['initials'] }}</div>
                                                <div style="flex:1;min-width:0;">
                                                    <div class="tg-who-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $h['name'] }}</div>
                                                    <div class="tg-who-sub">
                                                        @if($h['source'] === 'gps')
                                                            <span class="adm-src-tag adm-src-gps">GPS</span> {{ \Illuminate\Support\Carbon::parse($h['recorded_at'])->diffForHumans() }}
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
                            <div><label>First name</label><input name="first_name" required></div>
                            <div><label>Last name</label><input name="last_name" required></div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Email</label><input type="email" name="email" required></div>
                            <div><label>Phone</label><input name="phone" required placeholder="09XX XXX XXXX"></div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Temporary password</label><input type="text" name="password" required minlength="8" placeholder="Min 8 chars"></div>
                            <div><label>Status</label>
                                <select name="status" required>
                                    @foreach($statusOptions as $val => $label)
                                        <option value="{{ $val }}" @selected($val === 'available')>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="tg-form-row">
                            <div><label>Specialty</label><input name="specialty" required placeholder="Day Hikes Specialist"></div>
                            <div><label>Years experience</label><input name="experience_years" type="number" min="0" max="60" required></div>
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

        // ====== Section navigation ======
        const menuLinks = document.querySelectorAll('.menu-item');
        const sections = {
            'home':     document.getElementById('view-dashboard'),
            'live-map': document.getElementById('view-live-map'),
            'guides':   document.getElementById('view-guides'),
            'hikers':   document.getElementById('view-hikers'),
            'admins':   document.getElementById('view-admins'),
            'audit':    document.getElementById('view-audit'),
            'health':   document.getElementById('view-health'),
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
            if (rawId === 'live-map') {
                setTimeout(() => {
                    try { Object.values(mapState).forEach(s => s.map && s.map.invalidateSize()); } catch(_) {}
                }, 100);
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

        // ====== Live maps (one per mountain) ======
        const mountainsContainer = document.getElementById('adm-mountains');
        let initialMountains = [];
        try { initialMountains = mountainsContainer ? JSON.parse(mountainsContainer.getAttribute('data-mountains') || '[]') : []; } catch(_){ initialMountains = []; }

        // mapState: { [slug]: { map, trail, jumpoff, summit, markers: {uid: marker} } }
        const mapState = {};

        function makeIcon(source) {
            const cls = source === 'gps' ? 'adm-leaflet-pin adm-pin-gps' : 'adm-leaflet-pin adm-pin-sim';
            return L.divIcon({
                className: '',
                html: `<div class="${cls}"><iconify-icon icon="${source === 'gps' ? 'lucide:user-round' : 'lucide:footprints'}"></iconify-icon></div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -28],
            });
        }

        function endpointMarker(latlng, label, color) {
            return L.marker(latlng, {
                icon: L.divIcon({
                    className: '',
                    html: `<div class="adm-leaflet-endpoint" style="--c:${color}"><span>${label}</span></div>`,
                    iconSize: [80, 26],
                    iconAnchor: [40, 26],
                }),
            });
        }

        function initMountainMap(m) {
            const el = document.getElementById('adm-map-' + m.slug);
            if (!el || mapState[m.slug]) return;

            const map = L.map(el, { zoomControl: true, attributionControl: false });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);
            L.control.attribution({ prefix: false }).addAttribution('&copy; OpenStreetMap').addTo(map);

            const trailLine = (m.trail && m.trail.length > 1)
                ? L.polyline(m.trail.map(p => [p.lat, p.lng]), { color: '#10b981', weight: 4, opacity: 0.85 }).addTo(map)
                : null;

            const jumpoff = endpointMarker([m.jumpoff.lat, m.jumpoff.lng], 'Jump-off', '#0ea5e9').addTo(map);
            jumpoff.bindPopup('<strong>' + (m.jumpoff.label || 'Jump-off') + '</strong>');
            const summit = endpointMarker([m.summit.lat, m.summit.lng], 'Summit', '#ef4444').addTo(map);
            summit.bindPopup('<strong>' + (m.summit.label || 'Summit') + '</strong>');

            const bounds = trailLine ? trailLine.getBounds() : L.latLngBounds([[m.jumpoff.lat, m.jumpoff.lng], [m.summit.lat, m.summit.lng]]);
            bounds.extend([m.jumpoff.lat, m.jumpoff.lng]).extend([m.summit.lat, m.summit.lng]);
            map.fitBounds(bounds, { padding: [30, 30] });

            mapState[m.slug] = { map, trailLine, jumpoff, summit, markers: {} };
            renderHikersFor(m);
        }

        function renderHikersFor(m) {
            const state = mapState[m.slug];
            if (!state) return;
            const seen = new Set();
            (m.hikers || []).forEach(h => {
                seen.add(h.user_id);
                const popup = `
                    <div style="min-width:160px;">
                        <strong>${h.name || 'Hiker'}</strong><br>
                        <span class="adm-src-tag adm-src-${h.source}">${h.source === 'gps' ? 'GPS' : 'Simulated'}</span>
                        ${h.source === 'gps'
                            ? '<br><small>' + (h.recorded_at ? new Date(h.recorded_at).toLocaleTimeString() : '') + '</small>'
                            : '<br><small>~' + h.progress_pct + '% along trail</small>'}
                        ${h.note ? '<br><em style="color:#6b7280;font-size:11px;">' + h.note + '</em>' : ''}
                    </div>`;
                const latlng = [h.lat, h.lng];
                if (state.markers[h.user_id]) {
                    state.markers[h.user_id].setLatLng(latlng).setIcon(makeIcon(h.source)).setPopupContent(popup);
                } else {
                    state.markers[h.user_id] = L.marker(latlng, { icon: makeIcon(h.source) })
                        .addTo(state.map)
                        .bindPopup(popup);
                }
            });
            Object.keys(state.markers).forEach(uid => {
                if (!seen.has(parseInt(uid))) {
                    state.map.removeLayer(state.markers[uid]);
                    delete state.markers[uid];
                }
            });
        }

        function updateMountainCard(m) {
            const card = document.querySelector('.adm-mountain-card[data-mountain-id="' + m.id + '"]');
            if (!card) return;
            card.querySelector('[data-role="active-count"]').textContent = m.active_count + ' active';
            card.querySelector('[data-role="gps-count"]').textContent = m.gps_count + ' GPS';
            card.querySelector('[data-role="sim-count"]').textContent = m.simulated_count + ' sim';
            const srcEl = card.querySelector('[data-role="trail-source"] span');
            if (srcEl) srcEl.textContent = m.trail_label;

            const list = card.querySelector('[data-role="hiker-list"]');
            if (!list) return;
            if (!m.hikers || m.hikers.length === 0) {
                list.innerHTML = '<div class="tg-empty">No hikers on ' + m.name + ' right now.</div>';
                return;
            }
            list.innerHTML = m.hikers.map(h => {
                const meta = h.source === 'gps'
                    ? '<span class="adm-src-tag adm-src-gps">GPS</span> ' + (h.recorded_at ? new Date(h.recorded_at).toLocaleTimeString() : '')
                    : '<span class="adm-src-tag adm-src-sim">Sim</span> ~' + h.progress_pct + '% along trail';
                return `<div class="adm-map-row" data-uid="${h.user_id}" data-lat="${h.lat}" data-lng="${h.lng}" data-slug="${m.slug}">
                    <div class="tg-mini-avatar" style="${h.avatar ? 'background-image:url('+h.avatar+')' : ''}">${h.initials || '??'}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="tg-who-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${h.name || 'Hiker'}</div>
                        <div class="tg-who-sub">${meta}</div>
                    </div>
                </div>`;
            }).join('');
        }

        // initial paint
        initialMountains.forEach(initMountainMap);

        async function refreshLive() {
            try {
                const res = await fetch(`{{ route('admin.live-locations') }}`, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (!data.success || !Array.isArray(data.mountains)) return;
                let totalActive = 0;
                data.mountains.forEach(m => {
                    totalActive += (m.active_count || 0);
                    if (!mapState[m.slug]) initMountainMap(m); else renderHikersFor(m);
                    updateMountainCard(m);
                });
                const badge = document.querySelector('.menu-item .menu-badge');
                // (badge update is best-effort; safe no-op if not present)
            } catch(_) {}
        }
        setInterval(refreshLive, 15000);

        document.addEventListener('click', (e) => {
            const row = e.target.closest('.adm-map-row');
            if (!row) return;
            const slug = row.getAttribute('data-slug') || row.closest('[data-slug]')?.getAttribute('data-slug');
            const state = slug ? mapState[slug] : null;
            if (!state) return;
            const lat = parseFloat(row.getAttribute('data-lat'));
            const lng = parseFloat(row.getAttribute('data-lng'));
            const uid = row.getAttribute('data-uid');
            state.map.setView([lat, lng], 15);
            if (state.markers[uid]) state.markers[uid].openPopup();
        });

        // Re-trigger Leaflet sizing when the live-map section becomes visible
        window.addEventListener('hashchange', () => {
            if (location.hash === '#live-map') {
                setTimeout(() => Object.values(mapState).forEach(s => s.map.invalidateSize()), 50);
            }
        });
    })();
    </script>
</body>
</html>
