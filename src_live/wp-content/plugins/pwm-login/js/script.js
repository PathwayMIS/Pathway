( function( $ ) {
	$( window ).load( function() {
		if ( 'https://acp.pathwaymis.co.uk/wp-login.php' === location.href ) {
			$( '#login_error a' ).attr( 'href', 'https://acp.pathwaymis.co.uk/wp-login.php?action=lostpassword' );
		}
	} );
} )( jQuery );