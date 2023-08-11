( function () {
	if ( ! window.cmls_qc_config ) {
		console.log( 'QC not configured.' );
		return;
	}
	var host = window.location.hostname;
	var element = document.createElement( 'script' );
	var firstScript = document.getElementsByTagName( 'script' )[ 0 ];
	var url = 'https://cmp.quantcast.com'.concat(
		'/choice/',
		window.cmls_qc_config.UTID,
		'/',
		host,
		'/choice.js?tag_version=V2'
	);
	var uspTries = 0;
	var uspTriesLimit = 3;
	element.async = true;
	element.type = 'text/javascript';
	element.src = url;

	firstScript.parentNode.insertBefore( element, firstScript );

	function makeStub() {
		var TCF_LOCATOR_NAME = '__tcfapiLocator';
		var queue = [];
		var win = window;
		var cmpFrame;

		function addFrame() {
			var doc = win.document;
			var otherCMP = !! win.frames[ TCF_LOCATOR_NAME ];

			if ( ! otherCMP ) {
				if ( doc.body ) {
					var iframe = doc.createElement( 'iframe' );

					iframe.style.cssText = 'display:none';
					iframe.name = TCF_LOCATOR_NAME;
					doc.body.appendChild( iframe );
				} else {
					setTimeout( addFrame, 5 );
				}
			}
			return ! otherCMP;
		}

		function tcfAPIHandler() {
			var gdprApplies;
			var args = arguments;

			if ( ! args.length ) {
				return queue;
			} else if ( args[ 0 ] === 'setGdprApplies' ) {
				if (
					args.length > 3 &&
					args[ 2 ] === 2 &&
					typeof args[ 3 ] === 'boolean'
				) {
					gdprApplies = args[ 3 ];
					if ( typeof args[ 2 ] === 'function' ) {
						args[ 2 ]( 'set', true );
					}
				}
			} else if ( args[ 0 ] === 'ping' ) {
				var retr = {
					gdprApplies: gdprApplies,
					cmpLoaded: false,
					cmpStatus: 'stub',
				};

				if ( typeof args[ 2 ] === 'function' ) {
					args[ 2 ]( retr );
				}
			} else {
				if ( args[ 0 ] === 'init' && typeof args[ 3 ] === 'object' ) {
					args[ 3 ] = Object.assign( args[ 3 ], {
						tag_version: 'V2',
					} );
				}
				queue.push( args );
			}
		}

		function postMessageEventHandler( event ) {
			var msgIsString = typeof event.data === 'string';
			var json = {};

			try {
				if ( msgIsString ) {
					json = JSON.parse( event.data );
				} else {
					json = event.data;
				}
			} catch ( ignore ) {}

			var payload = json.__tcfapiCall;

			if ( payload ) {
				window.__tcfapi(
					payload.command,
					payload.version,
					function ( retValue, success ) {
						var returnMsg = {
							__tcfapiReturn: {
								returnValue: retValue,
								success: success,
								callId: payload.callId,
							},
						};
						if ( msgIsString ) {
							returnMsg = JSON.stringify( returnMsg );
						}
						if (
							event &&
							event.source &&
							event.source.postMessage
						) {
							event.source.postMessage( returnMsg, '*' );
						}
					},
					payload.parameter
				);
			}
		}

		while ( win ) {
			try {
				if ( win.frames[ TCF_LOCATOR_NAME ] ) {
					cmpFrame = win;
					break;
				}
			} catch ( ignore ) {}

			if ( win === window.top ) {
				break;
			}
			win = win.parent;
		}
		if ( ! cmpFrame ) {
			addFrame();
			win.__tcfapi = tcfAPIHandler;
			win.addEventListener( 'message', postMessageEventHandler, false );
		}
	}

	makeStub();

	var uspStubFunction = function () {
		var arg = arguments;
		if ( typeof window.__uspapi !== uspStubFunction ) {
			setTimeout( function () {
				if ( typeof window.__uspapi !== 'undefined' ) {
					window.__uspapi.apply( window.__uspapi, arg );
				}
			}, 500 );
		}
	};

	var checkIfUspIsReady = function () {
		uspTries++;
		if ( window.__uspapi === uspStubFunction && uspTries < uspTriesLimit ) {
			console.warn( 'USP is not accessible' );
		} else {
			clearInterval( uspInterval );
		}
	};

	if ( typeof window.__uspapi === 'undefined' ) {
		window.__uspapi = uspStubFunction;
		var uspInterval = setInterval( checkIfUspIsReady, 6000 );
	}
} )();

