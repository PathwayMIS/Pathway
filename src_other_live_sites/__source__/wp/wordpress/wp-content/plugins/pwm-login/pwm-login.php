<?php
/**
 * Plugin Name: Pathwaymis Login
 * Plugin URI:
 * Description: Pathwaymis Login
 * Version: 1.0
 * Author:
 * Author URI:
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

class PWM_Login {
	private $plugin_info;

	public function __construct() {
		if ( ! function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$this->plugin_info = get_plugin_data( __FILE__ );

		$this->hooks();
	}

	public function plugins_loaded() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( is_plugin_active( 'project-tracker-v1/project-tracker.php' ) && class_exists( 'Clientside_Setup' ) ) {
			remove_action( 'login_enqueue_scripts', array( 'Clientside_Setup', 'action_enqueue_login_styles' ) );
			remove_action( 'login_enqueue_scripts', array( 'Clientside_Setup', 'action_enqueue_login_scripts' ) );
			remove_filter( 'login_body_class', array( 'Clientside_Setup', 'filter_add_body_classes' ) );
			remove_filter( 'login_headerurl', array( 'Clientside', 'filter_change_login_logo_link' ) );
			remove_filter( 'login_headertitle', array( 'Clientside', 'filter_change_login_logo_title' ) );
			remove_action( 'login_head', array( 'Clientside', 'action_change_login_logo' ) );
			remove_filter( 'login_errors', array( 'Clientside', 'filter_login_errors' ) );
		}
	}

	public function login_body_class( $classes ) {
		$classes[] = 'pwm-login-body';
		return $classes;
	}

	public function login_enqueue_scripts() {
		wp_enqueue_style( 'pwm_login_styles', plugins_url( 'css/login-styles.css', __FILE__ ), $this->plugin_info['Version'] );
	}

	public function login_header() { ?>
		<div class="pwm-login-wrapper">
			<div class="pwm-login-header">
				<img src="<?php echo plugins_url( 'images/logo-login-top.jpg', __FILE__ ); ?>" alt="">
			</div>
			<div class="pwm-login-content">
				<div class="pwm-login-block">
					<p class="pwm-login-logo">
						<a href="<?php echo home_url(); ?>">
							<img src="<?php echo plugins_url( 'images/logo-login-form.jpg', __FILE__ ); ?>" alt="">
						</a>
					</p>
	<?php }

	public function login_footer() { ?>
				</div>
			</div>
			<div class="pwm-login-footer">
				<img src="<?php echo plugins_url( 'images/logo-login-bottom.jpg', __FILE__ ); ?>" alt="">
				<ul class="pwm-login-footer-menu">
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
			</div>
		</div>
	<?php }

	public function login_form() { ?>
		<p class="pwm-forgot-password"><a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Forgot Password?', 'pwm-sites' ); ?></a></p>
	<?php }

	private function hooks() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );
		add_action( 'login_enqueue_scripts', array( $this, 'login_enqueue_scripts' ) );
		add_action( 'login_body_class', array( $this, 'login_body_class' ) );
		add_action( 'login_header', array( $this, 'login_header' ) );
		add_action( 'login_footer', array( $this, 'login_footer' ) );
		add_action( 'login_form', array( $this, 'login_form' ) );
	}
}

new PWM_Login();



