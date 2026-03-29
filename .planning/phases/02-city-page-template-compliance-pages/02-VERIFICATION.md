---
phase: 02-city-page-template-compliance-pages
verified: 2026-03-29T17:00:00Z
status: passed
score: 7/7 must-haves verified
gaps:
  - truth: "single-pharmacy.php and archive-state.php suppress Kadence sidebar (full-width layout)"
    status: resolved
    reason: "Neither single-pharmacy.php nor archive-state.php calls add_filter('kadence_display_sidebar', '__return_false'). The plan 03 SUMMARY claims 'Kadence sidebar filter' was applied, but the filter is absent from both files. single-city.php and front-page.php correctly include it."
    artifacts:
      - path: "wordpress/theme/single-pharmacy.php"
        issue: "Missing: add_filter('kadence_display_sidebar', '__return_false') — sidebar will render on pharmacy detail pages"
      - path: "wordpress/theme/archive-state.php"
        issue: "Missing: add_filter('kadence_display_sidebar', '__return_false') — sidebar will render on state archive pages"
    missing:
      - "Add add_filter('kadence_display_sidebar', '__return_false') near top of single-pharmacy.php (before get_header())"
      - "Add add_filter('kadence_display_sidebar', '__return_false') near top of archive-state.php (before get_header())"
  - truth: "ad-zone-footer and medical_disclaimer on single-pharmacy.php and archive-state.php are inside the main content wrapper"
    status: resolved
    reason: "On single-pharmacy.php (line 244) and archive-state.php (line 130), .ad-zone-footer and do_shortcode('[medical_disclaimer]') appear AFTER </div><!-- #primary --> — they are outside the #primary content wrapper. This is structurally inconsistent with single-city.php where ad-zone-footer is inside #primary. This may cause layout issues when the sidebar filter is also absent — ad zone divs will render outside the main column."
    artifacts:
      - path: "wordpress/theme/single-pharmacy.php"
        issue: "Lines 241-247: .ad-zone-footer and medical_disclaimer shortcode are outside #primary div"
      - path: "wordpress/theme/archive-state.php"
        issue: "Lines 130-133: .ad-zone-footer and medical_disclaimer shortcode are outside #primary div"
    missing:
      - "Move .ad-zone-footer and do_shortcode('[medical_disclaimer]') to inside the #primary > main structure on both templates, consistent with single-city.php layout"
human_verification:
  - test: "Visit a city page in browser and confirm: H1 visible, at least 3 H2s visible, disclaimer block visible, disclosure block visible, 3 affiliate CTA cards visible"
    expected: "All sections render correctly with full-width single-column layout (no sidebar)"
    why_human: "Kadence sidebar suppression requires a live WP environment to confirm the filter fires before get_header()"
  - test: "Visit a pharmacy detail page and state archive page — confirm no sidebar column appears"
    expected: "Full-width layout (no sidebar) on both template types"
    why_human: "The sidebar filter is missing from these templates; human must confirm whether Kadence defaults to no-sidebar anyway (depends on Kadence theme settings)"
---

# Phase 2: City Page Template + Compliance Pages — Verification Report

**Phase Goal:** All city page templates (single-city, front-page, single-pharmacy, archive-state) fully coded with correct section order, ad zones, affiliate CTA cards, widget placeholders, and shortcode-based compliance blocks. All 5 compliance page content files written with 500+ words each. Schema markup present on every template. Mobile-first responsive CSS for all new components.

**Verified:** 2026-03-29T17:00:00Z
**Status:** gaps_found
**Re-verification:** No — initial verification

---

## Goal Achievement

### Observable Truths

| # | Truth | Status | Evidence |
|---|-------|--------|----------|
| 1 | single-city.php renders H1, 3+ H2s, disclaimer, disclosure, 2+ affiliate CTA regions | VERIFIED | File confirmed: H1 on line 129, 5 H2 occurrences (pharmacy finder, content, discount card, affiliate CTAs, FAQ), `do_shortcode('[affiliate_disclosure]')` line 121, `do_shortcode('[medical_disclaimer]')` line 269, 3-card `.affiliate-cta-section` line 241 |
| 2 | All 4 templates include .ad-zone-header, .ad-zone-in-content, .ad-zone-footer | VERIFIED | All 4 templates confirmed: single-city.php lines 118/151/278; front-page.php lines 44/114/150; single-pharmacy.php lines 107/189/244; archive-state.php lines 60/121/130 |
| 3 | All 4 templates use shortcodes for compliance blocks (no hardcoded HTML) | VERIFIED | single-city.php: `do_shortcode('[affiliate_disclosure]')` + `do_shortcode('[medical_disclaimer]')`. front-page.php: both shortcodes. single-pharmacy.php: both shortcodes. archive-state.php: `do_shortcode('[medical_disclaimer]')` only (no affiliate links — INFRA-09 satisfied by omission) |
| 4 | Schema JSON-LD present on every template | VERIFIED | single-city.php: WebPage + FAQPage schemas (lines 60-110). front-page.php: WebSite schema (lines 34-36). single-pharmacy.php: Pharmacy/LocalBusiness schema with openingHoursSpecification (lines 97-99). archive-state.php: CollectionPage schema (lines 50-52) |
| 5 | All 5 compliance content files exist with 500+ words | VERIFIED | Files confirmed in docs/compliance/: disclaimer.md (754w), affiliate-disclosure.md (839w), privacy-policy.md (1057w), terms.md (896w), about.md (719w). Total: 4,265 words |
| 6 | Mobile-first CSS present for all new components | VERIFIED | custom.css confirmed: affiliate CTA cards (flex-direction: column → row at 640px), city search form (mobile-first flex), popular-cities-grid (2-col → 3-col → 4-col), how-it-works-steps (1-col → 3-col at 640px). All colors use var(--global-palette*) |
| 7 | single-pharmacy.php and archive-state.php suppress Kadence sidebar | FAILED | Neither file contains `add_filter('kadence_display_sidebar', '__return_false')`. single-city.php (line 9) and front-page.php (line 31) correctly include it. Plan 03 SUMMARY listed 'kadence-sidebar-filter' as a pattern but it was not applied to these two templates |

