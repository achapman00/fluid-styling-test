<?php
/**
 * A convenient function to gather all url sizes for a
 * provided post object.
 *
 * @package Wonderpress Theme
 */

/**
 * Gather all sizes of thumbnails.
 *
 * @param Int|Object $post_obj_or_id The post object or id to gather images for.
 */
function get_all_post_thumbnail_urls( $post_obj_or_id ) {
	$urls = array();
	foreach ( get_intermediate_image_sizes() as $size ) {
		$urls[ $size ] = get_the_post_thumbnail_url( $post_obj_or_id, $size );
	}

	return $urls;
}
