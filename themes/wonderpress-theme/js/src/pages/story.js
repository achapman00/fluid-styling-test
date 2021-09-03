//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
import KeenSlider from "keen-slider";

module.exports = function () {

	const NAME = 'STORY (SINGLE)';

	w.log.log(NAME + ' > page was loaded.');

	const bdy              = document.body;
	const slider_component = w.el.id('slider');
	const socials          = w.el.id('socials');
	const the_content      = w.el.id('the-content');
	let slider, slider_instance;

	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(NAME + ' > init');

		addHandlers();
		addImgToAllButtons();

		if (!slider_component) return;
		slider_component.setAttribute('data-slider', 'about-1');
		let el = createElementPro('div', {
			class: 'global-horizontal-carousel__pagination-dots'
		});
		insertElementAfter(slider_component, el);

		const li_s = slider_component.getElementsByTagName('LI');

		for (let i = 0; i < li_s.length; i++) li_s[i].classList.add("keen-slider__slide");

		slider = new KeenSlider('#slider', {
			slidesPerView: 1,
			mode: "free-snap",
			spacing: 32,
			loop: true,
			created: onSliderCreated,
			slideChanged: onSliderChanged,
			dragEnd: onSliderDragEnd
		});
	}

	/**
	 * addHandlers --- Add event handlers for view
	 */
	function addHandlers() {
		// w.log.log(NAME + ' > addHandlers');
		w.evt.add(document, 'scroll', onScroll);
		w.evt.add(socials, 'click', onSocial);
	}

	function onScroll(evt) {
		let distanceFromTop = the_content.getBoundingClientRect().top;
		// console.log(distanceFromTop);
		if (distanceFromTop <= 333) socials.classList.add('story-article__social-btns--vis');
		else socials.classList.remove('story-article__social-btns--vis');
	}

	/**
	 * onSliderCreated --- When the Keen slider is initialized, this method is method is called.
	 * @param slider_instance
	 */
	function onSliderCreated(instance) {
		slider_instance = instance;
		// console.log(NAME + '  > onSliderCreated > ', slider_instance);
		w.evt.add(document, 'keydown', onKeyboard);
		const dots_wrapper = document.getElementsByClassName("global-horizontal-carousel__pagination-dots")[0];
		const slides       = document.querySelectorAll(".keen-slider__slide");

		slides.forEach(function (t, idx) {
			let dot = document.createElement("button")
			dot.classList.add("global-horizontal-carousel__pagination-dot")
			dots_wrapper.appendChild(dot)
			dot.addEventListener("click", function () {
				slider_instance.moveToSlide(idx)
				slider_instance.moveToSlide(idx)
			})
		});
		updateSlider();
	}

	/**
	 * updateSlider --- Update all slider styles to reflect changes
	 */
	function updateSlider() {
		if (!slider_instance) return;
		const slide = slider_instance.details().relativeSlide;
		const dots  = document.querySelectorAll(".global-horizontal-carousel__pagination-dot");

		if (!dots) return;
		dots.forEach(function (dot, idx) {
			idx === slide
				? dot.classList.add("global-horizontal-carousel__pagination-dot--active")
				: dot.classList.remove("global-horizontal-carousel__pagination-dot--active")
		});
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
				updateSlider();
				break;
		}
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
	 * onSliderDragEnd --- When the Keen slider dargging ends, calls this method.
	 * @param instance
	 */
	function onSliderDragEnd(instance) {
		// console.log(NAME + '  > onSliderDragEnd > ', instance);
		updateSlider();
	}

	function onSocial(evt) {
		// console.log(evt);
		let target = evt.target;

		if (target && target.nodeName === "A" || target && target.nodeName === "BUTTON") {
			//console.log(NAME,'sNav > onMap >','Target element clicked with custom data of =',customData);
			let classes = target.className.split(" ");
			if (classes) {
				// Search for class and react on match
				for (let x = 0; x < classes.length; x++) {
					switch (classes[x]) {
						case "story-article__social-btns__button":
							const msg = target.getAttribute('data-share-msg');
							const shareType      = target.getAttribute('data-share-button');
							const windowFeatures = "width=600,height=300,menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
							let url              = window.single_vars.permalink;

							if (shareType === 'facebook') {
								FB.ui({
									method: 'share',
									href: url,
								}, (response) => console.info("Facebook " + response));
							} else if (shareType === 'twitter') {
								window.open('https://twitter.com/intent/tweet?url=' + url, 'twitter_wdw', windowFeatures);

							} else if (shareType === 'linkedin') {
								window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + url, 'linkedin_wdw', windowFeatures);
							}
							break;
					}
				}
			}
		}
	}

	const story_body  = w.el.id('story');
	const button_ctas = story_body.querySelectorAll(".wp-block-button__link");

	// creates a div, sets provided attributes, returns the div for doing whatever you want with it
	function createElementPro(element, attrs) {
		const el = document.createElement(element);
		for (let i in attrs) {
			el.setAttribute(i, attrs[i]);
		}
		return el;
	}

	function addImgToAllButtons() {
		button_ctas.forEach(function (element, idx) {
			element.appendChild(createElementPro('span', {
				style: 'background-image: url("' + window.app.uri_assets_imgs + 'global/godaddy-venture-cta_arrow_42x15-everyday-green-and-ffffff-on-trans.svg")',
				alt: '',
				class: 'story-article__link-img'
			}));
		});
	}

	/**
	 * insertElementAfter --- Insert an element after another
	 * @param referenceNode
	 * @param newNode
	 */
	function insertElementAfter(referenceNode, newNode) {
		referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
	}

	init();

	// Trigger onScroll on load in case the user
	// loaded the page while already scrolled
	onScroll();
}
