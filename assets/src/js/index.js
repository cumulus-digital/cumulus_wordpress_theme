import '../css/index.scss';
import throttle from 'lodash/throttle';

( function ( $, window, undefined ) {
	// eslint-disable-line no-unused-vars

	var $html = $( 'html' ),
		$body = $( 'body' ),
		$main = $( 'main' ),
		$masthead = $( '.masthead' ),
		$scrollArrow = $( '.scroll-down-arrow' ),
		$heroVideoContainer = $( '.hero .video-container' ),
		$heroVideo = $( '.hero video' ),
		detectionArea,
		windowHeight,
		mastheadHeight,
		mainPos,
		heroVideoHeight,
		heroVideoBottom;

	function handleWindowUpdates( func, windowEvents, interval, immediate ) {
		if ( typeof interval == 'undefined' ) {
			interval = 100;
		}
		if ( typeof immediate == 'undefined' ) {
			immediate = true;
		}
		$( func );
		$( window ).on(
			windowEvents,
			throttle( func, interval, { leading: true, trailing: true } )
		);
		if ( immediate ) {
			func();
		}
	}

	function showScrollArrow() {
		if (
			$heroVideo.length &&
			heroVideoBottom > windowHeight * 0.7 &&
			$( window ).scrollTop() < 100
		) {
			/*
			var pos = '2vh';
			if (heroVideoBottom < windowHeight) {
				pos = windowHeight - heroVideoBottom - 15;
			}
			*/
			//$scrollArrow.css({ bottom: pos }).fadeIn(200);
			$scrollArrow.fadeIn( 200 );
		} else {
			$scrollArrow.fadeOut( 200 );
		}
	}

	// Update height calculations on resize
	function updateHeights() {
		windowHeight = $( window ).height();
		mastheadHeight = $masthead.outerHeight();
		mainPos = Math.ceil( $main.position().top );
		detectionArea = {
			top: Math.abs( $html.position().top ) + mastheadHeight,
			bottom: $( window ).height(),
		};
		if ( $heroVideo.length ) {
			heroVideoHeight = $heroVideoContainer.outerHeight();
			heroVideoBottom =
				$heroVideoContainer.position().top + heroVideoHeight;
		}
	}
	handleWindowUpdates( updateHeights, 'resize load' );

	function updateOnScroll() {
		var scrollPos = $html.scrollTop();

		// Toggle masthead class when main reaches it
		if ( scrollPos > 0 && scrollPos + detectionArea.top >= mainPos ) {
			$masthead.addClass( 'switch' );
			pauseHeroVideo();
		} else {
			playHeroVideo();
			$masthead.removeClass( 'switch' );
		}

		showScrollArrow();
	}
	handleWindowUpdates( updateOnScroll, 'scroll resize load' );

	function isHeroVideoPlaying() {
		if ( $heroVideo.length ) {
			var video = $heroVideo.get( 0 );
			if (
				video.currentTime > 0 &&
				! video.paused &&
				! video.ended &&
				video.readyState > 2
			) {
				return true;
			}
		}
		return false;
	}
	function pauseHeroVideo() {
		if ( isHeroVideoPlaying() ) {
			$heroVideo.trigger( 'pause' );
		}
	}
	function playHeroVideo() {
		if ( ! isHeroVideoPlaying() ) {
			$heroVideo.trigger( 'play' );
		}
	}

	$( window ).on( 'blur', function () {
		pauseHeroVideo();
	} );
	$( window ).on( 'focus', function () {
		updateOnScroll();
	} );

	function toggleMainMenu( openIt ) {
		if ( openIt === true || ! $body.hasClass( 'menu-active' ) ) {
			$heroVideo.trigger( 'pause' );
			$body.addClass( 'menu-active' );
			$masthead.addClass( 'menu-active' );
			$masthead.find( '.menu' ).scrollTop( 0 );
		} else if ( openIt === false || $body.hasClass( 'menu-active' ) ) {
			$heroVideo.trigger( 'play' );
			$body.removeClass( 'menu-active' );
			$masthead.removeClass( 'menu-active' );
		}
	}
	function openMainMenu() {
		toggleMainMenu( true );
	}
	function closeMainMenu() {
		toggleMainMenu( false );
	}
	$( '.hamburger-container' ).on( 'click', function () {
		toggleMainMenu();
	} );
	$( '.masthead nav.menu a[href*="#"]' ).on( 'click', function () {
		setTimeout( highlightCurrentAnchor, 500 );
		closeMainMenu();
	} );

	// Highlight current anchor item
	function highlightCurrentAnchor() {
		if ( window.location.hash.length > 1 ) {
			$( '.masthead nav.menu a[href*="#"]' )
				.parent()
				.removeClass( 'current_page_item' );
			var hashItem = $(
				'.masthead nav.menu a[href*="' + window.location.hash + '"]'
			);
			if ( hashItem.length ) {
				hashItem.parent().addClass( 'current_page_item' );
			}
		}
	}
	$( function () {
		highlightCurrentAnchor();
	} );

	$scrollArrow.on( 'click', function ( e ) {
		e.preventDefault();
		$( 'html, body' ).animate(
			{
				scrollTop: $main.offset().top - mastheadHeight,
			},
			500
		);
	} );
} )( jQuery, window.self );
