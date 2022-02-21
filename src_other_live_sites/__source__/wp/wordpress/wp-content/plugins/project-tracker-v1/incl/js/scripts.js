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
            quickSort();
        }

        // Mobile Device Check
        function isMobile() {
            if ($(window).width() < 739) {
                return true;
            }
            return false;
        }

        // Sorting functions

        function quickSort() {
            $('.js-quickSort').click(function(){
                var sortType = $(this).data('sort-type');
                console.log(sortType);

                $('.js-quickSort').attr('data-selected', false);
                $(this).attr('data-selected', true);

                if (sortType == "name") {
                    tinysort('.app__table__row',{data:'sort-name'});
                } else if (sortType == "deadline") {
                    tinysort('.app__table__row',{data:'sort-deadline'});
                }
            })
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