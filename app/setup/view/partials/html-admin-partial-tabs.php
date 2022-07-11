<?php
/**
 * Tabs partial
 *
 * @since 1.0.0
 */

$tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';

?>

<section id="wpc-admin-partial-tabs">
	<h2 class="nav-tab-wrapper">
		<?php do_action( 'wpc_admin_before_tabs' ); ?>
			<?php echo '<a href="#themes" class="nav-tab ' . esc_attr( 'themes' === $tab || empty( $tab ) ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Themes', WPC_SLUG ) . '</a>'; ?>
			<?php echo '<a href="#extensions" class="nav-tab">' . esc_html__( 'Extensions', WPC_SLUG ) . '</a>'; ?>
			<?php //echo '<a href="#settings" class="nav-tab">' . esc_html__( 'Settings', WPC_SLUG ) . '</a>'; ?>
			<?php //echo '<a href="#help" class="nav-tab ' . esc_attr( 'help' === $tab ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Help', WPC_SLUG ) . '</a>'; ?>
		<?php do_action( 'wpc_admin_after_tabs' ); ?>
	</h2>
</section>