<?php
/**
 * Cuisine Core
 * 
 * Makes functions a little more readable.
 *
 * @author 		Chef du Web
 * @category 	Core
 * @package 	Cuisine
 */





	/**************************************************/
	/** Registered Conditionals ***********************/
	/**************************************************/



	/**
	*	Check if a plugin is registered with cuisine:
	*/
	function cuisine_has_plugin( $slug ){
		global $cuisine;
		return $cuisine->integrations->is_plugin_registered( $slug );

	}


	/**
	*	Check if a theme is compatible with all plugins
	*/
	function cuisine_is_compatible(){

	}


	/** 
	*	Check if a theme has a capability:
	*/

	function cuisine_has_capability( $slug ){
		global $cuisine;
		return $cuisine->intergrations->is_theme_capable( $slug );
	}


	/**
	*	Check if a theme is Cuisine capable
	*/

	function is_cuisine_theme(){
		global $cuisine;
		return $cuisine->theme->is_cuisine_enabled_theme();
	}



	/**************************************************/
	/** Theme Helpers *********************************/
	/**************************************************/


	function cuisine_get_theme_style( $sanitize = false ){
		global $cuisine;
		return $cuisine->theme->get_theme_style( $sanitize );
	}

	function cuisine_has_theme_style( $sanitize = false ){
		global $cuisine;
		return $cuisine->theme->has_theme_style();
	}

	function cuisine_get_google_font_link(){
		global $cuisine;
		return $cuisine->theme->get_google_fonts();
	}

	function cuisine_site_url(){
		global $cuisine;
		return $cuisine->site_url;
	}

	function cuisine_template_url(){
		global $cuisine;
		return $cuisine->template_url;
	}

	function cuisine_plugin_url(){
		global $cuisine;
		return $cuisine->plugin_url;
	}


	function cuisine_register_scripts( $scripts ){
		global $cuisine;
		$cuisine->theme->register_scripts( $scripts );
	}



	//A quick function to compare 2 variables and output some text:
	function cuisine_current( $first, $second, $raw = true, $class = 'current' ){

		if( $first == $second ){

			if( $raw ) echo $class;

			return $class;

		}

	} 


	/**************************************************/
	/** Post / Post-meta  getters *********************/
	/**************************************************/


	/**
	*	Get a post id by the $_GET['post'] parameter or the global $post object
	*/	
	function cuisine_get_post_id(){
		global $post, $pagenow;

		if( isset( $_GET['post'] ) )
			return $_GET['post'];

		if( $pagenow == 'post-new.php' && isset( $post ) )
			return $post->ID;

		if( isset( $post ) )
			return $post->ID;

		return false;
	}

	/**
	*	Get post type:
	*/

	function cuisine_is_posttype( $t ){
		global $cuisine;
		return $cuisine->posttypes->admin_current( $t );
	}

	/**
	*	Get a nonce for a certain page:
	*/

	function cuisine_get_nonce(){

		//first, check if we're dealing with a valid cuisine session:
		if( !cuisine_is_valid_session() )
			return false;

		global $cuisine;
		$cuisine->plugins->get_plugin_nonce();
	}



	/* Post Extras */
	function cuisine_register_post_extra( $id, $label, $func, $js = array(), $priority = null, $args = array() ){
		global $cuisine;
		$cuisine->plugins->register_post_extra( $id, $label, $func, $js, $priority, $args );

	}




?>
