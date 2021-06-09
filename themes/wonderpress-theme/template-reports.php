<?php
/**
 * Template Name: Reports Page Template
 * Description: A custom Home Page template.
 *
 * @package Wonderpress Theme
 */

// Set the <body> id
wonder_body_id( 'reports-and-resources' );

get_header();
?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<main role="main">

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="hero" class="reports-and-resources__section reports-and-resources__section--hero">
				<div class="reports-and-resources-hero__grid">
					<header class="reports-and-resources-hero__header">
						<h1 class="reports-and-resources-hero__h1"><?php the_field( 'hero_headline' ); ?></h1>
						<p role="doc-subtitle"><?php the_field( 'hero_paragraph' ); ?></p>
					</header>
					<h3 class="reports-and-resources__h3 reports-and-resources__h3--hero">Featured Report</h3>
					<section class="reports-and-resources-hero__featured-report-section">
						<?php $resource_obj = get_field( 'hero_featured_resource' ); ?>
						<h6 class="reports-and-resources__h6">
							<?php
							$category = get_the_terms( $resource_obj, 'resource_categories' );
							echo esc_html( $category[0]->name );
							?>
						</h6>
						<h2 class="reports-and-resources-hero__featured-report-h2">
							<?php echo esc_html( get_the_title( $resource_obj ) ); ?>
						</h2>
						<p>
							<?php echo esc_html( get_the_excerpt( $resource_obj ) ); ?>
						</p>
						<div class="global-cta-area">
							<?php
							// Template: Button
							wonder_include_template_file(
								'partials/button.php',
								array(
									'acf' => get_field( 'downloadable_attachment_button', $resource_obj ),
									'class' => 'global-cta',
									'eid_page_slug' => get_field( 'eid_page_slug' ),
									'eid_section_slug' => get_field( 'hero_eid_section_slug_eid_section_slug' ),
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
					<section class="reports-and-resources-hero__featured-report-section">
						<?php
						// Template: Button
						wonder_include_template_file(
							'partials/image.php',
							array(
								'src' => get_the_post_thumbnail_url( get_the_ID(), 'extra_large' ),
							)
						);
						?>
					</section>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ reports-and-filters ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<header id="filters" class="reports-and-resources-reports-and-filters__header">
				<div class="reports-and-resources-reports-and-filters__header-grid">
					<div class="reports-and-resources-reports-and-filters__header-filters">
						<input type="checkbox" id="toggle0"
							   class="global-filter__checkbox" data-type="show-all" checked/>
						<label for="toggle0"
							   class="global-filter__label" aria-label="Show All">Show All</label>

						<?php
						$iter_x = 1;
						while ( have_rows( 'categories_categories' ) ) {
							the_row();
							$term_to_display = get_sub_field( 'category' );
							?>
							<input type="checkbox" id="toggle<?php echo esc_html( $iter_x ); ?>"
								   class="global-filter__checkbox"
								   data-type="<?php echo esc_attr( strtolower( sanitize_title( $term_to_display->name ) ) ); ?>"/>
							<label for="toggle<?php echo esc_html( $iter_x ); ?>"
								   class="global-filter__label"
								   aria-label="<?php echo esc_attr( $term_to_display->name ); ?>"><?php echo esc_attr( $term_to_display->name ); ?></label>

							<?php
							$iter_x++;
						}
						?>
					</div>
					<div id="filters_cont" class="global-header-filters-touch">
						<label for="filters-select">Filter Reports</label>
						<div class="select">
							<select id="filters-select">
								<option value="show-all" data-type="show-all">Show All Reports</option>
								<?php
								$iter_x = 1;
								while ( have_rows( 'categories_categories' ) ) {
									the_row();
									$term_to_display = get_sub_field( 'category' );
									?>
									<option value="<?php echo esc_attr( strtolower( sanitize_title( $term_to_display->name ) ) ); ?>"><?php echo esc_attr( $term_to_display->name ); ?></option>
									<?php
									$iter_x++;
								}
								?>
							</select>
						</div>
					</div>

				</div>
			</header>

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ reports-and-filters ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="reports-and-filters" class="reports-and-resources__section reports-and-resources__section--reports-and-filters">
				<div class="reports-and-resources-reports-and-filters__grid">
					<?php
					while ( have_rows( 'download_grid_sections' ) ) {
						the_row();

						$subscriptions = array();
						foreach ( get_sub_field( 'resources' ) as $resource_obj ) {
							$category = get_the_terms( $resource_obj, 'resource_categories' );
							$subscriptions[] = isset( $category[0] ) ? sanitize_title( $category[0]->name ) : 'report';
						}
						$subscriptions = array_unique( $subscriptions );
						?>
					<span class="reports-and-resources-reports-and-filters__grid-block" data-subscriptions="<?php echo esc_attr( implode( ',', $subscriptions ) ); ?>">
						<h3 class="reports-and-resources__h3" data-subscriptions="<?php echo esc_attr( $subscriptions ); ?>"><?php the_sub_field( 'headline' ); ?></h3>
						<?php foreach ( get_sub_field( 'resources' ) as $resource_obj ) { ?>

							<?php
							// Template: Resource Card
							wonder_include_template_file(
								'partials/reports-and-resources--report.php',
								array(
									'color_scheme' => get_sub_field( 'color_scheme' ),
									'resource_obj' => $resource_obj,
									'eid_page_slug' => get_field( 'eid_page_slug' ),
									'eid_section_slug' => get_field( 'download_grid_eid_section_slug_eid_section_slug' ) . '-' . get_sub_field( 'headline' ),
								)
							);
							?>
						<?php } ?>
					</span>
					<?php } ?>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ reports-and-filters: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->


			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ highlights-1 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="highlights-1" class="reports-and-resources__section reports-and-resources__section--highlights-1" data-subscriptions="<?php echo esc_attr( $subscriptions ); ?>">
				<div class="reports-and-resources-highlights-1__grid">
					<h3 class="reports-and-resources__h3"><?php the_field( 'highlights_headline' ); ?></h3>
					<section class="reports-and-resources-highlights-1__section">
						<?php $resource_obj = get_field( 'highlights_featured_resource' ); ?>
						<?php $datasource_obj = get_field( 'data_source', $resource_obj ); ?>

						<?php
						// Template: Resource Card
						wonder_include_template_file(
							'partials/reports-and-resources--report.php',
							array(
								'resource_obj' => $resource_obj,
								'filterable' => false,
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'highlights_eid_section_slug_eid_section_slug' ),
							)
						);
						?>
					</section>

					<section class="reports-and-resources-highlights-1__section">
						<?php
						// Template: Global Small Dual Bar Chart
						wonder_include_template_file(
							'partials/global-small-dual-bar-chart.php',
							array(
								'acf' => get_field( 'highlights_small_dual_bar_chart' ),
							)
						);
						?>
					</section>

				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ highlights-1: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		</main>

		<?php
	endwhile;
endif;
?>

<?php get_footer(); ?>
