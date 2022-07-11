<?php

namespace WPC\Control;

class Multiple_Select extends \WP_Customize_Control {

	public $type = 'wpc_multiple_select';
	public $choices;
	public $label;
	public $value;

	public function to_json() 
	{
		parent::to_json();
		$this->json['value']       =  (array) $this->value;
		$this->json['label']       =  $this->label;
		$this->json['choices']     =  $this->choices;
		$this->json['inputAttrs']  =  '';
	
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}
	}

	public function render_content() 
	{

	}

	public function content_template() 
	{
	?>
	<# if ( ! data.choices ) { return; } #>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<select data-value="{{{ data.value }}}" class="wpc-multiple-select" {{{ data.inputAttrs }}} multiple="multiple">
			<# _.each( data.choices, function( content, item ) { #>
				<#  if ( _.isObject( content ) ) { #>
					<optgroup label="{{ item }}">
						<# _.each( content, function( value, title ) { #>
							<option value="{{ value }}" <# if ( -1 !== data.value.indexOf( value ) ) { #> selected="selected" <# } #>>{{ title }}</option>
						<# } ) #>
					</optgroup>
				<# } else { #>
					<option value="{{ content }}" <# if ( -1 !== data.value.indexOf( content ) ) { #> selected="selected" <# } #>>{{ item }}</option>
				<# } #>
			<# } ) #>
		</select>
	
	<?php
	}

	public function enqueue() 
	{
		wp_enqueue_script( 
			'wpc-multiple-select', 
			plugins_url( 'assets/js/script.js', __FILE__ ),
			array( 'jquery', 'customize-base' ), 
			WPC_VERSION, 
			true 
		);
		
		wp_enqueue_script( 
			'WPC_select2' ,
			WPC_URI . 'app/assets/js/select2/WPC_select2.js',
			array( 'jquery' ), 
			'4.0.13', 
			true
		);
		
		wp_enqueue_style( 
			'wpc-multiple-select-css', 
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array(  ), 
			WPC_VERSION, 
			'all' 
		);
		
		wp_enqueue_style( 
			'WPC_select2',
			WPC_URI . 'app/assets/css/select2/WPC_select2.css',
			array(), 
			'4.0.13',
			'all' 
		);

	}
}

