module.exports = function() {

	const NAME = 'GLOBAL-SMALL-DUAL-BAR-CHART';

	w.log.log(NAME + ' > service was loaded.');

	function init() {
		$('[data-global-small-dual-bar-chart]').each((i,v) => {

			w.log.log(NAME + ' > Initializing Small Dual Bar Chart...');

			if($(v).children('[data-global-small-dual-bar-chart-left-bar]').length) {
				w.log.log(NAME + ' > Initializing left bar...');
				let $leftBar = $(v).children('[data-global-small-dual-bar-chart-left-bar]').first();
				let heightPercentage = $leftBar.data('height-percentage');
				$leftBar.css('height', heightPercentage + '%');
			}

			if($(v).children('[data-global-small-dual-bar-chart-right-bar]').length) {
				w.log.log(NAME + ' > Initializing right bar...');
				let $rightBar = $(v).children('[data-global-small-dual-bar-chart-right-bar]').first();
				let heightPercentage = $rightBar.data('height-percentage');
				$rightBar.css('height', heightPercentage + '%');
			}
		});
	}

	init();
}
