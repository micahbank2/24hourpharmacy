# Coding Conventions

**Analysis Date:** 2026-03-28

## Naming Patterns

**PHP Files:**
- Class files: `class-{name}.php` in the main branch scaffold (e.g., `class-post-types.php`, `class-schema.php`)
- Procedural includes: `{noun}.php` without class prefix (e.g., `post-types.php`, `shortcodes.php`, `schema.php`) — used in the worktree branch
- Templates: WordPress naming conventions (`single-{post-type}.php`, `archive-{post-type}.php`, `front-page.php`)

**PHP Functions:**
- All custom functions prefixed with `pharmacy_tools_` (e.g., `pharmacy_tools_register_shortcodes`, `pharmacy_tools_enqueue_widget`)
- Child theme functions prefixed with `twentyfourhour_` (e.g., `twentyfourhour_enqueue_styles`)
- Snake_case throughout

**PHP Constants:**
- Screaming snake_case, prefixed with `PHARMACY_TOOLS_` (e.g., `PHARMACY_TOOLS_VERSION`, `PHARMACY_TOOLS_PATH`)

**WordPress Options:**
- All options prefixed with `twentyfourhour_` (e.g., `twentyfourhour_google_maps_key`, `twentyfourhour_lowermyrx_bin`)

**React/JS Files:**
- Components: PascalCase `.jsx` (e.g., `DiscountCard.jsx`, `Disclosure.jsx`)
- Entry point: `main.jsx`, `App.jsx`
- Styles: `styles.css` per widget

**CSS Classes:**
- Widget-scoped BEM-style with widget prefix (e.g., `.dc-` for discount-card, `.dc-card`, `.dc-card__header`, `.dc-card__btn--copy`)
- Ad zone classes: `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer` (required on all pages)
- WordPress widget containers: `.pharmacy-widget`, `.{widget-name}-widget` (e.g., `.discount-card-widget`)

**JS Global Config:**
- WordPress passes config via `wp_localize_script()` to `window.PharmacyToolsConfig`
- Widget reads with graceful fallback: `window.PharmacyToolsConfig?.affiliate`

## PHP Coding Style

Follow WordPress Coding Standards (WPCS):

- Tabs for indentation (not spaces)
- Space before opening brace of functions and control structures
- Space after commas in function arguments
- All files begin with `if ( ! defined( 'ABSPATH' ) ) { exit; }` guard
- Docblock on every function and file header (`@package`, `@param`, `@return`)
- Use `esc_attr()`, `esc_html()`, `esc_url()` on all output — never echo raw user data
- Use `get_option()` / `update_option()` for settings storage — no hardcoded values
- Native WP functions only — no framework dependencies (Composer, etc.)
- Use `sprintf()` for HTML string construction with escaping, not heredoc/concatenation

Example from `wordpress/plugin/24hr-pharmacy-tools/includes/shortcodes.php`:
```php
return sprintf(
    '<div id="discount-card-root" class="pharmacy-widget discount-card-widget" data-style="%s">' .
    '<noscript><p>%s</p></noscript>' .
    '</div>',
    esc_attr( $atts['style'] ),
    esc_html__( 'Please enable JavaScript to view the discount card.', '24hr-pharmacy-tools' )
);
```

- Load scripts in footer (`true` as last arg to `wp_enqueue_script()`)
- Cache-bust with `filemtime()` when file exists, fall back to version constant
- Use `shortcode_atts()` with defaults for all shortcode attributes
- Text domain: `24hr-pharmacy-tools` for all i18n functions

## React/JS Patterns

**Rules:**
- ES6+ only. Functional components with hooks. No class components.
- Do NOT use Tailwind. Style with CSS custom properties and widget-scoped CSS.
- No external UI libraries inside widgets — self-contained bundles only.

