<?php
/**
 * A reusable template for a horizontal section header
 *
 * @package Wonderpress Theme
 */

// Allow for the passing in of a reusable acf component
$acf = ( isset( $acf ) && is_array( $acf ) ) ? $acf : null;

$headline = isset( $headline ) ? $headline : null;
if ( ! $headline && $acf ) {
	$headline = ( isset( $acf['headline'] ) ) ? $acf['headline'] : $headline;
}

$show_button = false;
if ( $acf && $acf['show_button'] ) {
	$show_button = boolval( $acf['show_button'] );
}

// Get the button payload
$button_payload = array();

if ( $acf && $acf['button'] ) {
	$button_payload['acf'] = $acf['button'];
} else {
	// Currently does not support passing in button params w/o ACF
	$button_payload = $button_payload;
}

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>

<header class="global-horizontal-section-header">

	<h2 class="global-horizontal-section-header__heading"><?php echo esc_html( $headline ); ?></h2>

	<?php if ( isset( $show_button ) && $show_button && isset( $button_payload ) && $button_payload ) { ?>
	<div class="global-horizontal-section-header__action">
		<?php
		// Template: Button
		// TODO Come back to logic below, I negated conditional above to pass attachments and class
		wonder_include_template_file(
			'partials/button.php',
			array(
				'acf' => ( isset( $acf ) ) ? $acf['button'] : null,
				'attachment' => ( isset( $attachment ) ) ? $attachment : null,
				'class' => ( isset( $class ) ) ? $class : null,
				'eid_page_slug' => $eid_page_slug,
				'eid_section_slug' => $eid_section_slug,
			)
		);
		?>
	</div>
	<?php } ?>
</header>
