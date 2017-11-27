<?php

function tiered_shipping_add( $methods ) {
	$methods[] = 'WC_Tiered_Shipping';
	return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'tiered_shipping_add' );
