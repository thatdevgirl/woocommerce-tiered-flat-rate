<?php

namespace ThreePM\WCTieredShipping;

class Assets {

  /**
   * __construct()
   */
  public function __construct() {
    add_action( 'admin_enqueue_scripts', [ $this, 'add_js' ] );
  }


  /**
   * add_js()
   *
   * Enqueue the JS to the WooCommerce Settings page.
   *
   * @return void
   */
  public function add_js( $hook ): void {
    if ( 'woocommerce_page_wc-settings' != $hook ) {
      return;
    }

    $handle = 'wc_tiered_shipping';
    $js = '../build/wc-tiered-shipping-scripts.min.js';

    wp_enqueue_script(
      $handle,
      plugins_url( $js, __FILE__ ),
      [ 'jquery' ],
      filemtime( plugin_dir_path( __FILE__ ) . $js )
    );
  }

}

new Assets;
