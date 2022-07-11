<?php
/**
 * The code that runs during this plugin activation and deactivation
 */
add_action( 'activate_' . WPC_BASENAME, 'wpc_plugin_activate' );
add_action( 'deactivate_' . WPC_BASENAME, 'wpc_plugin_deactivate' );

/**
 * The code that runs during any plugin activation and deactivation
 */
add_action( 'activated_plugin', 'wpc_plugin_any_activate' );
add_action( 'deactivated_plugin', 'wpc_plugin_any_deactivate' );

/**
 * Essential hook for plugin core run
 */
add_action( 'init', 'wpc_plugin_run' );
add_action( 'plugins_loaded', 'wpc_plugin_i18n' );
add_filter( 'wpc_load_addon', 'wpc_plugin_load_addons', PHP_INT_MAX );

/**
 * Initial hook for plugin admin run
 */
add_action( 'admin_action_wpc_plugin_active', 'wpc_admin_plugin_active' );
add_action( 'wp_ajax_wpc_tmp_preview', 'wpc_plugin_tmp_preview' );
add_action( 'update-custom_wpc-install-plugin', 'wpc_admin_plugin_install' );
add_filter( 'install_plugin_complete_actions', 'wpc_admin_install_plugin_complete_actions', 10, 3 );

/**
 * Initial hook for plugin run in customize
 */
add_action( 'customize_register', 'wpc_customize_builder_commmon' );
add_action( 'customize_controls_print_styles', 'wpc_customize_enqueue' );
add_action( 'customize_register', 'wpc_customize_run_addons' );

add_action( 'customize_preview_init', 'wpc_preview_enqueue' );

/**
 * Initial hook for template load
 */
add_filter( 'template_include', 'wpc_template_include' );
add_filter( 'wpc_template_file', 'wpc_template_default_path' );
add_filter( 'wpc_template_head', 'wp_head' );
add_filter( 'wpc_template_footer', 'wp_footer' );
add_filter( 'wpc_template_content', 'wpc_template_default_content' );
add_filter( 'wpc_template_callback', 'wpc_plugin_load_addons' );

