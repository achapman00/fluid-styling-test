<?php
/**
 * A WordPress utility class for string convenience functions.
 *
 * @package Wonderpress Theme
 */

/**
 * WPStringUtil class
 */
class WPStringUtil {

	/**
	 * Get_directory_uri --- Get the WordPress base URI to concatenate with assets URI
	 * Created by vincent on 09 Feb, 2021
	 *
	 * @usage <img alt="Company Trademark" src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/company_tm_1000x200.svg">
	 */
	private static function get_directory_uri() {
		echo esc_url( get_template_directory_uri() . '/' );
	}

	/**
	 * Get_base_uri --- Get WordPress directory URI
	 * Created by vincent on 09 Feb, 2021
	 */
	public static function get_base_uri() {
		return self::get_directory_uri();
	}
}
