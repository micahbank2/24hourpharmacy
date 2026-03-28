# Project State: 24HourPharmacy.com

**Last updated:** 2026-03-28
**Current phase:** Pre-execution (planning complete, no phase started)
**Overall status:** Ready to execute Phase 1

---

## Current Phase: None (Phase 1 queued)

Phase 1 has not started. All planning artifacts are complete. The codebase is in scaffold/stub state — nothing functional exists yet.

**To begin:** Run `/gsd:plan-phase 1`

---

## Phase History

| Phase | Status | Started | Completed | Notes |
|-------|--------|---------|-----------|-------|
| — | — | — | — | No phases executed yet |

---

## Completed Setup

- [x] GSD config written (`config.json`) — YOLO mode, Standard granularity, Parallel, Balanced models
- [x] Codebase mapped (`.planning/codebase/` — 7 documents: STACK, INTEGRATIONS, ARCHITECTURE, STRUCTURE, CONVENTIONS, TESTING, CONCERNS)
- [x] PROJECT.md written — project definition, core value, constraints, key decisions
- [x] REQUIREMENTS.md written — 38 v1 requirements across 6 categories, full traceability matrix
- [x] ROADMAP.md written — 8 phases mapped to CLAUDE.md priority build roadmap
- [x] Command center dashboard updated — audit results, playbooks, GitHub repos, AI tools, foundation tasks, launch tasks, milestones

---

## Open Decisions

| Decision | Options | Blocker |
|----------|---------|---------|
| LowerMyRx vs NDC white-label for savings card | LowerMyRx (better rates, easier approval) vs NDC (broader coverage) | Need to apply to both and compare; not blocking Phase 1-3 |
| Exa.ai for pharmacy data (vs Google Places only) | Exa Websets for initial DB build; Places API for real-time widget | Free tier test required before committing to $49/mo; not blocking v1 |
| n8n hosting approach | Self-hosted on Hostinger vs n8n.cloud ($20/mo) | Phase 8 decision; Hostinger shared hosting may not support persistent processes |

---

## Blockers

None currently. Phase 1 is fully plannable from the existing codebase scaffold.

**Known future blockers (not blocking now):**
- Affiliate program approvals (Phase 6) — most programs require a live site with real content. Timeline: 1-14 days per program after submission. Cannot accelerate.
- AdSense approval (Phase 6) — requires sufficient content + quality review. Typically 1-2 weeks.
- Google Business Profile verification (Phase 7) — physical address required for postcard verification.

---

## Key Context for Next Agent

**What exists:** Scaffold only. All PHP plugin class files are stubs (ABSPATH check + comment only). All widget directories contain only `.gitkeep`. WordPress theme templates are stubs. Python scripts are stub docstrings.

**What works:** WordPress core is live on Hostinger. GeneratePress Pro theme is active. Child theme loads correctly. The plugin is installed but has no functionality.

**What Phase 1 must build:**
1. `class-post-types.php` — register City and Pharmacy CPTs with rewrite rules
2. `class-settings.php` — WP admin settings page for API keys + affiliate codes
3. `class-schema.php` — JSON-LD output (LocalBusiness/Pharmacy, WebPage, FAQPage)
4. `class-shortcodes.php` — `[medical_disclaimer]` and `[affiliate_disclosure]` shortcodes
5. `functions.php` — enqueue widget bundles conditionally, pass config via `wp_localize_script`
6. `24hr-pharmacy-tools.php` — wire all classes with correct `add_action` hooks

**Architecture constraints for Phase 1:**
- PHP must follow WordPress Coding Standards (WPCS)
- No framework dependencies — native WP functions only
- API keys and affiliate codes stored in WP options table (`get_option()` / `update_option()`)
- Never hardcoded in PHP files
- Plugin uses `ABSPATH` guard on every include file

---

## Metrics Baseline

*No metrics yet — site not indexed, no traffic.*

| Metric | Target | Current |
|--------|--------|---------|
| Organic sessions/mo | 1,000 by Month 3 | 0 |
| City pages live | 10 by Phase 4 | 0 |
| Affiliate programs approved | 5+ by Phase 6 | 0 |
| Savings card downloads | 100/mo by Month 4 | 0 |
| AdSense RPM | $5+ | N/A |
| Lighthouse mobile score | >90 | N/A |

---

## Risk Register

| Risk | Likelihood | Impact | Mitigation |
|------|-----------|--------|------------|
| Thin content Google penalty | High (programmatic pages) | High | 500+ word unique content rule enforced in generator; no launch without city-specific content |
| Google HCU (Helpful Content Update) | Medium | High | Functional tools (finder + card) are primary defense; unique data from Google Places API |
| Affiliate program rejections | Medium | Medium | Apply to 13+ programs; diversification reduces single-program risk |
| Google Places API cost overrun | Low | Medium | `sync-pharmacy-data.py` caches all responses; city pages use cached data, not live API per pageview |
| FTC affiliate disclosure violation | Low (avoidable) | High | Disclosure shortcode on every page template; automated via PHP, not manual |
| YMYL medical advice claim | Low (avoidable) | High | No health claims policy enforced in content review checklist; disclaimer on every page |
| Hostinger downtime | Low | Medium | Cloudflare CDN caches static assets; planned Hostinger VPS migration at 50K sessions/mo |

---

*State initialized: 2026-03-28*
*Auto-updated by GSD at each phase transition*
