<?php

namespace WPC\Theme;
	
class Onepage_Checkout extends \WPC\Abstract_Addon 
{
	public function __construct() 
	{
		require_once( __DIR__ . '/inc/template-functions.php' );
		require_once( __DIR__ . '/inc/template-hooks.php' );

		$this->type        = 'theme';
		$this->slug        = 'wpc_theme_onepage_checkout';
		$this->title       = 'Onepage Checkout';
		$this->thumbnail   = 'https://s3.envato.com/files/266866500/thumbnail.png';
		$this->screenshot  = 'http:\/\/wp-sandbox.com.br\/wp-content\/plugins\/wc-premium-checkout\/includes\/plug-in\/embedded\/themes\/default\/assets\/img\/screenshot.png';
		$this->path        = __DIR__ . '/templates/';
		$this->description = 'The Onepage Checkout theme for WPC';
		$this->author      = 'WPC';
		$this->author_url  = 'https://s3.envato.com/';
		$this->url         = 'https://s3.envato.com/';
		$this->version     = WPC_VERSION;
		
		$this->register();
		
		add_filter( 'wpc_template_init', array( $this, 'frontend_enqueue' ) );
		add_action( 'customize_preview_init',  array( $this, 'preview_enqueue' ) );
	}
	
	public function preview_enqueue() 
	{
		wp_enqueue_script( 
			'wpc_theme_onepage_checkout_preview', 
			plugins_url( 'assets/js/preview.js', __FILE__ ), 
			array( 'jquery', 'customize-preview' ), 
			WPC_VERSION, 
			true 
		);
	}

	public function frontend_enqueue() 
	{
		wp_enqueue_style( 
			'wpc_theme_onepage_checkout_frontend',
			plugins_url( 'assets/css/frontend.css', __FILE__ ),
			array(), 
			WPC_VERSION,
			'all' 
		);

		wp_enqueue_style( 
			'wpc_theme_onepage_checkout_frontend_form',
			plugins_url( 'assets/css/frontend-form.css', __FILE__ ),
			array(), 
			WPC_VERSION,
			'all' 
		);

		wp_enqueue_style( 
			'wpc_theme_onepage_checkout_frontend_form_inline',
			plugins_url( 'assets/css/frontend-form-inline.css', __FILE__ ),
			array(), 
			WPC_VERSION,
			'all' 
		);
	
		wp_enqueue_style( 
			'normalize',
			plugins_url( 'assets/css/normalize.min.css', __FILE__ ),
			array(), 
			'8.0.1',
			'all' 
		);
		
		wp_enqueue_script( 
			'wpc_theme_onepage_checkout_frontend', 
			plugins_url( 'assets/js/frontend.js', __FILE__ ), 
			array( 'jquery' ), 
			WPC_VERSION, 
			true 
		);
	}
	
	public function customizer() 
	{
		//delete_option('wpc_theme_onepage_checkout_order_button_color');
		//delete_option('wpc_theme_onepage_checkout_logo');
		//delete_option('wpc_theme_onepage_checkout_content_primary_color');
		
		return (
			array_merge(
				array( 
					'sections' => array( 
						'wpc_onepage_checkout_styles' => array(
						'title' => __( 'Checkout Editor', WPC_SLUG ),
						'description'  =>  __( '', WPC_SLUG ),
						'priority' => 10,
						) 
					),
					'settings' => array( 
						'wpc_theme_onepage_checkout_logo' => array( 
							'default'   =>  WPC_LOGO_URL,
							'transport' => 'postMessage',
							'sanitize_callback' => 'esc_url_raw'
						), 
						'wpc_theme_onepage_checkout_order_button_color' => array( 
							'default'   => '#00899d',
							'transport' => 'postMessage',
						), 
						'wpc_theme_onepage_checkout_content_primary_color' => array( 
							'default'   => '#00646d',
							'transport' => 'postMessage',
						), 
						'wpc_theme_onepage_checkout_background_color' => array( 
							'default'   => '#f1f1f1',
							'transport' => 'postMessage',
						), 
						'wpc_theme_onepage_checkout_header_color' => array( 
							'default'   => '#00000000',
							'transport' => 'postMessage',
						), 
						'wpc_theme_onepage_checkout_form_layout' => array( 
							//'transport' => 'postMessage',
						),  
					),
					'controls' => array(
						'wpc_theme_onepage_checkout_logo' => array(
							'class'        =>  'WP_Customize_Image_Control',
							'label'        =>  __( 'Logo' , WPC_SLUG ),
							'section'      =>  'wpc_onepage_checkout_styles',
							'settings'     =>  'wpc_theme_onepage_checkout_logo'
						),
						'wpc_theme_onepage_checkout_content_primary_color' => array(
							'class'        =>  'WP_Customize_Color_Control',
							'label'        =>  __( 'Primary color' , WPC_SLUG ),
							'section'      =>  'wpc_onepage_checkout_styles',
							'settings'     =>  'wpc_theme_onepage_checkout_content_primary_color'
						),
						'wpc_theme_onepage_checkout_order_button_color' => array(
							'class'         =>  'WP_Customize_Color_Control',
							'label'        =>  __( 'Button place order color' , WPC_SLUG ),
							'section'      =>  'wpc_onepage_checkout_styles',
							'settings'     =>  'wpc_theme_onepage_checkout_order_button_color'
						),
						'wpc_theme_onepage_checkout_background_color' => array(
							'type'         =>  'select',
							'choices'      =>  array( '#f1f1f1' => __( 'Default', WPC_SLUG ), '#ffffff' => __( 'White', WPC_SLUG ) ),
							'label'        =>  __( 'Background' , WPC_SLUG ),
							'section'      =>  'wpc_onepage_checkout_styles',
							'settings'     =>  'wpc_theme_onepage_checkout_background_color'
						),
						'wpc_theme_onepage_checkout_header_color' => array(
							'type'         =>  'select',
							'choices'      =>  array( '#00000000' => __( 'Default', WPC_SLUG ), '#ffffff' => __( 'White', WPC_SLUG ) ),
							'label'        =>  __( 'Header' , WPC_SLUG ),
							'section'      =>  'wpc_onepage_checkout_styles',
							'settings'     =>  'wpc_theme_onepage_checkout_header_color'
						),
						'wpc_theme_onepage_checkout_form_layout' => array(
							'type'         =>  'select',
							'choices'      =>  array( 'inline-form' => __( 'Inline', WPC_SLUG ), '' => __( 'Default', WPC_SLUG ) ),
							'label'        =>  __( 'Form layout' , WPC_SLUG ),
							'section'      =>  'wpc_onepage_checkout_styles',
							'settings'     =>  'wpc_theme_onepage_checkout_form_layout'
						)
					),
				)
			)
		);
		
	}

}


