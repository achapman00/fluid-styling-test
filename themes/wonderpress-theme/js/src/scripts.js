// Production steps of ECMA-262, Edition 5, 15.4.4.18
// Reference: https://es5.github.io/#x15.4.4.18

if (!Array.prototype['forEach']) {

	Array.prototype.forEach = function(callback, thisArg) {

		if (this == null) { throw new TypeError('Array.prototype.forEach called on null or undefined'); }

		var T, k;
		// 1. Let O be the result of calling toObject() passing the
		// |this| value as the argument.
		var O = Object(this);

		// 2. Let lenValue be the result of calling the Get() internal
		// method of O with the argument "length".
		// 3. Let len be toUint32(lenValue).
		var len = O.length >>> 0;

		// 4. If isCallable(callback) is false, throw a TypeError exception.
		// See: https://es5.github.com/#x9.11
		if (typeof callback !== "function") { throw new TypeError(callback + ' is not a function'); }

		// 5. If thisArg was supplied, let T be thisArg; else let
		// T be undefined.
		if (arguments.length > 1) { T = thisArg; }

		// 6. Let k be 0
		k = 0;

		// 7. Repeat, while k < len
		while (k < len) {

			var kValue;

			// a. Let Pk be ToString(k).
			//    This is implicit for LHS operands of the in operator
			// b. Let kPresent be the result of calling the HasProperty
			//    internal method of O with argument Pk.
			//    This step can be combined with c
			// c. If kPresent is true, then
			if (k in O) {

				// i. Let kValue be the result of calling the Get internal
				// method of O with argument Pk.
				kValue = O[k];

				// ii. Call the Call internal method of callback with T as
				// the this value and argument list containing kValue, k, and O.
				callback.call(T, kValue, k, O);
			}
			// d. Increase k by 1.
			k++;
		}
		// 8. return undefined
	};
}

// missing forEach on NodeList for IE11
if (window.NodeList && !NodeList.prototype.forEach) {
	NodeList.prototype.forEach = Array.prototype.forEach;
}

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
* Setting up analytics
*/
window._expDataLayer = window._expDataLayer || [];

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
		unsupported_browser: require('./pages/unsupported-browser')
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





// CSS Grid Annotator for IE 11
//
// Version: 0.2.1
// Author: Tom Rothe
// URL: https://github.com/motine/css_grid_annotator
//
// Please see notes in README.md.
// NOTE: This annotation is quite expensive (only for IE11), because check every element on the page for its display type.
//       Furthermore, for each grid container we look through all style definitions.
/* eslint-disable */
function cssGridAnnotate() {

	// console.log('Detecting browser...');

	// check if we have IE11
	var agent = navigator.userAgent;
	var isIE11 = (agent.indexOf("Trident") >= 0) && (agent.indexOf("rv:11") >= 0);
	if (!isIE11) {
		// console.log('Browser is supported!');
		return;
	}

	// console.log('Detected');

	var CSS_DISPLAY_GRID = "-ms-grid";
	var CSS_TEMPLATE_COLS = "-ms-grid-columns";
	var CSS_ROW = "-ms-grid-row";
	var CSS_COL = "-ms-grid-column";

	function annotateAll(parentElement) {
		// we have to go through every single element to check the computed style
		// this is very performance heavy
		var elements = parentElement.querySelectorAll("*");
		var elementsLength = elements.length;
		for (var i = 0; i < elementsLength; i++) {
			var elm = elements[i];
			if (isGridContainer(elm) && !containsAnnotations(elm)) { // we only check grid container, but we ignore the ones with pre-defined annotations
				annotateContainer(elm);
			}
		}
	}

	// it annotates the children with grid-column and grid-row, based on the grid-template-columns style attribute.
	function annotateContainer(container) {
		// determine columns
		var colCount = getTemplateColCount(container);
		if (!colCount) { return; }
		// annotate children
		var children = container.children;
		var childrenLength = children.length;
		for (var i = 0, visibleIndex = 0; i < childrenLength; i++) { // i: which child do currently address?, visibleIndex: how many children were visible up until now? these two only differ if there are hidden elements
			var child = children[i];
			if (isHiddenElement(child)) { continue; }
			child.style[CSS_COL] = (visibleIndex % colCount) + 1;
			child.style[CSS_ROW] = Math.floor(visibleIndex / colCount) + 1;
			visibleIndex++;
		}
	}

	function handleInsert(ev) {
		if ((ev.target) && (ev.target.parentElement)) {
			annotateAll(ev.target.parentElement);
		}
	}

	function isGridContainer(elm) {
		var styles = window.getComputedStyle(elm);
		return styles.display === CSS_DISPLAY_GRID;
	}

	function isHiddenElement(elm) {
		return (elm.type === "hidden") || (window.getComputedStyle(elm).getPropertyValue("display") === "none");
	}

	// returns true if any of the direct children has CSS_COL or CSS_ROW in their computed style.
	function containsAnnotations(elm) {
		var children = elm.children;
		var childrenLength = children.length;
		for (var i = 0; i < childrenLength; i++) {
			var child = children[i];
			var styles = window.getComputedStyle(child);
			if (styles.getPropertyValue(CSS_COL) != "1" || styles.getPropertyValue(CSS_ROW) != "1") { // IE will automatically determine that all elements are at (1, 1)
				return true;
			}
		}
		return false;
	}

	// returns the number of elements in a computed grid-template-columns attribute.
	// We do not need to consider functions such as repeat or minmax, because they are not supported by IE11 anyway (so either the autoprefixer resolves them or the style definition is broken for IE11 anyway).
	function getTemplateColCount(elm) {
		var styles = window.getComputedStyle(elm);
		var templateColumns = styles.getPropertyValue(CSS_TEMPLATE_COLS);
		if (!templateColumns) { return; }
		return templateColumns.split(" ").length;
	}

	annotateAll(document.body);
	window.addEventListener("DOMNodeInserted", handleInsert, false);
}

