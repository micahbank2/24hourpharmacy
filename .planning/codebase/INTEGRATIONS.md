# External Integrations

**Analysis Date:** 2026-03-28

## APIs & External Services

**Maps & Location Data:**
- Google Maps Platform (Places API + Maps JavaScript API) — pharmacy location lookup and map display
  - SDK/Client: Maps JavaScript API loaded client-side in widgets; Places API called server-side via Python scripts
  - Auth: `GOOGLE_MAPS_API_KEY` env var (passed to JS via `wp_localize_script()`)

**Content Management:**
- WordPress REST API — Python data scripts push generated city pages as drafts
  - Auth: `WP_REST_URL`, `WP_APP_USER`, `WP_APP_PASSWORD` env vars
  - Used by: `data/scripts/generate-city-pages.py`, `data/scripts/sync-pharmacy-data.py`

**Search (Planned):**
- Exa.ai / Websets — planned replacement or supplement for pharmacy finder search
  - Status: Evaluation pending; free tier to be assessed before committing to $49/mo Core plan
  - Use case: Power on-site search and build/verify 24-hour pharmacy location database

## Data Storage

**Databases:**
- WordPress database (MySQL on Hostinger) — all CMS content, options, post types
  - Connection: Managed by WordPress core; credentials not in this repo
  - Custom post types registered in `wordpress/plugin/24hr-pharmacy-tools/includes/class-post-types.php`
  - Affiliate codes stored in WordPress options table via `class-settings.php`

**File Storage:**
- Local filesystem on Hostinger — WordPress uploads, theme, plugin files
- `data/pharmacies/raw/` — cached API responses stored locally during script runs (not production)

**Caching:**
- None detected (no Redis, Memcached, or WP caching plugin in this repo)

## Authentication & Identity

**Auth Provider:**
- WordPress native auth — WP Application Passwords used for REST API access by data scripts
  - `WP_APP_USER` / `WP_APP_PASSWORD` env vars
- No external identity provider (no OAuth, no SSO)

## Affiliate & Monetization Integrations

All affiliate IDs are stored in WordPress options table via `class-settings.php`. Never hardcoded. Accessed in PHP via `get_option()`. Inserted into pages via shortcodes defined in `class-shortcodes.php`.

**Prescription Discount Cards (Primary):**
- LowerMyRx — up to $4/filled prescription; affiliate ID in WP options; widget or API embed
- National Drug Card — $2–$2.50/filled prescription; affiliate ID in WP options

**Prescription Discount Cards (Secondary / A/B Testing):**
- EzRx, SaveonMeds, RxGo, USARx, AffordableMeds — per-prescription CPA; rotate for conversion testing

**Pharmacy Retail Affiliates (via CJ Affiliate / Commission Junction):**
- Walgreens — 2% revenue share; deep links from CJ dashboard
- Rite Aid — 5.6% revenue share
- CVS — revenue share (rate varies)
- eHealthInsurance — $10–$75 CPA per lead/signup
- Signup: https://signup.cj.com (publisher account required)

**Telehealth Affiliates (via Impact / impact.com):**
- Telehealth platforms (GoodRx, Hims, Hers, Ro, etc.) — CPA per signup
- Health & wellness brands — revenue share / CPA
- Signup: https://impact.com (publisher account required)

## Advertising Networks

Ad placement zones are built into all templates using CSS classes: `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer`.

**Current:**
- Google AdSense — baseline display ads; apply after 10+ quality pages live
  - `ADSENSE_PUB_ID` stored in `.env` / WP options
  - Snippet added to `<head>` via `functions.php`

**Planned Upgrades (traffic-gated):**
- Raptive (formerly AdThrive) — 25,000 page views/month threshold; $15–$25+ RPM
- Mediavine — 50,000 sessions/month threshold; $15–$30+ RPM
- Migration path: AdSense → Raptive → Mediavine; remove AdSense when premium network goes live

## Monitoring & Observability

**Error Tracking:** Not detected
**Analytics:** Not explicitly configured in repo (likely Google Analytics added via WordPress admin or theme)
**Logs:** WordPress native error logging; Python scripts output to console

## CI/CD & Deployment

**Hosting:** Hostinger (WordPress 6.x)
**CI Pipeline:** None — no GitHub Actions or automated deploy
**Deployment process (manual):**
1. Zip `wordpress/theme/` → upload via WP Admin > Appearance > Themes
2. Zip `wordpress/plugin/24hr-pharmacy-tools/` → upload via WP Admin > Plugins > Add New
3. Run data scripts locally → push content via WordPress REST API
- See `docs/deployment.md` for full steps

## Environment Configuration

**Required env vars:**
- `GOOGLE_MAPS_API_KEY` — Google Maps Platform access
- `WP_REST_URL` — WordPress site URL for REST API
- `WP_APP_USER` — WordPress application username
- `WP_APP_PASSWORD` — WordPress application password
- `ADSENSE_PUB_ID` — Google AdSense publisher ID (when live)

**Secrets location:**
- `.env` file locally (never committed; `.env.example` is the committed template)
- Affiliate tracking codes in WordPress options table (managed via plugin settings UI)

## Webhooks & Callbacks

**Incoming:** None detected
**Outgoing:** None detected

---

*Integration audit: 2026-03-28*
