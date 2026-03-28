<?php
/**
 * JSON-LD structured data output.
 *
 * @package 24HrPharmacyTools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs JSON-LD structured data via wp_head for appropriate page contexts.
 */
class Pharmacy_Tools_Schema {

	/**
	 * Hooks schema output onto wp_head.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'wp_head', array( $this, 'output_schema' ) );
	}

	/**
	 * Dispatches schema output based on current page context.
	 * Guards against admin pages where wp_head also fires.
	 *
	 * @return void
	 */
	public function output_schema() {
		if ( is_admin() ) {
			return;
		}

		if ( is_singular( 'pharmacy' ) ) {
			$this->output_pharmacy_schema();
		} elseif ( is_singular( 'city' ) ) {
			$this->output_city_schema();
		} elseif ( is_front_page() ) {
			$this->output_website_schema();
		}

		if ( is_singular() ) {
			// Skip generic WebPage schema on types that already have more-specific schema.
			if ( ! is_singular( 'pharmacy' ) && ! is_singular( 'city' ) ) {
				$this->output_webpage_schema();
			}
			$this->output_faqpage_schema();
		}
	}

	/**
	 * Outputs Pharmacy + LocalBusiness JSON-LD schema for pharmacy CPT pages.
	 *
	 * @return void
	 */
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

	/**
	 * Outputs WebPage JSON-LD schema for city CPT pages.
	 *
	 * @return void
	 */
	private function output_city_schema() {
		$schema = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'WebPage',
			'name'        => get_the_title(),
			'url'         => get_permalink(),
			'description' => get_the_excerpt(),
		);
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		);
	}

	/**
	 * Outputs WebSite JSON-LD schema for the homepage.
	 *
	 * @return void
	 */
	private function output_website_schema() {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url( '/' ),
		);
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		);
	}

	/**
	 * Outputs generic WebPage JSON-LD schema for non-CPT singular pages.
	 *
	 * @return void
	 */
	private function output_webpage_schema() {
		if ( ! is_singular() ) {
			return;
		}
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

	/**
	 * Outputs FAQPage JSON-LD schema stub.
	 *
	 * Phase 1 stub: mainEntity is empty. Phase 7 will populate FAQ entries
	 * without modifying this hook structure.
	 *
	 * @return void
	 */
	private function output_faqpage_schema() {
		if ( ! is_singular() ) {
			return;
		}
		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array(),
		);
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		);
	}
}
