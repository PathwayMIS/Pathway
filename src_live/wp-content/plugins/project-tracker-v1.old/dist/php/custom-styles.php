/* Variables
--------------------------*/

.brandColour {
	color:<?php the_field('brand_colour','option');?>;
}

.brandColourBG {
	background-color:<?php the_field('brand_colour','option');?>;
	color: white;
}

.brandCurrentPage {
	border-left: 2px solid <?php the_field('brand_colour','option');?>;
}

.brandCurrentPage a{
	color: <?php the_field('brand_colour','option');?>
}

.brandCurrentPage:before {
	color: <?php the_field('brand_colour','option');?>
}

.brandCurrent .acf-form-submit input {
 	background-color:<?php the_field('brand_colour','option');?>!important;
	color: white!important;
	
}

.brandCurrent .editTools__delete, .brandCurrent .editTools__edit {
 	background-color:<?php the_field('brand_colour','option');?>;
 	color: white;
}

.brandCurrent .filterControls__search {
	color: <?php the_field('brand_colour','option');?>;
}