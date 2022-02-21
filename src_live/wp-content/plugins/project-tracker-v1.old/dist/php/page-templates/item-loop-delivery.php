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

<?php 
	if (get_field('project_stage', $post->ID)) {
		$stage = get_field('project_stage', $post->ID); //gets the overall stage
		$stage_object = str_replace(' ', '_', $stage) . '_stage'; //joins the overall and secondary stage together
		$stage_object = strtolower($stage_object); 

		if (get_field($stage_object, $post->ID)) {
			$stage_secondary = get_field($stage_object, $post->ID); //defines stage_secondary as the numerical value attached to each stage
		}
	} 
?>
<?php if ($stage_secondary == 1 ) { ?>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-check-circle brandColour"></i>
		</span>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-close"></i>
		</span>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-close"></i>
		</span>
<?php } elseif ($stage_secondary == 2 ) { ?>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-close"></i>
		</span>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-check-circle brandColour"></i>
		</span>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-close"></i>
		</span>
<?php } elseif ($stage_secondary == 3 ) { ?>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-close"></i>
		</span>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Stage">
			<i class="fa fa-close"></i>
		</span>
		<span class="app__table__row__item app__table__row__item--stage app__table__row__item--tablet" data-heading="Delivery">
			<i class="fa fa-lg fa-truck js-delivery-open brandColour"></i>
			<div class="app__table__row__item--delivery">
			<h3 class="brandColour">Delivery Details</h3>
			<h5><?php echo get_field('client'); ?> — <?php echo get_field('reference'); ?></h5>
			<div class="app__table__row__item--delivery--close js-delivery-close"><i class="fa fa-close"></i></div>
				<?php $options = array(
					'fields' => array(
						'field_578671d963e07',
						'field_5786721763e08'
						),
					);
				acf_form($options); ?>
			</div>
		</span>
<?php } ?>
	<i class="fa fa-pencil post-edit-link"></i>
<div class="editTools">
	<div class="editTools__stage">
		<?php $options = array(
			'fields' => array(
				'field_57867067bce90',
				'field_578671ae63e06'
				),
			);
		acf_form($options); ?>
	</div>
	<div class="editTools__container">
		<div class="editTools__delete">
			<?php if( !(get_post_status() == 'trash') ) : ?>
			    <a onclick="return confirm('Are you sure you wish to delete post: <?php echo get_the_title() ?>?')"href="<?php echo get_delete_post_link( get_the_ID() ); ?>"><i class="fa fa-trash-o"></i> Delete project</a>
			<?php endif; ?>
		</div>
		<div class="editTools__edit">
			<a href="<?php echo get_edit_post_link( get_the_ID() ); ?>"><i class="fa fa-file-text-o"></i> Edit project</a>
		</div>
	</div>
</div>