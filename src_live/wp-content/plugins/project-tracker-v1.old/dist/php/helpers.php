<?php

/*-------------------------------------------------------------------------------
  Contents
-------------------------------------------------------------------------------

1. 
2. 
3. 
4.

-------------------------------------------------------------------------------*/

/*-------------------------------------------------------------------------------
  1. Create base pages
-------------------------------------------------------------------------------*/



/*-------------------------------------------------------------------------------
2. Ajax Backend — Removed for debugging, may be re-added
-------------------------------------------------------------------------------*/

// The JavaScript
function my_action_javascript() {
  //Set Your Nonce
  $ajax_nonce = wp_create_nonce( 'my-special-string' );
  ?>
  <script>
  jQuery( document ).ready( function( $ ) {

    var data = {
      action: 'my_action',
      security: '<?php echo $ajax_nonce; ?>',
      whatever: 1234
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    $.post( ajaxurl, data, function( response)  {
      
    });
  });
  </script>
  <?php
}
add_action( 'admin_footer', 'my_action_javascript' );

// The function that handles the AJAX request
function my_action_callback() {
  global $wpdb; // this is how you get access to the database

  check_ajax_referer( 'my-special-string', 'security' );
  $whatever = intval( $_POST['whatever'] );
  $whatever += 10;
  echo $whatever;

  die(); // this is required to return a proper result
}
//add_action( 'wp_ajax_my_action', 'my_action_callback' );

/*-------------------------------------------------------------------------------
  3. Remove Menu Options
-------------------------------------------------------------------------------*/

add_action( 'admin_menu', 'my_remove_menu_pages' );

  function my_remove_menu_pages() {

    if ( !current_user_can( 'level_10' ) ) {
      remove_menu_page('edit.php?post_type=page');
      remove_menu_page('edit-comments.php');
      remove_menu_page('options-general.php');
      remove_menu_page('edit.php');
      remove_menu_page('tools.php');  
    }        
  }

  add_action('after_setup_theme', 'disable_admin_bar');
    function disable_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
      show_admin_bar(false);
    }
}

/*-------------------------------------------------------------------------------
  4. Custom Post Type
-------------------------------------------------------------------------------*/

add_action('init', 'create_post_type_projects'); // Add our Projects Custom Post Type

function create_post_type_projects()
{
    register_taxonomy_for_object_type('category', 'projects'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'projects');
    register_post_type('projects', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Projects', 'projects'), // Rename these to suit
            'singular_name' => __('Project', 'projects'),
            'add_new' => __('Add New', 'projects'),
            'add_new_item' => __('Add New Project', 'projects'),
            'edit' => __('Edit', 'projects'),
            'edit_item' => __('Edit Project', 'projects'),
            'new_item' => __('New Project', 'projects'),
            'view' => false,
            'view_item' => false,
            'search_items' => __('Search Projects', 'projects'),
            'not_found' => __('No Projects found', 'projects'),
            'not_found_in_trash' => __('No Projects found in Trash', 'projects')
        ),
        'capability_type' => 'post',
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

/*-------------------------------------------------------------------------------
  4.1. Custom Columns
-------------------------------------------------------------------------------*/

add_filter( 'manage_edit-projects_columns', 'my_edit_projects_columns' ) ;

function my_edit_projects_columns( $columns ) {

  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Order No.' ),
    'client' => __( 'Client' ),
    'deadline' => __( 'Deadline' ),
    //'artwork' => __( 'Artwork' ),
    //'notes' => __( 'Notes' ),
    //'order_form' => __( 'Order Form' ),
    'stage' => __( 'Stage' )
  );

  return $columns;
}

function my_custom_projects_columns($column)
{
  global $post;
  if($column == 'client')
  {
    echo get_field('client', $post->ID);
  }
  if($column == 'deadline')
  {
    echo get_field('deadline', $post->ID);
  }
  if($column == 'stage')
  { 
    if (get_field('project_stage', $post->ID)) {
      $stage = get_field('project_stage', $post->ID);
      $stage_object = str_replace(' ', '_', $stage) . '_stage';
      $stage_object = strtolower($stage_object);
      
      if (get_field($stage_object, $post->ID)) {
        $field = get_field_object($stage_object);
        $value = get_field($stage_object);
        $stage_secondary = $field['choices'][ $value ];
      }
    } 
    echo $stage . ' — ' . $stage_secondary;
  }
}

add_action("manage_pages_custom_column", "my_custom_projects_columns");

/*-------------------------------------------------------------------------------
  4.2. Sortable Columns
-------------------------------------------------------------------------------*/

