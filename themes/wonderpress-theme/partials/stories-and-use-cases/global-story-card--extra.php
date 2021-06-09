<?php
/**
 * A reusable template for an extra grid card.
 *
 * @package Wonderpress Theme
 */

$style = isset( $style ) ? $style : 'light';
$icon = isset( $icon ) ? $icon : null;
$image = isset( $image ) ? $image : null;
$preheadline = isset( $preheadline ) ? $preheadline : null;
$headline = isset( $headline ) ? $headline : null;
$paragraph = isset( $paragraph ) ? $paragraph : null;
$button = isset( $button ) ? $button : null;

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>

<div class="global-story__card global-story__card--extra-card global-story__card--extra-card-<?php echo esc_attr( $style ); ?>">
	<?php
	// Template: Image
	if ( $icon ) {
		wonder_include_template_file(
			'partials/image.php',
			array(
				'acf' => $icon,
				'class' => 'global-story__card-graph',
			)
		);
	} elseif ( $image ) {
		wonder_include_template_file(
			'partials/image.php',
			array(
				'acf' => ( is_array( $image ) ? $image : null ),
				'src' => ( ! is_array( $image ) ? $image : null ),
				'size' => 'large',
				'alt' => $headline,
			)
		);
	}
	?>
	<?php if ( $preheadline ) { ?>
	<h6 class="global-story__h6"><?php echo esc_html( $preheadline ); ?></h6>
	<?php } ?>

	<?php if ( $headline ) { ?>
	<h3 class="global-story__h3"><?php echo esc_html( $headline ); ?></h3>
	<?php } ?>

	<?php if ( $paragraph ) { ?>
	<p class="global-story__p"><?php echo esc_html( $paragraph ); ?></p>
	<?php } ?>

	<?php if ( $button ) { ?>
	<div class="global-cta-area">
		<?php
		// Template: Button
		wonder_include_template_file(
			'partials/button.php',
			array(
				'acf' => $button,
				'class' => 'global-cta' . ( isset( $style ) && 'dark' == $style ? ' global-cta--dark' : '' ),
				'attachment' => 'right_arrow',
				'eid_page_slug' => $eid_page_slug,
				'eid_section_slug' => $eid_section_slug,
			)
		);
		?>
	</div>
	<?php } ?>
</div>
