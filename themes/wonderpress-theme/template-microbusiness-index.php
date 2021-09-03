<?php
/**
 * Template Name: Microbusiness Index Page Template
 * Description: A custom Microbusiness Index Page template.
 *
 * @package Wonderpress Theme
 */

global $vf_data_ingestor_county_index_data_table_name;
global $wpdb;

// Get the mix and max activity_index
$national_activity_index_stats = vf_get_national_activity_index_stats();
$avg_activity_index = $national_activity_index_stats['avg'];
$max_activity_index = $national_activity_index_stats['max'];
$min_activity_index = $national_activity_index_stats['min'];

// Set the <body> id
wonder_body_id( 'microbusiness-index' );

get_header();
?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<main class="global-main" role="main">

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="hero" class="microbusiness-index__section microbusiness-index__section--hero">
				<div class="microbusiness-index-hero__grid">
					<header class="microbusiness-index-hero__header">
						<h1 class="microbusiness-index__h1"><?php the_field( 'hero_headline' ); ?></h1>
					</header>
					<div class="microbusiness-index-hero__body">
						<?php the_field( 'hero_paragraph' ); ?>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Overview ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="overview" class="microbusiness-index__section microbusiness-index__section--overview">
				<div class="microbusiness-index-overview__grid">
					<h2 class="microbusiness-index-overview__h2"><?php the_field( 'overview_preheadline' ); ?></h2>
					<p class="microbusiness-index-overview__body"><?php the_field( 'overview_headline' ); ?></p>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Overview: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ three sub-indexes ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="three-sub-indexes" class="microbusiness-index__section microbusiness-index__section--three-sub-indexes">
				<div class="microbusiness-index-three-sub-indexes__grid">
					<div id="indexes-cont" class="microbusiness-index-three-sub-indexes__indexes">

						<!-- ••••••• H2 container ••••••• -->
						<div id="three-sub-index__1" class="microbusiness-index-three-sub-indexes__h2-cont">
							<div class="microbusiness-index-three-sub-indexes__h2-cont-shade">
								<svg id="microbusiness-index-three-sub-indexes__path-1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 71">
									<line class="microbusiness-index-three-sub-indexes__line-vert" x1="4.5" y1="4.5" x2="4.5" y2="66.5" style="fill:none;stroke:#004249;stroke-linecap:round;stroke-miterlimit:10;stroke-width:9px"/>
								</svg>
							</div>
							<h2 class="microbusiness-index-three-sub-indexes__h2"><?php the_field( 'subindex_headline_1' ); ?></h2>
							<p><?php the_field( 'subindex_paragraph_1' ); ?></p>
						</div>

						<!-- ••••••• H2 container ••••••• -->
						<div id="three-sub-index__2" class="microbusiness-index-three-sub-indexes__h2-cont">
							<div class="microbusiness-index-three-sub-indexes__h2-cont-shade">
								<svg id="microbusiness-index-three-sub-indexes__path-2" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 71">
									<line class="microbusiness-index-three-sub-indexes__line-vert microbusiness-index-three-sub-indexes__line-vert--style-2" x1="4.5" y1="4.5" x2="4.5" y2="66.5" style="fill:none;stroke:#004249;stroke-linecap:round;stroke-miterlimit:10;stroke-width:9px"/>
								</svg>
							</div>
							<h2 class="microbusiness-index-three-sub-indexes__h2 microbusiness-index-three-sub-indexes__h2--style-2"><?php the_field( 'subindex_headline_2' ); ?></h2>
							<p><?php the_field( 'subindex_paragraph_2' ); ?></p>
						</div>

						<!-- ••••••• H2 container ••••••• -->
						<div id="three-sub-index__3" class="microbusiness-index-three-sub-indexes__h2-cont">
							<div class="microbusiness-index-three-sub-indexes__h2-cont-shade">
								<svg id="microbusiness-index-three-sub-indexes__path-3" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 71">
									<line class="microbusiness-index-three-sub-indexes__line-vert microbusiness-index-three-sub-indexes__line-vert--style-3" x1="4.5" y1="4.5" x2="4.5" y2="66.5" style="fill:none;stroke:#004249;stroke-linecap:round;stroke-miterlimit:10;stroke-width:9px"/>
								</svg>
							</div>
							<h2 class="microbusiness-index-three-sub-indexes__h2 microbusiness-index-three-sub-indexes__h2--style-3"><?php the_field( 'subindex_headline_3' ); ?></h2>
							<p><?php the_field( 'subindex_paragraph_3' ); ?></p>
						</div>
					</div>

					<div class="microbusiness-index-three-sub-indexes__circle-anchor">
						<svg id="three_indexes_layer" data-safari="" class="microbusiness-index-three-sub-indexes__circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 700"><g id="background_group"><circle id="background_circle__1" cx="350" cy="350" r="350" style="fill:#fff"/><path id="curve__3" d="M257,618.81c-4.79,13.81,2.5,29,16.67,32.61C440,693.64,609,593.05,651.24,426.74a310.28,310.28,0,0,0,7.85-43.93c1.53-14.54-10.2-26.65-24.82-26.94s-26.56,11.37-28.35,25.88C588.55,523,460,623.47,318.68,606.1a258.25,258.25,0,0,1-27.61-4.93C276.83,597.82,261.77,605,257,618.81Z" style="fill:#fff"/><path id="curve__2" d="M204.3,594.2c-7.49,12.56-23.81,16.77-35.69,8.25C29.2,502.42-2.73,308.32,97.3,168.91a310.34,310.34,0,0,1,28.57-34.32c10.14-10.54,27-9.36,36.59,1.64s8.42,27.64-1.54,38.35C64,278.86,70,441.94,174.3,538.83a254.39,254.39,0,0,0,21.53,17.93C207.56,565.49,211.78,581.64,204.3,594.2Z" style="fill:#fff"/><path id="curve__1" d="M211.34,102.24c-7.14-12.76-2.62-29,10.7-35,156.34-70.73,340.42-1.33,411.15,155a311.53,311.53,0,0,1,15.41,41.83c4.06,14.05-5.37,28-19.71,30.88s-28.14-6.54-32.45-20.51c-41.87-136-186.1-212.38-322.14-170.51a258,258,0,0,0-26.3,9.69C234.57,119.42,218.48,115,211.34,102.24Z" style="fill:#fff"/></g><circle id="forground_circle" cx="350.92" cy="350.68" r="211.5" style="fill:#d8efef"/><path id="mb_txt" d="M262.47,347V328.88a.9.9,0,0,1,.35-.88l2.33-2.57v-.07h-7.58l-6.5,16.08-7.37-16.08h-8.43v.07l2.9,3.81a1.23,1.23,0,0,1,.32,1v14c0,.57-.08.63-.47,1.15l-3.55,4.71v.06h8.77v-.06l-3.56-4.71c-.39-.52-.46-.58-.46-1.15V330.75c.18.49.46,1.09.67,1.55l8.25,18.13h.08l8-19.71c-.06.7-.09,1.51-.09,2.11V347a.9.9,0,0,1-.33.85l-2.36,2.23v.06h11.75v-.06l-2.33-2.23A.85.85,0,0,1,262.47,347Zm8.77-16.26a2.64,2.64,0,0,0,2.85-2.41v-.19a2.84,2.84,0,0,0-5.68,0,2.66,2.66,0,0,0,2.68,2.62h.14Zm-4.84,1.94v.08l1.75,1.76a1,1,0,0,1,.3.87V347.6c0,.46,0,.57-.3.85l-1.75,1.63v.06h9.88v-.06l-1.72-1.63c-.33-.28-.33-.39-.33-.85V332.69Zm19,17.74c4.05,0,6.52-2.39,7.13-6.2h-.07a4.84,4.84,0,0,1-4.35,2.89c-2.39,0-4.75-1.8-4.75-7.19,0-4.56,1.19-6.85,3.34-6.85h.18c.42,0,.5.08.63.42l2.72,6h.06l2.27-6.07a18.62,18.62,0,0,0-6-1.06c-4.56,0-9.39,2.39-9.39,9.21,0,6,4,8.86,8.2,8.86Zm23.19-16.85a2.86,2.86,0,0,0-2.39-1.17c-2.48,0-4,2.29-4.56,4.8v-4.52h-7.83v.08l1.74,1.76a1,1,0,0,1,.31.87V347.6c0,.46,0,.57-.31.85l-1.74,1.63v.06H305.6v-.06l-3.45-2c-.49-.31-.54-.49-.54-.88v-9.52a5.53,5.53,0,0,1,1.53-2.05c.24-.18.46,0,.63.18l2.79,3.32h.06Zm9.46-.5c1.75,0,2.84,1.56,2.84,8.36s-1.09,8.35-2.84,8.35-2.87-1.58-2.87-8.38,1.06-8.32,2.82-8.32Zm0,17.35c5.56,0,9.31-3.06,9.31-9s-3.75-9-9.31-9-9.28,3.08-9.28,9,3.66,9,9.23,9Zm17.61-26-7.8.92v.07l1.69,1.63a.93.93,0,0,1,.31.87v22.5h.08l5.57-1.91a5.15,5.15,0,0,0,4.38,1.91c4.72,0,7.89-3.78,7.89-9.17s-2.54-8.85-6.62-8.85c-2.59,0-4.38,1.38-5.5,3.87Zm2.63,10.79c2,0,3.39,1.69,3.39,6.21,0,6-1.79,7.47-4,7.47a2.76,2.76,0,0,1-2-.78V336.76a3.16,3.16,0,0,1,2.58-1.53ZM367,332.69h-7.83v.08l1.73,1.76a1,1,0,0,1,.32.87V346a3.15,3.15,0,0,1-2.54,1.6c-2,0-2.44-2.12-2.44-5.26v-9.6h-7.79v.08l1.74,1.76a.87.87,0,0,1,.31.84c0,2.87-.07,5.41-.07,7.73,0,4.79,1.87,7.33,5.36,7.33a5.72,5.72,0,0,0,5.4-4.05v3.77h7.86v-.06l-1.76-1.75c-.32-.27-.32-.42-.32-.87Zm13,14.53c0,1.52-1.27,2.57-3.32,2.57h-.81a.74.74,0,0,1-.73-.45l-2.84-5.47h-.06l-1.94,5.47a21.15,21.15,0,0,0,6.5,1.09c4.68,0,7.89-2,7.89-5.85,0-2.71-1.73-3.92-3.92-4.86l-2.25-1c-2.08-.92-3.25-1.66-3.25-3.21A2.58,2.58,0,0,1,378,333h.66c.5,0,.6.17.81.53l2.75,4.9h.07l2-5.11a21.12,21.12,0,0,0-6.21-1c-4.48,0-7.23,2.23-7.23,5.57a5.37,5.37,0,0,0,3.42,5l2.41,1.12C378.87,345.17,380,345.77,380,347.22Zm10.87-16.47a2.65,2.65,0,0,0,2.86-2.42v-.18a2.84,2.84,0,0,0-5.68,0,2.65,2.65,0,0,0,2.7,2.6h.12ZM386,332.69v.08l1.74,1.76a1,1,0,0,1,.31.87v12.21c0,.46,0,.57-.31.85L386,350.09v.06h9.88v-.06l-1.73-1.63c-.32-.28-.32-.39-.32-.85V332.69Zm29.88,7.16c0-4.87-2-7.44-5.5-7.44a5.63,5.63,0,0,0-5.61,4v-3.74h-7.83v.08l1.74,1.76a1,1,0,0,1,.31.87v12.21c0,.46,0,.57-.31.85l-1.74,1.63v.06h9.88v-.06l-1.73-1.63c-.32-.28-.32-.39-.32-.85v-10.7a3.13,3.13,0,0,1,2.62-1.55c2.18,0,2.67,2.12,2.67,5.32,0,1.69-.1,6.46-.1,7a.85.85,0,0,1-.33.81l-1.69,1.63v.06h9.91v-.06l-1.75-1.63a.89.89,0,0,1-.31-.85C415.82,344.83,415.88,342.05,415.88,339.85Zm19.36.78v-.78c0-5.68-4-7.44-7.73-7.44-4.69,0-8.84,2.6-8.84,9.13,0,6.22,4.18,8.89,8.63,8.89,4.13,0,6.88-2.09,7.79-6.53H435a5.75,5.75,0,0,1-5.36,3.26c-2.72,0-5-1.88-5-6.46v-.07Zm-7.77-7.55c1.45,0,2.39,1.23,2.39,5.86V340h-5.17c0-5.12,1-6.92,2.78-6.92Zm19.09,14.14c0,1.52-1.27,2.57-3.32,2.57h-.81a.74.74,0,0,1-.73-.45l-2.83-5.47h-.07l-1.94,5.47a21.15,21.15,0,0,0,6.5,1.09c4.69,0,7.89-2,7.89-5.85,0-2.71-1.72-3.92-3.92-4.86l-2.25-1c-2.08-.92-3.25-1.66-3.25-3.21a2.58,2.58,0,0,1,2.69-2.48h.66c.51,0,.6.17.81.53l2.76,4.9h.06l2-5.11a21.18,21.18,0,0,0-6.22-1c-4.48,0-7.23,2.23-7.23,5.57a5.37,5.37,0,0,0,3.42,5l2.41,1.12c2.28,1.12,3.37,1.72,3.37,3.17Zm16.08,0c0,1.52-1.26,2.57-3.32,2.57h-.81a.74.74,0,0,1-.73-.45L455,343.87h-.07L453,349.34a21.15,21.15,0,0,0,6.5,1.09c4.69,0,7.89-2,7.89-5.85,0-2.71-1.72-3.92-3.92-4.86l-2.25-1c-2.08-.92-3.25-1.66-3.25-3.21a2.58,2.58,0,0,1,2.69-2.48h.66c.51,0,.6.17.81.53l2.76,4.9H465l2-5.11a21.18,21.18,0,0,0-6.22-1c-4.48,0-7.23,2.23-7.23,5.57a5.37,5.37,0,0,0,3.42,5l2.41,1.12c2.23,1.12,3.32,1.72,3.32,3.17Zm-147,38v-18.5a.88.88,0,0,1,.35-.84l2.33-2.22v-.06H306.56v.06l2.36,2.22a.93.93,0,0,1,.31.84v18.53a.93.93,0,0,1-.31.84l-2.36,2.23v.07h11.73v-.07L316,386.09a.88.88,0,0,1-.39-.84Zm22.91-7.17c0-4.86-2-7.43-5.5-7.43a5.61,5.61,0,0,0-5.61,4v-3.75h-7.83V371l1.74,1.76a1,1,0,0,1,.31.88v12.21c0,.45,0,.57-.31.84l-1.74,1.63v.07h9.88v-.07l-1.73-1.63c-.32-.27-.32-.39-.32-.84V375.12a3.14,3.14,0,0,1,2.62-1.54c2.18,0,2.67,2.11,2.67,5.32,0,1.69-.1,6.46-.1,7a.85.85,0,0,1-.33.81l-1.69,1.63v.07h9.91v-.07l-1.75-1.63a.87.87,0,0,1-.31-.84c0-2.81.06-5.59.06-7.79Zm20.77-15.41-7.79.93v.06l1.69,1.63a.91.91,0,0,1,.34.88v7.29a4.61,4.61,0,0,0-4.54-2.81c-4.41,0-7.59,3.63-7.59,9.06,0,5.78,2.93,9,7,9a5.42,5.42,0,0,0,5.18-3.59v3.32h7.83v-.07l-1.76-1.74c-.31-.28-.31-.37-.31-.85ZM351.07,386c-2,0-3.56-1.7-3.56-6.5,0-5.11,1.82-6.83,3.73-6.83a2.87,2.87,0,0,1,2.29,1.09v10.89A3.09,3.09,0,0,1,351.07,386Zm27.57-7.17v-.78c0-5.68-4-7.43-7.73-7.43-4.69,0-8.84,2.6-8.84,9.13,0,6.21,4.18,8.88,8.62,8.88,4.14,0,6.89-2.08,7.8-6.52h-.07a5.73,5.73,0,0,1-5.35,3.25c-2.72,0-5.05-1.87-5.05-6.46v-.07Zm-7.76-7.55c1.45,0,2.39,1.24,2.39,5.86v1.06h-5.18c0-5.08,1-6.89,2.79-6.89Zm18.72,6.59,1.8-2.24a9.26,9.26,0,0,1,1.49-1.51l3.77-3.15v-.08h-7.13V371l1.81,2.79c.31.45.28.52-.11,1l-2,2.54-2.58-3.84c-.28-.39-.31-.54,0-.85L388,371v-.08h-9.6V371l1.3,1.55a11.24,11.24,0,0,1,1.06,1.33l4.92,7.35-2,2.44a8.81,8.81,0,0,1-1.48,1.51l-3.74,3.14v.07h7.09v-.07l-1.79-2.75c-.32-.5-.29-.57.1-1.06L386,381.8l2.72,4c.24.4.32.53,0,.88l-1.33,1.63v.07H397v-.07l-1.38-1.59c-.36-.43-.64-.74-1-1.24Z"/></svg>
					</div>


				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ three sub-indexes: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Graph 1 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="graph-1" class="microbusiness-index__section microbusiness-index__section--graph-1">
				<div class="microbusiness-index-graph-1__grid">
					<h2 class="microbusiness-index-graph-1__h2"><?php the_field( 'graph_headline' ); ?></h2>
					<p class="microbusiness-index-graph-1__caption"><?php the_field( 'graph_description' ); ?></p>
					<div class="microbusiness-index-graph-1__chart-tools-locations">
						<span class="microbusiness-index-graph-1__chart-tools-location microbusiness-index-graph-1__chart-tools-location--county microbusiness-index-graph-2__chart-tools-location--active">
							Los Angeles, CA
						</span>
						<span class="microbusiness-index-graph-1__chart-tools-location microbusiness-index-graph-2__chart-tools-location--active">
							US National Average
						</span>
					</div>
					<div class="microbusiness-index-graph-1__image microbusiness-index-graph-1__image--desktop">
						<?php
						wonder_include_template_file(
							'partials/image.php',
							array(
								'acf' => get_field( 'graph_image' ),
								'class' => 'microbusiness-index-graph-1__graph-1',
								'attributes' => array(
									'width' => '996',
									'height' => '635',
								),
							)
						);
						?>
					</div>
					<div class="microbusiness-index-graph-1__image microbusiness-index-graph-1__image--mobile">
						<?php
						wonder_include_template_file(
							'partials/image.php',
							array(
								'acf' => get_field( 'graph_image_mobile' ),
								'class' => 'microbusiness-index-graph-1__graph-1',
								'attributes' => array(
									'width' => '343',
									'height' => '214',
								),
							)
						);
						?>
					</div>

					<div class="microbusiness-index-graph-1__footnote">
						<?php the_field( 'graph_footnote' ); ?>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Graph 1: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ A Closer Look ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="a-closer-look" class="microbusiness-index__section microbusiness-index__section--a-closer-look">
				<div class="microbusiness-index-a-closer-look__grid">
					<h2 class="microbusiness-index-a-closer-look__h2"><?php the_field( 'closer_look_preheadline' ); ?></h2>
					<p class="microbusiness-index-a-closer-look__body"><?php the_field( 'closer_look_headline' ); ?></p>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ A Closer Look: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Map ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="map" class="microbusiness-index__section microbusiness-index__section--map">
				<div class="microbusiness-index-map__grid">
					<div class="microbusiness-index-map__image-container">
						<div class="microbusiness-index-map__map-image">
							<?php
							wonder_include_template_file(
								'partials/image.php',
								array(
									'acf' => get_field( 'map_map_image' ),
								)
							);
							?>
						</div>
						<div class="microbusiness-index-map__legend-image microbusiness-index-map__legend-image--desktop">
							<?php
							wonder_include_template_file(
								'partials/image.php',
								array(
									'acf' => get_field( 'map_legend_desktop' ),
								)
							);
							?>
						</div>
						<div class="microbusiness-index-map__legend-image microbusiness-index-map__legend-image--mobile">
							<?php
							wonder_include_template_file(
								'partials/image.php',
								array(
									'acf' => get_field( 'map_legend_mobile' ),
								)
							);
							?>
						</div>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Map: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Search ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="search" class="microbusiness-index__section microbusiness-index__section--search">
				<div class="microbusiness-index-search__grid">
					<h2 class="microbusiness-index-search__h2"><?php the_field( 'search_headline' ); ?></h2>
					<p class="microbusiness-index-search__body"><?php the_field( 'search_paragraph' ); ?></p>

					<div class="microbusiness-index-search__search-input" data-search-input-container>
						<div class="microbusiness-index-search__search-selection microbusiness-index-search__search-selection--selection-1"
							 data-search-selection-1></div>
						<input type="text" name="hero-search" placeholder="Enter a County or Metro" class="microbusiness-index-search__input" autocomplete="off" data-search-input/>
						<ul class="microbusiness-index-search__search-autocomplete" data-search-autocomplete></ul>
					</div>

					<div class="microbusiness-index-search__bars" data-search-bars>

						<div class="microbusiness-index-search__bar" data-search-bar-county>
							<span data-search-bar-county-name>-</span>
							<span data-search-bar-county-index>-</span>
						</div>
						
						<div class="microbusiness-index-search__bar" data-search-bar-national data-width="<?php echo esc_attr( number_format( (float) round( ( ( $avg_activity_index / $max_activity_index ) * 0.75 ) * 100, 2, PHP_ROUND_HALF_DOWN ), 2, '.', ',' ) ); ?>%">
							<span>National Average</span>
							<span data-search-bar-ntl-index></span>
						</div>

						<div class="microbusiness-index-search__bars-legend">
							<span>National Low: <?php echo esc_html( $min_activity_index ); ?></span>
							<span>National High: <?php echo esc_html( $max_activity_index ); ?></span>
						</div>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Search: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Graph 2 ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="graph-2" class="microbusiness-index__section microbusiness-index__section--graph-2">
				<div class="microbusiness-index-graph-2__grid">
					<h2 class="microbusiness-index-graph-2__h2"><?php the_field( 'graph_2_headline' ); ?></h2>
					<p class="microbusiness-index-graph-2__body"><?php the_field( 'graph_2_paragraph' ); ?></p>

					<div class="microbusiness-index-graph-2__chart-tools-locations">
						<span class="microbusiness-index-graph-2__chart-tools-location microbusiness-index-graph-2__chart-tools-location--active">
							US National Average
						</span>
						<span class="microbusiness-index-graph-2__chart-tools-location microbusiness-index-graph-2__chart-tools-location--county" data-graph-2-county-tag>
							-
						</span>
					</div>

					<canvas id="compare-chart" class="microbusiness-index-graph-2__chart-canvas"></canvas>

					<div class="microbusiness-index-graph-2__action-area">
						<?php
						wonder_include_template_file(
							'partials/button.php',
							array(
								'acf' => get_field( 'graph_2_button' ),
								'class' => 'global-cta',
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'graph_2_eid_section_slug_eid_section_slug' ),
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
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Graph 2: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ footer  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<footer class="microbusiness-index-footer"></footer>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ footer: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		</main>

		<?php
	endwhile;
endif;
?>

<?php
wp_localize_script(
	'global',
	'index_vars',
	array(
		'eid_page_slug' => get_field( 'eid_page_slug' ),
		'search_bars_high' => $max_activity_index,
		'search_bars_low' => $max_activity_index,
		'vf_get_all_locations' => vf_get_all_locations(),
	)
);
get_footer(); ?>