function my_column_register_sortable( $columns )
{
  $columns['order_number'] = 'order_number';
  $columns['client'] = 'client';
  $columns['deadline'] = 'deadline';
  $columns['stage'] = 'stage';
  return $columns;
}

add_filter("manage_edit-projects_sortable_columns", "my_column_register_sortable" );

/*-------------------------------------------------------------------------------
  4.3. Save Post Title
-------------------------------------------------------------------------------*/
add_action('acf/save_post', 'my_save_post', 20);
function my_save_post($post_id){
  
  // Get the data from a field
  $new_title = get_field('order_number', $post_id);
  
  // Set the post data
  $new_post = array(
      'ID'           => $post_id,
      'post_title'   => $new_title,
  );
  
  // Remove the hook to avoid infinite loop. Please make sure that it has
  // the same priority (20)
  remove_action('acf/save_post', 'my_save_post', 20);
  
  // Update the post
  wp_update_post( $new_post );
  
  // Add the hook back
  add_action('acf/save_post', 'my_save_post', 20);
  
}

/*-------------------------------------------------------------------------------
  5. Initialise Filters
-------------------------------------------------------------------------------*/
//add_action( 'restrict_manage_posts', 'wpse45436_admin_posts_filter_restrict_manage_posts' );
/**
 * First create the dropdown
 * make sure to change projects to the name of your custom post type
 * 
 * @author Ohad Raz
 * 
 * @return void
 */
function wpse45436_admin_posts_filter_restrict_manage_posts(){
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    //only add filter to post type you want
    if ('projects' == $type){
        //change this to the list of values you want to show
        //in 'label' => 'value' format
        $values = array(
            'Design' => 'design_stage', 
            'Print Production' => 'print_production_stage',
            'Jobs Out' => 'jobs_out_stage',
            'Delivery' => 'delivery_stage',
        );
        ?>
        <select name="ADMIN_FILTER_FIELD_VALUE">
        <option value=""><?php _e('Filter By ', 'wose45436'); ?></option>
        <?php
            $current_v = isset($_GET['ADMIN_FILTER_FIELD_VALUE'])? $_GET['ADMIN_FILTER_FIELD_VALUE']:'';
            foreach ($values as $label => $value) {
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $value,
                        $value == $current_v? ' selected="selected"':'',
                        $label
                    );
                }
        ?>
        </select>
        <?php
    }
}


//add_filter( 'parse_query', 'wpse45436_posts_filter' );
/**
 * if submitted filter by post meta
 * 
 * make sure to change META_KEY to the actual meta key
 * and projects to the name of your custom post type
 * @author Ohad Raz
 * @param  (wp_query object) $query
 * 
 * @return Void
 */
function wpse45436_posts_filter( $query ){
    global $pagenow;
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if ( 'projects' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != '') {
        $query->query_vars['meta_key'] = 'META_KEY';
        $query->query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
    }
}

/*-------------------------------------------------------------------------------
  6. Add Menu Items
-------------------------------------------------------------------------------*/

function project_tracker_sidebar() {
    add_menu_page(
        __( 'Custom Menu Title', 'textdomain' ),
        'Design',
        'edit_pages',
        'edit.php?s&post_status=all&post_type=projects&cat=5',
        '',
        plugins_url( 'project-tracker/img/icon.png' ),
        50
    );

     add_menu_page(
        __( 'Custom Menu Title', 'textdomain' ),
        'Print Production',
        'edit_pages',
        'edit.php?s&post_status=all&post_type=projects&cat=3',
        '',
        plugins_url( 'project-tracker/img/icon.png' ),
        50
    );

      add_menu_page(
        __( 'Custom Menu Title', 'textdomain' ),
        'Jobs Out',
        'edit_pages',
        'edit.php?s&post_status=all&post_type=projects&cat=4',
        '',
        plugins_url( 'project-tracker/img/icon.png' ),
        50
    );

       add_menu_page(
        __( 'Custom Menu Title', 'textdomain' ),
        'Delivery',
        'edit_pages',
        'edit.php?s&post_status=all&post_type=projects&cat=2',
        '',
        plugins_url( 'project-tracker/img/icon.png' ),
        50
    );
}
add_action( 'admin_menu', 'project_tracker_sidebar' );

/*-------------------------------------------------------------------------------
  7. Remove View Project
-------------------------------------------------------------------------------*/

function remove_row_actions( $actions )
{
    if( get_post_type() === 'project' )
        unset( $actions['view'] );
    return $actions;
}

//add_filter( 'post_row_actions', 'remove_row_actions', 10, 1 );

?>