//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'UNSUPPORTED BROWSER';

	w.log.log(NAME + ' > page was loaded.');

	/**
	 * init --- Initialize the view
	 */
	function init() {
		w.log.log(NAME + ' > init');
		addHandlers();

		const browser_cards          = document.querySelectorAll(".unsupported-browsers-hero__browser-card");
		browser_cards.forEach(function (element, idx) {
			element.style.opacity = '1';
		});
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		w.log.log(NAME + ' > addHandlers');
	}

	init();
}
