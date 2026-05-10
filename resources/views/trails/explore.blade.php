<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $mountain->name }} Trail — HikeConnect</title>
    <meta name="description" content="Explore the {{ $mountain->name }} trail in {{ $mountain->location }}. {{ $mountain->short_description }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/HikeConnect-Logo.png') }}">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --pg: #064e3b; --dg: #022c22; --lg: #065f46; --ag: #10b981; --al: #34d399;
            --bc: #f0fdf4; --td: #111827; --tg: #6b7280; --w: #fff;
            --tr: .3s ease; --ease-smooth: cubic-bezier(0.16, 1, 0.3, 1);
            --radius: 16px; --radius-sm: 10px;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; color: var(--td); line-height: 1.6; background: #f8faf9; -webkit-font-smoothing: antialiased; }

        /* Header */
        .te-header { position: sticky; top: 0; z-index: 100; background: rgba(255,255,255,0.92); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.06); padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
        .te-header-left { display: flex; align-items: center; gap: 1rem; }
        .te-header-left a { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--td); font-weight: 600; font-size: 0.9rem; transition: color var(--tr); }
        .te-header-left a:hover { color: var(--ag); }
        .te-header-right { display: flex; align-items: center; gap: 0.75rem; }
        .te-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; border-radius: 100px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all var(--tr); cursor: pointer; border: none; }
        .te-btn-primary { background: linear-gradient(135deg, var(--pg), var(--lg)); color: var(--w); box-shadow: 0 4px 14px rgba(6,78,59,0.25); }
        .te-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(6,78,59,0.35); }
        .te-btn-outline { background: transparent; color: var(--pg); border: 1.5px solid rgba(6,78,59,0.2); }
        .te-btn-outline:hover { border-color: var(--ag); background: rgba(16,185,129,0.04); }

        /* Hero */
        .te-hero { position: relative; height: 380px; overflow: hidden; }
        .te-hero-img { width: 100%; height: 100%; object-fit: cover; }
        .te-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.1) 60%); }
        .te-hero-content { position: absolute; bottom: 2.5rem; left: 2rem; right: 2rem; color: var(--w); }
        .te-hero-content h1 { font-size: 2.25rem; font-weight: 800; margin-bottom: 0.5rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3); }
        .te-hero-meta { display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.85rem; opacity: 0.9; }
        .te-hero-meta span { display: flex; align-items: center; gap: 0.35rem; }
        .te-status-pill { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .te-status-pill.open { background: rgba(16,185,129,0.15); color: #059669; }
        .te-status-pill.closed { background: rgba(239,68,68,0.15); color: #dc2626; }
        .te-diff-pill { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 0.75rem; font-weight: 600; background: rgba(255,255,255,0.15); backdrop-filter: blur(4px); margin-left: 0.5rem; }

        /* Main layout */
        .te-main { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem 4rem; display: grid; grid-template-columns: 1fr 340px; gap: 2rem; }
        @media (max-width: 900px) { .te-main { grid-template-columns: 1fr; } }

        /* Cards */
        .te-card { background: var(--w); border-radius: var(--radius); border: 1px solid rgba(0,0,0,0.06); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .te-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .te-card h3 iconify-icon { color: var(--ag); }

        /* Trail overview */
        .te-overview-subtitle { color: var(--tg); font-size: 0.95rem; margin-bottom: 1.25rem; line-height: 1.7; }
        .te-metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .te-metric { text-align: center; padding: 1rem; background: var(--bc); border-radius: var(--radius-sm); }
        .te-metric span { display: block; font-size: 0.75rem; color: var(--tg); margin-bottom: 4px; }
        .te-metric strong { font-size: 1.1rem; color: var(--pg); }

        /* Route markers */
        .te-route-markers { display: flex; justify-content: space-between; padding: 1rem 0; border-top: 1px solid rgba(0,0,0,0.06); border-bottom: 1px solid rgba(0,0,0,0.06); margin-bottom: 1.25rem; position: relative; }
        .te-route-markers::before { content: ''; position: absolute; top: 50%; left: 10%; right: 10%; height: 2px; background: linear-gradient(90deg, var(--ag), var(--al)); transform: translateY(-50%); z-index: 0; }
        .te-route-marker { text-align: center; position: relative; z-index: 1; background: var(--w); padding: 0 0.5rem; }
        .te-route-marker strong { display: block; font-size: 0.8rem; }
        .te-route-marker span { font-size: 0.7rem; color: var(--tg); }

        /* Highlights */
        .te-highlights { list-style: none; }
        .te-highlights li { padding: 0.6rem 0; padding-left: 1.5rem; position: relative; font-size: 0.9rem; color: #374151; border-bottom: 1px solid rgba(0,0,0,0.04); }
        .te-highlights li:last-child { border-bottom: none; }
        .te-highlights li::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 8px; height: 8px; border-radius: 50%; background: var(--ag); }

        /* Top sights */
        .te-sight { padding: 0.75rem 0; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .te-sight:last-child { border-bottom: none; }
        .te-sight-name { font-weight: 600; font-size: 0.9rem; }
        .te-sight-type { font-size: 0.7rem; color: var(--ag); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .te-sight-desc { font-size: 0.85rem; color: var(--tg); margin-top: 2px; }

        /* Conditions */
        .te-conditions-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; }
        .te-condition-item { padding: 0.75rem; background: #fefce8; border-radius: var(--radius-sm); text-align: center; }
        .te-condition-item span { display: block; font-size: 0.7rem; color: var(--tg); margin-bottom: 2px; }
        .te-condition-item strong { font-size: 0.8rem; color: #92400e; }
        .te-conditions-summary { font-size: 0.9rem; color: #374151; margin-bottom: 1rem; line-height: 1.7; }
        .te-tips { list-style: none; }
        .te-tips li { padding: 0.5rem 0; padding-left: 1.5rem; position: relative; font-size: 0.85rem; color: #374151; }
        .te-tips li::before { content: '💡'; position: absolute; left: 0; top: 0.5rem; font-size: 0.75rem; }

        /* Reviews */
        .te-review-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .te-review-avg { font-size: 2rem; font-weight: 800; color: var(--pg); }
        .te-review-stars { color: #f59e0b; font-size: 1.1rem; }
        .te-review-count { font-size: 0.8rem; color: var(--tg); }
        .te-review-item { padding: 0.75rem 0; border-top: 1px solid rgba(0,0,0,0.05); }
        .te-review-item-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
        .te-review-item-head strong { font-size: 0.85rem; }
        .te-review-item-head span { font-size: 0.75rem; color: var(--tg); }
        .te-review-item p { font-size: 0.85rem; color: #4b5563; }

        /* Sidebar */
        .te-sidebar { position: sticky; top: 5rem; align-self: start; }
        .te-book-card { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); border: 1.5px solid rgba(16,185,129,0.2); }
        .te-book-card h3 { color: var(--pg); }
        .te-price-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 0.4rem 0; border-bottom: 1px solid rgba(0,0,0,0.04); }
        .te-price-row:last-of-type { border-bottom: none; }
        .te-price-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 1rem; padding-top: 0.75rem; margin-top: 0.5rem; border-top: 2px solid rgba(6,78,59,0.15); }
        .te-book-btn { width: 100%; margin-top: 1.25rem; justify-content: center; padding: 0.85rem; font-size: 0.95rem; }
        .te-book-note { font-size: 0.75rem; color: var(--tg); text-align: center; margin-top: 0.75rem; }

        /* Weather */
        .te-weather { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: linear-gradient(135deg, #eff6ff, #f0f9ff); border-radius: var(--radius-sm); margin-bottom: 1rem; }
        .te-weather-temp { font-size: 1.5rem; font-weight: 700; color: #1e40af; }
        .te-weather-label { font-size: 0.75rem; color: var(--tg); }

        /* Safety alert */
        .te-safety-alert { background: linear-gradient(135deg, rgba(239,68,68,0.06), rgba(245,158,11,0.04)); border: 1px solid rgba(239,68,68,0.2); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: flex-start; }
        .te-safety-alert iconify-icon { font-size: 1.25rem; color: #b91c1c; flex-shrink: 0; margin-top: 2px; }
        .te-safety-alert strong { display: block; color: #991b1b; font-size: 0.85rem; margin-bottom: 2px; }
        .te-safety-alert p { font-size: 0.8rem; color: var(--tg); line-height: 1.5; }

        /* Gallery */
        .te-gallery { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.25rem; }
        .te-gallery-item { border-radius: var(--radius-sm); overflow: hidden; position: relative; aspect-ratio: 4/3; }
        .te-gallery-item img { width: 100%; height: 100%; object-fit: cover; }
        .te-gallery-item figcaption { position: absolute; bottom: 0; left: 0; right: 0; padding: 0.75rem; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); color: var(--w); }
        .te-gallery-item figcaption strong { display: block; font-size: 0.8rem; }
        .te-gallery-item figcaption span { font-size: 0.7rem; opacity: 0.8; }

        /* Jumpoff info */
        .te-jumpoff-info { font-size: 0.85rem; color: #374151; }
        .te-jumpoff-info p { margin-bottom: 0.5rem; }
        .te-jumpoff-info strong { color: var(--td); }



        @media (max-width: 600px) {
            .te-hero { height: 280px; }
            .te-hero-content h1 { font-size: 1.5rem; }
            .te-metrics { grid-template-columns: 1fr; }
            .te-conditions-grid { grid-template-columns: 1fr; }
            .te-gallery { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="te-header">
        <div class="te-header-left">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/HikeConnect-Logo.png') }}" alt="HikeConnect" style="height:1.75rem;">
                <span>HikeConnect</span>
            </a>
        </div>
        <div class="te-header-right">
            @auth
                <a href="{{ route('hikers.dashboard') }}#mountain-overview" class="te-btn te-btn-outline">
                    <iconify-icon icon="lucide:layout-dashboard"></iconify-icon> Dashboard
                </a>
            @else
                <a href="{{ route('home') }}?auth=login" class="te-btn te-btn-outline">Log In</a>
                <a href="{{ route('home') }}?auth=register" class="te-btn te-btn-primary">Sign Up Free</a>
            @endauth
        </div>
    </header>

    <!-- Hero -->
    <section class="te-hero">
        <img class="te-hero-img" src="{{ asset($mountain->image_path) }}" alt="{{ $mountain->name }} trail view">
        <div class="te-hero-overlay"></div>
        <div class="te-hero-content">
            @php $st = $mountain->status ?? 'open'; @endphp
            <span class="te-status-pill {{ $st }}">
                <iconify-icon icon="lucide:circle" style="font-size:8px;"></iconify-icon>
                {{ $st === 'open' ? 'Open' : 'Closed' }}
            </span>
            <span class="te-diff-pill">{{ $mountain->difficulty }}</span>
            <h1>{{ $mountain->name }}</h1>
            <div class="te-hero-meta">
                <span><iconify-icon icon="lucide:map-pin"></iconify-icon> {{ $mountain->location }}</span>
                <span><iconify-icon icon="lucide:mountain"></iconify-icon> {{ $mountain->elevation_label }}</span>
                <span><iconify-icon icon="lucide:timer"></iconify-icon> {{ $mountain->duration_label }}</span>
                <span><iconify-icon icon="lucide:star"></iconify-icon> {{ $mountainData['reviews']['average'] }} / 5</span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="te-main">
        <div class="te-content">
            {{-- Safety Alert --}}
            @if($mountain->hasSafetyWarning())
                <div class="te-safety-alert">
                    <iconify-icon icon="lucide:triangle-alert"></iconify-icon>
                    <div>
                        <strong>{{ $mountain->safety_status_label }}</strong>
                        <p>{{ $mountain->safety_note ?: 'Please check trail conditions before booking or starting your hike.' }}</p>
                    </div>
                </div>
            @endif

            {{-- Trail Overview --}}
            @if(!empty($mountainData['experience']['enabled']))
            <div class="te-card">
                <h3><iconify-icon icon="lucide:route"></iconify-icon> Trail Overview</h3>
                <p class="te-overview-subtitle">{{ $mountainData['experience']['subtitle'] }}</p>

                <div class="te-metrics">
                    <div class="te-metric">
                        <span>Distance</span>
                        <strong>{{ $mountainData['experience']['distanceKm'] }} km</strong>
                    </div>
                    <div class="te-metric">
                        <span>Elevation Gain</span>
                        <strong>{{ $mountainData['experience']['elevationGainM'] }} m</strong>
                    </div>
                    <div class="te-metric">
                        <span>Route Type</span>
                        <strong>{{ $mountainData['experience']['routeType'] }}</strong>
                    </div>
                </div>

                {{-- Route Markers --}}
                @if(!empty($mountainData['experience']['routeMarkers']))
                <div class="te-route-markers">
                    @foreach($mountainData['experience']['routeMarkers'] as $marker)
                        <div class="te-route-marker">
                            <strong>{{ $marker['name'] }}</strong>
                            <span>{{ $marker['detail'] }}</span>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- Gallery --}}
                @if(!empty($mountainData['experience']['gallery']))
                <div class="te-gallery">
                    @foreach($mountainData['experience']['gallery'] as $img)
                        <figure class="te-gallery-item">
                            <img src="{{ $img['image'] }}" alt="{{ $img['label'] }}">
                            <figcaption>
                                <strong>{{ $img['label'] }}</strong>
                                <span>{{ $img['accent'] }}</span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
                @endif

                {{-- Highlights --}}
                @if(!empty($mountainData['experience']['highlights']))
                <h3 style="margin-top:1.5rem;"><iconify-icon icon="lucide:sparkles"></iconify-icon> Trail Highlights</h3>
                <ul class="te-highlights">
                    @foreach($mountainData['experience']['highlights'] as $highlight)
                        <li>{{ $highlight }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif

            {{-- Top Sights --}}
            @if(!empty($mountainData['experience']['topSights']))
            <div class="te-card">
                <h3><iconify-icon icon="lucide:eye"></iconify-icon> Top Sights</h3>
                @foreach($mountainData['experience']['topSights'] as $sight)
                    <div class="te-sight">
                        <span class="te-sight-type">{{ $sight['type'] }}</span>
                        <div class="te-sight-name">{{ $sight['name'] }}</div>
                        <div class="te-sight-desc">{{ $sight['description'] }}</div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Trail Conditions --}}
            @if(!empty($mountainData['experience']['conditions']))
            <div class="te-card">
                <h3><iconify-icon icon="lucide:cloud-sun"></iconify-icon> Trail Conditions & Tips</h3>
                <div class="te-conditions-grid">
                    <div class="te-condition-item">
                        <span>Crowds</span>
                        <strong>{{ $mountainData['experience']['conditions']['crowdLabel'] }}</strong>
                    </div>
                    <div class="te-condition-item">
                        <span>Shade</span>
                        <strong>{{ $mountainData['experience']['conditions']['shadeLabel'] }}</strong>
                    </div>
                    <div class="te-condition-item">
                        <span>Surface</span>
                        <strong>{{ $mountainData['experience']['conditions']['surfaceLabel'] }}</strong>
                    </div>
                </div>
                <p class="te-conditions-summary">{{ $mountainData['experience']['conditions']['summary'] }}</p>
                @if(!empty($mountainData['experience']['conditions']['tips']))
                <ul class="te-tips">
                    @foreach($mountainData['experience']['conditions']['tips'] as $tip)
                        <li>{{ $tip }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif

            {{-- Jump-off Information --}}
            <div class="te-card">
                <h3><iconify-icon icon="lucide:map-pin"></iconify-icon> Jump-off Point</h3>
                <div class="te-jumpoff-info">
                    <p><strong>{{ $mountainData['jumpoff']['name'] }}</strong></p>
                    <p>{{ $mountainData['jumpoff']['address'] }}</p>
                    @if($mountainData['jumpoff']['meetingTime'])
                        <p><iconify-icon icon="lucide:clock" style="vertical-align:text-bottom;margin-right:4px;"></iconify-icon> Meeting time: <strong>{{ $mountainData['jumpoff']['meetingTime'] }}</strong></p>
                    @endif
                    @if($mountainData['jumpoff']['notes'])
                        <p style="margin-top:0.5rem;color:var(--tg);font-size:0.8rem;">{{ $mountainData['jumpoff']['notes'] }}</p>
                    @endif
                </div>
            </div>

            {{-- Reviews --}}
            @if($reviewSummary['count'] > 0)
            <div class="te-card">
                <h3><iconify-icon icon="lucide:star"></iconify-icon> Hiker Reviews</h3>
                <div class="te-review-header">
                    <span class="te-review-avg">{{ $reviewSummary['average'] }}</span>
                    <div>
                        <div class="te-review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <iconify-icon icon="{{ $i <= round($reviewSummary['average']) ? 'lucide:star' : 'lucide:star' }}" style="opacity:{{ $i <= round($reviewSummary['average']) ? '1' : '0.3' }};"></iconify-icon>
                            @endfor
                        </div>
                        <span class="te-review-count">{{ $reviewSummary['count'] }} {{ Str::plural('review', $reviewSummary['count']) }}</span>
                    </div>
                </div>
                @foreach($reviewSummary['items'] as $review)
                    <div class="te-review-item">
                        <div class="te-review-item-head">
                            <strong>{{ $review['reviewer'] }}</strong>
                            <span>{{ $review['date'] }}</span>
                        </div>
                        @if($review['body'])
                            <p>{{ $review['body'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Description --}}
            @if($mountainData['description'])
            <div class="te-card">
                <h3><iconify-icon icon="lucide:info"></iconify-icon> About This Mountain</h3>
                <p style="font-size:0.9rem;color:#374151;line-height:1.8;">{{ $mountainData['description'] }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="te-sidebar">
            {{-- Weather --}}
            <div class="te-card">
                <div class="te-weather">
                    <iconify-icon icon="lucide:cloud-sun" style="font-size:1.5rem;color:#3b82f6;"></iconify-icon>
                    <div>
                        <span class="te-weather-temp" id="te-weather-temp">--°C</span>
                        <span class="te-weather-label">Current at trail</span>
                    </div>
                </div>
            </div>

            {{-- Booking Card --}}
            <div class="te-card te-book-card">
                <h3><iconify-icon icon="lucide:calendar-check"></iconify-icon> Book This Trail</h3>

                @php
                    $totalPerPerson = ($mountainData['pricing']['registrationFeePerPerson'] ?? 0)
                        + ($mountainData['pricing']['environmentalFeePerPerson'] ?? 0)
                        + ($mountainData['pricing']['localFeePerPerson'] ?? 0)
                        + ($mountainData['pricing']['guideFeePerPerson'] ?? 0);
                @endphp

                @if($totalPerPerson > 0)
                    <div class="te-price-row"><span>Registration fee</span><strong>₱{{ number_format($mountainData['pricing']['registrationFeePerPerson'], 0) }}</strong></div>
                    @if($mountainData['pricing']['environmentalFeePerPerson'] > 0)
                        <div class="te-price-row"><span>Environmental fee</span><strong>₱{{ number_format($mountainData['pricing']['environmentalFeePerPerson'], 0) }}</strong></div>
                    @endif
                    @if($mountainData['pricing']['localFeePerPerson'] > 0)
                        <div class="te-price-row"><span>Local fee</span><strong>₱{{ number_format($mountainData['pricing']['localFeePerPerson'], 0) }}</strong></div>
                    @endif
                    @if($mountainData['pricing']['guideFeePerPerson'] > 0)
                        <div class="te-price-row"><span>Guide fee (per person)</span><strong>₱{{ number_format($mountainData['pricing']['guideFeePerPerson'], 0) }}</strong></div>
                    @endif
                    <div class="te-price-total"><span>Est. total / person</span><span>₱{{ number_format($totalPerPerson, 0) }}</span></div>
                @else
                    <p style="font-size:0.85rem;color:var(--tg);margin-bottom:0.75rem;">Contact a guide for pricing details.</p>
                @endif

                @auth
                    <a href="{{ route('hikers.dashboard') }}#mountain-overview" class="te-btn te-btn-primary te-book-btn">
                        <iconify-icon icon="lucide:calendar-plus"></iconify-icon> Book Now
                    </a>
                @else
                    <a href="{{ route('home') }}?auth=login" class="te-btn te-btn-primary te-book-btn">
                        <iconify-icon icon="lucide:calendar-plus"></iconify-icon> Book Now
                    </a>
                    <p class="te-book-note">Log in or create an account to book this hike.</p>
                @endauth

                @if($mountainData['pricing']['sourceNote'])
                    <p class="te-book-note" style="margin-top:0.5rem;">{{ $mountainData['pricing']['sourceNote'] }}</p>
                @endif
            </div>

            {{-- Available Guides --}}
            @if(count($guideData) > 0)
            <div class="te-card">
                <h3><iconify-icon icon="lucide:users"></iconify-icon> Available Guides</h3>
                @foreach($guideData as $guide)
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0;border-bottom:1px solid rgba(0,0,0,0.04);">
                        <div style="width:36px;height:36px;border-radius:50%;background:{{ $guide['gradient'] ?? 'linear-gradient(135deg,#065f46,#10b981)' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;font-weight:700;">{{ $guide['initials'] }}</div>
                        <div>
                            <div style="font-size:0.85rem;font-weight:600;">{{ $guide['name'] }}</div>
                            <div style="font-size:0.75rem;color:var(--tg);">{{ $guide['spec'] ?? 'General Guide' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Gear Recommendations --}}
            @if(!empty($mountainData['gear']))
            <div class="te-card">
                <h3><iconify-icon icon="lucide:backpack"></iconify-icon> Recommended Gear</h3>
                <ul style="list-style:none;font-size:0.85rem;">
                    @foreach($mountainData['gear'] as $item)
                        <li style="padding:0.35rem 0;display:flex;align-items:center;gap:0.5rem;">
                            <iconify-icon icon="lucide:check-circle" style="color:var(--ag);font-size:0.9rem;"></iconify-icon>
                            {{ is_string($item) ? $item : ($item['name'] ?? '') }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Emergency Contact --}}
            @if($mountainData['emergencyContact'])
            <div class="te-card" style="border-color:rgba(239,68,68,0.15);">
                <h3 style="color:#b91c1c;"><iconify-icon icon="lucide:phone-call"></iconify-icon> Emergency Contact</h3>
                <p style="font-size:0.85rem;color:#374151;">{{ $mountainData['emergencyContact'] }}</p>
            </div>
            @endif
        </aside>
    </div>



    <script>
    (function() {
        'use strict';

        // Weather fetch
        const lat = @json($mountainData['weather']['lat']);
        const lng = @json($mountainData['weather']['lng']);
        if (lat && lng) {
            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`)
                .then(r => r.json())
                .then(data => {
                    if (data?.current_weather?.temperature != null) {
                        document.getElementById('te-weather-temp').textContent = Math.round(data.current_weather.temperature) + '°C';
                    }
                })
                .catch(() => {});
        }
    })();
    </script>
</body>
</html>
