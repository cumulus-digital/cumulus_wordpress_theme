import jQuery from 'jquery';
import {throttle} from 'lodash-es';

(function($, window, undefined) {
	
	var supportPageOffset = window.pageXOffset !== undefined;
	var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
	var isHome = ($('body').hasClass('home')) ? true : false;
	var hasFeaturedImage = ($('body').hasClass('post_header_image')) ? true : false;

	function toggleMainMenu() {
		$('.masthead').toggleClass('menu-active');
		$('body').toggleClass('menu-active');
	}

	// Open and close the menu when the hamburger is clicked
	$('.hamburger-container').click(toggleMainMenu);

	// Close the menu if clicked on a link
	$('.masthead nav.menu a[href*="#"]').click(toggleMainMenu);

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
		
		if (hasFeaturedImage) {
			detectPoint = mainPos + mastheadHeight;
		}
		if (isHome) {
			detectPoint = mainPos - (mastheadHeight*1.5);
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