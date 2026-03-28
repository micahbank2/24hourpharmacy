# Architecture

**Analysis Date:** 2026-03-28

## Pattern Overview

**Overall:** Multi-layer content site with WordPress as CMS/server, standalone React widgets embedded via shortcodes, and offline Python scripts for programmatic content generation.

**Key Characteristics:**
- WordPress handles all routing, templating, and content storage
- React widgets are compiled to static JS bundles — no runtime build step on the server
- Content generation (city pages) runs as an offline process that pushes to WordPress via REST API
- No API layer owned by this repo — PHP backend is WordPress core + plugin hooks
- Affiliate codes and API keys flow from PHP to React via `wp_localize_script()`

## Layers

**WordPress CMS (Server):**
- Purpose: Routing, content storage, template rendering, plugin execution
- Location: Not in repo — lives on Hostinger production server
- Contains: WordPress core, GeneratePress Pro parent theme, all database content
- Depends on: MySQL, PHP 8.x, GeneratePress Pro (must be installed on server)
- Used by: All web requests

**Child Theme (`wordpress/theme/`):**
- Purpose: Page templates and custom CSS — overrides GeneratePress Pro defaults
- Location: `wordpress/theme/`
- Contains: PHP templates, `functions.php`, `assets/css/custom.css`
- Depends on: GeneratePress Pro parent theme being installed
- Used by: WordPress template hierarchy for every rendered page

**Custom Plugin (`wordpress/plugin/24hr-pharmacy-tools/`):**
- Purpose: Registers custom post types, taxonomies, shortcodes, JSON-LD schema, and admin settings
- Location: `wordpress/plugin/24hr-pharmacy-tools/`
- Contains: `24hr-pharmacy-tools.php` (main loader), four include classes
- Depends on: WordPress hooks (`add_action`, `add_filter`)
- Used by: WordPress at boot via plugin activation; shortcodes used inside post content

**React Widgets (`widgets/`):**
- Purpose: Interactive in-page tools (pharmacy finder, discount card, price checker, open-now checker)
- Location: `widgets/<widget-name>/` (each is a standalone Vite project)
- Contains: React 18 source, `vite.config.js`, `package.json`
- Depends on: Node/npm for build; Google Maps API at runtime (passed via `wp_localize_script`)
- Used by: Plugin shortcodes that enqueue the compiled bundle and render a mount point `<div>`

**Data Scripts (`data/scripts/`):**
- Purpose: Programmatic city-page generation and pharmacy data refresh
- Location: `data/scripts/`
- Contains: Python 3.11 scripts, `requirements.txt`
- Depends on: WordPress REST API, `.env` file with credentials, `data/cities.json`
- Used by: Developer running locally — output published as WordPress draft posts

## Data Flow

**City Page Generation:**

1. Developer runs `python generate-city-pages.py --cities ../cities.json` locally
2. Script reads city coordinates from `data/cities.json` (50 US metros)
3. Script calls Google Places API to find pharmacy locations; responses cached in `data/pharmacies/raw/`
4. Script composes 500+ word page content per city
5. Script calls WordPress REST API (`WP_REST_URL`) using app password credentials to create draft posts
6. Developer reviews drafts in WP admin and publishes

**Page Request (End User):**

1. Browser requests URL (e.g., `/pharmacy-finder/new-york/`)
2. WordPress routes to child theme template (`single-city.php`, `archive-state.php`, etc.)
3. Template calls `get_header()` / `get_footer()` which load GeneratePress Pro structure
4. Plugin shortcodes in page content trigger `wp_enqueue_script()` for appropriate widget bundle
5. Plugin calls `wp_localize_script()` to pass Google Maps API key and affiliate codes to JS
6. Browser loads and executes compiled React bundle; widget mounts into `<div>` placeholder
7. Widget makes client-side Google Maps API calls for live pharmacy data

**Config/Secrets Flow:**

- Google Maps API key: stored in WordPress options table → passed to React via `wp_localize_script()`
- Affiliate tracking codes: stored in WordPress options table (managed via `class-settings.php` admin UI) → passed to React via `wp_localize_script()`
- WP REST credentials: only needed by local data scripts via `.env` — never exposed to browser

## Key Abstractions

**Shortcode-to-Widget Bridge:**
- Purpose: Connect WordPress content to React interactive tools
- Pattern: Plugin registers shortcode → shortcode callback enqueues JS bundle + calls `wp_localize_script()` with config → outputs `<div id="widget-mount">` → React mounts on that div
- Files: `wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php`, compiled bundles in `wordpress/plugin/24hr-pharmacy-tools/assets/js/`

**WordPress Template Hierarchy:**
- Purpose: Map URL patterns to PHP templates
- Examples: `wordpress/theme/single-city.php` (individual city pages), `wordpress/theme/archive-state.php` (state listings), `wordpress/theme/front-page.php` (homepage), `wordpress/theme/single-pharmacy.php` (individual pharmacy)
- Pattern: WordPress resolves template file based on post type and URL; child theme files override GeneratePress Pro defaults

**Custom Plugin Classes:**
- `class-post-types.php` — registers custom post types (e.g., `city`, `pharmacy`)
- `class-schema.php` — outputs JSON-LD structured data in `<head>` via `wp_head` hook
- `class-shortcodes.php` — registers shortcodes that embed React widgets
- `class-settings.php` — admin settings page for affiliate codes (stored in WP options table)

## Entry Points

**Web Requests:**
- Location: WordPress routing (not in repo)
- Triggers: Any HTTP request to the domain
- Responsibilities: Resolves to appropriate PHP template in `wordpress/theme/`

**Widget Initialization:**
- Location: Each widget's `src/main.jsx` (e.g., pattern established in `widgets/discount-card/src/main.jsx`)
- Triggers: Browser parsing the compiled JS bundle on page load
- Responsibilities: Mount React component tree onto `<div>` rendered by shortcode

**Data Script Entry:**
- Location: `data/scripts/generate-city-pages.py`, `data/scripts/sync-pharmacy-data.py`
- Triggers: Developer CLI invocation
- Responsibilities: Fetch/cache external data, compose content, push to WordPress via REST API

## Error Handling

**Strategy:** WordPress-native for PHP; no centralized error handling defined yet in this scaffold.

**Patterns:**
- Plugin files gate on `defined('ABSPATH')` to prevent direct execution
- Data scripts must implement rate limiting per CLAUDE.md requirements (pattern not yet implemented — scripts are stubs)
- React widgets: no error boundary pattern defined yet

## Cross-Cutting Concerns

**Structured Data (SEO):** JSON-LD output handled by `class-schema.php` hooked into `wp_head` — required on every page.

**Medical/Legal Compliance:** Medical disclaimer and FTC affiliate disclosure must appear on every rendered page — enforced in theme templates (not yet implemented in scaffolded templates).

**Ad Zones:** CSS classes `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer` must be present in templates for Mediavine/Raptive ad network integration.

**Config Injection:** All runtime config (API keys, affiliate codes) flows PHP → JS via `wp_localize_script()`. Never hardcoded in JS source.

**Async Widget Loading:** React bundles must load with `async`/`defer` to protect Core Web Vitals (LCP < 2.5s target).

---

*Architecture analysis: 2026-03-28*
