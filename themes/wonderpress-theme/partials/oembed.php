<?php
/**
 * A reusable template for an oembed element
 *
 * @package Wonderpress Theme
 */

// Load value.
$iframe = isset( $acf ) ? $acf : null;

// Use preg_match to find iframe src.
preg_match( '/src="(.+?)"/', $iframe, $matches );
$src = $matches[1];

// Add extra parameters to src and replcae HTML.
$params = array(
	// Uncomment when we want to hear this
	// 'autoplay'  => ( isset( $autoplay ) ? (bool) $autoplay : true ),
		'controls'  => ( isset( $show_controls ) ? (bool) $show_controls : true ),
);
$new_src = add_query_arg( $params, $src );
$iframe = str_replace( $src, $new_src, $iframe );

// Add extra attributes to iframe HTML.
$attributes = 'frameborder="0"';
$iframe = str_replace( '></iframe>', ' ' . $attributes . '></iframe>', $iframe );

// Display customized HTML. We don't need to escape this because
// we are controlling the output.
echo $iframe; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
