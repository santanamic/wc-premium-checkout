<?php defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpc_plugin_register_page' ) ) {

	/**
	 * Register a plugin menu page
	 *
	 * @since    1.0.0
	 * @param    bool    $template   The file for output the content for this page
	 * @param    bool    $icon_url   The URL to the icon to be used for this menu in admin
	 * @param    bool    $position   The position in the menu order this item should appear
	 * @return   void
	 */
	function wpc_plugin_register_page( $template = '', $icon_url = '', $position = null  ) 
	{		
		add_menu_page(
			__( 'WC Premium Checkout', WPC_SLUG ),
			__( 'Premium Checkout', WPC_SLUG ),
			'manage_options',
			WPC_SLUG,
			function() use ( $template ) {
				return (
					include ( $template )
				);
			}
		);
		
	}
	
}

if ( ! function_exists( 'wpc_admin_register_submenu' ) ) {

	/**
	 * Register a plugin sub page in WC menu
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_admin_register_submenu() 
	{		
		add_submenu_page(
			'woocommerce',
			WPC_TITLE,
			str_replace( 
				'WooCommerce ', 
				'<i style="font-style: normal;font-size: 1.5em;position: relative;top: 0.04em;left: -0.3em; margin-left: 0.2em;">★</i>', 
				WPC_TITLE 
			),
			'manage_options',
			WPC_SLUG,
			function() {
				return (
					include ( 
						WPC_PATH . '/setup/view/callback/html-admin-callback-page.php' 
					)
				);
			}
		);
		
	}
	
}

if ( ! function_exists( 'wpc_admin_add_notice' ) ) {

	/**
	 * Adds a message in the administrative panel
	 *
	 * @since    1.0.0
	 * @param    bool    $class   The class priority supported by WordPress
	 * @param    bool    $msg     The message body in plain text or HTML
	 * @return   void
	 */
	function wpc_admin_add_notice( string $class = 'notice notice-info is-dismissible', string $msg = '' ) 
	{			
		add_action( 
			'admin_notices', 
			function() use ( $class, $msg ) {
				return ( 
					wpc_include_view( 
						WPC_PATH . '/setup/view/html-admin-notice.php', 
						array(
							'class'   => $class,
							'message' => $msg,
						),
						true
					)
				);
			} 
		);
	}

}

