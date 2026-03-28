<?php
/**
 * Custom post types registration.
 *
 * @package 24HrPharmacyTools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the City and Pharmacy custom post types.
 */
class Pharmacy_Tools_Post_Types {

	/**
	 * Hooks CPT registration methods onto the init action.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_city_cpt' ) );
		add_action( 'init', array( $this, 'register_pharmacy_cpt' ) );
	}

	/**
	 * Registers the City custom post type.
	 *
	 * @return void
	 */
	public function register_city_cpt() {
		$labels = array(
			'name'               => __( 'Cities', '24hr-pharmacy-tools' ),
			'singular_name'      => __( 'City', '24hr-pharmacy-tools' ),
			'add_new'            => __( 'Add New', '24hr-pharmacy-tools' ),
			'add_new_item'       => __( 'Add New City', '24hr-pharmacy-tools' ),
			'edit_item'          => __( 'Edit City', '24hr-pharmacy-tools' ),
			'new_item'           => __( 'New City', '24hr-pharmacy-tools' ),
			'view_item'          => __( 'View City', '24hr-pharmacy-tools' ),
			'search_items'       => __( 'Search Cities', '24hr-pharmacy-tools' ),
			'not_found'          => __( 'No cities found.', '24hr-pharmacy-tools' ),
			'not_found_in_trash' => __( 'No cities found in trash.', '24hr-pharmacy-tools' ),
			'menu_name'          => __( 'Cities', '24hr-pharmacy-tools' ),
		);

		register_post_type(
			'city',
			array(
				'labels'       => $labels,
				'public'       => true,
				'has_archive'  => false,
				'rewrite'      => array(
					'slug'       => 'city',
					'with_front' => false,
				),
				'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-location',
			)
		);
	}

	/**
	 * Registers the Pharmacy custom post type.
	 *
	 * @return void
	 */
	public function register_pharmacy_cpt() {
		$labels = array(
			'name'               => __( 'Pharmacies', '24hr-pharmacy-tools' ),
			'singular_name'      => __( 'Pharmacy', '24hr-pharmacy-tools' ),
			'add_new'            => __( 'Add New', '24hr-pharmacy-tools' ),
			'add_new_item'       => __( 'Add New Pharmacy', '24hr-pharmacy-tools' ),
			'edit_item'          => __( 'Edit Pharmacy', '24hr-pharmacy-tools' ),
			'new_item'           => __( 'New Pharmacy', '24hr-pharmacy-tools' ),
			'view_item'          => __( 'View Pharmacy', '24hr-pharmacy-tools' ),
			'search_items'       => __( 'Search Pharmacies', '24hr-pharmacy-tools' ),
			'not_found'          => __( 'No pharmacies found.', '24hr-pharmacy-tools' ),
			'not_found_in_trash' => __( 'No pharmacies found in trash.', '24hr-pharmacy-tools' ),
			'menu_name'          => __( 'Pharmacies', '24hr-pharmacy-tools' ),
		);

		register_post_type(
			'pharmacy',
			array(
				'labels'       => $labels,
				'public'       => true,
				'has_archive'  => false,
				'rewrite'      => array(
					'slug'       => 'pharmacy',
					'with_front' => false,
				),
				'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-plus-alt',
			)
		);
	}

	/**
	 * Plugin activation callback.
	 *
	 * Registers CPTs and flushes rewrite rules so new slugs resolve immediately.
	 * CRITICAL: flush_rewrite_rules() is called ONLY here, never on init.
	 *
	 * @return void
	 */
	public static function activation() {
		$instance = new self();
		$instance->register_city_cpt();
		$instance->register_pharmacy_cpt();
		flush_rewrite_rules();
	}
}
