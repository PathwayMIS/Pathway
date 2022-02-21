<h6 class="app__table__row__item" data-heading="Company"><?php echo get_field('client'); ?></h6>
<h6 class="app__table__row__item" data-heading="Reference"><?php echo get_field('reference'); ?></h6>
<h6 class="app__table__row__item" data-heading="Order #"><?php echo get_field('order_number'); ?></h6>
<h6 class="app__table__row__item" data-heading="Deadline"><?php echo get_field('deadline'); ?></h6>
<h6 class="app__table__row__item app__table__row__item--desktop" data-heading="Order Form">
	<i class="fa fa-lg fa-qrcode js-notes-open <?php if ( get_field('order_form') ) { echo 'brandColour'; } else { } ?>"></i>
	<div class="app__table__row__item--notes">
		<div class="app__table__row__item--notes--close js-notes-close"><i class="fa fa-close"></i></div>
		<h3 class="brandColour">Order Form</h3>
		<h5><?php echo get_field('client'); ?> — <?php echo get_field('reference'); ?></h5>
		<?php $options = array(
				'fields' => array('field_5786492a95fe2'),
		);
		acf_form($options); ?>
		<?php if ( get_field('order_form') ) { ?>
			<a href="<?php echo get_field('order_form'); ?>" target="_blank"><i class="fa fa-lg fa-qrcode brandColour"></i> Open link in new tab</a>
		<?php } ?>
	</div>
</h6>
<h6 class="app__table__row__item app__table__row__item--desktop" data-heading="Notes">
	<i class="fa fa-lg fa-edit js-notes-open <?php if ( get_field('notes') ) { echo 'brandColour'; } else { } ?>"></i>
	<div class="app__table__row__item--notes">
	<div class="app__table__row__item--notes--close js-notes-close"><i class="fa fa-close"></i></div>
		<h3 class="brandColour">Notes</h3>
		<h5><?php echo get_field('client'); ?> — <?php echo get_field('reference'); ?></h5>
		<?php $options = array(
				'fields' => array('field_5786488d95fe1'),
		);
		acf_form($options); ?>
	</div>
</h6>
<h6 class="app__table__row__item  app__table__row__item--desktop" data-heading="Artwork">
	<i class="fa fa-lg fa-external-link js-notes-open <?php if ( have_rows('artwork_links') ) { echo 'brandColour'; } else { } ?>"></i>
	<div class="app__table__row__item--notes">
	<div class="app__table__row__item--notes--close js-notes-close"><i class="fa fa-close"></i></div>
		<h3 class="brandColour">Artwork Links</h3>
		<h5><?php echo get_field('client'); ?> — <?php echo get_field('reference'); ?></h5>
		<h6 class="app__table__row__item--notes--btn brandColour">View/Add links</h6>
		<div class="app__table__row__item--notes__add">
			<?php $options = array(
					'fields' => array('field_57f23ba1c8ed6'),
			);
			acf_form($options); ?>
		</div>
		<?php if( have_rows('artwork_links') ) : ?>
			<?php while( have_rows('artwork_links') ) : the_row(); ?>
				<a href="<?php echo get_sub_field('artwork'); ?>" target="_blank"><i class="fa fa-lg fa-external-link brandColour"></i> <?php echo get_sub_field('link_title'); ?></a>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</h6>
<h6 class="app__table__row__item" data-heading="Stage"><?php echo get_field('project_stage'); ?></h6>
<h6 class="app__table__row__item" data-heading="Sub Stage">
<?php 
	if ( get_field('project_stage', $post->ID ) ) {
		$stage = get_field( 'project_stage', $post->ID ); //gets the overall stage
		$stage_object = str_replace( ' ', '_', $stage ) . '_stage'; //joins the overall and secondary stage together
		$stage_object = strtolower( $stage_object );
	}

	$field = get_field_object($stage_object);
	$value = $field['value'];
	$label = $field['choices'][ $value ];
	echo $label; 
?>
</h6>
