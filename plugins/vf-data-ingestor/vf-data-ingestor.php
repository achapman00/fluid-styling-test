<?php
/**
* Plugin Name: Venture Forward Data Ingestor
* Description: A custom plugin to ingest Venture Forward data for use in a WordPress website.
* Version: 1.0
* Author: Johnnie Munger
* Author URI: https://wonderful.io
**/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once 'class-vfdataingestor.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

global $wpdb;

global $vf_data_ingestor_db_version;
$vf_data_ingestor_db_version = '1.0';

global $vf_data_ingestor_county_to_cbsa_table_name;
$vf_data_ingestor_county_to_cbsa_table_name = $wpdb->prefix . 'vf_county_to_cbsa';

global $vf_data_ingestor_cbsa_data_table_name;
$vf_data_ingestor_cbsa_data_table_name = $wpdb->prefix . 'vf_cbsa_data';

global $vf_data_ingestor_cbsa_index_data_table_name;
$vf_data_ingestor_cbsa_index_data_table_name = $wpdb->prefix . 'vf_cbsa_index_data';

global $vf_data_ingestor_county_index_data_table_name;
$vf_data_ingestor_county_index_data_table_name = $wpdb->prefix . 'vf_county_index_data';

global $vf_data_ingestor_county_data_table_name;
$vf_data_ingestor_county_data_table_name = $wpdb->prefix . 'vf_county_data';

global $vf_data_ingestor_cbsas_table_name;
$vf_data_ingestor_cbsas_table_name = $wpdb->prefix . 'vf_cbsas';

global $vf_data_ingestor_counties_table_name;
$vf_data_ingestor_counties_table_name = $wpdb->prefix . 'vf_counties';

global $vf_data_ingestor_wam_data_table_name;
$vf_data_ingestor_wam_data_table_name = $wpdb->prefix . 'vf_wam_data';

global $vf_data_ingestor_city_portal_data_table_name;
$vf_data_ingestor_city_portal_data_table_name = $wpdb->prefix . 'vf_city_portal_data';

