# Varni Agro Foods Private Limited — Cold Storage Platform Roadmap

> **Tagline:** Preserving Freshness, Powering Agriculture.
> **Stack:** Nuxt 3 (frontend) · Laravel 11 (backend API) · MySQL · Redis · MeiliSearch · Tailwind CSS · Pinia
> **Goal:** Build an SEO-optimised public website + admin panel for managing cold storage / warehouse operations (chambers, products, bookings, billing, IoT monitoring).

---

## 1. Executive Summary

Varni Agro Foods Pvt. Ltd. requires a unified digital platform that serves **three distinct audiences**:

| Audience | What they need |
|---|---|
| **Public visitors / prospective clients** | Marketing site, services info, capacity, pricing enquiry, blog, contact |
| **Registered clients (farmers / traders / FMCG)** | Self-service portal — book storage, track stock, view invoices, get alerts |
| **Internal staff / admin** | Full operations console — chambers, inventory, billing, IoT, staff, reports |

The system must be **SEO-first** (SSR via Nuxt), **mobile-responsive**, **multilingual** (English + Hindi + Gujarati), and **API-driven** so the same Laravel backend powers the website, the client portal, the admin panel, and (later) a mobile app.

---

## 2. Tech Stack & Architecture

### 2.1 Architecture diagram (logical)

```
                ┌────────────────────────────────────────┐
                │          Public Website (Nuxt SSR)     │  ← SEO, marketing, blog
                └──────────────┬─────────────────────────┘
                               │
                ┌──────────────┴─────────────────────────┐
                │   Client Portal (Nuxt SPA + auth)      │  ← bookings, invoices
                └──────────────┬─────────────────────────┘
                               │   REST + Sanctum tokens
                ┌──────────────┴─────────────────────────┐
                │   Admin Panel (Nuxt SPA, role-gated)   │  ← operations
                └──────────────┬─────────────────────────┘
                               │
                ┌──────────────┴─────────────────────────┐
                │          Laravel 11 API                │
                │  (Sanctum · Spatie Permissions · Jobs) │
                └──────────────┬─────────────────────────┘
                               │
   ┌──────────────┬────────────┼─────────────┬──────────────┐
   │   MySQL 8    │  Redis     │  MeiliSearch│  S3 / Spaces │
   │ (system DB)  │ (cache,Q)  │  (search)   │  (media)     │
   └──────────────┴────────────┴─────────────┴──────────────┘
                               │
                ┌──────────────┴─────────────────────────┐
                │     IoT Gateway  (MQTT → Laravel)      │  ← temp/humidity sensors
                └────────────────────────────────────────┘
```

### 2.2 Frontend (Nuxt 3)

- **Framework:** Nuxt 3 (Vue 3, Composition API, `<script setup>`)
- **Rendering:** Hybrid — `ssr: true` for marketing/blog pages, `ssr: false` for `/admin/*` and `/portal/*`
- **State:** Pinia (`@pinia/nuxt`)
- **HTTP:** `$fetch` + a typed `useApi()` composable that injects Sanctum bearer
- **UI:** Tailwind CSS + Headless UI + custom design tokens (brand colours of Varni)
- **Forms:** VeeValidate + Zod schemas
- **Icons:** Lucide
- **i18n:** `@nuxtjs/i18n` (en, hi, gu)
- **SEO:** `@nuxtjs/sitemap`, `@nuxtjs/robots`, `nuxt-schema-org`, `nuxt-og-image`
- **Analytics:** Plausible / GA4 + Microsoft Clarity
- **Maps:** Mapbox GL or Leaflet for warehouse locator
- **Charts:** ApexCharts / Chart.js (admin dashboards)

### 2.3 Backend (Laravel 11)

- **Auth:** Laravel Sanctum (SPA + token), 2FA (laragear/two-factor)
- **Roles/permissions:** Spatie laravel-permission
- **Search:** Laravel Scout + MeiliSearch
- **Media:** spatie/laravel-medialibrary on S3-compatible storage
- **Activity log:** spatie/laravel-activitylog
- **Settings:** spatie/laravel-settings
- **Queues:** Redis + Horizon
- **Scheduling:** Laravel Scheduler (rent invoices, expiry alerts, IoT polling)
- **PDFs:** barryvdh/laravel-dompdf or spatie/browsershot (invoices, gate-pass)
- **Excel:** maatwebsite/excel
- **Notifications:** Mail (SES/Mailgun) + SMS (MSG91 / Twilio) + WhatsApp (Meta Cloud API)
- **Payments:** Razorpay (primary, India) + Stripe (optional)
- **API docs:** Scribe (auto-generated OpenAPI)
- **Testing:** PestPHP, factories, RefreshDatabase

