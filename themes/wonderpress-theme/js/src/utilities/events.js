module.exports = {
	add: function (el, evt, handler, bubbles) {
		if (el.addEventListener) {
			el.addEventListener(evt, handler, bubbles || false);
		} else {
			el.attachEvent('on' + evt, handler);
		}
	},
	rm: function (c, b, a) {
		if (c.detachEvent) {
			c.detachEvent("on" + b, c[b + a]);
			c[b + a] = null
		} else {
			c.removeEventListener(b, a, false)
		}
	},
	fire: function (el, type, obj) {
		if (document.createEvent) {
			var evt = document.createEvent("HTMLEvents");
			evt.initEvent(type, true, true);
			if (obj) evt.data = obj;
			return !el.dispatchEvent(evt);
		} else {
			var evt = document.createEventObject();
			if (obj) evt.data = obj;
			return el.fireEvent('on' + type, evt);
		}
	},
	addMultiple: function (el, evts, handler, bubbles) {
		evts.forEach(e => el.addEventListener(e, handler, bubbles || false));
	}
};
