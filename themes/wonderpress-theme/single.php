<?php
/**
 * The template for displaying a single post.
 *
 * @package Wonderpress Theme
 */

wonder_eid_page_slug( get_field( 'stories_single_eid_page_slug', 'option' ) );

// Set the <body> id
wonder_body_id( 'story' );

get_header();
?>

<main class="global-main" role="main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			$page_theme = has_post_thumbnail( get_the_ID() ) ? '' : 'dark';
			?>

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Article ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>
					 data-theme="<?php echo esc_attr( $page_theme ); ?>">
				<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

				<?php
				// Template: Stories Loop
				wonder_include_template_file(
					'partials/stories-and-use-cases/global-stories-hero.php',
					array()
				);
				?>

				<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
				<section id="story" class="story__section story__section--article">
					<div class="story-article__grid">
						<nav id="socials" class="story-article__social-btns">
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
									data-share-msg="twitter" data-share-button="twitter" data-eid="<?php echo esc_attr( $eid ); ?>"></button>

							<?php
							$eid = wonder_create_eid_string(
								get_field( 'stories_single_eid_page_slug', 'option' ),
								get_field( 'stories_single_share_eid_section_slug_eid_section_slug', 'option' ),
								'facebook',
								'click'
							);
							?>
							<button class="story-article__social-btns__button story-article__social-btns__button--<?php echo esc_attr( $page_theme ); ?>"
									data-share-msg="facebook" data-share-button="facebook" data-eid="<?php echo esc_attr( $eid ); ?>"></button>


							<?php
							$eid = wonder_create_eid_string(
								get_field( 'stories_single_eid_page_slug', 'option' ),
								get_field( 'stories_single_share_eid_section_slug_eid_section_slug', 'option' ),
								'linkedin',
								'click'
							);
							?>
							<button class="story-article__social-btns__button story-article__social-btns__button--<?php echo esc_attr( $page_theme ); ?>"
									data-share-msg="linkedin" data-share-button="linkedin" data-eid="<?php echo esc_attr( $eid ); ?>"></button>
									
						</nav>
						<div id="the-content" class="story-article__the-content">
							<?php
							wonder_eid_section_slug( get_field( 'stories_single_content_eid_section_slug', 'option' ) );
							the_content();
							?>
						</div>

						<div class="story-article__story-cards">
							<?php
							$card_types = array( 'explore-data', 'story-style-2' );
							foreach ( $card_types as $val ) {
								switch ( $val ) {
									// case 'story-style-2':
									// if ( get_field( 'stories_single_featured_story', 'option' ) ) {
									// Template: Download Data Set
									// wonder_include_template_file(
									// 'partials/stories-and-use-cases/global-story-card--story-style-2.php',
									// array(
									// 'story_obj' => get_field( 'stories_single_featured_story', 'option' ),
									// 'class_modifier' => array(
									// 'story-style-2',
									// 'block',
									// ),
									// )
									// );
									// }
									// break;
									case 'explore-data':
										$story_obj = ( get_field( 'featured_story' ) ) ? get_field( 'featured_story' ) : get_field( 'stories_single_featured_story', 'option' );
										$story_button = ( get_field( 'featured_story_button' ) && ! empty( get_field( 'featured_story_button' )['link_text'] ) ) ? get_field( 'featured_story_button' ) : get_field( 'stories_single_featured_story_button', 'option' );

										// $style = isset( $style ) ? $style : 'light';
										// $icon = isset( $icon ) ? $icon : null;
										// $image = isset( $image ) ? $image : null;
										// $preheadline = isset( $preheadline ) ? $preheadline : null;
										// $headline = isset( $headline ) ? $headline : null;
										// $paragraph = isset( $paragraph ) ? $paragraph : null;
										// $button = isset( $button ) ? $button : null;

										// Template: Explore the Data
										wonder_include_template_file(
											'partials/stories-and-use-cases/global-story-card--extra.php',
											array(
												'style' => 'light',
												'image' => get_the_post_thumbnail_url( $story_obj, 'large' ),
												'headline' => get_the_title( $story_obj ),
												'paragraph' => get_the_excerpt( $story_obj ),
												'button' => $story_button,
												'class_modifier' => array(
													'block',
													'explore-the-data-story',
												),
												'eid_page_slug' => get_field( 'stories_single_eid_page_slug', 'option' ),
												'eid_section_slug' => get_field( 'stories_single_featured_story_eid_section_slug_eid_section_slug', 'option' ),
											)
										);
										break;
								}
							}
							?>
						</div>
					</div>
				</section>

				<?php
				$horizontal_grid_row = ( get_field( 'more_stories_horizontal_grid_row' ) && is_array( get_field( 'more_stories_horizontal_grid_row' )['stories'] ) && count( get_field( 'more_stories_horizontal_grid_row' )['stories'] ) ? get_field( 'more_stories_horizontal_grid_row' ) : get_field( 'stories_single_more_stories_horizontal_grid_row', 'option' ) );
				$horizontal_section_header = ( get_field( 'more_stories_horizontal_section_header' ) && ! empty( get_field( 'more_stories_horizontal_section_header' )['headline'] ) ? get_field( 'more_stories_horizontal_section_header' ) : get_field( 'stories_single_more_stories_horizontal_section_header', 'option' ) );

				// Template: Featured Stories Grid Row
				wonder_include_template_file(
					'partials/global-featured-stories-grid-row-section.php',
					array(
						'acf' => $horizontal_grid_row,
						'style' => 'style-1',
						'header_acf' => $horizontal_section_header,
						'eid_page_slug' => get_field( 'stories_single_eid_page_slug', 'option' ),
						'eid_section_slug' => get_field( 'stories_single_more_stories_eid_section_slug_eid_section_slug', 'option' ),
					)
				);
				?>

			</article>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Article: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

		<?php endwhile; ?>

	<?php else : ?>
		<article>
			<h3 class="global-story__h3"><?php esc_html_e( 'Sorry, nothing to display.', 'bt' ); ?></h3>
		</article>
	<?php endif; ?>

</main>
<?php

wp_localize_script(
	'global',
	'single_vars',
	array(
		'permalink' => get_permalink(),
	)
);

get_footer(); ?>
