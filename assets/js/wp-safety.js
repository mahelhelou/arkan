/*
 * Arkan — front-end safety net (vanilla JS, no dependencies).
 *
 * Why this exists
 * ---------------
 * The template's preloader is a full-screen #1b1b1b overlay at z-index 999999
 * that is ONLY removed by jQuery in script.js, and `.js .animate-box` starts at
 * opacity 0 and is only revealed by the Waypoints handler in the same file.
 *
 * That makes script.js a single point of failure: any JS error in it — a plugin
 * conflict, a blocked CDN, a jQuery version clash — leaves the visitor staring
 * at a completely blank dark screen with no way to tell what went wrong.
 *
 * This file runs independently. If script.js did not finish initialising, it
 * tears the overlay down and reveals the content anyway, so the worst case is
 * a site without scroll animations rather than a site that looks broken.
 */
( function () {
	'use strict';

	var GRACE_MS = 2500;
	var done = false;

	function killPreloader() {
		var nodes = document.querySelectorAll( '#preloader, .preloader-bg' );
		for ( var i = 0; i < nodes.length; i++ ) {
			nodes[ i ].style.display = 'none';
		}
	}

	function rescue() {
		if ( done ) {
			return;
		}
		done = true;

		// script.js sets this flag on its last line when it initialises cleanly.
		if ( window.arkanScriptReady ) {
			return;
		}

		if ( window.console && window.console.warn ) {
			window.console.warn(
				'[Arkan] Theme JavaScript did not finish initialising. ' +
				'Revealing content without animations. Check the console above ' +
				'for the underlying error (often a jQuery or plugin conflict).'
			);
		}

		document.documentElement.className += ' arkan-js-fallback';
		killPreloader();
	}

	if ( document.readyState === 'complete' ) {
		window.setTimeout( rescue, GRACE_MS );
	} else {
		window.addEventListener( 'load', function () {
			window.setTimeout( rescue, GRACE_MS );
		} );
	}

	// Hard ceiling: never leave the overlay up longer than this, even if the
	// load event never fires (a hanging image or iframe will do that).
	window.setTimeout( rescue, 8000 );
} )();
