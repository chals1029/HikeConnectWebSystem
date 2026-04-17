<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags -->
    <title>HikeConnect - Discover Batangas Mountains | Hiking Guide & Booking</title>
    <meta name="description" content="Discover the beauty of Batangas mountains with HikeConnect. Book guided hikes to Mt. Batulao, Mt. Pico de Loro, and Mt. Talamitam. Perfect for beginners and experienced hikers.">
    <meta name="keywords" content="Batangas hiking, Mt. Batulao, Mt. Pico de Loro, Mt. Talamitam, hiking guide Philippines, mountain trekking">
    <meta name="author" content="HikeConnect">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="HikeConnect - Discover Batangas Mountains">
    <meta property="og:description" content="Your gateway to authentic mountain adventures in Batangas. Book guided hikes and explore stunning trails.">
    <meta property="og:image" content="{{ asset('images/mt-batulao.jpg') }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="HikeConnect - Discover Batangas Mountains">
    <meta name="twitter:description" content="Your gateway to authentic mountain adventures in Batangas.">
    <meta name="twitter:image" content="{{ asset('images/mt-batulao.jpg') }}">
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Icon Framework -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    
    <!-- Favicon & app icons (HikeConnect logo) -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/HikeConnect-Logo.png') }}">
    
    <!-- Critical CSS Inline -->
    <style>
        /* Reset & Critical CSS */
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--pg:#064e3b;--dg:#022c22;--lg:#065f46;--ag:#10b981;--al:#34d399;--bc:#f0fdf4;--td:#111827;--tg:#6b7280;--w:#fff;--tr:.3s ease;--ease-smooth:cubic-bezier(0.16,1,0.3,1)}
        html{scroll-behavior:smooth}
        .scroll-progress{position:fixed;top:0;left:0;height:3px;width:0%;z-index:10001;background:linear-gradient(90deg,var(--ag),var(--al),#6ee7b7);pointer-events:none;transform-origin:left;box-shadow:0 0 12px rgba(16,185,129,.45)}
        body{font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:var(--td);line-height:1.6;background:var(--w);-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
        
        /* Header */
        header{position:fixed;top:1.25rem;left:50%;transform:translateX(-50%);width:calc(100% - 2.5rem);max-width:87.5rem;z-index:1000;padding:.75rem 1.25rem;display:flex;justify-content:space-between;align-items:center;transition:all .45s var(--ease-smooth);border-radius:100px;background:rgba(255,255,255,0.06);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.12);box-shadow:0 4px 30px rgba(0,0,0,0.1)}
        header.scrolled{top:0.75rem;background:rgba(255,255,255,.92);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);box-shadow:0 10px 40px rgba(0,0,0,.08);border:1px solid rgba(255,255,255,0.8);padding:.625rem 1.25rem;width:calc(100% - 1.5rem)}
        header.scrolled .logo,header.scrolled nav a{color:var(--td)}
        .logo{display:flex;align-items:center;text-decoration:none;gap:.625rem;padding-left:.5rem}
        .logo img{height:2.25rem;width:auto;transition:all .45s var(--ease-smooth)}
        .logo span{font-size:1.375rem;font-weight:700;color:var(--w);transition:all .45s var(--ease-smooth);letter-spacing:-0.02em}
        header.scrolled .logo img{height:2rem}
        header.scrolled .logo span{color:var(--ag);font-size:1.25rem}
        header nav{display:flex;gap:1.5rem;background:rgba(255,255,255,0.06);padding:0.375rem 1.5rem;border-radius:100px;border:1px solid rgba(255,255,255,0.08);transition:all .45s var(--ease-smooth)}
        header.scrolled nav{background:rgba(16,185,129,0.06);border-color:rgba(16,185,129,0.12)}
        header nav a{color:var(--w);text-decoration:none;font-size:.9rem;font-weight:600;transition:all var(--tr);position:relative;padding:.25rem 0}
        header.scrolled nav a:hover{color:var(--ag)}
        header nav a::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:0;height:2px;background:var(--ag);transition:all var(--tr);border-radius:2px}
        header nav a:hover::after{width:100%}
        .login-btn{background:var(--w);color:var(--pg);padding:.625rem 1.5rem;border-radius:100px;text-decoration:none;font-size:.875rem;font-weight:700;transition:all .35s ease;border:none;font:inherit;cursor:pointer;box-shadow:0 4px 15px rgba(0,0,0,.1)}
        .login-btn:hover{background:var(--ag);color:var(--w);transform:translateY(-2px);box-shadow:0 8px 25px rgba(16,185,129,.4)}
        header.scrolled .login-btn{background:var(--td);color:var(--w)}
        header.scrolled .login-btn:hover{background:var(--ag);box-shadow:0 8px 25px rgba(16,185,129,.35)}
        
        /* Mobile Menu Button */
        .mobile-menu-btn{display:none;flex-direction:column;gap:5px;padding:8px;background:none;border:none;cursor:pointer;z-index:1001}
        .mobile-menu-btn span{width:24px;height:2px;background:var(--w);transition:all var(--tr);border-radius:2px}
        header.scrolled .mobile-menu-btn span{background:var(--td)}
        
        /* Hero */
        .hero{height:100vh;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;isolation:isolate}
        .hero::before{content:'';position:absolute;inset:-25% -20%;z-index:1;pointer-events:none;background:
            radial-gradient(circle at 20% 25%,rgba(52,211,153,.34),transparent 42%),
            radial-gradient(circle at 78% 18%,rgba(110,231,183,.26),transparent 48%),
            radial-gradient(circle at 50% 90%,rgba(6,95,70,.28),transparent 58%);
            mix-blend-mode:screen;animation:auroraShift 18s ease-in-out infinite alternate}
        .hero-bg{--bg-parallax-x:0px;--bg-parallax-y:0px;position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;will-change:transform;transform:translate3d(var(--bg-parallax-x),var(--bg-parallax-y),0) scale(1.05);animation:heroKenBurns 32s var(--ease-smooth) infinite alternate}
        .hero-overlay{position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;pointer-events:none;background:linear-gradient(to bottom,rgba(6,78,59,.32)0%,rgba(6,78,59,.42)28%,rgba(6,78,59,.38)52%,rgba(6,78,59,.2)72%,rgba(6,78,59,.06)88%,rgba(6,78,59,0)96%)}
        .hero-particles{position:absolute;inset:0;z-index:2;pointer-events:none;overflow:hidden}
        .hero-particle{position:absolute;bottom:-10%;border-radius:999px;background:radial-gradient(circle at 35% 35%,rgba(255,255,255,.75),rgba(255,255,255,.08) 72%,transparent 100%);filter:blur(.35px);opacity:.45;animation:particleRise var(--duration,14s) linear infinite;animation-delay:var(--delay,0s)}
        .hero-mist{position:absolute;left:0;right:0;bottom:0;z-index:2;height:clamp(3.25rem,9vh,7rem);pointer-events:none;background:
            radial-gradient(ellipse 160% 120% at 30% 100%,rgba(255,255,255,.35) 0%,transparent 50%),
            radial-gradient(ellipse 140% 100% at 70% 100%,rgba(255,255,255,.3) 0%,transparent 48%),
            linear-gradient(to bottom,rgba(255,255,255,0) 0%,rgba(255,255,255,0) 35%,rgba(255,255,255,.18) 62%,rgba(255,255,255,.72) 82%,#fff 100%)}
        .hero-content{position:relative;z-index:3;text-align:center;max-width:56rem;padding:0 1.25rem;transform-style:preserve-3d;will-change:transform;transition:transform .45s var(--ease-smooth)}
        .hero-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);padding:.625rem 1.5rem;border-radius:1.875rem;color:var(--w);font-size:.8125rem;font-weight:500;margin-bottom:1.875rem;border:1px solid rgba(255,255,255,.2);animation:heroStagger 1s var(--ease-smooth) .12s both}
        .hero h1{font-size:4rem;font-weight:700;color:var(--w);line-height:1.1;margin-bottom:1.5rem;animation:heroStagger 1s var(--ease-smooth) .28s both}
        .hero h1 span{display:inline-block;background:linear-gradient(110deg,var(--al),#a7f3d0,var(--ag),#6ee7b7,var(--al));background-size:220% auto;-webkit-background-clip:text;background-clip:text;color:transparent;animation:gradientFlow 10s ease-in-out infinite;animation-delay:.5s}
        .hero p{font-size:1.125rem;color:rgba(255,255,255,.85);max-width:37.5rem;margin:0 auto 2.5rem;animation:heroStagger 1s var(--ease-smooth) .44s both}
        .hero-cta{position:relative;overflow:hidden;display:inline-flex;align-items:center;gap:.75rem;background:var(--ag);color:var(--w);padding:1.125rem 2.5rem;border-radius:2.5rem;text-decoration:none;font-size:1rem;font-weight:600;transition:transform .45s var(--ease-smooth),box-shadow .45s var(--ease-smooth),background .35s ease;box-shadow:0 15px 40px rgba(16,185,129,.4);animation:heroStagger 1s var(--ease-smooth) .58s both}
        .hero-cta::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 35%,rgba(255,255,255,.4) 50%,transparent 65%);transform:translateX(-120%);transition:transform .75s var(--ease-smooth);pointer-events:none}
        .hero-cta:hover::after{transform:translateX(120%)}
        .hero-cta:hover{background:var(--al);transform:translateY(-4px) scale(1.02);box-shadow:0 22px 56px rgba(16,185,129,.55)}
        .scroll-indicator{position:absolute;bottom:2.5rem;left:50%;transform:translateX(-50%);z-index:4;display:flex;flex-direction:column;align-items:center;gap:.625rem;color:var(--w);font-size:.75rem;opacity:.85;animation:bounce 2s infinite;text-shadow:0 1px 8px rgba(6,78,59,.5),0 0 1px rgba(0,0,0,.35)}
        
        /* Animations */
        @keyframes fadeInUp{from{opacity:0;transform:translateY(2.5rem)}to{opacity:1;transform:translateY(0)}}
        @keyframes heroStagger{from{opacity:0;transform:translateY(2rem) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}
        @keyframes heroKenBurns{from{transform:translate3d(var(--bg-parallax-x),var(--bg-parallax-y),0) scale(1.05)}to{transform:translate3d(calc(var(--bg-parallax-x) * .75),calc(var(--bg-parallax-y) * .75),0) scale(1.15)}}
        @keyframes gradientFlow{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}
        @keyframes bounce{0%,20%,50%,80%,100%{transform:translateX(-50%)translateY(0)}40%{transform:translateX(-50%)translateY(-10px)}60%{transform:translateX(-50%)translateY(-5px)}}
        @keyframes auroraShift{0%{transform:translate3d(0,0,0) scale(1) rotate(0deg)}50%{transform:translate3d(-2%,2%,0) scale(1.06) rotate(5deg)}100%{transform:translate3d(2%,-2%,0) scale(1.03) rotate(-3deg)}}
        @keyframes particleRise{0%{transform:translate3d(0,0,0) scale(.85);opacity:0}12%{opacity:.5}85%{opacity:.24}100%{transform:translate3d(var(--drift,0px),-120vh,0) scale(1.35);opacity:0}}
        @media(prefers-reduced-motion:reduce){
            .hero-bg,.cta-bg{animation:none!important;transform:none!important}
            .hero::before,.hero-particle{animation:none!important}
            .hero-badge,.hero h1,.hero h1 span,.hero p,.hero-cta{animation:none!important;opacity:1!important;transform:none!important}
            .hero h1 span{background:none!important;-webkit-background-clip:unset!important;background-clip:unset!important;color:var(--al)!important}
            .scroll-progress{display:none!important}
            .hero-cta::after,.connect-btn::after,.cta-btn::after{transition:none!important;transform:none!important}
        }
        
        /* Responsive */
        @media(max-width:48rem){
            header{top:0.75rem;width:calc(100% - 1.5rem);padding:0.5rem 0.75rem}
            header.scrolled{top:0.5rem;width:calc(100% - 1rem);padding:0.5rem 0.75rem}
            header nav{display:none}
            .hero h1{font-size:2.25rem}
            .hero-badge{font-size:.75rem;padding:.5rem 1rem}
        }
    </style>
    
    <!-- Non-Critical CSS Loaded Asynchronously -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"></noscript>
    
    <style>
        /* Gateway Section */
        .gateway{position:relative;z-index:1;margin-top:-2px;padding:7.5rem 3.75rem;background:var(--w);text-align:center}
        .section-tag{display:inline-block;color:var(--ag);font-size:.8125rem;font-weight:600;text-transform:uppercase;letter-spacing:2px;margin-bottom:1.25rem}
        .gateway h2{font-size:2.625rem;font-weight:700;color:var(--td);max-width:37.5rem;margin:0 auto 1.875rem;line-height:1.2}
        .gateway p{font-size:1.0625rem;color:var(--tg);max-width:43.75rem;margin:0 auto 2.5rem}
        .connect-btn{position:relative;overflow:hidden;display:inline-flex;align-items:center;gap:.625rem;background:var(--dg);color:var(--w);padding:1rem 2rem;border-radius:1.875rem;text-decoration:none;font-size:.9375rem;font-weight:600;transition:transform .45s cubic-bezier(0.16,1,0.3,1),box-shadow .45s cubic-bezier(0.16,1,0.3,1),background .35s ease}
        .connect-btn::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 38%,rgba(255,255,255,.14) 50%,transparent 62%);transform:translateX(-120%);transition:transform .75s cubic-bezier(0.16,1,0.3,1);pointer-events:none}
        .connect-btn:hover::after{transform:translateX(120%)}
        .connect-btn:hover{background:var(--pg);transform:translateY(-3px);box-shadow:0 12px 36px rgba(6,78,59,.35)}
        
        /* Mountains */
        .mountains{padding:5rem 3.75rem;background:var(--bc)}
        .mountains-header{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:3.75rem;max-width:87.5rem;margin-left:auto;margin-right:auto;gap:2rem}
        .mountains-header h3{font-size:2.25rem;font-weight:700;color:var(--td);max-width:25rem;line-height:1.2}
        .mountains-header p{font-size:1rem;color:var(--tg);max-width:25rem}
        .mountain-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:1.875rem;max-width:87.5rem;margin:0 auto}
        .mountain-card{background:var(--w);border-radius:1.25rem;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.08);transition:all var(--tr)}
        .mountain-card:hover{transform:translateY(-10px);box-shadow:0 25px 60px rgba(0,0,0,.15)}
        .mountain-img{height:15rem;background-size:cover;background-position:center;position:relative;transform:scale(1);transition:transform .85s cubic-bezier(0.16,1,0.3,1)}
        .mountain-card:hover .mountain-img{transform:scale(1.07)}
        .mountain-img::after{content:'';position:absolute;bottom:0;left:0;right:0;height:6.25rem;background:linear-gradient(to top,rgba(0,0,0,.6),transparent)}
        .mountain-tag{position:absolute;top:1rem;left:1rem;background:var(--ag);color:var(--w);padding:.375rem .875rem;border-radius:1.25rem;font-size:.75rem;font-weight:600;z-index:2}
        .mountain-info{padding:1.5rem}
        .mountain-info h4{font-size:1.375rem;font-weight:700;color:var(--td);margin-bottom:.75rem}
        .mountain-info p{font-size:.875rem;color:var(--tg);line-height:1.6;margin-bottom:1.25rem}
        .mountain-meta{display:flex;gap:1.25rem;font-size:.8125rem;color:var(--tg);flex-wrap:wrap}
        .mountain-meta span{display:flex;align-items:center;gap:.375rem}
        .weather-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.28rem .6rem;border-radius:999px;background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.22);color:var(--pg);font-weight:600;line-height:1}
        .explore-link{display:inline-flex;align-items:center;gap:.5rem;color:var(--ag);text-decoration:none;font-size:.875rem;font-weight:600;margin-top:1rem;transition:gap var(--tr)}
        .explore-link:hover{gap:.75rem}
        .slider-dots{display:flex;justify-content:center;gap:.625rem;margin-top:3.125rem}
        .dot{width:.625rem;height:.625rem;border-radius:50%;background:rgba(6,78,59,.2);cursor:pointer;transition:all var(--tr)}
        .dot.active{background:var(--ag);width:1.875rem;border-radius:5px}
        
        /* Info Section */
        .info-section{padding:7.5rem 3.75rem;background:var(--w)}
        .info-container{max-width:75rem;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:start}
        .info-left h3{font-size:2.25rem;font-weight:700;color:var(--td);margin-bottom:1.25rem;line-height:1.2}
        .info-left>p{font-size:1rem;color:var(--tg);margin-bottom:1.875rem}
        .partners-section .section-tag{margin-bottom:.75rem}
        .partners-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
        .partner-item{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;background:var(--bc);border-radius:.75rem;transition:all var(--tr);border:1px solid transparent;opacity:0;transform:translateY(1.25rem)}
        .partner-item.visible{opacity:1;transform:translateY(0)}
        .partner-item:nth-child(1).visible{transition-delay:.1s}
        .partner-item:nth-child(2).visible{transition-delay:.15s}
        .partner-item:nth-child(3).visible{transition-delay:.2s}
        .partner-item:nth-child(4).visible{transition-delay:.25s}
        .partner-item:nth-child(5).visible{transition-delay:.3s}
        .partner-item:nth-child(6).visible{transition-delay:.35s}
        .partner-item:hover{border-color:var(--ag);background:var(--w);box-shadow:0 4px 20px rgba(16,185,129,.1);transform:translateY(-3px)}
        .partner-icon{width:2.5rem;height:2.5rem;background:var(--dg);border-radius:.75rem;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0;color:var(--ag)}
        .partner-item span{font-size:.9375rem;font-weight:600;color:var(--td)}
        .faq-list{display:flex;flex-direction:column;gap:1rem}
        .faq-item{background:var(--bc);border-radius:.75rem;padding:1.25rem 1.5rem;cursor:pointer;transition:all var(--tr);border:1px solid transparent;opacity:0;transform:translateY(1.25rem)}
        .faq-item.visible{opacity:1;transform:translateY(0)}
        .faq-item:hover{border-color:var(--ag);background:var(--w);box-shadow:0 4px 20px rgba(16,185,129,.1)}
        .faq-question{font-size:.9375rem;font-weight:600;color:var(--td);display:flex;justify-content:space-between;align-items:center;gap:1rem}
        .faq-icon{color:var(--ag);font-size:1.125rem;transition:transform var(--tr);flex-shrink:0}
        .faq-item.active .faq-icon{transform:rotate(45deg)}
        .faq-answer{font-size:.875rem;color:var(--tg);margin-top:.75rem;padding-top:.75rem;border-top:1px solid rgba(16,185,129,.2);display:none;line-height:1.6}
        .faq-item.active .faq-answer{display:block;animation:fadeIn .3s ease}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        
        /* Features Section */
        .features-section{padding:6.25rem 3.75rem;background:var(--bc)}
        .features-header{text-align:center;margin-bottom:3.75rem}
        .features-header h3{font-size:2.25rem;font-weight:700;color:var(--td);margin-bottom:1rem}
        .features-header p{font-size:1.125rem;color:var(--tg);max-width:37.5rem;margin:0 auto}
        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.875rem;max-width:87.5rem;margin:0 auto}
        .feature-card{background:var(--w);border-radius:1.25rem;padding:2.5rem;box-shadow:0 4px 20px rgba(0,0,0,.06);transition:all var(--tr);position:relative;overflow:hidden;border:1px solid transparent}
        .feature-card:hover{transform:translateY(-8px);box-shadow:0 20px 40px rgba(0,0,0,.1);border-color:rgba(16,185,129,.2)}
        .feature-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--ag),var(--al));transform:scaleX(0);transition:transform var(--tr)}
        .feature-card:hover::before{transform:scaleX(1)}
        .feature-number{position:absolute;top:1.25rem;right:1.25rem;width:2.5rem;height:2.5rem;border:2px solid var(--ag);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--ag);font-size:.875rem;font-weight:700}
        .feature-icon-wrap{width:3.5rem;height:3.5rem;background:var(--dg);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem}
        .feature-icon-wrap svg{width:1.75rem;height:1.75rem;color:var(--ag)}
        .feature-card h4{font-size:1.25rem;font-weight:700;color:var(--td);margin-bottom:.875rem}
        .feature-card p{font-size:.9375rem;color:var(--tg);line-height:1.7}
        
        /* Who We Are Section */
        .who-we-are{padding:6.25rem 3.75rem;background:var(--w)}
        .who-container{max-width:62.5rem;margin:0 auto;text-align:center}
        .who-container .section-tag{margin-bottom:1.25rem}
        .who-container h3{font-size:2.5rem;font-weight:700;color:var(--td);margin-bottom:1.5rem}
        .who-container>p{font-size:1.125rem;color:var(--tg);line-height:1.8;margin-bottom:2.5rem;max-width:50rem;margin-left:auto;margin-right:auto}
        .who-mission{background:linear-gradient(135deg,var(--bc),rgba(16,185,129,.08));border-radius:1.25rem;padding:2.5rem;border-left:4px solid var(--ag);text-align:left}
        .who-mission p{font-size:1rem;color:var(--td);line-height:1.8;margin:0}
        .who-mission p strong{color:var(--ag);font-weight:600}
        
        /* CTA Banner */
        .cta-banner{height:25rem;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden}
        .cta-bg{position:absolute;top:0;left:0;width:100%;height:100%;background:url('{{ asset('images/mt-pico-de-loro.jpg') }}') center/cover no-repeat;will-change:transform;transform:scale(1);animation:heroKenBurns 36s cubic-bezier(0.16,1,0.3,1) infinite alternate}
        .cta-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(6,78,59,.75)}
        .cta-content{position:relative;z-index:2;text-align:center;color:var(--w);padding:0 1.25rem}
        .cta-content h2{font-size:2.625rem;font-weight:700;margin-bottom:1.875rem;line-height:1.2}
        .cta-btn{position:relative;overflow:hidden;display:inline-flex;align-items:center;gap:.625rem;background:var(--w);color:var(--pg);padding:1rem 2.25rem;border-radius:1.875rem;text-decoration:none;font-size:.9375rem;font-weight:600;transition:transform .45s cubic-bezier(0.16,1,0.3,1),box-shadow .45s cubic-bezier(0.16,1,0.3,1),background .35s ease,color .35s ease}
        .cta-btn::after{content:'';position:absolute;inset:0;background:linear-gradient(105deg,transparent 35%,rgba(16,185,129,.18) 50%,transparent 65%);transform:translateX(-120%);transition:transform .75s cubic-bezier(0.16,1,0.3,1);pointer-events:none}
        .cta-btn:hover::after{transform:translateX(120%)}
        .cta-btn:hover{background:var(--ag);color:var(--w);transform:translateY(-3px);box-shadow:0 14px 40px rgba(16,185,129,.4)}
        
        /* Footer */
        footer{background:var(--dg);color:var(--w);padding:5rem 3.75rem 2.5rem}
        .footer-content{max-width:87.5rem;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3.75rem;margin-bottom:3.75rem}
        .footer-brand h4{margin-bottom:1.25rem;display:flex;align-items:center;gap:.625rem}
        .footer-brand h4 img{height:2rem;width:auto}
        .footer-brand h4 span{font-size:1.25rem;font-weight:700;color:var(--ag)}
        .footer-brand p{font-size:.9375rem;color:rgba(255,255,255,.7);line-height:1.7;margin-bottom:1.875rem}
        .newsletter{display:flex;gap:.625rem}
        .newsletter input{flex:1;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);padding:.875rem 1.25rem;border-radius:1.875rem;color:var(--w);font-size:.875rem;outline:none;transition:border-color var(--tr)}
        .newsletter input:focus{border-color:var(--ag)}
        .newsletter input::placeholder{color:rgba(255,255,255,.5)}
        .newsletter button{background:var(--ag);color:var(--w);border:none;padding:.875rem 1.75rem;border-radius:1.875rem;font-size:.875rem;font-weight:600;cursor:pointer;transition:all var(--tr)}
        .newsletter button:hover{background:var(--al)}
        .footer-links h5{font-size:.875rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:1.5rem;color:var(--al)}
        .footer-links ul{list-style:none}
        .footer-links li{margin-bottom:.75rem}
        .footer-links a{color:rgba(255,255,255,.7);text-decoration:none;font-size:.9375rem;transition:color var(--tr)}
        .footer-links a:hover{color:var(--ag)}
        .footer-bottom{max-width:87.5rem;margin:0 auto;padding-top:2.5rem;border-top:1px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;align-items:center;font-size:.875rem;color:rgba(255,255,255,.5)}
        .social-links{display:flex;align-items:center;gap:1.125rem;flex-wrap:wrap;justify-content:center}
        .social-links a{width:2.75rem;height:2.75rem;min-width:2.75rem;border-radius:50%;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,0.05);display:inline-flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.25rem;transition:all .35s ease}
        .social-links a:hover{border-color:var(--ag);color:var(--w);background:var(--ag);transform:translateY(-3px);box-shadow:0 10px 25px rgba(16,185,129,.35)}
        .social-links a:focus-visible{outline:2px solid var(--al);outline-offset:3px}
        
        /* Section reveal (scroll) */
        .reveal-section{opacity:0;transform:translateY(1.75rem);transition:opacity .7s cubic-bezier(0.16,1,0.3,1),transform .7s cubic-bezier(0.16,1,0.3,1)}
        .reveal-section.visible{opacity:1;transform:translateY(0)}
        .interactive-tilt{position:relative;transform:perspective(900px) rotateX(var(--rx,0deg)) rotateY(var(--ry,0deg));transition:transform .35s var(--ease-smooth),box-shadow .35s var(--ease-smooth)}
        .interactive-tilt::after{content:'';position:absolute;inset:0;pointer-events:none;background:radial-gradient(circle at var(--mx,50%) var(--my,50%),rgba(52,211,153,.18),transparent 42%);opacity:0;transition:opacity .35s ease}
        .interactive-tilt:hover::after{opacity:1}
        
        /* Mountain Cards Animation */
        .mountain-card{opacity:0;transform:translateY(1.875rem);transition:opacity .6s cubic-bezier(.4,0,.2,1),transform .6s cubic-bezier(.4,0,.2,1),box-shadow .35s ease}
        .mountain-card.visible{opacity:1;transform:translateY(0)}
        .mountain-card:nth-child(1).visible{transition-delay:.1s}
        .mountain-card:nth-child(2).visible{transition-delay:.2s}
        .mountain-card:nth-child(3).visible{transition-delay:.3s}
        
        /* Feature Cards Animation */
        .feature-card{opacity:0;transform:translateY(1.875rem);transition:all .6s cubic-bezier(.4,0,.2,1)}
        .feature-card.visible{opacity:1;transform:translateY(0)}
        .feature-card:nth-child(1).visible{transition-delay:.1s}
        .feature-card:nth-child(2).visible{transition-delay:.15s}
        .feature-card:nth-child(3).visible{transition-delay:.2s}
        .feature-card:nth-child(4).visible{transition-delay:.25s}
        .feature-card:nth-child(5).visible{transition-delay:.3s}
        .feature-card:nth-child(6).visible{transition-delay:.35s}
        
        /* Who We Are Animation */
        .who-mission{opacity:0;transform:translateY(1.875rem);transition:all .6s ease}
        .who-mission.visible{opacity:1;transform:translateY(0)}
        
        @media (prefers-reduced-motion: reduce) {
            .reveal-section{opacity:1!important;transform:none!important;transition:none!important}
            .mountain-card:hover .mountain-img{transform:scale(1)!important}
            .interactive-tilt{transform:none!important}
            .interactive-tilt::after{display:none!important}
        }
        
        /* Responsive */
        @media(max-width:64rem){
            .mountain-cards{grid-template-columns:repeat(2,1fr)}
            .footer-content{grid-template-columns:repeat(2,1fr)}
            .info-container{grid-template-columns:1fr;gap:3.125rem}
            .features-grid{grid-template-columns:repeat(2,1fr)}
        }
        
        @media(max-width:48rem){
            .gateway,.mountains,.info-section,.features-section,.who-we-are,.cta-banner,footer{padding:3.75rem 1.25rem}
            .logo{padding-left:0}
            .logo img{height:1.75rem}
            .logo span{font-size:1.125rem}
            .login-btn{padding:.5rem .875rem;font-size:.75rem;white-space:nowrap}
            .mobile-menu-btn{display:flex}
            .mountain-cards{grid-template-columns:1fr}
            .mountains-header{flex-direction:column;text-align:center;gap:1.25rem}
            .mountains-header h3,.mountains-header p{max-width:100%}
            .footer-content{grid-template-columns:1fr;gap:2.5rem}
            .footer-bottom{flex-direction:column;gap:1.25rem;text-align:center}
            .quote{font-size:1.375rem}
            .features-grid{grid-template-columns:1fr}
            .features-header h3{font-size:1.75rem}
            .who-container h3{font-size:1.75rem}
            .cta-content h2{font-size:1.75rem}
            .hero h1{font-size:2rem}
            .gateway h2{font-size:1.75rem}
            .mountains-header h3,.info-left h3{font-size:1.5rem}
            .partners-grid{grid-template-columns:1fr}
            .partner-item{padding:.75rem 1rem}
            .partner-icon{width:2rem;height:2rem;font-size:1rem}
        }
    </style>
