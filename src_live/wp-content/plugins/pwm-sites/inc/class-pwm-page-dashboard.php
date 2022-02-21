<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'PWM_Page_Dashboard' ) ) {
	class PWM_Page_Dashboard extends PWM_Page {
		public $page_slug = 'pwm_dashboard';

		public function admin_menu() {
			add_menu_page( __( 'Dashboard', 'pwm-sites' ), __( 'Dashboard', 'pwm-sites' ), 'manage_options', 'pwm_dashboard', array( $this, 'page_dashboard' ), array(), '1.0' );
			
		}

		public function admin_init() {
			ob_start();
		}

		public function page_dashboard() {
			
		}

		public function hooks() {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}
	}
}