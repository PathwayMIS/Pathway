<?php
/*
Plugin Name: Quote Calculator
Plugin URI: 
Description: 
Author: 
Version: 0.7
Author URI: 
License: GPLv2 or later
*/

/*  © Copyright 2017

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//$quote_calculator_baseUrl = 'development';
$quote_calculator_baseUrl = 'production';

/*
* Function to initialize plugin.
*/
if ( ! function_exists( 'quote_calculator_install' ) ) {
	function quote_calculator_install(){
		$quote_calculator_version = 0.7;
		$quote_calculator_installed_version = get_option( 'quote_calculator_version' );
		if ( $quote_calculator_installed_version != $quote_calculator_version ) {
			quote_calculator_create_table();
		}
	}
}

/*
* Function to set up default options.
*/
if ( ! function_exists ( 'quote_calculator_create_table' ) ) {
	function quote_calculator_create_table(){
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_product_type` (
			`product_type_id` int(10) NOT NULL AUTO_INCREMENT,
			`product_type_title` varchar(255) NOT NULL,
			`product_type_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`product_type_id`)
		) ' . $charset_collate;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_paper_size` (
			`paper_size_id` int(10) NOT NULL AUTO_INCREMENT,
			`paper_size_title` varchar(255) NOT NULL,
			`paper_size_calculation` varchar(255) NOT NULL,
			`paper_size_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`paper_size_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_quantity` (
			`quantity_id` int(10) NOT NULL AUTO_INCREMENT,
			`quantity_count` int(10) NOT NULL,
			`quantity_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`quantity_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_impressions` (
			`impressions_id` int(10) NOT NULL AUTO_INCREMENT,
			`impressions_title` varchar(255) NOT NULL,
			`impressions_calculation` varchar(255) NOT NULL,
			`impressions_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`impressions_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_colour` (
			`colour_id` int(10) NOT NULL AUTO_INCREMENT,
			`colour_title` varchar(255) NOT NULL,
			`colour_price` decimal(20,3) NOT NULL DEFAULT "0.000",
			`colour_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`colour_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_page_count` (
			`page_count_id` int(10) NOT NULL AUTO_INCREMENT,
			`page_count_title` varchar(255) NOT NULL,
			`page_count_price` decimal(20,2) NOT NULL DEFAULT "0.00",
			`page_count_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`page_count_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_paper` (
			`paper_id` int(10) NOT NULL AUTO_INCREMENT,
			`paper_title` varchar(255) NOT NULL,
			`paper_weight` varchar(10) NOT NULL,
			`paper_format` varchar(2) NOT NULL,
			`paper_part` tinyint(4) NOT NULL,
			`paper_price` decimal(20,2) NOT NULL DEFAULT "0.00",
			`paper_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`paper_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_finishing` (
			`finishing_id` int(10) NOT NULL AUTO_INCREMENT,
			`finishing_title` varchar(255) NOT NULL,
			`finishing_format` varchar(2) NOT NULL,
			`finishing_price` decimal(20,2) NOT NULL DEFAULT "0.00",
			`finishing_category` tinyint(4) NOT NULL,
			PRIMARY KEY (`finishing_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_delivery` (
			`delivery_id` int(10) NOT NULL AUTO_INCREMENT,
			`delivery_title` varchar(255) NOT NULL,
			`delivery_price` decimal(20,2) NOT NULL DEFAULT "0.00",
			PRIMARY KEY (`delivery_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_customers` (
			`customers_id` int(10) NOT NULL AUTO_INCREMENT,
			`customers_qb_id` int(10) NOT NULL,
			`customers_given_name` varchar(100) NOT NULL,
			`customers_family_name` varchar(100) NOT NULL,
			`customers_company_name` varchar(100) NOT NULL,
			`customers_display_name` varchar(100) NOT NULL,
			`customers_phone` varchar(20) NOT NULL,
			`customers_email` varchar(50) NOT NULL,
			`customers_bill_addr` int(10) NOT NULL,
			`customers_ship_addr` int(10) NOT NULL,
			`customers_modification` tinyint(4) NOT NULL DEFAULT "0",
			PRIMARY KEY (`customers_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_customer_address` (
			`customer_address_id` int(10) NOT NULL AUTO_INCREMENT,
			`customer_address_type` tinyint(4) NOT NULL DEFAULT "1",
			`customer_address_line1` varchar(100) NOT NULL,
			`customer_address_line2` varchar(100) NULL,
			`customer_address_line3` varchar(100) NULL,
			`customer_address_city` varchar(100) NOT NULL,
			`customer_address_country` varchar(100) NULL,
			`customer_address_country_sub_division_code` varchar(10) NULL,
			`customer_address_postal_code` varchar(100) NULL,
			`customer_address_lat` varchar(15) NULL,
			`customer_address_long` varchar(15) NULL,
			PRIMARY KEY (`customer_address_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_estimates` (
			`estimates_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`estimates_reference_no` varchar(20) NOT NULL,
			`estimates_date` int(10) NOT NULL,
			`estimates_prepared` varchar(50) NOT NULL,
			`estimates_customer_id` int(10) unsigned NOT NULL,
			`estimates_customer_name` varchar(100) NOT NULL,
			`estimates_customer_email` varchar(50) NOT NULL,
			`estimates_customer_phone` varchar(20) NOT NULL,
			`estimates_customer_bill_address_id` int(10) unsigned NOT NULL,
			`estimates_customer_ship_address_id` int(10) unsigned NOT NULL,
			`estimates_deadline` int(10) NOT NULL,
			`estimates_delivery` varchar(20) NOT NULL,
			`estimates_notes` varchar(255) NOT NULL,
			`estimates_terms` varchar(40) NOT NULL,
			`estimates_status` varchar(15) NOT NULL,
			`estimates_cost` decimal(20,2) NOT NULL,
			`estimates_cost_incl_vat` decimal(20,2) NOT NULL,
			PRIMARY KEY (`estimates_id`)
		) ' . $charset_collate;
		dbDelta( $sql );


		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimates` CHANGE `estimates_notes` `estimates_delivery` VARCHAR(20) NOT NULL;';
		dbDelta( $sql );

		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimates` ADD `estimates_notes` varchar(255) NOT NULL AFTER `estimates_delivery`;';
		dbDelta( $sql );

		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimates` ADD `estimates_memo` varchar(255) NOT NULL AFTER `estimates_notes`;';
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_estimate_sections` (
			`estimate_section_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`estimate_section_category` tinyint(4) unsigned NOT NULL,
			`estimate_section_type` varchar(255) NOT NULL,
			`estimate_section_size` varchar(255) NOT NULL,
			`estimate_section_sided` varchar(255) NULL,
			`estimate_section_qty` TEXT unsigned NULL,
			`estimate_section_color` varchar(255) NULL,
			`estimate_section_cover` varchar(255) NULL,
			`estimate_section_paper_type` varchar(255) NULL,
			`estimate_section_weight` varchar(255) NULL,
			`estimate_section_format` varchar(255) NULL,
			`estimate_section_finishing` varchar(255) NULL,
			`estimate_section_suplier` varchar(255) NULL,
			`estimate_section_rate` decimal(20,2) NULL,
			`estimate_section_hours` decimal(20,1) NULL,
			`estimate_section_total` decimal(20,2) NULL,
			`estimate_section_overhead` int(10) unsigned NOT NULL,
			`estimate_section_profit` int(10) unsigned NOT NULL,
			`estimate_section_vat` int(10) unsigned NOT NULL,
			`estimate_section_cost` text NOT NULL,
			`estimate_section_cost_without_vat` text NOT NULL,
			`estimate_sections_estimate_id` int(10) unsigned NOT NULL,
			PRIMARY KEY (`estimate_section_id`)
		) ' . $charset_collate;
		dbDelta( $sql );
		

		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimate_sections` ADD `estimate_section_supplier_price` VARCHAR(255) NOT NULL AFTER `estimate_section_suplier`;';
		dbDelta( $sql );

		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimate_sections` ADD `estimate_section_cost_without_vat` decimal(20,2) NOT NULL AFTER `estimate_section_cost`;';
		dbDelta( $sql );

		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimate_sections` ADD `estimate_section_page_count` varchar(255) NULL AFTER `estimate_section_color`;';
		dbDelta( $sql );
		
		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimate_sections` ADD `estimate_section_orientation` CHAR( 10 ) NULL AFTER `estimate_section_page_count`;';
		dbDelta( $sql );

		$sql = 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_estimate_sections` ADD `estimate_section_title` VARCHAR( 255 ) NOT NULL AFTER `estimate_section_category`;';
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_estimates_api` (
			`estimates_api_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`estimates_api_object_id` int(10) NOT NULL,
			`estimates_id` int(10) NOT NULL,
			PRIMARY KEY (`estimates_api_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'quote_calculator_items` (
			`items_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`items_object_id` int(10) NOT NULL,
			`items_name` varchar(255) NOT NULL,
			`items_status` tinyint(1) NOT NULL,
			`items_income_account_ref` int(10) NOT NULL,
			`items_parent_ref` int(10) NOT NULL,
			PRIMARY KEY (`items_api_id`)
		) ' . $charset_collate;
		dbDelta( $sql );

	}
}

/*
* Function to add localization to the plugin.
*/
if ( ! function_exists( 'quote_calculator_loaded' ) ) {
	function quote_calculator_loaded() {
		/* Internationalization. */
		load_plugin_textdomain( 'quote_calculator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

if( ! function_exists( 'quote_calculator_widgets_init' ) ){
	function quote_calculator_widgets_init(){
		register_sidebar( array(
			'name'          => __( 'Quote Calculator Sidebar', 'quote_calculator' ),
			'id'            => 'quote-calculator-sidebar',
			'description'   => __( 'Widgets in this area will be shown on the all Quote Calculator Pages', 'quote_calculator' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );
	}
}

/*
* Function to init the plugin.
*/
if ( ! function_exists ( 'quote_calculator_init' ) ) {
	function quote_calculator_init() {
		global $wpdb, $quote_calculator_plugin_info, $quote_calculator_baseUrl;
		if( ! session_id() ){
			session_save_path( dirname( __FILE__) . '/temp' );
			session_start();
		}

		show_admin_bar( false );

		/*if ( ! wp_next_scheduled ( 'quote_calculator_add_user' ) ) {
			wp_schedule_event( time(), 'daily', 'quote_calculator_add_user' );
    }*/

		if ( empty( $quote_calculator_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$quote_calculator_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Function check if plugin is compatible with current WP version  */
		quote_calculator_version_check( plugin_basename( __FILE__ ), $quote_calculator_plugin_info, '3.8' );

		if ( ! is_admin() || isset( $_GET['page'] ) && ( $_GET['page'] == 'quote_calculator' || $_GET['page'] == 'quote_calculator_margins_vat' || $_GET['page'] == 'quote_calculator_sync' || $_GET['page'] == 'quote_calculator_oauth' || $_GET['page'] == 'quote_calculator_editor' ) ) {
			quote_calculator_default_options();
		}
	//echo '<pre>'; var_dump($_POST); die();
		register_nav_menu( 'quote-calculator-menu', __( 'Quote Calculator Menu', 'quote_calculator' ) );
		if( ! is_admin() && isset( $_POST['quote_calculator_save_form'] ) && isset( $_POST['quote_calculator_field'] ) && wp_verify_nonce( $_POST['quote_calculator_field'], 'quote_calculator_action' ) /*&& '91.204.62.147' == $_SERVER['REMOTE_ADDR']*/ ){
			$estimate_bill_address = array(
				'customer_address_line1' => trim( $_POST['quote_calculator_bill_address_1'] ),
				'customer_address_line2' => trim( $_POST['quote_calculator_bill_address_2'] ),
				'customer_address_line3' => trim( $_POST['quote_calculator_bill_address_3'] ),
				'customer_address_city' => trim( $_POST['quote_calculator_bill_city'] ),
				'customer_address_country' => trim( $_POST['quote_calculator_bill_country'] ),
				'customer_address_country_sub_division_code' => trim( $_POST['quote_calculator_bill_country_code'] ),
				'customer_address_postal_code' => trim( $_POST['quote_calculator_bill_post_code'] )
			);
			$estimate_ship_address = array(
				'customer_address_type'			=> 2,
				'customer_address_line1'		=> trim( $_POST['quote_calculator_ship_address_1'] ),
				'customer_address_line2'		=> trim( $_POST['quote_calculator_ship_address_2'] ),
				'customer_address_line3'		=> trim( $_POST['quote_calculator_ship_address_3'] ),
				'customer_address_city'			=> trim( $_POST['quote_calculator_ship_city'] ),
				'customer_address_country'	=> trim( $_POST['quote_calculator_ship_country'] ),
				'customer_address_country_sub_division_code'	=> trim( $_POST['quote_calculator_ship_country_code'] ),
				'customer_address_postal_code'								=> trim( $_POST['quote_calculator_ship_post_code'] )
			);
			if( isset( $_POST['quote_calculator_bill_address_id'] ) && '' == $_POST['quote_calculator_bill_address_id'] ){
				$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
				$wpdb->insert( 
					$table_name,
					$estimate_bill_address,
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)
				);
				$_POST['quote_calculator_bill_address_id'] = $wpdb->insert_id;
				$wpdb->insert( 
					$table_name,
					$estimate_ship_address,
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)
				);
				$_POST['quote_calculator_ship_address_id'] = $wpdb->insert_id;
				$table_name = $wpdb->prefix . 'quote_calculator_customers';
				$wpdb->insert( 
					$table_name,
					array(
						'customers_qb_id'					=> 0,
						'customers_given_name'		=> '',
						'customers_family_name'		=> '',
						'customers_company_name'	=> '',
						'customers_display_name'	=> wp_unslash( trim( $_POST['quote_calculator_name'] ) ),
						'customers_phone'					=> wp_unslash( trim( $_POST['quote_calculator_phone'] ) ),
						'customers_email'					=> wp_unslash( trim( $_POST['quote_calculator_email'] ) ),
						'customers_bill_addr'			=> $_POST['quote_calculator_bill_address_id'],
						'customers_ship_addr'			=> $_POST['quote_calculator_ship_address_id'],
						'customers_modification'	=> 1
					),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d'
					)
				);
				$_POST['quote_calculator_customer_id'] = $wpdb->insert_id;
			} else if( isset( $_POST['quote_calculator_bill_address_id'] ) && '' != $_POST['quote_calculator_bill_address_id'] ){
				$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
				$wpdb->update( 
					$table_name,
					$estimate_bill_address,
					array( 'customer_address_id' => $_POST['quote_calculator_bill_address_id'] ),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					),
					array( '%d' )
				);
				$wpdb->update( 
					$table_name,
					$estimate_ship_address,
					array( 'customer_address_id' => $_POST['quote_calculator_ship_address_id'] ),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					),
					array( '%d' )
				);
			}
			$estimate_data = array(
				'estimates_reference_no'							=> isset( $_POST['quote_calculator_reference_no'] ) ? trim( $_POST['quote_calculator_reference_no'] ) : '',
				'estimates_date'											=> strtotime( trim( $_POST['quote_calculator_date'] ) ),
				'estimates_prepared'									=> trim( $_POST['quote_calculator_prepared'] ),
				'estimates_customer_id'								=> trim( $_POST['quote_calculator_customer_id'] ),
				'estimates_customer_name'							=> trim( $_POST['quote_calculator_name'] ),
				'estimates_customer_email'						=> trim( $_POST['quote_calculator_email'] ),
				'estimates_customer_phone'						=> trim( $_POST['quote_calculator_phone'] ),
				'estimates_customer_bill_address_id'	=> trim( $_POST['quote_calculator_bill_address_id'] ),
				'estimates_customer_ship_address_id'	=> trim( $_POST['quote_calculator_ship_address_id'] ),
				'estimates_deadline'									=> strtotime( trim( $_POST['quote_calculator_deadline'] ) ),
				'estimates_delivery'									=> trim( $_POST['quote_calculator_delivery'] ),
				'estimates_notes'											=> trim( $_POST['quote_calculator_delivery_notes'] ),
				'estimates_memo'											=> trim( $_POST['quote_calculator_memo'] ),
				'estimates_terms'											=> trim( $_POST['quote_calculator_terms'] ),
				'estimates_status'										=> trim( $_POST['quote_calculator_status'] ),
				'estimates_cost'											=> trim( $_POST['quote_calculator_cost'] ),
				'estimates_cost_incl_vat'							=> trim( $_POST['quote_calculator_cost_incl_vat'] )
			);
			$table_name = $wpdb->prefix . 'quote_calculator_estimates';
			if( isset( $_POST['quote_calculator_estimates_id'] ) ){
				if( empty( $estimate_data['estimates_reference_no'] ) ){ 
					$estimate_data['estimates_reference_no'] = $_POST['quote_calculator_estimates_id'];
				}
				$wpdb->update( 
					$table_name,
					$estimate_data,
					array( 'estimates_id' => $_POST['quote_calculator_estimates_id'] ),
					array(
						'%s',
						'%d',
						'%s',
						'%d',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%f',
						'%f'
					),
					array( '%d' )
				);
				$current_estimate_id = $_POST['quote_calculator_estimates_id'];
			} else {
				$wpdb->insert(
					$table_name,
					$estimate_data,
					array(
						'%s',
						'%d',
						'%s',
						'%d',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%f',
						'%f'
					)
				);
				$current_estimate_id = $wpdb->insert_id;
			}
			if( isset( $_POST['quote_calculator_category'] ) && ! empty( $_POST['quote_calculator_category'] ) && ! empty( $current_estimate_id ) ){
				$table_name = $wpdb->prefix . 'quote_calculator_estimate_sections';
				foreach( $_POST['quote_calculator_category'] as $category => $estimate_section_array ){
					foreach( $estimate_section_array as $estimate_section ){
						$format = array();
						foreach( $estimate_section as $key => $value ){
							if( is_numeric( $estimate_section[ $key ] ) && is_int( $estimate_section[ $key ] + 0 ) ){ 
								$format[] = '%d';
							} elseif( is_numeric( $estimate_section[ $key ] ) && is_float( $estimate_section[ $key ] + 0 ) ){ 
								$format[] = '%f';
							} elseif( is_string( $estimate_section[ $key ] ) ){ 
								$format[] = '%s';
							} elseif( is_array( $estimate_section[ $key ] ) ){ 
								$format[] = '%s';
								$estimate_section[ $key ] = serialize( $estimate_section[ $key ] );
							}
						}
						$estimate_section['estimate_section_category'] = $category;
						$format[] = '%d';
						$estimate_section['estimate_sections_estimate_id'] = $current_estimate_id;
						$format[] = '%d';
						if( 3 == $estimate_section['estimate_section_category'] ){
							$estimate_section['estimate_section_qty'] = 1;
							$format[] = '%d';
						}
						//echo '<pre>'; var_dump($estimate_section, $format);
						if( ! isset( $estimate_section['estimate_section_id'] ) ){
							$wpdb->insert(
								$table_name,
								$estimate_section,
								$format
							);
						} else {
							$wpdb->update( 
								$table_name,
								$estimate_section,
								array( 'estimate_section_id' => $estimate_section['estimate_section_id'] ),
								$format,
								array( '%d' )
							);
						}
						//var_dump($wpdb->last_error); die();
					}
				}
			}
			if( isset( $_POST['quote_calculator_category_remove'] ) && ! empty( $_POST['quote_calculator_category'] ) && ! empty( $current_estimate_id ) ){
				$table_name = $wpdb->prefix . 'quote_calculator_estimate_sections';
				foreach( $_POST['quote_calculator_category_remove']['estimate_section_id'] as $estimate_section_remove_id ){
					$wpdb->delete( $table_name, array( 'estimate_section_id' => $estimate_section_remove_id ), array( '%d' ) );
				}
			}
			if( 'sales' == $estimate_data['estimates_status'] ) {
				$posts = get_posts( array( 'title' => $estimate_data['estimates_reference_no'], 'post_type' => 'projects' ) );
				if( empty( $posts ) ) {
					$project = array(
						'post_title'			=> $estimate_data['estimates_reference_no'],
						'post_content'		=> '',
						'post_status'		=> 'publish',
						'post_author'		=> 7,
						'comment_status'	=> 'closed',
						'ping_status'		=> 'closed',
						'post_type'			=> 'projects'
					);
					$project_id = wp_insert_post( $project );
					add_post_meta( $project_id, 'deadline', date( 'Ymd', $estimate_data['estimates_deadline'] ) );
					add_post_meta( $project_id, '_deadline', 'field_578647e195fdf' );
					add_post_meta( $project_id, 'client', $estimate_data['estimates_customer_name'] );
					add_post_meta( $project_id, '_client', 'field_578647289c450' );
					add_post_meta( $project_id, 'order_number', $estimate_data['estimates_reference_no'] );
					add_post_meta( $project_id, '_order_number', 'field_5786476b9c451' );
					add_post_meta( $project_id, 'notes', $estimate_data['estimates_notes'] );
					add_post_meta( $project_id, '_notes', 'field_5786488d95fe1' );
					add_post_meta( $project_id, 'project_stage', 'Design' );
					add_post_meta( $project_id, '_project_stage', 'field_57867067bce90' );
					add_post_meta( $project_id, 'design_stage', 1 );
					add_post_meta( $project_id, '_design_stage', 'field_57b5e9a7f7531' );
					add_post_meta( $project_id, 'reference', $estimate_data['estimates_memo'] );
					add_post_meta( $project_id, '_reference', 'field_57f239b08b12a' );
					add_post_meta( $project_id, 'artwork', '' );
					add_post_meta( $project_id, '_artwork', 'field_5786480295fe0' );
				}
			}
			wp_redirect( get_permalink() . $current_estimate_id  );
			exit;
		} else if( isset( $_POST['quote_calculator_save_form'] ) && '91.204.62.147' == $_SERVER['REMOTE_ADDR'] ){
			echo '<pre>';
			if( isset( $_POST['quote_calculator_category'] ) && ! empty( $_POST['quote_calculator_category'] ) ){
				$table_name = $wpdb->prefix . 'quote_calculator_estimate_sections';
				foreach( $_POST['quote_calculator_category'] as $category => $estimate_section_array ){
					foreach( $estimate_section_array as $estimate_section ){
						$format = array();
						foreach( $estimate_section as $key => $value ){
							if( is_numeric( $estimate_section[ $key ] ) && is_int( $estimate_section[ $key ] + 0 ) ){ 
								$format[] = '%d';
							} elseif( is_numeric( $estimate_section[ $key ] ) && is_float( $estimate_section[ $key ] + 0 ) ){ 
								$format[] = '%f';
							} elseif( is_string( $estimate_section[ $key ] ) ){ 
								$format[] = '%s';
							} elseif( is_array( $estimate_section[ $key ] ) ){ 
								$format[] = '%s';
								$estimate_section[ $key ] = serialize( $estimate_section[ $key ] );
							}
						}
						$estimate_section['estimate_section_category'] = $category;
						$format[] = '%d';
						$estimate_section['estimate_sections_estimate_id'] = $current_estimate_id;
						$format[] = '%d';
						if( 3 == $estimate_section['estimate_section_category'] ){
							$estimate_section['estimate_section_qty'] = 1;
							$format[] = '%d';
						}
						//echo '<pre>'; var_dump($estimate_section, $format);
						/*if( ! isset( $estimate_section['estimate_section_id'] ) ){
							$wpdb->insert(
								$table_name,
								$estimate_section,
								$format
							);
						} else {
							$wpdb->update( 
								$table_name,
								$estimate_section,
								array( 'estimate_section_id' => $estimate_section['estimate_section_id'] ),
								$format,
								array( '%d' )
							);
						}*/
						//var_dump($wpdb->last_error); 	die();
						var_dump($estimate_section);
					}
				}
			}

			 die();
		}

		if( ! is_admin() && isset( $_GET['_wp_nonce'] ) && isset( $_GET['quote_calculator_create_pdf'] ) && wp_verify_nonce( $_GET['_wp_nonce'], 'quote_calculator_create_pdf_action' ) ){
			quote_calculator_create_pdf();
		}
		if( ! is_admin() && isset( $_GET['_wp_nonce'] ) && isset( $_GET['quote_calculator_create_invoice'] ) && wp_verify_nonce( $_GET['_wp_nonce'], 'quote_calculator_create_invoice_action' ) ){
			quote_calculator_create_estimate();
		}
		if( ! is_admin() && isset( $_GET['_wp_nonce'] ) && isset( $_GET['quote_calculator_duplicate_estimate'] ) && wp_verify_nonce( $_GET['_wp_nonce'], 'quote_calculator_duplicate_estimate_action' ) ){
			$current_estimate_id = quote_calculator_duplicate_estimate();
			wp_redirect( get_permalink() . $current_estimate_id  );
			exit;
		}
		if( ! is_admin() && isset( $_GET['_wp_nonce'] ) && isset( $_GET['quote_calculator_delete_estimate'] ) && wp_verify_nonce( $_GET['_wp_nonce'], 'quote_calculator_delete_estimate_action' ) ){
			quote_calculator_delete_estimate();
			$page = get_page_by_title( 'dashboard' );
			wp_redirect( get_permalink( $page->ID ) );
			exit;
		}
	}
}

/*
* Function to check for compatibility of the current WP version.
*/
if ( ! function_exists ( 'quote_calculator_version_check' ) ) {
	function quote_calculator_version_check( $plugin, $quote_calculator_plugin_info, $require_wp ) {
		global $wp_version;
		if ( version_compare( $wp_version, $require_wp, "<" ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
				$admin_url = ( function_exists( 'get_admin_url' ) ) ? get_admin_url( null, 'plugins.php' ) : esc_url( '/wp-admin/plugins.php' );
				if ( ! $quote_calculator_plugin_info ){
					$quote_calculator_plugin_info = get_plugin_data( __FILE__, false );
				}
				wp_die( "<strong>" . $quote_calculator_plugin_info['Name'] . " </strong> " . __( 'requires', 'quote_calculator' ) . " <strong>WordPress " . $require_wp . "</strong> " . __( 'or higher, that is why it has been deactivated! Please upgrade WordPress and try again.', 'quote_calculator') . "<br /><br />" . __( 'Back to the WordPress', 'quote_calculator') . " <a href='" . $admin_url . "'>" . __( 'Plugins page', 'quote_calculator') . "</a> . " );
			}
		}
	}
}

/*
* Function to set up default options.
*/
if ( ! function_exists ( 'quote_calculator_default_options' ) ) {
	function quote_calculator_default_options() {
		global $wpmu, $quote_calculator_options, $quote_calculator_default_options, $quote_calculator_plugin_info;

		$quote_calculator_default_options = array(
			'plugin_option_version'						=> $quote_calculator_plugin_info['Version'],
			'consumer_key'										=> '',
			'consumer_secret'									=> '',
			'client_id'												=> '',
			'client_secret'										=> '',
			'design_hourly_rate'							=> '',
			'vat'															=> '',
			'overhead'												=> '',
			'profit'													=> '',
			'last_import'											=> '',
			'last_items_import'								=> '',
			'last_customers_export'						=> ''
		);
		if ( $wpmu == 1 ) {
			if ( ! get_site_option( 'quote_calculator_options' ) ){
				add_site_option( 'quote_calculator_options', $quote_calculator_default_options, '', 'yes' );
			}
			$quote_calculator_options = get_site_option( 'quote_calculator_options' );
		} else {
			if ( ! get_option( 'quote_calculator_options' ) ){
				add_option( 'quote_calculator_options', $quote_calculator_default_options, '', 'yes' );
			}
			$quote_calculator_options = get_option( 'quote_calculator_options' );
		}

		if ( ! isset( $quote_calculator_options['plugin_option_version'] ) || $quote_calculator_options['plugin_option_version'] != $quote_calculator_plugin_info['Version'] ) {
			$quote_calculator_options = array_merge( $quote_calculator_default_options, $quote_calculator_options );
			$quote_calculator_options['plugin_option_version'] = $quote_calculator_plugin_info['Version'];
			update_option( 'quote_calculator_options', $quote_calculator_options );
		}
	}
}

/*
* flush_rules() if our rules are not yet included
*/
function quote_calculator_flush_rules(){
	$rules = get_option( 'rewrite_rules' );
	if ( ! isset( $rules['^estimate/([0-9]+)/?'] ) ) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
}

/*
* Adding a new rule
*/
function quote_calculator_insert_rewrite_rules( $rules ) {
	$newrules = array();
	$newrules['^estimate/([0-9]+)/?'] = 'index.php?pagename=estimate&estimate_id=$matches[1]';
	return $newrules + $rules;
}

/*
* Adding the id var so that WP recognizes it
*/
function quote_calculator_insert_query_vars( $vars ) {
    array_push( $vars, 'estimate_id' );
    return $vars;
}

/*
* Function to add plugin version.
*/
if ( ! function_exists ( 'quote_calculator_admin_init' ) ) {
	function quote_calculator_admin_init() {
		global $quote_calculator_plugin_info;

		if ( ! $quote_calculator_plugin_info )
			$quote_calculator_plugin_info = get_plugin_data( __FILE__ );

	}
}

if ( ! function_exists ( 'quote_calculator_add_5_minutes' ) ) {
	function quote_calculator_add_5_minutes( $schedules ) {

	$schedules['5minutes'] = array(
			'interval' => 300,
			'display'  => __('5 minutes')
		);
		return $schedules;
	}
}

/*
* Function to display admin menu.
*/
if ( ! function_exists( 'quote_calculator_admin_menu' ) ) {
	function quote_calculator_admin_menu() {
		add_menu_page( 'Quote Calculator Settings', 'Quote Calculator', 'manage_options', 'quote_calculator', 'quote_calculator_admin_page' );
		add_submenu_page( 'quote_calculator', __( 'Quote Calculator Oauth', 'performpress_seo' ), NULL, 'manage_options', 'quote_calculator_oauth', 'quote_calculator_oauth' );
		add_submenu_page( 'quote_calculator', __( 'Sync with Quickbooks', 'performpress_seo' ), __( 'Sync with Quickbooks', 'performpress_seo' ), 'manage_options', 'quote_calculator_sync', 'quote_calculator_sync' );
		add_submenu_page( 'quote_calculator', __( 'Add Clients', 'quote_calculator' ), __( 'Add Clients', 'quote_calculator' ), 'manage_options', 'quote_calculator_add_clients', 'quote_calculator_customer_operation' );
		add_submenu_page( 'quote_calculator', __( 'Client List', 'quote_calculator' ), __( 'Client List', 'quote_calculator' ), 'manage_options', 'quote_calculator_client_list', 'quote_calculator_customers' );
		add_submenu_page( 'quote_calculator', __( 'Margins & VAT', 'performpress_seo' ), __( 'Margins & VAT', 'performpress_seo' ), 'manage_options', 'quote_calculator_margins_vat', 'quote_calculator_margins_vat' );
		add_submenu_page( 'quote_calculator', __( 'Design', 'quote_calculator' ), __( 'Design', 'quote_calculator' ), 'manage_options', 'quote_calculator_design', 'quote_calculator_design' );
		add_submenu_page( 'quote_calculator', __( 'Digital Printing', 'quote_calculator' ), __( 'Digital Printing', 'quote_calculator' ), 'manage_options', 'quote_calculator_digital_page', 'quote_calculator_digital_printing' );
		add_submenu_page( 'quote_calculator', __( 'Jobs Out', 'quote_calculator' ), __( 'Jobs Out', 'quote_calculator' ), 'manage_options', 'quote_calculator_jobs_out', 'quote_calculator_jobs_out' );
		add_submenu_page( 'quote_calculator', __( 'Wide Format', 'quote_calculator' ), __( 'Wide Format', 'quote_calculator' ), 'manage_options', 'quote_calculator_wide_format', 'quote_calculator_wide_format' );
		add_submenu_page( 'quote_calculator', __( 'Delivery Options', 'quote_calculator' ), __( 'Delivery Options', 'quote_calculator' ), 'manage_options', 'quote_calculator_delivery', 'quote_calculator_delivery_options' );
		global $submenu;
		unset( $submenu['quote_calculator'][0] );
	}
}

require_once( plugin_dir_path( __FILE__ ) . 'quickbooks_oauth/quickbooks-v3-php-sdk-master/src/config.php' );

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

/*
* Function to display plugins admin page.
*/
if ( ! function_exists( 'quote_calculator_admin_page' ) ) {
	function quote_calculator_admin_page() { 
		global $title, $quote_calculator_options, $quote_calculator_baseUrl; 
		if( isset( $_GET['quote_calculator_disconnect'] ) && wp_verify_nonce( $_GET['quote_calculator_disconnect'], 'quote_calculator_disconnect' ) ){
			if( isset( $_SESSION['code'] ) ){
				unset( $_SESSION['code'] );
				unset( $_SESSION['realmId'] );
				unset( $_SESSION['accessToken'] );
			}
			if( isset( $quote_calculator_options['code'] ) ) {
				unset( $quote_calculator_options['code'] );
				unset( $quote_calculator_options['realmId'] );
				unset( $quote_calculator_options['accessToken'] );
			}

			update_option( 'quote_calculator_options', $quote_calculator_options );
		}
		if( isset( $_POST['quote_calculator_admin_page'] ) && wp_verify_nonce( $_POST['quote_calculator_admin_page'], 'quote_calculator_admin_page' ) ){
			$update_flag	= false;
			$errors				= array();
			/*if( 5 < strlen( $_POST['consumer_key'] ) ){
				if( $quote_calculator_options['consumer_key'] != $_POST['consumer_key'] ){
					$quote_calculator_options['consumer_key'] = trim( $_POST['consumer_key'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Consumer Key before you can run the import', 'quote_calculator' );
			}
			if( 5 < strlen( $_POST['consumer_secret'] ) ){
				if( $quote_calculator_options['consumer_secret'] != $_POST['consumer_secret'] ){
					$quote_calculator_options['consumer_secret'] = trim( $_POST['consumer_secret'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Consumer Secret before you can run the import', 'quote_calculator' );
			}*/
			if( 5 < strlen( $_POST['client_id'] ) ){
				if( $quote_calculator_options['client_id'] != $_POST['client_id'] ){
					$quote_calculator_options['client_id'] = trim( $_POST['client_id'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Client ID before you can run the import', 'quote_calculator' );
			}
			if( 5 < strlen( $_POST['client_secret'] ) ){
				if( $quote_calculator_options['client_secret'] != $_POST['client_secret'] ){
					$quote_calculator_options['client_secret'] = trim( $_POST['client_secret'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Client Secret before you can run the import', 'quote_calculator' );
			}
			if( is_numeric( $_POST['design_hourly_rate'] ) && $_POST['design_hourly_rate'] > 0 ){
				if( $quote_calculator_options['design_hourly_rate'] != $_POST['design_hourly_rate'] ){
					$quote_calculator_options['design_hourly_rate'] = trim( $_POST['design_hourly_rate'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Hourly Rate to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
			}
			if( is_numeric( $_POST['vat'] ) && $_POST['vat'] > 0 ){
				if( $quote_calculator_options['vat'] != $_POST['vat'] ){
					$quote_calculator_options['vat'] = trim( $_POST['vat'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the VAT to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
			}
			if( is_numeric( $_POST['overhead'] ) && $_POST['overhead'] > 0 ){
				if( $quote_calculator_options['overhead'] != $_POST['overhead'] ){
					$quote_calculator_options['overhead'] = trim( $_POST['overhead'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Overhead to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
			}
			if( is_numeric( $_POST['profit'] ) && $_POST['profit'] > 0 ){
				if( $quote_calculator_options['profit'] != $_POST['profit'] ){
					$quote_calculator_options['profit'] = trim( $_POST['profit'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Profit Margin to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
			}

			/*if( 5 < strlen( $_POST['digital_finishing_calculation'] ) ){
				if( $quote_calculator_options['digital_finishing_calculation'] != $_POST['digital_finishing_calculation'] ){
					$quote_calculator_options['digital_finishing_calculation'] = trim( $_POST['digital_finishing_calculation'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set Calculation for the Digital Finishing', 'quote_calculator' );
			}*/
			if( $update_flag ) {
				$message = __( 'Settings saved.', 'quote_calculator' );
				/*if( isset( $_SESSION['token'] ) ){
					unset( $_SESSION['token'] );
				}
				if( isset( $quote_calculator_options['token'] ) ) {
					unset( $quote_calculator_options['token'] );
					unset( $quote_calculator_options['realmId'] );
					unset( $quote_calculator_options['dataSource'] );
				}*/
				if( isset( $_SESSION['code'] ) ){
					unset( $_SESSION['code'] );
					unset( $_SESSION['realmId'] );
					unset( $_SESSION['accessToken'] );
				}
				if( isset( $quote_calculator_options['code'] ) ) {
					unset( $quote_calculator_options['code'] );
					unset( $quote_calculator_options['realmId'] );
					unset( $quote_calculator_options['accessToken'] );
				}

				update_option( 'quote_calculator_options', $quote_calculator_options );
			}
		} ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<?php if( ! empty( $errors ) ) { ?>
				<div id="setting-error-settings_updated" class="error settings-error"> 
					<?php foreach( $errors as $error )	{ ?>
						<p><strong><?php echo $error; ?></strong></p>
					<?php } ?>
				</div>
			<?php } 
			if( ! empty( $message ) ) { ?>
				<div id="setting-error-settings_updated" class="updated settings-error"> 
					<p><strong><?php echo $message; ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=quote_calculator_sync' ); ?>" novalidate="novalidate">
				<input name="action" value="update" type="hidden">
				<?php wp_nonce_field( 'quote_calculator_admin_page', 'quote_calculator_admin_page' ); ?>
				<table class="form-table">
					<tbody>
						<!--<tr>
							<th scope="row"><label for="consumer_key"><?php _e( 'Quickbooks Consumer Key', 'quote_calculator' ); ?></label></th>
							<td><input name="consumer_key" id="consumer_key" value="<?php echo $quote_calculator_options['consumer_key']; ?>" class="regular-text" type="text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="consumer_secret"><?php _e( 'Quickbooks Consumer Secret', 'quote_calculator' ); ?></label></th>
							<td><input name="consumer_secret" id="consumer_secret" value="<?php echo $quote_calculator_options['consumer_secret']; ?>" class="regular-text" type="text"></td>
						</tr>-->
						<tr>
							<th scope="row"><label for="client_id"><?php _e( 'Quickbooks Client ID', 'quote_calculator' ); ?></label></th>
							<td><input name="client_id" id="client_id" value="<?php echo $quote_calculator_options['client_id']; ?>" class="regular-text" type="text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="client_secret"><?php _e( 'Quickbooks Client Secret', 'quote_calculator' ); ?></label></th>
							<td><input name="client_secret" id="client_secret" value="<?php echo $quote_calculator_options['client_secret']; ?>" class="regular-text" type="text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="design_hourly_rate"><?php _e( 'Design Hourly Rate', 'quote_calculator' ); ?></label></th>
							<td><input name="design_hourly_rate" id="design_hourly_rate" value="<?php echo $quote_calculator_options['design_hourly_rate']; ?>" class="regular-text" type="text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="vat"><?php _e( 'VAT', 'quote_calculator' ); ?></label></th>
							<td><input name="vat" id="vat" value="<?php echo $quote_calculator_options['vat']; ?>" class="regular-text" type="text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="overhead"><?php _e( 'Overhead', 'quote_calculator' ); ?></label></th>
							<td><input name="overhead" id="overhead" value="<?php echo $quote_calculator_options['overhead']; ?>" class="regular-text" type="text"></td>
						</tr>
						<tr>
							<th scope="row"><label for="profit"><?php _e( 'Profit Margin', 'quote_calculator' ); ?></label></th>
							<td><input name="profit" id="profit" value="<?php echo $quote_calculator_options['profit']; ?>" class="regular-text" type="text"></td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>" type="submit"></p>
				<?php if( ! isset( $_SESSION['code'] ) && ! empty( $quote_calculator_options['client_id'] ) && ! empty( $quote_calculator_options['client_secret'] ) ){
					$dataService = DataService::Configure(array(
						'auth_mode' => 'oauth2',
						'ClientID' => $quote_calculator_options['client_id'],
						'ClientSecret' => $quote_calculator_options['client_secret'],
						'RedirectURI' => admin_url( 'admin.php?page=quote_calculator_oauth' ),
						'scope' => "com.intuit.quickbooks.accounting",
						'baseUrl' => $quote_calculator_baseUrl
					));

					if( ! isset( $_GET['code'] ) && ! isset( $_SESSION['code'] )  ){
						$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

						$url = $OAuth2LoginHelper->getAuthorizationCodeURL();
					} ?>
					<p><a href="<?php echo $url; ?>">Connect</a></p>
				<?php } else if( ! empty( $quote_calculator_options['client_id'] ) && ! empty( $quote_calculator_options['client_secret'] ) ) { //quote_calculator_import_function(); ?>
					<p class="connected-message"><?php _e( 'Now you connected to Intuit for import from Quickbooks', 'quote_calculator' ); ?></p>
					<p><input id="submit-import" class="button button-primary <?php if ( wp_next_scheduled( 'quote_calculator_import_hook' ) ) echo 'hidden'; ?>" value="<?php _e( 'Run Clients Import', 'quote_calculator' ); ?>" type="button"></p>
					<p class="import-clients-message <?php if ( ! wp_next_scheduled( 'quote_calculator_import_hook' ) ) echo 'hidden'; ?>"><?php _e( 'Importing Clients', 'quote_calculator' ); if( ! empty( $quote_calculator_options['start_position'] ) ) echo ' (' . __( 'from', 'quote_calculator' ) . ' ' . $quote_calculator_options['start_position'] . ')'; ?> ... <img src="<?php echo admin_url( 'images/loading.gif' ); ?>" /></p>
					<p><?php _e( 'Last clients import at', 'quote_calculator' ); ?> <?php echo isset( $quote_calculator_options['last_import'] ) ? date( 'd F Y', $quote_calculator_options['last_import'] ) : 'never'; ?></p>
					<p><input id="item-import" class="button button-primary <?php if ( wp_next_scheduled( 'quote_calculator_import_items_hook' ) ) echo 'hidden'; ?>" value="<?php _e( 'Run Items Import', 'quote_calculator' ); ?>" type="button"></p>
					<p class="import-items-message <?php if ( ! wp_next_scheduled( 'quote_calculator_import_items_hook' ) ) echo 'hidden'; ?>"><?php _e( 'Importing Items', 'quote_calculator' ); ?>... <img src="<?php echo admin_url( 'images/loading.gif' ); ?>" /></p>
					<p><?php _e( 'Last items import at', 'quote_calculator' ); ?> <?php echo isset( $quote_calculator_options['last_items_import'] ) && '' != $quote_calculator_options['last_items_import'] ? date( 'd F Y', $quote_calculator_options['last_items_import'] ) : 'never'; ?></p>
					<p><input id="customer-export" class="button button-primary <?php if ( wp_next_scheduled( 'quote_calculator_export_customers_hook' ) ) echo 'hidden'; ?>" value="<?php _e( 'Run Customers Export', 'quote_calculator' ); ?>" type="button"></p>
					<p class="export-customers-message <?php if ( ! wp_next_scheduled( 'quote_calculator_export_customers_hook' ) ) echo 'hidden'; ?>"><?php _e( 'Exporting Customers', 'quote_calculator' ); ?>... <img src="<?php echo admin_url( 'images/loading.gif' ); ?>" /></p>
					<p><?php _e( 'Last customers export at', 'quote_calculator' ); ?> <?php echo isset( $quote_calculator_options['last_customers_export'] ) && '' != $quote_calculator_options['last_customers_export'] ? date( 'd F Y', $quote_calculator_options['last_customers_export'] ) : 'never'; ?></p>
					<p><a class="button button-primary" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=quote_calculator_sync&action=disconnect' ), 'quote_calculator_disconnect', 'quote_calculator_disconnect' ); ?>">Disconnect</a></p>
				<?php } ?>
			</form>
		</div>
	<?php }
}

/*
* Function to connect to Quickbooks
*/
if ( ! function_exists( 'quote_calculator_oauth' ) ) {
	function quote_calculator_oauth(){
		global $quote_calculator_options, $quote_calculator_baseUrl;

		$dataService = DataService::Configure(array(
			'auth_mode'			=> 'oauth2',
			'ClientID'			=> $quote_calculator_options['client_id'],
			'ClientSecret'	=> $quote_calculator_options['client_secret'],
			'RedirectURI'		=> admin_url( 'admin.php?page=quote_calculator_oauth' ),
			'scope'					=> "com.intuit.quickbooks.accounting",
			'baseUrl'				=> $quote_calculator_baseUrl
		));

		if( ! isset( $_REQUEST['error'] ) ) {

			if( ! isset( $_GET['code'] ) ){
				$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

				$url = $OAuth2LoginHelper->getAuthorizationCodeURL(); ?>
				<script type="text/javascript">
					window.location.href = "<?php echo $url; ?>";
				</script>
			<?php }
			$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
			$_SESSION['code'] = $_GET['code'];
			$_SESSION['realmId'] = $_GET['realmId'];
			$accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken( $_GET['code'], $_GET['realmId']);
			$_SESSION['accessToken'] = $quote_calculator_options['accessToken'] = $accessToken; 
			update_option( 'quote_calculator_options', $quote_calculator_options ); ?>
			<script type="text/javascript">
				window.location.href = "<?php echo admin_url( 'admin.php?page=quote_calculator_sync' ); ?>";
			</script>
		<?php } else {
			echo $_REQUEST['error'];
		}
	}
}

/*
* Function to display Sync page.
*/
if ( ! function_exists( 'quote_calculator_sync' ) ) {
	function quote_calculator_sync(){
		global $title, $quote_calculator_options, $quote_calculator_baseUrl;
		if( isset( $_POST['quote_calculator_admin_page'] ) && wp_verify_nonce( $_POST['quote_calculator_admin_page'], 'quote_calculator_admin_page' ) ){
			$update_flag	= false;
			$errors				= array();
			if( 5 < strlen( $_POST['client_id'] ) ){
				if( $quote_calculator_options['client_id'] != $_POST['client_id'] ){
					$quote_calculator_options['client_id'] = trim( $_POST['client_id'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Client ID before you can run the import', 'quote_calculator' );
			}
			if( 5 < strlen( $_POST['client_secret'] ) ){
				if( $quote_calculator_options['client_secret'] != $_POST['client_secret'] ){
					$quote_calculator_options['client_secret'] = trim( $_POST['client_secret'] );
					$update_flag = true;
				}
			} else{
				$errors[] = __( 'Set the Client Secret before you can run the import', 'quote_calculator' );
			}
			if( $update_flag ) {
				$message = __( 'Settings saved.', 'quote_calculator' );
				if( isset( $_SESSION['code'] ) ){
					unset( $_SESSION['code'] );
					unset( $_SESSION['realmId'] );
					unset( $_SESSION['accessToken'] );
				}
				if( isset( $quote_calculator_options['code'] ) ) {
					unset( $quote_calculator_options['code'] );
					unset( $quote_calculator_options['realmId'] );
					unset( $quote_calculator_options['accessToken'] );
				}
				update_option( 'quote_calculator_options', $quote_calculator_options ); 
			} 
		} ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<?php if( ! empty( $errors ) ) { ?>
				<div id="setting-error-settings_updated" class="error settings-error"> 
					<?php foreach( $errors as $error )	{ ?>
						<p><strong><?php echo $error; ?></strong></p>
					<?php } ?>
				</div>
			<?php } 
			if( ! empty( $message ) ) { ?>
				<div id="setting-error-settings_updated" class="updated settings-error"> 
					<p><strong><?php echo $message; ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=quote_calculator_sync' ); ?>" novalidate="novalidate">
				<input name="action" value="update" type="hidden">
				<?php wp_nonce_field( 'quote_calculator_admin_page', 'quote_calculator_admin_page' ); ?>
				<table class="form-table qc-custom-table">
					<thead>
						<tr>
							<th scope="row"><label for="client_id"><?php _e( 'Quickbooks Client ID', 'quote_calculator' ); ?></label></th>
							<th scope="row"><label for="client_secret"><?php _e( 'Quickbooks Client Secret', 'quote_calculator' ); ?></label></th>
						</tr>
					</thead>
					<tbody>						
						<tr>
							<td><input name="client_id" id="client_id" value="<?php echo $quote_calculator_options['client_id']; ?>" class="regular-text" type="text"></td>
							<td><input name="client_secret" id="client_secret" value="<?php echo $quote_calculator_options['client_secret']; ?>" class="regular-text" type="text"></td>
						</tr>						
					</tbody>
				</table>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>" type="submit"></p>
				<?php if( ! isset( $_SESSION['code'] ) && ! empty( $quote_calculator_options['client_id'] ) && ! empty( $quote_calculator_options['client_secret'] ) ){
					$dataService = DataService::Configure(array(
						'auth_mode' => 'oauth2',
						'ClientID' => $quote_calculator_options['client_id'],
						'ClientSecret' => $quote_calculator_options['client_secret'],
						'RedirectURI' => admin_url( 'admin.php?page=quote_calculator_oauth' ),
						'scope' => "com.intuit.quickbooks.accounting",
						'baseUrl' => $quote_calculator_baseUrl
					));

					if( ! isset( $_GET['code'] ) && ! isset( $_SESSION['code'] )  ){
						$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

						$url = $OAuth2LoginHelper->getAuthorizationCodeURL();
					} ?>
					<p><a href="<?php echo $url; ?>"><input id="button" class="button button-primary" value="<?php _e( 'Connect To QB' ); ?>" type="button"></a></p>
				<?php } else if( ! empty( $quote_calculator_options['client_id'] ) && ! empty( $quote_calculator_options['client_secret'] ) ) { //quote_calculator_import_function(); ?>
					<p class="connected-message"><?php _e( 'Now you connected to Intuit for import from Quickbooks', 'quote_calculator' ); ?></p>
					<p><input id="submit-import" class="button button-primary <?php if ( wp_next_scheduled( 'quote_calculator_import_hook' ) ) echo 'hidden'; ?>" value="<?php _e( 'Run Clients Import', 'quote_calculator' ); ?>" type="button"></p>
					<p class="import-clients-message <?php if ( ! wp_next_scheduled( 'quote_calculator_import_hook' ) ) echo 'hidden'; ?>"><?php _e( 'Importing Clients', 'quote_calculator' ); if( ! empty( $quote_calculator_options['start_position'] ) ) echo ' (' . __( 'from', 'quote_calculator' ) . ' ' . $quote_calculator_options['start_position'] . ')'; ?> ... <img src="<?php echo admin_url( 'images/loading.gif' ); ?>" /></p>
					<p><?php _e( 'Last clients import at', 'quote_calculator' ); ?> <?php echo isset( $quote_calculator_options['last_import'] ) ? date( 'd F Y', $quote_calculator_options['last_import'] ) : 'never'; ?></p>
					<p><input id="item-import" class="button button-primary <?php if ( wp_next_scheduled( 'quote_calculator_import_items_hook' ) ) echo 'hidden'; ?>" value="<?php _e( 'Run Items Import', 'quote_calculator' ); ?>" type="button"></p>
					<p class="import-items-message <?php if ( ! wp_next_scheduled( 'quote_calculator_import_items_hook' ) ) echo 'hidden'; ?>"><?php _e( 'Importing Items', 'quote_calculator' ); ?>... <img src="<?php echo admin_url( 'images/loading.gif' ); ?>" /></p>
					<p><?php _e( 'Last items import at', 'quote_calculator' ); ?> <?php echo isset( $quote_calculator_options['last_items_import'] ) && '' != $quote_calculator_options['last_items_import'] ? date( 'd F Y', $quote_calculator_options['last_items_import'] ) : 'never'; ?></p>
					<p><input id="customer-export" class="button button-primary <?php if ( wp_next_scheduled( 'quote_calculator_export_customers_hook' ) ) echo 'hidden'; ?>" value="<?php _e( 'Run Customers Export', 'quote_calculator' ); ?>" type="button"></p>
					<p class="export-customers-message <?php if ( ! wp_next_scheduled( 'quote_calculator_export_customers_hook' ) ) echo 'hidden'; ?>"><?php _e( 'Exporting Customers', 'quote_calculator' ); ?>... <img src="<?php echo admin_url( 'images/loading.gif' ); ?>" /></p>
					<p><?php _e( 'Last customers export at', 'quote_calculator' ); ?> <?php echo isset( $quote_calculator_options['last_customers_export'] ) && '' != $quote_calculator_options['last_customers_export'] ? date( 'd F Y', $quote_calculator_options['last_customers_export'] ) : 'never'; ?></p>
					<p><a class="button button-primary" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=quote_calculator_sync&action=disconnect' ), 'quote_calculator_disconnect', 'quote_calculator_disconnect' ); ?>">Disconnect</a></p>
				<?php } ?>
			</form>
		</div>
	<?php }
}

/*
* Function to display Digital Printing page.
*/
if ( ! function_exists( 'quote_calculator_digital_printing' ) ) {
	function quote_calculator_digital_printing(){
		global $quote_calculator_options, $wpdb;
		$row = 0;
		$message = $errors = $order = '';
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'product_type';
		$all_tabs = array(
			'product_type'	=> __( 'Product Type', 'quote_calculator' ),
			'paper_size'		=> __( 'Paper Size', 'quote_calculator' ),
			'quantity'			=> __( 'Quantity', 'quote_calculator' ),
			'impressions'		=> __( 'Impressions', 'quote_calculator' ),
			'colour'				=> __( 'Colour', 'quote_calculator' ),
			'page_count'		=> __( 'Page Count', 'quote_calculator' ),
			'paper'					=> __( 'Paper', 'quote_calculator' ),
			'finishing'			=> __( 'Finishing', 'quote_calculator' )
		);
		$table_name = $wpdb->prefix . 'quote_calculator_' . $current_tab;
		$existing_columns = $wpdb->get_col( 'DESC ' . $table_name, 0 );
		$result = quote_calculator_save_data( 'quote_calculator_digital_page', $table_name, $current_tab, 1 );
		if( true === $result ){
			$message = __( 'All data saved.', 'quote_calculator' );
		} else {
			$errors = $result;
		}
		if ( 'page_count' == $current_tab ) {
			$order = ' ORDER BY	CAST( `page_count_title` AS unsigned )';
		} else if( 'quantity' != $current_tab ){
			$order = ' ORDER BY	`' . $current_tab . '_title`';
		} else {
			$order = ' ORDER BY	`' . $current_tab . '_count';
		}
		$digital_printing_data = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE ' . $current_tab . '_category = 1' . $order, ARRAY_A );
		quote_calculator_draw_table( 'quote_calculator_digital_page', $message, $errors, $current_tab, $all_tabs, $existing_columns, $digital_printing_data );
	}
}

/*
* Function to display Jobs Out page.
*/
if ( ! function_exists( 'quote_calculator_jobs_out' ) ) {
	function quote_calculator_jobs_out(){
		global $title, $quote_calculator_options, $wpdb;
		$row = 0;
		$message = $errors = $order = '';
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'product_type';
		$all_tabs = array(
			'product_type'	=> __( 'Product Type', 'quote_calculator' ),
			'paper_size'		=> __( 'Paper Size', 'quote_calculator' ),
			'quantity'			=> __( 'Quantity', 'quote_calculator' ),
			'impressions'		=> __( 'Impressions', 'quote_calculator' ),
			'colour'				=> __( 'Colour', 'quote_calculator' ),
			'page_count'		=> __( 'Page Count', 'quote_calculator' ),
			'paper'					=> __( 'Paper', 'quote_calculator' ),
			'finishing'			=> __( 'Finishing', 'quote_calculator' )
		);
		if ( 'page_count' == $current_tab ) {
			$order = ' ORDER BY	CAST( `page_count_title` AS unsigned )';
		} else if( 'quantity' != $current_tab ){
			$order = ' ORDER BY	`' . $current_tab . '_title`';
		} else {
			$order = ' ORDER BY	`' . $current_tab . '_count';
		}
		$table_name = $wpdb->prefix . 'quote_calculator_' . $current_tab;
		$existing_columns = $wpdb->get_col( 'DESC ' . $table_name, 0 );
		$result = quote_calculator_save_data( 'quote_calculator_jobs_out', $table_name, $current_tab, 2 );
		$jobs_out_data = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE ' . $current_tab . '_category = 2' . $order, ARRAY_A );
		quote_calculator_draw_table( 'quote_calculator_jobs_out', $message, $errors, $current_tab, $all_tabs, $existing_columns, $jobs_out_data );
	}
}

/*
* Function to display Design page.
*/
if ( ! function_exists( 'quote_calculator_design' ) ) {
	function quote_calculator_design(){
		global $title, $quote_calculator_options, $wpdb;
		quote_calculator_default_options();
		$row = 0;
		$message = $errors = $order = '';
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'product_type';
		$all_tabs = array(
			'product_type'	=> __( 'Product Type', 'quote_calculator' ),
			'paper_size'		=> __( 'Paper Size', 'quote_calculator' ),
			'quantity'			=> __( 'Quantity', 'quote_calculator' ),
			'impressions'		=> __( 'Impressions', 'quote_calculator' ),
			'hourly_rate'		=> __( 'Hourly Rate', 'quote_calculator' )
		);
		$table_name = $wpdb->prefix . 'quote_calculator_' . $current_tab;
		if( 'quantity' != $current_tab ){
			$order = ' ORDER BY	`' . $current_tab . '_title`';
		} else {
			$order = ' ORDER BY	`' . $current_tab . '_count';
		}
		if( 'hourly_rate' != $current_tab ) {
			$existing_columns = $wpdb->get_col( 'DESC ' . $table_name, 0 );
			$result = quote_calculator_save_data( 'quote_calculator_design', $table_name, $current_tab, 3 );
			$design_data = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE ' . $current_tab . '_category = 3' . $order, ARRAY_A );
			quote_calculator_draw_table( 'quote_calculator_design', $message, $errors, $current_tab, $all_tabs, $existing_columns, $design_data );
		} else {			
			if( isset( $_POST['quote_calculator_design'] ) && wp_verify_nonce( $_POST['quote_calculator_design'], 'quote_calculator_design' ) ) {
				if( is_numeric( $_POST['design_hourly_rate'] ) && $_POST['design_hourly_rate'] > 0 ){
					if( $quote_calculator_options['design_hourly_rate'] != $_POST['design_hourly_rate'] ){
						$quote_calculator_options['design_hourly_rate'] = trim( $_POST['design_hourly_rate'] );
						update_option( 'quote_calculator_options', $quote_calculator_options );
					}
				} else{
					$errors[] = __( 'Set the Hourly Rate to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
				}
			} ?>
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
						<li <?php echo $current_tab == $key ? 'class="active"' : '';?>><?php if( $current_tab != $key ) { ?><a href="<?php echo admin_url( 'admin.php?page=quote_calculator_design&tab=' . $key ); ?>"><?php } ?><?php echo $tab; ?><?php if( $current_tab != $key ) { ?></a><?php } ?></li>
					<?php } ?>
				</ul>
				<form method="post" action="<?php echo admin_url( 'admin.php?page=quote_calculator_design&tab=' . $current_tab ); ?>" novalidate="novalidate">
					<input name="action" value="update" type="hidden">
					<?php wp_nonce_field( 'quote_calculator_design', 'quote_calculator_design' ); ?>
					<input id="quote_calculator_current_tab" name="quote_calculator_current_tab" value="<?php echo $current_tab; ?>" type="hidden">
					<table class="wp-list-table widefat fixed striped qc-custom-table">
						<thead>
							<tr>
								<th class="manage-column column-posts num">
									<span><?php _e( 'Title', 'quote_calculator' ); ?></span>
								</th>
								<th class="manage-column column-posts num">
									<span><?php _e( 'Price', 'quote_calculator' ); ?></span>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input value="<?php echo $all_tabs[ $current_tab ]; ?>	" class="regular-text" type="text" disabled="disabled" />									
								</td>
								<td>
									<input name="design_hourly_rate" value="<?php echo $quote_calculator_options['design_hourly_rate']; ?>" class="regular-text" type="text" />												
								</td>
							</tr>						
						</tbody>
					</table>
					<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>" type="submit"></p>
				</form>
			</div>
		<?php }
	}
}

/*
* Function to display Design page.
*/
if ( ! function_exists( 'quote_calculator_wide_format' ) ) {
	function quote_calculator_wide_format(){
		global $title, $quote_calculator_options, $wpdb;
		$message = $errors = $order = '';
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'product_type';
		$all_tabs = array(
			'product_type'	=> __( 'Product Type', 'quote_calculator' ),
			'paper_size'		=> __( 'Paper Size', 'quote_calculator' ),
			'quantity'			=> __( 'Quantity', 'quote_calculator' ),
			'impressions'		=> __( 'Impressions', 'quote_calculator' ),
			'colour'				=> __( 'Colour', 'quote_calculator' ),
			'page_count'		=> __( 'Page Count', 'quote_calculator' ),
			'paper'					=> __( 'Paper', 'quote_calculator' ),
			'finishing'			=> __( 'Finishing', 'quote_calculator' )
		);
		if ( 'page_count' == $current_tab ) {
			$order = ' ORDER BY	CAST( `page_count_title` AS unsigned )';
		} else if( 'quantity' != $current_tab ){
			$order = ' ORDER BY	`' . $current_tab . '_title`';
		} else {
			$order = ' ORDER BY	`' . $current_tab . '_count';
		}
		$table_name = $wpdb->prefix . 'quote_calculator_' . $current_tab;
		$existing_columns = $wpdb->get_col( 'DESC ' . $table_name, 0 );
		$result = quote_calculator_save_data( 'quote_calculator_wide_format', $table_name, $current_tab, 4 );
		$wide_format_data = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE ' . $current_tab . '_category = 4' . $order, ARRAY_A );
		quote_calculator_draw_table( 'quote_calculator_wide_format', $message, $errors, $current_tab, $all_tabs, $existing_columns, $wide_format_data );
	}
}

if ( ! function_exists( 'quote_calculator_delivery_options' ) ) {
	function quote_calculator_delivery_options(){
		global $title, $quote_calculator_options, $wpdb;
		$message = $errors = '';
		$current_tab = 'delivery';
		$all_tabs = array(
			'delivery' => __( 'Delivery Type', 'quote_calculator' )
		);
		$table_name = $wpdb->prefix . 'quote_calculator_' . $current_tab;
		$existing_columns = $wpdb->get_col( 'DESC ' . $table_name, 0 );
		$result = quote_calculator_save_data( 'quote_calculator_delivery', $table_name, $current_tab, 0 );
		$delivery_options_data = $wpdb->get_results( 'SELECT * FROM ' . $table_name . '', ARRAY_A );
		quote_calculator_draw_table( 'quote_calculator_delivery', $message, $errors, $current_tab, $all_tabs, $existing_columns, $delivery_options_data );
	}
}

/*
* Function to save all data from all plugin admin pages .
*/
if ( ! function_exists( 'quote_calculator_save_data' ) ) {
	function quote_calculator_save_data( $page_slug, $table_name, $current_tab, $parent_id ) {
		global $quote_calculator_options, $wpdb;
		if( isset( $_POST[ $page_slug ] ) && wp_verify_nonce( $_POST[ $page_slug ], $page_slug ) && isset( $_POST[ $current_tab ] ) && is_array( $_POST[ $current_tab ] ) ){
			foreach( $_POST[ $current_tab ] as $new_value ){
				if( isset( $new_value[ $current_tab . '_id' ] ) ){
					$new_value_insert = $new_value;
					unset( $new_value_insert[ $current_tab . '_id' ] );
					$format = array();
					$array_values = array_values( $new_value_insert );
					for( $i = 0; $i < count( $array_values ); $i++ ){
						if( is_numeric( $array_values[ $i ] ) && is_int( $array_values[ $i ] + 0 ) ){ 
								$format[] = '%d';
							} elseif( is_numeric( $array_values[ $i ] ) && is_float( $array_values[ $i ] + 0 ) ){ 
								$format[] = '%f';
							} elseif( is_string( $array_values[ $i ] ) ){ 
								$format[] = '%s';
							} 
					}
					$wpdb->update( 
						$table_name, 
						$new_value_insert, 
						array( $current_tab . '_id' => $new_value[ $current_tab . '_id' ] ), 
						$format, 
						array( '%d' ) 
					);
				} else {
					if( ( isset( $new_value[ $current_tab . '_title' ] ) && ! empty( $new_value[ $current_tab . '_title' ] ) ) || 
						( isset( $new_value[ $current_tab . '_count' ] ) && ! empty( $new_value[ $current_tab . '_count' ] ) ) ){
						$format = array();
						$array_values = array_values( $new_value );
						for( $i = 0; $i < count( $array_values ); $i++ ){
							if( is_numeric( $array_values[ $i ] ) && is_int( $array_values[ $i ] + 0 ) ){ 
								$format[] = '%d';
							} elseif( is_numeric( $array_values[ $i ] ) && is_float( $array_values[ $i ] + 0 ) ){ 
								$format[] = '%f';
							} elseif( is_string( $array_values[ $i ] ) ){ 
								$format[] = '%s';
							} 
						}
						if( 0 < $parent_id ){
							$new_value[ $current_tab . '_category' ] = $parent_id;
							$format[] = '%d';
						}
						$wpdb->insert( 
							$table_name, 
							$new_value, 
							$format
						);
					}
				}
			}
			if( isset( $_POST['remove'] ) ){
				foreach( $_POST['remove'] as $remove_id ){
					$wpdb->delete( $table_name, array( $current_tab . '_id' => $remove_id ) );
				}
			}
			return true;
		}
	}
}

/*
* Function to draw table on all plugin admin pages.
*/
if ( ! function_exists( 'quote_calculator_draw_table' ) ) {
	function quote_calculator_draw_table( $page_slug, $message, $errors, $current_tab, $all_tabs, $existing_columns, $data_array ){
		global $title;
		$row = 0;
		$all_columns = array(
			'title'						=> __( 'Title', 'quote_calculator' ),
			'count'						=> __( 'Count', 'quote_calculator' ),
			'price'						=> __( 'Price', 'quote_calculator' ),
			'category_price'	=> __( 'Category Price', 'quote_calculator' ),
			'calculation'			=> __( 'Calculation', 'quote_calculator' ),
			'type'						=> __( 'Type', 'quote_calculator' ),
			'weight'					=> __( 'Weight', 'quote_calculator' ),
			'format'					=> __( 'Format', 'quote_calculator' ),
			'part'						=> __( 'Part', 'quote_calculator' )
		);
		$weight_array = array(
			'80gsm',
			'100gsm',
			'120gsm',
			'130gsm',
			'150gsm',
			'160gsm',
			'170gsm',
			'190gsm',
			'200gsm',
			'220gsm',
			'250gsm',
			'270gsm',
			'300gsm',
			'330gsm',
			'350gsm',
			'400gsm',
			'450gsm'
		);
		$format_array = array(
			'A0',
			'A1',
			'A2',
			'A3',
			'A4',
			'A5'
		);
		$part_array = array(
			'2 part',
			'3 part'
		); ?>
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
					<li <?php echo $current_tab == $key ? 'class="active"' : '';?>><?php if( $current_tab != $key ) { ?><a href="<?php echo admin_url( 'admin.php?page=' . $page_slug . '&tab=' . $key ); ?>"><?php } ?><?php echo $tab; ?><?php if( $current_tab != $key ) { ?></a><?php } ?></li>
				<?php } ?>
			</ul>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=' . $page_slug . '&tab=' . $current_tab ); ?>" novalidate="novalidate">
				<input name="action" value="update" type="hidden">
				<?php wp_nonce_field( $page_slug, $page_slug ); ?>
				<input id="quote_calculator_current_tab" name="quote_calculator_current_tab" value="<?php echo $current_tab; ?>" type="hidden">
				<table class="wp-list-table widefat fixed striped <?php echo $current_tab; ?> qc-custom-table">
					<thead>
						<tr>
							<?php foreach( $existing_columns as $column ) { 
								if( false === strpos( $column, '_id' ) && false === strpos( $column, '_category' ) ){ ?>
									<th>
										<span><?php echo $all_columns[ str_replace( $current_tab . '_', '', $column ) ]; ?></span>
									</th>
								<?php } 
							} ?>
							<th class="manage-column column-posts action">
								<span><?php _e( 'Action', 'quote_calculator' ); ?></span>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $data_array as $row => $value ) { ?>
							<tr>
								<?php foreach( $existing_columns as $column ) {
									if( false !== strpos( $column, '_id' ) ) {
										$current_id = $value[ $column ];
										$current_id_key = $column;
									} else if( false === strpos( $column, '_category' ) ){ ?>
										<td>
											<?php if( false !== strpos( $column, '_weight' ) ) { ?>
												<select name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]">
													<option value=""></option>
													<?php foreach( $weight_array as $weight ) { 
														$selected = '';
														if( $value[ $column ] == $weight ){
															$selected = 'selected="selected"';
														} ?>
														<option <?php echo $selected; ?>><?php echo $weight; ?></option>
													<?php } ?>
												</select>
											<?php } elseif( false !== strpos( $column, '_format' ) ) { ?>
												<select name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]">
													<option value=""></option>
													<?php foreach( $format_array as $format ) { 
														$selected = '';
														if( $value[ $column ] == $format ){
															$selected = 'selected="selected"';
														} ?>
														<option <?php echo $selected; ?>><?php echo $format; ?></option>
													<?php } ?>
												</select>
											<?php } elseif( false !== strpos( $column, '_part' ) ) { ?>
												<select name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]">
													<option value=""></option>
													<?php foreach( $part_array as $part ) { 
														$selected = '';
														if( $value[ $column ] == $part ){
															$selected = 'selected="selected"';
														} ?>
														<option <?php echo $selected; ?>><?php echo $part; ?></option>
													<?php } ?>
												</select>
											<?php } else { ?>
												<input name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]" value="<?php echo $value[ $column ]; ?>" class="regular-text" type="text">												
											<?php } ?>
										</td>
									<?php }
								} ?>
								<td>
									<span class="button button-primary button-remove"><?php _e( 'Remove', 'quote_calculator' ); ?> -</span>
									<input type="hidden" name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $current_id_key; ?>]" value="<?php echo $current_id; ?>" />
								</td>
							</tr>
						<?php } ?>
						<tr>
							<?php $row++; 
							foreach( $existing_columns as $column ) {
								if( false === strpos( $column, '_id' ) && false === strpos( $column, '_category' ) ){ ?>
									<td>
										<?php if( false !== strpos( $column, '_weight' ) ) { ?>
											<select name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]">
												<option value=""></option>
												<?php foreach( $weight_array as $weight ) { ?>
													<option><?php echo $weight; ?></option>
												<?php } ?>
											</select>
										<?php } elseif( false !== strpos( $column, '_format' ) ) { ?>
											<select name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]">
												<option value=""></option>
												<?php foreach( $format_array as $format ) { ?>
													<option><?php echo $format; ?></option>
												<?php } ?>
											</select>
										<?php } elseif( false !== strpos( $column, '_part' ) ) { ?>
											<select name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]">
												<option value=""></option>
												<?php foreach( $part_array as $part ) { ?>
													<option><?php echo $part; ?></option>
												<?php } ?>
											</select>
										<?php } else { ?>
											<input name="<?php echo $current_tab; ?>[<?php echo $row; ?>][<?php echo $column; ?>]" value="" class="regular-text" type="text">
											<?php if( 'finishing' == $current_tab && false !== strpos( $column, '_price' ) ){ ?>
												<p class="description"><?php _e( 'For "Jobs Out" value set price to "-1"', 'quote_calculator' ); ?></p>
											<?php }
										} ?>
									</td>
								<?php }
							} ?>
							<td>
								<span class="button button-primary button-add"><?php _e( 'Add', 'quote_calculator' ); ?> +</span> <span class="hidden button button-primary button-remove"><?php _e( 'Remove', 'quote_calculator' ); ?> -</span>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" id="current_row_number" value="<?php echo $row; ?>" />
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>" type="submit"></p>
			</form>
		</div>
	<?php }
}

