<?php
/**
 * Plugin Name: Image Upload Restrictor
 * Description: MU-plugin to filter out large images upon upload
 * Version: 0.1
 * Author: Vincent V. Toscano
 */

add_filter('image_upload_restrictor', 'on_image_upload_check');

function on_image_upload_check( $file ) {

	// Get image size in kilobytes
	$current_image_size     = $file['size']/1024;
	$max_image_filesize     = 1000; // 1 Megabyte
	$max_image_dimensions   = array(
		'width' => 3840,
		'height' => 3840
	);

	$image = getimagesize($file['tmp_name']);
	$image_width    = $image[0];
	$image_height   = $image[0];

	$image_error_messages   = array(
		'filesize' => "The image you attempted to upload exceeds the max filesize restriction of: $max_image_filesize KB. Please upload an JPG, PNG, GIF, or SVG no larger than: {$max_image_dimensions['width']}px x {$max_image_dimensions['height']}px, and >= $max_image_filesize KB.",
		'dimensions' => "The image you attempted to upload exceeds the max dimensions restriction of: {$max_image_dimensions['width']}px x {$max_image_dimensions['height']}px. Please upload an JPG, PNG, GIF, or SVG no larger than: {$max_image_dimensions['width']}px x {$max_image_dimensions['height']}px, and >= $max_image_filesize KB.",
	);

	$is_image = strpos($file['type'], 'image');

	if (( $current_image_size > $max_image_filesize ) && ($is_image )) {
		$file['error'] = $image_error_messages['filesize'];
		return $file;
	} elseif ( $image_width  > $max_image_dimensions['width'] || $image_height  > $max_image_dimensions['height']){
		$file['error'] = $image_error_messages['dimensions'];
		return $file;
	} else {
		return $file;
	}
}
