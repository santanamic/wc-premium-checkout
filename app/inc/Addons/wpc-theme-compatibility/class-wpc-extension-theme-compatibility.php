<?php

namespace WPC\Extension;

class Theme_Compatibility extends \WPC\Abstract_Addon 
{	
	public function __construct() 
	{		
		$this->section      =   'wpc_theme_compatibility';
		
		$this->type         =   'extension';
		$this->slug         =   'wpc-theme-compatibility';
		$this->version      =   WPC_VERSION;
		$this->title        =   __( 'Theme Compatibility', WPC_SLUG );
		$this->description  =   __( 'Embedded extension for help avoid conflicts caused by CSS styles and JS scripts that affect the look and functionalities of the checkout theme.', WPC_SLUG );
		$this->author       =   __( 'WPC' );
		$this->author_url   =   WPC_URL;
		$this->thumbnail    =   plugins_url( 'assets/img/thumbnail.svg', __FILE__ );
		$this->embedded     =   true;
		
		add_action( 'customize_register', array( $this, 'customize_init' ) );
		add_action( 'wpc_template_init', array( $this, 'template_init' ) );
		add_action( 'wpc_template_init', array( $this, 'do_remove_hooks' ) );
		add_action( 'wp_ajax_wpc_theme_compatibility_print_enquetes', array( $this, 'print_enquetes' ) );				
	}
	
	public static function customize_init() 
	{
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_enqueue' ) );
		add_action( 'customize_preview_init', array( $this, 'preview_enqueue' ) );		
	}

	public static function template_init() 
	{
		add_action( 'wp_print_styles', array( $this, 'save_enqueue' ), PHP_INT_MAX );
		add_action( 'wp_print_styles', array( $this, 'do_dequeue_scripts' ), PHP_INT_MAX );
		add_action( 'wp_print_styles', array( $this, 'do_dequeue_styles' ), PHP_INT_MAX );
		add_action( 'wpc_template_footer', array( $this, 'add_style_tag_in_template' ), PHP_INT_MAX );
		add_action( 'wpc_template_footer', array( $this, 'add_script_tag_in_template' ), PHP_INT_MAX );
		add_filter( 'wpc_theme_compatibility_removed_styles', array( $this, 'do_dequeue_all_theme_styles' ) );
		add_filter( 'wpc_theme_compatibility_removed_scripts', array( $this, 'do_dequeue_all_theme_scripts' ) );
		add_filter( 'wpc_theme_compatibility_removed_styles', array( $this, 'do_dequeue_plugin_styles' ) );
		add_filter( 'wpc_theme_compatibility_removed_scripts', array( $this, 'do_dequeue_plugin_scripts' ) );
		add_filter( 'wpc_theme_compatibility_removed_hooks', array( $this, 'do_remove_all_theme_hooks' ) );
	}

	public function save_enqueue() 
	{
		if ( true === wpc_customize_on_embed() ) {
			$return_list_themes  = 'yes' !== get_option( 'wpc_theme_compatibility_disable_scripts_wp_theme', 'yes' );
			$content['styles']   = $this->sanitize_enquete( 'styles', $return_list_themes );
			$content['scripts']  = $this->sanitize_enquete( 'scripts', $return_list_themes );
			$json_encode_content = json_encode( $content );
			
			if ( ! add_option( 'wpc_theme_compatibility_enquete', $json_encode_content ) ) {
				update_option( 'wpc_theme_compatibility_enquete', $json_encode_content );
			}
		}
	}

	public function print_enquetes() 
	{
		check_ajax_referer( 'wpc-theme-compatibility-nonce', 'security' );
	
		$json_content = get_option( 'wpc_theme_compatibility_enquete' );
		
		print( $json_content ); // print content in ajax response
		
		die;
	}

	public function customize_enqueue() 
	{
		wp_enqueue_script( 
			'wpc-theme-compatibility', 
			plugins_url( 'assets/js/customizer.js', __FILE__ ), 
			array( 'jquery', 'customize-base' ), 
			WPC_VERSION, 
			true 
		);
		
		wp_localize_script(
			'wpc-theme-compatibility',
			'wpc_theme_compatibility',
			array(
				'nonce' => wp_create_nonce( 
					'wpc-theme-compatibility-nonce' 
				),
			)
		);
	}

