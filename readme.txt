=== bbpress-loadmore-topics ===
Contributors: ckchaudhary 
Tags: bbpress,bbpress load more, bbpress loadmore
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.0
Version: 1.0
License:GPLv2 or later

load more topics with ajax

== Description ==

Add a 'load-more' button in topics archive. On click of it, new topics are appended at the end of topics list.

Integrates with your theme.

 

The plugin has no setting, check the 'Installation' screen for more information.

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

 

Once you have the plugin activated, call this function:

&lt;?php if( function_exists( 'bbpresslmt_loadmore_button' ) ): ?&gt;

&lt;?php bbpresslmt_loadmore_button(); ?&gt;

&lt;?php endif; ?&gt;

in bbpress template file called loop-topics.php, preferably inside &lt;li class="bbp-body"&gt; or &lt;li class="bbp-footer"&gt;

And you are done!
== Frequently Asked Questions ==

Nothing yet
== Changelog ==
**1.0**

Initial release