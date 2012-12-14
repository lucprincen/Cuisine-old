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

	function cuisine_front_init(){

		global $cuisine; 

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
			'id'		=>	'cuisine_frontend',
			'url'		=>	$cuisine->asset_url.'/js/cuisine-front.js',
		);

		$cuisine->theme->register_scripts( $args );

	}

	add_action('wp-footer', 'cuisine_frontend_scripts' );

	function cuisine_frontend_scripts(){

		global $cuisine, $post;

	 	wp_enqueue_script( 'chef-front-script', $cuisine->theme->url('scripts').'script.js', null, null, true );
		wp_localize_script( 'chef-front-script', 'post', array( 'ID' => $post->ID, 'post_title' => $post->post_title, 'slug' => $post->post_name, 'post_parent' => $post->post_parent, 'guid' => $post->guid ) );

	}	

?>