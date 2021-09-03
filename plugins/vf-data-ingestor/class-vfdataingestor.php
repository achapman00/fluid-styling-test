<?php
global $wpdb;

global $vf_data_ingestor_db_version;

class VFDataIngestor
{
	private function _log($msg)
	{
		echo '- ' . $msg . '<br />';
	}

	/**
	 * Ingest a list of CBSA names
	 **/
	public function ingestCbsaFile(array $uploaded_file)
	{
		global $vf_data_ingestor_cbsas_table_name;
	    global $wpdb;

		$this->_log('Ingesting the file: ' . $uploaded_file['name']);

		$row = 1;
		$rows = [];
		if (($handle = fopen($uploaded_file['tmp_name'], "r")) !== FALSE) {

			$wpdb->query("TRUNCATE TABLE $vf_data_ingestor_cbsas_table_name");

			// Loop thru each row and insert
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

		    	// Skip the header row
		    	if($row != 1) {

		    		$this->_log('Inserting ' . $data[0]);

		    		try {
		        		$wpdb->insert($vf_data_ingestor_cbsas_table_name, [
		        			'cbsa' => $data[1],
		        			'name' => $data[0],
		        			'city_name' => $data[2],
		        			'created_at' => date('Y-m-d h:i:s')
						]);
		        	}catch(\Exception $e) {
		        		echo $e;
		        	}
	        	}

		        $row++;
		    }
		    fclose($handle);
		}

	}

	/**
	 * Ingest a mapping of Counties to CBSA's
	 **/
	public function ingestCountyToCbsaFile(array $uploaded_file)
	{
		global $vf_data_ingestor_county_to_cbsa_table_name;
	    global $wpdb;

		$this->_log('Ingesting the file: ' . $uploaded_file['name']);

		$row = 1;
		$rows = [];
		if (($handle = fopen($uploaded_file['tmp_name'], "r")) !== FALSE) {

			$wpdb->query("TRUNCATE TABLE $vf_data_ingestor_county_to_cbsa_table_name");

			// Loop thru each row and insert
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

		    	// Skip the header row
		    	if($row != 1) {

		    		$this->_log('Inserting ' . $data[0]);

	        		$wpdb->insert($vf_data_ingestor_county_to_cbsa_table_name, [
	        			'cbsa' => $data[0],
	        			'county' => $data[1],
	        			'created_at' => date('Y-m-d h:i:s')
					]);
	        	}

		        $row++;
		    }
		    fclose($handle);
		}

	}


	/**
	 * Ingest a list of County names
	 **/
	public function ingestCountyFile(array $uploaded_file)
	{
		global $vf_data_ingestor_counties_table_name;
	    global $wpdb;

		$this->_log('Ingesting the file: ' . $uploaded_file['name']);

		$row = 1;
		$rows = [];
		if (($handle = fopen($uploaded_file['tmp_name'], "r")) !== FALSE) {

			$wpdb->query("TRUNCATE TABLE $vf_data_ingestor_counties_table_name");

			// Loop thru each row and insert
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

		    	// Skip the header row
		    	if($row != 1) {

		    		$this->_log('Inserting ' . $data[0]);

	        		$wpdb->insert($vf_data_ingestor_counties_table_name, [
	        			'cbsa' => $data[1],
	        			'name' => $data[0],
	        			'created_at' => date('Y-m-d h:i:s')
					]);
	        	}

		        $row++;
		    }
		    fclose($handle);
		}

	}

	/**
	 * Ingest the statistical data for any CBSAs
	 **/
	public function ingestCbsaDataFile(array $uploaded_file)
	{
		global $vf_data_ingestor_cbsa_data_table_name;
	    global $wpdb;

		$this->_log('Ingesting the file: ' . $uploaded_file['name']);

		$row = 1;
		$rows = [];
		if (($handle = fopen($uploaded_file['tmp_name'], "r")) !== FALSE) {

			// Update all previous to is_archived = True
			$wpdb->update($vf_data_ingestor_cbsa_data_table_name,[
				'is_archived' => true
			],[
				'is_archived' => false
			]);

			$ingestion_group = date('Y-m-d h:i:s');

			// Loop thru each row and insert
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

		    	// Skip the header row
		    	if($row != 1) {

		    		$this->_log('Inserting ' . $data[0] . ' - ' . $data[2] . '/' . $data[3]);

		    		// Row order for this CSV:
		    		// [0] - cbsa	
		    		// [1] - city_name	
		    		// [2] - month	
		    		// [3] - year	
		    		// [4] - month_year	
		    		// [5] - com_vac	
		    		// [6] - rev_dec	
		    		// [7] - spend_all	
		    		// [8] - vd	
		    		// [9] - havd	
		    		// [10] - pop19	
		    		// [11] - prosp19_ui	
		    		// [12] - recovery19_ui	
		    		// [13] - unemp_percent	
		    		// [14] - change_medinc_1619

	        		$wpdb->insert($vf_data_ingestor_cbsa_data_table_name, [
	        			'cbsa' => $data[0],
	        			'month' => $data[2],
	        			'year' => $data[3],
	        			'com_vac' => (is_numeric($data[5]) ? $data[5] : null),
	        			'rev_dec' => (is_numeric($data[6]) ? $data[6] : null),
	        			'spend_all' => (is_numeric($data[7]) ? $data[7] : null),
	        			'vd' => (is_numeric($data[8]) ? $data[8] : null),
	        			'havd' => (is_numeric($data[9]) ? $data[9] : null),
	        			'pop19' => (is_numeric($data[10]) ? $data[10] : null),
	        			'prosp19_ui' => (is_numeric($data[11]) ? $data[11] : null),
	        			'recovery19_ui' => (is_numeric($data[12]) ? $data[12] : null),
	        			'unemp_percent' => (is_numeric($data[13]) ? $data[13] : null),
	        			'change_medinc_1619' => (is_numeric($data[14]) ? $data[14] : null),
	        			'ingestion_group' => $ingestion_group,
	        			'is_archived' => false,
	        			'created_at' => date('Y-m-d h:i:s')
					]);
	        	}

		        $row++;
		    }
		    fclose($handle);
		}

	}

	/**
	 * Ingest the statistical index data for any CBSAs
	 **/
	public function ingestCbsaIndexDataFile(array $uploaded_file)
	{
		global $vf_data_ingestor_cbsa_index_data_table_name;
	    global $wpdb;

		$this->_log('Ingesting the file: ' . $uploaded_file['name']);

		$row = 1;
		$rows = [];
		if (($handle = fopen($uploaded_file['tmp_name'], "r")) !== FALSE) {

			// Update all previous to is_archived = True
			$wpdb->update($vf_data_ingestor_cbsa_index_data_table_name,[
				'is_archived' => true
			],[
				'is_archived' => false
			]);

			$ingestion_group = date('Y-m-d h:i:s');

			// Loop thru each row and insert
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

		    	// Skip the header row
		    	if($row != 1) {

		    		$this->_log('Inserting ' . $data[0] . ' - ' . $data[2] . '/' . $data[3]);

		    		// Row order for this CSV:
		    		// [0] - date	
		    		// [1] - cbsa	
		    		// [2] - name	
		    		// [3] - activity_index

	        		$wpdb->insert($vf_data_ingestor_cbsa_index_data_table_name, [
	        			'cbsa' => $data[1],
	        			'name' => $data[2],
	        			'month' => date('m',strtotime($data[0])),
	        			'year' => date('Y',strtotime($data[0])),
	        			'activity_index' => (is_numeric($data[3]) ? $data[3] : null),
	        			'ingestion_group' => $ingestion_group,
	        			'is_archived' => false,
	        			'created_at' => date('Y-m-d h:i:s')
					]);
	        	}

		        $row++;
		    }
		    fclose($handle);
		}

	}

	/**
	 * Ingest the statistical data for any counties
	 **/
	public function ingestCountyDataFileChunk($uploaded_file, $start = 0, $size = 100, $ingestion_group = null)
	{
		global $vf_data_ingestor_county_data_table_name;
	    global $wpdb;

		$end = $start + $size;

		$archived_old = false;
		$chunk_ended = false;
		$row = 0;
		$rows = [];

		try {

			if (($handle = fopen($uploaded_file, "r")) !== FALSE) {

				if(!$ingestion_group) {

					$ingestion_group = date('Y-m-d h:i:s');

					// Update all previous to is_archived = True
					$wpdb->update($vf_data_ingestor_county_data_table_name,[
						'is_archived' => true
					],[
						'is_archived' => false
					]);

					$archived_old = true;
				}


				// Loop thru each row and insert
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			    	// Only worry about rows >= start
			    	if($row < $start) {
			    		$row++;
			    		continue;
			    	}

			    	// If the row is > end, then stop
			    	if($row > $end) {
			    		// Flag that we still had data to go thru,
			    		// but we ended the chunk instead (this means
			    		// that we'll need to do another chunk in a subsequent request)
			    		$chunk_ended = true;
			    		break;
			    	}

			    	// Skip the header row
			    	if($row != 0) {

			    		// Row order from csv:
			    		// [0] - cfips
			    		// [1] - name
			    		// [2] - month
			    		// [3] - year
			    		// [4] - prosperity19_ui
			    		// [5] - recovery19_ui
			    		// [6] - unemp_percent
			    		// [7] - vd
			    		// [8] - havd
			    		// [9] - change_medinc_1619

		        		$wpdb->insert($vf_data_ingestor_county_data_table_name, [
		        			'county' => $data[0],
		        			'name' => $data[1],
		        			'month' => $data[2],
		        			'year' => $data[3],
		        			'vd' => (is_numeric($data[7]) ? $data[7] : null),
		        			'havd' => (is_numeric($data[8]) ? $data[8] : null),
		        			'unemp_percent' => (is_numeric($data[6]) ? $data[6] : null),
		        			'prosp19_ui' => (is_numeric($data[4]) ? $data[4] : null),
		        			'recovery19_ui' => (is_numeric($data[5]) ? $data[5] : null),
		        			'change_medinc_1619' => (is_numeric($data[9]) ? $data[9] : null),
		        			'counts' => (is_numeric($data[10]) ? $data[10] : null),
		        			'pop_county' => (is_numeric($data[11]) ? $data[11] : null),
		        			'ingestion_group' => $ingestion_group,
		        			'is_archived' => false,
		        			'created_at' => date('Y-m-d h:i:s')
						]);
		        	}

			        $row++;
			    }
			    fclose($handle);
			}
		}catch(\Exception $e) {
			die($e);
		}

		return [
			'archived_old' => $archived_old,
			'ingestion_group' => $ingestion_group,
			'start' => (int) $start,
			'end' => (int) $end,
			'size' => (int) $size,
			'next' => ($chunk_ended ? ((int) ($end + 1)) : false)
		];

	}

	/**
	 * Ingest the statistical data for any county indices
	 **/
	public function ingestCountyIndexDataFileChunk($uploaded_file, $start = 0, $size = 100, $ingestion_group = null)
	{
		global $vf_data_ingestor_county_index_data_table_name;
	    global $wpdb;

		$end = $start + $size;

		$archived_old = false;
		$chunk_ended = false;
		$row = 0;
		$rows = [];

		try {

			if (($handle = fopen($uploaded_file, "r")) !== FALSE) {

				if(!$ingestion_group) {

					$ingestion_group = date('Y-m-d h:i:s');

					// Update all previous to is_archived = True
					$wpdb->update($vf_data_ingestor_county_index_data_table_name,[
						'is_archived' => true
					],[
						'is_archived' => false
					]);

					$archived_old = true;
				}


				// Loop thru each row and insert
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

			    	// Only worry about rows >= start
			    	if($row < $start) {
			    		$row++;
			    		continue;
			    	}

			    	// If the row is > end, then stop
			    	if($row > $end) {
			    		// Flag that we still had data to go thru,
			    		// but we ended the chunk instead (this means
			    		// that we'll need to do another chunk in a subsequent request)
			    		$chunk_ended = true;
			    		break;
			    	}

			    	// Skip the header row
			    	if($row != 0) {

			    		// Row order from csv:
			    		// [0] - cfips
			    		// [1] - name
			    		// [2] - activity_index
			    		// [3] - year
			    		// [4] - month

			    		$wpdb->show_errors();
		        		$wpdb->insert($vf_data_ingestor_county_index_data_table_name, [
		        			'county' => $data[0],
		        			'name' => $data[1],
		        			'activity_index' => (is_numeric($data[2]) ? $data[2] : null),
		        			'year' => $data[3],
		        			'month' => $data[4],
		        			'ingestion_group' => $ingestion_group,
		        			'is_archived' => false,
		        			'created_at' => date('Y-m-d h:i:s')
						]);
		        	}

			        $row++;
			    }
			    fclose($handle);
			}
		}catch(\Exception $e) {
			die($e);
		}

		return [
			'archived_old' => $archived_old,
			'ingestion_group' => $ingestion_group,
			'start' => (int) $start,
			'end' => (int) $end,
			'size' => (int) $size,
			'next' => ($chunk_ended ? ((int) ($end + 1)) : false)
		];

	}



	public function ingestWamDataFile(array $uploaded_file)
	{
		global $vf_data_ingestor_wam_data_table_name;
	    global $wpdb;

		$this->_log('Ingesting the file: ' . $uploaded_file['name']);

		$row = 1;
		$rows = [];
		if (($handle = fopen($uploaded_file['tmp_name'], "r")) !== FALSE) {

			// Update all previous to is_archived = True
			$wpdb->update($vf_data_ingestor_wam_data_table_name,[
				'is_archived' => true
			],[
				'is_archived' => false
			]);

			$ingestion_group = date('Y-m-d h:i:s');

			// Loop thru each row and insert
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

		    	// Skip the header row
		    	if($row != 1) {

		    		$this->_log('Inserting ' . $data[0] . ' - ' . $data[2] . '/' . $data[3]);

		    		// Row order for this CSV:
		    		// [0] - month	
		    		// [1] - year	
		    		// [2] - traffic	
		    		// [3] - orders

	        		$wpdb->insert($vf_data_ingestor_wam_data_table_name, [
	        			'month' => $data[0],
	        			'year' => $data[1],
	        			'traffic' => (is_numeric($data[2]) ? $data[2] : null),
	        			'orders' => (is_numeric($data[3]) ? $data[3] : null),
	        			'ingestion_group' => $ingestion_group,
	        			'is_archived' => false,
	        			'created_at' => date('Y-m-d h:i:s')
					]);
	        	}

		        $row++;
		    }
		    fclose($handle);
		}

	}


	/**
	 * Ingest the data for the City Portal page
	 **/
	public function ingestCityPortalDataFile(array $uploaded_file)
	{
		global $vf_data_ingestor_city_portal_data_table_name;
	    global $wpdb;

		$this->_log('Ingesting the file: ' . $uploaded_file['name']);

		$row = 1;
		$rows = [];
		if (($handle = fopen($uploaded_file['tmp_name'], "r")) !== FALSE) {

			// Update all previous to is_archived = True
			$wpdb->update($vf_data_ingestor_city_portal_data_table_name,[
				'is_archived' => true
			],[
				'is_archived' => false
			]);

			$ingestion_group = date('Y-m-d h:i:s');

			// Loop thru each row and insert
		    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {

		    	// Skip the header row
		    	if($row != 1) {

		    		$this->_log('Inserting ' . $data[0] . ' - ' . $data[2] . '/' . $data[3]);

		    		// Row order for this CSV:
					// [0] - sum_micro
					// [1] - sum_smb
					// [2] - sum_medlrg
					// [3] - pop19
					// [4] - pop17
					// [5] - city_id
					// [6] - CITYOLD
					// [7] - STATEOLD
					// [8] - q1_21vd
					// [9] - change_vd
					// [10] - bb19
					// [11] - medinc19
					// [12] - housing19
					// [13] - college19
					// [14] - poverty19
					// [15] - unemp_dec20
					// [16] - city_size
					// [17] - city_name
					// [18] - pop_3yr
					// [19] - avg_pop19_sm
					// [20] - avg_pop19_med
					// [21] - avg_pop19_lrg
					// [22] - avg_q1_21vd_sm
					// [23] - avg_q1_21vd_med
					// [24] - avg_q1_21vd_lrg
					// [25] - avg_change_vd_sm
					// [26] - avg_change_vd_med
					// [27] - avg_change_vd_lrg
					// [28] - avg_bb19_sm
					// [29] - avg_bb19_med
					// [30] - avg_bb19_lrg
					// [31] - avg_medinc19_sm
					// [32] - avg_medinc19_med
					// [33] - avg_medinc19_lrg
					// [34] - avg_housing19_sm
					// [35] - avg_housing19_med
					// [36] - avg_housing19_lrg
					// [37] - avg_college19_sm
					// [38] - avg_college19_med
					// [39] - avg_college19_lrg
					// [40] - avg_poverty19_sm
					// [41] - avg_poverty19_med
					// [42] - avg_poverty19_lrg
					// [43] - avg_unemp_dec20_sm
					// [44] - avg_unemp_dec20_med
					// [45] - avg_unemp_dec20_lrg
					// [46] - avg_pop_3yr_sm
					// [47] - avg_pop_3yr_med
					// [48] - avg_pop_3yr_lrg
					// [49] - pop19_peercomparison_sm
					// [50] - pop19_peercomparison_med
					// [51] - pop19_peercomparison_lrg
					// [52] - q1_21vd_peercomparison_sm
					// [53] - q1_21vd_peercomparison_med
					// [54] - q1_21vd_peercomparison_lrg
					// [55] - change_vd_peercomparison_sm
					// [56] - change_vd_peercomparison_med
					// [57] - change_vd_peercomparison_lrg
					// [58] - bb19_peercomparison_sm
					// [59] - bb19_peercomparison_med
					// [60] - bb19_peercomparison_lrg
					// [61] - medinc19_peercomparison_sm
					// [62] - medinc19_peercomparison_med
					// [63] - medinc19_peercomparison_lrg
					// [64] - housing19_peercomparison_sm
					// [65] - housing19_peercomparison_med
					// [66] - housing19_peercomparison_lrg
					// [67] - college19_peercomparison_sm
					// [68] - college19_peercomparison_med
					// [69] - college19_peercomparison_lrg
					// [70] - poverty19_peercomparison_sm
					// [71] - poverty19_peercomparison_med
					// [72] - poverty19_peercomparison_lrg
					// [73] - unemp_dec20_peercomparison_sm
					// [74] - unemp_dec20_peercomparison_med
					// [75] - unemp_dec20_peercomparison_lrg
					// [76] - pop_3yr_peercomparison_sm
					// [77] - pop_3yr_peercomparison_med
					// [78] - pop_3yr_peercomparison_lrg
					// [79] - pop19_nationalcomparison
					// [80] - q1_21vd_nationalcomparison
					// [81] - change_vd_nationalcomparison
					// [82] - bb19_nationalcomparison
					// [83] - medinc19_nationalcomparison
					// [84] - housing19_nationalcomparison
					// [85] - college19_nationalcomparison
					// [86] - poverty19_nationalcomparison
					// [87] - unemp_dec20_nationalcomparison
					// [88] - pop_3yr_nationalcomparison
					// [89] - countymapflag
					// [90] - legendflag

		    		$wpdb->show_errors();
	        		$res = $wpdb->insert($vf_data_ingestor_city_portal_data_table_name, [
	        			'sum_micro'							=> (is_numeric($data[0]) ? $data[0] : null),
	        			'sum_smb'							=> (is_numeric($data[1]) ? $data[1] : null),
	        			'sum_medlrg'						=> (is_numeric($data[2]) ? $data[2] : null),
	        			'pop19'								=> (is_numeric($data[3]) ? $data[3] : null),
	        			'pop17'								=> (is_numeric($data[4]) ? $data[4] : null),
	        			'city_id'							=> (is_numeric($data[5]) ? $data[5] : null),
	        			'q1_21vd'							=> (is_numeric($data[8]) ? $data[8] : null),
	        			'change_vd'							=> (is_numeric($data[9]) ? $data[9] : null),
	        			'bb19'								=> (is_numeric($data[10]) ? $data[10] : null),
	        			'medinc19'							=> (is_numeric($data[11]) ? $data[11] : null),
	        			'housing19'							=> (is_numeric($data[12]) ? $data[12] : null),
	        			'college19'							=> (is_numeric($data[13]) ? $data[13] : null),
	        			'poverty19'							=> (is_numeric($data[14]) ? $data[14] : null),
	        			'unemp_dec20'						=> (is_numeric($data[15]) ? $data[15] : null),
	        			'city_size'							=> (!empty($data[16]) ? $data[16] : null),
	        			'city_name'							=> (!empty($data[17]) ? $data[17] : null),
	        			'pop_3yr'							=> (is_numeric($data[18]) ? $data[18] : null),
	        			'avg_pop19_sm'						=> (is_numeric($data[19]) ? $data[19] : null),
	        			'avg_pop19_med'						=> (is_numeric($data[20]) ? $data[20] : null),
	        			'avg_pop19_lrg'						=> (is_numeric($data[21]) ? $data[21] : null),
	        			'avg_q1_21vd_sm'					=> (is_numeric($data[22]) ? $data[22] : null),
	        			'avg_q1_21vd_med'					=> (is_numeric($data[23]) ? $data[23] : null),
	        			'avg_q1_21vd_lrg'					=> (is_numeric($data[24]) ? $data[24] : null),
	        			'avg_change_vd_sm'					=> (is_numeric($data[25]) ? $data[25] : null),
	        			'avg_change_vd_med'					=> (is_numeric($data[26]) ? $data[26] : null),
	        			'avg_change_vd_lrg'					=> (is_numeric($data[27]) ? $data[27] : null),
	        			'avg_bb19_sm'						=> (is_numeric($data[28]) ? $data[28] : null),
	        			'avg_bb19_med'						=> (is_numeric($data[29]) ? $data[29] : null),
	        			'avg_bb19_lrg'						=> (is_numeric($data[30]) ? $data[30] : null),
	        			'avg_medinc19_sm'					=> (is_numeric($data[31]) ? $data[31] : null),
	        			'avg_medinc19_med'					=> (is_numeric($data[32]) ? $data[32] : null),
	        			'avg_medinc19_lrg'					=> (is_numeric($data[33]) ? $data[33] : null),
	        			'avg_housing19_sm'					=> (is_numeric($data[34]) ? $data[34] : null),
	        			'avg_housing19_med'					=> (is_numeric($data[35]) ? $data[35] : null),
	        			'avg_housing19_lrg'					=> (is_numeric($data[36]) ? $data[36] : null),
	        			'avg_college19_sm'					=> (is_numeric($data[37]) ? $data[37] : null),
	        			'avg_college19_med'					=> (is_numeric($data[38]) ? $data[38] : null),
	        			'avg_college19_lrg'					=> (is_numeric($data[39]) ? $data[39] : null),
	        			'avg_poverty19_sm'					=> (is_numeric($data[40]) ? $data[40] : null),
	        			'avg_poverty19_med'					=> (is_numeric($data[41]) ? $data[41] : null),
	        			'avg_poverty19_lrg'					=> (is_numeric($data[42]) ? $data[42] : null),
	        			'avg_unemp_dec20_sm'				=> (is_numeric($data[43]) ? $data[43] : null),
	        			'avg_unemp_dec20_med'				=> (is_numeric($data[44]) ? $data[44] : null),
	        			'avg_unemp_dec20_lrg'				=> (is_numeric($data[45]) ? $data[45] : null),
	        			'avg_pop_3yr_sm'					=> (is_numeric($data[46]) ? $data[46] : null),
	        			'avg_pop_3yr_med'					=> (is_numeric($data[47]) ? $data[47] : null),
	        			'avg_pop_3yr_lrg'					=> (is_numeric($data[48]) ? $data[48] : null),
	        			'pop19_peercomparison_sm'			=> (is_numeric($data[49]) ? $data[49] : null),
	        			'pop19_peercomparison_med'			=> (is_numeric($data[50]) ? $data[50] : null),
	        			'pop19_peercomparison_lrg'			=> (is_numeric($data[51]) ? $data[51] : null),
	        			'q1_21vd_peercomparison_sm'			=> (is_numeric($data[52]) ? $data[52] : null),
	        			'q1_21vd_peercomparison_med'		=> (is_numeric($data[53]) ? $data[53] : null),
	        			'q1_21vd_peercomparison_lrg'		=> (is_numeric($data[54]) ? $data[54] : null),
	        			'change_vd_peercomparison_sm'		=> (is_numeric($data[55]) ? $data[55] : null),
	        			'change_vd_peercomparison_med'		=> (is_numeric($data[56]) ? $data[56] : null),
	        			'change_vd_peercomparison_lrg'		=> (is_numeric($data[57]) ? $data[57] : null),
	        			'bb19_peercomparison_sm'			=> (is_numeric($data[58]) ? $data[58] : null),
	        			'bb19_peercomparison_med'			=> (is_numeric($data[59]) ? $data[59] : null),
	        			'bb19_peercomparison_lrg'			=> (is_numeric($data[60]) ? $data[60] : null),
	        			'medinc19_peercomparison_sm'		=> (is_numeric($data[61]) ? $data[61] : null),
	        			'medinc19_peercomparison_med'		=> (is_numeric($data[62]) ? $data[62] : null),
	        			'medinc19_peercomparison_lrg'		=> (is_numeric($data[63]) ? $data[63] : null),
	        			'housing19_peercomparison_sm'		=> (is_numeric($data[64]) ? $data[64] : null),
	        			'housing19_peercomparison_med'		=> (is_numeric($data[65]) ? $data[65] : null),
	        			'housing19_peercomparison_lrg'		=> (is_numeric($data[66]) ? $data[66] : null),
	        			'college19_peercomparison_sm'		=> (is_numeric($data[67]) ? $data[67] : null),
	        			'college19_peercomparison_med'		=> (is_numeric($data[68]) ? $data[68] : null),
	        			'college19_peercomparison_lrg'		=> (is_numeric($data[69]) ? $data[69] : null),
	        			'poverty19_peercomparison_sm'		=> (is_numeric($data[70]) ? $data[70] : null),
	        			'poverty19_peercomparison_med'		=> (is_numeric($data[71]) ? $data[71] : null),
	        			'poverty19_peercomparison_lrg'		=> (is_numeric($data[72]) ? $data[72] : null),
	        			'unemp_dec20_peercomparison_sm'		=> (is_numeric($data[73]) ? $data[73] : null),
	        			'unemp_dec20_peercomparison_med'	=> (is_numeric($data[74]) ? $data[74] : null),
	        			'unemp_dec20_peercomparison_lrg'	=> (is_numeric($data[75]) ? $data[75] : null),
	        			'pop_3yr_peercomparison_sm'			=> (is_numeric($data[76]) ? $data[76] : null),
	        			'pop_3yr_peercomparison_med'		=> (is_numeric($data[77]) ? $data[77] : null),
	        			'pop_3yr_peercomparison_lrg'		=> (is_numeric($data[78]) ? $data[78] : null),
	        			'pop19_nationalcomparison'			=> (is_numeric($data[79]) ? $data[79] : null),
	        			'q1_21vd_nationalcomparison'		=> (is_numeric($data[80]) ? $data[80] : null),
	        			'change_vd_nationalcomparison'		=> (is_numeric($data[81]) ? $data[81] : null),
	        			'bb19_nationalcomparison'			=> (is_numeric($data[82]) ? $data[82] : null),
	        			'medinc19_nationalcomparison'		=> (is_numeric($data[83]) ? $data[83] : null),
	        			'housing19_nationalcomparison'		=> (is_numeric($data[84]) ? $data[84] : null),
	        			'college19_nationalcomparison'		=> (is_numeric($data[85]) ? $data[85] : null),
	        			'poverty19_nationalcomparison'		=> (is_numeric($data[86]) ? $data[86] : null),
	        			'unemp_dec20_nationalcomparison'	=> (is_numeric($data[87]) ? $data[87] : null),
	        			'pop_3yr_nationalcomparison'		=> (is_numeric($data[88]) ? $data[88] : null),
	        			'countymapflag'						=> (is_numeric($data[89]) ? $data[89] : 0),
	        			'legendflag'						=> (is_numeric($data[90]) ? $data[90] : 5),
	        			'ingestion_group' 					=> $ingestion_group,
	        			'is_archived' 						=> false,
	        			'created_at' 						=> date('Y-m-d h:i:s')
					]);

					if(!$res) {
						$wpdb->print_error();
					}
	        	}

		        $row++;
		    }
		    fclose($handle);
		}

	}
}