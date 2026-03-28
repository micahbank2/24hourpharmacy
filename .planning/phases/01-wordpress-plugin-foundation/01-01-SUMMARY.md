---
phase: 01-wordpress-plugin-foundation
plan: "01"
subsystem: wordpress-plugin
tags: [cpt, settings-api, json-ld, shortcodes, wp-localize-script]
dependency_graph:
  requires: []
  provides: [INFRA-01, INFRA-02, INFRA-03, INFRA-04, INFRA-05]
  affects: [wordpress/plugin/24hr-pharmacy-tools, wordpress/theme]
tech_stack:
  added: []
  patterns:
    - WordPress Settings API for admin options page
    - register_post_type with activation-only flush_rewrite_rules
    - Conditional script enqueue via shortcode callback
    - JSON-LD via wp_head with wp_json_encode
    - wp_localize_script at priority 20 for config passthrough
key_files:
  created: []
  modified:
    - wordpress/plugin/24hr-pharmacy-tools/24hr-pharmacy-tools.php
    - wordpress/plugin/24hr-pharmacy-tools/includes/class-post-types.php
    - wordpress/plugin/24hr-pharmacy-tools/includes/class-settings.php
    - wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php
    - wordpress/plugin/24hr-pharmacy-tools/includes/class-schema.php
    - wordpress/theme/functions.php
decisions:
  - "Register scripts unconditionally (with file_exists guard) — enqueue conditionally inside shortcode callback; this ensures wp_localize_script can reference the handle before shortcodes process"
  - "output_faqpage_schema() outputs empty mainEntity array as Phase 1 stub; Phase 7 populates without hook changes"
  - "wp_localize_script called at priority 20 so plugin's priority-10 register_scripts runs first"
metrics:
  duration: "~20 minutes"
  completed_date: "2026-03-28"
  tasks_completed: 2
  files_modified: 6
---

# Phase 01 Plan 01: WordPress Plugin Foundation Summary

**One-liner:** City and Pharmacy CPTs, Settings API admin page (6 option fields), JSON-LD schema (Pharmacy/WebPage/WebSite/FAQPage), compliance shortcodes, and window.PharmacyToolsConfig passthrough — all wired in six PHP files replacing stubs.

---

## Tasks Completed

| Task | Name | Commit | Files |
|------|------|--------|-------|
| 1 | CPT registration and main plugin bootstrap | 9ac8b57 | class-post-types.php, 24hr-pharmacy-tools.php |
| 2 | Settings page, shortcodes, schema, theme functions | 045a08e | class-settings.php, class-shortcodes.php, class-schema.php, functions.php |

---

## What Was Built

**class-post-types.php** — `Pharmacy_Tools_Post_Types` with `register_city_cpt()`, `register_pharmacy_cpt()`, and `activation()`. Both CPTs: `public=true`, `has_archive=false`, `with_front=false`, `show_in_rest=true`. `flush_rewrite_rules()` only in `activation()`.

**24hr-pharmacy-tools.php** — All four classes instantiated and `register()` called. `register_activation_hook` and `register_deactivation_hook` wired.

**class-settings.php** — `Pharmacy_Tools_Settings` with WP Settings API. Three sections, six fields: `twentyfourhour_google_maps_key`, `twentyfourhour_lowermyrx_bin/pcn/group`, `twentyfourhour_amazon_pharmacy_affiliate_id`, `twentyfourhour_singlecare_affiliate_id`. All use `sanitize_text_field`. Capability guard on `render_page()`.

**class-shortcodes.php** — `Pharmacy_Tools_Shortcodes`. Compliance shortcodes `[medical_disclaimer]` and `[affiliate_disclosure]` return escaped HTML. Widget shortcodes `[pharmacy_finder]` and `[discount_card]` call `wp_enqueue_script()` inside callback. `register_scripts()` uses `file_exists()` guard with `filemtime()` versioning, in-footer flag.

**class-schema.php** — `Pharmacy_Tools_Schema`. `output_schema()` guarded with `is_admin()`. Per-type dispatch: Pharmacy→`['Pharmacy','LocalBusiness']`, City→`WebPage` with excerpt, front page→`WebSite`. Generic `WebPage` fires on non-CPT singular pages. `output_faqpage_schema()` stub outputs `FAQPage` with empty `mainEntity`.

**functions.php** — `twentyfourhour_localize_config()` hooked at priority 20. Checks `wp_script_is('pharmacy-finder', 'registered')` and `wp_script_is('discount-card', 'registered')` before calling `wp_localize_script`. Config includes `ajaxUrl`, `siteUrl`, `affiliate.*`, `maps.api_key` — all via `get_option()` with empty-string defaults.

---

## Decisions Made

1. **Script registration uses unconditional wp_register_script (with file_exists guard for URL-only vs filemtime versioning)** — ensures the handle exists for `wp_localize_script` in functions.php priority-20 hook, before shortcodes run.

2. **FAQPage stub outputs empty `mainEntity` array** — Phase 7 populates FAQ data without needing to add a new hook; the schema type is already registered and discoverable by Google.

3. **wp_localize_script priority 20** — plugin registers scripts at default priority 10 on `wp_enqueue_scripts`; theme localize runs at 20 to guarantee the handle exists.

---

## Deviations from Plan

None — plan executed exactly as written. All ABSPATH guards, docblocks, WPCS tabs, and text domain `24hr-pharmacy-tools` applied throughout.

---

## Known Stubs

- `output_faqpage_schema()` in class-schema.php: `mainEntity` is an empty array. Intentional Phase 1 stub; Phase 7 will populate FAQ entries. The plan's INFRA-04 deliverable explicitly requires this stub.
- `register_scripts()` in class-shortcodes.php: widget JS files (`pharmacy-finder.js`, `discount-card.js`) do not exist until Phase 3. Scripts are registered with the PHARMACY_TOOLS_VERSION fallback but will 404 if enqueued before Phase 3 builds them. This is expected and noted in the plan.

---

## Checkpoint: Task 3

Task 3 is a `checkpoint:human-verify` — deployment and live site verification required before proceeding to Phase 2. See checkpoint details below.

---

## Self-Check: PASSED

- wordpress/plugin/24hr-pharmacy-tools/24hr-pharmacy-tools.php: FOUND
- wordpress/plugin/24hr-pharmacy-tools/includes/class-post-types.php: FOUND
- wordpress/plugin/24hr-pharmacy-tools/includes/class-settings.php: FOUND
- wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php: FOUND
- wordpress/plugin/24hr-pharmacy-tools/includes/class-schema.php: FOUND
- wordpress/theme/functions.php: FOUND
- Commit 9ac8b57: FOUND
- Commit 045a08e: FOUND
