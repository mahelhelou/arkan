/* Live preview for the Customizer. */
( function ( $ ) {
	'use strict';

	wp.customize( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			$( '.logo h2' ).contents().first().replaceWith( to + ' ' );
		} );
	} );

	wp.customize( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			$( '.logo h2 span' ).text( to );
		} );
	} );

	wp.customize( 'arkan_accent_color', function ( value ) {
		value.bind( function ( to ) {
			var style = document.getElementById( 'arkan-customizer-css' );
			if ( ! style ) {
				style = document.createElement( 'style' );
				style.id = 'arkan-customizer-css';
				document.head.appendChild( style );
			}
			style.innerHTML = to
				? 'a:hover,.section-title span,.sub-title,.services .item .con .numb,.contact .phone,.footer .item .phone{color:' + to + ';}' +
				  '.button-light:hover,input[type="submit"]:hover,.pagination-wrap li a.active{background-color:' + to + ';border-color:' + to + ';}'
				: '';
		} );
	} );
} )( jQuery );