window.addEventListener("load", cssGridAnnotate);

/**
 * @author:     Vincent V. Toscano
 * @version:    1.0
 * @date:       4/19/17
 * @time:       3:03 PM
 * SwipeSensor001 --- Use this module to track single finger swipe events and react to them.
 */
window.SwipeSensor001 = function () {
	const NAME              = 'SwipeSensor001';
	const _that             = this;
	const console__style001 = [
		'background: linear-gradient(#0013a8, orange)'
		, 'border: 1px solid #3E0E02'
		, 'color: white'
		, 'display: block'
		, 'box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255, 255, 255, 0.4) inset'
		, 'text-align: left'
		, 'font-weight: bold'
		, 'padding:3px 5px'
		, 'font: bold normal normal 16px\/normal \"Helvetica Black\", \"Helvetica Neue\", Roboto, Arial, Helvetica, sans-serif'
	].join(';');
	this.EVT_ON_SWIPE_RIGHT = "evt_on_swipe_right",
		this.EVT_ON_SWIPE_UP = "evt_on_swipe_up",
		this.EVT_ON_SWIPE_DOWN = "evt_on_swipe_down",
		this.EVT_ON_SWIPE_LEFT = "evt_on_swipe_left";
	let _powered         = false;
	let triggerElementID = null; // Used to identify the triggering element
	let fingerCount      = 0;
	let startX           = 0;
	let startY           = 0;
	let curX             = 0;
	let curY             = 0;
	let deltaX           = 0;
	let deltaY           = 0;
	let horzDiff         = 0;
	let vertDiff         = 0;
	let minLength        = 36; // the shortest distance the user may swipe in pixels
	let swipeLength      = 0;
	let swipeAngle       = null;
	let swipeDirection   = null;
	const bdy            = document.body;
	const Evt            = Evt || {
		add: function (el, evt, handler, bubbles) {
			if (el.addEventListener) {
				el.addEventListener(evt, handler, bubbles || false);
			} else {
				el.attachEvent('on' + evt, handler);
			}
		},
		rm: function (c, b, a) {
			if (c.detachEvent) {
				c.detachEvent("on" + b, c[b + a]);
				c[b + a] = null
			} else {
				c.removeEventListener(b, a, false)
			}
		},
		fire: function (el, type, obj) {
			if (document.createEvent) {
				let evt = document.createEvent("HTMLEvents");
				evt.initEvent(type, true, true);
				if (obj) evt.data = obj;
				return !el.dispatchEvent(evt);
			} else {
				let evt = document.createEventObject();
				if (obj) evt.data = obj;
				return el.fireEvent('on' + type, evt);
			}
		},
		addMultiple: function (el, evts, handler, bubbles) {
			evts.forEach(e => el.addEventListener(e, handler, bubbles || false));
		}
	};

	/**
	 * powerUp --- Initialize this module.
	 * @param triggerEl    Pass the element that will trigger the swipe events
	 * @param callBack     Optional callback method.
	 */
	this.powerUp = function (triggerEl, callBack) {
		if (_powered) return;
		_powered = true;
		addListeners(triggerEl);
		if (callBack) callBack();
		console.log("%c" + NAME + ' > powerUp >', console__style001, arguments);
	};

	function addListeners(el) {
		// Test whether or not the passive property is accessed (3rd option) is supported via a getter in the options object
		let supportsPassive = false;
		try {
			let opts = Object.defineProperty({}, 'passive', {
				get: function() {supportsPassive = true;}
			});
			window.addEventListener("testPassive", null, opts);
			window.removeEventListener("testPassive", null, opts);
		} catch (e) {}
		Evt.add(el, 'touchstart', touchStart, supportsPassive ? {passive:true} : false);
		Evt.add(el, 'touchmove', touchMove, supportsPassive ? {passive:true} : false);
		Evt.add(el, 'touchend', touchEnd);
		Evt.add(el, 'touchcancel', touchCancel);
	}

	// The 4 Touch Event Handlers
	function touchStart(evt) {
		let target = evt.target;

		// disable the standard ability to select the touched object
		//evt.preventDefault();
		// get the total number of fingers touching the screen
		fingerCount = evt.touches.length;
		// since we're looking for a swipe (single finger) and not a gesture (multiple fingers),
		// check that only one finger was used
		if (fingerCount === 1) {
			// get the coordinates of the touch
			startX           = evt.touches[0].pageX;
			startY           = evt.touches[0].pageY;
			// store the triggering element ID
			triggerElementID = target.id;
		} else {
			// more than one finger touched so cancel
			touchCancel(evt);
		}
	}

	function touchMove(evt) {
		//evt.preventDefault();
		if (evt.touches.length === 1) {
			curX = evt.touches[0].pageX;
			curY = evt.touches[0].pageY;
		} else {
			touchCancel(evt);
		}
	}

	function touchEnd(evt) {
		//evt.preventDefault();
		// check to see if more than one finger was used and that there is an ending coordinate
		if (fingerCount === 1 && curX !== 0) {
			// use the Distance Formula to determine the length of the swipe
			swipeLength = Math.round(Math.sqrt(Math.pow(curX - startX, 2) + Math.pow(curY - startY, 2)));
			// if the user swiped more than the minimum length, perform the appropriate action
			if (swipeLength >= minLength) {
				calculateAngle();
				determineSwipeDirection();
				processingRoutine();
				touchCancel(evt); // reset
			} else {
				touchCancel(evt);
			}
		} else {
			touchCancel(evt);
		}
	}

	/**
	 * touchCancel --- Reset letiables back to default values
	 * @param evt
	 */
	function touchCancel(evt) {
		fingerCount      = 0;
		startX           = 0;
		startY           = 0;
		curX             = 0;
		curY             = 0;
		deltaX           = 0;
		deltaY           = 0;
		horzDiff         = 0;
		vertDiff         = 0;
		swipeLength      = 0;
		swipeAngle       = null;
		swipeDirection   = null;
		triggerElementID = null;
	}

	/**
	 * calculateAngle --- Calculate the angle of the swipe
	 */
	function calculateAngle() {
		let X      = startX - curX;
		let Y      = curY - startY;
		let Z      = Math.round(Math.sqrt(Math.pow(X, 2) + Math.pow(Y, 2))); //the distance - rounded - in pixels
		let r      = Math.atan2(Y, X); //angle in radians (Cartesian system)
		swipeAngle = Math.round(r * 180 / Math.PI); //angle in degrees
		if (swipeAngle < 0) {
			swipeAngle = 360 - Math.abs(swipeAngle);
		}
	}

	/**
	 * determineSwipeDirection --- Use angles to determine direction ▲ ► ▼ ◄
	 * and set swipeDirection.
	 */
	function determineSwipeDirection() {
		if ((swipeAngle <= 45) && (swipeAngle >= 0)) {
			swipeDirection = 'left';
		} else if ((swipeAngle <= 360) && (swipeAngle >= 315)) {
			swipeDirection = 'left';
		} else if ((swipeAngle >= 135) && (swipeAngle <= 225)) {
			swipeDirection = 'right';
		} else if ((swipeAngle > 45) && (swipeAngle < 135)) {
			swipeDirection = 'down';
		} else {
			swipeDirection = 'up';
		}
	}

	/**
	 * processingRoutine --- Fires event on body element designating direction.
	 */
	function processingRoutine() {
		// console.log("%c" + NAME + ' > processingRoutine >', console__style001, arguments);
		if (swipeDirection === 'left') {
			Evt.fire(bdy, _that.EVT_ON_SWIPE_LEFT);
		} else if (swipeDirection === 'right') {
			Evt.fire(bdy, _that.EVT_ON_SWIPE_RIGHT);
		} else if (swipeDirection === 'up') {
			Evt.fire(bdy, _that.EVT_ON_SWIPE_UP);
		} else if (swipeDirection === 'down') {
			Evt.fire(bdy, _that.EVT_ON_SWIPE_DOWN);
		}
	}
};