/*
* Function to add script and styles to the admin.
*/
if ( ! function_exists( 'quote_calculator_customers' ) ) {
	function quote_calculator_customers(){
		global $title, $quote_calculator_options;

		if( ( ( isset( $_POST['action'] ) && 'delete' == $_POST['action'] ) || ( isset( $_POST['action2'] ) && 'delete' == $_POST['action2'] ) ) && ! empty( $_POST['customers'] ) ){
			foreach( $_POST['customers'] as $customer ){
				quote_calculator_delete_customers( $customer );
			}
		} ?> 
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php echo $title; ?></h1>
			<a href="<?php echo admin_url( 'admin.php?page=quote_calculator_customer_operation' ); ?>" class="page-title-action"><?php _e( 'Add New Client', 'quote_calculator' ); ?></a>
			<?php if( ! empty ( $_POST['s'] ) ){ ?>
				<span class="subtitle"><?php printf( __( 'Search results for &#8220;%s&#8221;' ), esc_html( $_POST['s'] ) ); ?></span>
			<?php } ?>
			<hr class="wp-header-end">
			<?php $quote_calculator_customers_table = new Quote_Calculator_Customers_Table();			
			$quote_calculator_customers_table->prepare_items();
			$quote_calculator_customers_table->views(); ?>
			<form id="quote_calculator_customers_filter" method="post" action="<?php echo admin_url( 'admin.php?page=quote_calculator_customers' ); ?>">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php $quote_calculator_customers_table->search_box( __( 'Search Client', 'quote_calculator' ), 'quote_calculator_customers_search' );
				$quote_calculator_customers_table->display(); ?>
			</form>
		</div>
	<?php }
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Quote_Calculator_Customers_Table extends WP_List_Table {
	function get_columns(){
		$columns = array(
			'cb'											=> '<input type="checkbox" />',
			'customers-given-name'		=> __( 'First Name', 'quote_calculator' ),
			'customers-family-name'		=> __( 'Surname', 'quote_calculator' ),
			'customers-display-name'	=> __( 'Display Name', 'quote_calculator' ),
			'customers-company-name'	=> __( 'Company Name', 'quote_calculator' ),
			'customers-phone'					=> __( 'Phone', 'quote_calculator' ),
			'customers-email'					=> __( 'Email', 'quote_calculator' ),
			'customers-bill-addr'			=> __( 'Billing address', 'quote_calculator' ),
			'customers-ship-addr'			=> __( 'Shipping address', 'quote_calculator' ),
		);
		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'customers-given-name'		=> array( 'customers_given_name', false ),
			'customers-family-name'		=> array( 'customers_family_name', false ),
			'customers-display-name'  => array( 'customers_display_name', false ),
			'customers-company-name'  => array( 'customers_company_name', false )
		);
		return $sortable_columns;
	}

	function column_default( $item, $column_name ) {
		global $wpdb;
		switch( $column_name ) { 
			case 'customers-given-name':
				$edit_query_args = array(
					'page'		=> 'quote_calculator_customer_operation',
					'customer_id'			=> $item['customers_id'],
				);
				$actions['edit'] = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( wp_nonce_url( add_query_arg( $edit_query_args, 'admin.php' ), 'editcustomer_' . $item['customers_id'] ) ),
					_x( 'Edit', 'List table row action', 'quote_calculator' )
				);
				return sprintf( '%1$s %2$s',
					$item[ str_replace( '-', '_', $column_name ) ],
					$this->row_actions( $actions )
				);
			case 'customers-family-name':
			case 'customers-display-name':
			case 'customers-company-name':
			case 'customers-phone':
			case 'customers-email':
				return $item[ str_replace( '-', '_', $column_name ) ];
			case 'customers-bill-addr':
			case 'customers-ship-addr':
				$customer_address = quote_calculator_get_customers_address( $item[ str_replace( '-', '_', $column_name ) ] );
				ob_start(); ?>
				<span class="customer-address-visible">
					<?php echo $customer_address['customer_address_line1']; ?>
				</span>
				<span class="customer-address-visible">
					<?php if( ! empty( $customer_address['customer_address_line2'] ) ){
						echo $customer_address['customer_address_line2'] . '<br />';
					}
					if( ! empty( $customer_address['	customer_address_line3'] ) ){
						echo $customer_address['	customer_address_line3'] . '<br />';
					}
					if( ! empty( $customer_address['customer_address_city'] ) ){
						echo $customer_address['customer_address_city'] . ', ';
					}
					if( ! empty( $customer_address['customer_address_country_sub_division_code'] ) ){
						echo $customer_address['customer_address_country_sub_division_code'] . ' ';
					}
					if( ! empty( $customer_address['customer_address_postal_code'] ) ){
						echo $customer_address['customer_address_postal_code'];
					}
					if( ! empty( $customer_address['customer_address_country'] ) ){
						echo '<br />' . $customer_address['customer_address_country'];
					} ?>
				</span>
				<?php $buffer = ob_get_contents();
				ob_end_clean();
				return $buffer;
			default:
				return; 
		}
	}

	function column_cb( $item ) {
		return '<input type="checkbox" name="customers[]" id="user_' . $item['customers_id'] . '" value="' . $item['customers_id'] . '" />';	
	}

	function get_bulk_actions() {
		$actions = array(
			'delete'    => 'Delete'
		);
		return $actions;
	}

	function get_views(){
		$views = array();
		$class = '';

		$all_url = remove_query_arg( array( 'orderby', 'order' ) );
		if( count( $_POST ) < 2 ){
			$class = 'class="current"';
		}

		$views['all'] = '<a href="' . $all_url . '" ' . $class . '>' . __( 'All', 'quote_calculator' ) . '</a> (' . quote_calculator_get_customers_total( false ) . ')';

		return $views;
	}

	public function no_items() {
		_e( 'No client found.', 'quote_calculator' );
	}

	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$paged = $this->get_pagenum();
		$per_page = 20;
		$this->items = quote_calculator_get_customers( $per_page, $paged );
		$total = quote_calculator_get_customers_total();
		$this->set_pagination_args( array(
			'total_items' => $total,
			'per_page' => 20
		) );
	}
}

