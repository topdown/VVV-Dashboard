<?php

/**
 *
 * PHP version 5
 *
 * Created: 11/15/16, 2:43 PM
 *
 * LICENSE:
 *
 * @author         Jeff Behnke <code@validwebs.com>
 * @copyright  (c) 2016 ValidWebs.com
 *
 * dashboard
 * taxonomy.php
 */

function tx_template_init() {
	register_taxonomy( 'tx_template', array( 'pt_template' ), array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'show_admin_column' => false,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'            => array(
			'name'                       => __( 'Ep tests1s', 'plugin-example' ),
			'singular_name'              => _x( 'Ep tests1', 'taxonomy general name', 'plugin-example' ),
			'search_items'               => __( 'Search ep tests1s', 'plugin-example' ),
			'popular_items'              => __( 'Popular ep tests1s', 'plugin-example' ),
			'all_items'                  => __( 'All ep tests1s', 'plugin-example' ),
			'parent_item'                => __( 'Parent ep tests1', 'plugin-example' ),
			'parent_item_colon'          => __( 'Parent ep tests1:', 'plugin-example' ),
			'edit_item'                  => __( 'Edit ep tests1', 'plugin-example' ),
			'update_item'                => __( 'Update ep tests1', 'plugin-example' ),
			'add_new_item'               => __( 'New ep tests1', 'plugin-example' ),
			'new_item_name'              => __( 'New ep tests1', 'plugin-example' ),
			'separate_items_with_commas' => __( 'Ep tests1s separated by comma', 'plugin-example' ),
			'add_or_remove_items'        => __( 'Add or remove ep tests1s', 'plugin-example' ),
			'choose_from_most_used'      => __( 'Choose from the most used ep tests1s', 'plugin-example' ),
			'not_found'                  => __( 'No ep tests1s found.', 'plugin-example' ),
			'menu_name'                  => __( 'Ep tests1s', 'plugin-example' ),
		),
		'show_in_rest'      => true,
		'rest_base'         => 'tx_template',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	) );

}
add_action( 'init', 'tx_template_init' );

// End taxonomy.php