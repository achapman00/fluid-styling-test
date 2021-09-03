<?php
/**
 * Template Name: Data Page Template
 * Description: A custom Home Page template.
 *
 * @package Wonderpress Theme
 */

$prefill_county = null;
if ( isset( $_REQUEST['county'] ) && ! empty( $_REQUEST['county'] ) ) {
	$prefill_county = vf_get_specific_county( sanitize_text_field( wp_unslash( $_REQUEST['county'] ) ) );
}

$default_page_section = null;
if ( isset( $_REQUEST['section'] ) && ! empty( $_REQUEST['section'] ) ) {
	$default_page_section = intval( $_REQUEST['section'] );
}

// Set the <body> id
wonder_body_id( 'explore-the-data' );

// Disable Yoast (so that we can do our custom share things)
if ( $prefill_county ) {

	// Override the Facebook url
	add_filter(
		'wpseo_opengraph_url',
		function( $url ) use ( $prefill_county, $default_page_section ) {
			$section = ( is_null( $default_page_section ) ) ? 0 : intval( $default_page_section );
			$url = $url . '?county=' . $prefill_county['county'];
			return $url;
		}
	);


	// Override the Facebook description
	add_filter(
		'wpseo_opengraph_desc',
		function( $description ) use ( $prefill_county, $default_page_section ) {
			switch ( $default_page_section ) {
				default:
					$description = str_replace( 'your area', $prefill_county['name'], $description );
					break;
			}
			return $description;
		}
	);

	// Override the Facebook image
	add_filter(
		'wpseo_opengraph_image',
		function( $image ) use ( $prefill_county, $default_page_section ) {
			$section = ( is_null( $default_page_section ) ) ? 0 : intval( $default_page_section );
			$image = 'https://godaddy-data-social.s3.amazonaws.com/images/' . $prefill_county['county'] . '_s_' . $section . '.png';
			return $image;
		}
	);

	// Override the Facebook title
	add_filter(
		'wpseo_opengraph_title',
		function( $title ) use ( $prefill_county, $default_page_section ) {
			switch ( $default_page_section ) {
				default:
					$title = str_replace( 'your community', $prefill_county['name'], $title );
					break;
			}
			return $title;
		}
	);

	// Override the Twitter description
	add_filter(
		'wpseo_twitter_description',
		function( $description ) use ( $prefill_county, $default_page_section ) {
			switch ( $default_page_section ) {
				default:
					$description = str_replace( 'your area', $prefill_county['name'], $description );
					break;
			}
			return $description;
		}
	);

	// Override the Twitter image
	add_filter(
		'wpseo_twitter_image',
		function( $image ) use ( $prefill_county, $default_page_section ) {
			$section = ( is_null( $default_page_section ) ) ? 0 : intval( $default_page_section );
			$image = 'https://godaddy-data-social.s3.amazonaws.com/images/' . $prefill_county['county'] . '_s_' . $section . '.png';
			return $image;
		}
	);

	// Override the Twitter title
	add_filter(
		'wpseo_twitter_title',
		function( $title ) use ( $prefill_county, $default_page_section ) {
			switch ( $default_page_section ) {
				default:
					$title = str_replace( 'your community', $prefill_county['name'], $title );
					break;
			}
			return $title;
		}
	);
}

