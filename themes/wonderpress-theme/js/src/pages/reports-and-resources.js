//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'RESEARCH & REPORTS';

	w.log.log(NAME + ' > page was loaded.');

	const bdy        = document.body;
	const filters    = w.el.id('filters');
	const observer_options = {
		root:null,
		rootMargin:'0px 0px -80% 0px',
		threshold: [.5]
	};
	const observer   = new IntersectionObserver(entries => {
		const [{ isIntersecting }] = entries
		if (isIntersecting) {
			// w.log.log(NAME + ' > observer > intersected');
			filters.classList.add("pinned");
		} else {
			// w.log.log(NAME + ' > observer > not-intersecting');
			filters.classList.remove("pinned");
		}
	}, observer_options);



	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(NAME + ' > init');
		if ('IntersectionObserver' in window) observer.observe(filters);
		addHandlers();
		bdy.style.overflowX = 'unset'; // makes sticky work
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(NAME + ' > addHandlers');
		w.evt.add(filters, 'input', onFilter, true);
	}

	const checkboxs      = document.getElementsByClassName("global-filter__checkbox");
	const reports        = document.getElementsByClassName("reports-and-resources__report");
	const reportBlocks        = document.getElementsByClassName("reports-and-resources-reports-and-filters__grid-block");
	const filters_select = w.el.id('filters-select');
	/**
	 * onFilter --- When the user selects a filter, this is triggered
	 * @param evt
	 */
	function onFilter(evt){
		const target = evt.target;
		let type;
		if (target && target.nodeName === "INPUT") {
			type = evt.target.getAttribute('data-type');
			resetCheckboxsAndSelect(type);
			hideReports(type);
		} else if (target && target.nodeName === "SELECT") {
			// console.log(2, target.nodeName);
			type = filters_select.options[filters_select.options.selectedIndex].value;
			resetCheckboxsAndSelect(type);
			hideReports(type);
		} else {
		}
	}

	function resetCheckboxsAndSelect(onlyShowThisType){
		//	Uncheck all others
		for(let i = 0; i < checkboxs.length; i++) {
			// console.log(i, checkboxs[i]);
			let ckbx = checkboxs[i];
			if (ckbx.getAttribute('data-type') !== onlyShowThisType) ckbx.checked = false;
			else ckbx.checked = true;
		}

		for (let j = 0; j < filters_select.options.length; j++) {
			if(filters_select.options[j].value === onlyShowThisType) filters_select.selectedIndex = j;
		}
	}

	function hideReports(onlyShowThisType){

		// Hide any blocks that do not have reports of this type
		for(let i = 0; i < reportBlocks.length; i++) {
			let reportBlock = reportBlocks[i];
			if (onlyShowThisType !== 'show-all'){
				let subscriptions = reportBlock.getAttribute('data-subscriptions').split(',');
				if (!subscriptions.includes(onlyShowThisType)) reportBlock.style.display = 'none';
				else {
					reportBlock.style.display = '-ms-grid';
					reportBlock.style.display = 'grid';
				}
			} else {
				reportBlock.style.display = '-ms-grid';
				reportBlock.style.display = 'grid';
			}
		}

		// Hide any reports that do not have this type
		for(let i = 0; i < reports.length; i++) {
			let report = reports[i];
			if (onlyShowThisType !== 'show-all'){
				let subscriptions = report.getAttribute('data-type').split(',');
				if (!subscriptions.includes(onlyShowThisType)) report.style.display = 'none';
				else report.style.display = 'block';
			} else report.style.display = 'block';
		}
	}

	init();
}
