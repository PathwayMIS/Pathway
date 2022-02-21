<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'PWM_Page_Sites' ) ) {
	class PWM_Page_Sites extends PWM_Page {
		public $page_slug = 'pwm_sites';

		public function admin_menu() {
			add_menu_page( __( 'Sites', 'pwm-sites' ), __( 'Sites', 'pwm-sites' ), 'manage_options', 'pwm_sites', array( $this, 'page_sites' ), array(), '1.1' );
			add_submenu_page( 'pwm_sites', __( 'Site List', 'pwm-sites' ), __( 'List', 'pwm-sites' ), 'manage_options', 'pwm_sites', array( $this, 'page_sites' ) );
		}

		public function admin_init() {
			ob_start();
		}

		public function page_sites() {
		    global $wpdb;
			require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-sites-list-table.php' );

			$message = '';
			$list_table = new PWM_Sites_List_Table();
			if ( isset( $_GET['site_action'] ) && 'delete' == $_GET['site_action'] && isset( $_GET['site_id'] ) ) {
			    $subdomain = $wpdb->get_results( 'SELECT subdomain FROM `' . $wpdb->base_prefix . 'pwm_sites` WHERE id=' . $_GET['site_id'], ARRAY_A );
			    $message = "The subdomain " . $subdomain[0]['subdomain'] . " was deleted successfully";
			    $wpdb->delete( "{$wpdb->base_prefix}pwm_sites", array( 'id' => $_GET['site_id'] ) );
            }
			$list_table->prepare_items(); ?>

			<div class="wrap pwm-wrap pwm-wrap-sites">
				<h1 class="wp-heading-inline"><?php _e( 'Sites', 'pwm-sites' ); ?></h1>
                <?php if ( ! empty( $message ) ) { ?>
                <div class="pwm-notice pwm-notice-updated">
                    <p>
                        <strong><?php echo $message; ?></strong>
                    </p>
                </div>
                <?php } ?>
				<div class="pwm-list-table-wrapper">
					<form class="pwm-list-table-form" method="get">
						<?php $list_table->display(); ?>
					</form>
				</div>
			</div>
		<?php }

		public function hooks() {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}
	}
}