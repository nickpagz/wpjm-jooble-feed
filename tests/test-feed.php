<?php
/**
 * Class FeedTest
 *
 * @package Wpjm_Jooble_Feed
 */

class FeedTest extends WP_UnitTestCase {

	public $job_listing;
	public $jooble_feed;
	protected $factory;

	public function __construct() {
		parent::__construct();
		require_once dirname( __FILE__ ) . '/includes/factories/class-wpjm-factory.php';
		require_once dirname( __FILE__ ) . '/includes/factories/class-wp-unittest-factory-for-job-listing.php';
		$this->job_listing = new WP_UnitTest_Factory_For_Job_Listing( $this );
	}

	public function set_up() {
		parent::set_up();
		add_option( 'jooble_feed_item_count', 35, '', 'no' );
		$this->factory = self::factory();
		$this->jooble_feed = new WPJM_Jooble_Feed();
	}

	public function tear_down() {
		parent::tear_down();
		delete_option( 'jooble_feed_item_count' );
	}

	protected static function factory() {
		static $factory = null;
		if ( ! $factory ) {
			$factory = new WPJM_Factory();
		}
		return $factory;
	}

	public function test_jooble_feed_type_returns_rss2() {
		$expected = 'application/rss+xml';
		$actual = WPJM_Jooble_Feed_Output::jooble_feed_type( '', 'jooble' );
		$this->assertEquals( $expected, $actual );
	}

	public function test_add_jooble_feed_returns_feed_name() {
		$expected = 'do_feed_jooble';
		$actual = WPJM_Jooble_Feed_Output::jooble_custom_rss();
		$this->assertEquals( $expected, $actual );
	}

	public function test_default_feed_type_returns_default() {
		$expected = 'something';
		$actual = WPJM_Jooble_Feed_Output::jooble_feed_type( 'something', 'not something' );
		$this->assertEquals( $expected, $actual );
	}

	public function test_default_feed_feed_count_setting() {
		$expected = 35;
		$actual = get_option( 'jooble_feed_item_count' );
		$this->assertSame( $expected, $actual );
	}

	public function test_sanitize_count_setting_returns_valid_setting_on_valid_value() {
		$expected = 12;
		$actual = $this->jooble_feed->sanitize_count_setting( 12 );
		$this->assertSame( $expected, $actual );
	}

	public function test_sanitize_count_setting_returns_default_value_on_invalid_entry() {
		$expected = 35;
		$actual_string = $this->jooble_feed->sanitize_count_setting( 'adc' );
		$actual_int = $this->jooble_feed->sanitize_count_setting( 300 );
		$this->assertEquals( $expected, $actual_string );
		$this->assertEquals( $expected, $actual_int );
	}
}
