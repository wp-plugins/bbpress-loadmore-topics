=== bbpress-loadmore-topics ===
Contributors: ckchaudhary 
Donate link: http://webdeveloperswall.com/contact-us
Tags: bbpress,bbpress load more, bbpress loadmore
Requires at least: 3.5
Tested up to: 4.1 
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Load more topics with ajax

== Description ==

Add a 'load-more' button in topics archive. On click of it, new topics are appended at the end of topics list.

The plugin has no setting, check the 'Installation' screen for more information.

== Installation ==

Install the plugin like you'd [install any wordpress plugin](http://codex.wordpress.org/Managing_Plugins).

The plugin will automatically add a loadmore button at the bottom of topics list.

+++++++++++++++++


**Important:-** If you are updating from old version of this plugin, chances are you might get 2 'load more' buttons.
In that case:

1. You can either remove the function call from your template file(which you'd have done earlier)

2. Or, you can put the following code in your functions.php

*`remove_action( "bbp_template_after_topics_loop", "bbpresslmt_loadmore_button" );`*

+++++++++++++++++


In case, 'load more' button is not added automatically, you can put following code in bbpress template file called loop-topics.php, 

*`<?php if( function_exists( 'bbpresslmt_loadmore_button' ) ): ?>
<?php bbpresslmt_loadmore_button(); ?>
<?php endif; ?>`*

preferably inside `<li class="bbp-body">` or `<li class="bbp-footer">`

And of course, you should copy template files into your theme/child theme and then make changes. [Instructions](http://codex.bbpress.org/amending-bbpress-templates/).

+++++++++++++++++++


If you want to add the button in a different place:

1. Remove original button by adding following code in your theme's functions.php file: 
*`remove_action( "bbp_template_after_topics_loop", "bbpresslmt_loadmore_button" );`*

2. Add following code where you want the button to appear: 
*`<?php if( function_exists( 'bbpresslmt_loadmore_button' ) ): ?>
<?php bbpresslmt_loadmore_button(); ?>
<?php endif; ?>`*


== Frequently Asked Questions ==
= Activated the plugin, but i don't see any load more link. =
you can put following code in bbpress template file called loop-topics.php, 

*`<?php if( function_exists( 'bbpresslmt_loadmore_button' ) ): ?>
<?php bbpresslmt_loadmore_button(); ?>
<?php endif; ?>`*

preferably inside `<li class="bbp-body">` or `<li class="bbp-footer">`

And of course, you should copy template files into your theme/child theme and then make changes. [Instructions](http://codex.bbpress.org/amending-bbpress-templates/).

== Changelog ==
**1.1**

* Automatically display load more button at proper location.
* Added security in ajax.
* Small miscellaneous improvements.

**1.0**

Initial release