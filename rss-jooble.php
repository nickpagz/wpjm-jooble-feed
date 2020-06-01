<?php 
/* 
Template Name: Article Feed
*/


$postCount = 100; // The number of posts to show in the feed
$postType = 'job_listing'; // post type to display in the feed

/**
*query_posts( array( 'post_type' => $postType, 'showposts' => $postCount ) );
*/

/**
*$numposts = 10; // number of posts in feed
*$posts = query_posts('showposts='.$numposts.'&cat=3');
*/

$posts = query_posts( array( 'post_type' => $postType, 'showposts' => $postCount ) );
$more = 1;

header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php do_action('rss2_ns'); ?>
>
<jobs>

	<?php while( have_posts()) : the_post(); ?>

	<job id="<?php echo get_the_ID(); ?>">
		<link><![CDATA[<?php the_permalink_rss(); ?>]]></link>
		<name><![CDATA[<?php the_title_rss(); ?>]]></name>
		<region><![CDATA[<?php echo get_the_job_location(); ?>]]></region>
		
		<?php $content = get_the_content_feed( 'rss2' ); ?>
		<description><![CDATA[<?php echo $content; ?>]]></description>
		
		<company><![CDATA[<?php echo get_the_company_name(); ?>]]></company>
		
		<pubdate><?php echo mysql2date('d.m.Y', get_post_time('Y-m-d H:i:s', true), false); ?></pubdate>
		
		<updated><?php echo get_the_modified_date('d.m.Y'); ?></updated>
		
		<?php $orgDate = get_post_custom_values( '_job_expires');
		$newDate = date("d.m.Y", strtotime($orgDate[0]));
		?>
		<expire><?php echo $newDate; ?></expire>
		
		<?php 
		$job_types_names = implode( ', ', wp_list_pluck( wpjm_get_the_job_types(), 'name' ) );
		
		echo '<jobtype>' . esc_html( $job_types_names ) . "</jobtype>\n";
		?>
		
<?php rss_enclosure(); ?>
<?php do_action('rss2_item'); ?>

	</job>
	<?php endwhile; ?>
</jobs>

</rss>