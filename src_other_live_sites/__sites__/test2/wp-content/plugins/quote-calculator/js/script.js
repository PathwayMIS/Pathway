try{Typekit.load( { async: true });}catch( e ){}
var separator = '';
( function( $ ){
	$( document ).ready( function(){
		var headerHeight = $( '.header' ).outerHeight();
		var adminBarHeight = $( '#wpadminbar' ).length > 0 ? $( '#wpadminbar' ).outerHeight() : 0;

		var currentQtyWfCount = $( '.wide-format .quote-calculator-copy.qty' ).length;
		var currentQtyJoCount = $( '.jobs-out .quote-calculator-copy.qty' ).length;
		var currentQtyDpCount = $( '.digital-printing .quote-calculator-copy.qty' ).length;

		$( window ).scroll( function( event ) {
			var scroll = $( window ).scrollTop();
			if( scroll < headerHeight ){
				$( 'aside' ).css( 'top', headerHeight - scroll + adminBarHeight );
			}
			else{
				$( 'aside' ).css( 'top', adminBarHeight );
			}
		});
		$( '.dashboard-page .quote-calculator-section-content' ).niceScroll( {
			cursorwidth: 4,
			cursoropacitymin: 1,
			cursorcolor: '#9E3393',
			background:'#cad4da',
			cursorborder: 'none',
			cursorborderradius: 4,
			autohidemode: false
		}); 

		$( '.new-estimate a' ).click( function(){
			$( '.quote-calculator-section-overflow-hidden, .quote-calculator-section-add-hidden' ).removeClass( 'hidden' );
			return false;
		});

		$( document ).on( 'click', '.quote-calculator-section-add-hidden .add', function() {
			document.location.href = $( '.new-estimate a' ).attr( 'href' );
		});

		$( document ).on( 'click', '.quote-calculator-section-add-hidden .default', function() {
			$( '.quote-calculator-section-overflow-hidden, .quote-calculator-section-add-hidden' ).addClass( 'hidden' );
		});

		$( document ).on( 'click', '.disbaled', function() {
			return false;
		});
					
		$( '.mobile-aside-nav' ).click( function(){
			$( this ).toggleClass( 'open' );
			$( this ).parent().toggleClass( 'open' );
		});
		
		$( '.quote-calculator-date' ).datepicker();
		$( '.quote-calculator-deadline' ).datepicker({ minDate: new Date(), dateFormat: 'MM dd, yy' });

		$( 'select' ).each( function(){
			//if( $( this ).attr( 'data-qty' ) ){
				displayCustomQtySelect( $( this ) );
			//} else {
				//displayCustomSelect( $( this ) );
			//}
		});

		displayCurrentSelectValue();

		$( document ).on( 'click', '.select-styled', function( e ) {
			e.stopPropagation();
			$( 'div.select-styled.active' ).not( this ).each( function(){
				$( this ).removeClass( 'active' ).next( '.select-options-wrapper' ).hide();
			});
			$( this ).toggleClass( 'active' ).next( '.select-options-wrapper' ).toggle();
		});
	 
		$( document ).on( 'click', '.select-options-wrapper li', function( e ) {
			e.stopPropagation();
			var ParentBlock = $( this ).parents( '.select' );
			ParentBlock.find( 'select option[selected]').removeAttr( 'selected' );
			var rel = $( this ).attr( 'rel' );
			if( ParentBlock.find( '.select-styled input' ).length > 0 ){
				ParentBlock.find( '.select-styled input' ).val( $( this ).text() );
				ParentBlock.find( 'select' ).val( $( this ).attr( 'rel' ) );
				
				ParentBlock.find( 'select option' ).map(function () { 
					return $( this ).text() == rel ? this : null;
				}).attr('selected', 'selected');
				//ParentBlock.find( 'select option:contains("' + $( this ).attr( 'rel' ) + '")' ).attr('selected', 'selected');
				ParentBlock.find( 'select' ).trigger( 'change' );
				ParentBlock.find( '.select-options-wrapper' ).hide();
			} else {
				ParentBlock.find( '.select-styled' ).text( $( this ).text() ).removeClass( 'active' );
				ParentBlock.find( 'select' ).val( $( this ).attr( 'rel' ) );
				ParentBlock.find( 'select option' ).map(function () { 
					return $( this ).text() == rel ? this : null;
				}).attr('selected', 'selected');
				ParentBlock.find( 'select' ).trigger( 'change' );
				ParentBlock.find( '.select-options-wrapper' ).hide();
			}
		});

		$( document ).click( function() {
			$( '.select-styled' ).removeClass( 'active' );
			$( '.select-options-wrapper' ).hide();
		});

		$( document ).on( 'click', '.quote-calculator-type .select-options-wrapper li', function(){
			var currentType = $( this ).attr( 'rel' );
			var currentTypeWeight = currentType.split( ' ' )[0];
			var currentTypeFormat = currentType.replace( / /gi, '.' );
			var ParentBlock = $( this ).parents( '.quote-calculator-copy' );
			ParentBlock.find( '.quote-calculator-weight .select-options-wrapper li' ).show();
			ParentBlock.find( '.quote-calculator-format .select-options-wrapper li' ).show();
			ParentBlock.find( '.quote-calculator-weight .select-options-wrapper li:not( .' + currentTypeWeight + ' )' ).each( function(){
				if( $( this ).attr( 'rel' ) != '' )
					$( this ).hide();
			});
			ParentBlock.find( '.quote-calculator-format .select-options-wrapper li:not( .' + currentTypeFormat + ' )' ).each( function(){
				if( $( this ).attr( 'rel' ) != '' )
					$( this ).hide();
			});
			ParentBlock.find( '.quote-calculator-weight select' ).val( '' );//.trigger( 'change' );
			ParentBlock.find( '.quote-calculator-format select' ).val( '' );//.trigger( 'change' );
			ParentBlock.find( '.quote-calculator-weight .select-styled input' ).val( ParentBlock.find( '.quote-calculator-weight select option' ).eq(0).text() );
			ParentBlock.find( '.quote-calculator-format .select-styled input' ).val( ParentBlock.find( '.quote-calculator-format select option' ).eq(0).text() );
		});
		$( '.select-options-wrapper' ).niceScroll( {
			cursorwidth: 4,
			cursoropacitymin: 1,
			cursorcolor: '#9E3393',
			background:'#cad4da',
			cursorborder: 'none',
			cursorborderradius: 4,
			autohidemode: false
		});
		$.expr[':'].icontains = $.expr.createPseudo( function( arg ) {
			return function( elem ) {
				return $( elem ).text().toUpperCase().indexOf( arg.toUpperCase() ) >= 0;
			};
		});
		$( '[name="quote-calculator-filter"]' ).focus( function(){
			$( '[name="quote-calculator-filter"]' ).bind( 'keyup', function() {
				var parentFilter = $( this ).parents( '.quote-calculator-section' );
				if( $( this ).val().length >= 2 ){
					var currentValue = $( this ).val();
					parentFilter.find( 'table tr' ).show();
					parentFilter.find( 'table tbody tr' ).not( ':icontains("' + currentValue + '")' ).hide();
				}
				else{
					parentFilter.find( 'table tr' ).show();
				}
			});
		});
		$( '[name="quote-calculator-filter"]' ).blur( function(){
			$( '.quote-calculator-filter' ).unbind( 'keyup' );
		});
		$( '.quote-calculator-section-content.last .submit' ).click( function(){
			if( $( '.add-new-item' ).hasClass( 'hidden' ) && $( '.quote-calculator-section' ).length > 4 ){
				$( 'main form' ).append( '<input type="hidden" name="quote_calculator_save_form" value="1" />' ).submit();
			} else {
				$( 'html, body' ).animate({ scrollTop: ( $( '.add-new-item' ).offset().top - 80 ) }, 1000);
				$( '.add-new-item span' ).addClass( 'error' );
				setTimeout( function(){ $( '.add-new-item span' ).removeClass( 'error' ); }, 2000 );
			}
		});
		$( document ).on( 'click', '.add-paper-type', function(){
			var newTypeBlock = $( this ).parent().prev().clone();
			newTypeBlock.insertBefore( $( this ).parent() );
			newTypeBlock.find( '.quote-calculator-type select' ).val( '' );//.trigger( 'change' );
			newTypeBlock.find( '.quote-calculator-weight select' ).val( '' );//.trigger( 'change' );
			newTypeBlock.find( '.quote-calculator-format select' ).val( '' );//.trigger( 'change' );
			newTypeBlock.find( '.quote-calculator-type .select-styled input' ).val( newTypeBlock.find( '.quote-calculator-type select option' ).eq(0).text() );
			newTypeBlock.find( '.quote-calculator-weight .select-styled input' ).val( newTypeBlock.find( '.quote-calculator-weight select option' ).eq(0).text() );
			newTypeBlock.find( '.quote-calculator-format .select-styled input' ).val( newTypeBlock.find( '.quote-calculator-format select option' ).eq(0).text() );
			newTypeBlock.find( '.select-options-wrapper' ).niceScroll( {
				cursorwidth: 4,
				cursoropacitymin: 1,
				cursorcolor: '#9E3393',
				background:'#cad4da',
				cursorborder: 'none',
				cursorborderradius: 4,
				autohidemode: false
			});
		});
		$( document ).on( 'click', '.add-finishing', function(){
			$( this ).prev().clone().insertBefore( $( this ) );
			$( this ).prev().find( '.select-options-wrapper' ).niceScroll( {
				cursorwidth: 4,
				cursoropacitymin: 1,
				cursorcolor: '#9E3393',
				background:'#cad4da',
				cursorborder: 'none',
				cursorborderradius: 4,
				autohidemode: false
			});
			/*if( $( this ).prev().find( '.quote-calculator-supplier-price' ).length > 0 ){
				$( this ).prev().find( '.quote-calculator-supplier-price' ).val(0);
				$( this ).prev().find( '.quote-calculator-supplier-name' ).val('');
			}*/
		});
		$( document ).on( 'click', '.add-qty', function(){
			var newQty = 0;
			var newQtyBlock = $( this ).prev().clone();
			if( $( this ).parents( '.wide-format' ).length > 0 ){
				newQty = currentQtyWfCount;
				currentQtyWfCount++;
			} else if( $( this ).parents( '.jobs-out' ).length > 0 ){
				newQty = currentQtyJoCount;
				currentQtyJoCount++;
			} else if( $( this ).parents( '.digital-printing' ).length > 0 ){
				newQty = currentQtyDpCount;
				currentQtyDpCount++;
			}
			newQtyBlock.find( 'select' ).attr( 'data-qty', newQty );
			newQtyBlock.find( 'select option' ).eq(1).attr( 'selected', 'selected' );
			newQtyBlock.find( '.select-styled input' ).val( newQtyBlock.find( 'select option' ).eq(1).text() );
			newQtyBlock.insertBefore( $( this ) );
			$( this ).prev().find( '.select-options-wrapper' ).niceScroll( {
				cursorwidth: 4,
				cursoropacitymin: 1,
				cursorcolor: '#9E3393',
				background:'#cad4da',
				cursorborder: 'none',
				cursorborderradius: 4,
				autohidemode: false
			});
			if( $( this ).parents( '.wide-format' ).length > 0 ){
				calculationWfTotal( newQtyBlock );
			} else if( $( this ).parents( '.jobs-out' ).length > 0 ){
				calculationJoTotal( newQtyBlock );
			} else if( $( this ).parents( '.digital-printing' ).length > 0 ){
				calculationDpTotal( newQtyBlock );
			}			
		});
		
		$( '#quote_calculator_name' ).autocomplete({
			minChars: 2,
			max: 12,
			mustMatch: true,
			matchContains: false,
			scroll: false,
			width: 250,
			source: function( request, response ) {
				$.ajax({
					method: 'POST',
					url: quote_calculator_object.ajaxurl,
					dataType: 'json',
					data: 'action=autocomplete_customers&quote_calculator_customer_name=' + $( '#quote_calculator_name' ).val(),
					error: function (a, b, c) {
						ajax_runner = false;
					},
					success: function( data ) {
						if( data.success == 1 ){
							separator = data.separator;
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
							response( $.map( data.data, function( item ) {
								return {
									label						: item.customers_display_name,
									value						: item.customers_display_name,
									customers_id				: item.customers_id,
									email						: item.customers_email,
									phone						: item.customers_phone,
									bill_address_id				: item.customers_bill_addr,
									ship_address_id				: item.customers_ship_addr,
									bill_address_line1			: item.customer_bill_address_line1,
									bill_address_line2			: item.customer_bill_address_line2,
									bill_address_line3			: item.customer_bill_address_line3,
									bill_address_city			: item.customer_bill_address_city,
									bill_address_country		: item.customer_bill_address_country,
									bill_address_country_code	: item.customer_bill_address_country_code,
									bill_address_postcode		: item.customer_bill_address_postal_code,
									ship_address_line1			: item.customer_ship_address_line1,
									ship_address_line2			: item.customer_ship_address_line2,
									ship_address_line3			: item.customer_ship_address_line3,
									ship_address_city			: item.customer_ship_address_city,
									ship_address_country		: item.customer_ship_address_country,
									ship_address_country_code	: item.customer_ship_address_country_code,
									ship_address_postcode		: item.customer_ship_address_postal_code
								}
							}) );
							$( '.ui-autocomplete' ).niceScroll( {
								cursorwidth: 4,
								cursoropacitymin: 1,
								cursorcolor: '#9E3393',
								background:'#cad4da',
								cursorborder: 'none',
								cursorborderradius: 4,
								autohidemode: false
							});
						}
					}
				});
			},
			select: function( event, ui ) {
				$( '#quote_calculator_customer_id' ).val( ui.item.customers_id );
				$( '#quote_calculator_bill_address_id' ).val( ui.item.bill_address_id );
				$( '#quote_calculator_bill_address_1' ).val( ui.item.bill_address_line1 );
				$( '#quote_calculator_bill_address_2' ).val( ui.item.bill_address_line2 );
				$( '#quote_calculator_bill_address_3' ).val( ui.item.bill_address_line3 );
				$( '#quote_calculator_bill_city' ).val( ui.item.bill_address_city );
				$( '#quote_calculator_bill_country' ).val( ui.item.bill_address_country );
				$( '#quote_calculator_bill_country_code' ).val( ui.item.bill_address_country_code );
				$( '#quote_calculator_bill_post_code' ).val( ui.item.bill_address_postcode );
				$( '#quote_calculator_ship_address_id' ).val( ui.item.ship_address_id );
				$( '#quote_calculator_ship_address_1' ).val( ui.item.ship_address_line1 );
				$( '#quote_calculator_ship_address_2' ).val( ui.item.ship_address_line2 );
				$( '#quote_calculator_ship_address_3' ).val( ui.item.ship_address_line3 );
				$( '#quote_calculator_ship_city' ).val( ui.item.ship_address_city );
				$( '#quote_calculator_ship_country' ).val( ui.item.ship_address_country );
				$( '#quote_calculator_ship_country_code' ).val( ui.item.ship_address_country_code );
				$( '#quote_calculator_ship_post_code' ).val( ui.item.ship_address_postcode );
				$( 'input[name="quote_calculator_email"]' ).val( ui.item.email );
				$( 'input[name="quote_calculator_phone"]' ).val( ui.item.phone );
				$( 'textarea[name="quote_calculator_address"]' ).val( ui.item.bill_address );
				$( 'textarea[name="quote_calculator_delivery_address"]' ).val( ui.item.ship_address );
			}
		});
		$( document ).on( 'click', '.quote-calculator-select-section-button', function(){
			$( '.quote-calculator-section-hidden, .quote-calculator-section-overflow-hidden' ).removeClass( 'hidden' );
		});
		$( '.quote-calculator-section-overflow-hidden' ).click( function(){
			$( '.quote-calculator-section-hidden, .quote-calculator-section-overflow-hidden, .quote-calculator-section-add-hidden' ).addClass( 'hidden' );
		});
		var currentSectionNumber = 0;
		if( $( '#current_section_count' ).length > 0 ){
			currentSectionNumber = $( '#current_section_count' ).val();
		}
		$( '#quote_calculator_section' ).change( function(){
			var currentValue = $( this ).val();
			$.ajax({
				method: 'POST',
				url: quote_calculator_object.ajaxurl,
				dataType: 'json',
				data: 'action=add_section&current_section_count=' + currentSectionNumber + '&section_name=' + currentValue,
				error: function (a, b, c) {
					alert( 'error' );
				},
				success: function( data ) {
					if( data.success == 1 ){
						currentSectionNumber++;
						$( '.quote-calculator-section-hidden, .quote-calculator-section-overflow-hidden' ).addClass( 'hidden' );
						if( ! $( '.quote-calculator-section.add-new-item' ).hasClass( 'hidden' ) ){
							$( '.quote-calculator-section.add-new-item' ).addClass( 'hidden' );
						}
						$( '.quote-calculator-section.last' ).before( data.data );
						var newSection = $( '.quote-calculator-section.last' ).prev();
						$( 'html, body' ).animate({ scrollTop: ( newSection.offset().top - 80 ) }, 1000);
						newSection.find( 'select' ).each( function(){
							//displayCustomSelect( $( this ) );
							//if( $( this ).attr( 'data-qty' ) ){
								displayCustomQtySelect( $( this ) );
							//} else {
								//displayCustomSelect( $( this ) );
							//}
						});
						newSection.find( '.select-options-wrapper' ).niceScroll( {
							cursorwidth: 4,
							cursoropacitymin: 1,
							cursorcolor: '#9E3393',
							background:'#cad4da',
							cursorborder: 'none',
							cursorborderradius: 4,
							autohidemode: false
						});
						currentQtyWfCount = $( '.wide-format .quote-calculator-copy.qty' ).length;
						currentQtyJoCount = $( '.jobs-out .quote-calculator-copy.qty' ).length;
						currentQtyDpCount = $( '.digital-printing .quote-calculator-copy.qty' ).length;
					}
				}
			});
		});
		$( document ).on( 'click', '.quote-calculator-duplicate-section-button', function(){
			var curSection = $( this ).parents( '.quote-calculator-section' );
			var newSection = curSection.clone();
			var sectionNumber = parseInt( newSection.find( 'input[name*="estimate_section_title"]' ).attr( 'name' ).match(/\d+/g)[0] );
			var oldSectionCount = parseInt( newSection.find( 'input[name*="estimate_section_title"]' ).attr( 'name' ).match(/\d+/g)[1] );
			newSection.find( '[name^="quote_calculator_category[' + sectionNumber + ']"]' ).each(function(){
				$( this ).attr( 'name', $( this ).attr( 'name' ).replace( 'quote_calculator_category[' + sectionNumber + '][' + oldSectionCount + ']', 'quote_calculator_category[' + sectionNumber + '][' + currentSectionNumber + ']' ));
			});
			if( curSection.next().val() != '' ){
				curSection.next().after( newSection );
				$( 'html, body' ).animate({ scrollTop: ( curSection.next().next().offset().top - 100 ) }, 1000);
			} else {
				curSection.after( newSection );
				$( 'html, body' ).animate({ scrollTop: ( curSection.next().offset().top - 100 ) }, 1000);
			}
			currentSectionNumber++;

			newSection.find( 'select' ).each( function(){
				displayCustomQtySelect( $( this ) );
			});
			newSection.find( '.select-options-wrapper' ).niceScroll( {
				cursorwidth: 4,
				cursoropacitymin: 1,
				cursorcolor: '#9E3393',
				background:'#cad4da',
				cursorborder: 'none',
				cursorborderradius: 4,
				autohidemode: false
			});
			currentQtyWfCount = $( '.wide-format .quote-calculator-copy.qty' ).length;
			currentQtyJoCount = $( '.jobs-out .quote-calculator-copy.qty' ).length;
			currentQtyDpCount = $( '.digital-printing .quote-calculator-copy.qty' ).length;
			calculateAllTotal();
		});

		$( document ).on( 'click', '.quote-calculator-delete-section-button', function(){
			var prevSection = $( this ).parents( '.quote-calculator-section' ).next();
			if( $( this ).parents( '.quote-calculator-section' ).next().val() != '' ){
				$( this ).parents( '.quote-calculator-section' ).after( '<input type="hidden" name="quote_calculator_category_remove[estimate_section_id][]" value="' + $( this ).parents( '.quote-calculator-section' ).next().val() + '" />' );
			}
			$( this ).parents( '.quote-calculator-section' ).remove();
			if( $( '.quote-calculator-section.add-new-item' ).parent().find( '.quote-calculator-section' ).length == 4 ){
				$( '.quote-calculator-section.add-new-item' ).removeClass( 'hidden' );
				$( 'html, body' ).animate({ scrollTop: ( $( '.quote-calculator-section.add-new-item' ).offset().top - 100 ) }, 1000);
			} else {
				$( 'html, body' ).animate({ scrollTop: ( prevSection.offset().top - 300 ) }, 1000);
			}
			calculateAllTotal();
		});

		$( document ).on( 'change', '.digital-printing .select-hidden', function(){
			calculationDpTotal( $( this ) );
		});
		$( document ).on( 'change', '.digital-printing input[id^="quote_calculator_colour"]', function(){
			calculationDpTotal( $( this ) );
		});
		$( document ).on( 'change', '.digital-printing input[type="number"]', function(){
			calculationDpTotal( $( this ) );
		});
		$( document ).on( 'click', '.digital-printing .quote-calculator-remove', function(){
			if( false !== $( this ).hasClass( 'remove-qty' ) ){
				var parent = $( this ).parents( '.digital-printing' );
				parent.find( '.quote-calculator-copy.display-cost[data-qty="' + $( this ).prev().find( 'select' ).attr( 'data-qty' ) + '"]' ).remove();
				if( parent.find( '.quote-calculator-copy.display-cost' ).length == 1 ){
					parent.find( '.quote-calculator-copy.display-cost span:first-child' ).addClass( 'hidden' );
				}
			}
			var prev = $( this ).parents( '.quote-calculator-copy' ).prev();
			$( this ).parents( '.quote-calculator-copy' ).remove();
			calculationDpTotal( prev );
		});

		$( document ).on( 'change', '.design input[type="number"]', function(){
			calculationDTotal( $( this ) );
		});

		$( document ).on( 'change', '.jobs-out .select-hidden', function(){
			calculationJoTotal( $( this ) );
		});
		$( document ).on( 'change', '.jobs-out input[id^="quote_calculator_colour"]', function(){
			calculationJoTotal( $( this ) );
		});
		$( document ).on( 'change', '.jobs-out input[type="number"]', function(){
			calculationJoTotal( $( this ) );
		});
		$( document ).on( 'change', '.jobs-out .quote-calculator-supplier-price', function(){
			calculationJoTotal( $( this ) );
		});
		$( document ).on( 'click', '.jobs-out .quote-calculator-remove', function(){
			if( false !== $( this ).hasClass( 'remove-qty' ) ){
				var parent = $( this ).parents( '.jobs-out' );
				parent.find( '.quote-calculator-copy.display-cost[data-qty="' + $( this ).prev().find( 'select' ).attr( 'data-qty' ) + '"]' ).remove();
				if( parent.find( '.quote-calculator-copy.display-cost' ).length == 1 ){
					parent.find( '.quote-calculator-copy.display-cost span:first-child' ).addClass( 'hidden' );
				}
			}
			var prev = $( this ).parents( '.quote-calculator-copy' ).prev();
			$( this ).parents( '.quote-calculator-copy' ).remove();
			calculationJoTotal( prev );
		});

		$( document ).on( 'change', '.wide-format .select-hidden', function(){
			calculationWfTotal( $( this ) );
		});
		$( document ).on( 'change', '.wide-format input[id^="quote_calculator_colour"]', function(){
			calculationWfTotal( $( this ) );
		});
		$( document ).on( 'change', '.wide-format input[type="number"]', function(){
			calculationWfTotal( $( this ) );
		});
		$( document ).on( 'click', '.wide-format .quote-calculator-remove', function(){
			if( false !== $( this ).hasClass( 'remove-qty' ) ){
				var parent = $( this ).parents( '.wide-format' );
				parent.find( '.quote-calculator-copy.display-cost[data-qty="' + $( this ).prev().find( 'select' ).attr( 'data-qty' ) + '"]' ).remove();
				if( parent.find( '.quote-calculator-copy.display-cost' ).length == 1 ){
					parent.find( '.quote-calculator-copy.display-cost span:first-child' ).addClass( 'hidden' );
				}
			}
			var prev = $( this ).parents( '.quote-calculator-copy' ).prev();
			$( this ).parents( '.quote-calculator-copy' ).remove();
			calculationWfTotal( prev );
		});

		$( document ).on( 'change', 'input[id^="quote_calculator_delivery_"]', function(){
			calculateAllTotal();
		});

		$( 'section ' ).on( 'dblclick', '.cost_without_vat', function(){
			var oldTotal = parseFloat( $( this ).html().substring(1) );
			$( this ).html( '<input type="text" id="cost_without_vat" value="' + oldTotal + '" />' );
			$( '#cost_without_vat' ).on( 'blur', function(){
				var newTotal = parseFloat( $( this ).val() );
				var VAT = $( this ).parents( '.quote-calculator-subsection' ).prev().find( 'input[name*="estimate_section_vat"]' ).val();
				var totalVat = newTotal + ( newTotal * parseInt( VAT ) / 100 );
				$( this ).parent().parent().parent().find( 'input[name*="estimate_section_cost"]').val( totalVat.toFixed( 2 ) );
				$( this ).parent().parent().parent().find( 'input[name*="estimate_section_cost_without_vat"]').val( newTotal.toFixed( 2 ) );
				$( this ).parent().parent().parent().find( '.cost .cost_with_vat' ).html( '( £' + totalVat.toFixed( 2 ) + ' incl VAT )' );
				$( this ).parent().parent().parent().find( '.cost .cost_without_vat' ).html( '£' + newTotal.toFixed( 2 ) );
				calculateAllTotal();
			});
		});
		
	});
})( jQuery );

