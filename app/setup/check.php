<?php defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpc_is_available_system' ) ) {

	/**
	 * Check if the environment is avalible to plugin run
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	function wpc_is_available_system() 
	{
		if ( null !== WPC_REQUIRE_SYSTEM ) {
			foreach ( WPC_REQUIRE_SYSTEM as $option => $value ) {
				switch ( $option ) {
					case 'php-version':
						if ( ! wpc_valid_php_version ( $value ) ) {
							wpc_admin_add_notice( 
								'error', 				
								__( 
									sprintf( 
										'The version of PHP on your host is not compatible with the <code>%1$s</code> plugin. Contact your host to resolve the issue.',
										WPC_TITLE
									), 
									WPC_SLUG
								)
							);
							return false;
						}
						break;
					case 'wp-version':
						if ( ! wpc_valid_wp_version ( $value ) ) {
							wpc_admin_add_notice( 
								'error', 				
								__( 
									sprintf( 
										'The version of WordPress on your host is not compatible with the <code>%1$s</code> plugin. Update your WordPress to resolve the issue.',
										WPC_TITLE
									), 
									WPC_SLUG
								)
							);
							return false;
						}
						break;
					case 'wc-version':
						if ( ! wpc_valid_wc_version ( $value ) ) {
							wpc_admin_add_notice( 
								'error',
								__( 
									sprintf( 
										'The version of WooCommerce on your WordPress is not compatible with the <code>%1$s</code> plugin. Update your WooCommerce to resolve the issue.',
										WPC_TITLE
									), 
									WPC_SLUG
								)								
							);
							return false;
						}
						break;
				}
			}
		}
		return true;
	}
}


if ( ! function_exists( 'wpc_is_available_plugins' ) ) {

	/**
	 * Check if the dependencies is avalible to plugin run
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	function wpc_is_available_plugins() 
	{
		if ( null !== WPC_REQUIRE_PLUGINS ) {	
			if ( $missing_plugins = wpc_list_wp_required_plugins( WPC_REQUIRE_PLUGINS ) ) {
				foreach ( $missing_plugins as $key => $plugin ) {
					wpc_admin_add_notice( 
						'notice notice-info is-dismissible', 				
						__( 
							sprintf( 
								'<code>%3$s</code> cannot work. Install and activate the following plugin: <a href="%1$s">%2$s</a> to fix.', 
								$plugin['url'], 
								$plugin['name'],
								WPC_TITLE
							), 
							WPC_SLUG 
						) 
					);
				}
				
				return false;
			}
		}
		return true;
	}
}