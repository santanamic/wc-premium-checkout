wp.customize.controlConstructor['wpc_field_group'] = wp.customize.Control.extend({

	ready: function() { 
		
		'use strict';
		
		var control  = this,
		    element  = this.container.find( 'ol.fields-content.ingroup' );

		var groupElement = element.sortable({
			group: 'fields-content',
			delay: 100,
			isValidTarget: function ($item, container) {
				var grup_el         = container.el;
				var limit_incontext = $item.closest( '.field-group' ).data( 'limitIncontext' );
				//console.log(grup_el);
				
				if ( $item.hasClass('item-field') && jQuery( grup_el ).hasClass('field-group-content') ) {

					var sortable_group_id  = element.find('li.placeholder').closest( '.field-group' ).attr('data-id');
					var origin_group_id    = $item.closest( '.field-group' ).attr('data-id');
					var sortable_incontext = element.find('li.placeholder').closest( '.field-group' ).data('limit-incontext');

					//verifica se o grupo de destino possui a limitação no contexto
					if( ( true == sortable_incontext || undefined == sortable_incontext ) && sortable_group_id != origin_group_id ) {
						
						// Se o item for custom libera para o drop
						if( true == $item.data( 'custom-field' ) ) {
							return true;
						}
						
						return false;
					} 
					//move todos os campos somente dentro do grupo de origem
					else if( true == limit_incontext || undefined == limit_incontext ) {

						if( sortable_group_id == origin_group_id ) {
							return true;
						}					

						return false;
					} 
					
					return true;
				}

				return false;

			},
			onMousedown: function ($item, _super, event) {
				var move_fields = $item.closest( '.field-group' ).data( 'moveFields' );

				if ( true !== move_fields ) {
					return false;

				}
				
				return true;
			},
			onDrop: function ($item, container, _super) {
				updateControlSetting();	

				_super($item, container);
			}
		});

		
		var updateDataValues = function() {
			jQuery( element ).find( '*[data-field]' ).each( function() {
					var field       = jQuery( this );
					var parent      = jQuery( this ).closest( '.item-field' );
					var field_label = field.data( 'field' );
					var field_value = field.val();
					
					if ( jQuery( this ).hasClass( 'option-group-field' ) ) {
						var option_value = jQuery( this ).val(),
							option_data  = {};    

						jQuery( this ).closest( '.arrangement-field' ).find( '.option-group  li:not(.option-group-default)' ).each( function() {
							var text  = jQuery( this ).find( '.option-text' ).val(),
								value = jQuery( this ).find( '.option-value' ).val(); 
						
							option_data[ value ] = text;
							parent.data( field_label, option_data );		
						} );
					} else {
						//parent.attr( 'data-' + field_label, field_value );
						parent.data( field_label, field_value );		
					}
					
			} )
		};

		var updateControlSetting = function() {
			updateDataValues();	

			var data = groupElement.sortable( 'serialize' ).get();
			var fixedData = fixeData( data );
			
			control.setting.set( fixedData );
		};

		var fixeData = function( arr ) {
			var count = 1,
				data = {},
				data_children = {};
			
			jQuery.each( arr[0], function( index, group ) {
				data_children    = {};
				data[ group.id ] = group;

				jQuery.each( group.children[0], function( index, field ) {					
					data_children[ field.id ] = { ...field, ...{ 'priority' : count++ } };
				} );
				
				data[ group.id ].children = data_children;
				
	
			} );
			
			return data;
		};
		
/*

			jQuery.each( group.children, function( index, children ) {
					children
				} );

*/				
		
		jQuery( element ) //addItemField addFieldGroup
			//open group
			.on( 'click', '.field-group-label', 
				function(e) {
					if( e.target === e.currentTarget ) {
						var _self = jQuery( this ),
							_content = jQuery( this ).closest( '.fields-content' ),
							_groups = [];
							
						 _self.closest( '.field-group' ).toggleClass( 'active' ); 
	
						if ( _self.closest( '.field-group' ).hasClass( 'active' ) ) {
							element.find( '.field-group' ).removeClass( 'active' );
							_self.closest( '.field-group' ).addClass( 'active' ); 
							
							element.find( '.field-group' ).not( '.active' ).find( '.item-field' ).removeClass( 'active' );
							_content.find( 'select.field-list' ).WPC_select2();
						} else {
							element.find( '.field-group' ).not( '.active' ).find( '.item-field' ).removeClass( 'active' );
						}
						
						e.stopImmediatePropagation();
					}
				})
			.on( 'select2:open', 'select.field-list', 
				function(e){ 
				
					var _content = jQuery( this ).closest( '.fields-content' ),
						_groups = [];
					
					jQuery( _content ).find( '.field-selector optgroup:not(.add-new-field-option)' ).remove();					
					
					jQuery( _content ).find( '.field-group:not(.active)' ).each(function( index ) {
						var _group = jQuery( this ),
							//_gincontext = jQuery( this ).data( 'limit-incontext' ),
							_gremove = jQuery( this ).attr( 'data-remove-fields' ),
							_gid = _group.attr( 'data-id' ),
							_gtitle = _group.attr( 'data-title' ),
							_gfields = [];

						if ( _gremove === 'true' ) {
						jQuery( _group.find( '.item-field' ) ).each(function( index ) {
							var _field = jQuery( this ),
								_fid = _field.attr( 'data-id' ),
								_ftitle = _field.attr( 'data-title' );
							_gfields[_fid] = _ftitle;
						});
						
						
						_groups[index] = { 'tile' : _gtitle, 'id' : _gid, 'fields' : _gfields };
						}
					
					});

					jQuery( _groups ).each( function( index, group ) {
						
						if ( undefined != group && Object.values(group.fields).length !== 0 ) {
							var _optgroup = '<optgroup label="' + group.tile + '">';
							
							for ( let [key, value] of Object.entries( group.fields ) ) {
							  _optgroup += '<option>' + key + '</option>';
							}
							jQuery( _content ).find( 'select.field-list' ).append( _optgroup );
						}

					} );
					
					//jQuery( _content ).find( 'select.field-list' ).WPC_select2();
					
					e.stopImmediatePropagation();
					
				} 
			)
			//open field
			.on( 'click', '.item-field .item-field-label', 
				function(e) {										
					if( e.target === e.currentTarget ) {
						var _self = jQuery( this ).closest( '.item-field' ),
							_group = jQuery( this ).closest( '.field-group' ); 
						_self.toggleClass( 'active' ); 
						jQuery( '.item-field' ).each( function() { 
							if( jQuery( this ).attr( 'data-id' ) != _self.attr( 'data-id' ) ) { 
								jQuery( this ).removeClass( 'active' ); 
							}
						} );
						
						if( ! _group.hasClass( 'active' ) ) {
							_group.find( '.field-group-label' ).trigger( 'click' );
						}
					}
				} )
			//select option
			.on( 'change', 'select.field-list', 
				function( e ) {
					var _self = jQuery( this ),
						_value = _self.val();
						
					// add item
					if( null !== _value ) {
						if( 'add' === _value[0] ) {
							var _field_name = jQuery( element )
								.find( 'input[name="default-field-title"]' ).val();
								
							var _new_item = jQuery( element )
								.find( '.item-field' )
								.eq(0)
								.clone( true )
								.find( '.field-title' )
								.val( _field_name )
								.closest( '.item-field' )
								.addClass( 'active' )
								.find( 'input, select, textarea:not( .field-title )' )
								.val( null )
								.closest( '.item-field' );

							[
							 'title', 
							 'id', 
							 'custom-field',
							 'enable-delete'
							 ].map( function( value ) {
								
								switch ( value ) {
									case 'title':
										_new_item
											.data( value, _field_name )
											.attr( 'data-' + value, _field_name );
										break;
									
									case 'id':
										_new_item
											.data( value, 'field_' + jQuery.now() )
											.attr( 'data-' + value, 'field_' + jQuery.now() );
										break;
									
									default: 
										_new_item.
											data( value, true )
											.attr( 'data-' + value, true );
										break;
								}
								
							} );
							
							_self.closest( '.field-group' )
								.find( '.field-group-content' ).prepend( _new_item );

							jQuery( element ).trigger( 'addItemField', _new_item );							
						} 
						// move item
						else {
							var _select_item = jQuery( element ).find( '.item-field[data-id="' + _value[0] + '"]' );
							var _moved_item = _select_item.clone( true );
							
							_self.closest( '.field-group' ).find( '.field-group-content' ).prepend( _moved_item.addClass('active') );
							_select_item.remove();
							_self.val(null);
							
							setTimeout(function(){ 
								_self.closest( '.field-group' ).find( '.field-group-label' ).trigger( 'click' );
								_self.trigger( 'moveItemField', _moved_item );
							}, 2);
							
						}
						
						jQuery( this ).val( '' );
					} 
					
					e.stopImmediatePropagation();
				} )
				
			//add group
			.on( 'click', '.group-add', 
				function(e) {
					var _new_group = jQuery( element )
						.find( '.field-group' ).eq(0).clone( true );
					
					var _group_name = jQuery( element )
						.find( 'input[name="default-group-title"]' ).val();
					

					[
					 'title', 
					 'id', 
					 'custom-group', 
					 'limit-incontext', 
					 'move-fields', 
					 'remove-fields', 
					 'add-fields', 
					 'enable-delete'
					 ].map( function( value ) {

						switch ( value ) {
							case 'title':
								_new_group
									.data( value, _group_name )
									.attr( 'data-' + value, _group_name );
							break;

							case 'id':
								_new_group
									.data( value, 'group_' + jQuery.now() )
									.attr( 'data-' + value, 'group_' + jQuery.now() );
							break;

							case 'limit-incontext':
								_new_group
									.data( value, false )
									.attr( 'data-' + value, false );
							break;

							default: 
								_new_group
									.data( value, true )
									.attr( 'data-' + value, true );
							break;
						}
						
					} );

					_new_group.find( '.item-field' ).remove();

					_new_group							
						.find( '.field-group-title' ).text( _group_name ).attr( 'title', _group_name );
					
					_new_group.find( 'select.field-list' ).WPC_select2();

					console.log(jQuery( this ).closest( '.fields-content' ).find( '.group-add' ).before( _new_group ));

					jQuery( element ).trigger( 'addFieldGroup', _new_group );

					e.stopPropagation();
				} 
			)
			//delete group
			.on( 'click', '.field-group.active .group-delete', 
				function(e) {
					var group = jQuery( this ).closest( '.field-group' );
					
					confirm( wpc_field_group.i18n.confirm_delete_group ) 
						? group.data( 'enable-delete' ) 
							? group.remove() 
							: false
						: false;  
					
					jQuery( element ).trigger( 'removeFieldGroup', group );

					e.stopPropagation();
				} 
			)

			//delete field
			.on( 'click', '.item-field.active .field-delete', 
				function(e) {
					var field = jQuery( this ).closest( '.item-field' );
					
					confirm( wpc_field_group.i18n.confirm_delete_field ) 
						? field.data( 'enable-delete' ) 
							? field.remove() 
							: false
						: false;  
						
						
					jQuery( element ).trigger( 'removeItemField', field );
					
					e.stopPropagation();
				} 
			)

			.on( 'change keypress', '.field-group[data-custom-group="true"] .field-group-label textarea, .item-field .item-field-label textarea', 
				function() {
					var value  = jQuery( this ).val();
					var parent  = jQuery( this ).closest( '[data-id]' );
					
					parent.closest( '[data-id]' ).data( 'title', value ).attr( 'data-title', value );					
				} 
			)
			.on( 'click', '.option-group li .add', 
				function( e ) {
					var parent  = $( this ).closest( 'ul.option-group' ),
						content = $( this ).closest( 'li' ).clone();
						
					jQuery( parent ).append( 
						content
							.removeClass( 'option-group-default' )
							.attr( 'class', '' )
							.find( 'button' ).text( ' x ' ).removeClass( 'add' ).addClass( 'remove' ).closest( 'li' )
					).find( 'li.option-group-default input' ).val( '' );
					
					jQuery( parent ).trigger( 'optionArrayUpdated', parent );
					
					e.preventDefault();
				} 
			).on( 'click', '.option-group li .remove', 
				function( e ) {
					var parent  = $( this ).closest( 'ul.option-group' ),
					    self    = $( this );
					
					self.closest( 'li' ).remove();
					jQuery( parent ).trigger( 'optionArrayUpdated', parent );	

					e.preventDefault();
				}
			).on( 'change optionArrayUpdated moveItemField', 'select[data-field], input[data-field], textarea[data-field], .option-group, .item-field-label textarea, select.field-list', 
				function() {
					updateControlSetting();	
				}
			).on( 'removeFieldGroup removeItemField', 
				function() {
					updateControlSetting();	
				}
			)
	}
});