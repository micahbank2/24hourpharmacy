# Roadmap: 24HourPharmacy.com

**Created:** 2026-03-28
**Granularity:** Standard
**Parallelization:** Enabled
**Total Phases:** 8

---

## Phase 1: WordPress Plugin Foundation

**Goal:** A working WordPress plugin that registers post types, provides an admin settings page for affiliate codes, outputs compliance shortcodes, and emits valid JSON-LD schema — so that city and pharmacy URLs exist, settings can be stored, and every page has the required legal and structured data output.

**Requirements covered:** INFRA-01, INFRA-02, INFRA-03, INFRA-04, INFRA-05


**Plans:** 1 plan

Plans:
- [x] 01-01-PLAN.md — Complete plugin foundation (CPTs, settings, schema, shortcodes, theme config)
**Deliverables:**
- `class-post-types.php` registers City and Pharmacy CPTs with correct rewrite rules
- `class-settings.php` admin page stores all API keys and affiliate codes in WP options table
- `class-schema.php` outputs JSON-LD (LocalBusiness/Pharmacy, WebPage, FAQPage) on relevant pages
- `class-shortcodes.php` registers `[medical_disclaimer]` and `[affiliate_disclosure]` shortcodes
- `functions.php` passes config to widgets via `wp_localize_script`, enqueues bundles conditionally
- Plugin main file wires all classes together with correct hooks

**Success criteria:**
- City CPT URL (e.g. `/city/new-york/`) returns 200, not 404
- WP admin shows "24Hr Pharmacy Tools" settings page with fields for API keys and affiliate codes
- A test city post outputs valid JSON-LD in `<head>` (validated via Google Rich Results Test)
- `[medical_disclaimer]` shortcode renders visible text on any page
- `[affiliate_disclosure]` shortcode renders visible text on any page

---

## Phase 2: City Page Template + Compliance Pages

**Goal:** A fully-built city page template that renders all required sections (pharmacy finder widget placeholder, discount card widget placeholder, 500-word content block, schema, disclaimer, disclosure, affiliate CTAs) plus all required compliance pages live on the site.

**Requirements covered:** INFRA-07, INFRA-08, INFRA-09, CONTENT-01

**Plans:** 3 plans

Plans:
- [ ] 02-01-PLAN.md — City page template + functions.php fix + CSS additions
- [ ] 02-02-PLAN.md — Compliance page content (5 files)
- [ ] 02-03-PLAN.md — Secondary templates (homepage, pharmacy detail, state archive)

**Deliverables:**
- `single-city.php` with correct H1/H2 heading hierarchy, all ad zones, disclaimer shortcode, disclosure shortcode, affiliate CTA blocks
- `front-page.php` with above-the-fold hero + quick search entry point
- `single-pharmacy.php` with pharmacy detail layout
- `archive-state.php` with state city listing
- Compliance page content (text): `/disclaimer/`, `/affiliate-disclosure/`, `/privacy-policy/`, `/terms/`, `/about/`
- Medical disclaimer shortcode fires on every city, pharmacy, and savings page
- Affiliate disclosure shortcode fires on every page with affiliate links

**Success criteria:**
- Visiting any city post renders: H1, at least 3 H2s, disclaimer block, disclosure block, 2+ affiliate CTA regions
- All 5 compliance pages return 200 with real content (not placeholder)
- Page source includes `.ad-zone-header`, `.ad-zone-in-content`, `.ad-zone-footer` class containers

---

## Phase 3: React Widgets

**Goal:** Both interactive widgets — pharmacy finder and discount card — are fully built, compiled to async-loading IIFE bundles, functional at 375px mobile width, and pass Core Web Vitals thresholds.

**Requirements covered:** WIDGET-01, WIDGET-02, WIDGET-03, WIDGET-04

**Deliverables:**
- `widgets/pharmacy-finder/` — full React 18 + Vite project: geolocation + Google Places API search, results list with pharmacy name/address/hours, zero-results Amazon Pharmacy CTA
- `widgets/discount-card/` — branded BIN/PCN/Group display, print action, save-to-phone action, text-to-phone action
- Both widgets compiled to `wordpress/plugin/24hr-pharmacy-tools/assets/js/` with `async`/`defer` loading
- Minimum-height placeholder containers in shortcode output to prevent CLS
- Both widgets render correctly at 375px viewport

