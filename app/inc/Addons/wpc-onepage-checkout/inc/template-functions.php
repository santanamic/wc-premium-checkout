<?php

if ( ! function_exists( 'wpc_onepage_checkout_place_order' ) ) {

	function wpc_onepage_checkout_place_order() {
		if ( WC()->cart->needs_payment() ) {
			$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
			WC()->payment_gateways()->set_current_gateway( $available_gateways );
		} else {
			$available_gateways = array();
		}
		wc_get_template(
			'checkout/place-order.php',
			array(
				'checkout'           => WC()->checkout(),
				'available_gateways' => $available_gateways,
				'order_button_text'  => apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ),
			)
		);
	}
}

if ( ! function_exists( 'wpc_onepage_checkout_cart_totals_shipping_html' ) ) {

	function wpc_onepage_checkout_cart_totals_shipping_html() { 
		$packages = WC()->shipping->get_packages(); 
	 
		foreach ( $packages as $i => $package ) { 
			$chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : ''; 
			$product_names = array(); 
	 
			if ( sizeof( $packages ) > 1 ) { 
				foreach ( $package['contents'] as $item_id => $values ) { 
					$product_names[ $item_id ] = $values['data']->get_name() . ' ×' . $values['quantity']; 
				} 
				$product_names = apply_filters( 'woocommerce_shipping_package_details_array', $product_names, $package ); 
			} 
	 
			wc_get_template( 'checkout/cart-shipping.php', array( 
				'package' => $package,  
				'available_methods' => $package['rates'],  
				'show_package_details' => sizeof( $packages ) > 1,  
				'package_details' => implode( ', ', $product_names ),  
				// @codingStandardsIgnoreStart 
				'package_name' => apply_filters( 'woocommerce_shipping_package_name', sprintf( _nx( 'Shipping', 'Shipping %d', ( $i + 1 ), 'shipping packages', 'woocommerce' ), ( $i + 1 ) ), $i, $package ),  
				// @codingStandardsIgnoreEnd 
				'index' => $i,  
				'chosen_method' => $chosen_method,  
			) ); 
		} 
	} 
}

if ( ! function_exists( 'wpc_onepage_checkout_cart_review' ) ) {

	function wpc_onepage_checkout_cart_review() {
		wc_get_template( 'checkout/cart-review.php' );
	}
}

if ( ! function_exists( 'wpc_onepage_checkout_header' ) ) {

	function wpc_onepage_checkout_header() {
		wc_get_template( 'header.php' );
	}
}

if ( ! function_exists( 'wpc_onepage_checkout_restrict_templates_parts' ) ) {

	function wpc_onepage_checkout_restrict_templates_parts( $load, $template, $template_name, $plugin_path ) 
	{
		switch( $template_name ) {
			case 'notices/notice.php':
			case 'notices/error.php':
			case 'notices/success.php':
				if( wpc_is_checkout() && !is_wc_endpoint_url() || ( wp_doing_ajax() && wpc_is_checkout( url_to_postid( wp_get_referer() ) ) ) ) {
					$load = true;
				} else {
					$load = false;
				}
				break;
		}
		
		return $load;
	}
}

if ( ! function_exists( 'wpc_onepage_checkout_template_active_callback' ) ) {

	function wpc_onepage_checkout_template_active_callback( $compare ) 
	{
		return (
			!is_wc_endpoint_url() && is_checkout()
		);
	}
	
}

if ( ! function_exists( 'wpc_onepage_checkout_template_active_callback' ) ) {

	function wpc_onepage_checkout_template_active_callback( $compare ) {
		return (
			!is_wc_endpoint_url() && is_checkout()
		);
	}
	
}

if ( ! function_exists( 'wpc_onepage_checkout_partial_logo' ) ) {

	function wpc_onepage_checkout_partial_logo() 
	{
		return (
			!is_wc_endpoint_url() && is_checkout()
		);
	}
	
}

if ( ! function_exists( 'wpc_onepage_checkout_print_css' ) ) {

	function wpc_onepage_checkout_print_css() 
	{
		$css = '';

		if( !is_wc_endpoint_url() && is_checkout() ) {
			$order_button  = get_option( 'wpc_theme_onepage_checkout_order_button_color', '#00899d' );
			$primary_color = get_option( 'wpc_theme_onepage_checkout_content_primary_color', '#00646d' );
			$background    = get_option( 'wpc_theme_onepage_checkout_background_color', '#f1f1f1' );
			$header_color  = get_option( 'wpc_theme_onepage_checkout_header_color', '#00000000' );
			
			
			// Primary Color
			$css .= "button#coupon-send{background-color: {$primary_color};border-color: {$primary_color};} .content-box-title, .woocommerce-button.button.woocommerce-form-login__submit, .steps .steps-item.steps-item-is-current div.steps-item-icon{background: {$primary_color};}";
			
			// Order Button
			$css .= "button#place_order{background-color: {$order_button};border-color: {$order_button};}";
			
			// Background
			$css .= "body{background-color: {$background};}";

			// Header
			$css .= ".header{background-color: {$header_color};}";

			if( '#00000000' === $header_color ) {
				$css .= ".header{border-color: #00000000;}";
			} else {
				$css .= ".header{border-color: #ebebeb;}";
			}
		
		}
		
		if ( ! empty( $css ) ){			
			printf( '<style type="text/css" id="wpc_onepage_checkout-css">%s</style>', $css );
		}
	}
	
}

if ( ! function_exists( 'wpc_onepage_checkout_body_classes' ) ) {

	function wpc_onepage_checkout_body_classes( $classes ) {	
		$classes[] = get_option( 'wpc_theme_onepage_checkout_form_layout', '' );
  
		return $classes;
	}
	
}