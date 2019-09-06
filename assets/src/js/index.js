import jQuery from 'jquery';
import {throttle} from 'lodash-es';

(function($, window, undefined) {
	
	var supportPageOffset = window.pageXOffset !== undefined;
	var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
	var isHome = ($('body').hasClass('home')) ? true : false;
	var hasFeaturedImage = ($('body').hasClass('post_header_image')) ? true : false;

	var $masthead = $('.masthead'),
		$body = $('body'),
		killScrollTimer;
	function toggleMainMenu() {
		$masthead.toggleClass('menu-active');
		$body.toggleClass('menu-active');
		if ($masthead.hasClass('menu-active')) {
			killScrollTimer = setTimeout(function() {
				var scrollPos = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop
				$body.data('scrollPos', scrollPos);
				$body.css('position', 'fixed');
			}, 100);
		} else {
			clearTimeout(killScrollTimer);
			killScrollTimer = null;
			document.body.scrollTo(0, $body.data('scrollPos'));
			$body.css('position', 'relative');
		}
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
	window.addEventListener('scroll', throttle(scrollPosition, 100, {leading: true, trailing: true}));
	$(window).on('load', function() {
		scrollPosition()
	});

	// Detect if video is near the bottom of the browser window, if so add scroll arrow
	if (isHome) {
		$(function() {
			var $video = $('.hero');
			if ($video.position().top + $video.outerHeight() > $(window).height()-250) {
				$video.append('<span class="scroll-down-arrow"></span>');
			}
		});
	}

}(jQuery, window.self));