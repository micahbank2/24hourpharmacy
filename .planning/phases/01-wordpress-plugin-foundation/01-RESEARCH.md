# Phase 1: WordPress Plugin Foundation - Research

**Researched:** 2026-03-28
**Domain:** WordPress plugin development — custom post types, settings API, JSON-LD schema, shortcodes, wp_localize_script
**Confidence:** HIGH

---

## Summary

Phase 1 converts six stub PHP files into a fully functional WordPress plugin. All patterns needed are well-established WordPress core APIs that have been stable across WP 5.x and 6.x. No third-party libraries are required or permitted — everything is native WP functions.

The main risk areas are: (1) CPT rewrite rule flushing, which must happen on activation only and cannot be called on every page load, and (2) `wp_localize_script` requiring the target script handle to already be registered before the localize call runs. Both are well-documented pitfalls with clear solutions.

The plugin scaffold already has correct ABSPATH guards and constants defined. The main file already `require_once`s all four class files. Phase 1 is pure implementation: fill in each class file and wire hooks in the main file.

**Primary recommendation:** Use WordPress Settings API with `register_setting()` / `add_settings_field()` for the admin page, `register_post_type()` with `has_archive => false` and hierarchical slug rewrites for CPTs, `wp_head` action for JSON-LD output, and `add_shortcode()` for compliance blocks. All patterns are native WP, no Composer, no dependencies.

---

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|------------------|
| INFRA-01 | WordPress plugin registers City and Pharmacy CPTs with correct rewrite rules | `register_post_type()` with rewrite slug, flush on activation hook |
| INFRA-02 | Plugin settings page stores all API keys and affiliate codes in WP options table | WordPress Settings API: `register_setting()`, `add_options_page()`, `get_option()` / `update_option()` |
| INFRA-03 | Plugin enqueues React widget bundles conditionally via shortcodes with async/defer loading | `wp_enqueue_script()` called inside shortcode callback; `wp_localize_script()` for config passthrough |
| INFRA-04 | Plugin outputs valid JSON-LD structured data on all relevant pages | `wp_head` action; `json_encode()` with `JSON_UNESCAPED_UNICODE`; Pharmacy/LocalBusiness/WebPage/FAQPage types |
| INFRA-05 | Child theme enqueues styles and passes config to widgets via wp_localize_script | `wp_enqueue_scripts` action in functions.php; `wp_localize_script()` to `window.PharmacyToolsConfig` |
</phase_requirements>

---

## Project Constraints (from CLAUDE.md)

These directives override all research recommendations. The planner must verify every task complies.

- **PHP coding standard:** WordPress Coding Standards (WPCS). Tabs for indentation. ABSPATH guard on every include file. Docblock on every function.
- **No framework dependencies:** Native WP functions only. No Composer. No external libraries in PHP.
- **API keys / affiliate codes:** Never hardcoded. Always via `get_option()` / `update_option()`. Option prefix: `twentyfourhour_`.
- **Function prefix (plugin):** `pharmacy_tools_`. Constants prefix: `PHARMACY_TOOLS_`. Theme functions prefix: `twentyfourhour_`.
- **Output escaping:** `esc_attr()`, `esc_html()`, `esc_url()` on all PHP output. Never echo raw data.
- **Scripts in footer:** `true` as final arg to `wp_enqueue_script()`.
- **Cache-bust:** `filemtime()` when file exists; fall back to version constant.
- **Shortcode attributes:** Always processed with `shortcode_atts()` with defaults.
- **`noscript` fallback:** Required inside every shortcode output div.
- **Text domain:** `24hr-pharmacy-tools` for all i18n.
- **Config global:** `window.PharmacyToolsConfig` — widgets read via `window.PharmacyToolsConfig?.affiliate`.
- **No Tailwind in PHP/CSS.** GeneratePress built-in grid only.
- **Medical disclaimer required** on every page. YMYL content.
- **FTC affiliate disclosure required** on every page with affiliate links.
- **Ad zone classes must be built in:** `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer`.

