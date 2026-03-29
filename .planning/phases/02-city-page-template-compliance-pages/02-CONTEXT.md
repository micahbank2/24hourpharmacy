# Phase 2: City Page Template + Compliance Pages - Context

**Gathered:** 2026-03-29
**Status:** Ready for planning

<domain>
## Phase Boundary

Build 4 PHP templates (`single-city.php`, `front-page.php`, `single-pharmacy.php`, `archive-state.php`) and write real content for 5 compliance pages (`/disclaimer/`, `/affiliate-disclosure/`, `/privacy-policy/`, `/terms/`, `/about/`). Templates must render widget placeholders, ad zones, compliance shortcodes, affiliate CTAs, and proper heading hierarchy.

Requirements covered: INFRA-07, INFRA-08, INFRA-09, CONTENT-01

</domain>

<decisions>
## Implementation Decisions

### City page layout (single-city.php)
- **D-01:** Pharmacy finder widget is the hero — H1 + `[pharmacy_finder]` shortcode above the fold. Tool-first, content second.
- **D-02:** Section order below the fold: 500+ word content block → `[discount_card]` widget placeholder → affiliate CTA cards → medical disclaimer + affiliate disclosure at bottom.
- **D-03:** Full-width single column layout, no sidebar. Mobile-first (70%+ mobile traffic).
- **D-04:** Affiliate CTAs appear as styled card blocks (icon + short copy + button linking to ThirstyAffiliates `/go/` URLs). 2-3 cards stacked vertically in a single section after the content block. One CTA section only — not repeated.

### Homepage (front-page.php)
- **D-05:** Search-first hero: big H1 ("Find a 24-Hour Pharmacy Near You"), simple city name input with autocomplete that redirects to `/city/{slug}/`. No API calls on homepage — real finder widget loads on city pages only.
- **D-06:** Below the fold: popular city cards (links to city pages) → 3-step "How It Works" section → featured articles. Good for SEO internal linking + trust.

### State listing (archive-state.php)
- **D-07:** Simple alphabetical city list with pharmacy counts. State name as H1. Clean, fast, good for SEO internal linking. No map (maps come in Phase 3).

### Pharmacy detail (single-pharmacy.php)
- **D-08:** Basic info card layout: pharmacy name, address, hours, phone, map placeholder, then links to nearby city pages. Minimal — real pharmacy data populates in Phase 4.

### Compliance pages
- **D-09:** Created as regular WordPress pages (content in database, editable via WP admin). This phase produces the text content as files in the repo; content gets pasted into WP admin on deploy.
- **D-10:** Detailed and specific content, 500-800 words each. Privacy policy covers GA4, Microsoft Clarity, CookieYes, affiliate cookies specifically. Terms covers YMYL disclaimers. Not boilerplate — site-specific language.
- **D-11:** About page is brand-only (24HourPharmacy.com as a resource). No personal name. Easier to scale or bring on contributors later.

### Claude's Discretion
- Ad zone placement on city pages (header, in-content, footer positioning optimized for Mediavine best practices)
- Exact heading hierarchy (H2/H3 structure within content sections)
- City autocomplete implementation approach on homepage (could be a simple dropdown from cities.json data or a text input with JS filtering)
- Pharmacy detail page section ordering
- CSS styling for affiliate CTA cards (using existing Kadence custom properties)

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Plugin foundation (Phase 1 outputs)
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php` — Shortcode implementations for `[medical_disclaimer]`, `[affiliate_disclosure]`, `[pharmacy_finder]`, `[discount_card]`
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-schema.php` — JSON-LD schema output hooks (Pharmacy, City, WebPage, FAQPage)
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-post-types.php` — City and Pharmacy CPT registration with rewrite rules

### Theme foundation
- `wordpress/theme/functions.php` — CPT registration (theme-side), widget config via `wp_localize_script`, auto-appended disclaimer via `the_content` filter
- `wordpress/theme/assets/css/custom.css` — Ad zone CSS (`.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer`), medical disclaimer styles, FTC disclosure styles, hours badge, pharmacy card styles

### Data
- `data/cities.json` — Target cities with coordinates (used for homepage autocomplete and city page generation)

### Project docs
- `CLAUDE.md` — Coding standards, file structure, key rules (mobile-first, YMYL disclaimers, FTC disclosure, ad zones, no hardcoded keys)
- `.planning/REQUIREMENTS.md` — INFRA-07 through INFRA-09, CONTENT-01 acceptance criteria

</canonical_refs>

<code_context>
## Existing Code Insights

### Reusable Assets
- `[medical_disclaimer]` shortcode: renders `<div class="pharmacy-disclaimer">` with disclaimer text
- `[affiliate_disclosure]` shortcode: renders `<div class="pharmacy-disclosure">` with FTC text
- `[pharmacy_finder]` shortcode: renders `<div id="pharmacy-finder-widget">` + conditionally enqueues JS bundle
- `[discount_card]` shortcode: renders `<div id="discount-card-widget">` + conditionally enqueues JS bundle
- `twentyfourhour_append_disclaimer()` filter: auto-appends medical disclaimer to `the_content` on city/pharmacy/page post types
- Ad zone CSS classes already styled with CLS-safe min-heights and dashed placeholder borders

### Established Patterns
- Kadence CSS custom properties for all colors (`--global-palette1` through `--global-palette9`) — never hardcode hex
- Child theme enqueues: `kadence-style` → `twentyfourhour-style` → `twentyfourhour-custom`
- Widget config passed via `window.PharmacyToolsConfig` using `wp_localize_script()`
- Plugin uses class-based architecture with `register()` method pattern
- State taxonomy already registered for grouping cities

### Integration Points
- Templates use `get_header()` / `get_footer()` from Kadence parent theme
- Shortcodes called inline in template PHP: `<?php echo do_shortcode('[medical_disclaimer]'); ?>`
- Schema auto-outputs via `wp_head` hook — no template action needed
- Widget JS bundles enqueue conditionally when shortcode is present on page

</code_context>

<specifics>
## Specific Ideas

No specific requirements — open to standard approaches within the decisions above.

</specifics>

<deferred>
## Deferred Ideas

None — discussion stayed within phase scope.

</deferred>

---

*Phase: 02-city-page-template-compliance-pages*
*Context gathered: 2026-03-29*
