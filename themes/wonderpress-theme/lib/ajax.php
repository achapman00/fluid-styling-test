<?php
/**
 * Various endpoints for ajax calls
 *
 * @package Wonderpress Theme
 */

global $vf_enable_ajax_transients;
$vf_enable_ajax_transients = true;

/**
 * Get a specific county
 *
 * @param Integer $county_id The idea of the county.
 */
function vf_get_specific_county( $county_id ) {
	global $vf_data_ingestor_county_data_table_name;
	global $wpdb;

	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				county, 
				county as "value", 
				name, 
				AVG(vd) as vd_avg,
				MAX(counts) as total_microbusinesses,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') as latest_year
			FROM ' . $vf_data_ingestor_county_data_table_name . ' 
			WHERE 
				county = %s
				AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) 
				AND vd IS NOT NULL
				AND is_archived = 0
			GROUP BY 
				county, name 
			ORDER BY 
				name ASC',
			array(
				$county_id,
			)
		),
		ARRAY_A
	);
	// phpcs:enable

	if ( $results ) {
		return $results[0];
	}

	return null;
}

/**
 * Get all the locations: CBSAs and Counties
 */
function vf_get_all_locations() {
	global $vf_data_ingestor_cbsas_table_name;
	global $vf_data_ingestor_cbsa_data_table_name;
	global $vf_data_ingestor_county_data_table_name;
	global $vf_data_ingestor_county_to_cbsa_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__;

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	/**
	 * Get all the CBSAs
	 */
	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				cbsa_table.cbsa, 
				cbsa_table.cbsa as "value", 
				cbsa_table.name, 
				"cbsa" as type,
				AVG(data.vd) as vd_avg,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ') as latest_year 
			FROM ' . $vf_data_ingestor_cbsas_table_name . ' as cbsa_table
			LEFT JOIN ' . $vf_data_ingestor_cbsa_data_table_name . ' as data ON data.cbsa = cbsa_table.cbsa
			WHERE
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ')) 
				AND data.vd IS NOT NULL
				AND data.is_archived = 0
			GROUP BY 
				cbsa_table.cbsa, cbsa_table.name 
			ORDER BY 
				cbsa_table.name ASC',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['cbsas'] = $results;

	/**
	 * Get all the Counties
	 */
	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				county, 
				county as "value", 
				name, 
				AVG(vd) as vd_avg,
				MAX(counts) as total_microbusinesses,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') as latest_year
			FROM ' . $vf_data_ingestor_county_data_table_name . ' 
			WHERE 
				name != \'\' 
				AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) 
				AND vd IS NOT NULL
				AND is_archived = 0
			GROUP BY 
				county, name 
			ORDER BY 
				name ASC',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['counties'] = $results;

	/**
	 * Get all the County to CBSA mappings
	 */
	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT DISTINCT
				mapping.county, mapping.cbsa, county.pop_county 
			FROM ' . $vf_data_ingestor_county_to_cbsa_table_name . ' as mapping
			LEFT JOIN
				' . $vf_data_ingestor_county_data_table_name . ' as county on county.county = mapping.county
			WHERE
				county.is_archived = 0
			ORDER BY
				county.pop_county DESC',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['counties_to_cbsas'] = $results;

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX: Get all the locations: CBSAs and Counties
 */
function vf_ajax_get_all_locations() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		 die( 'Security check' );
	}

	$payload = vf_get_all_locations();

	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_all_locations', 'vf_ajax_get_all_locations' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_all_locations', 'vf_ajax_get_all_locations' );




/**
 * Get a city for the CITY PORTAL page
 *
 * @param String $city_id The ID of the city to grab from the DB.
 */
