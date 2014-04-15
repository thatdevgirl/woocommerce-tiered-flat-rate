<?php
/**
 * @package TieredFlatRate
 */
/*
Plugin Name: WooCommerce Tiered Flat Rate Shipping
Plugin URI: http://www.thatdevgirl.com
Description: Add a tiered flat rate shipping option for WooCommerce
Version: 1.0
Author: Joni Halabi
Author URI: http://www.jhalabi.com
License: The MIT License
*/

/*
The MIT License (MIT)

Copyright (c) 2014 Joni Halabi (joni@jhalabi.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	function tiered_flat_rate_init() {
		
		if ( ! class_exists( 'WC_Tiered_Flat_Rate' ) ) {
			class WC_Tiered_Flat_Rate extends WC_Shipping_Method {
				/**
				 * Constructor for the shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'tiered_flat_rate';
					$this->method_title       = __( 'Tiered Flat Rate' );   // Admin settings title
					$this->title              = __( 'Tiered Flat Rate' );   // Shipping method list title

					$this->init();
				}

				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load settings from settings API.
					$this->init_form_fields();	// Overridden above.
					$this->init_settings();

					// Save settings.
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}
				
				/**
				 * Init Settings Form Fields (overridding default settings API)
				 */
				 function init_form_fields() {
				    $this->form_fields = array(
						'enabled' => array(
							'title' => __( 'Enabled/Disabled', 'tiered_flat_rate' ),
							'type' => 'checkbox'
						),
						'title' => array(
							'title' => __( 'Title', 'tiered_flat_rate' ),
							'type' => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'tiered_flat_rate' ),
							'default' => __( 'Tiered Flat Rate', 'tiered_flat_rate' )
						),
						'quantity' => array(
							'title' => __( 'Number of items', 'tiered_flat_rate' ),
							'type' => 'text',
							'description' => __( 'Number of items in the cart to activate the higher shipping rate', 'tiered_flat_rate' )
						),
						'basefee' => array(
							'title' => __( 'Base shipping fee', 'tiered_flat_rate' ),
							'type' => 'text'
						),
						'tierfee' => array(
							'title' => __( 'Higher shipping fee', 'tiered_flat_rate' ),
							'type' => 'text'
						)
				     );
				}

				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package ) {
					global $woocommerce;
					
					// Get total item count from cart.
					$cart_item_quantities = $woocommerce->cart->get_cart_item_quantities();
					$cart_total_items = array_sum($cart_item_quantities); 
					
					// Set the base shipping fee.
					$shipping = $this->settings['basefee'];
					
					// Override base fee with tiered fee if cart items are over the tier quantity.
					if ($cart_total_items > $this->settings['quantity']) {
						$shipping = $this->settings['tierfee'];
					}
					
					// Set the shipping rate.
					$rate = array(
						'id' => $this->id,
						'label' => $this->settings['title'],
						'cost' => $shipping
					);
					
					$this->add_rate( $rate );
				}
			}
		}
	}

	add_action( 'woocommerce_shipping_init', 'tiered_flat_rate_init' );

	function add_tiered_flat_rate( $methods ) {
		$methods[] = 'WC_Tiered_Flat_Rate';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'add_tiered_flat_rate' );
}