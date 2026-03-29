# Phase 2: City Page Template + Compliance Pages - Research

**Researched:** 2026-03-29
**Domain:** WordPress child theme PHP templates + compliance page content
**Confidence:** HIGH

## Summary

Phase 1 produced all 4 PHP template files and the full CSS for custom.css. The templates are skeletal scaffolds: correct file names, correct PHP structure, get_header()/get_footer(), ad zone divs, and static disclaimer text — but they do NOT yet fulfill the Phase 2 success criteria in several ways. The primary work of Phase 2 is (1) upgrading these templates to fully satisfy the acceptance criteria and (2) writing the 5 compliance page text files.

**What the templates are missing vs. success criteria:**

- `single-city.php` — does not include `[discount_card]` shortcode, does not include `[medical_disclaimer]` or `[affiliate_disclosure]` shortcodes (has hardcoded text instead), lacks affiliate CTA card blocks (ThirstyAffiliates `/go/` links), and does not use the `do_shortcode()` pattern for compliance blocks. Layout is sidebar-style (calls `get_sidebar()`), which contradicts D-03 (full-width, no sidebar).
- `front-page.php` — loads `[pharmacy_finder]` widget in hero (correct per D-05), but lacks the "popular city cards", "How It Works", and "featured articles" sections from D-06. Also calls `get_sidebar()` which contradicts the mobile-first single column intent.
- `archive-state.php` — mostly correct per D-07, but calls `get_sidebar()` and missing pharmacy counts per city item (D-07 says "city list with pharmacy counts").
- `single-pharmacy.php` — mostly correct per D-08, but calls `get_sidebar()`.
- Compliance page text files — do not exist yet (D-09 requires them as files in repo for pasting into WP admin).

**Primary recommendation:** Revise all 4 template files to: (1) replace hardcoded disclaimer text with `do_shortcode('[medical_disclaimer]')` and `do_shortcode('[affiliate_disclosure]')`, (2) add missing sections, (3) remove `get_sidebar()` in favor of full-width single-column layout. Then write 5 compliance page content files under `docs/compliance/`.

---

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions

**City page layout (single-city.php)**
- D-01: Pharmacy finder widget is the hero — H1 + `[pharmacy_finder]` shortcode above the fold. Tool-first, content second.
- D-02: Section order below the fold: 500+ word content block → `[discount_card]` widget placeholder → affiliate CTA cards → medical disclaimer + affiliate disclosure at bottom.
- D-03: Full-width single column layout, no sidebar. Mobile-first (70%+ mobile traffic).
- D-04: Affiliate CTAs appear as styled card blocks (icon + short copy + button linking to ThirstyAffiliates `/go/` URLs). 2-3 cards stacked vertically in a single section after the content block. One CTA section only — not repeated.

**Homepage (front-page.php)**
- D-05: Search-first hero: big H1 ("Find a 24-Hour Pharmacy Near You"), simple city name input with autocomplete that redirects to `/city/{slug}/`. No API calls on homepage — real finder widget loads on city pages only.
- D-06: Below the fold: popular city cards (links to city pages) → 3-step "How It Works" section → featured articles. Good for SEO internal linking + trust.

**State listing (archive-state.php)**
- D-07: Simple alphabetical city list with pharmacy counts. State name as H1. Clean, fast, good for SEO internal linking. No map (maps come in Phase 3).

**Pharmacy detail (single-pharmacy.php)**
- D-08: Basic info card layout: pharmacy name, address, hours, phone, map placeholder, then links to nearby city pages. Minimal — real pharmacy data populates in Phase 4.

**Compliance pages**
- D-09: Created as regular WordPress pages (content in database, editable via WP admin). This phase produces the text content as files in the repo; content gets pasted into WP admin on deploy.
- D-10: Detailed and specific content, 500-800 words each. Privacy policy covers GA4, Microsoft Clarity, CookieYes, affiliate cookies specifically. Terms covers YMYL disclaimers. Not boilerplate — site-specific language.
- D-11: About page is brand-only (24HourPharmacy.com as a resource). No personal name. Easier to scale or bring on contributors later.

