=== Ajax Scroll ===
Contributors: bankofcanada, ncrawford
Tags: presentation, formatting, display, style, text, links, ajax, scroll
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 0.42

Turns standard 'next' and 'previous' post links into animated AJAX scrolling.

== Description ==

AJAX Scroll links an AJAX-enabled page scroller to the standard next and previous post links.  

Clicking on a next or previous link will cause the current page to fade and slide out; the following page will slide and fade in. 

== Installation ==

1. Download the zip file, unzip it, and place it in your /wp-content/plugins directory.  
2. Activate the plugin via the 'Plugins' menu in WordPress.  
3. In your theme, before calling the "previous_posts_link" or "next_posts_link" functions, declare a global variable "as_elem_id" and store the ID of the content container.  (In the default WordPress theme, this is "content".)

For example, 

$as_elem_id = 'some_value';

where <some_value> is the element ID of the post container where the contents will be replaced.

== Frequently Asked Questions ==

= How can I change the speed of the slide or the opacity? =

At present this requires modifying the plugin's JavaScript file.  Open the file located in wp-content/plugins/ajax-scroll/js/ajax-scroll-js.php.

The first two lines of this file declare two variables, "speed" and "opacity_loading".  You can modify these values to change the slide timing and fade opacity.

The "speed" value is the time (in ms) it takes to slide the container. So, the higher the number, the slower the animation.

The "opacity" value is a decimal representation of the opacity percentage (from 0 to 1).  The lower the value, the more transparent the content will be while loading.  A value of 1 equals no fading.

= How do I add a loading icon? =

In your theme, place a (div or other) element where you want the loading icon to appear.  The ID of this element must be "<id of container>_load".  So, if the ID of your content's container is "content", the loading DIV must have an ID "content_load".  Once this is in place, the loading element will automatically appear during the transition animation.

= Can I have multiple sections of scrollable content? =

Yes!  Just replicate the instructions in "installation" for each section on the page.  Make sure that each content container has a unique ID!

= The direction of the scroll is wrong. Why? =

Scrolling through any set of single posts should work fine. However, if your paging system is unique (not standard), the direction of the scroll may be wrong.  In this case, you can either modify ajax-scroll.php to fit your needs or reverse the directions in js/ajax-scroll-js.php.


== Changelog ==
= 0.42 =
* Will now identify post scrolling vs page scrolling in a better way to make the direction of scrolling decision better
* Post scrolling will now work with all permalink methods
= 0.41 =
* The method in which the next/previous page contents are grabbed has changed to improve speed and circumvent login problems. jQuery is now used to grab and display the next page rather than the PHP DOM.
* Also fixed a small bug of a missing function and hooks in the change between 0.31 and 0.4
= 0.4 =
* Undid the 0.31 change because it can cause other problems.  If accented letters aren't appearing properly for you, see the FAQ.
* Changed the way the plugin is loaded. Instead of using admin-ajax.php, the plugin is called directly. This allows the wp-admin folder to be password protected without causing problems with the plugin.
* Reduced the priority of the link hooks because they were sometimes being executed too soon.
* Fixed a bug where the direction of the animation slide would sometimes be wrong.
= 0.31 =
* Fixed a bug where accented letters were being displayed incorrectly - thanks to 100tral for pointing this out!
= 0.3 =
* Initial public version