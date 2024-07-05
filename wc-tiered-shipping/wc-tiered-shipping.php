<?php

/*
 * Plugin Name: WooCommerce Tiered Shipping
 * Description: This WordPress plugin adds a tiered flat rate shipping option for the WooCommerce plugin.
 * Version: 3.2.0
 * Requires Plugins: woocommerce
 * Author: Joni Halabi
 * Author URI: https://jhalabi.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

require_once( 'inc/activate.php' );
require_once( 'inc/init.php' );
require_once( 'inc/add.php' );
require_once( 'inc/plugins-page.php' );
require_once( 'inc/set-assets.php' );
