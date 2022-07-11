<?php defined( 'ABSPATH' ) || exit;

/**
 * The plugin bootstrap file
 *
 * Plugin Name:       WPC - WooCommerce Premium Checkout
 * Plugin URI:        https://santanamic.github.io/wc-premium-checkout/
 * Description:       WPC is the best Checkout Page Customizer plugin.
 * Version:           1.0.0
 * Author:            WILLIAN SANTANA
 * Author URI:        https://github.com/santanamic
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       WPC
 * Domain Path:       /app/i18n
 */
 
require_once( __DIR__ . '/app/vendor/autoload.php' );
require_once( __DIR__ . '/app/setup/etc/config.php' );
require_once( __DIR__ . '/app/setup/helper.php' );
require_once( __DIR__ . '/app/setup/admin.php' );
require_once( __DIR__ . '/app/setup/check.php' );
require_once( __DIR__ . '/app/setup/plugin.php' );
require_once( __DIR__ . '/app/setup/customize.php' );
require_once( __DIR__ . '/app/setup/template.php' );
require_once( __DIR__ . '/app/hooks.php' );