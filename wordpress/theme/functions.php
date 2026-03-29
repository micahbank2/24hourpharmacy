<?php
/**
 * 24 Hour Pharmacy Child Theme Functions
 *
 * @package 24HourPharmacy
 */

/**
 * Enqueue parent Kadence stylesheet and child styles.
 *
 * Kadence's own stylesheet handle is 'kadence-global' for the global CSS
 * and 'kadence-style' for the main theme stylesheet. We depend on both so
 * our child stylesheet always loads after Kadence.
 */
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles' );
function child_enqueue_styles() {
	// Enqueue the Kadence parent theme stylesheet.
	wp_enqueue_style(
		'kadence-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'kadence' )->get( 'Version' )
	);

	// Enqueue the child theme stylesheet, depending on Kadence.
	wp_enqueue_style(
		'twentyfourhour-style',
		get_stylesheet_uri(),
		array( 'kadence-style' ),
		wp_get_theme()->get( 'Version' )
	);

	// Enqueue additional custom CSS.
	wp_enqueue_style(
		'twentyfourhour-custom',
		get_stylesheet_directory_uri() . '/assets/css/custom.css',
		array( 'twentyfourhour-style' ),
		'1.0.0'
	);
}

/**
 * Register custom post types and taxonomies.
 *
 * city    — individual city pharmacy finder pages
 * pharmacy — individual pharmacy location pages
 * state   — state archive grouping
 */
add_action( 'init', 'twentyfourhour_register_post_types' );
function twentyfourhour_register_post_types() {
	// City post type.
	register_post_type(
		'city',
		array(
			'labels'      => array(
				'name'          => __( 'Cities', '24hourpharmacy' ),
				'singular_name' => __( 'City', '24hourpharmacy' ),
			),
			'public'      => true,
			'has_archive' => false,
			'rewrite'     => array( 'slug' => 'pharmacy' ),
			'supports'    => array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
			'menu_icon'   => 'dashicons-location',
		)
	);

	// Pharmacy post type.
	register_post_type(
		'pharmacy',
		array(
			'labels'      => array(
				'name'          => __( 'Pharmacies', '24hourpharmacy' ),
				'singular_name' => __( 'Pharmacy', '24hourpharmacy' ),
			),
			'public'      => true,
			'has_archive' => false,
			'rewrite'     => array( 'slug' => 'pharmacy-location' ),
			'supports'    => array( 'title', 'editor', 'custom-fields' ),
			'menu_icon'   => 'dashicons-building',
		)
	);

	// State taxonomy for grouping cities.
	register_taxonomy(
		'state',
		array( 'city' ),
		array(
			'labels'            => array(
				'name'          => __( 'States', '24hourpharmacy' ),
				'singular_name' => __( 'State', '24hourpharmacy' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'state' ),
			'show_admin_column' => true,
		)
	);
}

/**
 * Passes plugin config to widget scripts via window.PharmacyToolsConfig.
 *
 * Runs at priority 20 so scripts registered at priority 10 (default) are
 * already registered before wp_localize_script is called.
 *
 * All sensitive values (API keys, affiliate codes) are pulled from the
 * WordPress options table — never hardcoded here.
 *
 * @return void
 */
add_action( 'wp_enqueue_scripts', 'twentyfourhour_localize_config', 20 );
function twentyfourhour_localize_config() {
	$config = array(
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'siteUrl'   => home_url( '/' ),
		'affiliate' => array(
			'lowermyrx_bin'   => get_option( 'twentyfourhour_lowermyrx_bin', '' ),
			'lowermyrx_pcn'   => get_option( 'twentyfourhour_lowermyrx_pcn', '' ),
			'lowermyrx_group' => get_option( 'twentyfourhour_lowermyrx_group', '' ),
			'amazon_id'       => get_option( 'twentyfourhour_amazon_pharmacy_affiliate_id', '' ),
			'singlecare_id'   => get_option( 'twentyfourhour_singlecare_affiliate_id', '' ),
		),
		'maps'      => array(
			'api_key' => get_option( 'twentyfourhour_google_maps_key', '' ),
		),
	);

	if ( wp_script_is( 'pharmacy-finder', 'registered' ) ) {
		wp_localize_script( 'pharmacy-finder', 'PharmacyToolsConfig', $config );
	}

	if ( wp_script_is( 'discount-card', 'registered' ) ) {
		wp_localize_script( 'discount-card', 'PharmacyToolsConfig', $config );
	}

	if ( wp_script_is( 'price-checker', 'registered' ) ) {
		wp_localize_script( 'price-checker', 'PharmacyToolsConfig', $config );
	}

	if ( wp_script_is( 'open-now-checker', 'registered' ) ) {
		wp_localize_script( 'open-now-checker', 'PharmacyToolsConfig', $config );
	}
}

/**
 * Add medical disclaimer and FTC affiliate disclosure to post content
 * on relevant post types.
 */
add_filter( 'the_content', 'twentyfourhour_append_disclaimer' );
function twentyfourhour_append_disclaimer( $content ) {
	if ( ! is_singular( array( 'city', 'pharmacy' ) ) && ! is_page() ) {
		return $content;
	}
	// Bail if template already rendered an explicit disclaimer shortcode.
	if ( has_shortcode( $content, 'medical_disclaimer' ) ) {
		return $content;
	}

	$disclaimer  = '<div class="medical-disclaimer" role="note" aria-label="' . esc_attr__( 'Medical Disclaimer', '24hourpharmacy' ) . '">';
	$disclaimer .= '<p><strong>' . esc_html__( 'Medical Disclaimer:', '24hourpharmacy' ) . '</strong> ';
	$disclaimer .= esc_html__( 'The information on this page is provided for informational purposes only and does not constitute medical advice. Always consult a qualified healthcare professional regarding medical conditions or medications.', '24hourpharmacy' );
	$disclaimer .= '</p></div>';

	return $content . $disclaimer;
}
