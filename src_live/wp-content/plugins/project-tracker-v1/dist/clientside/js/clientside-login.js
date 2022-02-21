/*!
 * This file is part of: Clientside
 * Author: Berend de Jong
 * Author URI: http://frique.me/
 * Version: 1.3.8 (2016-08-31 22:25)
 */

jQuery( function( $ ) {

	"use strict";

	// Add placeholders to inputs
	$( 'label' ).each( function() {

		var $this = $( this );
		var text = $this.text().trim();
		var $input = $this.children( 'input' ).not( '[type="checkbox"]' );

		// If the placeholder is not already set
		if ( ! $input.attr( 'placeholder' ) ) {
			$input.attr( 'placeholder', text );
		}

	} );

	// Login form: auto-check "Remember me"
	$( '#rememberme' ).prop( 'checked', true );

} );