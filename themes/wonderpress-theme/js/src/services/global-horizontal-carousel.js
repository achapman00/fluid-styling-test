//  Service
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
import KeenSlider from "keen-slider";

module.exports = function () {

	const NAME = 'GLOBAL-HORIZONTAL-CAROUSEL';

	w.log.log(NAME + ' > service was loaded.');

	// const bdy                   = document.body;
	// const main                  = document.getElementsByTagName('main')[0];
	// const footer                = w.el.id('footer');
	const slider_component      = document.getElementsByClassName('global-horizontal-carousel')[0];
	const slider_left           = w.el.id("slider-left_btn");
	const slider_right          = w.el.id("slider-right_btn");
	let slider_instance;

	/**
	 * init --- Initialize the service
	 */
	function init() {
		// w.log.log(NAME + ' > init');
		addHandlers();

		if (!slider_component) return;
		const slider = new KeenSlider('#slider', {
			slidesPerView: window.app.noOfSlides,
			mode: "free-snap",
			spacing: 32,
			loop: true,
			created: onSliderCreated,
			slideChanged: onSliderChanged,
			dragEnd: onSliderDragEnd,
			breakpoints: {
				'(min-width: 0px) and (max-width: 959px)': {
					slidesPerView: 1
				}
			}
		});
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(NAME + ' > addHandlers');
	}

	/**
	 * Listen for keyboard input to advance slider
	 * @param evt
	 */
	function onKeyboard(evt) {
		// w.log.log("onKeyboard");
		switch (evt.keyCode) {
			case 37:
				if (slider_instance) slider_instance.prev();
				updateSlider();
				break;
			case 39:
				if (slider_instance) slider_instance.next();
				if (slider_left.hasAttribute('disabled')){
					slider_left.removeAttribute('disabled');
					slider_left.classList.add('paused');
					slider_right.classList.add('paused');
					slider_right.getElementsByTagName('span')[0].classList.add('paused');
				}
				updateSlider();
				break;
		}
	}

	/**
	 * onSliderCreated --- When the Keen slider is initialized, this method is method is called.
	 * @param slider_instance
	 */
	function onSliderCreated(instance) {
		slider_instance = instance;
		// w.log.log(NAME + ' > onSliderCreated');
		w.evt.add(slider_component, 'click', onSliderNav);
		w.evt.add(document, 'keydown', onKeyboard);
		const dots_wrapper = document.getElementsByClassName("global-horizontal-carousel__pagination-dots")[0];
		const slides       = document.querySelectorAll(".keen-slider__slide");

		slides.forEach(function (t, idx) {
			let dot = w.el.createElementPro('button', {
				id: 'dot_'+ (idx + 1) +'_tab',
				class: 'global-horizontal-carousel__pagination-dot',
				role: 'tab',
				aria_label:'Navigate to slide '+ (idx + 1),
				aria_controls:'global-horizontal-carousel_'+ (idx + 1) +'_slide',
				aria_selected:false
			});

			dots_wrapper.appendChild(dot)
			dot.addEventListener("click", function () {
				slider_instance.moveToSlide(idx)
				slider_instance.moveToSlide(idx)
			})
		});
		updateSlider();
	}

	/**
	 * onSliderChanged --- When the Keen slider is initialized, it calls this method.
	 * @param instance
	 */
	function onSliderChanged(instance) {
		// console.log(NAME + '  > onSliderChanged > ', instance);
		updateSlider();
	}

	/**
	 * onSliderDragEnd --- When the Keen slider dragging ends, calls this method.
	 * @param instance
	 */
	function onSliderDragEnd(instance) {
		// console.log(NAME + '  > onSliderDragEnd > ', instance);
		if (slider_left.hasAttribute('disabled')){
			slider_left.removeAttribute('disabled');
			slider_left.classList.add('paused');
			slider_right.classList.add('paused');
			slider_right.getElementsByTagName('span')[0].classList.add('paused');
		}
		updateSlider();
	}

	/**
	 * onSliderNav --- When a user navigates the slider with left and right buttons, this is called.
	 * @param evt
	 */
	function onSliderNav(evt) {
		let target = evt.target;
		// w.log.log("onSliderNav", evt.target, target.nodeName);
		if (target && target.nodeName === "BUTTON") {
			//console.log(NAME,'sNav > onMap >','Target element clicked with custom data of =',customData);
			let classes = target.className.split(" ");
			if (classes) {
				// Search for class and react on match
				for (let x = 0; x < classes.length; x++) {
					switch (classes[x]) {
						case "global-direction-controls__button":
							const aria_label = target.getAttribute('aria-label');
							// w.log.log("global-direction-controls__button", console__style001,target.getAttribute('data-direction'));
							if (aria_label === 'Right') {
								if (slider_instance) slider_instance.next();
								if (slider_left.hasAttribute('disabled')){
									slider_left.removeAttribute('disabled');
									slider_left.classList.add('paused');
									slider_right.classList.add('paused');
									slider_right.getElementsByTagName('span')[0].classList.add('paused');
								}
								updateSlider();
							} else if (aria_label === 'Left') {
								if (slider_instance) slider_instance.prev();
								updateSlider();
							}
						case 'global-horizontal-carousel__pagination-dot':
							if (slider_left.hasAttribute('disabled')){
								slider_left.removeAttribute('disabled');
								slider_left.classList.add('paused');
								slider_right.classList.add('paused');
								slider_right.getElementsByTagName('span')[0].classList.add('paused');
							}
							updateSlider();
							break;
					}
				}
			}
		}
	}

	/**
	 * updateSlider --- Update all slider styles to reflect changes
	 */
	function updateSlider() {
		if (!slider_instance) return;
		const slide = slider_instance.details().relativeSlide;
		const dots  = document.querySelectorAll(".global-horizontal-carousel__pagination-dot");

		dots.forEach(function (dot, idx) {
			if (idx === slide){
				dot.classList.add("global-horizontal-carousel__pagination-dot--active");
				dot.setAttribute('aria-selected','true');
				dot.setAttribute('tabindex','0');
			} else {
				dot.classList.remove("global-horizontal-carousel__pagination-dot--active");
				dot.setAttribute('aria-selected','false');
				dot.setAttribute('tabindex','-1');
			}
		});
	}

	init();

}

