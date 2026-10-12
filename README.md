# Parish Website - Paroisse Saint-Joseph Artisan

A full-featured Catholic parish management web application built for **Paroisse Saint-Joseph Artisan - Pacita**, San Pedro, Laguna. Provides mass schedules, mass intention submissions, event management, gallery, AI-powered chatbot, and a comprehensive admin panel.

**Live:** [https://sto-rosario-parish.onrender.com](https://sto-rosario-parish.onrender.com)

## Features

### Public-Facing
- **Home Page** - Hero section, next mass countdown, quick actions, events & announcements feed
- **About Page** - Parish history timeline, sacred history entries, mission/vision
- **Mass Schedule** - Weekly schedule display with iCal export
- **Mass Intention Submission** - Online form with `INT-YYYY-XXXX` auto-generated reference IDs, email notifications, and tracking
- **Intention & Inquiry Tracking** - Search and look up intention or inquiry status by Reference Number (`INT-...`/`INQ-...`) or Email Address
- **Events** - Event listing with calendar view and detail pages
- **Gallery** - Photo/video album browsing with video highlights and queued background uploads (`UploadGalleryMediaJob`)
- **Bulletin Board** - Downloadable parish bulletins
- **Inquiry Form** - Contact/inquiry submission with `INQ-YYYY-XXXX` reference IDs and accept/decline workflow
- **Donation Page** - GCash, Maya, QR Ph, and card payment integration via PayMongo checkout with automated email receipts and signed PDF receipt routing (`DonationReceiptMail`)
- **AI Chatbot (RAG + Tool Calling)** - Intelligent AI concierge with Hybrid Retrieval-Augmented Generation (RAG), OpenAI-standard tool calling (`check_intention_status`, `check_inquiry_status`, `get_mass_schedules`, `get_upcoming_events`, `search_announcements`, `get_sacrament_requirements`), prompt-injection protection, live agent handoff, and local keyword fallback
- **Chatbot Evaluation Suite** - Built-in benchmark suite (`php artisan chatbot:eval`) evaluating RAG precision, tool calling execution, latency, and hallucination guardrails
- **Facebook Live Integration** - Webhook endpoint for live Mass notifications with platform/YouTube link toggling
- **Privacy & Legal Pages** - Privacy Policy (`/privacy-policy`) and Terms & Conditions (`/terms`) linked from the footer
- **Consent-Gated Analytics** - Cookie banner (Essential Only / Accept All) that loads GA4 only after explicit opt-in
- **Form Consent** - Mandatory consent checkboxes on the inquiry, mass intention, and donation forms, validated both client- and server-side
- **Custom Error Pages** - Styled 401, 403, 404, 405, 419, 429, 500, 502, 503, 504 pages
- **PWA Manifest** - Web manifest with branded theme color

### Admin Panel
- **Dashboard** - Overview stats, activity logs, and real-time notification polling (`/internal/notifications/count`)
- **Mass Intentions** - CRUD with batch status updates, email notifications, rejection reasons, and reference number tracking
- **Announcements** - CRUD with category support, featured announcements, recruitment fields, and date ranges
- **Events** - CRUD with multiple time slots and location management
- **Gallery** - Album management, image uploads, video highlights with reordering, and queued cloud uploads
- **Schedules** - Mass schedule management
- **Inquiries** - Review, accept, or decline inquiries with rejection reasons
- **Live Chat** - Real-time chat monitoring, agent replies, session pause/resume, and stale session pruning
- **Bulletins** - Public listing and download (admin CRUD currently disabled)
- **Users** - User management with role-based access (super_admin, staff, soccom) and active/inactive status
- **Settings** - Global site settings management with section-based updates
- **PPT Tools** - Auto-generate PowerPoint presentations from mass schedules (via PhpPresentation)
- **Google Slides** - Direct Google Slides integration via OAuth
- **Activity Logs** - Queued activity logging with sync fallback

## Legal, Compliance & Accessibility

| Area | Implementation |
|------|----------------|
| Cookie consent | Alpine `cookieConsent()` component in `components/public-layout.blade.php` (bottom banner, `role="region"`). Shown until the visitor chooses; choice persisted in `localStorage` key `parish_cookie_consent` |
| Analytics opt-in | GA4 is **only** injected after "Accept All" — gtag script appended with `anonymize_ip: true`, guarded by `window.__gaLoaded`. "Essential Only" sets consent without loading anything |
| Analytics config | `GTM_ID` env var → `config/services.php` (`analytics.gtm_id`). The "Accept All" button is hidden when unset (see GA4 Measurement ID setup below) |
| Form consent | `consent` field added to inquiry, mass intention, and donation forms with `required\|accepted` validation in `InquiryController`, `IntentionController`, and `DonationController` plus custom error messages; front-end gating too |
| Privacy pages | `privacy-policy.blade.php` and `terms.blade.php` behind `/privacy-policy` and `/terms` routes, linked in the footer Quick Links; consent labels link to both |
| Embed privacy | Third-party embeds hardened: YouTube uses `youtube-nocookie.com/embed` (`live-mass-slot`, `album-show`) and the Facebook plugin sets `dnt=1` |
| Accessibility | Global `:focus-visible` outlines (`resources/css/app.css`), pre-existing skip-link in the public layout, `aria-label` on the back-to-top button, corrected heading hierarchy, and WCAG AA contrast fixes (stat labels, eyebrows, chatbot timestamps) |

#### GA4 Measurement ID setup

1. Google Analytics → Admin → **Create property** (e.g. "Paroisse Saint-Joseph Artisan Website").
2. Admin → **Data Streams → Add stream → Web** → enter `https://sto-rosario-parish.onrender.com`.
3. Copy the **Measurement ID** (format `G-XXXXXXXXXX`) from the stream page — this is your `GTM_ID`.
4. On Render: service **Environment → Add Environment Variable** → `GTM_ID` = your ID → redeploy.
   *(GTM_ID is read at runtime — no `config:cache` in the deploy path, so no file changes needed. Never put it in `.env` for production; `.env` is gitignored and not deployed.)*

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.4+ |
| Frontend | Tailwind CSS v4, Alpine.js, Vite 7 |
| Database | SQLite (dev) / PostgreSQL (production) |
| Storage | Supabase (S3-compatible) |
| AI | Groq / OpenRouter API |
| PDF | Dompdf (receipt generation) |
| Presentations | PhpPresentation (PPT generation) |
| Integrations | Google Drive/Slides API, Facebook Live webhook, PayMongo |

## Requirements

- PHP 8.4+
- Composer
- Node.js 20+
- npm

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/JamesMangao/parish_website
   cd parish-website
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies and build assets**
   ```bash
   npm install
   npm run build
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your `.env`** with the following keys:
   - Database settings (SQLite by default)
   - `SUPABASE_*` keys for cloud storage
   - `GROQ_API_KEY` or `OPENROUTER_API_KEY` for AI chatbot
   - `GOOGLE_DRIVE_FOLDER_ID` and `GOOGLE_SHARE_EMAIL` for Slides integration
   - `GCASH_NUMBER`, `MAYA_NUMBER`, `BANK_DETAILS` for donation info
   - `PAYMONGO_SECRET_KEY`, `PAYMONGO_PUBLIC_KEY`, `PAYMONGO_WEBHOOK_SECRET` for payment processing
   - `FACEBOOK_APP_SECRET`, `FACEBOOK_PAGE_ID`, `FACEBOOK_PAGE_ACCESS_TOKEN` for Facebook Live webhook
   - `GTM_ID` (optional) for consent-gated Google Analytics — see [GA4 setup](#ga4-measurement-id-setup)

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed sample data** (optional)
   ```bash
   php artisan db:seed
   ```

8. **Create an admin user**
   ```bash
   php artisan tinker
   ```
   ```php
   User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'super_admin', 'is_active' => true]);
   ```

9. **Start the development server**
   ```bash
   composer dev
   ```
   This runs the artisan server, queue worker, pail log viewer, and Vite dev server concurrently.

## Docker

A multi-stage Dockerfile is included for containerized deployment:

```bash
docker build -t parish-pal .
docker run -p 80:80 parish-pal
```

The app will be available at `http://localhost:80`.

