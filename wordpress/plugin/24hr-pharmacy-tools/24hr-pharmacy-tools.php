<?php
/**
 * Plugin Name: 24hr Pharmacy Tools
 * Description: Custom post types, taxonomies, shortcodes, and schema markup for 24hourpharmacy.com
 * Version:     1.0.0
 * Author:      24hourpharmacy.com
 * Text Domain: 24hr-pharmacy-tools
 *
 * @package 24HrPharmacyTools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PHARMACY_TOOLS_VERSION', '1.0.0' );
define( 'PHARMACY_TOOLS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PHARMACY_TOOLS_URL', plugin_dir_url( __FILE__ ) );

require_once PHARMACY_TOOLS_PATH . 'includes/class-post-types.php';
require_once PHARMACY_TOOLS_PATH . 'includes/class-schema.php';
require_once PHARMACY_TOOLS_PATH . 'includes/class-shortcodes.php';
require_once PHARMACY_TOOLS_PATH . 'includes/class-settings.php';

// Instantiate and register all plugin components.
$pharmacy_post_types = new Pharmacy_Tools_Post_Types();
$pharmacy_post_types->register();

$pharmacy_settings = new Pharmacy_Tools_Settings();
$pharmacy_settings->register();

$pharmacy_schema = new Pharmacy_Tools_Schema();
$pharmacy_schema->register();

$pharmacy_shortcodes = new Pharmacy_Tools_Shortcodes();
$pharmacy_shortcodes->register();

// Activation: flush rewrite rules so CPT URLs resolve immediately.
register_activation_hook( __FILE__, array( 'Pharmacy_Tools_Post_Types', 'activation' ) );

// Deactivation: flush rewrite rules to remove CPT slugs from WP cache.
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