---

## Standard Stack

### Core
| API / Function | WP Version | Purpose | Why Standard |
|----------------|-----------|---------|--------------|
| `register_post_type()` | 2.9+ | Register City and Pharmacy CPTs | Native WP; stable across all WP 6.x |
| `register_activation_hook()` | 2.0+ | Flush rewrite rules on plugin activation | Required to make CPT URLs resolve |
| WordPress Settings API | 2.7+ | Admin settings page with options storage | `register_setting()`, `add_options_page()`, `add_settings_section()`, `add_settings_field()` |
| `wp_head` action | 1.5+ | Output JSON-LD into `<head>` | Standard hook; runs in `<head>` on every page |
| `add_shortcode()` | 2.5+ | Register `[medical_disclaimer]` and `[affiliate_disclosure]` | Native shortcode system |
| `wp_enqueue_script()` | 2.1+ | Enqueue widget JS bundles | Standard asset loading |
| `wp_localize_script()` | 2.2+ | Pass PHP config to JS as `window.PharmacyToolsConfig` | Standard PHP-to-JS bridge |
| `get_option()` / `update_option()` | 1.0+ | Read/write settings from WP options table | Native options API |

### Supporting
| API / Function | Purpose | When to Use |
|----------------|---------|-------------|
| `flush_rewrite_rules()` | Flush permalink cache so new CPT slugs resolve | Called once on `register_activation_hook`, never on `init` |
| `is_singular()` / `get_post_type()` | Conditional checks for schema/enqueue targeting | Limit JSON-LD and widget enqueues to relevant post types |
| `sanitize_text_field()` / `esc_attr()` | Input sanitization on settings save | Required in Settings API `sanitize_callback` |
| `wp_json_encode()` | Encode PHP data for JSON-LD | Preferred over `json_encode()` — handles WP-specific edge cases |
| `add_action( 'init', ... )` | Register CPTs and shortcodes | `init` is the correct hook for both |
| `add_action( 'admin_menu', ... )` | Register settings page | Correct hook for admin page registration |
| `add_action( 'admin_init', ... )` | Register settings fields | Correct hook for `register_setting()` calls |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| WordPress Settings API | Custom options form with manual `$_POST` | Settings API provides nonces, sanitization callbacks, and settings errors automatically. Never bypass it. |
| `wp_head` for JSON-LD | `wp_footer` | `wp_head` is correct — JSON-LD in `<head>` is what Google expects for structured data |
| Conditional enqueue inside shortcode | Always-enqueue on CPT pages | Shortcode-triggered enqueue is more precise; prevents loading unused JS |

**Installation:** No installation required. All APIs are native WordPress core. Zero Composer dependencies.

---

## Architecture Patterns

### Plugin File Structure (existing scaffold — fill in each file)
```
wordpress/plugin/24hr-pharmacy-tools/
├── 24hr-pharmacy-tools.php     # Bootstrap: constants, require_once, register hooks
├── includes/
│   ├── class-post-types.php    # CPT registration, activation hook
│   ├── class-settings.php      # Admin page, Settings API
│   ├── class-schema.php        # JSON-LD output on wp_head
│   └── class-shortcodes.php    # [medical_disclaimer], [affiliate_disclosure], widget shortcodes
└── assets/
    └── js/                     # Compiled widget bundles (populated in Phase 3)
```

### Pattern 1: CPT Registration with Activation Flush

**What:** Register CPTs on `init`, flush rewrite rules on plugin activation only.

**When to use:** Every CPT registration. The activation hook is the only safe place to flush.

**Critical gotcha:** Never call `flush_rewrite_rules()` on `init` — it runs on every page load and destroys performance. Only call it inside `register_activation_hook`.

