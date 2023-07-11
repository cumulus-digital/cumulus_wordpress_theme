/**
 * Inject USP footer if __uspapi is available
 */

( function ( $, window, undefined ) {
	function waitFor( check, timeout, cb, failcb ) {
		const now = Date.now();
		if ( now > timeout ) {
			return failcb();
		}
		if ( check() ) {
			return cb();
		}
		setTimeout( function () {
			waitFor( check, timeout, cb, failcb );
		}, 250 );
	}

	waitFor(
		function () {
			return !! window.__uspapi;
		},
		Date.now() + 20000,
		function () {
			window.__uspapi( 'uspPing', 1, function ( obj, status ) {
				$( function () {
					const footer = $( '#choice-footer-msg' );
					footer.html( `
						We use cookies and other technologies to provide targeted advertising aimed
						at providing the best experience for our customers.  You may opt out of the
						sale or sharing of data for targeted advertising here:
						<a onclick="window.__uspapi(\'displayUspUi\');">
							Do Not Sell or Share My Personal Information</a>
						</a>.
					` );
				} );
			} );
		},
		function () {}
	);
} )( jQuery, window.self );
