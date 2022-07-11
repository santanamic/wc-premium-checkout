<?php
/**
 * Page callback
 *
 * @since 1.0.0
 */

do_action( 'wpc_before_admin' );

?>

<?php do_action( 'wpc_admin_before' ); ?>
<div class="wrap about-wrap full-width-layout">
	<?php if( isset( $_REQUEST['welcome'] ) ) : ?>
		<?php wpc_admin_include_view( 'welcome_page' ); ?>
	<?php else : ?>
		<?php wpc_admin_include_view( 'partial_intro' ); ?>
		<?php wpc_admin_include_view( 'partial_tabs' ); ?>
		<form method="POST" action="">
				<?php wpc_admin_include_view( 'partial_themes' ); ?>
				<?php wpc_admin_include_view( 'partial_extensions' ); ?>
				<?php //wpc_admin_include_view( 'partial_settings' ); ?>
				<?php //wpc_admin_include_view( 'partial_help' ); ?>
		</form>
	<?php endif ?>
</div>
<?php do_action( 'wpc_admin_after' ); ?>