<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPJM_Jooble_Feed_Output {

	public static function generate_feed() {
		add_filter( 'feed_content_type', array( 'WPJM_Jooble_Feed_Output', 'jooble_feed_type' ), 10, 2 );
		add_action( 'pre_get_posts', array( 'WPJM_Jooble_Feed_Output', 'jooble_jobs_feed' ) );
		add_action( 'init', array( 'WPJM_Jooble_Feed_Output', 'jooble_custom_rss' ) );
	}

	public static function jooble_feed_type( $content_type, $type ) {
		if ( 'jooble' === $type ) {
			return feed_content_type( 'rss2' );
		}
		return $content_type;
	}

	public static function jooble_custom_rss() {
		$response = add_feed( 'jooble', array( 'WPJM_Jooble_Feed_Output', 'jooble_feed_output' ) );
		return $response;
	}

	public static function jooble_jobs_feed( $query ) {
		if ( is_feed( 'jooble' ) && $query->is_main_query() ) {
			$query->set( 'post_type', 'job_listing' );
			$query->set( 'posts_per_rss', get_option( 'jooble_feed_item_count' ) );
		}
	}

	public static function jooble_feed_output() {
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

			<job id="<?php echo esc_attr( get_the_ID() ); ?>">
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