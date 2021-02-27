/**
 * nw7 - jQuery barcode(NW-7) plugin
 * 
 * author: softel
 * version: 0.1(2010-08-26)
 * url: http://www.softel.co.jp/labs/
 */

(function($){

	$.fn.nw7 = function (options) {
		return this.each(function(){
			var o = this
			var s = $(o).html().toLowerCase()

			var opt = $.extend({}, $.fn.nw7.defaults, options)
			if (/^[a-d]{1}$/.test(opt.start) == false) {
				opt.start = $.fn.nw7.defaults.start
			}
			if (/^[a-d]{1}$/.test(opt.stop) == false) {
				opt.stop = opt.start
			}
			if (/^[a-d]+.*/.test(s)) {
				opt.start = ""
			}
			if (/.*[a-d]+$/.test(s)) {
				opt.stop = ""
			}
			if (opt.narrow == "" || opt.wide == "") {
				switch (opt.density) {
					default:
					case "1": opt.narrow = "3px"; opt.wide = "8px"; break;
					case "2": opt.narrow = "2px"; opt.wide = "5px"; break;
					case "3": opt.narrow = "1px"; opt.wide = "2px"; break;
				}
			}

			s = opt.start + s + opt.stop

			var code = "", _ = "", __ = []
			for (var i = 0, l = s.length; i < l; ++i) {
				switch (s[i]) {
					case "0": _ = "0000011"; break;
					case "1": _ = "0000110"; break;
					case "2": _ = "0001001"; break;
					case "3": _ = "1100000"; break;
					case "4": _ = "0010010"; break;
					case "5": _ = "1000010"; break;
					case "6": _ = "0100001"; break;
					case "7": _ = "0100100"; break;
					case "8": _ = "0110000"; break;
					case "9": _ = "1001000"; break;
					case "-": _ = "0001100"; break;
					case "$": _ = "0011000"; break;
					case "/": _ = "1010001"; break;
					case ":": _ = "1000101"; break;
					case "+": _ = "0010101"; break;
					case ".": _ = "1010100"; break;

					case "a": _ = "0011010"; break;
					case "b": _ = "0101001"; break;
					case "c": _ = "0001011"; break;
					case "d": _ = "0001110"; break;

					default: _ = ""; break;
				}
				__.push(_)
			}
			code = __.join("0")

			var html = ""
			for (var i = 0, l = code.length; i < l; ++i) {
				html += '<div style="width:' + ((code[i] == "1") ? opt.wide : opt.narrow) + '; height:' + opt.height + '; background:' + ((i % 2) ? opt.white : opt.black) + '; float:left;"></div>';
			}
			html = '<div class="nw7" style="height:' + opt.height + '">' + html + '</div>'

			$(o).before(html)
		})
	}

	$.fn.nw7.defaults = {
		start: "a",
		stop: "",
		density: "1",
		narrow: "",
		wide: "",
		height:"50px",
		black: "#000000",
		white: "#ffffff"
	}

})(jQuery);
