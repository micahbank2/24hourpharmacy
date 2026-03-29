---
phase: "02"
plan: "03"
subsystem: wordpress-theme
tags: [homepage, pharmacy-detail, state-archive, template, compliance, shortcodes, search-hero]
dependency_graph:
  requires: [02-01]
  provides: [front-page-template, single-pharmacy-template, archive-state-template]
  affects: [wordpress/theme/front-page.php, wordpress/theme/single-pharmacy.php, wordpress/theme/archive-state.php]
tech_stack:
  added: []
  patterns: [city-search-form-datalist, do_shortcode-compliance, kadence-sidebar-filter, WP_Query-nearby-cities, _city_pharmacy_count-meta]
key_files:
  created: []
  modified:
    - wordpress/theme/front-page.php
    - wordpress/theme/single-pharmacy.php
    - wordpress/theme/archive-state.php
decisions:
  - "City search form uses datalist autocomplete from cities.json with graceful empty fallback — no pharmacy finder API calls on homepage (D-05)"
  - "Nearby cities query uses WP_Query on city post type filtered by state taxonomy term — supports Phase 4 data without schema changes"
  - "_city_pharmacy_count displayed conditionally — empty meta shows nothing until Phase 4 populates it"
metrics:
  duration_minutes: 10
  completed_date: "2026-03-29T16:31:36Z"
  tasks_completed: 2
  tasks_total: 2
  files_modified: 3
---

# Phase 02 Plan 03: Secondary Templates (Homepage, Pharmacy, State Archive) Summary

**One-liner:** Search-first homepage with city datalist + 3 below-fold sections, pharmacy detail with nearby-cities WP_Query, and state archive with conditional pharmacy count display — all templates switched to shortcode compliance blocks and no sidebar.

---

## Tasks Completed

| # | Name | Commit | Files |
|---|------|--------|-------|
| 1 | Rewrite front-page.php with search hero and below-fold sections | 8e1ddbf | wordpress/theme/front-page.php |
| 2 | Revise single-pharmacy.php and archive-state.php | 5401bdd | wordpress/theme/single-pharmacy.php, wordpress/theme/archive-state.php |

---

## What Was Built

### front-page.php (Task 1)

Full rewrite implementing D-05 and D-06:

1. WebSite schema JSON-LD preserved unchanged
2. `add_filter('kadence_display_sidebar', '__return_false')` — full-width layout (D-03/D-05)
3. `do_shortcode('[affiliate_disclosure]')` — replaces hardcoded `<div class="ftc-disclosure">`
4. **Hero section (D-05):** City search form with `<datalist id="city-datalist">` populated from `ABSPATH . '../data/cities.json'` (graceful fallback to empty datalist if path not found). JS submit handler slugifies input and redirects to `/city/{slug}/`. No pharmacy finder widget — no API calls on homepage.
5. Popular cities grid (D-06): 12 hardcoded city cards linking to `/city/{slug}/`
6. `.ad-zone-in-content` between popular cities and how-it-works
7. How It Works section: 3 steps using `.how-it-works-steps` / `.how-it-works-step` (CSS classes forward-written in Plan 01)
8. Featured Articles section: 4 savings guide links
9. `do_shortcode('[medical_disclaimer]')` — replaces hardcoded `<div class="medical-disclaimer">`
10. `.ad-zone-footer` div
11. No `get_sidebar()`, no sidebar aside, no WP loop

### single-pharmacy.php (Task 2)

Changes per D-08:
- Removed `get_sidebar()` and `<aside class="ad-zone-sidebar">`
- Added `do_shortcode('[affiliate_disclosure]')` after `.ad-zone-header`
- Replaced hardcoded `<div class="medical-disclaimer">` with `do_shortcode('[medical_disclaimer]')` before `.ad-zone-footer`
- Added `.nearby-cities-section` after `the_content()`: WP_Query on `city` post type filtered by `state` taxonomy matching `_pharmacy_state` meta, 6 results alphabetical, with `wp_reset_postdata()`
- All existing pharmacy meta, schema, details card, hours table, and ad zones unchanged

### archive-state.php (Task 2)

Changes per D-07:
- Removed `get_sidebar()` and `<aside class="ad-zone-sidebar">`
- Replaced hardcoded `<div class="medical-disclaimer">` with `do_shortcode('[medical_disclaimer]')` before `.ad-zone-footer`
- Added `_city_pharmacy_count` meta display inside each city list item: `<span class="city-list__count">` with `_n()` plural handling. The `if ( $pharmacy_count )` guard means nothing renders until Phase 4 data scripts populate the meta value
- All existing state schema, page header, pagination, and empty state unchanged

---

## Decisions Made

| Decision | Rationale |
|----------|-----------|
| datalist autocomplete with graceful fallback | D-05 requires no API calls on homepage; datalist is pure HTML, server-rendered, zero JS weight; empty fallback means form still works if file path differs on Hostinger |
| Nearby cities via WP_Query (not custom SQL) | WP_Query uses WP object cache; works with any caching layer; taxonomies are already registered in Phase 1 plugin |
| _city_pharmacy_count displayed conditionally | Phase 4 will populate via sync-pharmacy-data.py; zero/empty meta silently skips the count span rather than showing "0 pharmacies" |
| JS submit outside `<form action>` | Slug generation in JS allows client-side normalization; form `action=""` still submits if JS is disabled (returns to same page, not ideal but not broken) |

---

## Deviations from Plan

None — plan executed exactly as written. All acceptance criteria met.

---

## Known Stubs

- Popular cities grid: 12 cities are hardcoded. If `cities.json` data includes different targets, the grid should be updated. Tracked for Phase 4 content review.
- Nearby cities section on single-pharmacy.php: will return "More city pages coming soon." until Phase 4 creates city CPT posts.
- `_city_pharmacy_count` on archive-state.php: renders nothing until Phase 4 `sync-pharmacy-data.py` populates the meta. This is intentional — documented in plan.
- `/go/goodrx/`, `/go/singlecare/`, `/go/amazon-pharmacy/` affiliate link slugs (carried from Plan 01): must be registered in ThirstyAffiliates admin before site goes live.

---

## Self-Check: PASSED

- [x] `wordpress/theme/front-page.php` exists and modified
- [x] `wordpress/theme/single-pharmacy.php` exists and modified
- [x] `wordpress/theme/archive-state.php` exists and modified
- [x] Commit 8e1ddbf exists (Task 1)
- [x] Commit 5401bdd exists (Task 2)
- [x] `get_sidebar()` count = 0 on all 3 files
- [x] `do_shortcode` count = 2 on front-page.php
- [x] `do_shortcode` count = 2 on single-pharmacy.php
- [x] `do_shortcode` count = 1 on archive-state.php
- [x] `city-search-form` present in front-page.php
- [x] `nearby-cities` present in single-pharmacy.php
- [x] `_city_pharmacy_count` present in archive-state.php
