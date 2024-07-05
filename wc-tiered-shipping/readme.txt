=== WC Tiered Shipping ===
Contributors: thatdevgirl
Tags: woocommerce, shipping, flat rate
Donate Link: https://www.buymeacoffee.com/thatdevgirl
Requires at least: 3.0
Tested up to: 6.6
Requires PHP: 7.0
Stable tag: 3.2.0

This WordPress plugin adds a tiered flat rate shipping option for the WooCommerce plugin.

== Description ==

WordPress administrative users can create a tiered flat rate shipping fee based on the total number of items in a WooCommerce cart. The store administrator can choose to apply this shipping method to all allowed countries that the store may ship to or only specific countries.

There are two tiers in this shipping method:

* **Base shipping fee:** This is the base (default) flat shipping fee that is applied automatically to the cart total for any number of items.

* **Additional shipping fee for tiers:** This is the additional shipping fee that is added to the base fee if the number of items in the user's cart exceeds a specified number.  This tiered fee can either be a flat fee, meaning that it is applied to carts of any size above the specified tier quantity, or a progressive fee, meaning that the tier quantity is used as a multiplier to the tiered fee.

== Installation ==

1. Upload the plugin to your WordPress installation and activate the plugin.

2. In the WordPress admin, go to "WooCommerce" -> "Settings", then click on the "Shipping" tab.

3. At the top of the Shipping settings page, click on "Tiered flat rate shipping".

4. Enable this shipping option and enter your shipping settings.

== Frequently Asked Questions ==

= I updated to 3.1 and the plugin is not working quite right. What's going on? =

I have noticed some weirdness after making the necessary maintenance changes to make the plugin work with WordPress 5.*. Please go to the settings page for the shipping method and click "Save Changes". You do not need to actually change anything. Just re-saving the settings seems to clear things up.

= What version of the plugin should I use if I am still on WordPress 4.*? =

You probably want to stay on plugin version 3.0 in this case. However, I strongly recommend staying current with WordPress core and upgrading to 5.*.

== Screenshots ==

1. Screenshot of "Tiered Shipping" setup as an enabled shipping method in WooCommerce Settings.  This can be found by going to WooCommerce > Settings > Shipping in the WP admin.

== Changelog ==

= 3.2 =
* Tested plugin compatibility with WP core 6.5.
* Added WooCommerce as a required plugin (a new feature as of WP core 6.5).

= 3.1 =
* Tested plugin on WP core 5.7.
* [FIX] Make sure that WooCommerce is activated before activating this plugin. Or, if WooCommerce is deactivated and this plugin is still activated, auto-deactivate it, because this plugin does not work without WooCommerce.
* [FIX] Actually add the shipping method to WooCommerce. Apparently this stopped working with some update to either WooCommerce or WordPress. Sorry, folks, that it's been a while since I've updated this plugin!
* [ENHANCEMENT] Minified plugin admin Javascript for performance improvements.

= 3.0 =
* Tested plugin on v4.9.
* Updated code style to better adhere to WordPress code standards.
* [NEW FEATURE!] Added functionality to toggle the settings form based on whether the shipping method is enabled.
* [NEW FEATURE!] Plugin automatically deactivates if the WooCommerce plugin is deactivated.
* [TWEAK] Updating ordering and language of the admin form.

= 2.6 =
* [FIX] No longer count virtual products when calculating shipping fee. (Thank you idpaterson!)
* [FIX] Do not charge base fee if there are no items to ship. (Thank you idpaterson!)
* [NEW FEATURE!] Allow this shipping rate for all countries except specific ones.

= 2.5.1 =
* Updates to readme file documentation; adding donate link.

= 2.5 =
* [NEW FEATURE!] Adding new setting that allows admin to select if this shipping method is available in all countries or just selected ones.
* [TWEAK] Resetting the stable tag (now that I have started using it) to make more sense.
* [TWEAK] Fleshing out installation instructions in this readme file.

= 2.4.6 =
* Updating license and tested up to information in readme file.
* Adding screenshots.

= 2.4.5 =
* Actually fixing the plugin setting issue.

= 2.4 =
* Fixing issue where plugin settings are saved but not applied.  Please note that after changing your settings now, you may need to clear your current cache and/or user session.

= 2.3 =
* Updating readme file to include "tested up to" and other additional information.

= 2.2 =
* Fixing typos and documentation.

= 2.1 =
* Updated the language around the progressive (incremental) fee option for clarity.

= 2.0 =
* Updated the package name.
* Added the progressive fee feature, which gives users the option to apply the tiered shipping fee as either a flat or progressive fee.
* Updated the tiered shipping fee so that it is added to the base fee instead of replacing it.
* Updated documentation and enhanced admin settings labels and descriptions.

= 1.0 =
* Initial release.
