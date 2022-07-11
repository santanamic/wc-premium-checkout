<?php 

namespace WPC;

abstract class Abstract_Addon
{
	public $section;
	public $setting;
	
	public $type;
	public $embedded = false;
	public $require  = [];
	public $fields   = [];
	public $slug;
	public $title;
	public $thumbnail;
	public $screenshot;
	public $path;
	public $description;
	public $version;

	abstract public function customizer();

	public function builder_fields() 
	{		
		global $wp_customize;	

		if( empty( $fields = $this->customizer() ) ) {
			return;
		}
		
		$customizer = apply_filters( 
			'wpc_addon_customizer', 
			$fields,
			$this->type,
			$this->slug,
			$this->version
		);
		
		if( isset( $customizer['sections'] ) ) {
			foreach(  $customizer['sections'] as $id => $args ) {			
				$type = $this->type;
				$slug = $this->slug;
				
				$args['active_callback'] = function() use( $type, $slug, $id ) {
					return apply_filters( 
						'wpc_addon_section_active_callback', 
						$args['active_callback'] ?? true, 
						$type, 
						$slug,
						$id
					);
				};				
				$args['panel'] = 'wpc';	
				$wp_customize->add_section( 
					$id, 
					$args 
				);
			}
		}
		if( isset( $customizer['settings'] ) ) {
			foreach(  $customizer['settings'] as $id => $args ) {
				$args['type'] = 'option';
				
				if ( ! isset( $args['class'] ) ) {
					$wp_customize->add_setting( 
						$id, 
						$args 
					);
				} else {
					$id  = $args['id'] ?? $id;
					$obj = $args['class'];
					
					unset( $args['class'] ); //remove unused values
					
					$wp_customize->add_control(
						new $obj( 
							$wp_customize, 
							$id, 
							$args 
						) 
					);
				}
			}
		}	
		if( isset( $customizer['controls'] ) ) {
			foreach( $customizer['controls'] as $id => $args ) {
				if ( ! isset( $args['class'] ) ) {
					$type = $this->type;
					$slug = $this->slug;
					
					$args['active_callback'] = function() use( $type, $slug, $id ) {
						return apply_filters( 
							'wpc_addon_control_active_callback', 
							$args['active_callback'] ?? true, 
							$type, 
							$slug,
							$id
						);
					};
			
					$wp_customize->add_control(
						$id,
						$args
					);
				} else {
					$obj = $args['class'];
					
					unset( $args['class'] ); //remove unused values
					
					$wp_customize->add_control(
						new $obj( 
							$wp_customize, 
							$id, 
							$args 
						) 
					);
				}
			}
		}
		if( isset( $customizer['refresh'] ) ) {
			foreach(  $customizer['refresh'] as $id => $args ) {
				$wp_customize->selective_refresh->add_partial( 
					$id, 
					$args 
				);
			}
		}
		
	}
	
	public function load_template()
	{
		add_filter( 
			'woocommerce_locate_template', 
			function( $template, $template_name, $plugin_path ) {
				if ( !is_null( $this->path ) 
					&& file_exists( $this->path . $template_name ) 
					&& apply_filters( 'wpc_woocommerce_part_callback', true, $template, $template_name, $plugin_path ) ) {
						return $this->path . $template_name;
				}
				return $template;
			}, 
			300, 
			3 
		);
	}

	public function get( $name )
	{
		if( property_exists( $this , $name ) ) {
			return $this->$name;
		}
		else {
			throw new Exception( 
				sprintf( 
					'The property %1$s dow not exists in %2$s class', 
					$name, 
					__CLASS__ 
				) 
			);
		}
	}

	public function set( $name, $value )
	{
		if( property_exists( $this , $name ) ) {
			$this->$name = $value;
			return $this->$name;
		}
		else {
			throw new Exception( 
				sprintf( 
					'The property %1$s dow not exists in %2$s class', 
					$name, 
					__CLASS__ 
				) 
			);
		}
	}
	
	public function register() 
	{
		$class_name = get_class( $this );
		
		add_filter( 
			'wpc_addons', 
			function( $addons ) use( $class_name ) {
				$addons[] = $class_name;
				return $addons;
			} 
		);
	}
		
	public function is_available() 
	{
		return true;
	}
}