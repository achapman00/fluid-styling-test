<?php
/**
 * Template Name: City Portal Page Template
 * Description: A custom city portal Page template.
 *
 * @package Wonderpress Theme
 */

// Set the <body> id
wonder_body_id( 'city-portal' );

$showing_default_city = false;
$city_data = null;

// Check if a city was requested
if ( isset( $_REQUEST['city'] ) ) {
	$requested_city = intval( $_REQUEST['city'] );
	$city_payload = vf_get_city_for_city_portal( $requested_city );

	if ( isset( $city_payload['city'] ) && $city_payload['city'] ) {
		$city_data = $city_payload['city'];
	}
}

// If no city data yet, run the default
if ( ! $city_data ) {

	// Default to Austin, TX
	$default_city_id = 1221;
	$city_payload = vf_get_city_for_city_portal( $default_city_id );

	if ( isset( $city_payload['city'] ) && $city_payload['city'] ) {
		$city_data = $city_payload['city'];
		$showing_default_city = true;
	}
}

// If nothing worked, something is seriously wrong...


// Put together the table information
switch ( $city_data['city_size'] ) {
	case 'Large City':
		$key_name_size = 'lrg';
		$size_label = 'Large ';
		break;
	case 'Midsize City':
		$key_name_size = 'med';
		$size_label = 'Midsize ';
		break;
	case 'Small City':
		$key_name_size = 'sm';
		$size_label = 'Small ';
		break;
	default:
		$size_label = '';
		break;
}

$table_col_namespaces = array(
	'q1_21vd',
	'change_vd',
	'bb19',
	'medinc19',
	'housing19',
	'college19',
	'poverty19',
	'unemp_dec20',
	'pop19',
	'pop_3yr',
);

$table_row_configs = array(
	// 'Average Microbusiness Density*' => array(
	// 'format' => 'round_to_tenth',
	// 'slug' => 'q1_21vd',
	// ),
		'Change in Microbusiness Density*' => array(
			'format' => 'percent',
			'slug' => 'change_vd',
		),
	'Broadband Subscription' => array(
		'format' => 'percent',
		'slug' => 'bb19',
	),
	'Median Household Income' => array(
		'format' => 'money',
		'slug' => 'medinc19',
	),
	'Average Monthly Housing Cost' => array(
		'format' => 'money',
		'slug' => 'housing19',
	),
	'Population with a 4-year degree' => array(
		'format' => 'percent',
		'slug' => 'college19',
	),
	'Population Living in Poverty' => array(
		'format' => 'percent',
		'slug' => 'poverty19',
	),
	'Unemployed in Respective County**' => array(
		'format' => 'percent',
		'slug' => 'unemp_dec20',
	),
	'Population' => array(
		'format' => 'round',
		'slug' => 'pop19',
	),
	'3-Year Population Growth' => array(
		'format' => 'percent',
		'slug' => 'pop_3yr',
	),
);

$table_rows = array();
$table_rows['header'] = array(
	'',
	'<span data-dynamic-city-name>' . $city_data['city_name'] . '</span>',
	'<span data-dynamic-city-size>' . $size_label . '</span> Metro Center Average',
	'<span data-dynamic-city-name>' . $city_data['city_name'] . '</span> Relative to Peer Cities<sup>***</sup>',
	'<span data-dynamic-city-name>' . $city_data['city_name'] . '</span> Relative to All Cities in Dataset',
);
foreach ( $table_row_configs as $name => $config ) {

	$namespace = $config['slug'];

	$row = array();
	$row[] = $name;

	switch ( $config['format'] ) {
		case 'money':
			$city_val = '$' . number_format( round( $city_data[ $namespace ], 0 ), 0 );
			$metro_val = '$' . number_format( round( $city_data[ 'avg_' . $namespace . '_' . $key_name_size ], 0 ), 0 );
			break;
		case 'percent':
			$city_val = number_format( round( $city_data[ $namespace ], 0 ), 0 ) . '%';
			$metro_val = number_format( round( $city_data[ 'avg_' . $namespace . '_' . $key_name_size ], 0 ), 0 ) . '%';
			break;
		case 'round_to_tenth':
			$city_val = number_format( round( $city_data[ $namespace ], 1 ), 1 );
			$metro_val = number_format( round( $city_data[ 'avg_' . $namespace . '_' . $key_name_size ], 1 ), 1 );
			break;
		case 'round':
		default:
			$city_val = number_format( round( $city_data[ $namespace ], 0 ), 1 );
			$metro_val = number_format( round( $city_data[ 'avg_' . $namespace . '_' . $key_name_size ], 0 ), 1 );
			break;
	}
	$row[] = $city_val;
	$row[] = $metro_val;
	$row[] = number_format( round( $city_data[ $namespace . '_peercomparison_' . $key_name_size ], 0 ) ) . '%';
	$row[] = number_format( round( $city_data[ $namespace . '_nationalcomparison' ], 0 ) ) . '%';

	$table_rows[ $namespace ] = $row;
}

