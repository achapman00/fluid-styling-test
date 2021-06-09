<?php
/**
 * A function to include template files
 *
 * @package Wonderpress Theme
 */

/**
 * Render or return the contents of a template file.
 *
 * @param String  $_filename The path to the file.
 * @param Mixed[] $_params An array of variables to pass to the template.
 * @param Boolean $_return Whether to return the contents (instead of echoing them).
 */
function wonder_include_template_file( $_filename, $_params = array(), $_return = false ) {
	if ( $_return ) {
		$html = '';
		ob_start();
	}

	foreach ( $_params as $k => $v ) {
		$$k = $v;
	}
	include( locate_template( $_filename ) );

	if ( $_return ) {
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