/*
* Function to add new/edit exists customer.
*/
if ( ! function_exists( 'quote_calculator_customer_operation' ) ) {
	function quote_calculator_customer_operation(){
		global $title, $quote_calculator_options, $wpdb;
		$errors = array();
		$message = '';
		$submit = __( 'Add Client', 'quote_calculator' );
		
		if( isset( $_POST['action'] ) && 'save' == $_POST['action'] && wp_verify_nonce( $_POST['save_customer_info'], 'savecustomer' ) ){
			if( isset( $_GET['customer_id'] ) && is_numeric( $_GET['customer_id'] ) ){
				$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
				$wpdb->update( 
					$table_name,
					array(
						'customer_address_line1'											=> wp_unslash( trim( $_POST['customer_address_line1'][1] ) ),
						'customer_address_line2'											=> wp_unslash( trim( $_POST['customer_address_line2'][1] ) ),
						'customer_address_line3'											=> wp_unslash( trim( $_POST['customer_address_line3'][1] ) ),
						'customer_address_city'												=> wp_unslash( trim( $_POST['customer_address_city'][1] ) ),
						'customer_address_country'										=> wp_unslash( trim( $_POST['customer_address_country'][1] ) ),
						'customer_address_country_sub_division_code'	=> wp_unslash( trim( $_POST['customer_address_country_sub_division_code'][1] ) ),
						'customer_address_postal_code'								=> wp_unslash( trim( $_POST['customer_address_postal_code'][1] ) )
					),
					array( 'customer_address_id' => wp_unslash( trim( $_POST['customers_bill_addr'] ) ) ), 
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					),
					array( '%d' )
				);
				$wpdb->update( 
					$table_name,
					array(
						'customer_address_line1'											=> wp_unslash( trim( $_POST['customer_address_line1'][2] ) ),
						'customer_address_line2'											=> wp_unslash( trim( $_POST['customer_address_line2'][2] ) ),
						'customer_address_line3'											=> wp_unslash( trim( $_POST['customer_address_line3'][2] ) ),
						'customer_address_city'												=> wp_unslash( trim( $_POST['customer_address_city'][2] ) ),
						'customer_address_country'										=> wp_unslash( trim( $_POST['customer_address_country'][2] ) ),
						'customer_address_country_sub_division_code'	=> wp_unslash( trim( $_POST['customer_address_country_sub_division_code'][2] ) ),
						'customer_address_postal_code'								=> wp_unslash( trim( $_POST['customer_address_postal_code'][2] ) )
					),
					array( 'customer_address_id' => wp_unslash( trim( $_POST['customers_ship_addr'] ) ) ), 
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					),
					array( '%d' )
				);
				$table_name = $wpdb->prefix . 'quote_calculator_customers';
				$wpdb->update( 
					$table_name,
					array(
						'customers_given_name'		=> wp_unslash( trim( $_POST['customers_given_name'] ) ),
						'customers_family_name'		=> wp_unslash( trim( $_POST['customers_family_name'] ) ),
						'customers_company_name'	=> wp_unslash( trim( $_POST['customers_company_name'] ) ),
						'customers_display_name'	=> wp_unslash( trim( $_POST['customers_display_name'] ) ),
						'customers_phone'					=> wp_unslash( trim( $_POST['customers_phone'] ) ),
						'customers_email'					=> wp_unslash( trim( $_POST['customers_email'] ) )
					),
					array( 'customers_id' => wp_unslash( trim( $_POST['customers_id'] ) ) ),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					),
					array( '%d' )
				);
			}
			else{
				$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
				$wpdb->insert( 
					$table_name,
					array(
						'customer_address_line1'											=> wp_unslash( trim( $_POST['customer_address_line1'][1] ) ),
						'customer_address_line2'											=> wp_unslash( trim( $_POST['customer_address_line2'][1] ) ),
						'customer_address_line3'											=> wp_unslash( trim( $_POST['customer_address_line3'][1] ) ),
						'customer_address_city'												=> wp_unslash( trim( $_POST['customer_address_city'][1] ) ),
						'customer_address_country'										=> wp_unslash( trim( $_POST['customer_address_country'][1] ) ),
						'customer_address_country_sub_division_code'	=> wp_unslash( trim( $_POST['customer_address_country_sub_division_code'][1] ) ),
						'customer_address_postal_code'								=> wp_unslash( trim( $_POST['customer_address_postal_code'][1] ) )
					),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)
				);
				$bill_address = $wpdb->insert_id;
				$wpdb->insert( 
					$table_name,
					array(
						'customer_address_type'												=> 2,
						'customer_address_line1'											=> wp_unslash( trim( $_POST['customer_address_line1'][2] ) ),
						'customer_address_line2'											=> wp_unslash( trim( $_POST['customer_address_line2'][2] ) ),
						'customer_address_line3'											=> wp_unslash( trim( $_POST['customer_address_line3'][2] ) ),
						'customer_address_city'												=> wp_unslash( trim( $_POST['customer_address_city'][2] ) ),
						'customer_address_country'										=> wp_unslash( trim( $_POST['customer_address_country'][2] ) ),
						'customer_address_country_sub_division_code'	=> wp_unslash( trim( $_POST['customer_address_country_sub_division_code'][2] ) ),
						'customer_address_postal_code'								=> wp_unslash( trim( $_POST['customer_address_postal_code'][2] ) )
					),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)
				);
				$ship_address = $wpdb->insert_id;
				$table_name = $wpdb->prefix . 'quote_calculator_customers';
				$wpdb->insert( 
					$table_name,
					array(
						'customers_qb_id'					=> 0,
						'customers_given_name'		=> wp_unslash( trim( $_POST['customers_given_name'] ) ),
						'customers_family_name'		=> wp_unslash( trim( $_POST['customers_family_name'] ) ),
						'customers_company_name'	=> wp_unslash( trim( $_POST['customers_company_name'] ) ),
						'customers_display_name'	=> wp_unslash( trim( $_POST['customers_display_name'] ) ),
						'customers_phone'					=> wp_unslash( trim( $_POST['customers_phone'] ) ),
						'customers_email'					=> wp_unslash( trim( $_POST['customers_email'] ) ),
						'customers_bill_addr'			=> $bill_address,
						'customers_ship_addr'			=> $ship_address,
						'customers_modification'	=> 1
					),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%d'
					)
				);
				$_GET['customer_id'] = $wpdb->insert_id;
			}
			$current_customer = quote_calculator_get_customers_by_id( $_GET['customer_id'] );
			$title = __( 'Edit Customer', 'quote_calculator' );
			$submit = __( 'Update Customer', 'quote_calculator' );
			$message = __( 'Customer Info saved.', 'quote_calculator' );
		} elseif( isset( $_GET['customer_id'] ) && is_numeric( $_GET['customer_id'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'editcustomer_' . $_GET['customer_id'] ) ){
			$current_customer = quote_calculator_get_customers_by_id( $_GET['customer_id'] );
			$title = __( 'Edit Customer', 'quote_calculator' );
			$submit = __( 'Update Customer', 'quote_calculator' );
		}
		if( isset( $_GET['customer_id'] ) && empty( $current_customer ) ){
			$errors[] = __( 'Are you sure you want to do this?', 'quote_calculator' );
		}	else if( ! isset( $_GET['customer_id'] ) ){
			$current_customer = $customer_bill_address = $customer_ship_address = array();
			$form_url = admin_url( 'admin.php?page=quote_calculator_customer_operation' );
		} else {
			$customer_bill_address = quote_calculator_get_customers_address( $current_customer['customers_bill_addr'] );
			$customer_ship_address = quote_calculator_get_customers_address( $current_customer['customers_ship_addr'] );
			$form_url = admin_url( 'admin.php?page=quote_calculator_customer_operation&customer_id=' . $_GET['customer_id'] );
		} ?> 
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php echo $title; ?></h1>
			<?php if( ! empty( $errors ) ) { ?>
				<div id="setting-error-settings_updated" class="error settings-error"> 
					<?php foreach( $errors as $error )	{ ?>
						<p><strong><?php echo $error; ?></strong></p>
					<?php } ?>
				</div>
			<?php }
			if( ! empty( $message ) ) { ?>
				<div id="setting-error-settings_updated" class="updated settings-error"> 
					<p><strong><?php echo $message; ?></strong></p>
				</div>
			<?php }
			if( ( isset( $_GET['customer_id'] ) && ! empty( $current_customer ) ) || ! isset( $_GET['customer_id'] ) ){ ?>
				<form id="quote_calculator_customers_operation" method="post" action="<?php echo $form_url; ?>">
					<input type="hidden" name="action" value="save" />
					<?php if( isset( $_GET['customer_id'] ) ){ ?>
						<input type="hidden" name="customers_id" value="<?php echo $_GET['customer_id']; ?>" />
					<?php } ?>
					<?php wp_nonce_field( 'savecustomer', 'save_customer_info' ); ?>
					<table class="form-table">
						<tbody>
							<tr>
								<td>
									<label for="customers_given_name"><?php _e( 'First Name', 'quote_calculator' ); ?></label><br />
									<input name="customers_given_name" id="customers_given_name" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_given_name'] : ''; ?>" class="regular-text" type="text">
								</td>
								<td>
									<label for="customers_family_name"><?php _e( 'Surname', 'quote_calculator' ); ?></label><br />
									<input name="customers_family_name" id="customers_family_name" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_family_name'] : ''; ?>" class="regular-text" type="text">
								</td>
							</tr>
							<tr>
								<td>
									<label for="customers_display_name"><?php _e( 'Display Name', 'quote_calculator' ); ?></label><br />
									<input name="customers_display_name" id="customers_display_name" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_display_name'] : ''; ?>" class="regular-text" type="text">
								</td>
								<td>
									<label for="customers_company_name"><?php _e( 'Company Name', 'quote_calculator' ); ?></label><br />
									<input name="customers_company_name" id="customers_company_name" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_company_name'] : ''; ?>" class="regular-text" type="text">
								</td>
							</tr>
							<tr>
								<td>
									<label for="customers_phone"><?php _e( 'Phone', 'quote_calculator' ); ?></label><br />
									<input name="customers_phone" id="customers_phone" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_phone'] : ''; ?>" class="regular-text" type="text">
								</td>
								<td>
									<label for="customers_email"><?php _e( 'Email', 'quote_calculator' ); ?></label><br />
									<input name="customers_email" id="customers_email" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_email'] : ''; ?>" class="regular-text" type="email">
								</td>
							</tr>
							<tr>
								<td>
									<label for="customers_bill_addr"><?php _e( 'Billing Address', 'quote_calculator' ); ?></label><br />								
									<input name="customers_bill_addr" id="customers_bill_addr" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_bill_addr'] : ''; ?>" type="hidden">
									<input name="customer_address_line1[1]" id="customer_address_line1" value="<?php echo ! empty( $customer_bill_address ) ? $customer_bill_address['customer_address_line1'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Address Line 1', 'quote_calculator' ); ?></p>
									<input name="customer_address_line2[1]" id="customer_address_line2" value="<?php echo ! empty( $customer_bill_address ) ? $customer_bill_address['customer_address_line2'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Address Line 2', 'quote_calculator' ); ?></p>
									<input name="customer_address_line3[1]" id="vcustomer_address_line3" value="<?php echo ! empty( $customer_bill_address ) ? $customer_bill_address['customer_address_line3'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Address Line 3', 'quote_calculator' ); ?></p>
									<input name="customer_address_city[1]" id="customer_address_city" value="<?php echo ! empty( $customer_bill_address ) ? $customer_bill_address['customer_address_city'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'City', 'quote_calculator' ); ?></p>
									<input name="customer_address_country[1]" id="customer_address_country" value="<?php echo ! empty( $customer_bill_address ) ? $customer_bill_address['customer_address_country'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Country', 'quote_calculator' ); ?></p>
									<input name="customer_address_country_sub_division_code[1]" id="customer_address_country_sub_division_code" value="<?php echo ! empty( $customer_bill_address ) ? $customer_bill_address['customer_address_country_sub_division_code'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Country Sub Division Code', 'quote_calculator' ); ?></p>
									<input name="customer_address_postal_code[1]" id="customer_address_postal_code" value="<?php echo ! empty( $customer_bill_address ) ? $customer_bill_address['customer_address_postal_code'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Postal Code', 'quote_calculator' ); ?></p>
								</td>
								<td>
									<label for="customers_ship_addr"><?php _e( 'Shipping Address', 'quote_calculator' ); ?></label><br />								
									<input name="customers_ship_addr" id="customers_ship_addr" value="<?php echo ! empty( $current_customer ) ? $current_customer['customers_ship_addr'] : ''; ?>" type="hidden">
									<input name="customer_address_line1[2]" id="customer_address_line1" value="<?php echo ! empty( $customer_ship_address ) ? $customer_ship_address['customer_address_line1'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Address Line 1', 'quote_calculator' ); ?></p>
									<input name="customer_address_line2[2]" id="customer_address_line2" value="<?php echo ! empty( $customer_ship_address ) ? $customer_ship_address['customer_address_line2'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Address Line 2', 'quote_calculator' ); ?></p>
									<input name="customer_address_line3[2]" id="vcustomer_address_line3" value="<?php echo ! empty( $customer_ship_address ) ? $customer_ship_address['customer_address_line3'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Address Line 3', 'quote_calculator' ); ?></p>
									<input name="customer_address_city[2]" id="customer_address_city" value="<?php echo ! empty( $customer_ship_address ) ? $customer_ship_address['customer_address_city'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'City', 'quote_calculator' ); ?></p>
									<input name="customer_address_country[2]" id="customer_address_country" value="<?php echo ! empty( $customer_ship_address ) ? $customer_ship_address['customer_address_country'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Country', 'quote_calculator' ); ?></p>
									<input name="customer_address_country_sub_division_code[2]" id="customer_address_country_sub_division_code" value="<?php echo ! empty( $customer_ship_address ) ? $customer_ship_address['customer_address_country_sub_division_code'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Country Sub Division Code', 'quote_calculator' ); ?></p>
									<input name="customer_address_postal_code[2]" id="customer_address_postal_code" value="<?php echo! empty( $customer_ship_address ) ?  $customer_ship_address['customer_address_postal_code'] : ''; ?>" class="regular-text" type="text">
									<p class="description"><?php _e( 'Postal Code', 'quote_calculator' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php echo $submit; ?>" type="submit"></p>
				</form>
			<?php } ?>
		</div>
	<?php }
}