// Check to see if we are spitting out a CSV
if ( isset( $_REQUEST['format'] ) && sanitize_text_field( wp_unslash( $_REQUEST['format'] ) ) == 'csv' ) {
	// force download of CSV
	// simulate file handle w/ php://output, direct to output (from http://www.php.net/manual/en/function.fputcsv.php#72428)
	// (could alternately write to memory handle & read from stream, this seems more direct)
	// headers from http://us3.php.net/manual/en/function.readfile.php
	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/csv' );
	header( 'Content-Disposition: attachment; filename=' . sanitize_title( $city_data['city_name'] . '-data' ) . '.csv' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );

	$handle = fopen( 'php://output', 'w' );
	ob_clean(); // clean slate

	foreach ( $table_rows as $row ) {
		foreach ( $row as $k => $value ) {
			$row[ $k ] = strip_tags( $value );
		}
		fputcsv( $handle, $row );   // direct to buffered output
	}

	ob_flush(); // dump buffer
	fclose( $handle );
	die();
}

get_header();
?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<main class="global-main" role="main">

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Banner ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<header class="city-portal-banner">
				<div class="city-portal-banner__grid">
					<div>
						<h1>Venture Forward</h1>
						<span></span>
						<h2>Venture Forward is a multiyear research effort to quantify the impact online microbusinesses have on their local economies.</h2>
					</div>
				</div>
			</header>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="hero" class="city-portal__section city-portal__section--hero">
				<div class="city-portal-hero__grid">
					<div id="city-portal-hero-card" class="city-portal-hero__card">
						<h1 class="city-portal-hero__h1">
							<?php the_field( 'hero_headline' ); ?>
						</h1>
						<p id="city-portal-hero-card-p" class="city-portal-hero__paragraph">
							<?php the_field( 'hero_paragraph' ); ?>
						</p>
						<div id="city-portal-hero-card-search-cont" class="city-portal-hero__search-input" data-search-input-container>
							<div class="city-portal-hero__search-selection"
								 data-search-selection-1></div>

							<input type="text" name="hero-search" id="hero-search" placeholder="<?php the_field( 'hero_input_placeholder' ); ?>" class="city-portal-hero__input" autocomplete="off" data-search-input />
							<ul class="city-portal-hero__search-autocomplete" data-search-autocomplete></ul>
							<div class="city-portal-hero__search-no-results" data-search-no-results>
								<p>Looks like we don’t have info on "<span data-search-no-result-preview></span>" published yet. Would you like to request it?</p>
								<a href="mailto:ventureforward@godaddy.com">
									Request City <img alt="right arrow" class="right-arrow" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/imgs/global/godaddy-venture-cta_arrow_21x12_00a4a6-on-trans.svg">
								</a>
							</div>
						</div>
					</div>
				</div>

				<div id="map" class="city-portal-hero__map-canvas"></div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->


			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Data Summary ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="data-summary" class="city-portal__section city-portal__section--data-summary">
				<div class="city-portal-data-summary__grid">
					<div class="city-portal-data-summary__text-stack">
						<h6 class="city-portal-data-summary__h6">
							<span data-dynamic-city-size><?php echo esc_html( $city_data['city_size'] ); ?></span> Metro Center
						</h6>
						<h2 class="city-portal-data-summary__h2" data-dynamic-city-name>
							<?php echo esc_html( $city_data['city_name'] ); ?>
						</h2>
						<h3 class="city-portal-data-summary__h3
						<?php
						if ( ! $showing_default_city ) {
							echo ' city-portal-data-summary__h3--hidden';}
						?>
						" data-default-data-summary>
							<?php the_field( 'city_summary_default_headline' ); ?>
						</h3>
						<p class="city-portal-data-summary__paragraph">
							<?php the_field( 'city_summary_paragraph' ); ?>
						</p>
					</div>

					<div class="city-portal-data-summary__graph-header">
						<h3 class="city-portal-data-summary__graph-header-headline">
							<?php the_field( 'city_summary_chart_headline' ); ?>
						</h3>

						<div class="city-portal-data-summary__graph-legend-items" data-graph-legend>
							<div class="city-portal-data-summary__graph-legend-item city-portal-data-summary__graph-legend-item--city" data-dynamic-city-name>
								<?php echo esc_html( $city_data['city_name'] ); ?>
							</div>
							<div class="city-portal-data-summary__graph-legend-item city-portal-data-summary__graph-legend-item--national">
								National Average
							</div>
						</div>
					</div>
					<div class="city-portal-data-summary__graph">
						<canvas id="compare-chart" class="city-portal-data-summary__graph-chart"></canvas>
						<p class="city-portal-data-summary__graph-source"><cite>Source: U.S. Census</cite>&nbsp;(2019)</p>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Data Summary: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->


			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Microbusiness ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="microbusiness" class="city-portal__section city-portal__section--microbusiness">
				<div class="city-portal-microbusiness__grid">
					<p class="city-portal-microbusiness__subtitle"><?php the_field( 'facts_preheadline' ); ?></p>
				</div>
				<div class="city-portal-microbusiness__grid">
					<h2 class="city-portal-microbusiness__h2"><?php the_field( 'facts_headline' ); ?></h2>

					<div class="city-portal-microbusiness__carousel">
						<div data-microbusiness-carousel>
							<?php
							while ( have_rows( 'facts_cards' ) ) {
								the_row();
								?>
							<div class="city-portal-microbusiness__carousel--blade-container">
								<div class="city-portal-microbusiness__carousel--blade">
									<?php
									switch ( get_sub_field( 'icon' ) ) {
										case 'down':
											$alt = 'Downward arrow';
											$src = get_template_directory_uri() . '/assets/imgs/global/info-graphic-arrow-down.svg';
											break;
										case 'graph':
											$alt = 'Graph arrow';
											$src = get_template_directory_uri() . '/assets/imgs/global/info-graphic-arrow-graph.svg';
											break;
										case 'up':
										default:
											$alt = 'Upward arrow';
											$src = get_template_directory_uri() . '/assets/imgs/global/godaddy-venture-carousel_arrow_140x140.svg';
											break;
									}
									?>
									<img alt="<?php esc_attr( $alt ); ?>"
											 src="<?php echo esc_url( $src ); ?>">
									<div class="city-portal-microbusiness__carousel--content">
										<h2 class="city-portal-microbusiness__carousel--content--h2">
											<?php the_sub_field( 'statistic' ); ?>
										</h2>
										<h3 class="city-portal-microbusiness__carousel--content--h3">
											<?php the_sub_field( 'paragraph' ); ?>
										</h3>
										<p class="city-portal-microbusiness__carousel--content--body">
											<?php the_sub_field( 'dates' ); ?>
										</p>

										<?php
										$img = wonder_include_template_file(
											'partials/image.php',
											array(
												'acf' => get_sub_field( 'logo' ),
												'class' => 'four-factors-article__card--image',
											),
											true
										);

										// Template: Button
										wonder_include_template_file(
											'partials/button.php',
											array(
												'acf' => get_sub_field( 'publisher_link' ),
												'class' => 'city-portal-microbusiness__carousel--content--attribution',
												// 'eid_page_slug' => get_field( 'eid_page_slug' ),
												// 'eid_section_slug' => get_field( 'hero_eid_section_slug_eid_section_slug' ),
												// 'eid_widget_slug' => get_the_title( $resource_obj ),
												'attributes' => array(
													'rel' => 'noopener',
												),
												'text' => $img . '<span>' . get_sub_field( 'publisher_name' ) . '</span>',
											)
										);
										?>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>

						<div class="global-horizontal-carousel__pagination-dots" role="tablist" data-microbusiness-carousel-dots></div>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Microbusiness: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->


			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Microbusiness Stats ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="microbusiness-stats" class="city-portal__section city-portal__section--microbusiness-stats">
				<div class="city-portal-microbusiness-stats__grid">
					<h2 class="city-portal-microbusiness-stats__h2">
						<?php the_field( 'map_headline' ); ?>
					</h2>
					<p class="city-portal-microbusiness-stats__paragraph">
						<?php the_field( 'map_paragraph' ); ?>
					</p>
					<div class="city-portal-microbusiness-stats__map">

						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/imgs/city-portal-maps/graph_<?php echo esc_attr( $city_data['city_id'] ); ?>.svg" alt="<?php echo esc_attr( $city_data['city_name'] ); ?>" data-image-directory="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/imgs/city-portal-maps/" data-dynamic-city-map />

						<div class="city-portal-microbusiness-stats__map-legend city-portal-microbusiness-stats__map-legend--size-5
						<?php
						if ( 5 == $city_data['legendflag'] ) {
							echo ' city-portal-microbusiness-stats__map-legend--active';}
						?>
						" data-map-legend-size="5">

							<div class="city-portal-microbusiness-stats__map-legend-city" data-dynamic-city-name>
								<?php echo esc_html( $city_data['city_name'] ); ?>
							</div>
							<h4 class="city-portal-microbusiness-stats__map-legend-headline">
								Microbusiness Density
							</h4>

							<ul class="city-portal-microbusiness-stats__map-legend-list">
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--5">
										Bottom 20%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--4">
										20%-40%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--3">
										40%-60%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--2">
										60%-80%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--1">
										Top 20%
									</div>
								</li>
							</ul>
						</div>

						<div class="city-portal-microbusiness-stats__map-legend city-portal-microbusiness-stats__map-legend--size-4
						<?php
						if ( 4 == $city_data['legendflag'] ) {
							echo ' city-portal-microbusiness-stats__map-legend--active';}
						?>
						" data-map-legend-size="4">

							<div class="city-portal-microbusiness-stats__map-legend-city" data-dynamic-city-name>
								<?php echo esc_html( $city_data['city_name'] ); ?>
							</div>
							<h4 class="city-portal-microbusiness-stats__map-legend-headline">
								Microbusiness Density
							</h4>

							<ul class="city-portal-microbusiness-stats__map-legend-list">
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--1">
										Top 25%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--2">
										26%-50%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--3">
										51%-75%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--4">
										76%-100%
									</div>
								</li>
							</ul>
						</div>

						<div class="city-portal-microbusiness-stats__map-legend city-portal-microbusiness-stats__map-legend--size-3
						<?php
						if ( 3 == $city_data['legendflag'] ) {
							echo ' city-portal-microbusiness-stats__map-legend--active';}
						?>
						" data-map-legend-size="3">

							<div class="city-portal-microbusiness-stats__map-legend-city" data-dynamic-city-name>
								<?php echo esc_html( $city_data['city_name'] ); ?>
							</div>
							<h4 class="city-portal-microbusiness-stats__map-legend-headline">
								Microbusiness Density
							</h4>

							<ul class="city-portal-microbusiness-stats__map-legend-list">
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--1">
										Top 33%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--2">
										34%-66%
									</div>
								</li>
								<li class="city-portal-microbusiness-stats__map-legend-list-item">
									<div class="city-portal-microbusiness-stats__map-legend-level city-portal-microbusiness-stats__map-legend-level--3">
										67%-100%
									</div>
								</li>
							</ul>
						</div>

					</div>
					<div class="city-portal-microbusiness-stats__chart-table-wrapper">
						<div class="city-portal-microbusiness-stats__chart-table-scroller">
							<table class="city-portal-microbusiness-stats__chart-table" data-microbusiness-stats-chart>
								<?php
								$c = 0;
								foreach ( $table_rows as $slug => $row ) {
									if ( 0 == $c ) {
										?>
								<tr>
									<th><?php echo esc_html( $row[0] ); ?></th>
									<th><?php echo wp_kses_post( $row[1] ); ?></th>
									<th><?php echo wp_kses_post( $row[2] ); ?></th>
									<th><?php echo wp_kses_post( $row[3] ); ?></th>
									<th><?php echo wp_kses_post( $row[4] ); ?></th>
								</tr>
									<?php } else { ?>
								<tr>
									<td><?php echo esc_html( $row[0] ); ?></td>
									<td><span data-dynamic-<?php echo esc_attr( $slug ); ?>-city><?php echo esc_html( $row[1] ); ?></span></td>
									<td><span data-dynamic-<?php echo esc_attr( $slug ); ?>-metro><?php echo esc_html( $row[2] ); ?></span></td>
									<td><span data-dynamic-<?php echo esc_attr( $slug ); ?>-peer><?php echo esc_html( $row[3] ); ?></span></td>
									<td><span data-dynamic-<?php echo esc_attr( $slug ); ?>-national><?php echo esc_html( $row[4] ); ?></span></td>
								</tr>
										<?php
									}
									$c++;
								}
								?>
							</table>
						</div>
						<div class="city-portal-microbusiness-stats__chart-table-legend">
							<span><span>*</span> Q1 2020 - Q1 2021, Per 100 People, GoDaddy proprietary data</span>
							<span><span>**</span> March 2021, Bureau of Labor Statistics, monthly unemployment estimates</span>
							<span><span>***</span> Peer Cities by Population: Small Population 0-50k, Medium 50-250k, Large 250k+</span>
						</div>

						<div class="global-cta-area">
							<?php
							// Template: Button
							wonder_include_template_file(
								'partials/button.php',
								array(
									'acf' => get_field( 'table_button' ),
									'class' => 'global-cta global-cta--lg',
									'eid_page_slug' => get_field( 'eid_page_slug' ),
									'eid_section_slug' => get_field( 'table_eid_section_slug_eid_section_slug' ),
									'attachment' => 'download-ani',
									'attributes' => array(
										'data-ani-action' => 'download',
										'data-dynamic-csv-download' => true,
										'rel' => 'noopener',
									),
									'url' => home_url(
										add_query_arg(
											array(
												'city' => $city_data['city_id'],
												'format' => 'csv',
											)
										)
									),
								)
							);
							?>
						</div>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Microbusiness Stats: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->


			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Four Factors Stats ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="four-factors" class="city-portal__section city-portal__section--four-factors">
				<div class="city-portal-four-factors__grid">
					<h2 class="city-portal-four-factors__h2">
						<?php the_field( 'pillars_headline' ); ?>
					</h2>
					<header id="category-buttons" class="city-portal-four-factors__category-buttons">
						<div class="reports-and-resources-reports-and-filters__header-grid">
							<div class="reports-and-resources-reports-and-filters__header-filters reports-and-resources-reports-and-filters__header-filters--city-portal">
								<?php
								$iter_x = 1;
								while ( have_rows( 'pillars_categories' ) ) {
									the_row();
									?>
									<input type="checkbox" id="category-button__<?php echo esc_attr( $iter_x ); ?>"
										   class="global-filter__checkbox global-filter__checkbox--city-portal"
										   aria-label="<?php the_sub_field( 'category_name' ); ?>"
										   data-type="<?php echo esc_html( $iter_x ); ?>"
																 <?php
																	if ( 1 === $iter_x ) {
																		echo 'checked';}
																	?>
											/>
									<label for="category-button__<?php echo esc_attr( $iter_x ); ?>"
										   class="global-filter__label global-filter__label--city-portal"
										   aria-label="<?php the_sub_field( 'category_name' ); ?>"><?php the_sub_field( 'category_name' ); ?></label>

									<?php
									$iter_x++;
								}
								?>
							</div>
							<div id="filters_cont" class="global-header-filters-touch">
								<label for="filters-select">Four Factors</label>
								<div class="select">
									<select id="filters-select">
										<?php
										$iter_x = 1;
										while ( have_rows( 'pillars_categories' ) ) {
											the_row();
											?>
											<option value="<?php echo esc_html( $iter_x ); ?>"><?php the_sub_field( 'category_name' ); ?></option>
											<?php
											$iter_x++;
										}
										?>
									</select>
								</div>
							</div>

						</div>
					</header>
					<div id="four-factors-holder" class="city-portal-four-factors__category-card-holder">
						<?php
						$c = 1;
						while ( have_rows( 'pillars_categories' ) ) {
							the_row();

							$category_name = get_sub_field( 'category_name' );
							$category_section_eid_slug = get_sub_field( 'category_section_eid_slug_eid_section_slug' );
							?>
						<article id="four-factors-slide__<?php echo esc_attr( $c ); ?>"
								 class="city-portal-four-factors__category-card-slide">

							<?php
							// Get the KEY STATS card
							while ( have_rows( 'key_stats' ) ) {
								the_row();
								?>
							<section class="city-portal-four-factors__category-card" data-section="Key Stats">
								<div class="city-portal-four-factors__card-division">
									<h3 class="city-portal-four-factors__h3"><?php echo esc_html( $category_name ); ?></h3>

									<div class="city-portal-four-factors__h4-cont city-portal-four-factors__h4-cont--card-0">
										<div class="city-portal-four-factors__h4-cont-shade">
											<svg id="four-factors-slide-<?php echo esc_attr( $c ); ?>__path-1"
												 xmlns="http://www.w3.org/2000/svg" style="display:inline-block;width:5px;">
												<line class="city-portal-four-factors__line-vert" x1="2.5" y1="2.5" x2="2.5"
													  y2="45.5" stroke="#00A4A6" stroke-linecap="round"/>
											</svg>
										</div>
										<h4 class="city-portal-four-factors__h4">Key Stats</h4>
									</div>
									<div class="city-portal-four-factors__subtitle-copy"">
										<?php the_sub_field( 'paragraph' ); ?>
									</div>
								</div>

								<div id="four-factors-slide-<?php echo esc_attr( $c ); ?>__graph" class="city-portal-four-factors__card-division" data-ani-four-factors-graph="<?php the_sub_field( 'chart_percentage' ); ?>">
								</div>

								<div class="global-bullet-list city-portal-four-factors__list--style-1">
									<ul>
								<?php
								// Loop thru all the bullets
								while ( have_rows( 'bullets' ) ) {
									the_row();
									?>
								<li>
									<?php the_sub_field( 'paragraph' ); ?>
								</li>
								<?php } ?>
									</ul>
									<p class="city-portal-data-summary__graph-source city-portal-data-summary__graph-source--four-factors"><cite>Source: GoDaddy U.S. National Survey</cite>&nbsp;July 2020, 2300+ respondents</p>
									<p class="city-portal-data-summary__graph-source city-portal-data-summary__graph-source--four-factors"><cite>Source: GoDaddy Venture Forward Research</cite>&nbsp;(2018-2021)</p>
								</div>

							</section>
							<?php } ?>

							<?php
							// Get the Resources card
							while ( have_rows( 'resources' ) ) {
								the_row();
								?>
							<section class="city-portal-four-factors__category-card" data-section="Resources">
								<div class="city-portal-four-factors__card-division city-portal-four-factors__card-division--full-width">
									<h3 class="city-portal-four-factors__h3"><?php echo esc_html( $category_name ); ?></h3>

									<div class="city-portal-four-factors__h4-cont">
										<div class="city-portal-four-factors__h4-cont-shade">
											<svg id="four-factors-slide-<?php echo esc_attr( $c ); ?>__path-2"
												 xmlns="http://www.w3.org/2000/svg" style="display:inline-block;width:5px;">
												<line class="city-portal-four-factors__line-vert" x1="2.5" y1="2.5" x2="2.5"
													  y2="45.5" stroke="#00A4A6" stroke-linecap="round"/>
											</svg>
										</div>
										<h4 class="city-portal-four-factors__h4">Resources</h4>
									</div>
								</div>

								<div class="city-portal-four-factors__card-division city-portal-four-factors__card-division--full-width">

									<div class="city-portal-four-factors__resources-container">
									<?php
									// Loop thru the bullets
									while ( have_rows( 'bullets' ) ) {
										the_row();
										?>
										<div class="city-portal-four-factors__resource">
											<?php
											wonder_include_template_file(
												'partials/link.php',
												array(
													'acf' => get_sub_field( 'link' ),
													'text' => get_sub_field( 'headline' ),
													'class' => 'city-portal-four-factors__resource-link',
													'eid_page_slug' => get_field( 'eid_page_slug' ),
													'eid_section_slug' => $category_section_eid_slug,
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
								</div>

								<div class="city-portal-four-factors__card-division city-portal-four-factors__card-division--full-width">
									<div class="city-portal-four-factors__contributing-partner">

										<div class="city-portal-four-factors__contributing-partner-headline">
											Contributing Partner
										</div>

										<?php
										// Get (dont echo) the contributing partner logo, and then link it
										$img = wonder_include_template_file(
											'partials/image.php',
											array(
												'acf' => get_sub_field( 'contributing_partner_logo' ),
												// 'size' => 'small',
											),
											true
										);

										wonder_include_template_file(
											'partials/button.php',
											array(
												'acf' => get_sub_field( 'contributing_partner_link' ),
												'text' => $img,
												'class' => 'city-portal-four-factors__contributing-partner-link',
												'eid_page_slug' => get_field( 'eid_page_slug' ),
												'eid_section_slug' => $category_section_eid_slug,
												'attributes' => array(
													'rel' => 'noopener',
												),
											)
										);
										?>
									</div>
								</div>
							</section>
							<?php } ?>

							<?php
							// Get the Case Study card
							while ( have_rows( 'use_cases' ) ) {
								the_row();
								?>
							<section class="city-portal-four-factors__category-card" data-section="Use Cases">
								<div class="city-portal-four-factors__card-division city-portal-four-factors__card-division--full-width">
									<h3 class="city-portal-four-factors__h3"><?php echo esc_html( $category_name ); ?></h3>

									<div class="city-portal-four-factors__h4-cont">
										<div class="city-portal-four-factors__h4-cont-shade">
											<svg id="four-factors-slide-<?php echo esc_attr( $c ); ?>__path-3"
												 xmlns="http://www.w3.org/2000/svg" style="display:inline-block;width:5px;">
												<line class="city-portal-four-factors__line-vert" x1="2.5" y1="2.5" x2="2.5"
													  y2="45.5" stroke="#00A4A6" stroke-linecap="round"/>
											</svg>
										</div>
										<h4 class="city-portal-four-factors__h4">Use Case</h4>
									</div>
								</div>


								<div class="city-portal-four-factors__card-division city-portal-four-factors__card-division--full-width">
									<a href="<?php echo esc_url( get_the_permalink( get_sub_field( 'story' ) ) ); ?>" class="city-portal-four-factors__use-cases-highlight-card" title="<?php echo esc_attr( get_the_title( get_sub_field( 'story' ) ) ); ?>" data-eid="<?php echo esc_attr( wonder_create_eid_string( get_field( 'eid_page_slug' ), $category_section_eid_slug, sanitize_title( get_the_title( get_sub_field( 'story' ) ) ), 'click' ) ); ?>">
										<div class="city-portal-four-factors__use-cases-highlight-card-copy-cont">
											<h6 class="city-portal-four-factors__h6"><?php echo esc_html( get_the_title( get_sub_field( 'story' ) ) ); ?></h6>
											<p><?php echo esc_html( get_the_excerpt( get_sub_field( 'story' ) ) ); ?></p>
										</div>
										<?php
										// Get the story's thumbnail image
										wonder_include_template_file(
											'partials/image.php',
											array(
												'src' => get_the_post_thumbnail_url( get_sub_field( 'story' ), 'medium' ),
												'alt' => get_the_title( get_sub_field( 'story' ) ),
												'class' => 'city-portal-four-factors__use-cases-highlight-card-img',
											)
										);
										?>
									</a>
								</div>
							</section>
							<?php } ?>

						</article>
							<?php
							$c++;
						}
						?>
					</div>

				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Four Factors: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->


			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Partners ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="partners" class="city-portal__section city-portal__section--partners">
				<div class="city-portal-partners__grid">
					<div class="city-portal-partners__contributer">
						<h2 class="city-portal-partners__h2">
							<?php the_field( 'partners_contributing_partner_headline' ); ?>
						</h2>
						<div class="city-portal-partners__logo">
							<?php
							$img = wonder_include_template_file(
								'partials/image.php',
								array(
									'acf' => get_field( 'partners_contributing_partner_logo' ),
									'size' => 'small',
								),
								true
							);

							wonder_include_template_file(
								'partials/button.php',
								array(
									'acf' => get_field( 'partners_contributing_partner_link' ),
									'text' => $img,
									'class' => 'city-portal-partners__logo-link',
									'eid_page_slug' => get_field( 'eid_page_slug' ),
									'eid_section_slug' => get_field( 'partners_eid_section_slug_eid_section_slug' ),
								)
							);
							?>
						</div>
						<p class="city-portal-partners__body">
							<?php the_field( 'partners_contributing_partner_subtext' ); ?>
						</p>
					</div>
					<div class="city-portal-partners__community">
						<h2 class="city-portal-partners__h2">
							<?php the_field( 'partners_research_partners_headline' ); ?>
						</h2>
						<div class="city-portal-partners__logos">
							<?php
							while ( have_rows( 'research_partners_logos' ) ) {
								the_row();

								$img = wonder_include_template_file(
									'partials/image.php',
									array(
										'acf' => get_sub_field( 'logo' ),
										'size' => 'small',
									),
									true
								);

								wonder_include_template_file(
									'partials/button.php',
									array(
										'acf' => get_sub_field( 'link' ),
										'text' => $img,
										'class' => 'city-portal-partners__logos-link',
										'eid_page_slug' => get_field( 'eid_page_slug' ),
										'eid_section_slug' => get_field( 'partners_eid_section_slug_eid_section_slug' ),
									)
								);
							}
							?>
						</div>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Partners: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Connect ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<section id="connect" class="city-portal__section city-portal__section--connect">
				<div class="city-portal-connect__grid">
					<h2 class="city-portal__h2">
						<?php the_field( 'connect_headline' ); ?>
					</h2>
					<p class="city-portal-connect__body">
						<?php the_field( 'connect_paragraph' ); ?>
					</p>
					<div class="global-cta-area">
						<?php
						wonder_include_template_file(
							'partials/button.php',
							array(
								'acf' => get_field( 'connect_button' ),
								'attachment' => 'lottie',
								'lottie_id' => 'city-portal-mail-ani',
								'class' => 'global-cta global-cta--dark-3 global-cta--lg',
								'eid_page_slug' => get_field( 'eid_page_slug' ),
								'eid_section_slug' => get_field( 'connect_eid_section_slug_eid_section_slug' ),
							)
						);
						?>
					</div>
				</div>
			</section>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Connect: End  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

			<nav id="socials" class="city-portal__social-btns">
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
		</main>

		<?php
	endwhile;
endif;
?>

<?php

wp_localize_script(
	'global',
	'portal_vars',
	array(
		'city_id' => $city_data['city_id'],
		'eid_page_slug' => get_field( 'eid_page_slug' ),
		'permalink' => get_permalink(),
		'total_national_microbusinesses' => get_field( 'city_summary_chart_national_microbusinesses' ),
		'total_national_small_businesses' => get_field( 'city_summary_chart_national_small_businesses' ),
		'total_national_medium_and_large_businesses' => get_field( 'city_summary_chart_national_medium_and_large_businesses' ),
		'vf_get_all_cities_for_city_portal' => vf_get_all_cities_for_city_portal(),
	)
);

get_footer(); ?>