```php
// Source: https://developer.wordpress.org/reference/functions/register_post_type/
// In class-post-types.php

class Pharmacy_Tools_Post_Types {

	public function register() {
		add_action( 'init', array( $this, 'register_city_cpt' ) );
		add_action( 'init', array( $this, 'register_pharmacy_cpt' ) );
	}

	public function register_city_cpt() {
		$labels = array(
			'name'          => __( 'Cities', '24hr-pharmacy-tools' ),
			'singular_name' => __( 'City', '24hr-pharmacy-tools' ),
		);
		register_post_type(
			'city',
			array(
				'labels'      => $labels,
				'public'      => true,
				'has_archive' => false,
				'rewrite'     => array( 'slug' => 'city', 'with_front' => false ),
				'supports'    => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
				'show_in_rest' => true,
			)
		);
	}

	public function register_pharmacy_cpt() {
		$labels = array(
			'name'          => __( 'Pharmacies', '24hr-pharmacy-tools' ),
			'singular_name' => __( 'Pharmacy', '24hr-pharmacy-tools' ),
		);
		register_post_type(
			'pharmacy',
			array(
				'labels'      => $labels,
				'public'      => true,
				'has_archive' => false,
				'rewrite'     => array( 'slug' => 'pharmacy', 'with_front' => false ),
				'supports'    => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
				'show_in_rest' => true,
			)
		);
	}

	public static function activation() {
		// Must call register first so rules exist to flush.
		$instance = new self();
		$instance->register_city_cpt();
		$instance->register_pharmacy_cpt();
		flush_rewrite_rules();
	}
}
```

In main plugin file:
```php
register_activation_hook( __FILE__, array( 'Pharmacy_Tools_Post_Types', 'activation' ) );
$post_types = new Pharmacy_Tools_Post_Types();
$post_types->register();
```

### Pattern 2: WordPress Settings API Admin Page

**What:** Register an options page under WP Settings menu with fields for API keys and affiliate codes.

**When to use:** All plugin settings that must be stored in `wp_options`.

**Critical gotcha:** `wp_localize_script` requires the script handle to already be registered. Always register/enqueue the script before calling `wp_localize_script`.

```php
// Source: https://developer.wordpress.org/plugins/settings/settings-api/
// In class-settings.php

class Pharmacy_Tools_Settings {

	const OPTION_GROUP = 'twentyfourhour_settings';
	const PAGE_SLUG    = 'twentyfourhour-settings';

	public function register() {
		add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_admin_page() {
		add_options_page(
			__( '24Hr Pharmacy Tools', '24hr-pharmacy-tools' ),
			__( '24Hr Pharmacy Tools', '24hr-pharmacy-tools' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	public function register_settings() {
		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_google_maps_key',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_lowermyrx_bin',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		// Add one register_setting() call per option key.
		// Sections and fields via add_settings_section() / add_settings_field().
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( '24Hr Pharmacy Tools Settings', '24hr-pharmacy-tools' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
```

**Required options keys to register** (prefix: `twentyfourhour_`):
- `twentyfourhour_google_maps_key`
- `twentyfourhour_lowermyrx_bin`
- `twentyfourhour_lowermyrx_pcn`
- `twentyfourhour_lowermyrx_group`
- `twentyfourhour_amazon_pharmacy_affiliate_id`
- `twentyfourhour_singleware_affiliate_id` (and others per affiliate program roster)

### Pattern 3: JSON-LD Schema Output via wp_head

**What:** Output `<script type="application/ld+json">` in `<head>` conditional on post type.

**When to use:** Every page that has a CPT (city or pharmacy). Also WebPage schema on all pages.

```php
// Source: https://developer.wordpress.org/reference/hooks/wp_head/
// In class-schema.php

class Pharmacy_Tools_Schema {

	public function register() {
		add_action( 'wp_head', array( $this, 'output_schema' ) );
	}

	public function output_schema() {
		if ( is_singular( 'city' ) ) {
			$this->output_city_schema();
		} elseif ( is_singular( 'pharmacy' ) ) {
			$this->output_pharmacy_schema();
		}
		// WebPage schema can fire on all pages.
		$this->output_webpage_schema();
	}

	private function output_city_schema() {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebPage',
			'name'     => get_the_title(),
			'url'      => get_permalink(),
		);
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		);
	}

	private function output_pharmacy_schema() {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => array( 'Pharmacy', 'LocalBusiness' ),
			'name'     => get_the_title(),
			'url'      => get_permalink(),
		);
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		);
	}
}
```