function displayCustomSelect( currentSelect ){
	( function($){
		/*
		Reference: http://jsfiddle.net/BB3JK/47/
		*/		
		var $this = $( currentSelect ), numberOfOptions = $( currentSelect ).children( 'option' ).length;
		 
		$this.addClass( 'select-hidden' ); 
		$this.wrap( '<div class="select"></div>' );
		$this.after( '<div class="select-styled"></div>' );

		var $styledSelect = $this.next( 'div.select-styled' );
		if( $this.children( 'option:selected' ).length > 0 ){
			$styledSelect.text( $this.children( 'option:selected' ).text() );
		} else {
			$styledSelect.text( $this.children( 'option' ).eq( 0 ).text() );
		}
	 
		var $list = $( '<ul />', {
			'class': 'select-options'
		}).insertAfter( $styledSelect );
		$list.wrap( '<div class="select-options-wrapper"></div>' );
		var $listWrap = $list.parent();
	 
		for ( var i = 0; i < numberOfOptions; i++ ) {
			$( '<li />', {
				text: $this.children( 'option' ).eq( i ).text(),
				rel: $this.children( 'option' ).eq( i ).val(),
				class: $this.children( 'option' ).eq( i ).attr( 'class' )
			}).appendTo( $list );
		}
	 
		var $listItems = $list.children( 'li' );
		for ( var i = 0; i < numberOfOptions; i++ ) {
			$( '<li />', {
				text: $this.children( 'option' ).eq( i ).text(),
				rel: $this.children( 'option' ).eq( i ).val(),
				class: $this.children( 'option' ).eq( i ).attr( 'class' )
			}).appendTo( $list );
		}
	 
		var $listItems = $list.children( 'li' );
	})( jQuery );
}

