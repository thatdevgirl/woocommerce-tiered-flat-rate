<?php

namespace ThreePM\WCTieredShipping;

class Activate {

  /**
   * __construct()
   */
  public function __construct() {
    add_action( 'admin_init', [ $this , 'activate' ] );
  }


  /**
   * activate()
   *
   * Activate this plugin only when WooCommerce is also activated.
   *
   * @return void
   */
  public function activate(): void {
    // Get the list of active plugins.
    $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

    // Check to see if WooCommerce is an active plugin.
    if ( ! in_array( 'woocommerce/woocommerce.php',  $active_plugins ) ) {
      // Do not activate this plugin.
      deactivate_plugins( 'wc-tiered-shipping/wc-tiered-shipping.php' );
      // Display an error.
      add_action( 'admin_notices', [ $this, 'error' ] );
      // Do not show the "this plugin has been activated" message... b/c it hasn't.
      unset( $_GET[ 'activate' ] );
    }
  }


  /**
   * error()
   *
   * Error notice that is displayed when the plugin cannot be activated.
   *
   * @return void
   */
  public function error(): void {
    print <<<HTML
      <div id="message" class="error">
        <p>
          The WC Tiered Shipping plugin requires WooCommerce to be activated.
          Please activate WooCommerce and try again.
        </p>
      </div>
HTML;
  }

}

new Activate;
