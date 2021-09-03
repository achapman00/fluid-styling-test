//  Service
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'MODAL';

	w.log.log(NAME + ' > service was loaded.');

	const html          = document.getElementsByTagName('html')[0];
	const bdy           = document.body;
	const body_blackout = document.querySelector('.global-body-blackout');

	/**
	 * init --- Initialize the service
	 */
	function init() {
		// w.log.log(NAME + ' > init');
		setupEvents();
		addHandlers();
		// console.log(bdy);
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(NAME + ' > addHandlers');
		w.evt.addMultiple(bdy, [window.app.services.modal.evts.MODEL_CLOSE, window.app.services.modal.evts.MODEL_OPEN], onModal);
		w.evt.add(body_blackout, 'click', onModalClose);

		// const close_btns = document.querySelectorAll(".global-modal__close-btn");
		// close_btns.forEach(function (btn, idx) {w.evt.add(btn, 'click', onModalClose);});
	}

	/**
	 * Allow body blackout element to close the modal upon click
	 * @param evt
	 */
	function onModalClose(evt) {
		w.evt.fire(bdy, window.app.services.modal.evts.MODEL_CLOSE, {});
	}

	/**
	 * setupEvents --- Add events that each view can trigger
	 */
	function setupEvents() {
		// w.log.log(NAME + ' > setupEvents');
		window.app.services.modal.evts = {
			MODEL_OPEN: 'model_open',
			MODEL_CLOSE: 'model_close',
			MODEL_TYPE: {
				ABOUT: 'about'
			}
		};
	}

	let requested_modal;

	/**
	 * onModal --- Catch modal events launched application-wide and perform an action
	 * @param evt
	 */
	function onModal(evt) {
		if (!evt.data) return;
		switch (evt.type) {
			case window.app.services.modal.evts.MODEL_OPEN:
				// console.log(evt.data.modal);
				switch (evt.data.modal) {
					case window.app.services.modal.evts.MODEL_TYPE.ABOUT:
						html.classList.add('html--blacked-out');
						bdy.classList.add('body--blacked-out');
						w.el.id("global-nav").classList.add('global-nav--blacked-out');
						body_blackout.classList.add('global-body-blackout--blacked-out');
						requested_modal = document.querySelector(`[data-modal="${window.app.services.modal.evts.MODEL_TYPE.ABOUT}"]`);
						requested_modal.classList.add('global-modal--visible');
						useTemplate(window.app.services.modal.evts.MODEL_TYPE.ABOUT, evt.data);
						break;
				}
				// if (bdy.getAttribute('data-device-ios').toLowerCase() ==="yes" )
				window.scrollTo(0, 0);
				break;
			case window.app.services.modal.evts.MODEL_CLOSE:
				html.classList.remove('html--blacked-out');
				bdy.classList.remove('body--blacked-out');
				w.el.id("global-nav").classList.remove('global-nav--blacked-out');
				body_blackout.classList.remove('global-body-blackout--blacked-out');

				const modals = document.querySelectorAll(".global-modal");

				modals.forEach(function (el, idx) {
					el.classList.remove('global-modal--visible');
				});
				break;
			default:
				console.warn(NAME + '  > onModal > No matching case for =', evt);
		}

	}

	function useTemplate(requestedTpl, options) {
		/**
		 * Test to see if the browser supports the HTML template element
		 */
		if ('content' in document.createElement('template')) {

			const template = document.querySelector(`#${requestedTpl}_tpl`);
			const clone    = template.content.cloneNode(true);

			switch (requestedTpl) {
				case window.app.services.modal.evts.MODEL_TYPE.ABOUT:
					if (document.contains(document.getElementById("video_player"))) document.getElementById("video_player").remove();
					let div = clone.getElementById("video_player");
					div.setAttribute('data-vimeo-id', options.video);
					requested_modal.appendChild(clone);
					break;
			}
		} else {
			/**
			 * Add a backup solution here
			 */
		}
	}

	init();
}