**Schema types required per INFRA-04:**
- `Pharmacy` + `LocalBusiness` — on pharmacy CPT pages
- `WebPage` — on all pages (city + pharmacy + content)
- `FAQPage` — prepared now, populated in Phase 7 (stub `@type` is fine for Phase 1 validation)

### Pattern 4: Compliance Shortcodes

**What:** Register `[medical_disclaimer]` and `[affiliate_disclosure]` shortcodes returning escaped HTML strings.

**When to use:** Both shortcodes must be available for use in any WP page/post.

```php
// Source: https://developer.wordpress.org/plugins/shortcodes/
// In class-shortcodes.php

class Pharmacy_Tools_Shortcodes {

	public function register() {
		add_shortcode( 'medical_disclaimer', array( $this, 'medical_disclaimer' ) );
		add_shortcode( 'affiliate_disclosure', array( $this, 'affiliate_disclosure' ) );
	}

	public function medical_disclaimer( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'medical_disclaimer' );
		return sprintf(
			'<div class="pharmacy-disclaimer" role="note"><p>%s</p></div>',
			esc_html__(
				'This website provides general information and referrals only. It is not a substitute for professional medical advice, diagnosis, or treatment. Always seek guidance from a qualified healthcare provider.',
				'24hr-pharmacy-tools'
			)
		);
	}

	public function affiliate_disclosure( $atts ) {
		$atts = shortcode_atts( array(), $atts, 'affiliate_disclosure' );
		return sprintf(
			'<div class="pharmacy-disclosure" role="note"><p>%s</p></div>',
			esc_html__(
				'This page contains affiliate links. We may earn a commission when you make a purchase through these links, at no additional cost to you.',
				'24hr-pharmacy-tools'
			)
		);
	}
}
```

### Pattern 5: wp_localize_script for Config Passthrough

**What:** Pass PHP-read options to JS as `window.PharmacyToolsConfig`.

**When to use:** In `functions.php` (theme) for all widgets. Must run after the script is registered.

```php
// Source: https://developer.wordpress.org/reference/functions/wp_localize_script/
// In wordpress/theme/functions.php

add_action( 'wp_enqueue_scripts', 'twentyfourhour_localize_config' );
function twentyfourhour_localize_config() {
	// Only localize if a widget script is registered for this page.
	// Scripts are conditionally registered by shortcode — localize fires after enqueue.
	wp_localize_script(
		'pharmacy-finder',   // handle — must match wp_enqueue_script() handle
		'PharmacyToolsConfig',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'affiliate' => array(
				'lowermyrx_bin'   => get_option( 'twentyfourhour_lowermyrx_bin', '' ),
				'lowermyrx_pcn'   => get_option( 'twentyfourhour_lowermyrx_pcn', '' ),
				'lowermyrx_group' => get_option( 'twentyfourhour_lowermyrx_group', '' ),
				'amazon_id'       => get_option( 'twentyfourhour_amazon_pharmacy_affiliate_id', '' ),
			),
			'maps' => array(
				'api_key' => get_option( 'twentyfourhour_google_maps_key', '' ),
			),
		)
	);
}
```

**Critical note:** `wp_localize_script()` only fires if the named script handle is already registered. For conditional enqueue (shortcode-triggered), the `wp_enqueue_scripts` hook runs before shortcodes are processed. The standard workaround: register (not enqueue) scripts on `wp_enqueue_scripts`, then call `wp_enqueue_script()` inside the shortcode callback. `wp_localize_script()` can be called any time after registration and before output.

### Pattern 6: Conditional Widget Enqueue via Shortcode

**What:** Enqueue widget bundle only when its shortcode appears on the current page.

