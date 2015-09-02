<?php
/**
 * @package WCTieredShipping
 */
/*
Plugin Name: WC Tiered Shipping
Plugin URI: http://www.thatdevgirl.com/wc-tiered-shipping
Description: This WordPress plugin adds a tiered flat rate shipping option for the WooCommerce plugin.
Version: 2.5
Author: Joni Halabi
Author URI: http://www.jhalabi.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function tiered_shipping_init() {
	
	if ( !class_exists( 'WC_Tiered_Shipping' ) ) {
		class WC_Tiered_Shipping extends WC_Shipping_Method {
		
			/**
			 * Constructor for the shipping class
			 *
			 * @access public
			 * @return void
			 */
			public function __construct() {
				$this->id           = 'tiered_shipping';
				$this->method_title = __( 'Tiered Shipping' );   // Admin settings title
				$this->title        = __( 'Tiered Shipping' );   // Shipping method list title
				
				$this->init();
			}
			
			/**
			 * init function.
			 *
			 * @access public
			 * @return void
			 */
			function init() {
				// Load the settings.
				$this->init_form_fields();
				$this->init_settings();

				// Save settings.
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_options' ) );
			}
			
			/**
			 * Init Settings Form Fields (overriding default settings API)
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
						'title'       => __( 'Title', 'tiered_shipping' ),
						'type'        => 'text',
						'description' => __( 'Shipping method label that is visible to the user.', 'tiered_shipping' ),
						'default'     => __( 'Tiered Flat Rate', 'tiered_shipping' )
					),
					
					'availability' => array(
						'title'       => __( 'Availability', 'tiered_shipping' ),
						'type'        => 'select',
						'class'       => 'wc-enhanced-select availability',
						'options'     => array(
							'all'      => 'All allowed countries',
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

					'quantity' => array(
						'title'       => __( 'Number of items to activate tiered fee', 'tiered_shipping' ),
						'type'        => 'text',
						'description' => __( 'Number of items in the cart to activate the additional shipping fee for the next tier.', 'tiered_shipping' )
					),
					
					'progressive' => array(
						'title'       => __('Incremental fee?', 'tiered_shipping'),
						'type'        => 'checkbox',
						'label'       => 'Make the tiered shipping fee incremental',
						'description' => __( 'If this option is checked, the tiered shipping fee will be applied incrementally in multiples of the tier item quantity; otherwise, the tiered shipping fee will be a flat fee if the cart is above the specified quantity.', 'tiered_shipping' )
					),
					
					'basefee' => array(
						'title' => __( 'Base shipping fee ($)', 'tiered_shipping' ),
						'type'  => 'text'
					),
					
					'tierfee' => array(
						'title' => __( 'Additional shipping fee for tiers ($)', 'tiered_shipping' ),
						'type'  => 'text'
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
			public function calculate_shipping( $package = array() ) {
				// Only add the shipping rate for this method if the user's country is included.
				if ($this->is_tiered_allowed($package)) { 
					global $woocommerce;

					// Get total item count from cart.
					$cart_item_quantities = $woocommerce->cart->get_cart_item_quantities();
					$cart_total_items = array_sum($cart_item_quantities); 
					
					// Set the base shipping fee.
					$shipping = $this->get_option('basefee');
					
					// Override base fee with tiered fee if cart items are over the tier quantity.
					if ($cart_total_items > $this->get_option('quantity')) {
						
						// If the tier fee should be progressive, calculate the multiplier and add the tier fee * multiplier.
						if ($this->get_option('progressive') == 'yes') {
							$multiplier = ceil($cart_total_items / $this->get_option('quantity')) - 1;
							$shipping += $this->get_option('tierfee') * $multiplier;
						} 
						
						// If the tier fee is flat, simply add the tier fee.
						else {
							$shipping += $this->get_option('tierfee');
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
			 * is_tiered_allowed function.
			 *
			 * @param mixed $package
			 * @return true|false
			 */
			function is_tiered_allowed( $package = array() ) {
				// If plugin availability is set to all countries, just return true.
				$availability = $this->get_option('availability');

				if ($availability == 'all') {
					return true;
				}

				// Otherwise, if user's country is not set, return false.
				//    We cannot allow this shipping option if it is not available in all countries 
				//    and we do not know what country the user is in.
				$user_country = $package['destination']['country'];

				if (!$user_country) {
					return false;
				}

				// Otherwise, make sure the user's country is in the array of allowed countries.
				$countries = $this->get_option('countries');

				$in_allowed_country = false;
				
				for ($i=0; $i<sizeof($countries); $i++) {
					if ($user_country == $countries[$i]) {
						$in_allowed_country = true;
						break;
					}
				}

				return $in_allowed_country;
			}
		}
	}
}

add_action( 'woocommerce_shipping_init', 'tiered_shipping_init' );

function add_tiered_shipping( $methods ) {
	$methods[] = 'WC_Tiered_Shipping';
	return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'add_tiered_shipping' );
