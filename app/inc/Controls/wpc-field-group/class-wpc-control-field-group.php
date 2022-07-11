<?php

namespace WPC\Control;

class Field_Group extends \WP_Customize_Control 
{
	public $type = 'wpc_field_group';
	
	public $id;
	public $label;
	public $default_values;
	public $description;
	public $groups;
	public $unallocated_fields;
	public $arrangement_fields;
	public $default;
	
	public function to_json() 
	{
		parent::to_json();
		
		$this->json['id']                  = $this->id;
		$this->json['label']               = $this->label;
		$this->json['default_values']      = $this->default_values;
		$this->json['description']         = $this->description;
		$this->json['groups']              = $this->groups;
		$this->json['unallocated_fields']  = $this->unallocated_fields;
		$this->json['arrangement_fields']  = $this->arrangement_fields;
		$this->json['default']             = $this->default;
		$this->json['value']               = (array) $this->value();
	}

	public function render_content() 
	{
	}

	public function content_template() 
	{
	?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>	
		
		<ol class="fields-content ingroup">
			
			<# if ( data.default_values.group_title ) { #>
				<input name="default-group-title" type="hidden" value="{{ data.default_values.group_title }}"></input>
			<# } #>

			<# if ( data.default_values.field_title ) { #>
				<input name="default-field-title" type="hidden" value="{{ data.default_values.field_title }}"></input>
			<# } #>

			<# _.each( data.groups, function( group, id ) { #>
				<li class="field-group" data-custom-group='{{ group.customGroup }}' data-limit-incontext='{{ group.limitIncontext }}' data-move-fields='{{ group.moveFields }}' data-remove-fields='{{ group.removeFields }}' data-add-fields='{{ group.addFields }}' data-enable-delete='{{ group.enableDelete }}' data-id='{{ id }}' data-title='{{ group.title }}'>
					<div class="field-group-label">
						<textarea maxlength="22" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title="{{ group.title }}" contenteditable="true" class="field-group-title">{{ group.title }}</textarea>
						<div class="field-group-insert">
							<span class="on-add">&#8629;</span>
						</div>
					</div>
					

					<div class="field-selector">
						<span class="on-add">&#10010;</span>
						<select class="field-list" multiple="multiple">
							<optgroup class="add-new-field-option">
								<option value="add"><?php _e( '--Add New Item--', WPC_SLUG ) ?></option>
							</optgroup>
						</select>
					</div>
					<ol class="field-group-content">
						<# if ( group.children ) { #>
							<# _.each( group.children = _.sortBy(group.children, function(o) { return o.priority; }), function( item, id ) { #>
								<li class="item-field"  data-custom-field='{{ item.customField }}' data-enable-delete='{{ item.enableDelete }}' data-id='{{ item.id }}' data-title='{{ item.title }}'>
									<div class="item-field-label">
										<textarea maxlength="22" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title="{{ item.title }}" contenteditable="true" class="field-title">{{ item.title }}</textarea>

										<div class="field-group-toggle">
											<span class="close">&#9650;</span>
											<span class="open">&#9660;</span>
										</div>
									</div>

									<div class="item-field-content"> 
										<# if ( data.arrangement_fields ) { #>
											<# _.each( data.arrangement_fields, function( field, id ) { #>

												<div class="arrangement-field arrangement-field-{{{ field.type }}} arrangement-field-{{ id }}">
												
														<# if ( 'text' === field.type ) { #>
															<label class="arrangement-field-{{{ field.type }}} arrangement-field-{{ id }}">
																<# if ( field.label ) { #><span class="customize-control-title">{{{ field.label }}}</span><# } #>
																<# if ( field.description ) { #><span class="description customize-control-description">{{{ field.description }}}</span><# } #>
																<input type="{{field.type}}" name="{{{ id }}}" <# if (  field.disabled ) { #> disabled="disabled" <# } #> value="<# if (  item[id] ) { #>{{{ item[id] }}}<# } #>" data-field="{{{ id }}}">
															</label>
														<# } #>

														<# if ( 'hidden' === field.type ) { #>
															<label class="arrangement-field-{{{ field.type }}} arrangement-field-{{ id }}">
																<input type="{{field.type}}" name="{{{ id }}}" value="<# if (  item[id] ) { #>{{{ item[id] }}}<# } #>" data-field="{{{ id }}}" disabled="disabled">
															</label>
														<# } #>	
														<# if ( 'textarea' === field.type ) { #>
															<label class="arrangement-field-{{{ field.type }}} arrangement-field-{{ id }}">
																<# if ( field.label ) { #><span class="customize-control-title">{{{ field.label }}}</span><# } #>
																<# if ( field.description ) { #><span class="description customize-control-description">{{{ field.description }}}</span><# } #>
																<textarea <# if (  field.disabled ) { #> disabled="disabled" <# } #> name="{{{ id }}}" data-field="{{{ id }}}" ><# if (  item[id] ) { #>{{{ item[id] }}}<# } #></textarea>
															</label>
														<# } #>	

														<# if ( 'select' === field.type ) { #>
															<label class="arrangement-field-{{{ field.type }}} arrangement-field-{{ id }}">
																<# if ( field.label ) { #><span class="customize-control-title">{{{ field.label }}}</span><# } #>
																<# if ( field.description ) { #><span class="description customize-control-description">{{{ field.description }}}</span><# } #>
																<select <# if (  field.disabled ) { #> disabled="disabled" <# } #> name="{{{ id }}}" data-field="{{{ id }}}"<# if ( ! _.isUndefined( field.multiple ) && false !== field.multiple ) { #> multiple="multiple" data-multiple="{{ field.multiple }}"<# } #>								
																
																<# if ( item['step_disabled_fields'] ) { 
																if ( _.contains( Array.isArray( item['step_disabled_fields'] ) ? item['step_disabled_fields'] : item['step_disabled_fields'].split( ',' ), id ) ) { #>  disabled="disabled" <# } } #>>
																	<# _.each( field.choices, function( choice, i ) { #>
																		<option value="{{{ i }}}" <# if ( i === item[id] ) { #> selected="selected" <# } #>>{{ choice }}</option>
																	<# }); #>
																</select>
															</label>
														<# } #>	

														<# if ( 'array' === field.type ) { #>
															<label class="arrangement-field-{{{ field.type }}} arrangement-field-{{ id }}">
																<# if ( field.label ) { #><span class="customize-control-title">{{{ field.label }}}</span><# } #>
																<# if ( field.description ) { #><span class="description customize-control-description">{{{ field.description }}}</span><# } #>
																
																<input type="hidden" name="{{{ id }}}" class="option-group-field" data-field="{{{ id }}}" disabled="disabled">

																<ul class="option-group <# if (  field.disabled ) { #> disabled <# } #>">
																	<li class="option-group-default">
																		<input placeholder="<?php _e( 'Text', WPC_SLUG ) ?>" class="option-text">
																		<input placeholder="<?php _e( 'Value', WPC_SLUG ) ?>" class="option-value"> 
																		<button class="add"> + </button> 
																	</li>
																	<# if ( item[id] != undefined || field.choices != undefined ) { #>
																		<# _.each( ( item[id] != undefined ? item[id] : field.choices ), function( text, value ) { #>
																			<li>
																				<input class="option-text" value="{{ text }}">
																				<input class="option-value" value="{{{ value }}}"> 
																				<button class="remove"> x </a> 
																			</li>
																		<# }); #>
																	<# } #>
																</ul>
															</label>
														<# } #>	
												</div>
											
											<# } ) #>
										<# } #>	
										<div class="field-delete">
											<button type="button" class="button-link">
												<span><?php _e( '>> Delete field', WPC_SLUG ); ?></span>
											</button>
										</div>
									</div>
								</li>
							<# } ) #>
						<# } #>	
					</ol>
					<div class="group-delete">
						<button type="button" class="button-link">
							<span><?php _e( '>> Delete group', WPC_SLUG ); ?></span>
						</button>
					</div>
				</li>
			<# } ) #>
				
				<br>
				<br>
				
				<div class="group-add">
					<button type="button" class="button-link">
						<span><?php _e( '>> Add Group', WPC_SLUG ) ?></span>
					</button>
				</div>

		</ol>
		
		<ol class='fields-content outgroup'>
			<# _.each( data.unallocated_fields, function( field, id ) { #>
				<li class="item-field" data-id='{{ id }}' data-title='{{ field.title }}'>
					
					<div class="item-field-label">
						<textarea maxlength="22" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" title="{{ field.title }}" contenteditable="true" class="field-title">{{ field.title }}</textarea>
						
						<div class="field-group-toggle">
							<span class="close">&#9650;</span>
							<span class="open">&#9660;</span>
						</div>
					</div>
					
					<div class="item-field-content"> </div>
				</li>
			<# } ) #>
		</ol>
	<?php
	}

	public function enqueue() 
	{
		wp_enqueue_script( 
			'wpc-field-group', 
			plugins_url( 'assets/js/script.js', __FILE__ ),
			array( 'jquery', 'customize-base' ), 
			WPC_VERSION, 
			true 
		);
		
		wp_localize_script(
			'wpc-field-group',
			'wpc_field_group',
			array(
				'i18n' => array(
					'confirm_delete_group' => __( 'The group and its fields will be removed. Do you want to proceed?', WPC_SLUG ),
					'confirm_delete_field' => __( 'The field will be removed. Do you want to proceed?', WPC_SLUG ),
				),
			)
		);
		
		wp_enqueue_script( 
			'WPC_select2' ,
			WPC_URI . 'app/assets/js/select2/WPC_select2.js',
			array( 'jquery' ), 
			'4.0.13', 
			true
		);
		
		wp_enqueue_script( 
			'sortable' ,
			plugins_url( 'assets/js/sortable/sortable.min.js', __FILE__ ),
			array( 'jquery' ), 
			'4.0.12', 
			true
		);
		
		wp_enqueue_style(
			'wpc-field-group-css', 
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array(), 
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

