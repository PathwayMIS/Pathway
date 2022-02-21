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
    padding-left: 9% !important;
    border-left: 2px solid <?php the_field('brand_colour','option');?>;
}

.brandCurrentPage a{
    color: <?php the_field('brand_colour','option');?>;
    font-size: 15px;
}

.brandCurrentPage:before {
    margin-right: 21px !important;
    color: <?php the_field('brand_colour','option');?>;
    font-size: 15px;
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

.brandCurrent .filterControls__sort {
	color: <?php the_field('brand_colour','option');?>;
}

.brandCurrent .filterControls__sort span[data-selected="true"] {
	background: <?php the_field('brand_colour','option');?>;
}