# Requirements: 24HourPharmacy.com

**Defined:** 2026-03-28
**Core Value:** A working pharmacy finder tool with a branded discount card that earns recurring commissions — the tool is the moat, not just the content.

## v1 Requirements

### Infrastructure (INFRA)

- [x] **INFRA-01**: WordPress plugin registers City and Pharmacy custom post types with correct rewrite rules
- [x] **INFRA-02**: Plugin settings page stores all API keys and affiliate codes in WP options table (never in repo)
- [x] **INFRA-03**: Plugin enqueues React widget bundles conditionally via shortcodes with async/defer loading
- [x] **INFRA-04**: Plugin outputs valid JSON-LD structured data (LocalBusiness/Pharmacy, WebPage, FAQPage) on all relevant pages
- [x] **INFRA-05**: Child theme enqueues styles and passes config to widgets via wp_localize_script
- [ ] **INFRA-06**: Google Maps API key restricted to 24hourpharmacy.com domain in Cloud Console before any widget goes live
- [ ] **INFRA-07**: All required compliance pages live: /disclaimer/, /affiliate-disclosure/, /privacy-policy/, /terms/, /about/
- [x] **INFRA-08**: Medical disclaimer shortcode outputs on every city, pharmacy, and savings page
- [x] **INFRA-09**: Affiliate disclosure shortcode outputs on every page with affiliate links
- [ ] **INFRA-10**: Cookie consent supports separate opt-out flows for analytics vs advertising (CCPA compliant)

### Content (CONTENT)

- [x] **CONTENT-01**: City page template (single-city.php) renders: pharmacy finder widget, discount card widget, 500+ word unique content, schema, disclaimer, disclosure, affiliate CTAs
- [ ] **CONTENT-02**: Python generator script (generate-city-pages.py) produces unique 500+ word city pages via WP REST API with --dry-run mode
- [ ] **CONTENT-03**: 10 launch city pages published (top 10 US metros) with real pharmacy data
- [ ] **CONTENT-04**: 5 core informational articles published (pharmacy savings, GoodRx alternatives, discount cards explained, telehealth fallback, uninsured guide)
- [ ] **CONTENT-05**: GLP-1 shortage tracker page live at /glp1-shortage-tracker/ with FAQPage schema, updated weekly
- [ ] **CONTENT-06**: /data/ page with downloadable CSV of US 24-hour pharmacy locations
- [ ] **CONTENT-07**: Heading hierarchy correct on all pages (single H1, question-based H2s per GEO strategy)

### Widgets (WIDGET)

- [ ] **WIDGET-01**: Pharmacy finder widget locates nearby 24-hour pharmacies using Geolocation + Google Places API, renders on mobile at 375px
- [ ] **WIDGET-02**: Pharmacy finder widget shows Amazon Pharmacy CTA when zero results returned
- [ ] **WIDGET-03**: Discount card widget displays branded BIN/PCN/Group codes with print/save/text-to-phone actions
- [ ] **WIDGET-04**: Both widgets load async (defer) and do not block render (LCP < 2.5s)
- [ ] **WIDGET-05**: Both widgets pass Lighthouse mobile score > 90 and Core Web Vitals (LCP < 2.5s, CLS < 0.1, INP < 200ms)

### Affiliate & Monetization (AFF)

- [ ] **AFF-01**: White-label 24HourPharmacy Savings Card created with LowerMyRx or NDC white-label program
- [ ] **AFF-02**: Branded savings card PDF downloadable from every city page above the fold
- [ ] **AFF-03**: All priority affiliate programs applied to: LowerMyRx, NDC, SingleCare, CJ (Walgreens/Rite Aid/CVS/eHealth), Impact (Hims/Ro), MediaAlpha, EverQuote, GoHealth, Mochi Health, Fridays Health, Brightside, EverlyWell, Amazon Associates
- [ ] **AFF-04**: Lasso installed and all affiliate links managed through it
- [ ] **AFF-05**: GA4 conversion events tracking: affiliate_link_click, card_download, insurance_cta_click
- [ ] **AFF-06**: AdSense approved and live on all pages

### SEO & Technical (SEO)

- [ ] **SEO-01**: XML sitemap submitted to Google Search Console and Bing Webmaster Tools
- [ ] **SEO-02**: IndexNow enabled in RankMath (instant Bing indexing on publish)
- [ ] **SEO-03**: RankMath configured with site type, GSC connection, schema defaults
- [ ] **SEO-04**: Cloudflare configured: CDN, Always HTTPS, Brotli, Rocket Loader tested with widgets
- [ ] **SEO-05**: ADA/WCAG 2.1 compliance: alt text, contrast ratios (AA), keyboard navigation, form labels
- [ ] **SEO-06**: robots.txt configured correctly for Hostinger WP install
- [ ] **SEO-07**: Google Business Profile created and verified as Health Information Service
- [ ] **SEO-08**: FAQ schema on all city pages (3-5 questions per page)
- [ ] **SEO-09**: H2s on all content pages formatted as questions answered in opening 2 sentences (GEO strategy)