**Config access pattern** (from `widgets/discount-card/src/App.jsx`):
```jsx
function getConfig() {
  const config = window.PharmacyToolsConfig;
  if (!config || !config.affiliate) return null;
  return config.affiliate;
}

export default function App({ style = 'full' }) {
  const config = useMemo(() => getConfig(), []);
  // Graceful fallback when config missing:
  if (!config || !config.lowermyrx_bin) {
    return <div className="dc-fallback"><p>Coming soon.</p></div>;
  }
  // ...
}
```

**Component structure:**
- Each widget has `App.jsx` (orchestrator) and `components/` subfolder
- Sub-components receive only the props they render — no prop drilling of full config
- Helper/sub-components defined in same file when small (e.g., `function Field({ label, value })` in `DiscountCard.jsx`)

**Error handling:**
- Silent fail on unavailable browser APIs: `try { ... } catch { // silent fail. }`
- Always provide fallback UI when config not available

**Build output:**
- Each widget compiles to IIFE bundle via Vite
- Output goes to `wordpress/plugin/24hr-pharmacy-tools/assets/js/{widget-name}.js`
- CSS output: `wordpress/plugin/24hr-pharmacy-tools/assets/js/{widget-name}.css`
- Config: `vite.config.js` at each widget root with `lib.formats: ['iife']`

## CSS Approach

**No Tailwind.** All widget CSS uses:
- Widget-scoped BEM selectors with widget prefix (`.dc-`, `.pf-`, etc.)
- CSS custom properties with Kadence theme fallbacks:
  ```css
  color: var(--color-text, #1A1A1A);
  padding: var(--spacing-lg, 1.5rem);
  border-radius: var(--radius-md, 8px);
  ```
- Mobile-first: base styles target 375px minimum, scale up with `min-width` media queries
- No Tailwind classes, no utility-class frameworks

**Theme CSS:**
- Child theme styles: `wordpress/theme/assets/css/custom.css`
- Uses Kadence built-in grid — do not replicate grid logic in custom CSS
- Custom styles should be minimal augmentations only

**Ad zones (required):**
All page templates must include container divs with these classes:
- `.ad-zone-header` — above content
- `.ad-zone-sidebar` — sidebar slot
- `.ad-zone-in-content` — mid-content
- `.ad-zone-footer` — below content

**Print styles:**
Widget CSS includes `@media print` rules to hide interactive elements and ensure card fields remain visible.

## File Organization

**Theme files** (`wordpress/theme/`):
- One template per post type / page type
- `functions.php` for enqueues and hooks only — no business logic
- `assets/css/custom.css` for all theme CSS overrides

**Plugin files** (`wordpress/plugin/24hr-pharmacy-tools/`):
- Main file: `24hr-pharmacy-tools.php` — bootstrap only (define constants, require includes, register hooks)
- All logic in `includes/` — one file per concern
- Compiled widget assets output to `assets/js/`

**Widget source** (`widgets/{widget-name}/`):
- Each widget is an independent Vite project with its own `package.json` and `vite.config.js`
- Source in `src/` with `main.jsx`, `App.jsx`, `components/`, and `styles.css`
- Build output goes directly to plugin `assets/js/`

**Data scripts** (`data/scripts/`):
- Python 3.11, type hints required
- Handle rate limiting on external APIs
- Not production code — no need for test coverage

## Key Rules (Non-Negotiable)

1. **No hardcoded API keys or affiliate codes.** Use `get_option()` in PHP, `window.PharmacyToolsConfig` in JS.
2. **Medical disclaimer on every page.** YMYL content — required for Google compliance.
3. **FTC affiliate disclosure on every page with affiliate links.**
4. **No health claims or medical advice.** Information and referrals only.
5. **500+ words of unique content per city page.** Thin programmatic pages get penalized.
6. **React widgets load async** (footer enqueue + `wp_enqueue_script` with `true` last arg).
7. **Every page: valid JSON-LD structured data + proper heading hierarchy** (`h1` > `h2` > `h3`).
8. **Ad zone classes built into every template** before content goes live.
9. **`noscript` fallback in every shortcode output.**

---

*Convention analysis: 2026-03-28*