/**
 * DataLayer Push & CCPA Support
 *
 * Version 2.0.6
 */
( function ( window, undefined ) {
	/**
	 * @CUSTOM Push to all our defined dataLayers
	 */
	function CMLS_pushToDataLayer( data ) {
		if ( window.cmls_qc_config && ! window.cmls_qc_config.datalayer_push ) {
			return;
		}

		var DLs = [ 'dataLayer' ];
		if ( window.cmls_qc_config && window.cmls_qc_config.datalayers ) {
			DLs = window.cmls_qc_config.datalayers;
		}

		console.log( '[QC] Pushing to dataLayers', data );

		DLs.forEach( function ( dl ) {
			window.self[ dl ] = window.self[ dl ] || [];
			window.self[ dl ].push( data );
		} );
	}

	/**
	 * @CUSTOM Event handler for opening USP UI
	 */
	window.addEventListener( 'click', function ( e ) {
		if (
			e &&
			e.target &&
			e.target.className &&
			e.target.className.indexOf( 'cmls-uspapi-displayuspui' ) > -1
		) {
			window.__uspapi( 'displayUspUi' );
		}
	} );

	//URLs need to be externalized based on environments, during build update.
	var get_iab_vendors_url =
		'https://cmp.quantcast.com/GVL-v2/vendor-list.json';
	var get_google_atp_url =
		'https://cmp.quantcast.com/tcfv2/google-atp-list.json';
	var iab_vendors, iab_vendor_ids, iab_vendor_names;
	var google_vendors, google_vendor_ids, google_vendor_names;
	var google_vendors_arr = [],
		google_vendor_ids_arr = [],
		google_vendor_names_arr = [];
	var iab_vendors_arr = [],
		iab_vendor_ids_arr = [],
		iab_vendor_names_arr = [];
	var non_iab_vendors, non_iab_vendor_ids, non_iab_vendor_names;
	var non_iab_vendors_arr = [],
		non_iab_vendor_ids_arr = [],
		non_iab_vendor_names_arr = [];
	var publisher_consents, publisher_legitimate_interests;
	var purpose_consents, purpose_legitimate_interests;

	function resultToList( a ) {
		b = ',';
		for ( var i in a ) {
			if ( a[ i ] ) {
				b += i + ',';
			}
		}
		return b;
	}

	/**
	 * Get a full list of Non-IAB Vendors, specific to the Quantcast Universal Tag ID, using tcfapi
	 */
	function vendors_getNonIABVendorList() {
		window.__tcfapi( 'getConfig', 2, function ( retObj ) {
			/**
			 * @CUSTOM Return if retObj doesn't exist
			 */
			if ( ! retObj ) {
				return;
			}

			if ( retObj.hasOwnProperty( 'nonIabVendorsInfo' ) ) {
				if (
					retObj.nonIabVendorsInfo.hasOwnProperty(
						'nonIabVendorList'
					)
				) {
					non_iab_vendors = retObj.nonIabVendorsInfo.nonIabVendorList;
					// Dont assume array key = vendor id, set key as vendor id with iab_vendors[i].id
					Object.keys( non_iab_vendors ).forEach( function ( i ) {
						non_iab_vendors_arr[ non_iab_vendors[ i ].vendorId ] =
							non_iab_vendors[ i ].name;
					} );
				}
			}
		} );
	}

	/**
	 * Get a full list of IAB Vendors.
	 */
	function vendors_getIABVendorList() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if ( this.readyState == 4 && this.status == 200 ) {
				var res = JSON.parse( this.responseText );
				if ( res.hasOwnProperty( 'vendors' ) ) {
					iab_vendors = res.vendors;

					// Dont assume array key = vendor id, set key as vendor id with iab_vendors[i].id
					Object.keys( iab_vendors ).forEach( function ( i ) {
						iab_vendors_arr[ iab_vendors[ i ].id ] =
							iab_vendors[ i ].name;
					} );
				}
			}
		};
		xhttp.open( 'GET', get_iab_vendors_url, true );
		xhttp.send();
	}

	/**
	 * Get a full list of google atp list
	 */
	function vendors_getGoogleVendorList() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if ( this.readyState == 4 && this.status == 200 ) {
				google_vendors = JSON.parse( this.responseText );
				google_vendors.forEach( function ( vendor ) {
					google_vendors_arr[ vendor.provider_id ] =
						vendor.provider_name;
				} );
			}
		};
		xhttp.open( 'GET', get_google_atp_url, true );
		xhttp.send();
	}

	/**
	 * CMP Loaded.
	 *
	 * Push __cmpLoaded event to the data layer.
	 */
	function dlSend_tcLoaded( tcData ) {
		/**
		 * @CUSTOM Use our custom datalayer function
		 */
		CMLS_pushToDataLayer( {
			event: '__cmpLoaded',
			__cmpLoaded: true,
			gdpr: tcData.gdprApplies,
		} );

		/**
		 * @CUSTOM Send a window event when loaded
		 */
		var ev = new CustomEvent( '__cmpLoaded', { detail: tcData } );
		window.dispatchEvent( ev );

		/*
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push({
			'event': '__cmpLoaded',
			'__cmpLoaded': true,
			'gdpr': tcData.gdprApplies,
		});
		*/
	}

	/**
	 * IAB Vendors.
	 *
	 * Push __cmpIABConsents event to the datalayer, along with iab vendor consent ids.
	 */
	function dlSend_consentData( tcData ) {
		window.dataLayer = window.dataLayer || [];

		iab_vendor_names_arr = [];
		iab_vendor_ids_arr = [];
		non_iab_vendor_names_arr = [];
		non_iab_vendor_ids_arr = [];
		google_vendor_names_arr = [];
		google_vendor_ids_arr = [];

		if ( tcData.hasOwnProperty( 'publisher' ) ) {
			publisher_consents = resultToList( tcData.publisher.consents );
			publisher_legitimate_interests = resultToList(
				tcData.publisher.legitimateInterests
			);
		}

		if ( tcData.hasOwnProperty( 'purpose' ) ) {
			purpose_consents = resultToList( tcData.purpose.consents );
			purpose_legitimate_interests = resultToList(
				tcData.purpose.legitimateInterests
			);
		}

		// Run this in an interval (every 0.1s) just in case we are still waiting
		// on the return with our iab_vendors list calls, bail after 10 seconds
		var cnt = 0;
		var interval = setInterval( function () {
			cnt += 1;

			if ( cnt === 100 ) {
				clearInterval( interval );
			}

			if ( iab_vendors ) {
				clearInterval( interval );

				if ( tcData.gdprApplies ) {
					// Create name & id arrays of iab vendors with consent.
					if ( tcData.hasOwnProperty( 'vendor' ) ) {
						Object.keys( tcData.vendor.consents ).forEach(
							function ( vendorId ) {
								if (
									tcData.vendor.consents[ vendorId ] ||
									! tcData.gdprApplies
								) {
									iab_vendor_names_arr[ vendorId ] =
										iab_vendors_arr[ vendorId ];
									iab_vendor_ids_arr[ vendorId ] = vendorId;
								}
							}
						);
					}
				} else {
					// GDPR does not apply, add all vendor names/ids
					iab_vendor_names_arr = iab_vendors_arr;
					iab_vendors_arr.forEach( function ( vendorName, vendorId ) {
						iab_vendor_ids_arr[ vendorId ] = vendorId;
					} );
				}

				if ( tcData.addtlConsent && google_vendors_arr ) {
					// Create name and id arrays of google vendors with consent.
					// addtlConsent: "1~39.43.46.55.61.
					// 1~ is the version and the rest are the decoded ids
					google_vendor_ids_arr = tcData.addtlConsent
						.split( '1~' )[ 1 ]
						.split( '.' );
					google_vendor_ids_arr &&
						google_vendor_ids_arr.forEach( function ( google_id ) {
							google_vendor_names_arr.push(
								google_vendors_arr[ google_id ]
							);
						} );
				}

				// Non IAB Vendors
				window.__tcfapi(
					'getNonIABVendorConsents',
					2,
					function ( nonIabConsent, nonIabSuccess ) {
						if ( nonIabSuccess ) {
							if ( nonIabConsent.gdprApplies ) {
								// Create name & id arrays of non iab vendors with consent.
								nonIabConsent.nonIabVendorConsents &&
									Object.keys(
										nonIabConsent.nonIabVendorConsents
									).forEach( function ( vendorId ) {
										if (
											nonIabConsent.nonIabVendorConsents[
												vendorId
											] ||
											! nonIabConsent.gdprApplies
										) {
											non_iab_vendor_names_arr[
												vendorId
											] = non_iab_vendors_arr[ vendorId ];
											non_iab_vendor_ids_arr[ vendorId ] =
												vendorId;
										}
									} );
							} else {
								// GDPR does not apply, add all non iab vendor names/ids
								non_iab_vendor_names_arr = non_iab_vendors_arr;
								non_iab_vendors_arr.forEach( function (
									vendorName,
									vendorId
								) {
									non_iab_vendor_ids_arr[ vendorId ] =
										vendorId;
								} );
							}
						}

						// Join our array values, skipping empty items and joing them with a delimiter
						iab_vendor_names = iab_vendor_names_arr
							.filter( Boolean )
							.join( '|' );
						iab_vendor_ids = iab_vendor_ids_arr
							.filter( Boolean )
							.join( ',' );
						google_vendor_names = google_vendor_names_arr
							.filter( Boolean )
							.join( '|' );
						google_vendor_ids = google_vendor_ids_arr
							.filter( Boolean )
							.join( '|' );
						non_iab_vendor_names = non_iab_vendor_names_arr
							.filter( Boolean )
							.join( '|' );
						non_iab_vendor_ids = non_iab_vendor_ids_arr
							.filter( Boolean )
							.join( ',' );

						/**
						 * @CUSTOM Use our custom dataLayer push function
						 */
						CMLS_pushToDataLayer( {
							event: '__cmpConsents',
							__cmpConsents: {
								iabVendorConsentIds: iab_vendor_ids,
								iabVendorsWithConsent: iab_vendor_names,
								nonIABVendorConsentIds: non_iab_vendor_ids,
								nonIABVendorsWithConsent: non_iab_vendor_names,
								googleVendorConsentIds: google_vendor_ids,
								googleVendorsWithConsent: google_vendor_names,
								gdpr: tcData.gdprApplies,
								publisherConsents: publisher_consents,
								publisherLegitimateInterests:
									publisher_legitimate_interests,
								purposeConsents: purpose_consents,
								purposeLegitimateInterests:
									purpose_legitimate_interests,
							},
						} );

						// Push __cmpIABConsents signals to the data layer.
						/*
					window.dataLayer.push({
						'event': '__cmpConsents',
						'__cmpConsents': {
							'iabVendorConsentIds': iab_vendor_ids,
							'iabVendorsWithConsent': iab_vendor_names,
							'nonIABVendorConsentIds': non_iab_vendor_ids,
							'nonIABVendorsWithConsent': non_iab_vendor_names,
							'googleVendorConsentIds': google_vendor_ids,
							'googleVendorsWithConsent': google_vendor_names,
							'gdpr': tcData.gdprApplies,
							'publisherConsents': publisher_consents,
							'publisherLegitimateInterests': publisher_legitimate_interests,
							'purposeConsents': purpose_consents,
							'purposeLegitimateInterests': purpose_legitimate_interests,
						}
					});
					*/
					}
				);
			}
		}, 100 );
	}

	/**
	 * CCPA footer message.
	 */
	function ccpa_footerMsg( tcData ) {
		/**
		 * @CUSTOM Bail if our config isn't prepared
		 */
		if ( ! window.cmls_qc_config || ! window.cmls_qc_config.ccpa_msg_id ) {
			console.warn(
				'[QC] Attempted to set up footer message, but config does not exist.'
			);
			return;
		}

		/*
		// Bail if there is no ccpa msg id value
		if( ! {{QCChoice - CCPA msg id}})
		return;
		*/

		/**
		 * @CUSTOM Wait for footer message div to exist
		 */
		var footer_msg = document.getElementById(
			window.cmls_qc_config.ccpa_msg_id
		);
		if ( ! footer_msg ) {
			setTimeout( function () {
				ccpa_footerMsg( tcData );
			}, 100 );
			return;
		}

		window.__uspapi( 'uspPing', 1, function ( obj, status ) {
			/**
			 * @CUSTOM Use our own div ID and message
			 */
			var footer_msg = document.getElementById(
				window.cmls_qc_config.ccpa_msg_id
			);
			// We don't check for jurisdiction, we always show message
			if ( status && obj.mode.includes( 'USP' ) && footer_msg !== null ) {
				if ( window.cmls_qc_config.hasOwnProperty( 'ccpa_msg' ) ) {
					footer_msg.innerHTML += window.cmls_qc_config.ccpa_msg;
				} else {
					footer_msg.innerHTML +=
						'We use cookies' +
						' and other data collection technologies' +
						' to provide the best experience for our customers. You may request' +
						' that your data not be shared with third parties here: ' +
						'<a href="#" onclick="window.__uspapi(\'displayUspUi\');"' +
						'>Do Not Sell My Data</a>' +
						'.';
				}

				// Add the 'ccpa-msg-added' class to the container for post message add styling.
				footer_msg.classList.add( 'ccpa-msg-added' );

				window.__uspapi( 'setUspDftData', 1, function ( obj, status ) {
					if ( ! status ) {
						console.log( '[QC] Error: USP string not updated!' );
					}
				} );
			}

			/*
			var footer_msg = document.getElementById({{QCChoice - CCPA msg id}}); // get the footer container for our CCPA message
			if (status && obj.mode.includes('USP') && obj.jurisdiction.includes(obj.location.toUpperCase()) && footer_msg !== null) {
				footer_msg.innerHTML = footer_msg.innerHTML + 'We use cookies'
				+ ' and other data collection technologies'
				+ ' to provide the best experience for our customers. You may request'
				+ ' that your data not be shared with third parties here: '
				+ '<a href="#" onclick="window.__uspapi(\'displayUspUi\');"'
				+ '>Do Not Sell My Data</a>'
				+ '.';

				// Add the 'ccpa-msg-added' class to the container for post message add styling.
				footer_msg.classList.add("ccpa-msg-added");

				window.__uspapi('setUspDftData', 1, function(obj, status) {
					if (!status) {
						console.log("Error: USP string not updated!")
					}
				});
			}
			*/
		} );
	}

	// Only get the IAB and Non-IAB vendor lists if the the Data layer push functionality
	// is enabled, otherwise we do not need the vendor lists.
	window.__tcfapi(
		'addEventListener',
		2,
		function ( getConfig, listenerSuccess ) {
			/**
			 * @CUSTOM use our config
			 */
			//if({{QCChoice - DataLayer Push}} === 'true') {
			var do_datalayer_push =
				window.cmls_qc_config.datalayer_push || false;
			if ( do_datalayer_push ) {
				vendors_getIABVendorList(); // Get global IAB vendor list
				vendors_getGoogleVendorList(); // Get google vendor list
				vendors_getNonIABVendorList(); // Get UTID specific Non-IAB vendors
			}
		}
	);

	// Add our main listener.
	window.__tcfapi(
		'addEventListener',
		2,
		function ( tcData, listenerSuccess ) {
			if ( listenerSuccess ) {
				/**
				 * User Visits:
				 * 1st visit/no cookie = 'cmpuishown' called and then 'useractioncomplete' after user make their selection
				 * Repeat visit/has cookie = only 'tcloaded' is called as the UI does not show unless the user clicks to show
				 */

				/**
				 * @CUSTOM use our config
				 */
				var do_datalayer_push =
					window.cmls_qc_config.datalayer_push || false;
				var do_ccpa = window.cmls_qc_config.ccpa || false;

				switch ( tcData.eventStatus ) {
					case 'cmpuishown':
						// Data Layer
						/**
						 * @CUSTOM use our config
						 */
						//if({{QCChoice - DataLayer Push}} === 'true') {
						if ( do_datalayer_push ) {
							dlSend_tcLoaded( tcData );
						}
						break;

					case 'tcloaded':
						// Data Layer
						/**
						 * @CUSTOM use our config
						 */
						//if({{QCChoice - DataLayer Push}} === 'true') {
						if ( do_datalayer_push ) {
							dlSend_tcLoaded( tcData );
							dlSend_consentData( tcData );
						}

						// CCPA
						/**
						 * @CUSTOM use our config
						 */
						//if({{QCChoice - CCPA}}) {
						if ( do_ccpa ) {
							ccpa_footerMsg( tcData );
						}
						break;

					case 'useractioncomplete':
						// Data Layer
						/**
						 * @CUSTOM use our config
						 */
						//if({{QCChoice - DataLayer Push}} === 'true') {
						if ( do_datalayer_push ) {
							dlSend_consentData( tcData );
						}
						break;
				}
			}
		}
	);
} )( window.self );
