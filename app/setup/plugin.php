<?php defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpc_plugin_activate' ) ) {

	/**
	 * Plugin activate call function
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_activate() 
	{
		add_option( 'wpc_plugin_activated', true );
	}

}

if ( ! function_exists( 'wpc_plugin_deactivate' ) ) {

	/**
	 * Plugin deactivation call function
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_deactivate() 
	{
	}
	
}

if ( ! function_exists( 'wpc_plugin_load_addons' ) ) {

	/**
	 * Plugin load addon
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	function wpc_plugin_load_addons( $addon = null ) 
	{		
		$is_active = 'yes' === get_option( 'wpc', 'no' );
		
		if ( !is_admin() && !wpc_customize_on_embed() ) {
			return $is_active;
		} else if ( ( is_customize_preview() && !wpc_customize_on_embed() ) ) {	
			update_option( 'wpc_temp_preview', $is_active );
			return true;
		} else if( wpc_customize_on_embed() ) {
			if ( true == get_option( 'wpc_temp_preview', false ) ) {
				return true;
			} else {
				return false;
			}
		} 
		return $is_active;
		
	}
}

if ( ! function_exists( 'wpc_plugin_tmp_preview' ) ) {

	/**
	 * Enable and disable plugin temp preview.
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	function wpc_plugin_tmp_preview( $status ) 
	{		
		check_ajax_referer( 'wpc-customizer', 'security' );

		if( 1 == $_REQUEST['status'] ) {
			update_option( 'wpc_temp_preview', true );
		} else {
			update_option( 'wpc_temp_preview', false );
		}
	
		wp_send_json_success( get_option( 'wpc_temp_preview' ) ); die;		
	}
}

if ( ! function_exists( 'wpc_plugin_load' ) ) {

	/**
	 * Begins load of the plugin.  
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_load() 
	{
		if ( wpc_customize_on_embed() ) {
			add_action( 'customize_preview_init', 'wpc_plugin_run' );
		} else {
			wpc_plugin_run();
		}
	}

}

if ( ! function_exists( 'wpc_plugin_run' ) ) {

	/**
	 * Begins run of the plugin. 
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_run() 
	{
		if ( wpc_is_available_system() && wpc_is_available_plugins() ) {
			if ( is_admin() ) { 
				wpc_plugin_admin_load();
				wpc_plugin_open_welcome_page();
			}
			do_action( 'wpc_before_init' );
				WPC();	
			do_action( 'wpc_init' );
		}

	}

}

if ( ! function_exists( 'WPC' ) ) {

	/**
	 * Load plugin main instance
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function WPC() 
	{
		return (
			WPC\Main_Plugin::instance()
		);
	}

}

if ( ! function_exists( 'wpc_plugin_admin_load' ) ) {

	/**
	 * Begins execution of the plugin admin.
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_admin_load() 
	{		
		add_action( 'admin_menu', 'wpc_admin_register_submenu' );
		add_action( 'admin_enqueue_scripts', 'wpc_admin_enqueue' );
	
	}
	
}

if ( ! function_exists( 'wpc_plugin_any_activate' ) ) {

	/**
	 * Runs any time any plugin is successfully activated
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_any_activate() 
	{

	}
	
}

if ( ! function_exists( 'wpc_plugin_any_deactivate' ) ) {

	/**
	 * Runs any time any plugin is successfully de-activated
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_any_deactivate() 
	{

	}
	
}

if ( ! function_exists( 'wpc_plugin_open_welcome_page' ) ) {

	/**
	 * Open welcomopage after plugin activate
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_open_welcome_page() 
	{

		if ( get_option( 'wpc_plugin_activated' ) ) {
			delete_option( 'wpc_plugin_activated' );
			if ( ! headers_sent() ) {
				wp_redirect( add_query_arg( array( 'page' => WPC_SLUG, 'welcome' => true, 'domain' => get_site_url() ), admin_url( 'admin.php' ) ) );
			}
		}

	}
	
}
	
if ( ! function_exists( 'wpc_plugin_i18n' ) ) {

	/**
	 * Load the plugin text domain for translation
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_plugin_i18n() 
	{

	}
	
}
