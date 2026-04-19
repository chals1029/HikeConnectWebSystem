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
                    <input type="text" placeholder="Search..." aria-label="Search">
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
                </a>

                <div class="group-title">Group</div>
                <div class="group-list">
                    @php $dots = ['green', 'blue', 'orange']; @endphp
                    @forelse($mountains->take(8) as $i => $gm)
                    <div class="group-item"><span class="dot {{ $dots[$i % count($dots)] }}"></span> <span class="group-item-text">{{ $gm->name }}</span></div>
                    @empty
                    <div class="group-item"><span class="dot green"></span> <span class="group-item-text" style="color:var(--muted);">No trails yet</span></div>
                    @endforelse
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
                <header class="dashboard-header">
                    <h2>Welcome back, <span id="dashboard-welcome-first-name">{{ $user->first_name ?? 'Hiker' }}</span>! <iconify-icon icon="lucide:sparkles" style="color: #10b981; font-size: 24px; vertical-align: text-bottom; margin-left: 4px;"></iconify-icon></h2>
                    <p>Ready for your next adventure? Check out your stats and upcoming plans.</p>
                </header>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m8 3 4 8 5-5 5 15H2L8 3z"></path></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Hikes Completed</h4>
                            <div class="stat-value">{{ $stats['hikes_completed'] }}</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Total Hiking Hours</h4>
                            <div class="stat-value">{{ $stats['total_hours'] }}h</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Total Elevation</h4>
                            <div class="stat-value">{{ number_format($stats['total_elevation']) }}m</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        </div>
                        <div class="stat-info">
                            <h4>Badges claimed</h4>
                            <div class="stat-value"><a href="#achievements" id="stat-badges-count-link" style="color:inherit;text-decoration:none;">{{ $stats['badges'] }}</a></div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-main-grid">
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h3>Recent Community Activity</h3>
                            <a href="#community-chat">View All</a>
                        </div>
                        <div class="activity-list">
                            @forelse($communityPosts->take(3) as $post)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                </div>
                                <div class="activity-details">
                                    <h5>{{ $post->author_name }} @if($post->mountain)<span style="color:var(--muted);font-weight:500">· {{ $post->mountain->name }}</span>@endif</h5>
                                    <p>{{ \Illuminate\Support\Str::limit($post->body, 90) }}</p>
                                    <div class="activity-time">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @empty
                            <p style="padding:12px;color:var(--muted);font-size:14px;">No community posts yet. Share your first adventure in Community Chat.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="dashboard-side-grid">
                        <div class="dashboard-card" style="margin-bottom: 20px;">
                            @if($upcoming)
                            <div class="upcoming-hike">
                                <span class="upcoming-hike-date">{{ $upcoming->hike_on->isToday() ? 'Today' : ($upcoming->hike_on->isTomorrow() ? 'Tomorrow' : $upcoming->hike_on->format('M j, Y')) }}</span>
                                <h4>{{ $upcoming->mountain->name }}</h4>
                                <div class="upcoming-hike-meta">
                                    <span>
                                        <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        {{ $upcoming->mountain->jumpoff_meeting_time }}
                                    </span>
                                    <span>
                                        <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        {{ $upcoming->mountain->location }}
                                    </span>
                                </div>
                                <a href="#trail-plan" class="upcoming-btn">View Trail Plan</a>
                            </div>
                            @else
                            <div class="upcoming-hike">
                                <span class="upcoming-hike-date">Next adventure</span>
                                <h4>No upcoming hike</h4>
                                <p style="font-size:13px;color:var(--muted);margin:8px 0 12px;">Book a mountain and guide to see your schedule here.</p>
                                <a href="#book-hike" class="upcoming-btn">Book a Hike</a>
                            </div>
                            @endif
                        </div>
                        
                        <div class="dashboard-card">
                            <div class="dashboard-card-header">
                                <h3>Weather Alert</h3>
                            </div>
                            <div style="background: var(--bg); border: 1px solid var(--line); border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 16px;">
                                <div style="font-size: 32px;"><iconify-icon icon="lucide:sun" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon></div>
                                <div>
                                    <div style="font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 4px;">Clear Skies Ahead</div>
                                    <div style="font-size: 12px; color: var(--muted);">Great conditions expected for your next hike at {{ $upcoming?->mountain->name ?? $trailMountain?->name ?? 'your trail' }}.</div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    @php
        $__hikerBootstrap = [
            'csrf' => csrf_token(),
            'userId' => $user->id,
            'hasGoogleMapsKey' => filled(config('services.google_maps.key')),
            'mapsFallbackCenter' => ['lat' => 13.94, 'lng' => 120.92],
            'weather' => ($weatherLat !== null && $weatherLng !== null)
                ? ['lat' => $weatherLat, 'lng' => $weatherLng]
                : null,
            'jumpoffMarkers' => $jumpoffMarkers,
            'defaultJumpoff' => $defaultJumpoff,
            'routes' => [
                'storeBooking' => url('/hikers/bookings'),
                'storeReview' => url('/hikers/reviews'),
                'storeGuideReview' => url('/hikers/guide-reviews'),
                'storeCommunityPost' => url('/hikers/community-posts'),
                'cancelBookingPrefix' => url('/hikers/bookings'),
                'updateProfilePicture' => url('/hikers/profile/picture'),
                'updateProfile' => url('/hikers/profile'),
                'sendPasswordChangeCode' => url('/hikers/profile/password/send-code'),
                'updatePasswordWithCode' => url('/hikers/profile/password'),
                'achievementClaimBase' => url('/hikers/achievements'),
            ],
        ];
    @endphp
    <!-- Interactive Logic -->
    <script>
        // ============================================================
        // HikeConnect — Complete Dashboard Logic
        // ============================================================

        window.HIKER_BOOTSTRAP = @json($__hikerBootstrap);

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

        document.addEventListener('DOMContentLoaded', () => {
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
                'trail-plan': document.getElementById('view-trail-plan'),
                'community-chat': document.getElementById('view-community-chat'),
                'settings': document.getElementById('view-settings'),
                'safety-alerts': document.getElementById('view-settings')
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
                        const dj = window.HIKER_BOOTSTRAP.defaultJumpoff;
                        const fb = window.HIKER_BOOTSTRAP.mapsFallbackCenter;
                        const c = (dj && dj.lat != null && dj.lng != null) ? dj : fb;
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
                    mapTypeId: visibleTrailPath.length > 1 ? 'terrain' : 'hybrid',
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
                preview.innerHTML = `<div class="ns-preview-filled">
                    <div class="ns-preview-row"><span>Mountain</span><strong>${mData ? mData.name : '—'}</strong></div>
                    <div class="ns-preview-row"><span>Date</span><strong>${date || '—'}</strong></div>
                    <div class="ns-preview-row"><span>Hikers</span><strong>${hikers || '1'}</strong></div>
                    <div class="ns-preview-row"><span>Guide</span><strong>${gData ? gData.name : '—'}</strong></div>
                    <div class="ns-preview-row"><span>Jump-off</span><strong>${mData ? mData.jumpoff.name : '—'}</strong></div>
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
            }).then(r => r.json().then(data => ({ status: r.status, body: data })))
              .then(res => {
                  if (btn) { btn.disabled = false; }
                  if (res.status === 200 && res.body.success) {
                      document.getElementById('booking-success').style.display = 'flex';
                  } else if (res.body.errors) {
                      alert(Object.values(res.body.errors).flat().join('\n'));
                  } else {
                      alert(res.body.message || 'Could not create booking.');
                  }
              }).catch(() => { if (btn) { btn.disabled = false; } alert('Could not create booking.'); });
        };

        window.resetBookingForm = function() {
            document.getElementById('booking-form')?.reset();
            document.getElementById('booking-success').style.display = 'none';
            updateBookingPreview();
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
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    const status = card.querySelector('.ns-booking-status');
                    status.textContent = 'Cancelled';
                    status.className = 'ns-booking-status cancelled';
                    card.dataset.bookingType = 'cancelled';
                    btn.remove();
                } else {
                    alert(d.message || 'Could not cancel.');
                }
            }).catch(() => alert('Could not cancel.'));
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
        };

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
            const fb = window.HIKER_BOOTSTRAP.mapsFallbackCenter || { lat: 13.94, lng: 120.92 };
            const center = (dj && dj.lat != null && dj.lng != null) ? { lat: dj.lat, lng: dj.lng } : fb;
            const map = new google.maps.Map(mapEl, {
                center,
                zoom: 14,
                mapTypeId: 'terrain',
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
                    title: j.title,
                    icon: { url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png' }
                });
            });
            return true;
        }

        window.toggleLiveTracking = function() {
            if (window.hikeTracker.isTracking) {
                stopLiveTracking();
            } else {
                startLiveTracking();
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
            }

            window.hikeTracker.isTracking = true;
            const btn = document.getElementById('track-btn');
            if (btn) btn.setAttribute('aria-pressed', 'true');
            if (label) label.textContent = 'Stop tracking';
            if (clearBtn) clearBtn.style.display = '';

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

                if (altEl) {
                    altEl.textContent = (pos.coords.altitude != null && !isNaN(pos.coords.altitude))
                        ? Math.round(pos.coords.altitude) + ' m' : '—';
                }
                if (accEl) {
                    accEl.textContent = (acc != null && !isNaN(acc)) ? ('±' + Math.round(acc) + ' m') : '—';
                }
                if (lastEl) lastEl.textContent = new Date().toLocaleTimeString();

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

                const tKm = hikeTrailLengthKm(window.hikeTracker.path);
                if (trailEl) {
                    trailEl.textContent = window.hikeTracker.path.length < 2
                        ? '0 m'
                        : (tKm < 0.1 ? (tKm * 1000).toFixed(0) + ' m' : tKm.toFixed(2) + ' km');
                }
            };

            const onErr = (err) => {
                if (statusEl) {
                    statusEl.textContent = err.code === 1 ? 'Location permission denied' : (err.code === 2 ? 'Position unavailable' : 'GPS timeout');
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
            if (window.hikeTracker.polyline) {
                window.hikeTracker.polyline.setPath([]);
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
