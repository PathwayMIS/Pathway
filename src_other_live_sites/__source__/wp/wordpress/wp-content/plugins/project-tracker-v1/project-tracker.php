<?php
/*
Plugin Name: Project Tracker
Plugin URI: http://madebyfalcon.co.uk
Description: Project Tracker for Print Houses.
Author: Ed Craddock
Version: 1.0
Author URI: http://madebyfalcon.co.uk
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('init', 'create_post_type_projects'); // Add our Projects Custom Post Type

/*-------------------------------------------------------------------------------
    Runs when plugin is activated
-------------------------------------------------------------------------------*/

register_activation_hook(__FILE__,'wpse_71863_default_pages'); 

/*-------------------------------------------------------------------------------
    Runs when plugin is deactivated
-------------------------------------------------------------------------------*/

/* register_deactivation_hook( __FILE__, 'project_tracker_remove_design_page' );
register_deactivation_hook( __FILE__, 'project_tracker_remove_prpro_page' );
register_deactivation_hook( __FILE__, 'project_tracker_remove_jobs_out_page' );
register_deactivation_hook( __FILE__, 'project_tracker_remove_delivery_page' );
register_deactivation_hook( __FILE__, 'project_tracker_remove_completed_page' ); */

/*-------------------------------------------------------------------------------
    Helpers & Init
-------------------------------------------------------------------------------*/
include_once('dist/php/helpers.php');
include_once( 'dist/php/page-init.php' );
add_filter('show_admin_bar', '__return_false'); // remove admin bar

function reset_permalinks() {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
}
add_action( 'init', 'reset_permalinks' );

function set_front() {
    $design = get_page_by_title( 'Project Tracker Design' );
    update_option( 'page_on_front', $design->ID );
    update_option( 'show_on_front', 'page' );
}

add_action( 'init', 'set_front' );

/*-------------------------------------------------------------------------------
    Include Plugins
-------------------------------------------------------------------------------*/

/* Clientside */
include_once( 'dist/clientside/index.php' );

/* ACF */

// 1. Include ACF
include_once( 'dist/acf/acf.php' );

// 2. Add saving of field groups
//add_filter('acf/settings/save_json', 'my_acf_json_save_point'); Get working at another point
 
function my_acf_json_save_point( $path ) {
    // update path
    $path = plugin_dir_url( __FILE__ ) . 'dist/acf/acf-json';

    // return
    return $path; 
    echo $path;
}

// 3. Add loading of field groups
//add_filter('acf/settings/load_json', 'my_acf_json_load_point'); Get working at another point

function my_acf_json_load_point( $paths ) {
    // remove original path (optional)
    unset($paths[0]);    
    // append path
    $paths[] = plugin_dir_url( __FILE__ ) . 'dist/acf/acf-json';
    // return
    return $paths;
    echo $paths;
}

// 4. Hide ACF field group menu item
add_filter('acf/settings/show_admin', '__return_true');

/*-------------------------------------------------------------------------------
    Setup fields
-------------------------------------------------------------------------------*/
include_once( 'dist/php/plugin-fields.php' );

/*-------------------------------------------------------------------------------
    Stylesheet load & deload
-------------------------------------------------------------------------------*/

function remove_default_stylesheet() {
    wp_dequeue_style( 'twentysixteen-style' );
    wp_deregister_style( 'twentysixteen-style' );
}

/* Include CSS in backend */
function load_custom_wp_admin_style() {
    wp_register_style( 'custom_wp_admin_css', plugin_dir_url(__FILE__) . 'dist/css/backend_styles.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );
}

/* Include CSS in frontend */
function load_custom_wp_style() {
    wp_enqueue_style( 'stylesheet', plugin_dir_url(__FILE__) . 'dist/css/main.css', false, '1.0.0' );
    wp_enqueue_style( 'custom-stylesheet', plugin_dir_url(__FILE__) . 'dist/css/custom-styles.css', false, '1.0.0' );
    wp_enqueue_style( 'font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', false, '4.6.3' );
    wp_enqueue_style( 'animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css', false, '3.5.2' );
}

/* Include Custom JS */

function load_custom_scripts() {
    wp_register_script('scripts', plugin_dir_url(__FILE__) . 'dist/js/scripts.min.js','1.0.0', true);
    wp_enqueue_script('scripts');
}

add_action( 'wp_enqueue_scripts', 'remove_default_stylesheet', 20 );
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );
add_action( 'wp_enqueue_scripts', 'load_custom_wp_style' );
add_action( 'wp_enqueue_scripts', 'load_custom_scripts' );
//remove_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' ); // remove default theme shit


