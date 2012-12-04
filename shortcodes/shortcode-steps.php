<?php
/**
 * Cuisine STEPS Shortcodes
 * 
 * Includes all the shortcodes for steps.
 *
 * @author 		Chef du Web
 * @category 	Shortcodes
 * @package 	Cuisine
 */


function cuisine_step( $atts, $content = null ) {

	$html = '<div class="cuisine-step" id="step-'.$atts['id'].'">';
	$html .= $content;
	$html .= '</div>';

	return $html;
}

add_shortcode('step', 'cuisine_step');


?>