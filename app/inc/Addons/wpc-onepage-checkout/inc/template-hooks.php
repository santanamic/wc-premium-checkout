<?php

/**
 * Template
 */
add_action( 'wpc_woocommerce_part_callback', 'wpc_onepage_checkout_restrict_templates_parts', 10, 4 );
add_filter( 'wpc_template_callback', 'wpc_onepage_checkout_template_active_callback' );
add_filter( 'wpc_body_class', 'wpc_onepage_checkout_body_classes' );

/**
 * Remove default
 */
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );

/**
 * Head
 */
add_action( 'wpc_template_head', 'wpc_onepage_checkout_print_css', -1 );

/**
 * Header
 */
add_action( 'wpc_template_before_main_content', 'wpc_onepage_checkout_header', -1 );

/**
 * Payment
 */
add_action( 'wpc_onepage_checkout_payment', 'woocommerce_checkout_payment', 20 );

/**
 * Order review
 */
add_action( 'woocommerce_checkout_after_order_review', 'wpc_onepage_checkout_place_order', 20 );

/**
 * Cart review
 */
add_action( 'wpc_onepage_cart_totals_shipping', 'wpc_onepage_checkout_cart_totals_shipping_html', 10 );
add_action( 'wpc_onepage_checkout_payment_cart_review', 'wpc_onepage_checkout_cart_review', 10 );

/**
 * Add form login
 */
 
//add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
