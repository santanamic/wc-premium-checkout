<?php defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpc_customize_builder_commmon' ) ) {

	/**
	 * Begins execution of the WP customize
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_customize_builder_commmon( $wp_customize ) 
	{		
		$wp_customize->add_panel( 
			'wpc', 
			array(
				'title' => str_replace( 
					'WooCommerce ', 
					'', 
					WPC_TITLE 
				),
				'description' => __( 
					'Premium Checkout is the best plugin for checkout customization. Use to create beautiful checkouts.', 
					WPC_SLUG 
				),
				'priority' => 150
			) 
		);

		$wp_customize->add_section(
			'wpc',
			array(
				'title'    => __( 'Enable / Disable', WPC_SLUG ),
				'priority' => 1000,
				'panel'    => 'wpc',
			)
		);

		$wp_customize->add_setting( 
			'wpc', 
			array(
				'default'   => 'no',
				'type'      => 'option',
				'transport' => 'postMessage',
				'sanitize_callback'    => 'wpc_bool_to_string', 
				'sanitize_js_callback' => 'wpc_string_to_bool',
			)
		);

		$wp_customize->add_control(
			'wpc',
			array(
				'label'    => __( 'Check to activate' , WPC_SLUG ),
				'type'     => 'checkbox',
				'section'  => 'wpc',
				'settings' => 'wpc',
			)
		);
	}
	
}

if ( ! function_exists( 'wpc_customize_enqueue' ) ) {

	/**
	 * Register the JavaScrip and CSS for customize
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_customize_enqueue() 
	{
		wp_enqueue_script( 
			'wpc-customize', 
			WPC_URI . 'app/assets/js/customizer.js', 
			array( 'jquery', 'customize-base' ),
			WPC_VERSION, 
			false  
		);
		
		wp_localize_script(
			'wpc-customize',
			'wpc',
			array(
				'nonce' => wp_create_nonce( 
					'wpc-customizer' 
				),
				'ajaxurl' => admin_url(
					'admin-ajax.php'
				)
			)
		);
		
		wp_enqueue_style( 
			'wpc-customize', 
			WPC_URI . 'app/assets/css/customizer.css', 
			array(), 
			WPC_VERSION,
			'all' 
		);
	}
	
}

if ( ! function_exists( 'wpc_preview_enqueue' ) ) {

	/**
	 * Register the JavaScrip and CSS for customize preview
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_preview_enqueue() 
	{		
		wp_enqueue_script( 
			'wpc', 
			WPC_URI . 'app/assets/js/preview.js', 
			array( 'jquery', 'customize-preview' ), 
			WPC_VERSION, 
			true 
		);
	}
	
}

if ( ! function_exists( 'wpc_customize_run_addons' ) ) {

	/**
	 * Run addons enabled on customize
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_customize_run_addons() 
	{
		$addons = WPC()->addons->get();
		
		foreach ( $addons as $slug => $addon ) {
			$addon->builder_fields();
		}		
	}
	
}
