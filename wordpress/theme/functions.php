<?php
/**
 * 24 Hour Pharmacy Child Theme Functions
 *
 * @package 24HourPharmacy
 */

// Enqueue parent and child styles.
add_action( 'wp_enqueue_scripts', 'twentyfourhour_enqueue_styles' );
function twentyfourhour_enqueue_styles() {
	wp_enqueue_style( 'generatepress-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'twentyfourhour-style', get_stylesheet_uri(), array( 'generatepress-style' ) );
	wp_enqueue_style( 'twentyfourhour-custom', get_stylesheet_directory_uri() . '/assets/css/custom.css', array(), '1.0.0' );
}

/**
 * Passes plugin config to widget scripts via window.PharmacyToolsConfig.
 *
 * Runs at priority 20 so scripts registered at priority 10 (default) are
 * already registered before wp_localize_script is called.
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
}
