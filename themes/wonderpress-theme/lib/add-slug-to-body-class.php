<?php
/**
 * Adds the slug of the current page or post as a class to the <body> tag
 *
 * @package Wonderpress Theme
 */

/**
 * Extract the slug and add it to the classes[] array.
 *
 * @param mixed[] $classes An array of classes for the body tag.
 */
function wonder_add_slug_to_body_class( $classes ) {
	global $post;
	if ( is_home() ) {
		$key = array_search( 'blog', $classes, true );
		if ( $key > -1 ) {
			unset( $classes[ $key ] );
		}
	} elseif ( is_page() ) {
		$classes[] = sanitize_html_class( $post->post_name );
	} elseif ( is_singular() ) {
		$classes[] = sanitize_html_class( $post->post_name );
	}

	return $classes;
}

add_filter( 'body_class', 'wonder_add_slug_to_body_class' );
