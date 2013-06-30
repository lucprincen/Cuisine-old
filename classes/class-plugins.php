<?php
/**
 * Cuisine Plugins handles the admin initiation and the compatibilities of a Chef du Web plugin
 *
 * @class 		Cuisine_Plugins
 * @package		Cuisine
 * @category	Class
 * @author		Chef du Web
 */
class Cuisine_Plugins {



	var $plugin_main_menu_items = array();
	var $plugin_options_menu_items = array();

	var $plugin_data_to_save = array();

	var $tinymce_plugins = array();

	var $nonce_list = array();
	var $redirect_list = array();
	var $redirect_list_types = array();

	var $postextras = array();



	function __construct(){
		
		// the plugin_main_menu_items contain arrays for which pluginmenu's to add on the main admin_menu hook
		$this->plugin_main_menu_items = array();

		// the plugin_options_menu_items contain arrays for which pluginmenu's to add on the admin_menu hook for options
		$this->plugin_options_menu_items = array();


	}


	function init(){ 

		// add the save_post hook
		add_action( 'save_post', array( &$this, 'do_save_post' ), 0 );

	}



	/*************************************************************************/
	/** Metabox Functions ****************************************************/
	/*************************************************************************/


	function add_plugin_meta( $meta ){

		//meta['id']
		//meta['title']
		//meta['data']			optional (add data to add to the save loop)
		//meta['function'] 		optional (defaults to meta[id].'_html')
		//meta['post_id'] 		optional (either this or post_type)
		//meta['post_type'] 	optional (either this or post_id)
		//meta['context'] 		(normal / side )optional 
		//meta['priority'] 		(high / default) optional


		if( !isset( $meta['id'] ) )
			throw new Exception("a metabox needs an ID");

		$meta = $this->sanitize_meta( $meta );

		//First check if this is for a specific post:		
		if( isset($meta['post_id'] ) && $meta['post_id'] != null ){
			$pid = $meta['post_id'];

			if( !isset( $this->plugin_data_to_save[$pid] ) )
				$this->plugin_data_to_save[$pid] = array();

			if( !empty($meta['data'] ) ){
				//add the meta data to the to_save list
				$this->plugin_data_to_save[$pid][] = $meta['data'];
			}

			// Check for the specific post id:
			if( isset( $_GET['post'] ) && $_GET['post'] == $meta['post_id'] ){
				//get the post_type for WordPress:
				$meta['post_type'] = get_post_type( $meta['post_id'] );
				//add the metabox:
				add_meta_box( $meta['id'], $meta['title'], $meta['function'], $meta['post_type'], $meta['context'], $meta['priority'] );
			}

		//then check if this is for a specific post_type (including 'all')
		}else if( !empty( $meta['post_type'] ) && $meta['post_type'] != null){

			$pid = $meta['post_type'];
			if( !isset( $this->plugin_data_to_save[$pid] ) )
				$this->plugin_data_to_save[$pid] = array();

			if( !empty($meta['data'] ) ){
				//add the meta data to the to_save list
				$this->plugin_data_to_save[$pid][] = $meta['data'];
			}


			// if this metabox applies to all post types:
			if($meta['post_type'] == 'all'){

				//get alle posttypes:
				global $cuisine;
				$posttypes = $cuisine->posttypes->find();

			}else{

				//populate the posttypes array with the set post type
				$posttypes[] = $meta['post_type'];
			}


			//loop through the posttypes:
			foreach( $posttypes as $pt ){

				//add the meta_box for eacht post type
				add_meta_box($meta['id'], $meta['title'], $meta['function'], $pt, $meta['context'], $meta['priority'] );
			}
		}

	}


	/**
	*	Save a post with the data registered to save
	*/