**Correct approach:** Register scripts globally on `wp_enqueue_scripts`, then call `wp_enqueue_script()` inside the shortcode callback. WordPress deduplicates enqueues.

```php
// In class-shortcodes.php

// Called on wp_enqueue_scripts to register (not enqueue yet):
public function register_scripts() {
	$js_file = PHARMACY_TOOLS_PATH . 'assets/js/pharmacy-finder.js';
	wp_register_script(
		'pharmacy-finder',
		PHARMACY_TOOLS_URL . 'assets/js/pharmacy-finder.js',
		array(),
		file_exists( $js_file ) ? filemtime( $js_file ) : PHARMACY_TOOLS_VERSION,
		true   // footer
	);
}

// Shortcode callback — enqueues only when shortcode is on the page:
public function pharmacy_finder_shortcode( $atts ) {
	wp_enqueue_script( 'pharmacy-finder' );
	$atts = shortcode_atts( array( 'height' => '400px' ), $atts, 'pharmacy_finder' );
	return sprintf(
		'<div id="pharmacy-finder-root" class="pharmacy-widget pharmacy-finder-widget" style="min-height:%s;">' .
		'<noscript><p>%s</p></noscript>' .
		'</div>',
		esc_attr( $atts['height'] ),
		esc_html__( 'Please enable JavaScript to use the pharmacy finder.', '24hr-pharmacy-tools' )
	);
}
```

### Pattern 7: Plugin Bootstrap — Main File Structure

The main file currently `require_once`s all four class files but does not instantiate or register anything. Phase 1 must add instantiation and hook wiring:

```php
// In 24hr-pharmacy-tools.php (after require_once lines)

// Instantiate and register.
$pharmacy_post_types = new Pharmacy_Tools_Post_Types();
$pharmacy_post_types->register();

$pharmacy_settings = new Pharmacy_Tools_Settings();
$pharmacy_settings->register();

$pharmacy_schema = new Pharmacy_Tools_Schema();
$pharmacy_schema->register();

$pharmacy_shortcodes = new Pharmacy_Tools_Shortcodes();
$pharmacy_shortcodes->register();

register_activation_hook( __FILE__, array( 'Pharmacy_Tools_Post_Types', 'activation' ) );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
```

### Anti-Patterns to Avoid

- **Calling `flush_rewrite_rules()` on `init`:** Runs on every request, severe performance impact. Only call on `register_activation_hook`.
- **Calling `wp_localize_script()` before `wp_enqueue_script()`/`wp_register_script()`:** The handle must be registered first or the localize call silently does nothing.
- **Hardcoding option keys without the `twentyfourhour_` prefix:** Options without the project prefix risk collision with other plugins.
- **Echoing in shortcode callbacks:** Shortcodes must `return` HTML strings, never `echo`. Echoing causes content to appear above the page layout.
- **Using `json_encode()` instead of `wp_json_encode()`:** `wp_json_encode()` handles `JSON_UNESCAPED_UNICODE` and WP-specific edge cases.
- **Registering CPTs with `'with_front' => true`:** Prepends the blog base to all CPT URLs (e.g., `/blog/city/new-york/`). Use `'with_front' => false`.
- **Outputting raw post meta in schema without sanitization:** Always pass post meta through `sanitize_text_field()` before including in JSON-LD output.

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Options storage | Custom DB table | `get_option()` / `update_option()` | WP options table handles serialization, caching, autoload — custom tables require manual schema migration |
| Settings form + nonces | Raw `$_POST` handling | WordPress Settings API | Settings API handles nonce generation, sanitization callbacks, and settings error notices automatically |
| Script deduplication | Manual "already enqueued" flags | `wp_register_script()` + `wp_enqueue_script()` | WordPress dependency system handles deduplication and load order |
| PHP-to-JS config | Inline `<script>` in template | `wp_localize_script()` | Localize runs after enqueue, handles escaping, and integrates with WP asset system |
| JSON-LD output ordering | Custom template system | `wp_json_encode()` + `printf()` | Handles encoding edge cases; Google accepts any key order in JSON-LD |

