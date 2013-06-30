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
		
		// Buttons
		include( 'shortcode-buttons.php' );

		// STEPS:
		include( 'shortcode-steps.php' );

	}

	cuisine_shortcodes_include();

?>
