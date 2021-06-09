<?php
/**
 * A custom WordPress navigation function to use with
 * WordPress menus.
 *
 * @package Wonderpress Theme
 */

/**
 * Function: wonder_nav --- Uses wp_nav_menu() to generate a new menu.
 *
 * @param string     $location                      The name the navigation location to hook into.
 * @param array|null $options                   Pass a custom options to WP.
 * @param array|null $insert_elements_before_menu  If you'd like to insert an html elements just after the container's opening tag,
 * pass an array with the search query, and the element(s) you'd like inserted.
 * @return false|string|string[]|void
 * Created by vincent on 08 Feb, 2021
 */
function wonder_nav( string $location = 'header-menu', array $options = null, array $insert_elements_before_menu = null ) {
	if ( is_null( $options ) ) {
		if ( is_null( $insert_elements_before_menu ) ) {
			return wp_nav_menu(
				array(
					'theme_location' => $location,
					'menu' => '',
					'container' => 'div',
					'container_class' => 'menu-{menu slug}-container',
					'container_id' => '',
					'menu_class' => 'menu',
					'menu_id' => '',
					'echo' => true,
					'fallback_cb' => 'wp_page_menu',
					'before' => '<>',
					'after' => '',
					'link_before' => '',
					'link_after' => '',
					'items_wrap' => '<ul>%3$s</ul>',
					'depth' => 0,
					'walker' => '',
				)
			);
		} else {
			$menu = wp_nav_menu(
				array(
					'theme_location' => $location,
					'menu' => '',
					'container' => 'div',
					'container_class' => 'menu-{menu slug}-container',
					'container_id' => '',
					'menu_class' => 'menu',
					'menu_id' => '',
					'echo' => false,
					'fallback_cb' => 'wp_page_menu',
					'before' => '<>',
					'after' => '',
					'link_before' => '',
					'link_after' => '',
					'items_wrap' => '<ul>%3$s</ul>',
					'depth' => 0,
					'walker' => '',
				)
			);
			return StringUtil::insert_after_1st_occurrence( $menu, $insert_elements_before_menu['search'], $insert_elements_before_menu['insert'] );
		}
	} else if ( is_null( $insert_elements_before_menu ) ) {
		return wp_nav_menu( $options );
	} else {
		return StringUtil::insert_after_1st_occurrence( wp_nav_menu( $options ), $insert_elements_before_menu['search'], $insert_elements_before_menu['insert'] );
	}
}
