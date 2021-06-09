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
}