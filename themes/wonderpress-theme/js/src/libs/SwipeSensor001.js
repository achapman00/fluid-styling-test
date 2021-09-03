/**
 * @author:     Vincent V. Toscano
 * @version:    1.0
 * @date:       4/19/17
 * @time:       3:03 PM
 * SwipeSensor001 --- Use this module to track single finger swipe events and react to them.
 */
window.peace = 1;
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
