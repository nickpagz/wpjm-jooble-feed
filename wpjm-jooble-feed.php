<?php

/**
* Plugin Name: WPJM Jooble Feed
* Description: Creates a custom rss/xml feed for WP Job Manager compatible with Jooble feed requirements.
* Version: 2.0
* Author: Nick Pagazani
* Author URI: https://nickpagazani.com
* License: GPL v2 or later
* Text Domain: wpjmjooblefeed
* Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * Required functions
 */
require_once plugin_dir_path( __FILE__ ) . 'class-wpjm-jooble-rss-feed.php';
