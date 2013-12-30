=== WP LinkedIn/BuddyStream integration ===
Author: Claude Vedovini
Contributors: cvedovini
Donate link: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin-buddystream
Tags: linkedin,resume,recommendations,profile,buddystream,buddypress
Requires at least: 2.7
Tested up to: 3.8
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html


== Description ==

This plugin integrates the WP-LinkedIn and BuddyStream plugins and enables showing more than one profile per install.


== Installation ==

This plugin follows the [standard WordPress installation method](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins):

1. Upload the `wp-linkedin-buddystream` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= Version 1.0.2 =
- Fixing call to `get_cache` in `WPLinkedInBuddystreamConnection` class.

= Version 1.0.1 =
- Fixing methods visibility issues in class WPLinkedInBuddystreamConnection
after incompatible change in WP-LinkedIn

= Version 1.0 =
- Initial release.
