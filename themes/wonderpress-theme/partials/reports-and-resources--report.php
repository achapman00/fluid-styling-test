<?php
/**
 * A reusable template for a resource card
 *
 * @package Wonderpress Theme
 */

$filterable = ( ! isset( $filterable ) || true == $filterable );

$resource_obj = isset( $resource_obj ) ? $resource_obj : null;
$color_scheme = isset( $color_scheme ) ? $color_scheme : null;

$category = get_the_terms( $resource_obj, 'resource_categories' );

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>
<section class="reports-and-resources__report reports-and-resources__report--<?php echo esc_attr( $color_scheme ); ?>"
																						<?php
																						if ( $filterable ) {
																							?>
	 data-type="<?php echo esc_attr( isset( $category[0] ) ? sanitize_title( $category[0]->name ) : 'report' ); ?>"<?php } ?>>
	<?php $datasource_obj = get_field( 'data_source', $resource_obj ); ?>
	<?php
	if ( isset( $datasource_obj[0] ) ) {
		// Template: Button
		wonder_include_template_file(
			'partials/image.php',
			array(
				'src' => get_the_post_thumbnail_url( $datasource_obj[0], 'small' ),
				'class' => 'reports-and-resources__report-img',
			)
		);
	}
	?>
	<h6 class="reports-and-resources__h6 <?php echo esc_attr( $color_scheme ); ?>">
		<?php
		if ( isset( $datasource_obj[0] ) ) {
			echo esc_html( get_the_title( $datasource_obj[0] ) ) . ' / '; }
		?>
		<?php echo esc_html( isset( $category[0] ) ? $category[0]->name : 'Report' ); ?>
	</h6>

	<?php if ( get_field( 'publication_date', $resource_obj ) ) { ?>
	<div class="reports-and-resources__report-publication-date">
		<?php the_field( 'publication_date', $resource_obj ); ?>
	</div>
	<?php } ?>

	<h4><?php echo esc_html( get_the_title( $resource_obj ) ); ?></h4>

	<p><?php echo esc_html( get_the_excerpt( $resource_obj ) ); ?></p>


	<div class="reports-and-resources__report-cta-area">
		<?php
		// Template: Button
		wonder_include_template_file(
			'partials/button.php',
			array(
				'acf' => get_field( 'downloadable_attachment_button', $resource_obj ),
				'class' => 'global-cta global-cta--' . $color_scheme,
				'eid_page_slug' => $eid_page_slug,
				'eid_section_slug' => $eid_section_slug,
				'eid_widget_slug' => get_the_title( $resource_obj ),
				'attachment' => 'download-ani',
				'attributes' => array(
					'data-ani-action' => 'download',
					'rel' => 'noopener',
				),
			)
		);
		?>
	</div>
</section>
