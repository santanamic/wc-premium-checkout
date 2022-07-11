<?php

namespace WPC;

trait Singleton 
{
    protected static $instance = null;

	public static function instance() 
	{		
		if ( ! isset( static::$instance ) && ! ( static::$instance instanceof static ) ) {
			static::$instance = new static();

			if ( method_exists( static::$instance, 'init' ) ) {
				static::$instance->init();
			}			
		}

		return static::$instance;
	}
	
}
