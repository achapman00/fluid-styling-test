<?php
/**
 * Set / get the <body> ID
 *
 * @package Wonderpress Theme
 */

/**
 * Stash a static record of the intended body id
 *
 * @param String $body_id The ID of the body tag.
 */
function wonder_body_id( $body_id = null ) {
	static $_body_id;

	if ( ! is_null( $body_id ) ) {
		$_body_id = $body_id;
		return;
	}

	return ( $_body_id ? $_body_id : 'body' );
}
