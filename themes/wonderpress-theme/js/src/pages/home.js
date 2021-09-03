// A function to determine the color for a given
// county on the map
const choroplethColor = () => {
    /*
        Fn to generate choropleth color expressions for County level data.
        Each color expression should return an array of the form:

        ['match',
        ['get', 'cfips'],       <- get the cfips property of geo feature
        '01101', '#ffeeaa',     <- color pairing for each possible cfips
        '01102', '#aaFe13',
        ...,
        '#<default color>']     <- default color in case of no match

    */

    let expression = ["match", ["get", "cfips"]];

    window.app.global.vars.counties.forEach(row => {

    	const vd_avg = parseFloat(row.vd_avg);

    	let color = '#ffffff';
    	if(vd_avg <= 3.0) {
    		color = '#004249';

    	} else if(vd_avg > 3.0 && vd_avg <= 5.0) {
    		color = '#09757A';

    	} else if(vd_avg > 5.0 && vd_avg <= 8.0) {
    		color = '#00a4a6';
    	} else if(vd_avg > 8.0) {
    		color = '#1bdbdb';
    	}

		const county = (row.county.length < 5) ? '0' + row.county : row.county;
		expression.push(county, color);
    });

    let noDataColor = "#ffffff";
    expression.push(noDataColor);

    return expression;
}