**Key insight:** WordPress core handles every infrastructure problem in this phase. The entire plugin is standard WordPress API usage — no custom infrastructure is needed.

---

## Common Pitfalls

### Pitfall 1: CPT URLs Return 404 After Registration

**What goes wrong:** City CPT URL (`/city/new-york/`) returns 404 even though the CPT is registered and a post exists.

**Why it happens:** WordPress caches rewrite rules in the `rewrite_rules` option. Adding a new CPT does not automatically invalidate this cache.

**How to avoid:** Call `flush_rewrite_rules()` inside `register_activation_hook()`. Never call it on `init`. If testing on a live site without deactivating/reactivating, go to Settings > Permalinks and click Save (which flushes rules manually).

**Warning signs:** The post exists in WP Admin and has a permalink shown, but visiting the URL returns 404.

### Pitfall 2: wp_localize_script Silently Does Nothing

**What goes wrong:** `window.PharmacyToolsConfig` is undefined in the browser despite calling `wp_localize_script()`.

**Why it happens:** `wp_localize_script()` was called before the script handle was registered, or the handle name does not exactly match the registered handle string.

**How to avoid:** Always call `wp_register_script()` or `wp_enqueue_script()` before `wp_localize_script()`. Handle strings must match exactly (case-sensitive).

**Warning signs:** `console.log(window.PharmacyToolsConfig)` returns `undefined`; no `var PharmacyToolsConfig` appears in page source before the script tag.

### Pitfall 3: Shortcode Returns Empty / Content Appears in Wrong Place

**What goes wrong:** Shortcode output appears above the post content, or the shortcode tag appears literally in the page instead of the rendered output.

**Why it happens (empty):** The shortcode callback `echo`es instead of `return`ing. WordPress captures shortcode return values; echoed output goes to the output buffer above the post.

**Why it happens (literal tag):** Shortcode is registered after `the_content` filter runs, or the shortcode name has a typo.

**How to avoid:** Always `return` in shortcode callbacks. Register shortcodes on the `init` hook. Verify the shortcode name string matches exactly between `add_shortcode()` and usage in content.

### Pitfall 4: JSON-LD Fails Google Rich Results Test

**What goes wrong:** Schema output exists in `<head>` but Google Rich Results Test shows errors or warnings.

**Why it happens:** Common causes: (1) `@type` value uses wrong capitalization (`pharmacy` instead of `Pharmacy`), (2) required properties missing (e.g., `name` is required for `LocalBusiness`), (3) JSON is invalid due to unescaped characters in PHP string concatenation.

**How to avoid:** Use `wp_json_encode()` — never manual string concatenation. Check schema.org for required vs. recommended properties per type. Test every schema change with Google Rich Results Test (`https://search.google.com/test/rich-results`).

### Pitfall 5: Settings Page Shows Fields But Doesn't Save

**What goes wrong:** Settings page renders correctly but clicking Save has no effect, or values reset to blank.

**Why it happens:** Option name in `register_setting()` does not exactly match the `name` attribute on the form field. Or `settings_fields()` was called with the wrong option group name.

**How to avoid:** Use the same string constant for the option group and page slug throughout. Verify the `name` attribute on each `<input>` matches the registered option key exactly.

### Pitfall 6: Schema Output on Every Page (Including Admin)

**What goes wrong:** JSON-LD is output on WP admin pages, causing unnecessary data in admin HTML.

**Why it happens:** `wp_head` fires on admin pages too when `add_action( 'wp_head', ... )` is used without a front-end check.

**How to avoid:** Add `if ( is_admin() ) { return; }` at the top of the `wp_head` callback, or use `is_singular()` / `is_front_page()` conditional checks before outputting schema.

---

## Code Examples

### Complete option key list for class-settings.php