### Claude's Discretion
- Ad zone placement on city pages (header, in-content, footer positioning optimized for Mediavine best practices)
- Exact heading hierarchy (H2/H3 structure within content sections)
- City autocomplete implementation approach on homepage (could be a simple dropdown from cities.json data or a text input with JS filtering)
- Pharmacy detail page section ordering
- CSS styling for affiliate CTA cards (using existing Kadence custom properties)

### Deferred Ideas (OUT OF SCOPE)
None — discussion stayed within phase scope.
</user_constraints>

---

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|------------------|
| INFRA-07 | All required compliance pages live: /disclaimer/, /affiliate-disclosure/, /privacy-policy/, /terms/, /about/ | Content files written as repo artifacts under docs/compliance/; pasted into WP admin on deploy |
| INFRA-08 | Medical disclaimer shortcode outputs on every city, pharmacy, and savings page | `[medical_disclaimer]` shortcode exists in class-shortcodes.php; templates must call `do_shortcode('[medical_disclaimer]')` rather than hardcoded text; `twentyfourhour_append_disclaimer` filter also fires on city/pharmacy/page CPTs |
| INFRA-09 | Affiliate disclosure shortcode outputs on every page with affiliate links | `[affiliate_disclosure]` shortcode exists; templates must call `do_shortcode('[affiliate_disclosure]')` rather than hardcoded `.ftc-disclosure` div |
| CONTENT-01 | City page template renders: pharmacy finder widget, discount card widget, 500+ word unique content, schema, disclaimer, disclosure, affiliate CTAs | single-city.php needs: discount_card shortcode added, compliance shortcodes replacing hardcoded text, affiliate CTA card block, sidebar removed, D-01/D-02 section order enforced |
</phase_requirements>

---

## Standard Stack

### Core
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| WordPress | 6.x | CMS platform | Project decision — on Hostinger |
| Kadence | Free tier | Parent theme | Project decision — child theme already set up |
| PHP | 7.4+ | Server-side templating | WordPress requirement |
| ThirstyAffiliates | Free tier | Affiliate link management | CLAUDE.md rule 9 — all affiliate links via cloaked `/go/` URLs |

### Supporting
| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| wp_localize_script | WP built-in | Pass PHP config to JS | Widget config already wired in functions.php |
| do_shortcode() | WP built-in | Render compliance blocks | Call inline in template PHP |
| get_post_meta() | WP built-in | Read city/pharmacy CPT custom fields | Already used in existing templates |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| `do_shortcode('[medical_disclaimer]')` | Hardcoded HTML | Shortcode is the correct pattern per class-shortcodes.php — changes propagate from one place |
| `docs/compliance/` text files | Directly editing WP DB | Files-in-repo approach per D-09 — auditable, version-controlled |

**No installation required** — all dependencies are already present from Phase 1.

---

## Architecture Patterns

### Recommended Project Structure (additions only)
```
docs/
  compliance/
    disclaimer.md          # /disclaimer/ page content
    affiliate-disclosure.md # /affiliate-disclosure/ page content
    privacy-policy.md      # /privacy-policy/ page content
    terms.md               # /terms/ page content
    about.md               # /about/ page content
wordpress/
  theme/
    single-city.php        # REVISED — full spec
    front-page.php         # REVISED — full spec
    single-pharmacy.php    # REVISED — full spec
    archive-state.php      # REVISED — full spec
    assets/
      css/
        custom.css         # ADD affiliate-cta-card styles
```

### Pattern 1: Compliance Shortcodes in Templates
**What:** Replace all hardcoded disclaimer/disclosure HTML in templates with shortcode calls.
**When to use:** Every template for city, pharmacy, savings, and pages with affiliate links.
**Example:**
```php
// Source: class-shortcodes.php (Phase 1 output)
// CORRECT — uses plugin shortcode, single source of truth:
<?php echo do_shortcode( '[medical_disclaimer]' ); ?>
<?php echo do_shortcode( '[affiliate_disclosure]' ); ?>

// WRONG — hardcoded (existing state in Phase 1 templates):
<div class="medical-disclaimer" role="note">
    <p><strong>Medical Disclaimer:</strong> ...</p>
</div>
```

