(function($) {
	$(document).ready(function() {
		$( '.pwm-mobile-menu-button' ).on( 'click', function() {
			$( '.pwm-sites-body' ).toggleClass( 'pwm-mobile-menu' );
		});
		$( '.pwm-site-form .pwm-button-build-site' ).on( 'click', function() {
			var $button = $( this ),
				$spinner = $button.next();

			setTimeout( function() {
				$button.attr( 'disabled', true );
				$spinner.addClass('is-active');
			}, 1);
		});
	});
})(jQuery);