function vf_get_city_for_city_portal( $city_id ) {
	global $vf_data_ingestor_city_portal_data_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__ . '-' . $city_id;

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	/**
	 * Get all the CBSAs
	 */
	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				city_table.*
			FROM ' . $vf_data_ingestor_city_portal_data_table_name . ' as city_table
			WHERE
				city_id = %d 
				AND city_table.is_archived = 0
			LIMIT 1',
			array(
				$city_id,
			)
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['city'] = $results[0];

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX: Get all the locations: CBSAs and Counties
 */
function vf_ajax_get_city_for_city_portal() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		 die( 'Security check' );
	}

	// Verify nonce
	$city_id = isset( $_REQUEST['city_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['city_id'] ) ) : null;
	if ( ! $city_id ) {
		wp_send_json_error( array( 'msg' => 'Please provide a city_id.' ) );
	}

	$payload = vf_get_city_for_city_portal( $city_id );

	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_city_for_city_portal', 'vf_ajax_get_city_for_city_portal' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_city_for_city_portal', 'vf_ajax_get_city_for_city_portal' );




/**
 * Get all cities for the CITY PORTAL page
 */
function vf_get_all_cities_for_city_portal() {
	global $vf_data_ingestor_city_portal_data_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__;

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	/**
	 * Get all the CBSAs
	 */
	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				city_table.*
			FROM ' . $vf_data_ingestor_city_portal_data_table_name . ' as city_table
			WHERE
				city_table.is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['cities'] = $results;

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX: Get all the Cities
 */
function vf_ajax_get_all_cities_for_city_portal() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		 die( 'Security check' );
	}

	// Verify nonce
	$city_id = isset( $_REQUEST['city_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['city_id'] ) ) : null;
	if ( ! $city_id ) {
		wp_send_json_error( array( 'msg' => 'Please provide a city_id.' ) );
	}

	$payload = vf_get_all_cities_for_city_portal();

	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_all_cities_for_city_portal', 'vf_ajax_get_all_cities_for_city_portal' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_all_cities_for_city_portal', 'vf_ajax_get_all_cities_for_city_portal' );




/**
 * Get all the data for the EXPLORE section on the Data page
 */
function vf_get_data_page_explore_section_data() {
	global $vf_data_ingestor_cbsa_data_table_name;
	global $vf_data_ingestor_county_data_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__;

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	// GRAB SOME NATIONAL STATS / AVERAGES Run the query

	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$national_cbsa_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				MIN(vd) as vd_min, 
				MAX(vd) as vd_max, 
				AVG(vd) as vd_avg,
				AVG(unemp_percent) as unemp_avg,
				AVG(prosp19_ui) as prosp_avg,
				AVG(recovery19_ui) as rec_avg,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ') as latest_year,
				(SELECT MAX(month) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ')) as latest_month
			FROM ' . $vf_data_ingestor_cbsa_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ')) 
				AND vd IS NOT NULL
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$national_county_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				MIN(vd) as vd_min, 
				MAX(vd) as vd_max, 
				AVG(vd) as vd_avg,
				AVG(unemp_percent) as unemp_avg,
				AVG(prosp19_ui) as prosp_avg,
				AVG(recovery19_ui) as rec_avg,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') as latest_year,
				(SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) as latest_month
			FROM ' . $vf_data_ingestor_county_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')  
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) 
				AND vd IS NOT NULL
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	/**
	 * Get all the Counties
	 */
	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$all_counties_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				county, 
				county as "value", 
				name, 
				AVG(vd) as vd_avg,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') as latest_year
			FROM ' . $vf_data_ingestor_county_data_table_name . ' 
			WHERE 
				name != \'\' 
				AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . '))
				AND vd IS NOT NULL
				AND is_archived = 0
			GROUP BY 
				county, name 
			ORDER BY 
				pop_county DESC
			LIMIT 100',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['explore'] = array(
		'active_region' => 'national_county',
		'all_counties' => $all_counties_results,
		'national_cbsa' => array(
			'prosp_avg' => (float) $national_cbsa_results[0]['prosp_avg'],
			'rec_avg' => (float) $national_cbsa_results[0]['rec_avg'],
			'unemp_avg' => (float) $national_cbsa_results[0]['unemp_avg'],
			'vd_avg' => (float) $national_cbsa_results[0]['vd_avg'],
			'vd_min' => (float) $national_cbsa_results[0]['vd_min'],
			'vd_max' => (float) $national_cbsa_results[0]['vd_max'],
			'month' => (int) $national_cbsa_results[0]['latest_month'],
			'year' => (int) $national_cbsa_results[0]['latest_year'],
		),
		'national_county' => array(
			'prosp_avg' => (float) $national_county_results[0]['prosp_avg'],
			'rec_avg' => (float) $national_county_results[0]['rec_avg'],
			'unemp_avg' => (float) $national_county_results[0]['unemp_avg'],
			'vd_avg' => (float) $national_county_results[0]['vd_avg'],
			'vd_min' => (float) $national_county_results[0]['vd_min'],
			'vd_max' => (float) $national_county_results[0]['vd_max'],
			'month' => (int) $national_county_results[0]['latest_month'],
			'year' => (int) $national_county_results[0]['latest_year'],
		),
	);

	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX: Get all the data for the EXPLORE section on the Data page
 */
