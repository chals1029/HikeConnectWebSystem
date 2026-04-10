{{-- Auth modal: blur backdrop, flip login ↔ register. Included from welcome. --}}
@php
    $authSlides = [
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
    $initialAuthMode = null;
    if ($errors->any()) {
        $initialAuthMode = old('form_side') === 'register' ? 'register' : 'login';
    } elseif (session('register_notice')) {
        $initialAuthMode = 'register';
    } elseif (session('login_notice')) {
        $initialAuthMode = 'login';
    } elseif (in_array(request()->query('auth'), ['login', 'register'], true)) {
        $initialAuthMode = request()->query('auth');
    }
@endphp

<style>
    .auth-modal { display: none; position: fixed; inset: 0; z-index: 10002; align-items: center; justify-content: center; padding: clamp(0.75rem, 3vw, 1.5rem); }
    .auth-modal[aria-hidden="false"] {
        display: flex;
        overflow-x: hidden;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    .auth-modal__backdrop {
        position: absolute; inset: 0;
        z-index: 0;
        background: rgba(6, 20, 16, 0.42);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }
    .auth-modal__scene {
        position: relative;
        z-index: 1;
        width: min(820px, 96vw);
        flex-shrink: 0;
        overflow: hidden;
        perspective: 1100px;
    }
    .auth-modal__inner {
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        min-height: min(460px, 72vh);
        transform: rotateY(0deg);
    }
    .auth-modal[data-mode="register"] .auth-modal__inner {
        min-height: min(640px, 90vh);
    }
    .auth-modal[data-mode="register"] .auth-modal__inner { transform: rotateY(180deg); }
    .auth-modal__face {
        position: absolute;
        inset: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-radius: 20px;
        overflow: hidden;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        background: #121212;
        box-shadow:
            0 0 0 1px rgba(255, 255, 255, 0.06),
            0 16px 40px rgba(0, 0, 0, 0.45),
            0 0 32px rgba(166, 139, 103, 0.06);
    }
    .auth-modal__face--back { transform: rotateY(180deg); grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr); }
    /* Hidden flip face + its children can still steal clicks — disable entire subtree */
    .auth-modal[data-mode="login"] .auth-modal__face--back,
    .auth-modal[data-mode="login"] .auth-modal__face--back * { pointer-events: none !important; }
    .auth-modal[data-mode="register"] .auth-modal__face--front,
    .auth-modal[data-mode="register"] .auth-modal__face--front * { pointer-events: none !important; }
    .auth-slideshow {
        position: relative;
        min-height: min(460px, 72vh);
        background: #000;
    }
    .auth-modal[data-mode="register"] .auth-slideshow {
        min-height: min(640px, 90vh);
    }
    @media (max-width: 640px) {
        .auth-modal__scene { width: min(480px, 96vw); }
        .auth-modal__face { grid-template-columns: 1fr; }
        .auth-modal__face--back { grid-template-columns: 1fr; }
        .auth-slideshow { min-height: 200px; order: -1; }
        .auth-modal[data-mode="register"] .auth-modal__inner { min-height: min(560px, 85vh); }
        .auth-modal__face { position: relative; display: flex; flex-direction: column; }
        .auth-modal__face--front,
        .auth-modal__face--back { position: absolute; }
        .auth-modal__inner { min-height: 520px; }
        .auth-modal[data-mode="register"] .auth-slideshow { min-height: 200px; }
    }
    .auth-slide {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        opacity: 0;
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    .auth-slide.active { opacity: 1; pointer-events: auto; z-index: 1; }
    .auth-slide::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(105deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.32) 50%, rgba(0,0,0,.48) 100%);
    }
    .auth-slide__inner {
        position: relative; z-index: 2; height: 100%;
        display: flex; flex-direction: column;
        padding: 1.2rem 1.15rem 2.1rem;
    }
    .auth-slide__brand { display: flex; align-items: center; gap: 0.45rem; }
    .auth-slide__brand img { height: 34px; width: auto; filter: brightness(0) invert(1); }
    .auth-slide__brand span { font-weight: 700; font-size: 1.0625rem; color: #fff; letter-spacing: -0.02em; }
    .auth-slide__body { margin-top: auto; padding-bottom: 0.25rem; }
    .auth-badge {
        display: inline-block;
        background: #A68B67;
        color: #111;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        margin-bottom: 0.65rem;
    }
    .auth-slide h3 {
        font-size: clamp(1.1rem, 2.2vw, 1.35rem);
        font-weight: 700;
        line-height: 1.15;
        color: #fff;
        margin-bottom: 0.45rem;
        max-width: 22ch;
    }
    .auth-slide p {
        font-size: 0.8125rem;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.88);
        max-width: 38ch;
    }
    .auth-dots {
        position: absolute;
        bottom: 0.85rem;
        left: 1.25rem;
        right: 1.25rem;
        z-index: 3;
        display: flex;
        gap: 0.4rem;
        align-items: center;
    }
    .auth-dot {
        flex: 1;
        height: 3px;
        border: none;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.22);
        cursor: pointer;
        padding: 0;
        transition: background 0.3s ease;
    }
    .auth-dot:hover { background: rgba(255, 255, 255, 0.38); }
    .auth-dot.active {
        background: #A68B67;
        box-shadow: 0 0 10px rgba(166, 139, 103, 0.4);
    }
    .auth-progress-track {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        z-index: 4;
        background: rgba(255, 255, 255, 0.12);
    }
    .auth-progress-fill {
        height: 100%;
        width: 0%;
        background: #A68B67;
        border-radius: 0 2px 2px 0;
    }
    .auth-progress-fill.is-animating {
        animation: authProgressFill 6.5s linear forwards;
    }
    @keyframes authProgressFill { to { width: 100%; } }
    .auth-form-panel {
        position: relative;
        padding: 1.35rem 1.35rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        text-align: center;
        overflow: visible;
        min-height: 100%;
    }
    .auth-modal__face--front .auth-form-panel { justify-content: center; }
    .auth-modal__face--back .auth-form-panel { justify-content: flex-start; padding-top: 1.5rem; }
    .auth-close {
        position: absolute;
        top: 0.85rem;
        right: 0.85rem;
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s, background 0.2s;
    }
    .auth-close:hover { color: #fff; background: rgba(255, 255, 255, 0.06); }
    .auth-close:focus-visible { outline: 2px solid #A68B67; outline-offset: 2px; }
    .auth-form-panel h2 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #f5f5f5;
        margin-bottom: 0.4rem;
        letter-spacing: -0.02em;
    }
    .auth-sub {
        font-size: 0.8125rem;
        color: #9ca3af;
        margin-bottom: 1.2rem;
        line-height: 1.45;
    }
    .auth-sub a,
    .auth-sub .auth-flip-link {
        color: #fff;
        text-decoration: underline;
        text-underline-offset: 3px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        background: none;
        font: inherit;
        padding: 0;
    }
    .auth-sub a:hover,
    .auth-sub .auth-flip-link:hover { color: #A68B67; }
    .auth-field { margin-bottom: 0.65rem; text-align: left; }
    .auth-field label {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        color: #9ca3af;
        margin-bottom: 0.35rem;
    }
    .auth-input-wrap { position: relative; }
    .auth-input-wrap input {
        width: 100%;
        padding: 0.65rem 0.9rem;
        padding-right: 2.65rem;
        border-radius: 0.5rem;
        border: 1px solid #2a2a2a;
        background: #1a1a1a;
        color: #f5f5f5;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .auth-input-wrap input:focus {
        border-color: rgba(166, 139, 103, 0.45);
        box-shadow: 0 0 0 3px rgba(166, 139, 103, 0.12);
    }
    .auth-input-wrap input::placeholder { color: #6b7280; }
    .auth-toggle-pw {
        position: absolute;
        right: 0.2rem;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 0.375rem;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .auth-toggle-pw:hover { color: #fff; }
    .auth-forgot {
        text-align: right;
        margin-top: -0.05rem;
        margin-bottom: 0.95rem;
    }
    .auth-forgot a {
        font-size: 0.75rem;
        color: #fff;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .auth-forgot a:hover { color: #A68B67; }
    .auth-forgot button {
        font: inherit;
        font-size: 0.75rem;
        color: #fff;
        text-decoration: underline;
        text-underline-offset: 3px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    .auth-forgot button:hover { color: #A68B67; }
    .auth-login-views { width: 100%; }
    .auth-login-view {
        display: none;
        flex-direction: column;
        align-items: stretch;
        width: 100%;
        text-align: center;
    }
    .auth-login-view.is-active { display: flex; }
    .auth-login-view > .auth-field,
    .auth-login-view > form .auth-field { text-align: left; }
    .auth-back-row { margin-top: 1rem; }
    .auth-back-link {
        font: inherit;
        font-size: 0.8125rem;
        color: #9ca3af;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem 0;
        text-align: center;
        width: 100%;
    }
    .auth-back-link:hover { color: #A68B67; }
    .auth-code-input {
        letter-spacing: 0.25em;
        text-align: center;
        font-size: 1rem;
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
    .auth-inline-row {
        display: flex;
        align-items: stretch;
        gap: 0.5rem;
    }
    .auth-inline-row .auth-input-wrap {
        flex: 1;
        min-width: 0;
    }
    .auth-inline-btn {
        flex-shrink: 0;
        align-self: stretch;
        padding: 0 1rem;
        min-width: 4.5rem;
        border: none;
        border-radius: 999px;
        background: #A68B67;
        color: #fff;
        font-weight: 600;
        font-size: 0.8125rem;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        white-space: nowrap;
    }
    .auth-inline-btn:hover {
        background: #b89a75;
    }
    .auth-inline-btn:active {
        transform: scale(0.98);
    }
    .auth-forgot-sent {
        display: none;
        margin-bottom: 0.65rem !important;
        font-size: 0.75rem !important;
        color: #9ca3af !important;
    }
    .auth-forgot-sent.is-visible {
        display: block;
    }
    .auth-forgot-step {
        display: none;
        margin-top: 0.5rem;
    }
    .auth-forgot-step.is-open {
        display: block;
    }
    .auth-login-view--reset-pw {
        padding-top: 0.25rem;
    }
    .auth-login-view--reset-pw .auth-field {
        margin-bottom: 0.85rem;
    }
    .auth-login-view--reset-pw .auth-submit {
        margin-top: 0.35rem;
    }
    @media (max-width: 400px) {
        .auth-inline-row {
            flex-direction: column;
        }
        .auth-inline-btn {
            width: 100%;
            padding: 0.65rem 1rem;
        }
    }
    .auth-forgot-demo {
        font-size: 0.6875rem;
        color: #6b7280;
        margin: -0.35rem 0 0.65rem;
        line-height: 1.4;
        text-align: left;
    }
    .auth-success-block {
        padding: 0.5rem 0 0.25rem;
    }
    .auth-success-block .auth-success-icon {
        display: flex;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
    .auth-success-block .auth-success-icon svg { display: block; }
    .auth-success-block p {
        font-size: 0.875rem;
        color: #9ca3af;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    .auth-notice {
        margin-bottom: 0.75rem;
        padding: 0.6rem 0.75rem;
        border-radius: 0.5rem;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.8125rem;
        color: #9ca3af;
        line-height: 1.45;
        text-align: left;
    }
    .auth-errors {
        margin-bottom: 0.75rem;
        padding: 0.6rem 0.75rem;
        border-radius: 0.5rem;
        background: rgba(220, 38, 38, 0.12);
        border: 1px solid rgba(220, 38, 38, 0.25);
        font-size: 0.8125rem;
        color: #fecaca;
        text-align: left;
    }
    .auth-errors ul { margin: 0; padding-left: 1.1rem; }
    .auth-submit {
        width: 100%;
        padding: 0.78rem 1.2rem;
        border: none;
        border-radius: 999px;
        background: #A68B67;
        color: #fff;
        font-size: 0.9375rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
    }
    .auth-submit:hover {
        background: #b89a75;
        box-shadow: 0 10px 28px rgba(166, 139, 103, 0.32);
        transform: translateY(-1px);
    }
    /* Register form: pill inputs + layout */
    .auth-register-form .auth-input-wrap input {
        border-radius: 999px;
        padding-left: 1rem;
        padding-right: 2.65rem;
    }
    .auth-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem 0.65rem;
        margin-bottom: 0.65rem;
    }
    .auth-field-row .auth-field { margin-bottom: 0; }
    @media (max-width: 480px) {
        .auth-field-row { grid-template-columns: 1fr; }
    }
    .auth-checkbox-row {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        margin-bottom: 0.85rem;
        text-align: left;
        cursor: pointer;
        font-size: 0.8125rem;
        color: #e5e7eb;
        line-height: 1.4;
    }
    .auth-checkbox-row input {
        width: 1.05rem;
        height: 1.05rem;
        margin-top: 0.12rem;
        flex-shrink: 0;
        accent-color: #A68B67;
        cursor: pointer;
    }
    @media (prefers-reduced-motion: reduce) {
        .auth-modal__inner { transition: none; }
        .auth-slide { transition: none; }
        .auth-progress-fill.is-animating { animation: none; width: 100%; }
    }
</style>

<div id="auth-modal" class="auth-modal" role="dialog" aria-modal="true" aria-labelledby="auth-login-title" aria-hidden="{{ $initialAuthMode ? 'false' : 'true' }}" data-mode="{{ $initialAuthMode ?? 'login' }}">
    <div class="auth-modal__backdrop js-auth-close" aria-hidden="true"></div>
    <div class="auth-modal__scene">
        <div class="auth-modal__inner">
            {{-- Front: login --}}
            <div class="auth-modal__face auth-modal__face--front">
                <div class="auth-slideshow" data-slideshow-group="login">
                    @foreach ($authSlides as $idx => $slide)
                        <div class="auth-slide {{ $idx === 0 ? 'active' : '' }}" data-slide-index="{{ $idx }}" style="background-image: url('{{ $slide['image'] }}')">
                            <div class="auth-slide__inner">
                                <div class="auth-slide__brand">
                                    <img src="{{ asset('images/HikeConnect-Logo.png') }}" alt="">
                                    <span>HikeConnect</span>
                                </div>
                                <div class="auth-slide__body">
                                    <span class="auth-badge">{{ $slide['badge'] }}</span>
                                    <h3>{{ $slide['title'] }}</h3>
                                    <p>{{ $slide['body'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="auth-dots" role="tablist" aria-label="Slides">
                        @foreach ($authSlides as $idx => $_)
                            <button type="button" class="auth-dot {{ $idx === 0 ? 'active' : '' }}" data-slide-to="{{ $idx }}" aria-label="Slide {{ $idx + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="auth-progress-track" aria-hidden="true"><div class="auth-progress-fill is-animating" data-progress-bar></div></div>
                </div>
                <div class="auth-form-panel">
                    <button type="button" class="auth-close js-auth-close" aria-label="Close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>

                    <div class="auth-login-views">
                        <div class="auth-login-view is-active" data-login-view="login" role="tabpanel">
                            <h2 id="auth-login-title">Welcome back</h2>
                            <p class="auth-sub">Don’t have an account? <button type="button" class="auth-flip-link js-auth-flip" data-flip="register">Register</button></p>

                            @if (session('login_notice'))
                                <p class="auth-notice" role="status">{{ session('login_notice') }}</p>
                            @endif
                            @if ($errors->any() && old('form_side') !== 'register')
                                <div class="auth-errors" role="alert"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                            @endif

                            <form method="post" action="{{ route('login.attempt') }}" id="auth-login-form">
                                @csrf
                                <input type="hidden" name="form_side" value="login">
                                <div class="auth-field">
                                    <label for="auth-email">Email</label>
                                    <div class="auth-input-wrap">
                                        <input id="auth-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="auth-field">
                                    <label for="auth-password">Password</label>
                                    <div class="auth-input-wrap">
                                        <input id="auth-password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
                                        <button type="button" class="auth-toggle-pw" data-target="auth-password" aria-label="Show password">…</button>
                                    </div>
                                </div>
                                <div class="auth-forgot"><button type="button" class="js-auth-show-forgot">Forgot password?</button></div>
                                <button type="submit" class="auth-submit">Log In</button>
                            </form>
                        </div>

                        <div class="auth-login-view" data-login-view="forgot-flow" role="tabpanel">
                            <h2 id="auth-forgot-title">Forgot password</h2>
                            <p class="auth-sub">Enter your email, tap <strong style="color:#e5e7eb;font-weight:600">Send</strong>, then enter the code we send (demo).</p>
                            <p class="auth-forgot-demo">Demo only — no real email. Any 6-digit code works.</p>
                            <p class="auth-sub auth-forgot-sent" id="auth-forgot-code-sent" aria-live="polite"></p>

                            <div id="auth-forgot-card">
                                <div class="auth-field">
                                    <label for="auth-forgot-email">Email</label>
                                    <div class="auth-inline-row">
                                        <div class="auth-input-wrap">
                                            <input id="auth-forgot-email" type="email" autocomplete="email" placeholder="you@example.com" required>
                                        </div>
                                        <button type="button" class="auth-inline-btn js-forgot-send-code">Send</button>
                                    </div>
                                </div>

                                <div id="auth-forgot-code-block" class="auth-forgot-step">
                                    <div class="auth-field">
                                        <label for="auth-forgot-code">Verification code</label>
                                        <div class="auth-inline-row">
                                            <div class="auth-input-wrap">
                                                <input id="auth-forgot-code" class="auth-code-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="000000" autocomplete="one-time-code">
                                            </div>
                                            <button type="button" class="auth-inline-btn js-forgot-verify-code">Verify</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="auth-back-row"><button type="button" class="auth-back-link js-forgot-to-login">← Back to log in</button></div>
                        </div>

                        <div class="auth-login-view auth-login-view--reset-pw" data-login-view="forgot-reset" role="tabpanel">
                            <h2 id="auth-forgot-reset-title">Set your new password</h2>
                            <p class="auth-sub">Choose a strong password you’ll remember.</p>
                            <div class="auth-field">
                                <label for="auth-forgot-new-pw">New password</label>
                                <div class="auth-input-wrap">
                                    <input id="auth-forgot-new-pw" type="password" autocomplete="new-password" placeholder="••••••••" required minlength="8">
                                    <button type="button" class="auth-toggle-pw" data-target="auth-forgot-new-pw" aria-label="Show password">…</button>
                                </div>
                            </div>
                            <div class="auth-field">
                                <label for="auth-forgot-new-pw2">Confirm password</label>
                                <div class="auth-input-wrap">
                                    <input id="auth-forgot-new-pw2" type="password" autocomplete="new-password" placeholder="••••••••" required minlength="8">
                                    <button type="button" class="auth-toggle-pw" data-target="auth-forgot-new-pw2" aria-label="Show password">…</button>
                                </div>
                            </div>
                            <p class="auth-forgot-demo" id="auth-forgot-pw-hint" style="display:none;color:#f87171;">Passwords must match.</p>
                            <button type="button" class="auth-submit js-forgot-save-password">Update password</button>
                            <div class="auth-back-row"><button type="button" class="auth-back-link js-forgot-back-to-verify">← Back to verification</button></div>
                        </div>

                        <div class="auth-login-view" data-login-view="forgot-done" role="tabpanel">
                            <div class="auth-success-block">
                                <div class="auth-success-icon" aria-hidden="true"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                                <h2 id="auth-forgot-done-title">Password updated</h2>
                                <p>You’re all set. Your password was reset (demo — nothing was saved).</p>
                                <button type="button" class="auth-submit js-forgot-to-login">Back to log in</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back: register --}}
            <div class="auth-modal__face auth-modal__face--back">
                <div class="auth-slideshow" data-slideshow-group="register">
                    @foreach ($authSlides as $idx => $slide)
                        <div class="auth-slide {{ $idx === 0 ? 'active' : '' }}" data-slide-index="{{ $idx }}" style="background-image: url('{{ $slide['image'] }}')">
                            <div class="auth-slide__inner">
                                <div class="auth-slide__brand">
                                    <img src="{{ asset('images/HikeConnect-Logo.png') }}" alt="">
                                    <span>HikeConnect</span>
                                </div>
                                <div class="auth-slide__body">
                                    <span class="auth-badge">{{ $slide['badge'] }}</span>
                                    <h3>{{ $slide['title'] }}</h3>
                                    <p>{{ $slide['body'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="auth-dots" role="tablist" aria-label="Slides">
                        @foreach ($authSlides as $idx => $_)
                            <button type="button" class="auth-dot {{ $idx === 0 ? 'active' : '' }}" data-slide-to="{{ $idx }}" aria-label="Slide {{ $idx + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="auth-progress-track" aria-hidden="true"><div class="auth-progress-fill is-animating" data-progress-bar></div></div>
                </div>
                <div class="auth-form-panel">
                    <button type="button" class="auth-close js-auth-close" aria-label="Close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
                    <h2 id="auth-register-title">Create an account</h2>
                    <p class="auth-sub">Already have an account? <button type="button" class="auth-flip-link js-auth-flip" data-flip="login">Log in</button></p>

                    @if (session('register_notice'))
                        <p class="auth-notice" role="status">{{ session('register_notice') }}</p>
                    @endif
                    @if ($errors->any() && old('form_side') === 'register')
                        <div class="auth-errors" role="alert">
                            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('register.attempt') }}" id="auth-register-form" class="auth-register-form">
                        @csrf
                        <input type="hidden" name="form_side" value="register">
                        <div class="auth-field-row">
                            <div class="auth-field">
                                <label for="auth-reg-first">First name</label>
                                <div class="auth-input-wrap">
                                    <input id="auth-reg-first" name="first_name" type="text" autocomplete="given-name" placeholder="First name" value="{{ old('first_name') }}" required>
                                </div>
                            </div>
                            <div class="auth-field">
                                <label for="auth-reg-last">Last name</label>
                                <div class="auth-input-wrap">
                                    <input id="auth-reg-last" name="last_name" type="text" autocomplete="family-name" placeholder="Last name" value="{{ old('last_name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="auth-reg-email">Email</label>
                            <div class="auth-input-wrap">
                                <input id="auth-reg-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="auth-reg-phone">Phone number</label>
                            <div class="auth-input-wrap">
                                <input id="auth-reg-phone" name="phone" type="tel" autocomplete="tel" placeholder="09123456789" value="{{ old('phone') }}" inputmode="numeric" required>
                            </div>
                        </div>
                        <div class="auth-field">
                            <label for="auth-reg-password">Password</label>
                            <div class="auth-input-wrap">
                                <input id="auth-reg-password" name="password" type="password" autocomplete="new-password" placeholder="••••••••" required minlength="8">
                                <button type="button" class="auth-toggle-pw" data-target="auth-reg-password" aria-label="Show password">…</button>
                            </div>
                        </div>
                        <label class="auth-checkbox-row">
                            <input type="checkbox" name="confirm_details" value="1" {{ old('confirm_details') ? 'checked' : '' }} required>
                            <span>I confirm all the details are correct</span>
                        </label>
                        <button type="submit" class="auth-submit" style="margin-top:0.15rem">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($initialAuthMode)
<script>document.body.style.overflow = 'hidden';</script>
@endif

<script>
(function () {
    var modal = document.getElementById('auth-modal');
    if (!modal) return;

    var progressMs = 6500;

    function showLoginView(name) {
        modal.querySelectorAll('.auth-login-view').forEach(function (el) {
            el.classList.toggle('is-active', el.getAttribute('data-login-view') === name);
        });
    }

    function resetForgotFlowUI() {
        var cb = document.getElementById('auth-forgot-code-block');
        var sent = document.getElementById('auth-forgot-code-sent');
        if (cb) cb.classList.remove('is-open');
        if (sent) {
            sent.classList.remove('is-visible');
            sent.textContent = '';
        }
        var fe = document.getElementById('auth-forgot-email');
        var fc = document.getElementById('auth-forgot-code');
        var np = document.getElementById('auth-forgot-new-pw');
        var np2 = document.getElementById('auth-forgot-new-pw2');
        if (fe) fe.value = '';
        if (fc) fc.value = '';
        if (np) np.value = '';
        if (np2) np2.value = '';
        var hint = document.getElementById('auth-forgot-pw-hint');
        if (hint) hint.style.display = 'none';
    }

    function resetLoginViews() {
        showLoginView('login');
        resetForgotFlowUI();
    }

    function maskEmail(email) {
        var at = email.indexOf('@');
        if (at < 1) return email;
        var u = email.slice(0, at);
        var d = email.slice(at);
        var show = u.length <= 2 ? u.charAt(0) + '••' : u.slice(0, 2) + '•••';
        return show + d;
    }

    function openModal(mode) {
        modal.setAttribute('aria-hidden', 'false');
        modal.setAttribute('data-mode', mode === 'register' ? 'register' : 'login');
        document.body.style.overflow = 'hidden';
        if (mode !== 'register') {
            resetLoginViews();
        }
        restartAllProgress();
        startSlideshowTimers();
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        modal.setAttribute('data-mode', 'login');
        document.body.style.overflow = '';
        stopSlideshowTimers();
        resetLoginViews();
    }

    modal.querySelectorAll('.js-auth-flip').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var to = btn.getAttribute('data-flip');
            modal.setAttribute('data-mode', to === 'register' ? 'register' : 'login');
            resetLoginViews();
            restartAllProgress();
            startSlideshowTimers();
        });
    });

    document.querySelectorAll('.js-auth-open').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var m = el.getAttribute('data-auth-mode') || 'login';
            openModal(m);
        });
    });

    modal.querySelectorAll('.js-auth-close').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            closeModal();
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') closeModal();
    });

    var slideState = { i: 0, timers: [], interval: null };

    function getGroups() {
        return modal.querySelectorAll('[data-slideshow-group]');
    }

    function setSlideAll(idx) {
        var n = 3;
        slideState.i = ((idx % n) + n) % n;
        getGroups().forEach(function (root) {
            root.querySelectorAll('.auth-slide').forEach(function (s, j) {
                s.classList.toggle('active', j === slideState.i);
            });
            root.querySelectorAll('.auth-dot').forEach(function (d, j) {
                d.classList.toggle('active', j === slideState.i);
            });
            var bar = root.querySelector('[data-progress-bar]');
            if (bar) {
                bar.classList.remove('is-animating');
                void bar.offsetWidth;
                bar.classList.add('is-animating');
            }
        });
    }

    function restartAllProgress() {
        getGroups().forEach(function (root) {
            var bar = root.querySelector('[data-progress-bar]');
            if (bar) {
                bar.classList.remove('is-animating');
                void bar.offsetWidth;
                bar.classList.add('is-animating');
            }
        });
    }

    function startSlideshowTimers() {
        stopSlideshowTimers();
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        slideState.interval = setInterval(function () {
            setSlideAll(slideState.i + 1);
        }, progressMs);
    }

    function stopSlideshowTimers() {
        if (slideState.interval) {
            clearInterval(slideState.interval);
            slideState.interval = null;
        }
    }

    getGroups().forEach(function (root) {
        root.querySelectorAll('.auth-dot').forEach(function (dot, j) {
            dot.addEventListener('click', function () {
                setSlideAll(j);
                stopSlideshowTimers();
                startSlideshowTimers();
            });
        });
        root.addEventListener('mouseenter', stopSlideshowTimers);
        root.addEventListener('mouseleave', function () {
            if (modal.getAttribute('aria-hidden') === 'false') startSlideshowTimers();
        });
    });

    modal.querySelectorAll('.auth-toggle-pw').forEach(function (btn) {
        var id = btn.getAttribute('data-target');
        if (!id) return;
        btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        btn.addEventListener('click', function () {
            var input = document.getElementById(id);
            if (!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    });

    var btnForgot = modal.querySelector('.js-auth-show-forgot');
    if (btnForgot) {
        btnForgot.addEventListener('click', function () {
            resetForgotFlowUI();
            showLoginView('forgot-flow');
        });
    }
    var btnSend = modal.querySelector('.js-forgot-send-code');
    if (btnSend) {
        btnSend.addEventListener('click', function () {
            var inp = document.getElementById('auth-forgot-email');
            if (!inp || !inp.checkValidity()) {
                if (inp) inp.reportValidity();
                return;
            }
            var sent = document.getElementById('auth-forgot-code-sent');
            if (sent) {
                sent.textContent = 'Code sent to ' + maskEmail(inp.value.trim()) + ' (demo).';
                sent.classList.add('is-visible');
            }
            var cb = document.getElementById('auth-forgot-code-block');
            if (cb) cb.classList.add('is-open');
        });
    }
    var btnVerify = modal.querySelector('.js-forgot-verify-code');
    if (btnVerify) {
        btnVerify.addEventListener('click', function () {
            var inp = document.getElementById('auth-forgot-code');
            if (!inp) return;
            var v = inp.value.replace(/\s/g, '');
            if (!/^\d{6}$/.test(v)) {
                inp.setCustomValidity('Enter the 6-digit code.');
                inp.reportValidity();
                inp.setCustomValidity('');
                return;
            }
            showLoginView('forgot-reset');
        });
    }
    var btnSavePw = modal.querySelector('.js-forgot-save-password');
    if (btnSavePw) {
        btnSavePw.addEventListener('click', function () {
            var a = document.getElementById('auth-forgot-new-pw');
            var b = document.getElementById('auth-forgot-new-pw2');
            var hint = document.getElementById('auth-forgot-pw-hint');
            if (!a || !b) return;
            if (!a.checkValidity() || !b.checkValidity()) {
                a.reportValidity();
                return;
            }
            if (a.value !== b.value) {
                if (hint) hint.style.display = 'block';
                return;
            }
            if (hint) hint.style.display = 'none';
            showLoginView('forgot-done');
        });
    }
    modal.querySelectorAll('.js-forgot-to-login').forEach(function (btn) {
        btn.addEventListener('click', function () {
            resetLoginViews();
        });
    });

    var btnBackVerify = modal.querySelector('.js-forgot-back-to-verify');
    if (btnBackVerify) {
        btnBackVerify.addEventListener('click', function () {
            var np = document.getElementById('auth-forgot-new-pw');
            var np2 = document.getElementById('auth-forgot-new-pw2');
            var hint = document.getElementById('auth-forgot-pw-hint');
            if (np) np.value = '';
            if (np2) np2.value = '';
            if (hint) hint.style.display = 'none';
            showLoginView('forgot-flow');
        });
    }

    var startMode = @json($initialAuthMode);
    if (startMode) {
        openModal(startMode);
        var q = new URLSearchParams(window.location.search).get('auth');
        if (q && window.history.replaceState) {
            window.history.replaceState(null, '', window.location.pathname + window.location.hash);
        }
    }
})();
</script>
