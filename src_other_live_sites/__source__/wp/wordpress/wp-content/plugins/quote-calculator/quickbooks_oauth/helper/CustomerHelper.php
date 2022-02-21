<?php
require_once('Address.php'); 

use QuickBooksOnline\API\Facades\Customer;

class CustomerHelper {
	static function getCustomerFields() {
		global $quote_calculator_estimate_customer;
		$customerObj = Customer::create( array(
			'BillAddr' => Address::getPhysicalBillAddress(),
			'ShipAddr' => Address::getPhysicalShipAddress(),
			//'Notes' =>  'Here are other details.',
			//'Title'=>  'Mr',
			'GivenName'=>  $quote_calculator_estimate_customer['customers_given_name'],
			//'MiddleName'=>  '1B',
			'FamilyName'=>  $quote_calculator_estimate_customer['customers_family_name'],
			//'Suffix'=>  'Jr',
			//'FullyQualifiedName'=>  'Evil King',
			'CompanyName'=>  $quote_calculator_estimate_customer['customers_company_name'],
			'DisplayName'=>  $quote_calculator_estimate_customer['customers_display_name'],
			'PrimaryPhone'=> array(
				'FreeFormNumber'=>   $quote_calculator_estimate_customer['customers_phone']
			),
			'PrimaryEmailAddr'=> array(
				'Address' => $quote_calculator_estimate_customer['customers_email']
			)
		) );

		return $customerObj;
	}

	static function createCustomer( $dataService) {
		return $dataService->Add( CustomerHelper::getCustomerFields() );
	}

	static function getCustomer( $dataService ) {
		global $quote_calculator_estimate_customer;
		$where = '';
		if( ! empty( $quote_calculator_estimate_customer['customers_given_name'] ) ){
			$where .= "GivenName LIKE '" . addslashes( $quote_calculator_estimate_customer['customers_given_name'] ) . "'";
		}
		if( ! empty( $quote_calculator_estimate_customer['customers_family_name'] ) ){
			if( '' != $where ){
				$where .= ' AND ';
			}
			$where .= "FamilyName LIKE '" . addslashes( $quote_calculator_estimate_customer['customers_family_name'] ) . "'";
		}
		if( ! empty( $quote_calculator_estimate_customer['customers_display_name'] ) ){
			if( '' != $where ){
				$where .= ' AND ';
			}
			$where .= "DisplayName LIKE '" . addslashes( $quote_calculator_estimate_customer['customers_display_name'] ) . "'";
		}
		$current_customer = $dataService->Query( "select * from Customer where " . $where );
		if( ! empty( $current_customer ) && sizeof( $current_customer ) == 1 ){
			$current_customer = current( $current_customer );
		} else {
			$current_customer = CustomerHelper::createCustomer( $dataService );
			//error_log(print_r($current_customer, true) . PHP_EOL, 3, dirname(__FILE__) . '/error.log');
			//error_log(print_r($quote_calculator_estimate_customer['customers_id'], true) . PHP_EOL, 3, dirname(__FILE__) . '/error.log');
			global $wpdb;
			$table_name = $wpdb->prefix . 'quote_calculator_customers';
			$wpdb->update( 
				$table_name,
				array(
					'customers_qb_id'		=> $current_customer->Id
				),
				array( 'customers_id' => $quote_calculator_estimate_customer['customers_id'] ),
				array(
					'%d'
				),
				array( '%d' )
			);
		}
		return $current_customer;
	}
	
}
?>