### 2.4 DevOps

- **Repo layout:** monorepo with `/backend` and `/frontend` (or two repos — see §11)
- **CI/CD:** GitHub Actions → build, test, deploy
- **Hosting (recommended):**
  - Backend: AWS / DigitalOcean droplet + Forge (or Laravel Cloud)
  - Frontend: Vercel / Netlify / self-hosted Node on the same droplet
  - DB: managed MySQL
  - Files: S3 / DO Spaces
- **Domains:** `varniagrofoods.com` (public), `app.varniagrofoods.com` (portal), `admin.varniagrofoods.com` (admin)

---

## 3. Domain Model — Cold Storage Concepts

Before designing tables, fix the vocabulary.

| Term | Meaning |
|---|---|
| **Facility / Branch** | A physical cold storage premises at one address |
| **Block / Building** | A sub-unit inside a facility (Block A, Block B) |
| **Chamber / Cold Room** | A temperature-controlled room (e.g. "Chamber-3, –2 °C, 5000 MT") |
| **Floor / Tier / Rack** | Vertical division inside a chamber |
| **Bay / Lot / Stack** | Addressable storage location: `A-3-F2-B17` |
| **Commodity / Product** | What is stored: potato, apple, frozen peas, dairy, pharma, etc. |
| **Lot / Consignment** | A specific intake batch belonging to one client |
| **Bag / Crate / Carton / Pallet** | The package unit; multiple per lot |
| **Inward / Receipt** | Goods entering the facility |
| **Outward / Delivery** | Goods leaving |
| **Gate Pass** | Document authorising movement in/out |
| **Rent Cycle** | Billing frequency (per-bag-per-month, per-MT-per-day, season) |
| **Season** | E.g. "Potato Season 2026" — a contractual period |
| **Tariff / Rate Card** | Pricing rules per commodity × chamber × duration |

---

## 4. Modules & Features

### 4.1 Public Website (SEO-first, SSR)

| Page | Purpose | Key SEO targets |
|---|---|---|
| `/` Home | Hero, USPs, capacity counter, CTA | "cold storage in <city>", "Varni Agro" |
| `/about` | Company story, leadership, certifications | "Varni Agro Foods" |
| `/services` | Cold storage, controlled-atmosphere, blast freezing, ripening, logistics | "blast freezing service Gujarat" |
| `/services/[slug]` | Detail page per service | Long-tail |
| `/facilities` | Map + list of warehouses | "cold storage near me" |
| `/facilities/[slug]` | Per-facility page (capacity, chambers, photos, address, hours, certifications) | "<city> cold storage facility" |
| `/products` or `/commodities` | What we store (potato, fruit, dairy, pharma, frozen, seeds) | "potato cold storage rates" |
| `/products/[slug]` | Per-commodity guide + FAQ + CTA to enquire | High-value long-tail |
| `/pricing` or `/tariff` | Indicative tariff + enquiry form | "cold storage charges per bag" |
| `/blog` | Articles (storage tips, seasonal advisories) | Topical authority |
| `/blog/[slug]` | Article | Long-tail |
| `/careers` | Open roles + application form | Brand |
| `/contact` | Form + map + phone + WhatsApp deep link | Local SEO |
| `/get-quote` | Multi-step quote calculator | Conversion |
| `/track` | Public consignment tracking by ID + OTP | Utility |
| `/sitemap.xml`, `/robots.txt` | Auto-generated | Crawl |

**Conversion features:**
- Sticky "Enquire Now" + WhatsApp floating button
- Lead form pushes to admin → CRM
- Newsletter signup
- Live capacity-availability widget (read-only) on facility pages

**SEO must-haves:**
- Server-rendered HTML for every public route
- Per-page `<title>`, meta description, OG image (auto-generated via `nuxt-og-image`)
- JSON-LD: `Organization`, `LocalBusiness` (per facility), `BreadcrumbList`, `FAQPage`, `Service`, `BlogPosting`
- Canonical URLs, hreflang for en/hi/gu
- Image optimisation via `<NuxtImg>` (AVIF/WebP, lazy)
- Core Web Vitals budget: LCP < 2.0s, CLS < 0.05, INP < 200ms
- Internal linking (services ↔ facilities ↔ blog ↔ products)
- Schema for reviews / testimonials

### 4.2 Client Portal (`/portal/*`)

For registered customers (traders, farmers, FMCG companies).

