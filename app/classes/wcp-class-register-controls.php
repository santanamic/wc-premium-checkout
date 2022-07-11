<?php

namespace WPC;

final class Register_Controls 
{
	public $controls = [];
	
	public function __construct() 
	{		
		$embedded = array(
			'WPC\Control\Card_Selector',
			'WPC\Control\Multiple_Select',
			'WPC\Control\Field_Group',
		);
		$load_controls = apply_filters( 'wpc_register_controls', $embedded );
		
		foreach ( $load_controls as $class ) {		
			$this->controls[] = $class;
			
			add_action( 
				'customize_register', 
				function( $wp_customize ) use( $class ) {
					if( is_string( $class ) && class_exists( $class ) ) {
						$wp_customize->register_control_type( 
							$class
						);
					} else {
						throw new \Exception( 
							sprintf( 'The %1$s control is invalid' , $class )
						);
					}
				} 
			);
		}		
	}
	
	public function list() 
	{
		return $this->controls;
	}

}