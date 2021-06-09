<?php
/**
 * A reusable template for a global-horizontal-carousel
 *
 * @package Wonderpress Theme
 */

$section_id = ( isset( $section_id ) ) ? $section_id : 'carousel';

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>

<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Story Carousel ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
<section id="<?php echo esc_attr( $section_id ); ?>" class="global-horizontal-carousel__section global-horizontal-carousel__section--story-carousel">
	<div class="global-horizontal-carousel__grid">
		<?php
		// Template: Horizontal Section Header
		wonder_include_template_file(
			'partials/global-horizontal-section-header.php',
			array(
				'acf' => get_field( 'community_spotlight_horizontal_header' ),
				'attachment' => 'right_arrow',
				'class' => 'global-cta global-cta--style-2',
				'eid_page_slug' => $eid_page_slug,
				'eid_section_slug' => $eid_section_slug,
			)
		);
		?>
		<?php
		// Template: Featured Stories Slider
		wonder_include_template_file(
			'partials/global-horizontal-carousel/global-horizontal-carousel--slider.php',
			array(
				'acf' => get_field( 'community_spotlight_story_slider' ),
				'slider_id' => isset( $slider_id ) ? $slider_id : 'slider',
				'eid_page_slug' => $eid_page_slug,
				'eid_section_slug' => $eid_section_slug,
			)
		);
		?>
		<div id="dots" class="global-horizontal-carousel-dots"></div>
</section>
<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Story: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
