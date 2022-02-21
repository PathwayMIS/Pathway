<?php

require_once('AccountHelper.php'); 
require_once('CustomerHelper.php'); 
require_once('ItemHelper.php'); 

use QuickBooksOnline\API\Facades\Estimate;

class EstimateHelper {
    static function getEstimateFields( $dataService ) {
			global $quote_calculator_estimate, $quote_calculator_estimate_all_sections, $quote_calculator_estimate_all_sections_qty, $quote_calculator_estimate_all_sections_tax, $quote_calculator_estimate_all_sections_tax_code;
			date_default_timezone_set('UTC');
			$all_line = $all_tax_line = array();
			foreach( $quote_calculator_estimate_all_sections as $key => $section ){
				$line1 = array(
					'LineNum'								=> $key + 1,
					'Amount'								=> $section->UnitPrice,
					'DetailType'						=> 'SalesItemLineDetail',
					'Description'						=> $section->Description,
					'SalesItemLineDetail'		=> array(
						'Qty'										=> ( isset( $quote_calculator_estimate_all_sections_qty[ $key ] ) ? $quote_calculator_estimate_all_sections_qty[ $key ] : 1 ),
						'UnitPrice'							=> $section->UnitPrice / ( isset( $quote_calculator_estimate_all_sections_qty[ $key ] ) && $quote_calculator_estimate_all_sections_qty[ $key ] > 0 ? $quote_calculator_estimate_all_sections_qty[ $key ] : 1 ),
						'ItemRef'								=> $section->Id,
						'TaxCodeRef'						=> array(
							'value'								=> ( isset( $quote_calculator_estimate_all_sections_tax_code[ $key ] ) ? $quote_calculator_estimate_all_sections_tax_code[ $key ] : 3 )
						)
					)
				);
				/*$tax_line = array(
					'Amount'								=> ( isset( $quote_calculator_estimate_all_sections_tax[ $key ] ) ? $quote_calculator_estimate_all_sections_tax[ $key ] : 0 ),
					'DetailType'						=> 'TaxLineDetail',
					'TaxLineDetail'					=> array(
						'TaxRateRef'		=> array( 'value' => ( isset( $quote_calculator_estimate_all_sections_tax[ $key ] ) && $quote_calculator_estimate_all_sections_tax[ $key ] > 0 ? 23 : 39 ) ),
						'PercentBased'	=> true,
						'TaxPercent'		=> 20,
						'NetAmountTaxable'	=> $section->UnitPrice
					)
				);
				$all_tax_line[] = $tax_line;*/
				$all_line[] = $line1;
			}

			$customer	= CustomerHelper::getCustomer( $dataService );
			$estimate = Estimate::create( array( 
				'DocNumber'				=> 'QC' . $quote_calculator_estimate['estimates_id'],
				'TxnDate'				=> date( 'Y-m-d', time() ),
				'ExpirationDate'		=> date( 'Y-m-d', time() + 15 * ( 24 * 60 * 60 ) ),
				'Line'					=> $all_line,
				'CustomerRef'			=> $customer->Id,
				'BillAddr'				=> $customer->BillAddr,
				'ShipAddr'				=> $customer->ShipAddr,
				'ApplyTaxAfterDiscount'	=> 'false',
				//'TxnStatus'				=> 'Accepted',
				'TxnTaxDetail'		=> array(
					'TxnTaxCodeRef'		=> array(
						'value'								=> '3'
					),
					/*'TotalTax'				=> $quote_calculator_estimate['estimates_cost_incl_vat'] - $quote_calculator_estimate['estimates_cost'],
					'TaxLine'					=> array(
						'Amount'				=> $quote_calculator_estimate['estimates_cost_incl_vat'] - $quote_calculator_estimate['estimates_cost'],
						'DetailType'			=> 'TaxLineDetail',
						'TaxLineDetail'			=> array(
							'TaxRateRef'		=> array( 'value' => 3 ),
							'PercentBased'	=> true,
							'TaxPercent'		=> 20,
							'NetAmountTaxable'	=> $quote_calculator_estimate['estimates_cost']
						)
					)*/ //$all_tax_line
				),
				'TotalAmt'				=> $quote_calculator_estimate['estimates_cost_incl_vat'],
				'PrivateNote'			=> $quote_calculator_estimate['estimates_memo'],
				'CustomField'			=> array(
					array(
						'DefinitionId'	=> '1',
						'Name'					=> 'Deadline',
						'Type'					=> 'StringType',
						'StringValue'		=> date( 'd/m/Y', $quote_calculator_estimate['estimates_deadline'] )
					),
					array(
						'DefinitionId'	=> '2',
						'Name'					=> 'Notes',
						'Type'					=> 'StringType',
						'StringValue'		=> substr( $quote_calculator_estimate['estimates_notes'], 0, 31 )
					),
					array(
						'DefinitionId'	=> '3',
						'Name'					=> 'Terms',
						'Type'					=> 'StringType',
						'StringValue'		=> substr( $quote_calculator_estimate['estimates_terms'], 0, 31 )
					)
				)
			) );
      return $estimate;
    }   
}
?>