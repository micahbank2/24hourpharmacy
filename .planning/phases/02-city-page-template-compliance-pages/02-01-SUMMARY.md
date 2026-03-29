---
phase: "02"
plan: "01"
subsystem: wordpress-theme
tags: [city-page, template, compliance, affiliate, css]
dependency_graph:
  requires: []
  provides: [single-city-template, compliance-shortcodes-wired, affiliate-cta-cards, custom-css-classes]
  affects: [single-city.php, functions.php, custom.css]
tech_stack:
  added: []
  patterns: [do_shortcode-compliance, has_shortcode-double-render-prevention, kadence-sidebar-filter, ThirstyAffiliates-go-cloaked-urls]
key_files:
  created: []
  modified:
    - wordpress/theme/single-city.php
    - wordpress/theme/functions.php
    - wordpress/theme/assets/css/custom.css
decisions:
  - "Use do_shortcode for all compliance blocks (medical_disclaimer, affiliate_disclosure) — shortcodes are single source of truth for disclaimer text"
  - "has_shortcode check in the_content filter prevents double-render when template explicitly calls do_shortcode"
  - "add_filter('kadence_display_sidebar', '__return_false') at top of template — Kadence-native approach for full-width layout without layout overrides"
  - "All affiliate /go/ links are cloaked ThirstyAffiliates paths — no raw affiliate URLs in template per CLAUDE.md rule 9"
metrics:
  duration_minutes: 8
  completed_date: "2026-03-29T16:26:59Z"
  tasks_completed: 3
  tasks_total: 3
  files_modified: 3
---

# Phase 02 Plan 01: City Page Template and Compliance CSS Summary

**One-liner:** Full D-01/D-02/D-03/D-04 city page layout with shortcode-wired compliance blocks, 3-card affiliate CTA section, sidebar suppression, and 269 lines of new Kadence-native CSS.

---

## Tasks Completed

| # | Name | Commit | Files |
|---|------|--------|-------|
| 1 | Update functions.php to prevent double disclaimer | f01754a | wordpress/theme/functions.php |
| 2 | Rewrite single-city.php to full Phase 2 spec | 3c098a4 | wordpress/theme/single-city.php |
| 3 | Add affiliate CTA card and shortcode compliance CSS | bda3e2d | wordpress/theme/assets/css/custom.css |

---

## What Was Built

### functions.php (Task 1)
Added `has_shortcode( $content, 'medical_disclaimer' )` bail in `twentyfourhour_append_disclaimer()`. When the city page template explicitly calls `do_shortcode('[medical_disclaimer]')`, the `the_content` filter no-ops and does not append a second disclaimer block. All other functions unchanged.

### single-city.php (Task 2)
Full rewrite to D-01 through D-04 section order:
1. `add_filter('kadence_display_sidebar', '__return_false')` — full-width layout
2. JSON-LD structured data (WebPage + FAQPage) — unchanged
3. `do_shortcode('[affiliate_disclosure]')` — FTC disclosure above fold
4. H1 + pharmacy finder hero via `do_shortcode('[pharmacy_finder]')`
5. Ad zone in-content
6. City content section with `the_content()` and 500+ word placeholder
7. `do_shortcode('[discount_card]')` in new discount-card-section
8. 3-card affiliate-cta-section (GoodRx `/go/goodrx/`, SingleCare `/go/singlecare/`, Amazon Pharmacy `/go/amazon-pharmacy/`) — all with `rel="sponsored noopener"`
9. `do_shortcode('[medical_disclaimer]')` — bottom of content
10. Ad zone footer

Removed: `get_sidebar()`, `<aside class="ad-zone-sidebar">`, hardcoded `<div class="ftc-disclosure">`, hardcoded `<div class="medical-disclaimer">`.

### custom.css (Task 3)
Appended 4 new sections (269 lines) — zero existing rules modified:
- **Affiliate CTA Cards**: `.affiliate-cta-section`, `.affiliate-cta-cards`, `.affiliate-cta-card`, BEM modifiers (`__name`, `__copy`, `__btn`), responsive flex layout
- **Shortcode Compliance Blocks**: `.pharmacy-disclaimer`, `.pharmacy-disclosure` — styled to match existing `.medical-disclaimer` / `.ftc-disclosure` visual language
- **Homepage Sections**: `.city-search-form`, `.popular-cities-grid`, `.popular-city-card`, `.how-it-works-steps`, `.how-it-works-step`, `.featured-articles-list` — forward-written for Plan 03
- **City List Count**: `.city-list__count` — for archive-state.php Plan 03

All colors use `var(--global-palette*)` — no hex values.

---

## Decisions Made

| Decision | Rationale |
|----------|-----------|
| Shortcodes for all compliance output | Single source of truth in class-shortcodes.php; text changes in one place affect all pages |
| has_shortcode bail in the_content filter | Prevents double-render without removing the filter entirely — non-city pages still get the fallback disclaimer |
| Kadence sidebar filter in template (not functions.php) | Scoped to city post type only; does not affect other templates |
| ThirstyAffiliates /go/ cloaked paths | CLAUDE.md rule 9 — no raw affiliate URLs in templates |
| CSS forward-written for Plan 03 | Homepage and archive CSS classes added now since Plan 03 will use them — avoids a separate CSS-only commit |

---

## Deviations from Plan

None — plan executed exactly as written. All acceptance criteria met.

---

## Known Stubs

- `/go/goodrx/`, `/go/singlecare/`, `/go/amazon-pharmacy/` — ThirstyAffiliates cloaked link slugs. These must be created in the ThirstyAffiliates plugin admin before the site goes live. The template is correct; the link targets need to be registered in WP admin.
- `do_shortcode('[discount_card]')` — renders the React widget root div but the discount-card bundle is not yet compiled (widget scaffold is empty). Tracked in Phase 3 (widget implementation).
- `do_shortcode('[pharmacy_finder]')` — same as above; pharmacy-finder bundle not yet compiled.

---

## Self-Check: PASSED

- [x] `wordpress/theme/single-city.php` exists and modified
- [x] `wordpress/theme/functions.php` exists and modified
- [x] `wordpress/theme/assets/css/custom.css` exists and modified
- [x] Commit f01754a exists (Task 1)
- [x] Commit 3c098a4 exists (Task 2)
- [x] Commit bda3e2d exists (Task 3)
