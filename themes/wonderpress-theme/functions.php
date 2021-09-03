<?php
/**
 * Global functions.
 *
 * @package Wonderpress Theme
 */

define(
	'DATA_PAGE_AVAILABLE_COMPARISONS',
	array(
		'com_vac' => array(
			'format' => 'percentage',
			'label' => 'Commercial Vacancy Rate',
			'description' => 'High commercial vacancy rates leave communities with distressed assets and drive down property values. Microbusiness density and commercial vacancy rates don’t show a correlation.',
			'source' => 'Brookings Institution',
			'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-brookings.png',
			'source_url' => 'https://www.brookings.edu/',
		),
		'havd' => array(
			'format' => 'percentage',
			'label' => 'Highly Active Microbusiness Density',
			'description' => 'Microbusinesses with a more active online presence are associated with a range of benefits like higher household median income',
			'source' => 'GoDaddy',
			'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-godaddy.png',
			'source_url' => 'https://godaddy.com/',
		),
		'rev_dec' => array(
			'format' => 'percentage',
			'label' => 'Reported revenue decrease',
			'description' => 'Percent of small businesses reporting a loss in revenue in the last week, according to the U.S. Census. Communities with more microbusinesses had fewer small businesses reporting recent revenue loss.',
			'source' => 'U.S. Census Bureau',
			'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-census.png',
			'source_url' => 'https://www.census.gov/',
		),
		'spend_all' => array(
			'format' => 'percentage',
			'label' => 'Relative change in credit/debit card spending',
			'description' => 'Seasonally adjusted credit/debit-card spending is strongly correlated with overall consumer spending. Communities with more microbusinesses had greater relative levels of credit/debit-card spending.',
			'source' => 'Opportunity Insights',
			'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-insight.png',
			'source_url' => 'https://opportunityinsights.org/',
		),
		'unemp_percent' => array(
			'format' => 'percentage',
			'label' => 'Unemployment Rate',
			'description' => 'Percent of workforce with no job but actively seeking employment. Each additional microbusiness/100 people causes unemployment to drop .05 percentage points in a county.',
			'source' => 'Bureau of Labor Statistics',
			'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-bls.png',
			'source_url' => 'https://www.bls.gov/',
		),
		'orders' => array(
			'format' => 'float',
			'label' => 'GoDaddy Website Transactions',
			'description' => 'Number of commercial orders per website in 2020 to 2019, aggregated nationally across 1 million GoDaddy Websites + Marketing (W+M) websites. The higher the number, the greater the relative order frequency.',
			'source' => 'GoDaddy',
			'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-godaddy.png',
			'source_url' => 'https://www.godaddy.com/',
		),
		'traffic' => array(
			'format' => 'float',
			'label' => 'GoDaddy Website Traffic',
			'description' => 'Number of visits per website in 2020 relative to 2019, aggregated nationally across 1 million GoDaddy Websites + Marketing (W+M) websites',
			'source' => 'GoDaddy',
			'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-godaddy.png',
			'source_url' => 'https://www.godaddy.com/',
		),
		// 'activity_index' => array(
		// 'format' => 'float',
		// 'label' => 'Microbusiness Index',
		// 'description' => 'An index to track microbusiness activity',
		// 'source' => 'GoDaddy',
		// 'source_icon' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-godaddy.png',
		// 'source_url' => 'https://www.godaddy.com/',
		// ),
	)
);

/**
 * Require all files in a directory.
 *
 * @param String $path The path to the directory (with trailing slash).
 */
function require_all( $path ) {
	foreach ( glob( $path . '*.php' ) as $filename ) {
		require_once $filename;
	}
}

/**
 * Import PHP files from ./lib/ directory
 */
require_all( dirname( __FILE__ ) . '/lib/' );


/**
 * Theme Support
 */

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
}

/**
 * Custom Post Types
 */

require_all( dirname( __FILE__ ) . '/lib/custom-post-types/' );

/**
 * Shortcodes
 */

require_all( dirname( __FILE__ ) . '/lib/shortcodes/' );


/**
 * Remove Various Actions
 */

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
 * Options pages for ACF
 */
