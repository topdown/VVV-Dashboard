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
 * post-type.php
 */

function pt_template_init() {
	register_post_type( 'pt_template', array(
		'labels'            => array(
			'name'                => __( 'Ep examples1s', 'plugin-example' ),
			'singular_name'       => __( 'Ep examples1', 'plugin-example' ),
			'all_items'           => __( 'All Ep examples1s', 'plugin-example' ),
			'new_item'            => __( 'New ep examples1', 'plugin-example' ),
			'add_new'             => __( 'Add New', 'plugin-example' ),
			'add_new_item'        => __( 'Add New ep examples1', 'plugin-example' ),
			'edit_item'           => __( 'Edit ep examples1', 'plugin-example' ),
			'view_item'           => __( 'View ep examples1', 'plugin-example' ),
			'search_items'        => __( 'Search ep examples1s', 'plugin-example' ),
			'not_found'           => __( 'No ep examples1s found', 'plugin-example' ),
			'not_found_in_trash'  => __( 'No ep examples1s found in trash', 'plugin-example' ),
			'parent_item_colon'   => __( 'Parent ep examples1', 'plugin-example' ),
			'menu_name'           => __( 'Ep examples1s', 'plugin-example' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-admin-post',
		'show_in_rest'      => true,
		'rest_base'         => 'pt_template',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'pt_template_init' );

function pt_template_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['pt_template'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Ep examples1 updated. <a target="_blank" href="%s">View ep examples1</a>', 'plugin-example'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'plugin-example'),
		3 => __('Custom field deleted.', 'plugin-example'),
		4 => __('Ep examples1 updated.', 'plugin-example'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Ep examples1 restored to revision from %s', 'plugin-example'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Ep examples1 published. <a href="%s">View ep examples1</a>', 'plugin-example'), esc_url( $permalink ) ),
		7 => __('Ep examples1 saved.', 'plugin-example'),
		8 => sprintf( __('Ep examples1 submitted. <a target="_blank" href="%s">Preview ep examples1</a>', 'plugin-example'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Ep examples1 scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ep examples1</a>', 'plugin-example'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Ep examples1 draft updated. <a target="_blank" href="%s">Preview ep examples1</a>', 'plugin-example'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'pt_template_updated_messages' );

// End post-type.php