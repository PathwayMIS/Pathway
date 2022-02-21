<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		<link href="//www.google-analytics.com" rel="dns-prefetch">
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div class="dimmed"></div>
		<header id="header" class="header clear container" role="banner">
			<div id="logo" class="logo">
				<a href="<?php echo home_url(); ?>">Project Tracker</a>
			</div>
			<div id="hamburger" class="hamburger">Menu</div>
			<div id="user" class="user">
				<?php 
					$current_user = wp_get_current_user(); 
					$first_name = $current_user->user_firstname;
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
						<?php if (get_field('logo','option') ) { ?>
							<?php $logo = get_field('logo','option'); ?>
							<img src="<?php echo $logo[url]; ?>"/>
						<?php } ?>
					</div>
					<?php
						$all_url = '/project-tracker-all-jobs/'; 
						$design_url = '/project-tracker-design/';
						$prpro_url = '/project-tracker-print-production/';
						$jobs_out_url = '/project-tracker-jobs-out/';
						$delivery_url = '/project-tracker-delivery/';
						$completed_url = '/project-tracker-completed/';
					?>

					<ul class="navWrap">
						<li class="navWrap__item" data-stage="all"><a href="<?php echo site_url() . $all_url; ?>">All Jobs</a></li>
						<li class="navWrap__item" data-stage="design"><a href="<?php echo site_url() . $design_url; ?>">Design</a></li>
						<li class="navWrap__item" data-stage="print-production"><a href="<?php echo site_url() . $prpro_url; ?>">Print Production</a></li>
						<li class="navWrap__item" data-stage="jobs-out"><a href="<?php echo site_url() . $jobs_out_url; ?>">Jobs Out</a></li>
						<li class="navWrap__item" data-stage="delivery"><a href="<?php echo site_url() . $delivery_url; ?>">Delivery</a></li>
						<li class="navWrap__item" data-stage="completed"><a href="<?php echo site_url() . $completed_url; ?>">Completed</a></li>
						<li class="navWrap__item" data-stage="settings"><a href="<?php echo admin_url( 'admin.php?page=branding-settings', 'http' ); ?>">Settings</a></li>
					</ul>
				</div>
			</nav>
			<div id="main-content" class="container fadeIn brandCurrent">