/*
* Function to display margins & VAT page.
*/
if ( ! function_exists( 'quote_calculator_margins_vat' ) ) {
	function quote_calculator_margins_vat(){
		global $title, $quote_calculator_options; 
		$errors = '';
		if( isset( $_POST['quote_calculator_margins_vat'] ) && wp_verify_nonce( $_POST['quote_calculator_margins_vat'], 'quote_calculator_margins_vat' ) ){
			$update_flag	= false;
			
			if( isset( $_POST['vat'] ) ) {
				if( is_numeric( $_POST['vat'] ) && $_POST['vat'] > 0 ){
					if( $quote_calculator_options['vat'] != $_POST['vat'] ){
						$quote_calculator_options['vat'] = trim( $_POST['vat'] );
						$update_flag = true;
					}
				} else{
					$errors[] = __( 'Set the VAT to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
				}
			}
			if( isset( $_POST['overhead'] ) ) {
				if( is_numeric( $_POST['overhead'] ) && $_POST['overhead'] > 0 ){
					if( $quote_calculator_options['overhead'] != $_POST['overhead'] ){
						$quote_calculator_options['overhead'] = trim( $_POST['overhead'] );
						$update_flag = true;
					}
				} else{
					$errors[] = __( 'Set the Overhead to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
				}
			}
			if( isset( $_POST['profit'] ) ) {
				if( is_numeric( $_POST['profit'] ) && $_POST['profit'] > 0 ){
					if( $quote_calculator_options['profit'] != $_POST['profit'] ){
						$quote_calculator_options['profit'] = trim( $_POST['profit'] );
						$update_flag = true;
					}
				} else{
					$errors[] = __( 'Set the Profit Margin to Integer or Floating numbers more than 0 or equal to 0', 'quote_calculator' );
				}
			}
			
			if( $update_flag ) {
				$message = __( 'Settings saved.', 'quote_calculator' );				

				update_option( 'quote_calculator_options', $quote_calculator_options );
			}
		}

		$all_tabs = array(
			'vat'					=> __( 'VAT', 'quote_calculator' ),
			'profit'			=> __( 'Margins', 'quote_calculator' ),
			'overhead'		=> __( 'Overheads', 'quote_calculator' )
		);
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'vat'; ?>
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
					<li <?php echo $current_tab == $key ? 'class="active"' : '';?>><?php if( $current_tab != $key ) { ?><a href="<?php echo admin_url( 'admin.php?page=quote_calculator_margins_vat&tab=' . $key ); ?>"><?php } ?><?php echo $tab; ?><?php if( $current_tab != $key ) { ?></a><?php } ?></li>
				<?php } ?>
			</ul>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=quote_calculator_margins_vat&tab=' . $current_tab ); ?>" novalidate="novalidate">
				<input name="action" value="update" type="hidden">
				<?php wp_nonce_field( 'quote_calculator_margins_vat', 'quote_calculator_margins_vat' ); ?>
				<input id="quote_calculator_current_tab" name="quote_calculator_current_tab" value="<?php echo $current_tab; ?>" type="hidden">
				<table class="wp-list-table widefat fixed striped margins_vat qc-custom-table">
					<thead>
						<tr>
							<th class="manage-column column-posts num">
								<span><?php _e( 'Title', 'quote_calculator' ); ?></span>
							</th>
							<th class="manage-column column-posts num">
								<span><?php _e( 'Price', 'quote_calculator' ); ?></span>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input value="<?php echo $all_tabs[ $current_tab ]; ?>	" class="regular-text" type="text" disabled="disabled" />									
							</td>
							<td>
								<input name="<?php echo $current_tab; ?>" value="<?php echo $quote_calculator_options[ $current_tab ]; ?>" class="regular-text" type="text" />												
							</td>
						</tr>						
					</tbody>
				</table>
				<p class="submit"><input name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>" type="submit"></p>
			</form>
		</div>
	<?php }
}

/*
* Function to add script and styles to the admin.
*/
if ( ! function_exists( 'quote_calculator_admin_head' ) ) {
	function quote_calculator_admin_head(){
		wp_enqueue_script( 'quote_calculator_admin_script', plugins_url( 'js/admin-script.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_style( 'quote_calculator_admin_style', plugins_url( 'css/admin-style.css', __FILE__ ) );
	}
}

/*
* Function to set up ajax action.
*/
if ( ! function_exists ( 'quote_calculator_import_customers' ) ) {
	function quote_calculator_import_customers() {
		if ( ! wp_next_scheduled( 'quote_calculator_import_hook' ) ) {
			wp_schedule_event( time(), '5minutes', 'quote_calculator_import_hook' );
		}
		echo '1';
		wp_die();
	}
}

/*
* Function to set up ajax action.
*/
if ( ! function_exists ( 'quote_calculator_import_items' ) ) {
	function quote_calculator_import_items() {
		if ( ! wp_next_scheduled( 'quote_calculator_import_items_hook' ) ) {
			wp_schedule_event( time(), '5minutes', 'quote_calculator_import_items_hook' );
		}
		echo '1';
		wp_die();
	}
}

/*
* Function to set up ajax action.
*/
if ( ! function_exists ( 'quote_calculator_export_customers' ) ) {
	function quote_calculator_export_customers() {
		if ( ! wp_next_scheduled( 'quote_calculator_export_customers_hook' ) ) {
			wp_schedule_event( time(), '5minutes', 'quote_calculator_export_customers_hook' );
		}
		echo '1';
		wp_die();
	}
}

/*
* Function to import customers from quickbook.
*/
if ( ! function_exists ( 'quote_calculator_import_function' ) ) {
	function quote_calculator_import_function() {
		global $quote_calculator_options, $wpdb, $quote_calculator_baseUrl;
		$max_results = 300;
		if( isset( $quote_calculator_options['client_id'] ) ){
			if( isset( $quote_calculator_options['accessToken'] ) ){
				$dataService = DataService::Configure( array(
					'auth_mode'				=> 'oauth2',
					'ClientID'				=> $quote_calculator_options['client_id'],
					'ClientSecret'		=> $quote_calculator_options['client_secret'],
					'RedirectURI'			=> admin_url( 'admin.php?page=quote_calculator_oauth' ),
					'accessTokenKey'	=> $quote_calculator_options['accessToken']->getAccessToken(),
					'refreshTokenKey' => $quote_calculator_options['accessToken']->getRefreshToken(),
					'QBORealmID'			=> $quote_calculator_options['accessToken']->getRealmID(),
					'scope'						=> "com.intuit.quickbooks.accounting",
					'baseUrl'					=> $quote_calculator_baseUrl
				) );
				$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

				$accessToken = $OAuth2LoginHelper->refreshToken();
				$_SESSION['accessToken'] = $quote_calculator_options['accessToken'] = $accessToken;
			} else {
				echo 'Please reconnect to api'; die();
			}
			$error = $OAuth2LoginHelper->getLastError();
			if( empty( $quote_calculator_options['start_position'] ) ){
				$quote_calculator_options['start_position'] = 1;
			} else {
				$quote_calculator_options['start_position'] += $max_results;
			}
			if( empty( $quote_calculator_options['max_results'] ) ){
				$quote_calculator_options['max_results'] = $max_results;
			}
			$quote_calculator_options['max_results'] = $max_results;
			//error_log(print_r($quote_calculator_options, true) . PHP_EOL, 3, __DIR__ . '/error.log' );
			update_option( 'quote_calculator_options', $quote_calculator_options );
			$allCustomers = $dataService->FindAll( 'Customer', $quote_calculator_options['start_position'], $quote_calculator_options['max_results'] );
			if( ! empty( $allCustomers ) ){
				foreach( $allCustomers as $customer ){
					$table_name = $wpdb->prefix . 'quote_calculator_customers';
					$current_customer_id = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE `customers_qb_id` = ' . $customer->Id, ARRAY_A );
					$bill_address = array(
						'customer_address_type'												=> 1,
						'customer_address_line1'											=> ! empty( $customer->BillAddr->Line1 ) ? $customer->BillAddr->Line1 : '',
						'customer_address_line2'											=> ! empty( $customer->BillAddr->Line2 ) ? $customer->BillAddr->Line2 : '',
						'customer_address_line3'											=> ! empty( $customer->BillAddr->Line3 ) ? $customer->BillAddr->Line3 : '',
						'customer_address_city'												=> ! empty( $customer->BillAddr->City ) ? $customer->BillAddr->City : '',
						'customer_address_country'										=> ! empty( $customer->BillAddr->Country ) ? $customer->BillAddr->Country : '',
						'customer_address_country_sub_division_code'	=> ! empty( $customer->BillAddr->CountrySubDivisionCode ) ? $customer->BillAddr->CountrySubDivisionCode : '',
						'customer_address_postal_code'								=> ! empty( $customer->BillAddr->PostalCode ) ? $customer->BillAddr->PostalCode : '',
						'customer_address_lat'												=> ! empty( $customer->BillAddr->Lat ) ? $customer->BillAddr->Lat : '',
						'customer_address_long'												=> ! empty( $customer->BillAddr->Long ) ? $customer->BillAddr->Long : ''
					);
					$bill_ship_address_format = array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					);
					if( ! empty( $customer->ShipAddr->Line1 ) ){
						$ship_address = array(
							'customer_address_type'												=> 2,
							'customer_address_line1'											=> ! empty( $customer->ShipAddr->Line1 ) ? $customer->ShipAddr->Line1 : '',
							'customer_address_line2'											=> ! empty( $customer->ShipAddr->Line2 ) ? $customer->ShipAddr->Line2 : '',
							'customer_address_line3'											=> ! empty( $customer->ShipAddr->Line3 ) ? $customer->ShipAddr->Line3 : '',
							'customer_address_city'												=> ! empty( $customer->ShipAddr->City ) ? $customer->ShipAddr->City : '',
							'customer_address_country'										=> ! empty( $customer->ShipAddr->Country ) ? $customer->ShipAddr->Country : '',
							'customer_address_country_sub_division_code'	=> ! empty( $customer->ShipAddr->CountrySubDivisionCode ) ? $customer->ShipAddr->CountrySubDivisionCode : '',
							'customer_address_postal_code'								=> ! empty( $customer->ShipAddr->PostalCode ) ? $customer->ShipAddr->PostalCode : '',
							'customer_address_lat'												=> ! empty( $customer->ShipAddr->Lat ) ? $customer->ShipAddr->Lat : '',
							'customer_address_long'												=> ! empty( $customer->ShipAddr->Long ) ? $customer->ShipAddr->Long : ''
						);
					} else {
						$ship_address = array(
							'customer_address_type'												=> 2,
							'customer_address_line1'											=> '',
							'customer_address_line2'											=> NULL,
							'customer_address_line3'											=> NULL,
							'customer_address_city'												=> '',
							'customer_address_country'										=> NULL,
							'customer_address_country_sub_division_code'	=> NULL,
							'customer_address_postal_code'								=> NULL,
							'customer_address_lat'												=> NULL,
							'customer_address_long'												=> NULL
						);
					}
					if( NULL != $current_customer_id ){
						$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
						//error_log( print_r( 'Find     ' . $customer->Id, true ) . PHP_EOL, 3, dirname( __FILE__ ). '/error.log' );
						if( ! empty( $current_customer_id['customers_bill_addr'] ) && 0 < $current_customer_id['customers_bill_addr'] ) {
							$wpdb->update( 
								$table_name,
								$bill_address,
								array( 'customer_address_id' => $current_customer_id['customers_bill_addr'] ), 
								$bill_ship_address_format,
								array( '%d' )
							);
						} else {
							$wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` (`customer_address_type`, `customer_address_line1`, `customer_address_line2`, `customer_address_line3`, `customer_address_city`, `customer_address_country`, `customer_address_country_sub_division_code`, `customer_address_postal_code`, `customer_address_lat`, `customer_address_long`) VALUES (" . implode( ',', $bill_ship_address_format ) . ")", array_values( $bill_address ) ) );
							$current_customer_id['customers_bill_addr'] = $wpdb->insert_id;
						}
						if( ! empty( $current_customer_id['customers_ship_addr'] ) && 0 < $current_customer_id['customers_ship_addr'] ) {
							$wpdb->update( 
								$table_name,
								$ship_address,
								array( 'customer_address_id' => $current_customer_id['customers_ship_addr'] ), 
								$bill_ship_address_format,
								array( '%d' )
							);
						} else {
							$wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` (`customer_address_type`, `customer_address_line1`, `customer_address_line2`, `customer_address_line3`, `customer_address_city`, `customer_address_country`, `customer_address_country_sub_division_code`, `customer_address_postal_code`, `customer_address_lat`, `customer_address_long`) VALUES (" . implode( ',', $bill_ship_address_format ) . ")", array_values( $ship_address ) ) );
							$current_customer_id['customers_ship_addr'] = $wpdb->insert_id;
						}
						$table_name = $wpdb->prefix . 'quote_calculator_customers';
						$wpdb->update( 
							$table_name,
							array(
								'customers_qb_id'					=> $customer->Id,
								'customers_given_name'		=> ! empty( $customer->GivenName ) ? $customer->GivenName : '',
								'customers_family_name'		=> ! empty( $customer->FamilyName ) ? $customer->FamilyName : '',
								'customers_company_name'	=> ! empty( $customer->CompanyName ) ? $customer->CompanyName : '',
								'customers_display_name'	=> ! empty( $customer->DisplayName ) ? $customer->DisplayName : '',
								'customers_phone'					=> ! empty( $customer->PrimaryPhone ) ? $customer->PrimaryPhone->FreeFormNumber : '',
								'customers_bill_addr'			=> $current_customer_id['customers_bill_addr'],
								'customers_ship_addr'			=> $current_customer_id['customers_ship_addr']
							),
							array( 'customers_id' => $current_customer_id['customers_id'] ),
							array(
								'%d',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%d',
								'%d'
							),
							array( '%d' )
						);
					}
					else{
						$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
						$wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` (`customer_address_type`, `customer_address_line1`, `customer_address_line2`, `customer_address_line3`, `customer_address_city`, `customer_address_country`, `customer_address_country_sub_division_code`, `customer_address_postal_code`, `customer_address_lat`, `customer_address_long`) VALUES (" . implode( ',', $bill_ship_address_format ) . ")", array_values( $bill_address ) ) );
						$bill_address = $wpdb->insert_id;
						$wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` (`customer_address_type`, `customer_address_line1`, `customer_address_line2`, `customer_address_line3`, `customer_address_city`, `customer_address_country`, `customer_address_country_sub_division_code`, `customer_address_postal_code`, `customer_address_lat`, `customer_address_long`) VALUES (" . implode( ',', $bill_ship_address_format ) . ")", array_values( $ship_address ) ) );
						$ship_address = $wpdb->insert_id;
						$table_name = $wpdb->prefix . 'quote_calculator_customers';
						$wpdb->query( $wpdb->prepare( "INSERT INTO `" . $table_name . "` (
								`customers_qb_id`, 
								`customers_given_name`, 
								`customers_family_name`, 
								`customers_company_name`, 
								`customers_display_name`, 
								`customers_phone`, 
								`customers_email`, 
								`customers_bill_addr`, 
								`customers_ship_addr`
							) VALUES (" . implode( ',', array(
								'%d',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%d',
								'%d'
							) ) . ")", array_values( array(
								'customers_qb_id'					=> $customer->Id,
								'customers_given_name'		=> ! empty( $customer->GivenName ) ? $customer->GivenName : '',
								'customers_family_name'		=> ! empty( $customer->FamilyName ) ? $customer->FamilyName : '',
								'customers_company_name'	=> ! empty( $customer->CompanyName ) ? $customer->CompanyName : '',
								'customers_display_name'	=> ! empty( $customer->DisplayName ) ? $customer->DisplayName : '',
								'customers_phone'					=> ! empty( $customer->PrimaryPhone ) ? $customer->PrimaryPhone->FreeFormNumber : '',
								'customers_email'					=> ! empty( $customer->PrimaryEmailAddr ) ? $customer->PrimaryEmailAddr->Address : '',
								'customers_bill_addr'			=> $bill_address,
								'customers_ship_addr'			=> $ship_address
							) ) ) );
					}
				}
				if( $max_results > count( $allCustomers ) ){
					if( ! empty( $quote_calculator_options['start_position'] ) ){
						unset( $quote_calculator_options['start_position'] );
					}
					if( ! empty( $quote_calculator_options['max_results'] ) ){
						unset( $quote_calculator_options['max_results'] );
					}
					$quote_calculator_options['last_import'] = time();
					update_option( 'quote_calculator_options', $quote_calculator_options );
					wp_clear_scheduled_hook( 'quote_calculator_import_hook' );
				}
			} else {
				if( ! empty( $quote_calculator_options['start_position'] ) ){
					unset( $quote_calculator_options['start_position'] );
				}
				if( ! empty( $quote_calculator_options['max_results'] ) ){
					unset( $quote_calculator_options['max_results'] );
				}
				$quote_calculator_options['last_import'] = time();
				update_option( 'quote_calculator_options', $quote_calculator_options );
				wp_clear_scheduled_hook( 'quote_calculator_import_hook' );
			}
		} else {
			if( ! empty( $quote_calculator_options['start_position'] ) ){
				unset( $quote_calculator_options['start_position'] );
			}
			if( ! empty( $quote_calculator_options['max_results'] ) ){
				unset( $quote_calculator_options['max_results'] );
			}
			$quote_calculator_options['last_import'] = time();
			update_option( 'quote_calculator_options', $quote_calculator_options );
			wp_clear_scheduled_hook( 'quote_calculator_import_hook' );
		}
	}
}

