<?php

require_once( dirname( __DIR__ ) . '/xero/src/XeroPHP/loader.php' );

use XeroPHP\Remote\URL;
use XeroPHP\Remote\Request;
/* use XeroPHP\Application\PublicApplication; */
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting;

class QC_Xero {

    use XeroPHP\Traits\SendEmailTrait;

	public $xero;

	public $oauth_response;

	public function __construct() {
		global $quote_calculator_options;

		if ( ! isset( $quote_calculator_options ) ) {
			$quote_calculator_options = get_option( 'quote_calculator_options' );
		}

		if ( empty( $quote_calculator_options['xero_consumer_key'] ) ||
             empty( $quote_calculator_options['xero_consumer_secret'] ) ) {
			$this->xero = 'Connection error';
			echo( 'You didn\'t connect to Xero' );
			exit;
		}

		$site_url = site_url();

		/* if ( 'public' == $quote_calculator_options['xero_app_type'] ) {
			$public_certificate = dirname(__DIR__ ) . '/xero/src/certificates/ca-bundle.crt';

			if ( ! is_file( $public_certificate ) ) {
				$this->xero = 'Connection error';
				echo( 'You haven\'t a certificate for Public app' );
				exit;
            }

			// Start a session for the oauth session storage
			if ( ! session_id() ) {
				session_start();
			}

			$config = [
				'oauth' => [
					'callback'        => $site_url . '/wp-admin/admin.php?page=quote_calculator_xero',
					'consumer_key'    => $quote_calculator_options['xero_consumer_key'] ? $quote_calculator_options['xero_consumer_key'] : '',
					'consumer_secret' => $quote_calculator_options['xero_consumer_secret'] ? $quote_calculator_options['xero_consumer_secret'] : '',
				],
				'curl'  => [
					CURLOPT_CAINFO => $public_certificate,
				],
			];

			$this->xero = new XeroPHP\Application\PublicApplication( $config );

			//if no session or if it is expired
			if ( ( null === $oauth_session = $this->getOAuthSession() ) ) {

				$url = new XeroPHP\Remote\URL( $this->xero, XeroPHP\Remote\URL::OAUTH_REQUEST_TOKEN );

				$request = new XeroPHP\Remote\Request( $this->xero, $url );

				//Here's where you'll see if your keys are valid.
				//You can catch a BadRequestException.
				try {
					$request->send();
				} catch ( Exception $e ) {
					print_r( $e );
					if ( $request->getResponse() ) {
						print_r( $request->getResponse()->getOAuthResponse() );
					}
					exit;
				}

				if ( ! isset( $e ) ) {

					$this->oauth_response = $request->getResponse()->getOAuthResponse();

					$this->setOAuthSession(
						$this->oauth_response['oauth_token'],
						$this->oauth_response['oauth_token_secret']
					);
				}
			} else {
				$uri_parts = explode( '&', $_SERVER['REQUEST_URI'] );

				$this->xero->getOAuthClient()
				           ->setToken( $oauth_session['xero_token'] )
				           ->setTokenSecret( $oauth_session['xero_token_secret'] );

				if ( isset( $_REQUEST['oauth_verifier'] ) ) {
					$this->xero->getOAuthClient()->setVerifier( $_REQUEST['oauth_verifier'] );

					$url = new XeroPHP\Remote\URL( $this->xero, XeroPHP\Remote\URL::OAUTH_ACCESS_TOKEN );

					$request = new XeroPHP\Remote\Request( $this->xero, $url );

					$request->send();

					$this->oauth_response = $request->getResponse()->getOAuthResponse();

					$this->setOAuthSession(
						$this->oauth_response['oauth_token'],
						$this->oauth_response['oauth_token_secret'],
						$this->oauth_response['oauth_expires_in']
					);

					$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $uri_parts[0];

					?>
                    <script type="text/javascript">
						window.location.href = "<?php echo $url; ?>";
                    </script>
					<?php
				}
			}
		} elseif ( 'private' == $quote_calculator_options['xero_app_type'] ) { */
			$private_certificate = 'file://' . dirname( dirname( __FILE__ ) )  . '/xero/src/certificates/privatekey.pem';

			if ( ! openssl_pkey_get_private( $private_certificate ) ) {
				$this->xero = 'Connection error';
				echo( 'You haven\'t a certificate for Private app' );
				exit;
			}

			$config = [
				'oauth' => [
					'callback' => $site_url . '/wp-admin/admin.php?page=quote_calculator_xero',
					'consumer_key' => $quote_calculator_options['xero_consumer_key'] ? $quote_calculator_options['xero_consumer_key'] : '',
					'consumer_secret' => $quote_calculator_options['xero_consumer_secret'] ? $quote_calculator_options['xero_consumer_secret'] : '',
					'rsa_private_key' => $private_certificate,
				],
			];

			$this->xero = new XeroPHP\Application\PrivateApplication( $config );

		/* } else {
		    exit;
        } */
		$this->add_columns();
	}

