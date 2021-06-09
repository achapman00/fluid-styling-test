//  Service
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'GLOBAL-NAV-MOBILE-TABLET-DESKTOP';

	w.log.log(NAME + ' > service was loaded.');

	const bdy                   = document.body;
	const main                  = document.getElementsByTagName('main')[0];
	const footer                = w.el.id('footer');
	const supportsVib           = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate || null;
	const mobileTablet_menu     = w.el.id('mobile-tablet-menu');
	const mobileTablet_anchors  = document.querySelectorAll('.global-nav-mobile-tablet__a');
	window.app.touchDevice      = (('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
	window.app.noOfSlides       = !window.app.touchDevice ? 2 : 1;
	window.app.uri_assets_imgs  = bdy.getAttribute('data-uri-assets-imgs');
	/**
	 * init --- Initialize the service
	 */
	function init() {
		// w.log.log(NAME + ' > init');
		addHandlers();
		if (bdy.id === 'stories-and-use-cases' || bdy.id === 'story'){
			const desktop_anchors  = document.querySelectorAll('.global-nav-desktop__a');
			for (const [index, val] of desktop_anchors.entries()) {
				// console.log(NAME + ` > init > ${index}, ${val}`,val);
				if (val.getElementsByTagName('SPAN')[0].textContent.search(/Use Cases/i) > -1) {
					val.setAttribute('aria-current',"page");
					break;
				}
			}
		}
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(NAME + ' > addHandlers');
		w.evt.add(w.el.id("global-nav"), 'click', onGlobalNav);
	}

	function onGlobalNav(evt) {
		let target = evt.target;
		// w.log.log("onGlobalNav >", w.log.console__style001, target, target.nodeName);

		if (target && target.nodeName === "BUTTON" || target.nodeName === "A") {
			let classes = target.className.split(" ");
			if (classes) {
				// Search for class and react on match
				for (let x = 0; x < classes.length; x++) {

					switch (classes[x]) {
						case "global-nav-mobile-tablet__button":

							if (supportsVib) navigator.vibrate(76);

							if (mobileTablet_menu.getAttribute('aria-expanded') === 'false') {
								mobileTablet_menu.setAttribute('aria-expanded', 'true');
								main.setAttribute('disabled', true);
								footer.setAttribute('disabled', true);


								mobileTablet_anchors.forEach(function (el) {
									// el.style.opacity = '1';
									el.classList.add('global-nav-mobile-tablet__a--vis');
								});

								// display close icon and hide open icon
								target.getElementsByTagName('IMG')[0].classList.add('hide');
								target.getElementsByTagName('DIV')[0].classList.add('reveal');

							} else {
								mobileTablet_menu.setAttribute('aria-expanded', 'false');
								main.setAttribute('disabled', false);
								footer.setAttribute('disabled', false);

								mobileTablet_anchors.forEach(function (el) {
									// el.style.opacity = '0';
									el.classList.toggle('global-nav-mobile-tablet__a--vis');
								});

								// hide close icon and display open icon
								target.getElementsByTagName('IMG')[0].classList.remove('hide');
								target.getElementsByTagName('DIV')[0].classList.remove('reveal');
							}

							break;
						case 'global-nav-mobile-tablet__a':
							// w.log.log("global-nav-mobile-tablet__a");
							if (supportsVib) navigator.vibrate([20, 76, 20]);
							break;
						case 'global-nav-mobile-tablet__tm':
							if (supportsVib) navigator.vibrate(76);
							break;

					}
				}
			}
		}
	}

	init();
}

