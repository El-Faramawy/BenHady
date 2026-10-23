(function($) {
	"use strict";
	
	if (document.querySelector('.app-sidebar')) {
		const ps = new PerfectScrollbar('.app-sidebar', {
		  useBothWheelAxes:false,
		  suppressScrollX:false,
		});
	}
	if (document.querySelector('.sidebar-left')) {
		const ps1 = new PerfectScrollbar('.sidebar-left', {
		  useBothWheelAxes:false,
		  suppressScrollX:false,
		});
	}
	
})(jQuery);