	/* private function setOAuthSession( $token, $secret, $expires = null ) {
		// expires sends back an int
		if ( $expires !== null ) {
			$expires = time() + ( int ) $expires;
		}
		$_SESSION['xero_oauth'] = [
			'xero_token' => $token,
			'xero_token_secret' => $secret,
			'xero_expires' => $expires,
		];
	}

	private function getOAuthSession() {
		//If it doesn't exist or is expired, return null
		if ( ! isset($_SESSION['xero_oauth'] )
		     || ($_SESSION['xero_oauth']['xero_expires'] !== null
		         && $_SESSION['xero_oauth']['xero_expires'] <= time())
		) {
			return;
		}
		return $_SESSION['xero_oauth'];
	} */

	private function qc_xero_create_address_object( $address_id ) {
	    global $wpdb;
		$current_address = $wpdb->get_row( 'SELECT * FROM `' . $wpdb->base_prefix . 'quote_calculator_customer_address` WHERE customer_address_id=' . $address_id, ARRAY_A );

		$address = new Accounting\Address( $this->xero );

		if ( 1 == $current_address['customer_address_type'] ) {
		    $address_type = $address::ADDRESS_TYPE_POBOX;
        } else {
			$address_type = $address::ADDRESS_TYPE_STREET;
        }

		$address->setAddressType( $address_type )
                ->setAddressLine1( $current_address['customer_address_line1'] );

		if ( $current_address['customer_address_line2'] ) {
			$address->setAddressLine2( $current_address['customer_address_line2'] );
		}
		if ( $current_address['customer_address_line3'] ) {
			$address->setAddressLine3( $current_address['customer_address_line3'] );
		}
		if ( $current_address['customer_address_city'] ) {
			$address->setCity( $current_address['customer_address_city'] );
		}
		if ( $current_address['customer_address_country'] ) {
			$address->setCountry( $current_address['customer_address_country'] );
		}
		if ( $current_address['customer_address_country_sub_division_code'] ) {
			$address->setRegion( $current_address['customer_address_country_sub_division_code'] );
		}
		if ( $current_address['customer_address_postal_code'] ) {
			$address->setPostalCode( $current_address['customer_address_postal_code'] );
		}

		return $address;
    }

	private function qc_xero_create_phone_object( $phone_number ) {
        $phone = new Accounting\Phone( $this->xero );
        $phone->setPhoneType( $phone::PHONE_TYPE_DEFAULT );

        $current_customer_phone = explode( ' ', $phone_number );

        switch ( count( $current_customer_phone ) ) {
            case 3:
                $phone->setPhoneCountryCode( $current_customer_phone[0] )
                      ->setPhoneAreaCode( $current_customer_phone[1] )
                      ->setPhoneNumber( $current_customer_phone[2] );
                break;
            case 2:
                $phone->setPhoneCountryCode( $current_customer_phone[0] )
                      ->setPhoneNumber( $current_customer_phone[1] );
                break;
            default:
                $phone->setPhoneNumber( $current_customer_phone[0] );
        }

		return $phone;
	}

