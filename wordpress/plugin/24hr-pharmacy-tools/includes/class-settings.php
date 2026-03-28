<?php
/**
 * Admin settings page for affiliate codes and configuration.
 *
 * @package 24HrPharmacyTools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the plugin's admin settings page via the WordPress Settings API.
 */
class Pharmacy_Tools_Settings {

	/**
	 * Option group name for settings_fields().
	 *
	 * @var string
	 */
	const OPTION_GROUP = 'twentyfourhour_settings';

	/**
	 * Admin page slug.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'twentyfourhour-settings';

	/**
	 * Registers admin_menu and admin_init hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Adds the settings page under WP Admin > Settings.
	 *
	 * @return void
	 */
	public function add_admin_page() {
		add_options_page(
			__( '24Hr Pharmacy Tools', '24hr-pharmacy-tools' ),
			__( '24Hr Pharmacy Tools', '24hr-pharmacy-tools' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Registers settings sections, fields, and option keys.
	 *
	 * @return void
	 */
	public function register_settings() {

		// --- Section: API Keys ---
		add_settings_section(
			'twentyfourhour_api_section',
			__( 'API Keys', '24hr-pharmacy-tools' ),
			'__return_false',
			self::PAGE_SLUG
		);

		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_google_maps_key',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_field(
			'twentyfourhour_google_maps_key',
			__( 'Google Maps API Key', '24hr-pharmacy-tools' ),
			array( $this, 'render_text_field' ),
			self::PAGE_SLUG,
			'twentyfourhour_api_section',
			array( 'option' => 'twentyfourhour_google_maps_key' )
		);

		// --- Section: Savings Card ---
		add_settings_section(
			'twentyfourhour_savings_section',
			__( 'Savings Card', '24hr-pharmacy-tools' ),
			'__return_false',
			self::PAGE_SLUG
		);

		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_lowermyrx_bin',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_field(
			'twentyfourhour_lowermyrx_bin',
			__( 'LowerMyRx BIN', '24hr-pharmacy-tools' ),
			array( $this, 'render_text_field' ),
			self::PAGE_SLUG,
			'twentyfourhour_savings_section',
			array( 'option' => 'twentyfourhour_lowermyrx_bin' )
		);

		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_lowermyrx_pcn',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_field(
			'twentyfourhour_lowermyrx_pcn',
			__( 'LowerMyRx PCN', '24hr-pharmacy-tools' ),
			array( $this, 'render_text_field' ),
			self::PAGE_SLUG,
			'twentyfourhour_savings_section',
			array( 'option' => 'twentyfourhour_lowermyrx_pcn' )
		);

		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_lowermyrx_group',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_field(
			'twentyfourhour_lowermyrx_group',
			__( 'LowerMyRx Group', '24hr-pharmacy-tools' ),
			array( $this, 'render_text_field' ),
			self::PAGE_SLUG,
			'twentyfourhour_savings_section',
			array( 'option' => 'twentyfourhour_lowermyrx_group' )
		);

		// --- Section: Affiliate IDs ---
		add_settings_section(
			'twentyfourhour_affiliate_section',
			__( 'Affiliate IDs', '24hr-pharmacy-tools' ),
			'__return_false',
			self::PAGE_SLUG
		);

		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_amazon_pharmacy_affiliate_id',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_field(
			'twentyfourhour_amazon_pharmacy_affiliate_id',
			__( 'Amazon Pharmacy Affiliate ID', '24hr-pharmacy-tools' ),
			array( $this, 'render_text_field' ),
			self::PAGE_SLUG,
			'twentyfourhour_affiliate_section',
			array( 'option' => 'twentyfourhour_amazon_pharmacy_affiliate_id' )
		);

		register_setting(
			self::OPTION_GROUP,
			'twentyfourhour_singlecare_affiliate_id',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_field(
			'twentyfourhour_singlecare_affiliate_id',
			__( 'SingleCare Affiliate ID', '24hr-pharmacy-tools' ),
			array( $this, 'render_text_field' ),
			self::PAGE_SLUG,
			'twentyfourhour_affiliate_section',
			array( 'option' => 'twentyfourhour_singlecare_affiliate_id' )
		);
	}

	/**
	 * Renders the settings page HTML.
	 *
	 * @return void
	 */
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

	/**
	 * Renders a single text input field for the settings page.
	 *
	 * @param array $args Field arguments. Expects 'option' key with the option name.
	 * @return void
	 */
	private function render_text_field( $args ) {
		$option = isset( $args['option'] ) ? $args['option'] : '';
		$value  = get_option( $option, '' );
		printf(
			'<input type="text" name="%s" id="%s" value="%s" class="regular-text" />',
			esc_attr( $option ),
			esc_attr( $option ),
			esc_attr( $value )
		);
	}
}
