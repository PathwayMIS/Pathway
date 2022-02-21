<?php acf_form_head(); ?>
<?php
//Template Name: Design
?>
<?php 
	//Includes the header lol //
	include (plugin_dir_path( __FILE__ ) . 'pt-header.php'); 
?>
<main role="main">
	<?php 
		//quick search lol //
		include (plugin_dir_path( __FILE__ ) . 'quick-search.php'); 
	?>
	<section class="app__table">
		<div class="app__table__headings__container">
			<h6 class="app__table__heading">Company</h6>
			<h6 class="app__table__heading">Ref.</h6>
			<h6 class="app__table__heading">Order No.</h6>
			<h6 class="app__table__heading">Deadline</h6>
			<h6 class="app__table__heading app__table__heading--popup">Order</h6>
			<h6 class="app__table__heading app__table__heading--popup">Notes</h6>
			<h6 class="app__table__heading app__table__heading--popup">Artwork</h6>
			<h6 class="app__table__heading app__table__heading--stage">Design</h6>
			<h6 class="app__table__heading app__table__heading--stage">Proof</h6>
			<h6 class="app__table__heading app__table__heading--stage">Changes</h6>
		</div>
		<div class="searchContainer">
			<?php 
			$args = array( 
				'post_type' => 'projects',
				'posts_per_page' => -1,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'client'
					),
					array(
						'key' => 'project_stage',
						'value' => 'Design'
					),
				),
				'orderby' => array( 
						'client' => 'ASC'
				),
				'post_status'	=> 'publish'
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post(); ?>
				<article class="app__table__row" 
					data-sort-name="<?php echo strtolower(get_field('client')); ?>" 
					data-sort-deadline="<?php echo get_field('deadline'); ?>"
					>
				  	<?php 
						//stage loop lol //
						include (plugin_dir_path( __FILE__ ) . 'item-loop.php'); 
					?>
				</article>
			<?php endwhile; ?>
		</div>
	</section>
	<?php 
		//new item lol //
		include (plugin_dir_path( __FILE__ ) . 'new-item.php'); 
	?>
</main>
</div></div>
<?php wp_footer(); ?>