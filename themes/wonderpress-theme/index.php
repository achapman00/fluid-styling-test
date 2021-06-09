<?php
/**
 * The index page template.
 *
 * @package Wonderpress Theme
 */

// Set the <body> id
wonder_body_id( 'stories-and-use-cases' );

get_header();
?>

<main class="global-main" role="main">
	<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
	<?php
	// Template: Stories Loop
	wonder_include_template_file(
		'partials/stories-and-use-cases/global-stories-hero.php',
		array(
			'eid_page_slug' => get_field( 'stories_eid_page_slug', 'option' ),
			'eid_section_slug' => get_field( 'stories_hero_eid_section_slug_eid_section_slug', 'option' ),
		)
	);
	?>
	<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
	<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ stories ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
	<header id="filters" class="stories-and-use-cases-stories__header">
		<div class="stories-and-use-cases-stories__header-grid">
			<div class="stories-and-use-cases-stories__header-filters">
				<?php

				/**
				 * Determine_if_active_page --- Determine which filter should be highlighted and drop-down option selected.
				 *
				 * @param int  $term_id Submit the filter's term ID to check whether it is currently the active page's category.
				 * @param bool $option In the case of a drop-down menu, set this to true and then returned string will change.
				 * @return string
				 * Created by vincent on 10 Mar, 2021
				 */
				function determine_if_active_page( int $term_id, bool $option = false ) {
					// Find the current category
					$current_category = ( is_category() ) ? get_queried_object()->term_id : -1;
					if ( $current_category === $term_id ) {
						return $option ? 'selected' : 'active';
					} else {
						return '';
					}
				}

				wonder_include_template_file(
					'partials/link.php',
					array(
						'text' => 'Show All',
						'url' => '/ventureforward/profiles-and-use-cases',
						'class' => 'global-filter__a global-filter__a--' . determine_if_active_page( -1 ),
						'eid_page_slug' => get_field( 'stories_eid_page_slug', 'option' ),
						'eid_section_slug' => get_field( 'stories_categories_eid_section_slug_eid_section_slug', 'option' ),
						'attributes' => array(
							'data-type' => 'show-all',
							'aria-label' => 'Show All',
						),
					)
				);
				?>

				<?php
				while ( have_rows( 'stories_categories_to_display', 'option' ) ) {
					the_row();
					$term_to_display = get_sub_field( 'category' );

					if ( $term_to_display ) {
						?>

						<?php
						wonder_include_template_file(
							'partials/link.php',
							array(
								'text' => esc_attr( $term_to_display->name ),
								'url' => esc_url( get_category_link( $term_to_display ) ),
								'class' => 'global-filter__a global-filter__a--' . determine_if_active_page( $term_to_display->term_id ),
								'eid_page_slug' => get_field( 'stories_eid_page_slug', 'option' ),
								'eid_section_slug' => get_field( 'stories_categories_eid_section_slug_eid_section_slug', 'option' ),
								'eid_widget_slug' => $term_to_display->name,
								'attributes' => array(
									'data-type' => esc_attr( strtolower( sanitize_title( $term_to_display->name ) ) ),
									'aria-label' => esc_attr( $term_to_display->name ),
								),
							)
						);
						?>
						<?php
					}
				}
				?>
			</div>
			<div id="filters_cont" class="global-header-filters-touch">
				<label for="filters-select">Filter Stories</label>
				<div class="select">
					<select id="filters-select">
						<option value="/stories-and-use-cases">Show All</option>
						<?php
						while ( have_rows( 'stories_categories_to_display', 'option' ) ) {
							the_row();
							$term_to_display = get_sub_field( 'category' );
							?>
							<option value="<?php echo esc_url( get_category_link( $term_to_display ) ); ?>" <?php echo esc_attr( determine_if_active_page( $term_to_display->term_id, true ) ); ?>><?php echo esc_attr( $term_to_display->name ); ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>

		</div>
	</header>

	<section class="stories-and-use-cases__section stories-and-use-cases__section--stories">
		<?php
		// Template: Stories Loop
		wonder_include_template_file(
			'partials/stories-and-use-cases/global-stories-loop.php',
			array(
				'eid_page_slug' => get_field( 'stories_eid_page_slug', 'option' ),
				'eid_section_slug' => get_field( 'stories_grid_eid_section_slug_eid_section_slug', 'option' ),
			)
		);
		?>
	</section>

	<section class="stories-and-use-cases__pagination">
		<?php get_template_part( 'pagination' ); ?>
	</section>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
