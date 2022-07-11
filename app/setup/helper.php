<?php defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpc_is_wc_active' ) ) {

	/**
	 * Check if WC is active
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	function wpc_is_wc_active() 
	{	
		return ( 
			class_exists ( 
				'WC_Payment_Gateway' 
			)
		);
	}

}

if ( ! function_exists( 'wpc_get_wc_version' ) ) {

	/**
	 * Return WC version
	 *
	 * @since    1.0.0
	 * @return   string|null
	 */
	function wpc_get_wc_version() 
	{
		return (
			wpc_is_wc_active() 
				? WC_VERSION 
				: 0
		);
	}

}

if ( ! function_exists( 'wpc_is_wc_lt' ) ) {

	/**
	 * Checks if WC version is less than passed in version
	 *
	 * @since    1.0.0
	 * @param    string   $version   Version to check against
	 * @return   bool
	 */
	function wpc_is_wc_lt( string $version ) 
	{
		return (
			wpc_is_wc_active() 
				? version_compare( 
					wpc_get_wc_version(), 
					$version, '<' 
				) 
				: false
		);
	}

}

if ( ! function_exists( 'wpc_is_php_extension_loaded' ) ) {

	/**
	 * Find out if an PHP extension is loaded or return all extensions on the system
	 *
	 * @since    1.0.0
	 * @param    string    $extension   The extension name
	 * @return   bool
	 */
	function wpc_is_php_extension_loaded( string $extension = null ) 
	{
		return (
			null !== $extension
				? extension_loaded( 
					$extension 
				) 
				: false
		);
	}

}

if ( ! function_exists( 'wpc_get_wp_plugin_info' ) ) {

	/**
	 * Returns information for a specific installed plugin
	 *
	 * @since    1.0.0
	 * @param    string    $name   The plugin slug/folder 
	 * @return   array|null
	 */
	function wpc_get_wp_plugin_info( string $name = null ) 
	{
		$plugins = get_plugins();
		
		return (
			isset ( $plugins[$name] )
				? $plugins[$name] 
				: null
		);
	}

}

if ( ! function_exists( 'wpc_valid_wp_version' ) ) {

	/**
	 * Valid the WP installation version with the entry
	 *
	 * @since    1.0.0
	 * @param    string    $compare   The logic comparation with WP Version
	 * @return   bool
	 */
	function wpc_valid_wp_version( string $compare ) 
	{
		$wp_version = get_bloginfo( 'version' );

		return ( 
			version_compare ( 
				$wp_version,
				substr( $compare, 2 ), // number version
				substr( $compare, 0, 2 ) // logical operator
			)
		);
	}

}

if ( ! function_exists( 'wpc_valid_wc_version' ) ) {

	/**
	 * Valid the WC installation version with the entry
	 *
	 * @since    1.0.0
	 * @param    string    $compare   The logic comparation with WC Version
	 * @return   bool
	 */
	function wpc_valid_wc_version( string $compare ) 
	{
		$wc_version = wpc_get_wc_version();

		return ( 
			version_compare ( 
				$wc_version,
				substr( $compare, 2 ), // number version
				substr( $compare, 0, 2 ) // logical operator
			)
		);
	}

}

if ( ! function_exists( 'wpc_valid_php_version' ) ) {

	/**
	 * Valid the PHP installation version with the entry
	 *
	 * @since    1.0.0
	 * @param    string    $compare   The logic comparation with PHP Version
	 * @return   bool
	 */
	function wpc_valid_php_version( string $compare ) 
	{
		$php_version = phpversion();

		return ( 
			version_compare ( 
				$php_version,
				substr( $compare, 2 ), // number version
				substr( $compare, 0, 2 ) // logical operator
			)
		);
	}

}

if ( ! function_exists( 'wpc_list_wp_required_plugins' ) ) {

	/**
	 * Return required plugins not activated.
	 *
	 * @since    1.0.0
	 * @param    array    $plugins   Returns false if all required plugins are active or returns array of remaining plugins.
	 * @return   bool|array
	 */
	function wpc_list_wp_required_plugins( array $plugins ) 
	{
		$all_active_plugins = wpc_get_plugins( true );
		
		return (
			empty ( $plugins )
				? false
				: array_diff_key( 
					$plugins, 
					$all_active_plugins 
				)
		);
	}

}

