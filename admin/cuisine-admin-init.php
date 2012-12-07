<?php
/**
 * Cuisine Admin
 * 
 * Main admin file which loads all settings panels and sets up admin menus.
 * Next to that it init's the alternative admin area
 *
 * @author 		Chef du Web
 * @category 	Admin
 * @package 	Cuisine
 */


	add_action( 'init', 'cuisine_admin_includes', 1);
	add_action( 'admin_init', 'cuisine_admin_init' );
	add_action( 'admin_menu', 'cuisine_admin_scripts' );
	
	
	/**
	* Include required admin core files
	**/
	function cuisine_admin_includes(){
	
		if( is_admin() ){
			include( 'cuisine-admin-view.php' );						//all the simple view functions
			include( 'cuisine-admin-conditionals.php' );				//simple admin conditionals
			include( 'cuisine-admin-settings.php' );
			include( 'cuisine-admin-media.php');
		}
	}
	
	
	/**
	* Checks to see if Cuisine's simple view exists and toggles views.
	**/
	function cuisine_admin_init(){
	
	
		//if the simple view option doesnt exist, create it:
		if( !cuisine_simple_view_exists() ){
			cuisine_update_simple_view( 'false' );
		}
	
		//toggle views:
		cuisine_check_toggle_simple_view();
	
		if( cuisine_simple_view_is_active() ){

			//add the UI:
			add_action( 'admin_footer', 'cuisine_admin_simple_ui' );

			//add the body class:
			add_filter( 'admin_body_class', 'cuisine_admin_body_class' );

			//add simple view menu items from cuisine:
			add_filter( 'register_cuisine_simple_view_plugin', 'cuisine_register_native_menu_items' );


		}else{
			//add the top menu button:
			cuisine_advanced_view_button();
		}

	}
	
	
	/**
	* Add Cuisine's admin scripts.
	**/
	function cuisine_admin_scripts(){

		global $pagenow, $post, $cuisine;

		if( isset($_GET['post'] ) ){
			wp_localize_script( 'jquery', 'JSvars', array( 'post_id' => $_GET['post'], 'post_type' => get_post_type( $_GET['post'] ), 'adminurl' => admin_url(), 'pluginurl' => $cuisine->plugin_url, 'asseturl' => $cuisine->asset_url ));		
		
		}else if( isset( $_GET['post_type'] ) ){
			wp_localize_script( 'jquery', 'JSvars', array( 'post_type' => $_GET['post_type'], 'adminurl' => admin_url(), 'pluginurl' => $cuisine->plugin_url, 'asseturl' => $cuisine->asset_url ));		

		}else{
			wp_localize_script( 'jquery', 'JSvars', array( 'adminurl' => admin_url(), 'pluginurl' => $cuisine->plugin_url, 'asseturl' => $cuisine->asset_url ));

		}

		// Add scripts for simple view:
		if( cuisine_simple_view_is_active() ){
				
	
			if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ){
			    //first check for the editscreen:
			    wp_enqueue_script( 'cuisine_edit_script', $cuisine->asset_url.'/js/admin-edit.js' );

	
			}else if( $pagenow == 'media-upload.php' && isset( $_GET['cuisine_media'] ) ){
				//check for the media library
				wp_enqueue_style( 'cuisine_media_style', $cuisine->asset_url.'/css/admin-media.css' );
				wp_enqueue_script( 'cuisine_media_script', $cuisine->asset_url.'/js/admin-media.js' );
		
			}
	
		}
		

		//add the general admin styles:
		wp_enqueue_style( 'cuisine_admin', $cuisine->asset_url.'/css/admin.css' );

		//add the scripts for the general admin area:

		wp_enqueue_script( 'cuisine_admin', $cuisine->asset_url.'/js/admin.js', array('jquery', 'jquery-ui-sortable', 'thickbox'), FALSE, TRUE  );

		
		// Add scripts for normal view:
			
		if( $pagenow == 'widgets.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php' ){

			wp_enqueue_style( 'thickbox' );

		}else if( $pagenow == 'media-upload.php' && isset( $_GET['cuisine_widget_media'] ) ){
			//check for the media library for widgets
			wp_enqueue_script( 'cuisine_widget_media_script', $cuisine->asset_url.'/js/admin-widgets-media.js' );
		
		}else if( $pagenow == 'media-upload.php' && isset( $_GET['cuisine_plugin_media'] ) ){
			wp_enqueue_script('cuisine_media_script', $cuisine->asset_url. '/js/admin-plugin-media.js', 'jquery');
		
		}

	}

	

	/**
	* Add the cuisine UI file and outputs the UI in the footer
	**/
	function cuisine_admin_simple_ui(){
		global $pagenow;

		//include the UI view file:
		include_once( 'cuisine-admin-ui.php' );

		if( $pagenow == 'index.php' )
			cuisine_simple_ui_init();

		if( $pagenow == 'edit.php')
			cuisine_simple_ui_overview();
	
		//add the button:
		cuisine_simple_view_button();

		$settings = get_cuisine_setting( 'simple_view' );

		if( $settings['back_button'] == true )
			cuisine_simple_view_back_button();

	}


	/**
	*	Add the admin body class:
	*/
	
	function cuisine_admin_body_class( $classes ){
		$classes .= 'simple-view';
		return $classes;
	}

	/**
	*	Add the simple view button to the admin bar:
	*/
	function cuisine_advanced_view_button(){
		//else add the button of god (if a user is capable)
		if( current_user_can( 'toggle_admin_mode' ) ){
			add_action( 'admin_bar_menu', 'cuisine_admin_bar_menu', 1000 );
		}
	}

	/**
	*	Admin bar button:
	*/

	function cuisine_admin_bar_menu(){
		global $wp_admin_bar;
		/* Add the main siteadmin menu item */
		$wp_admin_bar->add_menu( array(
 			'id'     => 'debug-bar',
			'href' => admin_url().'?toggle-simple-view=true',
			'parent' => 'top-secondary',
			'title'  => __('Simple view', 'cuisine'),
		) );
	}


	/**
	*	Menu items to be added to Simple View from Cuisine:
	*/

	function cuisine_register_native_menu_items( $array ){

		global $cuisine;
		$settings = get_cuisine_setting( 'simple_view' );

		if( $settings['edit_menus'] ){

			$a['Title'] = 'Menu\'s';
			$a['link'] = 'nav-menus.php';
			$a['icon']	= $cuisine->asset_url.'/images/menu-icon.png';
			$array[] = $a;
		}


		return $array;

	}

	
	/**
	* Install cuisine
	**/
	function install_cuisine() {
		include_once( 'cuisine-admin-install.php' );
		do_install_cuisine();
	}

?>