```php
// Source: CLAUDE.md and CONVENTIONS.md — project naming convention
// All WP option keys for Phase 1 settings page

$options = array(
	'twentyfourhour_google_maps_key'             => __( 'Google Maps API Key', '24hr-pharmacy-tools' ),
	'twentyfourhour_wp_rest_url'                 => __( 'WP REST API URL', '24hr-pharmacy-tools' ),
	'twentyfourhour_lowermyrx_bin'               => __( 'LowerMyRx BIN', '24hr-pharmacy-tools' ),
	'twentyfourhour_lowermyrx_pcn'               => __( 'LowerMyRx PCN', '24hr-pharmacy-tools' ),
	'twentyfourhour_lowermyrx_group'             => __( 'LowerMyRx Group', '24hr-pharmacy-tools' ),
	'twentyfourhour_amazon_pharmacy_affiliate_id' => __( 'Amazon Pharmacy Affiliate ID', '24hr-pharmacy-tools' ),
	'twentyfourhour_singleware_affiliate_id'     => __( 'SingleCare Affiliate ID', '24hr-pharmacy-tools' ),
);
```

### Schema types matrix

```php
// Source: schema.org — verified types for pharmacy/health content
// Map of post type / context → @type value

// is_singular( 'city' )
array( '@type' => 'WebPage' )

// is_singular( 'pharmacy' )
array( '@type' => array( 'Pharmacy', 'LocalBusiness' ) )

// is_front_page()
array( '@type' => 'WebSite' )

// is_singular() generically (content pages)
array( '@type' => 'Article' )

// For FAQPage (Phase 7, but @type stub can be registered now)
array( '@type' => 'FAQPage', 'mainEntity' => array() )
```

---

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| Manual `$_POST` form for settings | WordPress Settings API | WP 2.7 (2008) | Settings API handles nonces, sanitization, and error notices automatically |
| `json_encode()` for JSON-LD | `wp_json_encode()` | WP 4.1 (2014) | Safer encoding; WP wrapper handles edge cases |
| Hard-coded version strings for cache-busting | `filemtime()` | Long-standing WP pattern | `filemtime()` busts cache on every file change without manual version bumps |
| `show_in_rest => false` for CPTs | `show_in_rest => true` | WP 4.7 (2016) | Required for Block Editor support and WP REST API access by data scripts |

**Deprecated/outdated:**
- `register_post_type()` with `'publicly_queryable' => false` for content CPTs: makes the CPT inaccessible on the front end. Phase 1 CPTs must be publicly queryable.
- `the_content` filter injection for schema: `wp_head` is the correct hook. Do not inject JSON-LD via `the_content`.

---

## Open Questions

1. **Affiliate option keys — full roster**
   - What we know: Google Maps key, LowerMyRx BIN/PCN/Group, Amazon Pharmacy ID confirmed needed
   - What's unclear: Full list of affiliate program IDs for Phase 1 settings page (SingleCare, CJ programs, Impact programs)
   - Recommendation: Register a generic text field per program slug in settings. Options can be blank until Phase 6 (affiliate activation). The settings page can have a section per affiliate program with placeholder fields.

2. **Schema data source for pharmacy CPT**
   - What we know: Pharmacy CPT needs `Pharmacy`/`LocalBusiness` JSON-LD with name, URL
   - What's unclear: Address, phone, hours fields — are these stored as custom post meta or standard WP fields?
   - Recommendation: For Phase 1, output schema with `name` and `url` only (both available from WP core). Address/hours schema enhancement deferred to Phase 4 when pharmacy data pipeline is built.

3. **Deactivation hook behavior**
   - What we know: `register_deactivation_hook( __FILE__, 'flush_rewrite_rules' )` is standard practice
   - What's unclear: Whether to also delete options on deactivation (plugin uninstall vs. deactivate)
   - Recommendation: Deactivation flushes rewrite rules only. Option cleanup belongs in `register_uninstall_hook()` if added later. Do not delete options on deactivation.

---

## Environment Availability

