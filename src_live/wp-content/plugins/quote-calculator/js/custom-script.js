( function( $ ){
    $(document).ready(function(){
		if ( $( ".project-tracker-proof" ).length ) {
			$( "[data-stage='proof']" ).addClass('brandCurrentPage');
		}
        var title2 = '';
        var class_attr = '';
        if( $( 'body' ).hasClass( 'project-tracker-design' ) ) {
            title2 = 'Design';
            class_attr = 'project-tracker-design';
        } else if( $( 'body' ).hasClass( 'project-tracker-proof' ) ) {
            title2 = 'Proof';
            class_attr = 'project-tracker-proof';
        } else if( $( 'body' ).hasClass( 'project-tracker-all-jobs' ) ) {
            title2 = 'All jobs';
            class_attr = 'project-tracker-all-jobs';
        } else if( $( 'body' ).hasClass( 'project-tracker-print-production' ) ) {
            title2 = 'Print Production';
            class_attr = 'project-tracker-print-production';
        } else if( $( 'body' ).hasClass( 'project-tracker-wideformat' ) ) {
            title2 = 'Wideformat';
            class_attr = 'project-tracker-wideformat';
        } else if( $( 'body' ).hasClass( 'project-tracker-jobs-out' ) ) {
            title2 = 'Jobs out';
            class_attr = 'project-tracker-jobs-out';
        } else if( $( 'body' ).hasClass( 'project-tracker-delivery' ) ) {
            title2 = 'Delivery';
            class_attr = 'project-tracker-delivery';
        } else if( $( 'body' ).hasClass( 'project-tracker-completed' ) ) {
            title2 = 'Completed';
            class_attr = 'project-tracker-completed';
        }
        
        $('.wpfc-calendar-wrapper').before( '<h2 class="calendar-title">Calendar view</h2>' );
    
        if( class_attr != '' ){
            if( $('.' + class_attr + ' .filterControls').length > 0 ){
                $('.' + class_attr + ' .filterControls').before('<h2 class="design-title">' + title2 + '</h2>' );
            }      
        }
        
        if( class_attr != '' ){
            $('.' + class_attr + ' .app__table').after('<div class="pathway"><div class="qc-admin-footer-logo"><img src="http://acp.pathwaymis.co.uk/wp-content/plugins/quote-calculator/images/Pathway_Logo.jpg"></div></div>' );
        }
        $('#nav .navBarWrap ul.navWrap .navWrap__item[data-stage="settings"]').after('<div class="back-to-top"><span class="dashicons dashicons-arrow-up-alt"></span></div>' );

        $(".back-to-top").hide();


        $(window).scroll(function (){
            if ($(this).scrollTop() > 100){
                $('.back-to-top').fadeIn();
            } else{
                $('.back-to-top').fadeOut();
            }
        });

        $('.back-to-top').click(function (){
            $('body,html').animate({
                scrollTop:0
            }, 800);
            return false;
        });

        $( '.page-template-default .app__table .searchContainer' ).niceScroll( {
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