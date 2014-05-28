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
	*	@access public
	*	@return bool 
	*/
	function cuisine_has_plugin( $slug ){
		global $cuisine;
		return $cuisine->integrations->is_plugin_registered( $slug );

	}


	/**
	*	Check if a theme is compatible with all plugins
	*	@access public
	*	@return  bool
	*/
	function cuisine_is_compatible(){
		return true;
	}


	/** 
	*	Check if a theme has a capability:
	*	@access public
	*	@param  String plugin slug
	*	@return bool
	*/

	function cuisine_has_capability( $slug ){
		global $cuisine;
		return $cuisine->intergrations->is_theme_capable( $slug );
	}


	/**
	*	Check if a theme is Cuisine capable
	*	@access public
	*	@return  bool
	*/

	function is_cuisine_theme(){
		global $cuisine;
		return $cuisine->theme->is_cuisine_enabled_theme();
	}


	/**************************************************/
	/** Plugin Helpers ********************************/
	/**************************************************/

	/**
	 * Add a rewrite:
	 * @access public
	 * @param  String $slug        The url to rewrite
	 * @param  String $type        post_type or page (default: post_type)
	 * @param  String $object      which post_type / page (default: slug)
	 * @param  String $template    which template to query (default {post_type}.php / {page-name}-template.php)
	 * @param  Array  $page_object details on the page we need to create
	 * @return void
	 */
	function cuisine_register_rewrite( $slug, $type, $object = null, $template = null, $page_object = array() ){
		global $cuisine;

		$cuisine->plugins->register_new_url( $slug, $type, $object, $template, $page_object );
	}


	/**
	 * Add a rewrite:
	 * @access public
	 * @param  String $slug        The url to rewrite
	 * @param  String $type        post_type or page (default: post_type)
	 * @param  String $object      which post_type / page (default: slug)
	 * @param  String $template    which template to query (default {post_type}.php / {page-name}-template.php)
	 * @param  Array  $page_object details on the page we need to create
	 * @return void
	 */
	function cuisine_add_url( $slug, $type, $object = null, $template = null, $page_object = array() ){
		global $cuisine;

		$cuisine->plugins->register_new_url( $slug, $type, $object, $template, $page_object );
	}



	/**************************************************/
	/** Theme Helpers *********************************/
	/**************************************************/


	/**
	 * Get the current theme's style:
	 * @access public
	 * @param  boolean $sanitize Sanatize css
	 * @return String (css)
	 */
	function cuisine_get_theme_style( $sanitize = false ){
		global $cuisine;
		return $cuisine->theme->get_theme_style( $sanitize );
	}

	/**
	 * Does the current theme have a style?
	 * @access public
	 * @return bool
	 */
	function cuisine_has_theme_style(){
		global $cuisine;
		return $cuisine->theme->has_theme_style();
	}

	/**
	 * Get the google font link
	 * @access public
	 * @return String (url)
	 */
	function cuisine_get_google_font_link(){
		global $cuisine;
		return $cuisine->theme->get_google_fonts();
	}

	/**
	 * Get the site url (basically the same as get_bloginfo( url ) );
	 * @access public
	 * @return String (url)
	 */
	function cuisine_site_url(){
		global $cuisine;
		return $cuisine->site_url;
	}

	/**
	 * Get the template url (basically the same as get_bloginfo( template_url ) );
	 * @access public
	 * @return String (url)
	 */
	function cuisine_template_url(){
		global $cuisine;
		return $cuisine->template_url;
	}

	/**
	 * Url for the Cuisine plugin
	 * @access public
	 * @return String (url)
	 */
	function cuisine_plugin_url(){
		global $cuisine;
		return $cuisine->plugin_url;
	}


	/**
	 * Register multiple scripts at once
	 * @access public
	 * @param  Array :: see classes/theme -> register_scripts
	 * @return void
	 */
	function cuisine_register_scripts( $scripts ){
		global $cuisine;
		$cuisine->theme->register_scripts( $scripts );
	}

	/**
	 * Get the stylesheet url (depends on production mode on/off)
	 * @access public
	 * @return String (url)
	 */
	function cuisine_stylesheet_url(){
		global $cuisine;
		return $cuisine->theme->stylesheet_url();

	}


	/**
	 * Quickly register a script
	 * @access public
	 * @param  String $id      script unique identifier
	 * @param  String $itemurl js file locations
	 * @param  String $onpage  slug or "all", defaults to all.
	 * @return String          plugin-slug or theme
	 */
	function cuisine_register_script( $id, $itemurl, $onpage = 'all', $type = 'theme' ){

		if( $type == 'theme' ){
			$url = $cuisine->theme->url('scripts').'/';
			$rooturl = $cuisine->theme->root_url('scripts').'/';
		}else{
			$url = $cuisine->plugins->url( $type, true );
			$rooturl = $cuisine->plugins->root_url( $type, true );
		}

		global $cuisine;
		$args = array(
			'id'		=> $id,
			'url'		=> $url.$itemurl,
			'root_url'	=> $rooturl.$itemurl,
			'on_page'	=> $onpage
		);
			
		$cuisine->theme->register_scripts( $args );
	}
	

	/**
	 * Quickly compare to variables and output a string
	 *
	 * @access public
	 * @param  Var  $first  First variable
	 * @param  Var  $second Second variable
	 * @param  Boolean $raw    echo true/false
	 * @param  String  $class  what to echo/return
	 * @return String  $class
	 */
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
	*	@access public
	*	@return  Int post ID
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
	*	Check to see if this is a Cuisine overview page:
	*	@access public
	*	@return bool is this a cuisine-generated page?
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

	/**
	 * Gets a postobject by slug
	 * @access public
	 * @param  String $slug the posts slug
	 * @param  String $type the post_type
	 * @return Object post object.
	 */
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
	*	Check if this is the current post_type (in the admin)
	*	@access public
	*	@param  String post_type
	*	@return bool is this the current post_type?
	*/
	function cuisine_is_posttype( $t ){
		global $cuisine;
		return $cuisine->posttypes->admin_current( $t );
	}

	/**
	*	Get a nonce for a certain page:
	*	@access public
	*	@return String ( nonce HTML )
	*/

	function cuisine_get_nonce(){

		global $cuisine;
		$cuisine->plugins->get_plugin_nonce();
	}



	/**
	 * Register a post extra
	 * @param  String $id       unique identifier
	 * @param  String $label    Label
	 * @param  String $func     the function to call
	 * @param  Array  $js       javascript dependencies
	 * @param  Int $priority 	priority of this button
	 * @param  Array  $args     arguments ( found in plugins/register_post_extra )
	 * @return void
	 */
	function cuisine_register_post_extra( $id, $label, $func, $js = array(), $priority = null, $args = array() ){
		global $cuisine;
		$cuisine->plugins->register_post_extra( $id, $label, $func, $js, $priority, $args );

	}



?>