- **Dashboard** — current stock summary, dues, alerts
- **My Lots / Inventory** — list of consignments, qty in/out, balance, location
- **Bookings** — request new storage (chamber + commodity + duration)
- **Inward requests** — pre-notify arrival of trucks
- **Outward / delivery requests** — request release of N bags
- **Gate passes** — download PDF
- **Invoices & payments** — list, pay online (Razorpay), download receipt
- **Documents** — upload Aadhaar/PAN/GST, signed agreements
- **Notifications** — email / SMS / WhatsApp toggles
- **Profile & KYC**
- **Support tickets**

### 4.3 Admin Panel (`/admin/*`) — the operations brain

#### A. Master Data
1. **Facilities / Branches** — name, address, GST, lat/lng, photos, certifications, capacity (MT), operating hours, manager
2. **Blocks & Chambers** — per facility: chamber code, type (cold-room / freezer / CA / ripening / blast), set-point temp, humidity, gross/net capacity, current occupancy %, refrigeration unit details
3. **Storage Locations** — bays/racks/levels (auto-generate grid, printable QR labels)
4. **Commodities / Products** — name, category, recommended temp/humidity, default packing, HSN code, GST %, image, SEO fields (slug, meta) for public site
5. **Packing types** — bag (50 kg), crate, carton, pallet, big-bag (1 MT), with weight & volume defaults
6. **Tariff / Rate cards** — matrix: commodity × chamber-type × packing × duration → rate; surcharges (handling, weighing, shifting, fumigation, labour, electricity, after-hours)
7. **Seasons** — start/end dates, applicable rate card, advance %
8. **Vehicles** — owned / hired trucks with capacity, driver
9. **Vendors / Suppliers** — refrigerant, packaging, electricity, AMC contractors
10. **Insurance policies** — per facility / per lot
11. **Certifications** — FSSAI, ISO, NABL, HACCP — file + expiry tracking

#### B. CRM & Clients
- Customer master (individual / firm / company)
- KYC docs (Aadhaar, PAN, GST, FSSAI)
- Credit limit, payment terms
- Linked bank account for refunds
- Lead pipeline (enquiry → quote → booking → repeat)
- Communication log (calls, WhatsApp, email)

#### C. Operations — Inward (Receipt)
1. **Booking / Reservation** — block chamber capacity for a client × commodity × period
2. **Pre-arrival notice** — vehicle, driver, ETA
3. **Gate-in** — weighbridge entry, vehicle photo, driver KYC
4. **Quality check / grading** — sampling, A/B/C grade, rejected qty
5. **Receipt / Inward note** — qty, packing, weight (gross/tare/net), lot number auto-generated, location assignment
6. **Stacking plan** — system suggests optimal location based on FIFO, commodity isolation, weight
7. **Print labels (QR)** — stuck on each lot/pallet
8. **Photos** — at intake (proof)

#### D. Operations — Internal Movement
- Shifting (chamber A → chamber B), reasons (restacking, temp issue, customer request)
- Re-bagging / repacking
- Sorting, sieving, washing (value-added services)
- Damage / loss reporting

#### E. Operations — Outward (Delivery)
1. **Release request** (from portal or counter)
2. **Pick list** — system shows location, FIFO suggestion
3. **Picking confirmation**
4. **Weighbridge out**
5. **Gate pass** (PDF, with QR)
6. **Delivery challan / e-way bill** (GST compliant)
7. **Photos at dispatch**

#### F. Quality & Compliance
- Periodic quality inspections (per lot, per chamber)
- Rodent / pest control schedule
- Fumigation log
- Cleaning & sanitation log
- HACCP CCPs (Critical Control Points)

#### G. IoT / Monitoring (Phase 2)
- Live temperature & humidity per chamber (line charts)
- Door-open events
- Power & generator status
- Refrigeration compressor cycles
- Threshold breach → SMS/email/WhatsApp to manager
- Historical drill-down (last 30 / 90 / 365 days)
- Export CSV (for FSSAI audits)

#### H. Maintenance
- Asset register (compressors, condensers, evaporators, generators, forklifts)
- Preventive maintenance schedule (hours / months)
- Breakdown log → work order → completion
- Spare parts inventory
- AMC contracts & expiry

#### I. HR & Staff
- Employees, departments, designations
- Shift roster, attendance (biometric integration optional)
- Payroll (basic — or integrate later)
- Permissions & roles

#### J. Finance & Billing
- **Rent calculation engine** — generates per-lot bills based on tariff, seasons, days stored, packing units
- **Invoice generation** — GST-compliant, with HSN, e-invoice ready
- **Advance & deposit ledger**
- **Payment recording** — cash, cheque, NEFT, UPI, Razorpay
- **Credit notes / debit notes**
- **Customer ledger** statement
- **TDS** tracking (clients deducting TDS)
- **Receivables aging** — 0-30 / 31-60 / 61-90 / 90+
- **Expenses** — electricity, salaries, fuel, repairs, insurance, refrigerant, packaging
- **Bank reconciliation**
- **P&L per facility**, per chamber, per commodity (profitability analytics)
- **Tally / Zoho Books export** (CSV / API)