	public function preview_enqueue() 
	{
		wp_enqueue_script( 
			'wpc-theme-compatibility', 
			plugins_url( 'assets/js/preview.js', __FILE__ ), 
			array( 'jquery', 'customize-preview' ), 
			WPC_VERSION, 
			true 
		);
	}

	public function customizer() 
	{
		/*
		delete_option( 'wpc_theme_compatibility_removed_styles' );
		delete_option( 'wpc_theme_compatibility_removed_scripts' );
		delete_option( 'wpc_theme_compatibility_custom_css' );
		delete_option( 'wpc_theme_compatibility_custom_js' );
		delete_option( 'wpc_theme_compatibility_disable_styles_wp_theme' );
		delete_option( 'wpc_theme_compatibility_disable_scripts_wp_theme' );
		delete_option( 'wpc_theme_compatibility_remove_hooks_wp_theme' );
		*/
		
		return (
			array( 
				'sections' => array( 
					$this->section => array(
						'title' => __( 'Compatibility', WPC_SLUG ),
						'description'  =>  __( 'These options help to avoid conflicts caused by CSS styles and JS scripts that affect the theme appearance and functionality at checkout.', WPC_SLUG ),
						'priority' => 205,
					) 
				),
				'settings' => array( 
					'wpc_theme_compatibility_removed_styles'  => array (),
					'wpc_theme_compatibility_removed_scripts' => array (),
					'wpc_theme_compatibility_custom_css'      => array (
						'default'   => '',
						'transport' => 'postMessage',
					),
					'wpc_theme_compatibility_custom_js'      => array (),
					'wpc_theme_compatibility_disable_styles_wp_theme'  => array ( 
						'default'              => 'yes', 
					),
					'wpc_theme_compatibility_disable_scripts_wp_theme' => array ( 
						'default'              => 'yes', 
					),
					'wpc_theme_compatibility_remove_hooks_wp_theme' => array ( 
						'default'              => 'yes', 
					),
				),
				'controls' => array( 
					'wpc_theme_compatibility_removed_styles' => array(
						'class'        =>  'WPC\Control\Multiple_Select',
						'label'        =>  __( 'Remove CSS in Checkout', WPC_SLUG ),
						'choices'      =>  array(),
						'value'        =>  (array) get_option( 'wpc_theme_compatibility_removed_styles', [] ),
						'description'  =>  __( 'Select styles to remove from the checkout.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  'wpc_theme_compatibility_removed_styles',
					), 
					'wpc_theme_compatibility_removed_scripts' => array(
						'class'        =>  'WPC\Control\Multiple_Select',
						'label'        =>  __( 'Remove JS in Checkout', WPC_SLUG ),
						'choices'      =>  array(),
						'value'        =>  (array) get_option( 'wpc_theme_compatibility_removed_scripts', [] ),
						'description'  =>  __( 'Select scripts to remove from the checkout.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  'wpc_theme_compatibility_removed_scripts',
					), 
					'wpc_theme_compatibility_disable_styles_wp_theme' => array(
						'type'         =>  'checkbox',
						'label'        =>  __( 'Disable all theme styles', WPC_SLUG ),
						'description'  =>  __( 'By checking this option all CSS styles of the active theme will be disabled.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  'wpc_theme_compatibility_disable_styles_wp_theme',
					),
					'wpc_theme_compatibility_disable_scripts_wp_theme' => array(
						'type'         =>  'checkbox',
						'label'        =>  __( 'Disable all theme scripts', WPC_SLUG ),
						'description'  =>  __( 'By checking this option all JS scripts of the active theme will be disabled.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  'wpc_theme_compatibility_disable_scripts_wp_theme',
					),
					'wpc_theme_compatibility_remove_hooks_wp_theme' => array(
						'type'         =>  'checkbox',
						'label'        =>  __( 'Remove all theme hooks', WPC_SLUG ),
						'description'  =>  __( 'By checking this option all hooks of the active theme will be removed.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  'wpc_theme_compatibility_remove_hooks_wp_theme',
					),
					'wpc_theme_compatibility_custom_css' => array(
						'type'         =>  'textarea',
						'label'        =>  __( 'Custom CSS', WPC_SLUG ),
						'description'  =>  __( 'Insert custom CSS on the Checkout page. Use to correct elements or conflicts in the layout.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  'wpc_theme_compatibility_custom_css',
					),
					'wpc_theme_compatibility_custom_js' => array(
						'type'         =>  'textarea',
						'label'        =>  __( 'Custom JS', WPC_SLUG ),
						'description'  =>  __( 'Insert custom JS on the Checkout page. Use to add visual effects or dynamism to the checkout fields.', WPC_SLUG ),
						'section'      =>  $this->section,
						'settings'     =>  'wpc_theme_compatibility_custom_js',
					),
				),

			)
		);

	}
	
	public function sanitize_enquete( $type, $return_themes = true, $return_plugin = true ) 
	{		
		$sanitize  = [];
		$enquetes  = self::list_enquete( $type );
		
		if( true === $return_themes && isset ( $enquetes['themes'] ) ) {
			foreach ( $enquetes['themes'] as $theme ) {
				$enquete = null;				
				$enquete['text'] = $theme['Name'];				
				foreach ( $theme[ $type ] as $handle => $style_data ) {
					$enquete['children'][] = array( 'id' => $handle, 'text' => $handle );					
				}
				$sanitize[] = $enquete;				
			}
		}

		if( true === $return_plugin && isset ( $enquetes['plugins'] ) ) {
			foreach ( $enquetes['plugins'] as $plugin ) {
				$ignore_plugins = apply_filters( 'wpc_theme_compatibility_ignore_plugins_for_removal_list', array( WPC_SLUG ), $type );
				if ( !in_array( $plugin['TextDomain'], $ignore_plugins ) ) {
					$enquete = null;				
					$enquete['text'] = $plugin['Name'];				
					foreach ( $plugin[ $type ] as $handle => $style_data ) {
						$ignore_handles = apply_filters( 'wpc_theme_compatibility_ignore_handles_for_removal_list', array(), $type );
						if ( !in_array( $handle, $ignore_handles ) ) {
							$enquete['children'][] = array( 'id' => $handle, 'text' => $handle );									
						}
					}
					$sanitize[] = $enquete;					
				}		
			}
		}

		return $sanitize;		
	}
	
	public static function list_enquete( $type ) 
	{
		$plugins = [];
		$themes  = [];
		
		if ( 'styles' === $type ) {
			$wp_dependencie = wp_styles();
		} elseif ( 'scripts' === $type ) {
			$wp_dependencie = wp_scripts();
		} else {
			return false;
		}
		foreach ( $wp_dependencie->queue as $handle ) {
			$base_url     = wpc_remove_url_protocol( $wp_dependencie->base_url );
			$handle_url   = $wp_dependencie->registered[ $handle ]->src;
			$handle_parse = wpc_url_to_array( str_replace( $base_url, null, $handle_url ) );				
			$handle_parse = wpc_get_util_path( $handle_parse );

			foreach ( wpc_get_themes() as $folder => $theme ) {
				if ( in_array( $folder, $handle_parse, true ) && $folder === $handle_parse[2] ) {
					if ( !isset( $themes[ $folder ] ) )
						$themes[ $folder ] = $theme;
					$themes[ $folder ][ $type ][ $handle ] = $handle_url;
				}
			}
			foreach ( wpc_get_plugins() as $path => $plugin ) {
				$folder = explode( '/', $path )[0];
				if ( in_array( $folder, $handle_parse, true ) && $folder === $handle_parse[2] ) {
					if ( !isset( $plugins[ $path ] ) )
						$plugins[ $path ] = $plugin;
					$plugins[ $path ][ $type ][ $handle ] = $handle_url;
				}				
			}				
		}

		return (
			compact (
				'themes',
				'plugins'
			)
		);
	}

	public static function list_hooks( $hook = '' ) {
		global $wp_filter;
	
		$themes  = [];
		$plugins = [];

		if ( isset( $wp_filter[$hook]->callbacks ) ) {      
			array_walk( $wp_filter[$hook]->callbacks, function( $callbacks, $priority ) use ( &$hooks ) {           
				foreach ( $callbacks as $id => $callback )
					$hooks[] = array_merge( [ 'id' => $id, 'priority' => $priority ], $callback );
			});         
		} else {
			return [];
		}

		foreach( $hooks as &$item ) {
			// skip if callback does not exist
			if ( !is_callable( $item['function'] ) ) continue;

			// function name as string or static class method eg. 'Foo::Bar'
			if ( is_string( $item['function'] ) ) {
				$ref = strpos( $item['function'], '::' ) ? new \ReflectionClass( strstr( $item['function'], '::', true ) ) : new \ReflectionFunction( $item['function'] );
				$item['file'] = $ref->getFileName();
				$item['line'] = get_class( $ref ) == 'ReflectionFunction' 
					? $ref->getStartLine() 
					: $ref->getMethod( substr( $item['function'], strpos( $item['function'], '::' ) + 2 ) )->getStartLine();

			// array( object, method ), array( string object, method ), array( string object, string 'parent::method' )
			} elseif ( is_array( $item['function'] ) ) {

				$ref = new \ReflectionClass( $item['function'][0] );

				// $item['function'][0] is a reference to existing object
				$item['function'] = array(
					is_object( $item['function'][0] ) ? get_class( $item['function'][0] ) : $item['function'][0],
					$item['function'][1]
				);
				$item['file'] = $ref->getFileName();
				$item['line'] = strpos( $item['function'][1], '::' )
					? $ref->getParentClass()->getMethod( substr( $item['function'][1], strpos( $item['function'][1], '::' ) + 2 ) )->getStartLine()
					: $ref->getMethod( $item['function'][1] )->getStartLine();

			// closures
			} elseif ( is_callable( $item['function'] ) ) {     
				$ref = new \ReflectionFunction( $item['function'] );         
				$item['function'] = get_class( $item['function'] );
				$item['file'] = $ref->getFileName();
				$item['line'] = $ref->getStartLine();

			}

		}
		
		foreach( $hooks as $item ) {
			$base_path  = wpc_fix_dir_separator( ABSPATH );
			$path_parse = wpc_path_to_array( str_replace( ABSPATH, null, $item['file'] ) );
			$path_parse = wpc_get_util_path( $path_parse );

			foreach ( wpc_get_themes() as $folder => $theme ) {
				if ( in_array( $folder, $path_parse ) && $folder === $path_parse[3] ) {
					if ( !isset( $themes[ $folder ] ) )
						$themes[ $folder ] = $theme;
					$themes[ $folder ][ 'hooks' ][ $hook ][] = $item;
				}
			}
			foreach ( wpc_get_plugins() as $path => $plugin ) {
				$folder = explode( '/', $path )[0];
				if ( in_array( $folder, $path_parse ) && $folder === $path_parse[3] ) {
					if ( !isset( $plugins[ $path ] ) )
						$plugins[ $path ] = $plugin;
					$plugins[ $path ][ 'hooks' ][ $hook ][] = $item;
				}
			}
		}

		return (
			compact (
				'themes',
				'plugins'
			)
		);
	}
	
	public function do_dequeue_scripts() 
	{		
		$this->dequeue_handles( 
			(array) apply_filters( 
				'wpc_theme_compatibility_removed_scripts', 
				(array) ( 
					get_option( 
						'wpc_theme_compatibility_removed_scripts'
					)
				)
			), 
			'scripts' 
		);
	}

	public function do_dequeue_styles() 
	{
		$this->dequeue_handles( 
			(array) apply_filters( 
				'wpc_theme_compatibility_removed_styles', 
				(array) ( 
					get_option( 
						'wpc_theme_compatibility_removed_styles'
					)
				)
			), 
			'styles' 
		);
	}

	public function do_dequeue_all_theme_styles( $styles ) 
	{
		if ( 'yes' === get_option( 'wpc_theme_compatibility_disable_styles_wp_theme', 'yes' ) ) {
			$theme_handles = $this->sanitize_enquete( 'styles', true, false );
			foreach ( $theme_handles as $key => $theme ) {
				foreach ( $theme['children'] as $children ) {
					if ( ! in_array( $children['id'], $styles ) ) {
						$styles[] = $children['id'];
					}
				}
			}
		}		
		return $styles;

	}

	public function do_dequeue_all_theme_scripts( $scripts ) 
	{
		if ( 'yes' === get_option( 'wpc_theme_compatibility_disable_scripts_wp_theme', 'yes' ) ) {
			$theme_handles = $this->sanitize_enquete( 'scripts', true, false );
			foreach ( $theme_handles as $key => $theme ) {
				foreach ( $theme['children'] as $children ) {
					if ( ! in_array( $children['id'], $scripts ) ) {
						$scripts[] = $children['id'];
					}				
				}
			}
		}
		return $scripts;

	}

	public function do_dequeue_plugin_styles( $styles ) 
	{
		$plugins = apply_filters( 
			'wpc_theme_compatibility_dequeue_plugin_styles', 
			array(
				'woocommerce/woocommerce.php' => array( )
			)
		);
		
		if( !empty( $plugins ) ) {
			foreach ( $plugins as $key => $handles ) {
				if( !empty( $handles ) ) {
					foreach ( $handles as $handle ) {
						$styles[] = $handle;
					}	
				} else {
					$all_styles_enquete  = self::list_enquete( 'styles' );					
					if( isset( $all_styles_enquete[ 'plugins' ][ $key ] ) ) {						
						$plugin_data = $all_styles_enquete[ 'plugins' ][ $key ];
						foreach ( $plugin_data[ 'styles' ] as $handle => $path ) {
							$styles[] = $handle;
						}
					}
				}
			}	
		}
		return $styles;

	}

	public function do_dequeue_plugin_scripts ( $scripts ) 
	{
		$plugins = apply_filters( 
			'wpc_theme_compatibility_dequeue_plugin_scripts', 
			array(
				'woocommerce/woocommerce.php' => array( 'selectWoo' )
			)
		);
		
		if( !empty( $plugins ) ) {
			foreach ( $plugins as $key => $handles ) {
				if( !empty( $handles ) ) {
					foreach ( $handles as $handle ) {
						$scripts[] = $handle;
					}	
				} else {
					$all_scripts_enquete  = self::list_enquete( 'scripts' );					
					if( isset( $all_scripts_enquete['plugins'][ $key ] ) ) {						
						$plugin_data = $all_scripts_enquete['plugins'][ $key ];						
						foreach ( $plugin_data[ 'scripts' ] as $handle => $path ) {
							$scripts[] = $handle;
						}
					}
				}
			}	
		}
		return $scripts;

	}

	public function dequeue_handles( $dequeue_handles, $type ) 
	{
		foreach ( $dequeue_handles as $handle ) {
			if ( 'styles' === $type ) {
				wp_dequeue_style( $handle );
			} elseif ( 'scripts' === $type ) {
				wp_dequeue_script( $handle );
			}
		}

	}
	
	public function do_remove_all_theme_hooks( $hooks ) 
	{
		if ( 'yes' === get_option( 'wpc_theme_compatibility_remove_hooks_wp_theme', 'yes' ) ) {
			$list = array(
				$this->list_hooks( 'wp_head' )[ 'themes' ],
				$this->list_hooks( 'wp_footer' )[ 'themes' ],
				$this->list_hooks( 'wp_print_styles' )[ 'themes' ]
			);

			foreach ( $list as $themes ) {
				foreach ( $themes as $theme ) {
					if ( isset( $theme['hooks'] ) ) {
						foreach ( $theme['hooks'] as $hook => $items ) {
							foreach( $items as $item ) {
								$hooks[ $hook ][] = $item;
							}
						}
					}
				}
			}
		}
		
		return $hooks;

	}

	public function do_remove_hooks() 
	{
		$this->remove_hooks( 
			apply_filters( 
				'wpc_theme_compatibility_removed_hooks', 
				array()
			)
		);
	}

	public function remove_hooks( $hooks ) 
	{
		foreach ( $hooks as $hook => $items ) {
			foreach( $items as $item ) {
				remove_action( $hook, $item['id'], $item['priority'] );
			}
		}

	}
	
	public function add_style_tag_in_template() 
	{	
	?>
		<style id="wpc-theme-compatibility-css">
			<?php do_action( 'wpc_theme_compatibility_print_css' ); ?>
		</style>

		<style id="wpc-theme-compatibility-css-saved">
			<?php 
				$conetnt = get_option( 'wpc_theme_compatibility_custom_css', false );
				if ( false !== $conetnt && is_string( $conetnt ) ) {
					_e(
						$conetnt
					);
				}
			?>
		</style>
	<?php
	}

	public function add_script_tag_in_template() 
	{	
	?>
		<script id="wpc-theme-compatibility-js">
			<?php do_action( 'wpc_theme_compatibility_print_js' ); ?>
		</script>

		<script id="wpc-theme-compatibility-js-saved">
			<?php 
				$conetnt = get_option( 'wpc_theme_compatibility_custom_js', false );
				if ( false !== $conetnt && is_string( $conetnt ) ) {
					_e(
						$conetnt
					);
				}
			?>
		</script>
	<?php
	}
	
}