**Multi-stage build:** Stage 1 uses `node:20-alpine` to compile frontend assets; Stage 2 uses `php:8.4-fpm-alpine` with Nginx for production serving. Includes opcache + JIT tuning, upload limits (128M/256M), and on-demand PHP-FPM.

## Deployment

The project is deployed on **Render** with:
- **Web service** (`parish-website`) — Docker-based, runs the Laravel app behind Nginx
- **Worker service** (`parish-queue-worker`) — runs `php artisan queue:work --tries=3 --timeout=60 --sleep=3` for background jobs (donation receipts, gallery uploads, activity logs, inquiry notifications)
- **Scheduled task** — `chat:prune-stale` runs hourly to resolve abandoned chat sessions

See `render.yaml` for the full infrastructure-as-code configuration.

## Testing

```bash
php artisan test
```

Tests use SQLite `:memory:`, array cache, sync queue, and array mail driver. CI runs on GitHub Actions (PHP 8.4 + Node 20 + MySQL 8.0).

## Project Structure

```
app/
├── Console/Commands/       # Artisan commands (image migration, chat pruning, timeline seed, asset upload)
├── Data/                   # Seed data (DefaultTimeline for Sacred History entries)
├── Http/
│   ├── Controllers/        # 27 controllers (public + admin + auth)
│   ├── Middleware/          # Role-based access control
│   └── Requests/           # Form request validation (3 requests)
├── Jobs/                   # Queued jobs (activity logging, gallery upload, inquiry notification)
├── Mail/                   # Email classes (donation receipt, inquiry accepted, intention received)
├── Models/                 # 15 Eloquent models
├── Notifications/          # Mail notifications (4 types)
├── Providers/              # App service provider (HTTPS forcing, rate limiters, global view sharing)
├── Services/               # AI service (OpenRouter/Groq race + local fallback), activity logging
└── Support/                # Video embed helpers (YouTube URL parsing)
resources/
├── css/                    # Tailwind v4 + custom parish design tokens and utilities
├── js/                     # Alpine.js stores, components, and bootstrap
└── views/
    ├── admin/              # Admin panel views (22+ templates)
    ├── announcements/      # Public announcement listing and detail
    ├── bulletins/          # Public bulletin listing
    ├── components/         # Reusable layout components (22 components)
    ├── emails/             # Email blade templates (5 templates)
    ├── errors/             # Custom error pages (9 error codes)
    ├── partials/           # Reusable form partials
    ├── pdfs/               # PDF templates (donation receipt via dompdf)
    └── *.blade.php         # Public page views (17 templates)
routes/
├── web.php                 # All routes (public + admin)
└── console.php             # Scheduled tasks (chat:prune-stale hourly)
database/
├── migrations/             # 44 migration files
└── seeders/                # Database seeder (admin users, sample data)
```

## Roles

| Role | Permissions |
|------|------------|
| `super_admin` | Full access to all features, user management, settings, activity logs |
| `staff` | Mass intentions (CRUD + batch updates), inquiries, donations, PPT/Slides tools |
| `soccom` | Announcements, events, gallery (+ highlights), schedules, inquiries, live chat, Facebook Live management |

## Custom Artisan Commands

| Command | Description |
|---------|-------------|
| `php artisan migrate:images` | Upload local storage images to Supabase cloud storage |
| `php artisan assets:upload-supabase` | Upload local public image assets to Supabase storage bucket |
| `php artisan chat:prune-stale {--days=2}` | Mark stale/abandoned chat sessions as resolved |
| `php artisan parish:seed-timeline` | Seed Sacred History timeline entries (1982-2025) |
| `php artisan chatbot:eval {--quick}` | Run automated evaluation benchmark for RAG retrieval, tool calling, and guardrails |

## License

MIT License
