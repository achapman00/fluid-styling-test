<?php
/**
 * Temporary place to store reports-and-resources-removed-components
 *
 * @package Wonderpress Theme
 */

?>

<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ highlights-2 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
<section id="highlights-2" class="reports-and-resources__section reports-and-resources__section--highlights-2">
	<div class="reports-and-resources-highlights-2__grid">
		<section class="reports-and-resources-highlights-2__section">
			<?php
			while ( have_rows( 'highlights_continued_bullets' ) ) {
				the_row();
				?>
				<div class="reports-and-resources-highlights-2__cell">
					<div class="reports-and-resources-highlights-2__bullet reports-and-resources-highlights-2__bullet--<?php the_sub_field( 'color_scheme' ); ?>">
						<?php the_sub_field( 'number' ); ?>
					</div>
					<div class="reports-and-resources-highlights-2__p"><?php the_sub_field( 'paragraph' ); ?></div>
				</div>
			<?php } ?>
		</section>

		<section class="reports-and-resources-highlights-2__section">
			<?php $resource_obj = get_field( 'highlights_continued_featured_resource' ); ?>
			<?php $datasource_obj = get_field( 'data_source', $resource_obj ); ?>

			<?php
			// Template: Resource Card
			wonder_include_template_file(
				'partials/reports-and-resources--report.php',
				array(
					'resource_obj' => $resource_obj,
					'eid_page_slug' => get_field( 'eid_page_slug' ),
					'eid_section_slug' => get_field( 'highlights_continued_eid_section_slug_eid_section_slug' ),
					'color_scheme' => 2,
				)
			);
			?>
		</section>
	</div>
</section>
<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ highlights-2: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ quick-facts ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
<section id="quick-facts"
		 class="reports-and-resources__section reports-and-resources__section--quick-facts">
	<div class="reports-and-resources-quick-facts__grid">
		<h3 class="reports-and-resources__h3">
			<hr>
			<span><?php the_field( 'quick_facts_headline' ); ?></span></h3>

		<?php
		$iter_x = 1;
		while ( have_rows( 'quick_facts_facts' ) ) {
			the_row();
			?>
			<!-- ▩▩ card <?php echo esc_html( $iter_x ); ?> ▩▩ -->
			<div class="reports-and-resources-quick-facts__card"
				 data-card="<?php echo esc_html( $iter_x ); ?>">
				<i class="reports-and-resources-quick-facts__info-graphic-arrow"
				   data-icon="<?php the_sub_field( 'icon_type' ); ?>"></i>
				<h5 class="reports-and-resources-quick-facts__info-stat"><?php the_sub_field( 'number' ); ?></h5>
				<p class="reports-and-resources-quick-facts__info_copy"><?php the_sub_field( 'paragraph' ); ?></p>
			</div>
			<?php
			$iter_x++;
		}
		?>
		<!-- ▩▩ CTA ▩▩ -->
		<div class="reports-and-resources-quick-facts__cta-area">
			<?php
			// Template: Button
			wonder_include_template_file(
				'partials/button.php',
				array(
					'acf' => get_field( 'quick_facts_button' ),
					'class' => 'global-cta',
					'eid_page_slug' => get_field( 'eid_page_slug' ),
					'eid_section_slug' => get_field( 'quick_facts_eid_section_slug_eid_section_slug' ),
					'attachment' => 'download-ani',
					'attributes' => array(
						'data-ani-action' => 'download',
					),
				)
			);
			?>
		</div>
	</div>
</section>
<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ quick-facts: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