#### K. Reports
- Stock summary (live)
- Stock ledger (any commodity, any client, any date range)
- Occupancy report (chamber × date)
- Revenue per facility / commodity / customer
- Rent due / collected
- Inward & outward registers (statutory)
- Gate movement register
- Temperature breach log
- Loss & damage report
- GST reports (GSTR-1, GSTR-3B helper)
- Custom report builder (Phase 3)

#### L. Public-site CMS (inside admin)
- Blog editor (Tiptap rich text)
- Pages editor (about, services)
- Facilities content (managed from facility master, auto-pushed to public site)
- Testimonials
- FAQs
- Banners / hero slides
- SEO meta editor per page
- Lead inbox (from contact / quote forms)

#### M. System
- Users, roles, permissions
- Activity log / audit trail
- Settings (company info, taxes, currencies, numbering series)
- Numbering series (INV/2026/0001 …)
- Notification templates (email / SMS / WhatsApp)
- API keys (for integrations / future mobile app)
- Backup status

---

## 5. Database Schema (high-level)

> Naming: snake_case, plural tables, soft-deletes everywhere relevant, `created_by` / `updated_by` audit columns, ULIDs for public IDs.

### 5.1 Identity & access
- `users` (id, name, email, phone, password, type [admin|staff|client], …)
- `roles`, `permissions`, `model_has_roles` (Spatie)
- `customers` (id, user_id, code, name, type, gstin, pan, credit_limit, …)
- `customer_kyc_documents`
- `customer_addresses`

### 5.2 Facility hierarchy
- `facilities` (id, code, name, slug, address, city, state, pincode, lat, lng, gstin, manager_id, hero_image, seo_title, seo_description, …)
- `facility_certifications`
- `facility_media`
- `blocks` (id, facility_id, code, name)
- `chambers` (id, block_id, code, name, type, temp_min, temp_max, humidity_min, humidity_max, gross_capacity_mt, net_capacity_mt, status)
- `storage_locations` (id, chamber_id, row, level, position, code [A-3-F2-B17], qr_code)

### 5.3 Catalog
- `commodity_categories`
- `commodities` (id, category_id, name, slug, hsn_code, gst_percent, recommended_temp_min, recommended_temp_max, default_packing_id, image, seo_title, seo_description, body_html)
- `packings` (id, name, default_weight_kg, default_volume_m3)
- `seasons` (id, name, start_date, end_date)
- `tariffs` (id, season_id, commodity_id, chamber_type, packing_id, rate, basis [per_bag_per_month | per_mt_per_day | flat], min_charge, advance_percent)
- `tariff_surcharges` (id, tariff_id, name, amount, type [flat|percent])

### 5.4 Operations
- `bookings` (id, customer_id, facility_id, chamber_id, commodity_id, planned_qty, planned_packing_id, season_id, status)
- `lots` (id, lot_number, booking_id, customer_id, facility_id, commodity_id, packing_id, intake_date, intake_qty, balance_qty, gross_weight, net_weight, status)
- `lot_locations` (id, lot_id, storage_location_id, qty)  -- a lot may span multiple bays
- `inward_notes` (id, lot_id, gate_in_at, vehicle_no, driver_name, driver_phone, weighbridge_in, weighbridge_out, photos, …)
- `outward_notes` (id, lot_id, gate_out_at, qty, vehicle_no, driver_name, gate_pass_no, e_way_bill_no, …)
- `movements` (id, lot_id, from_location_id, to_location_id, qty, reason, performed_by, performed_at)
- `gate_passes` (id, type [in|out], lot_id, vehicle_no, generated_pdf_path)
- `quality_checks` (id, lot_id, grade, sample_qty, rejected_qty, notes, photos)
- `loss_damage_reports`

### 5.5 Billing
- `rent_runs` (id, period_start, period_end, status) – the monthly billing job
- `invoices` (id, number, customer_id, facility_id, date, due_date, subtotal, tax, total, status)
- `invoice_lines` (id, invoice_id, lot_id, description, qty, rate, amount, hsn, gst_percent)
- `payments` (id, customer_id, invoice_id, mode, ref_no, amount, paid_at, gateway_payload)
- `credit_notes`, `debit_notes`
- `expense_categories`, `expenses`
- `customer_ledger` (view or table)