function vf_data_ingestor_install() {
	global $wpdb;
	global $vf_data_ingestor_db_version;
	global $vf_data_ingestor_cbsa_data_table_name;
	global $vf_data_ingestor_cbsa_index_data_table_name;
	global $vf_data_ingestor_county_index_data_table_name;
	global $vf_data_ingestor_city_portal_data_table_name;
	global $vf_data_ingestor_county_data_table_name;
	global $vf_data_ingestor_county_to_cbsa_table_name;
	global $vf_data_ingestor_cbsas_table_name;
	global $vf_data_ingestor_counties_table_name;
	global $vf_data_ingestor_wam_data_table_name;

	$charset_collate = $wpdb->get_charset_collate();

	// Create the CITY PORTAL DATA table
	$sql = "CREATE TABLE $vf_data_ingestor_city_portal_data_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		sum_micro mediumint(6) UNSIGNED NOT NULL,
		sum_smb mediumint(6) UNSIGNED NOT NULL,
		sum_medlrg mediumint(6) UNSIGNED NOT NULL,
		pop19 mediumint(9) UNSIGNED NOT NULL,
		pop17 mediumint(9) UNSIGNED NOT NULL,
		city_id mediumint(6) UNSIGNED NOT NULL,
		q1_21vd decimal(14,6) NOT NULL,
		change_vd decimal(14,6) NOT NULL,
		bb19 decimal(13,6),
		medinc19 decimal(13,6),
		housing19 decimal(13,6),
		college19 decimal(13,6),
		poverty19 decimal(13,6),
		unemp_dec20 decimal(13,6),
		city_size varchar(25) NOT NULL,
		city_name varchar(100) NOT NULL,
		pop_3yr decimal(14,6) NOT NULL,
		avg_pop19_sm decimal(12,3),
		avg_pop19_med decimal(12,3),
		avg_pop19_lrg decimal(12,3),
		avg_q1_21vd_sm decimal(14,6),
		avg_q1_21vd_med decimal(14,6),
		avg_q1_21vd_lrg decimal(14,6),
		avg_change_vd_sm decimal(14,6),
		avg_change_vd_med decimal(14,6),
		avg_change_vd_lrg decimal(14,6),
		avg_bb19_sm decimal(14,6),
		avg_bb19_med decimal(14,6),
		avg_bb19_lrg decimal(14,6),
		avg_medinc19_sm decimal(12,3),
		avg_medinc19_med decimal(12,3),
		avg_medinc19_lrg decimal(12,3),
		avg_housing19_sm decimal(13,4),
		avg_housing19_med decimal(13,4),
		avg_housing19_lrg decimal(13,4),
		avg_college19_sm decimal(14,6),
		avg_college19_med decimal(14,6),
		avg_college19_lrg decimal(14,6),
		avg_poverty19_sm decimal(14,6),
		avg_poverty19_med decimal(14,6),
		avg_poverty19_lrg decimal(14,6),
		avg_unemp_dec20_sm decimal(14,6),
		avg_unemp_dec20_med decimal(14,6),
		avg_unemp_dec20_lrg decimal(14,6),
		avg_pop_3yr_sm decimal(14,6),
		avg_pop_3yr_med decimal(14,6),
		avg_pop_3yr_lrg decimal(14,6),
		pop19_peercomparison_sm decimal(14,6),
		pop19_peercomparison_med decimal(14,6),
		pop19_peercomparison_lrg decimal(14,6),
		q1_21vd_peercomparison_sm decimal(14,6),
		q1_21vd_peercomparison_med decimal(14,6),
		q1_21vd_peercomparison_lrg decimal(14,6),
		change_vd_peercomparison_sm decimal(14,6),
		change_vd_peercomparison_med decimal(14,6),
		change_vd_peercomparison_lrg decimal(14,6),
		bb19_peercomparison_sm decimal(14,6),
		bb19_peercomparison_med decimal(14,6),
		bb19_peercomparison_lrg decimal(14,6),
		medinc19_peercomparison_sm decimal(14,6),
		medinc19_peercomparison_med decimal(14,6),
		medinc19_peercomparison_lrg decimal(14,6),
		housing19_peercomparison_sm decimal(14,6),
		housing19_peercomparison_med decimal(14,6),
		housing19_peercomparison_lrg decimal(14,6),
		college19_peercomparison_sm decimal(14,6),
		college19_peercomparison_med decimal(14,6),
		college19_peercomparison_lrg decimal(14,6),
		poverty19_peercomparison_sm decimal(14,6),
		poverty19_peercomparison_med decimal(14,6),
		poverty19_peercomparison_lrg decimal(14,6),
		unemp_dec20_peercomparison_sm decimal(14,6),
		unemp_dec20_peercomparison_med decimal(14,6),
		unemp_dec20_peercomparison_lrg decimal(14,6),
		pop_3yr_peercomparison_sm decimal(14,6),
		pop_3yr_peercomparison_med decimal(14,6),
		pop_3yr_peercomparison_lrg decimal(14,6),
		pop19_nationalcomparison decimal(14,6),
		q1_21vd_nationalcomparison decimal(14,6),
		change_vd_nationalcomparison decimal(14,6),
		bb19_nationalcomparison decimal(14,6),
		medinc19_nationalcomparison decimal(14,6),
		housing19_nationalcomparison decimal(14,6),
		college19_nationalcomparison decimal(14,6),
		poverty19_nationalcomparison decimal(14,6),
		unemp_dec20_nationalcomparison decimal(14,6),
		pop_3yr_nationalcomparison decimal(14,6),
		countymapflag tinyint(1),
		legendflag tinyint(1),
		is_archived tinyint(1),
		ingestion_group varchar(25) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the WAM DATA table
	$sql = "CREATE TABLE $vf_data_ingestor_wam_data_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		month tinyint(2) UNSIGNED NOT NULL,
		year smallint(4) UNSIGNED NOT NULL,
		traffic decimal(4,2) NULL,
		orders decimal(4,2) NULL,
		is_archived tinyint(1),
		ingestion_group varchar(25) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the COUNTY TO CBSA MAPPING table
	$sql = "CREATE TABLE $vf_data_ingestor_county_to_cbsa_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		cbsa mediumint(6) UNSIGNED NOT NULL,
		county mediumint(6) UNSIGNED NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the CBSA DATA table
	$sql = "CREATE TABLE $vf_data_ingestor_cbsa_data_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		cbsa mediumint(6) UNSIGNED NOT NULL,
		month tinyint(2) UNSIGNED NOT NULL,
		year smallint(4) UNSIGNED NOT NULL,
		com_vac decimal(4,2) NULL,
		rev_dec decimal(4,2) NULL,
		spend_all decimal(4,2) NULL,
		pop19 integer(8) NULL,
		prosp19_ui decimal(4,2) NULL,
		recovery19_ui decimal(4,2) NULL,
		unemp_percent decimal(4,2) NULL,
		vd decimal(4,2) NULL,
		havd decimal(4,2) NULL,
		change_medinc_1619 smallint(4) NULL,
		is_archived tinyint(1),
		ingestion_group varchar(25) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the CBSA INDEX DATA table
	$sql = "CREATE TABLE $vf_data_ingestor_cbsa_index_data_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		cbsa mediumint(6) UNSIGNED NOT NULL,
		name varchar(48) NOT NULL,
		month tinyint(2) UNSIGNED NOT NULL,
		year smallint(4) UNSIGNED NOT NULL,
		activity_index decimal(5,2) NULL,
		is_archived tinyint(1),
		ingestion_group varchar(25) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the COUNTY INDEX DATA table
	$sql = "CREATE TABLE $vf_data_ingestor_county_index_data_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		county mediumint(6) UNSIGNED NOT NULL,
		name varchar(48) NOT NULL,
		month tinyint(2) UNSIGNED NOT NULL,
		year smallint(4) UNSIGNED NOT NULL,
		activity_index decimal(5,2) NULL,
		is_archived tinyint(1),
		ingestion_group varchar(25) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the COUNTY DATA table
	$sql = "CREATE TABLE $vf_data_ingestor_county_data_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		county mediumint(6) UNSIGNED NOT NULL,
		name varchar(48) NOT NULL,
		month tinyint(2) UNSIGNED NOT NULL,
		year smallint(4) UNSIGNED NOT NULL,
		prosp19_ui decimal(4,2) NULL,
		recovery19_ui decimal(4,2) NULL,
		unemp_percent decimal(4,2) NULL,
		vd decimal(4,2) NULL,
		havd decimal(4,2) NULL,
		change_medinc_1619 smallint(4) NULL,
		counts integer(8) NULL,
		pop_county integer(8) NULL,
		is_archived tinyint(1),
		ingestion_group varchar(25) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the CBSAs table
	$sql = "CREATE TABLE $vf_data_ingestor_cbsas_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		cbsa mediumint(6) UNSIGNED NOT NULL,
		name varchar(125) NOT NULL,
		city_name varchar(125) NOT NULL,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	dbDelta( $sql );

	// Create the Counties table
	// $sql = "CREATE TABLE $vf_data_ingestor_counties_table_name (
	// 	id mediumint(9) NOT NULL AUTO_INCREMENT,
	// 	county mediumint(6) UNSIGNED NOT NULL,
	// 	name varchar(48) NOT NULL,
	// 	created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	// 	PRIMARY KEY (id)
	// ) $charset_collate;";
	// dbDelta( $sql );

	add_option( 'vf_data_ingestor_db_version', $vf_data_ingestor_db_version );
}
register_activation_hook( __FILE__, 'vf_data_ingestor_install' );


