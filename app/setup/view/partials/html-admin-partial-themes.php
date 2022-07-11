<?php

/**
 * Theme panel partial
 *
 * @since 1.0.0
 */

do_action( 'wpc_admin_themes_before' ); 
?>

<div id="themes" class="panel">
	<div class="wpc-blocks">
		<?php
			wpc_admin_addon_column( 'themes', 'active' );
			wpc_admin_addon_column( 'themes', 'installed' );
			wpc_admin_addon_column( 'themes', 'install' );
			wpc_admin_addon_column( 'themes', 'demo' );
		?>
	</div>
</div>

<?php do_action( 'wpc_admin_themes_after' ); ?>