### 5.6 Maintenance & monitoring
- `assets` (id, facility_id, type, model, serial, purchase_date, …)
- `maintenance_schedules`
- `work_orders`
- `sensor_readings` (id, chamber_id, recorded_at, temperature, humidity, source)  -- partitioned by month
- `sensor_alerts`

### 5.7 CMS / SEO
- `posts` (id, slug, title, body_html, hero_image, author_id, status, published_at, seo_title, seo_description, og_image)
- `pages` (id, slug, title, body_html, seo_title, seo_description, og_image)
- `faqs` (id, question, answer, group)
- `testimonials`
- `leads` (id, name, phone, email, commodity_id, qty, source, status, notes)
- `subscribers` (newsletter)

### 5.8 System
- `activity_log` (Spatie)
- `settings`, `notification_templates`, `numbering_series`, `media`

---

## 6. API Surface (REST, prefix `/api/v1`)

### 6.1 Public (no auth)
```
GET   /facilities                 list (for map)
GET   /facilities/{slug}
GET   /commodities
GET   /commodities/{slug}
GET   /services
GET   /services/{slug}
GET   /posts
GET   /posts/{slug}
GET   /pages/{slug}
GET   /faqs
GET   /testimonials
POST  /leads                      contact / enquiry
POST  /quote                      quote calculator
POST  /subscribers
POST  /track                      public lot tracking by code+OTP
GET   /sitemap-data
```

### 6.2 Auth
```
POST  /auth/register              (clients)
POST  /auth/login
POST  /auth/logout
POST  /auth/forgot-password
POST  /auth/reset-password
POST  /auth/2fa/verify
GET   /auth/me
```

### 6.3 Client portal (auth: `client`)
```
GET   /portal/dashboard
GET   /portal/lots
GET   /portal/lots/{id}
GET   /portal/bookings
POST  /portal/bookings
POST  /portal/inward-requests
POST  /portal/outward-requests
GET   /portal/invoices
GET   /portal/invoices/{id}/pdf
POST  /portal/invoices/{id}/pay
GET   /portal/gate-passes
GET   /portal/notifications
PATCH /portal/profile
POST  /portal/kyc
```

### 6.4 Admin (auth: `staff` with permissions)
```
# Master
GET|POST|PATCH|DELETE /admin/facilities, /chambers, /commodities, /packings, /tariffs, /seasons …

# Operations
POST  /admin/inward
POST  /admin/inward/{id}/quality-check
POST  /admin/outward
POST  /admin/movements
POST  /admin/gate-passes/{id}/print

# Billing
POST  /admin/rent-runs            run billing job
POST  /admin/invoices
POST  /admin/payments
GET   /admin/reports/stock
GET   /admin/reports/occupancy
GET   /admin/reports/revenue
GET   /admin/reports/aging
GET   /admin/reports/temperature

# IoT
POST  /admin/sensors/ingest        (called by gateway)
GET   /admin/sensors/{chamber_id}
GET   /admin/alerts

# CMS
GET|POST|PATCH|DELETE /admin/posts, /pages, /faqs, /testimonials, /leads, /banners
GET   /admin/leads
PATCH /admin/leads/{id}/status

# System
GET|POST|PATCH|DELETE /admin/users, /roles, /permissions, /settings
GET   /admin/activity-log
```

### 6.5 Webhooks
```
POST  /webhooks/razorpay
POST  /webhooks/whatsapp
POST  /webhooks/sms-delivery
POST  /webhooks/iot/{gateway_id}
```

---

## 7. Roles & Permissions

| Role | Scope |
|---|---|
| **super-admin** | Everything, including settings, user management |
| **branch-manager** | Operations + reports for assigned facilities |
| **operations-supervisor** | Inward, outward, movements, quality |
| **gate-clerk** | Gate-in / gate-out, weighbridge |
| **accounts** | Invoices, payments, ledger, expenses |
| **cms-editor** | Blog, pages, FAQs, testimonials, leads |
| **maintenance** | Assets, work orders |
| **client** | Their own data only (portal) |
| **viewer / auditor** | Read-only across modules |

Implemented with Spatie permissions; permissions are granular (e.g. `invoice.create`, `chamber.update`, `report.revenue.view`).

---

## 8. SEO Strategy (deep-dive)

