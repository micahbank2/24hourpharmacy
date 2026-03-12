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
