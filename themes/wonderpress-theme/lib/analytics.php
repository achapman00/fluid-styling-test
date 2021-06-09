<?php
/**
 * Various analytics helper functions.
 *
 * @package Wonderpress Theme
 */

/**
 * Create the prefix of an EID string based off given attributes.
 * String must match the format:
 * comms.microsites.venture-forward/<page>.<slash_delimited_section>
 *
 * @param String $page The name of the page.
 * @param Array  $section An array of section parts - will be slash delimited.
 **/
function wonder_create_eid_string_prefix( $page = null, $section = null ) {
	$parts = array();

	if ( $page ) {
		$parts[] = sanitize_title( $page );
	}

	if ( $section ) {
		$parts[] = ( is_array( $section ) ) ? sanitize_title( join( '/', $section ) ) : sanitize_title( $section );
	}

	return 'comms.microsites.venture-forward/' . esc_attr( join( '.', $parts ) );

}

/**
 * Create an EID string suffix based off given attributes.
 * String must match the format:
 * comms.microsites.venture-forward/<page>.<slash_delimited_section>.widget.action.result
 *
 * @param String $action The name of the action.
 * @param String $result The name of the result of the action.
 **/
function wonder_create_eid_string_suffix( $action = null, $result = null ) {
	$parts = array();

	if ( $action ) {
		$parts[] = sanitize_title( $action );
	}

	if ( $result ) {
		$parts[] = sanitize_title( $result );
	}

	return esc_attr( join( '.', $parts ) );

}

/**
 * Create an EID string based off given attributes.
 * String must match the format:
 * comms.microsites.venture-forward/<page>.<slash_delimited_section>.widget.action.result
 *
 * @param String $page The name of the page.
 * @param Array  $section An array of section parts - will be slash delimited.
 * @param String $widget The name of the widget on the page.
 * @param String $action The name of the action.
 * @param String $result The name of the result of the action.
 **/
function wonder_create_eid_string( $page = null, $section = null, $widget = null, $action = null, $result = null ) {
	$parts = array();

	$parts[] = wonder_create_eid_string_prefix( $page, $section );

	if ( $widget ) {
		$parts[] = sanitize_title( $widget );
	}

	$parts[] = wonder_create_eid_string_suffix( $action, $result );

	return esc_attr( join( '.', $parts ) );

}

/**
 * Set a static page slug variable.
 *
 * @param String $slug The page slug.
 **/
function wonder_eid_page_slug( $slug = null ) {
	static $_slug;

	if ( $slug ) {
		$_slug = $slug;
	}

	return $_slug;
}

/**
 * Set a static section slug variable.
 *
 * @param String $slug The section slug.
 **/
function wonder_eid_section_slug( $slug = null ) {
	static $_slug;

	if ( $slug ) {
		$_slug = $slug;
	}

	return $_slug;
}


/**
 * Add EID's to links from the WYSIWYG.
 *
 * @param String $content The content.
 **/
function wonder_add_eids_to_wysiwyg( $content ) {
	global $eid_page_slug;
	global $eid_section_slug;

	$content = preg_replace_callback(
		'/<a([^>]*)>([^<]+)<\/a>/i',
		function( $m ) {

			$eid = wonder_create_eid_string(
				wonder_eid_page_slug(),
				wonder_eid_section_slug(),
				$m[2],
				'click'
			);

			return '<a' . $m[1] . ' data-eid="' . $eid . '">' . $m[2] . '</a>';

		},
		$content
	);

	return $content;
}

add_filter( 'the_content', 'wonder_add_eids_to_wysiwyg', 100 );
