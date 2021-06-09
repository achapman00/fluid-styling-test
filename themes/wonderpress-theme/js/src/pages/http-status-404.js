//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'HTTP STATUS 404';

	w.log.log(NAME + ' > page was loaded.');

	/**
	 * init --- Initialize the view
	 */
	function init() {
		w.log.log(NAME + ' > init');
		addHandlers();
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		w.log.log(NAME + ' > addHandlers');
	}

	init();
}
