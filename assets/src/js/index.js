import jQuery from 'jquery';
import {throttle} from 'lodash-es';

(function($, window, undefined) {
	
	var supportPageOffset = window.pageXOffset !== undefined;
	var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
	var isHome = ($('body').hasClass('home')) ? true : false;
	var hasFeaturedImage = ($('body').hasClass('post_header_image')) ? true : false;

	$('.hamburger').click(
		function() {
			$('.masthead').toggleClass('menu-active');
			$('body').toggleClass('menu-active');
		}
	);

	// Switch masthead when page scrolls to main el
	var monitorEls = {
		main: $('main'),
		masthead: $('.masthead')
	};
	function scrollPosition() {
		var scrollPos = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop,
			mainPos = monitorEls.main.position().top,
			mastheadHeight = monitorEls.masthead.outerHeight(),
			detectPoint = mainPos - mastheadHeight;
		
		if (isHome) {
			detectPoint = mainPos - (mastheadHeight*2);
		}
		if (hasFeaturedImage) {
			detectPoint = mainPos + mastheadHeight;
		}
		
		if ( ! monitorEls.masthead.hasClass('switch') && scrollPos >= detectPoint) {
			monitorEls.masthead.addClass('switch');
		} else if (monitorEls.masthead.hasClass('switch') && scrollPos < detectPoint) {
			monitorEls.masthead.removeClass('switch');
		}
	}
	window.addEventListener('scroll', throttle(scrollPosition, 50, {leading: true, trailing: true}));
	$(window).on('load', function() {
		scrollPosition()
	});

}(jQuery, window.self));