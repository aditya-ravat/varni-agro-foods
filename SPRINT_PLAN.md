# Sprint Plan (2-week sprints)

Maps the phased roadmap (`ROADMAP.md` §13) to concrete sprint backlogs. Adjust scope per actual team capacity.

> Assumed team: 1 PM · 1 TL · 2 backend · 2 frontend · 1 QA · 0.5 designer · 0.5 devops

---

## Sprint 0 — Foundation (Wk 1–2)

**Goal:** every developer can clone, run, and deploy.

- [ ] Brand kit signed off (logo, colours, fonts) — designer
- [ ] Domain `varniagrofoods.com` + Cloudflare DNS
- [ ] Cloud accounts: AWS/DO, S3, Razorpay, MSG91, Meta WhatsApp, Sentry, GA4, GSC
- [ ] GitHub orgs, two repos: `varni-backend` (Laravel 11), `varni-frontend` (Nuxt 3)
- [ ] Backend skeleton: Sanctum, Spatie permissions, Scribe, Pint, Pest, Horizon
- [ ] Frontend skeleton: Tailwind, Pinia, i18n, sitemap, robots, og-image, ESLint
- [ ] Dockerfiles + docker-compose for local
- [ ] GitHub Actions: lint + test + build on PR; deploy to dev on merge
- [ ] ER diagram v1 (drawio committed to repo)
- [ ] Design system tokens in code (Tailwind preset)
- [ ] README + CONTRIBUTING + commit conventions

**Demo:** dev environment up, hello-world from Nuxt → Laravel.

---

## Sprint 1 — Public Site Skeleton (Wk 3–4)

- [ ] Layout (header, footer, mobile menu, language switcher)
- [ ] Home page (hero, USPs, capacity counter, services teaser, facilities map teaser, testimonials, CTA)
- [ ] About page
- [ ] Contact page (form → `POST /leads`)
- [ ] Get-quote page (multi-step form)
- [ ] WhatsApp + sticky enquire button
- [ ] Sitemap + robots + base SEO meta
- [ ] Lighthouse pass (≥ 90)

---

## Sprint 2 — Public Site: Services + Facilities + Commodities (Wk 5–6)

- [ ] Services index + detail (`[slug]`)
- [ ] Facilities index (with map) + detail
- [ ] Commodities index + detail (with FAQ + CTA)
- [ ] Pricing page
- [ ] Public lot tracker (`/track`)
- [ ] JSON-LD: Organization, LocalBusiness (per facility), Service, Product, FAQ, Breadcrumb
- [ ] OG image generation per page
- [ ] Hindi i18n strings

**Demo:** marketing site fully navigable.

---

## Sprint 3 — Auth + Admin Shell + Masters Part 1 (Wk 7–8)

- [ ] Auth: login / register / forgot / reset / 2FA
- [ ] Admin layout + sidebar + breadcrumbs + permission directives
- [ ] Users · Roles · Permissions UI
- [ ] Settings (company, taxes, numbering series)
- [ ] Customers + KYC
- [ ] Facilities CRUD (with public SEO fields)
- [ ] Activity log viewer
- [ ] Public site goes live on `varniagrofoods.com` 🚀

---

## Sprint 4 — Masters Part 2: Operations Setup (Wk 9–10)

- [ ] Blocks · Chambers · Storage Locations
- [ ] QR code generator + printable label sheets (A4, 24-up)
- [ ] Commodities · Packings · Seasons
- [ ] Tariffs + surcharges (matrix UI)
- [ ] Vehicles · Vendors
- [ ] Certifications & insurance with expiry alerts

---

## Sprint 5 — Inward Flow (Wk 11–12)

- [ ] Bookings (block capacity, reserve)
- [ ] Pre-arrival notice
- [ ] Gate-in screen (mobile-friendly, one-handed) — vehicle photo, driver KYC
- [ ] Weighbridge entry (+ optional serial integration)
- [ ] Quality check + grade + sample/rejected qty
- [ ] Lot creation + auto location assignment (FIFO + commodity isolation)
- [ ] Photo capture
- [ ] Print labels
- [ ] Stock summary view (live occupancy)

---

## Sprint 6 — Outward + Movements + Reports v1 (Wk 13–14)

- [ ] Movements (shifting, re-bagging)
- [ ] Outward request (from portal)
- [ ] Pick list (FIFO suggestion)
- [ ] Gate-out + gate pass PDF
- [ ] e-Way bill (manual entry first; API integration later)
- [ ] Stock ledger
- [ ] Operations reports: gate register, occupancy, stock summary

---

## Sprint 7 — Billing Engine (Wk 15–16)

- [ ] Rent calculation engine (per-bag-per-month, per-MT-per-day, flat)
- [ ] Apply seasons + surcharges
- [ ] Invoice generator (GST, HSN, PDF)
- [ ] Numbering series with finance-year reset
- [ ] Razorpay integration + webhook
- [ ] Offline payment recording (cash, cheque, NEFT, UPI)
- [ ] Credit / debit notes
- [ ] Customer ledger + aging report

---

## Sprint 8 — Client Portal (Wk 17–18)

- [ ] Portal dashboard
- [ ] My lots / inventory view
- [ ] Bookings, inward & outward requests
- [ ] Invoices + Razorpay pay flow
- [ ] Gate passes + receipts download
- [ ] KYC upload
- [ ] Notifications (email, SMS via MSG91, WhatsApp via Meta)
- [ ] Notification template editor in admin
- [ ] Gujarati i18n

**Demo:** end-to-end client flow → enquiry → booking → intake → bill → pay → release.

---

## Sprint 9 — Finance Polish + Expenses + Reports v2 (Wk 19–20)

- [ ] Expenses module + categories
- [ ] Bank reconciliation
- [ ] P&L per facility / commodity / customer
- [ ] Tally / Zoho CSV export
- [ ] TDS tracking
- [ ] GST helper reports (GSTR-1 / 3B prep)
- [ ] Custom date-range report wrappers
- [ ] Hardening: rate limits, audit review, pen-test fixes
- [ ] Backup & DR drill

**Milestone:** v1.0 — full company rollout.

---

## Sprint 10 — IoT + Maintenance (Wk 21–22)

- [ ] IoT ingest endpoint (MQTT bridge → HTTP)
- [ ] Sensor readings table (partitioned by month)
- [ ] Live chamber dashboard (temp / humidity charts)
- [ ] Threshold breach alerts (WhatsApp / SMS)
- [ ] Asset register + preventive schedule + work orders
- [ ] Spares inventory

---

## Sprint 11 — Mobile App (Gate Clerk + Supervisor) (Wk 23–26)

- [ ] Flutter (or React Native) app
- [ ] Offline-capable gate-in / gate-out
- [ ] Camera + label scanning (QR)
- [ ] Sync queue
- [ ] Push notifications (FCM)
- [ ] Play Store internal release

---

## Definition of Done (every story)

- Code reviewed, merged to `develop`
- Unit + feature tests passing in CI
- Migrations + seeders updated
- API documented (Scribe annotations)
- UI is mobile-responsive and meets a11y AA
- Deployed to staging, QA signed off
- Translation strings added (en/hi/gu)
- Audit log entries for state changes
- Permissions enforced server-side, not just hidden client-side

---

## Risk Watch List (review every retro)

- Tariff exceptions piling up → keep a single override mechanism
- Public-site SEO regressions → Lighthouse-CI on PRs
- Mobile UX for non-tech staff → field-test every two sprints
- Season pressure (potato Feb–Apr) → load-test before season

---

*Edit this file when sprints actually start. Treat it as the source of truth for sprint scope.*
