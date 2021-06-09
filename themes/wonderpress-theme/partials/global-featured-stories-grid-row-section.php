<?php
/**
 * A reusable template for a featured stories grid row
 *
 * @package Wonderpress Theme
 */

// Allow for the passing in of a reusable acf component
$acf = ( isset( $acf ) && is_array( $acf ) ) ? $acf : null;

$header = ( isset( $header_acf ) && is_array( $header_acf ) ) ? $header_acf : null;

$style = ( isset( $style ) ) ? $style : null;

$story_objs = isset( $story_objs ) ? $story_objs : array();
if ( ! $story_objs && $acf ) {
	$story_objs = ( isset( $acf['stories'] ) ) ? $acf['stories'] : $story_objs;
}

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>

<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Stories and Use Cases  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
<section id="stories-and-use-cases" class="global-featured-stories-grid-row__section
<?php
if ( $style ) {
	echo esc_attr( ' global-featured-stories-grid-row__section--' . $style ); }
?>
">
	<div class="global-featured-stories-grid-row__grid">
		<?php
		if ( $header_acf ) {
			// Template: Horizontal Section Header
			wonder_include_template_file(
				'partials/global-horizontal-section-header.php',
				array(
					'acf' => $header_acf,
					'attachment' => 'right_arrow',
					'class' => 'global-cta global-cta--style-2',
					'eid_page_slug' => $eid_page_slug,
					'eid_section_slug' => $eid_section_slug,
				)
			);
		}
		?>
		<div class="global-featured-stories-grid-row__grid-lower">
			<?php foreach ( $story_objs as $story_obj ) { ?>
				<div class="global-featured-stories-grid-row__card">

					<?php
					// Template: Theme Vertical Story Card
					wonder_include_template_file(
						'partials/stories-and-use-cases/global-story-card--style-1.php',
						array(
							'story_obj' => $story_obj,
							'eid_page_slug' => $eid_page_slug,
							'eid_section_slug' => $eid_section_slug,
						)
					);
					?>

				</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Stories and Use Cases: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