### Pattern 2: Full-Width Layout (No Sidebar)
**What:** Remove `get_sidebar()` calls and `.ad-zone-sidebar` aside from city and homepage templates. Kadence handles layout via theme settings; removing get_sidebar() from a child template is sufficient.
**When to use:** single-city.php, front-page.php per D-03.
**Example:**
```php
// Remove these two blocks from single-city.php and front-page.php:
// <?php get_sidebar(); ?>
// <aside class="ad-zone-sidebar" ...></aside>
```

### Pattern 3: Affiliate CTA Card Block
**What:** A styled `<section class="affiliate-cta-section">` with 2-3 `.affiliate-cta-card` divs. Each card has an icon, short copy, and a `<a href="<?php echo esc_url(get_option('twentyfourhour_[program]_go_url', '#')); ?>">` button pointing to a ThirstyAffiliates `/go/` slug.
**When to use:** single-city.php only — after content block, before compliance shortcodes.
**Example:**
```php
<section class="affiliate-cta-section" aria-label="<?php esc_attr_e( 'Save on Prescriptions', '24hourpharmacy' ); ?>">
    <h2><?php esc_html_e( 'Save on Your Prescription Today', '24hourpharmacy' ); ?></h2>
    <div class="affiliate-cta-cards">
        <div class="affiliate-cta-card">
            <p class="affiliate-cta-card__name"><?php esc_html_e( 'GoodRx', '24hourpharmacy' ); ?></p>
            <p class="affiliate-cta-card__copy"><?php esc_html_e( 'Free card. Accepted at 70,000+ pharmacies.', '24hourpharmacy' ); ?></p>
            <a href="/go/goodrx/" class="affiliate-cta-card__btn" rel="sponsored noopener" target="_blank">
                <?php esc_html_e( 'Get Free Card', '24hourpharmacy' ); ?>
            </a>
        </div>
        <!-- repeat for SingleCare, RxSaver or Amazon Pharmacy -->
    </div>
</section>
```
Note: ThirstyAffiliates `/go/` slugs are set up via WP admin; hardcode the slug paths in templates (they are not API keys). The actual destination URLs are managed in ThirstyAffiliates, so the template can safely reference `/go/goodrx/` etc.

### Pattern 4: Homepage City Autocomplete (Claude's Discretion — Recommended)
**What:** A text `<input>` with a `<datalist>` populated from `data/cities.json` baked into inline JS. No API calls, no async loading. User types a city name, selects from dropdown, form submits to `/city/{slug}/`.
**When to use:** front-page.php hero section.
**Example:**
```php
// PHP bakes city list into a datalist; JS handles slug generation
<form class="city-search-form" action="" method="get" id="city-search">
    <label for="city-input" class="screen-reader-text">
        <?php esc_html_e( 'Enter city name', '24hourpharmacy' ); ?>
    </label>
    <input type="text" id="city-input" list="city-datalist"
           placeholder="<?php esc_attr_e( 'Enter a city or ZIP code...', '24hourpharmacy' ); ?>"
           autocomplete="off" aria-label="<?php esc_attr_e( 'City name', '24hourpharmacy' ); ?>">
    <datalist id="city-datalist">
        <?php
        // City data is small enough to inline from cities.json via PHP
        $cities_json = file_get_contents( get_template_directory() . '/../../data/cities.json' );
        $cities = json_decode( $cities_json, true );
        foreach ( $cities as $city ) {
            echo '<option value="' . esc_attr( $city['name'] . ', ' . $city['state'] ) . '">';
        }
        ?>
    </datalist>
    <button type="submit"><?php esc_html_e( 'Find Pharmacies', '24hourpharmacy' ); ?></button>
</form>
<script>
document.getElementById('city-search').addEventListener('submit', function(e) {
    e.preventDefault();
    var val = document.getElementById('city-input').value.trim().toLowerCase();
    var slug = val.split(',')[0].trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    if (slug) { window.location.href = '/city/' + slug + '/'; }
});
</script>
```
**Caveat:** The `file_get_contents` path above assumes relative path from theme — confirm cities.json location relative to theme at deploy time. Alternative: wp_localize a small JSON array from functions.php. The datalist approach requires no JS bundle and has zero CLS impact.

