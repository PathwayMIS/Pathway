(function( $ ) { 
    // Add Color Picker to all inputs that have 'color-field' class
    $( function() {
        $( '.color-field-print' ).iris({
			width: 215,
			hide: false,
			palettes: ['#000', '#fff', '#ff0000', '#ffff00', '#00ff00', '#0000ff', '#9d3292']
		});
		$( '.color-field-job' ).iris({
			width: 215,
			hide: false,
			palettes: ['#000', '#fff', '#ff0000', '#ffff00', '#00ff00', '#0000ff', '#9d3292']
		});
		$( '.color-field-delivery' ).iris({
			width: 215,
			hide: false,
			palettes: ['#000', '#fff', '#ff0000', '#ffff00', '#00ff00', '#0000ff', '#9d3292']
		});
		$( '.color-field-titles' ).iris({
			width: 215,
			hide: false,
			palettes: ['#000', '#fff', '#ff0000', '#ffff00', '#00ff00', '#0000ff', '#9d3292']
		});
		$( '.color-field-buttons' ).iris({
			width: 215,
			hide: false,
			palettes: ['#000', '#fff', '#ff0000', '#ffff00', '#00ff00', '#0000ff', '#9d3292']
		});

		if( $( '.qc_edit_dashboard_logo_upload' ).length > 0 ) {
			 if ( typeof wp !== 'undefined' && wp.media && wp.media.editor ) {
				$( document ).on( 'click', '.qc_edit_dashboard_logo_upload', function( e ) {
					e.preventDefault();
					var button = $( this );
					wp.media.editor.send.attachment = function( props, attachment ) {
						if( attachment.type == 'image' ){
							button.prev().attr( 'src', attachment.url );
							button.next().next().val( attachment.url );
						}
					};
					wp.media.editor.open( button );
					return false;
				});
			}
		}
		if( $( '.qc_edit_dashboard_logo_remove' ).length > 0 ) {
			$( document ).on( 'click', '.qc_edit_dashboard_logo_remove', function( e ) {
				var button = $( this );
				button.prev().prev().attr( 'src', '' );
				button.next().val( '' );
				return false;
			});
		}
		if( $( '.qc_remove_user' ).length > 0 ) {
			$( '.qc_remove_user' ).click( function(){
				var currentButton = $( this );
				currentButton.next().addClass( 'is-active' );
				 $.ajax({
					method: 'POST',
					url: ajaxurl,
					data: 'action=qc_remove_user&security='+ qc_admin_script.nonce +'&user=' + currentButton.attr( 'data-user-id' ),
					error: function (a, b, c) {
						
					},
					success: function(data) {
						if( data == 1 ){
							currentButton.parent().parent().remove();
						}
					}
				});
			});
		}

		if( $( '.qc-custom-page .wp-hide-pw' ).length > 0 ) {
			$( '.qc-custom-page .wp-hide-pw' ).click( function(){
				if( $( '.qc-pass-show.active' ).length > 0 ) {
					$( '#qc_user_pass' ).attr( 'type', 'text' );
					$( '.qc-pass-show' ).removeClass( 'active' );
					$( '.qc-pass-hide' ).addClass( 'active' );
				} else {
					$( '#qc_user_pass' ).attr( 'type', 'password' );
					$( '.qc-pass-show' ).addClass( 'active' );
					$( '.qc-pass-hide' ).removeClass( 'active' );
				}
			});
		}
		/*$('#colorpicker').farbtastic('.color-field-farb');
		$('.color-field-picker').wpColorPicker();*/
		$( '.qc-custom-page .qc-dashboard-deadlines-table' ).niceScroll( {
			cursorwidth: 4,
			cursoropacitymin: 1,
			cursorcolor: '#9E3393',
			background:'#cad4da',
			cursorborder: 'none',
			cursorborderradius: 4,
			autohidemode: false
		}); 
    });

	/* Display dashboard image */
	$( window ).on( 'load', function() {
		var topMagin = $( '.qc_admin_menu' ).height() + $( '.qc_admin_menu img' ).height() + 25;
		$( '#adminmenuwrap' ).css( 'margin-top', topMagin );

		console.log( $( '#adminmenuwrap' ).width() );
		console.log( $( '.qc_admin_menu img' ).width() );
		var marginLeft = ( $( '#adminmenuwrap' ).width() - $( '.qc_admin_menu img' ).width()  ) / 2 - 5;
		$( '.qc_admin_menu img' ).css( 'margin-left', marginLeft );
	} );

	/************ Billing page ***********/

	/* Show Bank Account details */
	$( '.bank-arrow-show' ).on( 'click', function() {

		/* Hide the arrow down */
		$( this ).addClass( 'hidden' );

		/* Hide one row details */
		$( '.qc-billing-bank-one-row' ).hide();

		/* Show all Bank Account details */
		$( '.bank-hidden' ).each( function() {
			$( this ).removeClass( 'hidden' );
		} );

		/* Show arrow up */
		$( '.bank-arrow-hide' ).removeClass( 'hidden' )

	} );

	/* Hide Bank Account details */
	$( '.bank-arrow-hide' ).on( 'click', function() {

		/* Hide the arrow up */
		$( this ).addClass( 'hidden' );

		/* Show one row details */
		$( '.qc-billing-bank-one-row' ).show();

		/* Hide all Bank Account details */
		$( '.bank-hidden' ).each( function() {
			$( this ).addClass( 'hidden' );
		} );

		/* Show arrow down */
		$( '.bank-arrow-show' ).removeClass( 'hidden' )

	} );

	/* Show Subscription details */
	$( '.subscription-arrow-show' ).on( 'click', function() {

		/* Hide the arrow down */
		$( this ).addClass( 'hidden' );

		/* Hide one row details */
		$( '.qc-billing-subscription-one-row' ).hide();

		/* Show all Subscription details */
		$( '.subscription-hidden' ).each( function() {
			$( this ).removeClass( 'hidden' );
		} );

		/* Show arrow up */
		$( '.subscription-arrow-hide' ).removeClass( 'hidden' )

	} );

	/* Hide Subscription details */
	$( '.subscription-arrow-hide' ).on( 'click', function() {

		/* Hide the arrow up */
		$( this ).addClass( 'hidden' );

		/* Show one row details */
		$( '.qc-billing-subscription-one-row' ).show();

		/* Hide all Subscription details */
		$( '.subscription-hidden' ).each( function() {
			$( this ).addClass( 'hidden' );
		} );

		/* Show arrow down */
		$( '.subscription-arrow-show' ).removeClass( 'hidden' )

	} );

	/************** End Billing page ****************/
     
})( jQuery );