	function do_save_post( $post_id ){


      	//check if there is data to save:
		if( !empty($this->plugin_data_to_save ) ){


			//check permissions:
			if ( !current_user_can( 'edit_cuisine_content', $post_id ) )
				return $post_id;
	
			//check if we aren't running an autosave:
  			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
     	 		return $post_id;
	

	 	 	//check for the nonce:
     	 	if ( !isset( $_POST[ $this->get_nonce_name() ] ) )
     	 		return $post_id;


     	 	//then verify the nonce:
	 	 	if ( !wp_verify_nonce( $_POST[ $this->get_nonce_name() ], plugin_basename( __FILE__ ) ) )
    			return $post_id;
	

    		//check for a valid Cuisine session:
    		if ( !cuisine_is_valid_session() )
    			return $post_id;


			$save = $this->plugin_data_to_save;

			$current_post_type = get_post_type( $post_id );


			//first check data to save for 'all' post types
			if( isset($save['all'] ) ){

				foreach( $save['all'] as $item ){

					$this->save_meta_to_post( $post_id, $item );
				}
			}


			//then on post types:
			if( isset( $save[$current_post_type] ) ){

				foreach( $save[$current_post_type] as $item ){


					$this->save_meta_to_post( $post_id, $item );

				}
			}


			//then on post id:
			if( isset( $save[$post_id] ) ){
				foreach( $save[$post_id] as $item ){

					$this->save_meta_to_post( $post_id, $item );
				}
			}


		}	
	}



	/**
	*	SAVES A SINGLE PIECE OF METADATA:
	*/

	function save_meta_to_post( $post_id, $item ){
		$values = $this->sanitize_values( $item['value'] );
		
		//allow plugins to change and filter the data to save at the last minute: 	
		$values = apply_filters( 'cuisine_save_data', $values, $item, $post_id );


		if( $values ){
					
			if( isset( $item['orderby'] ) && is_array( $item['value'] ) )
				$values = cuisine_sort_array_by( $values, $item['orderby'], $item['order'] );

			update_post_meta( $post_id, $item['key'], $values );
		}

	}



	/**
	*	Generate a custom nonce name using the post id or the current php page:
	*/

	function get_nonce_name(){

		if( isset( $_GET['post'] ) ){
			return 'cuisine_nonce_'.$_GET['post'];
		}else if( isset( $_GET['page'] ) ){
			return 'cuisine_nonce_'.$_GET['page'];
		}else {
			
			global $post;
			if( !empty( $post ) )
				return 'cuisine_nonce_'.$post->ID;

			global $pagenow;
			return 'cuisine_nonce_'.str_replace( '.', '_', $pagenow );
		}
	}


	/**
	*	Show a nonce on the specific page:
	*/

	function get_plugin_nonce(){

		if( !$this->has_nonce( $this->get_nonce_name() ) )
			wp_nonce_field( plugin_basename( __FILE__ ), $this->get_nonce_name() );
	
	}


	/**
	*	Check if a nonce already exists:
	*/

	function has_nonce( $nonce_name ){

		if( in_array( $nonce_name, $this->nonce_list ) ){
			return true;
		
		}else{
			$this->nonce_list[] = $nonce_name;
			return false;
		}

	}


	/**
	*	Add default values to the meta array:
	*/

	function sanitize_meta( $meta ){

		//check if there's a title set:
		if( empty($meta['title'] ) )
			$meta['title'] = 'New Metabox';


		//check if there's a context:
		if( empty($meta['context'] ) )
			$meta['context'] = 'normal';
		

		//check if there's a priority:
		if( empty($meta['priority'] ) )
			$meta['priority'] = 'default';
		

		//check if there's a function:
		if( empty($meta['function'] ) )
			$meta['function'] = $meta['id'].'_html';

		//add the post_id if a post slug is given:
		if( !empty($meta['post'] ) ){
			$pid = url_to_postid( $meta['post'] );
			$meta['post_id'] = $pid;
		}

		if( !empty( $meta['data']['orderby'] ) && empty( $meta['data']['order'] ) )
			$meta['data']['order'] = 'ASC';


		return $meta;
	}


	/**
	*	Wrap all variables in $_POST[]
	*/

	function sanitize_values( $values ){
		
		$array = array();
		
		//Add the $_POST wrapper:
		if( is_array($values) ){
			foreach( $values as $value ){

				$array[$value] = $_POST[$value];

			}

			return $array;
		}else if( isset( $_POST[$values] ) ){
			return $_POST[$values];
		
		}else{
			return false;
		
		}
	}



	/*************************************************************************/
	/** Menu Functions *******************************************************/
	/*************************************************************************/


    /**
	 * Register a single plugin page:
	 */

