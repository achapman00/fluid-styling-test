<?php
/**
 * A utility class for string convenience functions.
 *
 * @package Wonderpress Theme
 */

/**
 * StringUtil class
 */
class StringUtil {

	/**
	 * Function insert_after_1st_occurrence --- Search for the first instance of a string, and insert a string
	 *
	 * @param string $original  Original string.
	 * @param string $search    Character(s) to search for.
	 * @param string $insert           String to insert.
	 * @return string|string[]
	 * Created by vincent on 08 Feb, 2021
	 */
	public static function insert_after_1st_occurrence( string $original, string $search, $insert ) {
		$index = strpos( $original, $search );
		return false === $index ? $original : substr_replace( $original, $search . $insert, $index, strlen( $search ) );
	}
}