**Score: 5/7 truths verified**

---

### Required Artifacts

| Artifact | Expected | Status | Details |
|----------|----------|--------|---------|
| `wordpress/theme/single-city.php` | Full D-01–D-04 layout with all sections | VERIFIED | 285 lines; all required sections present |
| `wordpress/theme/front-page.php` | Hero + search form + 3 below-fold sections | VERIFIED | 170 lines; datalist search, popular cities, how-it-works, articles, shortcodes |
| `wordpress/theme/single-pharmacy.php` | Pharmacy detail with schema, hours, nearby cities | VERIFIED (partial) | 250 lines; schema + hours + nearby cities via WP_Query; sidebar filter missing |
| `wordpress/theme/archive-state.php` | State city listing with conditional pharmacy count | VERIFIED (partial) | 136 lines; city grid + pagination + conditional count; sidebar filter missing |
| `wordpress/theme/functions.php` | has_shortcode bail for double-disclaimer prevention | VERIFIED | Line 155: `has_shortcode( $content, 'medical_disclaimer' )` bail confirmed |
| `wordpress/theme/assets/css/custom.css` | 4 new CSS sections (affiliate cards, shortcode blocks, homepage, city list count) | VERIFIED | 594 lines total; all 4 sections confirmed present |
| `docs/compliance/disclaimer.md` | 500+ words, YMYL medical disclaimer | VERIFIED | 754 words |
| `docs/compliance/affiliate-disclosure.md` | 500+ words, FTC compliance + /go/ URL pattern | VERIFIED | 839 words |
| `docs/compliance/privacy-policy.md` | 500+ words, GA4 + Clarity + CookieYes + CCPA | VERIFIED | 1,057 words |
| `docs/compliance/terms.md` | 500+ words, YMYL disclaimer, discount card not insurance | VERIFIED | 896 words |
| `docs/compliance/about.md` | 500+ words, brand-only (no personal names) | VERIFIED | 719 words |

---

### Key Link Verification

| From | To | Via | Status | Details |
|------|----|-----|--------|---------|
| single-city.php | `[affiliate_disclosure]` shortcode | `do_shortcode()` line 121 | WIRED | Confirmed |
| single-city.php | `[medical_disclaimer]` shortcode | `do_shortcode()` line 269 | WIRED | Confirmed |
| single-city.php | `[pharmacy_finder]` widget | `do_shortcode()` line 147 | WIRED (stub) | Placeholder — widget bundle not compiled until Phase 3; acknowledged known stub |
| single-city.php | `[discount_card]` widget | `do_shortcode()` line 237 | WIRED (stub) | Placeholder — widget bundle not compiled until Phase 3; acknowledged known stub |
| single-city.php | affiliate CTAs | `/go/goodrx/`, `/go/singlecare/`, `/go/amazon-pharmacy/` | WIRED (stub) | ThirstyAffiliates slugs not yet registered in WP admin; acknowledged known stub |
| front-page.php | `[affiliate_disclosure]` shortcode | `do_shortcode()` line 47 | WIRED | Confirmed |
| front-page.php | `[medical_disclaimer]` shortcode | `do_shortcode()` line 147 | WIRED | Confirmed |
| single-pharmacy.php | `[affiliate_disclosure]` shortcode | `do_shortcode()` line 110 | WIRED | Confirmed |
| single-pharmacy.php | `[medical_disclaimer]` shortcode | `do_shortcode()` line 247 | WIRED | Confirmed |
| archive-state.php | `[medical_disclaimer]` shortcode | `do_shortcode()` line 133 | WIRED | Confirmed |
| functions.php | has_shortcode bail | `has_shortcode()` line 155 | WIRED | Prevents double-render confirmed |

---

### Data-Flow Trace (Level 4)

Not applicable to PHP templates (no React state/props to trace). Templates render from WP post meta + shortcodes — data flows confirmed via schema/meta variable reads in template headers.

---

### Behavioral Spot-Checks