### Pattern 5: Section Order for single-city.php (per D-01/D-02)
```
1. .ad-zone-header
2. [affiliate_disclosure] shortcode  ← moved above fold per FTC requirements
3. <h1> "24-Hour Pharmacies in {City}, {State}"
4. <h2> "Find a Pharmacy Open Now" + [pharmacy_finder] shortcode
5. .ad-zone-in-content
6. <section class="city-content-section"> — 500+ word content block (the_content())
7. <h2> "Save on Prescriptions" + [discount_card] shortcode
8. <section class="affiliate-cta-section"> — 2-3 affiliate CTA cards
9. [medical_disclaimer] shortcode
10. .ad-zone-footer
```

### Anti-Patterns to Avoid
- **Hardcoded disclaimer/disclosure HTML in templates:** If the compliance text ever changes, every template must be updated. Use `do_shortcode()` exclusively.
- **Calling get_sidebar() on city/homepage templates:** Single-column layout is required (D-03). get_sidebar() will pull in a WP sidebar widget area that may not be empty in all Kadence configurations.
- **Hardcoding ThirstyAffiliates destination URLs:** Only reference cloaked `/go/` paths. Never put raw affiliate URLs in PHP templates.
- **Duplicate disclaimer output:** The `twentyfourhour_append_disclaimer()` filter in functions.php already auto-appends to `the_content()` on city/pharmacy/page types. Templates should NOT also output a manual disclaimer block — this would double-fire. Use one or the other. Recommendation: keep the filter for safety net, use shortcode calls for the explicit positioned blocks, and suppress the filter duplicate by checking if template has already rendered the shortcode. Simplest approach: rely on the explicit `do_shortcode()` calls in templates and update `twentyfourhour_append_disclaimer()` to skip when a `[medical_disclaimer]` shortcode is already in the content.

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Affiliate link management | Raw `<a href="https://track.affiliate.com/...">` in templates | ThirstyAffiliates `/go/` cloaked URLs | CLAUDE.md rule 9 — compliance, trackability, centralized management |
| Cookie consent banner | Custom JS consent manager | CookieYes (already installed) | CLAUDE.md rule 10 — handles GDPR/CCPA; GA4 must load conditionally via it |
| PHP template routing | Custom CPT→template mapping | WordPress template hierarchy | `single-{post-type}.php` and `archive-{taxonomy}.php` are native WP patterns |
| City slug generation | Custom slug API | WordPress `sanitize_title()` | Built-in, handles edge cases (accents, punctuation) |

---

## Common Pitfalls

### Pitfall 1: Double Disclaimer Output
**What goes wrong:** `the_content()` triggers `twentyfourhour_append_disclaimer()` (filter in functions.php), and the template also manually calls `do_shortcode('[medical_disclaimer]')` — producing two disclaimer blocks.
**Why it happens:** Phase 1 set up the filter as a safety net; Phase 2 adds explicit shortcode calls.
**How to avoid:** Update `twentyfourhour_append_disclaimer()` to bail early if the post content already contains `[medical_disclaimer]` shortcode: `if ( has_shortcode( $content, 'medical_disclaimer' ) ) return $content;`. Or remove the filter and rely solely on template shortcode calls.
**Warning signs:** Two disclaimer blocks rendered back-to-back in the page source.

### Pitfall 2: Sidebar Rendering Despite Removed get_sidebar() Call
**What goes wrong:** Kadence theme may inject sidebar content via `kadence_after_content` or `kadence_sidebar` hooks regardless of whether the template calls `get_sidebar()`.
**Why it happens:** Kadence has its own layout system; some configurations force sidebar injection via hooks.
**How to avoid:** In addition to removing `get_sidebar()`, add `add_filter('kadence_display_sidebar', '__return_false')` conditionally in `single-city.php` and `front-page.php`.
**Warning signs:** A blank sidebar column appears on city pages with no content in it.