if ( ! function_exists( 'wpc_get_plugins' ) ) {

	/**
	 * Return active plugins data or all installed plugins
	 *
	 * @since    1.0.0
	 * @param    bool    $return_active_plugins   For return all plugin only active plugins
	 * @return   bool
	 */
	function wpc_get_plugins( $return_active_plugins = false ) 
	{
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins    = get_plugins();
		$active_plugins = [];

		if ( true === $return_active_plugins ) {			
			$active_plugins_slugs = is_multisite() ? get_site_option( 'active_sitewide_plugins' ) : get_option( 'active_plugins' );
			
			foreach ( $active_plugins_slugs as $slug => $index ) {
				$plugin = is_multisite() ? $slug : $index;
				
				if ( array_key_exists( $plugin, $all_plugins ) ) {
					$active_plugins[$plugin] = $all_plugins[$plugin];
				}
			}
			return $active_plugins;
			
		} 
		return $all_plugins;
	}

}

if ( ! function_exists( 'wpc_sanitize_themes' ) ) {

	/**
	 * Sanitize array themes
	 *
	 * @since    1.0.0
	 * @param    array    $theme   The WP_Theme obj
	 * @return   array
	 */
	function wpc_sanitize_themes( $theme ) 
	{
		return (
			array(
				'Name' 		  => $theme->get( 'Name' ),
				'Description' => $theme->get( 'Description' ),
				'Author'      => $theme->get( 'Author' ),
				'AuthorURI'   => $theme->get( 'AuthorURI' ),
				'Version'     => $theme->get( 'Version' ),
				'TextDomain'  => $theme->get( 'TextDomain' ),
			)
		);
		
	}

}

if ( ! function_exists( 'wpc_get_themes' ) ) {

	/**
	 * Return active theme data or all installed themes
	 *
	 * @since    1.0.0
	 * @param    bool    $return_active_theme   For return all themes only active theme
	 * @return   bool
	 */
	function wpc_get_themes( $return_active_theme = false ) 
	{
		$all_themes = [];
		$installed_themes = wp_get_themes();
		
		foreach ( $installed_themes as $stylesheet => $theme ) {
			$all_themes[ $stylesheet ] = wpc_sanitize_themes( $theme );
		}
		
		if ( true === $return_active_theme ) {
			$active_theme = get_stylesheet();
			
			if ( isset( $all_themes[ $active_theme ] ) ) {
				return $all_themes[ $active_theme ];
			}
			
		}

		return $all_themes;
	}

}

if ( ! function_exists( 'wpc_include_view' ) ) {

	/**
	 * Return or print a partial template
	 *
	 * @since    1.0.0
	 * @param    bool     $filePath    
	 * @param    array    $variables    
	 * @param    bool     $print     
	 * @return   void
	 */
	function wpc_include_view( $filePath, $variables = array(), $print = false )
	{
		$output = null;
		if ( file_exists( $filePath ) ){
			extract( $variables );
			// Start output buffering
			ob_start();
			// Include the template file
			include $filePath;
			// End buffering and return its contents
			$output = ob_get_clean();
		}
		if ( $print ) {
			print $output;
		}
		
		return $output;
	}

}

if ( ! function_exists( 'wpc_fix_dir_separator' ) ) {

	/**
	 * Fixe directory separator
	 *
	 * @since    1.0.0
	 * @param    string     $path     
	 * @return   void
	 */
	function wpc_fix_dir_separator( $path )
	{		
		return (		
			str_replace( 
				array( 
					'/', 
					'\\', 
				), 
				DIRECTORY_SEPARATOR, 
				$path
			)
		);
	}

}

if ( ! function_exists( 'wpc_remove_url_protocol' ) ) {

	/**
	 * Remove HTTP protocol in URLs
	 *
	 * @since    1.0.0
	 * @param    string     $url     
	 * @return   void
	 */
	function wpc_remove_url_protocol( $url )
	{		
		return (		
			str_replace( 
				array( 
					'https://', 
					'http://', 
					'//' 
				), 
				null , 
				$url 
			)
		);
	}

}

if ( ! function_exists( 'wpc_url_to_array' ) ) {

	/**
	 * URL to array
	 *
	 * @since    1.0.0
	 * @param    string     $url  
	 * @param    bool       $remove_protocol  
	 * @return   void
	 */
	function wpc_url_to_array( $url )
	{
		$url = wpc_remove_url_protocol( $url );
		
		return (		
			array_filter(
				explode( 
					'/', 
					$url 
				),
				'strlen'
			)
		);
	}

}

if ( ! function_exists( 'wpc_path_to_array' ) ) {

	/**
	 * Path to array
	 *
	 * @since    1.0.0
	 * @param    string     $path  
	 * @return   void
	 */
	function wpc_path_to_array( $path )
	{		
		$path = wpc_fix_dir_separator( $path );
		
		return (		
			array_filter(
				explode( 
					DIRECTORY_SEPARATOR, 
					$path 
				),
				'strlen'
			)
		);
	}

}

