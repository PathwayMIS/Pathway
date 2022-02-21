<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<?php wp_head();
	$qc_dashboard_options = get_option( 'qc_dashboard_options' );
	if ( ! empty( $qc_dashboard_options ) && ( '#9d3292' !== $qc_dashboard_options['dashboard_details']['titles_color'] || '#9d3292' !== $qc_dashboard_options['dashboard_details']['buttons_color'] ) ) { ?>
        <style>
            <?php if( '#9d3292' !== $qc_dashboard_options['dashboard_details']['titles_color'] ) { ?>
            section h1,
            .menu .current-menu-item a,
            .menu .current-menu-item:before,
            .quote-calculator-section-add-hidden label,
            .quote-calculator-section-hidden label,
            form .quote-calculator-section-content .total-cost,
            .quote-calculator-section-content.last p a,
            form .quote-calculator-subsection .cost{
                color: <?php echo $qc_dashboard_options['dashboard_details']['titles_color']; ?>;
            }

            .estimate-page .ui-datepicker .ui-state-highlight,
            .select-options li:hover {
                background-color: <?php echo $qc_dashboard_options['dashboard_details']['titles_color']; ?>;
            }

            <?php }
			if( '#9d3292' !== $qc_dashboard_options['dashboard_details']['buttons_color'] ) { ?>
            .button {
                border-color: <?php echo $qc_dashboard_options['dashboard_details']['buttons_color']; ?>;
                background-color: <?php echo $qc_dashboard_options['dashboard_details']['buttons_color']; ?>;
            }

            <?php } ?>
        </style>
	<?php } ?>
