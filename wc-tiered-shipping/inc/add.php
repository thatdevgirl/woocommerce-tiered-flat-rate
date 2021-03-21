<?php

/**
 * Function to add this new shipping method to WooCommerce.
 */

function wp_tiered_shipping_add( $methods ) {
  $methods[ 'tiered_shipping' ] = 'WC_Tiered_Shipping';
  return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'wp_tiered_shipping_add' );
