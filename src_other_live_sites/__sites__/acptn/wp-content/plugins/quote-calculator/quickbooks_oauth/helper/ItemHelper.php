<?php

//require_once('AccountHelper.php'); 
use QuickBooksOnline\API\Facades\Item;

class ItemHelper {
    static function getItemFields( $dataService ) {
			global $quote_calculator_estimate_section;
			if( 2 == $quote_calculator_estimate_section['estimate_section_category'] && '' != $quote_calculator_estimate_section['estimate_section_suplier'] ){
				$custom_field = array();
				$suppliers = @unserialize( $quote_calculator_estimate_section['estimate_section_suplier'] );
				if( ! empty( $suppliers ) ){
					$custom_field = array(
						array(
							'DefinitionId'	=> '5',
							'Name'					=> 'Suppliers',
							'Type'					=> 'StringType',
							'StringValue'		=> implode( ', ', $suppliers )
						)
					);
				} else {
					$custom_field = array(
						array(
							'DefinitionId'	=> '4',
							'Name'					=> 'Supplier',
							'Type'					=> 'StringType',
							'StringValue'		=> $quote_calculator_estimate_section['estimate_section_suplier']
						)
					);
				}
				$item = Item::create( array(
					'Name'					=> $quote_calculator_estimate_section['estimate_section_single_name'],
					'Description'		=> $quote_calculator_estimate_section['estimate_section_single_description'],
					'ParentRef'			=> $quote_calculator_estimate_section['estimate_section_single_parent'],
					'Active'				=> true,
					'Taxable'				=> false,
					'UnitPrice'			=> $quote_calculator_estimate_section['estimate_section_single_cost'],
					'Type'					=> 'Service',
					'IncomeAccountRef'	=> array(
						'value'	=> 1,
						'name'	=> 'Sales'
					),
					'SubItem'				=> true,
					'UnitPrice'			=> $quote_calculator_estimate_section['estimate_section_single_cost'],
					'TrackQtyOnHand'	=> false,
					'InvStartDate'		=> new \DateTime('NOW'),
					'CustomField'			=> $custom_field
				) );
			} else {
				$item = Item::create( array(
					'Name'					=> $quote_calculator_estimate_section['estimate_section_single_name'],
					'Description'		=> $quote_calculator_estimate_section['estimate_section_single_description'],
					'ParentRef'			=> $quote_calculator_estimate_section['estimate_section_single_parent'],
					'Active'				=> true,
					'Taxable'				=> false,
					'UnitPrice'			=> $quote_calculator_estimate_section['estimate_section_single_cost'],
					'Type'					=> 'Service',
					'IncomeAccountRef'	=> array(
						'value'	=> 1,
						'name'	=> 'Sales'
					),
					'SubItem'				=> true,
					'UnitPrice'			=> $quote_calculator_estimate_section['estimate_section_single_cost'],
					'TrackQtyOnHand'	=> false,
					'InvStartDate'		=> new \DateTime('NOW')
				) );
			}
			
			return $item;
    }

    static function createItem( $dataService ) {
			$item = $dataService->Add( ItemHelper::getItemFields( $dataService ) );
			quote_calculator_add_item( $item );
      return $item;
    }

		static function UpdateItem( $dataService, $item ) {
        return $dataService->Update( $item );
    }

    static function getItem( $dataService ) {
		global $quote_calculator_estimate_section;
		//$allItems = $dataService->FindAll( 'Item', 0, 500 );
		$items = $dataService->Query( "select * from Item where Name = '" . $quote_calculator_estimate_section['estimate_section_single_name'] . "' AND ParentRef = '" . $quote_calculator_estimate_section['estimate_section_single_parent'] . "'" );
		if ( ! $items || ( 0 == count( $items ) ) ) {
			$current_item = ItemHelper::createItem( $dataService );
			quote_calculator_add_item( $current_item );
		} else {
			$current_item;
			foreach( $items as $item ){
				$current_item = $item;
				quote_calculator_update_item( $current_item );
				$current_item->Description = $quote_calculator_estimate_section['estimate_section_single_description'];
				$current_item->UnitPrice = $quote_calculator_estimate_section['estimate_section_single_cost'];
			}
		}
		if( empty( $current_item ) ){
			$current_item = ItemHelper::createItem( $dataService );
			quote_calculator_add_item( $current_item );
		}
		return $current_item;
    }

	static function getItemByID( $dataService, $item_id ) {
		$current_item = $dataService->FindById( 'item', $item_id );
		return $current_item;
	}    
}
?>