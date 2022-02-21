(function($, root, undefined) {

    $(function() {

        'use strict';


        // Run on page load
        $(document).ready(function() {
            pageFunctions();
        });


        // Run on window resize
        $(window).resize(function() {

        });

        function pageFunctions() {
            hamburglar();
            currentPage();
            openNotes();
            addArtwork();
            newItem();
            openDelivery();
            updateButton();
            editButton();
            editUpdateButton();
            $('#search').hideseek();
        }

        /*$(function(){
          'use strict';
          var $page = $('#ajaxLoading'),
              options = {
                debug: true,
                //prefetch: true,//
                //cacheLength: 2,//
                forms: '.acf-form',
                onStart: {
                  duration: 1000, // Duration of our animation
                  render: function ($container) {
                    // Add your CSS animation reversing class
                    $container.addClass('is-exiting');
                    // Restart your animation
                    smoothState.restartCSSAnimations();
                  }
                },
                onReady: {
                    duration: 0,
                    render: function ($container, $newContent) {
                    // Remove your CSS animation reversing class
                    $container.removeClass('is-exiting');
                    // Inject the new content
                    $container.html($newContent);
                  }
                },
                onAfter: function() {
                    pageFunctions();
                }
            },
              smoothState = $page.smoothState(options).data('smoothState');
        });

        (function($, undefined) {
            var isFired = false;
            var oldReady = jQuery.fn.ready;
            $(function() {
                isFired = true;
                $(document).ready();
            });
            jQuery.fn.ready = function(fn) {
                if(fn === undefined) {
                    $(document).trigger('_is_ready');
                    return;
                }
                if(isFired) {
                    window.setTimeout(fn, 1);
                }
                $(document).bind('_is_ready', fn);
            };
        })(jQuery); */

        // Mobile Device Check
        function isMobile() {
            if ($(window).width() < 739) {
                return true;
            }
            return false;

        }

        // Fixes Heading
        $('.app__table__headings__container').scrollToFixed();

        // Notes Scripts
        function openNotes() {

            $('.js-notes-open').click(function () {
                $(this).next('.app__table__row__item--notes').fadeToggle();
                $('.dimmed').fadeToggle();
                return false;
            });

            $('.js-notes-close').click(function () {
               $(this).parent('.app__table__row__item--notes').fadeToggle();
                $('.dimmed').fadeToggle();
                return false;
            });
        }

        function addArtwork() {

            $('.app__table__row__item--notes--btn').click(function () {
                $(this).next('.app__table__row__item--notes__add').slideToggle();
                return false;
            });
        }

        function openDelivery() {

            $('.js-delivery-open').click(function () {
                $(this).next('.app__table__row__item--delivery').fadeToggle();
                $('.dimmed').fadeToggle();
                return false;
            });

            $('.js-delivery-close').click(function () {
                $(this).parent('.app__table__row__item--delivery').fadeToggle();
                $('.dimmed').fadeToggle();
                return false;
            });
        }

        function updateButton() {
            $('.app__table__row__item--stage .acf-input').click(function () {
                $(this).parents( ".app__table__row__item--stage" ).addClass('is-changed');
            });
        }

        function editUpdateButton() {
            $('.editTools__stage .acf-input').click(function () {
                $(this).parents( ".editTools__stage" ).addClass('is-changed');
            });
        }

        function editButton() {

            $('.post-edit-link').click(function () {
                $(".app__table__row").not($(this).closest()).removeClass('is-editing');
                $(this).closest('.app__table__row').toggleClass('is-editing'); //adds class to current row
                //$('.editTools').slideUp(); //slides up all other edit tools
                $(this).next('.editTools').slideToggle(); //slides the current
                $(".editTools").not($(this).next()).slideUp(); //slides up the others
                return false;
            });
        }

        // Menu Scripts
        function currentPage() {
            if ( $( ".project-tracker-all-jobs" ).length ) {
                $( "[data-stage='all']" ).addClass('brandCurrentPage');
            }
            if ( $( ".project-tracker-design" ).length ) {
                $( "[data-stage='design']" ).addClass('brandCurrentPage');
            }
            if ( $( ".project-tracker-print-production" ).length ) {
                $( "[data-stage='print-production']" ).addClass('brandCurrentPage');
            }
            if ( $( ".project-tracker-jobs-out" ).length ) {
                $( "[data-stage='jobs-out']" ).addClass('brandCurrentPage');
            }
            if ( $( ".project-tracker-delivery" ).length ) {
                $( "[data-stage='delivery']" ).addClass('brandCurrentPage');
            }
            if ( $( ".project-tracker-completed" ).length ) {
                $( "[data-stage='completed']" ).addClass('brandCurrentPage');
            }
        }

        // New Item Scripts
        function newItem() {

            $('.js-new-item-open').click(function () {
                $('.newItem--popup').fadeToggle();
                $(this).toggleClass('active');
                $('.dimmed').fadeToggle();
                return false;
            });
        }

        // Hamburger
        function hamburglar() {
            $('#hamburger').click(function() {
                $('body').toggleClass('open');
            });
        }
    });

})(jQuery, this);