//  View
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = function () {

	const NAME = 'HOME';

	w.log.log(`${NAME} > page was loaded.`);

	// Stash the counties
	window.app.global.vars.counties = window.home_vars.vf_get_all_locations.counties;

	let bdy, main, footer, mapbox_map, steps, mapEl, SwipeSensor;

	let heroMapEngagement = {
		autoplay: false,
		interval: null
	};

	/**
	 * VentureEvts --- Enums for Venture custom events
	 * @type {{ON_MAP_BLADE_NAVIGATE: string}}
	 */
	const VentureEvts = {
		ON_MAP_BLADE_NAVIGATE: "on_map_blade_navigate"
	};

	/**
	 * init --- Initialize the view
	 */
	function init() {
		// w.log.log(`${NAME} > init`);
		bdy         = document.body;
		main        = document.getElementsByTagName('main')[0];
		footer      = w.el.id('footer');
		mapbox_map  = w.el.id('map');
		steps       = JSON.parse(home_vars.map_steps);
		SwipeSensor = new SwipeSensor001();
		SwipeSensor.powerUp(mapbox_map);
		addHandlers();
		onLoop();

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
		// w.log.log(NAME + ' >  addHandlers');
		// console.log(bdy);
		// console.log(mapbox_map);
		w.evt.add(mapbox_map, VentureEvts.ON_MAP_BLADE_NAVIGATE, onBladeNavigate);
		w.evt.add(bdy, SwipeSensor.EVT_ON_SWIPE_LEFT, onSwipe);
		w.evt.add(bdy, SwipeSensor.EVT_ON_SWIPE_RIGHT, onSwipe);
	}

	function onSwipe(evt) {
		w.log.log(`${NAME} > onSwipe`);

		clearInterval(heroMapEngagement.interval); // cancels autoplay
		heroMapEngagement.autoplay = false;
		// w.log.log("global-direction-controls__button", console__style001,target.getAttribute('data-direction'));

		switch (evt.type) {
			case SwipeSensor.EVT_ON_SWIPE_LEFT:
				w.log.log(`${NAME} > onSwipe > ${evt.type}`);
				w.evt.fire(mapbox_map, VentureEvts.ON_MAP_BLADE_NAVIGATE, {
					direction: "Down",
					autoplay: false
				});
				break;
			case SwipeSensor.EVT_ON_SWIPE_RIGHT:
				w.log.log(`${NAME} > onSwipe > ${evt.type}`);
				w.evt.fire(mapbox_map, VentureEvts.ON_MAP_BLADE_NAVIGATE, {
					direction: "Up",
					autoplay: false
				});
				break;
		}
	}

	/**
	 * onWindow --- Ensure map is offset when on mobile, then pump through showStep method
	 * @param evt
	 * TODO Need a debounce to ensure this doesn't get slammed
	 */
	function onWindow(evt) {
		showStep(1, true);
	}

	const blade_up        = w.el.id("home-hero-map-layer-up_btn");
	const blade_down      = w.el.id("home-hero-map-layer-down_btn");
	const blade_left      = w.el.id("home-hero-map-layer-left_btn");
	const blade_right     = w.el.id("home-hero-map-layer-right_btn");
	let currentBlade      = 0;
	let currentBladeTotal = document.querySelectorAll('#blades .home-hero-blades__blade').length - 1;
	// const comStyle        = blade_right.currentStyle ? blade_right.currentStyle.display : getComputedStyle(blade_right, '::before').display;

	/**
	 * onBladeNavigate  --- Selecting up or down triggers this event
	 * @param evt
	 */
	function onBladeNavigate(evt) {
		// console.log(NAME + ' > onBladeNavigate >', evt.data.autoplay, heroMapEngagement.autoplay);
		if (evt.data.autoplay === true && heroMapEngagement.autoplay === true) {
			if (currentBlade == currentBladeTotal) {
				heroMapEngagement.autoplay = false;
				clearInterval(heroMapEngagement.interval);
				return;
			}
			if (currentBlade === 0) {
				//	Reveal up arrow button
				blade_up.removeAttribute('disabled');
				blade_left.removeAttribute('disabled');
				blade_down.classList.toggle('paused');
				blade_right.classList.toggle('paused');
				blade_down.getElementsByTagName('span')[0].classList.toggle('paused');
				blade_right.getElementsByTagName('span')[0].classList.toggle('paused');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[0], 0, '-95%');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[1], 0, 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[0], '-120%', 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[1], 0, 0);
				currentBlade = 1;
			} else if (currentBlade > 0 && currentBlade <= currentBladeTotal) {
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[currentBlade], 0, '-95%');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[currentBlade + 1], 0, 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[currentBlade], '-120%', 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[currentBlade + 1], 0, 0);
				currentBlade++;
				if (currentBlade === currentBladeTotal) {
					blade_down.setAttribute('disabled', 'true');
					blade_right.setAttribute('disabled', 'true');
					heroMapEngagement.autoplay = false;
					clearInterval(heroMapEngagement.interval);
				}
			}
		} else if (evt.data.direction === 'Up') {
			// w.log.log(NAME + ' > onBladeNavigate > global-direction-controls__button > up > before: ' + currentBlade);
			if (currentBlade === 1) {
				//	Reveal down arrow button
				blade_up.setAttribute('disabled', 'true');
				blade_left.setAttribute('disabled', 'true');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[currentBlade], 0, '95%');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[0], 0, 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[currentBlade], '120%', 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[0], 0, 0);
				blade_down.classList.toggle('paused');
				blade_right.classList.toggle('paused');
				blade_down.getElementsByTagName('span')[0].classList.toggle('paused');
				blade_right.getElementsByTagName('span')[0].classList.toggle('paused');
				currentBlade = 0;
			} else if (currentBlade > 0 && currentBlade <= currentBladeTotal) {
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[currentBlade], 0, '95%');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[currentBlade - 1], 0, 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[currentBlade], '120%', 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[currentBlade - 1], 0, 0);
				currentBlade--;
				blade_down.removeAttribute('disabled');
				blade_right.removeAttribute('disabled');
			} else {
				// this would be zero
				// w.log.log(NAME + ' > onBladeNavigate > global-direction-controls__button > up > end');
			}
			// w.log.log(NAME + ' > onBladeNavigate > global-direction-controls__button > up > after: ' + currentBlade);
		} else {
			// w.log.log(NAME + ' > onBladeNavigate > global-direction-controls__button > down > before: ' + currentBlade);
			if (currentBlade === 0) {
				//	Reveal up arrow button
				blade_up.removeAttribute('disabled');
				blade_left.removeAttribute('disabled');
				blade_down.classList.toggle('paused');
				blade_right.classList.toggle('paused');
				blade_down.getElementsByTagName('span')[0].classList.toggle('paused');
				blade_right.getElementsByTagName('span')[0].classList.toggle('paused');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[0], 0, '-95%');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[1], 0, 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[0], '-120%', 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[1], 0, 0);
				currentBlade = 1;
			} else if (currentBlade > 0 && currentBlade <= currentBladeTotal) {
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[currentBlade], 0, '-95%');
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade")[currentBlade + 1], 0, 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[currentBlade], '-120%', 0);
				setTranslate3D(document.getElementsByClassName("home-hero-blades__blade mobile")[currentBlade + 1], 0, 0);
				currentBlade++;
				if (currentBlade === currentBladeTotal) {
					blade_down.setAttribute('disabled', 'true');
					blade_right.setAttribute('disabled', 'true');
				}
			} else {
				// this would be 3
				// w.log.log(NAME + ' > global-direction-controls__button > down > end');
			}
			map.resize();
			// w.log.log(NAME + ' > global-direction-controls__button > down > after: ' + currentBlade);
		}
		showStep(currentBlade);
	}

	/**
	 * setTranslate3D --- translate3d utility
	 * @param el - Element to apply transform on.
	 * @param xPos X-axis
	 * @param yPos Y- axis
	 * @param zPos Z-axis
	 */
	function setTranslate3D(el, xPos, yPos, zPos = 0) {
		let trans = `translate3d(${xPos}, ${yPos}, ${zPos})`;
		let styles;
		if (yPos === 0) {
			styles = `
			    opacity:1;
			    transform:` + trans;
		} else {
			styles = `
			    opacity:.5;
			    transform:` + trans;
		}
		el.style = styles;
	}

	/**
	 * map ---- Initialize Mapbox map
	 */
	const map = new mapboxgl.Map({
		container: 'map',
		style: 'mapbox://styles/kenmanz0/ckml2836d595y17ny3qp5t4rv?optimize=true',
		center: [39.050, -90],
		zoom: 6,
		interactive: false,
		accessToken: 'pk.eyJ1Ijoia2VubWFuejAiLCJhIjoiY2swaWJmdm5nMGJpcjNubGU5NGlmbmFzeCJ9.ejCc3y6Y-1Kl9e69OOWq0g'

	});

	// Will be called after we have county data to paint the map with
	var configureMapLayers = () => {

		var layers = map.getStyle().layers;
		// Find the index of the first symbol layer in the map style
		var firstSymbolId;
		for (var i = 0; i < layers.length; i++) {
		if (layers[i].type === 'symbol') {
		firstSymbolId = layers[i].id;
		break;
		}
		}

		map.getCanvas().style.cursor = "default";

		map.addLayer({
			id:"countyLayer",
			type:"fill",
			source:{
				type:"vector",
				url:"mapbox://kenmanz0.5i75rlai"
			},
			"source-layer":"counties_500k-31pnqp",
			layout:{
				visibility:"visible"
			},
			paint:{
				"fill-color":"rgb(255, 0, 0)",
				"fill-opacity":1
			}
		},firstSymbolId);

	    map.setPaintProperty(
	      "countyLayer",
	      "fill-color",
	      choroplethColor()
	    );

		map.resize();
	}
	let currentStep = 0; // Store current map position

	/**
	 * showStep --- Update map position
	 * @param i The step/position to travel to
	 * @param bypassLocation    When we resize the browser, ensure the center point corresponds to the browser width
	 */
	function showStep(i, bypassLocation = false) {
		const step        = steps[i];
		const desktopMenu = document.getElementsByClassName('global-nav-desktop')[0];
		const comStyle    = desktopMenu.currentStyle ? desktopMenu.currentStyle.display : getComputedStyle(desktopMenu, null).display;
		const padding     = {
			top: comStyle === "none" ? 399 : 0,
			left: comStyle === "none" ? 0 : 611
		};
		if (!bypassLocation) {
			map.flyTo({
				bearing: step.camera.bearing,
				center: step.geometry.coordinates,
				duration: step.animation.duration,
				easing: easingFunctions[step.animation.easing],
				pitch: step.camera.pitch,
				zoom: step.camera.zoom,
				padding: padding,
				essential: true
			});
			currentStep = i;
		} else {
			map.easeTo({
				padding: padding
			});
		}
		map.resize();
	}


	/**
	 * Upon Mapbox map load, do something
	 */
	map.on('load', function () {
		// w.log.log('Map Load > Will transition in blades + controls when loaded');
		configureMapLayers();
		showStep(0);
		document.getElementsByClassName("home-hero-blades__map-layer")[0].classList.add("home-hero-blades__map-layer--vis");
		document.getElementsByClassName("home-hero-blades__map-layer")[1].classList.add("home-hero-blades__map-layer--vis");
		w.evt.add(window, 'resize', onWindow);
		w.evt.add(w.el.id('hero'), 'click', onMap);
		map.resize();
		mapEl = document.getElementsByClassName('mapboxgl-canvas')[0];
		const links = Array.from(document.querySelectorAll('a[target]'));
		for (let link of links) {
			const target = link.getAttribute('target');
			if (target && (!link.getAttribute('rel') || link.getAttribute('rel').indexOf('noopener') === -1)) {
				// console.error(`Unsafe link ${link} is vulnerable to reverse tabnabbing.`);
				link.setAttribute('rel', "noopener");
			}
		}

		if (heroMapEngagement.interaction === true) return;
		setTimeout(() => {
			heroMapEngagement.autoplay = true;
			heroMapEngagement.interval = setInterval(() => w.evt.fire(mapbox_map, VentureEvts.ON_MAP_BLADE_NAVIGATE, {
				direction: "Down",
				autoplay: true
			}), 10100);
			w.el.id('map-legend').classList.add('vis');
		}, 288);
	});

	/**
	 * onMap --- Use event delegation to detect interactions on layers above hero map
	 * @param evt
	 */
	function onMap(evt) {
		let target = evt.target;
		// w.log.log("onMap >", console__style001,target, target.nodeName);
		if (target && target.nodeName === "BUTTON") {
			//console.log(NAME,'sNav > onMap >','Target element clicked with custom data of =',customData);
			let classes = target.className.split(" ");
			if (classes) {
				// Search for class and react on match
				for (let x = 0; x < classes.length; x++) {
					switch (classes[x]) {
						case "global-direction-controls__button":
							clearInterval(heroMapEngagement.interval); // cancels autoplay
							heroMapEngagement.autoplay = false;
							const aria_label           = target.getAttribute('aria-label');
							// w.log.log("global-direction-controls__button", console__style001,target.getAttribute('data-direction'));
							if (aria_label === 'Up' || aria_label === 'Left') w.evt.fire(mapbox_map, VentureEvts.ON_MAP_BLADE_NAVIGATE, {
								direction: "Up",
								autoplay: false
							});
							else w.evt.fire(mapbox_map, VentureEvts.ON_MAP_BLADE_NAVIGATE, {
								direction: "Down",
								autoplay: false
							});
							break;
					}
				}
			}
		}
	}

	/**
	 * onLoop --- Fires event on each frame.
	 */
	function onLoop() {
		requestAnimFrame(onLoop);
		// w.evt.fire(bdy, 'reqAnim');
	}

	init();
}