function vf_ajax_get_data_page_explore_section_data() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		 die( 'Security check' );
	}

	$payload = vf_get_data_page_explore_section_data();

	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_data_page_explore_section_data', 'vf_ajax_get_data_page_explore_section_data' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_data_page_explore_section_data', 'vf_ajax_get_data_page_explore_section_data' );




/**
 * Get all the data for the Hero Chart on the Data Page
 *
 * @param Int $total_bars The number of bars to prepare data for.
 */
function vf_get_data_page_hero_chart_data( $total_bars = 50 ) {
	global $vf_data_ingestor_county_data_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__;

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	/*
	 * Grab data for the HERO
	 * The bars will be populated by County data only (re: convo w Alex and team)
	 *
	 * 1. Get the lowest vd and the highest vd for all Counties
	 * 2. Divide the difference between them by 50 (which is the total number of bars in the chart)
	 * 3. Set up buckets for the 50 divisions
	 * 4. Loop through all Counties and, based on their VD, put them in each bucket
	 * 5. Return the number of Counties in each bucket
	 */

	$max_vd = 14;

	// GRAB SOME NATIONAL STATS / AVERAGES Run the query
	// This is used for understanding averages / min / max VD
	// which helps sort buckets
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$national_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				MIN(vd) as vd_min, 
				LEAST(' . $max_vd . ',MAX(vd)) as vd_max, 
				AVG(vd) as vd_avg
			FROM ' . $vf_data_ingestor_county_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) 
				AND vd IS NOT NULL
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	// Grab min / max / avg
	$avg = $national_results[0]['vd_avg'];
	$min = $national_results[0]['vd_min'];
	$max = $national_results[0]['vd_max'];

	$payload['hero_chart'] = array(
		'bars' => array(),
		'bucket_size' => 0,
		'vd_avg' => 0,
		'vd_max' => 0,
		'vd_min' => 0,
	);

	$payload['hero_chart']['vd_avg'] = (float) $national_results[0]['vd_avg'];
	$payload['hero_chart']['vd_max'] = (float) $national_results[0]['vd_max'];
	$payload['hero_chart']['vd_min'] = (float) $national_results[0]['vd_min'];

	$min_max_diff = $max - $min;
	$bucket_size = $min_max_diff / $total_bars;
	$payload['hero_chart']['bucket_size'] = $bucket_size;

	// Get all the Counties and determine which buckets they fit into
	$buckets = array();
	for ( $i = 0; $i < $total_bars; $i++ ) {
		$buckets[] = array(
			'min' => (float) $i * $bucket_size,
			'max' => (float) ( ( ( $i + 1 ) * $bucket_size ) - 0.01 ),
			'total' => 0,
		);
	}

	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT county, LEAST(' . $max_vd . ',AVG(vd)) as avg_vd 
				FROM ' . $vf_data_ingestor_county_data_table_name . ' 
				WHERE 
					year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
					AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) 
					AND vd IS NOT NULL
					AND is_archived = 0 
				GROUP BY county, year',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	// Tally the results per bucket
	foreach ( $results as $result ) {

		$avg_vd = (float) $result['avg_vd'];

		if ( $avg_vd >= $max_vd ) {
			$buckets[ count( $buckets ) - 1 ]['total']++;
			continue;
		}

		foreach ( $buckets as $i => $bucket ) {
			if ( $avg_vd >= $buckets[ $i ]['min'] && $avg_vd <= $buckets[ $i ]['max'] ) {
				$buckets[ $i ]['total']++;
				break;
			}
		}
	}

	$payload['hero_chart']['bars'] = $buckets;

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX: Get all the data for the Hero Chart on the Data Page
 */
function vf_ajax_get_data_page_hero_chart_data() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		 die( 'Security check' );
	}

	$total_bars = ( isset( $_POST['total_hero_bars'] ) ? (int) $_POST['total_hero_bars'] : 50 );

	$payload = vf_get_data_page_hero_chart_data( $total_bars );
	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_data_page_hero_chart_data', 'vf_ajax_get_data_page_hero_chart_data' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_data_page_hero_chart_data', 'vf_ajax_get_data_page_hero_chart_data' );





/**
 * Get the Venture Density for a given County
 *
 * @param String $county The county id to look for.
 **/