	private function qc_xero_create_contact_object( $estimate, $customer ) {
		$contact = new Accounting\Contact( $this->xero );

		$contact->setName( $estimate['estimates_customer_name'] )
		        ->setFirstName( $customer['customers_given_name'] )
		        ->setLastName( $customer['customers_family_name'] )
		        ->setEmailAddress( $estimate['estimates_customer_email'] )
		        ->setContactStatus( $contact::CONTACT_STATUS_ACTIVE )
		        ->setIsCustomer( true );

		if ( 0 != $estimate['estimates_customer_bill_address_id'] ) {
			$bill_address = $this->qc_xero_create_address_object( $estimate['estimates_customer_bill_address_id'] );
			$contact->addAddress( $bill_address );
		}

		if ( 0 != $estimate['estimates_customer_ship_address_id'] ) {
			$ship_address = $this->qc_xero_create_address_object( $estimate['estimates_customer_ship_address_id'] );
			$contact->addAddress( $ship_address );
		}

		/* Create new phone */
		if ( $estimate['estimates_customer_phone'] ) {
			$phone = $this->qc_xero_create_phone_object( $estimate['estimates_customer_phone'] );
			$contact->addPhone( $phone );
		}

		return $contact;
	}

	/* Create invoice and send email to customer */
	public function qc_create_invoice_in_xero() {
	    global $wpdb;

	    $current_estimate_id = explode( '&', $_SERVER['QUERY_STRING'] );
		$current_estimate_id = explode( '=', $current_estimate_id[0] )[1];
		$current_estimate = $wpdb->get_row( 'SELECT * FROM `' . $wpdb->base_prefix . 'quote_calculator_estimates` WHERE estimates_id=' . $current_estimate_id, ARRAY_A );

		$current_customer = $wpdb->get_row( 'SELECT * FROM `' . $wpdb->base_prefix . 'quote_calculator_customers` WHERE customers_id=' . $current_estimate['estimates_customer_id'], ARRAY_A );

		if ( $current_customer['customers_xero_id'] ) {
			$contact = $this->xero->loadByGUID(Accounting\Contact::class, $current_customer['customers_xero_id'] );
        } else {
            $contact = $this->qc_xero_create_contact_object( $current_estimate, $current_customer );
        }

		$line_item = new Accounting\Invoice\LineItem( $this->xero );
		$line_item->setQuantity( '1' )
                  ->setUnitAmount( $current_estimate['estimates_cost'] )
                  ->setAccountCode( '200' );

		$tax_amount = $current_estimate['estimates_cost_incl_vat'] - $current_estimate['estimates_cost'];
		$line_item->setTaxAmount( $tax_amount );

		if ( $current_estimate['estimates_notes'] ) {
			$line_item->setDescription( $current_estimate['estimates_notes'] );
        } else {
			$line_item->setDescription( 'none' );
        }

		$date = new DateTime();

		$currency = new Accounting\Currency( $this->xero );
		$currency->setCode( 'GBP' )
                 ->setDescription( 'United Kingdom Pound' );

		$invoice = new Accounting\Invoice( $this->xero );
		$invoice->setType( $invoice::INVOICE_TYPE_ACCREC )
			->setContact( $contact )
            ->setDate( $date )
			->setLineAmountType( 'Exclusive' )
            ->addLineItem( $line_item )
            ->setStatus( $invoice::INVOICE_STATUS_AUTHORISED );

		if ( $current_estimate['estimates_deadline'] ) {
			$deadline = date( 'F j, Y', $current_estimate['estimates_deadline'] );
		} else {
			$deadline = date( 'F j, Y', strtotime('+1 years') );
		}
		$deadline = new DateTime( $deadline );
		$invoice->setDueDate( $deadline);

		$response = $this->xero->save( $invoice, true );

		if ( $current_estimate['estimates_customer_email'] ) {
			$new_invoice_id = $response->getElements()[0]["InvoiceID"];
			$new_invoice = $this->xero->loadByGUID( Accounting\Invoice::class, $new_invoice_id );
			$new_invoice->sendEmail();
		}
	}

	/* Add columns to the database, if not exists */
	private function add_columns() {
	    global $wpdb;
	    $sql = 'SHOW COLUMNS FROM `' . $wpdb->prefix . 'quote_calculator_customers` LIKE "customers_xero_id"';
	    $is_exists = $wpdb->get_results( $sql );
	    if ( ! $is_exists ) {
		    $wpdb->get_results( 'ALTER TABLE `' . $wpdb->prefix . 'quote_calculator_customers` ADD `customers_xero_id` varchar(100) AFTER `customers_qb_id`;' );
        }
    }

