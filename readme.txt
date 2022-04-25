=== XML Feed for Jooble and WP Job Manager ===

Contributors: npagazani
Tags: WP Job Manager, XML Feed, Jooble
Requires at least: 4.7
Tested up to: 5.9.3
Requires PHP: 7.0
License: GPLv2+
Stable tag: 2.0

Creates a custom rss/xml feed for WP Job Manager compatible with Jooble feed requirements.

== Description ==
This plugin generates a custom xml job feed that is compatible with Jooble.org's xml feed requirements for listing jobs on their site. 
WP Job Manager's default job feed uses the standard WordPress RSS feed template which is not compatible with Jooble. This plugin solves that problem.
The plugin has only one setting - the number of jobs to show in the feed. The minimum value is 1, the maximum is 250. After installing and activating the plugin, you can access the Jooble feed at yourdomain.com/feed/jooble

Note: This plugin requires WP Job Manager to be installed and activated to work properly. 

== Installation ==
An activated install of the WP Job Manager plugin on your site is required for this plugin to work. You can download it here: https://wordpress.org/plugins/wp-job-manager/

No further setup is required, just install and activate the plugin under Plugins > Add New to activate your Jooble compatible job feed.

== Frequently Asked Questions ==

= Where are the plugin's settings? =
You'll find the only plugin setting in **Settings > Jooble RSS Feed**.

= How do I access the job feed? =
You can view the feed at yourdomain.com/feed/jooble
Make sure to replace "yourdomain.com" with your actual domain name.

= Why does my custom feed fail validation when using common online feed validators? =
Jooble's feed requirements do not use standard rss feed elements, so the custom feed will not pass on these validators.

= My feed doesn't work, I see a "Page not Found" error?
Go to Settings > Permalinks in your WordPress dashboard and click on "Save Permalinks".

== Changelog ==

= 2.0 =
* Use default query instead of "query_posts"
* Added settings to customize the number of jobs in feed
* Updated to meet WordPress Extra coding standards
* Added translation capability

= 1.0 =
* Initial plugin release.

== Upgrade Notice ==

= 2.0 =
More efficient code execution, fewer potential conflicts, adjustable size feed.