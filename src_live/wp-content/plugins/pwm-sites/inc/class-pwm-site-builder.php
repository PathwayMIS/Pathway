<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'PWM_Site_Builder' ) ) {
	class PWM_Site_Builder {
		protected static $subdomain, $username, $password, $email, $db_name, $db_username, $db_password, $db_host, $blogname,
			$display_name, $company_name, $phone, $company_address_line1, $company_address_line2, $company_address_line3, $registered_address_line1,
			$registered_address_line2, $registered_address_line3, $company_address_city, $registered_address_city, $company_address_location,
			$registered_address_location, $company_address_postal_code, $registered_address_postal_code, $company_registration_number,
			$company_vat_number;

		protected static $wp_zip_file = PWM_SOURCE_DIR . 'wp/wordpress.zip';
		protected static $db_file = PWM_SOURCE_DIR . 'db/db.sql';
		protected static $wp_config = PWM_SOURCE_DIR . 'config/wp-config.php';
		protected static $site_url = NULL;

		public static function set( $subdomain, $username, $password, $email, $db_name, $db_username, $db_password, $db_host, $display_name, $company_name, $phone,
			$company_address_line1, $company_address_line2, $company_address_line3, $registered_address_line1,
			$registered_address_line2, $registered_address_line3, $company_address_city, $registered_address_city, $company_address_location,
			$registered_address_location, $company_address_postal_code, $registered_address_postal_code, $company_registration_number,
			$company_vat_number ) {
			self::$subdomain = $subdomain;
			self::$username = $username;
			self::$password = $password;
			self::$email = $email;
			self::$db_name = $db_name;
			self::$db_username = $db_username;
			self::$db_password = $db_password;
			self::$db_host = $db_host;
			self::$blogname = $subdomain . ' - Pathwaymis';
			self::$display_name = $display_name;
			self::$company_name = $company_name;
			self::$phone = $phone;
			self::$company_address_line1 = $company_address_line1;
			self::$company_address_line2 = $company_address_line2;
			self::$company_address_line3 = $company_address_line3;
			self::$registered_address_line1 = $registered_address_line1;
			self::$registered_address_line2 = $registered_address_line2;
			self::$registered_address_line3 = $registered_address_line3;
			self::$company_address_city = $company_address_city;
			self::$registered_address_city = $registered_address_city;
			self::$company_address_location = $company_address_location;
			self::$registered_address_location = $registered_address_location;
			self::$company_address_postal_code = $company_address_postal_code;
			self::$registered_address_postal_code = $registered_address_postal_code;
			self::$company_registration_number = $company_registration_number;
			self::$company_vat_number = $company_vat_number;
		}

		private static function check_db() {
			$mysqli = new mysqli( self::$db_host, self::$db_username, self::$db_password, self::$db_name );
			if ( $mysqli->connect_error ) {
				$result = new WP_Error(
					'db_connection',
					sprintf( __( 'DB Connection: %s', 'pwm-sites' ), $mysqli->connect_error )
				);
			} else {
				if ( self::$db_name == DB_NAME ) {
					$result = new WP_Error(
						'db_error',
						sprintf( __( 'DB Error: It is forbidden to use DB Name: %s', 'pwm-sites' ), self::$db_name )
					);
				} else {
					$result = true;
				}
			}

			$mysqli->close();

			return $result;
		}

		public static function get_site_url( $subdomain = '' ) {
			$subdomain = ( ! empty( $subdomain ) ) ? $subdomain : self::$subdomain;

			return PWM_HTTP_PROTOCOL . $subdomain . '.' . PWM_DOMAIN;
		}

		private static function DB_prepare_file() {
			global $wp_filesystem;

			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			WP_Filesystem();

			$temp_db_file = PWM_TEMP_DIR . 'db_' . self::$subdomain . '.sql';

			$db_file_replacment = $wp_filesystem->get_contents( self::$db_file );

			if ( ! $db_file_replacment ) {
				return new WP_Error(
					'db_preparing_file',
					sprintf( __( 'Can\'t open DB file %s', 'pwm-sites' ), self::$db_file )
				);
			}

			/* Replace data in serialized array into 'db.sql' */

			//If we need save data from signup form into qc_dashboard_options tabel.
			/*$customer_data = array( 'display_name' => self::$display_name,
									'company_name' => self::$company_name,
									'phone' => self::$phone,
									'email' => self::$email,
									'company_address_line1' => self::$company_address_line1,
									'company_address_line2' => self::$company_address_line2,
									'company_address_line3' => self::$company_address_line3,
									'company_address_city' => self::$company_address_city,
									'company_address_location' => self::$company_address_location,
									'company_address_postal_code' => self::$company_address_postal_code,
									'registered_address_line1' => self::$registered_address_line1,
									'registered_address_line2' => self::$registered_address_line2,
									'registered_address_line3' => self::$registered_address_line3,
									'registered_address_city' => self::$registered_address_city,
									'registered_address_location' => self::$registered_address_location,
									'registered_address_postal_code' => self::$registered_address_postal_code,
									'registration_number' => self::$company_registration_number,
									'vat_number' => self::$company_vat_number );*/

			$customer_data = array( 'display_name' => '',
			                        'company_name' => '',
			                        'phone' => '',
			                        'email' => '',
			                        'company_address_line1' => '',
			                        'company_address_line2' => '',
			                        'company_address_line3' => '',
			                        'company_address_city' => '',
			                        'company_address_location' => '',
			                        'company_address_postal_code' => '',
			                        'registered_address_line1' => '',
			                        'registered_address_line2' => '',
			                        'registered_address_line3' => '',
			                        'registered_address_city' => '',
			                        'registered_address_location' => '',
			                        'registered_address_postal_code' => '',
			                        'registration_number' => '',
			                        'vat_number' => '' );

			$serialized_array = 'a:5:{s:15:"company_details";a:18:{s:12:"display_name";s:14:"%display_name%";s:12:"company_name";s:14:"%company_name%";s:5:"phone";s:7:"%phone%";s:5:"email";s:7:"%email%";s:21:"company_address_line1";s:23:"%company_address_line1%";s:21:"company_address_line2";s:23:"%company_address_line2%";s:21:"company_address_line3";s:23:"%company_address_line3%";s:20:"company_address_city";s:22:"%company_address_city%";s:24:"company_address_location";s:26:"%company_address_location%";s:27:"company_address_postal_code";s:29:"%company_address_postal_code%";s:24:"registered_address_line1";s:26:"%registered_address_line1%";s:24:"registered_address_line2";s:26:"%registered_address_line2%";s:24:"registered_address_line3";s:26:"%registered_address_line3%";s:23:"registered_address_city";s:25:"%registered_address_city%";s:27:"registered_address_location";s:29:"%registered_address_location%";s:30:"registered_address_postal_code";s:32:"%registered_address_postal_code%";s:19:"registration_number";s:29:"%company_registration_number%";s:10:"vat_number";s:20:"%company_vat_number%";}s:17:"dashboard_details";a:3:{s:12:"titles_color";s:7:"#1a089f";s:13:"buttons_color";s:7:"#1a089f";s:4:"logo";s:82:"https://acp.pathwaymis.co.uk/wp-content/uploads/sites/PathwayMIS_Logo_Colour-1.jpg";}s:7:"billing";a:0:{}s:21:"calendar_view_options";a:2:{s:8:"day_view";a:0:{}s:11:"format_view";a:0:{}}s:22:"calendar_color_options";a:3:{s:16:"print_production";s:7:"#9d3292";s:8:"jobs_out";s:7:"#800080";s:7:"deliver";s:7:"#d8bfd8";}}';
			$new_array = unserialize( $serialized_array );
			foreach ( $new_array['company_details'] as $key => $value ) {
				$new_array['company_details'][ $key ] = $customer_data[ $key ];
			}
			$new_array = serialize( $new_array );

			$db_file_replacment = str_replace( $serialized_array, $new_array, $db_file_replacment );

			/* end replace */

			$password = wp_hash_password( self::$password );

			$date = date_i18n( 'Y-m-d H:m:s' );
			$site_url = self::get_site_url();

			$db_file_replacment = str_replace(
				array( '%username%', '%password%', '%email%', '%date%', '%site_url%', '%blogname%' ),
				array( self::$username, $password, self::$email, $date, $site_url, self::$blogname ),
				$db_file_replacment
			);

			$process_db_file_replacment = $wp_filesystem->put_contents( $temp_db_file, $db_file_replacment );

			if ( ! $process_db_file_replacment ) {
				return new WP_Error(
					'db_preparing_file',
					sprintf( __( 'Can\'t save DB file to %s', 'pwm-sites' ), $temp_db_file )
				);
			}

			return true;
		}

		private static function DB_drop_tables( $mysqli, $close_connection = false ) {
			$mysqli->query( 'SET foreign_key_checks = 0' );

			if ( $result = $mysqli->query( "SHOW TABLES" ) ) {
				while ( $row = $result->fetch_array( MYSQLI_NUM ) ) {
					$mysqli->query( 'DROP TABLE IF EXISTS ' . $row[0] );
				}
			}

			$mysqli->query( 'SET foreign_key_checks = 1' );

			if ( $close_connection )
				$mysqli->close();
		}

		private static function DB_upload_file() {
			global $wp_filesystem;

			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			WP_Filesystem();

			$mysqli = new mysqli( self::$db_host, self::$db_username, self::$db_password, self::$db_name );

			self::DB_drop_tables( $mysqli );

			$temp_db_file = PWM_TEMP_DIR . 'db_' . self::$subdomain . '.sql';

			// $db_file = file( $temp_db_file );
			// $db_file_contents = file_get_contents( $temp_db_file );
			// $mysqli->multi_query( $db_file_contents );
			// var_dump( $mysqli->store_result() );
			// $mysqli->close();

			$result = true;
			$content = file( $temp_db_file );
			$temp_line = '';
			$error = '';

			foreach ( $content as $line ) {
				if( '--' == substr( $line, 0, 2 ) || '' == $line ) {
					continue;
				}

				$temp_line .= $line;

				if ( ';' == substr( trim( $line ), -1, 1 ) ) {
					if ( ! $mysqli->query( $temp_line ) ) {
						$result = new WP_Error(
							'db_uploading_file',
							sprintf( __( 'An error occurred while uploading DB file to the DB: %s %s', 'pwm-sites' ), '<br><br>' . $temp_line, '<br><br>' . $mysqli->error )
						);

						break;
					}

					$temp_line = '';
				}
			}

			if ( ! is_wp_error( $result ) )
				$wp_filesystem->delete( $temp_db_file );

			return $result;
		}

		private static function get_temp_wp_config() {
			return PWM_TEMP_DIR . '/wp-config-'. self::$subdomain . '.php';
		}

		private static function create_wp_config() {
			global $wp_filesystem;

			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			WP_Filesystem();

			$config_file_replacment = $wp_filesystem->get_contents( self::$wp_config );

			if ( ! $config_file_replacment ) {
				return new WP_Error(
					'config_preparing_file',
					sprintf( __( 'Can\'t open wp-config file %s', 'pwm-sites' ), self::$wp_config )
				);
			}

			$config_file_replacment = str_replace(
				array( '%db_name%', '%db_username%', '%db_password%', '%db_host%' ),
				array( self::$db_name, self::$db_username, self::$db_password, self::$db_host ),
				$config_file_replacment
			);

			$process_config_file_replacment = $wp_filesystem->put_contents( self::get_temp_wp_config(), $config_file_replacment );

			if ( ! $process_config_file_replacment ) {
				return new WP_Error(
					'config_preparing_file',
					sprintf( __( 'Can\'t save wp-config file to %s', 'pwm-sites' ), self::get_temp_wp_config() )
				);
			}

			return true;
		}

		private static function deploy_wp() {
			global $wp_filesystem;

			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			WP_Filesystem();

			$unzip_process = unzip_file( self::$wp_zip_file, PWM_TEMP_DIR );

			if ( ! is_wp_error( $unzip_process ) ) {
				$from_folder = PWM_TEMP_DIR . 'wordpress';
				$to_folder = PWM_SITE_DIR . self::$subdomain;
				$move_process = $wp_filesystem->move( $from_folder, $to_folder );

				if ( ! $move_process ) {
					return new WP_Error(
						'wp_deploying',
						sprintf( __( 'Can\'t move wordpress folder from %s to %s', 'pwm-sites' ), $from_folder, $to_folder )
					);
				} else {
					$site_wp_config = $to_folder . '/wp-config.php';
					$move_wp_config = $wp_filesystem->move( self::get_temp_wp_config(), $site_wp_config );

					if ( ! $move_wp_config ) {
						return new WP_Error(
							'wp_config',
							sprintf( __( 'Can\'t move wp-config file from %s to %s', 'pwm-sites' ), self::get_temp_wp_config(), $site_wp_config )
						);
					}
				}
			} else {
				return $unzip_process;
			}

			return true;
		}

		public static function start() {

			/* Checking DB Connection */
			$db_connection = self::check_db();

			if ( is_wp_error( $db_connection ) )
				return $db_connection;

			/* Replacing the data in the DB file */
			$db_prepare_file = self::DB_prepare_file();

			if ( is_wp_error( $db_prepare_file ) )
				return $db_prepare_file;

			/* Uploading the DB file */
			$db_upload_file = self::DB_upload_file();

			if ( is_wp_error( $db_upload_file ) )
				return $db_upload_file;

			/* Creating wp-config file */
			$wp_config = self::create_wp_config();

			if ( is_wp_error( $wp_config ) )
			return $wp_config;

			/* Deploying WP */
			$deploy_wp = self::deploy_wp();

			if ( is_wp_error( $deploy_wp ) )
				return $deploy_wp;

			return self::get_site_url();
		}

		public static function is_installed( $subdomain = '' ) {
			return ( ! empty( $subdomain ) && file_exists( PWM_SITE_DIR . $subdomain ) );
		}
	}
}