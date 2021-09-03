<?php
/**
 * Template Name: Home Page Template
 * Description: A custom Home Page template.
 *
 * @package Wonderpress Theme
 */

// If the browser is unsupported, redirect
wonder_enforce_unsupported_browser();

// Set the <body> id
wonder_body_id( 'home' );

get_header();
?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>

		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Main ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		<main class="global-main" role="main">

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Banner ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<header class="home-banner">
				<div class="home-banner__grid">
					<div>
						<h1><?php the_field( 'hero_banner_label' ); ?></h1>
						<span></span>
						<h2><?php the_field( 'hero_banner_copy' ); ?></h2>
					</div>
				</div>
			</header>

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="hero" class="home__section home__section--hero">
				<div id='map'></div>
				<div class="home-hero-blades">
					<div id="blades" class="home-hero-blades__map-layer home-hero-blades__map-layer--desktop">
						<?php
						$c = 0;
						while ( have_rows( 'hero_cards' ) ) {
							the_row();
							$c++;
							?>
							<div class="home-hero-blades__blade">
								<div class='home-hero-blades__card'>
									<?php
									switch ( get_sub_field( 'type' ) ) {

										case 'text':
											// 3/24/21 Removed ability to show 'paragraph' below as per QA
											?>
											<section class='home-hero-blades__card-section home-hero-blades__card-section--headline' data-type="text">
												<h2><?php the_sub_field( 'headline' ); ?></h2>
											</section>

											<?php if ( 1 === $c ) { ?>
												<section class='home-hero-blades__card-section home-hero-blades__card-section--vf-statement' data-type="text">
													<p><?php the_field( 'hero_banner_copy' ); ?></p>
												</section>
											<?php } ?>

											<section class='home-hero-blades__card-section home-hero-blades__card-section--unique' data-type="text">
												<div class="home-hero-blades__card-stats-module" data-pos="left">
													<h3><?php the_sub_field( 'stat_1_headline' ); ?></h3>
													<span><?php the_sub_field( 'stat_1_number' ); ?></span>
													<p><?php the_sub_field( 'stat_1_paragraph' ); ?></p>
												</div>
												<div class="home-hero-blades__card-stats-module" data-pos="right">
													<h3><?php the_sub_field( 'stat_2_headline' ); ?></h3>
													<span><?php the_sub_field( 'stat_2_number' ); ?></span>
													<p><?php the_sub_field( 'stat_2_paragraph' ); ?></p>
												</div>
												<?php
												// Show Venture Density legend?
												if ( get_sub_field( 'show_venture_density_legend' ) ) {
													?>
													<div class="home-hero-blades__card-density-legend">
														<h4>Microbusiness Density</h4>
														<div>0 to 2.9</div>
														<div>3.0 to 4.9</div>
														<div>5.0 to 7.9</div>
														<div>8+</div>
													</div>
												<?php } ?>
											</section>
											<?php
											break;
										default:
											?>
											<section class='home-hero-blades__card-section home-hero-blades__card-section--headline' data-type="img">
												<?php
												wonder_include_template_file(
													'partials/image.php',
													array(
														'acf' => get_sub_field( 'image' ),
													)
												);
												?>
											</section>

											<section class='home-hero-blades__card-section  home-hero-blades__card-section--unique' data-type="img">
												<h2><?php the_sub_field( 'headline' ); ?></h2>
												<p><?php the_sub_field( 'paragraph' ); ?></p>
											</section>
										<?php } ?>

									<div class="global-cta-area">
										<?php
										wonder_include_template_file(
											'partials/button.php',
											array(
												'acf' => get_sub_field( 'button' ),
												'attachment' => 'right_arrow',
												'class' => 'global-cta',
												'eid_page_slug' => get_field( 'eid_page_slug' ),
												'eid_section_slug' => get_field( 'hero_eid_section_slug_eid_section_slug' ),
											)
										);
										?>
									</div>
								</div>
							</div>
						<?php } ?>

						<button id="home-hero-map-layer-up_btn" class="global-direction-controls__button"
								aria-controls="map blades" aria-label="Up" disabled>
							<img alt=""
								 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-carousel_arrow_44x44_ffffff-on-trans.svg">
						</button>
						<button id="home-hero-map-layer-down_btn" class="global-direction-controls__button"
								aria-controls="map blades" aria-label="Down">
							<span></span>
							<img alt=""
								 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-carousel_arrow_44x44_ffffff-on-trans.svg">
						</button>
					</div>
					<div id="blades-on-mobile" class="home-hero-blades__map-layer" data-type="mobile-tablet">
						<?php
						$c = 0;
						while ( have_rows( 'hero_cards' ) ) {
							the_row();
							$c++;
							?>
							<div class="home-hero-blades__blade mobile">
								<div class='home-hero-blades__card'>
									<?php
									switch ( get_sub_field( 'type' ) ) {

										case 'text':
											// 3/24/21 Removed ability to show 'paragraph' below as per QA
											?>
											<section class='home-hero-blades__card-section home-hero-blades__card-section--headline' data-type="text">
												<h2><?php the_sub_field( 'headline' ); ?></h2>
											</section>

											<?php if ( 1 === $c ) { ?>
											<section class='home-hero-blades__card-section home-hero-blades__card-section--vf-statement' data-type="text">
												<p><?php the_field( 'hero_banner_copy' ); ?></p>
											</section>
										<?php } ?>

											<section class='home-hero-blades__card-section home-hero-blades__card-section--unique' data-type="text">
												<div class="home-hero-blades__card-stats-module" data-pos="left">
													<h3><?php the_sub_field( 'stat_1_headline' ); ?></h3>
													<span><?php the_sub_field( 'stat_1_number' ); ?></span>
													<p><?php the_sub_field( 'stat_1_paragraph' ); ?></p>
												</div>
												<div class="home-hero-blades__card-stats-module" data-pos="right">
													<h3><?php the_sub_field( 'stat_2_headline' ); ?></h3>
													<span><?php the_sub_field( 'stat_2_number' ); ?></span>
													<p><?php the_sub_field( 'stat_2_paragraph' ); ?></p>
												</div>
												<?php
												// Show Venture Density legend?
												if ( get_sub_field( 'show_venture_density_legend' ) ) {
													?>
													<div class="home-hero-blades__card-density-legend">
														<h4>Microbusiness Density</h4>
														<div>0 to 2.9</div>
														<div>3.0 to 4.9</div>
														<div>5.0 to 7.9</div>
														<div>8+</div>
													</div>
												<?php } ?>
											</section>
											<?php
											break;
										default:
											?>
											<section class='home-hero-blades__card-section home-hero-blades__card-section--headline' data-type="img">
												<?php
												wonder_include_template_file(
													'partials/image.php',
													array(
														'acf' => get_sub_field( 'image' ),
													)
												);
												?>
											</section>

											<section class='home-hero-blades__card-section  home-hero-blades__card-section--unique' data-type="img">
												<h2><?php the_sub_field( 'headline' ); ?></h2>
												<p><?php the_sub_field( 'paragraph' ); ?></p>
											</section>
										<?php } ?>

									<div class="global-cta-area">
										<?php
										wonder_include_template_file(
											'partials/button.php',
											array(
												'acf' => get_sub_field( 'button' ),
												'attachment' => 'right_arrow',
												'class' => 'global-cta',
												'eid_page_slug' => get_field( 'eid_page_slug' ),
												'eid_section_slug' => get_field( 'hero_eid_section_slug_eid_section_slug' ),
											)
										);
										?>
									</div>
								</div>
							</div>
						<?php } ?>

						<button id="home-hero-map-layer-left_btn" class="global-direction-controls__button"
								aria-controls="map blades" aria-label="Left" disabled>
							<img alt=""
								 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-carousel_arrow_44x44_ffffff-on-trans.svg">
						</button>
						<button id="home-hero-map-layer-right_btn" class="global-direction-controls__button"
								aria-controls="map blades" aria-label="Right">
							<span></span>
							<img alt=""
								 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-carousel_arrow_44x44_ffffff-on-trans.svg">
						</button>
					</div>
					<div id="map-legend" class="explore-the-data-interactive-map__map-legend explore-the-data-interactive-map__map-legend--home">
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
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<?php
			// Template: Featured Stories Slider
			wonder_include_template_file(
				'partials/global-horizontal-carousel/global-horizontal-carousel.php',
				array(
					'section_id' => 'story-carousel',
					'slider_id' => 'slider',
					'eid_page_slug' => get_field( 'eid_page_slug' ),
					'eid_section_slug' => get_field( 'community_spotlight_eid_section_slug_eid_section_slug' ),
				)
			);
			?>

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ featured-articles ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="featured-articles" class="global-featured-articles">
				<div class="global-featured-articles__grid">
					<?php
					while ( have_rows( 'articles_articles' ) ) {
						the_row();
						$eid = wonder_create_eid_string(
							get_field( 'eid_page_slug' ),
							get_field( 'articles_eid_section_slug_eid_section_slug' ),
							get_sub_field( 'title' ),
							'click'
						);
						?>
						<a href="<?php the_sub_field( 'url' ); ?>" class="global-featured-articles__a" title="<?php echo esc_attr( get_sub_field( 'title' ) ); ?>" data-eid="<?php echo esc_attr( $eid ); ?>" target="_blank" rel="noopener">
							<?php
							// Template: Image
							wonder_include_template_file(
								'partials/image.php',
								array(
									'acf' => get_sub_field( 'logo' ),
									'alt' => get_sub_field( 'title' ),
									'attributes' => array(
										'width' => '84',
										'height' => '84',
									),
								)
							);
							?>
							<span>
								<div><?php the_sub_field( 'date' ); ?></div>
								<div><?php the_sub_field( 'title' ); ?></div>
							</span>
						</a>
					<?php } ?>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ featured-articles: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Case Studies ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="case-studies" class="home__section home__section--case-studies">
				<div class="home-case-studies__grid">
					<header class="home-case-studies__card home-case-studies__card--header">
						<h2><?php the_field( 'ventures_headline' ); ?></h2>
						<p><?php the_field( 'ventures_paragraph' ); ?></p>
					</header>
					<?php
					$card_iteration = 0;
					$card_type = array( 1, 2, 2, 1 );
					foreach ( get_field( 'ventures_featured_ventures' ) as $venture_obj ) {
						?>
						<?php if ( 2 !== $card_iteration ) { ?>
							<a data-eid="
							<?php
							echo esc_attr(
								wonder_create_eid_string(
									get_field( 'eid_page_slug' ),
									get_field( 'ventures_eid_section_slug_eid_section_slug' ),
									get_the_title( $venture_obj ),
									'click'
								)
							);
							?>
							" href="<?php echo esc_url( get_permalink( $venture_obj ) ); ?>" class="home-case-studies__card home-case-studies__card--<?php echo esc_attr( $card_type[ $card_iteration ] ); ?>">
								<span class="home-case-studies__card-span">
								<?php
								// Template: Image
								wonder_include_template_file(
									'partials/image.php',
									array(
										'src' => get_the_post_thumbnail_url( $venture_obj, 'large' ),
										'alt' => get_the_title( $venture_obj ),
										'srcset' => get_all_post_thumbnail_urls( $venture_obj ),
									)
								);
								?>
								</span>
								<div class="home-case-studies__inner">
									<h3><?php echo esc_html( get_the_title( $venture_obj ) ); ?></h3>
									<p><?php echo esc_html( get_field( 'venture_url', $venture_obj ) ); ?></p>
									<p><?php echo esc_html( get_the_excerpt( $venture_obj ) ); ?></p>
								</div>
							</a>
						<?php } else { ?>
							<div class="home-case-studies__card home-case-studies__card--3">
								<?php $basic_stats = get_field( 'ventures_basic_stats' ); ?>
								<header>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 40">
										<g>
											<path fill="#00C2FF" class="st0" d="M0,26.6c0-0.7,0.6-1.3,1.3-1.3H7c0.7,0,1.3,0.6,1.3,1.3V40H0V26.6z"/>
											<path fill="#00C2FF" class="st0" d="M10.4,14c0-0.7,0.6-1.3,1.3-1.3h5.7c0.7,0,1.3,0.6,1.3,1.3v26h-8.3V14z"/>
											<path fill="#00C2FF" class="st0" d="M20.8,20.3c0-0.7,0.6-1.3,1.3-1.3h5.7c0.7,0,1.3,0.6,1.3,1.3V40h-8.3V20.3z"/>
											<path fill="#00C2FF" class="st0" d="M31.2,9.8c0-0.7,0.6-1.3,1.3-1.3h5.7c0.7,0,1.3,0.6,1.3,1.3V40h-8.3V9.8z"/>
											<path fill="#00C2FF" class="st0" d="M41.7,1.3C41.7,0.6,42.3,0,43,0h5.7C49.4,0,50,0.6,50,1.3V40h-8.3V1.3z"/>
										</g>
									</svg>
									<h3><?php echo esc_html( $basic_stats['headline'] ); ?></h3>
								</header>

								<ul>
									<?php foreach ( $basic_stats['stats'] as $stat ) { ?>
										<li>
											<div class="home-case-studies__stats-label"><?php echo esc_html( $stat['label'] ); ?></div>
											<div class="home-case-studies__stats-number"><?php echo esc_html( $stat['value'] ); ?></div>
										</li>
									<?php } ?>
								</ul>
								<div class="global-cta-area">
									<?php
									wonder_include_template_file(
										'partials/button.php',
										array(
											'acf' => $basic_stats['button'],
											'attachment' => 'right_arrow',
											'attrs' => array(),
											'class' => 'global-cta',
											'eid_page_slug' => get_field( 'eid_page_slug' ),
											'eid_section_slug' => get_field( 'ventures_eid_section_slug_eid_section_slug' ),
										)
									);
									?>
								</div>
							</div>
							<a data-eid="
							<?php
							echo esc_attr(
								wonder_create_eid_string(
									get_field( 'eid_page_slug' ),
									get_field( 'ventures_eid_section_slug_eid_section_slug' ),
									get_the_title( $venture_obj ),
									'click'
								)
							);
							?>
							" href="<?php echo esc_url( get_permalink( $venture_obj ) ); ?>" class="home-case-studies__card home-case-studies__card--<?php echo esc_attr( $card_type[ $card_iteration ] ); ?>">
								<span class="home-case-studies__card-span">
								<?php
								// Template: Image
								wonder_include_template_file(
									'partials/image.php',
									array(
										'src' => get_the_post_thumbnail_url( $venture_obj, 'large' ),
										'alt' => get_the_title( $venture_obj ),
										'srcset' => get_all_post_thumbnail_urls( $venture_obj ),
									)
								);
								?>
								</span>
								<div class="home-case-studies__inner">
									<h3><?php echo esc_html( get_the_title( $venture_obj ) ); ?></h3>
									<p><?php echo esc_html( get_field( 'venture_url', $venture_obj ) ); ?></p>
									<p><?php echo esc_html( get_the_excerpt( $venture_obj ) ); ?></p>
								</div>
							</a>
							<?php
						}
						$card_iteration++;
						?>
						<?php
					}
					?>

				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Case Studies: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Data Summary ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="data-summary" class="home__section home__section--data-summary">
				<div class="home-data-summary__grid">

					<header class="home-data-summary__card home-data-summary__card--header">
						<h2><?php the_field( 'highlighted_reports_headline' ); ?></h2>
						<p><?php the_field( 'highlighted_reports_paragraph' ); ?></p>
					</header>

					<?php $module = get_field( 'highlighted_reports_top_industries' ); ?>
					<div class="home-data-summary__card home-data-summary__card--1">
						<h3><?php echo esc_html( $module['headline'] ); ?></h3>

						<table>
							<thead>
								<tr>
									<th style="text-align: left">
										<?php echo esc_html( $module['column_1_header'] ); ?>
									</th>
									<th>
										<?php echo esc_html( $module['column_2_header'] ); ?>
									</th>
									<th>
										<?php echo esc_html( $module['column_3_header'] ); ?>
									</th>
								</tr>
							</thead>
							<?php foreach ( $module['rows'] as $row ) { ?>
								<tr>
									<td class="home-data-summary__column-header">
										<?php echo esc_html( $row['column_1_value'] ); ?>
									</td>
									<td>
										<span class="home-data-summary__column-value home-data-summary__column-value--<?php echo( $row['column_2_value'] > 0 ? 'positive' : 'negative' ); ?>">
											<?php echo esc_html( $row['column_2_value'] ); ?>%
										</span>
									</td>
									<td>
										<span class="home-data-summary__column-value home-data-summary__column-value--<?php echo( $row['column_3_value'] > 0 ? 'positive' : 'negative' ); ?>">
											<?php echo esc_html( $row['column_3_value'] ); ?>%
										</span>
									</td>
								</tr>
							<?php } ?>
						</table>

						<footer class="home-data-summary__card-footer"><?php echo esc_html( $module['footnote'] ); ?></footer>
					</div>

					<?php
					// HIGHLIGHTED REPORTS: TOP NEEDS
					?>
					<div class="home-data-summary__card home-data-summary__card--3">
						<?php
						while ( have_rows( 'highlighted_reports_top_needs' ) ) {
							the_row();
							?>
							<h3><?php the_sub_field( 'headline' ); ?></h3>

							<ul class="home-data-summary__top-needs-list">
								<?php
								while ( have_rows( 'rows' ) ) {
									the_row();
									?>
									<li class="home-data-summary__top-needs-list-item">
										<div class="home-data-summary__top-needs-percentage-bar"
											 style="width:<?php echo esc_attr( the_sub_field( 'percentage' ) ); ?>%"></div>
										<span class="home-data-summary__top-needs-percentage"><?php the_sub_field( 'percentage' ); ?>%</span>
										<div class="home-data-summary__top-needs-label"><?php the_sub_field( 'label' ); ?></div>
									</li>
								<?php } ?>
							</ul>

						<?php } ?>
					</div>

					<div class="home-data-summary__card home-data-summary__card--2">
						<?php
						while ( have_rows( 'highlighted_reports_downloads' ) ) {
							the_row();
							?>
							<h3><?php the_sub_field( 'headline' ); ?></h3>

							<div>
								<ul>
									<?php
									while ( have_rows( 'downloads' ) ) {
										the_row();
										?>
										<li>
											<div class="home-data-summary__downloads-logo-label-unit">
												<?php
												// Template: Button
												wonder_include_template_file(
													'partials/image.php',
													array(
														'acf' => get_sub_field( 'icon' ),
														'class' => 'home-data-summary__downloads-img',
													)
												);
												?>

												<div class="home-data-summary__downloads-label"><?php the_sub_field( 'label' ); ?></div>
											</div>


											<?php
											// Button Template
											wonder_include_template_file(
												'partials/button.php',
												array(
													'acf' => get_sub_field( 'button' ),
													'class' => 'global-cta global-cta--style-3',
													'eid_page_slug' => get_field( 'eid_page_slug' ),
													'eid_section_slug' => get_field( 'highlighted_reports_eid_section_slug_eid_section_slug' ),
													'url' => wp_get_attachment_url( get_sub_field( 'button' )['url'] ),
													'attachment' => 'download-ani',
													'attributes' => array(
														'data-ani-action' => 'download',
													),
												)
											);
											?>
										</li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>
					</div>

				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Data Summary: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Testimonial  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="testimonial" class="home__section home__section--testimonial">
				<div class="home-testimonial__grid">
					<?php $case_study_id = get_field( 'testimonial_post' ); ?>
					<blockquote class="home-testimonial__blockquote">
						<span><?php the_field( 'testimonial_quote_part_1', $case_study_id ); ?>”</span>
						<?php
						/*
						<span><?php the_field( 'testimonial_quote_part_2', $case_study_id ); ?></span>
						*/
						?>
						<cite>
							<?php
							// Template: Image
							wonder_include_template_file(
								'partials/image.php',
								array(
									'acf' => get_field( 'contact_image', $case_study_id ),
									'class' => 'home-testimonial__citation-img',
								)
							);
							?>
							<span>
								<div class="home-testimonial__citation-name"><?php the_field( 'contact_name', $case_study_id ); ?></div>
								<div class="home-testimonial__citation-title"><?php the_field( 'contact_title', $case_study_id ); ?></div>
							</span>
						</cite>
					</blockquote>

					<div class="global-cta-area">
						<?php
						// Template: Button
						wonder_include_template_file(
							'partials/button.php',
							array(
								'acf' => get_field( 'testimonial_button' ),
								// 'attachment' => 'right_arrow',
								'class' => 'global-cta global-cta--style-3',
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'testimonial_eid_section_slug_eid_section_slug' ),
							)
						);
						?>
					</div>

				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Testimonial: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<?php
			// Template: --stories-and-use-cases
			wonder_include_template_file(
				'partials/global-featured-stories-grid-row-section.php',
				array(
					'acf' => get_field( 'featured_stories_featured_stories_grid_row' ),
					'header_acf' => get_field( 'featured_stories_horizontal_header' ),
					'eid_page_slug' => get_field( 'eid_page_slug' ),
					'eid_section_slug' => get_field( 'featured_stories_eid_section_slug_eid_section_slug' ),
				)
			);
			?>

		</main>
		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Main: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		<?php
	endwhile;
endif;
?>

<?php
/**
 * Get all the various steps for the map and
 * output has a global javascript object for this page.
 * JavaScript variable: JSON.parse(home_vars.map_steps);
 */
$steps = array();
while ( have_rows( 'hero_cards' ) ) {
	the_row();
	while ( have_rows( 'map_settings' ) ) {
		the_row();

		$coords = get_sub_field( 'coordinates' );

		$steps[] = array(
			'type' => 'Feature',
			'animation' => array(
				'duration' => get_sub_field( 'animation_duration' ),
				'easing' => 'easeInOutCirc',
			),
			'camera' => array(
				'bearing' => get_sub_field( 'camera_bearing' ),
				'pitch' => get_sub_field( 'camera_pitch' ),
				'zoom' => get_sub_field( 'camera_zoom' ),
			),
			'geometry' => array(
				'type' => 'Point',
				'coordinates' => array(
					$coords['lng'],
					$coords['lat'],
				),
			),
		);
	}
}

wp_localize_script(
	'global',
	'home_vars',
	array(
		'map_steps' => json_encode( $steps ),
		'vf_get_all_locations' => vf_get_all_locations(),
	)
);
?>
<?php get_footer(); ?>
