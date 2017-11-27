<?php
/**
 * @package WooCommerce Tiered Shipping
 */

/*
 * Plugin Name: WooCommerce Tiered Shipping
 * Description: This WordPress plugin adds a tiered flat rate shipping option for the WooCommerce plugin.
 * Version: 3.0
 * Author: Joni Halabi
 * Author URI: http://www.thatdevgirl.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  require_once( 'inc/tiered-shipping-init.inc' );
  require_once( 'inc/tiered-shipping-add.inc' );
  require_once( 'inc/tiered-shipping-js.inc' );
} else {
  deactivate_plugins( plugin_basename( __FILE__ ) );
}
