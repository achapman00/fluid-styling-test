<div class="wrap">
	
	<h1>Ingest Venture Forward Data</h1>

	<hr />

	<h2>Import CBSA Statistical Data</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_cbsa_data_ingestion_requested" />

		<?php
		wp_nonce_field('vf_cbsa_data_ingestion_requested_nonce');
		submit_button('Upload and Ingest','primary','sub-data');
		?>
	</form>

	<hr />

	<h2>Import CBSA Statistical Index Data</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_cbsa_index_data_ingestion_requested" />

		<?php
		wp_nonce_field('vf_cbsa_index_data_ingestion_requested_nonce');
		submit_button('Upload and Ingest','primary','sub-cbsa-index-data');
		?>
	</form>

	<hr />

	<h2>Import CBSAs</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_cbsa_ingestion_requested" />

		<?php
		wp_nonce_field('vf_cbas_ingestion_requested_nonce');
		submit_button('Import','primary','sub-cbas');
		?>
	</form>

	<hr />

	<h2>Import County Statistical Data</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_county_data_ingestion_requested" />

		<?php
		wp_nonce_field('vf_county_data_ingestion_requested_nonce');
		submit_button('Upload and Ingest','primary','sub-county-data');
		?>
	</form>

	<hr />

	<h2>Import County Statistical Index Data</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_county_index_data_ingestion_requested" />

		<?php
		wp_nonce_field('vf_county_index_data_ingestion_requested_nonce');
		submit_button('Upload and Ingest','primary','sub-county-index-data');
		?>
	</form>

	<hr />

	<h2>Import County <=> CBSA Mapping</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_county_to_cbsa_ingestion_requested" />

		<?php
		wp_nonce_field('vf_county_to_cbsa_ingestion_requested_nonce');
		submit_button('Upload and Ingest','primary','sub-county-to-cbsa-data');
		?>
	</form>

	<hr />

	<h2>Import WAM Data</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_wam_data_ingestion_requested" />

		<?php
		wp_nonce_field('vf_wam_data_ingestion_requested_nonce');
		submit_button('Upload and Ingest','primary','sub-wam-data');
		?>
	</form>

	<hr />

	<h2>Import City Portal Data</h2>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imported_file" />
		<input type="hidden" value="true" name="vf_city_portal_data_ingestion_requested" />

		<?php
		wp_nonce_field('vf_city_portal_data_ingestion_requested_nonce');
		submit_button('Upload and Ingest','primary','sub-city-portal-data');
		?>
	</form>

	<div data-county-data-ingestion-log></div>

</div>