/**
 * Engagement Time
 */
(function() {
	var startEngage = new Date().getTime();
	var timeEngaged = 0;
	var idleTime = 0;
	var idle = true;
	var idleReport = false;
	var idleTimer, reportTimer;
	var timeEngagedGoal = 0;
	var count = 0;

	/*  Set the user as idle, and calculate the time
		they were non-idle */
	var setIdle = function() {
		idleTime = new Date().getTime();
		timeEngaged += idleTime - startEngage;
		idle = true;
	};

	/*  Reset the 5 second idle timer.
		If the user was idle, start the non-idle timer */
	var pulse = function(evt) {
		if (idle) {
			idle = false;
			startEngage = new Date().getTime();
			idleReport = false;
		}
		window.clearTimeout(idleTimer);
		idleTimer = window.setTimeout(setIdle, 15000);
	};

	//  Utility function for attaching listeners to the window
	var addListener = function(evt, cb) {
		if (window.addEventListener) {
			window.addEventListener(evt, cb);
		} else if (window.attachEvent) {
			window.attachEvent('on' + evt, cb);
		}
	};

	/* Push an event to dataLayer every 15 seconds
		unless the user is idle.
		Also, push an event when the user leaves the page */
	var report = function(evt) {
		if (!window._analyticsDataLayer) {
			var styles = [
				'background: linear-gradient(yellow, orange)'
				, 'color: black'
				, 'border: 1px solid #3E0E02'
				, 'display: block'
				, 'box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255, 255, 255, 0.4) inset'
				, 'text-align: left'
				, 'font-weight: bold'
				, 'padding:3px 5px'
				, 'font: bold normal normal 16px\/normal \"Helvetica Black\", \"Helvetica Neue\", Roboto, Arial, Helvetica, sans-serif'
			].join(';');
			console.log('%c ENGAGEMENT TIME > [SUSPENDED] Cannot locate window._analyticsDataLayer ', styles);
			return;
		}
		if (!idle) {
			timeEngaged += new Date().getTime() - startEngage;
		}

		// Push the payload to dataLayer, and only push valid time values
		if (!idleReport && timeEngaged > 0 && timeEngaged < 3600000) {
			window._analyticsDataLayer.push({
				'event' : 'nonIdle',
				'nonIdleTimeElapsed' : timeEngaged
			});
			timeEngagedGoal = timeEngagedGoal += timeEngaged;
		}
		if (idle) {
			idleReport = true;
		}

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
								  TIME BAND EVENTS
		* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		/* 5 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 5000 && timeEngagedGoal < 14999 && count == 0) {
			console.log('ENGAGEMENT TIME >',count + " " + Date.now());
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 5
			});
			count = 1;
			console.log('ENGAGEMENT TIME >',count);
		}

		/* 15 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 15000 && timeEngagedGoal < 29999 && count == 1) {
			console.log('ENGAGEMENT TIME >',count);
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 15
			});
			count = 2;
			console.log('ENGAGEMENT TIME >',count);
		}

		/* 30 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 30000 && timeEngagedGoal < 44999 && count == 2) {
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 30
			});
			count = 3;
		}

		/* 45 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 45000 && timeEngagedGoal < 59999 && count == 3) {
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 45
			});
			count = 4;
		}

		/* 60 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 60000 && timeEngagedGoal < 119999 && count == 4) {
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 60
			});
			count = 5;
		}

		/* 120 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 120000 && timeEngagedGoal < 179999 && count == 5) {
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 120
			});
			count = 6;
		}

		/* 180 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 180000 && timeEngagedGoal < 239999 && count == 6) {
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 180
			});
			count = 7;
		}

		/* 240 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 240000 && timeEngaged < 3600000 && count == 7) {
			window._analyticsDataLayer.push({
				'event' : 'nonIdleGoal',
				'nonIdleGoalTime' : timeEngagedGoal,
				'timeBand' : 240
			});
		}

		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		// Fix possible beforeunload duplication problem
		if (evt && evt.type === 'beforeunload') window.removeEventListener('beforeunload', report);

		timeEngaged = 0;
		startEngage = new Date().getTime();
		reportTimer = window.setTimeout(report, 15000);
	};

	addListener('mousedown', pulse);
	addListener('keydown', pulse);
	addListener('scroll', pulse);
	addListener('mousemove', pulse);
	addListener('beforeunload', report);
	idleTimer = window.setTimeout(setIdle, 10000);
	reportTimer = window.setTimeout(report, 10000);
})();
