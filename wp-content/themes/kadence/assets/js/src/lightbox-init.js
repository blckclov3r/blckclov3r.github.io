/* global SimpleLightbox */
/**
 * File lightbox-init.js.
 * Gets Lightbox working for Kadence Theme.
 */

(function() {
	'use strict';
	var kadenceThemeLightbox = {
		checkImage: function( element ) {
			return /(png|jpg|jpeg|gif|tiff|bmp)$/.test(
				element.getAttribute( 'href' ).toLowerCase().split( '?' )[0].split( '#' )[0]
			);
		},
		findImages: function() {
			var foundLinks = document.querySelectorAll( 'a[href]:not(.kt-no-lightbox):not(.custom-link):not(.kb-gallery-item-link)' );
			if ( ! foundLinks.length ) {
				return;
			}
			if ( foundLinks )
			for ( let i = 0; i < foundLinks.length; i++ ) {
				if ( kadenceThemeLightbox.checkImage( foundLinks[ i ] ) ) {
					foundLinks[ i ].classList.add( 'kt-lightbox' );
					new SimpleLightbox({
						elements: [ foundLinks[ i ] ],
					});
				}
			}
		},
		/**
		 * Initiate the script to process all
		 */
		initAll: function() {
			kadenceThemeLightbox.findImages();
		},
		// Initiate the menus when the DOM loads.
		init: function() {
			if ( typeof SimpleLightbox == 'function' ) {
				kadenceThemeLightbox.initAll();
			} else {
				var initLoadDelay = setInterval( function(){ if ( typeof SimpleLightbox == 'function' ) { kadenceThemeLightbox.initAll(); clearInterval(initLoadDelay); } }, 200 );
			}
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', kadenceThemeLightbox.init );
	} else {
		// The DOM has already been loaded.
		kadenceThemeLightbox.init();
	}
})();
