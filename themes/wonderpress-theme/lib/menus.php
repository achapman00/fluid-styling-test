<?php
/**
 * Register all menu locations for the WordPress CMS
 *
 * @package Wonderpress Theme
 */

/**
 * Register nav menus
 */
function wonder_register_menu() {
	register_nav_menus(
		array(
			'header-menu-1'  => 'Header Menu 1',
			'footer-menu-1'  => 'Footer Menu 1',
			'footer-menu-2'  => 'Footer Menu 2',
			'footer-menu-legal-links'  => 'Footer Menu Legal Links',
			'http-status-menu'  => 'HTTP Status Menu',
		)
	);
}

add_action( 'init', 'wonder_register_menu' );


/**
 * Add custom attributes to menu items (from ACF)
 *
 * @param Array  $atts The existing attributes.
 * @param Object $item The menu item.
 * @param Array  $args The other args.
 **/
function wonder_menu_custom_attrs( $atts, $item, $args ) {
	$eid = get_field( 'eid', $item );

	if ( $eid ) {
		$atts['data-eid'] = $eid;
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'wonder_menu_custom_attrs', 10, 3 );
