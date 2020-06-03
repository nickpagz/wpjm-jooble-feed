<?php 

/*
Plugin Name: 	WPJM Jooble Feed
Description: 	Creates a custom rss/xml feed for WP Job Manager compatible with Jooble feed requirements.
Version: 1.0
Author:	Nick Pagazani
Author URI:	https://nickpagazani.com
License:	GPL v2 or later
*/

function jooble_rss_feed(){
	header( 'Content-Type: application/rss+xml; charset=' . get_option( 'blog_charset' ), true );
	// header( 'Content-Type: text/html' );
  	include_once( dirname( __FILE__ ) . '/rss-jooble.php' );
  	// add_filter('pre_option_rss_use_excerpt', '__return_zero');
  	// load_template( PATHTEMPLATEFILE . '/rss-jooble1.php' );
  	// get_template_part('rss', 'jooble');
}

/* This code initializes the custom Jooble RSS Feed*/
add_action( 'init', 'JoobleCustomRSS' );
function JoobleCustomRSS(){
   add_feed( 'jooble', 'jooble_rss_feed' );
}



function jooble_feed_type( $content_type, $type ) {
        if ( 'jooble' === $type ) {
                return feed_content_type( 'rss2' );
        }

        return $content_type;
}

add_filter( 'feed_content_type', 'jooble_feed_type', 10, 2 );
