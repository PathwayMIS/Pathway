<?php
/*
Plugin Name: QC Dashboard Theme
Plugin URL:
Description: A nice plugin for your custom dashboard pages
Version: 0.1
Author: BWS
Author URI:
Contributors: bestwebsoft
Text Domain: qc_dashboard
*/
require_once( ABSPATH . 'wp-includes/plugin.php' );

class QS_Dashboard {
	private $page_array;
	private $qc_dashboard_options;
	/**
	 * Initializes the plugin
	 */
	function __construct() {
		$this->page_array = array(
			'custom_dashboard',
			'quote_calculator',
			'company_profile',
			'company_details',
			'edit_dashboard',
			'users',
			'billing',
			'project_tracker',
			'edit_departments',
			'calendar_view',
			'view_options',
			'colour_coding',
			'quote_calculator_oauth',
			'quote_calculator_sync',
			'quote_calculator_xero',
			'quote_calculator_add_clients',
			'quote_calculator_client_list',
			'quote_calculator_margins_vat',
			'quote_calculator_design',
			'quote_calculator_digital_page',
			'quote_calculator_jobs_out',
			'quote_calculator_wide_format',
			'quote_calculator_delivery',
		);
		require_once( ABSPATH . 'wp-includes/pluggable.php' );
		if( isset( $_POST['qc_save_colour_options'] ) && wp_verify_nonce( $_POST['qc_save_colour_options'], 'savecolour' ) ) {
			$this->qc_dashboard_options = get_option( 'qc_dashboard_options' );
			$new_dashboard_options = array();
			$new_dashboard_options['dashboard_details'] = array(
				'titles_color'	=> sanitize_text_field( $_POST['qc_colour_title'] ),
				'buttons_color' => sanitize_text_field( $_POST['qc_colour_buttons'] ),
				'logo'					=> sanitize_text_field( $_POST['qc_logo_url'] )
			);
			$this->qc_dashboard_options = array_merge( $this->qc_dashboard_options, $new_dashboard_options );
			update_option( 'qc_dashboard_options', $this->qc_dashboard_options );
		}
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'admin_title', array( $this, 'admin_title' ), 10, 2 );
		//add_filter( 'wp_title', array( $this, 'wp_title' ), 10, 3 );
		add_action( 'admin_menu', array( $this,'register_menu' ), 200 );