/**
 *  easingFunctions --- Easing methods used for Mapbox GL JS
 * • Each function takes a parameter t that represents the progress of the animation.
 * • T is in a range of 0 to 1 where 0 is the initial state, and 1 is the completed state.
 * @type {{easeInOutCirc: (function(*): number), easeInCubic: (function(*)), easeOutQuint: (function(*)), easeOutBounce: (function(*): *)}}
 */
const easingFunctions = {
	// start slow and gradually increase speed
	easeInCubic: function (t) {
		return t * t * t;
	},

	// start fast with a long, slow wind-down
	easeOutQuint: function (t) {
		return 1 - Math.pow(1 - t, 5);
	},

	// slow start and finish with fast middle
	easeInOutCirc: function (t) {
		return t < 0.5 ? (1 - Math.sqrt(1 - Math.pow(2 * t, 2))) / 2 : (Math.sqrt(1 - Math.pow(-2 * t + 2, 2)) + 1) / 2;
	},

	// fast start with a "bounce" at the end
	easeOutBounce: function (t) {
		let n1 = 7.5625;
		let d1 = 2.75;

		if (t < 1 / d1) {
			return n1 * t * t;
		} else if (t < 2 / d1) {
			return n1 * (t -= 1.5 / d1) * t + 0.75;
		} else if (t < 2.5 / d1) {
			return n1 * (t -= 2.25 / d1) * t + 0.9375;
		} else {
			return n1 * (t -= 2.625 / d1) * t + 0.984375;
		}
	}
};
