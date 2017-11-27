# WC Tiered Shipping

__Author:__ Joni Halabi (www.thatdevgirl.com)
__License:__ GPLv2 or later (http://www.gnu.org/licenses/gpl-2.0.html)

## Description

This WordPress plugin adds a tiered flat rate shipping option for the WooCommerce plugin.

## What is in the plugin?

WordPress administrative users can create a tiered flat rate shipping fee based on the total number of items in a WooCommerce cart. The store administrator can choose to apply this shipping method to all available countries that the store may ship to, or only specific countries.

There are two tiers in this shipping method:

__Base fee:__ This is the base (default) flat shipping fee that is applied automatically to the cart total for any number of items.

__Tiered fee:__ This is the additional shipping fee that is added to the base fee if the number of items in the user's cart exceeds a specified number.  This tiered fee can either be a flat fee, meaning that it is applied to carts of any size above the specified tier quantity, or a progressive fee, meaning that the tier quantity is used as a multiplier to the tiered fee.

## Virtual products

Products that cannot be shipped, such as digital downloads or other intangible goods, should be marked as virtual in WooCommerce. Virtual products are ignored when calculating the shipping fee.
