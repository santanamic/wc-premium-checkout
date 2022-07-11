<?php

namespace WPC\Theme;
	
class Default_Theme extends \WPC\Abstract_Addon 
{
	public function __construct() 
	{
		$this->type        = 'theme';
		$this->slug        = 'wpc_theme_default_theme';
		$this->title       = 'Default Theme';
		$this->thumbnail   = 'https://s3.envato.com/files/266866500/thumbnail.png';
		$this->screenshot  = 'http:\/\/wp-sandbox.com.br\/wp-content\/plugins\/wc-premium-checkout\/includes\/plug-in\/embedded\/themes\/default\/assets\/img\/screenshot.png';
		$this->description = 'Don\'t want to use WPC? Activate the default installation theme again';
		$this->author      = 'WPC';
		$this->author_url  = 'https://s3.envato.com/';
		$this->url         = 'https://s3.envato.com/';
		$this->version     = WPC_VERSION;
		
		add_filter( 
				'wpc_enable_addon_to_load', 
				array(
					$this,
					'prevent_load_addon',
				),
				PHP_INT_MAX,
				2
			);
		
		$this->register();		
	}
	
	public function prevent_load_addon( $is_enable, $addon ) 
	{
		if( 'WPC\Extension\Theme_Selector' === $addon ) {
			return $is_enable;
		}
		
		
		return false;
	}

	public function customizer() 
	{
		
	}	
	
}