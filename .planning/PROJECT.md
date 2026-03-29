# 24HourPharmacy.com

## What This Is

A WordPress-based content site that helps uninsured and underinsured Americans find 24-hour pharmacies near them and save on prescription costs. Built by a solo developer as a personal revenue business combining a functional pharmacy finder tool with a full affiliate monetization stack (discount cards, telehealth, insurance leads, GLP-1 programs, and more).

## Core Value

A working pharmacy finder tool with a branded discount card that earns recurring commissions — the tool is the moat, not just the content.

## Requirements

### Validated

- ✓ WordPress + Kadence child theme architecture — existing scaffold
- ✓ Custom 9-file plugin architecture designed (class-post-types, schema, shortcodes, settings, etc.)
- ✓ 4 React widget projects scaffolded (pharmacy-finder, discount-card, price-checker, open-now-checker)
- ✓ Python data pipeline designed (cities.json with 50 US metros, generate-city-pages.py)
- ✓ Command center dashboard live on GitHub Pages with full playbook and task tracking
- ✓ Codebase mapped (.planning/codebase/ — 7 documents)

### Active

- [ ] WordPress plugin fully implemented (post types, shortcodes, schema, settings, affiliate storage)
- [ ] City page template live with correct schema, disclaimers, and affiliate CTAs
- [ ] Pharmacy finder React widget deployed and functional
- [ ] Discount card React widget deployed with white-label BIN/PCN/Group
- [ ] Content pipeline generating unique 500+ word city pages programmatically
- [ ] 10 launch city pages live with real pharmacy data
- [ ] All required compliance pages live (disclaimer, disclosure, privacy, terms, about)
- [ ] AdSense approved and live
- [ ] All priority affiliate programs applied to and approved
- [ ] SEO foundation complete (sitemap, RankMath, Bing, IndexNow, ADA, cookie consent)
- [ ] GEO/AI strategy implemented (FAQ schema, LLM-optimized content structure)
- [ ] GLP-1 shortage tracker live and updated weekly
- [ ] White-label branded savings card published on every city page

### Out of Scope

- Headless WordPress / decoupled frontend — Hostinger shared hosting, simplicity required
- Real-time server-side pharmacy data — client-side Google Places API is sufficient for v1
- User accounts or personalization — anonymous tool, no auth needed
- Mobile app — web-first (70%+ traffic is mobile web already)
- Multi-author CMS — solo operator
- International pharmacy coverage — US-only for v1; Canadian pharmacy angle flagged as high risk/controversial

## Context

- **Stack**: WordPress 6.x on Hostinger, Kadence child theme, React 18 widgets via Vite, Python 3.11 data scripts
- **Deployment**: Manual — zip theme/plugin → upload via WP admin. No CI/CD. No staging environment.
- **Infrastructure**: Domain at GoDaddy (transferred March 2026, active), hosting at Hostinger, CDN via Cloudflare
- **Codebase state**: Scaffold only — all plugin class files are stubs, all widget directories are empty, all PHP templates are stubs. Nothing renders yet.
- **Revenue model**: Affiliate commissions (discount cards, telehealth, insurance leads, GLP-1, medical alerts, OTC, etc.) + display advertising (AdSense → Raptive). Full playbook in docs/docs/command-center/index.html.
- **Legal context**: YMYL site. Medical disclaimer required on every page. FTC affiliate disclosure required on every page with affiliate links. Cookie consent must support CCPA separate opt-out flows.
- **Audience**: Uninsured and underinsured Americans searching for pharmacy access and prescription savings. 70%+ mobile traffic.
- **SEO posture**: Programmatic at scale (50→500+ city pages, FDA NDC drug pages). Google HCU risk is real — unique data and functional tools are the primary defense.

## Constraints

- **Tech**: PHP must follow WordPress Coding Standards. No framework dependencies in PHP. React widgets must load async (defer/async in wp_enqueue_script). Do NOT use Tailwind in widgets — use Kadence CSS custom properties.
- **Deployment**: All changes deploy via manual WP admin upload. No git hooks to production.
- **Content**: Every programmatic city page must have 500+ words of unique, city-specific content. Google penalizes thin YMYL programmatic pages.
- **Legal**: Medical disclaimer + FTC affiliate disclosure on every page. Cookie consent must satisfy CCPA (separate advertising cookie opt-out). Never make health claims or provide medical advice.
- **API costs**: Google Places API has per-request costs. Cache all responses in data/pharmacies/raw/. Implement --dry-run mode on the page generator before any live runs.
- **Performance**: LCP < 2.5s, CLS < 0.1, INP < 200ms required for ad network approval.

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| WordPress as CMS (not headless) | Hostinger shared hosting, solo operator, plugin ecosystem for SEO/caching | — Pending |
| React widgets as standalone Vite IIFE bundles | Loaded conditionally by shortcode, must not block render | — Pending |
| Google Places API for pharmacy data (client-side) | No server-side data scraping, lower infra complexity | — Pending |
| Python scripts push content via WP REST API | Page generation runs locally, not on server | — Pending |
| LowerMyRx + National Drug Card as primary discount card affiliates | Best per-fill rates, white-label program available | — Pending |
| White-label branded savings card as primary conversion artifact | Permanent attribution, multi-year passive income per download | — Pending |
| GLP-1 shortage tracker as first traffic play | Highest-search pharmacy topic, ranks fast with manual updates | — Pending |

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd:transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd:complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---
*Last updated: 2026-03-28 after project initialization*