/*-------------------------------------------------------------------------------
    Tell base page to use page template
-------------------------------------------------------------------------------*/

add_filter( 'page_template', 'insert_my_page_template' );

function insert_my_page_template( $template )
{
    if ( is_page('project-tracker-design' ) ) {
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/page-project-tracker-design.php';
    } elseif ( is_page('project-tracker-print-production' ) ) {
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/page-project-tracker-print-production.php';
    } elseif ( is_page('project-tracker-jobs-out' ) ) {
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/page-project-tracker-jobs-out.php';
    } elseif ( is_page('project-tracker-delivery' ) ) {
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/page-project-tracker-delivery.php';
    } elseif ( is_page('project-tracker-completed' ) ) {
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/page-project-tracker-completed.php';
    } elseif ( is_page('project-tracker-all-jobs' ) ) {
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/page-project-tracker-all-jobs.php';
    } elseif ( is_page('calendar-view' ) ) {
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/page-calendar-view.php';
    }

    return $template;
}

add_filter( 'single_template', 'insert_my_template' );

function insert_my_template( $template )
{
    if ( 'projects' === get_post_type() )
        return plugin_dir_path( __FILE__ ) . 'dist/php/page-templates/single-projects.php';

    return $template;
}

/*-------------------------------------------------------------------------------
    Add Options Page
-------------------------------------------------------------------------------*/

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'Branding Settings',
        'menu_title'    => 'Branding Settings',
        'menu_slug'     => 'branding-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));    
}

/*-------------------------------------------------------------------------------
    Setup Option Fields
-------------------------------------------------------------------------------*/
include_once( 'dist/php/plugin-options-fields.php' );

/*-------------------------------------------------------------------------------
    Generate Custom CSS
-------------------------------------------------------------------------------*/

function generate_options_css() {
    $ss_dir = plugin_dir_path( __FILE__ );
    ob_start(); // Capture all output into buffer
    require($ss_dir . 'dist/php/custom-styles.php'); // Grab the custom-style.php file
    $css = ob_get_clean(); // Store output in a variable, then flush the buffer
    file_put_contents($ss_dir . 'dist/css/custom-styles.css', $css, LOCK_EX); // Save it as a css file
}
add_action( 'acf/save_post', 'generate_options_css' ); //Parse the output and write the CSS file on post save


/*-------------------------------------------------------------------------------
    Slug in Body Class
-------------------------------------------------------------------------------*/

function add_slug_to_body_class($classes) // love dis
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)

/*-------------------------------------------------------------------------------
    Redirect Non-Logged in User // Mercilessly stolen from Daan Korenbach
-------------------------------------------------------------------------------*/

add_action( 'parse_request', 'dmk_redirect_to_login_if_not_logged_in', 1 );
/**
 * Redirects a user to the login page if not logged in.
 *
 * @author Daan Kortenbach
 */
function dmk_redirect_to_login_if_not_logged_in() {
    //is_user_logged_in() || auth_redirect();
    if ( ! is_user_logged_in() ) {
        wp_redirect( wp_login_url() );
        exit;
    }
}


add_filter( 'login_url', 'dmk_strip_loggedout', 1, 1 );
/**
 * Strips '?loggedout=true' from redirect url after login.
 *
 * @author Daan Kortenbach
 *
 * @param  string $login_url
 * @return string $login_url
 */
function dmk_strip_loggedout( $login_url ) {
    return str_replace( '%3Floggedout%3Dtrue', '', $login_url );
}

/*-------------------------------------------------------------------------------
    Use Logo from Options on Login Page
-------------------------------------------------------------------------------*/

function my_login_logo() { ?>
    <?php if (get_field('logo','option') ) { ?>
        <?php $logo = get_field('logo','option'); ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo $logo['url']; ?>);
                height: 50px;
                background-position: center;
            }
        </style>
    <?php } else { ?>
         <style type="text/css">
            #login h1 a, .login h1 a {
                display: none;
            }
        </style>
    <?php } ?>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

?>