/*
* Function to import itemss from quickbook.
*/
if ( ! function_exists ( 'quote_calculator_import_items_function' ) ) {
	function quote_calculator_import_items_function() {
		global $quote_calculator_options, $wpdb, $quote_calculator_baseUrl;
		$max_results = 300;
		if( isset( $quote_calculator_options['client_id'] ) ){
			if( isset( $quote_calculator_options['accessToken'] ) ){
				$dataService = DataService::Configure( array(
					'auth_mode'				=> 'oauth2',
					'ClientID'				=> $quote_calculator_options['client_id'],
					'ClientSecret'		=> $quote_calculator_options['client_secret'],
					'RedirectURI'			=> admin_url( 'admin.php?page=quote_calculator_oauth' ),
					'accessTokenKey'	=> $quote_calculator_options['accessToken']->getAccessToken(),
					'refreshTokenKey' => $quote_calculator_options['accessToken']->getRefreshToken(),
					'QBORealmID'			=> $quote_calculator_options['accessToken']->getRealmID(),
					'scope'						=> "com.intuit.quickbooks.accounting",
					'baseUrl'					=> $quote_calculator_baseUrl
				) );
				$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

				$accessToken = $OAuth2LoginHelper->refreshToken();
				$_SESSION['accessToken'] = $quote_calculator_options['accessToken'] = $accessToken;
			} else {
				echo 'Please reconnect to api'; die();
			}
			$error = $OAuth2LoginHelper->getLastError();
			if( empty( $quote_calculator_options['start_items_position'] ) ){
				$quote_calculator_options['start_items_position'] = 1;
			} else {
				$quote_calculator_options['start_items_position'] += $max_results;
			}
			if( empty( $quote_calculator_options['max_items_results'] ) ){
				$quote_calculator_options['max_items_results'] = $max_results;
			}
			//error_log(print_r($quote_calculator_options, true) . PHP_EOL, 3, __DIR__ . '/error.log' );
			update_option( 'quote_calculator_options', $quote_calculator_options );
			$allItems = $dataService->FindAll( 'Item', $quote_calculator_options['start_items_position'], $quote_calculator_options['max_items_results'] );
			if( ! empty( $allItems ) ){
				//error_log( print_r( $allItems, true ) . PHP_EOL, 3, dirname(__FILE__) . '/error.log'); die();
				foreach( $allItems as $item ){
					if( empty( $item->ExpenseAccountRef ) ){
						$table_name = $wpdb->prefix . 'quote_calculator_items';
						$current_item_id = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE `	items_object_id` = ' . $item->Id, ARRAY_A );
						if( NULL != $current_item_id ){						
							$wpdb->update( 
								$table_name,
								array(
									'items_object_id'						=> $item->Id,
									'items_name'								=> $item->Name,
									'items_status'							=> $item->Active == 'true' ? 1 : 0,
									'items_income_account_ref'	=> $item->IncomeAccountRef,
									'items_parent_ref'					=> '' != $item->ParentRef ? $item->ParentRef : 0
								),
								array( 'items_id' => $current_item_id['items_id'] ),
								array(
									'%d',
									'%s',
									'%d',
									'%d',
									'%d'
								),
								array( '%d' )
							);
						}
						else{
							$wpdb->insert( 
								$table_name,
								array(
									'items_object_id'						=> $item->Id,
									'items_name'								=> $item->Name,
									'items_status'							=> $item->Active == 'true' ? 1 : 0,
									'items_income_account_ref'	=> $item->IncomeAccountRef,
									'items_parent_ref'					=> '' != $item->ParentRef ? $item->ParentRef : 0
								),
								array(
									'%d',
									'%s',
									'%d',
									'%d',
									'%d'
								)
							);
						}
					}
				}
				if( $max_results > count( $allItems ) ){
					if( ! empty( $quote_calculator_options['start_items_position'] ) ){
						unset( $quote_calculator_options['start_items_position'] );
					}
					if( ! empty( $quote_calculator_options['max_items_results'] ) ){
						unset( $quote_calculator_options['max_items_results'] );
					}
					$quote_calculator_options['last_items_import'] = time();
					update_option( 'quote_calculator_options', $quote_calculator_options );
					wp_clear_scheduled_hook( 'quote_calculator_import_items_hook' );
				}
			} else {
				if( ! empty( $quote_calculator_options['start_items_position'] ) ){
					unset( $quote_calculator_options['start_items_position'] );
				}
				if( ! empty( $quote_calculator_options['max_items_results'] ) ){
					unset( $quote_calculator_options['max_items_results'] );
				}
				$quote_calculator_options['last_items_import'] = time();
				update_option( 'quote_calculator_options', $quote_calculator_options );
				wp_clear_scheduled_hook( 'quote_calculator_import_items_hook' );
			}
		} else {
			if( ! empty( $quote_calculator_options['start_items_position'] ) ){
				unset( $quote_calculator_options['start_items_position'] );
			}
			if( ! empty( $quote_calculator_options['max_items_results'] ) ){
				unset( $quote_calculator_options['max_items_results'] );
			}
			$quote_calculator_options['last_items_import'] = time();
			update_option( 'quote_calculator_options', $quote_calculator_options );
			wp_clear_scheduled_hook( 'quote_calculator_import_items_hook' );
		}
	}
}

/*
* Function to import itemss from quickbook.
*/
if ( ! function_exists ( 'quote_calculator_export_customers_function' ) ) {
	function quote_calculator_export_customers_function() {
		global $quote_calculator_options, $wpdb, $quote_calculator_baseUrl, $quote_calculator_estimate_customer;
		if( isset( $quote_calculator_options['client_id'] ) ){
			if( isset( $quote_calculator_options['accessToken'] ) ){
				$dataService = DataService::Configure( array(
					'auth_mode'				=> 'oauth2',
					'ClientID'				=> $quote_calculator_options['client_id'],
					'ClientSecret'		=> $quote_calculator_options['client_secret'],
					'RedirectURI'			=> admin_url( 'admin.php?page=quote_calculator_oauth' ),
					'accessTokenKey'	=> $quote_calculator_options['accessToken']->getAccessToken(),
					'refreshTokenKey' => $quote_calculator_options['accessToken']->getRefreshToken(),
					'QBORealmID'			=> $quote_calculator_options['accessToken']->getRealmID(),
					'scope'						=> "com.intuit.quickbooks.accounting",
					'baseUrl'					=> $quote_calculator_baseUrl
				) );
				$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

				$accessToken = $OAuth2LoginHelper->refreshToken();
				$_SESSION['accessToken'] = $quote_calculator_options['accessToken'] = $accessToken;
			} else {
				echo 'Please reconnect to api'; die();
			}
			$error = $OAuth2LoginHelper->getLastError();
			//error_log(print_r($quote_calculator_options, true) . PHP_EOL, 3, __DIR__ . '/error.log' );
			$table_name = $wpdb->prefix . 'quote_calculator_customers';
			$new_customers = $wpdb->get_results( 'SELECT * FROM `' . $table_name . '` WHERE `customers_qb_id` = 0', ARRAY_A );
			if( ! empty( $new_customers ) ){
				require_once( plugin_dir_path( __FILE__ ) . 'quickbooks_oauth/helper/CustomerHelper.php' ); 
				foreach( $new_customers as $customer ){
					$quote_calculator_estimate_customer = $customer;
					$quote_calculator_estimate_customer['bill_addr'] = quote_calculator_get_customers_address( $customer['customers_bill_addr'] );
					$quote_calculator_estimate_customer['ship_addr'] = quote_calculator_get_customers_address( $customer['customers_ship_addr'] );
					$where = '';
					if( ! empty( $quote_calculator_estimate_customer['customers_given_name'] ) ){
						$where .= "GivenName LIKE '" . $quote_calculator_estimate_customer['customers_given_name'] . "'";
					}
					if( ! empty( $quote_calculator_estimate_customer['customers_family_name'] ) ){
						if( '' != $where ){
							$where .= ' AND ';
						}
						$where .= "FamilyName LIKE '" . $quote_calculator_estimate_customer['customers_family_name'] . "'";
					}
					if( ! empty( $quote_calculator_estimate_customer['customers_display_name'] ) ){
						if( '' != $where ){
							$where .= ' AND ';
						}
						$where .= "DisplayName LIKE '" . $quote_calculator_estimate_customer['customers_display_name'] . "'";
					}
					$current_customer = $dataService->Query( "select * from Customer where " . $where );
					if( ! empty( $current_customer ) && sizeof( $current_customer ) == 1 ){
						$current_customer = current( $current_customer );
						$wpdb->update( 
							$table_name,
							array(
								'customers_qb_id'		=> $current_customer->Id
							),
							array( 'customers_id' => $customer['customers_id'] ),
							array(
								'%d'
							),
							array( '%d' )
						);
					} else {
						$current_customer = CustomerHelper::createCustomer( $dataService );
						$wpdb->update( 
							$table_name,
							array(
								'customers_qb_id'		=> $current_customer->Id
							),
							array( 'customers_id' => $customer['customers_id'] ),
							array(
								'%d'
							),
							array( '%d' )
						);
					}
				}
			} 
			$quote_calculator_options['last_customers_export'] = time();
			update_option( 'quote_calculator_options', $quote_calculator_options );
			wp_clear_scheduled_hook( 'quote_calculator_export_customers_hook' );
		} else {
			$quote_calculator_options['last_customers_export'] = time();
			update_option( 'quote_calculator_options', $quote_calculator_options );
			wp_clear_scheduled_hook( 'quote_calculator_export_customers_hook' );
		}
	}
}

