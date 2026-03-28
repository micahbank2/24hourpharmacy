# Testing Patterns

**Analysis Date:** 2026-03-28

## Test Framework

**Runner:** None detected.

No automated test framework is installed or configured. No `jest.config.*`, `vitest.config.*`, or test runner entries exist in any `package.json`. No `*.test.*` or `*.spec.*` files exist in the codebase.

**PHP Testing:** No PHPUnit configuration detected. No `phpunit.xml` or `tests/` directory.

**Python Testing:** No pytest or unittest files in `data/scripts/`.

## What Validation Exists

**Manual deployment checklist:** `docs/seo-checklist.md` — covers SEO and content requirements (exact contents not fully implemented yet).

**Build validation:** Vite build process (`npm run build`) serves as a compile-time check for React widget source. Build output to `wordpress/plugin/24hr-pharmacy-tools/assets/js/` is the only automated gate.

**PHP validation:** WordPress's built-in fatal error handling is the only safety net. No static analysis (PHPStan, PHPCS) is configured.

## Core Web Vitals Requirements

These are hard requirements — violating them harms SEO rankings. Verify manually with PageSpeed Insights or Chrome DevTools after any template or widget change:

| Metric | Target |
|--------|--------|
| LCP (Largest Contentful Paint) | < 2.5s |
| CLS (Cumulative Layout Shift) | < 0.1 |
| INP (Interaction to Next Paint) | < 200ms |

**React widget async loading is mandatory** to protect LCP. Widgets enqueue in footer (`true` as last arg to `wp_enqueue_script()`). Never enqueue widget scripts in `<head>`.

CLS risk: widget containers must have explicit height or aspect ratio defined before JS loads to prevent layout shift when React mounts.

## Mobile Testing Requirements

**Minimum viewport: 375px** (iPhone SE). 70%+ of traffic is mobile.

Test at these breakpoints before any template or widget change:
- 375px — minimum supported
- 390px — iPhone 14/15
- 414px — iPhone Plus sizes
- 768px — tablet

**All CSS is mobile-first.** Base styles target 375px; scale up with `min-width` queries. Do not write desktop-first styles and override with `max-width`.

Widget CSS example pattern from `widgets/discount-card/src/styles.css`:
```css
/* Base (375px) */
.dc-card__fields {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md, 1rem);
}

/* Scale up */
@media (min-width: 480px) { ... }
```

## Content Requirements (Manual Verification)

Every city page must pass these checks before publishing:

- [ ] 500+ words of unique content (Google penalizes thin programmatic pages)
- [ ] Valid JSON-LD structured data present in `<head>` (use Google's Rich Results Test)
- [ ] Proper heading hierarchy: one `h1`, `h2` for sections, `h3` for subsections
- [ ] Medical disclaimer visible on page
- [ ] FTC affiliate disclosure visible if affiliate links present
- [ ] Ad zone divs present: `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer`
- [ ] `noscript` fallback inside each widget container div

## PHP Code Safety (Manual)

Before deploying plugin or theme changes:

- All output uses `esc_attr()`, `esc_html()`, `esc_url()`, or `esc_html__()`
- No hardcoded API keys, affiliate IDs, or tracking codes in PHP files
- All options accessed via `get_option()` with the `twentyfourhour_` prefix
- Shortcode attributes processed through `shortcode_atts()` with defaults
- `ABSPATH` guard at top of every PHP include file

## Widget Build Verification

After running `npm run build` in any widget directory:

- Output file exists at `wordpress/plugin/24hr-pharmacy-tools/assets/js/{widget-name}.js`
- CSS output exists at `wordpress/plugin/24hr-pharmacy-tools/assets/js/{widget-name}.css`
- Bundle format is IIFE (required for WordPress compatibility — not ESM)
- `window.PharmacyToolsConfig` graceful degradation: widget renders fallback UI when config unavailable, not a blank div or JS error

Manual smoke test: open `widgets/{widget-name}/index.html` in browser (if present) to verify render without WordPress environment.

## Test Coverage Gaps

**No automated tests exist anywhere in the codebase.** All verification is manual.

**High-priority gaps:**

**React widgets:**
- No unit tests for component rendering or prop handling
- No tests for `getConfig()` graceful fallback when `window.PharmacyToolsConfig` is absent
- No tests for clipboard API failure path in copy handler
- Files: `widgets/discount-card/src/App.jsx`, `widgets/discount-card/src/components/DiscountCard.jsx`

**PHP shortcodes:**
- No tests verifying `shortcode_atts()` defaults are applied correctly
- No tests verifying `esc_attr()` applied to all shortcode output
- No tests for `pharmacy_tools_enqueue_widget()` cache-busting logic
- File: `wordpress/plugin/24hr-pharmacy-tools/includes/shortcodes.php`

**Data scripts:**
- No tests for `generate-city-pages.py` or `sync-pharmacy-data.py`
- Rate limiting logic is untested
- File: `data/scripts/generate-city-pages.py`, `data/scripts/sync-pharmacy-data.py`

**JSON-LD schema:**
- No automated validation that output is valid structured data
- File: `wordpress/plugin/24hr-pharmacy-tools/includes/class-schema.php` (stub — not yet implemented)
- Manual check: Google Rich Results Test after each schema change

**Content word count:**
- No automated check that city pages meet 500-word minimum
- Risk: programmatic pages could be published thin without detection

**Core Web Vitals:**
- No CI/CD performance budget enforcement
- Must be checked manually after every deploy via PageSpeed Insights

## Recommended Testing to Add

Priority order based on risk:

1. **Vitest for React widgets** — add `vitest` + `@testing-library/react` to each widget's `package.json`; test config loading, fallback rendering, and copy interaction
2. **PHPUnit for plugin** — add `phpunit/phpunit` and a `tests/` directory; test shortcode output escaping and option reading
3. **Lighthouse CI** — add to deployment workflow to enforce Core Web Vitals budget automatically
4. **JSON-LD validator** — script that fetches a city page and validates structured data against schema.org spec

---

*Testing analysis: 2026-03-28*
