=== Simple Upcoming Events ===
Contributors: hideaki
Tags: widget, Post, posts, plugin, sidebar, upcoming, events
Stable tag: trunk

Displays a list of posts for upcoming events. 
Event dates are specifed as "date" Custom Field.
Depends on NO external services like Google Calendar.

== Installation ==
1. Upload `simple-upcoming-events.php` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Add 'Simple Upcoming Events' widget through 'Appearance' > 'Widgets' menu.

== Usage ==
When you publish a new post, add a Custom Field named "date", and set its value to the date the event will take place (i.e. "2009-05-02"). That's it!

== Version History ==
1.0.1 - Removed usage of date_create, which was added in PHP 5.2, to support PHP 4.
