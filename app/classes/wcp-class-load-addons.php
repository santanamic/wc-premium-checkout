<?php 

namespace WPC;

final class Load_Addons 
{	
	public $themes     = [];
	public $extensions = [];

	public function __construct() 
	{			
		$embedded = array(
			'WPC\Extension\Theme_Selector',
			'WPC\Extension\Theme_Compatibility',
			'WPC\Extension\Fields_Manager',
			'WPC\Extension\Conditional_Rules',
			'WPC\Theme\Onepage_Checkout',
		);

		$load_addons = apply_filters( 'wpc_addons', $embedded );
		
		foreach ( $load_addons as $addon ) {
			$is_load = apply_filters( 'wpc_load_addon', true, $addon );
			
			if ( ! $is_load ) {
				continue;
			}
			
			if ( is_string( $addon ) && class_exists( $addon ) ) {
				$addon = new $addon();
				$type  = $addon->get( 'type' );
				$slug  = $addon->get( 'slug' );
			}

			if ( ! is_a( $addon, 'WPC\Abstract_Addon' ) ) {
				throw new \Exception( 
					'The WPC\AbstractAddon class has not been extended to one or more elements.'
				);
			}
			
			if ( $addon->is_available() ) {
				switch( $type ) {
					case 'theme':
						$this->themes[ $addon->slug ] = $addon;
						break;
					case 'extension':
						$this->extensions[ $addon->slug ] = $addon;
						break;
				}
			}
		}		
	}

	public function get( $type = 'all' ) 
	{		
		switch( $type ) {
			case 'themes':
				return (
					$this->themes
				);
				break;
			case 'extensions':
				return (
					$this->extensions
				);
				break;
			case 'all':
				return (
					array_merge(
						$this->themes,
						$this->extensions
					)
				);
				break;
		}
	}

	public function get_by_slug( $slug ) 
	{		
		$addons = array_merge(
			$this->themes,
			$this->extensions
		);
		
		if ( isset( $addons[ $slug ] ) ) {
			return $addons[ $slug ];
		} else {
			return false;
		}
	
	}
	
}