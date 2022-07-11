<?php

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
	<div class="woocommerce-terms-and-conditions-wrapper">
		<?php
		do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>
		
	</div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
