/*
* GLOBAL DEPENDENCIES
* ---------------------
* Set up any dependencies that will be required across
* the board. If there are dependencies that are only required
* on specific pages, you should instead include them in the
* script for that page (inside /pages/mypage.js)
*/
window.jQuery = window.$ = require('jquery');

/*
* APP OBJECT
* ---------------------
* We are going to store all app-specific logic
* inside window.app, so that we don't muddy the namespaces.
*/
window.app = {

	/*
    * GLOBAL BOOT
    * ---------------------
    * Things that should only happen once, ever.
	*/
	boot: function () {

		// Lets us know that the app has booted.
		console.info('✨Do you believe in magic?✨');

		// Put anything that should be booted *once* on page load, below..
		// ...

		// Run the init() method for the app
		app.global.init();
	},

	global: {

		/*
	    * GLOBAL VARIABLES
	    * ---------------------
	    * A place to stash variables that are referenced
	    * on a global level
		*/
		vars: {
			window: {
				w: 0,
				h: 0
			}
		},

		/**
		 * REUSABLE INITIALIZATION
		 * •••••••••••••••••••••••••••••••••••••••••••••••••••••••••
		 * Is guaranteed to be run on page load.
		 * This can also be safely re-run periodically after page load
		 */
		init: function () {
			/**
			 * Initialize Utility if it contains an init method
			 * •••••••••••••••••••••••••••••••••••••••••••••••••••••••••
			 */
			for (let u in window.app.utilities) {
				if (window.app.utilities[u].init) {
					window.app.utilities[u].init();
				}
			}

			/**
			 * INITIALIZE ALL GLOBAL SERVICES
			 * •••••••••••••••••••••••••••••••••••••••••••••••••••••••••
			 */
			for (let s in window.app.services) {
				if (window.app.services[s]) {
					window.app.services[s]();
				}
			}

			/**
			 * Initialize Shims if it contains an init method
			 * •••••••••••••••••••••••••••••••••••••••••••••••••••••••••
			 */
			for (let sh in window.app.shims) {
				if (window.app.shims[sh]) {
					window.app.shims[sh]();
				}
			}

			/**
			 * INITIALIZE ANY PAGE-SPECIFIC SCRIPTS
			 * •••••••••••••••••••••••••••••••••••••••••••••••••••••••••
			 * We are going to use the body id attribute to
			 * attempt to load any scripts for this specific page
			 *
			 * Note: when coding your html templates, make sure to set
			 * the ID of the body tag to something like <body id="home">
			 */
			let id = $('body').attr('id');

			if (id) {
				id = id.replace(/-/g, '_');
				if (window.app.pages[id]) {
					window.app.pages[id]();
				}
			}
		},

	},

	/*
	* PAGE-SPECIFIC SCRIPTS
	* ---------------------
	* Load all scripts that should only be initialized
	* on specific pages
	*/
	pages: {
		home: require('./pages/home'),
		explore_the_data: require('./pages/explore-the-data'),
		reports_and_resources: require('./pages/reports-and-resources'),
		stories_and_use_cases: require('./pages/stories-and-use-cases'),
		about: require('./pages/about'),
		http_status_404: require('./pages/http-status-404'),
		story: require('./pages/story'),
		unsupported_browser: require('./pages/unsupported-browser'),
		city_portal: require('./pages/city-portal'),
		microbusiness_index: require('./pages/microbusiness-index')
	},

	/*
	* GLOBAL SERVICES
	* ---------------------
	* Load any scripts / services that should be initialized
	* on every page. This is good for things like analytics, UI
	* elements that are present on every page, utilities, etc.
	*/
	services: {
		global_nav_mobile_tablet_desktop: require('./services/global-nav-mobile-tablet-desktop'),
		global_small_dual_bar_chart: require('./services/global-small-dual-bar-chart'),
		modal: require('./services/modal'),
		carousel: require('./services/global-horizontal-carousel'),
		fb: require('./services/facebook-sdk'),
		twitter: require('./services/twitter-sdk'),
		animation_registrar: require('./services/animation-registrar')
	},

	/**
	 * Global Utilities
	 * These utilities will be available application-wide
	 */
	utilities: {
		el: require('./utilities/elements'),
		evt: require('./utilities/events'),
		log: require('./utilities/log'),
		str: require('./utilities/strings')
	},

	/**
	 * Global Shims
	 * These shims will be available application-wide
	 */
	shims: {
		requestAnimFrame: require('./shims/requestAnimFrame')
	}
};

/**
 * Add utilities alias to window for convenience
 */
window.w = window.app.utilities;

/*
* KICK EVERYTHING OFF
* ---------------------
* Wait for the document to load and then boot our app.
*/
(function (root, $, undefined) {
	"use strict";
	$(function () {
		// Boot the app
		window.app.boot();
	});
}(this, jQuery));
