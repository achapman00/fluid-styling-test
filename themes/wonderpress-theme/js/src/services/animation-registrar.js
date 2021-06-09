//  Service
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'ANIMATION-REGISTRAR';

	w.log.log(NAME + ' > service was loaded.');

	const bdy                   = document.body;
	/**
	 * init --- Initialize the service
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
		w.evt.addMultiple(bdy,["animationend","webkitAnimationEnd","oAnimationEnd",'mouseup'], onDownload, true);
		w.evt.addMultiple(w.el.id('global-nav-mobile-tablet_nav'),["animationend","webkitAnimationEnd","oAnimationEnd",'click'], onMobileMenu, true);
		// data-ani-action="mobile-close-button"
	}

	/**
	 * onMobileMenu --- Monitors all data-ani-action === mobile-close-button activity and reacts to state changes
	 * @param evt
	 */
	function onMobileMenu(evt) {
		// w.log.log(NAME + ' > onMobileMenu');
		let target = evt.target;
		if (target && (target.nodeName === "BUTTON")) {
			// console.log(NAME + ' > onMobileMenu > A/BUTTON',evt.type);
			switch (evt.type) {
				case 'click':
					// w.log.log(NAME + ' > onMobileMenu > click, play animation');
					// console.log(NAME, target);
					if (!target.hasAttribute('data-ani-action')) return;
					const close_match_btn = target.getAttribute('data-ani-action').search(/close/i);
					if (close_match_btn > -1){
						// console.log(NAME + ' > onMobileMenu > found close substring at:',close_match_btn);
						target.getElementsByTagName('SPAN')[0].classList.add("reveal");
					}
				// else console.log(NAME + ' > onMobileMenu >',dwn_match_btn);
				default:
					// console.log(NAME + ' > onMobileMenu > evt.type =',evt.type, target);
					if (target.classList.contains('download-ani')) setTimeout(() => {target.classList.remove("reveal");}, 770);

			}
		}
	}

	/**
	 * onDownload --- Monitors all data-ani-action === download activity and reacts to state changes
	 * @param evt
	 */
	function onDownload(evt) {
		// w.log.log(NAME + ' > onDownload');
		let target = evt.target;
		// evt.stopPropagation();
		if (target && (target.nodeName === "A" || target.nodeName === "BUTTON" || target.nodeName === "SPAN")) {
			// console.log(NAME + ' > onDownload > A/BUTTON',evt.type);
			switch (evt.type) {
				case 'mouseup':
					// w.log.log(NAME + ' > onDownload > mouse up, play animation');
					// console.log(NAME, target);
					if (!target.hasAttribute('data-ani-action')) return;
					const dwn_match_btn = target.getAttribute('data-ani-action').search(/download/i);
					if (dwn_match_btn > -1){
						// console.log(NAME + ' > onDownload > found Download substring at:',dwn_match_btn);
						target.getElementsByTagName('SPAN')[0].classList.add("reveal");
					}
				// else console.log(NAME + ' > onDownload >',dwn_match_btn);
				default:
					// console.log(NAME + ' > onDownload > evt.type =',evt.type, target);
					if (target.classList.contains('download-ani')) setTimeout(() => {target.classList.remove("reveal");}, 770);

			}
		}
	}

	init();
}

