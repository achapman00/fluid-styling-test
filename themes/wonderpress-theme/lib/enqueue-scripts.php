<?php
/**
 * Enqueue any javascript files to be used in this theme.
 *
 * @package Wonderpress Theme
 */

/**
 * Enqueue scripts
 * These scrips will be added to the
 */
function wonder_enqueue_scripts() {
	if ( 'wp-login.php' !== $GLOBALS['pagenow'] && ! is_admin() ) {

		// Remove the built-in WordPress copy of jQuery
		wp_deregister_script( 'jquery' );

		// Replace with our own copy of jquery (and our custom scripts)
		$path = '/js/min-scripts.js';
		$version = filemtime( get_template_directory() . $path );
		wp_register_script( 'global', get_template_directory_uri() . $path, array(), $version, true );
		wp_enqueue_script( 'global' );
		wp_localize_script(
			'global',
			'global_vars',
			array(
				'ajax_nonce' => wp_create_nonce( 'ajax-nonce' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

		if ( is_front_page() || wonder_body_id() == 'explore-the-data' || wonder_body_id() == 'city-portal' ) {
			wp_register_script( 'mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.js', array(), '1.0.0', true );
			wp_enqueue_script( 'mapbox' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'wonder_enqueue_scripts' );