/*
* Function to autocomplete customer' select.
*/
if ( ! function_exists ( 'quote_calculator_autocomplete_customers' ) ) {
	function quote_calculator_autocomplete_customers() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_customers';
		$table_name_address = $wpdb->prefix . 'quote_calculator_customer_address';
		$like = '';
		if( isset( $_POST['quote_calculator_customer_name'] ) && ! empty( $_POST['quote_calculator_customer_name'] ) ){
			$like = 'LIKE "%' . $_POST['quote_calculator_customer_name'] . '%"';
		}
		$customers_name_array = $wpdb->get_results( 'SELECT `customers_display_name`,	`customers_id`, `customers_bill_addr`, `customers_email`, `customers_phone`, `customers_ship_addr`, 
		a.`customer_address_line1` AS customer_bill_address_line1, 
		a.`customer_address_line2` AS customer_bill_address_line2, 
		a.`customer_address_line3` AS customer_bill_address_line3, 
		a.`customer_address_city` AS customer_bill_address_city, 
		a.`customer_address_country` AS customer_bill_address_country, 
		a.`customer_address_country_sub_division_code` AS customer_bill_address_country_code, 
		a.`customer_address_postal_code` AS customer_bill_address_postal_code,
		b.`customer_address_line1` AS customer_ship_address_line1, 
		b.`customer_address_line2` AS customer_ship_address_line2, 
		b.`customer_address_line3` AS customer_ship_address_line3, 
		b.`customer_address_city` AS customer_ship_address_city, 
		b.`customer_address_country` AS customer_ship_address_country, 
		b.`customer_address_country_sub_division_code` AS customer_ship_address_country_code, 
		b.`customer_address_postal_code` AS customer_ship_address_postal_code
		FROM ' . $table_name . ', ' . $table_name_address . ' AS a, ' . $table_name_address . ' AS b WHERE `customers_display_name` ' . $like . ' AND `customers_bill_addr` = a.`customer_address_id` AND `customers_ship_addr` = b.`customer_address_id` ORDER BY `customers_display_name` ASC', ARRAY_A );
		echo json_encode( array( 'success' => 1, 'data' => $customers_name_array, 'separator' => PHP_EOL ) );
		wp_die();
	}
}

/*
* Function to add new section to Estimate page.
*/
if ( ! function_exists ( 'quote_calculator_add_section' ) ) {
	function quote_calculator_add_section() {
		global $wpdb, $current_section_count;
		quote_calculator_default_options();
		if( isset( $_POST['section_name'] ) && ! empty( $_POST['section_name'] ) ){
			ob_start();
			$current_section_count = $_POST['current_section_count'];
			switch( $_POST['section_name']){
				case 'design':
					include( plugin_dir_path( __FILE__ ) . 'templates/includes/part-design.php' );
					$success = 1;
					break;
				case 'digital':
					include( plugin_dir_path( __FILE__ ) . 'templates/includes/part-digital-printing.php' );
					$success = 1;
					break;
				case 'jobs':
					include( plugin_dir_path( __FILE__ ) . 'templates/includes/part-jobs-out.php' );
					$success = 1;
					break;
				case 'wide':
					include( plugin_dir_path( __FILE__ ) . 'templates/includes/part-wide-format.php' );
					$success = 1;
					break;
				default:
					$success = 0;
					break;
			}
			$buffer = ob_get_contents();
			ob_end_clean();
			echo json_encode( array( 'success' => $success, 'data' => $buffer ) );
		}
		wp_die();
	}
}

/*
* Function to display plugin main settings page.
*/
if ( ! function_exists( 'quote_calculator_options_page' ) ) {
	function quote_calculator_options_page() {
		global $quote_calculator_options, $quote_calculator_default_options, $quote_calculator_plugin_info, $wp_version;
		$error = ""; ?>
		<div id="quote_calculator_options_wrap" class="wrap">
		</div><!-- #quote_calculator_options_wrap -->
	<?php }
}

/*
* Function to redirect Quote сalculator pages.
*/
if ( ! function_exists( 'quote_calculator_template_redirect' ) ) {
	function quote_calculator_template_redirect(){
		//if( current_user_can( 'activate_plugins' ) ){
			if ( ( $locations = get_nav_menu_locations() ) && isset( $locations['quote-calculator-menu'] ) )
			$menu = wp_get_nav_menu_object( $locations['quote-calculator-menu'] );
			$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );
			foreach( $menu_items as $item ){
				if( is_page( $item->object_id ) ){
					include( plugin_dir_path( __FILE__ ) . 'templates/'.strtolower( $item->title ).'.php' );
					exit();
				}
			}
		//}
	}
}