</head>
<body>
    <div id="scroll-progress" class="scroll-progress" aria-hidden="true"></div>
    <a href="#main" class="sr-only" style="position:absolute;left:-9999px;">Skip to main content</a>
    
    <header id="header" role="banner">
        <a href="#" class="logo" aria-label="HikeConnect Home">
            <img src="{{ asset('images/HikeConnect-Logo.png') }}" alt="" style="height: 40px; width: auto;">
            <span>HikeConnect</span>
        </a>
        <nav role="navigation" aria-label="Main navigation">
            <a href="#mountains">Mountains</a>
            <a href="#features">Features</a>
            <a href="#info">Information</a>
            <a href="#about">About</a>
        </nav>
        <button type="button" class="login-btn js-auth-open" data-auth-mode="login">Log In</button>
        <button class="mobile-menu-btn" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <main id="main">
        <section class="hero" aria-label="Hero section">
            <video class="hero-bg" autoplay loop muted playsinline aria-label="Batangas mountain landscape">
                <source src="{{ asset('videos/MountBatulao.mp4') }}" type="video/mp4">
            </video>
            <div class="hero-overlay"></div>
            <div class="hero-particles" id="hero-particles" aria-hidden="true"></div>
            <div class="hero-mist" aria-hidden="true"></div>
            <div class="hero-content">
                <div class="hero-badge">
                    <span aria-hidden="true"><iconify-icon icon="lucide:mountain" style="vertical-align:text-bottom;"></iconify-icon></span>
                    <span>Batangas #1 Hiking Platform</span>
                </div>
                <h1>Discover the Beauty of <span>Batangas Mountains</span></h1>
                <p>From gentle slopes to challenging peaks, experience the breathtaking views and unforgettable adventures that await in Batangas.</p>
                <a href="#mountains" class="hero-cta">
                    <span>Start Your Journey</span>
                    <span aria-hidden="true">→</span>
                </a>
            </div>
            <div class="scroll-indicator" aria-hidden="true">
                <span>Scroll to explore</span>
                <span>↓</span>
            </div>
        </section>

        <section class="gateway reveal-section" aria-labelledby="gateway-heading">
            <span class="section-tag">Your Gateway</span>
            <h2 id="gateway-heading">Your Gateway to Authentic Mountain Adventures</h2>
            <p>At HikeConnect, we're passionate about helping you experience the natural beauty of Batangas. Whether you're a beginner seeking scenic trails or an experienced climber looking for challenging peaks, we connect you with the best mountains, local guides, and essential information to make your hiking journey safe, enjoyable, and truly memorable.</p>
            <a href="#mountains" class="connect-btn">
                <span>Connect With Us</span>
                <span aria-hidden="true">↗</span>
            </a>
        </section>

        @php
            $exploreTrailHref = auth()->check()
                ? route('hikers.dashboard') . '#mountain-overview'
                : route('home') . '?auth=login&next_section=mountain-overview';
        @endphp
        <section class="mountains" id="mountains" aria-labelledby="mountains-heading">
            <div class="mountains-header reveal-section">
                <h3 id="mountains-heading">From Beginner Trails to Expert Peaks: Discover Batangas</h3>
                <p>From the iconic views of Batulao to the crystal-clear waters near Pico de Loro, discover the hidden gems of Batangas mountains.</p>
            </div>
            
            <div class="mountain-cards" role="list">
                <article class="mountain-card" role="listitem" data-weather-lat="14.0474" data-weather-lng="120.9877">
                    <div class="mountain-img" style="background-image:url('{{ asset('images/mt-batulao.jpg') }}')" role="img" aria-label="Mount Batulao scenic view">
                        <span class="mountain-tag">Beginner Friendly</span>
                    </div>
                    <div class="mountain-info">
                        <h4>Mt. Batulao</h4>
                        <p>Known for its rolling hills and stunning views of Nasugbu. Perfect for beginners with well-established trails and campsites.</p>
                        <div class="mountain-meta">
                            <span><span aria-hidden="true"><iconify-icon icon="lucide:mountain" style="vertical-align:text-bottom;"></iconify-icon></span> 811 MASL</span>
                            <span><span aria-hidden="true"><iconify-icon icon="lucide:timer" style="vertical-align:text-bottom;"></iconify-icon></span> 4-5 Hours</span>
                            <span class="weather-chip"><span aria-hidden="true"><iconify-icon icon="lucide:cloud-sun" style="vertical-align:text-bottom;"></iconify-icon></span> <span data-weather-temp>--°C</span></span>
                        </div>
                        <a href="{{ $exploreTrailHref }}" class="explore-link" aria-label="Explore Mt. Batulao trail">
                            <span>Explore Trail</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
                
                <article class="mountain-card" role="listitem" data-weather-lat="14.1520" data-weather-lng="120.6309">
                    <div class="mountain-img" style="background-image:url('{{ asset('images/mt-pico-de-loro.jpg') }}')" role="img" aria-label="Mount Pico de Loro landscape">
                        <span class="mountain-tag">Moderate</span>
                    </div>
                    <div class="mountain-info">
                        <h4>Mt. Pico de Loro</h4>
                        <p>Famous for its iconic monolith and panoramic views of the West Philippine Sea. A must-visit for every hiker.</p>
                        <div class="mountain-meta">
                            <span><span aria-hidden="true"><iconify-icon icon="lucide:mountain" style="vertical-align:text-bottom;"></iconify-icon></span> 664 MASL</span>
                            <span><span aria-hidden="true"><iconify-icon icon="lucide:timer" style="vertical-align:text-bottom;"></iconify-icon></span> 5-6 Hours</span>
                            <span class="weather-chip"><span aria-hidden="true"><iconify-icon icon="lucide:cloud-sun" style="vertical-align:text-bottom;"></iconify-icon></span> <span data-weather-temp>--°C</span></span>
                        </div>
                        <a href="{{ $exploreTrailHref }}" class="explore-link" aria-label="Explore Mt. Pico de Loro trail">
                            <span>Explore Trail</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
                
                <article class="mountain-card" role="listitem" data-weather-lat="14.0508" data-weather-lng="120.9050">
                    <div class="mountain-img" style="background-image:url('{{ asset('images/mt-talamitam.jpg') }}')" role="img" aria-label="Mount Talamitam grasslands">
                        <span class="mountain-tag">Beginner Friendly</span>
                    </div>
                    <div class="mountain-info">
                        <h4>Mt. Talamitam</h4>
                        <p>Batulao's sister mountain offering open trails, grasslands, and spectacular summit views. Great for day hikes.</p>
                        <div class="mountain-meta">
                            <span><span aria-hidden="true"><iconify-icon icon="lucide:mountain" style="vertical-align:text-bottom;"></iconify-icon></span> 630 MASL</span>
                            <span><span aria-hidden="true"><iconify-icon icon="lucide:timer" style="vertical-align:text-bottom;"></iconify-icon></span> 3-4 Hours</span>
                            <span class="weather-chip"><span aria-hidden="true"><iconify-icon icon="lucide:cloud-sun" style="vertical-align:text-bottom;"></iconify-icon></span> <span data-weather-temp>--°C</span></span>
                        </div>
                        <a href="{{ $exploreTrailHref }}" class="explore-link" aria-label="Explore Mt. Talamitam trail">
                            <span>Explore Trail</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            </div>
            
            <div class="slider-dots" role="tablist" aria-label="Mountain carousel navigation">
                <button class="dot active" role="tab" aria-selected="true" aria-label="View page 1"></button>
                <button class="dot" role="tab" aria-selected="false" aria-label="View page 2"></button>
            </div>
        </section>

        <section class="info-section" id="info" aria-labelledby="info-heading">
            <div class="info-container">
                <div class="info-left partners-section">
                    <span class="section-tag">Our Network</span>
                    <h3 id="info-heading">Trusted Partners & Supporters</h3>
                    <p>We collaborate with leading organizations to ensure safe, sustainable, and unforgettable hiking experiences across Batangas.</p>
                    
                    <div class="partners-grid">
                        <div class="partner-item">
                            <div class="partner-icon"><iconify-icon icon="lucide:landmark"></iconify-icon></div>
                            <span>DENR</span>
                        </div>
                        <div class="partner-item">
                            <div class="partner-icon"><iconify-icon icon="lucide:palmtree"></iconify-icon></div>
                            <span>Tourism Batangas</span>
                        </div>
                        <div class="partner-item">
                            <div class="partner-icon"><iconify-icon icon="lucide:footprints"></iconify-icon></div>
                            <span>Philippine Hiking Society</span>
                        </div>
                        <div class="partner-item">
                            <div class="partner-icon"><iconify-icon icon="lucide:compass"></iconify-icon></div>
                            <span>Trail Blazers PH</span>
                        </div>
                        <div class="partner-item">
                            <div class="partner-icon"><iconify-icon icon="lucide:shield-check"></iconify-icon></div>
                            <span>Mt. Safe Philippines</span>
                        </div>
                        <div class="partner-item">
                            <div class="partner-icon"><iconify-icon icon="lucide:leaf"></iconify-icon></div>
                            <span>Eco Warriors</span>
                        </div>
                    </div>
                </div>
                
                <div class="faq-list" role="list">
                    <div class="faq-item" role="listitem">
                        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
                            <span>What is HikeConnect and who can join?</span>
                            <span class="faq-icon" aria-hidden="true">+</span>
                        </div>
                        <div class="faq-answer">HikeConnect is a web-based community platform dedicated to connecting hiking enthusiasts with famous hiking destinations in Batangas, particularly Mt. Batulao, Mt. Talamitam, and Mt. Masapinit. Anyone passionate about hiking, from beginners to experienced trekkers, can join our community.</div>
                    </div>
                    <div class="faq-item" role="listitem">
                        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
                            <span>How do I access trail information and updates?</span>
                            <span class="faq-icon" aria-hidden="true">+</span>
                        </div>
                        <div class="faq-answer">Once you join our community, you'll have access to detailed trail information, real-time weather updates, difficulty ratings, and user reviews. Our platform provides comprehensive guides for each mountain, including trail maps, safety tips, and recommended gear.</div>
                    </div>
                    <div class="faq-item" role="listitem">
                        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
                            <span>Is there a fee to use HikeConnect?</span>
                            <span class="faq-icon" aria-hidden="true">+</span>
                        </div>
                        <div class="faq-answer">HikeConnect is completely free to join and use. Our mission is to make hiking information accessible to everyone. You can browse trails, read reviews, and connect with other hikers without any charges.</div>
                    </div>
                    <div class="faq-item" role="listitem">
                        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
                            <span>How can I contribute to the community?</span>
                            <span class="faq-icon" aria-hidden="true">+</span>
                        </div>
                        <div class="faq-answer">You can contribute by sharing your hiking experiences, posting trail reviews, uploading photos, participating in community discussions, and helping fellow hikers with tips and advice. Your contributions help make our community stronger and more informed.</div>
                    </div>
                    <div class="faq-item" role="listitem">
                        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
                            <span>What safety measures does HikeConnect promote?</span>
                            <span class="faq-icon" aria-hidden="true">+</span>
                        </div>
                        <div class="faq-answer">We prioritize hiker safety by providing up-to-date trail conditions, weather alerts, safety guidelines, and emergency contact information. We also encourage hikers to register their trips, hike in groups, and follow Leave No Trace principles.</div>
                    </div>
                    <div class="faq-item" role="listitem">
                        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
                            <span>Can I organize group hikes through the platform?</span>
                            <span class="faq-icon" aria-hidden="true">+</span>
                        </div>
                        <div class="faq-answer">Yes! Our community feature allows you to create and join group hikes. You can post upcoming hikes, find hiking buddies, and coordinate with other community members. Safety in numbers makes for better hiking experiences.</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section" id="features" aria-labelledby="features-heading">
            <div class="features-header reveal-section">
                <span class="section-tag">What We Offer</span>
                <h3 id="features-heading">Everything You Need for Your Next Adventure</h3>
                <p>Comprehensive tools and resources designed to make your hiking experience safe, connected, and unforgettable.</p>
            </div>
            
            <div class="features-grid" role="list">
                <article class="feature-card" role="listitem">
                    <span class="feature-number" aria-label="Feature 1">01</span>
                    <div class="feature-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <h4>Comprehensive Trail Guides</h4>
                    <p>Access detailed information about every hiking trail in Batangas, including difficulty levels, estimated time, trail conditions, and points of interest.</p>
                </article>
                
                <article class="feature-card" role="listitem">
                    <span class="feature-number" aria-label="Feature 2">02</span>
                    <div class="feature-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <h4>Community Connection</h4>
                    <p>Connect with fellow hiking enthusiasts, find hiking buddies, and join group treks. Share experiences and learn from seasoned hikers.</p>
                </article>
                
                <article class="feature-card" role="listitem">
                    <span class="feature-number" aria-label="Feature 3">03</span>
                    <div class="feature-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <h4>Safety Resources</h4>
                    <p>Stay safe with our comprehensive safety guidelines, weather updates, emergency contacts, and real-time alerts about trail conditions.</p>
                </article>
                
                <article class="feature-card" role="listitem">
                    <span class="feature-number" aria-label="Feature 4">04</span>
                    <div class="feature-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    </div>
                    <h4>Photo Sharing</h4>
                    <p>Share your hiking adventures through photos and inspire others. Browse stunning images from Batangas' most beautiful peaks.</p>
                </article>
                
                <article class="feature-card" role="listitem">
                    <span class="feature-number" aria-label="Feature 5">05</span>
                    <div class="feature-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <h4>Event Management</h4>
                    <p>Discover and join organized hiking events, clean-up drives, and community gatherings. Create your own events and build the community.</p>
                </article>
                
                <article class="feature-card" role="listitem">
                    <span class="feature-number" aria-label="Feature 6">06</span>
                    <div class="feature-icon-wrap" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <h4>Trail Reviews & Tips</h4>
                    <p>Read honest reviews from fellow hikers, get insider tips, and share your own experiences to help others plan their adventures.</p>
                </article>
            </div>
        </section>

        <!-- Who We Are Section -->
        <section class="who-we-are" id="about" aria-labelledby="who-heading">
            <div class="who-container reveal-section">
                <span class="section-tag">About Us</span>
                <h3 id="who-heading">Who We Are</h3>
                <p>HikeConnect is a student-led initiative dedicated to building a comprehensive digital platform for Batangas' hiking community. Our mission is to make mountain information accessible, connect hikers, and promote safe and sustainable hiking practices.</p>
                <div class="who-mission">
                    <p>We believe that <strong>every hiker deserves access to accurate trail information, a supportive community, and the tools to explore Batangas' natural beauty responsibly.</strong></p>
                </div>
            </div>
        </section>

        <section class="cta-banner" aria-labelledby="cta-heading">
            <div class="cta-bg" role="img" aria-label="Mountain landscape background"></div>
            <div class="cta-overlay"></div>
            <div class="cta-content reveal-section">
                <h2 id="cta-heading">Let the Journey Begin<br>Explore Batangas</h2>
                <a href="#mountains" class="cta-btn">Explore Now</a>
            </div>
        </section>
    </main>

    <footer role="contentinfo">
        <div class="footer-content">
            <div class="footer-brand">
                <h4>
                    <img src="{{ asset('images/HikeConnect-Logo.png') }}" alt="" style="height: 2rem; width: auto;">
                    <span>HikeConnect</span>
                </h4>
                <p>Do what you love - Hiking. Leave the rest to us. From guided treks to detailed trail information, we're here to help you discover the beauty of Batangas mountains.</p>
                <form class="newsletter" onsubmit="return false;" aria-label="Newsletter signup">
                    <input type="email" placeholder="Enter your email" aria-label="Email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
            
            <nav class="footer-links" aria-label="Company links">
                <h5>Company</h5>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Press</a></li>
                </ul>
            </nav>
            
            <nav class="footer-links" aria-label="Support links">
                <h5>Support</h5>
                <ul>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Terms</a></li>
                </ul>
            </nav>
            
            <nav class="footer-links" aria-label="Social links">
                <h5>Connect</h5>
                <ul>
                    <li><a href="#" aria-label="Facebook">Facebook</a></li>
                    <li><a href="#" aria-label="Instagram">Instagram</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} HikeConnect. All rights reserved.</span>
            <div class="social-links" aria-label="Social media">
                <a href="#" aria-label="Facebook"><iconify-icon icon="lucide:facebook"></iconify-icon></a>
                <a href="#" aria-label="Instagram"><iconify-icon icon="lucide:instagram"></iconify-icon></a>
            </div>
        </div>
    </footer>

    @include('auth._modal')

    <!-- Optimized JavaScript -->
    <script>
        (function() {
            'use strict';
            
            // Header + scroll progress (throttled)
            const header = document.getElementById('header');
            const scrollProgress = document.getElementById('scroll-progress');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let ticking = false;
            
            function applyScrollUI() {
                header.classList.toggle('scrolled', window.scrollY > 100);
                if (scrollProgress) {
                    const doc = document.documentElement;
                    const max = doc.scrollHeight - doc.clientHeight;
                    scrollProgress.style.width = max > 0
                        ? Math.min(100, (window.scrollY / max) * 100) + '%'
                        : '0%';
                }
                ticking = false;
            }
            
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(applyScrollUI);
                    ticking = true;
                }
            }, { passive: true });
            applyScrollUI();

            // Hero particles + depth parallax for premium landing feel
            const hero = document.querySelector('.hero');
            const heroBg = document.querySelector('.hero-bg');
            const heroContent = document.querySelector('.hero-content');
            const particleField = document.getElementById('hero-particles');
            const finePointer = window.matchMedia('(pointer:fine)').matches;

            if (!prefersReducedMotion && particleField) {
                const particleCount = window.innerWidth < 768 ? 14 : 28;
                const fragment = document.createDocumentFragment();
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('span');
                    const size = (Math.random() * 5 + 2).toFixed(1);
                    const left = (Math.random() * 100).toFixed(2);
                    const drift = (Math.random() * 70 - 35).toFixed(1);
                    const delay = (Math.random() * 12).toFixed(2);
                    const duration = (Math.random() * 10 + 12).toFixed(2);
                    particle.className = 'hero-particle';
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    particle.style.left = `${left}%`;
                    particle.style.setProperty('--drift', `${drift}px`);
                    particle.style.setProperty('--delay', `${delay}s`);
                    particle.style.setProperty('--duration', `${duration}s`);
                    fragment.appendChild(particle);
                }
                particleField.appendChild(fragment);
            }

            if (!prefersReducedMotion && finePointer && hero && heroBg && heroContent) {
                let heroRAF = null;
                const pointer = { x: 0, y: 0 };
                const target = { x: 0, y: 0 };

                function animateHeroDepth() {
                    pointer.x += (target.x - pointer.x) * 0.12;
                    pointer.y += (target.y - pointer.y) * 0.12;
                    heroContent.style.transform = `translate3d(${pointer.x * 10}px,${pointer.y * 8}px,0)`;
                    heroBg.style.setProperty('--bg-parallax-x', `${(pointer.x * -14).toFixed(2)}px`);
                    heroBg.style.setProperty('--bg-parallax-y', `${(pointer.y * -10).toFixed(2)}px`);
                    heroRAF = requestAnimationFrame(animateHeroDepth);
                }

                hero.addEventListener('pointermove', (event) => {
                    const rect = hero.getBoundingClientRect();
                    const x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
                    const y = ((event.clientY - rect.top) / rect.height) * 2 - 1;
                    target.x = Math.max(-1, Math.min(1, x));
                    target.y = Math.max(-1, Math.min(1, y));
                    if (!heroRAF) {
                        heroRAF = requestAnimationFrame(animateHeroDepth);
                    }
                });

                hero.addEventListener('pointerleave', () => {
                    target.x = 0;
                    target.y = 0;
                    if (!heroRAF) {
                        heroRAF = requestAnimationFrame(animateHeroDepth);
                    }
                    setTimeout(() => {
                        if (Math.abs(pointer.x) < 0.01 && Math.abs(pointer.y) < 0.01 && heroRAF) {
                            cancelAnimationFrame(heroRAF);
                            heroRAF = null;
                            heroContent.style.transform = '';
                            heroBg.style.removeProperty('--bg-parallax-x');
                            heroBg.style.removeProperty('--bg-parallax-y');
                        }
                    }, 520);
                });
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            // Open-Meteo temperatures for landing mountain cards (same behavior as dashboard chips)
            const mountainWeatherCards = document.querySelectorAll('.mountain-card[data-weather-lat][data-weather-lng]');
            mountainWeatherCards.forEach((card) => {
                const lat = card.getAttribute('data-weather-lat');
                const lng = card.getAttribute('data-weather-lng');
                const tempEl = card.querySelector('[data-weather-temp]');
                if (!lat || !lng || !tempEl) return;
                const weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${encodeURIComponent(lat)}&longitude=${encodeURIComponent(lng)}&current_weather=true`;
                fetch(weatherUrl)
                    .then((response) => response.json())
                    .then((data) => {
                        if (data && data.current_weather && typeof data.current_weather.temperature === 'number') {
                            tempEl.textContent = `${Math.round(data.current_weather.temperature)}°C`;
                        } else {
                            tempEl.textContent = '--°C';
                        }
                    })
                    .catch(() => {
                        tempEl.textContent = '--°C';
                    });
            });
            
            // Intersection Observer for reveal animations
            const observerOptions = { 
                threshold: 0.1, 
                rootMargin: '0px 0px -50px 0px' 
            };
            
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        revealObserver.unobserve(entry.target); // Only animate once
                    }
                });
            }, observerOptions);
            
            // Observe elements for reveal
            document.querySelectorAll('.reveal-section, .mountain-card, .faq-item, .partner-item, .feature-card, .who-mission')
                .forEach(el => revealObserver.observe(el));

            // Card tilt interactions for mountain + feature cards
            if (!prefersReducedMotion && finePointer) {
                document.querySelectorAll('.mountain-card, .feature-card').forEach((card) => {
                    card.classList.add('interactive-tilt');
                    card.addEventListener('pointermove', (event) => {
                        const rect = card.getBoundingClientRect();
                        const x = event.clientX - rect.left;
                        const y = event.clientY - rect.top;
                        const rx = ((y / rect.height) - 0.5) * -10;
                        const ry = ((x / rect.width) - 0.5) * 12;
                        card.style.setProperty('--rx', `${rx.toFixed(2)}deg`);
                        card.style.setProperty('--ry', `${ry.toFixed(2)}deg`);
                        card.style.setProperty('--mx', `${(x / rect.width * 100).toFixed(2)}%`);
                        card.style.setProperty('--my', `${(y / rect.height * 100).toFixed(2)}%`);
                    });
                    card.addEventListener('pointerleave', () => {
                        card.style.removeProperty('--rx');
                        card.style.removeProperty('--ry');
                        card.style.removeProperty('--mx');
                        card.style.removeProperty('--my');
                    });
                });
            }
            
            // FAQ accordion functionality
            document.querySelectorAll('.faq-item').forEach(item => {
                const question = item.querySelector('.faq-question');
                
                function toggle() {
                    const isActive = item.classList.contains('active');
                    
                    // Close all others (optional - remove if you want multiple open)
                    document.querySelectorAll('.faq-item').forEach(other => {
                        other.classList.remove('active');
                        other.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                    });
                    
                    if (!isActive) {
                        item.classList.add('active');
                        question.setAttribute('aria-expanded', 'true');
                    }
                }
                
                question.addEventListener('click', toggle);
                question.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggle();
                    }
                });
            });
            
            // Slider dots (visual only - can be extended)
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    dots.forEach(d => {
                        d.classList.remove('active');
                        d.setAttribute('aria-selected', 'false');
                    });
                    dot.classList.add('active');
                    dot.setAttribute('aria-selected', 'true');
                });
            });
            
            // Performance: Lazy load images below the fold (if needed)
            if ('IntersectionObserver' in window) {
                const imgObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.style.backgroundImage = `url('${img.dataset.src}')`;
                                img.removeAttribute('data-src');
                                imgObserver.unobserve(img);
                            }
                        }
                    });
                });
                
                document.querySelectorAll('[data-src]').forEach(img => imgObserver.observe(img));
            }
        })();
    </script>
</body>
</html>