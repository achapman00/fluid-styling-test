<?php
/**
 * Create a custom post type
 *
 * @package Wonderpress Theme
 */

/**
 * Uses register_post_type() to create a custom post type.
 */
function wonder_create_resource_post_type() {
	register_post_type(
		'resource',
		array(
			'labels'       => array(
				'name'               => __( 'Resources' ),
				'singular_name'      => __( 'Resource' ),
				'add_new'            => __( 'Add New' ),
				'add_new_item'       => __( 'Add New Resource' ),
				'edit'               => __( 'Edit' ),
				'edit_item'          => __( 'Edit Resource' ),
				'new_item'           => __( 'New Resource' ),
				'view'               => __( 'View Resource' ),
				'view_item'          => __( 'View Resource' ),
				'search_items'       => __( 'Search Resources' ),
				'not_found'          => __( 'No Resources found' ),
				'not_found_in_trash' => __( 'No Resources found in Trash' ),
			),
			'public'       => true,
			'hierarchical' => true,
			'has_archive'  => true,
			'menu_icon'     => 'dashicons-analytics',
			'menu_position' => 5,
			'exclude_from_search' => true,
			'supports'     => array(
				'title',
				'editor',
				'excerpt',
				// 'thumbnail',
			),
			'can_export'   => true,
			'taxonomies'   => array(
				// 'post_tag',
				// 'category'
			),
		)
	);

	register_taxonomy(
		'resource_categories',
		array(
			'resource',
		),
		array(
			'hierarchical' => true,
			'label' => 'Resource Category',
			'query_var' => false,
			'rewrite' => false,
		)
	);
}

add_action( 'init', 'wonder_create_resource_post_type' );
