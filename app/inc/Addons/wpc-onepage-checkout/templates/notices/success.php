<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

?>

<div class="message is-success message-icon line-icon success-icon"><?php foreach ( $messages as $message ) : ?><div class="woocommerce-message message-description " role="alert">
	<?php
		echo wc_kses_notice( $message );
	?>
</div><?php endforeach; ?></div>
