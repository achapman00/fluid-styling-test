// https://developer.vimeo.com/player/sdk/reference#destroy-a-player
import Player from "@vimeo/player";

//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'ABOUT';

	w.log.log(NAME + ' > page was loaded.');

	const bdy = document.body;
	let plyr;

	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(NAME + ' > init');
		addHandlers();

		const about_featured_articles__btns          = document.querySelectorAll(".about-featured-articles__a");

		setTimeout(() => {
			about_featured_articles__btns.forEach(function (element, idx) {
				element.style.opacity = '1';
				// element.classList.add('vis');
			});
		}, 333);
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(NAME + ' > addHandlers');
		w.evt.add(w.el.id('video-section'), 'click', onNav);
		w.evt.add(bdy, window.app.services.modal.evts.MODEL_CLOSE, onModal, true);
	}

	const thumbs_cont     = w.el.id('thumbnails');
	const thumbs          = thumbs_cont.querySelectorAll(".about-hero-video__thumbnail");
	const thumbnail_large = w.el.id('thumbnail_large');

	function onNav(evt) {
		// console.log(modal);
		let target = evt.target;

		if (target && target.nodeName === "BUTTON") {
			//console.log(NAME,'sNav > onMap >','Target element clicked with custom data of =',customData);
			let classes = target.className.split(" ");
			if (classes) {
				// Search for class and react on match
				for (let x = 0; x < classes.length; x++) {
					switch (classes[x]) {
						case "about-hero-video__thumbnail":
							let vid_id = target.getAttribute('data-video');

							if (target !== thumbnail_large) {
								// Reset all thumbnails
								thumbs.forEach(function (element, idx) {
									element.setAttribute('data-disabled', '0');
								});

								// Set disabled thumbnail
								target.setAttribute('data-disabled', '1');
							}

							thumbnail_large.getElementsByTagName('IMG')[0].src = target.getElementsByTagName("IMG")[0].src;

							w.evt.fire(bdy, window.app.services.modal.evts.MODEL_OPEN, {
								modal: window.app.services.modal.evts.MODEL_TYPE.ABOUT,
								video: vid_id
							});

							plyr = new Player('video_player');
							plyr.on('play', function () {
								// w.log.log(NAME + ' > Vimeo > playing…');
							});

							/*plyr.setAutopause(false).then(function (fullscreen) {
								// Autopause is disabled
							}).catch(function (error) {
								switch (error.name) {
									case 'UnsupportedError':
										// Autopause isn't supported by the current player or browser
										w.log.log(NAME + ' > Vimeo > error[0] > ' + error.name);
										break;

									default:
										// Some other error occurred
										w.log.log(NAME + ' > Vimeo > error[1] > ' + error.name);
										break;
								}
							});

							plyr.requestFullscreen().then(function() {
								// the player entered fullscreen
								w.log.log(NAME + ' > Vimeo > requestFullscreen > the player entered fullscreen');
							}).catch(function(error) {
								// an error occurred
								w.log.log(NAME + ' > Vimeo > requestFullscreen > ' + error.name);
							});*/

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
					w.log.log(NAME + ' > onModal > Vimeo > The player is destroyed');
				});
				break;
			default:
				console.warn(NAME + ' > onModal > No matching case for =', evt);
		}
	}
	init();
}
