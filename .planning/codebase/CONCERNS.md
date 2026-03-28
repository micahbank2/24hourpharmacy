# Codebase Concerns

**Analysis Date:** 2026-03-28

---

## Planned vs. Built Gaps (Critical)

The codebase is largely a scaffold. Most files are stubs with no implementation. This is the foundational risk — almost nothing is built yet.

**Unimplemented files:**
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php` — empty (no shortcodes registered)
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-schema.php` — empty (no JSON-LD output)
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-post-types.php` — empty (no post types registered)
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-settings.php` — empty (no admin settings page)
- `wordpress/theme/single-city.php` — stub comment only: `<!-- City page template: to be implemented -->`
- `wordpress/theme/functions.php` — only enqueues styles, no widget script enqueueing, no REST API integration
- `data/scripts/generate-city-pages.py` — stub docstring only, no implementation
- `data/scripts/sync-pharmacy-data.py` — not inspected but likely similar
- `docs/deployment.md` — placeholder comment only
- `docs/seo-checklist.md` — placeholder comment only
- All four widget directories (`widgets/pharmacy-finder/`, `widgets/discount-card/`, `widgets/price-checker/`, `widgets/open-now-checker/`) — contain only `.gitkeep`
- `wordpress/plugin/24hr-pharmacy-tools/assets/js/` — contains only `.gitkeep` (no compiled widget bundles)
- `wordpress/theme/assets/js/` — contains only `.gitkeep`

**Impact:** The site cannot function in its current state. No city pages can render content, no widgets exist, no schema is output, no shortcodes work, no affiliate codes can be stored.

---

## YMYL / Medical Content Risks

**Medical disclaimer absent from templates:**
- `wordpress/theme/single-city.php` is a stub — no disclaimer rendered
- `wordpress/theme/single-pharmacy.php`, `archive-state.php`, `front-page.php` not inspected but pattern suggests stubs
- Files: `wordpress/theme/single-city.php`, `wordpress/theme/single-pharmacy.php`
- Risk: Google rates pharmacy/health content as YMYL (Your Money Your Life). Pages without explicit medical disclaimers ("not medical advice, consult a pharmacist") are at high algorithmic and manual review risk.
- Requirement from CLAUDE.md: "Medical disclaimer required on every page."
- Current mitigation: None — templates not implemented yet.
- Fix: Every PHP template must include a disclaimer block before any pharmacy/health content. The `[medical_disclaimer]` shortcode (once implemented in `class-shortcodes.php`) must fire on every city, pharmacy, and savings page.

**No health claims enforcement:**
- There is no linting, content review, or CMS validation that flags health claims in page content.
- Risk: YMYL penalties, FTC scrutiny, potential legal exposure on a medical-adjacent site.
- Fix: Add editorial checklist to `docs/seo-checklist.md`; content review required before pages go live.

---

## FTC Compliance Risks

**Affiliate disclosure not implemented:**
- `class-shortcodes.php` is empty — the `[affiliate_disclosure]` shortcode described in `docs/affiliate-setup.md` does not exist yet.
- Files: `wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php`
- Risk: FTC requires clear and conspicuous affiliate disclosure before or near the first affiliate link. Legally required. Programmatically generated city pages are explicitly called out as a risk area in `docs/affiliate-setup.md`.
- Fix: Implement `[affiliate_disclosure]` shortcode in `class-shortcodes.php` first, before any affiliate links go live. Embed in every page template.

**Disclosure page not linked:**
- No `/disclosure` page exists yet (no content directory in repo root for this).
- Every page footer must link to it per FTC guidance.

---

## SEO Risks

**Thin content on programmatic city pages:**
- CLAUDE.md rule: "Every city page must have 500+ words of unique content."
- `data/scripts/generate-city-pages.py` is a stub — no content generation logic exists.
- `data/cities.json` contains 50 cities (top US metros by population). Each city needs unique, substantive content.
- Risk: Launching placeholder or templated city pages triggers Google Panda/HCU thin-content penalties. Programmatic pages with identical structure and shallow content are a known Google enforcement target, especially for YMYL sites.
- Fix: The page generator script must produce genuinely differentiated content per city (local pharmacy count, hours context, neighborhood context). Consider Exa.ai integration (noted in CLAUDE.md as planned) to supply real pharmacy data per city.