if ( ! function_exists( 'wpc_admin_enqueue' ) ) {

	/**
	 * Register the JavaScrip and CSS for admin
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_admin_enqueue( $type ) 
	{
		wp_enqueue_style( 
			'wpc', 
			WPC_URI . 'app/assets/css/admin.css', 
			array(), 
			WPC_VERSION,
			'all' 
		);
		
		wp_enqueue_script( 
			'wpc', 
			WPC_URI . 'app/assets/js/admin.js', 
			array( 'jquery' ), 
			WPC_VERSION, 
			false 
		);

	}
	
}

if ( ! function_exists( 'wpc_admin_include_view' ) ) {

	/**
	 * Include partial template
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_admin_include_view( $type ) 
	{
		switch( $type ) {
			case 'partial_intro':
				require( WPC_PATH . '/setup/view/partials/html-admin-partial-intro.php' );
				break;
			case 'partial_tabs':
				require( WPC_PATH . '/setup/view/partials/html-admin-partial-tabs.php' );
				break;
			case 'partial_themes':
				require( WPC_PATH . '/setup/view/partials/html-admin-partial-themes.php' );
				break;
			case 'partial_extensions':
				require( WPC_PATH . '/setup/view/partials/html-admin-partial-extensions.php' );
				break;
			case 'partial_settings':
				require( WPC_PATH . '/setup/view/partials/html-admin-partial-settings.php' );
				break;
			case 'partial_help':
				require( WPC_PATH . '/setup/view/partials/html-admin-partial-help.php' );
				break;
			case 'welcome_page':
				require( WPC_PATH . '/setup/view/html-admin-welcome.php' );
				break;
		}
	}
	
}

if ( ! function_exists( 'wpc_admin_addon_card' ) ) {

	/**
	 * Include admin loop template for addon cards
	 *
	 * @since    1.0.0
	 * @return   void
	 */
	function wpc_admin_addon_card( $addon ) 
	{
	?>
	<div class="wpc-block" data-id="<?php echo esc_attr( $addon['slug'] ); ?>">
	<div class="wpc-card <?php echo esc_attr( implode( ' ', $addon['classes'] ) ); ?>">
		<div class="wpc-card-top">
			<a href="<?php _e( $addon['slug'] ) ?>" class="column-icon">
				<img src="<?php _e( $addon['thumbnail'] ) ?>">
			</a>
			<div class="column-name">
				<h4>
					<a href="<?php _e( $addon['url'] ) ?>"><?php _e( $addon['title'] ) ?></a>
					<?php sprintf( 'Version: %s', $addon['version'] ) ?></span>
				</h4>
			</div>
			<div class="column-description">
				<div class="description">
					<p><?php _e( $addon['description'] ) ?></p>
				</div>
				<p class="author">
					<cite><?php _e( 'By', WPC_SLUG ) ?> <a href="<?php _e( $addon['author_url'] ) ?>" target="_blank"><?php _e( $addon['author'] ) ?></a></cite>
				</p>
			</div>
		</div>
		<div class="wpc-card-bottom">
			<div class="column-rating">
				<div class="column-rating">
					<?php
						wp_star_rating(
							array(
								'rating' => ( 5 / 5 * 100 ), // temporarily sets 5 for all
								'type'   => 'percent',
							)
						);
					?>
				</div>
			</div>
			<div class="column-actions">
				<?php
				//default action
				$action = array( 
					'text' => __( 'View Demo ⧉', WPC_SLUG ), 
					'url'   => ( $addon['preview'] ?? $addon['url'] )  
				);
				if ( in_array( 'installed', $addon['classes'] ) ) {
					$autofocus = isset( $addon['section'] ) ? 'section' : 'panel';
					$autofocus_id = 'panel' === $autofocus ? 'wpc' : $addon['section'];
					$action = array( 
						'text' => __( 'Maneger', WPC_SLUG ), 
						'url'   => sprintf( '%1$s?autofocus[%2$s]=%3$s&url=%4$s?' , admin_url( 'customize.php' ), $autofocus, $autofocus_id, wc_get_checkout_url() ),
					);
					
				} 

				if ( in_array( 'install', $addon['classes'] ) ) {
					$action = array( 
						'text' => __( 'Install', WPC_SLUG ), 
						'url'   => sprintf( 
							'%1$s?action=wpc-install-plugin&addon=%2$s&nonce=%3$s', admin_url( 'update.php' ), 
							urlencode( 
								http_build_query( 
									$addon 
								) 
							), 
							wp_create_nonce( 
								'wpc-install-plugin_' . $addon['slug'] 
							) 
						),
					);
					
				} 
				$button_action = apply_filters( 
					'wpc_admin_card_actions', 
					$action,
					$addon
				);
				?>
				<a href="<?php echo $button_action['url'] ?>" target="_blank" class="button button-primary"><span> <?php echo $button_action['text'] ?> </span></a>
			</div>
		</div>
	</div>
	</div>
		<?php
	}
	
}

if ( ! function_exists( 'wpc_admin_addon_column' ) ) {
	/**
	
	* Displays each addons.
	 *
	 * @since 1.0.0
	 * @param   string   $group   The column group.
	 */
	 
	function wpc_admin_addon_column( $type, $group ) 
	{
		if ( 'themes' === $type && 'active' === $group ) {
			$theme = get_option( 'wpc_theme_selector_active' );
			$_addon = WPC()->addons->get_by_slug( $theme );
			
			if ( false != $_addon ) {
				$addon = (array) $_addon;
				$addon['classes'] = array( 'themes', 'active' );
				
				wpc_admin_addon_card( $addon );
			}
		} 
		else if ( 'installed' === $group ) {
			$addons = WPC()->addons->get( $type );
			foreach ( $addons as $slug => $addon ) {
				if ( 'themes' === $type ) {
					if ( 'example_addon' === $slug ) { //remove active theme in installed cards
						continue;
					}
				}
				
				$addon = (array) $addon;				
				$addon['classes'] = array( $type, 'installed' );

				wpc_admin_addon_card( $addon );
			}
			
		} else if ( 'install' === $group || 'demo' === $group ) {
			$response = wp_remote_request(
				sprintf( 'https://wc-premium-checkout.github.io/json-remote-addons/%1$s-%2$s.json' , $group, $type ),
				array(
					'method' => 'GET',
					'timeout' => 2,
				)
			);
			
			if( !is_wp_error( $response ) && isset( $response['body'] ) ){
				$content = json_decode( $response['body'], true );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					$installed = (array) WPC()->addons->get( 'all' );
					$addons = array_diff_key( $content, $installed ); // if exists, remove installed addons

					foreach ( $addons as $slug => $addon ) {
						$addon = (array) $addon;				
						$addon['classes'] = array( $type, $group );

						wpc_admin_addon_card( $addon );
					}
				}
			}			
		}
	}	
}

