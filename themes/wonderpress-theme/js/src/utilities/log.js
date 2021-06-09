module.exports = {
	init:function (){
	},
	console__style001: [
		'background: linear-gradient(#0013a8, #000c69)'
		, 'color: white'
		, 'display: block'
		, 'box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255, 255, 255, 0.4) inset'
		, 'text-align: left'
		, 'font-weight: bold'
		, 'padding:3px 5px'
		, 'font: bold normal normal 14px\/normal \"Helvetica Black\", \"Helvetica Neue\", Roboto, Arial, Helvetica, sans-serif'
	].join(';'),

	/**
	 * _log --- Presents a stylized console log
	 * @param message
	 * @param color
	 * @private
	 */
	log:function (message, color = this.console__style001, ...args) {
/*		switch (color) {
			case "success":
				this.console__style001 = this.console__style001.replace("linear-gradient(#0013a8, #000c69)","Green");
				break;
			case "info":
				this.console__style001 = this.console__style001.replace("linear-gradient(#0013a8, #000c69)","DodgerBlue");
				break;
			case "error":
				this.console__style001 = this.console__style001.replace("linear-gradient(#0013a8, #000c69)","Red");
				break;
			case "warning":
				this.console__style001 = this.console__style001.replace("linear-gradient(#0013a8, #000c69)","Orange");
				break;
			default:
				color = color;
		}*/
		console.log("%c" + message, this.console__style001, ...args);
	}
}

