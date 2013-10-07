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
	/** Plugin Helpers ********************************/
	/**************************************************/

	function cuisine_register_rewrite( $slug, $type, $object = null, $template = null, $page_object = array() ){
		global $cuisine;

		$cuisine->plugins->register_new_url( $slug, $type, $object, $template, $page_object );
	}


	function cuisine_add_url( $slug, $type, $object = null, $template = null, $page_object = array() ){
		global $cuisine;

		$cuisine->plugins->register_new_url( $slug, $type, $object, $template, $page_object );
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

	function cuisine_stylesheet_url(){
		global $cuisine;
		return $cuisine->theme->stylesheet_url();

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


	/*
	*	Check to see if this is a Cuisine overview page:
	*/
	function is_cuisine_page( $page ){

		global $wp_query;

		if( isset( $wp_query ) && !empty( $wp_query ) ){

			if( isset( $wp_query->cuisine_slug ) && $wp_query->cuisine_slug == $page ){
				return true;
			}

		}

		if( is_page( $page ) ){
			return true;
		}

		return false;

	}


	function cuisine_get_post_by_slug( $slug, $type = 'post' ){

		if( $type != 'page' ){
			
			global $wpdb;

         	
			$args=array(
				'name' => $slug,
				'post_type' => $type,
				'post_status' => 'publish',
				'showposts' => 1,
			);
			
			$posts = get_posts($args);

         	if ( $posts )  
            	return $posts[0]; 

			
			return null;

		}else{

			$page = get_page_by_path( $page_slug );

			if ( $page )
				return $page;
			
			return null;
			
		}

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
		//if( !cuisine_is_valid_session() )
		//	return false;

		global $cuisine;
		$cuisine->plugins->get_plugin_nonce();
	}



	/* Post Extras */
	function cuisine_register_post_extra( $id, $label, $func, $js = array(), $priority = null, $args = array() ){
		global $cuisine;
		$cuisine->plugins->register_post_extra( $id, $label, $func, $js, $priority, $args );

	}



?>
