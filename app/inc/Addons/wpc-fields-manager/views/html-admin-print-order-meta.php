<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! empty( $fields ) ) : ?>
	<div class="clear"></div>
	<div class="wpc-field-manager-order-data">
		<h4><?php _e( 'Additional information', WPC_SLUG ); ?></h4>
		<p>
			<?php foreach ( $fields as $field ) : ?>
				<strong><?php echo wp_kses_post( $field['label'] ); ?>:</strong> <span class="text"><?php echo wp_kses_post( $field['value'] ); ?></span> <br />
			<?php endforeach; ?>
		</p>
	</div>
<?php endif; ?>