**Success criteria:**
- Pharmacy finder shortcode on a test page: browser geolocation prompt appears, results render within 3s
- Zero-results state shows Amazon Pharmacy CTA with correct affiliate link
- Discount card renders BIN/PCN/Group codes from WP options; print button opens print dialog
- Lighthouse mobile score > 90 on a city page with both widgets loaded
- LCP < 2.5s, CLS < 0.1, INP < 200ms (measured via PageSpeed Insights)

---

## Phase 4: Content Pipeline + Launch Content

**Goal:** Python data pipeline scripts fully implemented, 10 launch city pages published with unique 500+ word content and real pharmacy data, 5 core informational articles live.

**Requirements covered:** CONTENT-02, CONTENT-03, CONTENT-04, PIPE-01, PIPE-02, PIPE-04

**Deliverables:**
- `sync-pharmacy-data.py` — fetches and caches Google Places pharmacy data per city to `data/pharmacies/raw/`, handles rate limiting
- `generate-city-pages.py` — reads cities.json + cached pharmacy data, produces unique 500+ word city pages via WP REST API, supports `--dry-run` mode
- 10 launch city pages published: NYC, LA, Chicago, Houston, Phoenix, Philadelphia, San Antonio, San Diego, Dallas, San Jose
- Each city page: unique intro paragraph, local pharmacy count, notable 24-hour chains present, neighborhood context
- 5 core informational articles published (pharmacy savings guide, GoodRx alternatives, discount cards explained, telehealth fallback, uninsured guide)
- DataForSEO keyword research run for all city + drug query variations; results in `data/keyword-research/`

**Success criteria:**
- `python sync-pharmacy-data.py --city new-york` writes JSON to `data/pharmacies/raw/new-york.json`
- `python generate-city-pages.py --dry-run --limit 1` outputs city page content to stdout without posting
- All 10 launch city pages: WP post status = published, word count ≥ 500, unique content (no identical paragraphs across cities)
- All 5 informational articles published with correct categories and internal links

---

## Phase 5: SEO & Technical Foundation

**Goal:** Full technical SEO stack configured: sitemap submitted, IndexNow live, RankMath configured, Cloudflare CDN active, ADA compliance verified, cookie consent with CCPA opt-out, Google Maps API key domain-restricted.

**Requirements covered:** INFRA-06, INFRA-10, SEO-01, SEO-02, SEO-03, SEO-04, SEO-05, SEO-06, WIDGET-05

**Deliverables:**
- XML sitemap submitted to Google Search Console and Bing Webmaster Tools
- IndexNow API key configured in RankMath (instant Bing indexing on publish)
- RankMath: site type = Health, GSC connected, LocalBusiness schema default set
- Cloudflare: CDN enabled, Always HTTPS, Brotli compression, Rocket Loader tested with widget bundles
- WCAG 2.1 AA: all images have alt text, contrast ratios pass, keyboard navigation works, form labels present
- robots.txt configured for Hostinger WP install (blocks `/wp-admin/`, allows all content)
- Cookie consent banner: separate opt-out flows for analytics cookies vs. advertising cookies (CCPA)
- Google Maps API key: domain-restricted to `24hourpharmacy.com`, scoped to Maps JS API + Places API only
- Core Web Vitals: LCP < 2.5s, CLS < 0.1, INP < 200ms verified on 3 representative pages

**Success criteria:**
- Google Search Console shows sitemap successfully processed
- RankMath dashboard shows GSC connected and IndexNow enabled
- PageSpeed Insights mobile score ≥ 90 on homepage, a city page, and an informational article
- Cookie consent banner appears on first visit; "Manage Preferences" link opens separate analytics/ads opt-out controls
- Google Cloud Console shows Maps API key with HTTP referrer restriction

---

## Phase 6: Affiliate & Monetization Activation

**Goal:** White-label branded savings card live, all priority affiliate programs applied to, Lasso installed for link management, GA4 conversion tracking live, AdSense approved and serving.

**Requirements covered:** AFF-01, AFF-02, AFF-03, AFF-04, AFF-05, AFF-06

