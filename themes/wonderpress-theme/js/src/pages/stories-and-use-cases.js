//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'STORIES & USE CASES';

	w.log.log(NAME + ' > page was loaded.');

	const filters    = w.el.id('filters');
	const filters_select = w.el.id('filters-select');
	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(NAME + ' > init');
		addHandlers();
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(NAME + ' > addHandlers');
		w.evt.add(filters, 'input', onFilter, true);
	}

	/**
	 * onFilter --- When the user selects a filter, this is triggered
	 * @param evt
	 */
	function onFilter(evt){
		const target = evt.target;
		let type;
		// console.log(1, target.nodeName);
		if (target && target.nodeName === "SELECT") {
			// console.log(target.value);
			window.location = target.value;
		}
	}

	init();
}