### Pitfall 3: cities.json Path Resolution in Template
**What goes wrong:** `file_get_contents( get_template_directory() . '/../../data/cities.json' )` fails silently, datalist is empty.
**Why it happens:** Path is relative to theme directory, which is 2 levels up from data/ depending on WP installation structure.
**How to avoid:** Use `ABSPATH` reference or define a constant in functions.php pointing to the data directory: `define('TWENTYFOURHOUR_DATA_DIR', dirname(get_stylesheet_directory(), 3) . '/data/');`. Alternative: wp_localize a pre-built city array from functions.php instead of reading file in template.
**Warning signs:** Datalist renders empty in homepage HTML source.

### Pitfall 4: Compliance Page Slugs Not Matching /disclaimer/ etc.
**What goes wrong:** WP page slug auto-generates as "medical-disclaimer" or "affiliate-disclosure-2" rather than the exact slugs required.
**Why it happens:** WP may sanitize titles differently, or a draft/trashed page with the slug already exists.
**How to avoid:** After pasting content into WP admin, manually verify the permalink slug matches exactly: `/disclaimer/`, `/affiliate-disclosure/`, `/privacy-policy/`, `/terms/`, `/about/`.
**Warning signs:** Compliance page URLs return 404 or redirect to different slugs.

### Pitfall 5: Affiliate CTA Buttons Hardcoding Destination URLs
**What goes wrong:** Developer puts raw affiliate URL directly in template `href` instead of ThirstyAffiliates path.
**Why it happens:** ThirstyAffiliates may not be configured yet at time of writing template.
**How to avoid:** Always use `/go/{slug}/` paths. If the TA link doesn't exist yet, use `#` as placeholder. Log a task to register the TA links before phase verification.
**Warning signs:** Template has `href="https://www.goodrx.com/?utm_source=..."` instead of `href="/go/goodrx/"`.

---

## Code Examples

### Correct do_shortcode Pattern
```php
// Source: wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php
// Renders <div class="pharmacy-disclaimer" role="note">...</div>
<?php echo do_shortcode( '[medical_disclaimer]' ); ?>

// Renders <div class="pharmacy-disclosure" role="note">...</div>
<?php echo do_shortcode( '[affiliate_disclosure]' ); ?>

// Renders <div id="discount-card-root" class="pharmacy-widget discount-card-widget">
<?php echo do_shortcode( '[discount_card]' ); ?>
```

### Kadence Layout Override
```php
// Source: Kadence theme docs — filter to suppress sidebar on specific templates
// Add near top of single-city.php, inside the template file (not functions.php)
add_filter( 'kadence_display_sidebar', '__return_false' );
```

### Suppress Duplicate Disclaimer in Filter
```php
// Source: wordpress/theme/functions.php — update the existing filter
add_filter( 'the_content', 'twentyfourhour_append_disclaimer' );
function twentyfourhour_append_disclaimer( $content ) {
    if ( ! is_singular( array( 'city', 'pharmacy' ) ) && ! is_page() ) {
        return $content;
    }
    // Bail if template already rendered an explicit disclaimer block.
    if ( has_shortcode( $content, 'medical_disclaimer' ) ) {
        return $content;
    }
    // ... rest of function unchanged
```

