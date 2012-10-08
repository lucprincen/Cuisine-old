<?php

/**
 * Cuisine Shortcodes
 * 
 * Includes all the shortcode files
 *
 * @author 		Chef du Web
 * @category 	Shortcodes
 * @package 	Cuisine
 */

	function cuisine_shortcodes_include(){

		// Columns
		include( 'shortcode-columns.php' );
	
		//Text Highlights
		include( 'shortcode-highlights.php' );
	
		//Image frames
		include( 'shortcode-frames.php' );
	
		// Buttons
		include( 'shortcode-buttons.php' );

	}

	cuisine_shortcodes_include();

?>
