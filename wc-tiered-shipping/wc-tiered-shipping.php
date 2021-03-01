<?php

/*
 * Plugin Name: WooCommerce Tiered Shipping
 * Description: This WordPress plugin adds a tiered flat rate shipping option for the WooCommerce plugin.
 * Version: 3.1
 * Author: Joni Halabi
 * Author URI: https://jhalabi.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

require_once( 'inc/activate.php' );

// if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
//   require_once( 'inc/tiered-shipping-init.inc' );
//   require_once( 'inc/tiered-shipping-add.inc' );
//   require_once( 'inc/tiered-shipping-js.inc' );
// } else {
//   deactivate_plugins( plugin_basename( __FILE__ ) );
// }
