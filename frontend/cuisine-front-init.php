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

		// init video functions
		require_once( 'cuisine-front-videos.php' );

		// init color functions:
		require_once( 'cuisine-front-color.php' );

		// init query functions:
		require_once( 'cuisine-front-query.php' );

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