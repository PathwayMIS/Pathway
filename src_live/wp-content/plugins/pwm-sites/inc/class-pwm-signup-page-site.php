<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PWM_Signup_Page_Site' ) ) {
	class PWM_Signup_Page_Site extends PWM_Page_Site {

		public function __construct() {

			$this->add_columns();
			$this->hooks();
		}

		public function add_columns () {
			global $wpdb;

			$is_exists = $wpdb->get_results( "SELECT company_address_line3 FROM `" . $wpdb->base_prefix . "pwm_sites`" );
			if ( ! $is_exists ) {
				$wpdb->get_results( "ALTER TABLE `" . $wpdb->base_prefix . "pwm_sites` ADD `company_address_line3` VARCHAR(100) NOT NULL AFTER company_address_line2;" );
			}
			$is_exists = $wpdb->get_results( "SELECT registered_address_line3 FROM `" . $wpdb->base_prefix . "pwm_sites`" );
			if ( ! $is_exists ) {
				$wpdb->get_results( "ALTER TABLE `" . $wpdb->base_prefix . "pwm_sites` ADD `registered_address_line3` VARCHAR(100) NOT NULL AFTER registered_address_line2;" );
			}
			$is_exists = $wpdb->get_results( "SELECT subdomain_url FROM `" . $wpdb->base_prefix . "pwm_sites`" );
			if ( ! $is_exists ) {
				$wpdb->get_results( "ALTER TABLE `" . $wpdb->base_prefix . "pwm_sites` ADD `subdomain_url` VARCHAR(100) NOT NULL AFTER id;" );
			}

		}

		public function page_site() {
			global $wpdb;

			require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-site-builder.php' );
			require_once( plugin_dir_path( PWM_FILE ) . '/inc/class-pwm-pdf.php' );

			$message = array(
				'built'   => array(),
				'error'     => array()
			);
			$domain_site_id = '';
			$is_db_exists = '';

			$site_url = site_url();
			$form_action = $site_url . '/signup/';

			if ( isset( $_GET['site_message'] ) && $_GET['site_message'] == 1 && isset( $_GET['site_id'] ) ) {
				$message['built'][] = __( 'Your website, will be live in the next 24 hours.', 'pwm-sites' );
			}

            $this->site_data = array(
                'subdomain'                      => '',
                'username'                       => '',
                'password'                       => '',
                'email'                          => '',
                'display_name'                   => '',
                'company_name'                   => '',
                'company_phone'                  => '',
                'company_email'                  => '',
                'company_address_line1'          => '',
                'company_address_line2'          => '',
                'company_address_line3'          => '',
                'company_address_city'           => '',
                'company_address_location'       => '',
                'company_address_postal_code'    => '',
                'registered_address_line1'       => '',
                'registered_address_line2'       => '',
                'registered_address_line3'       => '',
                'registered_address_city'        => '',
                'registered_address_location'    => '',
                'registered_address_postal_code' => '',
                'registration_number'            => '',
                'vat_number'                     => '',
            );

			if ( isset( $_POST['pwm-submit-block__button'] ) && isset( $_POST['pwm_signup_site_creation'] ) && wp_verify_nonce( $_POST['pwm_signup_site_creation'], 'build_site') ) {

				$company_name                   = ( isset( $_POST['pwm_signup_company_name'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_name'] ) : '';
				$company_address_city           = ( isset( $_POST['pwm_signup_company_city'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_city'] ) : '';
				$registered_address_city        = ( isset( $_POST['pwm_signup_registered_city'] ) ) ? sanitize_text_field( $_POST['pwm_signup_registered_city'] ) : '';
				$company_address_line1          = ( isset( $_POST['pwm_signup_company_address_1'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_address_1'] ) : '';
				$company_address_line2          = ( isset( $_POST['pwm_signup_company_address_2'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_address_2'] ) : '';
				$company_address_line3          = ( isset( $_POST['pwm_signup_company_address_3'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_address_3'] ) : '';
				$company_address_postal_code    = ( isset( $_POST['pwm_signup_company_postcode'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_postcode'] ) : '';
				$registered_address_postal_code = ( isset( $_POST['pwm_signup_registered_postcode'] ) ) ? sanitize_text_field( $_POST['pwm_signup_registered_postcode'] ) : '';
				$registered_address_line1       = ( isset( $_POST['pwm_signup_registered_address_1'] ) ) ? sanitize_text_field( $_POST['pwm_signup_registered_address_1'] ) : '';
				$registered_address_line2       = ( isset( $_POST['pwm_signup_registered_address_2'] ) ) ? sanitize_text_field( $_POST['pwm_signup_registered_address_2'] ) : '';
				$registered_address_line3       = ( isset( $_POST['pwm_signup_registered_address_3'] ) ) ? sanitize_text_field( $_POST['pwm_signup_registered_address_3'] ) : '';
				$company_address_location       = ( isset( $_POST['pwm_signup_company_country'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_country'] ) : '';
				$registered_address_location    = ( isset( $_POST['pwm_signup_registered_country'] ) ) ? sanitize_text_field( $_POST['pwm_signup_registered_country'] ) : '';
				$company_email                  = ( isset( $_POST['pwm_signup_company_email'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_email'] ) : '';
				$company_phone                  = ( isset( $_POST['pwm_signup_company_phone'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_phone'] ) : '';
				$registration_number            = ( isset( $_POST['pwm_signup_company_registr_number'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_registr_number'] ) : '';
				$vat_number                     = ( isset( $_POST['pwm_signup_company_vat_number'] ) ) ? sanitize_text_field( $_POST['pwm_signup_company_vat_number'] ) : '';

				$subdomain    = ( isset( $_POST['pwm_signup_subdomain'] ) ) ? sanitize_text_field( $_POST['pwm_signup_subdomain'] ) : '';
				$email        = ( isset( $_POST['pwm_signup_site_email'] ) ) ? sanitize_text_field( $_POST['pwm_signup_site_email'] ) : '';
				$display_name = ( isset( $_POST['pwm_signup_subdomain'] ) ) ? sanitize_text_field( $_POST['pwm_signup_subdomain'] ) : '';
				$username     = ( isset( $_POST['pwm_signup_username'] ) ) ? sanitize_text_field( $_POST['pwm_signup_username'] ) : '';
				$password     = ( isset( $_POST['pwm_signup_password'] ) ) ? sanitize_text_field( $_POST['pwm_signup_password'] ) : '';

				$company_error = array();

				if ( empty( $company_name ) ) {
					$company_error[] = __( 'Company Name is required.', 'pwm-sites' );
				}
				if ( empty( $company_phone ) ) {
					$company_error[] = __( 'Telephone is required.', 'pwm-sites' );
				}
				if ( empty( $company_email ) ) {
					$company_error[] = __( 'Company Email is required.', 'pwm-sites' );
				} elseif ( ! is_email( $company_email ) ) {
					$company_error[] = __( 'Company Email is invalid.', 'pwm-sites' );
				}
				if ( empty( $company_address_line1 ) && empty( $company_address_line2 ) && empty( $company_address_line3 ) ) {
					$company_error[] = __( 'Company Address is required.', 'pwm-sites' );
				}
				if ( empty( $registered_address_line1 ) && empty( $registered_address_line2 ) && empty( $registered_address_line3 ) ) {
					$company_error[] = __( 'Registered Address is required.', 'pwm-sites' );
				}
				if ( empty( $company_address_city ) ) {
					$company_error[] = __( 'Company City is required.', 'pwm-sites' );
				}
				if ( empty( $registered_address_city ) ) {
					$company_error[] = __( 'Registered City is required.', 'pwm-sites' );
				}
				if ( empty( $company_address_location ) ) {
					$company_error[] = __( 'Company Country is required.', 'pwm-sites' );
				}
				if ( empty( $registered_address_location ) ) {
					$company_error[] = __( 'Registered Country is required.', 'pwm-sites' );
				}
				if ( empty( $company_address_postal_code ) ) {
					$company_error[] = __( 'Company Postcode is required.', 'pwm-sites' );
				}
				if ( empty( $registered_address_postal_code ) ) {
					$company_error[] = __( 'Registered Postcode is required.', 'pwm-sites' );
				}
				if ( empty( $registration_number ) ) {
					$company_error[] = __( 'Co Registration Number is required.', 'pwm-sites' );
				}
				if ( empty( $vat_number ) ) {
					$company_error[] = __( 'Company VAT Number is required.', 'pwm-sites' );
				}

				if ( empty( $subdomain ) ) {
					$company_error[] = __( 'Domain Name is required.', 'pwm-sites' );
				} else {
					$check_domain_query = $wpdb->prepare( "SELECT `id` FROM {$wpdb->base_prefix}pwm_sites WHERE subdomain = %s", $subdomain );
					$domain_site_id     = $wpdb->get_var( $check_domain_query );

					if ( $domain_site_id ) {
						$company_error[] = sprintf( __( "Subdomain '%s' is already exists.", 'pwm-sites' ), $subdomain );
					}
				}
				if ( empty( $email ) ) {
					$company_error[] = __( 'Email is required.', 'pwm-sites' );
				} elseif ( ! is_email( $email ) ) {
					$company_error[] = __( 'Email is invalid.', 'pwm-sites' );
				}

				if ( empty( $username ) ) {
					$company_error[] = __( 'Username is required.', 'pwm-sites' );
				}
				if ( empty( $password ) ) {
					$company_error[] = __( 'Password is required.', 'pwm-sites' );
				}
				if ( ! isset( $_POST['pwm_signup_terms'] ) ) {
					$company_error[] = __( 'Terms & Conditions are required.', 'pwm-sites' );
                }

				$company_details = array(
					'display_name'                   => $display_name,
					'company_name'                   => $company_name,
					'company_phone'                  => $company_phone,
					'company_address_city'           => $company_address_city,
					'company_email'                  => $company_email,
					'company_address_line1'          => $company_address_line1,
					'company_address_line2'          => $company_address_line2,
					'company_address_line3'          => $company_address_line3,
					'registered_address_city'        => $registered_address_city,
					'company_address_location'       => $company_address_location,
					'company_address_postal_code'    => $company_address_postal_code,
					'registered_address_line1'       => $registered_address_line1,
					'registered_address_line2'       => $registered_address_line2,
					'registered_address_line3'       => $registered_address_line3,
					'registered_address_location'    => $registered_address_location,
					'registered_address_postal_code' => $registered_address_postal_code,
					'registration_number'            => $registration_number,
					'vat_number'                     => $vat_number,

					'subdomain'   => preg_replace( '/[^a-z0-9-]/i', '', strtolower( $subdomain ) ),
					'email'       => $email,
					'username'    => sanitize_user( wp_unslash( $username ), true ),
					'password'    => $password,
				);

				if ( ! $company_error ) {
					$company_details['date_submitted'] = date_i18n( 'Y-m-d H:m:s' );

					$this->site_data = array_merge( $this->site_data, $company_details );

					$result_insert = $wpdb->insert(
						"{$wpdb->base_prefix}pwm_sites",
						$company_details,
						array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
					);

					if ( ! empty( $this->site_data['company_email'] ) ) {
						$content = "<img src='https://" . $_SERVER['SERVER_NAME'] . "/wp-content/plugins/pwm-sites/images/pdf-logo-2.jpg'" . "\r\n\r\n";
						$content .= "<br>";
	                    $content .= "<p style='font-size: 14px; font-family: \"Futuraptbook-text\", sans-serif;' >" . "Thank you for signing up to Pathway MIS, we are creating your site which will be live within the next 24 hours." . "</p>";
	                    $content .= "<p style='font-size: 14px;' >" . "The next email you receive from us will contain your site url and full login details." . "</p>" . "<br>";
						$content .= "0800 107 0722 | sites@pathwaymis.co.uk | " . "<a href='https://pathwaymis.co.uk' target='_blank'>" . "www.pathwaymis.co.uk" . "</a>" . "\r\n";
						$content .= "<p style='font-size: 12px;' >" . "We Know Print Ltd T/A Pathway MIS | A company registered in England & Wales | 10160343" . "</p>" . "\r\n";
						$content .= "<p style='font-size: 12px;' >" . "This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whome they are addressed. If you received this email in error please notify the system manager. This message contains confidential information and is intended only for the individual named. If you are not the named addressee you should not disseminate, distribute or copy this-mail. Please the sender immediately by e-mail have received this e-mail by mistake and delete this e-mail from your system. If you are not the intended recipient you are notified that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited." . "</p>";

						$headers = array();
						$headers[] = 'Content-type: text/html;';
	                    $headers[] = 'From: PathwayMIS <sites@pathwaymis.co.uk>' . "\r\n";
	                    $process_mail = wp_mail( $this->site_data['company_email'], 'Pathwaymis', $content, $headers );

                        if ( ! $process_mail ) {
                            $message['error'][] = __( 'The Email hasn\'t been sent.', 'pwm-sites' );
                        }
                    }

                    $content = __( "There is a new application for site creation. Company and site details: " . "\r\n", 'pwm-sites' );
                    foreach ( $company_details as $name => $value ) {
                        $content .= $name . " => " . $value . "\r\n";
                    }
                    $headers = 'From: PathwayMIS <sites@pathwaymis.co.uk>' . "\r\n";
                    $process_mail_to_admin = wp_mail( 'sites@pathwaymis.co.uk', 'A new application for site creation.', $content, $headers );

                    if ( ! $process_mail_to_admin ) {
                        $message['error'][] = __( 'The Email to Administrator hasn\'t been sent.', 'pwm-sites' );
                    }

                    if ( $result_insert && empty( $message['error'] ) ) {
                        if ( isset( $_GET['site_id'] ) ) {
                            unset( $_GET['site_id'] );
                        }
                        if ( isset( $_GET['site_message'] ) ) {
                            unset( $_GET['site_message'] );
                        }
                        wp_redirect( $form_action . '?site_id=' . $wpdb->insert_id . '&site_message=1' );
                        exit;
                    }

				} else {
					$this->site_data = array_merge( $this->site_data, $company_details );
					$message['error'][] = implode( '<br>', $company_error );
					$message['error'][] = __( 'Please make corrections below and try again.', 'pwm-sites' );
				}
			}

			if ( ! function_exists( 'plugin_dir_path' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$styles_file_path = plugin_dir_url( __FILE__ );
			$styles_file_path = dirname( $styles_file_path ) . "/css/signup-styles.css"; ?>
			<link rel="stylesheet" href="<?php echo $styles_file_path; ?>" >
			<meta name="viewport" content="width=device-width,initial-scale=1">
            <div class="pwm-signup-wrapper">
	            <?php if ( current_user_can( 'manage_options' ) ) {
		            add_filter( 'show_admin_bar', '__return_true' );
	            } ?>
                <!-- Page header -->
				<div class="pwm-signup-header">
					<div class="pwm-signup-header__wrapper clearfix">
                        <a href="https://www.pathwaymis.co.uk"><img src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/plugins/pwm-login/images/logo-login-top.jpg" alt="Main logo" class="pwm-signup-header__logo"></a>
						<nav class="pwm-signup-header__main-nav">
							<ul class="pwm-signup-header__nav-list">
								<li class="pwm-signup-header__nav-item"><a href="https://www.pathwaymis.co.uk/features" class="pwm-signup-header__nav-link">Features</a></li>
								<li class="pwm-signup-header__nav-item"><a href="https://www.pathwaymis.co.uk/plans-pricing" class="pwm-signup-header__nav-link">Plans & Pricing</a></li>
								<li class="pwm-signup-header__nav-item"><a href="https://acp.pathwaymis.co.uk/signup" class="pwm-signup-header__nav-link pwm-signup-header__nav-link--active">Sign up</a></li>
							</ul>
						</nav>
					</div>
				</div>

				<!-- Main part of the page -->
				<div class="pwm-signup-main">
					<div class="pwm-signup-main__wrapper">
						<h1 class="pwm-signup-main__page-title">Sign Up</h1>
						<?php foreach ( $message as $class => $messages ) {
							if ( ! empty( $class ) ) {
								foreach ( $messages as $text ) { ?>
                                    <div class="pwm-notice pwm-notice-<?php echo $class; ?>">
                                        <p>
                                            <strong><?php echo $text; ?></strong>
                                        </p>
                                    </div>
								<?php }
							}
                        } ?>
						<hr class="pwm-signup-main__top-line pwm-signup-line">
						<div class="pwm-signup-main__form-wrapper">
							<p class="pwm-signup-main__form-caption">Signing up is easy - just enter your details below to get started.</p>
							<form action="<?php echo esc_url( $form_action ); ?>" class="pwm-signup-main__form pwm-signup-form" method="post">
                                <?php wp_nonce_field( 'build_site', 'pwm_signup_site_creation' ); ?>

								<div class="pwm-signup-form__item">
									<label for="pwm_signup_company_name" class="pwm-signup-form__label">Company Name :</label>
                                    <?php $error_class = '';
                                    if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $company_name ) ) {
                                        $error_class = " pwm-signup-form__input--error";
                                    } ?>
									<input type="text" id="pwm_signup_company_name" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_name" value="<?php echo $this->site_data['company_name']; ?>" maxlength="100" placeholder="Company Name">
								</div>

                                <div class="pwm-signup-form__two-items clearfix">
                                    <div class="pwm-signup-form__item pwm-signup-form__item--left">
                                        <label for="pwm_signup_company_phone" class="pwm-signup-form__label">Company Phone :</label>
		                                <?php $error_class = '';
		                                if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $company_phone ) ) {
			                                $error_class = " pwm-signup-form__input--error";
		                                } ?>
                                        <input type="text" id="pwm_signup_company_phone" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_phone" maxlength="64" value="<?php echo $this->site_data['company_phone']; ?>" maxlength="64" placeholder="Company Phone">
                                    </div>

                                    <div class="pwm-signup-form__item pwm-signup-form__item--right">
                                        <label for="pwm_signup_company_email" class="pwm-signup-form__label">Company Email :</label>
		                                <?php $error_class = '';
		                                if ( isset( $_POST['pwm-submit-block__button'] ) && ( empty( $company_email ) || ! is_email( $company_email ) ) ) {
			                                $error_class = " pwm-signup-form__input--error";
		                                } ?>
                                        <input type="email" id="pwm_signup_company_email" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_email" value="<?php echo $this->site_data['company_email']; ?>" maxlength="64" placeholder="mail@example.com">
                                    </div>
                                </div>

                                <div class="pwm-signup-form__item">
                                    <label for="pwm_signup_company_address_1" class="pwm-signup-form__label">Company Address :</label>
									<?php $error_class = '';
									if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $company_address_line1 ) && empty( $company_address_line2 ) && empty( $company_address_line3 ) ) {
										$error_class = " pwm-signup-form__input--error";
									} ?>
                                    <input type="text" id="pwm_signup_company_address_1" class="pwm-signup-form__input pwm-signup-form__input--address<?php echo $error_class; ?>" name="pwm_signup_company_address_1" value="<?php echo $this->site_data['company_address_line1']; ?>" maxlength="100" placeholder="Company Address line1"><br>
                                    <input type="text" class="pwm-signup-form__input pwm-signup-form__input--address" name="pwm_signup_company_address_2" value="<?php echo $this->site_data['company_address_line2']; ?>" maxlength="100" placeholder="Company Address line2"><br>
                                    <input type="text" class="pwm-signup-form__input pwm-signup-form__input--address" name="pwm_signup_company_address_3" value="<?php echo $this->site_data['company_address_line3']; ?>" maxlength="100" placeholder="Company Address line3">
                                </div>

                                <div class="pwm-signup-form__two-items clearfix">
                                    <div class="pwm-signup-form__item pwm-signup-form__item--left">
                                        <label for="pwm_signup_company_city" class="pwm-signup-form__label">Company City :</label>
										<?php $error_class = '';
										if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $company_address_city ) ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
                                        <input type="text" id="pwm_signup_company_city" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_city" value="<?php echo $this->site_data['company_address_city']; ?>" maxlength="64" placeholder="Company City">
                                    </div>
                                    <div class="pwm-signup-form__item pwm-signup-form__item--right">
		                                <?php $error_class = '';
		                                if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $company_address_postal_code ) ) {
			                                $error_class = " pwm-signup-form__input--error";
		                                } ?>
                                        <label for="pwm_signup_company_postcode" class="pwm-signup-form__label">Company Postcode :</label>
                                        <input type="text" id="pwm_signup_company_postcode" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_postcode" value="<?php echo $this->site_data['company_address_postal_code']; ?>" maxlength="64" placeholder="Company Postcode">
                                    </div>
                                </div>

                                <div class="pwm-signup-form__item">
                                    <label for="pwm_signup_company_country" class="pwm-signup-form__label">Company County :</label>
									<?php $error_class = '';
									if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $company_address_location ) ) {
										$error_class = " pwm-signup-form__input--error";
									} ?>
                                    <input type="text" id="pwm_signup_company_country" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_country" value="<?php echo $this->site_data['company_address_location']; ?>" maxlength="100" placeholder="Company County">
                                </div>

                                <div class="pwm-signup-form__item">
                                    <label for="pwm_signup_registered_address_1" class="pwm-signup-form__label">Registered Address :</label>
									<?php $error_class = '';
									if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $registered_address_line1 ) && empty( $registered_address_line2 ) && empty( $registered_address_line3 ) ) {
										$error_class = " pwm-signup-form__input--error";
									} ?>
                                    <input type="text" id="pwm_signup_registered_address_1" class="pwm-signup-form__input pwm-signup-form__input--address<?php echo $error_class; ?>" name="pwm_signup_registered_address_1" value="<?php echo $this->site_data['registered_address_line1']; ?>" maxlength="100" placeholder="Register Address line1"><br>
                                    <input type="text" class="pwm-signup-form__input pwm-signup-form__input--address" name="pwm_signup_registered_address_2" value="<?php echo $this->site_data['registered_address_line2']; ?>" maxlength="100" placeholder="Register Address line2"><br>
                                    <input type="text" class="pwm-signup-form__input pwm-signup-form__input--address" name="pwm_signup_registered_address_3" value="<?php echo $this->site_data['registered_address_line3']; ?>" maxlength="100" placeholder="Register Address line3">
                                </div>

								<div class="pwm-signup-form__two-items clearfix">

									<div class="pwm-signup-form__item pwm-signup-form__item--left">
										<label for="pwm_signup_registered_city" class="pwm-signup-form__label">Registered City :</label>
										<?php $error_class = '';
                                        if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $registered_address_city ) ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
										<input type="text" id="pwm_signup_registered_city" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_registered_city" value="<?php echo $this->site_data['registered_address_city']; ?>" maxlength="64" placeholder="Register City">
									</div>

                                    <div class="pwm-signup-form__item pwm-signup-form__item--right">
                                        <label for="pwm_signup_registered_postcode" class="pwm-signup-form__label">Registered Postcode :</label>
										<?php $error_class = '';
										if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $registered_address_postal_code ) ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
                                        <input type="text" id="pwm_signup_registered_postcode" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_registered_postcode" value="<?php echo $this->site_data['registered_address_postal_code']; ?>" maxlength="64" placeholder="Register Postcode">
                                    </div>
								</div>

                                <div class="pwm-signup-form__item">
                                    <label for="pwm_signup_registered_country" class="pwm-signup-form__label">Registered County :</label>
                                    <?php $error_class = '';
                                    if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $registered_address_location ) ) {
                                        $error_class = " pwm-signup-form__input--error";
                                    } ?>
                                    <input type="text" id="pwm_signup_registered_country" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_registered_country" value="<?php echo $this->site_data['registered_address_location']; ?>" maxlength="100" placeholder="Register County">
                                </div>

								<div class="pwm-signup-form__two-items clearfix">
									<div class="pwm-signup-form__item pwm-signup-form__item--left">
										<label for="pwm_signup_company_registr_number" class="pwm-signup-form__label">Co Registration Number :</label>
										<?php $error_class = '';
										if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $registration_number ) ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
										<input type="text" id="pwm_signup_company_registr_number" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_registr_number" value="<?php echo $this->site_data['registration_number']; ?>" maxlength="64" placeholder="Company Registration Number">
									</div>

									<div class="pwm-signup-form__item pwm-signup-form__item--right">
										<label for="pwm_signup_company_vat_number" class="pwm-signup-form__label">Company VAT Number :</label>
										<?php $error_class = '';
										if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $vat_number ) ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
										<input type="text" id="pwm_signup_company_vat_number" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_company_vat_number" value="<?php echo $this->site_data['vat_number']; ?>" maxlength="64" placeholder="Company VAT Number">
									</div>
								</div>

								<hr class="pwm-signup-form__middle-line pwm-signup-line">

								<div class="pwm-signup-form__two-items clearfix">
									<div class="pwm-signup-form__item pwm-signup-form__item--left">
										<label for="pwm_signup_subdomain" class="pwm-signup-form__label">Domain Name Required :</label>
										<?php $error_class = '';
										if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $subdomain ) || $domain_site_id ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
										<input id="pwm_signup_subdomain" type="text" class="pwm-signup-form__input pwm-signup-form__input--domain<?php echo $error_class; ?>" name="pwm_signup_subdomain" value="<?php echo $this->site_data['subdomain']; ?>" maxlength="64" placeholder="Domain Name">
									</div>
									<p class="pwm-signup-form__label pwm-signup-form__item--right">. Pathwaymis.co.uk</p>
								</div>
								<div class="pwm-signup-form__error pwm-signup-form__error--input-text pwm-signup-form__error--domain pwm-signup-form__hidden">
                                    <?php if ( isset( $_POST['pwm-submit-block__button'] ) && ! empty( $domain_site_id ) ) { ?>
	                                    <span>Sorry, this has already been taken! Please try another domain name.</span>
                                    <?php } ?>
								</div>

								<div class="pwm-signup-form__two-items clearfix">
                                    <div class="pwm-signup-form__item pwm-signup-form__item--left">
                                        <label for="pwm_signup_site-email" class="pwm-signup-form__label">Email :</label>
										<?php $error_class = '';
										if ( isset( $_POST['pwm-submit-block__button'] ) && ( empty( $email ) || ! is_email( $email ) ) ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
                                        <input type="email" id="pwm_signup_site-email" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_site_email" value="<?php echo $this->site_data['email']; ?>" maxlength="64" placeholder="mail@example.com">
                                    </div>

									<div class="pwm-signup-form__item pwm-signup-form__item--right">
										<label for="pwm_signup_username" class="pwm-signup-form__label">Username :</label>
										<?php $error_class = '';
										if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $username ) ) {
											$error_class = " pwm-signup-form__input--error";
										} ?>
										<input type="text" id="pwm_signup_username" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_username" value="<?php echo $this->site_data['username']; ?>" maxlength="64" placeholder="Username">
									</div>
								</div>

								<div class="pwm-signup-form__item">
									<label for="pwm_signup_password" class="pwm-signup-form__label">Password :</label>
									<?php $error_class = '';
									if ( isset( $_POST['pwm-submit-block__button'] ) && empty( $password ) ) {
										$error_class = " pwm-signup-form__input--error";
									} ?>
									<input type="password" id="pwm_signup_password" class="pwm-signup-form__input<?php echo $error_class; ?>" name="pwm_signup_password" value="<?php echo $this->site_data['password']; ?>" maxlength="64" placeholder="password">
								</div>
								<div class="pwm-signup-form__error pwm-signup-form__error--input-text pwm-signup-form__hidden">
                                    <span class="pwm-signup-form__error pwm-signup-form__hidden">Passwords must contain at least 6 characters long, uppercase and lowercase
                                            letters, at least one digit and a special character.</span>
								</div>

								<hr class="pwm-signup-form__bottom-line pwm-signup-line">

								<div class="pwm-signup-form__item">
									<label for="pwm_signup_terms" class="pwm-signup-form__label pwm-signup-form__label--terms">Terms & Conditions :</label><br>
									<?php $error_class = '';
									if ( isset( $_POST['pwm-submit-block__button'] ) && ! isset( $_POST['pwm_signup_terms'] ) ) {
										$error_class = " pwm-signup-form__input--error";
									} ?>
									<input type="checkbox" id="pwm_signup_terms" class="pwm-signup-form__checkbox<?php echo $error_class; ?>" name="pwm_signup_terms">
									<div class="pwm-signup-form__terms-text">
										<p>I understand that once the 30 day trial period is over, my account will be suspended
											until full sign-up with payment is complete. No card details are required for signing up to a 30 day trial with Pathway MIS.</p>
										<div class="pwm-signup-form__error pwm-signup-form__hidden">
											<span class="pwm-signup-form__error pwm-signup-form__hidden">Please tick.</span>
										</div>
									</div>
								</div>

								<div class="pwm-signup-form__submit-block pwm-submit-block clearfix">
									<button class="pwm-submit-block__button" name="pwm-submit-block__button">Submit</button>
									<div class="pwm-submit-block__logo">
										<?php $logo_path = plugin_dir_url( __FILE__ );
										$logo_path = dirname( $logo_path ) . "/images/quickbooks-logo.png"; ?>
										<img src="<?php echo $logo_path; ?>" alt="quickbooks" class="pwm-submit-block__image">
									</div>
									<div class="pwm-submit-block__label-block">
										<p class="pwm-submit-block__label-text"><b>GO</b>CARDLESS</p>
									</div>
								</div>
							</form>
						</div>

					</div>
				</div>

				<!-- Page footer -->
				<div class="pwm-signup-footer">
					<div class="pwm-signup-footer__wrapper clearfix">
                        <a href="https://www.pathwaymis.co.uk"><img src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/wp-content/plugins/pwm-login/images/logo-login-bottom.jpg" alt="Footer logo" class="pwm-signup-footer__logo"></a>
						<nav class="pwm-signup-footer__nav">
							<ul class="pwm-signup-footer__nav-list">
								<li class="pwm-signup-footer__nav-item"><a href="https://www.pathwaymis.co.uk/contact" class="pwm-signup-footer__nav-link">Contact</a></li>
								<li class="pwm-signup-footer__nav-item"><a href="https://www.pathwaymis.co.uk/terms-conditions" class="pwm-signup-footer__nav-link">Terms & Conditions</a></li>
								<li class="pwm-signup-footer__nav-item"><a href="https://www.pathwaymis.co.uk/privacy-policy" class="pwm-signup-footer__nav-link">Privacy Policy</a></li>
								<li class="pwm-signup-footer__nav-item"><a href="https://www.pathwaymis.co.uk/cookie-policy" class="pwm-signup-footer__nav-link">Cookie Policy</a></li>
							</ul>
						</nav>
					</div>
                    <hr class="footer-line">
                </div>
			</div>
		<?php
			if ( current_user_can( 'manage_sites' ) ) {
				wp_footer();
			}
            die();
		}

		public function hooks() {
			add_filter( 'template_include', array( $this, 'page_site' ) );
		}
	}
}
