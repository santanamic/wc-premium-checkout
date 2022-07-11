<?php defined( 'ABSPATH' ) || exit; ?>

<h2 class="content-box-title"><span><?php esc_html_e( 'Addresses', 'woocommerce' ); ?></span></h2>
<section class="content-box-frame"> 
	<div class="woocommerce-shipping-fields">
		<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

			<h3 class="content-box-subtitle"><span><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></span></h3>

			<div id="ship-to-different-address">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
				</label>
			</div>

			<div class="shipping_address">

				<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

				<div class="woocommerce-shipping-fields__field-wrapper">
					<?php
					$fields = $checkout->get_checkout_fields( 'shipping' );

					foreach ( $fields as $key => $field ) {
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}
					?>
				</div>

				<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

			</div>

		<?php endif; ?>
	</div>
</section>