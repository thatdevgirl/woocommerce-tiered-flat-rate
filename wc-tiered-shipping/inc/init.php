<?php

/**
 * Class to initialize this new shipping method and set up its settings.
 */

function wp_tiered_shipping_init() {

  class WC_Tiered_Shipping extends WC_Shipping_Method {

    /**
     * __construct() {
     */
    public function __construct() {
      $this->id           = 'tiered_shipping';
      $this->title        = __( 'Tiered Flat Rate Shipping', $this->id );
      $this->method_title = __( 'Tiered flat rate shipping', $this->id );
      $this->description  = __( 'A flat rate shipping method that adjusts depending on the number of products purchased.', $this->id );
      $this->init();
    }


    /**
     * init()
     *
     * Initialize all settings for this shipping method.
     *
     * @return void
     */
    public function init(): void {
      // Load the settings API
      $this->init_form_fields(); // Overridden below.
      $this->init_settings();    // This is part of the settings API. Loads settings you previously init.

      // Save settings.
      add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
    }


    /**
     * init_form_fields()
     *
     * Initialize settings form fields. This is part of the settings API, but
     * overridden here.
     *
     * @return void
     */
    public function init_form_fields() {
      global $woocommerce;

      $this->form_fields = [
        'enabled' => [
          'title' => __( 'Enabled/Disabled', $this->id ),
          'type'  => 'checkbox',
          'label' => 'Enable this shipping method'
        ],

        'usertitle' => [
          'title'       => __( 'Shipping method label', $this->id ),
          'type'        => 'text',
          'description' => __( 'The label that is visible to the user.', $this->id ),
          'default'     => __( 'Tiered Flat Rate', $this->id )
        ],

        'availability' => [
          'title'       => __( 'Availability', $this->id ),
          'type'        => 'select',
          'class'       => 'wc-enhanced-select availability',
          'options'     => [
            'all'      => 'All allowed countries',
            'except'   => 'All allowed countries, except...',
            'specific' => 'Specific countries'
          ],
          'default'     => __( 'all', $this->id )
        ],

        'countries' => [
          'title'       => __( 'Countries', $this->id ),
          'type'        => 'multiselect',
          'class'       => 'wc-enhanced-select',
          'options'     => $woocommerce->countries->countries,
          'default'     => __( '', $this->id )
        ],

        'basefee' => [
          'title' => __( 'Base shipping fee ($)', $this->id ),
          'type'  => 'text',
          'description' => __( 'Flat shipping fee that is applied automatically to the cart total for any number of items.', $this->id )
        ],

        'tierfee' => [
          'title'       => __( 'Tier shipping fee ($)', $this->id ),
          'type'        => 'text',
          'description' => __( 'Additional shipping fee added to the base fee if the number of items in the cart exceeds a specified number.', $this->id )
        ],

        'quantity' => [
          'title'       => __( 'Number of items to activate tier shipping fee', $this->id ),
          'type'        => 'text',
          'description' => __( 'Number of items in the cart needed to activate the additional tier shipping fee.', $this->id )
        ],

        'progressive' => [
          'title'       => __( 'Incremental fee?', $this->id ),
          'type'        => 'checkbox',
          'label'       => __( 'Make the tiered shipping fee incremental' ),
          'description' => __( 'If this option is checked, the tiered shipping fee will be applied incrementally in multiples of the tier item quantity; otherwise, the tiered shipping fee will be a flat fee if the cart is above the specified quantity.', $this->id )
        ]
      ];
    }


    /**
     * calculate_shipping()
     *
     * Calculate the shipping rate based on product type and number of items.
     *
     * @param array $package
     */
    public function calculate_shipping( $package = [] ) {
      // First, we need to do some checks to make sure that this shipping method
      // is enabled and this users country is included / allowed. If one of these
      // is not true, just return false.
      // Is this shipping type enabled? If not, do nothing.
      $enabled = $this->get_option( 'enabled' );

      if ( $enabled == 'no' ) { return false; }
      if ( ! $this->is_tiered_allowed( $package ) ) { return false; }


      // If we get to this point, we can add this shipping method to the front
      // end and calculate the shipping rate!

      global $woocommerce;

      // Get all items from cart.
      $items = $woocommerce->cart->get_cart();
      $cart_total_items = 0;

      // Sum non-virtual (i.e. shippable) items
      foreach ( $items as $item ) {
        $product = wc_get_product( $item['product_id'] );
        if ( !$product->is_virtual() ) {
          $cart_total_items += $item['quantity'];
        }
      }

      // Set the base shipping fee.
      $shipping = $cart_total_items > 0 ? $this->get_option('basefee') : 0;

      // Override base fee with tiered fee if cart items are over the tier quantity.
      if ( $cart_total_items > $this->get_option( 'quantity' ) ) {

        // If the tier fee should be progressive, calculate the multiplier and add the tier fee * multiplier.
        if ( $this->get_option( 'progressive' ) == 'yes' ) {
          $multiplier = ceil( $cart_total_items / $this->get_option( 'quantity' ) ) - 1;
          $shipping += $this->get_option( 'tierfee' ) * $multiplier;
        }

        // If the tier fee is flat, simply add the tier fee.
        else {
          $shipping += $this->get_option( 'tierfee' );
        }
      }

      // Set the shipping rate.
      $rate = [
        'label'    => $this->title,
        'cost'     => $shipping,
        'calc_tax' => 'per_item'
      ];

      $this->add_rate( $rate );
    }


    /**
     * is_tiered_allowed()
     *
     * Determine if the tiered rate is allowed for this location.
     *
     * @param array $package
     */
    function is_tiered_allowed( $package = [] ) {
      $availability = $this->get_option( 'availability' );
      $user_country = $package['destination']['country'];
      $countries = $this->get_option( 'countries' );

      switch ( $availability ) {
        // Plugin availability is set to all countries.
        case 'all':
          return true;
          break;

        case 'specific':
          $in_allowed_country = false;

          for ( $i=0; $i<sizeof( $countries ); $i++ ) {
            if ( $user_country == $countries[$i] ) {
              $in_allowed_country = true;
              break;
            }
          }

          return $in_allowed_country;
          break;

        case 'except':
          $in_allowed_country = true;

          for ( $i=0; $i<sizeof( $countries ); $i++ ) {
            if ( $user_country == $countries[$i] ) {
              $in_allowed_country = false;
              break;
            }
          }

          return $in_allowed_country;
        }
    }

  }
}


add_action( 'woocommerce_shipping_init', 'wp_tiered_shipping_init' );