### 8.1 Technical SEO
- SSR by default for all public routes
- Static generation (ISR-style via Nitro `routeRules`) for blog posts and facility pages with revalidate intervals
- Auto sitemap with `lastmod` from DB, image sitemap, news sitemap
- `robots.txt` with environment-aware rules (block staging)
- Canonical URLs, no trailing slash, lowercase
- 301 redirects manager (table `redirects`, applied via Nuxt middleware)
- 404 + soft-404 monitoring
- Image: `<NuxtImg format="avif,webp" loading="lazy" sizes>` + descriptive `alt`
- Lazy-load below-the-fold; preload hero LCP image
- Avoid layout shifts: explicit `width`/`height`, font `display: swap` with preload
- Critical CSS inlined; defer JS where possible
- HTTPS, HSTS, gzip/brotli, HTTP/2
- `<link rel="preconnect">` to API & CDN
- Structured data: `Organization`, `LocalBusiness` (per facility with `geo`, `openingHours`, `priceRange`), `Service`, `Product` (commodity guides), `FAQPage`, `BreadcrumbList`, `BlogPosting`, `Review`
- OG / Twitter cards auto-generated per page (`nuxt-og-image`)
- Hreflang alternates for `en-IN`, `hi-IN`, `gu-IN`

### 8.2 Content SEO
- Keyword research per service & commodity (use Ahrefs/SEMrush for India + Gujarat/regional)
- City-targeted landing pages: `/cold-storage/<city>` for each city served
- Pillar + cluster: pillar = "Cold Storage Services"; clusters = potato, fruit, dairy, pharma, frozen
- Blog cadence: 2 posts / month minimum, seasonal advisories during potato/onion/mango seasons
- FAQ blocks on every service & commodity page → FAQ schema
- Internal linking matrix
- Author bio pages (E-E-A-T)
- Customer case studies / testimonials with `Review` schema

### 8.3 Local SEO
- Google Business Profile per facility, NAP consistent across site
- `LocalBusiness` JSON-LD per facility page
- Reviews integration
- Embed Google Maps with proper schema
- Submit to IndiaMART, Justdial, Sulekha (cold-storage directories)

### 8.4 Performance budget
- LCP < 2.0s on 4G, < 1.2s on cable
- CLS < 0.05
- INP < 200ms
- Total JS < 200 KB gzipped on home
- Lighthouse ≥ 95 on all four categories for marketing pages

---

## 9. Frontend Project Structure (Nuxt)

```
frontend/
├─ assets/
│  └─ css/  (tailwind.css, fonts)
├─ components/
│  ├─ public/        (Hero, Stats, ServiceCard, FacilityMap, Cta, …)
│  ├─ portal/
│  ├─ admin/
│  └─ ui/            (Button, Input, Modal, DataTable, …)
├─ composables/
│  ├─ useApi.ts
│  ├─ useAuth.ts
│  ├─ usePermissions.ts
│  ├─ useSeoMeta.ts
│  └─ useToast.ts
├─ layouts/
│  ├─ default.vue    (public)
│  ├─ portal.vue
│  └─ admin.vue
├─ middleware/
│  ├─ auth.ts
│  ├─ guest.ts
│  ├─ role.ts
│  └─ redirects.global.ts
├─ pages/
│  ├─ index.vue
│  ├─ about.vue
│  ├─ services/
│  │  ├─ index.vue
│  │  └─ [slug].vue
│  ├─ facilities/
│  │  ├─ index.vue
│  │  └─ [slug].vue
│  ├─ products/
│  │  ├─ index.vue
│  │  └─ [slug].vue
│  ├─ blog/
│  │  ├─ index.vue
│  │  └─ [slug].vue
│  ├─ pricing.vue
│  ├─ contact.vue
│  ├─ get-quote.vue
│  ├─ track.vue
│  ├─ login.vue
│  ├─ register.vue
│  ├─ portal/
│  │  ├─ index.vue
│  │  ├─ lots.vue
│  │  ├─ bookings/...
│  │  ├─ invoices/...
│  │  └─ profile.vue
│  └─ admin/
│     ├─ index.vue (dashboard)
│     ├─ facilities/...
│     ├─ chambers/...
│     ├─ commodities/...
│     ├─ tariffs/...
│     ├─ inward/...
│     ├─ outward/...
│     ├─ invoices/...
│     ├─ reports/...
│     ├─ cms/...
│     └─ settings/...
├─ plugins/
│  ├─ api.ts
│  ├─ pinia.ts
│  └─ sentry.client.ts
├─ server/
│  └─ api/  (BFF endpoints if needed; sitemap)
├─ stores/
│  ├─ auth.ts
│  ├─ ui.ts
│  └─ admin/...
├─ types/
├─ public/
├─ nuxt.config.ts
├─ tailwind.config.ts
├─ tsconfig.json
└─ package.json
```

---

## 10. Backend Project Structure (Laravel)

