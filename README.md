# Varni Agro Foods — Cold Storage Platform

**Tagline:** Preserving Freshness, Powering Agriculture.
**Stack:** Laravel 11 (API) · Nuxt 4 (SSR + SPA) · MySQL · Redis · MeiliSearch (optional) · Tailwind CSS · Pinia
**Languages:** English / हिन्दी / ગુજરાતી

This monorepo holds the public marketing website, client portal, and admin panel for Varni Agro Foods Pvt. Ltd.'s cold-storage operations.

```
varni-agro-foods/
├── ROADMAP.md            full project roadmap
├── MODULE_MAP.md         module + role matrix
├── SPRINT_PLAN.md        sprint backlog
├── backend/              Laravel 11 API
└── frontend/             Nuxt 4 (public + portal + admin)
```

---

## 1. One-time setup

### 1.1 Prerequisites

- PHP 8.3+ with extensions: `mbstring`, `xml`, `curl`, `pdo_mysql`, `gd`, `openssl`, `tokenizer`, `fileinfo`, `bcmath`, `intl`, `zip`
- Composer 2
- Node 20+ and npm 10+
- MySQL 8 (or MariaDB 10.11+)
- Redis 7

> On Ubuntu 24.04 install the missing PHP modules with:
> ```
> sudo apt install php8.3-bcmath php8.3-intl php8.3-zip
> ```

### 1.2 MySQL database & user

```sql
CREATE DATABASE varni_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'varni'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL ON varni_erp.* TO 'varni'@'localhost';
FLUSH PRIVILEGES;
```

(or use any credentials and update `backend/.env` accordingly).

### 1.3 Backend — Laravel

```bash
cd backend
composer install                 # if vendor missing
php artisan migrate --seed       # creates schema + sample facility/commodities/admin user
php artisan storage:link
php artisan serve                # runs on http://localhost:8000
```

The seeder creates an admin user:
- **Email:** `admin@varniagrofoods.com`
- **Password:** `password`

### 1.4 Frontend — Nuxt

```bash
cd frontend
npm install                      # if node_modules missing
npm run dev                      # runs on http://localhost:3000
```

Open <http://localhost:3000>. The site loads facilities, commodities and services from the local Laravel API.

---

## 2. Daily commands

| Command | What it does |
|---|---|
| `cd backend && php artisan serve` | Run the API on `:8000` |
| `cd backend && php artisan migrate:fresh --seed` | Reset DB (⚠ destructive) |
| `cd backend && php artisan queue:work` | Process queued jobs (mail, billing) |
| `cd backend && php artisan tinker` | REPL |
| `cd backend && ./vendor/bin/pest` | Run tests |
| `cd frontend && npm run dev` | Run Nuxt dev (HMR) |
| `cd frontend && npm run build` | Production build |
| `cd frontend && npm run preview` | Preview prod build locally |

---

## 3. Project structure

### 3.1 Backend (`backend/`)
```
app/
├── Http/Controllers/Api/
│   ├── Public/         (FacilityController, CommodityController, ServiceController, PostController, MetaController, LeadController)
│   ├── Auth/           (AuthController)
│   └── Admin/          (FacilityController, ChamberController, CommodityController, TariffController, LeadController)
├── Http/Resources/     (FacilityResource, CommodityResource, ServiceResource, PostResource)
└── Models/             (Customer, Facility, Block, Chamber, StorageLocation, Commodity, Packing, Season, Tariff,
                         Booking, Lot, InwardNote, OutwardNote, Movement, GatePass, Invoice, Payment, RentRun,
                         Service, Post, Page, Faq, Testimonial, Banner, Lead, Subscriber, ContactMessage,
                         SensorReading, SensorAlert, Expense, CreditNote, Redirect)
database/
├── migrations/         (users + cold-storage domain tables)
└── seeders/            (RolesAndPermissions, Catalog, Facilities)
routes/
└── api.php             (versioned at /api/v1)
```

### 3.2 Frontend (`frontend/`)
```
app/
├── app.vue, error.vue
├── assets/css/app.css
├── components/site/    (Header, Footer, WhatsAppFab)
├── composables/useApi.ts
├── layouts/            (default, admin, portal)
├── middleware/         (auth, admin, client)
├── pages/
│   ├── index.vue · about.vue · contact.vue · pricing.vue · get-quote.vue · login.vue · register.vue
│   ├── facilities/{index,[slug]}.vue
│   ├── services/{index,[slug]}.vue
│   ├── products/{index,[slug]}.vue
│   ├── blog/{index,[slug]}.vue
│   ├── admin/{index, facilities/index, leads/index}.vue
│   └── portal/index.vue
├── stores/auth.ts
└── i18n/locales/{en,hi,gu}.json
```

---

## 4. SEO & content

- **Sitemap** — `/sitemap.xml` (auto from `@nuxtjs/sitemap`)
- **Robots** — `/robots.txt` (admin/portal blocked)
- **OG images** — auto via `nuxt-og-image`
- **Schema.org** — Organization, LocalBusiness (per facility), Service, BreadcrumbList via `nuxt-schema-org`
- **i18n** — `@nuxtjs/i18n` with `en`, `hi`, `gu`

Edit content in:
- `backend/database/seeders/CatalogSeeder.php` — services, commodities, FAQs, testimonials
- `backend/database/seeders/FacilitiesSeeder.php` — facilities, chambers, locations
- After changes: `php artisan migrate:fresh --seed`
- Once admin pages mature, edit content from `/admin/*` instead of seeders.

---

## 5. API surface (v1)

### Public (no auth)
```
GET  /api/v1/facilities              GET /api/v1/facilities/{slug}
GET  /api/v1/commodities             GET /api/v1/commodities/{slug}
GET  /api/v1/services                GET /api/v1/services/{slug}
GET  /api/v1/posts                   GET /api/v1/posts/{slug}
GET  /api/v1/faqs/{group?}           GET /api/v1/testimonials
GET  /api/v1/banners/{placement}     GET /api/v1/pages/{slug}
GET  /api/v1/sitemap-data
POST /api/v1/quote                   POST /api/v1/contact   POST /api/v1/subscribe
```

### Auth
```
POST /api/v1/auth/login              POST /api/v1/auth/register
GET  /api/v1/auth/me                 POST /api/v1/auth/logout
```

### Admin (auth + role)
```
RESOURCE /api/v1/admin/facilities
RESOURCE /api/v1/admin/chambers
RESOURCE /api/v1/admin/commodities
RESOURCE /api/v1/admin/tariffs
RESOURCE /api/v1/admin/leads (no store)
```

### Portal (auth + role:client)
```
GET /api/v1/portal/dashboard         (lots, bookings, invoices coming in Phase-4)
```

---

## 6. Deploying

- **Backend** — Laravel Forge / Laravel Cloud / DigitalOcean droplet. Use `php artisan optimize` and `queue:work` via supervisor + Horizon.
- **Frontend** — Vercel / Netlify / Node host. Set `NUXT_PUBLIC_API_BASE` to the production API origin.
- **DB** — managed MySQL with hourly backups.
- **CDN / TLS** — Cloudflare in front of both subdomains.
- **Subdomains:** `varniagrofoods.com` (public), `app.varniagrofoods.com` (portal), `admin.varniagrofoods.com` (admin), `api.varniagrofoods.com` (API).

---

## 7. Where to next

Phase-3 work (Operations Core) lays migrations & models for inward / outward / movements / quality / gate passes; admin UI for those flows is the next sprint. See `SPRINT_PLAN.md`.
