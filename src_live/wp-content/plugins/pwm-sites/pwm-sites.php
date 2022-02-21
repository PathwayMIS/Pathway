<?php
/**
 * Plugin Name: Pathwaymis Sites
 * Plugin URI:
 * Description: Pathwaymis Sites
 * Version: 1.1
 * Author:
 * Author URI:
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

define( 'PWM_FILE', __FILE__ );
define( 'PWM_HTTP_PROTOCOL', 'https://' );
define( 'PWM_DOMAIN', 'pathwaymis.co.uk' );
define( 'PWM_SITE_FOLDER', '__sites__' );
define( 'PWM_SOURCE_FOLDER', '__source__' );
define( 'PWM_SITE_DIR', dirname( ABSPATH ) . '/' . PWM_SITE_FOLDER . '/' );
define( 'PWM_SOURCE_DIR', dirname( ABSPATH ) . '/' . PWM_SOURCE_FOLDER . '/' );
define( 'PWM_TEMP_DIR', dirname( ABSPATH ) . '/' . PWM_SOURCE_FOLDER . '/temp/' );
define( 'PWM_DB_HOST', '212.48.95.62');

class PWM_Sites {
	private $plugin_info;
	private $plugin_pages = array( 'pwm_dashboard', 'pwm_site', 'pwm_sites' );

	public function __construct() {
		if ( ! function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$this->plugin_info = get_plugin_data( PWM_FILE );

		$this->hooks();
		$this->includes();
		$this->pages();
	}

	public function activation() {
		$this->create_tables();
		$this->create_folders();
	}

	private function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->base_prefix . 'pwm_sites` (
			`id` int(10) NOT NULL AUTO_INCREMENT,
			`subdomain_url` VARCHAR(100) NOT NULL,
			`subdomain` varchar(64) NOT NULL,
			`username` varchar(64) NOT NULL,
			`password` varchar(64) NOT NULL,
			`email` varchar(64) NOT NULL,
			`date_submitted` datetime NOT NULL,
			`db_name` varchar(64) NOT NULL,
			`db_username` varchar(64) NOT NULL,
			`db_password` varchar(64) NOT NULL,
			`db_host` varchar(64) NOT NULL,
			`display_name` varchar(100) NOT NULL,
			`company_name` varchar(100) NOT NULL,
			`company_phone` varchar(64) NOT NULL,
			`company_email` varchar(64) NOT NULL,
			`company_address_line1` varchar(100) NOT NULL,
			`company_address_line2` varchar(100) NOT NULL,
			`company_address_line3` VARCHAR(100) NOT NULL,
			`company_address_city` varchar(64) NOT NULL,
			`company_address_location` varchar(100) NOT NULL,
			`company_address_postal_code` varchar(64) NOT NULL,
			`registered_address_line1` varchar(100) NOT NULL,
			`registered_address_line2` varchar(100) NOT NULL,
			`registered_address_line3` VARCHAR(100) NOT NULL,
			`registered_address_city` varchar(64) NOT NULL,
			`registered_address_location` varchar(100) NOT NULL,
			`registered_address_postal_code` varchar(64) NOT NULL,
			`registration_number` varchar(64) NOT NULL,
			`vat_number` varchar(64) NOT NULL,
			PRIMARY KEY (`id`)
		) ' . $charset_collate;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $sql );
	}

	private function create_folders() {
		global $wp_filesystem;

		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		WP_Filesystem();

		if ( ! $wp_filesystem->exists( PWM_SOURCE_DIR ) ) {
			$wp_filesystem->mkdir( PWM_SOURCE_DIR );
		}

		if ( ! $wp_filesystem->exists( PWM_TEMP_DIR ) ) {
			$wp_filesystem->mkdir( PWM_TEMP_DIR );
		}

		if ( ! $wp_filesystem->exists( PWM_SITE_DIR ) ) {
			$wp_filesystem->mkdir( PWM_SITE_DIR );
		}
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'pwm_admin_styles', plugins_url( 'css/admin-styles.css', PWM_FILE ), $this->plugin_info['Version'] );
		wp_enqueue_style( 'pwm_admin_page_styles', plugins_url( 'css/admin-page-styles.css', PWM_FILE ), $this->plugin_info['Version'] );
		wp_enqueue_script( 'pwm_admin_script', plugins_url( 'js/admin-script.js', PWM_FILE ), array( 'jquery' ), $this->plugin_info['Version'], true );
	}

	public function is_plugin_page( $page = '' ) {

		if ( ! empty( $page ) ) {
			$current_page = $page;
		} else {
			$current_page = ( isset( $_GET['page'] ) ) ? $_GET['page'] : '';
		}

		return ( in_array( $current_page, $this->plugin_pages ) );
	}

	public function admin_body_class( $classes ) {
		return $classes . 'pwm-sites-body';
	}

	public function admin_menu() {
		$this->remove_admin_menu();

		//add_menu_page( __( 'WP Dashboard', 'pwm-sites' ), __( 'WP Dashboard', 'pwm-sites' ), 'manage_options', admin_url(), false, array(), '1.2' );
		add_menu_page( __( 'Log Out', 'pwm-sites' ), __( 'Log Out', 'pwm-sites' ), 'manage_options', wp_nonce_url( '../wp-login.php?action=logout', 'log-out' ), false, array(), '1.2' );
	}

	private function remove_admin_menu() {
		global $menu;

		foreach ( $menu as $key => $value ) {
			if ( ! in_array( $value[2], $this->plugin_pages ) )
				unset( $menu[ $key ] );
		}
	}

	public function admin_header() {
		$current_user = wp_get_current_user(); ?>
		<div class="pwm-dashboard-header">
			<div class="pwm-logo-admin-top">
				<button class="pwm-mobile-menu-button">
					<span class="dashicons dashicons-menu"></span>
				</button>
				<img src="<?php echo plugins_url( 'images/logo-dashboard.jpg', PWM_FILE ); ?>" alt="">
			</div>
			<div class="pwm-user-greetings">
				<?php printf( '%s %s!', __( 'Hello', 'pwm-sites' ), $current_user->user_login ); ?>
			</div>
			<div class="clear"></div>
		</div>
	<?php }

	public function admin_footer() { ?>
		<div class="pwm-dashboard-footer">
			<div class="pwm-logo-admin-bottom">
				<img src="<?php echo plugins_url( 'images/logo-dashboard.jpg', PWM_FILE ); ?>" alt="">
			</div>
			<ul class="pwm-dashboard-footer-menu">
				<li>
					<a href="#"><?php _e( 'Contact', 'pwm-sites' ); ?></a>
				</li>
				<li>
					<a href="#"><?php _e( 'Terms & Conditions', 'pwm-sites' ); ?></a>
				</li>
				<li>
					<a href="#"><?php _e( 'Privacy Policy', 'pwm-sites' ); ?></a>
				</li>
				<li>
					<a href="#"><?php _e( 'Cookie Policy', 'pwm-sites' ); ?></a>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	<?php }


	public function plugins_loaded() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( is_plugin_active( 'project-tracker-v1/project-tracker.php' ) && class_exists( 'Clientside_Setup' ) && $this->is_plugin_page() ) {
			remove_action( 'admin_enqueue_scripts', array( 'Clientside_Setup', 'action_enqueue_admin_styles' ) );
			remove_action( 'admin_enqueue_scripts', array( 'Clientside_Setup', 'action_enqueue_admin_scripts' ) );
		}
	}

	private function hooks() {
		register_activation_hook( PWM_FILE, array( $this, 'activation' ) );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );

		if ( $this->is_plugin_page() ) {
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 9999 );
			add_action( 'admin_body_class', array( $this, 'admin_body_class' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action(	'admin_head', array( $this, 'admin_header' ) );
			add_action(	'admin_footer', array( $this, 'admin_footer' ) );
		}
	}

	private function includes() {
		require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-page.php' );
		require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-page-dashboard.php' );
		require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-page-sites.php' );
		require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-page-site.php' );
		require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-signup-page-site.php' );
	}

	private function pages() {
		if ( $this->is_plugin_page() ) {
			$page_dashboard = new PWM_Page_Dashboard;
		}

		$page_list = new PWM_Page_Sites;
		$page_site = new PWM_Page_Site;
		/* For signup page */
		if ( ! is_admin() && strstr( $_SERVER['REQUEST_URI'], 'signup' ) ) {
			new PWM_Signup_Page_Site;
		}
	}
}

new PWM_Sites();