function displayCustomQtySelect( currentSelect ){
	( function($){
		/*
		Reference: http://jsfiddle.net/BB3JK/47/
		*/		
		var $this = $( currentSelect ), numberOfOptions = $( currentSelect ).children( 'option' ).length;
		 
		$this.addClass( 'select-hidden' ); 
		$this.wrap( '<div class="select"></div>' );
		$this.after( '<div class="select-styled"><input type="text" value="" /></div>' );

		var $styledSelect = $this.next( 'div.select-styled' );
		if( $this.children( 'option:selected' ).length > 0 ){
			$styledSelect.find( 'input' ).val( $this.children( 'option:selected' ).text() );
		} else {
			$styledSelect.find( 'input' ).val( $this.children( 'option' ).eq( 0 ).text() );
		}
	 
		var $list = $( '<ul />', {
			'class': 'select-options'
		}).insertAfter( $styledSelect );
		$list.wrap( '<div class="select-options-wrapper"></div>' );
		var $listWrap = $list.parent();
	 
		for ( var i = 0; i < numberOfOptions; i++ ) {
			$( '<li />', {
				text: $this.children( 'option' ).eq( i ).text(),
				rel: $this.children( 'option' ).eq( i ).val(),
				class: $this.children( 'option' ).eq( i ).attr( 'class' )
			}).appendTo( $list );
		}
	 
		var $listItems = $list.children( 'li' );
		$styledSelect.find( 'input' ).on( 'focus', function(){
			//$( this ).parent()/*.addClass( 'active' )*/.next( '.select-options-wrapper' ).show();
			$( this ).on( 'keyup', function() {
				var value = $( this ).val();
				if( value.length > 0 ){
					$listItems.show();
					$listItems.not( ':icontains("' + value + '")' ).hide();
				}
				else{
					$listItems.show();
				}
			});
		});
		$styledSelect.find( 'input' ).blur( function(){
			$( this ).off( 'keyup' );
		});
	})( jQuery );
}