function vf_get_county_specifics_for_data_page( $county ) {
	global $vf_data_ingestor_county_data_table_name;
	global $vf_data_ingestor_county_index_data_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__ . '_' . md5( $county );

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				vd,
				unemp_percent,
				prosp19_ui,
				recovery19_ui,
				MAX(counts) as total_microbusinesses,
				year,
				month
			FROM ' . $vf_data_ingestor_county_data_table_name . '
			WHERE 
				county = %s
				AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) 
				AND is_archived = 0
			ORDER BY
				year DESC,
				month DESC
			LIMIT 1',
			array(
				$county,
			)
		),
		ARRAY_A
	);
	// phpcs:enable

	// Run the query
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				activity_index,
				year,
				month
			FROM ' . $vf_data_ingestor_county_index_data_table_name . '
			WHERE 
				county = %s
				AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_index_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ')) 
				AND is_archived = 0
			ORDER BY
				year DESC,
				month DESC
			LIMIT 1',
			array(
				$county,
			)
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['activity_index'] = (float) $results[0]['activity_index'];
	$payload['vd'] = (float) $results[0]['vd'];
	$payload['unemp_percent'] = (float) $results[0]['unemp_percent'];
	$payload['prosp19_ui'] = (float) $results[0]['prosp19_ui'];
	$payload['recovery19_ui'] = (float) $results[0]['recovery19_ui'];
	$payload['total_microbusinesses'] = (float) $results[0]['total_microbusinesses'];
	$payload['year'] = (int) $results[0]['year'];
	$payload['month'] = (int) $results[0]['month'];

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX: Get the Venture Density for a given County
 **/
function vf_ajax_get_county_specifics_for_data_page() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		wp_send_json_error( array( 'msg' => 'Security check' ) );
	}

	$county = isset( $_REQUEST['county'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['county'] ) ) : null;
	if ( ! $county ) {
		wp_send_json_error( array( 'msg' => 'Please provide a county.' ) );
	}

	$payload = vf_get_county_specifics_for_data_page( $county );

	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_county_specifics_for_data_page', 'vf_ajax_get_county_specifics_for_data_page' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_county_specifics_for_data_page', 'vf_ajax_get_county_specifics_for_data_page' );






/**
 * Get the data for the compare chart on the data page
 *
 * @param String $selection_1 The county ID that was selected by the user.
 * @param String $comparison The data comparison type.
 */