    function add_plugin_page($type, $menu, $func = null){


    	//$menu['title']
    	//$menu['label']
    	//$menu['capability']
    	//$menu['id']
    	//$menu['func']
    	//$menu['icon_url']
    	//$menu['position']


    	if($type == 'main'){
			$this->plugin_main_menu_items[] = array($menu, $menu['func']);

    	}else if($type == 'options'){
			$this->plugin_options_menu_items[] = array($menu, $menu['func']);
    	}


		if(!has_action( 'admin_menu', array( &$this, 'add_plugin_menu_items' ))){
			add_action( 'admin_menu', array( &$this, 'add_plugin_menu_items' ));
	
	   	}
    }


	 /**
	 * Add a single plugin options or main menuitem:
	 */

	function add_plugin_menu_items(){

		// FIRST, THE MAIN MENU:

		//go by all the saved main menu items:

		foreach($this->plugin_main_menu_items as $menuitem){

			$menu = $this->sanitize_menuitems($menuitem[0], $menuitem[1]);

			//add the options page
			add_menu_page( $menu['title'], $menu['label'], $menu['capability'], $menu['id'], $menu['func'], $menu['icon_url'], $menu['position'] );
			
			if( isset($menu['subpages']) && !empty($menu['subpages'] ) ){

				foreach( $menu['subpages'] as $subpage ){
					
					add_submenu_page( $menu['id'], $subpage['title'], $subpage['label'], $menu['capability'], $subpage['id'], $subpage['func']);

				}

			}

		}

		// SECOND, ALL THE OPTIONS PAGES:

		//go by all the saved options menu items:
		foreach($this->plugin_options_menu_items as $menuitem){
			$menu = $this->sanitize_menuitems($menuitem[0], $menuitem[1]);

			//add the options page
			add_options_page( $menu['title'], $menu['label'], $menu['capability'], $menu['id'], $menu['func'] );

		}

		//clear the menu items variable
		$this->plugin_main_menu_items = array();
		$this->plugin_options_menu_items = array();
	
	}


	/**
	*	Creates a neat array of menu-items and fills in the blanks.
	*/

	function sanitize_menuitems($menu, $func = null){
		if( empty($menu['label']) ){
			$menu['label'] = $menu['title'];
		}

		if( empty($menu['capability']) ){
			$menu['capability'] = 'manage_cuisine';
		}

		if( empty($menu['icon_url']) ){
			$menu['icon_url'] = null;
		}

		if( empty($menu['position']) ){
			$menu['position'] = null;
		}

		if( $func == null ){
			$menu['func'] = $menu['id'].'-page';
		}else{
			$menu['func'] = $func;
		}

		return $menu;
	}


	 /**
	 * Register a plugin submenu:
	 */

	 function add_plugin_submenu(){

	 }


	/*************************************************************************/
	/** Plugin PostExtras ****************************************************/
	/*************************************************************************/

	/**
	*	Register a post-extra; very much like a metabox. 
	*	Add a php ouput function, js-files and arguments.
	*/
	function register_post_extra( $id, $label, $function, $js = array(), $priority = null,  $args = array() ){

		$this->postextras[ $id ] = array( 
		                                 'id' 		=> $id,
		                                 'label' 	=> $label,
		                                 'priority' => $priority,
		                                 'func' 	=> $function,
		                                 'js' 		=> $js,
		                                 'args' 	=> $args
		                           );


		if( !has_filter( 'cuisine_post_extras_tabs', array( &$this, 'post_extra_tabs'), null, 1 ) )
			add_filter( 'cuisine_post_extras_tabs', array( &$this, 'post_extra_tabs' ), null, 1 );

		if( !has_action( 'cuisine_post_extras', array( &$this, 'post_extra_functions' ) ) )
			add_action( 'cuisine_post_extras', array( &$this, 'post_extra_functions' ) ); 
	
	}

	/**
	*	Display all the post-extra divs:
	*/
	function post_extra_functions(){

		if( !empty( $this->postextras ) ){

			foreach( $this->postextras as $extra ){

				if( function_exists( $extra['func'] ) ){

					echo '<div class="post-extra" id="pe_'.$extra['id'].'" data-id="'.$extra['id'].'">';

						call_user_func( $extra['func'], $extra['args'] );

					echo '</div>';


					if( !empty( $extra['js'] ) ){

						foreach( $extra['js'] as $url ){

							wp_enqueue_script( $extra['id'].'_js', $url, array(), null, true );

						}

					}
				}
			}
		}
	}