```
backend/
├─ app/
│  ├─ Console/
│  │  └─ Commands/  (RentRunCommand, SensorPollCommand, …)
│  ├─ Http/
│  │  ├─ Controllers/
│  │  │  ├─ Api/Public/
│  │  │  ├─ Api/Auth/
│  │  │  ├─ Api/Portal/
│  │  │  └─ Api/Admin/
│  │  ├─ Requests/   (FormRequest validation)
│  │  ├─ Resources/  (API resources)
│  │  └─ Middleware/
│  ├─ Models/
│  ├─ Policies/
│  ├─ Services/      (BillingService, InventoryService, SeoService, IotService …)
│  ├─ Actions/       (Inward, Outward, Movement, GenerateInvoice, …)
│  ├─ Jobs/
│  ├─ Events/, Listeners/, Notifications/
│  ├─ Observers/
│  └─ Support/
├─ database/
│  ├─ migrations/
│  ├─ factories/
│  └─ seeders/
├─ routes/
│  ├─ api.php
│  └─ web.php (minimal)
├─ tests/
│  ├─ Feature/
│  └─ Unit/
├─ config/
└─ ...
```

---

## 11. Repository & Branching

- **Two repos** recommended: `varni-backend`, `varni-frontend`
  (clean CI, separate deploys; or use a monorepo with PNPM + Turbo if preferred)
- Branches: `main` (prod), `develop` (staging), `feature/*`, `fix/*`
- Conventional commits, PR template, required reviews, GitHub Actions CI on every PR

---

## 12. Environments

| Env | Purpose | URL |
|---|---|---|
| local | Developer machines | localhost |
| dev | Shared sandbox | `dev.varniagrofoods.com` |
| staging | UAT, client review | `staging.varniagrofoods.com` |
| production | Live | `varniagrofoods.com` |

`.env` per env, secrets in GitHub Actions / Forge.

---

## 13. Phased Delivery Roadmap

> Total estimated effort assuming a small team (1 PM + 1–2 backend + 1–2 frontend + 1 QA): **~6 months to v1.0** with monitoring/IoT in v1.5.

### Phase 0 — Foundation (Week 1–2)
- Brand kit (logo, colours, typography) — confirm with client
- Domain + email + cloud accounts (AWS/DO, Cloudflare, S3, Razorpay, MSG91, Meta WhatsApp)
- Repos, CI skeleton, ESLint/Prettier/Pint, Husky pre-commit
- Laravel 11 install + Sanctum + Spatie permissions + Scribe
- Nuxt 3 install + Tailwind + i18n + sitemap module + base layout
- Design system (Figma or in-code) for buttons/forms/tables/typography
- Database design freeze; ER diagram
- Production-grade Dockerfiles + GitHub Actions deploy

### Phase 1 — Public Marketing Site (Week 3–6)
- All public pages: home, about, services (+ details), facilities (+ details), products/commodities (+ details), pricing, blog, contact, get-quote, careers, track
- CMS in admin for posts, pages, FAQs, testimonials, leads, banners
- SEO: sitemap, robots, JSON-LD, OG image, meta editor, redirects
- i18n (en + hi); gu added in Phase 4
- Lead capture → admin inbox + email/WhatsApp notify
- Newsletter
- Cookie banner (DPDP-ready), privacy & terms pages
- Lighthouse / PageSpeed > 95 sign-off
- Analytics + Search Console verified

**Milestone:** Public site goes live on `varniagrofoods.com`. Marketing can start running ads.

### Phase 2 — Identity & Master Data (Week 5–7, partly parallel)
- Auth (login, register, forgot, 2FA, role middleware)
- Customers / KYC
- Facilities, blocks, chambers, locations (with QR code generation & printable label sheets)
- Commodities, packings, seasons, tariffs, surcharges
- Settings, numbering series, taxes
- Activity log

### Phase 3 — Operations Core (Week 7–12)
- Bookings & reservation
- Inward (gate-in, weighbridge, quality, location assignment, lot creation, photos, label print)
- Internal movements
- Outward (release request, pick list, gate-out, gate pass PDF, e-way bill)
- Stock ledger & live occupancy
- Operations reports (stock, occupancy, gate register)

### Phase 4 — Billing & Client Portal (Week 11–16)
- Rent calculation engine with seasons & surcharges
- Invoice generator (GST, HSN, PDF, e-invoice ready)
- Payments (Razorpay) + payment recording (offline)
- Credit / debit notes
- Customer ledger & aging
- Client portal (dashboard, lots, bookings, invoices, gate passes, KYC, profile, support)
- WhatsApp / SMS / email notifications (templated)
- Add Gujarati i18n

**Milestone:** Internal staff can run full operations + billing. Pilot with 1 facility.