function vf_get_compare_chart_for_data_page( $selection_1 = null, $comparison = null ) {
	global $vf_data_ingestor_cbsas_table_name;
	global $vf_data_ingestor_cbsa_data_table_name;
	global $vf_data_ingestor_county_data_table_name;
	global $vf_data_ingestor_county_index_data_table_name;
	global $vf_data_ingestor_wam_data_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__ . '_' . md5( $selection_1 ) . '_' . md5( $comparison );

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	// Initialize our payload for the page
	$payload = array();
	$payload['labels'] = array();
	$payload['datasets'] = array();

	// GATHER THE NATIONAL CBSA DATA
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(vd) as vd_avg,
				year,
				month
			FROM ' . $vf_data_ingestor_cbsa_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ')
				AND vd IS NOT NULL
				AND is_archived = 0
			GROUP BY
				year, month
			ORDER BY
				year, month',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$dataset = array(
		'label' => false,
		'borderColor' => '#00C2FF',
		'data' => array(),
		'fill' => false,
		'pointRadius' => 0,
		'yAxisID' => 'vd',
	);
	foreach ( $results as $result ) {
		$dataset['data'][] = (float) $result['vd_avg'];

		$month = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );
		if ( ! in_array( $month, $payload['labels'] ) ) {
			$payload['labels'][] = $month;
		}
	}
	$payload['datasets'][] = $dataset;

	// GATHER THE NATIONAL COUNTY DATA
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(vd) as vd_avg,
				year,
				month
			FROM ' . $vf_data_ingestor_county_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')
				AND vd IS NOT NULL
				AND pop_county > 100000
				AND is_archived = 0
			GROUP BY
				year, month
			ORDER BY
				year, month',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$dataset = array(
		'label' => false,
		'borderColor' => '#0DD0D0',
		'data' => array(),
		'fill' => false,
		'pointRadius' => 0,
		'yAxisID' => 'vd',
	);

	foreach ( $results as $result ) {
		$dataset['data'][] = (float) $result['vd_avg'];

		$month = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );
		if ( ! in_array( $month, $payload['labels'] ) ) {
			$payload['labels'][] = $month;
		}
	}
	$payload['datasets'][] = $dataset;

	// GATHER THE SPECIFIC COUNTY DATA
	if ( $selection_1 ) {
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT 
					AVG(vd) as vd_avg,
					year,
					month
				FROM ' . $vf_data_ingestor_county_data_table_name . '
				WHERE 
					county = %s
					AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')
					AND vd IS NOT NULL
					AND is_archived = 0
				GROUP BY
					year, month
				ORDER BY
					year, month',
				array(
					$selection_1,
				)
			),
			ARRAY_A
		);
		// phpcs:enable

		$dataset = array(
			'label' => false,
			'borderColor' => '#A965FF',
			'data' => array(),
			'fill' => false,
			'pointRadius' => 0,
			'yAxisID' => 'vd',
		);
		foreach ( $results as $result ) {
			$dataset['data'][] = (float) $result['vd_avg'];

			$month = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );
			if ( ! in_array( $month, $payload['labels'] ) ) {
				$payload['labels'][] = $month;
			}
		}
		$payload['datasets'][] = $dataset;
	}

	// GATHER ANY COMPARISON DATA
	if ( $comparison ) {

		$wam_comparisons = array(
			'orders',
			'traffic',
		);

		$index_comparisons = array(
			'activity_index',
		);

		if ( in_array( $comparison, $wam_comparisons ) ) {

			// GET THE WAM COMPARISON DATA
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT 
						AVG(' . $comparison . ') as comparison,
						year,
						month
					FROM ' . $vf_data_ingestor_wam_data_table_name . '
					WHERE 
						year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_wam_data_table_name . ')
						AND ' . $comparison . ' IS NOT NULL
						AND is_archived = 0
					GROUP BY
						year, month
					ORDER BY
						year, month',
					array()
				),
				ARRAY_A
			);
			// phpcs:enable

			$dataset = array(
				'label' => false,
				'borderColor' => '#00E356',
				'borderDash' => array( 5, 10 ),
				'data' => array(),
				'fill' => false,
				'pointRadius' => 0,
				'yAxisID' => 'comparison',
			);

			$default = array();
			foreach ( $payload['labels'] as $key => $label ) {
				$default[ $key ] = null;
			}

			foreach ( $results as $result ) {
				$comparison_label = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );

				foreach ( $payload['labels'] as $key => $label ) {
					if ( $comparison_label == $label ) {
						$default[ $key ] = (float) $result['comparison'];
					}
				}
			}

			$dataset['data'] = $default;

			$payload['datasets'][] = $dataset;

		} elseif ( in_array( $comparison, $index_comparisons ) ) {

			// GET THE INDEX COMPARISON DATA
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT 
						AVG(' . $comparison . ') as comparison,
						year,
						month
					FROM ' . $vf_data_ingestor_county_index_data_table_name . '
					WHERE 
						year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ')
						AND ' . $comparison . ' IS NOT NULL
						AND is_archived = 0
					GROUP BY
						year, month
					ORDER BY
						year, month',
					array()
				),
				ARRAY_A
			);
			// phpcs:enable

			$dataset = array(
				'label' => false,
				'borderColor' => '#000000',
				'borderDash' => array( 5, 10 ),
				'data' => array(),
				'fill' => false,
				'pointRadius' => 0,
				'yAxisID' => 'comparison',
			);

			$default = array();
			foreach ( $payload['labels'] as $key => $label ) {
				$default[ $key ] = null;
			}

			foreach ( $results as $result ) {
				$comparison_label = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );

				foreach ( $payload['labels'] as $key => $label ) {
					if ( $comparison_label == $label ) {
						$default[ $key ] = (float) $result['comparison'];
					}
				}
			}

			$dataset['data'] = $default;

			$payload['datasets'][] = $dataset;

		} else {

			// GET THE NATIONAL CBSA COMPARISON DATA
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT 
						AVG(' . $comparison . ') as comparison,
						year,
						month
					FROM ' . $vf_data_ingestor_cbsa_data_table_name . '
					WHERE 
						year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ')
						AND ' . $comparison . ' IS NOT NULL
						AND is_archived = 0
					GROUP BY
						year, month
					ORDER BY
						year, month',
					array()
				),
				ARRAY_A
			);
			// phpcs:enable

			$dataset = array(
				'label' => false,
				'borderColor' => '#0DD0D0',
				'borderDash' => array( 10, 10 ),
				'data' => array(),
				'fill' => false,
				'pointRadius' => 0,
				'yAxisID' => 'comparison',
			);

			$default = array();
			foreach ( $payload['labels'] as $key => $label ) {
				$default[ $key ] = null;
			}

			foreach ( $results as $result ) {
				$comparison_label = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );

				foreach ( $payload['labels'] as $key => $label ) {
					if ( $comparison_label == $label ) {
						$default[ $key ] = (float) $result['comparison'];
					}
				}
			}

			$dataset['data'] = $default;

			$payload['datasets'][] = $dataset;

			// GET THE NATIONAL COUNTY COMPARISON DATA
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT 
						AVG(' . $comparison . ') as comparison,
						year,
						month
					FROM ' . $vf_data_ingestor_county_data_table_name . '
					WHERE 
						year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')
						AND ' . $comparison . ' IS NOT NULL
						AND pop_county > 100000
						AND is_archived = 0
					GROUP BY
						year, month
					ORDER BY
						year, month',
					array()
				),
				ARRAY_A
			);
			// phpcs:enable

			$dataset = array(
				'label' => false,
				'borderColor' => '#00C2FF',
				'borderDash' => array( 10, 10 ),
				'data' => array(),
				'fill' => false,
				'pointRadius' => 0,
				'yAxisID' => 'comparison',
			);
			foreach ( $results as $result ) {
				$dataset['data'][] = (float) $result['comparison'];
			}
			$payload['datasets'][] = $dataset;

			// GET THE SPECIFIC COUNTY DATA
			if ( $selection_1 ) {
				// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
				$results = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT 
							AVG(' . $comparison . ') as comparison,
							year,
							month
						FROM ' . $vf_data_ingestor_county_data_table_name . '
						WHERE 
							county = %s
							AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')
							AND ' . $comparison . ' IS NOT NULL
							AND is_archived = 0
						GROUP BY
							year, month
						ORDER BY
							year, month',
						array(
							$selection_1,
						)
					),
					ARRAY_A
				);
				// phpcs:enable

				$dataset = array(
					'label' => false,
					'borderColor' => '#A965FF',
					'borderDash' => array( 10, 10 ),
					'data' => array(),
					'fill' => false,
					'pointRadius' => 0,
					'yAxisID' => 'comparison',
				);
				foreach ( $results as $result ) {
					$dataset['data'][] = (float) $result['comparison'];
				}
				$payload['datasets'][] = $dataset;
			}
		}
	}

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX: Get the data for the compare chart on the data page
 */