		add_filter( 'login_redirect', array( $this, 'custom_login_redirect' ), 10, 3 );
		add_filter( 'wpfc_calendar_header_vars', array( $this, 'calendar_header_vars' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		if( isset( $_GET['page'] ) && in_array( $_GET['page'], $this->page_array ) ){
			add_action( 'admin_enqueue_scripts', array( $this,'load_admin_style' ), 20 );
			add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
			add_action( 'in_admin_header', array( $this, 'in_admin_header' ) );
			add_action( 'in_admin_footer', array( $this, 'in_admin_footer' ) );
			if ( ! $this->qc_dashboard_options = get_option( 'qc_dashboard_options' ) ) {
				$this->qc_dashboard_options = array(
					'company_details'				=> array(
						'display_name' 										=> '',
						'company_name' 										=> '',
						'phone' 													=> '',
						'email' 													=> '',
						'company_address_line1'						=> '',
						'company_address_line2'						=> '',
						'company_address_city'						=> '',
						'company_address_location'				=> '',
						'company_address_postal_code'			=> '',
						'registered_address_line1'				=> '',
						'registered_address_line2'				=> '',
						'registered_address_city'					=> '',
						'registered_address_location'			=> '',
						'registered_address_postal_code'	=> '',
						'registration_number'							=> '',
						'vat_number' 											=> '',
						'company_url' 											=> ''
					),
					'dashboard_details'			=> array(
						'titles_color'	=> '#1a089f',
						'buttons_color' => '#1a089f',
						'logo'					=> ''
					),
					'billing'								=> array(),
					'calendar_view_options' => array(
						'day_view'		=> array(),
						'format_view' => array()
					),
					'calendar_color_options' => array(
						'print_production'	=> '#1a089f',
						'jobs_out'					=> '#800080',
						'deliver'						=> '#d8bfd8'
					)
				);
				add_option( 'qc_dashboard_options', $this->qc_dashboard_options );
			}
		}
		add_action( 'wp_ajax_qc_remove_user', array( $this, 'remove_user' ) );

	} // end constructor

	function init(){
		if( is_user_logged_in() && ! current_user_can( 'activate_plugins' ) ) {
			show_admin_bar( false );
		}
	}

	function admin_title( $admin_title, $title ) {
		$admin_title =  get_bloginfo( 'name' ) . ' &#8211; ' . $title;
		return $admin_title;
	}

	function wp_title( $title, $sep, $seplocation ) {
		if( $title == 'Calendar View' ) {
			$title =  get_bloginfo( 'name' ) . ' &#8211; ' . $title;
		}
		return $title;
	}

	function load_admin_style(){
		wp_enqueue_style( 'qc_admin_css', plugins_url( 'css/admin-style.css', __FILE__ ), array(), '0.1' );
		wp_enqueue_style( 'iris' );
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media ();
		wp_enqueue_script( 'qc_nicescroll', plugins_url( 'js/jquery.nicescroll.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'qc_admin_script', plugins_url( 'js/admin-script.js', __FILE__ ), array( 'iris', 'farbtastic', 'wp-color-picker' ), false, true );
		$ajax_nonce = wp_create_nonce( 'qc_ajax_nonce' );
		wp_localize_script( 'qc_admin_script', 'qc_admin_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) , 'nonce' => $ajax_nonce ) );

		if( isset( $_GET['page'] ) && in_array( $_GET['page'], $this->page_array ) ){
			wp_deregister_style('custom_wp_admin_css');
			wp_deregister_style('clientside-theme-css');
		}
		//wp_deregister_style('wp-admin');
	}

	function custom_login_redirect( $redirect_to, $request, $user ) {
		// //is there a user to check?
		// if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		// 	//check for subscribers
		// 	if ( ! in_array( 'administrator', $user->roles ) ) {
		// 		// redirect them to another URL, in this case, the homepage
		// 		$redirect_to = admin_url( 'admin.php?page=custom_dashboard' );
		// 	}
		// }

		$redirect_to = admin_url( 'admin.php?page=custom_dashboard' );
		return $redirect_to;
	}

	function calendar_header_vars( $header_vars ){
		$current_blog_id = get_current_blog_id();
		$this->qc_dashboard_options = get_option( 'qc_dashboard_options' );
		if( ! empty( $this->qc_dashboard_options['calendar_view_options']['format_view'] ) ){
			$header_vars->right = implode( ',', $this->qc_dashboard_options['calendar_view_options']['format_view'] );
		}

		return $header_vars;
	}

	function register_menu() {
		if( isset( $_GET['page'] ) && in_array( $_GET['page'], $this->page_array ) ){
			add_menu_page(
					'Dashboard', 'Dashboard',
					'read',
					'custom_dashboard',
					array( $this, 'create_dashboard' ),
					array(),
					1
			);
		}
		else {
			add_menu_page(
					'Custom Dashboard', 'Custom Dashboard',
					'read',
					'custom_dashboard',
					array( $this, 'create_dashboard' ),
					array(),
					1
			);
		}


		/* Company Profile Menu Block */
		add_menu_page(
				'Company Profile', 'Company Profile',
        'read',
        'company_profile',
        array( $this, 'company_profile' ),
        array(),
        2
    );
		add_submenu_page(
			'company_profile',
			'Company Details', 'Company Details',
			'manage_options',
			'company_details',
			array( $this, 'company_details' )
		);
		add_submenu_page(
			'company_profile',
			'Edit Dashboard', 'Edit Dashboard',
			'manage_options',
			'edit_dashboard',
			array( $this, 'edit_dashboard' )
		);
		add_submenu_page(
			'company_profile',
			'Users', 'Users',
			'manage_options',
			'users',
			array( $this, 'users' )
		);
		add_submenu_page(
			'company_profile',
			'Billing', 'Billing',
			'manage_options',
			'billing',
			array( $this, 'billing' )
		);

		/* Project Tracker Menu Block */
		add_menu_page(
				'Project Tracker', 'Project Tracker',
        'read',
        'project_tracker',
        array( $this, 'project_tracker' ),
        array(),
        10
    );
		add_submenu_page(
			'project_tracker',
			'Edit Departments', 'Edit Departments',
			'manage_options',
			'edit_departments',
			array( $this, 'edit_departments' )
		);

		add_menu_page(
				'Calendar View', 'Calendar View',
        'read',
        'calendar_view',
        array( $this, 'calendar_view' ),
        array(),
        20
    );
		add_submenu_page(
			'calendar_view',
			'View Options', 'View Options',
			'manage_options',
			'view_options',
			array( $this, 'view_options' )
		);
		add_submenu_page(
			'calendar_view',
			'Colour Coding', 'Colour Coding',
			'manage_options',
			'colour_coding',
			array( $this, 'colour_coding' )
		);

		add_submenu_page(
			'index.php',
			'Logout', 'Logout',
			'manage_options',
			wp_nonce_url( '../wp-login.php?action=logout', 'log-out' ),
			false
		);

		if( isset( $_GET['page'] ) && in_array( $_GET['page'], $this->page_array ) ){
			global $submenu, $menu;
			remove_menu_page( 'edit.php' );
			remove_menu_page( 'plugins.php' );
			remove_menu_page( 'upload.php' );
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'themes.php' );
			remove_menu_page( 'users.php' );
			remove_menu_page( 'tools.php' );
			remove_menu_page( 'options-general.php' );
			remove_menu_page( 'edit.php?post_type=page' );
			remove_menu_page( 'edit.php?post_type=tribe_events' );
			remove_menu_page( 'edit.php?post_type=projects' );
			remove_menu_page( 'edit.php?s&post_status=all&post_type=projects&cat=5' );
			remove_menu_page( 'edit.php?s&post_status=all&post_type=projects&cat=3' );
			remove_menu_page( 'edit.php?s&post_status=all&post_type=projects&cat=4' );
			remove_menu_page( 'edit.php?s&post_status=all&post_type=projects&cat=2' );
			remove_menu_page( 'edit.php?post_type=acf-field-group' );
			remove_menu_page( 'branding-settings' );
			remove_menu_page( 'pwm_sites' );
			remove_submenu_page( 'index.php', 'index.php' );
			remove_submenu_page( 'index.php', 'update-core.php' );

			if( is_user_logged_in() && ! current_user_can( 'activate_plugins' ) ) {
				remove_menu_page( 'index.php' );

				add_menu_page(
					'Logout', 'Logout',
					'read',
					wp_nonce_url( '../wp-login.php?action=logout', 'log-out' ),
					false,
					false
				);
			}

			unset( $submenu['company_profile'][0] );
			unset( $submenu['project_tracker'][0] );
			unset( $submenu['calendar_view'][0] );
			unset( $submenu['quote_calculator'][0] );

			add_filter( 'custom_menu_order', array( $this, 'reorder_admin_menu' ) );
			add_filter( 'menu_order', array( $this, 'reorder_admin_menu' ) );

		}
	}

	function create_dashboard() {
		global $title, $wpdb;
		$current_month_estimates	= $wpdb->get_var( 'SELECT COUNT(*) FROM `' . $wpdb->prefix . 'quote_calculator_estimates` WHERE `estimates_date` >= ' . strtotime( date( 'Y-m-1' ) ) . ' AND `estimates_status` = "estimate"' );
		$current_month_sales			= $wpdb->get_var( 'SELECT COUNT(*) FROM `' . $wpdb->prefix . 'quote_calculator_estimates` WHERE `estimates_date` >= ' . strtotime( date( 'Y-m-1' ) ) . ' AND  `estimates_status` = "sales"' );
		$current_month_design			= $wpdb->get_var( 'SELECT COUNT(`estimate_section_id`) FROM `' . $wpdb->prefix . 'quote_calculator_estimate_sections`, `' . $wpdb->prefix . 'quote_calculator_estimates` WHERE  `estimates_date` >= ' . strtotime( date( 'Y-m-1' ) ) . ' AND `estimate_sections_estimate_id` = `estimates_id` AND `estimate_section_category` = 3' );
		$current_month_completed	= $wpdb->get_var( 'SELECT COUNT(*) FROM `' . $wpdb->prefix . 'quote_calculator_estimates` WHERE `estimates_date` >= ' . strtotime( date( 'Y-m-1' ) ) . ' AND `estimates_date` >= ' . strtotime( date( 'Y-m-1' ) ) . ' AND `estimates_status` = "completed"' );

		$design_count = get_posts(
			array(
				'post_type'		=> 'projects',
				'meta_query'	=> array(
					array(
						'key'			=> 'project_stage',
						'value'		=> 'Design',
					)
				),
				'numberposts' => -1
			)
		);
		$production_count = get_posts(
			array(
				'post_type'		=> 'projects',
				'meta_query'	=> array(
					array(
						'key'			=> 'project_stage',
						'value'		=> 'Print Production',
					)
				),
				'numberposts' => -1
			)
		);
		$jobs_count = get_posts(
			array(
				'post_type'		=> 'projects',
				'meta_query'	=> array(
					array(
						'key'			=> 'project_stage',
						'value'		=> 'Jobs Out',
					)
				),
				'numberposts' => -1
			)
		);
		$delivery_count = get_posts(
			array(
				'post_type'		=> 'projects',
				'meta_query'	=> array(
					array(
						'key'			=> 'project_stage',
						'value'		=> 'Delivery',
					)
				),
				'numberposts' => -1
			)
		);
		$deadline_projects = get_posts(
			array(
				'post_type'		=> 'projects',
				'meta_query'	=> array(
					'relation' => 'AND',
					array(
						'key'			=> 'deadline',
						'value'		=> date( 'Ymd' ),
						'compare' => '>='
					),
					array(
						'key'			=> 'deadline',
						'value'		=> date( 'Ymd', strtotime( '+' . intval( date( 'N', time() ) ) . ' days' ) ),
						'compare' => '<'
					)
				),
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
				'numberposts' => 20
			)
		); ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<div class="qc-dashboard-wrap">
				<div class="qc-dashboard-block-left">
					<div class="qc-dashboard-workflow">
						<div class="qc-dashboard-block-title"><?php _e( 'Current Workflow', 'qc_dashboard' ); ?></div>
						<div>
							<div class="qc-dashboard-workflow-content">
								<div class="qc-dashboard-workflow-number"><?php echo count( $design_count ); ?></div>
								<div class="qc-dashboard-workflow-label"><?php _e( 'Design', 'qc_dashboard' ); ?></div>
							</div>
							<div class="qc-dashboard-workflow-content">
								<div class="qc-dashboard-workflow-number"><?php echo count( $production_count ); ?></div>
								<div class="qc-dashboard-workflow-label"><?php _e( 'Production', 'qc_dashboard' ); ?></div>
							</div>
							<div class="qc-dashboard-workflow-content">
								<div class="qc-dashboard-workflow-number"><?php echo count( $jobs_count ); ?></div>
								<div class="qc-dashboard-workflow-label"><?php _e( 'Jobs Out', 'qc_dashboard' ); ?></div>
							</div>
							<div class="qc-dashboard-workflow-content">
								<div class="qc-dashboard-workflow-number"><?php echo count( $delivery_count ); ?></div>
								<div class="qc-dashboard-workflow-label"><?php _e( 'Delivery', 'qc_dashboard' ); ?></div>
							</div>
						</div>
					</div>
					<div class="qc-dashboard-deadlines">
						<div class="qc-dashboard-block-title"><?php _e( 'Deadlines', 'qc_dashboard' ); ?></div>
						<div class="qc-deadline-week"><?php _e( 'Week commencing', 'qc_dashboard' ); ?> <span class="qc-deadline-week-date"><?php echo date( 'j', strtotime('this week', time() ) ). '<sup>' . date( 'S', strtotime('this week', time() ) ) . '</sup> '. date( 'F Y', strtotime('this week', time() ) ); ?></span></div>
						<?php if( ! empty( $deadline_projects ) ){ ?>
							<div class="qc-dashboard-deadlines-table">
								<table>
									<?php foreach( $deadline_projects as $deadline_project ) { ?>
										<tr>
											<td width="200"><?php echo get_post_meta( $deadline_project->ID, 'client', true ); ?></td>
											<td><?php echo get_post_meta( $deadline_project->ID, 'order_number', true ); ?></td>
											<!--<td><?php echo get_post_meta( $deadline_project->ID, 'notes', true ); ?></td>-->
											<td><?php echo get_post_meta( $deadline_project->ID, 'deadline', true ); ?></td>
										</tr>
									<?php } ?>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="qc-dashboard-block-right">
					<div class="qc-dashboard-jobs">
						<div class="qc-dashboard-block-title"><?php _e( 'Jobs in Process', 'qc_dashboard' ); ?></div>
						<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
						<div class="qc-dashboard-jobs-canvas">
							<canvas id="qcJobChart"></canvas>
						</div>
						<script>
							var ctx = document.getElementById( 'qcJobChart' ).getContext( '2d' );
							var qcJobChart = new Chart(ctx, {
									type: 'doughnut',
									data: {
										labels: [ 'Design', 'Print Production', 'Jobs Out' ],
										datasets: [{
												label: '# of Votes',
												data: [
													<?php echo count( $design_count ); ?>,
													<?php echo count( $production_count ); ?>,
													<?php echo count( $jobs_count ); ?>
												],
												backgroundColor: [
													'rgba(157, 50, 146, 0.3)',
													'rgba(157, 50, 146, 0.5)',
													'rgba(157, 50, 146, 0.1)'
												]
										}]
									},
									options: {
										legend: {
												display: false
										}
									}
							});
						</script>
					</div>
				</div>
				<div class="clear"></div>
				<div class="qc-dashboard-stat-month-wrapper">
					<div class="qc-dashboard-stat-month">
						<div class="qc-dashboard-block-title"><?php _e( 'Estimates', 'qc_dashboard' ); ?><br /><?php _e( 'in', 'qc_dashboard' ); ?> <span class="qc-stat-month-date"><?php echo date( 'F' ); ?></span></div>
						<div class="qc-dashboard-stat-month-number"><?php echo $current_month_estimates; ?></div>
					</div>
					<div class="qc-dashboard-stat-month">
						<div class="qc-dashboard-block-title"><?php _e( 'Sale Orders', 'qc_dashboard' ); ?><br /><br /><!--<?php _e( 'in', 'qc_dashboard' ); ?> <span class="qc-stat-month-date"><?php echo date( 'F' ); ?></span>--></div>
						<div class="qc-dashboard-stat-month-number"><?php echo $current_month_sales; ?></div>
					</div>
					<div class="qc-dashboard-stat-month">
						<div class="qc-dashboard-block-title"><?php _e( 'Designs On Proof', 'qc_dashboard' ); ?><br /><br /><!--<?php _e( 'in', 'qc_dashboard' ); ?> <span class="qc-stat-month-date"><?php echo date( 'F' ); ?></span>--></div>
						<div class="qc-dashboard-stat-month-number"><?php echo $current_month_design	; ?></div>
					</div>
					<div class="qc-dashboard-stat-month">
						<div class="qc-dashboard-block-title"><?php _e( 'Completed Jobs', 'qc_dashboard' ); ?><br /><?php _e( 'in', 'qc_dashboard' ); ?> <span class="qc-stat-month-date"><?php echo date( 'F' ); ?></span></div>
						<div class="qc-dashboard-stat-month-number"><?php echo $current_month_completed; ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	function company_profile(){

	}

	function company_details(){
		global $title;
		$message = '';
		$this->qc_dashboard_options = get_option( 'qc_dashboard_options' );
		if( isset( $_POST['qc_save_company_details'] ) && wp_verify_nonce( $_POST['qc_save_company_details'], 'savecompany' ) ) {
		 	$this->qc_dashboard_options['company_details'] = array(
				'display_name' 										=> sanitize_text_field( $_POST['qc_company_display_name'] ),
				'company_name' 										=> sanitize_text_field( $_POST['qc_company_company_name'] ),
				'phone' 													=> sanitize_text_field( $_POST['qc_company_phone'] ),
				'email' 													=> sanitize_text_field( $_POST['qc_company_email'] ),
				'company_address_line1'						=> sanitize_text_field( $_POST['qc_company_address_line1'] ),
				'company_address_line2'						=> sanitize_text_field( $_POST['qc_company_address_line2'] ),
				'company_address_line3'						=> sanitize_text_field( $_POST['qc_company_address_line3'] ),
				'company_address_city'						=> sanitize_text_field( $_POST['qc_company_address_city'] ),
				'company_address_location'				=> sanitize_text_field( $_POST['qc_company_address_location'] ),
				'company_address_postal_code'			=> sanitize_text_field( $_POST['qc_company_address_postal_code'] ),
				'registered_address_line1'				=> sanitize_text_field( $_POST['qc_registered_address_line1'] ),
				'registered_address_line2'				=> sanitize_text_field( $_POST['qc_registered_address_line2'] ),
				'registered_address_line3'				=> sanitize_text_field( $_POST['qc_registered_address_line3'] ),
				'registered_address_city'					=> sanitize_text_field( $_POST['qc_registered_address_city'] ),
				'registered_address_location'			=> sanitize_text_field( $_POST['qc_registered_address_location'] ),
				'registered_address_postal_code'	=> sanitize_text_field( $_POST['qc_registered_address_postal_code'] ),
				'registration_number'							=> sanitize_text_field( $_POST['qc_company_registration_number'] ),
				'vat_number' 											=> sanitize_text_field( $_POST['qc_company_vat_number'] ),
				'company_url' 						=> sanitize_text_field( $_POST['qc_company_url'] )
			);
			update_option( 'qc_dashboard_options', $this->qc_dashboard_options );
			$message = __( 'Settings saved.', 'quote_calculator' );
		} ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<?php if( ! empty( $message ) ) { ?>
				<div id="setting-error-settings_updated" class="updated settings-error">
					<p><strong><?php echo $message; ?></strong></p>
				</div>
			<?php } ?>
			<form id="quote_calculator_company_details" method="post" action="<?php echo admin_url( 'admin.php?page=company_details' ); ?> ">
				<?php wp_nonce_field( 'savecompany', 'qc_save_company_details' ); ?>
				<table class="form-table">
					<tbody>
						<tr>
							<td>
								<label for="company_display_name"><?php _e( 'Display Name', 'qc_dashboard' ); ?></label><br />
								<input name="qc_company_display_name" id="company_display_name" value="<?php echo $this->qc_dashboard_options['company_details']['display_name']; ?>" class="regular-text" type="text" />
							</td>
							<td>
								<label for="company_company_name"><?php _e( 'Company Name', 'qc_dashboard' ); ?></label><br />
								<input name="qc_company_company_name" id="company_company_name" value="<?php echo $this->qc_dashboard_options['company_details']['company_name']; ?>" class="regular-text" type="text" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="company_phone"><?php _e( 'Phone', 'qc_dashboard' ); ?></label><br />
								<input name="qc_company_phone" id="company_phone" value="<?php echo $this->qc_dashboard_options['company_details']['phone']; ?>" class="regular-text" type="text" />
							</td>
							<td>
								<label for="company_email"><?php _e( 'Email', 'qc_dashboard' ); ?></label><br />
								<input name="qc_company_email" id="company_email" value="<?php echo $this->qc_dashboard_options['company_details']['email']; ?>" class="regular-text" type="email" />
							</td>
						</tr>
						<tr>
							<td>
								<label><?php _e( 'Company Address', 'qc_dashboard' ); ?></label><br />
								<input name="qc_company_address_line1" id="company_address_line1" value="<?php echo $this->qc_dashboard_options['company_details']['company_address_line1']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Address Line 1', 'qc_dashboard' ); ?></p>
								<input name="qc_company_address_line2" id="company_address_line2" value="<?php echo $this->qc_dashboard_options['company_details']['company_address_line2']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Address Line 2', 'qc_dashboard' ); ?></p>
                                <input name="qc_company_address_line3" id="company_address_line3" value="<?php echo $this->qc_dashboard_options['company_details']['company_address_line3']; ?>" class="regular-text" type="text">
                                <p class="description"><?php _e( 'Address Line 3', 'qc_dashboard' ); ?></p>
								<input name="qc_company_address_city" id="company_address_city" value="<?php echo $this->qc_dashboard_options['company_details']['company_address_city']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'City', 'qc_dashboard' ); ?></p>
								<input name="qc_company_address_location" id="company_address_location" value="<?php echo $this->qc_dashboard_options['company_details']['company_address_location']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Location', 'qc_dashboard' ); ?></p>
								<input name="qc_company_address_postal_code" id="company_address_postal_code" value="<?php echo $this->qc_dashboard_options['company_details']['company_address_postal_code']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Postal Code', 'qc_dashboard' ); ?></p>
							</td>
							<td>
								<label><?php _e( 'Registered Address', 'qc_dashboard' ); ?></label><br />
								<input name="qc_registered_address_line1" id="registered_address_line1" value="<?php echo $this->qc_dashboard_options['company_details']['registered_address_line1']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Address Line 1', 'qc_dashboard' ); ?></p>
								<input name="qc_registered_address_line2" id="registered_address_line2" value="<?php echo $this->qc_dashboard_options['company_details']['registered_address_line2']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Address Line 2', 'qc_dashboard' ); ?></p>
                                <input name="qc_registered_address_line3" id="registered_address_line3" value="<?php echo $this->qc_dashboard_options['company_details']['registered_address_line3']; ?>" class="regular-text" type="text">
                                <p class="description"><?php _e( 'Address Line 3', 'qc_dashboard' ); ?></p>
								<input name="qc_registered_address_city" id="registered_address_city" value="<?php echo $this->qc_dashboard_options['company_details']['registered_address_city']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'City', 'qc_dashboard' ); ?></p>
								<input name="qc_registered_address_location" id="registered_address_location" value="<?php echo $this->qc_dashboard_options['company_details']['registered_address_location']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Location', 'qc_dashboard' ); ?></p>
								<input name="qc_registered_address_postal_code" id="registered_address_postal_code" value="<?php echo $this->qc_dashboard_options['company_details']['registered_address_postal_code']; ?>" class="regular-text" type="text">
								<p class="description"><?php _e( 'Postal Code', 'qc_dashboard' ); ?></p>
							</td>
						</tr>
						<tr>
							<td>
								<label for="company_registration_number"><?php _e( 'Company Registration Number', 'qc_dashboard' ); ?></label><br />
								<input name="qc_company_registration_number" id="company_registration_number" value="<?php echo $this->qc_dashboard_options['company_details']['registration_number']; ?>" class="regular-text" type="text" />
							</td>
							<td>
								<label for="company_vat_number"><?php _e( 'VAT Number', 'qc_dashboard' ); ?></label><br />
								<input name="qc_company_vat_number" id="company_vat_number" value="<?php echo $this->qc_dashboard_options['company_details']['vat_number']; ?>" class="regular-text" type="text" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="company_url"><?php _e( 'Company Domain', 'pwm-sites' ); ?></label><br />
								<input name="qc_company_url" id="company_url" value="<?php echo $this->qc_dashboard_options['company_details']['company_url']; ?>" class="regular-text" type="text" />
							</td>
							<td>									
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'qc_dashboard' ); ?>" type="submit"></p>
			</form>
		</div>
		<?php
	}

	function edit_dashboard(){
		global $title; ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=edit_dashboard' ); ?>">
				<?php wp_nonce_field( 'savecolour', 'qc_save_colour_options' ); ?>
				<div class="qc_edit_dashboard_block">
					<label><?php _e( 'Titles', 'qc_dashboard'); ?></label>
					<input type="text" class="color-field-titles" value="<?php echo $this->qc_dashboard_options['dashboard_details']['titles_color']; ?>" name="qc_colour_title" />
					<div class="clear"></div>
				</div>
				<div class="qc_edit_dashboard_block">
					<label><?php _e( 'Buttons', 'qc_dashboard'); ?></label>
					<input type="text" class="color-field-buttons" value="<?php echo $this->qc_dashboard_options['dashboard_details']['buttons_color']; ?>" name="qc_colour_buttons" />
					<div class="clear"></div>
				</div>
				<div class="qc_edit_dashboard_block">
					<label><?php _e( 'Upload Logo', 'qc_dashboard'); ?></label>
					<div class="qc_edit_dashboard_logo" id="qc_edit_dashboard_logo">
						<?php if( isset( $this->qc_dashboard_options['dashboard_details']['logo'] ) && '' !== $this->qc_dashboard_options['dashboard_details']['logo'] ) {
							$url = $this->qc_dashboard_options['dashboard_details']['logo'];
						} else {
								$url = plugins_url( 'images/Pathway_Logo.jpg', __FILE__ );
						} ?>
						<img src="<?php echo $url; ?>" />
						<button class="qc_edit_dashboard_logo_upload button"><?php _e( 'Upload', 'qc_dashboard' ); ?></button> <button class="qc_edit_dashboard_logo_remove button"><?php _e( 'Remove', 'qc_dashboard' ); ?></button>
						<input type="hidden" name="qc_logo_url" value="<?php echo $url; ?>" />
					</div>
					<?php //media_buttons( 'content' ); ?>
					<div class="clear"></div>
				</div>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'qc_dashboard' ); ?>" type="submit"></p>
			</form>
		</div>
	<?php }

	function users(){
		global $title;
		$error = $message = '';
		$title_form = __( 'Add Users', 'qc_dashboard' );
		$title_button = __( 'Add User', 'qc_dashboard' );
		if( isset( $_POST['qc_add_user'] ) && wp_verify_nonce( $_POST['qc_add_user'], 'adduser' ) ) {
			$user_login		= sanitize_text_field( $_POST['qc_user_login'] );
			$user_email		= sanitize_text_field( $_POST['qc_user_email'] );
			$first_name		= sanitize_text_field( $_POST['qc_first_name'] );
			$last_name		= sanitize_text_field( $_POST['qc_last_name'] );
			$user_pass		= sanitize_text_field( $_POST['qc_user_pass'] );
			$role					= sanitize_text_field( $_POST['qc_user_role'] );

			$user_id = username_exists( $user_login );
			if ( ! $user_id && false === email_exists( $user_email ) ) {
				$userdata = compact( 'user_login', 'user_email', 'user_pass', 'first_name', 'last_name', 'role' );
				$user_id = wp_insert_user( $userdata ); //wp_create_user( $user_name, $user_password, $user_email );
				if ( ! is_wp_error( $user_id ) ) {
					$message = __( 'User added.', 'qc_dashboard' );
					unset( $_POST );
				} else {
					$error = $user_id->get_error_message();
				}
			} else {
				if( $user_id ) {
					$error = __( 'User already exists.', 'qc_dashboard' );
				} else {
					$error = __( 'Email already exists.', 'qc_dashboard' );
				}
			}
		}
		if( isset( $_POST['qc_edit_user'] ) && wp_verify_nonce( $_POST['qc_edit_user'], 'edituser' ) ) {
			$user_nicename	= sanitize_text_field( $_POST['qc_user_login'] );
			$user_email		= sanitize_text_field( $_POST['qc_user_email'] );
			$first_name		= sanitize_text_field( $_POST['qc_first_name'] );
			$last_name		= sanitize_text_field( $_POST['qc_last_name'] );
			$user_pass		= sanitize_text_field( $_POST['qc_user_pass'] );
			$role					= sanitize_text_field( $_POST['qc_user_role'] );
			$ID						= sanitize_text_field( $_POST['qc_user'] );
			$user_pass		= wp_hash_password( $user_pass );

			$edit_user_info = get_userdata( $ID );
			if ( ! empty( $edit_user_info ) ) {
				$user_login = $edit_user_info->user_login;
				if( $user_email !== $edit_user_info->user_email && email_exists( $user_email ) ) {
					$error = __( 'Email already exists.', 'qc_dashboard' );
				} else {
					if ( '' !== $user_pass ){
						$userdata = compact( 'ID', 'user_login', 'user_nicename', 'user_email', 'user_pass', 'first_name', 'last_name', 'role' );
					} else {
						$userdata = compact( 'ID', 'user_login', 'user_nicename', 'user_email', 'first_name', 'last_name', 'role' );
					} else {
						$userdata = compact( 'ID', 'user_login', 'user_nicename', 'user_email', 'first_name', 'last_name', 'role' );
					}
					$user_id = wp_insert_user( $userdata );
					if ( ! is_wp_error( $user_id ) ) {
						$message = __( 'User updated.', 'qc_dashboard' );
						unset( $_POST );
						unset( $_GET['user'] );
						unset( $edit_user_info );
					} else {
						$error = $user_id->get_error_message();
					}
				}
			} else {
				$error = __( 'This user does not exist.', 'qc_dashboard' );
			}
		}
		if( isset( $_GET['user'] ) ){
			$edit_user_info = get_userdata( $_GET['user'] );
			$title_form = __( 'Edit Users', 'qc_dashboard' );
			$title_button = __( 'Edit User', 'qc_dashboard' );
		}
		$args = array(
			'blog_id'      => $GLOBALS['blog_id'],
			'role'         => '',
			'role__in'     => array(),
			'role__not_in' => array(),
			'meta_key'     => '',
			'meta_value'   => '',
			'meta_compare' => '',
			'meta_query'   => array(),
			'date_query'   => array(),
			'include'      => array(),
			'exclude'      => array(),
			'orderby'      => 'login',
			'order'        => 'ASC',
			'offset'       => '',
			'search'       => '',
			'number'       => '',
			'count_total'  => false,
			'fields'       => 'all',
			'who'          => '',
		 );
		$all_users = get_users( $args ); ?>
		<div class="wrap">
			<div class="h1"><?php echo $title; ?></div>
			<table class="form-table striped qc_users">
				<thead>
					<tr>
						<th><?php _e( 'First Name', 'qc_dashboard' ); ?></th>
						<th><?php _e( 'Surname', 'qc_dashboard' ); ?></th>
						<th><?php _e( 'Username', 'qc_dashboard' ); ?></th>
						<th><?php _e( 'Email', 'qc_dashboard' ); ?></th>
						<th><?php _e( 'Role', 'qc_dashboard' ); ?></th>
						<th class="action"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $all_users as $user ) {
						$user_capabilities = '';
						$user_info = get_userdata( $user->ID );
						if ( '' !== $user_info->wp_capabilities ){
							$user_capabilities = ucwords( 'SuperAdmin' );
						}
						$user_role = ucwords( implode( ',', $user_info->roles ) ); ?>
						<tr>
							<td><?php echo $user_info->first_name; ?></td>
							<td><?php echo $user_info->last_name; ?></td>
							<td><?php echo $user_info->nickname; ?></td>
							<td><?php echo $user_info->user_email; ?></td>
							<td><?php echo ! empty( $user_capabilities ) ? $user_capabilities : $user_role; ?></td>
							<td>
								<a class="button qc_edit_user" href="<?php echo admin_url( 'admin.php?page=users&user=' . $user->ID ); ?>"><?php _e( 'Edit', 'qc_dashboard' ); ?> -</a>
								<?php if( ! is_super_admin( $user->ID ) ) { ?>
									<button class="button qc_remove_user" data-user-id="<?php echo $user->ID; ?>"><?php _e( 'Remove', 'qc_dashboard' ); ?> -</button> <span class="spinner"></span>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<h1><?php echo $title_form; ?></h1>
			<?php if( ! empty( $error ) ){ ?>
				<div class="qc_error">
					<p><strong><?php echo $error; ?></strong></p>
				</div>
			<?php }
			if( ! empty( $message ) ){ ?>
				<div class="qc_notice">
					<p><strong><?php echo $message; ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=users' ); ?>">
				<?php if( isset( $_GET['user'] ) && ! empty( $edit_user_info ) ){
					wp_nonce_field( 'edituser', 'qc_edit_user' ); ?>
					<input type="hidden" name="qc_user" value="<?php echo $edit_user_info->ID; ?>" />
				<?php } else {
					wp_nonce_field( 'adduser', 'qc_add_user' );
				}?>
				<table class="form-table qc-add-user">
					<tbody>
						<tr>
							<td>
								<label for="qc_first_name"><?php _e( 'First Name', 'qc_dashboard' ); ?></label><br />
								<input name="qc_first_name" id="qc_first_name" value="<?php echo isset( $_POST['qc_first_name'] ) ? $_POST['qc_first_name'] : ( ! empty( $edit_user_info ) ? $edit_user_info->first_name : '' ); ?>" class="regular-text" type="text" />
							</td>
							<td>
								<label for="qc_last_name"><?php _e( 'Surname', 'qc_dashboard' ); ?></label><br />
								<input name="qc_last_name" id="qc_last_name" value="<?php echo isset( $_POST['qc_last_name'] ) ? $_POST['qc_last_name'] : ( ! empty( $edit_user_info ) ? $edit_user_info->last_name : '' ); ?>" class="regular-text" type="text" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="qc_user_login"><?php _e( 'Username', 'qc_dashboard' ); ?></label><br />
								<input name="qc_user_login" id="qc_user_login" value="<?php echo isset( $_POST['qc_user_login'] ) ? $_POST['qc_user_login'] : ( ! empty( $edit_user_info ) ? $edit_user_info->user_login : '' ); ?>" class="regular-text" type="text" />
							</td>
							<td>
								<label for="qc_user_pass"><?php _e( 'Password', 'qc_dashboard' ); ?></label><br />
								<input name="qc_user_pass" id="qc_user_pass" value="<?php echo isset( $_POST['qc_user_pass'] ) ? $_POST['qc_user_pass'] : ''; ?>" class="regular-text" type="password" />
								<button type="button" class="button wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php _e( 'Hide password', 'qc_dashboard' ); ?>">
									<span class="qc-pass-hide">
										<span class="dashicons dashicons-hidden"></span>
										<span class="text"><?php _e( 'Hide', 'qc_dashboard' ); ?></span>
									</span>
									<span class="qc-pass-show active">
										<span class="dashicons dashicons-visibility"></span>
										<span class="text"><?php _e( 'Show', 'qc_dashboard' ); ?></span>
									</span>
								</button>
							</td>
						</tr>
						<tr>
							<td>
								<label for="qc_user_email"><?php _e( 'Email', 'qc_dashboard' ); ?></label><br />
								<input name="qc_user_email" id="qc_user_email" value="<?php echo isset( $_POST['qc_user_email'] ) ? $_POST['qc_user_email'] : ( ! empty( $edit_user_info ) ? $edit_user_info->user_email : '' ); ?>" class="regular-text" type="email" />
							</td>
							<td>
								<label for="qc_user_role"><?php _e( 'Role', 'qc_dashboard' ); ?></label><br />
								<select name="qc_user_role" id="qc_user_role" class="regular-text" >
									 <?php wp_dropdown_roles( isset( $_POST['qc_user_role'] ) ? $_POST['qc_user_role'] : ( ! empty( $edit_user_info ) ? $edit_user_info->roles[0] : 'subscriber' ) ); ?>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php echo $title_button; ?>" type="submit"></p>
			</form>
		</div>
	<?php }

	function billing(){
		global $title;
		$gocardless_logo_path = plugin_dir_url( __FILE__ ) . 'images/gocardless_logo.png';

		$direct_debit_logo_path = plugin_dir_url( __FILE__ ) . 'images/direct_debit_logo.png';
		?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
            <div class="qc-billing-block">
                <img src="<?php echo $gocardless_logo_path; ?>" alt="Go Cardless logo">
            </div>
            <div class="qc-billing-block">
                <img src="<?php echo $direct_debit_logo_path; ?>" alt="Direct Debit logo">
            </div>

            <div class="qc-billing-agreement">
                <p class="qc-billing-contacts">payments@pathwaymis.co.uk  |  +44 800 107 0722</p>
                <p class="qc-billing-agreement__descreption">By confirming you are agreeing to our Website Terms of Use. CoCardless uses personal
                    data as described in our Privacy Notice. We use analitics cookies.<br> Your payments are protected by the Direct Debit Guarantee.</p>
            </div>
		</div>
	<?php }

	function project_tracker(){

	}

	function edit_departments(){
		global $title, $quote_calculator_options;
		$errors = '';
		$qc_dashboard_department_options = get_option( 'qc_dashboard_department_options' );
		if( isset( $_POST['qc_edit_departments'] ) && wp_verify_nonce( $_POST['qc_edit_departments'], 'qc_edit_departments' ) ) {
			if( ! empty( $_POST['qc_department_current'] ) ){
				foreach( $_POST['qc_department_current'] as $key => $value ) {
					if( '' !== $value ) {
						$qc_dashboard_department_options[ $value ] = sanitize_text_field( $_POST['qc_department_new'][ $key ] );
					}
				}
			}
		 	update_option( 'qc_dashboard_department_options', $qc_dashboard_department_options );
		}


		$all_tabs = array(
			'departmens' => __( 'Departmens', 'qc_dashboard' )
		);
		$all_values = array( 'Design', 'Print Production', 'Jobs out', 'Delivery' );
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'departmens'; ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<?php if( ! empty( $errors ) ) { ?>
				<div id="setting-error-settings_updated" class="error settings-error">
					<?php foreach( $errors as $error )	{ ?>
						<p><strong><?php echo $error; ?></strong></p>
					<?php } ?>
				</div>
			<?php } else if( ! empty( $message ) ) { ?>
				<div id="setting-error-settings_updated" class="updated settings-error">
					<p><strong><?php echo $message; ?></strong></p>
				</div>
			<?php } ?>
			<ul class="quote-calculator-tabs">
				<?php foreach( $all_tabs as $key => $tab ) { ?>
					<li <?php echo $current_tab == $key ? 'class="active"' : '';?>><?php if( $current_tab != $key ) { ?><a href="<?php echo admin_url( 'admin.php?page=edit_departments&tab=' . $key ); ?>"><?php } ?><?php echo $tab; ?><?php if( $current_tab != $key ) { ?></a><?php } ?></li>
				<?php } ?>
			</ul>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=edit_departments&tab=' . $current_tab ); ?>" novalidate="novalidate">
				<input name="action" value="update" type="hidden">
				<?php wp_nonce_field( 'qc_edit_departments', 'qc_edit_departments' ); ?>
				<table class="wp-list-table widefat fixed striped edit_departments qc-custom-table">
					<thead>
						<tr>
							<th class="manage-column column-posts">
								<span><?php _e( 'Current Title', 'qc_dashboard' ); ?></span>
							</th>
							<th class="manage-column column-posts">
								<span><?php _e( 'New Title', 'qc_dashboard' ); ?></span>
							</th>
							<th class="manage-column column-posts action">
								<span><?php _e( 'Action', 'qc_dashboard' ); ?></span>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $all_values as $value ) {
							while( array_key_exists( $value, $qc_dashboard_department_options ) ) {
								if( '' !== $qc_dashboard_department_options[ $value ] ){
									$new_value = $qc_dashboard_department_options[ $value ];
									unset( $qc_dashboard_department_options[ $value ] );
									$value = $new_value;
								} else {
									unset( $qc_dashboard_department_options[ $value ] );
									break;
								}
							} ?>
							<tr>
								<td>
									<input name="qc_department_current[]" value="<?php echo $value; ?>" class="regular-text disabled" type="text" />
								</td>
								<td>
									<input name="qc_department_new[]" value="" class="regular-text" type="text" />
								</td>
								<td>
									<span class="hidden button button-primary button-add"><?php _e( 'Add', 'qc_dashboard' ); ?> +</span> <span class="button button-primary button-remove"><?php _e( 'Remove', 'qc_dashboard' ); ?> -</span>
								</td>
							</tr>
						<?php }
						if( ! empty( $qc_dashboard_department_options ) ){
							foreach( $qc_dashboard_department_options as $value ) { ?>
								<tr>
									<td>
										<input name="qc_department_current[]" value="<?php echo $value; ?>" class="regular-text disabled" type="text" />
									</td>
									<td>
										<input name="qc_department_new[]" value="" class="regular-text" type="text" />
									</td>
									<td>
										<span class="hidden button button-primary button-add"><?php _e( 'Add', 'qc_dashboard' ); ?> +</span> <span class="button button-primary button-remove"><?php _e( 'Remove', 'qc_dashboard' ); ?> -</span>
									</td>
								</tr>
							<?php }
						} ?>
						<!--<tr>
							<td>
								<input name="qc_department_current[]" value="" class="regular-text" type="text" />
							</td>
							<td>
								<input name="qc_department_new[]" value="" class="regular-text" type="text" />
							</td>
							<td>
								<span class="button button-primary button-add"><?php _e( 'Add', 'qc_dashboard' ); ?> +</span> <span class="hidden button button-primary button-remove"><?php _e( 'Remove', 'qc_dashboard' ); ?> -</span>
							</td>
						</tr>-->
					</tbody>
				</table>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>" type="submit"></p>
			</form>
		</div>
	<?php }

	function calendar_view(){

	}

	function view_options(){
		global $title;
		if( isset( $_POST['qc_save_view_options'] ) && wp_verify_nonce( $_POST['qc_save_view_options'], 'saveview' ) ) {
		 	$this->qc_dashboard_options['calendar_view_options'] = array(
				'day_view'			=> isset( $_POST['qc_day_view'] )			? $_POST['qc_day_view']			: array(),
				'format_view'		=> isset( $_POST['qc_format_view'] )	? $_POST['qc_format_view']	: array(),
			);
			update_option( 'qc_dashboard_options', $this->qc_dashboard_options );
		} ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=view_options' ); ?>">
				<?php wp_nonce_field( 'saveview', 'qc_save_view_options' ); ?>
				<table class="form-table qc_view_options">
					<tbody>
						<tr>
							<td>
								<label for="qc_day_view_monday">
									<input name="qc_day_view[]" id="qc_day_view_monday" value="monday" <?php checked( in_array( 'monday', $this->qc_dashboard_options['calendar_view_options']['day_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Monday', 'qc_dashboard' ); ?>
								</label>
							</td>
							<td>
								<label for="qc_format_view_month">
									<input name="qc_format_view[]" id="qc_format_view_month" value="month" <?php checked( in_array( 'month', $this->qc_dashboard_options['calendar_view_options']['format_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Month View', 'qc_dashboard' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td>
								<label for="qc_day_view_tuesday">
									<input name="qc_day_view[]" id="qc_day_view_tuesday" value="tuesday" <?php checked( in_array( 'tuesday', $this->qc_dashboard_options['calendar_view_options']['day_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Tuesday', 'qc_dashboard' ); ?>
								</label>
							</td>
							<td>
								<label for="qc_format_view_week">
									<input name="qc_format_view[]" id="qc_format_view_week" value="basicWeek" <?php checked( in_array( 'basicWeek', $this->qc_dashboard_options['calendar_view_options']['format_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Week View', 'qc_dashboard' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td>
								<label for="qc_day_view_wednesday">
									<input name="qc_day_view[]" id="qc_day_view_wednesday" value="wednesday" <?php checked( in_array( 'wednesday', $this->qc_dashboard_options['calendar_view_options']['day_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Wednesday', 'qc_dashboard' ); ?>
								</label>
							</td>
							<td>
								<label for="qc_format_view_day">
									<input name="qc_format_view[]" id="qc_format_view_day" value="basicDay" <?php checked( in_array( 'basicDay', $this->qc_dashboard_options['calendar_view_options']['format_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Day View', 'qc_dashboard' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="qc_day_view_thursday">
									<input name="qc_day_view[]" id="qc_day_view_thursday" value="thursday" <?php checked( in_array( 'thursday', $this->qc_dashboard_options['calendar_view_options']['day_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Thursday', 'qc_dashboard' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="qc_day_view_friday">
									<input name="qc_day_view[]" id="qc_day_view_friday" value="friday" <?php checked( in_array( 'friday', $this->qc_dashboard_options['calendar_view_options']['day_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Friday', 'qc_dashboard' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="qc_day_view_saturday">
									<input name="qc_day_view[]" id="qc_day_view_saturday" value="saturday" <?php checked( in_array( 'saturday', $this->qc_dashboard_options['calendar_view_options']['day_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Saturday', 'qc_dashboard' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="qc_day_view_sunday">
									<input name="qc_day_view[]" id="qc_day_view_sunday" value="sunday" <?php checked( in_array( 'sunday', $this->qc_dashboard_options['calendar_view_options']['day_view'] ), true ); ?> class="regular-text" type="checkbox" />
									<?php _e( 'Sunday', 'qc_dashboard' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'qc_dashboard' ); ?>" type="submit"></p>
			</form>
		</div>
	<?php }

	function colour_coding(){
		global $title;
		if( isset( $_POST['qc_save_colour_options'] ) && wp_verify_nonce( $_POST['qc_save_colour_options'], 'savecolour' ) ) {
		 	$this->qc_dashboard_options['calendar_color_options'] = array(
				'print_production'	=> sanitize_text_field( $_POST['qc_colour_print_production'] ),
				'jobs_out'					=> sanitize_text_field( $_POST['qc_colour_jobs_out'] ),
				'deliver'						=> sanitize_text_field( $_POST['qc_colour_deliver'] )
			);
			update_option( 'qc_dashboard_options', $this->qc_dashboard_options );
		} ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=colour_coding' ); ?>">
				<?php wp_nonce_field( 'savecolour', 'qc_save_colour_options' ); ?>
				<div class="qc_color_coding_block">
					<label><?php _e( 'Print Production', 'qc_dashboard'); ?></label>
					<input type="text" class="color-field-print" value="<?php echo $this->qc_dashboard_options['calendar_color_options']['print_production']; ?>" name="qc_colour_print_production" />
					<div class="clear"></div>
				</div>
				<div class="qc_color_coding_block">
					<label><?php _e( 'Jobs Out', 'qc_dashboard'); ?></label>
					<input type="text" class="color-field-job" value="<?php echo $this->qc_dashboard_options['calendar_color_options']['jobs_out']; ?>" name="qc_colour_jobs_out" />
					<div class="clear"></div>
				</div>
				<div class="qc_color_coding_block">
					<label><?php _e( 'Delivery', 'qc_dashboard'); ?></label>
					<input type="text" class="color-field-delivery" value="<?php echo $this->qc_dashboard_options['calendar_color_options']['deliver']; ?>" name="qc_colour_deliver" />
					<div class="clear"></div>
				</div>
				<!--<div class="qc_color_coding_block">
					<label><?php _e( 'Print Production', 'qc_dashboard'); ?></label>
					<input type="text" class="color-field-farb" value="#fff" />
					<div id="colorpicker"></div>
					<div class="clear"></div>
				</div>
				<div class="qc_color_coding_block">
					<label><?php _e( 'Print Production', 'qc_dashboard'); ?></label>
					<input type="text" class="color-field-picker" value="#fff" />
					<div class="clear"></div>
				</div>-->
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'qc_dashboard' ); ?>" type="submit"></p>
			</form>
		</div>
	<?php }

	function reorder_admin_menu( $__return_true ) {
		return array(
			'custom_dashboard',
			'company_profile',
			'project_tracker',
			'calendar_view',
			'quote_calculator',
			'index.php'
		);
	}

	function admin_head(){
		if( ! empty( $this->qc_dashboard_options ) && ( '#9d3292' !== $this->qc_dashboard_options['dashboard_details']['titles_color'] || '#9d3292' !== $this->qc_dashboard_options['dashboard_details']['buttons_color'] ) ) { ?>
			<style>
				<?php if( '#9d3292' !== $this->qc_dashboard_options['dashboard_details']['titles_color'] ) { ?>
					.qc-custom-page .wrap h1,
					#wpbody-content .wrap h1,
					#adminmenu li.current a.menu-top,
					#adminmenu a.menu-top,
					#collapse-button,
					#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
					#adminmenu .wp-has-current-submenu .wp-submenu,
					#adminmenu li.opensub > a.menu-top,
					#adminmenu li > a.menu-top:focus,
                    .qc-deadline-week-date,
                    .qc-stat-month-date {
						color: <?php echo $this->qc_dashboard_options['dashboard_details']['titles_color']; ?>;
					}
				<?php }
				if( '#9d3292' !== $this->qc_dashboard_options['dashboard_details']['buttons_color'] ) { ?>
					.qc-custom-page .wrap .button,
                    .qc-dashboard-stat-month:nth-child(4n+1) .qc-dashboard-stat-month-number:before,
                    .qc-dashboard-stat-month:nth-child(4n+2) .qc-dashboard-stat-month-number:before,
                    .qc-dashboard-stat-month:nth-child(4n+3) .qc-dashboard-stat-month-number:before,
                    .qc-dashboard-stat-month:nth-child(4n) .qc-dashboard-stat-month-number:before {
						border-color: <?php echo $this->qc_dashboard_options['dashboard_details']['buttons_color']; ?>;
						background-color: <?php echo $this->qc_dashboard_options['dashboard_details']['buttons_color']; ?>;
					}
                    .qc-dashboard-stat-month:nth-child(4n+1) .qc-dashboard-stat-month-number:before {
                        opacity: 0.3;
                    }
                    .qc-dashboard-stat-month:nth-child(4n+2) .qc-dashboard-stat-month-number:before {
                        opacity: 0.5;
                    }
                    .qc-dashboard-stat-month:nth-child(4n+3) .qc-dashboard-stat-month-number:before {
                        opacity: 0.7;
                    }
                    .qc-dashboard-stat-month:nth-child(4n) .qc-dashboard-stat-month-number:before {
                        opacity: 0.9;
                    }
					.qc-custom-page .wrap .striped thead tr {
						border-color: <?php echo $this->qc_dashboard_options['dashboard_details']['buttons_color']; ?>;
					}
				<?php } ?>
			</style>
		<?php }
		if( is_user_logged_in() && ! current_user_can( 'activate_plugins' ) ) { ?>
			<style type="text/css" media="all">
				#wpadminbar {
					display: none !important;
				}
				html.wp-toolbar {
						padding-top: 0;
						box-sizing: border-box;
				}
			</style>
		<?php }
	}

	function add_admin_body_class( $classes ) {
    return $classes . ' qc-custom-page';
	}

	function in_admin_header(){
		global $current_user;
		$logo_url = '' !== $this->qc_dashboard_options['dashboard_details']['logo'] ? $this->qc_dashboard_options['dashboard_details']['logo'] : plugins_url( 'images/Pathway_Logo.jpg', __FILE__ ); ?>
		<div class="qc_admin_menu">
			<div class="qc_admin_logo">
				<img src="<?php echo $logo_url; ?>" />
			</div>
			<ul>
				<li><a href="<?php bloginfo( 'url' ); ?>"><?php _e( 'Project Tracker', 'qc_dashboard' ); ?></a></li>
				<li><a href="<?php bloginfo( 'url' ); ?>/calendar-view/"><?php _e( 'Calendar View', 'qc_dashboard' ); ?></a></li>
				<li><a href="<?php bloginfo( 'url' ); ?>/dashboard/"><?php _e( 'Quote Calculator', 'qc_dashboard' ); ?></a></li>
				<li class="last"><?php _e( 'Howdy', 'qc_dashboard' ); ?>, <a href=""><?php echo $current_user->user_login; ?></a></li>
			</ul>
		</div>
	<?php }

	function in_admin_footer(){ ?>
		<div class="qc-admin-footer-logo">
			<img src="<?php echo plugins_url( 'images/Pathway_Logo.jpg', __FILE__ ); ?>" />
		</div>
		<div class="qc-admin-footer">
			<ul>
				<li><a href="#"><?php _e( 'Contact', 'qc_dashboard' ); ?></a></li>
				<li><a href="#"><?php _e( 'Terms & Conditions', 'qc_dashboard' ); ?></a></li>
				<li><a href="#"><?php _e( 'Privacy Policy', 'qc_dashboard' ); ?></a></li>
				<li><a href="#"><?php _e( 'Cookie Policy', 'qc_dashboard' ); ?></a></li>
			</ul>
		</div>
	<?php }

	function remove_user(){
		check_ajax_referer( 'qc_ajax_nonce', 'security' );
		if( isset( $_POST['user'] ) && false != get_userdata( $_POST['user'] ) ) {
			if( is_multisite() ){
				remove_user_from_blog( $_POST['user'], get_current_blog_id() );
			} else {
				wp_delete_user( $_POST['user'] );
			}
			echo '1';
			wp_die();
		}
	}

}

// instantiate plugin's class
new QS_Dashboard();
//https://torquemag.io/2016/08/customize-wordpress-backend-clients/
