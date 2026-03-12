# 24HourPharmacy.com

## What This Repo Contains

Custom code for 24hourpharmacy.com, a WordPress-based content site. WordPress core and third-party plugins are NOT in this repo; they live on the production server. This repo contains only the code we write:

- `wordpress/theme/` - GeneratePress child theme (PHP templates, functions, styles)
- `wordpress/plugin/` - Custom plugin: post types, taxonomies, shortcodes, schema markup
- `widgets/` - React source code for interactive tools (compiled to JS bundles)
- `data/` - City/pharmacy data and Python scripts for programmatic page generation
- `docs/` - Operational documentation

## Tech Stack

- **CMS**: WordPress 6.x on Cloudways (DigitalOcean)
- **Theme**: GeneratePress Pro with custom child theme
- **Custom tools**: React 18, compiled via Vite to standalone JS bundles
- **Data scripts**: Python 3.11
- **Maps**: Google Maps Platform (Places API + Maps JavaScript API)

## Coding Standards

- **PHP**: WordPress Coding Standards (WPCS). Use native WP functions, no framework dependencies.
- **React/JS**: ES6+, functional components, hooks. No class components. Tailwind for styling within widgets.
- **Python**: Data scripts only, not production code. Type hints. Handle rate limiting.
- **CSS**: Minimal. Use GeneratePress built-in grid. Mobile-first (70%+ traffic is mobile).
- **HTML**: Every page needs valid JSON-LD structured data. Every page needs proper heading hierarchy.

## Key Rules

1. **Never hardcode API keys or affiliate tracking codes.** Use environment variables or WordPress options.
2. **Every city page must have 500+ words of unique content.** Google penalizes thin programmatic pages.
3. **Mobile-first everything.** Test at 375px width minimum.
4. **Core Web Vitals matter.** LCP < 2.5s, CLS < 0.1, INP < 200ms. React widgets MUST load async.
5. **Medical disclaimer required on every page.** This is YMYL content in Google's eyes.
6. **FTC affiliate disclosure required on every page with affiliate links.**
7. **Ad placement zones must be built in from the start.** Use classes: `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer`. Mediavine/Raptive will use these.
8. **No health claims. No medical advice.** Information and referrals only.

## Environment Variables

See `.env.example` for the full list. Key ones:
- `GOOGLE_MAPS_API_KEY` - Google Maps Platform
- `WP_REST_URL` / `WP_APP_USER` / `WP_APP_PASSWORD` - WordPress REST API access for data scripts
- Affiliate tracking codes stored in WordPress options table, NOT in this repo

## File Structure

```
├── CLAUDE.md                          # This file
├── .env.example                       # Env var template (no real values)
├── .gitignore
├── wordpress/
│   ├── theme/                         # GeneratePress child theme
│   │   ├── style.css                  # Child theme declaration
│   │   ├── functions.php              # Post types, taxonomies, enqueues
│   │   ├── single-city.php            # City pharmacy finder template
│   │   ├── single-pharmacy.php        # Individual pharmacy template
│   │   ├── archive-state.php          # State city listing
│   │   ├── front-page.php             # Homepage
│   │   ├── page-savings.php           # Savings hub
│   │   └── assets/
│   │       ├── css/custom.css
│   │       └── js/
│   └── plugin/
│       └── 24hr-pharmacy-tools/
│           ├── 24hr-pharmacy-tools.php # Plugin main file
│           ├── includes/
│           │   ├── class-post-types.php
│           │   ├── class-schema.php
│           │   ├── class-shortcodes.php
│           │   └── class-settings.php  # Admin settings for affiliate codes
│           └── assets/
│               └── js/                 # Compiled React widget bundles
├── widgets/
│   ├── pharmacy-finder/
│   │   ├── package.json
│   │   ├── vite.config.js
│   │   └── src/
│   ├── discount-card/
│   ├── price-checker/
│   └── open-now-checker/
├── data/
│   ├── cities.json                    # Target cities with coordinates
│   ├── scripts/
│   │   ├── generate-city-pages.py     # Programmatic page generator
│   │   ├── sync-pharmacy-data.py      # Data refresh utility
│   │   └── requirements.txt
│   └── pharmacies/
│       └── raw/                       # Cached API responses
└── docs/
    ├── affiliate-setup.md
    ├── deployment.md
    └── seo-checklist.md
```

## Building React Widgets

Each widget is a standalone Vite project that compiles to a single JS bundle:

```bash
cd widgets/pharmacy-finder
npm install
npm run build
# Output: wordpress/plugin/24hr-pharmacy-tools/assets/js/pharmacy-finder.js
```

WordPress loads the bundle via `wp_enqueue_script()` when the corresponding shortcode is on the page. Config (API keys, affiliate codes) passes from PHP to JS via `wp_localize_script()`.

## Running Data Scripts

```bash
cd data/scripts
pip install -r requirements.txt
cp ../../.env.example ../../.env  # Fill in real values

# Generate city pages (outputs as WordPress drafts)
python generate-city-pages.py --cities ../cities.json --limit 10

# Refresh pharmacy data for existing cities
python sync-pharmacy-data.py --state texas
```

## Deploying to Production

Currently manual:
1. Zip `wordpress/theme/` folder, upload via WP admin Appearance > Themes
2. Zip `wordpress/plugin/24hr-pharmacy-tools/` folder, upload via WP admin Plugins > Add New
3. Data scripts run from local machine, push content via REST API

See `docs/deployment.md` for full steps.
