import throttle from 'lodash-es/throttle';

(function($, window, undefined) { // eslint-disable-line no-unused-vars

	var $html = $('html'),
		$body = $('body'),
		$main = $('main'),
		$masthead = $('.masthead'),
		$scrollArrow = $('.scroll-down-arrow'),
		$heroVideoContainer = $('.hero .video-container'),
		$heroVideo = $('.hero video'),
		detectionArea,
		windowHeight,
		mastheadHeight,
		mainPos,
		heroVideoHeight,
		heroVideoBottom;

	function handleWindowUpdates(func, windowEvents, interval, immediate) {
		if ( typeof interval == 'undefined') {
			interval = 100;
		}
		if ( typeof immediate == 'undefined') {
			immediate = true
		}
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
			heroVideoBottom > windowHeight * 0.7 &&
			$(window).scrollTop() < 100
		) {
			/*
			var pos = '2vh';
			if (heroVideoBottom < windowHeight) {
				pos = windowHeight - heroVideoBottom - 15;
			}
			*/
			//$scrollArrow.css({ bottom: pos }).fadeIn(200);
			$scrollArrow.fadeIn(200);
		} else {
			$scrollArrow.fadeOut(200);
		}
	}

	// Update height calculations on resize
	function updateHeights() {
		windowHeight = $(window).height();
		mastheadHeight = $masthead.outerHeight();
		mainPos = Math.ceil($main.position().top);
		detectionArea = {
			top: Math.abs($html.position().top) + mastheadHeight,
			bottom: $(window).height()
		};
		if ($heroVideo.length) {
			heroVideoHeight = $heroVideoContainer.outerHeight();
			heroVideoBottom = $heroVideoContainer.position().top + heroVideoHeight;
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
			//$heroVideo.trigger('pause');
		} else {
			//$heroVideo.trigger('play');
			$masthead.removeClass('switch');
		}

		showScrollArrow();
	}
	handleWindowUpdates(updateOnScroll, 'scroll resize load');

	$(window).blur(function() {
		$heroVideo.trigger('pause');
	});
	$(window).focus(function() {
		$heroVideo.trigger('play');
	});

	function toggleMainMenu() {
		if ($body.hasClass('menu-active')) {
			$heroVideo.trigger('play');
			$body.removeClass('menu-active');
			$masthead.removeClass('menu-active');
		} else {
			$heroVideo.trigger('pause');
			$body.addClass('menu-active');
			$masthead.addClass('menu-active');
			$masthead.find('.menu').scrollTop(0);
		}
	}
	$('.hamburger-container, .masthead nav.menu a[href*="#"]').on(
		'click',
		toggleMainMenu
	);

	$scrollArrow.on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $main.offset().top - mastheadHeight
		}, 500);
	});

}(jQuery, window.self));