//  Utility
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = {

	/**
	 * _id --- Grab element by ID.
	 * @param id
	 * @returns {HTMLElement}
	 */
	id: function (id) {
		return document.getElementById(id);
	},

	/**
	 * createElementPro --- creates a element of your choosing, sets provided attributes, and returns the element
	 * @param element
	 * @param attrs
	 * @returns {*}
	 */
	createElementPro: function (element, attrs) {
		const el = document.createElement(element);
		for (let i in attrs) {
			const aria_match = i.search(/aria/i);
			if (aria_match > -1){
				// console.log(NAME + ' > createElementPro > found aria substring at:',aria_match);
				el.setAttribute(i.replace(/_/g, '-') , attrs[i]);
			} else {
				el.setAttribute(i, attrs[i]);
			}

		}
		return el;
	}

};