### New CSS Classes Needed (affiliate-cta-card)
```css
/* Add to wordpress/theme/assets/css/custom.css */
/* Uses Kadence palette vars — no hex values */

.affiliate-cta-section {
    margin-block: 2rem;
}

.affiliate-cta-cards {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 640px) {
    .affiliate-cta-cards {
        flex-direction: row;
        flex-wrap: wrap;
    }
    .affiliate-cta-card {
        flex: 1 1 calc(50% - 0.5rem);
    }
}

.affiliate-cta-card {
    background-color: var(--global-palette7);
    border: 1px solid var(--global-palette6);
    border-radius: 8px;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.affiliate-cta-card__name {
    font-weight: 700;
    font-size: 1.0625rem;
    color: var(--global-palette1);
    margin: 0;
}

.affiliate-cta-card__copy {
    font-size: 0.9375rem;
    color: var(--global-palette5);
    margin: 0;
    flex-grow: 1;
}

.affiliate-cta-card__btn {
    display: inline-block;
    background-color: var(--global-palette3);
    color: var(--global-palette8);
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9375rem;
    font-weight: 600;
    text-align: center;
    margin-block-start: 0.25rem;
    transition: opacity 0.15s ease;
}

.affiliate-cta-card__btn:hover,
.affiliate-cta-card__btn:focus-visible {
    opacity: 0.88;
    text-decoration: none;
}
```

---

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| Hardcoded disclaimer HTML in templates | `do_shortcode('[medical_disclaimer]')` | Phase 2 | Single source of truth; consistent output |
| get_sidebar() in all templates | Removed from city/homepage; Kadence sidebar filter | Phase 2 | Enforces D-03 full-width layout |

---

## Open Questions

1. **cities.json path resolution from template**
   - What we know: `data/cities.json` exists at project root level, child theme lives at `wordpress/theme/`
   - What's unclear: Relative path from `get_template_directory()` depends on how WP is installed on Hostinger — whether the repo root maps to the WP root or a subdirectory
   - Recommendation: Use wp_localize_script approach in functions.php to pass city list as JSON to homepage template, avoiding file_get_contents path ambiguity entirely

2. **ThirstyAffiliates link slugs for affiliate CTAs**
   - What we know: D-04 says CTAs link to ThirstyAffiliates `/go/` URLs; TA not yet configured
   - What's unclear: Exact slugs (e.g., `/go/goodrx/` vs `/go/good-rx/`)
   - Recommendation: Use `#` placeholder `href` values in templates with a `<!-- TODO: register in ThirstyAffiliates -->` comment; set up TA links in WP admin as part of Phase 2 task verification

3. **archive-state.php pharmacy counts**
   - What we know: D-07 requires "city list with pharmacy counts"
   - What's unclear: Pharmacy counts per city are not stored in a city CPT custom field in Phase 1; counting live pharmacy CPT posts per city would require a WP_Query per city item (N+1)
   - Recommendation: Store pharmacy count as `_city_pharmacy_count` post meta on city CPT (set to 0 initially, populated by Phase 4 data scripts); display `get_post_meta(get_the_ID(), '_city_pharmacy_count', true)` in archive template. If 0, show nothing (hidden until Phase 4).

---

## Environment Availability

Step 2.6: SKIPPED — Phase 2 is PHP template + content file changes only. No external CLI tools, services, or runtimes beyond WordPress/PHP which are on Hostinger (deployment target). Local environment requires only a text editor and git.

---

## Validation Architecture

### Test Framework
| Property | Value |
|----------|-------|
| Framework | Manual (no PHP unit test framework configured for WP child theme) |
| Config file | none |
| Quick run command | Deploy to Hostinger, visit test URLs |
| Full suite command | See acceptance criteria checklist below |

### Phase Requirements → Test Map
| Req ID | Behavior | Test Type | Automated Command | File Exists? |
|--------|----------|-----------|-------------------|-------------|
| INFRA-07 | 5 compliance pages return 200 | smoke | `curl -o /dev/null -s -w "%{http_code}" https://24hourpharmacy.com/disclaimer/` | ❌ Wave 0 — content pasted into WP admin |
| INFRA-08 | Medical disclaimer shortcode fires on city/pharmacy/savings pages | manual | Visit city page, inspect source for `class="pharmacy-disclaimer"` | ❌ Wave 0 |
| INFRA-09 | Affiliate disclosure shortcode fires on pages with affiliate links | manual | Visit city page, inspect source for `class="pharmacy-disclosure"` | ❌ Wave 0 |
| CONTENT-01 | City page renders H1, 3+ H2s, disclaimer, disclosure, 2+ affiliate CTAs, ad zones | manual | Visit /city/new-york/, view source | ❌ Wave 0 |

