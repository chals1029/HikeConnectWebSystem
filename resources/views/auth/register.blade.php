<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — HikeConnect</title>
    <link rel="icon" type="image/png" href="{{ asset('images/HikeConnect-Logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #B88E64; --panel: #121212; --text: #f5f5f5; --muted: #9ca3af; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            background: #0a0a0a;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            -webkit-font-smoothing: antialiased;
        }
        .card {
            max-width: 420px;
            width: 100%;
            background: var(--panel);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.06);
            text-align: center;
        }
        h1 { font-size: 1.5rem; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
        p { color: var(--muted); font-size: 0.9375rem; line-height: 1.6; margin-bottom: 1.5rem; }
        a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1.5rem;
            border-radius: 999px;
            background: var(--accent);
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9375rem;
        }
        a:hover { filter: brightness(1.08); }
    </style>
</head>
<body>
    <div class="card">
        <h1>Create an account</h1>
        <p>Registration will open here soon. For now you can head back to log in or the home page.</p>
        <a href="{{ route('login') }}">Back to log in</a>
    </div>
</body>
</html>