	/**
	*	return all the post_extra tabs:
	*/
	function post_extra_tabs( $tabs ){

		$t = array();
		if( !empty( $this->postextras ) ){

			foreach( $this->postextras as $extra ){

				$default_js_func = apply_filters( 'cuisine_post_extra_js_output', str_replace('-', '_', $extra['id']).'_output', $extra['id'] );

				$t[] = array( 
				             'id' 		=> $extra['id'],
				             'title' 	=> $extra['label'],
				             'priority' => $extra['priority'],
				             'jsfunc' 	=> $default_js_func
				       );

			}

			if( !empty( $t ) )
				$tabs = cuisine_sort_array_by( $t, 'priority', 'ASC' );

		}

		return $tabs;

	}



	/*************************************************************************/
	/** Redirect Functions ***************************************************/
	/*************************************************************************/


	 /**
	 * Register a redirect domain:
	 */

	function register_template_redirect( $slug, $type = null, $post_type = null, $page_object = array() ){


		if( $type == null )
			$type = 'post_type';

		if( $post_type == null )
			$post_type = $slug;

		
		//don't add pages to the redirect list, they already have great permalink handling:
		if( $type != 'page' ){
			//add the slug to the redirect list:
			$this->redirect_list[$type][] = $slug; 
		}

		$this->redirect_list_types[$type][ $slug ] = $post_type;

		if( $type == 'page' && $this->has_permalink_structure() ) $this->register_page_object( $slug, $page_object );


		//add the template_redirect action if it doesn't exist yet:
		if( !has_action( 'template_redirect', array( &$this, 'add_template_redirects' ) ) ){

			add_action( 'template_redirect', array( &$this, 'add_template_redirects' ) );

		}
		
		add_filter( 'rewrite_rules_array', array( &$this, 'add_template_rewrites') );

		//check if there's a rewrite filter in place:
		if( !has_action( 'shutdown', array( &$this, 'flush_rewrites' ) ) ){

			add_action( 'shutdown', array( &$this, 'flush_rewrites' ) );

		}

	}



	/**
	*	Add a page, if it doesn't exist yet:
	*/

	function register_page_object( $slug, $object = array() ){

		//fill in the blanks:
		$object = $this->sanitize_page_object( $slug, $object );

		//check if the page exists:
		$page_id = get_page_by_path( $slug );

		
		//if it doesn't, create the page:
		if($page_id == null){
				
			wp_insert_post(array(
				'post_title' 	=> $object['post_title'],
				'post_content'	=> $object['post_content'],
				'post_type' 	=> 'page',
				'post_status'	=> 'publish',
				'post_name'		=> $slug
			));
		}
	}



	/**
	*	Sanitize the page object:
	*/

	function sanitize_page_object( $slug, $object ){

		if( empty( $object['post_title'] ) )
			$object['post_title'] = ucwords(str_replace('-', ' ', $slug ) );

		if( empty( $object['post_content'] ) )
			$object['post_content'] = '';


		return $object;
	}



	 /**
	 * 	Add all template redirects:
	 */

