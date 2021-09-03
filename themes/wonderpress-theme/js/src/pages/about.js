import Player from "@vimeo/player";

//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'ABOUT';

	w.log.log(`${NAME} > page was loaded.`);

	const bdy             = document.body;
	const thumbs_cont     = w.el.id('thumbnails');
	const thumbs          = thumbs_cont.querySelectorAll(".about-hero-video__thumbnail");
	const thumbnail_large = w.el.id('thumbnail_large');
	let plyr;

	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(`${NAME} > init`);
		addHandlers();

		setTimeout(() => {
			document.querySelectorAll(".global-featured-articles__a").forEach(function (element) {
				element.style.opacity = '1';
			});
		}, 333);
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(`${NAME} > addHandlers`);
		w.evt.add(w.el.id('video-section'), 'click', onVideoPosterThumbnail);
		w.evt.add(bdy, window.app.services.modal.evts.MODEL_CLOSE, onModal, true);
	}

	/**
	 * onVideoPosterThumbnail --- Open the selected video in a modal
	 * @param evt
	 */
	function onVideoPosterThumbnail(evt) {
		let target = evt.target;

		if (target && target.nodeName === "BUTTON") {
			let classes = target.className.split(" ");
			if (classes) {
				// Search for class and react on match
				for (let x = 0; x < classes.length; x++) {
					switch (classes[x]) {
						case "about-hero-video__thumbnail":
							let vid_id = target.getAttribute('data-video');

							if (target !== thumbnail_large) {
								// Reset all thumbnails
								thumbs.forEach(function (element) {
									element.setAttribute('data-disabled', '0');
								});

								// Set disabled thumbnail
								target.setAttribute('data-disabled', '1');
							}

							thumbnail_large.getElementsByTagName('IMG')[0].src = target.getElementsByTagName("IMG")[0].src;

							// Request modal to open and pass selected video id
							w.evt.fire(bdy, window.app.services.modal.evts.MODEL_OPEN, {
								modal: window.app.services.modal.evts.MODEL_TYPE.ABOUT,
								video: vid_id
							});

							// Utilize Vimeo SDK methods and events. See README for SDK link
							plyr = new Player('video_player');
							plyr.on('play', function () {
								// w.log.log(`${NAME} > Vimeo > playing…`);
							});
							break;
					}
				}
			}
		}
	}

	/**
	 * onModal --- Catch modal events launched application-wide and perform an action
	 * @param evt
	 */
	function onModal(evt) {
		if (!evt.data) return;
		switch (evt.type) {
			case window.app.services.modal.evts.MODEL_OPEN:
				// Not using this currently for this view
				break;
			case window.app.services.modal.evts.MODEL_CLOSE:
				if (plyr) plyr.destroy().then(function () {
					w.log.log(`${NAME} > onModal > Vimeo > The player is destroyed`);
				});
				break;
			default:
				console.warn(`${NAME} >  onModal > No matching case for = ${evt}`);
		}
	}

	init();
}
