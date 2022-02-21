<?php 
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function wpse_71863_default_pages( $blog_id )
{
    $default_pages = array(
        'Project Tracker All Jobs',
        'Project Tracker Design',
        'Project Tracker Print Production',
        'Project Tracker Jobs Out',
        'Project Tracker Delivery',
        'Project Tracker Completed',
    );

    if ( $current_pages = get_pages() )
        $default_pages = array_diff( $default_pages, wp_list_pluck( $current_pages, 'post_title' ) );

    foreach ( $default_pages as $page_title ) {        
        $data = array(
            'post_title'   => $page_title,
            'post_status'  => 'publish',
            'post_type'    => 'page',
        );

        wp_insert_post( add_magic_quotes( $data ) );
    }
    
}

add_action( 'init', 'wpse_71863_default_pages' );

?>