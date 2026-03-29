# Codebase Structure

**Analysis Date:** 2026-03-28

## What's In This Repo vs. What Lives on the Server

**In this repo (custom code only):**
- Kadence child theme (`wordpress/theme/`)
- Custom WordPress plugin (`wordpress/plugin/24hr-pharmacy-tools/`)
- React widget source code (`widgets/`)
- City/pharmacy data and generation scripts (`data/`)
- Operational docs (`docs/`)

**On the production server only (NOT in this repo):**
- WordPress 6.x core
- Kadence (free tier) parent theme (required dependency)
- All other third-party plugins
- MySQL database (all post content, options, user data)
- Compiled widget bundles after deployment (`wordpress/plugin/assets/js/*.js`)

## Directory Layout

```
24hourpharmacy/
├── CLAUDE.md                              # Full project context and rules
├── .env.example                           # Env var template — no real values
├── .gitignore
├── README.md
├── wordpress/
│   ├── theme/                             # Kadence child theme
│   │   ├── style.css                      # Child theme declaration (required by WP)
│   │   ├── functions.php                  # Enqueues parent + child styles
│   │   ├── front-page.php                 # Homepage template (stub)
│   │   ├── single-city.php                # City pharmacy finder page template (stub)
│   │   ├── single-pharmacy.php            # Individual pharmacy page template (stub)
│   │   ├── archive-state.php              # State city listing template (stub)
│   │   └── assets/
│   │       ├── css/custom.css             # Site-wide custom CSS overrides
│   │       └── js/                        # Theme JS (currently empty)
│   └── plugin/
│       └── 24hr-pharmacy-tools/
│           ├── 24hr-pharmacy-tools.php    # Plugin entry point — loads all includes
│           ├── includes/
│           │   ├── class-post-types.php   # Custom post type registration (stub)
│           │   ├── class-schema.php       # JSON-LD structured data output (stub)
│           │   ├── class-shortcodes.php   # Shortcodes that embed React widgets (stub)
│           │   └── class-settings.php     # Admin UI for affiliate codes (stub)
│           └── assets/
│               └── js/                    # Compiled React widget bundles (gitkeep, populated by build)
├── widgets/                               # React widget source (one Vite project per widget)
│   ├── discount-card/                     # Discount card widget (has source — most complete)
│   │   ├── package.json
│   │   ├── package-lock.json
│   │   ├── vite.config.js
│   │   ├── index.html
│   │   └── src/
│   │       ├── main.jsx                   # React mount entry point
│   │       ├── App.jsx                    # Root component
│   │       ├── styles.css                 # Widget-scoped styles
│   │       └── components/
│   │           ├── DiscountCard.jsx       # Main card component
│   │           └── Disclosure.jsx         # FTC disclosure component
│   ├── pharmacy-finder/                   # Pharmacy map finder (stub — gitkeep only)
│   ├── price-checker/                     # Prescription price checker (stub)
│   └── open-now-checker/                  # 24hr status checker (stub)
├── data/
│   ├── cities.json                        # Top 50 US metros with name, state, lat/lng, population
│   ├── scripts/
│   │   ├── generate-city-pages.py         # Creates WP draft posts for each city via REST API
│   │   ├── sync-pharmacy-data.py          # Refreshes pharmacy data for existing city pages
│   │   └── requirements.txt               # Python dependencies
│   └── pharmacies/
│       └── raw/                           # Cached Google Places API responses (gitkeep)
└── docs/
    ├── affiliate-setup.md                 # Affiliate program setup guide
    ├── deployment.md                      # Manual deployment steps (stub)
    ├── seo-checklist.md                   # SEO requirements checklist
    └── command-center/
        └── index.html                     # Operational command center HTML page
```

## Directory Purposes

**`wordpress/theme/`:**
- Purpose: Kadence child theme — controls page layout and presentation
- Contains: PHP template files, one CSS file, placeholder for theme JS
- Key files: `functions.php` (style enqueues), `single-city.php` (main content template), `custom.css` (site styles)
- Note: All templates are stubs — `<!-- to be implemented -->` is the current body content

**`wordpress/plugin/24hr-pharmacy-tools/`:**
- Purpose: All custom WordPress functionality — post types, schema, shortcodes, admin settings
- Contains: Main plugin loader + four class files in `includes/`
- Key files: `24hr-pharmacy-tools.php` (bootstraps all classes), `class-shortcodes.php` (widget embedding), `class-schema.php` (SEO structured data)
- Note: All include classes are stubs — class bodies not yet implemented

