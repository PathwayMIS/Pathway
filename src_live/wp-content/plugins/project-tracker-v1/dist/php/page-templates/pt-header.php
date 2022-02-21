<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		<link href="//www.google-analytics.com" rel="dns-prefetch">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div class="dimmed"></div>
		<header id="header" class="header clear container" role="banner">
			<div id="logo" class="logo">
				<a href="<?php bloginfo( 'url' ); ?>">Project Tracker</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo get_bloginfo( 'url' ) . '/calendar-view/'; ?>">Calendar View</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo get_bloginfo( 'url' ) . '/dashboard/'; ?>">Quote Calculator</a>
			</div>
			<div id="hamburger" class="hamburger">Menu</div>
			<div id="user" class="user">
				<?php 
					$current_user = wp_get_current_user(); 
					$first_name = empty( $current_user->user_firstname ) ? $current_user->display_name : $current_user->user_firstname ;
				?>
				<?php if ($first_name != '') { ?>
						<a href="<?php echo admin_url( 'edit.php?post_type=projects', 'http' ); ?>">Howdy, <?php echo $first_name ?></a>
				<?php } else { ?>
						<a href="<?php echo admin_url( 'edit.php?post_type=projects', 'http' ); ?>">Hey there!</a>
				<?php } ?>
			</div>
			<div class="clearfix"></div>
		</header>
		<div id="ajaxLoading" class="m-scene">
			<nav id="nav" class="nav">
				<div class="navBarWrap">
					<div id="brand" class="brand">
						<?php $qc_dashboard_options = get_option( 'qc_dashboard_options' );
						if ( get_field('logo','option') ) {
							$logo = get_field('logo','option');
						} else {
							$logo = array( 'url' => '' );
                        }
						$logo_url = ( '' !== $qc_dashboard_options['dashboard_details']['logo'] ) ? $qc_dashboard_options['dashboard_details']['logo'] : $logo['url']; ?>
						<img src="<?php echo $logo_url; ?>"/>
					</div>
					<?php
						$all_url = '/project-tracker-all-jobs/'; 
						$design_url = '/project-tracker-design/';
						$proof_url = '/project-tracker-proof/';
						$prpro_url = '/project-tracker-print-production/';
						$wideformat_url = '/project-tracker-wideformat/';
						$jobs_out_url = '/project-tracker-jobs-out/';
						$delivery_url = '/project-tracker-delivery/';
						$completed_url = '/project-tracker-completed/';
						$all_values = array( 'Design', 'Proof', 'Print Production', 'Wideformat', 'Jobs out', 'Delivery' );
						$all_results = array( 'Design' => '', 'Proof' => '', 'Print Production' => '', 'Wideformat' => '',  'Jobs out' => '', 'Delivery' => '' );
						if ( get_option( 'qc_dashboard_department_options' ) ) {
							$qc_dashboard_department_options = get_option( 'qc_dashboard_department_options' );
						} else {
							$qc_dashboard_department_options = array( 'Design' => '', 'Proof' => '', 'Print Production' => '', 'Jobs out' => '', 'Delivery' => '', 'Design Studio' => '' );
                        }
						foreach( $all_values as $value ) {
							$current_value = $value;
							while( array_key_exists( $value, $qc_dashboard_department_options ) ) {
								if( '' !== $qc_dashboard_department_options[ $value ] ){
									$new_value = $qc_dashboard_department_options[ $value ];
									unset( $qc_dashboard_department_options[ $value ] );
									$value = $new_value;
								} else {
									unset( $qc_dashboard_department_options[ $value ] );
									break;
								}
							}
							$all_results[ $current_value ] = $value;
						}
						//$cal_view_url = '/calendar-view/';
					?>

					<ul class="navWrap">
						<li class="navWrap__item" data-stage="all"><a href="<?php echo site_url() . $all_url; ?>">All Jobs</a></li>
						<li class="navWrap__item" data-stage="design"><a href="<?php echo site_url() . $design_url; ?>"><?php echo $all_results['Design']; ?></a></li>
						<li class="navWrap__item" data-stage="proof"><a href="<?php echo site_url() . $proof_url; ?>"><?php echo $all_results['Proof']; ?></a></li>
						<li class="navWrap__item" data-stage="print-production"><a href="<?php echo site_url() . $prpro_url; ?>"><?php echo $all_results['Print Production']; ?></a></li>
						<li class="navWrap__item" data-stage="wideformat"><a href="<?php echo site_url() . $wideformat_url; ?>"><?php echo $all_results['Wideformat']; ?></a></li>
						<li class="navWrap__item" data-stage="jobs-out"><a href="<?php echo site_url() . $jobs_out_url; ?>"><?php echo $all_results['Jobs out']; ?></a></li>
						<li class="navWrap__item" data-stage="delivery"><a href="<?php echo site_url() . $delivery_url; ?>"><?php echo $all_results['Delivery']; ?></a></li>
						<li class="navWrap__item" data-stage="completed"><a href="<?php echo site_url() . $completed_url; ?>">Completed</a></li>
						<li class="navWrap__item" data-stage="settings"><a href="<?php echo site_url() . '/wp-admin/admin.php?page=custom_dashboard'; ?>">Dashboard</a></li>
					</ul>
				</div>
			</nav>
			<div id="main-content" class="container fadeIn brandCurrent">