<?php
/**
 * Intro partial
 *
 * @since 1.0.0
 */

?>
<div class="col">
	<h1 class="about-title"><img class="about-logo" src="<?php echo WPC_LOGO_URL; ?>" alt="<?php echo WPC_TITLE ?>"><sup><?php echo WPC_VERSION; ?></sup></h1>
	<br>
	<p><?php _e( 'The <code>' . WPC_TITLE . '</code> has many personalization fields to change the layout of the checkout page. You can completely modify the appearance of the checkout page, including support for adding or removing fields from the form, input validations, fields masks, multistep, support for videos, texts, images and shortcodes. On this page you can install add-ons that extend the plug-in natives functionalitys.', WPC_SLUG ); ?></p>
	<p><strong><?php printf( esc_html__( 'Find out more at %1$splugin documentation%2$s.', WPC_VERSION ), '<a href="https://wordpress.org/plugins/wc-premium-checkout/" target="_blank">', '</a>' ); ?></strong></p>
</div>