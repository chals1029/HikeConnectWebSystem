# HikeConnect

<p align="center">
  <img src="public/images/HikeConnect-Logo.png" width="120" alt="HikeConnect Logo">
</p>

<p align="center">
  <strong>Your Gateway to Batangas Mountains</strong>
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#mountains">Mountains</a> •
  <a href="#about">About</a> •
  <a href="#technology-stack">Stack</a> •
  <a href="#installation">Installation</a> •
  <a href="#contributing">Contributing</a>
</p>

---

## About HikeConnect

HikeConnect is a web-based community platform dedicated to connecting hiking enthusiasts with famous hiking destinations in Batangas, Philippines. Our mission is to make mountain information accessible, connect hikers, and promote safe and sustainable hiking practices.

### Featured Mountains

- **Mt. Batulao** (811 MASL) — Beginner-friendly with rolling hills and stunning views
- **Mt. Pico de Loro** (664 MASL) — Famous for its iconic monolith
- **Mt. Talamitam** (630 MASL) — Great for day hikes with open trails
- **Mt. Masapinit** — Challenging peaks for experienced trekkers

---

## Features

### 🗺️ Comprehensive Trail Guides

Access detailed information about hiking trails, including difficulty, estimated time, conditions, and points of interest. The **hiker dashboard** lists mountains, jump-off points, and booking flows.

### 👥 Community & Bookings

Sign up, verify email, book guided hikes, leave reviews, and post in community threads (data-driven from the database).

### 🛡️ Safety Resources

Safety guidelines, weather hooks, and structured alerts for responsible hiking.

### 📍 Live location (hiker dashboard)

**Track Location** uses the browser Geolocation API and Google Maps for live GPS tracking and a trail polyline while hiking (best with the tab active; background tracking is limited on the web).

### ⭐ Trail Reviews & Tips

Read and submit reviews tied to mountains and your completed hikes.

---

## Who Can Join?

Anyone passionate about hiking, from **beginners to experienced trekkers**, can join. HikeConnect welcomes outdoor enthusiasts looking for trails, guides, and community.

---

## Trusted Partners & Supporters

- 🏛️ **DENR** — Department of Environment and Natural Resources
- 🌴 **Tourism Batangas**
- 🥾 **Philippine Hiking Society**
- 🧭 **Trail Blazers PH**
- 🛡️ **Mt. Safe Philippines**
- 🌿 **Eco Warriors**

---

## Technology Stack

| Layer | Choice |
|--------|--------|
| **Backend** | Laravel 13, PHP 8.3+ |
| **Auth** | Session-based login; AJAX registration with email verification; `AuthController` + `EmailService` (PHPMailer) |
| **Frontend** | Blade templates; **Tailwind CSS v4** + **Vite 8** for built assets |
| **Hiker UI** | `resources/css/hikers.css` (Tailwind + legacy component CSS extracted from inline styles) |
| **Maps** | Google Maps JavaScript API (tracking & jump-off markers) |
| **Database** | MySQL / MariaDB / PostgreSQL (via Laravel) |

The marketing **welcome** page and **auth modal** still include large inline/CSS blocks; the **hiker dashboard** loads compiled CSS via `@vite`.

---

## Installation

### Requirements

- PHP 8.3+
- Composer
- Node.js 20+ and npm (for Vite/Tailwind)
- MySQL, MariaDB, or PostgreSQL

### Setup

```bash
git clone https://github.com/chals1029/HikeConnectWebSystem.git
cd HikeConnectWebSystem

composer install
cp .env.example .env
php artisan key:generate
```

Configure `.env` (database, `APP_URL`, mail for verification codes). Then:

```bash
php artisan migrate

npm install
npm run build
```

For local development with hot reload for CSS/JS:

```bash
npm run dev
```

In another terminal:

```bash
php artisan serve
```

Open `http://127.0.0.1:8000` (or your Laragon host).

### One-shot Composer setup (optional)

The project includes a Composer `setup` script that installs PHP deps, ensures `.env`, runs migrations, and installs npm packages — see `composer.json` → `scripts.setup`.

### Google Maps (Track Location)

Set your Maps JavaScript API key in `.env` (e.g. `GOOGLE_MAPS_API_KEY` or as your app expects) so the hiker **Track Location** map and markers load.

---

## Project Structure

```
HikeConnectWebSystem/
├── app/
│   ├── Http/Controllers/     # HikerDashboardController, AuthController, …
│   ├── Models/               # User, Mountain, HikeBooking, …
│   └── Services/             # Email, achievements, …
├── database/migrations/
├── public/
│   ├── build/                # Vite manifest + hashed CSS/JS (after npm run build)
│   └── images/               # Logos & mountain photos
├── resources/
│   ├── css/
│   │   ├── app.css           # Tailwind entry (shared / welcome-oriented)
│   │   ├── hikers.css        # Tailwind + hiker dashboard styles
│   │   └── hikers-dashboard*.css
│   ├── js/app.js
│   └── views/
│       ├── welcome.blade.php
│       ├── hikers.blade.php
│       ├── hikers/           # Partials (_new-sections, _new-styles)
│       └── auth/
├── routes/web.php
└── vite.config.js
```

---

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/your-feature`)
3. Commit your changes
4. Push and open a Pull Request

---

## Safety Guidelines

- Register trips where required; hike in groups when possible
- Follow Leave No Trace
- Check weather and trail conditions
- Carry essentials and first aid
- Respect communities and wildlife

---

## License

Open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
  <strong>Do what you love — hiking. Leave the rest to us. 🥾</strong>
</p>

<p align="center">
  Made with care by the HikeConnect team
</p>
