<?php acf_form_head(); ?>
<?php
//Template Name: Design
?>
<?php 
	//Includes the header lol //
	include (plugin_dir_path( __FILE__ ) . 'pt-header.php'); 
?>
<main role="main">
	<?php if( have_posts() ) {
		the_post();
		the_content();
	} ?>
</main>
</div></div>
<?php wp_footer(); ?>