**`widgets/`:**
- Purpose: React source for interactive frontend tools; each compiles to a standalone JS bundle
- Contains: One Vite project per widget; `discount-card` is the most complete example
- Build output goes to: `wordpress/plugin/24hr-pharmacy-tools/assets/js/<widget-name>.js`
- Note: `pharmacy-finder`, `price-checker`, `open-now-checker` are gitkeep stubs only

**`data/`:**
- Purpose: Seed data and offline scripts for generating city pages at scale
- Contains: `cities.json` (50 metros), Python scripts, cached API responses
- Key files: `cities.json` (has full data), `generate-city-pages.py` (stub body), `sync-pharmacy-data.py` (stub body)

**`docs/`:**
- Purpose: Operational documentation for humans
- Contains: Affiliate setup guide (has content), SEO checklist (has content), deployment steps (stub), command center HTML

## Key File Locations

**Entry Points:**
- `wordpress/plugin/24hr-pharmacy-tools/24hr-pharmacy-tools.php`: Plugin bootstrap — loads all classes
- `wordpress/theme/functions.php`: Theme bootstrap — enqueues styles
- Each widget's `src/main.jsx`: React mount point for that widget

**Configuration:**
- `.env.example`: Template for all required environment variables
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-settings.php`: WP admin UI for affiliate codes

**Core Logic (to be built):**
- `wordpress/theme/single-city.php`: City page template — main SEO content surface
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php`: Widget embedding via shortcodes
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-schema.php`: JSON-LD output
- `data/scripts/generate-city-pages.py`: Bulk city page creation

**Data:**
- `data/cities.json`: 50 target US metros with coordinates and population

## Naming Conventions

**PHP Files:**
- Plugin classes: `class-{purpose}.php` (e.g., `class-post-types.php`)
- Theme templates: WordPress template hierarchy names (e.g., `single-{post-type}.php`, `archive-{taxonomy}.php`)

**Widgets:**
- Directory: kebab-case matching widget purpose (e.g., `discount-card`, `pharmacy-finder`)
- Build output: `{widget-name}.js` in plugin assets

**CSS Classes (Ad Zones — required pattern):**
- `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer`

## Where to Add New Code

**New PHP feature (post type, taxonomy, schema type):**
- Add to the relevant existing class in `wordpress/plugin/24hr-pharmacy-tools/includes/`
- Register the class loader in `wordpress/plugin/24hr-pharmacy-tools/24hr-pharmacy-tools.php` if creating a new class file

**New page template:**
- Add PHP file to `wordpress/theme/` following WordPress template hierarchy naming
- Include `get_header()`, `get_footer()`, medical disclaimer, FTC disclosure, and all four `.ad-zone-*` divs

**New React widget:**
- Create `widgets/{widget-name}/` with `package.json`, `vite.config.js`, and `src/` matching the `discount-card` widget structure
- Configure Vite output to `wordpress/plugin/24hr-pharmacy-tools/assets/js/{widget-name}.js`
- Register shortcode in `wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php`

**New CSS styles:**
- Site-wide: `wordpress/theme/assets/css/custom.css`
- Widget-scoped: `widgets/{widget-name}/src/styles.css` (use Kadence CSS custom properties, no Tailwind)

**New data script:**
- Add Python file to `data/scripts/`
- Add any new dependencies to `data/scripts/requirements.txt`

## Special Directories

**`data/pharmacies/raw/`:**
- Purpose: Cache for Google Places API responses — avoids redundant API calls during development
- Generated: Yes, by data scripts
- Committed: Only the `.gitkeep` placeholder; actual API responses are gitignored

**`wordpress/plugin/24hr-pharmacy-tools/assets/js/`:**
- Purpose: Compiled React widget bundles loaded by WordPress
- Generated: Yes, by `npm run build` in each widget directory
- Committed: Only `.gitkeep`; compiled bundles are gitignored (deployed manually)

**`.claude/worktrees/`:**
- Purpose: Claude Code worktree for parallel development sessions
- Generated: Yes, by Claude Code tooling
- Committed: Yes (present in repo)

---

*Structure analysis: 2026-03-28*
