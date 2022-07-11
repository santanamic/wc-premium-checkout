<?php

namespace WPC\Control;

class Card_Selector extends \WP_Customize_Control {

	public $type = 'wpc_card_selector';
	public $cards;
	public $columns;
	public $gap;
	public $action;

	public function to_json() 
	{
		parent::to_json();
		$this->json['cards']   = $this->cards;
		$this->json['columns'] = $this->columns ?: 1;
		$this->json['gap']     = $this->gap ?: '10px';
		$this->json['action']  = $this->action;
	}

	public function render_content() 
	{

	}

	public function content_template() 
	{
	?>		
		<# if ( ! data.cards ) {
			return;
		} #>
		
		<div class="wpc-container-card" style="<# if (  data.columns ) { #> grid-template-columns: repeat({{data.columns}}, 1fr); <# if (  data.gap ) { #> gap: {{data.gap}}; <# } } #>">
		<# _.each( data.cards, function( card, id ) { #>

			<div class="wpc-control-card <# if ( card.isActivecard ) { #>active <# } #>" <# if ( card.action['active_text'] ) { #> data-active-text="{{ card.action['active_text'] }}" <# } #> <# if ( card.action['text'] ) { #> data-text="{{ card.action['text'] }}" <# } #> <# if ( card.action['link'] ) { #> data-url="{{ card.action['link'] }}" <# } #> data-id="{{ card.action['id'] }}">

				<# if ( card.screenshot ) { #>
					<div class="wpc-control-card-screenshot">
						<img src="{{ card.screenshot }}">
					</div>
				<# } #>
					
				<# if ( card.hover ) { #>
					<a href="{{ card.hover['url'] }}" target="_blank">
						<span class="wpc-control-card-more-details">{{ card.hover['text'] }}</span>			
					</a>
				<# } #>
					
				<div class="wpc-control-card-bottom">
					<# if ( card.title && card.action ) { #>
						<h3 class="wpc-control-card-name">{{ card.title }}</h3>
					<# } #>
	
				<# if ( card.action ) { #>
					<div class="wpc-control-card-actions">
						<# if ( card.isActivecard ) { #>
							<span class="button card-active">{{ card.action['active_text'] }}</span>
						<# } else { #>
							<span class="button">{{ card.action['text'] }}</span>
						<# } #>
					</div> 
					<# } #>
				</div>
			</div>	
			<# }) #>
		</div>	
	<?php
	}

	public function enqueue() 
	{
		wp_enqueue_script( 
			'wpc_card_selector', 
			plugins_url( 'assets/js/script.js', __FILE__ ), 
			array( 'jquery', 'customize-base' ), 
			'1.0.0', 
			true  
		);
		
		wp_enqueue_style( 
			'wpc_card_selector-css', 
			plugins_url( 'assets/css/style.css', __FILE__ ), 
			array(), 
			'1.0.0', 
			'all' 
		);
	}
}

