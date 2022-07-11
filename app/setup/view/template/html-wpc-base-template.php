<?php
/**
 * Provide a public-facing view for the plugin
 *
 * @since      1.0.0
 */

do_action( 'wpc_template_init' );
?>

<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="generator" content="WooCommerce Premium Checkout">
		<?php do_action( 'wpc_template_head' ); ?>
	</head>
	<body <?php wpc_body_class(); ?>>
		<?php do_action( 'wpc_template_content' ); ?>
		<?php do_action( 'wpc_template_footer' ); ?>
	</body>
</html>