### Phase 5 — Reports, Finance & Polish (Week 16–20)
- Full reports module (revenue, profitability per facility/commodity/customer, aging, breach, loss)
- Expenses, bank reconciliation, P&L per facility
- Tally / Zoho export
- HR (employees, attendance via biometric CSV import)
- Maintenance (assets, work orders)
- Audit hardening, role/permission review, pen-test fixes
- Backup & disaster-recovery drill

**Milestone:** v1.0 — ready for company-wide rollout.

### Phase 6 — IoT Monitoring & Mobile (Week 20–26)
- IoT gateway integration (MQTT → ingest endpoint)
- Live temp/humidity dashboard, alerts, exports
- Sensor anomaly detection (basic threshold + later ML)
- Native mobile app (Flutter or React Native) for gate clerks & supervisors (offline-capable)
- WhatsApp Cloud API two-way (clients reply with "STOCK" → bot answers)

### Phase 7 — Advanced (post-launch)
- Custom report builder
- AI-based demand forecasting (chamber occupancy by season)
- Loyalty / referral program
- E-invoice & e-way bill direct API integration with NIC
- Marketplace / B2B trading (future)

---

## 14. Non-Functional Requirements

| Area | Target |
|---|---|
| Availability | 99.9 % monthly |
| Backups | Hourly DB snapshot, daily off-site, 30-day retention |
| RPO / RTO | RPO 1 h, RTO 4 h |
| Security | OWASP Top 10, Sanctum tokens, 2FA for admin, rate-limited APIs, signed URLs for media, encrypted at rest |
| Compliance | India DPDP Act, GST e-invoice, FSSAI record-keeping |
| Scalability | Horizontal: stateless API, queue workers separate, Redis cache, MeiliSearch separate |
| Observability | Sentry (errors), Logtail / CloudWatch (logs), UptimeRobot, Laravel Pulse |
| Accessibility | WCAG 2.1 AA on public site |

---

## 15. Risk Register (top items)

| Risk | Mitigation |
|---|---|
| Power / connectivity failure at facility | Offline-first mobile app + local sync queue |
| IoT vendor lock-in | Use MQTT-standard, abstract gateway layer |
| Tariff complexity (every client wants exceptions) | Tariff engine with overrides per customer |
| GST rule changes | Rate cards & HSN driven by master data; e-invoice via official API |
| Performance under intake season (potato Feb–Apr) | Load-test before season; queue-driven heavy ops |
| Data loss | Backups + activity log + soft deletes |
| Adoption by non-tech staff | Hindi/Gujarati UI + training sessions + simple mobile UX |

---

## 16. Team & Roles

| Role | Responsibility |
|---|---|
| Product Manager | Requirements, prioritisation, client comms |
| Tech Lead / Architect | Design, code review, infra |
| Backend (1–2) | Laravel API, billing, jobs |
| Frontend (1–2) | Nuxt public + portal + admin |
| QA | Test plans, automation, UAT |
| DevOps (part-time) | CI/CD, infra, monitoring |
| Designer | UI/UX, brand, marketing assets |
| Content / SEO | Copy, blog, schema, GBP |

---

## 17. Deliverables Checklist (v1.0)

- [ ] Branded design system (Figma)
- [ ] Public marketing website (SSR, SEO ≥ 95 Lighthouse)
- [ ] Admin panel with all modules in §4
- [ ] Client portal
- [ ] Laravel API (documented via Scribe / OpenAPI)
- [ ] Database with seed data (3 sample facilities, sample commodities, sample tariffs)
- [ ] Auth + RBAC + audit log
- [ ] Billing engine + Razorpay
- [ ] WhatsApp / SMS / Email notifications
- [ ] Reports (stock, occupancy, revenue, aging, GST helpers)
- [ ] Backup + monitoring + Sentry
- [ ] Multilingual (en, hi, gu)
- [ ] Sample data + UAT sign-off
- [ ] Training docs (admin & client)
- [ ] Production deploy + Search Console + Analytics

---

## 18. Immediate Next Steps (this week)

1. **Confirm scope** of v1 with stakeholder (any module out of §4 to deprioritise?)
2. **Brand assets** — logo + colours + typography + photography brief
3. **Domain** purchase & DNS routed to Cloudflare
4. **Cloud accounts** — AWS / DO, S3, Razorpay (KYB), MSG91, Meta Business
5. **Bootstrap repos** — `varni-backend` (Laravel 11) + `varni-frontend` (Nuxt 3) with the scaffold from §9 / §10
6. **ER diagram** review & sign-off
7. **Sprint 0** kickoff — set 2-week sprints, weekly demo to client

---

*Document owner: Tech Lead. Last updated: 2026-05-08.*
