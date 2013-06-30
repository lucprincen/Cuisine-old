<?php
/**
 * Cuisine Button Shortcodes
 * 
 * Includes all the shortcodes for buttons.
 *
 * @author 		Chef du Web
 * @category 	Shortcodes
 * @package 	Cuisine
 */


function cuisine_button( $atts, $content = null ) {
	return cuisine_generate_button( $atts, $content );
}
add_shortcode('button', 'cuisine_button');

function cuisine_big_button( $atts, $content = null ) {
    $atts['size'] = 'big';
    return cuisine_generate_button( $atts, $content );
}
add_shortcode('bigbutton', 'cuisine_big_button');

function cuisine_small_button( $atts, $content = null ) {
    $atts['size'] = 'small';
    return cuisine_generate_button( $atts, $content );
}
add_shortcode('smallbutton', 'cuisine_small_button');

function cuisine_eyecatcher_button( $atts, $content = null ) {
    $atts['size'] = 'eyecatcer';
    return cuisine_generate_button( $atts, $content );
}
add_shortcode('eyecatcher', 'cuisine_eyecatcher_button');

function cuisine_pill_button( $atts, $content = null ) {
    $atts['size'] = 'pil';
    return cuisine_generate_button( $atts, $content );
}
add_shortcode('pilbutton', 'cuisine_pil_button');


function cuisine_generate_button( $atts, $content = null ){

	if( !isset( $atts['iconpos'] ) ) $atts['iconpos'] = 'right';

	$html = '<a href="'.$atts['link'].'"';

		if( isset( $atts['target'] ) )
			$html .= ' target="'.$atts['target'].'"';

		$html .= ' class="button';

			if( isset($atts['size'] ) )
				$html .= $atts['size'];

		$html .= '">';

		if( isset( $atts['icon'] ) && $atts['iconpos'] == 'left' )
			$html .= '<span class="icon '.$atts['icon'].'"></span>';

		$html .= $content;


		if( isset( $atts['icon'] ) && $atts['iconpos'] == 'right' )
			$html .= '<span class="icon '.$atts['icon'].'"></span>';


		$html .= '</a>';

		return $html;

}
?>