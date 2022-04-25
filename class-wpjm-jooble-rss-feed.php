<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPJM_Jooble_RSS_Feed {
	public function __construct() {

		// Initialize the custom Jooble RSS Feed
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'init', array( $this, 'jooble_custom_rss' ) );
		add_filter( 'feed_content_type', array( $this, 'jooble_feed_type' ), 10, 2 );
		add_action( 'pre_get_posts', array( $this, 'jooble_jobs_feed' ) );
		add_action( 'init', array( $this, 'languages' ) );
	}

	public function jooble_feed_type( $content_type, $type ) {
		if ( 'jooble' === $type ) {
			return feed_content_type( 'rss2' );
		}
		return $content_type;
	}

	public function jooble_custom_rss() {
		add_feed( 'jooble', array( $this, 'jooble_rss_feed' ) );
	}

	public function jooble_rss_feed() {
		$this->jooble_feed_output();
	}

	public function jooble_jobs_feed( $query ) {
		if ( is_feed( 'jooble' ) && $query->is_main_query() ) {
			$query->set( 'post_type', 'job_listing' );
			$query->set( 'posts_per_rss', get_option( 'jooble_feed_item_count' ) );
		}
	}

	public function admin_page() {
		add_options_page( 'Jooble Feed Settings', __( 'Jooble RSS Feed', 'wpjmjooblefeed' ), 'manage_options', 'wpjm-jooble-feed-settings-page', array( $this, 'settings_page_html' ) );
	}

	public function settings() {
		add_settings_section(
			'wpjm_jooble_feed_section',
			null,
			null,
			'wpjm-jooble-feed-settings-page',
		);
		add_settings_field(
			'jooble_feed_item_count',
			__( 'No. of Jobs to include in feed', 'wpjmjooblefeed' ),
			array(
				$this,
				'count_setting_html',
			),
			'wpjm-jooble-feed-settings-page',
			'wpjm_jooble_feed_section',
		);
		register_setting(
			'wpjm_jooble_feed_plugin',
			'jooble_feed_item_count',
			array(
				'sanitize_callback' => array( $this, 'sanitize_count_setting' ),
				'default'           => '35',
			)
		);
	}

	public function sanitize_count_setting( $input ) {
		if ( $input < 1 || $input > 250 ) {
			add_settings_error( 'jooble_feed_item_count', 'jooble_feed_settings_error', __( 'Please enter a value between 1 and 250.', 'wpjmjooblefeed' ) );
			return get_option( 'jooble_feed_item_count' );
		}
		return $input;
	}

	public function count_setting_html() { ?>
		<input type="number" name="jooble_feed_item_count" value="<?php echo esc_attr( get_option( 'jooble_feed_item_count' ) ); ?>">
		<?php
	}

	public function settings_page_html() {
		?>
		<div class="wrap">
			<h1><?php esc_attr_e( 'WPJM Jooble RSS Feed Settings', 'wpjmjooblefeed' ); ?></h1>
			<form action="options.php" method="POST">
				<?php
					settings_fields( 'wpjm_jooble_feed_plugin' );
					do_settings_sections( 'wpjm-jooble-feed-settings-page' );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function languages() {
		load_plugin_textdomain( 'wpjmjooblefeed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	//---------------------------------
	// Add salary field
	// Add hook to output a different feed, and one to override the number of items

	public function jooble_feed_output() {
		header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
		echo '<?xml version="1.0" encoding="' . esc_attr( get_option( 'blog_charset' ) ) . '"?' . '>';
		?>

		<rss version="2.0"
			xmlns:content="http://purl.org/rss/1.0/modules/content/"
			xmlns:wfw="http://wellformedweb.org/CommentAPI/"
			xmlns:dc="http://purl.org/dc/elements/1.1/"
			xmlns:atom="http://www.w3.org/2005/Atom"
			xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
			xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
			<?php do_action( 'rss2_ns' ); ?>
		>
		<jobs>

			<?php
			while ( have_posts() ) {
				the_post();
				?>

			<job id="<?php echo get_the_ID(); ?>">
				<link><![CDATA[<?php the_permalink_rss(); ?>]]></link>
				<name><![CDATA[<?php the_title_rss(); ?>]]></name>
				<region><![CDATA[<?php echo esc_attr( get_the_job_location() ); ?>]]></region>

				<?php
				//place holder for <salary></salary> field
				?>

				<?php $content = get_the_content_feed( 'rss2' ); ?>
				<description><![CDATA[<?php echo esc_attr( $content ); ?>]]></description>

				<company><![CDATA[<?php echo esc_attr( get_the_company_name() ); ?>]]></company>

				<pubdate><?php echo esc_attr( mysql2date( 'd.m.Y', get_post_time( 'Y-m-d H:i:s', true ), false ) ); ?></pubdate>

				<updated><?php echo esc_attr( get_the_modified_date( 'd.m.Y' ) ); ?></updated>

				<?php
				$org_date = get_post_custom_values( '_job_expires' );
				$new_date = gmdate( 'd.m.Y', strtotime( $org_date[0] ) );
				?>
				<expire><?php echo esc_attr( $new_date ); ?></expire>

				<?php
				$job_types_names = implode( ', ', wp_list_pluck( wpjm_get_the_job_types(), 'name' ) );

				echo '<jobtype>' . esc_html( $job_types_names ) . "</jobtype>\n";
				?>

				<?php rss_enclosure(); ?>
				<?php do_action( 'rss2_item' ); ?>

			</job>
			<?php } ?>
		</jobs>

		</rss>

		<?php
		wp_reset_postdata();
	}
}

// Instantiate our class
new WPJM_Jooble_RSS_Feed( __FILE__ );