//**************************
// ADMIN MENU
//**************************

add_action( 'admin_menu', 'vf_data_ingestor_admin_menu' );
function vf_data_ingestor_admin_menu() {
	add_menu_page( 'Data Ingestor', 'Data Ingestor', 'import', 'vf-data-ingestor', 'vf_data_ingestor_admin_page', 'dashicons-cloud-upload' , 76 );
}


//**************************
// ADMIN PAGE
//**************************

function vf_data_ingestor_admin_page() {

	// This function creates the output for the admin page.
	// It also checks the value of the $_POST variable to see whether
	// there has been a form submission. 

	// The check_admin_referer is a WordPress function that does some security
	// checking and is recommended good practice.

	// General check for user permissions.
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient pilchards to access this page.') );
	}

	// Start building the page
	include_once 'templates/index.php';

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_city_portal_data_ingestion_requested'])) {

		// the button has been pressed AND we've passed the security check
		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			$ingestor = new VFDataIngestor;
			$ingestor->ingestCityPortalDataFile($_FILES['imported_file']);
		}		
	}

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_wam_data_ingestion_requested'])) {

		// the button has been pressed AND we've passed the security check
		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			$ingestor = new VFDataIngestor;
			$ingestor->ingestWamDataFile($_FILES['imported_file']);
		}		
	}

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_county_to_cbsa_ingestion_requested'])) {

		// the button has been pressed AND we've passed the security check
		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			$ingestor = new VFDataIngestor;
			$ingestor->ingestCountyToCbsaFile($_FILES['imported_file']);
		}		
	}

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_cbsa_ingestion_requested'])) {
		// the button has been pressed AND we've passed the security check

		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			$ingestor = new VFDataIngestor;
			$ingestor->ingestCbsaFile($_FILES['imported_file']);
		}
	}

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_cbsa_data_ingestion_requested'])) {
		// the button has been pressed AND we've passed the security check

		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			$ingestor = new VFDataIngestor;
			$ingestor->ingestCbsaDataFile($_FILES['imported_file']);
		}
	}

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_cbsa_index_data_ingestion_requested'])) {
		// the button has been pressed AND we've passed the security check

		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			$ingestor = new VFDataIngestor;
			$ingestor->ingestCbsaIndexDataFile($_FILES['imported_file']);
		}
	}

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_county_index_data_ingestion_requested'])) {
		// the button has been pressed AND we've passed the security check

		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			$upload_overrides = array(
				'test_form' => false
			);
 
			$movefile = wp_handle_upload( $_FILES['imported_file'], $upload_overrides );
			 
			if ( $movefile && ! isset( $movefile['error'] ) ) {

				add_action( 'admin_footer', function() use ($movefile) {
					?>
					<script type="text/javascript" >
						window.ingestCountyIndexDataFileByChunk('<?php echo $movefile["file"]; ?>');
					</script>
					<?php
				} ); // Write our JS below here

			} else {
			    /*
			     * Error generated by _wp_handle_upload()
			     * @see _wp_handle_upload() in wp-admin/includes/file.php
			     */
			    echo 'Error: ' . $movefile['error'];
			}
		}
	}

	// Check whether the button has been pressed AND also check the nonce
	if (isset($_POST['vf_county_data_ingestion_requested'])) {
		// the button has been pressed AND we've passed the security check

		if(isset($_FILES['imported_file'])) {

			if($_FILES['imported_file']['error'] > 0) {
				die($_FILES["imported_file"]["error"]);
			}

			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			$upload_overrides = array(
				'test_form' => false
			);
 
			$movefile = wp_handle_upload( $_FILES['imported_file'], $upload_overrides );
			 
			if ( $movefile && ! isset( $movefile['error'] ) ) {

				add_action( 'admin_footer', function() use ($movefile) {
					?>
					<script type="text/javascript" >
						window.ingestCountyDataFileByChunk('<?php echo $movefile["file"]; ?>');
					</script>
					<?php
				} ); // Write our JS below here

			} else {
			    /*
			     * Error generated by _wp_handle_upload()
			     * @see _wp_handle_upload() in wp-admin/includes/file.php
			     */
			    echo 'Error: ' . $movefile['error'];
			}
		}
	}

}

