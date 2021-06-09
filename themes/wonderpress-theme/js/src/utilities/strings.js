//  Utility
// ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
module.exports = {

	/**
	 * toTitleCase --- Supply the string you'd like to Title Case
	 * @author Vincent V. Toscano
	 * Created by Vincent on May 6, 2021
	 *
	 * @param str
	 * @returns {string}
	 * @example w.str.toTitleCase(v.obj.name)
	 */
	toTitleCase: function (str) {
		return str.toLowerCase().split(' ').map(function(word) {
			return word.replace(word[0], word[0].toUpperCase());
		}).join(' ');
	}
};
