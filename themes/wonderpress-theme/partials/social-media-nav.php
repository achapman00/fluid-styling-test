<?php
/**
 * A reusable template for a social media navigation
 *
 * @package Wonderpress Theme
 */

$section = ( isset( $section ) ) ? $section : 0;
$section_y_override = ( isset( $section_y_override ) ) ? $section_y_override : 'na';

if ( ! isset( $page_theme ) ) {
	$page_theme = 'dark';
}
?>

<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Social Media Navigation ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
<nav id="socials-<?php echo esc_attr( $section ); ?>" class="explore-the-data__social-btns explore-the-data__social-btns--<?php echo esc_attr( $section_y_override ); ?>">
	<div class="<?php echo esc_attr( $page_theme ); ?>">SHARE</div>

	<?php
	$eid = wonder_create_eid_string(
		get_field( 'stories_single_eid_page_slug', 'option' ),
		get_field( 'stories_single_share_eid_section_slug_eid_section_slug', 'option' ),
		'twitter',
		'click'
	);
	?>
	<button class="story-article__social-btns__button story-article__social-btns__button--<?php echo esc_attr( $page_theme ); ?>"
			data-share-msg="twitter" data-share-button="twitter" data-eid="<?php echo esc_attr( $eid ); ?>" data-share-section="<?php echo esc_attr( $section ); ?>"></button>

	<?php
	$eid = wonder_create_eid_string(
		get_field( 'stories_single_eid_page_slug', 'option' ),
		get_field( 'stories_single_share_eid_section_slug_eid_section_slug', 'option' ),
		'facebook',
		'click'
	);
	?>
	<button class="story-article__social-btns__button story-article__social-btns__button--<?php echo esc_attr( $page_theme ); ?>"
			data-share-msg="facebook" data-share-button="facebook" data-eid="<?php echo esc_attr( $eid ); ?>" data-eid="<?php echo esc_attr( $eid ); ?>" data-share-section="<?php echo esc_attr( $section ); ?>"></button>


	<?php
	$eid = wonder_create_eid_string(
		get_field( 'stories_single_eid_page_slug', 'option' ),
		get_field( 'stories_single_share_eid_section_slug_eid_section_slug', 'option' ),
		'linkedin',
		'click'
	);
	?>
	<button class="story-article__social-btns__button story-article__social-btns__button--<?php echo esc_attr( $page_theme ); ?>"
			data-share-msg="linkedin" data-share-button="linkedin" data-eid="<?php echo esc_attr( $eid ); ?>" data-eid="<?php echo esc_attr( $eid ); ?>" data-share-section="<?php echo esc_attr( $section ); ?>"></button>

</nav>
<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Modal: Social Media Navigation ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
