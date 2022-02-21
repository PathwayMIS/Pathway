<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

if( $_SERVER['HTTP_HOST'] == 'absolutecp.co.uk' || $_SERVER['HTTP_HOST'] == 'www.absolutecp.co.uk' ){
	if( $_SERVER['REQUEST_URI'] == '/' );
	include( 'index.html' );
	exit();
}

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
