import bbox from '@turf/bbox';

const hrn                               = require('hrn');
const Chart                             = require('chart.js');
const fuzzysort                         = require('fuzzysort');
let map;
Chart.defaults.global.defaultFontColor  = '#748B90';
Chart.defaults.global.defaultFontFamily = 'GD Sherpa';
Chart.defaults.global.defaultFontSize   = 16;
Chart.defaults.global.defaultFontStyle  = 'Bold';

Chart.plugins.register({
	beforeDraw: function (chartInstance) {
		let ctx       = chartInstance.chart.ctx;
		ctx.fillStyle = '#FCFCFC';

		let chartArea = chartInstance.chartArea;
		ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
	}
});



/**
 * VIEW
 **/
module.exports = async () => {

	let data;
	const NAME = 'EXPLORE THE DATA';

	w.log.log(NAME + ' > page was loaded.');

	// Setup the global defaults for this page
	window.app.global.vars.cbsas               = [];
	window.app.global.vars.compareTable        = {};
	window.app.global.vars.countyData          = {};
	window.app.global.vars.chart               = {};
	window.app.global.vars.counties            = [];
	window.app.global.vars.countiesToCbsas     = [];
	window.app.global.vars.searchableLocations = [];
	window.app.global.vars.explore             = {
		active_region: null,
	};
	window.app.global.vars.hero_chart          = {};
	window.app.global.vars.searchSelections    = {
		selection_1: null
	};
	const socials                              = document.querySelectorAll(".explore-the-data__social-btns");
	let socialsEngagement = {
		scrollPos: window.scrollY,
		visible: true,
		timeout: null
	};


	// A function to determine the color for a given
	// county on the map
	const choroplethColor = () => {
		/*
			Fn to generate choropleth color expressions for County level data.
			Each color expression should return an array of the form:

			['match',
			['get', 'cfips'],       <- get the cfips property of geo feature
			'01101', '#ffeeaa',     <- color pairing for each possible cfips
			'01102', '#aaFe13',
			...,
			'#<default color>']     <- default color in case of no match

		*/

		let expression = ["match", ["get", "cfips"]];

		window.app.global.vars.counties.forEach(row => {

			var vd_avg = parseFloat(row.vd_avg);

			var color = '#ffffff';
			if (vd_avg <= 3.0) {
				color = '#004249';

			} else if (vd_avg > 3.0 && vd_avg <= 5.0) {
				color = '#09757A';

			} else if (vd_avg > 5.0 && vd_avg <= 8.0) {
				color = '#00a4a6';
			} else if (vd_avg > 8.0) {
				color = '#1bdbdb';
			}

			var county = (row.county.length < 5) ? '0' + row.county : row.county;
			expression.push(county, color);
		});

		let noDataColor = "#ffffff";
		expression.push(noDataColor);

		return expression;
	}

	// When a person selects a specific county,
	// we need to gather data about it and populate
	// various parts of the page.
	const gatherSpecificDataForCounty = async (county) => {

		if (window.app.global.vars.countyData[county]) {
			w.log.log(NAME + ` > Data already gathered for county: ${county}`);
			return true;
		}
		w.log.log(NAME + ` > Loading specific data for county: ${county}`);

		// Set up the default record for this CBSA
		// This will prevent from having to load it again for this page load.
		window.app.global.vars.countyData[county] = {};

		// Gather the Venture Density information
		var data = {
			action: 'vf_ajax_get_county_specifics_for_data_page',
			ajax_nonce: global_vars.ajax_nonce,
			county: county,
		};
		await $.ajax(global_vars.ajax_url, {
			type: 'post',
			data: data,
			success: function (response) {
				if (response.success) {
					w.log.log(NAME + ` > Page data for ${window.app.global.vars.searchSelections.selection_1.label} loaded as: ${response.data}`);

					window.app.global.vars.countyData[county] = response.data;

				}
			}
		});

		return true;
	}

	const getDataForCounty = (county) => {
		return window.app.global.vars.countyData[county] ? window.app.global.vars.countyData[county] : {};
	}

	const getDataForSelectedCounty = () => {
		return getDataForCounty(window.app.global.vars.searchSelections.selection_1.value);
	}

	const zoomToCounty = function (county) {
		// based on this: https://www.mapbox.com/mapbox-gl-js/example/zoomto-linestring/

		const countyModded = (county.length < 5) ? '0' + county : county;

		// console.log('Zooming map to county ID: ' + county);

		const relatedFeatures = map.querySourceFeatures('countyLayer', {
			sourceLayer: 'counties_500k-31pnqp',
			filter: ['==', 'cfips', countyModded]
		});

		// console.log('Found map counties: ', relatedFeatures);

		if (relatedFeatures[0]) {

			const bounds = bbox(relatedFeatures[0].toJSON());

			map.fitBounds(bounds, {
				padding: 200
			});
		}
	};

	const stringToSlug = (str) => {
		str = str.replace(/^\s+|\s+$/g, ''); // trim
		str = str.toLowerCase();

		// remove accents, swap ñ for n, etc
		var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
		var to   = "aaaaeeeeiiiioooouuuunc------";
		for (var i = 0, l = from.length; i < l; i++) {
			str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		}

		str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
			.replace(/\s+/g, '-') // collapse whitespace and replace by -
			.replace(/-+/g, '-'); // collapse dashes

		return str;
	};

	// Setup the Compare chart
	// https://stackoverflow.com/a/38094165
	const updateCompareChart = async () => {

		// Do we need to add a location to the legend?
		$('[data-line-graph-locations-legend-selection-1]').remove();
		$('[data-line-graph-locations-legend-wam]').remove();
		if (window.app.global.vars.searchSelections.selection_1) {
			$('[data-line-graph-locations-legend]').prepend('<span class="explore-the-data-line-graph__chart-tools-location explore-the-data-line-graph__chart-tools-location--selection-1" data-line-graph-locations-legend-selection-1>' + window.app.global.vars.searchSelections.selection_1.label + '</span>');
		}

		// If 1) there isn't a city selection and 2) the data was preloaded
		if (!window.app.global.vars.chart.comparison_type && !window.app.global.vars.searchSelections.selection_1 && window.data_vars && window.data_vars.vf_get_compare_chart_for_data_page) {
			w.log.log(NAME + ' > Chart data preloaded.');

			let data = {
				labels: window.data_vars.vf_get_compare_chart_for_data_page.labels,
				datasets: window.data_vars.vf_get_compare_chart_for_data_page.datasets
			}

			window.app.global.vars.chart.data = data;

		} else {

			if (window.app.global.vars.chart.comparison_type && (window.app.global.vars.chart.comparison_type == 'traffic' || window.app.global.vars.chart.comparison_type == 'orders')) {
				$('[data-line-graph-locations-legend]')
					.append('<span class="explore-the-data-line-graph__chart-tools-location explore-the-data-line-graph__chart-tools-location--wam" data-line-graph-locations-legend-wam>U.S. Average</span>');
			}

			var data = {
				action: 'vf_ajax_get_compare_chart_for_data_page',
				ajax_nonce: global_vars.ajax_nonce,
				comparison: window.app.global.vars.chart.comparison_type
			};

			if (window.app.global.vars.searchSelections.selection_1) {
				data.selection_1 = window.app.global.vars.searchSelections.selection_1.value;
			}

			await $.ajax(global_vars.ajax_url, {
				type: 'post',
				data: data,
				success: function (response) {
					if (response.success) {
						w.log.log(NAME + ' > Chart data loaded.');
						let data = {
							labels: response.data.labels,
							datasets: response.data.datasets
						}

						window.app.global.vars.chart.data = data;
					}
				}
			});
		}

		// If mobile, adjust the font sizes, etc
		if ($(window).outerWidth() < 768) {
			Chart.defaults.global.defaultFontSize  = 12;
			Chart.defaults.global.defaultFontStyle = 'normal';
		}

		// Configure the yAxes
		// If there is a comparison, we need to add the
		// right side axis
		let yAxis = {
			id: 'vd',
			type: 'linear',
			position: 'left',
			gridLines: {
				color: '#FFFFFF',
				lineWidth: 12,
				tickMarkLength: 0,
				offsetGridLines: true,
			},
			ticks: {
				stepSize: 1,
				suggestedMax: 8,
				padding: 20,
			},
			color: 'red',
		};

		// Only display the label if this isn't mobile
		if ($(window).outerWidth() > 767) {

			yAxis.gridLines.lineWidth = 4;

			yAxis.scaleLabel = {
				display: true,
				labelString: 'Microbusiness Density',
			};
		}

		let yAxes = [yAxis];

		if (window.app.global.vars.chart.comparison_type) {

			let label = window.app.global.vars.chart.comparison_label;

			// Do we need to show the comparison legend item?
			$('[data-line-chart-legend-item-comparison]').text('Comparison').hide();
			$('[data-line-chart-legend-item-comparison]').html(label).css('display', 'flex');

			$('[data-line-graph-comparison-source]').removeClass('explore-the-data-line-graph__chart-sources-row--active');
			$('[data-line-graph-comparison-source="' + window.app.global.vars.chart.comparison_type + '"]').addClass('explore-the-data-line-graph__chart-sources-row--active');

			let secondaryYAxis = {
				id: 'comparison',
				type: 'linear',
				position: 'right',
				gridLines: {
					tickMarkLength: 0,
				},
				ticks: {
					padding: 20,
					callback: (value) => {
						if (window.app.global.vars.chart.comparison_format === 'percentage') return value + '%';
						else return value;
					}
				},
			};

			if ($(window).outerWidth() > 767) {
				secondaryYAxis.scaleLabel = {
					display: true,
					labelString: window.app.global.vars.chart.comparison_label,
				};
			}

			yAxes.push(secondaryYAxis);
		}

		// Set up the chart
		var ctx          = $('#compare-chart');
		var compareChart = new Chart(ctx, {
			type: 'line',
			data: window.app.global.vars.chart.data,
			options: {
				legend: {
					display: false
				},
				scales: {
					yAxes: yAxes,
					xAxes: [{
						gridLines: {
							color: '#FFFFFF',
							lineWidth: 12,
							tickMarkLength: 0,
							offsetGridLines: true,
						},
						ticks: {
							padding: 20,
						}
					}]
				}
			}
		});
	}

	// Update the compare table
	const updateCompareTable = async () => {

		// Attempt to preload IF selection_1 is not existant
		if (!window.app.global.vars.searchSelections.selection_1 && window.data_vars && window.data_vars.vf_get_compare_table_data_for_data_page) {
			w.log.log(NAME + ' > Compare table section data preloaded.');
			window.app.global.vars.compareTable = window.data_vars.vf_get_compare_table_data_for_data_page.compare_table;

		} else {
			var data = {
				action: 'vf_ajax_get_compare_table_data_for_data_page',
				ajax_nonce: global_vars.ajax_nonce,
			};

			if (window.app.global.vars.searchSelections.selection_1) {
				data.county = window.app.global.vars.searchSelections.selection_1.value;
			}

			await $.ajax(global_vars.ajax_url, {
				type: 'post',
				data: data,
				success: function (response) {
					if (response.success) {
						w.log.log(NAME + ' > Compare table section data loaded.');
						window.app.global.vars.compareTable = response.data.compare_table;
					} else {
						w.log.log(NAME + ' > There was an error while loading the compare table section data.');
					}
				}
			});
		}

		let compareTableData = window.app.global.vars.compareTable;

		if (window.app.global.vars.searchSelections.selection_1) {
			$('[data-compare-table-selection-name]').text(window.app.global.vars.searchSelections.selection_1 ? window.app.global.vars.searchSelections.selection_1.label : '+ Add County');

			// Specific fields for a SELECTION
			$('[data-compare-table-selection-vd]').text((compareTableData.selection && compareTableData.selection.vd_avg) ? compareTableData.selection.vd_avg.toFixed(1) : '-');
			$('[data-compare-table-selection-havd]').text((compareTableData.selection && compareTableData.selection.havd_avg) ? compareTableData.selection.havd_avg.toFixed(1) : '-');
			$('[data-compare-table-selection-recession-recovery]').text((compareTableData.selection) ? compareTableData.selection.recovery_avg : '-');
			$('[data-compare-table-selection-unemployment]').text((compareTableData.selection) ? compareTableData.selection.unemp_avg : '-');
			$('[data-compare-table-selection-median-income]').text((compareTableData.selection) ? compareTableData.selection.income_median : '-');
			$('[data-compare-table-selection-activity-index]').text((compareTableData.selection) ? compareTableData.selection.activity_index : '-');

			$('[data-compare-table-selection-column]').css('display', 'table-cell');
		} else {
			$('[data-compare-table-selection-column]').hide();
		}

		// Specific fields for a NATIONAL COUNTY AVG
		$('[data-compare-table-county-vd]').text((compareTableData.national_county && compareTableData.national_county.vd_avg) ? compareTableData.national_county.vd_avg.toFixed(1) : '-');
		$('[data-compare-table-county-havd]').text((compareTableData.national_county && compareTableData.national_county.havd_avg) ? compareTableData.national_county.havd_avg.toFixed(1) : '-');
		$('[data-compare-table-county-recession-recovery]').text((compareTableData.national_county) ? compareTableData.national_county.recovery_avg : '-');
		$('[data-compare-table-county-unemployment]').text((compareTableData.national_county) ? compareTableData.national_county.unemp_avg : '-');
		$('[data-compare-table-county-median-income]').text((compareTableData.national_county) ? compareTableData.national_county.income_median : '-');
		$('[data-compare-table-county-activity-index]').text((compareTableData.national_county) ? compareTableData.national_county.activity_index.toFixed(2) : '-');

		// Specific fields for a NATIONAL CITY AVG
		$('[data-compare-table-cbsa-vd]').text((compareTableData.national_cbsa && compareTableData.national_cbsa.vd_avg) ? compareTableData.national_cbsa.vd_avg.toFixed(1) : '-');
		$('[data-compare-table-cbsa-havd]').text((compareTableData.national_cbsa && compareTableData.national_cbsa.havd_avg) ? compareTableData.national_cbsa.havd_avg.toFixed(1) : '-');
		$('[data-compare-table-cbsa-recession-recovery]').text((compareTableData.national_cbsa) ? compareTableData.national_cbsa.recovery_avg : '-');
		$('[data-compare-table-cbsa-unemployment]').text((compareTableData.national_cbsa) ? compareTableData.national_cbsa.unemp_avg : '-');
		$('[data-compare-table-cbsa-median-income]').text((compareTableData.national_cbsa) ? compareTableData.national_cbsa.income_median : '-');
		$('[data-compare-table-cbsa-activity-index]').text((compareTableData.national_cbsa) ? compareTableData.national_cbsa.activity_index.toFixed(2) : '-');
	}

	// Handle the click of an autocomplete result.
	// Determine which "slot" (max of 2) this search result
	// should go in, and handle the UI accordingly
	const selectSearchAutocompleteResult = async (selected) => {

		$('[data-search-input]').val('');

		if (selected) {
			w.log.log(NAME + ` > Autocomplete result: ${selected} | Selection type: ${selected.type}`);

			// If a CBSA was selected, we need to map it to a county,
			// as this page only supports counties
			if (selected.type === 'cbsa') {
				let mapping = await window.app.global.vars.countiesToCbsas.find(o => o.cbsa == selected.value);

				// If we can't find a mapping, clear the selection entirely
				if (!mapping) {
					selected = null;
				} else {
					w.log.log(NAME + ` > Found mapping of CBSA to County: ${mapping}`);
					let county = await window.app.global.vars.counties.find(o => o.county === mapping.county);

					selected = {
						type: county.type,
						value: county.value,
						label: county.name
					};
					w.log.log(NAME + ` > Mapped CBSA selection to County selection: ${selected}`);
				}
			}

		}

		// It is possible that the selection was cleared above (if mapping was not found)
		if (selected) {
			w.log.log(NAME + ` > Setting search autocomplete result: ${selected}`);
			$('[data-hero-search-autocomplete]').html('');

			// First, try and set the first slot
			if (window.app.global.vars.searchSelections.selection_1 == null) {
				// console.log('Setting selection slot 1...');
				window.app.global.vars.searchSelections.selection_1 = selected;

				window.app.global.vars.explore.active_region = 'selection_1';


				// No slots are available
			} else {
				w.log.log(NAME + ` > No open search selection slots. Aborting search autocomplete selection assignment...`);
			}
		}

		// For convenience...
		w.log.log(NAME + ` > Selected search results: ${window.app.global.vars.searchSelections}`);

		// Hide the search bar?
		if (
			window.app.global.vars.searchSelections.selection_1 != null
		) {
			$('[data-search-input]').hide();
		} else {
			$('[data-search-input]').show();
		}

		// Do things for the first slot?
		if (window.app.global.vars.searchSelections.selection_1 != null) {

			w.log.log(NAME + ` > Populating UI for the first selection slot...`);
			let labelParts = window.app.global.vars.searchSelections.selection_1.label.split(',');
			labelParts.pop();

			// Show the first slot
			$('[data-search-selection-1]')
				.html(labelParts.join(','))
				.show();

			// Get all the info that the page will need for this CBSA
			await gatherSpecificDataForCounty(window.app.global.vars.searchSelections.selection_1.value);

		} else {
			$('[data-search-selection-1]')
				.html('')
				.hide();
		}

		// Back to defaults?
		if (
			window.app.global.vars.searchSelections.selection_1 == null
		) {
			window.app.global.vars.explore.active_region = null;
		}

		$(window).trigger('vf_search_selection_completed');

	}

	// Handle the removal of a search autocomplete selection
	const removeSearchAutocompleteSelection = (slot) => {
		if (slot !== 1) {
			w.log.log(NAME + ` > Incorrect slot provided. Please provide 1 (slot 2 is no longer supported).`);
			return;
		}

		if (slot === 1) {
			window.app.global.vars.searchSelections.selection_1 = null;
		}

		selectSearchAutocompleteResult();
	};

	// Update the explore section
	const updateExploreSection = () => {

		w.log.log(NAME + ' > Updating Explore section...');
		let data = {};

		$('#map')[0].width += 0;

		// Check to see if Selection 1 is not ready yet:
		if (
			window.app.global.vars.explore.active_region === 'selection_1'
			&& !window.app.global.vars.searchSelections.selection_1
		) {
			window.app.global.vars.explore.active_region = null;
		}

		// Do we need to create an extra toggle?
		$('[data-interactive-map-location-toggle-selection-1]').remove();
		if (window.app.global.vars.searchSelections.selection_1) {
			var eid = window.data_vars.data_interactive_map_locations_selection_1_eid_prefix + "." + stringToSlug(window.app.global.vars.searchSelections.selection_1.label) + "." + window.data_vars.data_interactive_map_locations_selection_1_eid_suffix;
			$('[data-interactive-map-location-toggles]')
				.prepend('<a class="explore-the-data-interactive-map__stats-location-button" href="#" data-eid="' + eid + '" title="Show data for ' + window.app.global.vars.searchSelections.selection_1.label + '" data-interactive-map-location-toggle="selection_1" data-interactive-map-location-toggle-selection-1>' + window.app.global.vars.searchSelections.selection_1.label + '</a>');
		}

		// Remove the active class
		$('[data-interactive-map-location-toggle]').removeClass('explore-the-data-interactive-map__stats-location-button--active');

		// Decide which data set to use...
		switch (window.app.global.vars.explore.active_region) {
			case 'selection_1':

				// Zoom to the selected county
				zoomToCounty(window.app.global.vars.searchSelections.selection_1.value);

				$('[data-interactive-map-location-toggle="selection_1"]').addClass('explore-the-data-interactive-map__stats-location-button--active');

				var selection             = window.app.global.vars.searchSelections.selection_1;
				data.sub_headline         = window.app.global.vars.searchSelections.selection_1.label;
				data.sub_headline_average = getDataForCounty(selection.value).vd.toFixed(1);

				data.national_average_type = 'county';

				data.unemployment_national_average = window.app.global.vars.explore.national_county.unemp_avg.toFixed(1) + '%';
				var date                           = new Date(window.app.global.vars.explore.national_county.year, (window.app.global.vars.explore.national_county.month - 1), 1);
				data.unemployment_national_date    = date.toLocaleString('default', {month: 'short', year: 'numeric'});
				data.unemployment_number           = getDataForCounty(selection.value).unemp_percent.toFixed(1) + '%';
				var date                           = new Date(getDataForCounty(selection.value).year, (getDataForCounty(selection.value).month - 1), 1);
				data.unemployent_rate_date         = date.toLocaleString('default', {month: 'short', year: 'numeric'});

				data.venture_density_number           = getDataForCounty(selection.value).vd.toFixed(1);
				data.venture_density_national_average = window.app.global.vars.explore.national_county.vd_avg.toFixed(1);

				data.economic_resilience                  = getDataForCounty(selection.value).recovery19_ui.toFixed(1);
				data.economic_resilience_national_average = window.app.global.vars.explore.national_county.rec_avg.toFixed(1);

				data.chart_headline = 'Counties with similar microbusiness density to ' + window.app.global.vars.searchSelections.selection_1.label;

				// Get counties with similar microbusiness density
				// Use the counties from the HERO, because it has all
				// the counties (whereas the counties list for this section is
				// limited to counties with > 10,000 population)
				var targetVd    = getDataForCounty(selection.value).vd;
				data.chart_rows = window.app.global.vars.counties.sort((a, b) => {
					return Math.abs(targetVd - a.vd_avg) - Math.abs(targetVd - b.vd_avg);
				}).slice(0, 10);

				break;

			case 'national_cbsa':

				// Adjust the map to show the full country
				map.flyTo({
					zoom: $(window).outerWidth() < 768 ? 1 : 2,
					center: [-98.5795, 39.8283],
				});

				$('[data-interactive-map-location-toggle="national_cbsa"]').addClass('explore-the-data-interactive-map__stats-location-button--active');

				data.sub_headline         = 'U.S. Average (Metro)';
				data.sub_headline_average = window.app.global.vars.explore.national_cbsa.vd_avg.toFixed(1);

				data.national_average_type = 'Metro';

				data.unemployment_national_average = window.app.global.vars.explore.national_cbsa.unemp_avg.toFixed(1) + '%';
				var date                           = new Date(window.app.global.vars.explore.national_cbsa.year, (window.app.global.vars.explore.national_cbsa.month - 1), 1);
				data.unemployment_national_date    = date.toLocaleString('default', {month: 'short', year: 'numeric'});
				data.unemployment_number           = window.app.global.vars.explore.national_cbsa.unemp_avg.toFixed(1) + '%';
				var date                           = new Date(window.app.global.vars.explore.national_cbsa.year, (window.app.global.vars.explore.national_cbsa.month - 1), 1);
				data.unemployent_rate_date         = date.toLocaleString('default', {month: 'short', year: 'numeric'});

				data.venture_density_number           = window.app.global.vars.explore.national_cbsa.vd_avg.toFixed(1);
				data.venture_density_national_average = window.app.global.vars.explore.national_cbsa.vd_avg.toFixed(1);

				data.economic_resilience                  = window.app.global.vars.explore.national_cbsa.rec_avg.toFixed(1);
				data.economic_resilience_national_average = window.app.global.vars.explore.national_cbsa.rec_avg.toFixed(1);

				data.chart_headline = 'Cities with the highest microbusiness density';

				// Get the highest cities
				data.chart_rows = window.app.global.vars.cbsas.sort((a, b) => {
					return Math.abs(b.vd_avg) - Math.abs(a.vd_avg);
				}).slice(0, 10);
				break;

			case 'national_county':
			default:

				// Adjust the map to show the full country
				map.flyTo({
					zoom: $(window).outerWidth() < 768 ? 1 : 2,
					center: [-98.5795, 39.8283],
				});

				$('[data-interactive-map-location-toggle="national_county"]').addClass('explore-the-data-interactive-map__stats-location-button--active');

				data.sub_headline         = 'U.S. Average (County)';
				data.sub_headline_average = window.app.global.vars.explore.national_county.vd_avg.toFixed(1);

				data.national_average_type = 'county';

				data.unemployment_national_average = window.app.global.vars.explore.national_county.unemp_avg.toFixed(1) + '%';
				var date                           = new Date(window.app.global.vars.explore.national_county.year, (window.app.global.vars.explore.national_county.month - 1), 1);
				data.unemployment_national_date    = date.toLocaleString('default', {month: 'short', year: 'numeric'});
				data.unemployment_number           = window.app.global.vars.explore.national_county.unemp_avg.toFixed(1) + '%';
				var date                           = new Date(window.app.global.vars.explore.national_county.year, (window.app.global.vars.explore.national_county.month - 1), 1);
				data.unemployent_rate_date         = date.toLocaleString('default', {month: 'short', year: 'numeric'});

				data.venture_density_number           = window.app.global.vars.explore.national_county.vd_avg.toFixed(1);
				data.venture_density_national_average = window.app.global.vars.explore.national_county.vd_avg.toFixed(1);

				data.economic_resilience                  = window.app.global.vars.explore.national_county.rec_avg.toFixed(1);
				data.economic_resilience_national_average = window.app.global.vars.explore.national_county.rec_avg.toFixed(1);

				data.chart_headline = 'Highest microbusiness density among the 100 largest counties';

				// Get the highest counties
				data.chart_rows = window.app.global.vars.explore.all_counties.sort((a, b) => {
					return Math.abs(b.vd_avg) - Math.abs(a.vd_avg);
				}).slice(0, 10);

				break;
		}

		// Acutally set all the data into the HTML DOM
		$('[data-explore-economic-resilience-number]').html(data.economic_resilience);
		$('[data-explore-economic-resilience-national-average]').html(data.economic_resilience_national_average);
		$('[data-explore-unemployment-national-average]').html(data.unemployment_national_average);
		$('[data-explore-unemployment-national-date]').html(data.unemployment_national_date);
		$('[data-explore-unemployment-number]').html(data.unemployment_number);
		$('[data-explore-unemployment-rate-date]').html(data.unemployent_rate_date);
		$('[data-explore-venture-density-number]').html(data.venture_density_number);
		$('[data-explore-venture-density-national-average]').html(data.venture_density_national_average);
		$('[data-explore-sub-headline]').html(data.sub_headline);
		$('[data-explore-sub-headline-average]').html(data.sub_headline_average);
		$('[data-explore-chart-headline]').html(data.chart_headline);
		$('[data-explore-venture-density-national-average-type]').html(data.national_average_type);

		$('[data-explore-chart-row]').remove();
		data.chart_rows.forEach((v, i) => {
			$('[data-explore-chart]').append('<tr data-explore-chart-row><td>' + (i + 1) + '. ' + v.name + '</td><td>' + parseFloat(v.vd_avg).toFixed(1) + '</td></tr>')
		});
	};

	const updateExploreSectionActiveRegion = (e) => {
		e.preventDefault();
		window.app.global.vars.explore.active_region = $(e.target).attr('data-interactive-map-location-toggle');
		console.log('Updating explore section to use: ' + window.app.global.vars.explore.active_region);
		updateExploreSection();
	}

	// Update the bar chart in the hero with
	// data saved from the API
	const updateHeroChart = () => {
		// w.log.log(NAME + ' > Updating hero chart...');

		// Reset all bars
		$('[data-hero-chart]').addClass('explore-the-data-hero__chart--thinking');
		$('[data-hero-chart-bar]').css({
			height: '50%'
		})
		$('[data-hero-chart-bar]').removeClass('explore-the-data-hero__chart-bar--active');
		$('[data-hero-chart-bar]').removeClass('explore-the-data-hero__chart-bar--national-avg');
		// $('[data-hero-chart-bar] > [data-hero-chart-bar-annotation] > [data-hero-chart-bar-annotation-number]').html('');
		// $('[data-hero-chart-bar] > [data-hero-chart-bar-annotation] > [data-hero-chart-bar-annotation-label]').html('');
		$('[data-hero-chart-bar] > [data-hero-chart-bar-annotation]').removeClass('explore-the-data-hero__chart-bar-annotation--active');

		// Set the low and the high labels
		$('[data-hero-chart-x-axis-label-low-number]').html('< ' + window.app.global.vars.hero_chart.bars[0].max.toFixed(1));
		$('[data-hero-chart-x-axis-label-high-number]').html(window.app.global.vars.hero_chart.vd_max.toFixed(1) + '+');

		setTimeout(function () {
			// Get the tallest bar. This will be the definition
			// of 100%
			let maxBarHeight = 0;
			let maxBarValue  = 0;
			if (!window.app.global.vars.hero_chart || !window.app.global.vars.hero_chart.bars) {
				return;
			}
			for (var i in window.app.global.vars.hero_chart.bars) {

				let bar = window.app.global.vars.hero_chart.bars[i];

				if (bar.total >= maxBarHeight) {
					maxBarHeight = bar.total;
				}

				if (bar.max > maxBarValue) {
					maxBarValue = bar.max;
				}
			}

			// Now, compare all bars against the maxBarHeight
			var avg = window.app.global.vars.hero_chart.vd_avg;
			for (var i in window.app.global.vars.hero_chart.bars) {


				let bar = window.app.global.vars.hero_chart.bars[i];

				let percentage = (bar.total / maxBarHeight) * 100;

				$('[data-hero-chart-bar=' + i + ']').css({
					height: Math.max(1, percentage) + '%'
				});

				// Add the DEFAULT annotation?
				// If there isn't a selected location in slot_1, then we should the tooltip
				// annotation with U.S. County Avg.
				if (!window.app.global.vars.searchSelections.selection_1) {
					// w.log.log(NAME + ' > Hero has no city selection, yet. We will display national averages.');

					if (avg >= bar.min && avg <= bar.max) {
						// w.log.log(NAME + ' > Hero bar ' + i + ' should display the annotation.');
						$('[data-hero-chart-bar=' + i + ']').addClass('explore-the-data-hero__chart-bar--active');
						$('[data-hero-chart-bar=' + i + '] > [data-hero-chart-bar-annotation] > [data-hero-chart-bar-annotation-number]').html('U.S. County Average');
						$('[data-hero-chart-bar=' + i + '] > [data-hero-chart-bar-annotation] > [data-hero-chart-bar-annotation-label]').html('<b>' + avg.toFixed(1) + ' per 100 people</b><span>20 million total microbusinesses</span>');
						$('[data-hero-chart-bar=' + i + '] > [data-hero-chart-bar-annotation]').addClass('explore-the-data-hero__chart-bar-annotation--active');
					}

				} else {

					var selectedCountyData = getDataForSelectedCounty();
					if (selectedCountyData && selectedCountyData.vd) {
						if (
							(selectedCountyData.vd >= bar.min && selectedCountyData.vd <= bar.max)
							|| (bar.max == maxBarValue && selectedCountyData.vd > bar.max)
						) {

							w.log.log(NAME + ' > Hero bar ' + i + ' should display the annotation.');
							let labelParts = window.app.global.vars.searchSelections.selection_1.label.split(',');
							labelParts.pop();
							$('[data-hero-chart-bar=' + i + ']').addClass('explore-the-data-hero__chart-bar--active');
							$('[data-hero-chart-bar=' + i + '] > [data-hero-chart-bar-annotation] > [data-hero-chart-bar-annotation-number]').html(labelParts.join(',') + ' Average');
							$('[data-hero-chart-bar=' + i + '] > [data-hero-chart-bar-annotation] > [data-hero-chart-bar-annotation-label]').html('<b>' + selectedCountyData.vd.toFixed(1) + ' per 100 people</b><span>' + hrn(selectedCountyData.total_microbusinesses).replace(' ', '') + ' total microbusinesses</span>');
							$('[data-hero-chart-bar=' + i + '] > [data-hero-chart-bar-annotation]').addClass('explore-the-data-hero__chart-bar-annotation--active');

							// If this isn't the bar for the selected county, lets check to see
							// if this is the bar for the national average
						} else if (avg >= bar.min && avg <= bar.max) {
							$('[data-hero-chart-bar=' + i + ']').addClass('explore-the-data-hero__chart-bar--national-avg');
						}
					}
				}
			}

			$('[data-hero-chart]').removeClass('explore-the-data-hero__chart--thinking');
		}, 2000);
	};


	// Listen for vf_hero_data_refreshed
	$(window).on('vf_hero_data_refreshed', () => {
		updateHeroChart();
	});

	// Listen for vf_explore_data_refreshed
	$(window).on('vf_explore_data_refreshed', () => {
		updateExploreSection();
	});

	// Listen for vf_search_selection_completed
	$(window).on('vf_search_selection_completed', () => {
		updateHeroChart();
		updateExploreSection();
		updateCompareChart();
		updateCompareTable();

		if (window.app.global.vars.searchSelections.selection_1) {

			_expDataLayer.push({
				'schema': 'add_event', 'version': 'v2', 'data': {
					'eid': `comms.microsites.venture-forward/${window.data_vars.eid_page_slug}.search-selection.selected`,
					'type': 'click',
					'custom_properties': {
						'search_value': window.app.global.vars.searchSelections.selection_1.label
					}
				}
			});
		}
	});

	// Listen for vf_comparison_chart_comparison_selected
	$(window).on('vf_comparison_chart_comparison_selected', () => {
		updateCompareChart();
	});



	// Start loading the map
	map = new mapboxgl.Map({
		container: "map",
		logoPosition: "bottom-right",
		style: 'mapbox://styles/kenmanz0/ckml2836d595y17ny3qp5t4rv?optimize=true',
		// style:"mapbox://styles/kenmanz0/ck8asciuu073x1jn4haf1au6z",
		interactive: false,
		minZoom: 3,
		maxZoom: 8.5,
		zoom: $(window).outerWidth() < 768 ? 1 : 2,
		center: [-98.5795, 39.8283],
		accessToken: "pk.eyJ1Ijoia2VubWFuejAiLCJhIjoiY2swaWJmdm5nMGJpcjNubGU5NGlmbmFzeCJ9.ejCc3y6Y-1Kl9e69OOWq0g"
	});

	// Will be called after we have county data to paint the map with
	var configureMapLayers = () => {

		var layers = map.getStyle().layers;
		// Find the index of the first symbol layer in the map style
		var firstSymbolId;
		for (var i = 0; i < layers.length; i++) {
			if (layers[i].type === 'symbol') {
				firstSymbolId = layers[i].id;
				break;
			}
		}

		map.getCanvas().style.cursor = "default";

		map.addLayer({
			id: "countyLayer",
			type: "fill",
			source: {
				type: "vector",
				url: "mapbox://kenmanz0.5i75rlai"
			},
			"source-layer": "counties_500k-31pnqp",
			layout: {
				visibility: "visible"
			},
			paint: {
				"fill-color": "rgb(255, 0, 0)",
				"fill-opacity": 1
			}
		}, firstSymbolId);

		map.setPaintProperty(
			"countyLayer",
			"fill-color",
			choroplethColor()
		);
	}

	// Grab all locations: CBSAs and Counties
	if (window.data_vars && window.data_vars.vf_get_all_locations) {
		w.log.log(NAME + ' > All locations (CBSAs, Counties) were preloaded.');
		window.app.global.vars.cbsas               = window.data_vars.vf_get_all_locations.cbsas;
		window.app.global.vars.counties            = window.data_vars.vf_get_all_locations.counties;
		window.app.global.vars.countiesToCbsas     = window.data_vars.vf_get_all_locations.counties_to_cbsas;
		window.app.global.vars.searchableLocations = window.app.global.vars.counties.concat(window.app.global.vars.cbsas);

		if (map.loaded()) {
			configureMapLayers();
		} else {
			map.on('load', function () {
				configureMapLayers();
				const links = Array.from(document.querySelectorAll('a[target]'));
				for (let link of links) {
					const target = link.getAttribute('target');
					if (target && (!link.getAttribute('rel') || link.getAttribute('rel').indexOf('noopener') === -1)) {
						// console.error(`Unsafe link ${link} is vulnerable to reverse tabnabbing.`);
						link.setAttribute('rel', "noopener");
					}
				}
				map.resize();
			});
		}

		$(window).trigger('vf_location_data_loaded');

	} else {
		data = {
			action: 'vf_ajax_get_all_locations',
			ajax_nonce: global_vars.ajax_nonce
		};
		$.ajax(global_vars.ajax_url, {
			type: 'post',
			data: data,
			success: function (response) {
				if (response.success) {
					w.log.log(NAME + ' > All locations (CBSAs, Counties) loaded.');
					window.app.global.vars.cbsas               = response.data.cbsas;
					window.app.global.vars.counties            = response.data.counties;
					window.app.global.vars.countiesToCbsas     = response.data.counties_to_cbsas;
					window.app.global.vars.searchableLocations = window.app.global.vars.counties.concat(window.app.global.vars.cbsas);

					if (map.loaded()) {
						configureMapLayers();
					} else {
						map.on('load', function () {
							configureMapLayers();
							const links = Array.from(document.querySelectorAll('a[target]'));
							for (let link of links) {
								const target = link.getAttribute('target');
								if (target && (!link.getAttribute('rel') || link.getAttribute('rel').indexOf('noopener') === -1)) {
									// console.error(`Unsafe link ${link} is vulnerable to reverse tabnabbing.`);
									link.setAttribute('rel', "noopener");
								}
							}
							map.resize();
						});
					}

					$(window).trigger('vf_location_data_loaded');
				} else {
					w.log.log(NAME + ' > There was an error while loading all locations.');
				}
			}
		});
	}


	// Grab data for this the HERO on this page
	if (window.data_vars && window.data_vars.vf_get_data_page_hero_chart_data) {
		w.log.log(NAME + ' > Hero data was preloaded..');
		window.app.global.vars.hero_chart = window.data_vars.vf_get_data_page_hero_chart_data.hero_chart;

		setTimeout(function () {
			$(window).trigger('vf_hero_data_refreshed');
		}, 2000);

	} else {
		data = {
			action: 'vf_ajax_get_data_page_hero_chart_data',
			ajax_nonce: global_vars.ajax_nonce,
			total_hero_bars: $('[data-hero-chart-bar]').length
		};
		$.ajax(global_vars.ajax_url, {
			type: 'post',
			data: data,
			success: function (response) {
				if (response.success) {
					// w.log.log(NAME + ' > Hero data loaded.');
					window.app.global.vars.hero_chart = response.data.hero_chart;
					$(window).trigger('vf_hero_data_refreshed');
				} else {
					w.log.log(NAME + ' > There was an error while loading hero data.');
				}
			}
		});
	}


	// Grab page data for the EXPLORE section
	if (window.data_vars && window.data_vars.vf_get_data_page_explore_section_data) {

		w.log.log(NAME + ' > Explore section data preloaded.');
		window.app.global.vars.explore = window.data_vars.vf_get_data_page_explore_section_data.explore;
		$(window).trigger('vf_explore_data_refreshed');

	} else {
		data = {
			action: 'vf_ajax_get_data_page_explore_section_data',
			ajax_nonce: global_vars.ajax_nonce
		};
		$.ajax(global_vars.ajax_url, {
			type: 'post',
			data: data,
			success: function (response) {
				if (response.success) {
					w.log.log(NAME + ' > Explore section data loaded.');
					window.app.global.vars.explore = response.data.explore;
					$(window).trigger('vf_explore_data_refreshed');
				} else {
					w.log.log(NAME + ' > There was an error while loading the explore section data.');
				}
			}
		});

	}

	$('[data-interactive-map-location-toggles]').on('click', '[data-interactive-map-location-toggle]', updateExploreSectionActiveRegion);

	// Force the COMPARE CHART to update
	updateCompareChart();

	// Grab page data for the COMPARE TABLE section
	updateCompareTable();

	// Add navigation control
	const nav = new mapboxgl.NavigationControl();
	map.addControl(nav, 'top-right');


	// If the user clicks a search selection, then
	// attempt to remove it
	$('[data-search-selection-1]').on('click', (e) => {
		e.preventDefault();
		removeSearchAutocompleteSelection(1);
	});

	// Autocomplete

	// Handle ARROW input and ENTER
	var focusedAutocompleteSearchResult = null;
	$('[data-search-input]').on('keydown', function (e) {

		const $parent       = $(this).closest('[data-search-input-container]');
		const $autocomplete = $parent.find('[data-search-autocomplete]');

		// Do nothing if autocomplete isn't active
		if (!$autocomplete
			.is('[data-search-autocomplete-active]')) {
			return;
		}

		// Clear all focus
		$autocomplete
			.find('li')
			.find('a')
			.removeAttr('data-search-autocomplete-select-is-focused');

		// up arrow
		if (e.keyCode === '38') {
			// console.log('Up arrow...');

			focusedAutocompleteSearchResult = (focusedAutocompleteSearchResult == null) ? 0 : focusedAutocompleteSearchResult - 1;
			if (focusedAutocompleteSearchResult < 0) {
				focusedAutocompleteSearchResult = $autocomplete
					.find('li')
					.find('a')
					.length - 1;
			}

			// down arrow
		} else if (e.keyCode === '40') {
			// console.log('Down arrow...');

			focusedAutocompleteSearchResult = (focusedAutocompleteSearchResult == null) ? 0 : focusedAutocompleteSearchResult + 1;
			if (focusedAutocompleteSearchResult > $autocomplete
				.find('li')
				.find('a').length - 1) {
				focusedAutocompleteSearchResult = 0;
			}

			// return / enter
		} else if (e.keyCode === 13) {

			$autocomplete
				.find('li')
				.eq(focusedAutocompleteSearchResult)
				.find('a')
				.click();
		}

		$autocomplete
			.find('li')
			.eq(focusedAutocompleteSearchResult)
			.find('a')
			.attr('data-search-autocomplete-select-is-focused', true);
	});

	// Handle NON-ARROW input
	$('[data-search-input]').on('keyup', function (e) {

		const $parent       = $(this).closest('[data-search-input-container]');
		const $autocomplete = $parent.find('[data-search-autocomplete]');

		if (e.keyCode === '40' || e.keyCode === '38') {
			return;
		}

		$autocomplete
			.removeAttr('data-search-autocomplete-active')
			.html('');

		focusedAutocompleteSearchResult = null;
		w.log.log(NAME + ' > Filtering Counties...');

		const results = fuzzysort.go($(this).val(), window.app.global.vars.searchableLocations, {
			threshold: -50, // Don't return matches worse than this (higher is faster)
			limit: 5, // Don't return more results than this (lower is faster)
			allowTypo: false, // Allwos a snigle transpoes (false is faster)
			key: 'name'
		});

		if (results.total <= 0) {
			return;
		}

		let html = [];
		results.forEach((v) => {
			html.push('<li><a data-search-autocomplete-select-type="' + v.obj.type + '" data-search-autocomplete-select="' + v.obj.value + '">' + v.obj.name + '</a></li>');
		});
		$autocomplete
			.html(html.join(''))
			.attr('data-search-autocomplete-active', true);

		// Bind to all the results
		$autocomplete.find('a[data-search-autocomplete-select]').on('click', (e) => {
			e.preventDefault();

			$autocomplete
				.removeAttr('data-search-autocomplete-active');

			selectSearchAutocompleteResult({
				type: $(e.target).attr('data-search-autocomplete-select-type'),
				value: $(e.target).attr('data-search-autocomplete-select'),
				label: $(e.target).html()
			});
		});
	});

	// Handle blurs on the search fields
	$('[data-search-input]').on('blur', function (evt) {

		var $parent       = $(this).closest('[data-search-input-container]');
		var $autocomplete = $parent.find('[data-search-autocomplete]');

		$autocomplete.removeAttr('data-search-autocomplete-active');

		setTimeout(function () {
			$('[data-search-input]').val('');
		}, 250);

	});

	// Allow users to select specific datasets to compare
	// against the venture density data
	$('[data-compare-chart-comparison-selector]').on('change', (e) => {
		e.preventDefault();
		w.log.log(NAME + ' > Adding comparison to compare chart...');
		window.app.global.vars.chart.comparison_format = $(e.target).find('option:selected').attr('data-yaxis-format');
		window.app.global.vars.chart.comparison_label  = $(e.target).find('option:selected').text();
		window.app.global.vars.chart.comparison_type   = $(e.target).val();
		$(window).trigger('vf_comparison_chart_comparison_selected');
	});

	var headerH;
	var heroH;
	let measureHeader = () => {
		headerH = $('#global-nav').outerHeight();
		heroH   = $('#hero').outerHeight();
		$('[ data-sticky-search]').css('top', headerH + 'px');
	}
	$(window).on('resize', () => {
		measureHeader();
	});
	measureHeader();

	let showOrHideStickySearch = () => {
		if (!$('[ data-sticky-search]').hasClass('explore-the-data-sticky-search--active') && $(window).scrollTop() > heroH) {
			$('[ data-sticky-search]').addClass('explore-the-data-sticky-search--active');
		} else if ($('[ data-sticky-search]').hasClass('explore-the-data-sticky-search--active') && $(window).scrollTop() < heroH) {
			$('[ data-sticky-search]').removeClass('explore-the-data-sticky-search--active');
		}
	}

	showOrHideStickySearch();

	if (window.data_vars.prefill_county !== '') {
		var matches = window.data_vars.vf_get_all_locations.counties.filter((location) => {
			return location.value == window.data_vars.prefill_county;
		});

		if (matches.length > 0) {
			selectSearchAutocompleteResult({
				type: 'county',
				value: matches[0].value,
				label: matches[0].name
			});
		}
	}


	/**
	 * checkPosition --- Check whether or not the current scrollY is equal to last position
	 */
	function checkPosition() {
		// console.log('checkPosition');
		const windowY = window.scrollY;

		// console.log(`current: ${windowY}, past: ${socialsEngagement.scrollPos}`);

		socials.forEach((element) => {
			element.classList.remove("explore-the-data__social-btns--vis");
		});
		socialsEngagement.visible = true;
		socialsEngagement.scrollPos = windowY;
	}

	$(window).on('scroll', () => {
		showOrHideStickySearch();
		// debounce(checkPosition, 1000, false);
		if (socialsEngagement.visible) {
			socials.forEach((element) => {
				element.classList.add("explore-the-data__social-btns--vis");
			});
			socialsEngagement.visible = false;
		}
		debounce2(checkPosition, 330);
	});

	/**
	 * debounce2 --- Ensure we wait 10ms before firing, otherwise it wreaks havoc on DOM
	 * @param func
	 * @param wait
	 */
	function debounce2(func, wait = 10) {
		clearTimeout(socialsEngagement.timeout);
		// console.log('debounce > later');
		socialsEngagement.timeout = setTimeout(func, wait);
	}

	/**
	 * Social Media Share buttons functionality
	 */
	socials.forEach((element) => {

		w.evt.add(element, 'click', (evt) => {
			// console.log(evt);
			const target = evt.target;

			if (target && target.nodeName === "A" || target && target.nodeName === "BUTTON") {

				let classes = target.className.split(" ");
				if (classes) {
					// Search for class and react on match
					for (let x = 0; x < classes.length; x++) {
						switch (classes[x]) {
							case "story-article__social-btns__button":
								console.clear();
								const shareType      = target.getAttribute('data-share-button');
								const shareSection   = target.getAttribute('data-share-section');
								const windowFeatures = "width=600,height=300,menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
								let url              = window.app.global.vars.searchSelections.selection_1 ? `${window.location.href}?county=${window.app.global.vars.searchSelections.selection_1.value}&section=${shareSection}` : window.location.href;
								console.log(url);
								if (shareType === 'facebook') {
									FB.ui({
										method: 'share',
										href: url,
									}, (response) => console.info(`%cResponse from Facebook = ${response}`, 'background:yellow;font-weight:bold;'));
								} else if (shareType === 'twitter') {
									window.open('https://twitter.com/intent/tweet?url=' + url, 'twitter_wdw', windowFeatures);

								} else if (shareType === 'linkedin') {
									window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + url, 'linkedin_wdw', windowFeatures);
								}
								break;
						}
					}
				}

			}
		});
	});
	console.clear();
}
