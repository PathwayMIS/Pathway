<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'estimate-page' ); ?>>
	<div class="wrapper">
		<header class="header clear">
			<div class="site-title">
				<h1>Quote Calculator</h1>
			</div>
			<div class="new-estimate">
				<a class="button" href="<?php echo get_permalink( get_page_by_path( 'estimate' ) ) . '0'; ?>"><?php _e( 'Create New Estimate', 'quote_сalculator' ); ?></a>
			</div>
			<div class="account">
				<?php if( is_user_logged_in() ){ 
					$current_user = wp_get_current_user();
					echo __( 'Howdy', 'quote_сalculator' ) . ', ' . esc_html( $current_user->user_login );
				}
				else{
					echo __( 'Login', 'quote_сalculator' );
				} ?>
			</div>
		</header>
		<aside class="sidebar" role="complementary">
			<div class="logo">
				<a href="<?php echo home_url(); ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/image/logo.svg" alt="Logo" class="logo-img" height="60px">
				</a>
			</div>
			<nav class="nav" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'quote-сalculator-menu' ) ); ?>
			</nav>
		</aside>
		<main role="main">
		</main>
	</div>
	<?php wp_footer(); ?>
</body>
</html>