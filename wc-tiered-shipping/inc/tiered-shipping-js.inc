<?php

function tiered_shipping_js( $hook ) {
  if ( 'woocommerce_page_wc-settings' != $hook ) {
    return;
  }

  wp_enqueue_script( 'tiered_shipping_script', plugin_dir_url( __FILE__ ) . '../js/scripts.js' );
}

add_action( 'admin_enqueue_scripts', 'tiered_shipping_js' );
