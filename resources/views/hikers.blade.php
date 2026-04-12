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
                <a href="#reviews" class="menu-item">
                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <span class="menu-text">Reviews & Feedback</span>
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

            <div class="view-section" id="view-reviews">
                <section>
                    <article class="card">
                        <h3>Mountain Reviews</h3>
                        <div class="reviews">
                            @forelse($mountainReviews as $rev)
                            <div class="review">
                                <div class="review-head">
                                    <strong>{{ $rev->reviewer_name }}</strong>
                                    <span class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                        <iconify-icon icon="lucide:star" style="vertical-align:text-bottom; color:{{ $i <= $rev->rating ? '#f59e0b' : '#cbd5e1' }};"></iconify-icon>
                                        @endfor
                                    </span>
                                </div>
                                <p>{{ $rev->body }}</p>
                                @if($rev->mountain)<p style="font-size:12px;color:var(--muted);margin-top:6px;">{{ $rev->mountain->name }}</p>@endif
                            </div>
                            @empty
                            <p style="color:var(--muted);">No reviews yet. Be the first to leave feedback below.</p>
                            @endforelse
                        </div>
                    </article>

                    {{-- Feedback Form --}}
                    <div class="ns-feedback-form">
                        <h3>📝 Leave Your Feedback</h3>
                        <form id="feedback-form" onsubmit="submitFeedback(event)">
                            
                            <div class="ns-feedback-card" id="feedback-step-1">
                                <div class="ns-step-progress"><span class="step-active"></span><span></span></div>
                                <div class="ns-form-group" style="margin-bottom: 20px;">
                                    <label class="ns-rating-label" style="font-size: 14px; color: #64748b; font-weight: 500; margin-bottom: 8px;">Who and what are you reviewing?</label>
                                    <select class="ns-form-select" id="feedback-mountain" required style="width: 100%; max-width: 400px; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #1e293b;">
                                        <option value="" disabled selected>Choose a mountain to review...</option>
                                        @foreach($mountains as $m)
                                        <option value="{{ $m->slug }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="ns-rating-group" style="margin-bottom:0;">
                                    <span class="ns-rating-label">Rate your experience with our product...</span>
                                    <div class="ns-star-rating" id="rating-experience">
                                        <button type="button" class="ns-num-btn" data-value="1" onclick="setRating('experience',1)">1</button>
                                        <button type="button" class="ns-num-btn" data-value="2" onclick="setRating('experience',2)">2</button>
                                        <button type="button" class="ns-num-btn" data-value="3" onclick="setRating('experience',3)">3</button>
                                        <button type="button" class="ns-num-btn" data-value="4" onclick="setRating('experience',4)">4</button>
                                        <button type="button" class="ns-num-btn active" data-value="5" onclick="setRating('experience',5)">5</button>
                                        <div class="ns-num-label"><span style="color:#10b981;"><iconify-icon icon="lucide:star" style="vertical-align:text-bottom; color:#f59e0b;"></iconify-icon></span> Stars</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ns-feedback-card" id="feedback-step-2">
                                <div class="ns-step-progress"><span></span><span class="step-active"></span></div>
                                <div class="ns-form-group" style="margin-bottom:16px;">
                                    <label class="ns-rating-label">Anything that can be improved?</label>
                                    <textarea class="ns-form-textarea" id="feedback-body" rows="3" placeholder="Your feedback (Optional)"></textarea>
                                </div>
                                <button type="submit" class="ns-feedback-submit" id="feedback-submit-btn">
                                    Submit
                                </button>
                            </div>
                            
                            <div class="ns-feedback-card" id="feedback-step-3" style="display:none; align-items:center; gap:10px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#10b981"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg> 
                                <span style="font-weight:500; font-size:15px; color:#1e293b;">Thanks for the feedback!</span>
                            </div>

                        </form>
                    </div>
                </section>
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
            'weather' => ($weatherLat !== null && $weatherLng !== null)
                ? ['lat' => $weatherLat, 'lng' => $weatherLng]
                : null,
            'jumpoffMarkers' => $jumpoffMarkers,
            'defaultJumpoff' => $defaultJumpoff,
            'routes' => [
                'storeBooking' => url('/hikers/bookings'),
                'storeReview' => url('/hikers/reviews'),
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

        let currentMountain = null;
        const ratings = { mountain: 0, guide: 0, experience: 5 };

        document.addEventListener('DOMContentLoaded', () => {
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
                'reviews': document.getElementById('view-reviews'),
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

            const initialHash = (window.location.hash || '').replace(/^#/, '');
            if (initialHash && sections[initialHash]) {
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
            currentMountain = id;
            document.getElementById('detail-hero').style.backgroundImage = `url('${m.image}')`;
            document.getElementById('detail-name').textContent = m.name;
            document.getElementById('detail-status').textContent = (m.status === 'open' ? 'Open' : 'Closed');
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

            // Gear tags
            const gearEl = document.getElementById('detail-gear-tags');
            gearEl.innerHTML = (m.gear || []).map(g => `<span class="ns-gear-tag">${g}</span>`).join('');

            // Google Maps for Jump-off Point (Satellite / Hybrid View)
            if (typeof google !== 'undefined' && google.maps) {
                const mapEl = document.getElementById('detail-jumpoff-gmap');
                const jumpoffPos = { lat: m.jumpoff.lat, lng: m.jumpoff.lng };
                const summitPos = { lat: m.summit.lat, lng: m.summit.lng };

                const map = new google.maps.Map(mapEl, {
                    center: jumpoffPos,
                    zoom: 14,
                    mapTypeId: 'hybrid',
                    disableDefaultUI: false,
                    zoomControl: true,
                    mapTypeControl: true,
                    streetViewControl: false,
                    fullscreenControl: true,
                });

                // Jump-off marker (green)
                new google.maps.Marker({
                    position: jumpoffPos,
                    map: map,
                    title: '<iconify-icon icon="lucide:map-pin" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> ' + m.jumpoff.name + ' (Jump-off)',
                    icon: { url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png' }
                });

                // Summit marker (red)
                new google.maps.Marker({
                    position: summitPos,
                    map: map,
                    title: '<iconify-icon icon="lucide:mountain" style="vertical-align:text-bottom; margin-right:4px;"></iconify-icon> ' + m.name + ' Summit (' + m.elevation + ')',
                    icon: { url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png' }
                });

                // Auto-fit to show both jump-off and summit
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(jumpoffPos);
                bounds.extend(summitPos);
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
            if (!dj || dj.lat == null || dj.lng == null) return false;
            const map = new google.maps.Map(mapEl, {
                center: { lat: dj.lat, lng: dj.lng },
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
                if (statusEl) statusEl.textContent = 'Map unavailable — add trails / jump-off in the system';
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

                if (dj && dj.lat != null && dj.lng != null && distEl) {
                    const d = haversine(lat, lng, dj.lat, dj.lng);
                    distEl.textContent = d.toFixed(2) + ' km';
                    if (statusEl) {
                        statusEl.textContent = d < 1 ? 'Near jump-off' : d < 5 ? 'On trail' : 'Away from jump-off';
                    }
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

        // ── Star Ratings ──────────────────────────────────────────
        window.setRating = function(category, value) {
            ratings[category] = value;
            const container = document.getElementById('rating-' + category);
            if (!container) return;
            container.querySelectorAll('.ns-num-btn').forEach(btn => {
                const v = parseInt(btn.dataset.value);
                btn.classList.toggle('active', v === value);
            });
        };

        window.submitFeedback = function(e) {
            e.preventDefault();
            const mountain = document.getElementById('feedback-mountain')?.value;
            if (!mountain) { alert('Choose a mountain.'); return; }
            const rating = ratings.experience || 5;
            const body = document.getElementById('feedback-body')?.value || '';
            const fd = new FormData();
            fd.append('mountain', mountain);
            fd.append('rating', rating);
            fd.append('body', body);
            fd.append('_token', window.HIKER_BOOTSTRAP.csrf);
            fetch(window.HIKER_BOOTSTRAP.routes.storeReview, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.HIKER_BOOTSTRAP.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    document.getElementById('feedback-step-1').style.display = 'none';
                    document.getElementById('feedback-step-2').style.display = 'none';
                    document.getElementById('feedback-step-3').style.display = 'flex';
                } else {
                    alert('Could not submit review.');
                }
            }).catch(() => alert('Could not submit review.'));
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
            if (typeof ensureHikeTrackerMap === 'function') {
                ensureHikeTrackerMap();
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwFcYfuG72Uo8RbWjFqfpOehIsyjalz54&callback=initTrackerDefaultMap" async defer></script>
</body>