get_header();
?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>

		<div class="explore-the-data-sticky-search" data-sticky-search>
			<div class="explore-the-data-sticky-search__grid">

				<div class="explore-the-data-sticky-search__search-input-area">

					<div class="explore-the-data-sticky-search__search-instructions">
						<?php the_field( 'sticky_bar_search_instructions' ); ?>
					</div>

					<div class="explore-the-data-sticky-search__search-input" data-search-input-container>

						<div class="explore-the-data-sticky-search__search-selection explore-the-data-sticky-search__search-selection--selection-1"
							 data-search-selection-1></div>

						<input type="text" name="sticky-search" placeholder="Enter a County or Metro" class="explore-the-data-sticky-search__input" autocomplete="off" data-search-input />

						<ul class="explore-the-data-sticky-search__search-autocomplete" data-search-autocomplete></ul>
					</div>
				</div>

				<div class="explore-the-data-sticky-search__cta-area">
					<?php
					// Template: Button
					wonder_include_template_file(
						'partials/button.php',
						array(
							'acf' => get_field( 'sticky_bar_action_button' ),
							'class' => 'global-cta global-cta--light-1',
							'size' => 'large',
							'eid_page_slug' => get_field( 'eid_page_slug' ),
							'eid_section_slug' => get_field( 'sticky_bar_eid_section_slug_eid_section_slug' ),
							'attachment' => 'download-ani',
							'attributes' => array(
								'data-ani-action' => 'download',
								'rel' => 'noopener',
							),
						)
					);
					?>
				</div>

			</div>
		</div>

		<main class="global-main" role="main">
			<div></div>

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="hero" class="explore-the-data__section explore-the-data__section--hero">
				<div class="explore-the-data-hero__grid">

					<header class="explore-the-data-hero__header">
						<h1 class="explore-the-data-hero__h1">
							<?php the_field( 'hero_headline' ); ?>
						</h1>

						<p class="explore-the-data-hero__paragraph">
							<?php the_field( 'hero_paragraph' ); ?>
						</p>

						<div class="explore-the-data-hero__search-input" data-search-input-container>

							<div class="explore-the-data-hero__search-selection data-hero__search-selection--selection-1"
								 data-search-selection-1></div>

							<input type="text" name="hero-search" placeholder="Enter a County or Metro" class="explore-the-data-hero__input" autocomplete="off" data-search-input/>

							<ul class="explore-the-data-hero__search-autocomplete" data-search-autocomplete></ul>
						</div>
					</header>

					<div class="explore-the-data-hero__chart explore-the-data-hero__chart--thinking" data-hero-chart>
						<div class="explore-the-data-hero__chart-bars">
							<?php for ( $i = 0; $i < 50; $i++ ) { ?>
								<div class="explore-the-data-hero__chart-bar"
									 data-hero-chart-bar="<?php echo esc_attr( $i ); ?>" style="animation-delay:<?php echo esc_attr( ( $i / 50 ) * 2 ); ?>s">
									<div class="explore-the-data-hero__chart-bar-annotation"
										 data-hero-chart-bar-annotation>
										<span class="explore-the-data-hero__chart-bar-annotation-number"
											  data-hero-chart-bar-annotation-number></span>
										<span class="explore-the-data-hero__chart-bar-annotation-label"
											  data-hero-chart-bar-annotation-label></span>
									</div>
								</div>
							<?php } ?>
						</div>

						<div class="explore-the-data-hero__chart-y-axis">
							<div class="explore-the-data-hero__chart-y-axis-label">
								Number of Counties
							</div>
						</div>

						<div class="explore-the-data-hero__chart-x-axis">
							<div class="explore-the-data-hero__chart-x-axis-label">
								National Low
								<span data-hero-chart-x-axis-label-low-number></span>
							</div>
							<div class="explore-the-data-hero__chart-x-axis-label">
								Microbusinesses per 100 people (Mar 2021)
							</div>
							<div class="explore-the-data-hero__chart-x-axis-label">
								National High
								<span data-hero-chart-x-axis-label-high-number></span>
							</div>
						</div>
					</div>
				</div>
				<?php
				// Template: social-media-nav
				wonder_include_template_file(
					'partials/social-media-nav.php',
					array(
						'section' => 0,
					)
				);
				?>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Logos  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="logos" class="explore-the-data__section explore-the-data__section--logos">
				<div class="explore-the-data-logos__grid">

					<div class="explore-the-data-logos__logos">
						<img src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/data/godaddy.svg"
							 class="explore-the-data-logos__logo" alt="GoDaddy"/>
						<img src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/data/us-census-bureau.svg"
							 class="explore-the-data-logos__logo" alt="U.S. Census Bureau"/>
						<img src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/data/eig.svg"
							 class="explore-the-data-logos__logo" alt="EIG"/>
						<img src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/data/brookings.svg"
							 class="explore-the-data-logos__logo" alt="Brookings"/>
						<img src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/data/opportunity-insights.svg"
							 class="explore-the-data-logos__logo" alt="Opportunity Insights"/>
						<img src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/data/bureau-labor-statistics.svg"
							 class="explore-the-data-logos__logo" alt="Bureau of Labor Statistics"/>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Logos: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Stats ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="stats" class="explore-the-data__section explore-the-data__section--stats">
				<div class="explore-the-data-stats__grid">

					<header class="explore-the-data-stats__header">
						<h2 class="explore-the-data-stats__headline global-headline global-headline--size-2">
							<?php the_field( 'reports_headline' ); ?>
						</h2>
					</header>

					<div class="explore-the-data-stats__featured-stats">
						<?php
						while ( have_rows( 'reports_featured_stats' ) ) {
							the_row();
							?>
							<div class="explore-the-data-featured-stat">
								<div class="explore-the-data-featured-stat__number">
									<span class="explore-the-data-featured-stat__number-container">
										<?php the_sub_field( 'number' ); ?>
									</span>
								</div>

								<div class="explore-the-data-featured-stat__content">
									<h4 class="explore-the-data-featured-stat__headline">
										<?php the_sub_field( 'headline' ); ?>
									</h4>

									<div class="explore-the-data-featured-stat__date-range">
										<?php the_sub_field( 'date_range' ); ?>
									</div>

									<div class="explore-the-data-featured-stat__paragraph">
										<?php the_sub_field( 'paragraph' ); ?>
									</div>
								</div>

								<div class="explore-the-data-featured-stat__attribution">
									<?php

									$img = get_sub_field( 'attribution_icon' );
									$text = get_sub_field( 'attribution_link' ) && isset( get_sub_field( 'attribution_link' )['link_text'] ) ? get_sub_field( 'attribution_link' )['link_text'] : '';

									$img_str = false;
									if ( $img ) {
										// Template: Link
										$img_str = wonder_include_template_file(
											'partials/image.php',
											array(
												'acf' => $img,
											),
											true // <-- returns a string instead of echoing
										);
									}

									// Template: Link
									wonder_include_template_file(
										'partials/link.php',
										array(
											'acf' => get_sub_field( 'attribution_link' ),
											'accessibility_title' => $text,
											'class' => 'explore-the-data-featured-stat__attribution-link',
											'text' => ( $img_str ? $img_str . ' ' : '' ) . $text,
											'eid_page_slug' => get_field( 'eid_page_slug' ),
											'eid_section_slug' => get_field( 'reports_eid_section_slug_eid_section_slug' ),
										)
									);
									?>
								</div>
							</div>
						<?php } ?>
					</div>

					<div class="explore-the-data-stats__button">
						<?php
						// Template: Button
						wonder_include_template_file(
							'partials/button.php',
							array(
								'acf' => get_field( 'reports_button' ),
								'class' => 'global-cta',
								'size' => 'large',
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'reports_eid_section_slug_eid_section_slug' ),
							)
						);
						?>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Stats: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Interactive Map ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="interactive-map" class="explore-the-data__section explore-the-data__section--interactive-map">

				<div class="explore-the-data-interactive-map__grid">
					<div class="explore-the-data-interactive-map__headline">
						<h2 class="global-headline global-headline--size-2">
							<?php the_field( 'explore_headline' ); ?>
						</h2>
					</div>
				</div>

				<div class="explore-the-data-interactive-map__content-grid-container">

					<div class="explore-the-data-interactive-map__map">
						<div id="map" class="explore-the-data-interactive-map__map-canvas"></div>
						<div class="explore-the-data-interactive-map__map-legend">
							<h4 class="explore-the-data-interactive-map__map-legend-headline">
								Microbusiness Density
							</h4>

							<ul class="explore-the-data-interactive-map__map-legend-list">
								<li class="explore-the-data-interactive-map__map-legend-list-item">
									<div class="explore-the-data-interactive-map__map-legend-level explore-the-data-interactive-map__map-legend-level--low">
										0 to 2.9
									</div>
								</li>
								<li class="explore-the-data-interactive-map__map-legend-list-item">
									<div class="explore-the-data-interactive-map__map-legend-level explore-the-data-interactive-map__map-legend-level--average">
										3.0 to 4.9
									</div>
								</li>
								<li class="explore-the-data-interactive-map__map-legend-list-item">
									<div class="explore-the-data-interactive-map__map-legend-level explore-the-data-interactive-map__map-legend-level--high">
										5.0 to 7.9
									</div>
								</li>
								<li class="explore-the-data-interactive-map__map-legend-list-item">
									<div class="explore-the-data-interactive-map__map-legend-level explore-the-data-interactive-map__map-legend-level--very-high">
										8+
									</div>
								</li>
							</ul>
						</div>
					</div>

					<div class="explore-the-data-interactive-map__grid">

						<div class="explore-the-data-interactive-map__scroller">

							<div class="explore-the-data-interactive-map__stats">

								<div class="explore-the-data-interactive-map__stats-location-buttons" data-interactive-map-location-toggles>
									<?php
									$eid = wonder_create_eid_string(
										get_field( 'eid_page_slug' ),
										get_field( 'explore_eid_section_slug_eid_section_slug' ),
										'US Average (County)',
										'click'
									);
									?>
									<a class="explore-the-data-interactive-map__stats-location-button" href="#" data-interactive-map-location-toggle="national_county" title="Show data for U.S. Average (County)" data-eid="<?php echo esc_attr( $eid ); ?>">
										U.S. Average (County)
									</a>
									<?php
									$eid = wonder_create_eid_string(
										get_field( 'eid_page_slug' ),
										get_field( 'explore_eid_section_slug_eid_section_slug' ),
										'US Average (Metro)',
										'click'
									);
									?>
									<a class="explore-the-data-interactive-map__stats-location-button" href="#" data-interactive-map-location-toggle="national_cbsa" title="Show data for U.S. Average (Metro)" data-eid="<?php echo esc_attr( $eid ); ?>">
										U.S. Average (Metro)
									</a>
								</div>

								<h3 class="explore-the-data-interactive-map__stats-headline" data-explore-sub-headline></h3>

								<div class="explore-the-data-interactive-map__stats-subheadline">
									The current average <span data-explore-sub-headline></span> Microbusiness Density is <span data-explore-sub-headline-average></span>.
								</div>

								<hr class="explore-the-data-interactive-map__stats-hr" />

								<div class="explore-the-data-interactive-map__stats-modules">

									<div class="explore-the-data-interactive-map__stats-module explore-the-data-interactive-map__stats-module--unemployment-rate">

										<h4 class="explore-the-data-interactive-map__stats-module-headline">
											Unemployment<br />Rate
										</h4>

										<div class="explore-the-data-interactive-map__stats-module-date-range"><span data-explore-unemployment-rate-date></span></div>

										<div class="explore-the-data-interactive-map__stats-module-number" data-explore-unemployment-number></div>

										<div class="explore-the-data-interactive-map__stats-module-paragraph">
											The average U.S. <span data-explore-venture-density-national-average-type></span> unemployment rate is <span data-explore-unemployment-national-average></span>.
										</div>
									</div>

									<div class="explore-the-data-interactive-map__stats-module explore-the-data-interactive-map__stats-module--venture-density">

										<h4 class="explore-the-data-interactive-map__stats-module-headline">
											Microbusiness<br />Density
										</h4>

										<div class="explore-the-data-interactive-map__stats-module-date-range"><span data-explore-unemployment-rate-date></span></div>

										<div class="explore-the-data-interactive-map__stats-module-number" data-explore-venture-density-number></div>
										<div class="explore-the-data-interactive-map__stats-module-paragraph">
											Measured as microbusinesses per 100 people. The average U.S. <span data-explore-venture-density-national-average-type></span> microbusiness density is <span data-explore-venture-density-national-average></span>.
										</div>
									</div>

									<div class="explore-the-data-interactive-map__stats-module explore-the-data-interactive-map__stats-module--economic-resilience">

										<h4 class="explore-the-data-interactive-map__stats-module-headline">
											Economy<br />Resilience
										</h4>

										<div class="explore-the-data-interactive-map__stats-module-date-range"><span data-explore-unemployment-rate-date></span></div>

										<div class="explore-the-data-interactive-map__stats-module-number" data-explore-economic-resilience-number></div>

										<div class="explore-the-data-interactive-map__stats-module-paragraph">
											Recession Recovery score as measured by the change in Prosperity (2011-2018). The average U.S. <span data-explore-venture-density-national-average-type></span> score is <span data-explore-economic-resilience-national-average></span>.
										</div>
									</div>
								</div>

								<hr class="explore-the-data-interactive-map__stats-hr" />

								<?php if ( get_field( 'explore_stories_grid' ) && count( get_field( 'explore_stories_grid' ) ) ) { ?>
								<h4 class="explore-the-data-interactive-map__stories-headline">
									Profiles and Use Cases
								</h4>

								<div class="explore-the-data-interactive-map__stories-grid">
									<?php foreach ( get_field( 'explore_stories_grid' ) as $story_obj ) { ?>
									<div class="explore-the-data-interactive-map__stories-grid-item">
										<?php
										$eid = wonder_create_eid_string(
											get_field( 'eid_page_slug' ),
											get_field( 'explore_eid_section_slug_eid_section_slug' ),
											'stories/' . get_the_title( $story_obj ),
											'click'
										);
										?>
										<a href="<?php echo esc_url( get_the_permalink( $story_obj ) ); ?>" class="explore-the-data-interactive-map__stories-grid-item-link" title="<?php echo esc_attr( get_the_title( $story_obj ) ); ?>" data-eid="<?php echo esc_attr( $eid ); ?>"></a>
										<?php
										// Image Template
										wonder_include_template_file(
											'partials/image.php',
											array(
												'src' => get_the_post_thumbnail_url( $story_obj, 'small' ),
												'alt' => get_the_title( $story_obj ),
												'size' => 'medium',
												'class' => 'explore-the-data-interactive-map__stories-grid-item-image',
											)
										);
										?>

										<div class="explore-the-data-interactive-map__stories-grid-item-category">
											<?php
											$category = get_the_category( $story_obj );
											echo esc_html( $category[0]->cat_name );
											?>
										</div>

										<p class="explore-the-data-interactive-map__stories-grid-item-paragraph">
											<?php echo esc_html( get_the_excerpt( $story_obj ) ); ?>
										</p>
									</div>
									<?php } ?>
								</div>

								<hr class="explore-the-data-interactive-map__stats-hr explore-the-data-interactive-map__stats-hr--chart" />
								<?php } ?>

								<div class="explore-the-data-interactive-map__chart">
									<h4 class="explore-the-data-interactive-map__chart-headline" data-explore-chart-headline></h4>

									<div class="explore-the-data-interactive-map__chart-table-wrapper">
										<table class="explore-the-data-interactive-map__chart-table global-stats-table" data-explore-chart>
											<tr>
												<th>
													Rank / Location
												</th>
												<th>
													Density
												</th>
											</tr>
										</table>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>

				<?php
				// Template: social-media-nav
				wonder_include_template_file(
					'partials/social-media-nav.php',
					array(
						'section' => 1,
					)
				);
				?>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Interactive Map: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Line Graph ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="line-graph" class="explore-the-data__section explore-the-data__section--line-graph">
				<div class="explore-the-data-line-graph__grid">

					<div class="explore-the-data-line-graph__logos">
						<img src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/data/icons.png" alt="Icons"/>
					</div>

					<div class="explore-the-data-line-graph__text-stack">
						<?php
						// Template: Theme Section Header Text Stack
						wonder_include_template_file(
							'partials/global-section-header-text-stack.php',
							array(
								'headline' => get_field( 'compare_headline' ),
								'paragraph' => get_field( 'compare_paragraph' ),
							)
						);
						?>
					</div>

					<ul class="explore-the-data-line-graph__links">
						<?php
						while ( have_rows( 'compare_links' ) ) {
							the_row();
							?>
						<li>
							<?php
							// Template: Link
							wonder_include_template_file(
								'partials/link.php',
								array(
									'acf' => get_sub_field( 'link' ),
									'class' => 'explore-the-data-line-graph__link',
									'eid_page_slug' => get_field( 'eid_page_slug' ),
									'eid_section_slug' => get_field( 'compare_eid_section_slug_eid_section_slug' ),
								)
							);
							?>
						</li>
						<?php } ?>
					</ul>

					<div class="explore-the-data-line-graph__chart-tools">

						<div class="explore-the-data-line-graph__chart-tools-selector-wrapper">
							<div class="explore-the-data-line-graph__chart-tools-label">
								Compare Microbusiness Density to:
							</div>

							<select class="explore-the-data-line-graph__chart-tools-selector" data-compare-chart-comparison-selector>
								<option>Select a comparison</option>
								<?php foreach ( DATA_PAGE_AVAILABLE_COMPARISONS as $key => $val ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>" data-yaxis-format="<?php echo esc_attr( $val['format'] ); ?>">
									<?php echo esc_html( $val['label'] ); ?>
								</option>
								<?php } ?>
							</select>
						</div>

						<div class="explore-the-data-line-graph__chart-tools-locations">

							<div class="explore-the-data-line-graph__chart-tools-label">
								Selected Locations:
							</div>

							<span data-line-graph-locations-legend>
								<span class="explore-the-data-line-graph__chart-tools-location explore-the-data-line-graph__chart-tools-location--county">
									U.S. Average (County)
								</span>
								<span class="explore-the-data-line-graph__chart-tools-location explore-the-data-line-graph__chart-tools-location--city">
									U.S. Average (Metro)
								</span>
							</span>
						</div>
					</div>

					<div class="explore-the-data-line-graph__chart">

						<div class="explore-the-data-line-graph__chart-legend">
							<div class="explore-the-data-line-graph__chart-legend-item explore-the-data-line-graph__chart-legend-item--vd">
								Microbusiness Density
							</div>
							<div class="explore-the-data-line-graph__chart-legend-item explore-the-data-line-graph__chart-legend-item--comparison" data-line-chart-legend-item-comparison>
								Comparison
							</div>
						</div>

						<canvas id="compare-chart" class="explore-the-data-line-graph__chart-canvas"></canvas>

						<table class="explore-the-data-line-graph__chart-sources">
							<tr>
								<th>
									Variable(s) Shown
								</th>
								<th>
									Description
								</th>
								<th>
									Source
								</th>
							</tr>
							<tr>
								<td>
									Microbusiness Density
								</td>
								<td>
									The number of microbusinesses per 100 people
								</td>
								<td>
									<?php
									// Template: Image
									$img = wonder_include_template_file(
										'partials/image.php',
										array(
											'src' => get_template_directory_uri() . '/assets/imgs/global/circular-source-icon-godaddy.png',
											'alt' => 'GoDaddy',
										),
										true // Return string instead of echo
									);

									// Template: Link
									wonder_include_template_file(
										'partials/link.php',
										array(
											'url' => 'https://godaddy.com/',
											'open_in_new_tab' => true,
											'text' => $img . 'GoDaddy',
											'class' => 'explore-the-data-line-graph__chart-sources-link',
											'accessibility_title' => 'GoDaddy',
											'eid_page_slug' => get_field( 'eid_page_slug' ),
											'eid_section_slug' => get_field( 'compare_eid_section_slug_eid_section_slug' ),
										)
									);
									?>
								</td>
							</tr>
							<?php foreach ( DATA_PAGE_AVAILABLE_COMPARISONS as $key => $comparison_type ) { ?>
							<tr class="explore-the-data-line-graph__chart-sources-row" data-line-graph-comparison-source="<?php echo esc_attr( $key ); ?>">
								<td>
									<?php echo esc_html( $comparison_type['label'] ); ?>
								</td>
								<td>
									<?php echo esc_html( $comparison_type['description'] ); ?>
								</td>
								<td>
									<?php
									// Template: Image
									$img = wonder_include_template_file(
										'partials/image.php',
										array(
											'src' => $comparison_type['source_icon'],
											'alt' => $comparison_type['source'],
										),
										true // Return string instead of echo
									);

									// Template: Link
									wonder_include_template_file(
										'partials/link.php',
										array(
											'url' => $comparison_type['source_url'],
											'open_in_new_tab' => true,
											'text' => $img . $comparison_type['source'],
											'class' => 'explore-the-data-line-graph__chart-sources-link',
											'accessibility_title' => $comparison_type['source'],
											'eid_page_slug' => get_field( 'eid_page_slug' ),
											'eid_section_slug' => get_field( 'compare_eid_section_slug_eid_section_slug' ),
										)
									);
									?>
								</td>
							</tr>
							<?php } ?>
						</table>
					</div>

				</div>
				<?php
				// Template: social-media-nav
				wonder_include_template_file(
					'partials/social-media-nav.php',
					array(
						'section' => 2,
					)
				);
				?>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Line Graph: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Compare the Numbers Graph ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="compare-the-numbers"
					 class="explore-the-data__section explore-the-data__section--compare-the-numbers">
				<div class="explore-the-data-compare-the-numbers__grid">

					<header class="explore-the-data-compare-the-numbers__header">
						<h2 class="explore-the-data-compare-the-numbers__headline">
							<?php the_field( 'at_a_glance_headline' ); ?>
						</h2>

						<div class="explore-the-data-compare-the-numbers__actions">
							<div class="explore-the-data-compare-the-numbers__download-button">
								<?php
								// Template: Button
								// wonder_include_template_file(
								// 'partials/button.php',
								// array(
								// 'acf' => get_field( 'at_a_glance_download_button' ),
								// 'size' => 'large',
								// 'class' => 'global-cta',
								// 'eid_page_slug' => get_field( 'eid_page_slug' ),
								// 'eid_section_slug' => get_field( 'at_a_glance_eid_section_slug_eid_section_slug' ),
								// 'attachment' => 'download-ani',
								// 'attributes' => array(
								// 'data-ani-action' => 'download',
								// 'rel' => 'noopener',
								// ),
								// )
								// );
								?>
							</div>
						</div>
					</header>

					<div class="explore-the-data-compare-the-numbers__table-wrapper">
						<table class="global-stats-table">
							<tr>
								<th>
									Variable
								</th>
								<th class="explore-the-data-compare-the-numbers__table-selection-cell" data-compare-table-selection-column>
									<span data-compare-table-selection-name>-</span>
								</th>
								<th>
									U.S. County Average
								</th>
								<th>
									U.S. Metro Average
								</th>
							</tr>
							<tr>
								<td>
									Microbusiness Density
								</td>
								<td class="explore-the-data-compare-the-numbers__table-selection-cell" data-compare-table-selection-column><span data-compare-table-selection-vd>-</span></td>
								<td><span data-compare-table-county-vd>-</span></td>
								<td><span data-compare-table-cbsa-vd>-</span></td>
							</tr>
							<tr>
								<td>
									Highly Active Microbusiness Density
								</td>
								<td class="explore-the-data-compare-the-numbers__table-selection-cell" data-compare-table-selection-column><span data-compare-table-selection-havd>-</span></td>
								<td><span data-compare-table-county-havd>-</span></td>
								<td><span data-compare-table-cbsa-havd>-</span></td>
							</tr>
							<tr>
								<td>
									Recession Recovery
								</td>
								<td class="explore-the-data-compare-the-numbers__table-selection-cell" data-compare-table-selection-column><span data-compare-table-selection-recession-recovery>-</span></td>
								<td><span data-compare-table-county-recession-recovery>-</span></td>
								<td><span data-compare-table-cbsa-recession-recovery>-</span></td>
							</tr>
							<tr>
								<td>
									Unemployment
								</td>
								<td class="explore-the-data-compare-the-numbers__table-selection-cell" data-compare-table-selection-column><span data-compare-table-selection-unemployment>-</span></td>
								<td><span data-compare-table-county-unemployment>-</span></td>
								<td><span data-compare-table-cbsa-unemployment>-</span></td>
							</tr>
							<tr>
								<td>
									Change in Household Median Income (2016-2019)
								</td>
								<td class="explore-the-data-compare-the-numbers__table-selection-cell" data-compare-table-selection-column><span data-compare-table-selection-median-income>-</span></td>
								<td><span data-compare-table-county-median-income>-</span></td>
								<td><span data-compare-table-cbsa-median-income>-</span></td>
							</tr>
