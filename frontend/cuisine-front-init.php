<?php

/**
 * Cuisine Front actions
 * 
 * Handles the includes for the frontend functions.
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */


	add_action( 'init', 'cuisine_front_init', 1 );
	add_action( 'login_head', 'cuisine_admin_login' );


	function cuisine_front_init(){

		global $cuisine, $post; 

		// init image functions
		require_once( 'cuisine-front-images.php' );

		// init image functions
		require_once( 'cuisine-front-videos.php' );

	
		// init excerpt functions
		require_once( 'cuisine-front-excerpt.php' );
	
		// init comments functions
		require_once( 'cuisine-front-comments.php' );	
		
		// include button functions:
		require_once( 'cuisine-front-buttons.php' );
	
		// get chef du web copyright
		require_once( 'cuisine-front-copyright.php' );

		// make sure we have clean code in Chef's themes:
		require_once( 'cuisine-front-clean-code.php' );


		// Register Cuisine frontend javascripts
		$args = array(
			'id'			=>	'cuisine_frontend',
			'root_url'		=>	$cuisine->plugins->root_url('cuisine', true ).'assets/js/cuisine-front.js',
			'on_page'		=>	'all'
		);

		$cuisine->theme->register_scripts( $args );

		$args = array(
		     'id'			=> 	'chef-front-script',
		     'root_url'		=>	$cuisine->theme->root_url( 'scripts', true ).'script.js',
		     'on_page'		=> 'all'
		);

		$cuisine->theme->register_scripts( $args );

		if( isset( $post ) )
			wp_localize_script( 'chef-front-script', 'post', array( 'ID' => $post->ID, 'post_title' => $post->post_title, 'slug' => $post->post_name, 'post_parent' => $post->post_parent, 'guid' => $post->guid ) );
	}


	/**
	* Add the custom login style
	* 
	* @access public
	* @return void
	**/

	function cuisine_admin_login(){

		global $cuisine;

		//enqueue the style:
		wp_enqueue_style( 'cuisine_login_style', $cuisine->asset_url.'/css/login.css' );

	}

?>