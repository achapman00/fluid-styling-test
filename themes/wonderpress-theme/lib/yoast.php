<?php
/**
 * A function to include template files
 *
 * @package Wonderpress Theme
 */

/**
 * Removes output from Yoast SEO on the frontend for a specific post, page or custom post type.
 *
 * @param String $disable_wpseo Whether or not to disable Yoast.
 */
function wonder_disable_wpseo( $disable_wpseo = null ) {
	static $_disable_wpseo;

	if ( ! is_null( $disable_wpseo ) ) {
		$_disable_wpseo = $disable_wpseo;
		return;
	}

	return ( $_disable_wpseo ? $_disable_wpseo : false );
}

/**
 * Removes output from Yoast SEO on the frontend for a specific post, page or custom post type.
 */
function wonder_remove_wpseo() {
	if ( wonder_disable_wpseo() ) {
		$front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
		remove_action( 'wpseo_head', array( $front_end, 'present_head' ), -9999 );
	}
}
add_action( 'wp_enqueue_scripts', 'wonder_remove_wpseo' );
