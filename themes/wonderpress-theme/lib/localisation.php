<?php
/**
 * Adds localisation support to a WordPress theme
 *
 * @package Wonderpress Theme
 */

/**
 * Uses load_theme_textdomain() to add localisation support.
 */
if ( function_exists( 'add_theme_support' ) ) {
	load_theme_textdomain( 'bt', get_template_directory() . '/languages' );
}
