<?php
/**
 * A convenience class to change mime types into something nice for humans.
 *
 * @package Wonderpress Theme
 */

/**
 * Function: mimetype_to_friendly.
 *
 * @param string $mimetype                      The mimetype to translate.
 * @return string
 */
function mimetype_to_friendly( $mimetype ) {
	switch ( strtolower( $mimetype ) ) {

		// CSV
		case ( 'text/csv' ):
			$def = 'CSV';
			break;

		// Images
		case ( 'image/jpeg' || 'image/png' || 'image/gif' ):
			$def = 'Image';
			break;

		// PDF
		case ( 'application/pdf' ):
			$def = 'PDF';
			break;

		default:
			$def = 'File';
			break;
	}

	return $def;
}
