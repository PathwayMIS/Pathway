<?php acf_form_head(); ?>
<?php
//Template Name: Delivery
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
			<h6 class="app__table__heading app__table__heading--stage">Collection</h6>
			<h6 class="app__table__heading app__table__heading--stage">Company Van</h6>
			<h6 class="app__table__heading app__table__heading--stage">Courier</h6>
		</div>
		<div class="searchContainer">
			<?php 
			$args = array( 
				'post_type' => 'projects',
				'posts_per_page' => -1,
				'meta_key'			=> 'deadline',
				'orderby'			=> 'meta_value_num',
				'order'				=> 'ASC'
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
			?>
			<?php if (get_field('project_stage', $post->ID) == 'Delivery' ){ ?>
				<article class="app__table__row">
					<?php 
						//stage loop lol //
						include (plugin_dir_path( __FILE__ ) . 'item-loop-delivery.php'); 
					?>
				</article>
			<?php } ?>
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