**JSON-LD schema not implemented:**
- `class-schema.php` is empty.
- CLAUDE.md requirement: "Every page needs valid JSON-LD structured data."
- Risk: Without LocalBusiness or Pharmacy schema, city pages won't generate rich results or map pack eligibility signals.
- Fix: Implement `class-schema.php` with `LocalBusiness`/`Pharmacy` JSON-LD for pharmacy pages and `WebPage` + `FAQPage` schema for city pages.

**Heading hierarchy not enforced:**
- `single-city.php` is a stub with no heading structure.
- Risk: Crawler cannot understand page structure; accessibility and SEO both affected.

**`seo-checklist.md` is empty:**
- Files: `docs/seo-checklist.md`
- There is no documented pre-publish checklist to verify 500+ words, schema, disclaimers, disclosures, heading hierarchy.

---

## Security Concerns

**Google Maps API key exposure risk:**
- The API key passes from PHP to JavaScript via `wp_localize_script()` (described in CLAUDE.md).
- `wp_localize_script()` embeds values in the page HTML — the Maps API key will be visible in source.
- Mitigation required: Restrict the API key in Google Cloud Console to the specific domain (`24hourpharmacy.com`) and to the Maps JavaScript API and Places API only. Without domain restriction, the key can be scraped and abused.
- Current state: No implementation exists yet, so no key is currently exposed — but this must be handled before any widget goes live.

**WordPress Application Password in data scripts:**
- `WP_APP_USER` and `WP_APP_PASSWORD` are used by `generate-city-pages.py` to push content via REST API.
- These credentials must never be committed. `.env.example` has placeholders only — correct.
- Risk: If `.env` file is accidentally committed, REST API write access to WordPress is exposed.
- Files: `data/scripts/generate-city-pages.py`, `.env.example`
- Fix: Confirm `.gitignore` includes `.env`. Rotate credentials immediately if committed.

**WordPress hardening not documented:**
- No `docs/` file covers WP hardening (disable XML-RPC, limit login attempts, file editor disable, etc.).
- Hostinger shared hosting adds surface area. No WAF or security plugin noted.
- Risk: Hostinger WordPress installs are common targets for automated attacks.

---

## Performance Risks

**React widgets must load async — not enforced yet:**
- CLAUDE.md rule: "React widgets MUST load async."
- `functions.php` currently only enqueues stylesheets — no script enqueueing exists.
- When widget enqueueing is implemented, scripts must use `async` or `defer` attribute via the `$args` parameter of `wp_enqueue_script()` (WordPress 6.3+) or a custom filter.
- Risk: Synchronous widget loading blocks render, directly harming LCP (target < 2.5s) and INP (target < 200ms).
- Files: `wordpress/theme/functions.php`, `wordpress/plugin/24hr-pharmacy-tools/24hr-pharmacy-tools.php`

**CLS risk from React widget mount:**
- Widgets that replace a container after hydration cause layout shift if the container has no reserved height.
- Fix: Every shortcode output must include a minimum-height placeholder container so the widget mounts without shifting content.

**No image optimization pipeline:**
- No mention of WebP conversion, lazy loading, or image CDN in any documentation.
- 70%+ mobile traffic (per CLAUDE.md) makes image performance critical for LCP.

---

## Infrastructure Risks

**Manual deployment process:**
- `docs/deployment.md` is a placeholder stub — the process is undocumented beyond a brief note in CLAUDE.md.
- Current process: Zip theme folder → upload via WP admin → zip plugin folder → upload via WP admin → run data scripts from local machine.
- Risk: No rollback mechanism. No staging environment mentioned. A failed upload could take down the site with no fast recovery path.
- Fix: Document the deploy process in `docs/deployment.md`. Consider WP CLI on Hostinger for scripted deploys. Set up a staging subdomain.

**No staging environment:**
- Theme and plugin changes go directly to production.
- Risk: Any PHP error in a template or plugin file will produce a white screen of death on the live site.

**Hostinger shared hosting limitations:**
- Google Maps Places API calls are made client-side (safe), but any server-side API calls or Python scripts run locally and push via REST — this is not a scalable data refresh pattern.
- Risk: As city count grows beyond 50, manual sync runs become a bottleneck.

**`data/pharmacies/raw/` is empty:**
- Only `.gitkeep` present. No cached API responses exist.
- The `sync-pharmacy-data.py` script (stub) would populate this. Until it does, city pages have no real pharmacy data.

---

## Data Freshness Risks

