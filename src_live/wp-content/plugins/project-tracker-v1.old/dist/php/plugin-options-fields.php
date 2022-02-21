<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
    'key' => 'group_57c6db510f535',
    'title' => 'Options Page',
    'fields' => array (
        array (
            'key' => 'field_57c6db61e5e04',
            'label' => 'Logo',
            'name' => 'logo',
            'type' => 'image',
            'instructions' => 'Upload your logo. Max height 50px.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
        ),
        array (
            'key' => 'field_57c6dba8e5e05',
            'label' => 'Brand Colour',
            'name' => 'brand_colour',
            'type' => 'color_picker',
            'instructions' => 'Choose the brand/accent colour to use throughout the application',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'branding-settings',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

endif;

?>