<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'PWM_Page' ) ) {
	abstract class PWM_Page {
		public $page_slug;

		public function __construct() {
			$this->_hooks();

			if ( isset( $_GET['page'] ) && $this->page_slug == $_GET['page'] ) {
				$this->hooks();
				$this->includes();
			}
		}

		public function admin_menu() {}

		public function hooks() {}

		public function includes() {}

		private function _hooks() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}
	}
}