/*
* Function to add script and styles to the front-end.
*/
if ( ! function_exists( 'quote_calculator_frontend_head' ) ) {
	function quote_calculator_frontend_head() {
		global $wp_query;
		wp_enqueue_style( 'dashicons' );
		$current_page = get_query_var( 'pagename' );
		if( ! empty( $current_page ) && in_array( $current_page, array( 'dashboard', 'estimate' ) ) ){
			wp_enqueue_style( 'quote_calculator_ui_style', plugins_url( 'css/jquery-ui-datepicker.css', __FILE__ ) );
			wp_enqueue_style( 'quote_calculator_intuit_style', 'https://appcenter.intuit.com/Content/IA/intuit.ipp.anywhere.css');
			wp_enqueue_style( 'quote_calculator_style', plugins_url( 'css/style.css', __FILE__ ) );
			wp_enqueue_script( 'quote_calculator_nicescroll', plugins_url( 'js/jquery.nicescroll.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'quote_calculator_fonts', 'https://use.typekit.net/ajc0ogc.js', array( 'jquery' ) );
			wp_enqueue_script( 'quote_calculator_script', plugins_url( 'js/script.js?1', __FILE__ ), array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-autocomplete', 'jquery-ui-button', 'jquery-ui-tooltip', 'jquery-ui-datepicker' ) );
			wp_localize_script( 'quote_calculator_script', 'quote_calculator_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_deregister_style( 'stylesheet' );
		}
	}
}

/* *Function to deload stylesheet
*/
function quote_calculator_remove_default_stylesheet() {
    wp_dequeue_style( 'twentysixteen-style' );
    wp_deregister_style( 'twentysixteen-style' );
}

/*
* Function to add settings links to the frontend menu.
*/
function quote_calculator_add_settings( $items, $args ){
	if ( $args->theme_location != 'quote-calculator-menu' ) {
		return $items;
	}

	if ( is_user_logged_in() ) {
		$items .= '<li class="dashicons-before dashicons-admin-generic last menu-item"><a href="' . admin_url( 'admin.php?page=custom_dashboard' ) . '">' . __( 'Dashboard', 'quote_calculator' ) . '</a></li>';
	} else {
		$items .= '<li class="dashicons-before dashicons-admin-generic last menu-item"><a href="' . wp_login_url() . '">' . __( 'Login In', 'quote_calculator' ) . '</a></li>';
	}

	return $items;
}

/*
* Function to add action links to the plugin menu.
*/
if ( ! function_exists ( 'quote_calculator_plugin_action_links' ) ) {
	function quote_calculator_plugin_action_links( $links, $file ) {
		/* Static so we don't call plugin_basename on every plugin row 
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename( __FILE__ );
		if ( $file == $this_plugin ) {
			$settings_link = '<a href="admin.php?page=bws-google-maps.php">' . __( 'Settings', 'quote_calculator' ) . '</a>';
			array_unshift( $links, $settings_link );
		}*/
		return $links;
	}
}

/*
* Function to add links to the plugin description on the plugins page.
*/
if ( ! function_exists ( 'quote_calculator_register_action_links' ) ) {
	function quote_calculator_register_action_links( $links, $file ) {
		/*if ( $file == plugin_basename( __FILE__ ) ) {
			$links[] = sprintf( '<a href="admin.php?page=bws-google-maps.php">%s</a>', __( 'Settings', 'quote_calculator' ) );
			$links[] = sprintf( '<a href="http://wordpress.org/plugins/bws-google-maps/faq/" target="_blank">%s</a>', __( 'FAQ', 'quote_calculator' ) );
			$links[] = sprintf( '<a href="http://support.bestwebsoft.com">%s</a>', __( 'Support', 'quote_calculator' ) );
		}*/
		return $links;
	}
}

/*
* Function to uninstall.
*/
if ( ! function_exists( 'quote_calculator_uninstall' ) ) {
	function quote_calculator_uninstall() {
		//delete_option( 'quote_calculator_options' );
		//delete_site_option( 'quote_calculator_options' );
	}
}

/*
* Function to receive product_type from the database
*/
if ( ! function_exists( 'quote_calculator_get_product_type' ) ) {
	function quote_calculator_get_product_type( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_product_type';
		$product_type = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `product_type_category` = ' . $category . ' ORDER BY `product_type_title` ASC', ARRAY_A );
		return $product_type;
	}
}

/*
* Function to receive paper_size from the database
*/
if ( ! function_exists( 'quote_calculator_get_paper_size' ) ) {
	function quote_calculator_get_paper_size( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_paper_size';
		$paper_size = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `paper_size_category` = ' . $category . ' ORDER BY `paper_size_title` DESC', ARRAY_A );
		return $paper_size;
	}
}

/*
* Function to receive quantity from the database
*/
if ( ! function_exists( 'quote_calculator_get_quantity' ) ) {
	function quote_calculator_get_quantity( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_quantity';
		$quantity = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `quantity_category` = ' . $category . ' ORDER BY `quantity_count` ASC', ARRAY_A );
		return $quantity;
	}
}

/*
* Function to receive impressions from the database
*/
if ( ! function_exists( 'quote_calculator_get_impressions' ) ) {
	function quote_calculator_get_impressions( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_impressions';
		$impressions = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `impressions_category` = ' . $category . ' ORDER BY `impressions_id` ASC', ARRAY_A );
		return $impressions;
	}
}

/*
* Function to receive colour from the database
*/
if ( ! function_exists( 'quote_calculator_get_colour' ) ) {
	function quote_calculator_get_colour( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_colour';
		$colour = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `colour_category` = ' . $category . ' ORDER BY `colour_id` ASC', ARRAY_A );
		return $colour;
	}
}

/*
* Function to receive page count from the database
*/
if ( ! function_exists( 'quote_calculator_get_page_count' ) ) {
	function quote_calculator_get_page_count( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_page_count';
		$colour = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `page_count_category` = ' . $category . ' ORDER BY CAST( `page_count_title` AS unsigned ) ASC', ARRAY_A );
		return $colour;
	}
}

/*
* Function to receive paper from the database
*/
if ( ! function_exists( 'quote_calculator_get_paper' ) ) {
	function quote_calculator_get_paper( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_paper';
		$paper = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `paper_category` = ' . $category . ' ORDER BY `paper_title` ASC', ARRAY_A );
		return $paper; 
	}
}

/*
* Function to receive finishing from the database
*/
if ( ! function_exists( 'quote_calculator_get_finishing' ) ) {
	function quote_calculator_get_finishing( $category ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_finishing';
		$finishing = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `finishing_category` = ' . $category . ' ORDER BY `finishing_title` ASC', ARRAY_A );
		return $finishing; 
	}
}

/*
* Function to receive customers from the database
*/
if ( ! function_exists( 'quote_calculator_get_customers' ) ) {
	function quote_calculator_get_customers( $per_page = 20, $paged = 1 ){
		global $wpdb;
		$where = '';
		$table_name = $wpdb->prefix . 'quote_calculator_customers';
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'customers_id';
		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'ASC';
		if( isset( $_POST['s'] ) && ! empty( $_POST['s'] ) ){
			$search = wp_unslash( trim( $_POST['s'] ) );
			$where = ' WHERE `customers_given_name` LIKE "%' . $search . '%" OR `customers_family_name` LIKE "%' . $search . '%" OR `customers_display_name` LIKE "%' . $search . '%" ';
		}
		$customers = $wpdb->get_results( 'SELECT * FROM ' . $table_name . $where . ' ORDER BY `' . $orderby . '` ' . $order . ' LIMIT ' . ( ( $paged - 1 ) * $per_page ) . ', ' . $per_page, ARRAY_A );
		return $customers; 
	}
}

/*
* Function to receive customers from the database by customers_id
*/
if ( ! function_exists( 'quote_calculator_get_customers_by_id' ) ) {
	function quote_calculator_get_customers_by_id( $customers_id ){
		global $wpdb;
		if( ! empty( $customers_id ) && is_numeric( $customers_id ) ){
			$table_name = $wpdb->prefix . 'quote_calculator_customers';
			$customer_info = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE customers_id = ' . $customers_id, ARRAY_A );
			return $customer_info; 
		}
		return false;
	}
}

/*
* Function to receive count customers from the database
*/
if ( ! function_exists( 'quote_calculator_get_customers_total' ) ) {
	function quote_calculator_get_customers_total( $flag_post = true){
		global $wpdb;
		$where = '';
		$table_name = $wpdb->prefix . 'quote_calculator_customers';
		if( isset( $_POST['s'] ) && $flag_post && ! empty( $_POST['s'] ) ){
			$search = wp_unslash( trim( $_POST['s'] ) );
			$where = ' WHERE `customers_given_name` LIKE "%' . $search . '%" OR `customers_family_name` LIKE "%' . $search . '%" OR `customers_display_name` LIKE "%' . $search . '%" ';
		}
		$customers_total = $wpdb->get_var( 'SELECT COUNT( * ) FROM ' . $table_name . $where );
		return $customers_total; 
	}
}

/*
* Function to receive customer's address
*/
if ( ! function_exists( 'quote_calculator_get_customers_address' ) ) {
	function quote_calculator_get_customers_address( $address_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
		$customer_address = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE `customer_address_id` = ' . $address_id, ARRAY_A );
		return $customer_address; 
	}
}

/*
* Function to print customer's address
*/
if ( ! function_exists( 'quote_calculator_print_customers_address' ) ) {
	function quote_calculator_print_customers_address( $address ){
		$customer_address = '';
		if( ! empty( $address ) ){
			$customer_address .= $address['customer_address_line1'] . PHP_EOL;
			if( ! empty( $address['customer_address_line2'] ) ){
				$customer_address .= $address['customer_address_line2'] . PHP_EOL;
			}
			if( ! empty( $address['customer_address_line3'] ) ){
				$customer_address .= $address['customer_address_line3'] . PHP_EOL;
			}
			if( ! empty( $address['customer_address_city'] ) ){
				$customer_address .= $address['customer_address_city'] . ' ';
			}
			if( ! empty( $address['customer_address_country_sub_division_code'] ) ){
				$customer_address .= $address['customer_address_country_sub_division_code'] . ' ';
			}
			if( ! empty( $address['customer_address_postal_code'] ) ){
				$customer_address .= $address['customer_address_postal_code'] . PHP_EOL;
			}
			if( ! empty( $address['customer_address_country'] ) ){
				$customer_address .= $address['customer_address_country'];
			}
		}
		return $customer_address; 
	}
}

/*
* Function to delete customers from the database
*/
if ( ! function_exists( 'quote_calculator_delete_customers' ) ) {
	function quote_calculator_delete_customers( $customers_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_customers';
		$customer_info = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE `customers_id` = ' . $customers_id, ARRAY_A );
		if( false !== $customer_info ){
			$table_name = $wpdb->prefix . 'quote_calculator_customer_address';
			$wpdb->delete( 
				$table_name, 
				array( 'customer_address_id' => $customer_info['customers_bill_addr'] ), 
				array( '%d' )
			);
			$wpdb->delete( 
				$table_name, 
				array( 'customer_address_id' => $customer_info['customers_ship_addr'] ), 
				array( '%d' )
			); 
			$table_name = $wpdb->prefix . 'quote_calculator_customers';
			$wpdb->delete( 
				$table_name, 
				array( 'customers_id' => $customers_id ), 
				array( '%d' )
			);
		}
	}
}

/*
* Function to get estimate from the database
*/
if ( ! function_exists( 'quote_calculator_get_all_delivery_options' ) ) {
	function quote_calculator_get_all_delivery_options(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_delivery';
		$delivery_info = $wpdb->get_results( 'SELECT * FROM ' . $table_name, ARRAY_A );
		return $delivery_info;
	}
}


/*
* Function to get estimate from the database
*/
if ( ! function_exists( 'quote_calculator_get_estimate' ) ) {
	function quote_calculator_get_estimate( $estimate_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimates';
		$estimate_info = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE `estimates_id` = ' . $estimate_id, ARRAY_A );
		return $estimate_info;
	}
}

/*
* Function to get estimate from the database
*/
if ( ! function_exists( 'quote_calculator_get_last_estimate_id' ) ) {
	function quote_calculator_get_last_estimate_id(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimates';
		$estimate_id = $wpdb->get_var( 'SELECT MAX( `estimates_id` ) FROM ' . $table_name . '' );
		return $estimate_id;
	}
}


/*
* Function to get all estimate from the database
*/
if ( ! function_exists( 'quote_calculator_get_all_estimate' ) ) {
	function quote_calculator_get_all_estimate(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimates';
		$estimates_info = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY `estimates_status` ASC', ARRAY_A );
		return $estimates_info;
	}
}

/*
* Function to get estimate sections from the database by estimate id value
*/
if ( ! function_exists( 'quote_calculator_get_estimate_sections' ) ) {
	function quote_calculator_get_estimate_sections( $estimate_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimate_sections';
		$estimate_sections_info = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' WHERE `estimate_sections_estimate_id` = ' . $estimate_id, ARRAY_A );
		return $estimate_sections_info;
	}
}

/*
* Function to get estimate sections from the database by estimate id value
*/
if ( ! function_exists( 'quote_calculator_get_item' ) ) {
	function quote_calculator_get_item( $item_category, $item_name ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_items';
		$parent_id = quote_calculator_get_estimate_item_parent( $item_category );
		$estimate_item_info = $wpdb->get_row( $wpdb->prepare( 'SELECT `items_object_id` FROM ' . $table_name . ' WHERE `items_name` = %s AND `items_parent_ref` = %d', $item_name, $parent_id ), ARRAY_A );
		return $estimate_item_info;
	}
}

/*
* Function to get estimate sections from the database by estimate id value
*/
if ( ! function_exists( 'quote_calculator_get_estimate_item_parent' ) ) {
	function quote_calculator_get_estimate_item_parent( $item_category ){
		switch( $item_category ){
			case 0:
				$parent_id = 75;
				break;
			case 1:
				$parent_id = 366;
				break;
			case 2:
				$parent_id = 519;
				break;
			case 3:
				$parent_id = 78;
				break;
			case 4:
				$parent_id = 520;
				break;
		}
		return $parent_id;
	}
}

/*
* Function to get estimate sections from the database by estimate id value
*/
if ( ! function_exists( 'quote_calculator_add_item' ) ) {
	function quote_calculator_add_item( $item ){
		global $wpdb;
		/*$table_name = $wpdb->prefix . 'quote_calculator_estimate_api_items';
		$wpdb->insert( 
			$table_name, 
			array( 
				'estimates_api_item_object_id'	=> $item->Id,
				'estimates_api_item_name'				=> $item->Name,
				'estimates_api_item_cost'				=> $item->PurchaseCost
			), 
			array( 
				'%d', 
				'%s',
				'%f'
			) 
		);*/
		$table_name = $wpdb->prefix . 'quote_calculator_items';
		$wpdb->insert( 
			$table_name, 
			array(
				'items_object_id'						=> $item->Id,
				'items_name'								=> $item->Name,
				'items_status'							=> $item->Active == 'true' ? 1 : 0,
				'items_income_account_ref'	=> $item->IncomeAccountRef,
				'items_parent_ref'					=> '' != $item->ParentRef ? $item->ParentRef : 0
			),
			array(
				'%d',
				'%s',
				'%d',
				'%d',
				'%d'
			)
		);
	}
}

/*
* Function to get estimate sections from the database by estimate id value
*/
if ( ! function_exists( 'quote_calculator_update_item' ) ) {
	function quote_calculator_update_item( $item ){
		global $wpdb;
		/*$table_name = $wpdb->prefix . 'quote_calculator_estimate_api_items';
		$wpdb->update( 
			$table_name, 
			array( 
				'estimates_api_item_name'				=> $item->Name,
				'estimates_api_item_cost'				=> $item->PurchaseCost
			), 
			array(
				'estimates_api_item_object_id'	=> $item->Id,
			),
			array( 
				'%s',
				'%f'
			),
			array(
				'%d'
			)
		);*/
		$table_name = $wpdb->prefix . 'quote_calculator_items';
		$wpdb->update( 
			$table_name,
			array(
				'items_name'								=> $item->Name,
				'items_status'							=> $item->Active == 'true' ? 1 : 0,
				'items_income_account_ref'	=> $item->IncomeAccountRef,
				'items_parent_ref'					=> '' != $item->ParentRef ? $item->ParentRef : 0
			),
			array( 'items_object_id' => $item->Id ),
			array(
				'%s',
				'%d',
				'%d',
				'%d'
			),
			array( '%d' )
		);
	}
}

/*
* Function to get estimate sections from the database by estimate id value
*/
if ( ! function_exists( 'quote_calculator_add_estimate_api' ) ) {
	function quote_calculator_add_estimate_api( $object_id, $estimate_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimates_api';
		$wpdb->insert( 
			$table_name, 
			array( 
				'estimates_api_object_id'	=> $object_id,
				'estimates_id'						=> $estimate_id
			), 
			array( 
				'%d', 
				'%d'
			)
		);
	}
}

/*
* Function to get estimate sections from the database by estimate id value
*/
if ( ! function_exists( 'quote_calculator_get_estimate_api' ) ) {
	function quote_calculator_get_estimate_api( $estimate_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimates_api';
		$estimate_api_info = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE `estimates_id` = ' . $estimate_id, ARRAY_A );
		return $estimate_api_info;
	}
}


if( ! function_exists( 'array_column' ) ){
	function array_column( $array, $column_title ) {
		return array_map( function( $element ) use( $column_title ){ return $element[ $column_title ]; }, $array );
	}
}

use QuickBooksOnline\API\Facades\Item;

if( ! function_exists( 'quote_calculator_create_estimate' ) ){
	function quote_calculator_create_estimate(){
		global $quote_calculator_options, $quote_calculator_estimate, $quote_calculator_estimate_section, $quote_calculator_estimate_all_sections, $quote_calculator_estimate_customer, $create_estimate_message, $create_estimate_error, $quote_calculator_estimate_all_sections_qty, $quote_calculator_baseUrl, $quote_calculator_estimate_all_sections_tax, $quote_calculator_estimate_all_sections_tax_code;
		require_once( plugin_dir_path( __FILE__ ) . 'quickbooks_oauth/helper/EstimateHelper.php'); 
		require_once( plugin_dir_path( __FILE__ ) . 'quickbooks_oauth/helper/ItemHelper.php');

		if( isset( $_SESSION['accessToken'] ) ){
			$dataService = DataService::Configure( array(
				'auth_mode'				=> 'oauth2',
				'ClientID'				=> $quote_calculator_options['client_id'],
				'ClientSecret'		=> $quote_calculator_options['client_secret'],
				'RedirectURI'			=> admin_url( 'admin.php?page=quote_calculator_oauth' ),
				'accessTokenKey'	=> $_SESSION['accessToken']->getAccessToken(),
				'refreshTokenKey' => $_SESSION['accessToken']->getRefreshToken(),
				'QBORealmID'			=> $_SESSION['accessToken']->getRealmID(),
				'scope'						=> "com.intuit.quickbooks.accounting",
				'baseUrl'					=> $quote_calculator_baseUrl
			) );
			$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

			$accessToken = $OAuth2LoginHelper->refreshToken();
			$dataService->throwExceptionOnError(true);
			$_SESSION['accessToken'] = $quote_calculator_options['accessToken'] = $accessToken;
			update_option( 'quote_calculator_options', $quote_calculator_options );
		} else {
			$dataService = DataService::Configure( array(
				'auth_mode'			=> 'oauth2',
				'ClientID'			=> $quote_calculator_options['client_id'],
				'ClientSecret'	=> $quote_calculator_options['client_secret'],
				'RedirectURI'		=> admin_url( 'admin.php?page=quote_calculator_oauth' ),
				'scope'					=> "com.intuit.quickbooks.accounting",
				'baseUrl'				=> $quote_calculator_baseUrl
			) );
			$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

			$url = $OAuth2LoginHelper->getAuthorizationCodeURL();
		}
		$error = $OAuth2LoginHelper->getLastError();
		if ($error != null) {
				echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
				echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
				echo "The Response message is: " . $error->getResponseBody() . "\n";
				return;
		}
		$dataService->updateOAuth2Token( $accessToken );
		$dataService->throwExceptionOnError( true );
		if ( ! $dataService ){
			$create_estimate_error =  __( 'Problem while initializing DataService.', 'quote_calculator' );
		} else { 
			$quote_calculator_estimate					= quote_calculator_get_estimate( $_GET['quote_calculator_create_invoice'] );
			if( ! empty( $quote_calculator_estimate ) ){
				$quote_calculator_estimate_sections = quote_calculator_get_estimate_sections( $quote_calculator_estimate['estimates_id'] );
				$quote_calculator_estimate_customer = quote_calculator_get_customers_by_id( $quote_calculator_estimate['estimates_customer_id'] );
				$quote_calculator_estimate_customer['bill_addr'] = quote_calculator_get_customers_address( $quote_calculator_estimate_customer['customers_bill_addr'] );
				$quote_calculator_estimate_customer['ship_addr'] = quote_calculator_get_customers_address( $quote_calculator_estimate_customer['customers_ship_addr'] );
				$current_item = array();
				foreach( $quote_calculator_estimate_sections as $section ){
					$quote_calculator_estimate_section = $section;
					$quantities				= @unserialize( $quote_calculator_estimate_section['estimate_section_qty'] );
					$costs						= @unserialize( $quote_calculator_estimate_section['estimate_section_cost_without_vat'] );
					$costs_with_vat		= @unserialize( $quote_calculator_estimate_section['estimate_section_cost'] );
					$covers						= @unserialize( $quote_calculator_estimate_section['estimate_section_cover'] );
					$paper_types			= @unserialize( $quote_calculator_estimate_section['estimate_section_paper_type'] );
					$weights					= @unserialize( $quote_calculator_estimate_section['estimate_section_weight'] );
					//$formats					= @unserialize( $quote_calculator_estimate_section['estimate_section_format'] );
					$finishings				= @unserialize( $quote_calculator_estimate_section['estimate_section_finishing'] );
					if( ! empty( $quantities ) && is_array( $quantities ) ){
						foreach( $quantities as $key => $value ){
							if( isset( $costs[ $key ] ) ) {
								$quote_calculator_estimate_section['estimate_section_single_cost'] = money_format('%.2n', $costs[ $key ] );
							} else {
								$quote_calculator_estimate_section['estimate_section_single_cost'] = money_format('%.2n', $quote_calculator_estimate_section['estimate_section_cost_without_vat'] );
							}							
							$quote_calculator_estimate_section['estimate_section_single_qty'] = $value;
							$quote_calculator_estimate_section['estimate_section_single_name'] = quote_calculator_get_estimate_section_name();
							$quote_calculator_estimate_section['estimate_section_single_description'] = quote_calculator_get_estimate_item_description( $covers, $paper_types, $weights, $finishings );
							$quote_calculator_estimate_section['estimate_section_single_parent'] = quote_calculator_get_estimate_item_parent( $quote_calculator_estimate_section['estimate_section_category'] );
							$item = quote_calculator_get_item( $quote_calculator_estimate_section['estimate_section_category'], $quote_calculator_estimate_section['estimate_section_type'] );
							if( ! empty( $item ) ){
								$item_find = ItemHelper::getItemByID( $dataService, $item['items_object_id'] );
								$dataService->throwExceptionOnError(true);
								if( ! empty( $item_find ) ){
									$item_find->Description = $quote_calculator_estimate_section['estimate_section_single_description'];
									$item_find->UnitPrice = $quote_calculator_estimate_section['estimate_section_single_cost'];
									$quote_calculator_estimate_all_sections[] = $item_find;
								} else {
									$quote_calculator_estimate_all_sections[] = ItemHelper::getItem( $dataService );
									$dataService->throwExceptionOnError(true);
								}
							} else {
								$quote_calculator_estimate_all_sections[] = ItemHelper::getItem( $dataService );
								$dataService->throwExceptionOnError(true);
							}
							$quote_calculator_estimate_all_sections_qty[] = $quote_calculator_estimate_section['estimate_section_single_qty'];
							$quote_calculator_estimate_all_sections_tax[] = isset( $costs_with_vat[ $key ] ) ? $costs_with_vat[ $key ] - $quote_calculator_estimate_section['estimate_section_single_cost'] : $quote_calculator_estimate_section['estimate_section_cost'] - $quote_calculator_estimate_section['estimate_section_cost_without_vat'];
							$quote_calculator_estimate_all_sections_tax_code[] = '13';
						}
					} else {
						$quote_calculator_estimate_section['estimate_section_single_cost'] = money_format('%.2n', $quote_calculator_estimate_section['estimate_section_cost_without_vat'] );
						$quote_calculator_estimate_section['estimate_section_single_qty'] = $quote_calculator_estimate_section['estimate_section_qty'];
						$quote_calculator_estimate_section['estimate_section_single_name'] = quote_calculator_get_estimate_section_name();
						$quote_calculator_estimate_section['estimate_section_single_description'] = quote_calculator_get_estimate_item_description( $covers, $paper_types, $weights, $finishings );
						$quote_calculator_estimate_section['estimate_section_single_parent'] = quote_calculator_get_estimate_item_parent( $quote_calculator_estimate_section['estimate_section_category'] );
						$item = quote_calculator_get_item( $quote_calculator_estimate_section['estimate_section_category'], $quote_calculator_estimate_section['estimate_section_type'] );
						if( ! empty( $item ) ){
							$item_find = ItemHelper::getItemByID( $dataService, $item['items_object_id'] );
							$dataService->throwExceptionOnError(true);
							if( ! empty( $item_find ) ){
								$item_find->Description = $quote_calculator_estimate_section['estimate_section_single_description'];
								$item_find->UnitPrice = $quote_calculator_estimate_section['estimate_section_single_cost'];
								$quote_calculator_estimate_all_sections[] = $item_find;
							} else {
								$quote_calculator_estimate_all_sections[] = ItemHelper::getItem( $dataService );
								$dataService->throwExceptionOnError(true);
							}
						} else {
							$quote_calculator_estimate_all_sections[] = ItemHelper::getItem( $dataService );
							$dataService->throwExceptionOnError(true);
						}
						$quote_calculator_estimate_all_sections_qty[] = $quote_calculator_estimate_section['estimate_section_single_qty'];
						$quote_calculator_estimate_all_sections_tax[] = $quote_calculator_estimate_section['estimate_section_cost'] - $quote_calculator_estimate_section['estimate_section_single_cost'];
						$quote_calculator_estimate_all_sections_tax_code[] = '13';
					}				
				}
				if( 'Tracked Courier' == $quote_calculator_estimate['estimates_delivery'] ){
					$item = quote_calculator_get_item( 0, $quote_calculator_estimate['estimates_delivery'] );
					$item_find = ItemHelper::getItemByID( $dataService, $item['items_object_id'] );
					$dataService->throwExceptionOnError(true);
					$item_find->UnitPrice = 12.50;
					$quote_calculator_estimate_all_sections[] = $item_find;
					$quote_calculator_estimate_all_sections_qty[] = 1;
					$quote_calculator_estimate_all_sections_tax[] = 0;
					$quote_calculator_estimate_all_sections_tax_code[] = '13';
				} else if( 'Royal Mail' == $quote_calculator_estimate['estimates_delivery'] ){
					$item = quote_calculator_get_item( 0, $quote_calculator_estimate['estimates_delivery'] );
					$item_find = ItemHelper::getItemByID( $dataService, $item['items_object_id'] );
					$dataService->throwExceptionOnError(true);
					$item_find->UnitPrice = 4.95;
					$quote_calculator_estimate_all_sections[] = $item_find;
					$quote_calculator_estimate_all_sections_qty[] = 1;
					$quote_calculator_estimate_all_sections_tax[] = 0;
					$quote_calculator_estimate_all_sections_tax_code[] = '13';
				} else if( 'Collection' == $quote_calculator_estimate['estimates_delivery'] ){
					$item = quote_calculator_get_item( 0, $quote_calculator_estimate['estimates_delivery'] );
					$item_find = ItemHelper::getItemByID( $dataService, $item['items_object_id'] );
					$dataService->throwExceptionOnError(true);
					$item_find->UnitPrice = 0.0;
					$quote_calculator_estimate_all_sections[] = $item_find;
					$quote_calculator_estimate_all_sections_qty[] = 1;
					$quote_calculator_estimate_all_sections_tax[] = 0;
					$quote_calculator_estimate_all_sections_tax_code[] = '13';
				} else if( 'Company Van' == $quote_calculator_estimate['estimates_delivery'] ){
					$item = quote_calculator_get_item( 0, $quote_calculator_estimate['estimates_delivery'] );
					$item_find = ItemHelper::getItemByID( $dataService, $item['items_object_id'] );
					$dataService->throwExceptionOnError(true);
					$item_find->UnitPrice = 0.0;
					$quote_calculator_estimate_all_sections[] = $item_find;
					$quote_calculator_estimate_all_sections_qty[] = 1;
					$quote_calculator_estimate_all_sections_tax[] = 0;
					$quote_calculator_estimate_all_sections_tax_code[] = '13';
				}
				//echo '<pre>'; var_dump($quote_calculator_estimate_all_sections, EstimateHelper::getEstimateFields( $dataService )); die();
				$resultingEstimateObj = $dataService->Add( EstimateHelper::getEstimateFields( $dataService ) );
				$dataService->throwExceptionOnError(true);
				if( ! $resultingEstimateObj ){
					$create_estimate_error =  __( 'Problem while creating Estimate.', 'quote_calculator' );
				} else {
					quote_calculator_add_estimate_api( $resultingEstimateObj->Id, $quote_calculator_estimate['estimates_id'] );
					$create_estimate_message = __( 'Estimate successfully added', 'quote_calculator' );
				}
			} else {
				$create_estimate_error =  __( 'No estimate found', 'quote_calculator' );
			}
		}
	}
}

if( ! function_exists( 'quote_calculator_create_pdf' ) ){
	function quote_calculator_create_pdf(){	
		if( isset( $_GET['quote_calculator_create_pdf'] ) && is_numeric( $_GET['quote_calculator_create_pdf'] ) ){
			global $flag_header, $flag_footer, $status;
			$estimate = quote_calculator_get_estimate( $_GET['quote_calculator_create_pdf'] );
			if( ! empty( $estimate ) ){
				require_once( plugin_dir_path( __FILE__ ) . 'fpdf/fpdf.php' );
				$estimate_sections = quote_calculator_get_estimate_sections( $estimate['estimates_id'] );
				$customer = quote_calculator_get_customers_by_id( $estimate['estimates_customer_id'] );
				$bill_address = quote_calculator_get_customers_address( $customer['customers_bill_addr'] );
				$ship_address = quote_calculator_get_customers_address( $customer['customers_ship_addr'] );

				$status = $estimate['estimates_status'];
				$flag_header = false;
				$flag_footer = false;

				$customer_address = array();
				if( ! empty( $bill_address['customer_address_line1'] ) ){
					$customer_address[] = stripslashes( $bill_address['customer_address_line1'] );
				}
				if( ! empty( $bill_address['customer_address_line2'] ) ){
					$customer_address[] = stripslashes( $bill_address['customer_address_line2'] );
				}
				if( ! empty( $bill_address['customer_address_line3'] ) ){
					$customer_address[] = stripslashes( $bill_address['customer_address_line3'] );
				}
				if( ! empty( $bill_address['customer_address_city'] ) ){
					$customer_address[] = stripslashes( $bill_address['customer_address_city'] );
				}
				if( ! empty( $bill_address['customer_address_country'] ) ){
					$customer_address[] = stripslashes( $bill_address['customer_address_country'] );
				}
				if( ! empty( $bill_address['customer_address_country_sub_division_code'] ) ){
					$customer_address[] = stripslashes( $bill_address['customer_address_country_sub_division_code'] );
				}
				if( ! empty( $bill_address['customer_address_postal_code'] ) ){
					$customer_address[] = stripslashes( $bill_address['customer_address_postal_code'] );
				}
				
				$customer_address_2 = array();
				if( ! empty( $ship_address['customer_address_line1'] ) ){
					$customer_address_2[] = stripslashes( $ship_address['customer_address_line1'] );
				}
				if( ! empty( $ship_address['customer_address_line2'] ) ){
					$customer_address_2[] = stripslashes( $ship_address['customer_address_line2'] );
				}
				if( ! empty( $ship_address['customer_address_line3'] ) ){
					$customer_address_2[] = stripslashes( $ship_address['customer_address_line3'] );
				}
				if( ! empty( $ship_address['customer_address_city'] ) ){
					$customer_address_2[] = stripslashes( $ship_address['customer_address_city'] );
				}
				if( ! empty( $ship_address['customer_address_country'] ) ){
					$customer_address_2[] = stripslashes( $ship_address['customer_address_country'] );
				}
				if( ! empty( $ship_address['customer_address_country_sub_division_code'] ) ){
					$customer_address_2[] = stripslashes( $ship_address['customer_address_country_sub_division_code'] );
				}
				if( ! empty( $ship_address['customer_address_postal_code'] ) ){
					$customer_address_2[] = stripslashes( $ship_address['customer_address_postal_code'] );
				}

				global $qc_dashboard_options;
				$qc_dashboard_options = get_option( 'qc_dashboard_options' );

				class PDF extends FPDF{
					// Page header
					function Header()	{
						global $flag_header, $status, $qc_dashboard_options;
						if( false === $flag_header ){
							$this->SetFont('Arial', '', 10);
							// Logo
							$this->Image( plugin_dir_path( __FILE__ ) . 'images/PathwayMIS_Logo_Colour-1.jpg', 10, 10, 45 );
							$this->SetTextColor( 0, 0, 0 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_name'], 0, 1 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_address_line1'], 0, 1 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_address_line2'], 0, 1 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_address_line3'], 0, 1 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_address_city'], 0, 1 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_address_location'], 0, 1 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_address_postal_code'], 0, 1 );
							$this->SetFont( 'Arial', '', 20 );
							$this->SetTextColor( 26, 8, 210 );
							if( 'estimate' == $status ) {
								$this->Cell( 35, 10, 'ESTIMATE', 0, 0, 'C' );
								$this->Cell( 100 );
							} elseif ( 'sales' == $status ) {
								$this->Cell( 53, 10, 'SALES ORDER', 0, 0, 'C' );
								$this->Cell( 82 );
							} elseif ( 'delivery_note' == $status ) {
								$this->Cell( 58, 10, 'DELIVERY NOTE', 0, 0, 'C' );
								$this->Cell( 77 );
							}
							$this->SetFont('Arial', '', 10);
							$this->SetTextColor( 0, 0, 0 );
							$this->Cell( 50, 5, 'Tel:  ' . $qc_dashboard_options['company_details']['phone'], 0, 1 );
							$this->Cell( 135 );
							$this->Cell( 50, 5, $qc_dashboard_options['company_details']['company_url'], 0, 1 );
							$this->Ln(5);
							$flag_header = true;
						}
					}
					// Page footer
					function Footer(){
						global $flag_footer, $qc_dashboard_options;
						if( true === $flag_footer ) {
							$this->SetY(-25);
							$this->SetFont( 'Arial', '', 10 );
							$this->SetTextColor( 26, 8, 210 );
							$this->Cell( 0, 5, $qc_dashboard_options['company_details']['company_name'], 0, 1, 'C' );
							$this->SetFont( 'Arial', '', 8 );
							$this->SetTextColor( 128, 128, 128 );
							$this->Cell( 0, 5, $qc_dashboard_options['company_details']['company_address_line1'] . ', ' . $qc_dashboard_options['company_details']['company_address_line2'] . ', ' . $qc_dashboard_options['company_details']['company_address_line3'] . ', ' . $qc_dashboard_options['company_details']['company_address_city'] . ', ' . $qc_dashboard_options['company_details']['company_address_location'] . ', ' . $qc_dashboard_options['company_details']['company_address_postal_code'], 0, 1, 'C' );
							$this->Cell( 0, 5, $qc_dashboard_options['company_details']['phone'] . '         ' . $qc_dashboard_options['company_details']['email'] . '          ' . $qc_dashboard_options['company_details']['company_url'], 0, 1, 'C' );
							$this->SetFont( 'Arial', '', 7 );
							$this->Cell( 0, 5, 'Company Address: '. $qc_dashboard_options['company_details']['registered_address_line1'] . ', ' . ( ! empty( $qc_dashboard_options['company_details']['registered_address_line2'] ) ? $qc_dashboard_options['company_details']['registered_address_line2'] . ', ' : '' ) . ( ! empty( $qc_dashboard_options['company_details']['registered_address_line3'] ) ? $qc_dashboard_options['company_details']['registered_address_line3'] . ', ' : '' ) . $qc_dashboard_options['company_details']['registered_address_city'] . ', ' . $qc_dashboard_options['company_details']['registered_address_location'] . ', ' . $qc_dashboard_options['company_details']['registered_address_postal_code'], 0, 1, 'C' );
							$this->Cell( 0, 5, 'Company Registration Number ' . $qc_dashboard_options['company_details']['registration_number'] . '     Registered in England and Wales.   VAT No: ' . $qc_dashboard_options['company_details']['vat_number'], 0, 1, 'C' );

						} else {
							$this->SetY(-15);
							// Select Arial italic 8
							$this->SetFont('Arial','',8);
							$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
						}
					}
				}

				// Instanciation of inherited class
				$pdf = new PDF();
				$pdf->AliasNbPages();
				$pdf->SetAutoPageBreak( true, 25 );
				$pdf->AddPage();
				$pdf->SetFont( 'Arial', '', 14 );
				$pdf->SetTextColor( 26, 8, 210 );
				if( 'estimate' == $status ) {
					$pdf->Cell( 135 );
					$pdf->Cell( 50, 6, 'ESTIMATE', 0, 1 );
				} elseif ( 'sales' == $status ) {
					$pdf->Cell( 135 );
					$pdf->Cell( 50, 6, 'SALES ORDER', 0, 1 );
				} elseif ( 'delivery_note' == $status ) {
					$pdf->Cell( 67, 6, 'INVOICE ADDRESS', 0, 0 );
					$pdf->Cell( 68, 6, 'DELIVERY ADDRESS', 0, 0 );
					$pdf->Cell( 50, 6, 'DELIVERY NOTE', 0, 1 );
				}
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->SetTextColor( 0, 0, 0 );
				$pdf->Cell( 60, 5, stripslashes( $estimate['estimates_customer_name'] ), 0, 0 );
				if( 'delivery_note' == $status ){
					$pdf->Cell( 7 );
					$pdf->Cell( 68,5, stripslashes( $estimate['estimates_customer_name'] ), 0, 0 );
				} else {
					$pdf->Cell( 75 );
				}
				
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->Cell( 50, 5, 'Ref No: ' . $estimate['estimates_reference_no'], 0, 1 );
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->Cell( 60, 5, isset( $customer_address[0] ) ? $customer_address[0] : '', 0, 0 );
				if( 'delivery_note' == $status ){
					$pdf->Cell( 7 );
					$pdf->Cell( 68, 5, isset( $customer_address_2[0] ) ? $customer_address_2[0] : '', 0, 0 );
				} else {
					$pdf->Cell( 75 );
				}
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->Cell( 50, 5, 'Date: ' . date( 'd/m/Y', $estimate['estimates_date'] ), 0, 1 );
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->Cell( 60, 5, isset( $customer_address[1] ) ? $customer_address[1] : '', 0, 0 );
				if( 'delivery_note' == $status ){
					$pdf->Cell( 7 );
					$pdf->Cell( 68, 5, isset( $customer_address_2[1] ) ? $customer_address_2[1] : '', 0, 0 );
				} else {
					$pdf->Cell( 75 );
				}
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->Cell( 50, 5, 'Contact: ' . $estimate['estimates_prepared'], 0, 1 );
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->Cell( 60, 5, isset( $customer_address[2] ) ? $customer_address[2] : '', 0, 0 );
				if( 'delivery_note' == $status ){
					$pdf->Cell( 7 );
					$pdf->Cell( 68, 5, isset( $customer_address_2[2] ) ? $customer_address_2[2] : '', 0, 0 );
				} else {
					$pdf->Cell( 75 );
				}
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->Cell( 50, 5, 'Tel: ' . $qc_dashboard_options['company_details']['phone'], 0, 1 );
				$pdf->SetFont( 'Arial', 'B', 10 );
				for( $i = 3; $i < count( $customer_address ); $i++ ) {
					if( 'delivery_note' == $status ){
						$pdf->Cell( 60, 5, $customer_address[ $i ], 0, 0 );
						$pdf->Cell( 7 );
						$pdf->Cell( 68, 5, isset( $customer_address_2[ $i ] ) ? $customer_address_2[ $i ] : '', 0, 1 );
					} else {
						$pdf->Cell( 60, 5, $customer_address[ $i ], 0, 1 );
					}
				}
				$pdf->Ln( 5 );
				$pdf->SetFont( 'Arial', '', 10 );
				if ( 'delivery_note' != $status ) {
					$pdf->Cell( 200, 8, 'Thank you for your recent enquiry in relation to the following job, I am pleased to quote as follows:', 0, 1 );
				} else {
					$pdf->Ln( 2 );
					$pdf->SetDrawColor( 200, 200, 200 );
					$pdf->Cell( 185, 0.8, '', 'B', 1, 'C' );
					$pdf->Ln( 3 );
				}
				$pdf->Ln( 4 );
				foreach( $estimate_sections as $estimate_section ) {
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->Cell( 30, 6, 'Title:', 0, 0 );
					$pdf->SetFont( 'Arial', '', 10 );
					if( ! empty( $estimate_section['estimate_section_title'] ) ){
						$pdf->Cell( 70, 6, $estimate_section['estimate_section_title'], 0, 0 );
					} else {
						if( 3 == $estimate_section['estimate_section_category'] ){
							$pdf->Cell( 70, 6, 'Design: ' . $estimate_section['estimate_section_type'], 0, 0 );
						} else if( 3 == $estimate_section['estimate_section_category'] ){
							$pdf->Cell( 70, 6, 'Design: ' . $estimate_section['estimate_section_type'], 0, 0 );
						} else {
							$pdf->Cell( 70, 6, $estimate_section['estimate_section_type'], 0, 0 );
						}
					}
					if ( 'delivery_note' != $status ) {
						$pdf->SetFont( 'Arial', 'B', 10 );
						$pdf->Cell( 119, 6, urldecode( 'Price (%A3)' ), 0, 1 );
					} else{
						$pdf->Cell( 0, 6, '', 0, 1 );
					}
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->Cell( 30, 6, 'Product:', 0, 0 );
					$pdf->SetFont( 'Arial', '', 10 );
					if( 3 == $estimate_section['estimate_section_category'] ){
						$pdf->Cell( 70, 6, 'Design: ' . $estimate_section['estimate_section_type'], 0, 1 );
					} else if( 3 == $estimate_section['estimate_section_category'] ){
						$pdf->Cell( 70, 6, 'Design: ' . $estimate_section['estimate_section_type'], 0, 1 );
					} else {
						$pdf->Cell( 70, 6, $estimate_section['estimate_section_type'], 0, 1 );
					}
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->Cell( 30, 6, 'Size/spec:', 0, 0 );
					$pdf->SetFont( 'Arial', '', 10 );
					$pdf->Cell( 80, 6, $estimate_section['estimate_section_size'], 0, 1 );
					if( 'delivery_note' != $status ){
						$pdf->SetFont( 'Arial', 'B', 10 );
						$pdf->Cell( 30, 6, 'Sided:', 0, 0 );
						$pdf->SetFont( 'Arial', '', 10 );
						$pdf->Cell( 80, 6, $estimate_section['estimate_section_sided'], 0, 1 );
						if( ! empty( $estimate_section['estimate_section_color'] ) ){
							$pdf->SetFont( 'Arial', 'B', 10 );
							$pdf->Cell( 30, 6, 'Printing:', 0, 0 );
							$pdf->SetFont( 'Arial', '', 10 );
							$pdf->Cell( 80, 6, $estimate_section['estimate_section_color'], 0, 1 );
						}
						if( ! empty( $estimate_section['estimate_section_page_count'] ) ){
							$pdf->SetFont( 'Arial', 'B', 10 );
							$pdf->Cell( 30, 6, 'Page Count:', 0, 0 );
							$pdf->SetFont( 'Arial', '', 10 );
							$pdf->Cell( 80, 6, $estimate_section['estimate_section_page_count'], 0, 1 );
						}
						if( ! empty( $estimate_section['estimate_section_orientation'] ) ){
							$pdf->SetFont( 'Arial', 'B', 10 );
							$pdf->Cell( 30, 6, 'Orientation:', 0, 0 );
							$pdf->SetFont( 'Arial', '', 10 );
							$pdf->Cell( 80, 6, ucfirst( $estimate_section['estimate_section_orientation'] ), 0, 1 );
						}
						if( ! empty( $estimate_section['estimate_section_cover'] ) ){
							$pdf->SetFont( 'Arial', 'B', 10 );
							$pdf->Cell( 30, 6, 'Materials:', 0, 0 );
							$pdf->SetFont( 'Arial', '', 10 );
							$estimate_section['estimate_section_cover']				= unserialize( $estimate_section['estimate_section_cover'] );
							$estimate_section['estimate_section_paper_type']	= unserialize( $estimate_section['estimate_section_paper_type'] );
							$estimate_section['estimate_section_weight']			= unserialize( $estimate_section['estimate_section_weight'] );
							foreach( $estimate_section['estimate_section_cover'] as $key => $value ){
								if( $key != 0 ){
									$pdf->Cell( 30 );
								}
								$pdf->Cell( 80, 6, $estimate_section['estimate_section_cover'][ $key ] . ' - ' . $estimate_section['estimate_section_weight'][ $key ] . ' ' . $estimate_section['estimate_section_paper_type'][ $key ], 0, 1 );
							}
						}
						if( ! empty( $estimate_section['estimate_section_finishing'] ) ){
							$estimate_section['estimate_section_finishing'] = unserialize( $estimate_section['estimate_section_finishing'] );
							if( 0 < count( $estimate_section['estimate_section_finishing'] ) && '' != $estimate_section['estimate_section_finishing'][0] ) {
								$pdf->SetFont( 'Arial', 'B', 10 );
								$pdf->Cell( 30, 6, 'Finishing:', 0, 0 );
								$pdf->SetFont( 'Arial', '', 10 );
								$pdf->Cell( 80, 6, implode( ', ', $estimate_section['estimate_section_finishing'] ), 0, 1 );
								//$pdf->Ln( 6 );
							}
						}
					}
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->Cell( 30, 6, 'Quantity', 0, 0 );
					$estimate_section['estimate_section_qty']	 = @unserialize( $estimate_section['estimate_section_qty'] ) === false ? array( $estimate_section['estimate_section_qty'] ) : unserialize( $estimate_section['estimate_section_qty'] );
					$estimate_section['estimate_section_cost_without_vat'] = @unserialize( $estimate_section['estimate_section_cost_without_vat'] ) === false ? array( $estimate_section['estimate_section_cost_without_vat'] ) : unserialize( $estimate_section['estimate_section_cost_without_vat'] );
					$estimate_section['estimate_section_cost'] = @unserialize( $estimate_section['estimate_section_cost'] ) === false ? array( $estimate_section['estimate_section_cost'] ) : unserialize( $estimate_section['estimate_section_cost'] );
					$pdf->SetFont( 'Arial', '', 10 );
					foreach( $estimate_section['estimate_section_qty'] as $key => $value ){
						if( $key != 0 ){
							$pdf->Cell( 30 );
						}
						if ( 'delivery_note' != $status ) {	
							$pdf->SetFont( 'Arial', '', 10 );
							$pdf->Cell( 10, 6, ( ! empty( $value ) ? $value : 1 ) , 0, 0 );
							$pdf->Cell( 60);
							$pdf->Cell( 20, 6, urldecode( '%A3' ) . number_format( $estimate_section['estimate_section_cost_without_vat'][ $key ], 2 ) . ' ' );
							//$pdf->Cell( 17, 6, urldecode( '%A3' ) . number_format( $estimate_section['estimate_section_cost_without_vat'][ $key ], 2 ), 0, 0 );
							$pdf->SetFont( 'Arial', '', 8 );
							if( 'estimate' == $status ) {
								if( number_format( $estimate_section['estimate_section_cost_without_vat'][ $key ], 2 ) != number_format( $estimate_section['estimate_section_cost'][ $key ], 2 ) ) {
									$pdf->Cell( 20, 6, '( ' . urldecode( '%A3' ) . number_format( $estimate_section['estimate_section_cost'][ $key ], 2 ) . ' incl vat )', 0, 1);
								} else {
									$pdf->Cell( 20, 6, '(' . urldecode( '%A3' ) . number_format( $estimate_section['estimate_section_cost'][ $key ], 2 ) . ' 0.0% Z )', 0, 1);
								}
							} elseif ( 'sales' == $status ) {
								if( number_format( $estimate_section['estimate_section_cost_without_vat'][ $key ], 2 ) != number_format( $estimate_section['estimate_section_cost'][ $key ], 2 ) ) {
									$pdf->Cell( 20, 6, '( ' . urldecode( '%A3' ) . number_format( $estimate_section['estimate_section_cost'][ $key ], 2 ) . ' incl vat )', 0, 1);
								} else {
									$pdf->Cell( 20, 6, '( 0.0% Z )', 0, 1);
								}
							}
						} else {
							$pdf->Cell( 10, 6, ( ! empty( $value ) ? $value : 1 ) , 0, 1 );
						}
					}

					$pdf->Ln( 6 );
					$pdf->SetDrawColor( 200, 200, 200 );
					$pdf->Cell( 185, 0.8, '', 'B', 1, 'C' );
					$pdf->Ln( 10 );
				}
				/* Comment this for turn off Total in PDF */
				if( ! isset( $_GET['without_total'] ) && 'delivery_note' != $status){
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->Cell( 30, 6, 'Total Cost:', 0, 0 );
					if ( 'delivery_note' != $status ) {
						$pdf->Cell( 70 );
						$pdf->Cell( 30, 6, urldecode( 'Price (%A3)' ), 0, 1 );
					} else{
						$pdf->Cell( 0, 6, '', 0, 1 );
					}
					$pdf->Ln( 3 );
					$pdf->Cell( 100 );
					$pdf->SetFont( 'Arial', '', 10 );
					$pdf->Cell( 20, 6, urldecode( '%A3' ) . number_format( $estimate['estimates_cost'], 2 ), 0, 0 );
					$pdf->SetFont( 'Arial', '', 8 );
					if( 'estimate' == $status ) {
						if( number_format( $estimate['estimates_cost'], 2 ) != number_format( $estimate['estimates_cost_incl_vat'], 2 ) ) {
							$pdf->Cell( 20, 6, '( ' . urldecode( '%A3' ) . number_format( $estimate['estimates_cost_incl_vat'], 2 ) . ' incl vat )', 0, 1);
						} else {
							$pdf->Cell( 20, 6, '( ' . urldecode( '%A3' ) . number_format( $estimate['estimates_cost_incl_vat'], 2 ) . ' 0.0% Z )', 0, 1);
						}
					} elseif ( 'sales' == $status ) {
						if( number_format( $estimate['estimates_cost'], 2 ) != number_format( $estimate['estimates_cost_incl_vat'], 2 ) ) {
							$pdf->Cell( 20, 6, '( ' . urldecode( '%A3' ) . number_format( $estimate['estimates_cost_incl_vat'], 2 ) . ' incl vat )', 0, 1);
						} else {
							$pdf->Cell( 20, 6, '( 0.0% Z )', 0, 1);
						}
					}
					$pdf->Ln( 3 );
					$pdf->Cell( 185, 0.8, '', 'B', 1, 'C' );
					$pdf->Ln( 10 );
				}
				/* End comment for turn off Total in PDF */
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->Cell( 30, 6, 'Deadline:', 0, 0 );
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->Cell( 80, 6, 0 < $estimate['estimates_deadline'] ? date( 'd/m/Y', $estimate['estimates_deadline'] ) : '', 0, 1 );
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->Cell( 30, 6, 'Delivery:', 0, 0 );
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->Cell( 80, 6, $estimate['estimates_delivery'], 0, 1 );
				if ( 'delivery_note' != $status ) {
					if( ! empty( $ship_address['customer_address_line1'] ) ){
						$pdf->SetFont( 'Arial', 'B', 10 );
						$pdf->Cell( 30, 6, 'Ship Address:', 0, 0 );
						$pdf->SetFont( 'Arial', '', 10 );
						$pdf->Cell( 80, 6, stripslashes( $ship_address['customer_address_line1'] ), 0, 1 );
						if( ! empty( $ship_address['customer_address_line2'] ) ){
							$pdf->Cell( 30, 6, '', 0, 0 );
							$pdf->Cell( 80, 6, stripslashes( $ship_address['customer_address_line2'] ), 0, 1 );
						}
						if( ! empty( $ship_address['customer_address_line3'] ) ){
							$pdf->Cell( 30, 6, '', 0, 0 );
							$pdf->Cell( 80, 6, stripslashes( $ship_address['customer_address_line3'] ), 0, 1 );
						}
						if( ! empty( $ship_address['customer_address_city'] ) ){
							$pdf->Cell( 30, 6, '', 0, 0 );
							$pdf->Cell( 80, 6, stripslashes( $ship_address['customer_address_city'] ), 0, 1 );
						}
						if( ! empty( $ship_address['customer_address_country'] ) ){
							$pdf->Cell( 30, 6, '', 0, 0 );
							$pdf->Cell( 80, 6, stripslashes( $ship_address['customer_address_country'] ), 0, 1 );
						}
						if( ! empty( $ship_address['customer_address_country_sub_division_code'] ) ){
							$pdf->Cell( 30, 6, '', 0, 0 );
							$pdf->Cell( 80, 6, stripslashes( $ship_address['customer_address_country_sub_division_code'] ), 0, 1 );
						}
						if( ! empty( $ship_address['customer_address_postal_code'] ) ){
							$pdf->Cell( 30, 6, '', 0, 0 );
							$pdf->Cell( 80, 6, stripslashes( $ship_address['customer_address_postal_code'] ), 0, 1 );
						}
					}
				}
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->Cell( 30, 6, 'Notes:', 0, 0 );
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->MultiCell( 155, 6, stripcslashes($estimate['estimates_notes']), 0, 1 );
				$pdf->SetFont( 'Arial', 'B', 10 );
				$pdf->Cell( 30, 6, 'Memo:', 0, 0 );
				$pdf->SetFont( 'Arial', '', 10 );
				$pdf->MultiCell( 155, 6, $estimate['estimates_memo'], 0, 1 );
				if ( 'delivery_note' != $status ) {			
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->Cell( 30, 6, 'Terms:', 0, 0 );
					$pdf->SetFont( 'Arial', '', 10 );
					$pdf->Cell( 80, 6, $estimate['estimates_terms'], 0, 1 );
					$pdf->Ln( 5 );
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->MultiCell( 0, 4, 'This quote is exclusive of  VAT, which will be charged at the standard rate where applicable.', 0, 1 );
					$pdf->Ln(3);
				} else {
					$pdf->Ln(10);
					$pdf->SetDrawColor( 200, 200, 200 );
					$pdf->Cell( 185, 0.8, '', 'B', 1, 'C' );
					$pdf->Ln(18);
					$pdf->SetFont( 'Arial', '', 14 );
					$pdf->SetTextColor( 26, 8, 210 );
					$pdf->Cell( 60, 10, 'CUSTOMER SIGNATURE', 0, 1, 'C' );
					$pdf->SetTextColor( 0, 0, 0 );
					$pdf->SetFont( 'Arial', 'B', 10 );
					$pdf->SetDrawColor( 0, 0, 0 );
					$pdf->Rect( 10, $pdf->GetY(), 6, 6 );
					$pdf->Cell( 7 );
					$pdf->Cell( 35, 7, 'Packaging Check', 0, 1 );
					$pdf->Ln(5);
					$pdf->Rect( 10, $pdf->GetY(), 6, 6 );
					$pdf->Cell( 7 );
					$pdf->Cell( 35, 7, 'Product Check', 0, 0 );
					$pdf->Cell( 80, 7, 'Signed', 0, 0 );
					$pdf->Line( 67, $pdf->GetY() + 4, 127, $pdf->GetY() + 4 );
					$pdf->Cell( 60, 7, 'Date', 0, 0 );
					$pdf->Line( 143, $pdf->GetY() + 4, 195, $pdf->GetY() + 4 );
				}
				$flag_footer = true;
				$pdf->Output( $estimate['estimates_id'] . '.pdf', 'D' );
				die();
			}
		}
	}
}

if( ! function_exists( 'quote_calculator_duplicate_estimate' ) ){
	function quote_calculator_duplicate_estimate(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimates';
		$wpdb->query('INSERT INTO `' . $table_name . '` ( 
			`estimates_reference_no`,
			`estimates_date`,
			`estimates_prepared`,
			`estimates_customer_id`,
			`estimates_customer_name`,
			`estimates_customer_email`,
			`estimates_customer_phone`,
			`estimates_customer_bill_address_id`,
			`estimates_customer_ship_address_id`,
			`estimates_deadline`,
			`estimates_delivery`,
			`estimates_notes`,
			`estimates_memo`,
			`estimates_terms`,
			`estimates_status`,
			`estimates_cost`,
			`estimates_cost_incl_vat` )
				 SELECT 
					`estimates_reference_no`,
					`estimates_date`,
					`estimates_prepared`,
					`estimates_customer_id`,
					`estimates_customer_name`,
					`estimates_customer_email`,
					`estimates_customer_phone`,
					`estimates_customer_bill_address_id`,
					`estimates_customer_ship_address_id`,
					`estimates_deadline`,
					`estimates_delivery`,
					`estimates_notes`,
					`estimates_memo`,
					`estimates_terms`,
					`estimates_status`,
					`estimates_cost`,
					`estimates_cost_incl_vat`		
				 FROM `' . $table_name . '`
				 WHERE `estimates_id` = ' . $_GET['quote_calculator_duplicate_estimate'] . ';');
		$id = $wpdb->insert_id;
		$wpdb->update( 
			$table_name,
			array( 'estimates_reference_no' => $id ),
			array( 'estimates_id' => $id ),
			array( '%d' ),
			array( '%d' )
		);
		$table_name = $wpdb->prefix . 'quote_calculator_estimate_sections';
		$wpdb->query('INSERT INTO `' . $table_name . '` ( 
			`estimate_section_category`,
			`estimate_section_type`,
			`estimate_section_title`,
			`estimate_section_size`,
			`estimate_section_sided`,
			`estimate_section_qty`,
			`estimate_section_color`,
			`estimate_section_page_count`,
			`estimate_section_orientation`,
			`estimate_section_cover`,
			`estimate_section_paper_type`,
			`estimate_section_weight`,
			`estimate_section_format`,
			`estimate_section_finishing`,
			`estimate_section_suplier`,
			`estimate_section_supplier_price`,
			`estimate_section_rate`,
			`estimate_section_hours`,
			`estimate_section_total`,
			`estimate_section_overhead`,
			`estimate_section_profit`,
			`estimate_section_vat`,
			`estimate_section_cost`,
			`estimate_section_cost_without_vat`,
			`estimate_sections_estimate_id` )
				 SELECT 
					`estimate_section_category`,
					`estimate_section_type`,
					`estimate_section_title`,
					`estimate_section_size`,
					`estimate_section_sided`,
					`estimate_section_qty`,
					`estimate_section_color`,
					`estimate_section_page_count`,
					`estimate_section_orientation`,
					`estimate_section_cover`,
					`estimate_section_paper_type`,
					`estimate_section_weight`,
					`estimate_section_format`,
					`estimate_section_finishing`,
					`estimate_section_suplier`,
					`estimate_section_supplier_price`,
					`estimate_section_rate`,
					`estimate_section_hours`,
					`estimate_section_total`,
					`estimate_section_overhead`,
					`estimate_section_profit`,
					`estimate_section_vat`,
					`estimate_section_cost`,
					`estimate_section_cost_without_vat`,
					' . $id . ' AS `estimate_sections_estimate_id`
				 FROM `' . $table_name . '`
				 WHERE `estimate_sections_estimate_id` = ' . $_GET['quote_calculator_duplicate_estimate'] . ';');
		return $id; 
	}
}

if( ! function_exists( 'quote_calculator_delete_estimate' ) ){
	function quote_calculator_delete_estimate(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'quote_calculator_estimate_sections';		
		$wpdb->delete( $table_name, array( 'estimate_sections_estimate_id' => $_GET['quote_calculator_delete_estimate'] ), array( '%d' ) );
		$table_name = $wpdb->prefix . 'quote_calculator_estimates';		
		$wpdb->delete( $table_name, array( 'estimates_id' => $_GET['quote_calculator_delete_estimate'] ), array( '%d' ) );
	}
}

if( ! function_exists( 'quote_calculator_get_estimate_section_name' ) ){
	function quote_calculator_get_estimate_section_name(){	
		global $quote_calculator_estimate_section;
		return $quote_calculator_estimate_section['estimate_section_type'];
	}
}

if( ! function_exists( 'quote_calculator_get_estimate_item_description' ) ){
	function quote_calculator_get_estimate_item_description( $covers, $paper_types, $weights, $finishings ){	
		global $quote_calculator_estimate_section;
		$description = '';
    switch( $quote_calculator_estimate_section['estimate_section_category'] ){
			case 0:
				$description = $quote_calculator_estimate_section['estimate_section_type'];
				break;
			case 1:
				$description .= 'Printing ' .
					$quote_calculator_estimate_section['estimate_section_type'] . ' ' .
					$quote_calculator_estimate_section['estimate_section_size'] . ' ' . 
					$quote_calculator_estimate_section['estimate_section_sided'] . ' ' .
					$quote_calculator_estimate_section['estimate_section_color'] . ' ' .
					( ! empty( $quote_calculator_estimate_section['estimate_section_page_count'] ) ? $quote_calculator_estimate_section['estimate_section_page_count'] . ' ' : '' ) .
					$quote_calculator_estimate_section['estimate_section_single_qty'];
				if( ! empty( $covers ) ){
					foreach( $covers as $key => $value ){
						$description .= ' ' . $value . ' ' . $paper_types[ $key ] . ' ' . $weights[ $key ] . ',';
					}
				}
				if( ! empty( $finishings ) ){
					$description .= ' ' . implode( ', ', $finishings );
				}
				break;
			case 2:
				$description .= 'Printing ' .
					$quote_calculator_estimate_section['estimate_section_type'] . ' ' .
					$quote_calculator_estimate_section['estimate_section_size'] . ' ' . 
					$quote_calculator_estimate_section['estimate_section_sided'] . ' ' .
					$quote_calculator_estimate_section['estimate_section_color'] . ' ' .
					( ! empty( $quote_calculator_estimate_section['estimate_section_page_count'] ) ? $quote_calculator_estimate_section['estimate_section_page_count'] . ' ' : '' ) .
					$quote_calculator_estimate_section['estimate_section_single_qty'];
				if( ! empty( $covers ) ){
					foreach( $covers as $key => $value ){
						$description .= ' ' . $value . ' ' . $paper_types[ $key ] . ' ' . $weights[ $key ] . ',';
					}
				}
				if( ! empty( $finishings ) ){
					$description .= ' ' . implode( ', ', $finishings );
				}
				break;
			case 3:
				$description .= 'Design ' .
					$quote_calculator_estimate_section['estimate_section_type'] . ' ' .
					$quote_calculator_estimate_section['estimate_section_size'] . ' ' . 
					$quote_calculator_estimate_section['estimate_section_sided'];
				break;
			case 4:
				$description .= 'Wide Format ' .
					$quote_calculator_estimate_section['estimate_section_type'] . ' ' .
					$quote_calculator_estimate_section['estimate_section_size'] . ' ' . 
					$quote_calculator_estimate_section['estimate_section_color'] . ' ' .
					( ! empty( $quote_calculator_estimate_section['estimate_section_page_count'] ) ? $quote_calculator_estimate_section['estimate_section_page_count'] . ' ' : '' ) .
					$quote_calculator_estimate_section['estimate_section_single_qty'];
				if( ! empty( $covers ) ){
					foreach( $covers as $key => $value ){
						$description .= ' ' . $value . ' ' . $paper_types[ $key ] . ' ' . $weights[ $key ] . ',';
					}
				}
				if( ! empty( $finishings ) ){
					$description .= ' ' . implode( ', ', $finishings );
				}
				break;
		}
		return $description;
	}
}

register_activation_hook( __FILE__, 'quote_calculator_install' );

/* Initialization */
add_action( 'plugins_loaded', 'quote_calculator_loaded' );
add_action( 'widgets_init', 'quote_calculator_widgets_init' );
add_action( 'init', 'quote_calculator_init' );
add_filter( 'rewrite_rules_array','quote_calculator_insert_rewrite_rules' );
add_filter( 'query_vars','quote_calculator_insert_query_vars' );
add_action( 'wp_loaded','quote_calculator_flush_rules' );
add_action( 'admin_init', 'quote_calculator_admin_init' );
add_filter( 'cron_schedules', 'quote_calculator_add_5_minutes' ); 
/* Displaying admin menu */
add_action( 'admin_menu', 'quote_calculator_admin_menu' );
/* Adding scripts and styles in the admin panel */
add_action( 'admin_enqueue_scripts', 'quote_calculator_admin_head' );
add_action( 'wp_ajax_import_customers', 'quote_calculator_import_customers' );
add_action( 'wp_ajax_import_items', 'quote_calculator_import_items' );
add_action( 'wp_ajax_export_customers', 'quote_calculator_export_customers' );
add_action( 'wp_ajax_autocomplete_customers', 'quote_calculator_autocomplete_customers' );
add_action( 'wp_ajax_nopriv_autocomplete_customers', 'quote_calculator_autocomplete_customers' );
add_action( 'wp_ajax_add_section', 'quote_calculator_add_section' );
add_action( 'wp_ajax_nopriv_add_section', 'quote_calculator_add_section' );
add_action( 'quote_calculator_import_hook', 'quote_calculator_import_function' );
add_action( 'quote_calculator_import_items_hook', 'quote_calculator_import_items_function' );
add_action( 'quote_calculator_export_customers_hook', 'quote_calculator_export_customers_function' );
add_action( 'template_redirect', 'quote_calculator_template_redirect' );
/* Adding meta tag, scripts and styles on the frontend */
add_action( 'wp_enqueue_scripts', 'quote_calculator_frontend_head', 100 );
add_action( 'wp_enqueue_scripts', 'quote_calculator_remove_default_stylesheet', 20 );
add_filter( 'wp_nav_menu_items', 'quote_calculator_add_settings', 100, 2 );
/* Adding additional links on the plugins page */
add_filter( 'plugin_action_links', 'quote_calculator_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'quote_calculator_register_action_links', 10, 2 );
/* Add action for cron */
//add_action( 'quote_calculator_add_user', 'quote_calculator_create_user' );

/* Uninstall plugin */
register_uninstall_hook( __FILE__, 'quote_calculator_uninstall' );