if ( ! function_exists( 'wpc_is_root_folder' ) ) {

	/**
	 * Check WP root folder
	 *
	 * @since    1.0.0
	 * @param    string     $name  
	 * @return   void
	 */
	function wpc_is_root_folder( $name )
	{	
		$admin    = defined( 'WP_ADMIN_DIR' ) ? ( 'WP_ADMIN_DIR' != WP_ADMIN_DIR ? WP_ADMIN_DIR : 'wp-admin' ) : 'wp-admin';
		$content  = defined( 'WP_CONTENT_FOLDERNAME' ) ? WP_CONTENT_FOLDERNAME : 'wp-content';
		$includes = 'wp-includes';
		
		return(
			array_search( 
				$name, 
				compact( '
					admin', 
					'content', 
					'includes' 
				)  
			)
		);
	}

}

if ( ! function_exists( 'wpc_get_util_path' ) ) {

	/**
	 * Get path after root wp dir 
	 *
	 * @since    1.0.0
	 * @param    array     $path_arr  
	 * @return   void
	 */
	function wpc_get_util_path( $path_arr )
	{
		$dir_parts = explode( DIRECTORY_SEPARATOR, dirname( ABSPATH ) );
		
		return(
			array_values(
				array_diff( 
					$path_arr, 
					$dir_parts 
				)
			)
		);
	}

}

if ( ! function_exists( 'wpc_customize_on_embed' ) ) {

	/**
	 * Checks if the view is inside the iframe.
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	function wpc_customize_on_embed() 
	{	
		return (
			isset( 
				$_REQUEST['wp_customize'] 
			) 
			||
			isset( 
				$_REQUEST['customize_changeset_uuid'] 
			) 
		);		
	}
	
}

if ( ! function_exists( 'wpc_string_to_bool' ) ) {

	/**
	 * Converts a string (e.g. 'yes' or 'no') to a bool.
	 *
	 * @since   3.0.0
	 * @param   string   $string   String to convert.
	 * @return  bool
	 */
	function wpc_string_to_bool( $string ) {
		return is_bool( $string ) 
			? $string 
			: ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
	}
}

if ( ! function_exists( 'wpc_bool_to_string' ) ) {

	/**
	 * Converts a bool to a 'yes' or 'no'.
	 *
	 * @since   1.0.0
	 * @param   bool    $bool   String to convert.
	 * @return  string
	 */
	function wpc_bool_to_string( $bool ) {
		if ( ! is_bool( $bool ) ) {
			$bool = wc_string_to_bool( $bool );
		}
		return (
			true === $bool 
				? 'yes' 
				: 'no'
		);
	}
}

if ( ! function_exists( 'wpc_string_serialize' ) ) {

	/**
	 * Serialize any content.
	 *
	 * @since   1.0.0
	 * @param   string   $content   The input string.
	 * @return  bool
	 */
	function wpc_content_serialize( $content = '' ) {
		return (
			serialize( 
				$content 
			)
		);
	}
}

if ( ! function_exists( 'wpc_content_unserialize' ) ) {

	/**
	 * Unserialize any content.
	 *
	 * @since   1.0.0
	 * @param   string   $content   The input string.
	 * @return  bool
	 */
	function wpc_content_unserialize( $content = '' ) {
		return (
			unserialize( 
				$content 
			)
		);
	}
}

if ( ! function_exists( 'wpc_is_checkout' ) ) {

	/**
	 * Returns true when viewing the checkout page.
	 *
	 * @since   1.0.0
	 * @param   string|int   $post_id   The post ID
	 * @return  bool
	 */
	function wpc_is_checkout( $post_id = false ) {
		if( !$post_id ) {
			return (
				is_page( wc_get_page_id( 'checkout' ) ) || wc_post_content_has_shortcode( 'woocommerce_checkout' ) || apply_filters( 'woocommerce_is_checkout', false )
			); 
		} else {
			return (
				wc_get_page_id( 'checkout' ) == $post_id || wpc_post_content_has_shortcode( 'woocommerce_checkout', $post_id ) || apply_filters( 'woocommerce_is_checkout', false )
			);
		}
	}
}

if ( ! function_exists( 'wpc_post_content_has_shortcode' ) ) {

	/**
	 * Checks whether the content passed contains a specific short code.
	 *
	 * @since   1.0.0
	 * @param   string       $tag       Shortcode tag to check.
	 * @param   string|int   $post_id   The post ID
	 * @return  bool
	 */
	function wpc_post_content_has_shortcode(  $tag = '', $post_id = false ) {
		if( !$post_id ) {
			global $post; 
		} else {
			$post = get_post( $post_id );
		}
		
		return (
			is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $tag )
		);		
	}
}