function vf_data_ingestor_enqueue($hook) {

    if( 'toplevel_page_vf-data-ingestor' != $hook ) {
		// Only applies to dashboard panel
		return;
    }
        
	wp_enqueue_script( 'ajax-script', plugins_url( '/templates/js/scripts.js', __FILE__ ), array('jquery') );

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'ajax-script', 'ajax_object',
        array( 
        	'ajax_url' => admin_url( 'admin-ajax.php' ), 
			'ajax_nonce' => wp_create_nonce( 'ajax-nonce' ),
        ) 
    );
}
add_action( 'admin_enqueue_scripts', 'vf_data_ingestor_enqueue' );



function vf_data_ingestor_ingest_county_data_chunk() {
	global $wpdb;

	$ingestor = new VFDataIngestor;
	$results = $ingestor->ingestCountyDataFileChunk($_POST['file'], (isset($_POST['start']) ? $_POST['start'] : 0), (isset($_POST['size']) ? $_POST['size'] : 999), (isset($_POST['ingestion_group']) ? $_POST['ingestion_group'] : null));
	wp_send_json_success( $results );
}
add_action( 'wp_ajax_vf_data_ingestor_ingest_county_data_chunk', 'vf_data_ingestor_ingest_county_data_chunk' );




function vf_data_ingestor_ingest_county_index_data_chunk() {
	global $wpdb;

	$ingestor = new VFDataIngestor;
	$results = $ingestor->ingestCountyIndexDataFileChunk($_POST['file'], (isset($_POST['start']) ? $_POST['start'] : 0), (isset($_POST['size']) ? $_POST['size'] : 999), (isset($_POST['ingestion_group']) ? $_POST['ingestion_group'] : null));
	$results['method'] = 'vf_data_ingestor_ingest_county_index_data_chunk';
	wp_send_json_success( $results );
}
add_action( 'wp_ajax_vf_data_ingestor_ingest_county_index_data_chunk', 'vf_data_ingestor_ingest_county_index_data_chunk' );


