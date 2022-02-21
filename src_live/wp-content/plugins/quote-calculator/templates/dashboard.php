<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'dashboard-page' ); ?>>
	<div class="wrapper">
		<div class="sidebar-wrapper">
			<aside class="sidebar" role="complementary">
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
				<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
				<div class="clientside-back-to-top" title="Back to Top" style="display: flex;">
					<span class="dashicons dashicons-arrow-up-alt"></span>
				</div>
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
				<div class="quote-calculator-section">
					<div class="quote-calculator-section-title">
						<h3><?php _e( 'Estimates', 'quote_calculator' ); ?></h3>
						<div class="quote-calculator-section-title-right">
							<form>
								<span><?php _e( 'Filter', 'quote_calculator' ); ?>:</span> <input type="text" name="quote-calculator-filter" value="" />
							</form>
						</div>
					</div>
					<div class="quote-calculator-section-content">
						<table>
							<thead>
								<tr>
									<th><?php _e( 'Client Name', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Reference', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Memo', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Date', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Prepared By', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Total Cost', 'quote_calculator' ); ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $all_estimates = quote_calculator_get_all_estimate(); 
								foreach( $all_estimates as $key => $estimate ){ 
									if( 'estimate' == $estimate['estimates_status'] ){ ?>
										<tr>
											<td><?php echo stripslashes( $estimate['estimates_customer_name'] ); ?></td>
											<td><?php echo $estimate['estimates_reference_no']; ?></td>
											<td><?php echo $estimate['estimates_memo']; ?></td>
											<td><?php echo date( 'm.d.Y', $estimate['estimates_date'] ); ?></td>
											<td><?php echo $estimate['estimates_prepared']; ?></td>
											<td>&pound;<?php echo $estimate['estimates_cost_incl_vat']; ?></td>
											<td><a href="<?php echo get_permalink( get_page_by_path( 'estimate' ) ) . $estimate['estimates_id']; ?>"><?php _e( 'Edit', 'quote_calculator' ); ?></a></td>
										</tr>
										<?php unset( $all_estimates[ $key ] );
									} 
								} 
								reset( $all_estimates ); ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="quote-calculator-section">
					<div class="quote-calculator-section-title">
						<h3><?php _e( 'Sales order', 'quote_calculator' ); ?></h3>
						<div class="quote-calculator-section-title-right">
							<form>
								<span><?php _e( 'Filter', 'quote_calculator' ); ?>:</span> <input type="text" name="quote-calculator-filter" value="" />
							</form>
						</div>
					</div>
					<div class="quote-calculator-section-content">
						<table>
							<thead>
								<tr>
									<th><?php _e( 'Client Name', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Reference', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Memo', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Date', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Prepared By', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Total Cost', 'quote_calculator' ); ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $all_estimates as $key => $estimate ){ 
									if( 'sales' == $estimate['estimates_status'] ){ ?>
										<tr>
											<td><?php echo stripslashes( $estimate['estimates_customer_name'] ); ?></td>
											<td><?php echo $estimate['estimates_reference_no']; ?></td>
											<td><?php echo $estimate['estimates_memo']; ?></td>
											<td><?php echo date( 'm.d.Y', $estimate['estimates_date'] ); ?></td>
											<td><?php echo $estimate['estimates_prepared']; ?></td>
											<td>&pound;<?php echo $estimate['estimates_cost_incl_vat']; ?></td>
											<td><a href="<?php echo get_permalink( get_page_by_path( 'estimate' ) ) . $estimate['estimates_id']; ?>"><?php _e( 'Edit', 'quote_calculator' ); ?></a></td>
										</tr>
										<?php unset( $all_estimates[ $key ] );
									} 
								} 
								reset( $all_estimates ); ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="quote-calculator-section">
					<div class="quote-calculator-section-title">
						<h3><?php _e( 'Completed', 'quote_calculator' ); ?></h3>
						<div class="quote-calculator-section-title-right">
							<form>
								<span><?php _e( 'Filter', 'quote_calculator' ); ?>:</span> <input type="text" name="quote-calculator-filter" value="" />
							</form>
						</div>
					</div>
					<div class="quote-calculator-section-content">
						<table>
							<thead>
								<tr>
									<th><?php _e( 'Client Name', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Reference', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Memo', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Date', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Prepared By', 'quote_calculator' ); ?></th>
									<th><?php _e( 'Total Cost', 'quote_calculator' ); ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $all_estimates as $key => $estimate ){ 
									if( 'completed' == $estimate['estimates_status'] ){ ?>
										<tr>
											<td><?php echo stripslashes( $estimate['estimates_customer_name'] ); ?></td>
											<td><?php echo $estimate['estimates_reference_no']; ?></td>
											<td><?php echo $estimate['estimates_memo']; ?></td>
											<td><?php echo date( 'm.d.Y', $estimate['estimates_date'] ); ?></td>
											<td><?php echo $estimate['estimates_prepared']; ?></td>
											<td>&pound;<?php echo number_format( $estimate['estimates_cost_incl_vat'], 2, '.', '' ); ?></td>
											<td><a href="<?php echo get_permalink( get_page_by_path( 'estimate' ) ) . $estimate['estimates_id']; ?>"><?php _e( 'Edit', 'quote_calculator' ); ?></a></td>
										</tr>
										<?php unset( $all_estimates[ $key ] );
									} 
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			
				<div class="quote-calculator-section-overflow-hidden hidden">
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

			<div class="pathway">
				<div class="qc-admin-footer-logo">
					<img src="http://acp.pathwaymis.co.uk/wp-content/plugins/quote-calculator/images/Pathway_Logo.jpg" />
				</div>
			</div>
		</main>
	</div>
	<?php wp_footer(); ?>
</body>
</html>