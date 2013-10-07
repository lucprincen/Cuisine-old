<?php
/**
 * Cuisine Highlights Shortcodes
 * 
 * Includes all the highlight shortcodes.
 *
 * @author 		Chef du Web
 * @category 	Shortcodes
 * @package 	Cuisine
 */


	function cuisine_icon( $atts, $content = null ){

  		if( isset( $atts['type'] ) )
    		return '<i class="icon-'.$atts['type'].'"></i>';
	}

	add_shortcode('icon', 'cuisine_icon');




?>