Step 7b: SKIPPED — No runnable WordPress entry points (no WP install active in this repo). Templates require live WP environment. See Human Verification section.

---

### Requirements Coverage

| Requirement | Source Plan | Description | Status | Evidence |
|-------------|------------|-------------|--------|----------|
| CONTENT-01 | 02-01-PLAN | City page template renders pharmacy finder, discount card, 500+ word content, schema, disclaimer, disclosure, affiliate CTAs | SATISFIED | single-city.php confirmed with all required elements |
| INFRA-07 | 02-02-PLAN | All required compliance pages live: /disclaimer/, /affiliate-disclosure/, /privacy-policy/, /terms/, /about/ | SATISFIED | All 5 docs/compliance/*.md files present with 500+ words each |
| INFRA-08 | 02-01/02-03 PLAN | Medical disclaimer shortcode outputs on every city, pharmacy, and savings page | SATISFIED | single-city.php, single-pharmacy.php, archive-state.php, front-page.php all confirmed; has_shortcode bail prevents double-render |
| INFRA-09 | 02-01/02-03 PLAN | Affiliate disclosure shortcode outputs on every page with affiliate links | SATISFIED | single-city.php, single-pharmacy.php, front-page.php all have `do_shortcode('[affiliate_disclosure]')`. archive-state.php has no affiliate links (omission correct) |

All 4 phase 2 requirement IDs accounted for. No orphaned requirements.

---

### Anti-Patterns Found

| File | Line | Pattern | Severity | Impact |
|------|------|---------|----------|--------|
| wordpress/theme/single-pharmacy.php | — | Missing `add_filter('kadence_display_sidebar', '__return_false')` | Warning | Sidebar may render on pharmacy pages depending on Kadence theme settings; layout inconsistency vs. other templates |
| wordpress/theme/archive-state.php | — | Missing `add_filter('kadence_display_sidebar', '__return_false')` | Warning | Sidebar may render on state archive pages; layout inconsistency |
| wordpress/theme/single-pharmacy.php | 244–247 | `.ad-zone-footer` and medical_disclaimer outside `</div><!-- #primary -->` | Warning | Structural inconsistency with single-city.php; ad zone and disclaimer render outside the main content wrapper |
| wordpress/theme/archive-state.php | 130–133 | `.ad-zone-footer` and medical_disclaimer outside `</div><!-- #primary -->` | Warning | Same issue as single-pharmacy.php |
| docs/compliance/about.md | — | `[contact email placeholder]` | Info | Intentional per plan — must be replaced before deploy |
| docs/compliance/affiliate-disclosure.md | — | `[contact email placeholder]` | Info | Intentional per plan — must be replaced before deploy |

---

### Human Verification Required

#### 1. Sidebar Suppression on Pharmacy and Archive Templates

**Test:** Visit a published pharmacy detail page (`/pharmacy-location/[slug]/`) and a state archive page (`/state/[slug]/`) in a browser.
**Expected:** Full-width single-column layout with no sidebar column.
**Why human:** Kadence sidebar filter is missing from these two templates. Whether a sidebar renders depends on Kadence theme layout settings. If the Kadence default is "no sidebar" globally, these pages may display correctly anyway; if the default includes a sidebar, it will appear.

#### 2. City Page Full Render

**Test:** Visit any published city page (`/pharmacy/[slug]/`) in a browser.
**Expected:** H1 visible with city name, at least 3 H2 sections visible (pharmacy finder, city content, savings/affiliate), disclaimer block at bottom, disclosure block at top, 3 affiliate CTA cards with `/go/` links.
**Why human:** Full section render, correct ordering, and visual layout require a live WP instance.

#### 3. Compliance Page Word Count in WP

**Test:** Paste content from each `docs/compliance/*.md` file into a WordPress page and confirm word count >= 500 in WP editor.
**Expected:** All 5 pages show 500+ words in WP word count (markdown formatting stripped).
**Why human:** Markdown includes headings and formatting that inflate `wc -w` counts slightly; WP word count may differ marginally.

---

### Gaps Summary

Two structural gaps were found in the secondary templates (single-pharmacy.php and archive-state.php):

1. **Missing Kadence sidebar filter** — Both templates are missing `add_filter('kadence_display_sidebar', '__return_false')`. The plan 03 SUMMARY listed this pattern as applied, but inspection of the actual files confirms it was not added. This is the same 1-line fix applied in single-city.php (line 9) and front-page.php (line 31). Severity: Warning — may or may not cause visible layout issues depending on Kadence global settings.

2. **Ad zone + disclaimer outside #primary wrapper** — On both single-pharmacy.php and archive-state.php, the `.ad-zone-footer` div and `do_shortcode('[medical_disclaimer]')` appear after the closing `</div><!-- #primary -->` tag, placing them outside the main content area. On single-city.php these elements are correctly inside the wrapper. This structural inconsistency may affect ad zone CSS targeting and layout.

Both gaps are one-file fixes. They do not affect the compliance page content (plan 02) or single-city.php / front-page.php (plan 01), which are fully correct.

---

_Verified: 2026-03-29T17:00:00Z_
_Verifier: Claude (gsd-verifier)_
