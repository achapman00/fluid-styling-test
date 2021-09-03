const fuzzysort                         = require('fuzzysort');
const Chart                             = require('chart.js');
Chart.defaults.global.defaultFontColor  = '#748B90';
Chart.defaults.global.defaultFontFamily = 'GD Sherpa';
Chart.defaults.global.defaultFontSize   = 16;
Chart.defaults.global.defaultFontStyle  = 'Bold';
Chart.plugins.register({
	beforeDraw: function (chartInstance, easing) {
		let ctx       = chartInstance.chart.ctx;
		ctx.fillStyle = '#FCFCFC';

		let chartArea = chartInstance.chartArea;
		ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
	}
});

//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'MICROBUSINESS INDEX';

	w.log.log(`${NAME} > page was loaded.`);

	const allCursors               = document.querySelectorAll(".microbusiness-index-three-sub-indexes__line-vert");
	const circle                   = w.el.id('three_indexes_layer');
	const three_sub_index_1        = w.el.id('three-sub-index__1');
	const three_sub_index_2        = w.el.id('three-sub-index__2');
	const three_sub_index_3        = w.el.id('three-sub-index__3');
	const three_indexes_colors     = ['', '#004249', '#09757A', '#00A4A6'];
	const three_indexes_bkgd_gp    = w.el.id('background_group');
	const data_graph_2_county_tag  = document.querySelector('[data-graph-2-county-tag]');
	const data_search_input_cont   = document.querySelector('[data-search-input-container]');
	const data_search_input        = $('[data-search-input]');
	const data_search_bars         = document.querySelector('[data-search-bars]');
	const data_search_bar_cty      = document.querySelector('[data-search-bar-county]');
	const data_search_bar_ntl      = document.querySelector('[data-search-bar-national]');
	const data_search_bar_ntl_perc = data_search_bar_ntl.getAttribute('data-width')
	const data_search_bar_cty_name = document.querySelector('[data-search-bar-county-name]');
	const data_search_bar_cty_lbl  = document.querySelector('[data-search-bar-county-index]');
	const data_search_bar_ntl_lbl  = document.querySelector('[data-search-bar-ntl-index]');
	let data_search_timeout        = undefined;

	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(`${NAME} > init`);
		addHandlers();
		circle.setAttribute('data-safari', isSafari());
		gsap.set(three_sub_index_1, {xPercent: 123});
		gsap.set(three_sub_index_2, {xPercent: 123});
		gsap.set(three_sub_index_3, {xPercent: 123});
		w.el.id('indexes-cont').style.visibility = 'visible';
		transInThreeSubIndexes();
		bootstrapAutocomplete();
		gatherCompareChartDataForCounty();
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(`${NAME} > addHandlers`);
		w.evt.add(window, 'resize', onWindowResize);
	}

	/**
	 * bootstrapAutocomplete --- Setup all autocomplete functionality
	 */
	function bootstrapAutocomplete() {

		// Grab all locations: CBSAs and Counties
		if (window.index_vars && window.index_vars.vf_get_all_locations) {
			w.log.log(NAME + ' > All locations (CBSAs, Counties) were preloaded.');
			window.app.global.vars.cbsas               = window.index_vars.vf_get_all_locations.cbsas;
			window.app.global.vars.counties            = window.index_vars.vf_get_all_locations.counties;
			window.app.global.vars.countiesToCbsas     = window.index_vars.vf_get_all_locations.counties_to_cbsas;
			window.app.global.vars.searchableLocations = window.app.global.vars.counties.concat(window.app.global.vars.cbsas);
			$(window).trigger('vf_location_data_loaded');

		} else {
			const data = {
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
						$(window).trigger('vf_location_data_loaded');
					} else {
						w.log.log(NAME + ' > There was an error while loading all locations.');
					}
				}
			});
		}

		$(window).on('vf_search_selection_completed', () => {
			let data = window.app.global.vars.searchSelection ? window.app.global.vars.countyData[window.app.global.vars.searchSelection.value] : null;
			let high = window.index_vars.search_bars_high;

			if (data_search_timeout) clearTimeout(data_search_timeout);
			data_search_bar_cty.style.width = "0";
			data_search_bar_cty.classList.remove('microbusiness-index-search__bar--trans-in');
			data_search_bar_ntl.style.width = "0";
			data_search_bar_ntl.classList.remove('microbusiness-index-search__bar--trans-in');
			data_search_bar_cty_name.innerHTML = '-';
			data_search_bar_cty_lbl.innerHTML  = '-';

			if (data) {
				data_search_timeout = setTimeout(() => {
					data_search_bars.classList.add('microbusiness-index-search__bars--active');
					data_search_bar_cty_name.innerHTML = window.app.global.vars.searchSelection.label;

					let ntl_val = {val: 0}, ntl_new_val = 102;
					let cty_val = {val: 0}, cty_new_val = data.activity_index.toFixed(0);

					gsap.to(ntl_val, 1.5, {
						val: ntl_new_val, roundProps: "val",
						onStart: () => {
							data_search_bar_ntl_lbl.innerHTML = '1'
						},
						onUpdate: () => {
							data_search_bar_ntl_lbl.innerHTML = ntl_val.val
						}
					});

					gsap.to(cty_val, 1.5, {
						val: cty_new_val, roundProps: "val",
						onStart: () => {
							data_search_bar_cty_lbl.innerHTML = '1'
						},
						onUpdate: () => {
							data_search_bar_cty_lbl.innerHTML = cty_val.val
						}
					});

					data_search_bar_cty.style.width = (((data.activity_index / high) * 0.80 * 100)) + "%";
					data_search_bar_ntl.style.width = data_search_bar_ntl_perc;
					data_search_bar_cty.classList.add('microbusiness-index-search__bar--trans-in');
					data_search_bar_ntl.classList.add('microbusiness-index-search__bar--trans-in');
				}, 400);

				data_graph_2_county_tag.innerHTML = window.app.global.vars.searchSelection.label;
				data_graph_2_county_tag.classList.add('microbusiness-index-graph-2__chart-tools-location--active');

				gatherCompareChartDataForCounty(window.app.global.vars.searchSelection.value);
			}
		});

		// Handle ARROW input and ENTER
		let focusedAutocompleteSearchResult = null;
		data_search_input.on('keydown', function (e) {
			data_search_input_cont.classList.add('microbusiness-index-search__search-input--keydown');
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
		data_search_input.on('keyup', function (e) {

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
				}).then(r => console.warn(r));
			});
		});

		// Handle blurs on the search fields
		data_search_input.on('blur', function () {
			data_search_input_cont.classList.remove('microbusiness-index-search__search-input--keydown');
			const $parent       = $(this).closest('[data-search-input-container]');
			const $autocomplete = $parent.find('[data-search-autocomplete]');

			$autocomplete.removeAttr('data-search-autocomplete-active');

			setTimeout(function () {
				data_search_input.val('');
			}, 250);

		});

		$('[data-search-selection-1]').on('click', function () {
			removeSearchAutocompleteSelection(1);
		});

	}

	// When a person selects a specific county,
	// we need to gather data about it and populate
	// various parts of the page.
	async function gatherSpecificDataForCounty(county) {

		if (!window.app.global.vars.countyData) {
			window.app.global.vars.countyData = [];
		}

		if (window.app.global.vars.countyData[county]) {
			w.log.log(NAME + ` > Data already gathered for county: ${county}`);
			return true;
		}
		w.log.log(NAME + ` > Loading specific data for county: ${county}`);

		// Set up the default record for this CBSA
		// This will prevent from having to load it again for this page load.
		window.app.global.vars.countyData[county] = {};

		// Gather the Venture Density information
		const data = {
			action: 'vf_ajax_get_county_specifics_for_data_page',
			ajax_nonce: global_vars.ajax_nonce,
			county: county,
		};
		await $.ajax(global_vars.ajax_url, {
			type: 'post',
			data: data,
			success: function (response) {
				if (response.success) {
					w.log.log(NAME + ` > Page data for ${window.app.global.vars.searchSelection.label} loaded as: ${response.data}`);

					window.app.global.vars.countyData[county] = response.data;
				}
			}
		});

		return true;
	}

	async function gatherCompareChartDataForCounty(county) {

		var data = {
			action: 'vf_ajax_get_compare_chart_for_index_page',
			ajax_nonce: global_vars.ajax_nonce,
			county: county
		};

		let chartData = {};

		await $.ajax(global_vars.ajax_url, {
			type: 'post',
			data: data,
			success: function (response) {
				if (response.success) {
					w.log.log(NAME + ' > Chart data loaded.');
					data_search_input_cont.classList.remove('microbusiness-index-search__search-input--searching');
					let data = {
						labels: response.data.labels,
						datasets: response.data.datasets
					}

					chartData = data;
				}
			}
		});

		// If mobile, adjust the font sizes, etc
		if ($(window).outerWidth() < 768) {
			Chart.defaults.global.defaultFontSize  = 12;
			Chart.defaults.global.defaultFontStyle = 'normal';
		}

		// Configure the yAxes
		// If there is a comparison, we need to add the
		// right side axis
		let yAxis = {
			id: 'activity_index',
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

		// Set up the chart
		const ctx        = $('#compare-chart');
		let compareChart = new Chart(ctx, {
			type: 'line',
			data: chartData,
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

	function isSafari() {
		return (navigator.vendor.match(/apple/i) || "").length > 0
	}

	/**
	 * onMicrobusinessIndexFutureEvent --- Open the selected video in a modal
	 * @param evt
	 */
	function onMicrobusinessIndexFutureEvent(evt) {
		let target = evt.target;

		if (target && target.nodeName === "BUTTON") {
			let classes = target.className.split(" ");
			if (classes) {
				// Search for class and react on match
				for (let x = 0; x < classes.length; x++) {
					switch (classes[x]) {
						case "microbusiness-index__placeholder":
							break;
					}
				}
			}
		}
	}

	/**
	 * onWindowResize --- Monitor Body width changes
	 * @param evt
	 */
	function onWindowResize(evt) {
		updateCursors();
	}

	// Handle the removal of a search autocomplete selection
	function removeSearchAutocompleteSelection(slot) {
		if (slot !== 1) {
			w.log.log(NAME + ` > Incorrect slot provided. Please provide 1 (slot 2 is no longer supported).`);
			return;
		}

		if (slot === 1) {
			window.app.global.vars.searchSelection = null;
		}

		selectSearchAutocompleteResult().then(r => console.warn(r));
	}

	// Handle the click of an autocomplete result.
	async function selectSearchAutocompleteResult(selected) {

		data_search_bars.classList.remove('microbusiness-index-search__bars--active');
		data_graph_2_county_tag.classList.remove('microbusiness-index-graph-2__chart-tools-location--active');

		data_search_input.val('');

		if (selected) {
			w.log.log(NAME + ` > Autocomplete result: ${selected} | Selection type: ${selected.type}`);

			// If a CBSA was selected, we need to map it to a county,
			// as this page only supports counties
			if (selected.type === 'cbsa') {
				let mapping = await window.app.global.vars.countiesToCbsas.find(o => o.cbsa === selected.value);

				// If we can't find a mapping, clear the selection entirely
				if (!mapping) {
					selected = null;
				} else {
					w.log.log(NAME + ` > Found mapping of CBSA to County: ${mapping}`);
					let county = await window.app.global.vars.counties.find(o => Number(o.county) === Number(mapping.county));

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
			if (window.app.global.vars.searchSelection == null) {
				window.app.global.vars.searchSelection = selected;

				// No slots are available
			} else {
				w.log.log(NAME + ` > No open search selection slots. Aborting search autocomplete selection assignment...`);
			}
		}

		// For convenience...
		w.log.log(NAME + ` > Selected search results: ${window.app.global.vars.searchSelections}`);

		// Hide the search bar?
		if (
			window.app.global.vars.searchSelection != null
		) {
			data_search_input.hide();
		} else {
			data_search_input.show();
		}

		// Do things for the first slot?
		if (window.app.global.vars.searchSelection != null) {

			w.log.log(NAME + ` > Populating UI for the first selection slot...`);
			data_search_input_cont.classList.add('microbusiness-index-search__search-input--searching');
			let labelParts = window.app.global.vars.searchSelection.label.split(',');
			labelParts.pop();

			// Show the first slot
			$('[data-search-selection-1]')
				.html(labelParts.join(','))
				.show();

			// Get all the info that the page will need for this CBSA
			await gatherSpecificDataForCounty(window.app.global.vars.searchSelection.value);

		} else {
			$('[data-search-selection-1]')
				.html('')
				.hide();
		}

		$(window).trigger('vf_search_selection_completed');

	}

	/**
	 * updateCursors --- Update the parent element's width according to the tallest children’s height
	 */
	function updateCursors() {
		const winWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

		if (winWidth <= 959) {
			allCursors.forEach((element) => {
				element.parentElement.setAttribute('viewBox', "0 0 9 79");
				element.setAttribute('y2', "74.5");
			})
		} else {
			allCursors.forEach((element) => {
				element.parentElement.setAttribute('viewBox', "0 0 9 71");
				element.setAttribute('y2', "66.5");
			})
		}
	}

	function transInThreeSubIndexes() {
		let positionAdjustment1   = 0;
		const positionAdjustment2 = 0.81;
		let tl                    = gsap.timeline({
			duration: .3,
			ease: "timingFuncEaseOutBack3",
			paused: true,
			onStart: () => {
				three_indexes_bkgd_gp.classList.add('background_group--rotate');
				onH2ShadeMoveCompleteAndReset(undefined);
			},
			scrollTrigger: {
				// markers: true,
				trigger: "#three-sub-indexes",
				toggleActions: "restart pause resume pause",
				start: "-130px 65%",
				end: "bottom 100px", // end starts at bottom of main nav
				onLeave: () => three_indexes_bkgd_gp.classList.remove('background_group--rotate'),
				onEnterBack: () => three_indexes_bkgd_gp.classList.add('background_group--rotate'),
				onLeaveBack: () => three_indexes_bkgd_gp.classList.remove('background_group--rotate'),
			},
		});

		for (let x = 1; x < 4; x++) {
			tl.to(`#curve__${x}`, {
				duration: 1,
				fill: three_indexes_colors[x]
			});
			positionAdjustment1 = .2;
			tl.to(`#three-sub-index__${x}`, {
				xPercent: 0,
				ease: "timingFuncEaseOutBack3",
				onComplete: onH2ShadeMoveCompleteAndReset,
				onCompleteParams: [x],
			}, `>-${positionAdjustment2}`);
		}
	}

	/**
	 * onH2ShadeMoveCompleteAndReset --- Play cursor and canned animations by target upon category card animation conclusion
	 * @param indexcty_val The individual index container, plus, this passing undefined will cause this handler to act as a reset
	 */
	function onH2ShadeMoveCompleteAndReset(indexcty_val = 99) {
		// w.log.log(`${NAME} > onH2ShadeMoveCompleteAndReset > Play animation: indexcty_val = ${indexcty_val}`);

		if (indexcty_val === 99) {
			// Reset
			for (let x = 1; x < 4; x++) {
				const idx = w.el.id(`three-sub-index__${x}`);
				idx.getElementsByClassName('microbusiness-index-three-sub-indexes__h2-cont-shade')[0].classList.remove("city-portal-four-factors__h4-cont-shade--trans-in");
				idx.getElementsByTagName('line')[0].classList.remove('microbusiness-index-three-sub-indexes__line-vert--trans-in');
			}
		} else {
			const idx = w.el.id(`three-sub-index__${indexcty_val}`);
			idx.getElementsByClassName('microbusiness-index-three-sub-indexes__h2-cont-shade')[0].classList.add("city-portal-four-factors__h4-cont-shade--trans-in");
			idx.getElementsByTagName('line')[0].classList.add('microbusiness-index-three-sub-indexes__line-vert--trans-in');
		}
	}

	init();
}
