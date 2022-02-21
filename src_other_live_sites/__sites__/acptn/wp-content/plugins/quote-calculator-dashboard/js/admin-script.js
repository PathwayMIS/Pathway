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
     
})( jQuery );