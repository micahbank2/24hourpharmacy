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
