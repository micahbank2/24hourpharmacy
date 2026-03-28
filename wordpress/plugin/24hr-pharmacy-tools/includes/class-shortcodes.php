<?php
/**
 * Shortcode registration for React widget embedding and compliance blocks.
 *
 * @package 24HrPharmacyTools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers compliance shortcodes and widget shortcodes with conditional script enqueue.
 */
class Pharmacy_Tools_Shortcodes {

	/**
	 * Hooks script registration and shortcode registration.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_shortcode( 'medical_disclaimer', array( $this, 'medical_disclaimer' ) );
		add_shortcode( 'affiliate_disclosure', array( $this, 'affiliate_disclosure' ) );
		add_shortcode( 'pharmacy_finder', array( $this, 'pharmacy_finder_shortcode' ) );
		add_shortcode( 'discount_card', array( $this, 'discount_card_shortcode' ) );
	}

	/**
	 * Registers (but does not enqueue) widget JS bundles.
	 *
	 * Uses file_exists() guard so no 404 errors before Phase 3 builds the bundles.
	 * Scripts are enqueued conditionally inside each shortcode callback.
	 *
	 * @return void
	 */
	public function register_scripts() {
		$finder_file = PHARMACY_TOOLS_PATH . 'assets/js/pharmacy-finder.js';
		if ( file_exists( $finder_file ) ) {
			wp_register_script(
				'pharmacy-finder',
				PHARMACY_TOOLS_URL . 'assets/js/pharmacy-finder.js',
				array(),
				filemtime( $finder_file ),
				true
			);
		} else {
			wp_register_script(
				'pharmacy-finder',
				PHARMACY_TOOLS_URL . 'assets/js/pharmacy-finder.js',
				array(),
				PHARMACY_TOOLS_VERSION,
				true
			);
		}

		$card_file = PHARMACY_TOOLS_PATH . 'assets/js/discount-card.js';
		if ( file_exists( $card_file ) ) {
			wp_register_script(
				'discount-card',
				PHARMACY_TOOLS_URL . 'assets/js/discount-card.js',
				array(),
				filemtime( $card_file ),
				true
			);
		} else {
			wp_register_script(
				'discount-card',
				PHARMACY_TOOLS_URL . 'assets/js/discount-card.js',
				array(),
				PHARMACY_TOOLS_VERSION,
				true
			);
		}
	}

	/**
	 * Renders the medical disclaimer block.
	 *
	 * @param array $atts Shortcode attributes (none used currently).
	 * @return string HTML disclaimer block.
	 */
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

	/**
	 * Renders the FTC affiliate disclosure block.
	 *
	 * @param array $atts Shortcode attributes (none used currently).
	 * @return string HTML disclosure block.
	 */
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

	/**
	 * Renders the pharmacy finder widget container and conditionally enqueues its bundle.
	 *
	 * @param array $atts Shortcode attributes. Supports 'height' (default '400px').
	 * @return string HTML widget container with noscript fallback.
	 */
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

	/**
	 * Renders the discount card widget container and conditionally enqueues its bundle.
	 *
	 * @param array $atts Shortcode attributes. Supports 'height' (default '300px').
	 * @return string HTML widget container with noscript fallback.
	 */
	public function discount_card_shortcode( $atts ) {
		wp_enqueue_script( 'discount-card' );
		$atts = shortcode_atts( array( 'height' => '300px' ), $atts, 'discount_card' );
		return sprintf(
			'<div id="discount-card-root" class="pharmacy-widget discount-card-widget" style="min-height:%s;">' .
			'<noscript><p>%s</p></noscript>' .
			'</div>',
			esc_attr( $atts['height'] ),
			esc_html__( 'Please enable JavaScript to use the discount card tool.', '24hr-pharmacy-tools' )
		);
	}
}