function displayCurrentSelectValue(){
	( function($){
		$( 'select[name*="estimate_section_paper_type"]' ).each( function(){
			var currentType = $( this ).find( ':selected' ).text();
			var currentTypeWeight = currentType.split( ' ' )[0];
			var currentTypeFormat = currentType.replace( / /gi, '.' );
			$( this ).parents( '.quote-calculator-copy' ).find( '.quote-calculator-weight .select-options-wrapper li' ).show();
			$( this ).parents( '.quote-calculator-copy' ).find( '.quote-calculator-format .select-options-wrapper li' ).show();
			$( this ).parents( '.quote-calculator-copy' ).find( '.quote-calculator-weight .select-options-wrapper li:not( .' + currentTypeWeight + ' )' ).each( function(){
				if( $( this ).attr( 'rel' ) != '' )
					$( this ).hide();
			});
			$( this ).parents( '.quote-calculator-copy' ).find( '.quote-calculator-format .select-options-wrapper li:not( .' + currentTypeFormat + ' )' ).each( function(){
				if( $( this ).attr( 'rel' ) != '' )
					$( this ).hide();
			});
		});
	})( jQuery );
}

function printCustomerAddress( item ){
	var address = '';
	address += item.customer_address_line1 + separator;
	if( null != item.customer_address_line2 ){
		address += item.customer_address_line2 + separator;
	}
	if( null != item.customer_address_line3 ){
		address += item.customer_address_line3 + separator;
	}
	if( null != item.customer_address_city ){
		address += item.customer_address_city + ' ';
	}
	if( null != item.customer_address_country_sub_division_code ){
		address += item.customer_address_country_sub_division_code + ' ';
	}
	if( null != item.customer_address_postal_code ){
		address += item.customer_address_postal_code + separator;
	}
	if( null != item.customer_address_country ){
		address += item.customer_address_country + separator;
	}
	return address;
}