### Sampling Rate
- **Per task commit:** Visual check of changed template in browser
- **Per wave merge:** Full acceptance criteria checklist against live Hostinger deploy
- **Phase gate:** All 4 success criteria pass before `/gsd:verify-work`

### Wave 0 Gaps
- [ ] Compliance page content files (`docs/compliance/*.md`) — cover INFRA-07
- [ ] ThirstyAffiliates `/go/` links registered in WP admin — required before INFRA-09 verification
- [ ] Test city post must exist in WP — required to render single-city.php for visual verification

*(No automated test framework applicable — this is a WordPress PHP template phase. All validation is deploy-and-inspect.)*

---

## Project Constraints (from CLAUDE.md)

| Directive | Impact on Phase 2 |
|-----------|-------------------|
| No hardcoded API keys or affiliate tracking codes | Affiliate CTA buttons must use ThirstyAffiliates `/go/` URLs, not raw affiliate URLs |
| Every city page must have 500+ words of unique content | Placeholder content block in single-city.php must satisfy this at render time; confirmed existing placeholder is sufficient |
| Mobile-first. Test at 375px minimum | Affiliate CTA cards CSS uses flex-direction: column mobile-first, expands at 640px |
| Core Web Vitals: LCP < 2.5s, CLS < 0.1, INP < 200ms | Ad zone CSS already has CLS-safe min-heights; widget divs have min-height set in shortcode attributes |
| Medical disclaimer required on every page (YMYL) | `do_shortcode('[medical_disclaimer]')` in all 4 templates |
| FTC affiliate disclosure required on every page with affiliate links | `do_shortcode('[affiliate_disclosure]')` in single-city.php and front-page.php |
| Ad placement zones must use .ad-zone-header, .ad-zone-sidebar, .ad-zone-in-content, .ad-zone-footer | All 4 classes present in Phase 1 templates; sidebar zones remain in pharmacy/state templates, removed from city/homepage |
| No health claims. No medical advice. Information and referrals only | Compliance page content (D-10) must be informational, include YMYL disclaimers, no treatment recommendations |
| Affiliate links managed through ThirstyAffiliates | Templates reference `/go/` slugs only |
| CookieYes manages consent banner — no custom code needed | No consent code in templates; GA4/Clarity loaded via CookieYes, not inline in templates |
| Do NOT use Tailwind | CSS in custom.css only, Kadence CSS vars, no Tailwind classes |
| PHP: WordPress Coding Standards | Templates follow WPCS: spacing, escaping (esc_html_e, esc_attr_e, esc_url), no direct DB queries |
| React/JS: No class components, CSS custom properties from Kadence | Applicable to city-autocomplete JS on front-page.php — keep it vanilla JS, not React |

---

## Sources

### Primary (HIGH confidence)
- `wordpress/plugin/24hr-pharmacy-tools/includes/class-shortcodes.php` — shortcode signatures, rendered HTML class names
- `wordpress/theme/functions.php` — disclaimer filter, wp_localize_script pattern, CPT registration
- `wordpress/theme/assets/css/custom.css` — existing CSS classes and Kadence palette var names
- `wordpress/theme/single-city.php` (Phase 1) — current template gaps identified
- `wordpress/theme/front-page.php` (Phase 1) — current template gaps identified
- `CLAUDE.md` — coding standards, key rules, file structure

### Secondary (MEDIUM confidence)
- Kadence theme documentation patterns (sidebar filter) — standard Kadence child theme practice; verify against installed Kadence version at deploy time

### Tertiary (LOW confidence)
- cities.json path resolution in template — path will depend on Hostinger WP installation structure; verify at deploy time

---

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH — all dependencies from Phase 1, no new installs
- Architecture: HIGH — patterns derived directly from existing codebase
- Pitfalls: HIGH — derived from code analysis of Phase 1 templates vs. Phase 2 spec
- Compliance content: HIGH — YMYL/FTC requirements well-understood, D-10 is explicit

**Research date:** 2026-03-29
**Valid until:** 2026-06-29 (stable domain — WordPress template patterns don't change)
