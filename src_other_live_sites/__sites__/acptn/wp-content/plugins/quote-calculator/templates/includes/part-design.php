<?php global $current_section_count, $quote_calculator_options; ?>
<div class="quote-calculator-section design">
	<div class="quote-calculator-section-title">
		<h3><?php _e( 'Design', 'quote_calculator' ); ?></h3>
	</div>
	<div class="quote-calculator-section-content">
		<div class="quote-calculator-subsection_title">
			<input name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_title]" value="<?php echo $current_estimate_section['estimate_section_title']; ?>" placeholder="Section Title" type="text" />
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Product & Quantity', 'quote_calculator' ); ?></p>
			</div>
			<div class="quote-calculator-item-type select-wrapper">
				<?php $product_type_array = quote_calculator_get_product_type( 3 ); ?>
				<select name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_type]">
					<option value=""><?php _e( 'Item Type', 'quote_calculator' ); ?></option>
					<?php foreach( $product_type_array as $key => $product_type ) { ?>
						<option <?php echo isset( $current_estimate_section ) && $product_type['product_type_title'] == $current_estimate_section['estimate_section_type'] ? 'selected="selected"' : ''; ?>><?php echo $product_type['product_type_title']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="quote-calculator-size select-wrapper">
				<?php $paper_size_array = quote_calculator_get_paper_size( 3 ); ?>
				<select name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_size]">
					<option value=""><?php _e( 'Size', 'quote_calculator' ); ?></option>
					<?php foreach( $paper_size_array as $key => $paper_size ) { ?>
						<option <?php echo isset( $current_estimate_section ) && $paper_size['paper_size_title'] == $current_estimate_section['estimate_section_size'] ? 'selected="selected"' : ''; ?>><?php echo $paper_size['paper_size_title']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="quote-calculator-sided select-wrapper">
				<?php $impressions_array = quote_calculator_get_impressions( 3 ); ?>
				<select name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_sided]">
					<option value=""><?php _e( 'Sided', 'quote_calculator' ); ?></option>
					<?php foreach( $impressions_array as $key => $impressions ) { ?>
						<option <?php echo isset( $current_estimate_section ) && trim( $impressions['impressions_title'] ) == $current_estimate_section['estimate_section_sided'] ? 'selected="selected"' : ''; ?>><?php echo trim( $impressions['impressions_title'] ); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Time Spent', 'quote_calculator' ); ?></p>
			</div>
			<label>
				<span><?php _e( 'Hourly Rate £', 'quote_calculator' ); ?></span><input type="number" min="0" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_rate]" value="<?php echo isset( $current_estimate_section['estimate_section_rate'] ) ? $current_estimate_section['estimate_section_rate'] : $quote_calculator_options['design_hourly_rate']; ?>" />
			</label>
			<label>
				<span><?php _e( 'No. of Hours', 'quote_calculator' ); ?></span><input type="number" min="0" step="0.5" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_hours]" value="<?php echo isset( $current_estimate_section['estimate_section_hours'] ) ? $current_estimate_section['estimate_section_hours'] : ''; ?>" />
			</label>
			<label>
				<span><?php _e( 'Total', 'quote_calculator' ); ?></span><input type="number" min="0" step="0.5" class="quote_calculator_estimate_section_total" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_total]" value="<?php echo isset( $current_estimate_section['estimate_section_total'] ) ? $current_estimate_section['estimate_section_total'] : ''; ?>" />
			</label>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Mark Up & Rates', 'quote_calculator' ); ?></p>
			</div>
			<label>
				<span><?php _e( 'Overhead %', 'quote_calculator' ); ?></span><input type="number" min="0" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_overhead]" value="<?php echo isset( $current_estimate_section['estimate_section_overhead'] ) ? $current_estimate_section['estimate_section_overhead'] : $quote_calculator_options['overhead']; ?>" />
			</label>
			<label>
				<span><?php _e( 'Profit Margin %', 'quote_calculator' ); ?></span><input type="number" min="0" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_profit]" value="<?php echo isset( $current_estimate_section['estimate_section_profit'] ) ? $current_estimate_section['estimate_section_profit'] : $quote_calculator_options['profit']; ?>" />
			</label>
			<label>
				<span><?php _e( 'VAT %', 'quote_calculator' ); ?></span><input type="number" min="0" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_vat]" value="<?php echo isset( $current_estimate_section['estimate_section_vat'] ) ? $current_estimate_section['estimate_section_vat'] : $quote_calculator_options['vat']; ?>" />
			</label>
		</div>
		<div class="quote-calculator-subsection">
			<div class="quote-calculator-subsection-title">
				<p><?php _e( 'Product Cost', 'quote_calculator' ); ?></p>
			</div>
			<span class="cost"><span class="cost_without_vat"><?php echo ! empty( $current_estimate_section['estimate_section_cost'] ) ? '£' . number_format( floatval( $current_estimate_section['estimate_section_cost_without_vat'] ), 2, '.', '' ) . '</span> <span class="cost_with_vat">( £' . number_format( floatval( $current_estimate_section['estimate_section_cost'] ), 2, '.', '' ) . ' incl VAT )</span>' : '£0.00</span> <span class="cost_with_vat">'; ?></span>
			<input type="hidden" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_cost]" value="<?php echo ! empty( $current_estimate_section['estimate_section_cost'] ) ? $current_estimate_section['estimate_section_cost'] : '0'; ?>" />
			<input type="hidden" name="quote_calculator_category[3][<?php echo $current_section_count; ?>][estimate_section_cost_without_vat]" value="<?php echo ! empty( $current_estimate_section['estimate_section_cost_without_vat'] ) ? $current_estimate_section['estimate_section_cost_without_vat'] : '0'; ?>" />
		</div>
		<div class="quote-calculator-subsection">
			<span class="button default quote-calculator-duplicate-section-button"><?php _e( 'Duplicate Item', 'quote_calculator' ); ?></span> <span class="button default quote-calculator-select-section-button"><?php _e( 'Add New Item', 'quote_calculator' ); ?></span> <span class="button delete quote-calculator-delete-section-button"><?php _e( 'Delete Item', 'quote_calculator' ); ?></span>
		</div>
	</div>
</div>
					