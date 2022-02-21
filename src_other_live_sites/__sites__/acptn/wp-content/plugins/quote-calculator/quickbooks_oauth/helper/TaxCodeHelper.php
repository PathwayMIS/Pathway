<?php
class TaxCodeHelper {
    static function getTaxCode( $dataService) {
        $allTaxCodes = $dataService->FindAll('TaxCode', 0, 2);
        return $allTaxCodes[0];
    }
    
}
?>