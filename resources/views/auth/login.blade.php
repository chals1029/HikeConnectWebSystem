<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In — HikeConnect</title>
    <link rel="icon" type="image/png" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #B88E64;
            --accent-hover: #c49a72;
            --panel: #121212;
            --panel-elevated: #1a1a1a;
            --border: #2a2a2a;
            --text: #f5f5f5;
            --muted: #9ca3af;
            --ease: cubic-bezier(0.16, 1, 0.3, 1);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            background: #0a0a0a;
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(1rem, 4vw, 2.5rem);
        }
        .login-shell {
            width: min(1040px, 100%);
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-radius: clamp(16px, 2vw, 24px);
            overflow: hidden;
            background: var(--panel);
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.06),
                0 25px 80px rgba(0, 0, 0, 0.55),
                0 0 60px rgba(184, 142, 100, 0.08);
        }
        /* —— Slideshow —— */
        .login-slideshow {
            position: relative;
            min-height: 520px;
            background: #000;
        }
        .login-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 0.9s var(--ease);
            pointer-events: none;
        }
        .login-slide.active {
            opacity: 1;
            pointer-events: auto;
            z-index: 1;
        }
        .login-slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.35) 45%, rgba(0, 0, 0, 0.5) 100%);
        }
        .login-slide-inner {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 1.75rem 1.75rem 1.5rem;
        }
        .login-slide-brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }
        .login-slide-brand img {
            height: 2.5rem;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }
        .login-slide-brand span {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .login-slide-body {
            margin-top: auto;
            padding-bottom: 0.5rem;
        }
        .login-badge {
            display: inline-block;
            background: var(--accent);
            color: #111;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }
        .login-slide h2 {
            font-size: clamp(1.5rem, 3.2vw, 2rem);
            font-weight: 700;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 0.75rem;
            max-width: 18ch;
        }
        .login-slide p {
            font-size: 0.9375rem;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.88);
            max-width: 34ch;
        }
        .login-dots {
            position: absolute;
            bottom: 1.25rem;
            left: 1.75rem;
            right: 1.75rem;
            z-index: 3;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .login-dot {
            flex: 1;
            height: 3px;
            border: none;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.22);
            cursor: pointer;
            padding: 0;
            transition: background 0.35s ease, transform 0.35s ease;
        }
        .login-dot:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        .login-dot.active {
            background: var(--accent);
            box-shadow: 0 0 12px rgba(184, 142, 100, 0.45);
        }
        /* —— Form panel —— */
        .login-form-panel {
            position: relative;
            padding: clamp(2rem, 4vw, 2.75rem);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-close {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease, background 0.2s ease;
        }
        .login-close:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }
        .login-close:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }
        .login-form-panel h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .login-sub {
            font-size: 0.9375rem;
            color: var(--muted);
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        .login-sub a {
            color: #fff;
            text-decoration: underline;
            text-underline-offset: 3px;
            font-weight: 500;
        }
        .login-sub a:hover {
            color: var(--accent);
        }
        .login-field {
            margin-bottom: 1rem;
        }
        .login-field label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }
        .login-input-wrap {
            position: relative;
        }
        .login-input-wrap input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            padding-right: 3rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: var(--panel-elevated);
            color: var(--text);
            font-size: 0.9375rem;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .login-input-wrap input:focus {
            border-color: rgba(184, 142, 100, 0.5);
            box-shadow: 0 0 0 3px rgba(184, 142, 100, 0.12);
        }
        .login-input-wrap input::placeholder {
            color: #6b7280;
        }
        .login-toggle-pw {
            position: absolute;
            right: 0.35rem;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }
        .login-toggle-pw:hover {
            color: #fff;
        }
        .login-forgot {
            text-align: right;
            margin-top: -0.25rem;
            margin-bottom: 1.5rem;
        }
        .login-forgot a {
            font-size: 0.8125rem;
            color: #fff;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        .login-forgot a:hover {
            color: var(--accent);
        }
        .login-notice {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.875rem;
            color: var(--muted);
            line-height: 1.5;
        }
        .login-errors {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: rgba(220, 38, 38, 0.12);
            border: 1px solid rgba(220, 38, 38, 0.25);
            font-size: 0.875rem;
            color: #fecaca;
        }
        .login-errors ul {
            margin: 0;
            padding-left: 1.1rem;
        }
        .login-submit {
            width: 100%;
            padding: 0.95rem 1.5rem;
            border: none;
            border-radius: 999px;
            background: var(--accent);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.25s ease, transform 0.2s ease, box-shadow 0.25s ease;
        }
        .login-submit:hover {
            background: var(--accent-hover);
            box-shadow: 0 12px 32px rgba(184, 142, 100, 0.35);
            transform: translateY(-1px);
        }
        .login-submit:active {
            transform: translateY(0);
        }
        @media (max-width: 860px) {
            .login-shell {
                grid-template-columns: 1fr;
                max-width: 440px;
            }
            .login-slideshow {
                min-height: 320px;
            }
            .login-slide-brand img {
                height: 1.75rem;
            }
            .login-slide-brand span {
                font-size: 1.125rem;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .login-slide {
                transition: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-shell">
            <div class="login-slideshow" data-slideshow aria-roledescription="carousel">
                @php
                    $slides = [
                        [
                            'image' => asset('images/mt-batulao.jpg'),
                            'badge' => 'Featured trail',
                            'title' => 'Sunrise ridges at Mount Batulao',
                            'body' => 'Rolling grasslands and sharp limestone outcrops—one of Batangas’ most iconic day hikes for every level.',
                        ],
                        [
                            'image' => asset('images/mt-pico-de-loro.jpg'),
                            'badge' => 'Summit views',
                            'title' => 'Coastal breeze near Pico de Loro',
                            'body' => 'Forest trails open onto rocky spires and ocean air. Plan early, pack water, and tread lightly on the path.',
                        ],
                        [
                            'image' => asset('images/mt-talamitam.jpg'),
                            'badge' => 'Open slopes',
                            'title' => 'Wide skies on Mount Talamitam',
                            'body' => 'Gentle climbs and open fields make this a favorite for first timers and sunset chasers alike.',
                        ],
                    ];
                @endphp
                @foreach ($slides as $idx => $slide)
                    <div class="login-slide {{ $idx === 0 ? 'active' : '' }}" role="group" aria-roledescription="slide" aria-label="{{ $idx + 1 }} of {{ count($slides) }}" style="background-image: url('{{ $slide['image'] }}')">
                        <div class="login-slide-inner">
                            <div class="login-slide-brand">
                                <img src="{{ asset('images/HikeConnect-Logo.png') }}" alt="">
                                <span>HikeConnect</span>
                            </div>
                            <div class="login-slide-body">
                                <span class="login-badge">{{ $slide['badge'] }}</span>
                                <h2>{{ $slide['title'] }}</h2>
                                <p>{{ $slide['body'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="login-dots" role="tablist" aria-label="Slide indicators">
                    @foreach ($slides as $idx => $_)
                        <button type="button" class="login-dot {{ $idx === 0 ? 'active' : '' }}" data-slide-to="{{ $idx }}" aria-label="Show slide {{ $idx + 1 }}" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
            </div>

            <div class="login-form-panel">
                <a href="{{ route('home') }}" class="login-close" aria-label="Close and return home">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </a>
                <h1>Welcome back</h1>
                <p class="login-sub">Don’t have an account? <a href="{{ route('register') }}">Register</a></p>

                @if (session('login_notice'))
                    <p class="login-notice" role="status">{{ session('login_notice') }}</p>
                @endif
                @if ($errors->any())
                    <div class="login-errors" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('login.attempt') }}" novalidate>
                    @csrf
                    <div class="login-field">
                        <label for="email">Email</label>
                        <div class="login-input-wrap">
                            <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="login-field">
                        <label for="password">Password</label>
                        <div class="login-input-wrap">
                            <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
                            <button type="button" class="login-toggle-pw" aria-label="Show password" aria-pressed="false">
                                <svg class="icon-eye" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="login-forgot">
                        <a href="#">Forgot password?</a>
                    </div>
                    <button type="submit" class="login-submit">Log In</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var root = document.querySelector('[data-slideshow]');
            if (!root) return;
            var slides = root.querySelectorAll('.login-slide');
            var dots = root.querySelectorAll('.login-dot');
            var i = 0;
            var timer = null;
            var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            function go(n) {
                i = (n + slides.length) % slides.length;
                slides.forEach(function (s, j) {
                    s.classList.toggle('active', j === i);
                });
                dots.forEach(function (d, j) {
                    d.classList.toggle('active', j === i);
                    d.setAttribute('aria-selected', j === i ? 'true' : 'false');
                });
            }

            function next() {
                go(i + 1);
            }

            function start() {
                if (reduced || timer) return;
                timer = setInterval(next, 6500);
            }

            function stop() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            dots.forEach(function (d, j) {
                d.addEventListener('click', function () {
                    go(j);
                    stop();
                    start();
                });
            });

            root.addEventListener('mouseenter', stop);
            root.addEventListener('mouseleave', start);
            start();

            var pw = document.getElementById('password');
            var toggle = document.querySelector('.login-toggle-pw');
            if (pw && toggle) {
                toggle.addEventListener('click', function () {
                    var show = pw.type === 'password';
                    pw.type = show ? 'text' : 'password';
                    toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                    toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
                });
            }
        })();
    </script>
</body>
</html>
