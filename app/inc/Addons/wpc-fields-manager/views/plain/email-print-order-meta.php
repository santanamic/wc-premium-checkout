<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $fields ) ) {
	echo esc_html( wc_strtoupper( __( 'Additional information', WPC_SLUG ) ) ) . "\n\n";

	foreach ( $fields as $field ) {
		echo wp_kses_post( $field['label'] ) . ': ' . wp_kses_post( $field['value'] ) . "\n";
	}
}

?>