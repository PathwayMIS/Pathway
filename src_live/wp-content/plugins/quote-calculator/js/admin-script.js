( function($){
	$( document ).ready( function(){
		var currentTab = $( '#quote_calculator_current_tab' ).val();
		var currentRowId = $( '#current_row_number' ).val();
		$( document ).on( 'click', '.button-remove', function(){
			var parent = $( this ).parents( 'tr' );
			if( parent.find( 'input[type="hidden"]' ).length > 0 ){
				parent.find( 'input[type="hidden"]' ).attr( 'name', 'remove[]' ).insertBefore( parent );
			}
			parent.remove();
		});
		$( document ).on( 'click', '.button-add', function(){
			currentRowId++;
			var parent = $( this ).parents( 'tr' );
			if( parent.find( 'input' ).val() != '' ){
				parent.clone().insertAfter( parent ).find( 'input[type!=hidden]').val( '' );
				parent.next().find( 'input' ).each(function(){
					$(this).attr( 'name', $( this ).attr( 'name' ).replace( /\d+/, currentRowId ) );
				});
				parent.next().find( 'select' ).each(function(){
					$(this).attr( 'name', $( this ).attr( 'name' ).replace( /\d+/, currentRowId ) );
				});
				$( this ).addClass( 'hidden' ).next().removeClass( 'hidden' );
			}
		});
		$( '#submit-import' ).click( function(){
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: 'action=import_customers',
				error: function (a, b, c) {
					ajax_runner = false;
				},
				success: function(data) {
					if( data == 1 ){
						$( '#submit-import' ).parent().hide();
						$( '.import-clients-message' ).show();
					}
				}
			});
		});
		$( '#item-import' ).click( function(){
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: 'action=import_items',
				error: function (a, b, c) {
					ajax_runner = false;
				},
				success: function(data) {
					if( data == 1 ){
						$( '#item-import' ).parent().hide();
						$( '.import-items-message' ).show();
					}
				}
			});
		});
		$( '#customer-export' ).click( function(){
			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: 'action=export_customers',
				error: function (a, b, c) {
					ajax_runner = false;
				},
				success: function(data) {
					if( data == 1 ){
						$( '#customer-export' ).parent().hide();
						$( '.export-customers-message' ).show();
					}
				}
			});
		});
	});
})(jQuery);
