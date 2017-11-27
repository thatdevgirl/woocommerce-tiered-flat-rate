<?php

function tiered_shipping_init() {

	if ( !class_exists( 'WC_Tiered_Shipping' ) ) {
		class WC_Tiered_Shipping extends WC_Shipping_Method {

			/**
			 * Constructor for the shipping class
			 */
			public function __construct() {
				$this->id           = 'tiered_shipping';
				$this->method_title = __( 'Tiered Flat Rate Shipping' );   // Admin settings title
				$this->title        = __( 'Tiered Flat Rate Shipping' );   // Shipping method list title

				$this->init();
			}

			/**
			 * Initialize all the settings.
			 */
			function init() {
				// Load the settings.
				$this->init_form_fields();
				$this->init_settings();

				// Save settings.
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_options' ) );
			}

			/**
			 * Settings Form Fields (overriding default settings API)
			 */
			 function init_form_fields() {
			 	global $woocommerce;

				$this->form_fields = array(
					'enabled' => array(
						'title' => __( 'Enabled/Disabled', 'tiered_shipping' ),
						'type'  => 'checkbox',
						'label' => 'Enable this shipping method'
					),

					'usertitle' => array(
						'title'       => __( 'Shipping method label', 'tiered_shipping' ),
						'type'        => 'text',
						'description' => __( 'The label that is visible to the user.', 'tiered_shipping' ),
						'default'     => __( 'Tiered Flat Rate', 'tiered_shipping' )
					),

					'availability' => array(
						'title'       => __( 'Availability', 'tiered_shipping' ),
						'type'        => 'select',
						'class'       => 'wc-enhanced-select availability',
						'options'     => array(
							'all'      => 'All allowed countries',
							'except'   => 'All allowed countries, except...',
							'specific' => 'Specific countries'
						),
						'default'     => __( 'all', 'tiered_shipping' )
					),

					'countries' => array(
						'title'       => __( 'Countries', 'tiered_shipping' ),
						'type'        => 'multiselect',
						'class'       => 'wc-enhanced-select',
						'options'     => $woocommerce->countries->countries,
						'default'     => __( '', 'tiered_shipping' )
					),

          'basefee' => array(
						'title' => __( 'Base shipping fee ($)', 'tiered_shipping' ),
						'type'  => 'text',
            'description' => __( 'Flat shipping fee that is applied automatically to the cart total for any number of items.', 'tiered_shipping' )
					),

          'tierfee' => array(
						'title'       => __( 'Tier shipping fee ($)', 'tiered_shipping' ),
						'type'        => 'text',
            'description' => __( 'Additional shipping fee added to the base fee if the number of items in the cart exceeds a specified number.', 'tiered_shipping' )
					),

          'quantity' => array(
						'title'       => __( 'Number of items to activate tier shipping fee', 'tiered_shipping' ),
						'type'        => 'text',
						'description' => __( 'Number of items in the cart needed to activate the additional tier shipping fee.', 'tiered_shipping' )
					),

					'progressive' => array(
						'title'       => __('Incremental fee?', 'tiered_shipping'),
						'type'        => 'checkbox',
						'label'       => __( 'Make the tiered shipping fee incremental' ),
						'description' => __( 'If this option is checked, the tiered shipping fee will be applied incrementally in multiples of the tier item quantity; otherwise, the tiered shipping fee will be a flat fee if the cart is above the specified quantity.', 'tiered_shipping' )
					)
				 );
			}

			/**
			 * Calculate the shipping rate based on product type and number of items.
			 */
			public function calculate_shipping( $package = array() ) {
				// Only add the shipping rate for this method if the user's country is included.
				if ( $this->is_tiered_allowed( $package ) ) {
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
					$rate = array(
						'id'    => $this->id,
						'label' => $this->get_option('usertitle'),
						'cost'  => $shipping
					);

					$this->add_rate( $rate );
				}
			}

			/**
			 * Function to determine if tierd rate is allowed for this location.
			 */
			function is_tiered_allowed( $package = array() ) {
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
}

add_action( 'woocommerce_shipping_init', 'tiered_shipping_init' );