function getSectionContent(){
	( function($){
		$.ajax({
			method: 'POST',
			url: quote_calculator_object.ajaxurl,
			dataType: 'json',
			data: 'action=add_section&section_name=' + $( this ).val(),
			error: function (a, b, c) {
				alert( 'error' );
			},
			success: function( data ) {
				if( data.success == 1 ){
					$( '.quote-calculator-section-hidden, .quote-calculator-section-overflow-hidden' ).addClass( 'hidden' );
					if( ! $( '.quote-calculator-section.add-new-item' ).hasClass( 'hidden' ) ){
						$( '.quote-calculator-section.add-new-item' ).addClass( 'hidden' );
					}
					$( '.quote-calculator-section.last' ).before( data.data );
					var newSection = $( '.quote-calculator-section.last' ).prev();
					$( 'html, body' ).animate({ scrollTop: ( newSection.offset().top - 40 ) }, 500);
					newSection.find( 'select' ).each( function(){
						displayCustomSelect( $( this ) );
					});
					newSection.find( '.select-options-wrapper' ).niceScroll( {
						cursorwidth: 4,
						cursoropacitymin: 1,
						cursorcolor: '#9E3393',
						background:'#cad4da',
						cursorborder: 'none',
						cursorborderradius: 4,
						autohidemode: false
					});
				}
			}
		});
	})( jQuery );
}

