=== WC Tiered Shipping ===
Contributors: thatdevgirl
Tags: woocommerce, shipping
Donate Link: https://www.paypal.me/thatdevgirl
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: 2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This WordPress plugin adds a tiered flat rate shipping option for the WooCommerce plugin.

== Description ==

WordPress administrative users can create a tiered flat rate shipping fee based on the total number of items in a WooCommerce cart. The store administrator can choose to apply this shipping method to all available countries that the store may ship to, or only specific countries.

There are two tiers in this shipping method:

**Base shipping fee:** This is the base (default) flat shipping fee that is applied automatically to the cart total for any number of items.

**Additional shipping fee for tiers:** This is the additional shipping fee that is added to the base fee if the number of items in the user's cart exceeds a specified number.  This tiered fee can either be a flat fee, meaning that it is applied to carts of any size above the specified tier quantity, or a progressive fee, meaning that the tier quantity is used as a multiplier to the tiered fee.

== Installation ==

1. Upload the plugin to your WordPress installation and activate the plugin.

2. Go to the "Shipping" tab on the WooCommerce Settings page in the WP admin.

3. Click on "Tiered Shipping" in the "Shipping Methods" table to enable this shipping methond and update its settings.

== Screenshots ==

1. Screenshot of "Tiered Shipping" setup as an enabled shipping method in WooCommerce Settings.  This can be found by going to WooCommerce > Settings > Shipping in the WP admin.

2. Screenshot of the "Tiered Shipping" settings screen.

== Changelog ==

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