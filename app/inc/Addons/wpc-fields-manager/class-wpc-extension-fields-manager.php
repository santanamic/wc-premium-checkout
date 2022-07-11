<?php

namespace WPC\Extension;

class Fields_Manager extends \WPC\Abstract_Addon 
{	
	public function __construct() 
	{		
		$this->section      =   'wpc_field_manager';
		$this->setting      =   'wpc_field_manager_list';
		
		$this->type         =   'extension';
		$this->slug         =   'wpc-fields-manager';
		$this->title        =   __( 'Fields Manager', WPC_SLUG );
		$this->description  =   __( 'Embedded extension for manage checkout fields. Support for new fields, field removal, field masks, pattern validation and HTML attribute customization.', WPC_SLUG );
		$this->author       =   __( 'WPC' );	
		$this->version      =   WPC_VERSION;
		$this->author_url   =   WPC_URL;
		$this->thumbnail    =   plugins_url( 'assets/img/thumbnail.svg', __FILE__ );
		$this->embedded     =   true;	

		add_action( 'customize_register', array( $this, 'customize_init' ) );
		add_action( 'wpc_template_init', array( $this, 'template_init' ) );
		add_filter( 'wpc_field_manager_saved_groups_and_fields', array( $this, 'before_return_saved_groups_and_fields' ), 1 );
		add_filter( 'wpc_field_manager_incontrol_group_field', array( $this, 'to_control_field_options_sanitize' ), 1, 3 );
		add_action( 'wpc_field_manager_incheckout_group_field', array( $this, 'to_checkout_field_options_sanitize' ), 1, 3 );
		add_filter( 'wpc_field_manager_to_save_field_options_sanitize', array( $this, 'to_save_field_options_sanitize' ), 1, 2 );
		add_action( 'woocommerce_checkout_fields', array( $this, 'to_checkout_fields_unify' ), 1 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'to_order_update_meta' ) );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'to_admin_print_order_meta' ) );
		add_action( 'woocommerce_email_order_meta', array( $this, 'to_email_print_order_meta'), 10, 3 );
		add_action( 'wp_ajax_wpc_field_manager_reset_settings', array( $this, 'reset_settings' ) );			
	}
	
	public function customize_init() 
	{
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_enqueue' ) );
	}

	public function customize_enqueue() 
	{
		wp_enqueue_script( 
			'wpc-field-manager', 
			plugins_url( 'assets/js/customizer.js', __FILE__ ), 
			array( 'jquery' ), 
			WPC_VERSION, 
			true 
		);
		
		wp_localize_script(
			'wpc-field-manager',
			'wpc_field_manager',
			array(
				'nonce' => wp_create_nonce( 
					'wpc-field-manager-nonce' 
				),
				'i18n' => array(
					'confirm_reset_settings' => __( 'Do you want to restore the initial settings?', WPC_SLUG ),
				),
			)
		);
		
		wp_enqueue_style( 
			'wpc-field-manager',
			plugins_url( 'assets/css/customizer.css', __FILE__ ),
			array(), 
			WPC_VERSION,
			'all' 
		);
	}


	public function template_init() 
	{
		add_action( 'woocommerce_checkout_init', array( $this, 'checkout_enqueue' ), PHP_INT_MAX );
	}

	public function checkout_enqueue() 
	{		
		wp_enqueue_script( 
			'jquery-mask', 
			plugins_url( 'assets/js/mask/mask.min', __FILE__ ), 
			array( 'jquery' ),
			'1.14.10',
			true 
		);

		wp_enqueue_script( 
			'jquery-validate', 
			plugins_url( 'assets/js/validate/validate.min', __FILE__ ), 
			array( 'jquery' ),
			'1.13.0',
			true 
		);

		wp_enqueue_script( 
			'wpc-field-manager-frontend', 
			plugins_url( 'assets/js/frontend', __FILE__ ), 
			array( 'jquery', 'jquery-validate' ),
			WPC_VERSION,
			true 
		);
		
		wp_enqueue_style( 
			'wpc-field-manager-frontend',
			plugins_url( 'assets/css/frontend.css', __FILE__ ),
			array(), 
			WPC_VERSION,
			'all' 
		);
	}
	
	public function customizer() 
	{
		return (
			array( 
				'sections' => array( 
					$this->section => array(
						'title' => __( 'Fields Manager', WPC_SLUG ),
						'description'  =>  __( 'Use these options to manage checkout fields.', WPC_SLUG ),
						'priority' => 300,
					) 
				),
				'settings' => array( 
					$this->setting => array(
						'sanitize_callback' => array( $this, 'filter_sanitize_before_saving' ),
					),
				),
				'controls' => array(
					'wpc_field_manager' => array(
						'class'  	   =>  'WPC\Control\Field_Group',
						'id'  	       =>  'wpc_field_manager_control',
						'label'        =>  'Add field group',
						'description'  =>  __( 'Add a new group to organize custom fields.', WPC_SLUG ),
						'default_values' => [
							'group_title' => esc_html__( 'Group Title', WPC_SLUG ),
							'field_title' => esc_html__( 'Field Title', WPC_SLUG ),
						],
						'groups' => $this->filter_groups_and_fields(),
						'arrangement_fields' => array(
							'id' => [
								'type'        => 'text',
								'label'       => esc_html__( 'Field ID', WPC_SLUG ),
								'description' => esc_html__( '', WPC_SLUG ),
								'disabled'    =>  true,
							],
							'priority' => [
								'type'        => 'hidden',
								'label'       => esc_html__( 'Priority', WPC_SLUG ),
								'description' => esc_html__( '', WPC_SLUG ),
								'disabled'    =>  true,
							],
							'type'  => [
								'type'        => 'select',
								'label'       => esc_html__( 'Type', WPC_SLUG ),
								'description' =>  esc_html__( '', WPC_SLUG ),
								'choices'     => array( 
									''               =>      __( 'Default', WPC_SLUG ),
									'text'           =>      __( 'Text', WPC_SLUG ),
									'password'       =>      __( 'Password', WPC_SLUG ),
									'email'          =>      __( 'E-mail', WPC_SLUG ),
									'tel'            =>      __( 'Phone', WPC_SLUG ),
									'textarea'       =>      __( 'Textarea', WPC_SLUG ),			
									'select'         =>      __( 'Select', WPC_SLUG ),			
									'radio'          =>      __( 'Radio', WPC_SLUG ),			
									'date'           =>      __( 'Date', WPC_SLUG ),			
									'datetime-local' =>      __( 'Date and Time', WPC_SLUG ),			
								),
							],
							'options' => [
								'type'        => 'array',
								'label'       => esc_html__( 'Options', WPC_SLUG ),
								'description' =>  esc_html__( '', WPC_SLUG ),
							],
							'value' => [
								'type'        => 'text',
								'label'       => esc_html__( 'Default Value', WPC_SLUG ),
								'description' => '',
							],
							'name'  => [ 
								'type'        => 'text',
								'label'       => esc_html__( 'Input name', WPC_SLUG ),
								'description' => '',
							],				
							'placeholder'  => [ 
								'type'        => 'text',
								'label'       => esc_html__( 'Placeholder', WPC_SLUG ),
								'description' => '',
							],
							'class' => [
								'type'        => 'text',
								'label'       => esc_html__( 'Class', WPC_SLUG ),
								'description' => '',
							],
							'validation' => [
								'type'        => 'text',
								'label'       => esc_html__( 'Pattern Validation', WPC_SLUG ),
								'description' => ''
							],
							'validation_message' => [
								'type'        => 'text',
								'label'       => esc_html__( 'Validation Error Message', WPC_SLUG ),
								'description' => ''
							],
							'mask' => [
								'type'        => 'text',
								'label'       => esc_html__( 'Input Mask', WPC_SLUG ),
								'description' => ''
							],
							'required' => [
								'type'        => 'select',
								'label'       => esc_html__( 'Required', WPC_SLUG ),
								'description' => esc_html__( '', WPC_SLUG ),
								'choices'     => array( 
									'yes'      =>      __( 'Yes', WPC_SLUG ),
									'no'       =>      __( 'No', WPC_SLUG ),			
								),
							],
							'visible' => [
								'type'        => 'select',
								'label'       => esc_html__( 'Visible at Checkout', WPC_SLUG ),
								'description' => esc_html__( '', WPC_SLUG ),
								'choices'     => array( 
									''         =>      __( 'Default', WPC_SLUG ),
									'yes'      =>      __( 'Yes', WPC_SLUG ),
									'no'       =>      __( 'No', WPC_SLUG ),			
								),
							],
							'inemail' => [
								'type'        => 'select',
								'label'       => esc_html__( 'Display in Emails', WPC_SLUG ),
								'description' => esc_html__( '', WPC_SLUG ),
								'choices'     => array( 
									''         =>      __( 'Default', WPC_SLUG ),
									'yes'      =>      __( 'Yes', WPC_SLUG ),
									'no'       =>      __( 'No', WPC_SLUG ),			
								),
							],
							'inorder' => [
								'type'        => 'select',
								'label'       => esc_html__( 'Display in Order Detail Pages', WPC_SLUG ),
								'description' => esc_html__( '', WPC_SLUG ),
								'choices'     => array( 
									''         =>      __( 'Default', WPC_SLUG ),
									'yes'      =>      __( 'Yes', WPC_SLUG ),
									'no'       =>      __( 'No', WPC_SLUG ),			
								),
							],
						),
						'section'      =>  $this->section,
						'settings'     =>  $this->setting
					),
					'wpc_field_manager_reset' => array(
						'type'  => 'button',
						'input_attrs' => array(
							'id' => 'wpc_field_manager_reset',
							'value' => __( '>> Reset Settings', WPC_SLUG ),
							'class' => 'button-link',
						),
						'section'  => $this->section,
						'settings' => array( )
					)
				),
			)
		);

	}
	
	public function get_saved_groups_and_fields()
	{
		$control = get_option( 'wpc_field_manager_list', array() );
		
		if ( empty( $control ) ) {
			
			$groups  = array();
			$default = array( 
				array( 'id' => 'billing', 'title' => 'Billing Fields', 'customGroup' => false, 'enableDelete' => false, 'addFields' => false, 'removeFields' => true, 'moveFields' => false, 'limitIncontext' => false, 'children' => array() ), 
				array( 'id' => 'shipping', 'title' => 'Shipping Fields', 'customGroup' => false, 'enableDelete' => false, 'addFields' => true, 'removeFields' => true, 'moveFields' => true, 'limitIncontext' => true, 'children' => array() ), 
				array( 'id' => 'order', 'title' => 'Additional Fields', 'customGroup' => false,'enableDelete' => false, 'addFields' => true, 'removeFields' => true, 'moveFields' => true, 'limitIncontext' => false, 'children' => array() ),
				array( 'id' => 'account', 'title' => 'Account Fields', 'customGroup' => false,'enableDelete' => false, 'addFields' => false, 'removeFields' => false, 'moveFields' => false, 'limitIncontexto' => true, 'children' => array() ),
			);
			
			foreach( $default as $group ) {				
				
				$gid    = $group['id'];
				$fields = WC()->checkout()->get_checkout_fields();

				if( isset( $fields[ $gid ] ) ) {
					foreach( $fields[ $gid ] as $fid => $field ) {
						$group['children'][ $fid ] = $field;

					}
				}
			
				$groups[ $group['id'] ] = $group;
			}
			
			update_option( 'wpc_field_manager_list', $groups );
			
			$control = get_option( 'wpc_field_manager_list', array() );		
		}

		return( 
			apply_filters( 
				'wpc_field_manager_saved_groups_and_fields',
				$control
			)
		);
	}
	
	public function before_return_saved_groups_and_fields( $control_fields ) 
	{	
		if ( 'yes' === get_option( 'woocommerce_registration_generate_username' ) ) {
			unset( $control_fields['account']['children']['account_username'] );
		}
		if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) ) {
			unset( $control_fields['account']['children']['account_password'] );
		}
		
		if ( isset( $control_fields['account']['children'] ) && empty( $control_fields['account']['children'] ) ) {
			unset( $control_fields['account'] );
		}
		
		return $control_fields;
	}

	public function filter_groups_and_fields( $type = 'incontrol' ) 
	{
		$control_fields  = $this->get_saved_groups_and_fields();
		
		foreach( $control_fields as $gid => &$group ) {
			
			$group = apply_filters( sprintf( 'wpc_field_manager_%s_group', $type ), $group, $gid );
			
			foreach( $group['children'] as $fid => &$field ) {

				$field = apply_filters( sprintf( 'wpc_field_manager_%s_group_field', $type ), $field, $fid, $gid );

				foreach( $field as $opt => &$value ) {
					
					$value = apply_filters( sprintf( 'wpc_field_manager_%s_group_field_option', $type ), $value, $opt, $fid, $gid );
				}
			}
			
		}

		return $control_fields;
	}
	
	public function to_checkout_field_options_sanitize( $content, $fid, $gid ) 
	{	
		if( empty( $content['type'] ) || is_null( $content['type'] ) ) {
			unset( $content['type'] );
		}

		if( ! empty( $content['title'] ) || ! is_null( $content['title'] ) ) {
			$content['label'] = $content['title']; 
		}

		if( ! empty( $content['value'] ) || ! is_null( $content['value'] ) ) {
			$content['default'] = $content['value']; 
		}

		if ( ! empty( $content['validation'] ) ) {
			$content['custom_attributes']['pattern'] = $content['validation'];
			$content['custom_attributes']['onchange'] = 'jQuery( this ).valid()';
			$content['custom_attributes']['data-msg-pattern'] = $content['validation_message'] ?: __( 'Invalid format.' );
		}
		
		if ( ! empty( $content['mask'] ) ) {
			$content['custom_attributes']['data-mask'] = $content['mask'];
			$content['custom_attributes']['data-mask-clearifnotmatch'] = 'true';
		}
		
		if ( ! isset( $content['class'] ) ) {
			$content['class'] = array();
		}
		
		return $content;
	}

	public function to_checkout_fields_unify( $defaul_fields ) 
	{		
		$control_fields = $this->filter_groups_and_fields( 'incheckout' );
		
		foreach( $control_fields as $gid => &$group ) {
			
			foreach( $group['children'] as $fid => &$field ) {
				
				if( false === $field['customField'] || !isset( $field['customField'] ) ) {
					
					if ( isset( $defaul_fields[ $gid ] ) ) {
						
						$check_key_exists = array_key_exists( $fid, $defaul_fields[ $gid ] );
						
						if ( false === $check_key_exists ) {
							
							foreach ( $defaul_fields as $key => $value ) {
								
								if ( $gid !== $key && isset( $value[ $fid ] ) ) {
									unset( $defaul_fields[ $key ][ $fid ] );
								}							
							}
						}
					}
				}
				
				foreach( $field as $opt => &$value ) {
			
					$defaul_fields[ $gid ][ $fid ][ $opt ] = $value;					
				
				}
			}
			
		}
		
		return $defaul_fields;
	}
	
	public function to_control_field_options_sanitize( $content, $fid, $gid )
	{	
		$valid_types   =  apply_filters( 'wpc_field_manager_define_valid_field_types', array( 'text', 'password', 'email', 'tel', 'textarea', 'select', 'radio', 'date', 'datetime-local' ) );
	
		$priority      =  $content['priority'] ?? '';
		$placeholder   =  $content['placeholder'] ?? '';
		$visible       =  $content['visible'] ?? '';
		$inemail       =  $content['inemail'] ?? '';
		$inorder       =  $content['inorder'] ?? '';
		$options       =  $content['options'] ?? array();
		$value         =  $content['value'] ?? $content['default'];
		$title         =  $content['title'] ?? $content['label'];
		$custom_field  =  $content['customField'] ?? false;
		$enable_delete =  $content['enableDelete'] ?? false;

		$required      =  isset( $content['required'] ) && !empty( $content['required'] ) ? wpc_bool_to_string( $content['required'] ) : 'no';		
		$class         =  isset( $content['class'] ) && !empty( $content['class'] ) ? implode( ' ', $content['class'] ) : '';
		$type          =  isset( $content['type'] ) ? ( in_array( $content['type'], $valid_types ) ? $content['type'] : '' ) : '';
		
		
		return(
			array_replace(
				$content,
				array(
					'id'           => $fid,
					'title'        => $title,
					'value'        => $value,
					'type'         => $type,
					'required'     => $required,
					'class'        => $class,
					'options'      => $options,
					'priority'     => $priority,
					'placeholder'  => $placeholder,
					'enableDelete' => $enable_delete,
					'customField'  => $custom_field,
				)
			)
		);
	}

	public function to_save_field_options_sanitize( $content, $id )
	{		
		foreach( array( 'customField', 'enableDelete', 'inorder', 'inemail', 'visible', 'required' ) as $option ) {
			if ( isset( $content[ $option ] ) && '' !== $content[ $option ] ) {
				$content[ $option ] = wpc_string_to_bool( $content[ $option ] );
			}
		}
		
		if ( isset( $content['class'] ) && ! empty( $content['class'] ) ) {
			$content['class'] = explode ( ' ', $content['class'] );
		} else {
			$content['class'] = array();
		}

		return $content;
	}
	
	public function filter_sanitize_before_saving( $groups )
	{	
		foreach( $groups as $gid => &$group ) {
			$group = apply_filters( 'wpc_field_manager_to_save_group_sanitize', $group, $gid );
			foreach( $group['children'] as $fid => $field ) {			
				$field = apply_filters( 'wpc_field_manager_to_save_field_options_sanitize', $field, $fid, $gid );

				$groups[ $gid ][ 'children' ][ $fid ] = $field;
			}

		}
		
		return $groups;
	}
	
	public function to_order_update_meta( $order_id ) 
	{
		$order = wc_get_order( $order_id );
		
		add_action( 'wpc_field_manager_inorder_group_field', function( $content, $fid, $gid ) use( &$order ) {

			if( ( true === $content['inemail'] || true === $content['inorder'] ) && isset( $_POST[ $fid ] ) && !empty( $_POST[ $fid ] ) ) {
				$order->update_meta_data( sprintf( '_%s', $fid ), sanitize_text_field( wp_unslash( $_POST[ $fid ] ) ) );
			}
		
			return $content;
		
		}, PHP_INT_MAX, 3 );

		$this->filter_groups_and_fields( 'inorder' );
		
		$order->save();
	}

	public function to_admin_print_order_meta( $order ) 
	{ 
		$fields = [];
		
		add_action( 'wpc_field_manager_inorder_group_field', function( $content, $fid, $gid ) use( &$order, &$fields ) {
			
			if( true === $content['inorder'] && !empty( $order->$fid ) ) {
				$fields[] = array( 'label' => $content['title'], 'value' =>  $order->$fid );
			 }

			return $content;
		
		}, PHP_INT_MAX, 3 );
	
		$this->filter_groups_and_fields( 'inorder' );
		
		include( dirname( __FILE__ ) . '/views/html-admin-print-order-meta.php' );
	}

	public function to_email_print_order_meta( $order, $sent_to_admin, $plain_text ) 
	{ 	
		$fields = [];
		
		add_action( 'wpc_field_manager_inemail_group_field', function( $content, $fid, $gid ) use( &$order, &$fields ) {

			if( true === $content['inemail'] && !empty( $order->$fid ) ) {
				$fields[] = array( 'label' => $content['title'], 'value' =>  $order->$fid );
			}
		
			return $content;
		
		}, PHP_INT_MAX, 3 );
		
		$this->filter_groups_and_fields( 'inemail' );
		
		if ( true === $plain_text ) {
			include( dirname( __FILE__ ) . '/views/plain/email-print-order-meta.php' );
		} 
		else {
			include( dirname( __FILE__ ) . '/views/html-email-print-order-meta.php' );
		}
	}

	public function reset_settings() 
	{
		check_ajax_referer( 'wpc-field-manager-nonce', 'security' );
	
		if ( update_option( 'wpc_field_manager_list', '' ) ) {
			wp_send_json_success( array(
				'message' => __( 'Settings have been reset.', WPC_SLUG ),
				'status'      => 1
			) );
		} else {
			wp_send_json_error( array(
				'message' => __( 'There was a problem with the process. The settings have not been reset.', WPC_SLUG ),
				'status'      => 0
			) );
		}
	
		die;
	}
}