	 function add_template_redirects(){

	 	//get the global query:
	 	global $wp_query, $cuisine;

	 	//check if there's a global query:
		if ( isset($wp_query->query_vars) ){

			//then check if we're not servicing a robot or feed:
			if( !is_robots() && !is_feed() && !is_trackback()  && !is_404() ){

				//get the queries post type:
				$posttype = $wp_query->query_vars['post_type'];

				if( $posttype == '' )
					$posttype = $wp_query->post->post_type;

				if( $posttype == '' && $wp_query->is_single == true )
					$posttype = 'post';

				$types = array_values( $this->redirect_list_types['post_type'] );


				//check if we are dealing with a post type overview or a single page:
				if( ! empty( $posttype ) ){


					//check if the post type is in the redirects list:
					if( in_array( $posttype, $types ) || $posttype == 'page' ){
							
						//it's a page:
						if( $posttype == 'page' && !empty( $this->redirect_list_types['page'] ) ){
							
							$pages = $this->redirect_list_types['page'];

							//we need to compare the post slug to the title in the query:
							$queried_slug = $wp_query->post->post_name;
							
							//loop through the pages in the redirect array:
							foreach( $pages as $slug => $page ){

								//the page is the same as the queried object, we are redirecting:
								if( $slug == $queried_slug ){

									$wp_query->cuisine_slug = $page;

									//create a template name out of the slug;
									$template_file = str_replace(' ', '-', strtolower( $page ) );

									//locate the template:
									locate_template( array( 'plugin-templates/template-'.$template_file.'.php', 'page.php', 'index.php' ), true );
									die();
								}

							}

						// else it's a post or post_type:
						}else if( in_array( $posttype, $types ) ){
							//it is, we are redirecting:
							$wp_query->is_home = false;
		
							if( $posttype == 'post' )
								$posttype = 'blog';
		

							$wp_query->cuisine_slug = $posttype;


							//check for a single:
							if( is_single() ){
								$wp_query->is_custom_post_type_archive = false;
	
	
								locate_template( array( 'plugin-templates/'.$posttype.'-single.php', 'single.php', 'index.php' ), true );
								die();
							
							//else it's an archive:
							}else{
		
								//check if we're dealing with a paged querie:
								if( $wp_query->query_vars['paged'] > 1 ){
									$wp_query->is_404 = false;
									$wp_query->is_paged = true;
								}
		
								$wp_query->is_custom_post_type_archive = true;
								locate_template( array( 'plugin-templates/'.$posttype.'.php', 'index.php' ), true );
								die();
							}
						}
					}
				}
			}
		}
	 }



	 /**
	 *	Add all template rewrites:
	 */

	function add_template_rewrites($rules) {
		
		$newrules = array();
		$types = $this->redirect_list;

		//for all registered types:
		foreach( $types as $key => $type ){


			// if the type isn't empty:
			if( !empty( $type ) && $type != 'page' ){

				//loop through the rewrites in the type:
				foreach( $type as $rewrite ){

					//get the slug:
					$slug = $this->redirect_list_types[$key][ $rewrite ];
		
					//add the new rule:
					$newrules[$rewrite.'/?$'] = 'index.php?post_type='.$slug;
					$newrules[$rewrite.'/page/?([0-9]{1,})/?$'] = 'index.php?post_type='.$slug.'&paged=$matches[1]';
				}
			}
		}	

		//and add the new rules to the existing template rewrites:
		return array_merge($newrules, $rules);
	
	}




	/**
	*	FLUSH THE REWRITES:
	*/	

	function flush_rewrites(){


		//if we're in the admin:
		if( is_admin() ){

			$rewrites = $this->redirect_list;
			$rewritestring = '';

			//create the rewrite string:
			if( !empty( $rewrites ) ){
				foreach( $rewrites as $rewrite ){
					$rewritestring .= implode('|', $rewrite ).'|';
				}
			}

			//check if there are new redirects:
			$redirects = get_cuisine_setting( 'rewrites' );


			if( empty( $redirects ) || $redirects != $rewritestring ){


				//then flush the rewrite rules:
				global $wp_rewrite;
				$wp_rewrite->flush_rules();

				//and update the redirects options:
				update_cuisine_setting( 'rewrites', $rewritestring );
			}

		}
	}



	/**
	*	CHECK THE PERMALINK STRUCTURE:
	*/

	function has_permalink_structure(){

		//get the option:
		$structure = get_option( 'permalink_structure', '' );
		if( $structure == apply_filters('cuisine_permalink_structure', '/%postname%/' ) ) return true;

		return false;

	} 


	/**
	*	GET ROOT URLS:
	*/

	function root_url( $slug = '', $trail = false ){
		
		$url = ABSPATH.'wp-content/plugins';

		if( $slug != '' ) $url .= '/'.$slug;

		if( $trail ) $url .= '/';

		return $url;		

	}

	/**
	*	Get page url by slug:
	*/

	function get_page_url( $slug ){

		$page = get_page_by_path( $slug );

		if( !empty( $page ) )
			return get_permalink( $page->ID );

		return '';
	}

}?>