<?php

/**
 * Extensions panel partial
 *
 * @since 1.0.0
 */

do_action( 'wpc_admin_extensions_before' ); 
?>

<div id="extensions" class="panel">
	<div class="wpc-blocks">
		<?php
			wpc_admin_addon_column( 'extensions', 'installed' );
			wpc_admin_addon_column( 'extensions', 'install' );
			wpc_admin_addon_column( 'extensions', 'demo' );
		?>
	</div>
</div>

<?php do_action( 'wpc_admin_extensions_after' ); ?>