**Static pharmacy data:**
- `data/cities.json` contains coordinates only — no actual pharmacy locations, hours, or phone numbers.
- Pharmacy hours change. Stores open and close. Static data will go stale.
- Risk: A user calls a pharmacy listed as "24-hour" that has since changed hours. Medical/health context makes this a trust and potential liability issue.
- Fix: The planned Exa.ai integration (noted in CLAUDE.md) would address this. Until then, pages must include a disclaimer that users should verify hours directly.

**Google Places API dependency:**
- `sync-pharmacy-data.py` (once implemented) presumably queries Google Places API for pharmacy data.
- Places API has rate limits and per-request costs. At 50 cities with potentially 20+ pharmacies each, API costs and rate limiting need to be accounted for.
- The `raw/` cache directory pattern suggests caching responses — this must be implemented to avoid repeat API costs.

---

## Monetization Risks

**All affiliate programs require approval:**
- CJ Affiliate (Walgreens, Rite Aid, CVS), LowerMyRx, National Drug Card, Impact (GoodRx, telehealth) — every program requires an active publisher application.
- Most require a live site with real content before approval. The site is currently unbuilt.
- Risk: Revenue is blocked entirely until (a) content is live, (b) applications are submitted, (c) approvals come through. CJ platform approval alone takes 1-3 business days, individual advertiser approvals additional time.
- Timeline dependency: No affiliate revenue is possible until city pages are live with sufficient content.

**`class-settings.php` is empty:**
- The admin settings page for storing affiliate IDs in the WordPress options table does not exist.
- Files: `wordpress/plugin/24hr-pharmacy-tools/includes/class-settings.php`
- Risk: Even when affiliate programs approve the site, there is no UI to store the tracking codes — they cannot be used in shortcodes.

**Ad network traffic thresholds:**
- AdSense requires content and quality review.
- Raptive requires 25K monthly page views; Mediavine requires 50K sessions/month.
- These thresholds take months of SEO growth to reach. AdSense is the only viable option at launch.

**No conversion tracking infrastructure:**
- No Google Analytics or Tag Manager implementation exists yet (only a placeholder `GA_MEASUREMENT_ID` in `.env.example`).
- Without conversion tracking, affiliate performance cannot be measured.

---

## Tech Debt

**Plugin class files are empty stubs:**
- `class-post-types.php`, `class-schema.php`, `class-shortcodes.php`, `class-settings.php` all contain only the PHP file header and ABSPATH check.
- These represent the core functionality of the entire plugin — none of it works.
- Fix: Implement in priority order: `class-settings.php` (affiliate storage), `class-shortcodes.php` (affiliate disclosure + widget embeds), `class-schema.php` (JSON-LD), `class-post-types.php` (city/pharmacy post types).

**Widget source code does not exist:**
- All four `widgets/` subdirectories contain only `.gitkeep`.
- `package.json` and `vite.config.js` are listed in CLAUDE.md file structure for `widgets/pharmacy-finder/` but do not exist on disk.
- The pharmacy finder widget is the primary interactive tool on the site — it does not exist yet.

**`docs/command-center/index.html` is empty:**
- Contains only `<!-- Paste command-center HTML here -->`.
- Intended as an operational task tracker, currently non-functional.

**`single-city.php` is a stub:**
- The template that will serve the highest-traffic pages on the site renders nothing except the theme header/footer.

---

## Missing Critical Features

**No 404 handling for city/pharmacy pages:**
- Without post types registered (`class-post-types.php` is empty), all city and pharmacy URLs return 404.
- Blocks: All SEO value, all traffic, all affiliate revenue.

**No Google Analytics / tracking:**
- `GA_MEASUREMENT_ID` is in `.env.example` but nothing in `functions.php` enqueues or outputs the GA snippet.
- Blocks: Any traffic analysis or conversion measurement.

**No sitemap configuration:**
- No sitemap plugin or custom sitemap generator is mentioned.
- Programmatic city pages need to be included in XML sitemap for Google to index them.

**No robots.txt configuration:**
- No documentation of robots.txt setup for the Hostinger WordPress install.

---

## Test Coverage Gaps

**No tests of any kind:**
- No PHP unit tests, no JS tests, no Python tests.
- No testing framework configured for any component.
- Files at risk: `data/scripts/generate-city-pages.py` (will create hundreds of WP posts — a bug here is destructive and hard to roll back), `class-schema.php` (invalid JSON-LD causes Google Search Console errors).
- Priority: High for the page generation script; a dry-run mode (`--dry-run`) should be implemented before any live execution.

---

*Concerns audit: 2026-03-28*
