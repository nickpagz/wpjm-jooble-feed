<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPJM_Jooble_Feed_Settings {

	public function init() {

		// Initialize the custom Jooble RSS Feed settings.
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'init', array( $this, 'languages' ) );
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
				'default'           => 35,
			)
		);
	}

	public function sanitize_count_setting( $input ) {
		$input = intval( $input );
		if ( $input < 1 || $input > 3000 ) {
			add_settings_error( 'jooble_feed_item_count', 'jooble_feed_settings_error', __( 'Please enter a value between 1 and 3000.', 'wpjmjooblefeed' ) );
			return get_option( 'jooble_feed_item_count' ); // would this be empty on first time activation?
		}
		return $input;
	}

	public function count_setting_html() {
		?>
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
			<?php
				$feed_url = home_url( '/feed/jooble' );
				echo '<p>' . esc_html__( 'Feed URL:', 'wpjmjooblefeed' ) . ' <a href="' . esc_url( $feed_url ) . '">' . esc_html( $feed_url ) . '</a></p>';
			?>
		</div>
		<?php
	}

	public function languages() {
		load_plugin_textdomain( 'wpjmjooblefeed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

// Instantiate our class
$jooble_feed = new WPJM_Jooble_Feed_Settings( __FILE__ );
$jooble_feed->init();
require_once plugin_dir_path( __FILE__ ) . 'class-wpjm-jooble-feed-output.php';
WPJM_Jooble_Feed_Output::generate_feed();
