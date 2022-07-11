<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

?>
<div class="message is-error"><ul class="woocommerce-error message-icon error-icon message-list" role="alert">
	<?php foreach ( $messages as $message ) : ?>
		<li>
			<?php
				echo wc_kses_notice( $message );
			?>
		</li>
	<?php endforeach; ?>
</ul></div>