<?php
/**
 * Create a custom post type
 *
 * @package Wonderpress Theme
 */

/**
 * Uses register_post_type() to create a custom post type.
 */
function wonder_create_data_source_post_type() {
	register_post_type(
		'data-source',
		array(
			'labels'       => array(
				'name'               => __( 'Data Sources' ),
				'singular_name'      => __( 'Data Source' ),
				'add_new'            => __( 'Add New' ),
				'add_new_item'       => __( 'Add New Data Source' ),
				'edit'               => __( 'Edit' ),
				'edit_item'          => __( 'Edit Data Source' ),
				'new_item'           => __( 'New Data Source' ),
				'view'               => __( 'View Data Source' ),
				'view_item'          => __( 'View Data Source' ),
				'search_items'       => __( 'Search Data Sources' ),
				'not_found'          => __( 'No Data Sources found' ),
				'not_found_in_trash' => __( 'No Data Sources found in Trash' ),
			),
			'public'       => true,
			'hierarchical' => false,
			'has_archive'  => false,
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 10,
			'exclude_from_search' => true,
			'supports'     => array(
				'title',
				// 'editor',
				// 'excerpt',
				'thumbnail',
			),
			'can_export'   => true,
			'taxonomies'   => array(
				// 'post_tag',
				// 'category'
			),
		)
	);
}

add_action( 'init', 'wonder_create_data_source_post_type' );