function calculationDpTotal( currentElement ){
	( function($){
		var Q1 = 0, Q2 = 0, Q3 = 0, Q4 = 0;
		var dataQty;
		var parent		= currentElement.parents( '.digital-printing' );
		var print		= parent.find( 'input[id^="quote_calculator_colour"]:checked' ).attr( 'data-calculation' );
		var impressions = parent.find( 'select[name*="estimate_section_sided"] option:selected' ).attr( 'data-calculation' );
		var paper_size	= parent.find( 'select[name*="estimate_section_size"] option:selected' ).attr( 'data-calculation' );
		var page_count	= parent.find( 'select[name*="estimate_section_page_count"] option:selected' ).attr( 'data-price' );
		var quantity;
		var countQtyBlock	= 0;
		var paper_type		= '';
		var paper_weight	= '';
		var paper_format	= '';
		var paper			= '';
		var finishing		= '';
		var finishing_type;
		var total		= 0.00;
		var totalVat	= 0.00;
		var overhead	= parent.find( 'input[name*="estimate_section_overhead"]' ).val();
		var profit		= parent.find( 'input[name*="estimate_section_profit"]' ).val();
		var VAT			= parent.find( 'input[name*="estimate_section_vat"]' ).val();

		countQtyBlock = parent.find( 'select[name*="estimate_section_qty"]' ).length;
		parent.find( 'select[name*="estimate_section_qty"]' ).each( function( select ){
			Q1 = 0, Q2 = 0, Q3 = 0, Q4 = 0, dataQty = '';
			quantity = $( this ).val();
			dataQty	 = $( this ).attr( 'data-qty' );
			if( print != '' && quantity != '' && impressions != '' && paper_size != '' ){
				Q1 = parseFloat( print ) * parseInt( quantity ) * parseInt( impressions ) / parseFloat( paper_size );
				if( isNaN( Q1 ) ){
					Q1 = 0.00;
				}
			}
			parent.find( '.quote-calculator-copy.paper' ).each( function(){
				paper_type = $( this ).find( 'select[name*="estimate_section_paper_type"]' ).val();
				paper_weight = $( this ).find( 'select[name*="estimate_section_weight"]' ).val();
				paper = parent.find( '.paper-calculation[data-type="'+paper_type+'-'+paper_weight+'"]' ).val();
				if( paper != '' && false === isNaN( paper ) && quantity != '' && paper_size != '' ){
					Q2 += parseFloat( paper ) * parseInt( quantity ) / parseFloat( paper_size );
				}
			});
			if( isNaN( Q2 ) ){
				Q2 = 0.00;
			}
			Q3 = Q1 + Q2;
			parent.find( '.quote-calculator-copy.finishing select[name*="estimate_section_finishing"]' ).each( function(){
				finishing_type = $( this ).val();
				finishing = parent.find( '.finishing-calculation[data-type="'+finishing_type+'"]' ).val();
				if( finishing != '' && false === isNaN( finishing ) && quantity != '' ){
					Q4 += parseFloat( finishing ) * parseInt( quantity );
				}
			});
			if( isNaN( Q4 ) ){
				Q4 = 0.00;
			}
			total = Q3 + Q4;
			if( overhead != '' ){
				total = total + ( total * parseInt( overhead ) / 100 );
			}
			if( profit != '' ){
				total = total + ( total * parseInt( profit ) / 100 );
			}
			if( VAT != '' ){
				totalVat = total + ( total * parseInt( VAT ) / 100 );
			}
			if( isNaN( total ) ){
				total = 0.00;
			}
			if( isNaN( totalVat ) ){
				totalVat = 0.00;
			}
			var currentDisplayBlock;
			if( parent.find( '.display-cost[data-qty="' + dataQty + '"]' ).length > 0 ){
				currentDisplayBlock = parent.find( '.display-cost[data-qty="' + dataQty + '"]' );
			} else {
				currentDisplayBlock = parent.find( '.quote-calculator-copy.display-cost:last-child' ).clone().attr( 'data-qty', dataQty );
				currentDisplayBlock.insertAfter( parent.find( '.quote-calculator-copy.display-cost:last-child' ) );
			}
			if( countQtyBlock > 1 ){
				currentDisplayBlock.find( 'span:first-child' ).text( quantity + ' - ' ).removeClass( 'hidden' );
			}
			currentDisplayBlock.find( '.cost .cost_without_vat' ).html( '£' + total.toFixed( 2 ) );
			currentDisplayBlock.find( '.cost .cost_with_vat' ).html( '( £' + totalVat.toFixed( 2 ) + ' incl VAT )' );
			currentDisplayBlock.find( 'input[name*="estimate_section_cost"]' ).val( totalVat.toFixed( 2 ) );
			currentDisplayBlock.find( 'input[name*="estimate_section_cost_without_vat"]' ).val( total.toFixed( 2 ) );
		});		
		calculateAllTotal();
	})( jQuery );
}

