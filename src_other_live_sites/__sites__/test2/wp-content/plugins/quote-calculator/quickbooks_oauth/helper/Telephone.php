<?php
class Telephone {
    static function getPrimaryPhone() {
				global $quote_calculator_estimate_customer;
        $primaryNum = new IPPTelephoneNumber();
				$primaryNum->FreeFormNumber = $quote_calculator_estimate_customer['customers_phone'];
				$primaryNum->Default = 'true';
				$primaryNum->Tag = 'Business';
        return $primaryNum;
    }

    static function getAlternatePhone() {
        $primaryNum = new IPPTelephoneNumber();
				$primaryNum->FreeFormNumber = "(650)111-2222";
				$primaryNum->Default = 'false';
				$primaryNum->Tag = "Business";
        return $primaryNum;
    }

    static function getMobilePhone() {
        $primaryNum = new IPPTelephoneNumber();
				$primaryNum->FreeFormNumber = "(650)111-3333";
				$primaryNum->Default = 'false';
				$primaryNum->Tag = "Home";
        return $primaryNum;
    }

    static function getFax() {
        $primaryNum = new IPPTelephoneNumber();
				$primaryNum->FreeFormNumber = "(650)111-1111";
				$primaryNum->Default = 'false';
				$primaryNum->Tag = "Business";
        return $primaryNum;
    }
    
}
?>