No external CLI tools are required for Phase 1. All work is pure PHP file editing deployed via WP admin zip upload.

| Dependency | Required By | Available | Version | Fallback |
|------------|------------|-----------|---------|----------|
| PHP CLI (local) | Static analysis / linting | Unknown (not on machine) | — | Deploy and test via WP admin |
| WordPress core (Hostinger) | All plugin features | Yes (confirmed live) | 6.x | — |
| WP Admin access | Settings page verification | Yes | — | — |
| Google Rich Results Test | JSON-LD validation | Yes (web tool) | — | — |

**No blocking missing dependencies.** All Phase 1 work deploys as PHP files via WP admin zip upload. No server CLI access is required.

---

## Validation Architecture

### Test Framework
| Property | Value |
|----------|-------|
| Framework | None — no automated test infrastructure exists |
| Config file | None |
| Quick run command | N/A |
| Full suite command | N/A |

No automated testing framework is installed in this project. All validation for Phase 1 is manual, via live site checks after deployment.

### Phase Requirements → Test Map

| Req ID | Behavior | Test Type | Automated Command | File Exists? |
|--------|----------|-----------|-------------------|-------------|
| INFRA-01 | City CPT URL `/city/new-york/` returns 200 | smoke | Manual — `curl -o /dev/null -s -w "%{http_code}" https://24hourpharmacy.com/city/new-york/` | ❌ Wave 0 (manual-only) |
| INFRA-02 | WP admin shows settings page with fields | smoke | Manual — browse to WP Admin > Settings > 24Hr Pharmacy Tools | ❌ manual-only |
| INFRA-03 | Widget bundle enqueued only on pages with shortcode | smoke | Manual — check page source for `<script>` tag presence/absence | ❌ manual-only |
| INFRA-04 | JSON-LD present in `<head>` on city post | smoke | Manual — Google Rich Results Test on test city post URL | ❌ manual-only |
| INFRA-05 | `window.PharmacyToolsConfig` available in browser console | smoke | Manual — browser console `window.PharmacyToolsConfig` | ❌ manual-only |

### Sampling Rate
- **Per task commit:** Deploy zip to WP admin, verify targeted behavior manually
- **Per wave merge:** Full Phase 1 success criteria checklist (all 5 items above pass)
- **Phase gate:** All 5 success criteria pass before Phase 2 begins

### Wave 0 Gaps
No automated test files to create. All validation is manual. The planner should include explicit verification tasks for each success criterion in the wave that implements its corresponding class file.

---

## Sources

### Primary (HIGH confidence)
- WordPress Developer Reference: `register_post_type()` — https://developer.wordpress.org/reference/functions/register_post_type/
- WordPress Plugin Handbook, Settings API — https://developer.wordpress.org/plugins/settings/settings-api/
- WordPress Developer Reference: `wp_localize_script()` — https://developer.wordpress.org/reference/functions/wp_localize_script/
- WordPress Developer Reference: `add_shortcode()` — https://developer.wordpress.org/plugins/shortcodes/
- WordPress Developer Reference: `wp_head` hook — https://developer.wordpress.org/reference/hooks/wp_head/
- Project CONVENTIONS.md — naming patterns, option prefixes, escaping rules (authoritative for this project)
- Project TESTING.md — no automated framework exists; manual validation only

### Secondary (MEDIUM confidence)
- Schema.org Pharmacy type — https://schema.org/Pharmacy (confirmed `@type` values: `Pharmacy`, `LocalBusiness`, `WebPage`, `FAQPage`)
- Google Rich Results Test — https://search.google.com/test/rich-results (validation tool for JSON-LD)

---

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH — all APIs are native WordPress core, stable for 10+ years
- Architecture: HIGH — patterns sourced from project CONVENTIONS.md and WP Developer Reference
- Pitfalls: HIGH — all are well-documented WP gotchas, not speculative

**Research date:** 2026-03-28
**Valid until:** 2026-09-28 (WordPress core APIs are stable; no significant changes expected in 6 months)