function calculationWfTotal( currentElement ){
	( function($){
		var Q1 = 0, Q2 = 0, Q3 = 0, Q4 = 0;
		var dataQty;
		var parent		= currentElement.parents( '.wide-format' );
		var print		= parent.find( 'input[id^="quote_calculator_colour"]:checked' ).attr( 'data-calculation' );
		var paper_size	= parent.find( 'select[name*="estimate_section_size"] option:selected' ).attr( 'data-calculation' );
		var page_count	= parent.find( 'select[name*="estimate_section_page_count"] option:selected' ).attr( 'data-price' );
		var impressions = parent.find( 'select[name*="estimate_section_sided"] option:selected' ).attr( 'data-calculation' );
		var quantity;
		var countQtyBlock	= 0;
		var paper_type		= '';
		var paper_weight	= '';
		var paper			= '';
		var finishing		= '';
		var finishing_type;
		var total		= 0.00;
		var totalVat	= 0.00;
		var overhead	= parent.find( 'input[name*="estimate_section_overhead"]' ).val();
		var profit		= parent.find( 'input[name*="estimate_section_profit"]' ).val();
		var VAT			= parent.find( 'input[name*="estimate_section_vat"]' ).val();
		parent.find( '.quote-calculator-copy.paper' ).each( function(){
			paper_type = $( this ).find( 'select[name*="estimate_section_paper_type"]' ).val();
			paper_weight = $( this ).find( 'select[name*="estimate_section_weight"]' ).val();
			if( paper_weight != '' ){
				paper = parent.find( '.paper-calculation[data-type="'+paper_type+'-'+paper_weight+'"]' ).val();
			} else{
				paper = parent.find( '.paper-calculation[data-type="'+paper_type+'-"]' ).val();
			}
			if( paper != '' && false === isNaN( paper ) && paper_size != '' ){
				Q2 += parseFloat( paper ) / parseFloat( paper_size );
			}
		});
		if( isNaN( Q2 ) ){
			Q2 = 0.00;
		}
		countQtyBlock = parent.find( 'select[name*="estimate_section_qty"]' ).length;
		parent.find( 'select[name*="estimate_section_qty"]' ).each( function( select ){
			Q1 = 0, Q3 = 0, Q4 = 0, dataQty = '';
			quantity = $( this ).val();
			dataQty	 = $( this ).attr( 'data-qty' );
			if( print != '' && quantity != '' && impressions != ''  ){
				//Q1 = parseFloat( print ) * parseInt( quantity );
				Q1 = parseFloat( print ) * parseInt( quantity ) * parseInt( impressions ) / parseFloat( paper_size );
				if( isNaN( Q1 ) ){
					Q1 = 0.00;
				}
			}
			Q3 = Q1 + Q2;
			parent.find( '.quote-calculator-copy.finishing select[name*="estimate_section_finishing"]' ).each( function(){
				finishing_type = $( this ).val();
				finishing = parent.find( '.finishing-calculation[data-type="'+finishing_type+'"]' ).val();
				if( finishing != '' && quantity != '' ){
					Q4 += parseFloat( finishing ) * parseInt( quantity );
				}
			});
			if( isNaN( Q4 ) ){
				Q4 = 0.00;
			}
			total = Q3 + Q4;
			if( overhead != '' ){
				total = total + ( total * parseInt( overhead ) / 100 );
			}
			if( profit != '' ){
				total = total + ( total * parseInt( profit ) / 100 );
			}
			if( VAT != '' ){
				totalVat = total + ( total * parseInt( VAT ) / 100 );
			}
			if( isNaN( total ) ){
				total = 0.00;
			}
			var currentDisplayBlock;
			if( parent.find( '.display-cost[data-qty="' + dataQty + '"]' ).length > 0 ){
				currentDisplayBlock = parent.find( '.display-cost[data-qty="' + dataQty + '"]' );
			} else {
				currentDisplayBlock = parent.find( '.quote-calculator-copy.display-cost:last-child' ).clone().attr( 'data-qty', dataQty );
				currentDisplayBlock.insertAfter( parent.find( '.quote-calculator-copy.display-cost:last-child' ) );
			}
			if( countQtyBlock > 1 ){
				currentDisplayBlock.find( 'span:first-child' ).text( quantity + ' - ' ).removeClass( 'hidden' );
			}
			currentDisplayBlock.find( '.cost .cost_without_vat' ).html( '£' + total.toFixed( 2 ) );
			currentDisplayBlock.find( '.cost .cost_with_vat' ).html( '( £' + totalVat.toFixed( 2 ) + ' incl VAT )' );
			currentDisplayBlock.find( 'input[name*="estimate_section_cost"]' ).val( totalVat.toFixed( 2 ) );
			currentDisplayBlock.find( 'input[name*="estimate_section_cost_without_vat"]' ).val( total.toFixed( 2 ) );
		});
		calculateAllTotal();
	})( jQuery );
}

function calculationDTotal( currentElement ){
	( function($){
		var Q4 = 0;
		var parent = currentElement.parents( '.design' );
		var hourly_rate = parent.find( 'input[name*="estimate_section_rate"]' ).val();
		var amount_hours = parent.find( 'input[name*="estimate_section_hours"]' ).val();
		Q4 = parseInt( hourly_rate ) * parseFloat( amount_hours );
		parent.find( 'input[name*="estimate_section_total"]').val( Q4 );
		var total = Q4;
		var totalVat = 0;
		var overhead = parent.find( 'input[name*="estimate_section_overhead"]' ).val();
		var profit = parent.find( 'input[name*="estimate_section_profit"]' ).val();
		var VAT = parent.find( 'input[name*="estimate_section_vat"]' ).val();
		if( overhead != '' ){
			total = total + ( total * parseInt( overhead ) / 100 );
		}
		if( profit != '' ){
			total = total + ( total * parseInt( profit ) / 100 );
		}
		if( VAT != '' ){
			totalVat = total + ( total * parseInt( VAT ) / 100 );
		}
		if( isNaN( total ) ){
			total = 0.00;
		}
		parent.find( '.cost .cost_without_vat' ).html( '£' + total.toFixed( 2 ) );
		parent.find( '.cost .cost_with_vat' ).html( '( £' + totalVat.toFixed( 2 ) + ' incl VAT )' );
		parent.find( 'input[name*="estimate_section_cost"]' ).val( totalVat.toFixed( 2 ) );
		parent.find( 'input[name*="estimate_section_cost_without_vat"]' ).val( total.toFixed( 2 ) );
		calculateAllTotal();
	})( jQuery );
}

