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
        /* Full-screen sheet: no rounded corners, no outer padding. */
        .auth-modal {
            padding: 0;
            align-items: stretch;
            justify-content: stretch;
        }
        .auth-modal__backdrop { display: none; }
        .auth-modal__scene {
            width: 100%;
            max-width: 100%;
            margin: 0;
            perspective: none;
        }
        /* Skip the 3D flip on mobile — it was janky on mid-range devices and
           made both faces occupy the same space. Swap with a simple fade. */
        .auth-modal__inner {
            transform: none !important;
            transition: none;
            height: 100vh;
            max-height: 100vh;
            min-height: 0;
            display: flex;
            flex-direction: column;
        }
        @supports (height: 100dvh) {
            .auth-modal__inner { height: 100dvh; max-height: 100dvh; }
        }
        .auth-modal[data-mode="register"] .auth-modal__inner { min-height: 0; }
        /* Show only the active face so the form claims its natural height and
           can scroll inside a predictable container. */
        .auth-modal__face {
            position: static;
            grid-template-columns: 1fr;
            border-radius: 0;
            box-shadow: none;
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
            overflow: hidden;
            transform: none !important;
            backface-visibility: visible;
            -webkit-backface-visibility: visible;
        }
        .auth-modal__face--back {
            grid-template-columns: 1fr;
        }
        .auth-modal[data-mode="login"] .auth-modal__face--back { display: none; }
        .auth-modal[data-mode="register"] .auth-modal__face--front { display: none; }
        /* Compact slideshow locked to the top; form panel takes the rest and
           scrolls on its own so the Log In / Create buttons are always reachable. */
        .auth-slideshow {
            order: -1;
            flex: 0 0 auto;
            height: clamp(150px, 22vh, 210px);
            min-height: 0;
            max-height: none;
        }
        .auth-modal[data-mode="register"] .auth-slideshow {
            height: clamp(140px, 20vh, 190px);
            min-height: 0;
            max-height: none;
        }
        .auth-slide__inner { padding: 0.95rem 1.05rem 1.45rem; }
        .auth-slide__brand img { height: 1.6rem; }
        .auth-slide__brand span { font-size: 1.05rem; }
        .auth-slide h3 { font-size: 1.05rem; margin-bottom: 0.3rem; }
        .auth-slide p { font-size: 0.72rem; line-height: 1.45; }
        .auth-dots {
            bottom: 0.5rem;
            left: 1rem;
            right: 1rem;
        }
        .auth-dot { height: 4px; }

        .auth-form-panel {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            padding: 1.5rem 1.15rem calc(1.75rem + env(safe-area-inset-bottom, 0px));
            justify-content: flex-start;
        }
        .auth-modal__face--front .auth-form-panel,
        .auth-modal__face--back .auth-form-panel { justify-content: flex-start; }
        .auth-form-panel h2 {
            font-size: 1.55rem;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
        }
        .auth-sub {
            margin-bottom: 1.2rem;
            font-size: 0.85rem;
            line-height: 1.5;
        }
        .auth-close {
            top: 0.6rem;
            right: 0.6rem;
            width: 44px;
            height: 44px;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            z-index: 5;
        }
        .auth-close:hover { background: rgba(0, 0, 0, 0.7); }
        .auth-close svg { width: 20px; height: 20px; }
        .auth-field { margin-bottom: 0.9rem; }
        .auth-field label {
            font-size: 0.78rem;
            margin-bottom: 0.4rem;
        }
        /* 16px input font-size prevents the iOS auto-zoom on focus. */
        .auth-input-wrap input {
            padding: 0.9rem 1rem;
            padding-right: 3rem;
            font-size: 1rem;
            min-height: 52px;
            border-radius: 12px;
        }
        .auth-register-form .auth-input-wrap input {
            min-height: 52px;
            border-radius: 999px;
        }
        .auth-submit,
        .auth-send-code-btn {
            min-height: 52px;
            padding-top: 0.95rem;
            padding-bottom: 0.95rem;
            font-size: 0.95rem;
        }
        .auth-toggle-pw {
            width: 46px;
            height: 46px;
        }
        .auth-field-row { gap: 0.65rem; }
        .auth-forgot { margin-bottom: 1.1rem; }
        .auth-forgot a,
        .auth-forgot button { font-size: 0.82rem; padding: 0.35rem 0; }
        .auth-phone-wrap { border-radius: 12px; }
        .auth-phone-prefix { padding: 0.9rem 1rem; font-size: 1rem; }
        .auth-checkbox-row { padding: 0.25rem 0; font-size: 0.85rem; }
        .auth-checkbox-row input {
            width: 1.2rem;
            height: 1.2rem;
            margin-top: 0.1rem;
        }
        .auth-inline-row { gap: 0.5rem; }
        .auth-inline-btn {
            padding: 0 1.1rem;
            min-height: 52px;
            font-size: 0.82rem;
        }
        .auth-channel-choice {
            padding: 0.7rem 0.8rem;
        }
        .auth-channel-choice label {
            padding: 0.4rem 0;
            min-height: 36px;
        }
        .auth-password-meter { margin-top: 0.55rem; }
        .auth-password-meter__head { font-size: 0.72rem; }
        .auth-password-meter__hint { font-size: 0.74rem; }
        .auth-code-input { font-size: 1.15rem; letter-spacing: 0.3em; }
    }

    /* Very small phones (iPhone SE, older Android handsets). */
    @media (max-width: 380px) {
        .auth-slideshow { height: clamp(130px, 18vh, 180px); }
        .auth-form-panel { padding: 1.3rem 0.95rem calc(1.5rem + env(safe-area-inset-bottom, 0px)); }
        .auth-form-panel h2 { font-size: 1.4rem; }
        .auth-slide h3 { font-size: 1rem; }
        .auth-slide p { font-size: 0.68rem; }
        .auth-field-row { grid-template-columns: 1fr; gap: 0.45rem; }
    }

    /* Landscape phones: keep the hero small so the form stays visible. */
    @media (max-height: 480px) and (orientation: landscape) {
        .auth-slideshow { height: clamp(110px, 30vh, 150px); }
        .auth-form-panel { padding-top: 1rem; padding-bottom: 1.25rem; }
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
    .auth-slide__brand { display: flex; align-items: center; gap: 0.625rem; }
    .auth-slide__brand img { height: 2.5rem; width: auto; object-fit: contain; flex-shrink: 0; }
    .auth-slide__brand span { font-weight: 700; font-size: 1.5rem; color: #fff; letter-spacing: -0.02em; }
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
    .auth-toggle-pw svg { overflow: visible; }
    .auth-toggle-pw .eye-pupil {
        transform-origin: 12px 12px;
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .auth-toggle-pw .eye-slash {
        opacity: 0;
        stroke-dasharray: 18;
        stroke-dashoffset: 18;
        transition: opacity 0.18s ease, stroke-dashoffset 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .auth-toggle-pw.is-showing {
        color: #fff;
    }
    .auth-toggle-pw.is-showing .eye-pupil {
        transform: scale(1.35);
    }
    .auth-toggle-pw.is-showing .eye-slash {
        opacity: 1;
        stroke-dashoffset: 0;
    }
    .auth-toggle-pw.is-animating svg {
        animation: authEyeBlink 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes authEyeBlink {
        0%, 100% { transform: scaleY(1); }
        45% { transform: scaleY(0.68); }
    }
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
    .auth-submit:hover:not(:disabled) {
        background: #b89a75;
        box-shadow: 0 10px 28px rgba(166, 139, 103, 0.32);
        transform: translateY(-1px);
    }
    .auth-submit:disabled {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.3);
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }
    /* Register form: pill inputs + layout */
    .auth-register-form .auth-input-wrap input {
        border-radius: 999px;
        padding-left: 1rem;
        padding-right: 2.65rem;
    }
    .auth-password-meter {
        margin-top: 0.45rem;
        text-align: left;
    }
    .auth-password-meter__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.7rem;
        color: #9ca3af;
        margin-bottom: 0.35rem;
    }
    .auth-password-meter__label {
        color: #9ca3af;
    }
    .auth-password-meter__value {
        color: #6b7280;
        font-weight: 600;
        letter-spacing: 0.01em;
    }
    .auth-password-meter__track {
        width: 100%;
        height: 0.36rem;
        border-radius: 999px;
        background: #1f2937;
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
    }
    .auth-password-meter__fill {
        width: 0%;
        height: 100%;
        border-radius: inherit;
        transition: width 0.2s ease, background-color 0.2s ease;
        background: #6b7280;
    }
    .auth-password-meter__hint {
        margin-top: 0.3rem;
        font-size: 0.72rem;
        color: #6b7280;
        line-height: 1.35;
    }
    .auth-password-meter.is-weak .auth-password-meter__value,
    .auth-password-meter.is-weak .auth-password-meter__hint {
        color: #f87171;
    }
    .auth-password-meter.is-medium .auth-password-meter__value,
    .auth-password-meter.is-medium .auth-password-meter__hint {
        color: #fbbf24;
    }
    .auth-password-meter.is-strong .auth-password-meter__value,
    .auth-password-meter.is-strong .auth-password-meter__hint {
        color: #34d399;
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
    .auth-phone-wrap {
        display: flex;
        align-items: center;
        gap: 0;
        border-radius: 999px;
        border: 1px solid #2a2a2a;
        background: #1a1a1a;
        overflow: hidden;
    }
    .auth-phone-prefix {
        flex-shrink: 0;
        padding: 0.65rem 0.9rem;
        color: #e5e7eb;
        font-size: 0.875rem;
        border-right: 1px solid #2a2a2a;
        background: #151515;
    }
    .auth-phone-wrap input {
        width: 100%;
        min-width: 0;
        border: none !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        padding-left: 0.75rem !important;
        padding-right: 0.9rem !important;
        color: #f5f5f5;
        font-size: 0.875rem;
        outline: none;
    }
    .auth-phone-wrap:focus-within {
        border-color: rgba(166, 139, 103, 0.45);
        box-shadow: 0 0 0 3px rgba(166, 139, 103, 0.12);
    }
    .auth-channel-choice {
        margin-bottom: 0.8rem;
        text-align: left;
        border: 1px solid #2a2a2a;
        border-radius: 0.65rem;
        background: #171717;
        padding: 0.55rem 0.65rem;
    }
    .auth-channel-choice legend {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-bottom: 0.4rem;
    }
    .auth-channel-choice label {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.35rem;
        font-size: 0.8125rem;
        color: #e5e7eb;
        cursor: pointer;
    }
    .auth-channel-choice label:last-child {
        margin-bottom: 0;
    }
    .auth-channel-choice input[type="radio"] {
        accent-color: #A68B67;
    }
    .auth-send-code-row {
        margin-bottom: 0.85rem;
        text-align: left;
    }
    .auth-send-code-btn {
        width: 100%;
        border: 1px solid rgba(166, 139, 103, 0.45);
        background: rgba(166, 139, 103, 0.12);
        color: #f5f5f5;
        border-radius: 999px;
        font-size: 0.8125rem;
        font-weight: 600;
        padding: 0.58rem 0.95rem;
        cursor: pointer;
    }
    .auth-send-code-btn:hover {
        background: rgba(166, 139, 103, 0.2);
    }
    .auth-send-code-status {
        margin-top: 0.35rem;
        font-size: 0.74rem;
        color: #9ca3af;
        line-height: 1.35;
    }
    .auth-verify-note {
        margin-bottom: 0.7rem;
        text-align: left;
        font-size: 0.74rem;
        line-height: 1.45;
        color: #9ca3af;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 0.55rem;
        padding: 0.48rem 0.62rem;
    }
    .auth-verify-note strong {
        color: #e5e7eb;
    }
    @media (prefers-reduced-motion: reduce) {
        .auth-modal__inner { transition: none; }
        .auth-slide { transition: none; }
        .auth-progress-fill.is-animating { animation: none; width: 100%; }
        .auth-toggle-pw,
        .auth-toggle-pw .eye-pupil,
        .auth-toggle-pw .eye-slash {
            transition: none;
        }
        .auth-toggle-pw.is-animating svg { animation: none; }
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
                                        <button type="button" class="auth-toggle-pw" data-target="auth-password" aria-label="Show password" aria-pressed="false">…</button>
                                    </div>
                                </div>
                                <div class="auth-forgot"><button type="button" class="js-auth-show-forgot">Forgot password?</button></div>
                                <button type="submit" class="auth-submit">Log In</button>
                            </form>
                        </div>

                        <div class="auth-login-view" data-login-view="forgot-flow" role="tabpanel">
                            <h2 id="auth-forgot-title">Forgot password</h2>
                            <p class="auth-sub" id="auth-forgot-subtext">Enter your email, tap <strong style="color:#e5e7eb;font-weight:600">Send</strong>, then enter the code.</p>
                            <p class="auth-forgot-demo" id="auth-forgot-format-hint"></p>
                            <p class="auth-sub auth-forgot-sent" id="auth-forgot-code-sent" aria-live="polite"></p>

                            <div id="auth-forgot-card">
                                <div class="auth-field">
                                    <label for="auth-forgot-identifier" id="auth-forgot-identifier-label">Email</label>
                                    <div class="auth-inline-row">
                                        <div class="auth-input-wrap">
                                            <input id="auth-forgot-identifier" type="email" autocomplete="email" placeholder="you@example.com" required>
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

                            <div class="auth-back-row"><button type="button" class="auth-back-link js-forgot-toggle-channel">Use phone number instead</button></div>
                            <div class="auth-back-row"><button type="button" class="auth-back-link js-forgot-to-login">← Back to log in</button></div>
                        </div>

                        <div class="auth-login-view auth-login-view--reset-pw" data-login-view="forgot-reset" role="tabpanel">
                            <h2 id="auth-forgot-reset-title">Set your new password</h2>
                            <p class="auth-sub">Choose a strong password you’ll remember.</p>
                            <div class="auth-field">
                                <label for="auth-forgot-new-pw">New password</label>
                                <div class="auth-input-wrap">
                                    <input id="auth-forgot-new-pw" type="password" autocomplete="new-password" placeholder="••••••••" required minlength="8">
                                    <button type="button" class="auth-toggle-pw" data-target="auth-forgot-new-pw" aria-label="Show password" aria-pressed="false">…</button>
                                </div>
                            </div>
                            <div class="auth-field">
                                <label for="auth-forgot-new-pw2">Confirm password</label>
                                <div class="auth-input-wrap">
                                    <input id="auth-forgot-new-pw2" type="password" autocomplete="new-password" placeholder="••••••••" required minlength="8">
                                    <button type="button" class="auth-toggle-pw" data-target="auth-forgot-new-pw2" aria-label="Show password" aria-pressed="false">…</button>
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
                                <p>You’re all set. Your password has been reset.</p>
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
                    <div class="auth-login-views">
                        <div class="auth-login-view is-active" data-login-view="register-form" role="tabpanel">
                            <button type="button" class="auth-close js-auth-close" aria-label="Close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
                            <h2 id="auth-register-title">Create an account</h2>
                            <p class="auth-sub">Already have an account? <button type="button" class="auth-flip-link js-auth-flip" data-flip="login">Log in</button></p>

                            <div class="auth-errors" id="register-errors" style="display:none;" role="alert"><ul></ul></div>

                            <form id="auth-register-form" class="auth-register-form">
                                @csrf
                                <div class="auth-field-row">
                                    <div class="auth-field">
                                        <label for="auth-reg-first">First name</label>
                                        <div class="auth-input-wrap">
                                            <input id="auth-reg-first" name="first_name" type="text" autocomplete="given-name" placeholder="First name" required>
                                        </div>
                                    </div>
                                    <div class="auth-field">
                                        <label for="auth-reg-last">Last name</label>
                                        <div class="auth-input-wrap">
                                            <input id="auth-reg-last" name="last_name" type="text" autocomplete="family-name" placeholder="Last name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="auth-field">
                                    <label for="auth-reg-email">Email</label>
                                    <div class="auth-input-wrap">
                                        <input id="auth-reg-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
                                    </div>
                                </div>
                                <div class="auth-field">
                                    <label for="auth-reg-phone">Phone number</label>
                                    <input type="hidden" name="phone" id="auth-reg-phone" value="+63">
                                    <div class="auth-phone-wrap">
                                        <span class="auth-phone-prefix">+63</span>
                                        <input id="auth-reg-phone-local" type="tel" autocomplete="tel-national" placeholder="9XXXXXXXXX" pattern="^9\d{9}$" maxlength="10" inputmode="numeric" title="Enter 10 digits starting with 9" required>
                                    </div>
                                </div>
                                <div class="auth-field">
                                    <label for="auth-reg-password">Password</label>
                                    <div class="auth-input-wrap">
                                        <input id="auth-reg-password" name="password" type="password" autocomplete="new-password" placeholder="••••••••" required minlength="8">
                                        <button type="button" class="auth-toggle-pw" data-target="auth-reg-password" aria-label="Show password" aria-pressed="false">…</button>
                                    </div>
                                    <div class="auth-password-meter" id="auth-password-meter" aria-live="polite">
                                        <div class="auth-password-meter__head">
                                            <span class="auth-password-meter__label">Password strength:</span>
                                            <span class="auth-password-meter__value" id="auth-password-meter-value">Enter password</span>
                                        </div>
                                        <div class="auth-password-meter__track" aria-hidden="true">
                                            <div class="auth-password-meter__fill" id="auth-password-meter-fill"></div>
                                        </div>
                                        <p class="auth-password-meter__hint" id="auth-password-meter-hint">Use 8+ chars with letters, numbers, and symbols.</p>
                                    </div>
                                </div>
                                <label class="auth-checkbox-row">
                                    <input type="checkbox" id="auth-reg-confirm" name="confirm_details" value="1" required>
                                    <span>I confirm all the details are correct</span>
                                </label>
                                <button type="submit" class="auth-submit" id="btn-register-submit" style="margin-top:0.15rem" disabled>Create Account</button>
                            </form>
                        </div>
                        
                        <div class="auth-login-view" data-login-view="register-verify" role="tabpanel">
                            <button type="button" class="auth-close js-auth-close" aria-label="Close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
                            <h2>Verify your account</h2>
                            <p class="auth-sub" style="margin-bottom:0.5rem">Choose where to send your 6-digit code.</p>
                            <p class="auth-sub">Destination: <strong id="verify-destination-display" style="color:#fff"></strong></p>

                            <div class="auth-errors" id="verify-errors" style="display:none;" role="alert"><ul></ul></div>

                            <fieldset class="auth-channel-choice">
                                <legend>Send code via</legend>
                                <label>
                                    <input type="radio" name="verify_channel_pick" value="email" checked>
                                    <span>Email (<span id="verify-email-display"></span>)</span>
                                </label>
                                <label>
                                    <input type="radio" name="verify_channel_pick" value="sms">
                                    <span>Phone (<span id="verify-phone-display"></span>)</span>
                                </label>
                            </fieldset>
                            <div class="auth-send-code-row">
                                <button type="button" class="auth-send-code-btn" id="btn-send-verify-code">Send code</button>
                                <p class="auth-send-code-status" id="verify-send-status"></p>
                            </div>
                            <p class="auth-verify-note" id="verify-channel-note">
                                <strong>Recommendation:</strong> Use email for faster verification. SMS may be delayed by carrier traffic, network signal, or OTP filtering.
                            </p>

                            <form id="auth-verify-form">
                                @csrf
                                <input type="hidden" name="email" id="verify-email-input">
                                <input type="hidden" name="phone" id="verify-phone-input">
                                <input type="hidden" name="channel" id="verify-channel-input" value="email">
                                <div class="auth-field">
                                    <label for="auth-verify-code">Verification code</label>
                                    <div class="auth-input-wrap">
                                        <input id="auth-verify-code" name="code" class="auth-code-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="000000" autocomplete="one-time-code" required>
                                    </div>
                                </div>
                                <button type="submit" class="auth-submit" id="btn-verify-submit" style="margin-top:0.75rem" disabled>Verify & Login</button>
                            </form>
                        </div>
                    </div>
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
        var isBackFace = name.startsWith('register');
        var faceSelector = isBackFace ? '.auth-modal__face--back' : '.auth-modal__face--front';
        var face = modal.querySelector(faceSelector);
        if (face) {
            face.querySelectorAll('.auth-login-view').forEach(function (el) {
                el.classList.toggle('is-active', el.getAttribute('data-login-view') === name);
            });
        }
    }

    function resetForgotFlowUI() {
        var cb = document.getElementById('auth-forgot-code-block');
        var sent = document.getElementById('auth-forgot-code-sent');
        if (cb) cb.classList.remove('is-open');
        if (sent) {
            sent.classList.remove('is-visible');
            sent.textContent = '';
        }
        var fe = document.getElementById('auth-forgot-identifier');
        var fc = document.getElementById('auth-forgot-code');
        var np = document.getElementById('auth-forgot-new-pw');
        var np2 = document.getElementById('auth-forgot-new-pw2');
        if (fe) fe.value = '';
        if (fc) fc.value = '';
        if (np) np.value = '';
        if (np2) np2.value = '';
        var hint = document.getElementById('auth-forgot-pw-hint');
        if (hint) hint.style.display = 'none';
        applyForgotChannel('email');
    }

    function resetLoginViews() {
        showLoginView('login');
        showLoginView('register-form');
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

    function maskPhone(phone) {
        var digits = (phone || '').replace(/\D/g, '');
        if (digits.length < 8) return phone || '';
        var tail = digits.slice(-4);
        return '••••••' + tail;
    }

    var forgotChannel = 'email';
    function applyForgotChannel(channel) {
        forgotChannel = channel === 'sms' ? 'sms' : 'email';
        var input = document.getElementById('auth-forgot-identifier');
        var label = document.getElementById('auth-forgot-identifier-label');
        var sub = document.getElementById('auth-forgot-subtext');
        var hint = document.getElementById('auth-forgot-format-hint');
        var toggleBtn = modal.querySelector('.js-forgot-toggle-channel');
        if (!input || !label || !sub || !hint || !toggleBtn) return;

        input.value = '';
        if (forgotChannel === 'sms') {
            label.textContent = 'Phone number';
            input.type = 'tel';
            input.autocomplete = 'tel';
            input.placeholder = '+639123456789';
            sub.innerHTML = 'Enter your phone number, tap <strong style="color:#e5e7eb;font-weight:600">Send</strong>, then enter the SMS code.';
            hint.textContent = 'Use PH format: +639XXXXXXXXX';
            toggleBtn.textContent = 'Use email instead';
        } else {
            label.textContent = 'Email';
            input.type = 'email';
            input.autocomplete = 'email';
            input.placeholder = 'you@example.com';
            sub.innerHTML = 'Enter your email, tap <strong style="color:#e5e7eb;font-weight:600">Send</strong>, then enter the code.';
            hint.textContent = '';
            toggleBtn.textContent = 'Use phone number instead';
        }
    }

    function openModal(mode) {
        modal.setAttribute('aria-hidden', 'false');
        modal.setAttribute('data-mode', mode === 'register' ? 'register' : 'login');
        document.body.style.overflow = 'hidden';
        resetLoginViews();
        restartAllProgress();
        startSlideshowTimers();
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        modal.setAttribute('data-mode', 'login');
        document.body.style.overflow = '';
        stopSlideshowTimers();
        clearVerifyCooldown();
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
        btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle class="eye-pupil" cx="12" cy="12" r="3"/><path class="eye-slash" d="M4 4l16 16" stroke-linecap="round"/></svg>';
        btn.addEventListener('click', function () {
            var input = document.getElementById(id);
            if (!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('is-showing', show);
            btn.classList.remove('is-animating');
            void btn.offsetWidth;
            btn.classList.add('is-animating');
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
        });
        btn.addEventListener('animationend', function () {
            btn.classList.remove('is-animating');
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
            var inp = document.getElementById('auth-forgot-identifier');
            var value = inp ? inp.value.trim() : '';
            if (!inp) return;
            if (forgotChannel === 'sms' && !/^\+639\d{9}$/.test(value)) {
                inp.setCustomValidity('Use format +639XXXXXXXXX.');
                inp.reportValidity();
                inp.setCustomValidity('');
                return;
            }
            if (forgotChannel === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                inp.setCustomValidity('Enter a valid email address.');
                inp.reportValidity();
                inp.setCustomValidity('');
                return;
            }

            btnSend.disabled = true;
            var oldText = btnSend.textContent;
            btnSend.textContent = 'Sending...';

            var tokenInput = modal.querySelector('#auth-login-form input[name="_token"]');
            var fd = new FormData();
            fd.append('_token', tokenInput ? tokenInput.value : '');
            fd.append('channel', forgotChannel);
            if (forgotChannel === 'sms') {
                fd.append('phone', value);
            } else {
                fd.append('email', value);
            }

            fetch(@json(url('/forgot-password/send-code')), {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            }).then(function (r) {
                return r.json().then(function (data) {
                    return { status: r.status, body: data };
                });
            }).then(function (res) {
                btnSend.disabled = false;
                btnSend.textContent = oldText;
                var sent = document.getElementById('auth-forgot-code-sent');
                if (res.status === 200 && res.body.success) {
                    if (sent) {
                        sent.textContent = forgotChannel === 'sms'
                            ? ('Code sent to ' + maskPhone(value) + '.')
                            : ('Code sent to ' + maskEmail(value) + '.');
                        sent.classList.add('is-visible');
                    }
                    var cb = document.getElementById('auth-forgot-code-block');
                    if (cb) cb.classList.add('is-open');
                    return;
                }
                if (sent) {
                    sent.textContent = res.body.message || 'Could not send code.';
                    sent.classList.add('is-visible');
                }
            }).catch(function () {
                btnSend.disabled = false;
                btnSend.textContent = oldText;
                var sent = document.getElementById('auth-forgot-code-sent');
                if (sent) {
                    sent.textContent = 'Could not send code right now.';
                    sent.classList.add('is-visible');
                }
            });
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
    var btnForgotToggle = modal.querySelector('.js-forgot-toggle-channel');
    if (btnForgotToggle) {
        btnForgotToggle.addEventListener('click', function () {
            applyForgotChannel(forgotChannel === 'email' ? 'sms' : 'email');
        });
    }
    var btnSavePw = modal.querySelector('.js-forgot-save-password');
    if (btnSavePw) {
        btnSavePw.addEventListener('click', function () {
            var a = document.getElementById('auth-forgot-new-pw');
            var b = document.getElementById('auth-forgot-new-pw2');
            var identifierInput = document.getElementById('auth-forgot-identifier');
            var codeInput = document.getElementById('auth-forgot-code');
            var hint = document.getElementById('auth-forgot-pw-hint');
            if (!a || !b || !identifierInput || !codeInput) return;
            if (!a.checkValidity() || !b.checkValidity()) {
                a.reportValidity();
                return;
            }
            if (a.value !== b.value) {
                if (hint) hint.style.display = 'block';
                return;
            }
            var identifierValue = identifierInput.value.trim();
            var codeValue = codeInput.value.replace(/\s/g, '');
            if (forgotChannel === 'sms' && !/^\+639\d{9}$/.test(identifierValue)) {
                identifierInput.setCustomValidity('Use format +639XXXXXXXXX.');
                identifierInput.reportValidity();
                identifierInput.setCustomValidity('');
                return;
            }
            if (forgotChannel === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(identifierValue)) {
                identifierInput.setCustomValidity('Enter a valid email address.');
                identifierInput.reportValidity();
                identifierInput.setCustomValidity('');
                return;
            }
            if (!/^\d{6}$/.test(codeValue)) {
                codeInput.setCustomValidity('Enter the 6-digit code.');
                codeInput.reportValidity();
                codeInput.setCustomValidity('');
                return;
            }

            if (hint) hint.style.display = 'none';
            btnSavePw.disabled = true;
            var oldText = btnSavePw.textContent;
            btnSavePw.textContent = 'Updating...';

            var tokenInput = modal.querySelector('#auth-login-form input[name="_token"]');
            var fd = new FormData();
            fd.append('_token', tokenInput ? tokenInput.value : '');
            fd.append('channel', forgotChannel);
            if (forgotChannel === 'sms') {
                fd.append('phone', identifierValue);
            } else {
                fd.append('email', identifierValue);
            }
            fd.append('code', codeValue);
            fd.append('password', a.value);
            fd.append('password_confirmation', b.value);

            fetch(@json(url('/forgot-password/reset')), {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            }).then(function (r) {
                return r.json().then(function (data) {
                    return { status: r.status, body: data };
                });
            }).then(function (res) {
                btnSavePw.disabled = false;
                btnSavePw.textContent = oldText;
                if (res.status === 200 && res.body.success) {
                    showLoginView('forgot-done');
                    return;
                }
                if (hint) {
                    hint.textContent = res.body.message || 'Could not update password.';
                    hint.style.display = 'block';
                }
            }).catch(function () {
                btnSavePw.disabled = false;
                btnSavePw.textContent = oldText;
                if (hint) {
                    hint.textContent = 'Could not update password right now.';
                    hint.style.display = 'block';
                }
            });
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

    // --- AJAX AUTHENTICATION LOGIC ---
    function handleFormErrors(formId, responseData) {
        var errContainer = null;
        if (formId === 'auth-register-form') errContainer = document.getElementById('register-errors');
        if (formId === 'auth-verify-form') errContainer = document.getElementById('verify-errors');
        if (formId === 'auth-login-form') {
            errContainer = document.createElement('div');
            errContainer.className = 'auth-errors';
            errContainer.role = 'alert';
            errContainer.innerHTML = '<ul></ul>';
            var form = document.getElementById(formId);
            var prevErr = form.parentElement.querySelector('.auth-errors');
            if (prevErr) prevErr.remove();
            form.parentNode.insertBefore(errContainer, form);
        }

        if (errContainer) {
            errContainer.style.display = 'block';
            var ul = errContainer.querySelector('ul');
            ul.innerHTML = '';
            if (responseData.errors) {
                for (var key in responseData.errors) {
                    responseData.errors[key].forEach(function(msg) {
                        var li = document.createElement('li');
                        li.textContent = msg;
                        ul.appendChild(li);
                    });
                }
            } else if (responseData.message) {
                var li = document.createElement('li');
                li.textContent = responseData.message;
                ul.appendChild(li);
            }
        }
    }

    var loginForm = document.getElementById('auth-login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = loginForm.querySelector('.auth-submit');
            var originalText = btn.textContent;
            btn.textContent = 'Wait...';
            btn.disabled = true;

            var fd = new FormData(loginForm);
            fetch(@json(url('/login')), {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            }).then(r => r.json().then(data => ({status: r.status, body: data})))
              .then(res => {
                  btn.textContent = originalText;
                  btn.disabled = false;
                  if (res.status === 200 && res.body.success) {
                      window.location.href = res.body.redirect;
                  } else {
                      handleFormErrors('auth-login-form', res.body);
                  }
              }).catch(err => {
                  btn.textContent = originalText;
                  btn.disabled = false;
              });
        });
    }

    var cbConfirm = document.getElementById('auth-reg-confirm');
    var btnReg = document.getElementById('btn-register-submit');
    var registerPasswordInput = document.getElementById('auth-reg-password');
    var passwordMeter = document.getElementById('auth-password-meter');
    var passwordMeterValue = document.getElementById('auth-password-meter-value');
    var passwordMeterFill = document.getElementById('auth-password-meter-fill');
    var passwordMeterHint = document.getElementById('auth-password-meter-hint');

    function scorePassword(value) {
        var hasLower = /[a-z]/.test(value);
        var hasUpper = /[A-Z]/.test(value);
        var hasDigit = /\d/.test(value);
        var hasSymbol = /[^A-Za-z0-9]/.test(value);
        var longEnough = value.length >= 8;
        var veryLong = value.length >= 12;
        var variety = 0;
        if (hasLower) variety++;
        if (hasUpper) variety++;
        if (hasDigit) variety++;
        if (hasSymbol) variety++;

        var score = 0;
        if (longEnough) score += 1;
        score += Math.min(variety, 3);
        if (veryLong && variety >= 3) score += 1;

        return {
            score: Math.max(0, Math.min(score, 5)),
            hasLower: hasLower,
            hasUpper: hasUpper,
            hasDigit: hasDigit,
            hasSymbol: hasSymbol,
            longEnough: longEnough
        };
    }

    function renderPasswordMeter() {
        if (!registerPasswordInput || !passwordMeter || !passwordMeterValue || !passwordMeterFill || !passwordMeterHint) return;

        var value = registerPasswordInput.value || '';
        var details = scorePassword(value);

        passwordMeter.classList.remove('is-weak', 'is-medium', 'is-strong');

        if (!value.length) {
            passwordMeterValue.textContent = 'Enter password';
            passwordMeterHint.textContent = 'Use 8+ chars with letters, numbers, and symbols.';
            passwordMeterFill.style.width = '0%';
            passwordMeterFill.style.backgroundColor = '#6b7280';
            return;
        }

        var percent = details.score * 20;
        passwordMeterFill.style.width = percent + '%';

        if (!details.longEnough || details.score <= 2) {
            passwordMeter.classList.add('is-weak');
            passwordMeterValue.textContent = 'Weak';
            passwordMeterHint.textContent = 'Add at least 8 chars and mix letters, numbers, and symbols.';
            passwordMeterFill.style.backgroundColor = '#ef4444';
            return;
        }

        if (details.score <= 3) {
            passwordMeter.classList.add('is-medium');
            passwordMeterValue.textContent = 'Medium';
            passwordMeterHint.textContent = 'Good start. Add uppercase letters or symbols to make it stronger.';
            passwordMeterFill.style.backgroundColor = '#f59e0b';
            return;
        }

        passwordMeter.classList.add('is-strong');
        passwordMeterValue.textContent = 'Strong';
        passwordMeterHint.textContent = 'Great password strength.';
        passwordMeterFill.style.backgroundColor = '#10b981';
    }

    if (registerPasswordInput) {
        registerPasswordInput.addEventListener('input', renderPasswordMeter);
        renderPasswordMeter();
    }

    if (cbConfirm && btnReg) {
        cbConfirm.addEventListener('change', function() {
            btnReg.disabled = !this.checked;
        });
    }


    var registerForm = document.getElementById('auth-register-form');
    var registerPhoneFullInput = document.getElementById('auth-reg-phone');
    var registerPhoneLocalInput = document.getElementById('auth-reg-phone-local');
    var btnSendVerifyCode = document.getElementById('btn-send-verify-code');
    var verifySendStatus = document.getElementById('verify-send-status');
    var verifyChannelInput = document.getElementById('verify-channel-input');
    var verifyDestinationDisplay = document.getElementById('verify-destination-display');
    var verifyEmailInput = document.getElementById('verify-email-input');
    var verifyPhoneInput = document.getElementById('verify-phone-input');
    var verifyCodeInput = document.getElementById('auth-verify-code');
    var btnVerifySubmit = document.getElementById('btn-verify-submit');
    var verifyEmailDisplay = document.getElementById('verify-email-display');
    var verifyPhoneDisplay = document.getElementById('verify-phone-display');
    var verifyChannelNote = document.getElementById('verify-channel-note');
    var verifyChannelRadios = modal.querySelectorAll('input[name="verify_channel_pick"]');
    var hasSentVerificationCode = false;
    var verifyCooldownTimer = null;
    var verifyCooldownRemaining = 0;

    function clearVerifyCooldown() {
        if (verifyCooldownTimer) {
            clearInterval(verifyCooldownTimer);
            verifyCooldownTimer = null;
        }
        verifyCooldownRemaining = 0;
        if (btnSendVerifyCode) {
            btnSendVerifyCode.disabled = false;
            btnSendVerifyCode.textContent = 'Send code';
        }
    }

    function startVerifyCooldown(seconds) {
        clearVerifyCooldown();
        verifyCooldownRemaining = Number(seconds) || 60;
        if (!btnSendVerifyCode) return;
        btnSendVerifyCode.disabled = true;
        btnSendVerifyCode.textContent = 'Send again in ' + verifyCooldownRemaining + 's';
        verifyCooldownTimer = setInterval(function () {
            verifyCooldownRemaining -= 1;
            if (verifyCooldownRemaining <= 0) {
                clearVerifyCooldown();
                return;
            }
            btnSendVerifyCode.textContent = 'Send again in ' + verifyCooldownRemaining + 's';
        }, 1000);
    }

    function refreshVerifySubmitState() {
        if (!btnVerifySubmit) return;
        var codeValue = verifyCodeInput ? verifyCodeInput.value.replace(/\s/g, '') : '';
        var codeLooksValid = /^\d{6}$/.test(codeValue);
        btnVerifySubmit.disabled = !(hasSentVerificationCode && codeLooksValid);
    }

    if (verifyCodeInput) {
        verifyCodeInput.addEventListener('input', refreshVerifySubmitState);
    }

    function selectedVerifyChannel() {
        var checked = modal.querySelector('input[name="verify_channel_pick"]:checked');
        return checked ? checked.value : 'email';
    }

    function syncVerifyChannelUI() {
        var channel = selectedVerifyChannel();
        if (verifyChannelInput) verifyChannelInput.value = channel;
        if (verifyDestinationDisplay) {
            verifyDestinationDisplay.textContent = channel === 'sms'
                ? (verifyPhoneDisplay ? verifyPhoneDisplay.textContent : '')
                : (verifyEmailDisplay ? verifyEmailDisplay.textContent : '');
        }
        if (verifyChannelNote) {
            verifyChannelNote.innerHTML = channel === 'sms'
                ? '<strong>SMS note:</strong> Delivery can take longer due to carrier routing, weak signal, or temporary network congestion. If delayed, switch to email for faster verification.'
                : '<strong>Recommendation:</strong> Email is usually the fastest for verification. Use SMS as backup when needed.';
        }
        if (verifySendStatus && verifySendStatus.textContent) {
            verifySendStatus.textContent = '';
        }
        refreshVerifySubmitState();
    }

    verifyChannelRadios.forEach(function (radio) {
        radio.addEventListener('change', syncVerifyChannelUI);
    });

    if (btnSendVerifyCode) {
        btnSendVerifyCode.addEventListener('click', function () {
            if (!verifyEmailInput || !verifyEmailInput.value) return;

            var channel = selectedVerifyChannel();
            if (verifyChannelInput) verifyChannelInput.value = channel;

            btnSendVerifyCode.disabled = true;
            var original = btnSendVerifyCode.textContent;
            btnSendVerifyCode.textContent = 'Sending...';
            if (verifySendStatus) verifySendStatus.textContent = '';

            var fd = new FormData();
            fd.append('_token', modal.querySelector('#auth-verify-form input[name="_token"]').value);
            fd.append('email', verifyEmailInput.value);
            fd.append('channel', channel);

            fetch(@json(url('/verification/send-code')), {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            }).then(r => r.json().then(data => ({status: r.status, body: data})))
              .then(function (res) {
                  if (res.status === 200 && res.body.success) {
                      hasSentVerificationCode = true;
                      refreshVerifySubmitState();
                      if (verifySendStatus) verifySendStatus.textContent = res.body.message || 'Code sent.';
                      startVerifyCooldown(res.body.cooldown_seconds || 60);
                      return;
                  }
                  btnSendVerifyCode.disabled = false;
                  btnSendVerifyCode.textContent = original;
                  if (res.status === 429 && res.body && typeof res.body.seconds_remaining !== 'undefined') {
                      if (verifySendStatus) verifySendStatus.textContent = res.body.message || 'Please wait before sending again.';
                      startVerifyCooldown(res.body.seconds_remaining);
                      return;
                  }
                  refreshVerifySubmitState();
                  handleFormErrors('auth-verify-form', res.body || {message: 'Could not send code.'});
              }).catch(function () {
                  btnSendVerifyCode.disabled = false;
                  btnSendVerifyCode.textContent = original;
                  refreshVerifySubmitState();
                  if (verifySendStatus) verifySendStatus.textContent = 'Could not send code right now.';
              });
        });
    }

    if (registerPhoneLocalInput) {
        registerPhoneLocalInput.addEventListener('input', function () {
            var digits = registerPhoneLocalInput.value.replace(/\D/g, '').slice(0, 10);
            registerPhoneLocalInput.value = digits;
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var errContainer = document.getElementById('register-errors');
            if (errContainer) errContainer.style.display = 'none';

            if (!registerPhoneLocalInput || !registerPhoneFullInput) {
                handleFormErrors('auth-register-form', { message: 'Phone number input is not ready. Please refresh and try again.' });
                return;
            }

            var localDigits = registerPhoneLocalInput.value.replace(/\D/g, '').slice(0, 10);
            registerPhoneLocalInput.value = localDigits;
            if (!/^9\d{9}$/.test(localDigits)) {
                registerPhoneLocalInput.setCustomValidity('Enter a valid PH mobile number starting with 9.');
                registerPhoneLocalInput.reportValidity();
                registerPhoneLocalInput.setCustomValidity('');
                return;
            }

            registerPhoneFullInput.value = '+63' + localDigits;

            var originalText = btnReg.textContent;
            btnReg.textContent = 'Sending code...';
            btnReg.disabled = true;

            var fd = new FormData(registerForm);
            fetch(@json(url('/register')), {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            }).then(r => r.json().then(data => ({status: r.status, body: data})))
              .then(res => {
                  btnReg.textContent = originalText;
                  btnReg.disabled = false;
                  if (res.status === 200 && res.body.success) {
                      if (verifyEmailDisplay) verifyEmailDisplay.textContent = maskEmail(res.body.email || '');
                      if (verifyPhoneDisplay) verifyPhoneDisplay.textContent = maskPhone(res.body.phone || '');
                      if (verifyEmailInput) verifyEmailInput.value = res.body.email || '';
                      if (verifyPhoneInput) verifyPhoneInput.value = res.body.phone || '';
                      if (verifyCodeInput) verifyCodeInput.value = '';
                      hasSentVerificationCode = false;
                      clearVerifyCooldown();
                      syncVerifyChannelUI();
                      refreshVerifySubmitState();
                      showLoginView('register-verify');
                  } else {
                      handleFormErrors('auth-register-form', res.body);
                  }
              }).catch(err => {
                  console.error(err);
                  alert('Something went wrong connecting to the server. Please try again.');
                  btnReg.textContent = originalText;
                  btnReg.disabled = false;
              });
        });
    }

    var verifyForm = document.getElementById('auth-verify-form');
    if (verifyForm) {
        verifyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var errContainer = document.getElementById('verify-errors');
            if (errContainer) errContainer.style.display = 'none';

            var btn = document.getElementById('btn-verify-submit');
            var originalText = btn.textContent;
            btn.textContent = 'Verifying...';
            btn.disabled = true;

            var fd = new FormData(verifyForm);
            fetch(@json(url('/verify-email')), {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            }).then(r => r.json().then(data => ({status: r.status, body: data})))
              .then(res => {
                  btn.textContent = originalText;
                  btn.disabled = false;
                  if (res.status === 200 && res.body.success) {
                      window.location.href = res.body.redirect;
                  } else {
                      handleFormErrors('auth-verify-form', res.body);
                  }
              }).catch(err => {
                  btn.textContent = originalText;
                  btn.disabled = false;
              });
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
