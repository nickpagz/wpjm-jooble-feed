<?php
/**
 * Class FeedTest
 *
 * @package Wpjm_Jooble_Feed
 */

class FeedTest extends WP_UnitTestCase {

	public function test_jooble_feed_type_returns_rss2() {
		$feed = new WPJM_Jooble_RSS_Feed();
		$expected = 'application/rss+xml';
		$actual = $feed->jooble_feed_type( '', 'jooble' );
		$this->assertEquals( $expected, $actual );
	}

	public function test_default_feed_type_returns_default() {
		$feed = new WPJM_Jooble_RSS_Feed();
		$expected = 'something';
		$actual = $feed->jooble_feed_type( 'something', 'whatever' );
		$this->assertEquals( $expected, $actual );
	}

	public function test_sanitize_count_setting_returns_valid_setting_on_valid_value() {
		$feed = new WPJM_Jooble_RSS_Feed();
		$expected = 12;
		$actual = $feed->sanitize_count_setting( 12 );
		$this->assertSame( $expected, $actual );
	}

	public function test_sanitize_count_setting_returns_default_value_on_invalid_entry() {
		$feed = new WPJM_Jooble_RSS_Feed();
		$expected = '';
		$actual_string = $feed->sanitize_count_setting( 'adc' );
		$actual_int = $feed->sanitize_count_setting( 300 );
		$this->assertEquals( $expected, $actual_string );
		$this->assertEquals( $expected, $actual_int );
	}

	
}
