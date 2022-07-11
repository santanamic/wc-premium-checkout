<?php

namespace WPC\Extension;

class Conditional_Rules extends \WPC\Abstract_Addon 
{	
	public function __construct() 
	{		
		$this->section      =   'wpc_conditional_rules';
		$this->setting      =   'wpc_conditional_rules_list';
		
		$this->type         =   'extension';
		$this->slug         =   'wpc-conditional-rules';
		$this->version      =   WPC_VERSION;
		$this->title        =   __( 'Conditional Rules', WPC_SLUG );
		$this->description  =   __( 'rrrrrrrrrr', WPC_SLUG );
		$this->author       =   __( 'WPC' );
		$this->author_url   =   WPC_URL;
		$this->thumbnail    =   plugins_url( 'assets/img/thumbnail.svg', __FILE__ );
		$this->embedded     =   true;
		
		//add_action( 'wpc_template_init', array( $this, 'get_all_checkout_fields' ) );
	}

	public static function customize_init() 
	{

	}

	public static function template_init() 
	{
		//add_action( 'woocommerce_checkout_init', array( $this, 'get_all_checkout_fields' ), PHP_INT_MAX );
	}

	public function customizer() 
	{
		return (
			array( 
				'sections' => array( 
					$this->section => array(
						'title' => __( 'Conditional Rules', WPC_SLUG ),
						'description'  =>  __( 'Use these options to add conditional rules in checkout page.', WPC_SLUG ),
						'priority' => 290,
					) 
				),
				'settings' => array( 
					$this->setting => array()
				),
				'controls' => array(
					'wpc_conditional_rules' => array(
						'id'  	       =>  'wpc_conditional_rules_control',
						'label'        =>  'Add field rules',
						'description'  =>  __( 'EEEEEEEEEEEE.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  $this->setting
					)
				),
			)
		);

	}
	
}