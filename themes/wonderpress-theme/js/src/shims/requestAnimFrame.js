//  Utility
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'REQUEST-ANIM-FRAME';

	w.log.log(NAME + ' > shim was loaded.');

	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(NAME + ' > init');
		/**
		 * requestAnimFrame --- Add requestAnimFrame.
		 */
		window.requestAnimFrame = (function () {
			return  window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (/* function */ callback, /* DOMElement */ element) {
				window.setTimeout(callback, 1000 / 60);
			};
		})();
	}

	init();
}