if ( ! function_exists( 'wpc_admin_plugin_install' ) ) {
	/**
	 * Custom function for plugin install
	 *
	 * @since 1.0.0
	 */
	 
	function wpc_admin_plugin_install() 
	{
		if ( ! current_user_can('install_plugins') )
			wp_die( __( 'Sorry, you are not allowed to install plugins on this site.', WPC_SLUG ) );

		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
			
		parse_str( $_REQUEST['addon'], $api['wpc'] );
		$slug = $api['wpc']['slug'];

		wp_verify_nonce( 'wpc-install-plugin_' . $api['wpc']['slug'] );
		
		$title = __( 'WPC Plugin Installation', WPC_SLUG );
		$parent_file = 'plugins.php';
		$submenu_file = 'plugin-install.php';

		require_once(ABSPATH . 'wp-admin/admin-header.php');

		$title = sprintf( __(' Installing Plugin: %s' ), $api['wpc']['title'] . ' ' . $api['wpc']['version'] );
		$nonce = 'wpc-install-plugin_' . $api['wpc']['slug'];
		$url = 'update.php?action=wpc-install-plugin&addon=' . $_REQUEST['addon'];
		if ( isset($_GET['from']) )
			$url .= '&from=' . urlencode(stripslashes($_GET['from']));

		$type = 'web'; //Install plugin type, From Web or an Upload.

		$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'slug', 'api') ) );
		$return = $upgrader->install( $api['wpc']['download'] );

		do_action( 'wpc_plugin_install_attempt', $api, $return );

		include( ABSPATH . 'wp-admin/admin-footer.php' );
	}
}

if ( ! function_exists( 'wpc_admin_install_plugin_complete_actions' ) ) {
	
	/**
	 * Change links in installation plugin page
	 *
	 * @since 1.0.0
	 */ 
	function wpc_admin_install_plugin_complete_actions( $install_actions, $data, $plugin ) 
	{
		if ( $data['wpc'] ) {		
			$plugin = wp_unslash( $plugin ) ?: '';
			$wpc_install_actions['activate_plugin'] = '<a class="button button-primary" href="' . wp_nonce_url( 'plugins.php?action=wpc_plugin_active&amp;plugin=' . urlencode( $plugin ) . '&data=' . urlencode( http_build_query( $data ) ), 'wpc-activate-plugin_' . $plugin ) . '" target="_parent">' . __( 'Activate Plugin', WPC_SLUG ) . '</a>';
			$wpc_install_actions['plugins_page'] = '<a href="' . self_admin_url( 'admin.php' ) . '?page=wc-premium-checkout" target="_parent">' . __( 'Return to WPC Page', WPC_SLUG ) . '</a>';
			$install_actions = $wpc_install_actions;
		}
		
		return $install_actions;
	}
}

if ( ! function_exists( 'wpc_admin_plugin_active' ) ) {
	
	/**
	 * For active plugin and redirect for WPC admin page
	 *
	 * @since 1.0.0
	 */
	function wpc_admin_plugin_active() 
	{
		$plugin = isset($_REQUEST['plugin']) ? wp_unslash( $_REQUEST['plugin'] ) : '';
		$data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';

		if ( ! current_user_can( 'activate_plugin', $plugin ) ) {
			wp_die( __( 'Sorry, you are not allowed to activate this plugin.', WPC_SLUG ) );
		}

		check_admin_referer( 'wpc-activate-plugin_' . $plugin );
		
		if ( '' != $plugin ) {
			$result = activate_plugin($plugin, self_admin_url('admin.php?page=wc-premium-checkout&addon=' . urlencode( $plugin ) . '&data=' . $data ), is_network_admin() );
			if ( is_wp_error( $result ) ) {
				if ( 'unexpected_output' == $result->get_error_code() ) {
					$redirect = self_admin_url('admin.php?page=true&charsout=' . strlen($result->get_error_data()) . '&plugin=' . urlencode( $plugin ) . "&plugin_status=$status&paged=$page&s=$s");
					wp_redirect(add_query_arg('_error_nonce', wp_create_nonce('plugin-activation-error_' . $plugin), $redirect));
					exit;
				} else {
					wp_die( $result );
				}
			}
		} else {
			wp_redirect( self_admin_url( 'plugins.php' ) );
		}
	}
}