<?php

namespace ThreePM\WCTieredShipping;

class Activate {

  /**
   * __construct()
   */
  public function __construct() {
    add_action( 'admin_notices', [ $this, 'sunset' ] );
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
   * sunset()
   * 
   * Sunset notice that is displayed with the plugin is activated.
   * 
   * @return void
   */
  public function sunset(): void {
    $screen = get_current_screen();
    if ($screen && ( $screen->id == 'dashboard' || $screen->id == 'plugins') ) {
      print <<<HTML
        <div id="sunset" class="notice notice-warning is-dismissible">
          <p><strong>Important notice about the WooCommerce Tiered Shipping plugin:</strong></p>
          <p>
            Due to a shift in my personal and professional priorities, I have decided 
            to take a step back from development. As a result, this plugin is <strong>no longer 
            being actively maintained.</strong> You are welcome to 
            <a href="https://github.com/thatdevgirl/woocommerce-tiered-flat-rate" target="_blank">fork it</a>
            and create your own updates. If you do so, please credit me as the original author. 
            (I would also love to 
            <a href="mailto:joni@jhalabi.com">hear about this plugin’s new life</a>!)
          </p>
          <p>
            All the best, Joni. 
          </p>
        </div>
HTML;
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
      <div id="message" class="notice notice-error is-dismissible">
        <p>
          The WC Tiered Shipping plugin requires WooCommerce to be activated.
          Please activate WooCommerce and try again.
        </p>
      </div>
HTML;
  }

}

new Activate;
