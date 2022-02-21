<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'PWM_Page_Site' ) ) {
	class PWM_Page_Site extends PWM_Page {
		public $page_slug = 'pwm_site';
		public $site_data = array();

		public function admin_menu() {
			add_submenu_page( 'pwm_sites', $this->page_title(), __( 'New', 'pwm-sites' ), 'manage_options', 'pwm_site', array( $this, 'page_site' ) );
		}

		public function get_site_id() {
			return ( isset( $_GET['site_id'] ) && is_numeric( $_GET['site_id'] ) ) ? $_GET['site_id'] : -1;
		}

		public function get_site_action() {
			return ( isset( $_GET['site_action'] ) && 'manage' == $_GET['site_action'] ) ? 'manage' : 'new';
		}

		private function page_title() {
			return ( 'new' == $this->get_site_action() ) ? __( 'New Site', 'pwm-sites' ) : __( 'Manage Site', 'pwm-sites' );
		}

		public function admin_init() {
			ob_start();
		}

		private function readonly( $helper, $current = true, $echo = true, $type = 'readonly' ) {
			if ( (string) $helper === (string) $current )
				$result = " $type='$type'";
			else
				$result = '';

			if ( $echo )
				echo $result;

			return $result;
		}

		public function page_site() {
			global $wpdb;

			require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-site-builder.php' );
			require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-pdf.php' );

			$save_site_error = false;

			$message = array(
				'updated'	=> array(),
				'error'		=> array()
			);

			$form_action = admin_url( 'admin.php?page=pwm_site' );

			if ( isset( $_GET['site_message'] ) && $_GET['site_message'] == 1 )
				$message['updated'][] = __( 'Company Details has been saved.', 'pwm-sites' );

			if ( 'new' == $this->get_site_action() ) {
				$this->site_data = array(
					'subdomain' 						=> '',
					'username' 							=> '',
					'password' 							=> '',
					'email'								=> '',
					'db_name' 							=> '',
					'db_username' 						=> '',
					'db_password' 						=> '',
					'db_host'							=> '',
					'display_name' 						=> '',
					'company_name' 						=> '',
					'company_phone' 					=> '',
					'company_email' 					=> '',
					'company_address_line1'				=> '',
					'company_address_line2'				=> '',
					'company_address_line3'				=> '',
					'company_address_city'				=> '',
					'company_address_location'			=> '',
					'company_address_postal_code'		=> '',
					'registered_address_line1'			=> '',
					'registered_address_line2'			=> '',
					'registered_address_line3'			=> '',
					'registered_address_city'			=> '',
					'registered_address_location'		=> '',
					'registered_address_postal_code'	=> '',
					'registration_number'				=> '',
					'vat_number' 						=> '',
				);
			} elseif ( 'manage' == $this->get_site_action() ) {
				$site_data_query = $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}pwm_sites WHERE id = %d", $this->get_site_id() );
				$get_site_data = $wpdb->get_row( $site_data_query, 'ARRAY_A' );

				if ( ! $get_site_data ) {
					wp_redirect( admin_url( 'admin.php?page=pwm_sites' ) );
					exit;
				}

				$form_action .= '&site_action=manage&site_id=' . $this->get_site_id();

				$this->site_data = $get_site_data;
			}

			if ( isset( $_POST['pwm_submit'] ) && check_admin_referer( 'save_site', 'pwm_site' ) ) {
				if ( 'send_pdf' == $_POST['pwm_submit'] ) {

					$pdf_contract = PWM_Pdf::create( $this->site_data );

					if ( ! empty( $pdf_contract ) ) {
						if ( ! empty( $this->site_data['company_email'] ) ) {
							$headers = 'From: PathwayMIS <sites@pathwaymis.co.uk>' . "\r\n";
							$process_mail = wp_mail( $this->site_data['company_email'], 'Pathwaymis Contract', 'Pathwaymis Contract', $headers, array( $pdf_contract ) );
							@unlink( $pdf_contract );

							if ( $process_mail ) {
								$message['updated'][] = __( 'The PDF Contract has been sent.', 'pwm-sites' );
							} else {
								$message['error'][] = __( 'The PDF Contract hasn\'t been sent.', 'pwm-sites' );
							}
						}
					}
				}

				if ( 'download_pdf' == $_POST['pwm_submit'] ) {
					ob_end_clean();

					PWM_Pdf::create( $this->site_data, 'download' );
				}

				if ( 'save_company' == $_POST['pwm_submit'] ) {
					$company_error = array();

					$display_name 						= ( isset( $_POST['pwm_display_name'] ) ) ? sanitize_text_field( $_POST['pwm_display_name'] ) : '';
					$company_name 						= ( isset( $_POST['pwm_company_name'] ) ) ? sanitize_text_field( $_POST['pwm_company_name'] ) : '';
					$company_phone						= ( isset( $_POST['pwm_company_phone'] ) ) ? sanitize_text_field( $_POST['pwm_company_phone'] ) : '';
					$company_email						= ( isset( $_POST['pwm_company_email'] ) ) ? sanitize_text_field( $_POST['pwm_company_email'] ) : '';
					$company_address_line1 				= ( isset( $_POST['pwm_company_address_line1'] ) ) ? sanitize_text_field( $_POST['pwm_company_address_line1'] ) : '';
					$company_address_line2 				= ( isset( $_POST['pwm_company_address_line2'] ) ) ? sanitize_text_field( $_POST['pwm_company_address_line2'] ) : '';
					$company_address_line3 				= ( isset( $_POST['pwm_company_address_line3'] ) ) ? sanitize_text_field( $_POST['pwm_company_address_line3'] ) : '';
					$company_address_city 				= ( isset( $_POST['pwm_company_address_city'] ) ) ? sanitize_text_field( $_POST['pwm_company_address_city'] ) : '';
					$company_address_location 			= ( isset( $_POST['pwm_company_address_location'] ) ) ? sanitize_text_field( $_POST['pwm_company_address_location'] ) : '';
					$company_address_postal_code 		= ( isset( $_POST['pwm_company_address_postal_code'] ) ) ? sanitize_text_field( $_POST['pwm_company_address_postal_code'] ) : '';
					$registered_address_line1 			= ( isset( $_POST['pwm_registered_address_line1'] ) ) ? sanitize_text_field( $_POST['pwm_registered_address_line1'] ) : '';
					$registered_address_line2 			= ( isset( $_POST['pwm_registered_address_line2'] ) ) ? sanitize_text_field( $_POST['pwm_registered_address_line2'] ) : '';
					$registered_address_line3 			= ( isset( $_POST['pwm_registered_address_line3'] ) ) ? sanitize_text_field( $_POST['pwm_registered_address_line3'] ) : '';
					$registered_address_city 			= ( isset( $_POST['pwm_registered_address_city'] ) ) ? sanitize_text_field( $_POST['pwm_registered_address_city'] ) : '';
					$registered_address_location 		= ( isset( $_POST['pwm_registered_address_location'] ) ) ? sanitize_text_field( $_POST['pwm_registered_address_location'] ) : '';
					$registered_address_postal_code 	= ( isset( $_POST['pwm_registered_address_postal_code'] ) ) ? sanitize_text_field( $_POST['pwm_registered_address_postal_code'] ) : '';
					$registration_number 				= ( isset( $_POST['pwm_company_registration_number'] ) ) ? sanitize_text_field( $_POST['pwm_company_registration_number'] ) : '';
					$vat_number 						= ( isset( $_POST['pwm_company_vat_number'] ) ) ? sanitize_text_field( $_POST['pwm_company_vat_number'] ) : '';

					if ( empty( $display_name ) )
						$company_error[] = __( 'Display Name is required.', 'pwm-sites' );

					if ( empty( $company_name ) )
						$company_error[] = __( 'Company Name is required.', 'pwm-sites' );

					if ( empty( $company_phone ) )
						$company_error[] = __( 'Company Phone is required.', 'pwm-sites' );

					if ( empty( $company_email ) ) {
						$company_error[] = __( 'Company Email is required.', 'pwm-sites' );
					} elseif ( ! is_email( $company_email ) ) {
						$company_error[] = __( 'Company Email is invalid.', 'pwm-sites' );
					}

					if ( empty( $company_address_line1 ) && empty( $company_address_line2 ) && empty( $company_address_line3 ) )
						$company_error[] = __( 'Company Address is required.', 'pwm-sites' );

					if ( empty( $company_address_city ) )
						$company_error[] = __( 'Company City is required.', 'pwm-sites' );

					if ( empty( $company_address_location ) )
						$company_error[] = __( 'Company Location is required.', 'pwm-sites' );

					if ( empty( $company_address_postal_code ) )
						$company_error[] = __( 'Company Postal Code is required.', 'pwm-sites' );

					if ( empty( $registered_address_line1 ) && empty( $registered_address_line2 ) && empty( $registered_address_line3 ) )
						$company_error[] = __( 'Registered Address is required.', 'pwm-sites' );

					if ( empty( $registered_address_city ) )
						$company_error[] = __( 'Registered City is required.', 'pwm-sites' );

					if ( empty( $registered_address_location ) )
						$company_error[] = __( 'Registered Location is required.', 'pwm-sites' );

					if ( empty( $registered_address_postal_code ) )
						$company_error[] = __( 'Registered Postal Code is required.', 'pwm-sites' );

					if ( empty( $registration_number ) )
						$company_error[] = __( 'Company Registration Number is required.', 'pwm-sites' );

					if ( empty( $vat_number ) )
						$company_error[] = __( 'Company VAT Number is required.', 'pwm-sites' );

					$company_details = array(
						'display_name' 						=>  $display_name,
						'company_name' 						=>  $company_name,
						'company_phone' 					=>  $company_phone,
						'company_email' 					=>  $company_email,
						'company_address_line1'				=>  $company_address_line1,
						'company_address_line2'				=>  $company_address_line2,
						'company_address_line3'				=>  $company_address_line3,
						'company_address_city'				=>  $company_address_city,
						'company_address_location'			=>  $company_address_location,
						'company_address_postal_code'		=>  $company_address_postal_code,
						'registered_address_line1'			=>  $registered_address_line1,
						'registered_address_line2'			=>  $registered_address_line2,
						'registered_address_line3'			=>  $registered_address_line3,
						'registered_address_city'			=>  $registered_address_city,
						'registered_address_location'		=>  $registered_address_location,
						'registered_address_postal_code'	=>  $registered_address_postal_code,
						'registration_number'				=>  $registration_number,
						'vat_number' 						=>  $vat_number
					);

					if ( ! $company_error ) {
						if ( 'manage' == $this->get_site_action() ) {
							$result_update = $wpdb->update(
								"{$wpdb->base_prefix}pwm_sites",
								$company_details,
								array( 'id' => $this->get_site_id() ),
								array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
								array( '%d' )
							);

							if ( $result_update )
								$message['updated'][] = __( 'Company Details has been saved.', 'pwm-sites' );
						} else {
							$company_details['date_submitted'] = date_i18n( 'Y-m-d H:m:s' );

							$result_insert = $wpdb->insert(
								"{$wpdb->base_prefix}pwm_sites",
								$company_details,
								array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
							);

							if ( $result_insert ) {
								wp_redirect( admin_url( 'admin.php?page=pwm_site&site_action=manage&site_id=' . $wpdb->insert_id . '&site_message=1') );
								exit;
							}
						}
					} else {
						$message['error'][] = implode( '<br>', $company_error );
						$message['error'][] = __( 'Company Details hasn\'t been saved.', 'pwm-sites' );
					}

					$this->site_data = array_merge( $this->site_data, $company_details );
				}

				if ( 'save_site' == $_POST['pwm_submit'] ) {
					$site_error = array();

					$subdomain 		= ( isset( $_POST['pwm_subdomain'] ) ) ? sanitize_text_field( $_POST['pwm_subdomain'] ) : '';
					$username 		= ( isset( $_POST['pwm_username'] ) ) ? sanitize_text_field( $_POST['pwm_username'] ) : '';
					$password 		= ( isset( $_POST['pwm_password'] ) ) ? sanitize_text_field( $_POST['pwm_password'] ) : '';
					$email 			= ( isset( $_POST['pwm_email'] ) ) ? sanitize_text_field( $_POST['pwm_email'] ) : '';
					$db_name 		= ( isset( $_POST['pwm_db_name'] ) ) ? sanitize_text_field( $_POST['pwm_db_name'] ) : '';
					$db_username 	= ( isset( $_POST['pwm_db_username'] ) ) ? sanitize_text_field( $_POST['pwm_db_username'] ) : '';
					$db_password 	= ( isset( $_POST['pwm_db_password'] ) ) ? sanitize_text_field( $_POST['pwm_db_password'] ) : '';
					$db_host 		= ( isset( $_POST['pwm_db_host'] ) ) ? sanitize_text_field( $_POST['pwm_db_host'] ) : '';

					if ( empty( $subdomain ) ) {
						$site_error[] = __( 'Subdomain is required.', 'pwm-sites' );
					} else {
						$check_domain_query = $wpdb->prepare( "SELECT `id` FROM {$wpdb->base_prefix}pwm_sites WHERE subdomain = %s", $subdomain );
						$domain_site_id = $wpdb->get_var( $check_domain_query );

						if ( $domain_site_id && $domain_site_id != $this->get_site_id() )
							$site_error[] = sprintf( __( "Subdomain '%s' is already exists.", 'pwm-sites' ), $subdomain );
					}

					if ( empty( $username ) )
						$site_error[] = __( 'Username is required.', 'pwm-sites' );

					if ( empty( $password ) )
						$site_error[] = __( 'Password is required.', 'pwm-sites' );

					if ( empty( $email ) ) {
						$site_error[] = __( 'Email is required.', 'pwm-sites' );
					} elseif ( ! is_email( $email ) ) {
						$site_error[] = __( 'Email is invalid.', 'pwm-sites' );
					}

					if ( empty( $db_name ) )
						$site_error[] = __( 'DB Name is required.', 'pwm-sites' );

					if ( empty( $db_username ) )
						$site_error[] = __( 'DB Username is required.', 'pwm-sites' );

					if ( empty( $db_password ) )
						$site_error[] = __( 'DB Password is required.', 'pwm-sites' );

					if ( empty( $db_host ) )
						$site_error[] = __( 'DB Host is required.', 'pwm-sites' );

					$site_settings = array(
						'subdomain' 	=> preg_replace( '/[^a-z0-9-]/i', '', strtolower( $subdomain ) ),
						'username' 		=> sanitize_user( wp_unslash( $username ), true ),
						'password' 		=> $password,
						'email'			=> $email,
						'db_name' 		=> preg_replace( '/[^a-zA-Z0-9_-]/i', '', strtolower( $db_name ) ),
						'db_username' 	=> $db_username,
						'db_password' 	=> $db_password,
						'db_host'		=> $db_host
					);

					if ( ! $site_error ) {
						$update_site = $wpdb->update( "{$wpdb->base_prefix}pwm_sites",
							$site_settings,
							array( 'id' => $this->get_site_id() ),
							array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
							array( '%d' )
						);

						if ( $update_site ) {
							$message['updated'][] = __( 'Site Settings has been saved.', 'pwm-sites' );
						}
					} else {
						$save_site_error = true;
						$message['error'][] = implode( '<br>', $site_error );
						$message['error'][] = __( 'Site Settings hasn\'t been saved.', 'pwm-sites' );
					}

					$this->site_data = array_merge( $this->site_data, $site_settings );
				}

				if ( 'build_site' == $_POST['pwm_submit'] ) {
					$build_error = array();

					if ( empty( $this->site_data['subdomain'] ) )
						$build_error[] = __( 'Subdomain is required.', 'pwm-sites' );

					if ( empty( $this->site_data['username'] ) )
						$build_error[] = __( 'Username is required.', 'pwm-sites' );

					if ( empty( $this->site_data['password'] ) )
						$build_error[] = __( 'Password is required.', 'pwm-sites' );

					if ( empty( $this->site_data['email'] ) ) {
						$build_error[] = __( 'Email is required.', 'pwm-sites' );
					} elseif ( ! is_email( $this->site_data['email'] ) ) {
						$build_error[] = __( 'Email is invalid.', 'pwm-sites' );
					}

					if ( empty( $this->site_data['db_name'] ) )
						$build_error[] = __( 'DB Name is required.', 'pwm-sites' );

					if ( empty( $this->site_data['db_username'] ) )
						$build_error[] = __( 'DB Username is required.', 'pwm-sites' );

					if ( empty( $this->site_data['db_password'] ) )
						$build_error[] = __( 'DB Password is required.', 'pwm-sites' );

					if ( empty( $this->site_data['db_host'] ) )
						$build_error[] = __( 'DB Host is required.', 'pwm-sites' );

					if ( ! $build_error ) {
						PWM_Site_Builder::set(
							$this->site_data['subdomain'],
							$this->site_data['username'],
							$this->site_data['password'],
							$this->site_data['email'],
							$this->site_data['db_name'],
							$this->site_data['db_username'],
							$this->site_data['db_password'],
							$this->site_data['db_host'],
							$this->site_data['display_name'],
							$this->site_data['company_name'],
							$this->site_data['company_phone'],
							$this->site_data['company_address_line1'],
							$this->site_data['company_address_line2'],
							$this->site_data['company_address_line3'],
							$this->site_data['registered_address_line1'],
							$this->site_data['registered_address_line2'],
							$this->site_data['registered_address_line3'],
							$this->site_data['company_address_city'],
							$this->site_data['registered_address_city'],
							$this->site_data['company_address_location'],
							$this->site_data['registered_address_location'],
							$this->site_data['company_address_postal_code'],
							$this->site_data['registered_address_postal_code'],
							$this->site_data['registration_number'],
							$this->site_data['vat_number']
						);

						$result = PWM_Site_Builder::start();

						if ( is_wp_error( $result ) ) {
							$message['error'][] = $result->get_error_message();
							$message['error'][] = __( 'The site hasn\'t been built.', 'pwm-sites' );
						} else {
							/* Send PDF Contract */
							$pdf_contract = PWM_Pdf::create( $this->site_data );
							$headers = array();
							$headers[] = 'Content-type: text/html;';
							$headers[] = 'From: PathwayMIS <sites@pathwaymis.co.uk>' . "\r\n";
							$content = "<img src='https://" . $_SERVER['SERVER_NAME'] . "/wp-content/plugins/pwm-sites/images/pdf-logo-2.jpg'" . "\r\n\r\n";
							$content .= "<br>";
							$content .= "<p style='font-size: 14px;' >" . "Great news! Your site is live!" . "</p>" . "<br>";
							$content .= "<p style='font-size: 14px;' >" . "Your unique URL is site URL: {$result}" . "</p>";
							$content .= "<p style='font-size: 14px;' >" . "Username: {$this->site_data['username']}" . "</p>";
							$content .= "<p style='font-size: 14px;' >" . "Password: {$this->site_data['password']}" . "</p>" ."<br>";
							$content .= "<p style='font-size: 14px;' >" . "Please contact us if you have any issues logging in to your site URL" . "</p>" . "<br>";
							$content .= "0800 107 0722 | sites@pathwaymis.co.uk | " . "<a href='https://pathwaymis.co.uk' target='_blank'>" . "www.pathwaymis.co.uk" . "</a>" . "\r\n";
							$content .= "<p style='font-size: 12px;' >" . "We Know Print Ltd T/A Pathway MIS | A company registered in England & Wales | 10160343" . "</p>" . "\r\n";
							$content .= "<p style='font-size: 12px;' >" . "This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whome they are addressed. If you received this email in error please notify the system manager. This message contains confidential information and is intended only for the individual named. If you are not the named addressee you should not disseminate, distribute or copy this-mail. Please the sender immediately by e-mail have received this e-mail by mistake and delete this e-mail from your system. If you are not the intended recipient you are notified that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited." . "</p>";

							wp_mail( $this->site_data['email'], 'Your site is ready to use', $content, $headers, array( $pdf_contract ) );

							$message['updated'][] = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $result ), esc_html( $result ) );
							$message['updated'][] = __( 'The site has been built.', 'pwm-sites' );
						}
					} else {
						$message['error'][] = implode( '<br>', $build_error );
						$message['error'][] = __( 'The site hasn\'t been built.', 'pwm-sites' );
					}
				}
			}

			if ( $save_site_error ) {
				$is_installed = false;
			} else {
				$is_installed = PWM_Site_Builder::is_installed( $this->site_data['subdomain'] );
			} ?>
			<div class="wrap pwm-wrap pwm-wrap-site">
				<h1 class="wp-heading-inline"><?php echo $this->page_title(); ?></h1>
				<?php foreach ( $message as $class => $messages ) {
					foreach ( $messages as $text ) { ?>
						<div class="pwm-notice pwm-notice-<?php echo $class; ?>">
							<p>
								<strong><?php echo $text; ?></strong>
							</p>
						</div>
					<?php }
				} ?>
				<form class="pwm-site-form" method="post" action="<?php echo esc_url( $form_action ); ?>">
					<?php wp_nonce_field( 'save_site', 'pwm_site' );
					if ( 0 < $this->get_site_id() ) { ?>
						<h4 class="pwm-setting-title"><?php _e( 'Site Settings', 'pwm-sites' ); ?></h4>
						<table class="form-table pwm-form-table">
							<tbody>
								<tr>
									<td colspan="2">
										<div class="pwm-site-installed">
											<span class="pwm-site-installed-title">
												<?php _e( 'Installed', 'pwm-sites' ); ?>:
											</span>
											<span class="pwm-site-installed-status">
												<?php echo ( $is_installed ) ? __( 'Yes', 'pwm-sites' ) : __( 'No', 'pwm-sites' ); ?>
											</span>
											<?php if ( ! $is_installed ) { ?>
												<button id="build" class="pwm-button pwm-button-primary pwm-site-installed-button pwm-button-build-site" type="submit" name="pwm_submit" value="build_site"><?php _e( 'Build Site', 'pwm-sites' ); ?></button><span class="pwm-spinner spinner"></span>
											<?php } ?>
										</div>
									</td>
								</tr>
								<?php if ( $is_installed ) { ?>
									<tr>
										<td colspan="2">
											<div class="pwm-site-info">
												<span class="pwm-site-info-title">
													<?php _e( 'Link', 'pwm-sites' ); ?>:
												</span>
												<a href="<?php echo esc_url( PWM_Site_Builder::get_site_url( $this->site_data['subdomain'] ) ); ?>" target="_blank"><?php echo esc_html( PWM_Site_Builder::get_site_url( $this->site_data['subdomain'] ) ); ?></a>
											</div>
										</td>
									</tr>
								<?php } ?>
								<tr>
									<td>
										<label for="subdomain"><?php _e( 'Subdomain', 'pwm-sites' ); ?></label><br />
										<input name="pwm_subdomain" id="subdomain" value="<?php echo $this->site_data['subdomain']; ?>" class="regular-text" type="text" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>
									<td>
										<label for="email"><?php _e( 'Email', 'pwm-sites' ); ?></label><br />
										<input name="pwm_email" id="email" value="<?php echo $this->site_data['email']; ?>" class="regular-text" type="email" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>

								</tr>
								<tr>
									<td>
										<label for="username"><?php _e( 'Username', 'pwm-sites' ); ?></label><br />
										<input name="pwm_username" id="username" value="<?php echo $this->site_data['username']; ?>" class="regular-text" type="text" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>
									<td>
										<label for="password"><?php _e( 'Password', 'pwm-sites' ); ?></label><br />
										<input name="pwm_password" id="password" value="<?php echo $this->site_data['password']; ?>" class="regular-text" type="text" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>
								</tr>
								<tr>
									<td>
										<label for="db_username"><?php _e( 'DB Username', 'pwm-sites' ); ?></label><br />
										<input name="pwm_db_username" id="db_username" value="<?php echo $this->site_data['db_username']; ?>" class="regular-text" type="text" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>
									<td>
										<label for="db_password"><?php _e( 'DB Password', 'pwm-sites' ); ?></label><br />
										<input name="pwm_db_password" id="db_password" value="<?php echo $this->site_data['db_password']; ?>" class="regular-text" type="text" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>
								</tr>
								<tr>
									<td>
										<label for="db_name"><?php _e( 'DB Name', 'pwm-sites' ); ?></label><br />
										<input name="pwm_db_name" id="db_name" value="<?php echo $this->site_data['db_name']; ?>" class="regular-text" type="text" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>
									<td>
										<label for="db_host"><?php _e( 'DB Host', 'pwm-sites' ); ?></label><br />
										<input name="pwm_db_host" id="db_host" value="<?php echo $this->site_data['db_host']; ?>" class="regular-text" type="text" maxlength="64" <?php $this->readonly( $is_installed ); ?> />
									</td>
								</tr>
							</tbody>
						</table>
						<?php if ( ! $is_installed ) { ?>
							<p class="submit">
								<button id="build" class="pwm-button pwm-button-primary" type="submit" name="pwm_submit" value="save_site"><?php _e( 'Save Site Settings', 'pwm-sites' ); ?></button>
								<div class="clear"></div>
							</p>
						<?php } ?>
					<?php } ?>
					<h4 class="pwm-setting-title"><?php _e( 'Company Details', 'pwm-sites' ); ?></h4>
					<table class="form-table pwm-form-table">
						<tbody>
							<tr>
								<td>
									<label for="company_display_name"><?php _e( 'Display Name', 'pwm-sites' ); ?></label><br />
									<input name="pwm_display_name" id="company_display_name" value="<?php echo $this->site_data['display_name']; ?>" class="regular-text" type="text" maxlength="100" />
								</td>
								<td>
									<label for="company_name"><?php _e( 'Company Name', 'pwm-sites' ); ?></label><br />
									<input name="pwm_company_name" id="company_name" value="<?php echo $this->site_data['company_name']; ?>" class="regular-text" type="text" maxlength="100" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="company_phone"><?php _e( 'Company Phone', 'pwm-sites' ); ?></label><br />
									<input name="pwm_company_phone" id="company_phone" value="<?php echo $this->site_data['company_phone']; ?>" class="regular-text" type="text" maxlength="64" />
								</td>
								<td>
									<label for="company_email"><?php _e( 'Company Email', 'pwm-sites' ); ?></label><br />
									<input name="pwm_company_email" id="company_email" value="<?php echo $this->site_data['company_email']; ?>" class="regular-text" type="email" maxlength="64" />
								</td>
							</tr>
							<tr>
								<td>
									<label><?php _e( 'Company Address', 'pwm-sites' ); ?></label><br />
									<input name="pwm_company_address_line1" id="company_address_line1" value="<?php echo $this->site_data['company_address_line1']; ?>" class="regular-text" type="text" maxlength="100" />
									<p class="description"><?php _e( 'Company Address Line 1', 'pwm-sites' ); ?></p>
									<input name="pwm_company_address_line2" id="company_address_line2" value="<?php echo $this->site_data['company_address_line2']; ?>" class="regular-text" type="text" maxlength="100" />
									<p class="description"><?php _e( 'Company Address Line 2', 'pwm-sites' ); ?></p>
                                    <input name="pwm_company_address_line3" id="company_address_line3" value="<?php echo $this->site_data['company_address_line3']; ?>" class="regular-text" type="text" maxlength="100" />
                                    <p class="description"><?php _e( 'Company Address Line 3', 'pwm-sites' ); ?></p>
									<input name="pwm_company_address_city" id="company_address_city" value="<?php echo $this->site_data['company_address_city']; ?>" class="regular-text" type="text" maxlength="64" />
									<p class="description"><?php _e( 'Company City', 'pwm-sites' ); ?></p>
									<input name="pwm_company_address_location" id="company_address_location" value="<?php echo $this->site_data['company_address_location']; ?>" class="regular-text" type="text" maxlength="100" />
									<p class="description"><?php _e( 'Company Location', 'pwm-sites' ); ?></p>
									<input name="pwm_company_address_postal_code" id="company_address_postal_code" value="<?php echo $this->site_data['company_address_postal_code']; ?>" class="regular-text" type="text" maxlength="64" />
									<p class="description"><?php _e( 'Company Postal Code', 'pwm-sites' ); ?></p>
								</td>
								<td>
									<label><?php _e( 'Registered Address', 'pwm-sites' ); ?></label><br />
									<input name="pwm_registered_address_line1" id="registered_address_line1" value="<?php echo $this->site_data['registered_address_line1']; ?>" class="regular-text" type="text" maxlength="100" />
									<p class="description"><?php _e( 'Registered Address Line 1', 'pwm-sites' ); ?></p>
									<input name="pwm_registered_address_line2" id="registered_address_line2" value="<?php echo $this->site_data['registered_address_line2']; ?>" class="regular-text" type="text" maxlength="100" />
									<p class="description"><?php _e( 'Registered Address Line 2', 'pwm-sites' ); ?></p>
                                    <input name="pwm_registered_address_line3" id="registered_address_line3" value="<?php echo $this->site_data['registered_address_line3']; ?>" class="regular-text" type="text" maxlength="100" />
                                    <p class="description"><?php _e( 'Registered Address Line 3', 'pwm-sites' ); ?></p>
									<input name="pwm_registered_address_city" id="registered_address_city" value="<?php echo $this->site_data['registered_address_city']; ?>" class="regular-text" type="text" maxlength="64" />
									<p class="description"><?php _e( 'Registered City', 'pwm-sites' ); ?></p>
									<input name="pwm_registered_address_location" id="registered_address_location" value="<?php echo $this->site_data['registered_address_location']; ?>" class="regular-text" type="text" maxlength="100" />
									<p class="description"><?php _e( 'Registered Location', 'pwm-sites' ); ?></p>
									<input name="pwm_registered_address_postal_code" id="registered_address_postal_code" value="<?php echo $this->site_data['registered_address_postal_code']; ?>" class="regular-text" type="text" maxlength="64" />
									<p class="description"><?php _e( 'Registered Postal Code', 'pwm-sites' ); ?></p>
								</td>
							</tr>
							<tr>
								<td>
									<label for="company_registration_number"><?php _e( 'Company Registration Number', 'pwm-sites' ); ?></label><br />
									<input name="pwm_company_registration_number" id="company_registration_number" value="<?php echo $this->site_data['registration_number']; ?>" class="regular-text" type="text" maxlength="64" />
								</td>
								<td>
									<label for="company_vat_number"><?php _e( 'Company VAT Number', 'pwm-sites' ); ?></label><br />
									<input name="pwm_company_vat_number" id="company_vat_number" value="<?php echo $this->site_data['vat_number']; ?>" class="regular-text" type="text" maxlength="64" />
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
						<button id="save" class="pwm-button pwm-button-primary" type="submit" name="pwm_submit" value="save_company"><?php _e( 'Save Company Details', 'pwm-sites' ); ?></button>
						<?php if ( 0 < $this->get_site_id() ) { ?>
							<button id="pdf" class="pwm-button pwm-button-primary" type="submit" name="pwm_submit" value="send_pdf"><?php _e( 'Send PDF', 'pwm-sites' ); ?></button>
							<button id="pdf" class="pwm-button pwm-button-primary" type="submit" name="pwm_submit" value="download_pdf"><?php _e( 'Download PDF', 'pwm-sites' ); ?></button>
						<?php } ?>
						<div class="clear"></div>
					</p>
				</form>
			</div>
			<?php
		}

		public function hooks() {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}
	}
}