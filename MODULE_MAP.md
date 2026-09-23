# Module Map — At a Glance

Quick-scan companion to `ROADMAP.md`. Every box below maps to a section in the main roadmap.

```
VARNI AGRO FOODS — COLD STORAGE PLATFORM
│
├── 🌐 PUBLIC WEBSITE (Nuxt SSR · SEO-first)
│   ├── Home  ·  About  ·  Services  ·  Facilities (map)  ·  Commodities
│   ├── Pricing  ·  Blog  ·  Careers  ·  Contact  ·  Get-Quote  ·  Track
│   └── i18n: English · हिंदी · ગુજરાતી
│
├── 👤 CLIENT PORTAL  (Nuxt SPA · Sanctum auth)
│   ├── Dashboard          – live stock + dues
│   ├── My Lots            – consignments, balances, locations
│   ├── Bookings           – request new storage
│   ├── Inward / Outward   – pre-notify trucks, request release
│   ├── Gate Passes        – PDF download
│   ├── Invoices           – view, pay (Razorpay), receipt
│   ├── Documents / KYC    – Aadhaar, PAN, GST
│   └── Notifications      – email / SMS / WhatsApp prefs
│
├── 🛠️ ADMIN PANEL  (Nuxt SPA · role-gated)
│   │
│   ├── 📦 MASTERS
│   │   ├── Facilities / Branches
│   │   ├── Blocks → Chambers → Locations  (QR-labelled)
│   │   ├── Commodities (with public SEO fields)
│   │   ├── Packings  ·  Seasons  ·  Tariffs  ·  Surcharges
│   │   ├── Vehicles  ·  Vendors  ·  Insurance  ·  Certifications
│   │   └── Customers + KYC
│   │
│   ├── 📥 INWARD (Receipt)
│   │   Booking → Pre-arrival → Gate-in → Weighbridge →
│   │   Quality check → Lot creation → Location assignment →
│   │   QR labels → Photos
│   │
│   ├── 🔄 INTERNAL MOVEMENT
│   │   Shifting · Re-bagging · Sorting · Damage reports
│   │
│   ├── 📤 OUTWARD (Delivery)
│   │   Release request → Pick list (FIFO) → Picking →
│   │   Weighbridge out → Gate pass PDF → e-Way bill → Photos
│   │
│   ├── ✅ QUALITY & COMPLIANCE
│   │   Inspections · Pest control · Fumigation · Sanitation · HACCP CCPs
│   │
│   ├── 🌡️ IoT MONITORING                 (Phase 2)
│   │   Live temp/humidity · Door events · Power · Compressor cycles
│   │   Threshold alerts (SMS / WhatsApp) · Historical exports for FSSAI
│   │
│   ├── 🔧 MAINTENANCE
│   │   Asset register · Preventive schedule · Work orders · Spares · AMCs
│   │
│   ├── 👥 HR
│   │   Employees · Shifts · Attendance · (optional) Payroll
│   │
│   ├── 💰 FINANCE & BILLING
│   │   Rent engine → Invoices (GST · HSN · e-invoice ready)
│   │   Payments (Razorpay + offline) · Credit/Debit notes
│   │   Customer ledger · Aging · TDS
│   │   Expenses · Bank recon · P&L per facility
│   │   Tally / Zoho export
│   │
│   ├── 📊 REPORTS
│   │   Stock · Occupancy · Revenue · Profitability · Aging
│   │   Gate register · Temperature breach · Loss/damage · GST helpers
│   │
│   ├── ✍️ CMS (powers public site)
│   │   Blog · Pages · FAQs · Testimonials · Banners · SEO meta
│   │   Lead inbox  ·  Newsletter subscribers
│   │
│   └── ⚙️ SYSTEM
│       Users · Roles · Permissions · Settings · Numbering series
│       Notification templates · API keys · Activity log · Backups
│
└── 🔌 INTEGRATIONS
    Razorpay · MSG91 (SMS) · Meta WhatsApp Cloud API · SES/Mailgun
    NIC e-invoice / e-way bill · MQTT (IoT) · Tally / Zoho
    Google Search Console · GA4 · Plausible · Sentry
```

## Role × Module Access Matrix

| Module / Role          | super-admin | branch-mgr | ops-supv | gate-clerk | accounts | cms-editor | maintenance | client | viewer |
|------------------------|:-----------:|:----------:|:--------:|:----------:|:--------:|:----------:|:-----------:|:------:|:------:|
| Facilities (CRUD)      | ✅          | 🔍         | 🔍       | 🔍         | 🔍       | 🔍         | 🔍          | ❌     | 🔍     |
| Chambers / Locations   | ✅          | ✅         | ✅       | 🔍         | 🔍       | ❌         | 🔍          | ❌     | 🔍     |
| Commodities / Tariffs  | ✅          | 🔍         | 🔍       | ❌         | ✅       | 🔍         | ❌          | 🔍     | 🔍     |
| Customers / KYC        | ✅          | ✅         | 🔍       | 🔍         | ✅       | ❌         | ❌          | self   | 🔍     |
| Inward                 | ✅          | ✅         | ✅       | ✅         | 🔍       | ❌         | ❌          | self*  | 🔍     |
| Outward                | ✅          | ✅         | ✅       | ✅         | 🔍       | ❌         | ❌          | self*  | 🔍     |
| Movements              | ✅          | ✅         | ✅       | 🔍         | ❌       | ❌         | ❌          | ❌     | 🔍     |
| Quality & Compliance   | ✅          | ✅         | ✅       | ❌         | ❌       | ❌         | 🔍          | ❌     | 🔍     |
| Invoices / Payments    | ✅          | 🔍         | ❌       | ❌         | ✅       | ❌         | ❌          | self   | 🔍     |
| Expenses / P&L         | ✅          | 🔍         | ❌       | ❌         | ✅       | ❌         | ❌          | ❌     | 🔍     |
| Reports                | ✅          | scoped     | scoped   | ❌         | ✅       | ❌         | scoped      | ❌     | ✅     |
| IoT / Alerts           | ✅          | ✅         | ✅       | 🔍         | ❌       | ❌         | ✅          | ❌     | 🔍     |
| Maintenance            | ✅          | ✅         | ❌       | ❌         | ❌       | ❌         | ✅          | ❌     | 🔍     |
| CMS / SEO / Leads      | ✅          | 🔍         | ❌       | ❌         | ❌       | ✅         | ❌          | ❌     | 🔍     |
| System / Users / Roles | ✅          | ❌         | ❌       | ❌         | ❌       | ❌         | ❌          | ❌     | ❌     |

Legend: ✅ full · 🔍 read-only · ❌ none · *self* = own records only · *self\** = request only, staff confirms

## Storage Location Code Convention

`<FacilityCode>-<Block>-<Chamber>-<Row>-<Level>-<Bay>` → e.g. `VAR-MOR-A-03-F2-B17`

- `VAR` = Varni · `MOR` = Morbi facility · `A` = Block A · `03` = Chamber 3 · `F2` = Floor/Level 2 · `B17` = Bay 17
- Each location gets a printable QR; scanning goes to `/admin/locations/<code>` showing live occupancy.

## Lot Number Convention

`<FacilityCode>/<SeasonCode>/<Sequential>` → e.g. `VAR-MOR/POT26/00472`
