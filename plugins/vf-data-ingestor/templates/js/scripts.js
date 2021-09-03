var countyLog = function(msg) {
	jQuery('[data-county-data-ingestion-log').prepend('<div>' + msg + '</div>');
}

var ingestCountyDataFileByChunk = function(file, ingestionGroup, start) {

	if(!start) {
		countyLog('Ingesting county data file at: ' + file);
	}

	// Gather the Venture Density information
	var data = { 
		action: 'vf_data_ingestor_ingest_county_data_chunk',
		ajax_nonce: window.ajax_object.ajax_nonce,
		file: file,
		size: 1999,
	};

	if(ingestionGroup && start) {
		data.ingestion_group = ingestionGroup;
		data.start = start;
	}

	var start = (data.start ? start : 0);
	countyLog('Ingesting county data chunk: ' + start + ' - ' + (start + data.size));

	jQuery.ajax(window.ajax_object.ajax_url, {
		type: 'post',
		data: data,
		success: function(response) {
			if(response.success) {

				if(response.data.archived_old) {
					countyLog('Old records were archived, and a new ingestion group was created: ' + response.data.ingestion_group);
				}

				if(response.data && response.data.next) {
					window.ingestCountyDataFileByChunk(data.file, response.data.ingestion_group, response.data.next);
				} else {
					countyLog('Ingestion complete.');
				}
			}
		}
	});
}

var ingestCountyIndexDataFileByChunk = function(file, ingestionGroup, start) {

	if(!start) {
		countyLog('Ingesting county index data file at: ' + file);
	}

	// Gather the Venture Density information
	var data = { 
		action: 'vf_data_ingestor_ingest_county_index_data_chunk',
		ajax_nonce: window.ajax_object.ajax_nonce,
		file: file,
		size: 1999,
	};

	if(ingestionGroup && start) {
		data.ingestion_group = ingestionGroup;
		data.start = start;
	}

	var start = (data.start ? start : 0);
	countyLog('Ingesting county data chunk: ' + start + ' - ' + (start + data.size));

	jQuery.ajax(window.ajax_object.ajax_url, {
		type: 'post',
		data: data,
		success: function(response) {
			console.log(response);
			if(response.success) {

				if(response.data.archived_old) {
					countyLog('Old records were archived, and a new ingestion group was created: ' + response.data.ingestion_group);
				}

				if(response.data && response.data.next) {
					window.ingestCountyIndexDataFileByChunk(data.file, response.data.ingestion_group, response.data.next);
				} else {
					countyLog('Ingestion complete.');
				}
			}
		}
	});
}

jQuery(document).ready(function($) {
	// Do things.
});