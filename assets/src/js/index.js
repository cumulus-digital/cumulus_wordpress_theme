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
				//$body.css('position', 'fixed');
			}, 100);
		} else {
			clearTimeout(killScrollTimer);
			killScrollTimer = null;
			document.body.scrollTo(0, $body.data('scrollPos'));
			//$body.css('position', 'relative');
		}
	}

	// Open and close the menu when the hamburger is clicked
	$('.hamburger-container').click(toggleMainMenu);

	// Close the menu if clicked on a link
	$('.masthead nav.menu a[href*="#"]').click(toggleMainMenu);

	var monitorEls = {
		main: $('main'),
		masthead: $('.masthead'),
		scrollArrow: $('.scroll-down-arrow')
	};

	// Alter monitored els on scroll change
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

		// Hide scroll arrow
		if (monitorEls.scrollArrow.length && scrollPos >= 100) {
			monitorEls.scrollArrow.hide().data('hidden', true);
		} else if (monitorEls.scrollArrow.data('hidden')) {
			monitorEls.scrollArrow.show();
		}
	}
	window.addEventListener('scroll', throttle(scrollPosition, 100, {leading: true, trailing: true}));
	$(window).on('load', function() {
		scrollPosition()
	});

	// Detect if video is near the bottom of the browser window, if so add scroll arrow
	if (monitorEls.scrollArrow.length) {
		$(function() {
			var $video = $('.hero'),
				video_bottom = $video.position().top + $video.outerHeight();
			if ($video.position().top + $video.outerHeight() > $(window).height()-250) {
				var pos = $(window).height() - video_bottom + 'px';
				if (video_bottom > $(window).height()) {
					pos = '0vh';
				}
				monitorEls.scrollArrow.css({ bottom: pos }).show();
			}
		});
	}

}(jQuery, window.self));