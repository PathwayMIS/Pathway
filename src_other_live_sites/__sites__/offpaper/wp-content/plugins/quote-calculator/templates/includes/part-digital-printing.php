<?php global $current_section_count, $quote_calculator_options; ?>
<div class="quote-calculator-section digital-printing">
	<div class="quote-calculator-section-title">
		<h3><?php _e( 'Digital Printing', 'quote_calculator' ); ?></h3>
	</div>
	<div class="quote-calculator-section-content">
		<div class="quote-calculator-subsection-title">
			<input name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_title]" value="<?php echo $current_estimate_section['estimate_section_title']; ?>" placeholder="Section Title" type="text" />
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Product & Quantity', 'quote_calculator' ); ?></p>
			</div>
			<div class="quote-calculator-type select-wrapper">
				<?php $product_type_array = quote_calculator_get_product_type( 1 ); ?>
				<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_type]">
					<option value=""><?php _e( 'Item Type', 'quote_calculator' ); ?></option>
					<?php reset( $product_type_array );
					foreach( $product_type_array as $key => $product_type ) { ?>
						<option <?php echo isset( $current_estimate_section ) && $product_type['product_type_title'] == $current_estimate_section['estimate_section_type'] ? 'selected="selected"' : ''; ?>><?php echo $product_type['product_type_title']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="quote-calculator-size select-wrapper">
				<?php $paper_size_array = quote_calculator_get_paper_size( 1 ); ?>
				<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_size]">
					<option value=""><?php _e( 'Size', 'quote_calculator' ); ?></option>
					<?php reset( $paper_size_array );
					foreach( $paper_size_array as $key => $paper_size ) { ?>
						<option data-calculation="<?php echo $paper_size['paper_size_calculation']; ?>" <?php echo isset( $current_estimate_section ) && $paper_size['paper_size_title'] == $current_estimate_section['estimate_section_size'] ? 'selected="selected"' : ''; ?>><?php echo $paper_size['paper_size_title']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="quote-calculator-sided select-wrapper">
				<?php $impressions_array = quote_calculator_get_impressions( 1 ); ?>
				<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_sided]">
					<option value=""><?php _e( 'Sided', 'quote_calculator' ); ?></option>
					<?php foreach( $impressions_array as $key => $impressions ) { ?>
						<option data-calculation="<?php echo $impressions['impressions_calculation']; ?>" <?php echo isset( $current_estimate_section ) && $impressions['impressions_title'] == $current_estimate_section['estimate_section_sided'] ? 'selected="selected"' : ''; ?>><?php echo $impressions['impressions_title']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="quote-calculator-subsection">
			<?php if( isset( $current_estimate_section ) ) {  
				$current_estimate_section['estimate_section_qty'] = @unserialize( $current_estimate_section['estimate_section_qty'] ) === false ? array( $current_estimate_section['estimate_section_qty'] ) : unserialize( $current_estimate_section['estimate_section_qty'] );
				$quantity_array = quote_calculator_get_quantity( 1 );
				foreach( $current_estimate_section['estimate_section_qty'] as $key => $estimate_section_qty ) { ?>
					<div class="quote-calculator-copy qty">
						<div class="quote-calculator-qty select-wrapper">
							<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_qty][]" data-qty="<?php echo $key; ?>">
								<option value=""><?php _e( 'Qty', 'quote_calculator' ); ?></option>
								<?php reset( $quantity_array );
								foreach( $quantity_array as $key => $quantity ) { ?>
									<option <?php echo isset( $current_estimate_section ) && $quantity['quantity_count'] == $estimate_section_qty ? 'selected="selected"' : ''; ?>><?php echo $quantity['quantity_count']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="quote-calculator-remove remove-qty"><span class="dashicons dashicons-no"></span></div>
					</div>
				<?php }
			} else { ?>
				<div class="quote-calculator-copy qty">
					<div class="quote-calculator-qty select-wrapper">
						<?php $quantity_array = quote_calculator_get_quantity( 1 ) ?>
						<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_qty][]" data-qty="0" >
							<option value=""><?php _e( 'Qty', 'quote_calculator' ); ?></option>
							<?php reset( $quantity_array );
							foreach( $quantity_array as $key => $quantity ) { ?>
								<option><?php echo $quantity['quantity_count']; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="quote-calculator-remove remove-qty"><span class="dashicons dashicons-no"></span></div>
				</div>
			<?php } ?>
			<div class="add-qty">
				<span><?php _e( 'Add qty', 'quote_calculator' ); ?></span> <span class="dashicons dashicons-plus"></span>
			</div>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Printing', 'quote_calculator' ); ?></p>
			</div>
			<?php $colour_array = quote_calculator_get_colour( 1 );
			foreach( $colour_array as $key => $colour ) { ?>
				<label data-for="quote_calculator_colour_1_<?php echo $key; ?>"><input type="radio" data-calculation="<?php echo $colour['colour_price']; ?>" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_color]" value="<?php echo $colour['colour_title']; ?>" id="quote_calculator_colour_1_<?php echo $key; ?>" <?php echo isset( $current_estimate_section ) && $colour['colour_title'] == $current_estimate_section['estimate_section_color'] ? 'checked="checked"' : ''; ?> /><span class="quote-calculator-colour"><?php echo $colour['colour_title']; ?></span></label>
			<?php } ?>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Page Count', 'quote_calculator' ); ?></p>
			</div>
			<div class="quote-calculator-qty select-wrapper">
				<?php $page_count_array = quote_calculator_get_page_count( 1 ); ?>
				<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_page_count]">
					<option value=""><?php _e( 'Page Count', 'quote_calculator' ); ?></option>
					<?php foreach( $page_count_array as $page_count ) { ?>
						<option data-price="<?php echo $page_count['page_count_price']; ?>" <?php echo isset( $current_estimate_section ) && $page_count['page_count_title'] == $current_estimate_section['estimate_section_page_count'] ? 'selected="selected"' : ''; ?>><?php echo $page_count['page_count_title']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Orientation', 'quote_calculator' ); ?></p>
			</div>
			<div class="quote-calculator-qty select-wrapper">
				<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_orientation]">
					<option value=""><?php _e( 'Orientation', 'quote_calculator' ); ?></option>
					<option value="landscape" <?php echo isset( $current_estimate_section ) && 'landscape' == $current_estimate_section['estimate_section_orientation'] ? 'selected="selected"' : ''; ?>>Landscape</option>
					<option value="portrait" <?php echo isset( $current_estimate_section ) && 'portrait' == $current_estimate_section['estimate_section_orientation'] ? 'selected="selected"' : ''; ?>>Portrait</option>
				</select>
			</div>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Paper', 'quote_calculator' ); ?></p>
			</div>
			<?php $paper_array = quote_calculator_get_paper( 1 );
			$paper_name_array = $paper_weigth_array = array(); 			
			foreach( $paper_array as $paper ) {
				if( ! in_array( $paper['paper_title'], $paper_name_array ) ) {
					$paper_name_array[] = $paper['paper_title'];
				}
				if( ! empty( $paper['paper_weight'] ) ){
					if( ! isset( $paper_weigth_array[ $paper['paper_weight'] ] ) ){
						$paper_weigth_array[ $paper['paper_weight'] ] = $paper['paper_title'];
					}
					else {
						if( false === strpos( $paper['paper_title'], $paper_weigth_array[ $paper['paper_weight'] ] ) ) {
							$paper_weigth_array[ $paper['paper_weight'] ] .= ' ' . $paper['paper_title'];
						}
					}
				} ?>
				<input type="hidden" class="paper-calculation" data-type="<?php echo $paper['paper_title']; ?>-<?php echo $paper['paper_weight']; ?>" value="<?php echo $paper['paper_price']; ?>" />
			<?php }	
			echo '<input type="hidden" class="paper-calculation" data-type="-" value="0" />';
			ksort( $paper_weigth_array, SORT_NUMERIC );
			if( ! empty( $current_estimate_section ) ){
				$current_estimate_section['estimate_section_cover']				= unserialize( $current_estimate_section['estimate_section_cover'] );
				$current_estimate_section['estimate_section_paper_type']	= unserialize( $current_estimate_section['estimate_section_paper_type'] );
				$current_estimate_section['estimate_section_weight']			= unserialize( $current_estimate_section['estimate_section_weight'] );
				foreach( $current_estimate_section['estimate_section_cover'] as $section_key => $estimate_section_cover ){ ?>
					<div class="quote-calculator-copy paper">
						<div class="quote-calculator-cover select-wrapper">
							<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_cover][]">
								<option value="Cover" <?php echo isset( $current_estimate_section ) && 'Cover' == $current_estimate_section['estimate_section_cover'][ $section_key ] ? 'selected="selected"' : ''; ?>><?php _e( 'Cover', 'quote_calculator' ); ?></option>
								<option value="Inner" <?php echo isset( $current_estimate_section ) && 'Inner' == $current_estimate_section['estimate_section_cover'][ $section_key ] ? 'selected="selected"' : ''; ?>><?php _e( 'Inner', 'quote_calculator' ); ?></option>
								<option value="Type" <?php echo isset( $current_estimate_section ) && 'Type' == $current_estimate_section['estimate_section_cover'][ $section_key ] ? 'selected="selected"' : ''; ?>><?php _e( 'Type', 'quote_calculator' ); ?></option>
							</select>
						</div>
						<div class="quote-calculator-type select-wrapper">
							<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_paper_type][]">
								<option value=""><?php _e( 'Type', 'quote_calculator' ); ?></option>
								<?php foreach( $paper_name_array as $paper ) { ?>
									<option <?php echo isset( $current_estimate_section ) && $paper == $current_estimate_section['estimate_section_paper_type'][ $section_key ] ? 'selected="selected"' : ''; ?>><?php echo $paper; ?></option>
								<?php	} ?>
							</select>
						</div>
						<div class="quote-calculator-weight select-wrapper">
							<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_weight][]">
								<option value=""><?php _e( 'Weight', 'quote_calculator' ); ?></option>
								<?php foreach( $paper_weigth_array as $paper_weight => $paper_type ) { ?>
									<option <?php echo isset( $current_estimate_section ) && $paper_weight == $current_estimate_section['estimate_section_weight'][ $section_key ] ? 'selected="selected"' : ''; ?> class="<?php echo $paper_type; ?>"><?php echo $paper_weight; ?></option>
								<?php	} ?>
							</select>
						</div>
						<div class="quote-calculator-remove"><span class="dashicons dashicons-no"></span></div>
					</div>
				<?php } 
			} else { ?>
				<div class="quote-calculator-copy paper">
					<div class="quote-calculator-cover select-wrapper">
						<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_cover][]">
							<option value=""><?php _e( 'N/A', 'quote_calculator' ); ?></option>
							<option value="Cover"><?php _e( 'Cover', 'quote_calculator' ); ?></option>
							<option value="Inner"><?php _e( 'Inner', 'quote_calculator' ); ?></option>
						</select>
					</div>
					<div class="quote-calculator-type select-wrapper">
						<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_paper_type][]">
							<option value=""><?php _e( 'Type', 'quote_calculator' ); ?></option>
							<?php foreach( $paper_name_array as $paper ) { ?>
								<option><?php echo $paper; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="quote-calculator-weight select-wrapper">
						<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_weight][]">
							<option value=""><?php _e( 'Weight', 'quote_calculator' ); ?></option>
							<?php foreach( $paper_weigth_array as $paper_weight => $paper_type ) { ?>
								<option class="<?php echo $paper_type; ?>"><?php echo $paper_weight; ?></option>
							<?php	} ?>
						</select>
					</div>
					<div class="quote-calculator-remove"><span class="dashicons dashicons-no"></span></div>
				</div>
			<?php } ?>
			<div>
				<div class="add-paper-type">
					<span><?php _e( 'Add paper type', 'quote_calculator' ); ?></span> <span class="dashicons dashicons-plus"></span>
				</div>
			</div>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Finishing', 'quote_calculator' ); ?></p>
			</div>
			<?php $finishing_array = quote_calculator_get_finishing( 1 );
			foreach( $finishing_array as $finishing ) { 
				if( 0 <= $finishing['finishing_price'] ) { ?>
					<input type="hidden" class="finishing-calculation" data-type="<?php echo $finishing['finishing_title']; ?>" value="<?php echo $finishing['finishing_price']; ?>" />
				<?php }
			}
			echo '<input type="hidden" class="finishing-calculation" data-type="" value="0" />';
			if( ! empty( $current_estimate_section ) ){	
				$current_estimate_section['estimate_section_finishing'] = unserialize( $current_estimate_section['estimate_section_finishing'] );
				foreach( $current_estimate_section['estimate_section_finishing'] as $section_key => $estimate_section_finishing ){ ?>
					<div class="quote-calculator-copy finishing">
						<div class="quote-calculator-finishing-type select-wrapper">
							<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_finishing][]">
								<option value=""><?php _e( 'Finishing type', 'quote_calculator' ); ?></option>
								<?php foreach( $finishing_array as $finishing ) { 
									if( 0 <= $finishing['finishing_price'] ) { ?>
										<option <?php echo isset( $current_estimate_section ) && $finishing['finishing_title'] == $current_estimate_section['estimate_section_finishing'][ $section_key ] ? 'selected="selected"' : ''; ?>><?php echo $finishing['finishing_title']; ?></option>
									<?php } 
								} ?>
							</select>
						</div>
						<div class="quote-calculator-remove"><span class="dashicons dashicons-no"></span></div>
					</div>
				<?php } 
			} else { ?>
				<div class="quote-calculator-copy finishing">
					<div class="quote-calculator-finishing-type select-wrapper">
						<select name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_finishing][]">
							<option value=""><?php _e( 'Finishing type', 'quote_calculator' ); ?></option>
							<?php foreach( $finishing_array as $finishing ) {
								if( 0 <= $finishing['finishing_price'] ) { ?>
									<option><?php echo $finishing['finishing_title']; ?></option>
								<?php } 
							} ?>
						</select>
					</div>
					<div class="quote-calculator-remove"><span class="dashicons dashicons-no"></span></div>
				</div>
			<?php } ?>
			<div class="add-finishing">
				<span><?php _e( 'Add finishing', 'quote_calculator' ); ?></span> <span class="dashicons dashicons-plus"></span>
			</div>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Mark Up & Rates', 'quote_calculator' ); ?></p>
			</div>
			<label>
				<span><?php _e( 'Overhead %', 'quote_calculator' ); ?></span><input type="number" min="0" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_overhead]" value="<?php echo isset( $current_estimate_section['estimate_section_overhead'] ) ? $current_estimate_section['estimate_section_overhead'] : $quote_calculator_options['overhead']; ?>" />
			</label>
			<label>
				<span><?php _e( 'Profit Margin %', 'quote_calculator' ); ?></span><input type="number" min="0" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_profit]" value="<?php echo isset( $current_estimate_section['estimate_section_profit'] ) ? $current_estimate_section['estimate_section_profit'] : $quote_calculator_options['profit']; ?>" />
			</label>
			<label>
				<span><?php _e( 'VAT %', 'quote_calculator' ); ?></span><input type="number" min="0" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_vat]" value="<?php echo isset( $current_estimate_section['estimate_section_vat'] ) ? $current_estimate_section['estimate_section_vat'] : $quote_calculator_options['vat']; ?>" />
			</label>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Product Cost', 'quote_calculator' ); ?></p>
			</div>
			<?php if( isset( $current_estimate_section ) ){
				$current_estimate_section['estimate_section_cost'] = @unserialize( $current_estimate_section['estimate_section_cost'] ) === false ? array( $current_estimate_section['estimate_section_cost'] ) : unserialize( $current_estimate_section['estimate_section_cost'] );
				$current_estimate_section['estimate_section_cost_without_vat'] = @unserialize( $current_estimate_section['estimate_section_cost_without_vat'] ) === false ? array( $current_estimate_section['estimate_section_cost_without_vat'] ) : unserialize( $current_estimate_section['estimate_section_cost_without_vat'] );
				foreach( $current_estimate_section['estimate_section_cost'] as $key => $estimate_section_cost ){ ?>
					<div class="quote-calculator-copy display-cost" data-qty="<?php echo $key; ?>">
						<span <?php if( count( $current_estimate_section['estimate_section_cost'] ) == 1 ) echo 'class="hidden"'; ?>><?php echo $current_estimate_section['estimate_section_qty'][ $key ]; ?> - </span><span class="cost"><span class="cost_without_vat"><?php echo '£' . number_format( floatval( $current_estimate_section['estimate_section_cost_without_vat'][ $key ] ), 2, '.', '' ) . '</span> <span class="cost_with_vat">( £' . number_format( floatval( $estimate_section_cost ), 2, '.', '' ) . ' incl VAT )</span>'; ?></span>
						<input type="hidden" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_cost][]" value="<?php echo $estimate_section_cost; ?>" />
						<input type="hidden" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_cost_without_vat][]" value="<?php echo $current_estimate_section['estimate_section_cost_without_vat'][ $key ]; ?>" />
					</div>
				<?php }
			} else { ?>
				<div class="quote-calculator-copy display-cost" data-qty="0">
					<span class="hidden"></span><span class="cost"><span class="cost_without_vat"><?php echo '£0.00'; ?></span> <span class="cost_with_vat"></span></span>
					<input type="hidden" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_cost][]" value="<?php echo '0'; ?>" />
					<input type="hidden" name="quote_calculator_category[1][<?php echo $current_section_count; ?>][estimate_section_cost_without_vat][]" value="<?php echo '0'; ?>" />
				</div>
			<?php } ?>
		</div>
		<div class="quote-calculator-subsection">
			<span class="button default quote-calculator-duplicate-section-button"><?php _e( 'Duplicate Item', 'quote_calculator' ); ?></span> <span class="button default quote-calculator-select-section-button"><?php _e( 'Add New Item', 'quote_calculator' ); ?></span> <span class="button delete quote-calculator-delete-section-button"><?php _e( 'Delete Item', 'quote_calculator' ); ?></span>
		</div>
	</div>
</div>