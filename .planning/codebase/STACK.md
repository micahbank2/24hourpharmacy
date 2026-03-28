# Technology Stack

**Analysis Date:** 2026-03-28

## Languages

**Primary:**
- PHP - WordPress theme (`wordpress/theme/`) and custom plugin (`wordpress/plugin/24hr-pharmacy-tools/`)
- JavaScript (ES6+) - React widget source in `widgets/`

**Secondary:**
- Python 3.11 - Data scripts in `data/scripts/` (not production code)
- CSS - Child theme styles at `wordpress/theme/assets/css/custom.css`

## Runtime

**Environment:**
- WordPress 6.x — CMS runtime on Hostinger shared/VPS hosting
- Browser JS — compiled widget bundles run client-side

**Package Manager:**
- npm — per-widget in each `widgets/*/` directory
- pip — Python deps via `data/scripts/requirements.txt`
- Lockfiles: not confirmed present (no package-lock.json found in repo root; widget lockfiles may exist locally)

## Frameworks

**Core:**
- WordPress 6.x — CMS, routing, content management
- GeneratePress Pro — parent theme; child theme at `wordpress/theme/`

**Frontend (Widgets):**
- React 18 — functional components with hooks; no class components
- Vite — build tool per widget, compiles to single JS bundle
  - Config at `widgets/pharmacy-finder/vite.config.js` (each widget has its own)
  - Output: `wordpress/plugin/24hr-pharmacy-tools/assets/js/{widget-name}.js`

**Data Scripts:**
- No framework; plain Python with `requests` and `python-dotenv`

## Key Dependencies

**PHP/WordPress:**
- GeneratePress Pro (parent theme) — loaded on server, not in repo
- WordPress core — loaded on server, not in repo
- Custom plugin `24hr-pharmacy-tools` at `wordpress/plugin/24hr-pharmacy-tools/24hr-pharmacy-tools.php`

**Python (`data/scripts/requirements.txt`):**
- `requests>=2.31.0` — HTTP calls to WordPress REST API and Google Maps API
- `python-dotenv>=1.0.0` — loads `.env` for API keys during script runs

**JavaScript (per widget — no root package.json found):**
- React 18
- Vite (build tool)
- Versions pinned per-widget in `widgets/*/package.json` (not all widgets scaffolded yet)

## Configuration

**Environment:**
- `.env.example` in repo root — template only, no real values committed
- Key vars: `GOOGLE_MAPS_API_KEY`, `WP_REST_URL`, `WP_APP_USER`, `WP_APP_PASSWORD`
- Affiliate tracking codes stored in WordPress options table via plugin settings (`class-settings.php`), not in `.env`
- PHP reads affiliate codes via `get_option()` — never hardcoded

**Build:**
- Per-widget Vite config: `widgets/pharmacy-finder/vite.config.js`
- Widget build output lands in `wordpress/plugin/24hr-pharmacy-tools/assets/js/`
- WordPress enqueues bundles via `wp_enqueue_script()` keyed to shortcode presence
- Config passed PHP → JS via `wp_localize_script()`

## Platform Requirements

**Development:**
- Node (version not specified) + npm for widget builds
- Python 3.11 for data scripts
- WordPress admin access for manual deploys
- `.env` populated for data script runs

**Production:**
- Hostinger hosting (WordPress 6.x)
- Deployment is currently manual: zip upload via WP admin for theme and plugin
- Data scripts run locally and push content via WordPress REST API
- No CI/CD pipeline in place

## Notable Observations

- No root-level package.json — each widget is an isolated Vite project
- No Tailwind; styling uses GeneratePress CSS custom properties and minimal custom CSS
- React widgets must load async (Core Web Vitals requirement: LCP < 2.5s)
- Four widget slots defined but scaffolding completeness varies: `pharmacy-finder`, `discount-card`, `price-checker`, `open-now-checker`
- Manual deployment is the only current path to production — no automated deploy on push

---

*Stack analysis: 2026-03-28*
