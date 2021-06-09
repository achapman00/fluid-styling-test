<?php
/**
 * Set image sizes for the WordPress CMS
 *
 * @package Wonderpress Theme
 */

if ( function_exists( 'add_theme_support' ) ) {
	add_image_size( 'banner', 2048, '', true );
	add_image_size( 'large', 1024, '', true );
	add_image_size( 'medium', 768, '', true );
	add_image_size( 'small', 320, '', true );
	add_image_size( 'micro', 120, '', true );
}
