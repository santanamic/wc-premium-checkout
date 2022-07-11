<?php defined( 'ABSPATH' ) || exit;

define( 'WPC_SETUP_CONFIG', ['bootstap' => 'hooks.php', 'welcome'  => true] );
define( 'WPC_REQUIRE_SYSTEM', ['php-version' => '>=7.0', 'wp-version' => '>=4.9', 'wc-version' => '>=3.6'] );
define( 'WPC_REQUIRE_PLUGINS', [ 'woocommerce/woocommerce.php' => [ 'name' => 'WooCommerce', 'url' => 'https://wordpress.org/plugins/woocommerce/'] ] );

define( 'WPC_SLUG', 'WPC' );
define( 'WPC_TITLE', __( 'WooCommerce Premium Checkout', WPC_SLUG ) );
define( 'WPC_URL', __( 'http://wpcplugin.com', WPC_SLUG ) );
define( 'WPC_VERSION', '1.0.0' );

define( 'WPC_PATH', dirname( __FILE__, 3 ) );
define( 'WPC_BASENAME', sprintf( '%s/bootstrap.php', 'wc-premium-checkout' ) );
define( 'WPC_URI', trailingslashit( plugin_dir_url( WPC_PATH ) ) );

define( 'WPC_LOGO_URL', WPC_URI . 'app/assets/img/logo.png' );