function vf_ajax_get_compare_chart_for_data_page() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		 die( 'Security check' );
	}

	$selection_1 = isset( $_REQUEST['selection_1'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['selection_1'] ) ) : null;

	$comparison = ( isset( $_REQUEST['comparison'] ) && array_key_exists( sanitize_text_field( wp_unslash( $_REQUEST['comparison'] ) ), DATA_PAGE_AVAILABLE_COMPARISONS ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['comparison'] ) ) : null;

	// Send it!
	$payload = vf_get_compare_chart_for_data_page( $selection_1, $comparison );
	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_compare_chart_for_data_page', 'vf_ajax_get_compare_chart_for_data_page' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_compare_chart_for_data_page', 'vf_ajax_get_compare_chart_for_data_page' );





/**
 * Get the numbers for the compare table on the data page
 *
 * @param String $county The county ID to include.
 **/
function vf_get_compare_table_data_for_data_page( $county = null ) {
	global $vf_data_ingestor_cbsa_data_table_name;
	global $vf_data_ingestor_cbsa_index_data_table_name;
	global $vf_data_ingestor_county_data_table_name;
	global $vf_data_ingestor_county_index_data_table_name;
	global $vf_enable_ajax_transients;
	global $wpdb;

	$transient_name = __FUNCTION__ . '_' . md5( $county );

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	if ( $county ) {
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$selection_results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT 
					AVG(vd) as vd_avg,
					AVG(havd) as havd_avg,
					AVG(unemp_percent) as unemp_avg,
					AVG(prosp19_ui) as prosp_avg,
					AVG(recovery19_ui) as recovery_avg,
					AVG(change_medinc_1619) as income_median,
					(SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') as latest_year,
					(SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) as latest_month
				FROM ' . $vf_data_ingestor_county_data_table_name . '
				WHERE 
					county = %s
					AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
					AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . '))
					AND vd IS NOT NULL
					AND is_archived = 0',
				array(
					$county,
				)
			),
			ARRAY_A
		);
		// phpcs:enable		
	}

	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$national_cbsa_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(vd) as vd_avg,
				AVG(havd) as havd_avg,
				AVG(unemp_percent) as unemp_avg,
				AVG(prosp19_ui) as prosp_avg,
				AVG(recovery19_ui) as recovery_avg,
				AVG(change_medinc_1619) as income_median,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ') as latest_year,
				(SELECT MAX(month) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ')) as latest_month
			FROM ' . $vf_data_ingestor_cbsa_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_cbsa_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_data_table_name . '))
				AND vd IS NOT NULL
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$national_cbsa_index_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(activity_index) as activity_index,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_index_data_table_name . ') as latest_year,
				(SELECT MAX(month) FROM ' . $vf_data_ingestor_cbsa_index_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_index_data_table_name . ')) as latest_month
			FROM ' . $vf_data_ingestor_cbsa_index_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_index_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_cbsa_index_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_cbsa_index_data_table_name . '))
				AND activity_index IS NOT NULL
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$national_county_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(vd) as vd_avg,
				AVG(havd) as havd_avg,
				AVG(unemp_percent) as unemp_avg,
				AVG(prosp19_ui) as prosp_avg,
				AVG(recovery19_ui) as recovery_avg,
				AVG(change_medinc_1619) as income_median,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') as latest_year,
				(SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ')) as latest_month
			FROM ' . $vf_data_ingestor_county_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_data_table_name . '))
				AND vd IS NOT NULL
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$national_county_index_results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(activity_index) as activity_index,
				(SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ') as latest_year,
				(SELECT MAX(month) FROM ' . $vf_data_ingestor_county_index_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ')) as latest_month
			FROM ' . $vf_data_ingestor_county_index_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_index_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . '))
				AND activity_index IS NOT NULL
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$payload['compare_table'] = array(
		'national_cbsa' => array(
			'activity_index' => (float) $national_cbsa_index_results[0]['activity_index'],
			'income_median' => '$' . number_format( $national_cbsa_results[0]['income_median'] ),
			'prosp_avg' => (float) $national_cbsa_results[0]['prosp_avg'],
			'recovery_avg' => round( $national_cbsa_results[0]['recovery_avg'], 2 ),
			'unemp_avg' => round( $national_cbsa_results[0]['unemp_avg'] ) . '%',
			'vd_avg' => round( $national_cbsa_results[0]['vd_avg'], 2 ),
			'havd_avg' => round( $national_county_results[0]['havd_avg'], 2 ),
			'month' => (int) $national_cbsa_results[0]['latest_month'],
			'year' => (int) $national_cbsa_results[0]['latest_year'],
		),
		'national_county' => array(
			'activity_index' => (float) $national_county_index_results[0]['activity_index'],
			'income_median' => '$' . number_format( $national_county_results[0]['income_median'] ),
			'prosp_avg' => (float) $national_county_results[0]['prosp_avg'],
			'recovery_avg' => round( $national_cbsa_results[0]['recovery_avg'], 2 ),
			'unemp_avg' => round( $national_county_results[0]['unemp_avg'] ) . '%',
			'vd_avg' => round( $national_county_results[0]['vd_avg'], 2 ),
			'havd_avg' => round( $national_county_results[0]['havd_avg'], 2 ),
			'month' => (int) $national_county_results[0]['latest_month'],
			'year' => (int) $national_county_results[0]['latest_year'],
		),
	);

	if ( isset( $selection_results ) && isset( $selection_results[0] ) ) {
		$payload['compare_table']['selection'] = array(
			'income_median' => '$' . number_format( $selection_results[0]['income_median'] ),
			'prosp_avg' => (float) $selection_results[0]['prosp_avg'],
			'recovery_avg' => round( $selection_results[0]['recovery_avg'], 2 ),
			'unemp_avg' => round( $selection_results[0]['unemp_avg'] ) . '%',
			'vd_avg' => round( $selection_results[0]['vd_avg'], 2 ),
			'havd_avg' => round( $selection_results[0]['havd_avg'], 2 ),
			'month' => (int) $selection_results[0]['latest_month'],
			'year' => (int) $selection_results[0]['latest_year'],
		);
	}

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );
	return $payload;
}