function calculationJoTotal( currentElement ){
	( function($){
		var Q1 = 0, Q2 = 0, Q3 = 0, Q4 = 0;
		var dataQty;
		var parent		= currentElement.parents( '.jobs-out' );
		var print		= parent.find( 'input[id^="quote_calculator_colour"]:checked' ).attr( 'data-calculation' );
		var paper_size	= parent.find( 'select[name*="estimate_section_size"] option:selected' ).attr( 'data-calculation' );
		var impressions = parent.find( 'select[name*="estimate_section_sided"] option:selected' ).attr( 'data-calculation' );
		var page_count	= parent.find( 'select[name*="estimate_section_page_count"] option:selected' ).attr( 'data-price' );
		var quantity;
		var countQtyBlock	= 0;
		var paper_type		= '';
		var paper_weight	= '';
		var paper_format	= '';
		var paper			= '';
		var finishing		= '';
		var finishing_type;
		var totalOverhead	= 0.00;
		var totalProfit		= 0.00;
		var total			= 0.00;
		var totalVat		= 0.00;
		var overhead		= parent.find( 'input[name*="estimate_section_overhead"]' ).val();
		var profit			= parent.find( 'input[name*="estimate_section_profit"]' ).val();
		var VAT				= parent.find( 'input[name*="estimate_section_vat"]' ).val();
		var supplier		= 0.00;
		var supplierFlag	= false;

		countQtyBlock = parent.find( 'select[name*="estimate_section_qty"]' ).length;

		parent.find( 'input[name*="estimate_section_supplier_price"]' ).each( function( input ){
			if( ! supplierFlag && '' != $( this ).val() ){
				supplierFlag = true;
			}
			supplier += parseInt( $( this ).val() );
			if( isNaN( supplier ) ){
				supplier = 0.00;
			}
		});
		parent.find( 'select[name*="estimate_section_qty"]' ).each( function( select ){
			Q1 = 0, Q2 = 0, Q3 = 0, Q4 = 0, dataQty = '';
			quantity = $( this ).val();
			dataQty	 = $( this ).attr( 'data-qty' );
			if( ! supplierFlag ){
				if( print != '' && quantity != '' && impressions != '' && paper_size != '' ){
					Q1 = parseFloat( print ) * parseInt( quantity ) * parseInt( impressions ) / parseFloat( paper_size );
					if( isNaN( Q1 ) ){
						Q1 = 0.00;
					}
				}
				parent.find( '.quote-calculator-copy.paper' ).each( function(){
					paper_type = $( this ).find( 'select[name*="estimate_section_paper_type"]' ).val();
					paper_weight = $( this ).find( 'select[name*="estimate_section_weight"]' ).val();
					paper = parent.find( '.paper-calculation[data-type="'+paper_type+'-'+paper_weight+'"]' ).val();
					if( paper != '' && false === isNaN( paper ) && quantity != '' && paper_size != '' ){
						Q2 += parseFloat( paper ) * parseInt( quantity ) / parseFloat( paper_size );
					}
				});
				if( isNaN( Q2 ) ){
					Q2 = 0.00;
				}
				Q3 = Q1 + Q2;
				parent.find( '.quote-calculator-copy.finishing select[name*="estimate_section_finishing"]' ).each( function(){
					finishing_type = $( this ).val();
					finishing = parent.find( '.finishing-calculation[data-type="'+finishing_type+'"]' ).val();
					if( finishing != '' && false === isNaN( finishing ) && quantity != '' ){
						Q4 += parseFloat( finishing ) * parseInt( quantity );
					}
				});
				if( isNaN( Q4 ) ){
					Q4 = 0.00;
				}
				total = Q3 + Q4;				
			} else {
				total = supplier;
			}
			if( overhead != '' ){
				totalOverhead = total * parseInt( overhead ) / 100;
			}
			if( profit != '' ){
				totalProfit = total * parseInt( profit ) / 100;
			}
			total = total + totalOverhead + totalProfit;
			/*if( supplier != 0.00 ){
				total = total + supplier;
			}*/
			if( VAT != '' ){
				totalVat = total + ( total * parseInt( VAT ) / 100 );
			}
			if( isNaN( total ) ){
				total = 0.00;
			}
			if( isNaN( totalVat ) ){
				totalVat = 0.00;
			}
			var currentDisplayBlock;
			if( parent.find( '.display-cost[data-qty="' + dataQty + '"]' ).length > 0 ){
				currentDisplayBlock = parent.find( '.display-cost[data-qty="' + dataQty + '"]' );
			} else {
				currentDisplayBlock = parent.find( '.quote-calculator-copy.display-cost:last-child' ).clone().attr( 'data-qty', dataQty );
				currentDisplayBlock.insertAfter( parent.find( '.quote-calculator-copy.display-cost:last-child' ) );
			}
			if( countQtyBlock > 1 ){
				currentDisplayBlock.find( 'span:first-child' ).text( quantity + ' - ' ).removeClass( 'hidden' );
			}
			currentDisplayBlock.find( '.cost .cost_without_vat' ).html( '£' + total.toFixed( 2 ) );
			currentDisplayBlock.find( '.cost .cost_with_vat' ).html( '( £' + totalVat.toFixed( 2 ) + ' incl VAT )' );
			currentDisplayBlock.find( 'input[name*="estimate_section_cost"]' ).val( totalVat.toFixed( 2 ) );
			currentDisplayBlock.find( 'input[name*="estimate_section_cost_without_vat"]' ).val( total.toFixed( 2 ) );
		});
		calculateAllTotal();
	})( jQuery );
}

function calculateAllTotal(){
	( function($){
		var total = 0;
		var totalVat = 0;
		$( 'input[name*="estimate_section_cost\]"]' ).each( function(){
			totalVat += parseFloat( $( this ).val() );
		});
		$( 'input[name*="estimate_section_cost_without_vat"]' ).each( function(){
			total += parseFloat( $( this ).val() );
		});
		if( $( 'input[id^="quote_calculator_delivery_"]:checked' ).length > 0 ){
			total += parseFloat( $( 'input[id^="quote_calculator_delivery_"]:checked' ).attr( 'data-price' ) );
			totalVat += parseFloat( $( 'input[id^="quote_calculator_delivery_"]:checked' ).attr( 'data-price' ) );
			totalVat += parseFloat( $( 'input[id^="quote_calculator_delivery_"]:checked' ).attr( 'data-price' ) ) * 20 / 100;
		};
		//console.log(total, totalVat);
		$( '.total-cost' ).text( '£' + total.toFixed( 2 ) );
		$( '#quote_calculator_cost' ).val( total );
		$( '.total-cost-vat span' ).text( '£' + totalVat.toFixed( 2 ) );
		$( '#quote_calculator_cost_incl_vat' ).val( totalVat );
	})( jQuery );
}