<!-- 							<tr>
								<td>
									Microbusiness Index
								</td>
								<td class="explore-the-data-compare-the-numbers__table-selection-cell" data-compare-table-selection-column><span data-compare-table-selection-activity-index>-</span></td>
								<td><span data-compare-table-county-activity-index>-</span></td>
								<td><span data-compare-table-cbsa-activity-index>-</span></td>
							</tr> -->
						</table>
					</div>
				</div>
				<?php
				// Template: social-media-nav
				wonder_include_template_file(
					'partials/social-media-nav.php',
					array(
						'section' => 3,
						'sectionYOverride' => 'adjust-y',
					)
				);
				?>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Compare the Numbers Graph: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<section id="community-spotlight" class="explore-the-data__section explore-the-data__section--community-spotlight">

				<div class="explore-the-data-community-spotlight__grid">
					<?php
					// Template: Horizontal Section Header
					wonder_include_template_file(
						'partials/global-horizontal-section-header.php',
						array(
							'acf' => get_field( 'featured_stories_horizontal_header' ),
							'attachment' => 'right_arrow',
							'class' => 'global-cta global-cta--style-2',
							'eid_page_slug' => get_field( 'eid_page_slug' ),
							'eid_section_slug' => get_field( 'featured_stories_eid_section_slug_eid_section_slug' ),
						)
					);
					?>

					<?php
					// Template: Featured Stories Slider
					wonder_include_template_file(
						'partials/global-horizontal-carousel/global-horizontal-carousel--slider.php',
						array(
							'acf' => get_field( 'featured_stories_featured_stories_slider' ),
							'slider_id' => isset( $slider_id ) ? $slider_id : 'slider',
							'eid_page_slug' => get_field( 'eid_page_slug' ),
							'eid_section_slug' => get_field( 'featured_stories_eid_section_slug_eid_section_slug' ),
						)
					);
					?>
				</div>
			</section>
		</main>
		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Main: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		<?php
	endwhile;
endif;
?>

<?php
wp_localize_script(
	'global',
	'data_vars',
	array(
		'eid_page_slug' => get_field( 'eid_page_slug' ),
		'prefill_county' => $prefill_county['county'],
		'vf_get_compare_chart_for_data_page' => vf_get_compare_chart_for_data_page(),
		'vf_get_compare_table_data_for_data_page' => vf_get_compare_table_data_for_data_page(),
		'vf_get_data_page_explore_section_data' => vf_get_data_page_explore_section_data(),
		// 'vf_get_data_page_hero_chart_data' => vf_get_data_page_hero_chart_data(),
		'vf_get_all_locations' => vf_get_all_locations(),
		'data_interactive_map_locations_selection_1_eid_prefix' => wonder_create_eid_string_prefix( get_field( 'eid_page_slug' ), get_field( 'compare_eid_section_slug_eid_section_slug' ) ),
		'data_interactive_map_locations_selection_1_eid_suffix' => wonder_create_eid_string_suffix( 'click' ),
	)
);

get_footer(); ?>