/**
 * AJAX: Get the numbers for the compare table on the data page
 **/
function vf_ajax_get_compare_table_data_for_data_page() {

	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		wp_send_json_error( array( 'msg' => 'Security check' ) );
	}

	$county = isset( $_REQUEST['county'] ) && ! empty( $_REQUEST['county'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['county'] ) ) : null;

	// Send it!
	$payload = vf_get_compare_table_data_for_data_page( $county );
	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_compare_table_data_for_data_page', 'vf_ajax_get_compare_table_data_for_data_page' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_compare_table_data_for_data_page', 'vf_ajax_get_compare_table_data_for_data_page' );


/**
 * Get the national stats for activity index.
 **/
function vf_get_national_activity_index_stats() {
	global $vf_data_ingestor_county_index_data_table_name;
	global $wpdb;

	// Grab vals for search bars
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(activity_index) as avg,
				MAX(activity_index) as max,
				MIN(activity_index) as min
			FROM ' . $vf_data_ingestor_county_index_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ') 
				AND month = (SELECT MAX(month) FROM ' . $vf_data_ingestor_county_index_data_table_name . ' WHERE year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ')) 
				AND is_archived = 0',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	return array(
		'avg' => $results[0]['avg'],
		'min' => $results[0]['min'],
		'max' => $results[0]['max'],

	);

}