### Pipeline & Automation (PIPE)

- [ ] **PIPE-01**: sync-pharmacy-data.py fetches and caches Google Places pharmacy data per city to data/pharmacies/raw/
- [ ] **PIPE-02**: generate-city-pages.py produces unique city pages via WP REST API with dry-run mode
- [ ] **PIPE-03**: n8n pipeline monitors pharmacy data freshness and triggers sync when hours changes detected
- [ ] **PIPE-04**: DataForSEO keyword research run for all city + drug query variations, results used to prioritize page creation order

## v2 Requirements

### Growth

- **GROWTH-01**: Spanish-language city pages for top 20 US cities with hreflang tags
- **GROWTH-02**: FDA NDC drug hub — /drug/[slug]/ pages for top 500 prescription drugs
- **GROWTH-03**: Compound pharmacy locator with GLP-1 telehealth CTA
- **GROWTH-04**: Open Now free JSON API endpoint published for backlink/citation value
- **GROWTH-05**: Browser push notifications for pharmacy open/close alerts (OneSignal)
- **GROWTH-06**: Reddit keyword monitoring automation via Make.com
- **GROWTH-07**: Drug price search tool (GoodRx API or similar) as interactive savings hub feature

### Revenue Expansion

- **REV-01**: Direct advertising program for independent pharmacies ($99-199/mo featured placement)
- **REV-02**: Sponsored newsletter at 2,000+ subscribers
- **REV-03**: Fertility medications hub (Carrot Fertility, Progyny)
- **REV-04**: Hearing aids comparison page (Jabra Enhance, MDHearing, Eargo)
- **REV-05**: Vision care hub (1-800-Contacts, Warby Parker)
- **REV-06**: Patient Assistance Programs hub (NeedyMeds, manufacturer PAPs)

## Out of Scope

| Feature | Reason |
|---------|--------|
| User accounts / personalization | Anonymous tool, no auth needed, adds complexity |
| Headless WordPress | Hostinger shared hosting, not worth the infra complexity for v1 |
| Real-time server-side pharmacy data scraping | Client-side Google Places API sufficient, server scraping violates ToS |
| Mobile app | Web-first — 70%+ mobile web traffic already, no app needed for v1 |
| International pharmacy coverage | US-only; Canadian pharmacy listing is legally complex and controversial |
| Multi-author CMS features | Solo operator |
| Custom analytics dashboard | GA4 + Microsoft Clarity sufficient for v1; Chartbrew/Plausible for v2 |

## Traceability

| Requirement | Phase | Status |
|-------------|-------|--------|
| INFRA-01 | Phase 1 | Complete |
| INFRA-02 | Phase 1 | Complete |
| INFRA-03 | Phase 1 | Complete |
| INFRA-04 | Phase 1 | Complete |
| INFRA-05 | Phase 1 | Complete |
| INFRA-06 | Phase 5 | Pending |
| INFRA-07 | Phase 2 | Pending |
| INFRA-08 | Phase 2 | Complete |
| INFRA-09 | Phase 2 | Complete |
| INFRA-10 | Phase 5 | Pending |
| CONTENT-01 | Phase 2 | Complete |
| CONTENT-02 | Phase 4 | Pending |
| CONTENT-03 | Phase 4 | Pending |
| CONTENT-04 | Phase 4 | Pending |
| CONTENT-05 | Phase 7 | Pending |
| CONTENT-06 | Phase 7 | Pending |
| CONTENT-07 | Phase 7 | Pending |
| WIDGET-01 | Phase 3 | Pending |
| WIDGET-02 | Phase 3 | Pending |
| WIDGET-03 | Phase 3 | Pending |
| WIDGET-04 | Phase 3 | Pending |
| WIDGET-05 | Phase 5 | Pending |
| AFF-01 | Phase 6 | Pending |
| AFF-02 | Phase 6 | Pending |
| AFF-03 | Phase 6 | Pending |
| AFF-04 | Phase 6 | Pending |
| AFF-05 | Phase 6 | Pending |
| AFF-06 | Phase 6 | Pending |
| SEO-01 | Phase 5 | Pending |
| SEO-02 | Phase 5 | Pending |
| SEO-03 | Phase 5 | Pending |
| SEO-04 | Phase 5 | Pending |
| SEO-05 | Phase 5 | Pending |
| SEO-06 | Phase 5 | Pending |
| SEO-07 | Phase 7 | Pending |
| SEO-08 | Phase 7 | Pending |
| SEO-09 | Phase 7 | Pending |
| PIPE-01 | Phase 4 | Pending |
| PIPE-02 | Phase 4 | Pending |
| PIPE-03 | Phase 8 | Pending |
| PIPE-04 | Phase 4 | Pending |

**Coverage:**
- v1 requirements: 38 total
- Mapped to phases: 38
- Unmapped: 0 ✓

---
*Requirements defined: 2026-03-28*
*Last updated: 2026-03-28 after initial definition*
