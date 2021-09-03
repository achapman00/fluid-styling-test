<?php
/**
 * Template Name: About Page Template
 * Description: A custom about Page template.
 *
 * @package Wonderpress Theme
 */

// Set the <body> id
wonder_body_id( 'about' );

get_header();
?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<main class="global-main" role="main">

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="hero" class="about__section about-hero__section--hero">
				<div class="about-hero__grid">
					<header class="about-hero__header">
						<h1 class="about-hero__h1"><?php the_field( 'hero_headline' ); ?></h1>
						<p role="doc-subtitle"><?php the_field( 'hero_paragraph' ); ?></p>
					</header>

					<section id="methodology" class="about-hero-methodology__section">
						<h2 class="about-hero__h2"><?php the_field( 'methodology_headline' ); ?></h2>
						<div class="about-hero-methodology__body"><?php the_field( 'methodology_content' ); ?></div>
					</section>

					<section id="video-section" class="about-hero-video__section">
						<h3 class="about-hero-video__h3">
							<span></span><span><?php the_field( 'methodology_videos_headline' ); ?></span></h3>

						<?php
						while ( have_rows( 'methodology_videos' ) ) {
							the_row();
							$vid_id = esc_attr( get_sub_field( 'vimeo_id' ) );
							$eid = wonder_create_eid_string(
								get_field( 'eid_page_slug' ),
								get_field( 'methodology_eid_section_slug_eid_section_slug' ),
								'vimeo-' . get_sub_field( 'vimeo_id' ),
								'click'
							);
							?>
							<button id='thumbnail_large'
									class="about-hero-video__thumbnail about-hero-video__thumbnail--large"
									data-eid="<?php echo esc_attr( $eid ); ?>"
									data-video="<?php echo esc_attr( get_sub_field( 'vimeo_id' ) ); ?>"
									data-disabled="0">
								<img alt="Video thumbnail"
									 src="<?php echo esc_url( get_sub_field( 'placeholder_image' )['sizes']['medium'] ); ?>"
									 class="about-hero-video__thumbnail_img">
								<img alt="Play icon"
									 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/about/godaddy-venture-forward_play_25x19_ffffff-on-trans.svg"
									 class="about-hero-video__play_img">
								<span class="global-screen-reader-copy">Play video in Vimeo video player with ID: <?php echo esc_html( $vid_id ); ?></span>
							</button>

							<?php
							break;
						}
						reset_rows();
						?>

						<div id="thumbnails" class="about-hero-video__poster-thumbnails">
							<?php
							$c = 0;
							while ( have_rows( 'methodology_videos' ) ) {
								the_row();
								$vid_id = esc_attr( get_sub_field( 'vimeo_id' ) );
								$eid = wonder_create_eid_string(
									get_field( 'eid_page_slug' ),
									get_field( 'methodology_eid_section_slug_eid_section_slug' ),
									'vimeo-' . get_sub_field( 'vimeo_id' ),
									'click'
								);
								?>
								<button class="about-hero-video__thumbnail"
										data-eid="<?php echo esc_attr( $eid ); ?>"
										data-video="<?php echo esc_html( $vid_id ); ?>"
										data-disabled="<?php echo esc_attr( 0 == $c ? 1 : 0 ); ?>">
									<img alt="Video thumbnail"
										 src="<?php echo esc_url( get_sub_field( 'placeholder_image' )['sizes']['medium'] ); ?>"
										 class="about-hero-video__thumbnail_img">
									<div class="global-stories-hero__bkgd-img-shade"></div>
									<span class="global-screen-reader-copy">Play video in Vimeo video player with ID: <?php echo esc_html( $vid_id ); ?></span>
								</button>
								<?php
								$c++;
							}
							?>
						</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

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

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ faq ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="faq" class="about__section about__section--faq">
				<div class="about-faq__grid">
					<h2 class="about__h2"><?php the_field( 'questions_headline' ); ?></h2>
					<div class="about-faq__body">
						<?php
						$iter_x = 1;
						while ( have_rows( 'questions_questions' ) ) {
							the_row();
							?>
							<input type="checkbox" id="toggle<?php echo esc_html( $iter_x ); ?>"
								   class="about-faq__checkbox"/>
							<label for="toggle<?php echo esc_html( $iter_x ); ?>"
								   class="about-faq__question"><?php the_sub_field( 'question' ); ?></label>
							<div class="about-faq__answer"><?php the_sub_field( 'answer' ); ?></div>
							<!-- ▩▩▩▩▩▩▩▩▩▩▩▩▩▩ question + answer: <?php echo esc_html( $iter_x ); ?> ▩▩▩▩▩▩▩▩▩▩▩▩▩▩ -->
							<?php
							$iter_x++;
						}
						?>
					</div>
					<!-- ▩▩ CTA ▩▩ -->
					<div class="global-cta-area">
						<?php
						// Template: Button
						wonder_include_template_file(
							'partials/button.php',
							array(
								'acf' => get_field( 'questions_button' ),
								// 'text' => 'Download test',
								'class' => 'global-cta',
								'attachment' => 'download-ani',
								'attributes' => array(
									'data-ani-action' => 'download',
								),
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'questions_eid_section_slug_eid_section_slug' ),
							)
						);
						?>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ faq: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ partner-orgs ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="partner-orgs" class="about__section about__section--partner-orgs">
				<div class="about-partner-orgs__grid">
					<h2 class="about__h2"><?php the_field( 'partners_headline' ); ?></h2>
					<div class="about-partner-orgs__orgs-container">
						<?php
						while ( have_rows( 'partners_partnerships' ) ) {
							the_row();
							?>
							<div class="about-partner-orgs__org">
								<?php
								wonder_include_template_file(
									'partials/link.php',
									array(
										'acf' => get_sub_field( 'link' ),
										'class' => 'about-partner-orgs__org-a',
										'eid_page_slug' => get_field( 'eid_page_slug' ),
										'eid_section_slug' => get_field( 'partners_eid_section_slug_eid_section_slug' ),
										'attributes' => array(
											'rel' => 'noopener',
										),

									)
								);
								?>
								<p><?php the_sub_field( 'paragraph' ); ?></p>
							</div>
						<?php } ?>
					</div>
					<div class="global-cta-area">
						<?php
						wonder_include_template_file(
							'partials/button.php',
							array(
								'acf' => get_field( 'partners_button' ),
								'class' => 'global-cta',
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'partners_eid_section_slug_eid_section_slug' ),
							)
						);
						?>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ partner-orgs: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ contact-us ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="contact-us" class="about__section about__section--contact-us">
				<div class="about-contact-us__grid">
					<h2 class="about__h2"><?php the_field( 'contact_headline' ); ?></h2>
					<p class="about-contact-us__body">
						<?php the_field( 'contact_paragraph' ); ?>
					</p>
					<div class="global-cta-area">
						<?php
						wonder_include_template_file(
							'partials/button.php',
							array(
								'acf' => get_field( 'contact_button' ),
								'class' => 'global-cta global-cta--style-1',
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'contact_eid_section_slug_eid_section_slug' ),
							)
						);
						?>

					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ contact-us: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

		</main>

		<?php
	endwhile;
endif;
?>

<?php get_footer(); ?>