    /* Importing customers info from xero to our db */
	public static function qc_xero_import_customers_function() {
	    global $wpdb, $quote_calculator_options;
		$max_results = 300;

		if ( ! isset( $quote_calculator_options ) ) {
			$quote_calculator_options = get_option( 'quote_calculator_options' );
		}

		if( empty( $quote_calculator_options['xero_start_position'] ) ){
			$quote_calculator_options['xero_start_position'] = 1;
		} else {
			$quote_calculator_options = get_option( 'quote_calculator_options' );
			$max_results = $quote_calculator_options['xero_start_position'] + 299;
		}

		$xero = new QC_Xero();
		$xero = $xero->xero;

		/* Reconnect to xero app, if connection had expired */
		if ( 'public' == $quote_calculator_options['xero_app_type'] && ! $_SESSION['xero_oauth']['xero_expires'] ) {

			/* Unset session and reload page if token has expired */
			unset( $_SESSION['xero_oauth'] );

			$xero = new QC_Xero();

			$url = $xero->xero->getAuthorizeURL( $xero->oauth_response['oauth_token'] );

			?>
            <script type="text/javascript">
	            window.location.href = "<?php echo $url; ?>";
            </script>
			<?php
		}

		$organization = $xero->load( Accounting\Organisation::class )->execute();

		$contacts = $xero->load( Accounting\Contact::class )->execute();
		$number_of_contacts = count( $contacts );

		if ( ! empty( $contacts ) ) {
			foreach ( $contacts as $contact ) {

				if ( $quote_calculator_options['xero_start_position'] > $max_results ) {
				    update_option( 'quote_calculator_options', $quote_calculator_options );
					break;
				} elseif ( $quote_calculator_options['xero_start_position'] >= $number_of_contacts ) {
					wp_clear_scheduled_hook( 'qc_xero_import_customers_hook' );

					$quote_calculator_options['xero_last_import'] = time();
					$quote_calculator_options['xero_import_from'] = $organization[0]['Name'];
					unset( $quote_calculator_options['xero_start_position'] );
					update_option( 'quote_calculator_options', $quote_calculator_options );

					break;
                }

				$quote_calculator_options['xero_start_position']++;

                /* Get one of 4 types of phone - DDI, DEFAULT, FAX, MOBILE */
                $phone = '';
                for ( $i = 0; $i < 4; $i ++ ) {
                    if ( isset( $contact['Phones'][ $i ]['PhoneNumber'] ) ) {
                        $phone_country_code = isset( $contact['Phones'][ $i ]['PhoneCountryCode'] ) ? $contact['Phones'][ $i ]['PhoneCountryCode'] : '';
                        $phone_area_code    = isset( $contact['Phones'][ $i ]['PhoneAreaCode'] ) ? $contact['Phones'][ $i ]['PhoneAreaCode'] : '';
                        $phone              = trim( $phone_country_code . ' ' . $phone_area_code . ' ' . $contact['Phones'][ $i ]['PhoneNumber'] );
                        break;
                    }
                }
                /***********************/

                $current_customer_id = $wpdb->get_var( "SELECT customers_id FROM `" . $wpdb->base_prefix . "quote_calculator_customers` WHERE customers_xero_id='" . $contact['ContactID'] . "'" );

                /* Update customer data */
                if ( isset( $current_customer_id ) ) {

                    $current_customer_bill_address_id = $wpdb->get_var( 'SELECT customers_bill_addr FROM `' . $wpdb->base_prefix . 'quote_calculator_customers` WHERE customers_id=' . $current_customer_id );
                    $current_customer_ship_address_id = $wpdb->get_var( 'SELECT customers_ship_addr FROM `' . $wpdb->base_prefix . 'quote_calculator_customers` WHERE customers_id=' . $current_customer_id );

                    /* Update billing and shipping addresses (POBOX and STREET addresses - xero equivalent) */

                    $table_name = $wpdb->prefix . 'quote_calculator_customer_address';

                    if ( isset( $contact['Addresses'][0]['AddressLine1'] ) ) {

                        $bill_address_info = array(
                            'customer_address_type'                      => '1',
                            'customer_address_line1'                     => $contact['Addresses'][0]['AddressLine1'],
                            'customer_address_line2'                     => isset( $contact['Addresses'][0]['AddressLine2'] ) ? $contact['Addresses'][0]['AddressLine2'] : null,
                            'customer_address_line3'                     => isset( $contact['Addresses'][0]['AddressLine3'] ) ? $contact['Addresses'][0]['AddressLine3'] : null,
                            'customer_address_city'                      => isset( $contact['Addresses'][0]['City'] ) ? $contact['Addresses'][0]['City'] : '',
                            'customer_address_country'                   => isset( $contact['Addresses'][0]['Country'] ) ? $contact['Addresses'][0]['Country'] : null,
                            'customer_address_country_sub_division_code' => isset( $contact['Addresses'][0]['Region'] ) ? $contact['Addresses'][0]['Region'] : null,
                            'customer_address_postal_code'               => isset( $contact['Addresses'][0]['PostalCode'] ) ? $contact['Addresses'][0]['PostalCode'] : null,
                        );

                        $wpdb->update(
                            $table_name,
                            $bill_address_info,
                            array( 'customer_address_id' => $current_customer_bill_address_id ),
                            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
                            array( '%d' )
                        );
                    }

                    if ( isset( $contact['Addresses'][1]['AddressLine1'] ) ) {

                        $ship_address_info = array(
                            'customer_address_type'                      => '2',
                            'customer_address_line1'                     => $contact['Addresses'][1]['AddressLine1'],
                            'customer_address_line2'                     => isset( $contact['Addresses'][1]['AddressLine2'] ) ? $contact['Addresses'][1]['AddressLine2'] : null,
                            'customer_address_line3'                     => isset( $contact['Addresses'][1]['AddressLine3'] ) ? $contact['Addresses'][1]['AddressLine3'] : null,
                            'customer_address_city'                      => isset( $contact['Addresses'][1]['City'] ) ? $contact['Addresses'][1]['City'] : '',
                            'customer_address_country'                   => isset( $contact['Addresses'][1]['Country'] ) ? $contact['Addresses'][1]['Country'] : null,
                            'customer_address_country_sub_division_code' => isset( $contact['Addresses'][1]['Region'] ) ? $contact['Addresses'][1]['Region'] : null,
                            'customer_address_postal_code'               => isset( $contact['Addresses'][1]['PostalCode'] ) ? $contact['Addresses'][1]['PostalCode'] : null,
                        );

                        $wpdb->update(
                            $table_name,
                            $ship_address_info,
                            array( 'customer_address_id' => $current_customer_ship_address_id ),
                            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
                            array( '%d' )
                        );
                    }
                    /* End Update billing and shipping addresses */

                    $table_name = $wpdb->prefix . 'quote_calculator_customers';

                    $first_name   = $contact['FirstName'] ? $contact['FirstName'] : '';
                    $last_name    = $contact['LastName'] ? $contact['LastName'] : '';
                    $company_name = $contact['Name'] ? $contact['Name'] : '';

                    $customer_info = array(
                        'customers_given_name'   => $first_name,
                        'customers_family_name'  => $last_name,
                        'customers_company_name' => $company_name,
                        'customers_display_name' => $company_name ? $company_name : trim( $first_name . $last_name ),
                        'customers_phone'        => $phone,
                        'customers_email'        => $contact['EmailAddress'],
                        'customers_bill_addr'    => $current_customer_bill_address_id,
                        'customers_ship_addr'    => $current_customer_ship_address_id,
                    );

                    $wpdb->update(
                        $table_name,
                        $customer_info,
                        array( 'customers_id' => $current_customer_id ),
                        array( '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' ),
                        array( '%d' )
                    );

                    /* End Update customer data */
                } else {
                    /* Insert customer data */
                    $customer_bill_address_id = $customer_ship_address_id = 0;

                    /* Insert billing and shipping addresses */

                    $table_name = $wpdb->prefix . 'quote_calculator_customer_address';

                    if ( isset( $contact['Addresses'][0]['AddressLine1'] ) ) {
                        $bill_address_info = array(
                            'customer_address_type'                      => '1',
                            'customer_address_line1'                     => $contact['Addresses'][0]['AddressLine1'],
                            'customer_address_line2'                     => isset( $contact['Addresses'][0]['AddressLine2'] ) ? $contact['Addresses'][0]['AddressLine2'] : null,
                            'customer_address_line3'                     => isset( $contact['Addresses'][0]['AddressLine3'] ) ? $contact['Addresses'][0]['AddressLine3'] : null,
                            'customer_address_city'                      => isset( $contact['Addresses'][0]['City'] ) ? $contact['Addresses'][0]['City'] : '',
                            'customer_address_country'                   => isset( $contact['Addresses'][0]['Country'] ) ? $contact['Addresses'][0]['Country'] : null,
                            'customer_address_country_sub_division_code' => isset( $contact['Addresses'][0]['Region'] ) ? $contact['Addresses'][0]['Region'] : null,
                            'customer_address_postal_code'               => isset( $contact['Addresses'][0]['PostalCode'] ) ? $contact['Addresses'][0]['PostalCode'] : null,
                        );

                        $wpdb->insert(
                            $table_name,
                            $bill_address_info,
                            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
                        );

                        $customer_bill_address_id = $wpdb->insert_id;
                    }

                    if ( isset( $contact['Addresses'][1]['AddressLine1'] ) ) {
                        $ship_address_info = array(
                            'customer_address_type'                      => '2',
                            'customer_address_line1'                     => $contact['Addresses'][1]['AddressLine1'],
                            'customer_address_line2'                     => isset( $contact['Addresses'][1]['AddressLine2'] ) ? $contact['Addresses'][1]['AddressLine2'] : null,
                            'customer_address_line3'                     => isset( $contact['Addresses'][1]['AddressLine3'] ) ? $contact['Addresses'][1]['AddressLine3'] : null,
                            'customer_address_city'                      => isset( $contact['Addresses'][1]['City'] ) ? $contact['Addresses'][1]['City'] : '',
                            'customer_address_country'                   => isset( $contact['Addresses'][1]['Country'] ) ? $contact['Addresses'][1]['Country'] : null,
                            'customer_address_country_sub_division_code' => isset( $contact['Addresses'][1]['Region'] ) ? $contact['Addresses'][1]['Region'] : null,
                            'customer_address_postal_code'               => isset( $contact['Addresses'][1]['PostalCode'] ) ? $contact['Addresses'][1]['PostalCode'] : null,
                        );

                        $wpdb->insert(
                            $table_name,
                            $ship_address_info,
                            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
                        );

                        $customer_ship_address_id = $wpdb->insert_id;
                    }
                    /* End Insert billing and shipping addresses */

                    $table_name = $wpdb->prefix . 'quote_calculator_customers';

                    $first_name   = $contact['FirstName'] ? $contact['FirstName'] : '';
                    $last_name    = $contact['LastName'] ? $contact['LastName'] : '';
                    $company_name = $contact['Name'] ? $contact['Name'] : '';

                    $customer_info = array(
                        'customers_given_name'   => $first_name,
                        'customers_family_name'  => $last_name,
                        'customers_company_name' => $company_name,
                        'customers_display_name' => $company_name ? $company_name : trim( $first_name . $last_name ),
                        'customers_xero_id'      => $contact['ContactID'],
                        'customers_phone'        => $phone ? $phone : '',
                        'customers_email'        => $contact['EmailAddress'] ? $contact['EmailAddress'] : '',
                        'customers_bill_addr'    => $customer_bill_address_id,
                        'customers_ship_addr'    => $customer_ship_address_id,
                    );

                    $wpdb->insert(
                        $table_name,
                        $customer_info,
                        array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' )
                    );
                }
			}
		} else { // If all contacts were loaded
			$quote_calculator_options['xero_last_import'] = time();
			$quote_calculator_options['xero_import_from'] = $organization[0]['Name'];
			unset( $quote_calculator_options['xero_start_position'] );
			update_option( 'quote_calculator_options', $quote_calculator_options );

			wp_clear_scheduled_hook( 'qc_xero_import_customers_hook' );
        }
    }
}