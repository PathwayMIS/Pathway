<?php
class Email {
    static function getEmailAddress() {
				global $quote_calculator_estimate_customer;
        $emailAddr = new IPPEmailAddress();
				$emailAddr->Address = $quote_calculator_estimate_customer['customers_email'];
        return $emailAddr;
    }
    
}
?>