**Deliverables:**
- White-label 24HourPharmacy Savings Card created with LowerMyRx or NDC white-label program (BIN/PCN/Group codes)
- Branded savings card PDF downloadable from every city page above the fold
- Applications submitted to all priority programs: LowerMyRx, NDC, SingleCare, CJ (Walgreens/Rite Aid/CVS/eHealth), Impact (Hims/Ro), MediaAlpha, EverQuote, GoHealth, Mochi Health, Fridays Health, Brightside, EverlyWell, Amazon Associates
- Lasso plugin installed; all affiliate links managed through Lasso (no bare affiliate URLs in content)
- GA4: conversion events tracking `affiliate_link_click`, `card_download`, `insurance_cta_click`
- AdSense application submitted; ad units placed in all `.ad-zone-*` containers

**Success criteria:**
- Savings card widget on any city page shows correct BIN/PCN/Group; PDF download works
- GA4 real-time report shows `affiliate_link_click` event firing on affiliate CTA click
- Lasso dashboard shows all active affiliate links with click tracking
- AdSense application submitted (approval timeline: 1-14 days, outside our control)

---

## Phase 7: GEO & AI Strategy + Remaining Content

**Goal:** FAQ schema on all city pages, H2s formatted as questions with 2-sentence answers, GLP-1 shortage tracker live, data CSV published, Google Business Profile created.

**Requirements covered:** CONTENT-05, CONTENT-06, CONTENT-07, SEO-07, SEO-08, SEO-09

**Deliverables:**
- FAQ schema (3-5 questions per page) added to all city pages via `class-schema.php`
- All city page H2s reformatted as questions (e.g. "Are There 24-Hour Pharmacies in [City]?")
- Each H2 question answered in the opening 2 sentences of its section
- GLP-1 shortage tracker page live at `/glp1-shortage-tracker/` with FAQPage schema, telehealth CTAs, updated weekly
- `/data/` page with downloadable CSV of US 24-hour pharmacy locations
- Google Business Profile created and verified as Health Information Service
- All content pages (informational articles) with H2s formatted as questions

**Success criteria:**
- Google Rich Results Test shows FAQPage schema on a city page
- `/glp1-shortage-tracker/` returns 200 with FAQPage schema and at least 5 FAQs
- `/data/` page has downloadable CSV link; CSV contains 50+ pharmacy entries
- Google Business Profile verification initiated

---

## Phase 8: Scale & Automation

**Goal:** n8n automation pipeline monitoring pharmacy data freshness, Spanish-language pages foundation, and site operationally stable for scale to 500+ city pages.

**Requirements covered:** PIPE-03

**Deliverables:**
- n8n workflow: monitors `data/pharmacies/raw/` file timestamps, triggers `sync-pharmacy-data.py` when data is > 30 days old
- n8n workflow: Slack/email alert when pharmacy hours change detected
- `generate-city-pages.py` extended to support full 50-city run with deduplication check
- `cities.json` expanded to 50 metros with coordinates
- Site performance verified stable under full 50-city content load
- Deployment process documented in `docs/deployment.md` with rollback steps

**Success criteria:**
- n8n dashboard shows active workflows with last-run timestamps
- Running `sync-pharmacy-data.py` for a city with stale data triggers n8n refresh alert
- Full 50-city page generation dry-run completes without errors
- `docs/deployment.md` contains step-by-step deploy instructions and a rollback section

---

## Phase Summary

| Phase | Name | Requirements | Key Risk |
|-------|------|-------------|----------|
| 1 | WordPress Plugin Foundation | INFRA-01–05 | PHP stub implementation quality |
| 2 | City Page Template + Compliance | INFRA-07–09, CONTENT-01 | YMYL legal exposure if shortcuts taken |
| 3 | React Widgets | WIDGET-01–04 | Google Places API costs, async loading |
| 4 | Content Pipeline + Launch Content | CONTENT-02–04, PIPE-01–02, PIPE-04 | Thin content penalty if <500 words |
| 5 | SEO & Technical Foundation | INFRA-06, INFRA-10, SEO-01–06, WIDGET-05 | Rocket Loader breaking widgets |
| 6 | Affiliate & Monetization | AFF-01–06 | Program approval timelines (out of our control) |
| 7 | GEO & AI Strategy | CONTENT-05–07, SEO-07–09 | FAQ schema duplication across pages |
| 8 | Scale & Automation | PIPE-03 | n8n self-hosting complexity on Hostinger |

---

*Roadmap created: 2026-03-28*
*Last updated: 2026-03-29 after Phase 2 planning*