if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title'    => 'Theme General Settings',
			'menu_title'    => 'Theme Settings',
			'menu_slug'     => 'theme-general-settings',
			'capability'    => 'edit_posts',
			'redirect'      => false,
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'    => 'Global Meta Tags',
			'menu_title'    => 'Global Meta Tags',
			'parent_slug'   => 'theme-general-settings',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'    => 'Stories List Settings',
			'menu_title'    => 'Stories List',
			'parent_slug'   => 'theme-general-settings',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'    => 'Stories Single Settings',
			'menu_title'    => 'Stories Single',
			'parent_slug'   => 'theme-general-settings',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'    => 'Unsupported Browser Settings',
			'menu_title'    => 'Unsupported Browser Settings',
			'parent_slug'   => 'theme-general-settings',
		)
	);

}

/**
 * Remove_css_id_filter --- Remove all unwanted WordPress-generated CSS classes all Classes and ID from Nav Menus
 *
 * @param array $var See WordPres Codex.
 * @return array|string
 * Created by vincent on 10 Feb, 2021
 */
function remove_css_id_filter( $var ) {
	return is_array( $var ) ? array_intersect( $var, array( 'current-menu-item' ) ) : '';
}
add_filter( 'page_css_class', 'remove_css_id_filter', 1, 1 );
add_filter( 'nav_menu_item_id', 'remove_css_id_filter', 1, 1 );
add_filter( 'nav_menu_css_class', 'remove_css_id_filter', 1, 1 );

/**
 * Add_menu_link_class --- Adds support to add custom classes to Anchor elements within Nav Menus
 *
 * @param array  $atts See WordPres Codex.
 * @param object $item See WordPres Codex.
 * @param array  $args See WordPres Codex.
 * @return mixed
 * Created by vincent on 10 Feb, 2021
 */
function add_menu_link_class( $atts, $item, $args ) {
	if ( property_exists( $args, 'anchor_classes' ) ) {
		$atts['class'] = $args->anchor_classes;
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_menu_link_class', 2, 3 );

/**
 * Add_menu_anchor_classes --- Adds support to add custom classes to List Item elements within Nav Menus
 *
 * @param array  $classes See WordPres Codex.
 * @param object $item See WordPres Codex.
 * @param array  $args See WordPres Codex.
 * @return mixed
 * Created by vincent on 10 Feb, 2021
 */
function add_menu_anchor_classes( $classes, $item, $args ) {
	if ( property_exists( $args, 'list_item_classes' ) ) {
		$classes[] = $args->list_item_classes;
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'add_menu_anchor_classes', 2, 3 );

/**
 * Set the Google Maps API Key
 *
 * @param string $api The Google Maps Api Key.
 */
function wonder_acf_google_map_api( $api ) {
	$api['key'] = 'AIzaSyBhh1TqybbSqdGgkuQf5BaovhPR3-Uvb3s';
	return $api;
}
add_filter( 'acf/fields/google_map/api', 'wonder_acf_google_map_api' );

/**
 * Get_estimated_reading_time --- Get estimated reading time in minutes
 *
 * @author Vincent V. Toscano
 * Created by Vincent on Oct. 19, 2020
 * Updated Oct. 20, 2020
 * Ref.: get_the_content https://developer.wordpress.org/reference/functions/get_the_content/
 * Ref.: strip_shortcodes https://developer.wordpress.org/reference/functions/strip_shortcodes/
 * Ref.: strip_tags https://www.php.net/manual/en/function.strip-tags.php
 * @param string $content Feed the method your copy/content/string.
 * @param int    $wpm Typical/average words per Minute 200 to 400 wpm.
 * @return false|float
 * @example <div class="read-time"><?php echo get_estimated_reading_time( get_the_content() ); ?> minutes</div>
 */
function get_estimated_reading_time( $content = '', $wpm = 250 ) {
	$cleaned_content = strip_tags( strip_shortcodes( $content ) );
	$word_count = str_word_count( $cleaned_content );
	$time = ceil( $word_count / $wpm );
	return $time;
}

/**
 * Check if the user is on an unsupported browser
 *
 * @author Johnnie Munger
 */
function wonder_enforce_unsupported_browser() {
	$user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : null;

	if ( ! $user_agent ) {
		return;
	}

	$ua = htmlentities( $user_agent, ENT_QUOTES, 'UTF-8' );

	if ( preg_match( '~MSIE|Internet Explorer~i', $ua ) || ( strpos( $ua, 'Trident/7.0' ) !== false && strpos( $ua, 'rv:11.0' ) !== false ) ) {
		wp_redirect( get_field( 'global_unsupported_browser_page', 'option' ) );
		exit;
	}
}
