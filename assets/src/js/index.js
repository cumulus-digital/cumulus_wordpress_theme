import throttle from 'lodash-es/throttle';

(function($, window, undefined) { // eslint-disable-line no-unused-vars

	var $html = $('html'),
		$body = $('body'),
		$main = $('main'),
		$masthead = $('.masthead'),
		$scrollArrow = $('.scroll-down-arrow'),
		$heroVideo = $('.hero .video-container'),
		detectionArea,
		windowHeight,
		mastheadHeight,
		mainPos,
		heroVideoHeight,
		heroVideoBottom;

	function handleWindowUpdates(func, windowEvents = 'resize scroll load', interval = 100, immediate = true) {
		$(document).ready(func);
		$(window).on(
			windowEvents,
			throttle(
				func,
				interval,
				{leading: true, trailing: true}
			)
		);
		if (immediate) {
			func();
		}
	}

	function showScrollArrow() {
		if (
			$heroVideo.length &&
			heroVideoBottom > windowHeight * 0.8 &&
			$(window).scrollTop() < 100
		) {
			var pos = '0vh';
			if (heroVideoBottom < windowHeight) {
				pos = windowHeight - heroVideoBottom;
			}
			$scrollArrow.css({ bottom: pos }).show();
		} else {
			$scrollArrow.hide();
		}
	}

	// Update height calculations on resize
	function updateHeights() {
		windowHeight = $(window).height();
		mastheadHeight = $masthead.outerHeight();
		mainPos = Math.ceil($main.position().top);
		detectionArea = {
			top: $html.position().top + mastheadHeight,
			bottom: $(window).height()
		};
		if ($heroVideo.length) {
			heroVideoHeight = $heroVideo.outerHeight();
			heroVideoBottom = $heroVideo.position().top + heroVideoHeight;
		}
	}
	handleWindowUpdates(updateHeights, 'resize load');

	function updateOnScroll() {
		var scrollPos = $html.scrollTop();

		// Toggle masthead class when main reaches it
		if (
			scrollPos > 0 &&
			scrollPos + detectionArea.top >= mainPos
		) {
			$masthead.addClass('switch');
		} else {
			$masthead.removeClass('switch');
		}

		showScrollArrow();
	}
	handleWindowUpdates(updateOnScroll, 'scroll resize load');

	function toggleMainMenu() {
		$masthead.toggleClass('menu-active');
		$body.toggleClass('menu-active');
	}
	$('.hamburger-container, .masthead nav.menu a[href*="#"]').on(
		'click',
		toggleMainMenu
	);

}(jQuery, window.self));