</head>
<body <?php body_class( 'estimate-page' ); ?>>
	<div class="wrapper">
		<div class="sidebar-wrapper">
			<aside class="sidebar" role="complementary">
				<div class="mobile-aside-nav">
					<span class="dashicons dashicons-arrow-right-alt2"></span>
					<span class="dashicons dashicons-arrow-left-alt2"></span>
				</div>
				<div class="logo">
					<a href="<?php echo home_url(); ?>">
						<?php $qc_dashboard_options = get_option( 'qc_dashboard_options' ); 
						$logo_url = ( '' !== $qc_dashboard_options['dashboard_details']['logo'] ) ? $qc_dashboard_options['dashboard_details']['logo'] : get_template_directory_uri() . '/image/logo.svg'; ?>
						<img src="<?php echo $logo_url; ?>" alt="Logo" class="logo-img" height="60px">
					</a>
				</div>
				<nav class="nav" role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'quote-calculator-menu' ) ); ?>
				</nav>
			</aside>
		</div>
		<main role="main">
			<header class="header clear">
				<div class="site-title">
					<h1><a href="<?php bloginfo( 'url' ); ?>">Project Tracker</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo get_bloginfo( 'url' ) . '/calendar-view/'; ?>">Calendar View</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo get_bloginfo( 'url' ) . '/dashboard/'; ?>">Quote Calculator</a></h1>
					<div class="new-estimate">
						<a class="button" href="<?php echo get_permalink( get_page_by_path( 'estimate' ) ) . '0'; ?>"><?php _e( 'Create New Estimate', 'quote_calculator' ); ?></a>
					</div>
				</div>				
				<div class="account">
					<?php if( is_user_logged_in() ){ 
						$current_user = wp_get_current_user();
						echo __( 'Howdy', 'quote_calculator' ) . ', ' . esc_html( $current_user->user_login );
					}
					else{
						echo __( 'Login', 'quote_calculator' );
					} ?>
				</div>
			</header>
			<section>
				<h1><?php the_title(); ?></h1>
				<?php $current_estimate_id = get_query_var( 'estimate_id' );
				global $create_estimate_message, $create_estimate_error, $quote_calculator_options;
				if ( ! $quote_calculator_options ) {
					$quote_calculator_options = get_option( 'quote_calculator_options' );
				}

				if( ! empty( $create_estimate_error ) ){
					echo '<p class="message error">' . $create_estimate_error . '</p>';
				}
				if( ! empty( $create_estimate_message ) ){
					echo '<p class="message success">' . $create_estimate_message . '</p>';
				}
				if ( isset( $_GET['quote_calculator_create_xero_invoice'] ) && ! empty( $_GET['_wp_nonce'] ) ) {
					echo '<p class="message success">Invoice was created in Xero successfully and sent to customer!</p>';
                }
				$current_address = $current_delivery_address = '';
				$current_delivery_options		= quote_calculator_get_all_delivery_options();
				if( ! empty( $current_estimate_id ) && $current_estimate_id > 0 ){
					$current_estimate						= quote_calculator_get_estimate( $current_estimate_id );
					$current_estimate_sections	= quote_calculator_get_estimate_sections( $current_estimate_id );
					$current_address						= quote_calculator_get_customers_address( $current_estimate['estimates_customer_bill_address_id'] );
					$current_delivery_address		= quote_calculator_get_customers_address( $current_estimate['estimates_customer_ship_address_id'] );
				} 
				$last_estimate_id = quote_calculator_get_last_estimate_id(); ?>
				<form action="<?php the_permalink(); ?>" method="post">
					<?php if( ! empty( $current_estimate['estimates_id'] ) ){ ?>
						<input type="hidden" name="quote_calculator_estimates_id" class="quote_calculator_estimates_id" value="<?php echo $current_estimate['estimates_id']; ?>" />
					<?php } ?>
					<div class="quote-calculator-section">
						<div class="quote-calculator-section-title">
							<h3><?php _e( 'Details', 'quote_calculator' ); ?></h3>
						</div>
						<div class="quote-calculator-section-content">
							<input type="text" name="quote_calculator_reference_no" class="quote-calculator-reference-no" value="<?php echo ! empty( $current_estimate['estimates_reference_no'] ) ? $current_estimate['estimates_reference_no'] : ( ! empty( $current_estimate['estimates_id'] ) ? $current_estimate['estimates_id'] : ++$last_estimate_id ); ?>" placeholder="<?php _e( 'Reference/Estimate No.', 'quote_calculator' ); ?>" />
							<input type="text" name="quote_calculator_date" class="quote-calculator-date" value="<?php echo ! empty( $current_estimate['estimates_date'] ) ? date( 'F j, Y', $current_estimate['estimates_date'] ) : date( 'F j, Y', time() ); ?>" placeholder="<?php _e( 'Date', 'quote_calculator' ); ?>" />
							<input type="text" name="quote_calculator_prepared" class="quote-calculator-prepared" value="<?php echo ! empty( $current_estimate['estimates_prepared'] ) ? $current_estimate['estimates_prepared'] : ''; ?>" placeholder="<?php _e( 'Prepared by...', 'quote_calculator' ); ?>" />
						</div>
						<div class="quote-calculator-section-title">
							<h3><?php _e( 'About the Client', 'quote_calculator' ); ?></h3>
						</div>
						<div class="quote-calculator-section-content">
							<input type="text" id="quote_calculator_name" name="quote_calculator_name" value="<?php echo ! empty( $current_estimate['estimates_customer_name'] ) ? stripslashes( $current_estimate['estimates_customer_name'] ) : ''; ?>" placeholder="<?php _e( 'Client Name', 'quote_calculator' ); ?>" />
							<input type="hidden" id="quote_calculator_customer_id" name="quote_calculator_customer_id" value="<?php echo ! empty( $current_estimate['estimates_customer_id'] ) ? $current_estimate['estimates_customer_id'] : ''; ?>" />
							<input type="hidden" id="quote_calculator_bill_address_id" name="quote_calculator_bill_address_id" value="<?php echo ! empty( $current_estimate['estimates_customer_bill_address_id'] ) ? $current_estimate['estimates_customer_bill_address_id'] : ''; ?>" />
							<input type="hidden" id="quote_calculator_ship_address_id" name="quote_calculator_ship_address_id" value="<?php echo ! empty( $current_estimate['estimates_customer_ship_address_id'] ) ? $current_estimate['estimates_customer_ship_address_id'] : ''; ?>" />
							<input type="email" name="quote_calculator_email" value="<?php echo ! empty( $current_estimate['estimates_customer_email'] ) ? $current_estimate['estimates_customer_email'] : ''; ?>" placeholder="<?php _e( 'Client Email', 'quote_calculator' ); ?>" />
							<input type="tel" name="quote_calculator_phone" value="<?php echo ! empty( $current_estimate['estimates_customer_phone'] ) ? $current_estimate['estimates_customer_phone'] : ''; ?>" placeholder="<?php _e( 'Client Telephone', 'quote_calculator' ); ?>" />
							<div>
								<div class="quote_calculator_address">
									<input type="text" name="quote_calculator_bill_address_1" id="quote_calculator_bill_address_1" value="<?php echo ! empty( $current_address['customer_address_line1'] ) ? $current_address['customer_address_line1'] : ''; ?>" placeholder="<?php _e( 'Billing Address Line 1', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_bill_address_2" id="quote_calculator_bill_address_2" value="<?php echo ! empty( $current_address['customer_address_line2'] ) ? $current_address['customer_address_line2'] : ''; ?>" placeholder="<?php _e( 'Billing Address Line 2', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_bill_address_3" id="quote_calculator_bill_address_3" value="<?php echo ! empty( $current_address['customer_address_line3'] ) ? $current_address['customer_address_line3'] : ''; ?>" placeholder="<?php _e( 'Billing Address Line 3', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_bill_city" id="quote_calculator_bill_city" value="<?php echo ! empty( $current_address['customer_address_city'] ) ? $current_address['customer_address_city'] : ''; ?>" placeholder="<?php _e( 'Billing City', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_bill_country" id="quote_calculator_bill_country" value="<?php echo ! empty( $current_address['customer_address_country'] ) ? $current_address['customer_address_country'] : ''; ?>" placeholder="<?php _e( 'Billing Country', 'quote_calculator' ); ?>" />
									<input type="hidden" name="quote_calculator_bill_country_code" id="quote_calculator_bill_country_code" value="<?php echo ! empty( $current_address['customer_address_country_sub_division_code'] ) ? $current_address['customer_address_country_sub_division_code'] : ''; ?>" placeholder="<?php _e( 'Billing Country Code', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_bill_post_code" id="quote_calculator_bill_post_code" value="<?php echo ! empty( $current_address['customer_address_postal_code'] ) ? $current_address['customer_address_postal_code'] : ''; ?>" placeholder="<?php _e( 'Billing Postal code', 'quote_calculator' ); ?>" />
								</div>
								<div class="quote_calculator_delivery_address">
									<input type="text" name="quote_calculator_ship_address_1" id="quote_calculator_ship_address_1" value="<?php echo ! empty( $current_delivery_address['customer_address_line1'] ) ? $current_delivery_address['customer_address_line1'] : ''; ?>" placeholder="<?php _e( 'Shipping Address Line 1', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_ship_address_2" id="quote_calculator_ship_address_2" value="<?php echo ! empty( $current_delivery_address['customer_address_line2'] ) ? $current_delivery_address['customer_address_line2'] : ''; ?>" placeholder="<?php _e( 'Shipping Address Line 2', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_ship_address_3" id="quote_calculator_ship_address_3" value="<?php echo ! empty( $current_delivery_address['customer_address_line3'] ) ? $current_delivery_address['customer_address_line3'] : ''; ?>" placeholder="<?php _e( 'Shipping Address Line 3', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_ship_city" id="quote_calculator_ship_city" value="<?php echo ! empty( $current_delivery_address['customer_address_city'] ) ? $current_delivery_address['customer_address_city'] : ''; ?>" placeholder="<?php _e( 'Shipping City', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_ship_country" id="quote_calculator_ship_country" " value="<?php echo ! empty( $current_delivery_address['customer_address_country'] ) ? $current_delivery_address['customer_address_country'] : ''; ?>" placeholder="<?php _e( 'Shipping Country', 'quote_calculator' ); ?>" />
									<input type="hidden" name="quote_calculator_ship_country_code" id="quote_calculator_ship_country_code" value="<?php echo ! empty( $current_delivery_address['customer_address_country_sub_division_code'] ) ? $current_delivery_address['customer_address_country_sub_division_code'] : ''; ?>" placeholder="<?php _e( 'Shipping Country Code', 'quote_calculator' ); ?>" />
									<input type="text" name="quote_calculator_ship_post_code" id="quote_calculator_ship_post_code" value="<?php echo ! empty( $current_delivery_address['customer_address_postal_code'] ) ? $current_delivery_address['customer_address_postal_code'] : ''; ?>" placeholder="<?php _e( 'Shipping Postal code', 'quote_calculator' ); ?>" />
								</div>
							</div>
						</div>
					</div>
					<div class="quote-calculator-section add-new-item <?php if( ! empty( $current_estimate_sections ) ) echo 'hidden'; ?>">
						<div class="quote-calculator-section-content">
							<div class="quote-calculator-subsection">
								<span class="button default quote-calculator-select-section-button"><?php _e( 'Add New Item', 'quote_calculator' ); ?></span>
							</div>
						</div>
					</div>
					<?php if( ! empty( $current_estimate_sections ) ){ 
						global $current_section_count;
						foreach( $current_estimate_sections as $key =>  $current_estimate_section ){
							$current_section_count = $key;
							switch( $current_estimate_section['estimate_section_category'] ){
								case '1':
									include( plugin_dir_path( __FILE__ ) . 'includes/part-digital-printing.php' );
									break;
								case '2':
									include( plugin_dir_path( __FILE__ ) . 'includes/part-jobs-out.php' );
									break;
								case '3':
									include( plugin_dir_path( __FILE__ ) . 'includes/part-design.php' );
									break;
								case '4':
									include( plugin_dir_path( __FILE__ ) . 'includes/part-wide-format.php' );
									break;
							}
							echo '<input type="hidden" name="quote_calculator_category[' . $current_estimate_section['estimate_section_category'] . '][' . $current_section_count . '][estimate_section_id]" value="' . $current_estimate_section['estimate_section_id'] . '" />';
						}
						echo '<input type="hidden" id="current_section_count" value="' . ( ++$current_section_count ) . '" />';
					} ?>
					<div class="quote-calculator-section last">
						<div class="quote-calculator-section-title">
							<h3><?php _e( 'Deadline', 'quote_calculator' ); ?></h3>
						</div>
						<div class="quote-calculator-section-content">
							<input type="text" name="quote_calculator_deadline" class="quote-calculator-deadline" value="<?php echo ! empty( $current_estimate['estimates_deadline'] ) ? date( 'F j, Y', $current_estimate['estimates_deadline'] ) : ''; ?>" placeholder="<?php _e( 'Deadline', 'quote_calculator' ); ?>" />
						</div>
						<div class="quote-calculator-section-title">
							<h3><?php _e( 'Delivery Notes', 'quote_calculator' ); ?></h3>
						</div>
						<div class="quote-calculator-section-content">
							<?php foreach( $current_delivery_options as $delivery_option ){ ?>
								<label for="quote_calculator_delivery_<?php echo $delivery_option['delivery_id']; ?>" class="radio-label">
									<input type="radio" name="quote_calculator_delivery" id="quote_calculator_delivery_<?php echo $delivery_option['delivery_id']; ?>" value="<?php echo $delivery_option['delivery_title']; ?>" data-price="<?php echo $delivery_option['delivery_price']; ?>" <?php echo empty( $current_estimate['estimates_delivery'] ) || ( ! empty( $current_estimate['estimates_delivery'] ) && $delivery_option['delivery_title'] == $current_estimate['estimates_delivery'] ) ? 'checked="checked"' : ''; ?> /><span> </span><?php echo $delivery_option['delivery_title']; ?>
								</label>
							<?php } ?>							
							<textarea name="quote_calculator_delivery_notes" placeholder="<?php _e( 'Notes', 'quote_calculator' ); ?>"><?php echo ! empty( $current_estimate['estimates_notes'] ) ? stripcslashes( $current_estimate['estimates_notes'] ) : ''; ?></textarea>
						</div>
						<div class="quote-calculator-section-title">
							<h3><?php _e( 'Memo', 'quote_calculator' ); ?></h3>
						</div>
						<div class="quote-calculator-section-content">
							<textarea name="quote_calculator_memo" placeholder="<?php _e( 'Memo', 'quote_calculator' ); ?>"><?php echo ! empty( $current_estimate['estimates_memo'] ) ? stripcslashes( $current_estimate['estimates_memo'] ) : ''; ?></textarea>
						</div>
						<div class="quote-calculator-section-title">
							<h3><?php _e( 'Terms', 'quote_calculator' ); ?></h3>
						</div>
						<div class="quote-calculator-section-content">
							<label for="quote_calculator_terms_1" class="radio-label"><input type="radio" name="quote_calculator_terms" id="quote_calculator_terms_1" value="50% to be paid on order" <?php echo empty( $current_estimate['estimates_terms'] ) || ( ! empty( $current_estimate['estimates_terms'] ) && '50% to be paid on order' == $current_estimate['estimates_terms'] ) ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( '50% to be paid on order', 'quote_calculator' ); ?></label>
							<label for="quote_calculator_terms_2" class="radio-label"><input type="radio" name="quote_calculator_terms" id="quote_calculator_terms_2" value="Net30" <?php echo ! empty( $current_estimate['estimates_terms'] ) && 'Net30' == $current_estimate['estimates_terms'] ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Net30', 'quote_calculator' ); ?></label>
							<label for="quote_calculator_terms_3" class="radio-label"><input type="radio" name="quote_calculator_terms" id="quote_calculator_terms_3" value="Net60" <?php echo ! empty( $current_estimate['estimates_terms'] ) && 'Net60' == $current_estimate['estimates_terms'] ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Net60', 'quote_calculator' ); ?></label>
							<label for="quote_calculator_terms_4" class="radio-label"><input type="radio" name="quote_calculator_terms" id="quote_calculator_terms_4" value="Payment on Collection" <?php echo ! empty( $current_estimate['estimates_terms'] ) && 'Payment on Collection' == $current_estimate['estimates_terms'] ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Payment on Collection', 'quote_calculator' ); ?></label>
							<label for="quote_calculator_terms_5" class="radio-label"><input type="radio" name="quote_calculator_terms" id="quote_calculator_terms_5" value="Payment on Order" <?php echo ! empty( $current_estimate['estimates_terms'] ) && 'Payment on Order' == $current_estimate['estimates_terms'] ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Payment on Order', 'quote_calculator' ); ?></label>
						</div>
					</div>
					<div class="quote-calculator-section last-section">
						<div class="quote-calculator-section-title">
							<h3 class="left"><?php _e( 'Estimate Stage', 'quote_calculator' ); ?></h3> <h3 class="right"><?php _e( 'Total Cost', 'quote_calculator' ); ?></h3> 
						</div>
						<div class="quote-calculator-section-content left">
							<label for="quote_calculator_status_1" class="radio-label"><input type="radio" name="quote_calculator_status" id="quote_calculator_status_1" value="estimate" <?php echo empty( $current_estimate['estimates_status'] ) || ( ! empty( $current_estimate['estimates_status'] ) && 'estimate' == $current_estimate['estimates_status'] ) ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Estimate', 'quote_calculator' ); ?></label>
							<label for="quote_calculator_status_2" class="radio-label"><input type="radio" name="quote_calculator_status" id="quote_calculator_status_2" value="sales" <?php echo ! empty( $current_estimate['estimates_status'] ) && 'sales' == $current_estimate['estimates_status'] ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Sales Order', 'quote_calculator' ); ?></label>
							<label for="quote_calculator_status_3" class="radio-label"><input type="radio" name="quote_calculator_status" id="quote_calculator_status_3" value="delivery_note" <?php echo ! empty( $current_estimate['estimates_status'] ) && 'delivery_note' == $current_estimate['estimates_status'] ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Delivery Note', 'quote_calculator' ); ?></label>
							<label for="quote_calculator_status_4" class="radio-label"><input type="radio" name="quote_calculator_status" id="quote_calculator_status_4" value="completed" <?php echo ! empty( $current_estimate['estimates_status'] ) && 'completed' == $current_estimate['estimates_status'] ? 'checked="checked"' : ''; ?> /><span> </span><?php _e( 'Completed', 'quote_calculator' ); ?></label>
						</div>
						<div class="quote-calculator-section-content right">
							<span class="total-cost"><?php echo ! empty( $current_estimate['estimates_cost'] ) ? '£' . number_format( floatval( $current_estimate['estimates_cost'] ), 2, '.', '' ) : '£0.00'; ?></span>
							<span class="total-cost-vat"><span><?php echo ! empty( $current_estimate['estimates_cost_incl_vat'] ) ? '£' . number_format( floatval( $current_estimate['estimates_cost_incl_vat'] ), 2, '.', '' ) : '£0.00'; ?></span> incl VAT</span>
							<input type="hidden" name="quote_calculator_cost" id="quote_calculator_cost" value="<?php echo ! empty( $current_estimate['estimates_cost'] ) ? $current_estimate['estimates_cost'] : '0'; ?>" />
							<input type="hidden" name="quote_calculator_cost_incl_vat" id="quote_calculator_cost_incl_vat" value="<?php echo ! empty( $current_estimate['estimates_cost_incl_vat'] ) ? $current_estimate['estimates_cost_incl_vat'] : '0'; ?>" />
						</div>
						<div class="quote-calculator-section-content last">
							<span class="button submit"><?php _e( 'Save', 'quote_calculator' ); ?></span> <a href="<?php echo isset( $current_estimate ) ? '?quote_calculator_create_pdf=' . $current_estimate['estimates_id'] . '&_wp_nonce=' . wp_create_nonce( 'quote_calculator_create_pdf_action' ) : ''; ?>" <?php if( ! isset( $current_estimate ) ) echo 'class="disbaled"'; ?>><span class="button generate"><?php _e( 'Generate PDF', 'quote_calculator' ); ?></span></a>
							<a href="<?php echo isset( $current_estimate ) ? '?quote_calculator_create_pdf=' . $current_estimate['estimates_id'] . '&without_total=1&_wp_nonce=' . wp_create_nonce( 'quote_calculator_create_pdf_action' ) : ''; ?>" <?php if( ! isset( $current_estimate ) ) echo 'class="disbaled"'; ?>><span class="button generate"><?php _e( 'Generate PDF Without Total', 'quote_calculator' ); ?></span></a>

                            <!-- Show Xero -->
							<?php if ( empty( $quote_calculator_options["client_id"] ) && empty( $quote_calculator_options["client_secret"] ) ) {
								if ( ! empty( $quote_calculator_options['xero_consumer_key'] ) &&
								     ! empty( $quote_calculator_options['xero_consumer_secret'] ) /*&&
								     ! empty( $quote_calculator_options['xero_app_type'] )*/ ) {
									// We should refresh $_SESSION
									$xero = new QC_Xero();
								}

								if ( isset( $xero ) /*&& ( 'private' == $quote_calculator_options['xero_app_type'] || $_SESSION['xero_oauth']['xero_expires'] )*/ ) { ?>
                                    <a href="<?php echo isset( $current_estimate ) ? '?quote_calculator_create_xero_invoice=' . $current_estimate['estimates_id'] . '&_wp_nonce=' . wp_create_nonce( 'quote_calculator_create_xero_invoice_action' ) : ''; ?>" <?php if ( ! isset( $current_estimate ) ) {
										echo 'class="disbaled"';
									} ?>><span class="button default right create-inv-xero"><?php _e( 'Create Invoice in Xero', 'quote_calculator' ); ?></span></a>
								<?php } else { ?>
                                    <p class="align-right qc-xero-connect">You need to <a href="<?php echo admin_url( 'admin.php?page=quote_calculator_xero', 'https' ); ?>">connect</a> to Xero</p>
								<?php }
							}

							/* Show Quickbooks */
							if ( empty( $quote_calculator_options['xero_consumer_key'] ) && empty( $quote_calculator_options['xero_consumer_secret'] ) ) {
								if ( isset( $current_estimate ) ) {
									$estimate_api = quote_calculator_get_estimate_api( $current_estimate['estimates_id'] );
								}
								if ( ! empty( $estimate_api ) ) { ?>
                                    <span class="button default right"><?php _e( 'Invoice already created', 'quote_calculator' ); ?></span>
								<?php } else {
									if ( isset( $_SESSION['accessToken'] ) && ! empty( $quote_calculator_options["client_id"] ) && ! empty( $quote_calculator_options["client_secret"] ) ) { ?>
                                        <a href="<?php echo isset( $current_estimate ) ? '?quote_calculator_create_invoice=' . $current_estimate['estimates_id'] . '&_wp_nonce=' . wp_create_nonce( 'quote_calculator_create_invoice_action' ) : ''; ?>" <?php if ( ! isset( $current_estimate ) ) {
											echo 'class="disbaled"';
										} ?>><span class="button default right"><?php _e( 'Create Invoice', 'quote_calculator' ); ?></span></a>
									<?php } else { ?>
                                        <p class="align-right">You need to <a href="<?php echo admin_url( 'admin.php?page=quote_calculator', 'https' ); ?>">connect</a> to QC</p>
									<?php }
								}
							}

							if( ! empty( $current_estimate_id ) && $current_estimate_id > 0 ){ ?>
								<br /><a class="button duplicate-button" href="<?php echo esc_url( add_query_arg( array( 'quote_calculator_duplicate_estimate' => $current_estimate_id, '_wp_nonce' => wp_create_nonce( 'quote_calculator_duplicate_estimate_action' ) ), get_the_permalink() ) ); ?>">Duplicate</a> <a class="button delete-button" href="<?php echo esc_url( add_query_arg( array( 'quote_calculator_delete_estimate' => $current_estimate_id, '_wp_nonce' => wp_create_nonce( 'quote_calculator_delete_estimate_action' ) ), get_the_permalink() ) ); ?>">Delete</a> 
							<?php } ?>
						</div>
					</div>
					<?php wp_nonce_field( 'quote_calculator_action', 'quote_calculator_field' ); ?>
				</form>
				<div class="quote-calculator-section-overflow-hidden hidden">
				</div>
				<div class="quote-calculator-section-hidden hidden">
					<div class="quote-calculator-section-hidden-content">
						<div class="quote-calculator-select-section">
							<label><?php _e( 'Please choose which type of service you wish to add', 'quote_calculator' ); ?></label>
							<div class="select-wrapper">
								<select id="quote_calculator_section">
									<option value="design"><?php _e( 'Design', 'quote_calculator' ); ?></option>
									<option value="digital"><?php _e( 'Digital Printing', 'quote_calculator' ); ?></option>
									<option value="jobs"><?php _e( 'Jobs Out', 'quote_calculator' ); ?></option>
									<option value="wide"><?php _e( 'Wide Format', 'quote_calculator' ); ?></option>									
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="quote-calculator-section-add-hidden hidden">
					<div class="quote-calculator-section-hidden-content">
						<div class="quote-calculator-select-section">
							<label><?php _e( 'You are about to create an estimate or sales order', 'quote_calculator' ); ?></label>
							<div class="label"><?php _e( 'All estimates or sales orders are able to be modified once created', 'quote_calculator' ); ?></div>
							<div class="button-wrapper"><span class="button add"><?php _e( 'Yep!', 'quote_calculator' ); ?></span> <span class="button default"><?php _e( 'Cancel', 'quote_calculator' ); ?></span></div>
						</div>
					</div>
				</div>
			</section>
		</main>
	</div>
	<?php wp_footer(); ?>
</body>
</html>