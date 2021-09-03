<?php
/**
 * Enqueue any stylesheets to be used in this theme.
 *
 * @package Wonderpress Theme
 */

/**
 * Enqueue styles
 */
function wonder_enqueue_styles() {

	// remove dashicons
	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
	}

	// wp_register_style( 'fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700,300,600', array(), '1.0', 'all' );
	// wp_enqueue_style( 'fonts' );

	if ( is_front_page() || wonder_body_id() == 'explore-the-data' || wonder_body_id() == 'city-portal' ) {
		wp_register_style( 'mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.css', array(), '1.0.0', 'all' );
		wp_enqueue_style( 'mapbox' );
	}

	$path = '/css/styles.css';
	$version = filemtime( get_template_directory() . $path );
	wp_register_style( 'theme', get_template_directory_uri() . $path, array(), $version, 'all' );
	wp_enqueue_style( 'theme' );
}

add_action( 'wp_enqueue_scripts', 'wonder_enqueue_styles' );
