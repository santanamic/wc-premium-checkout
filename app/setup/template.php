<?php defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpc_template_include' ) ) {

	/**
	 * Load page template 
	 *
	 * @since    1.0.0
	 * @param    string    The current path of the template to include
	 * @return   string
	 */
	function wpc_template_include( $template ) 
	{
		$callback = apply_filters( 
			'wpc_template_callback', 
			true 
		);
		
		if ( true === $callback ) {
			$locate = apply_filters( 
				'wpc_template_file', 
				'' 
			);
			
			if ( file_exists( $locate ) ) {
				return $locate;
			}
		}
		
		return $template;
	}
	
}

if ( ! function_exists( 'wpc_template_default_path' ) ) {

	/**
	 * Sets plugin default page template 
	 *
	 * @since    1.0.0
	 * @return   string
	 */
	function wpc_template_default_path() 
	{
		return (
			WPC_PATH . '/setup/view/template/html-wpc-base-template.php'
		);
	}
	
}

if ( ! function_exists( 'wpc_template_default_content' ) ) {

	/**
	 * Sets default content in page template 
	 *
	 * @since    1.0.0
	 * @return   string
	 */
	function wpc_template_default_content() 
	{
	?>
		<div id="wpc-wrapper">
			<?php do_action( 'wpc_template_before_main_content' ); ?>
			<main id="wpc-main" role="main">
				<?php
					while ( have_posts() ) : the_post();
						the_content();
					endwhile;
				?>
			</main>	
			<?php do_action( 'wpc_template_after_main_content' ); ?>
		</div>
	<?php
	}
	
}

if ( ! function_exists( 'wpc_get_body_class' ) ) {

	/**
	 * Retrieves an array of the class names for the body element.
	 *
	 * @since    1.0.0
	 * @param    string    $class    Space-separated string or array of class names to add to the class list.
	 * @return   string[]            Array of class names.
	 */
	function wpc_get_body_class( $class = '' ) {	 
		$classes = get_body_class();
	 	 
		/**
		 * Filters the list of CSS body class names for the current post or page.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $classes An array of body class names.
		 * @param string[] $class   An array of additional class names added to the body.
		 */
		$classes = apply_filters( 'wpc_body_class', $classes, $class );
	 
		return array_unique( $classes );
	}
	
}

if ( ! function_exists( 'wpc_body_class' ) ) {

	/**
	 * Displays the class names for the body element.
	 *
	 * @since    1.0.0
	 * @param    string    $class    Space-separated string or array of class names to add to the class list.
	 * @return   string
	 */
	function wpc_body_class( $class = '' ) {	 
		echo 'class="' . esc_attr( join( ' ', wpc_get_body_class( $class ) ) ) . '"';
	}
	
}