/**
 * Get compare chart data
 *
 * @param Integer $county The id of the county.
 **/
function vf_get_compare_chart_for_index_page( $county = null ) {
	global $vf_data_ingestor_county_index_data_table_name;
	global $wpdb;

	$transient_name = __FUNCTION__ . '_' . md5( $selection_1 ) . '_' . md5( $comparison );

	if ( isset( $_GET['clear_transients'] ) && sanitize_text_field( wp_unslash( $_GET['clear_transients'] ) ) ) {
		delete_transient( $transient_name );
	}

	// Initialize our payload for the page
	// Check for cached version, first
	$payload = ( $vf_enable_ajax_transients ) ? get_transient( $transient_name ) : array();
	if ( $payload ) {
		return $payload;
	}

	// Initialize our payload for the page
	$payload = array();
	$payload['labels'] = array();
	$payload['datasets'] = array();

	// GATHER THE NATIONAL COUNTY DATA
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT 
				AVG(activity_index) as activity_index,
				year,
				month
			FROM ' . $vf_data_ingestor_county_index_data_table_name . '
			WHERE 
				year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ')
				AND activity_index IS NOT NULL
				AND is_archived = 0
			GROUP BY
				year, month
			ORDER BY
				year, month',
			array()
		),
		ARRAY_A
	);
	// phpcs:enable

	$dataset = array(
		'label' => false,
		'borderColor' => '#00C2FF',
		'data' => array(),
		'fill' => false,
		'pointRadius' => 0,
		'yAxisID' => 'activity_index',
	);
	foreach ( $results as $result ) {
		$dataset['data'][] = (float) $result['activity_index'];

		$month = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );
		if ( ! in_array( $month, $payload['labels'] ) ) {
			$payload['labels'][] = $month;
		}
	}
	$payload['datasets'][] = $dataset;

	// GATHER THE SPECIFIC COUNTY DATA
	if ( $county ) {
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT 
					AVG(activity_index) as activity_index,
					year,
					month
				FROM ' . $vf_data_ingestor_county_index_data_table_name . '
				WHERE 
					county = %s
					AND year = (SELECT MAX(year) FROM ' . $vf_data_ingestor_county_index_data_table_name . ')
					AND activity_index IS NOT NULL
					AND is_archived = 0
				GROUP BY
					year, month
				ORDER BY
					year, month',
				array(
					$county,
				)
			),
			ARRAY_A
		);
		// phpcs:enable

		$dataset = array(
			'label' => false,
			'borderColor' => '#0DD0D0',
			'data' => array(),
			'fill' => false,
			'pointRadius' => 0,
			'yAxisID' => 'activity_index',
		);
		foreach ( $results as $result ) {
			$dataset['data'][] = (float) $result['activity_index'];

			$month = gmdate( 'M \'y', strtotime( $result['year'] . '-' . $result['month'] . '-01' ) );
			if ( ! in_array( $month, $payload['labels'] ) ) {
				$payload['labels'][] = $month;
			}
		}
		$payload['datasets'][] = $dataset;
	}

	// Send it!
	set_transient( $transient_name, $payload, ( 60 * 60 * 24 ) );

	return $payload;
}

/**
 * AJAX request for getting compare chart data
 **/
function vf_ajax_get_compare_chart_for_index_page() {
	// Verify nonce
	$nonce = isset( $_REQUEST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ajax_nonce'] ) ) : null;
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
		wp_send_json_error( array( 'msg' => 'Security check' ) );
	}

	$county = isset( $_REQUEST['county'] ) && ! empty( $_REQUEST['county'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['county'] ) ) : null;

	// Send it!
	$payload = vf_get_compare_chart_for_index_page( $county );
	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_vf_ajax_get_compare_chart_for_index_page', 'vf_ajax_get_compare_chart_for_index_page' );
add_action( 'wp_ajax_nopriv_vf_ajax_get_compare_chart_for_index_page', 'vf_ajax_get_compare_chart_for_index_page' );
