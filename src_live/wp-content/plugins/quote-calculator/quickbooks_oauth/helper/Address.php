<?php

class Address {
    static function getPhysicalBillAddress() {
				global $quote_calculator_estimate_customer;
				$address = array(
					 'Line1'									=>  $quote_calculator_estimate_customer['bill_addr']['customer_address_line1'],
					 'Line2'									=>  $quote_calculator_estimate_customer['bill_addr']['customer_address_line2'],
					 'Line3'									=>  $quote_calculator_estimate_customer['bill_addr']['customer_address_line3'],
					 'City'										=>  $quote_calculator_estimate_customer['bill_addr']['customer_address_city'],
					 'Country'								=>  $quote_calculator_estimate_customer['bill_addr']['customer_address_country'],
					 'CountrySubDivisionCode'	=>  $quote_calculator_estimate_customer['bill_addr']['customer_address_country_sub_division_code'],
					 'PostalCode'							=>  $quote_calculator_estimate_customer['bill_addr']['customer_address_postal_code']
				);
        return $address;
    }

		static function getPhysicalShipAddress() {
				global $quote_calculator_estimate_customer;
				$address = array(
					 'Line1'									=>  $quote_calculator_estimate_customer['ship_addr']['customer_address_line1'],
					 'Line2'									=>  $quote_calculator_estimate_customer['ship_addr']['customer_address_line2'],
					 'Line3'									=>  $quote_calculator_estimate_customer['ship_addr']['customer_address_line3'],
					 'City'										=>  $quote_calculator_estimate_customer['ship_addr']['customer_address_city'],
					 'Country'								=>  $quote_calculator_estimate_customer['ship_addr']['customer_address_country'],
					 'CountrySubDivisionCode'	=>  $quote_calculator_estimate_customer['ship_addr']['customer_address_country_sub_division_code'],
					 'PostalCode'							=>  $quote_calculator_estimate_customer['ship_addr']['customer_address_postal_code']
				);
        return $address;
    }

    static function getWebSiteAddress() {
				/*global $quote_calculator_estimate_customer;
        $address = new IPPWebSiteAddress();
				$address->URI = "http://abccorp.com";
				$address->Default = 'true';
				$address->Tag  = "Business";
        return $address;*/
    }
    
}
?>