import jQuery from 'jquery';
jQuery( () => {
	const ifr = document.querySelectorAll(
		'iframe[src*="jotform.com"],iframe[data-src*="jotform.com"]'
	);
	if ( ! ifr?.length ) {
		return;
	}

	ifr.forEach( ( i ) => {
		// Scroll parent document to top on subsequent load events
		function initialLoad( e ) {
			e.target.removeEventListener( 'load', initialLoad );
			e.target.addEventListener( 'load', () =>
				window.parent.scrollTo( 0, 0 )
			);
		}
		i.addEventListener( 'load', initialLoad );

		// Add current page's search params to jotform and set the src
		let src = i.getAttribute( 'src' );
		if ( i.getAttribute( 'data-src' ) ) {
			src = i.getAttribute( 'data-src' );
		}
		if ( window.location?.search?.length > 1 ) {
			const get = window.location.search.substring( 1 );
			src += ( src.indexOf( '?' ) > -1 ? '&' : '?' ) + get;
		}
		if ( src !== i.getAttribute( 'src' ) ) {
			i.setAttribute( 'src', src );
		}
	} );

	const handleIframeMessage = ( e ) => {
		if (
			typeof e.data === 'object' ||
			e?.origin !== 'https://form.jotform.com'
		) {
			console.log( e.origin, e.origin !== 'https://form.jotform.com' );
			return;
		}
		const args = e.data.split( ':' );
		if ( ! args || args.length < 2 ) {
			return;
		}
		const doAction = ( cb ) => {
			const ifr = document.querySelectorAll(
				'iframe[src*="jotform.com"],iframe[data-src*="jotform.com"]'
			);
			[ ...ifr ].some( ( i ) => {
				if ( i.contentWindow == e.source ) {
					cb( i );
					return false;
				}
			} );
		};
		switch ( args[ 0 ] ) {
			case 'scrollIntoView':
				doAction( ( i ) => {
					i.scrollIntoView( { block: 'center' } );
				} );
				break;
			case 'setHeight':
				if ( parseInt( args[ 1 ] ) > 0 ) {
					doAction( ( i ) => {
						i.setAttribute( 'height', args[ 1 ] + 'px' );
					} );
				}
				break;
			case 'reloadPage':
				window.self.location.reload();
				break;
			case 'loadScript':
				break;
			case 'exitFullScreen':
				const exitFullScreen = [
					'exitFullscreen',
					'mozCancelFullscreen',
					'webkitExitFullscreen',
					'msExitFullscreen',
				];
				exitFullScreen.some( ( m ) => {
					if ( window.document[ m ] ) {
						window.document[ m ]();
						return false;
					}
				} );
				break;
		}
		doAction( ( i ) => {
			if ( i?.contentWindow?.postMessage ) {
				var urls = {
					docurl: encodeURIComponent( document.URL ),
					referrer: encodeURIComponent( document.referrer ),
				};
				i.contentWindow.postMessage(
					JSON.stringify( { type: 'urls', value: urls } )
				);
			}
		} );
	};

	window.addEventListener( 'message', handleIframeMessage, false );
} );
