//  GoDaddy Engagement Time
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
(function () {
	let startEngage     = new Date().getTime();
	let timeEngaged     = 0;
	let idleTime        = 0;
	let idle            = true;
	let idleReport      = false;
	let idleTimer, reportTimer;
	let timeEngagedGoal = 0;
	let count           = 0;

	/**
	 * Set the user as idle, and calculate the time they were non-idle
	 */
	const onSetIdle = function () {
		idleTime = new Date().getTime();
		timeEngaged += idleTime - startEngage;
		idle     = true;
	};

	/**
	 * Reset the 5 second idle timer. If the user was idle, start the non-idle timer.
	 *
	 */
	const onPulse = function () {
		if (idle) {
			idle        = false;
			startEngage = new Date().getTime();
			idleReport  = false;
		}
		window.clearTimeout(idleTimer);
		idleTimer = window.setTimeout(onSetIdle, 15000);
	};

	/**
	 * Utility function for attaching listeners to the window
	 * @param evt
	 * @param cb
	 */
	const addListener = function (evt, cb) {
		if (window.addEventListener) window.addEventListener(evt, cb);
		else if (window.attachEvent) window.attachEvent('on' + evt, cb);
	};

	/**
	 * Push an event to dataLayer every 15 seconds unless the user is idle.
	 * Also, push an event when the user leaves the page
	 * @param evt
	 */
	const onReport = function (evt) {
		if (!window._analyticsDataLayer) {
			const styles = [
				'background: linear-gradient(yellow, orange)'
				, 'color: black'
				, 'box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255, 255, 255, 0.4) inset'
				, 'text-align: left'
				, 'padding:3px 5px'
				, 'font: normal normal normal 10px\/normal \"Helvetica Black\", \"Helvetica Neue\", Roboto, Arial, Helvetica, sans-serif'
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
				'event': 'nonIdle',
				'nonIdleTimeElapsed': timeEngaged
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
		if (!idleReport && timeEngagedGoal > 5000 && timeEngagedGoal < 14999 && count === 0) {
			console.log('ENGAGEMENT TIME >', count + " " + Date.now());
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 5
			});
			count = 1;
			console.log('ENGAGEMENT TIME >', count);
		}

		/* 15 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 15000 && timeEngagedGoal < 29999 && count === 1) {
			console.log('ENGAGEMENT TIME >', count);
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 15
			});
			count = 2;
			console.log('ENGAGEMENT TIME >', count);
		}

		/* 30 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 30000 && timeEngagedGoal < 44999 && count === 2) {
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 30
			});
			count = 3;
		}

		/* 45 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 45000 && timeEngagedGoal < 59999 && count === 3) {
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 45
			});
			count = 4;
		}

		/* 60 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 60000 && timeEngagedGoal < 119999 && count === 4) {
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 60
			});
			count = 5;
		}

		/* 120 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 120000 && timeEngagedGoal < 179999 && count === 5) {
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 120
			});
			count = 6;
		}

		/* 180 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 180000 && timeEngagedGoal < 239999 && count === 6) {
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 180
			});
			count = 7;
		}

		/* 240 SECOND TIME BAND */
		if (!idleReport && timeEngagedGoal > 240000 && timeEngaged < 3600000 && count === 7) {
			window._analyticsDataLayer.push({
				'event': 'nonIdleGoal',
				'nonIdleGoalTime': timeEngagedGoal,
				'timeBand': 240
			});
		}

		// Fix possible beforeunload duplication problem
		if (evt && evt.type === 'beforeunload') window.removeEventListener('beforeunload', onReport);

		timeEngaged = 0;
		startEngage = new Date().getTime();
		reportTimer = window.setTimeout(onReport, 15000);
	};

	addListener('mousedown', onPulse);
	addListener('keydown', onPulse);
	addListener('scroll', onPulse);
	addListener('mousemove', onPulse);
	addListener('beforeunload', onReport);
	idleTimer   = window.setTimeout(onSetIdle, 10000);
	reportTimer = window.setTimeout(onReport, 10000);
})();
