<?php

/**
 * Cuisine Column Shortcodes
 * 
 * Includes all the shortcodes to create columns;
 *
 * @author 		Chef du Web
 * @category 	Shortcodes
 * @package 	Cuisine
 */

function cuisine_row($atts, $content = null){
   $content = preg_replace('#^<\/p>|<p>$#', '', $content);
   return '<div class="row-fluid">' . do_shortcode($content) . '</div>';
 }

add_shortcode("row", "cuisine_row");


function cuisine_span($atts, $content = null){
   extract(shortcode_atts(array(
      "columns" => '6',
      "offset" => '',
   ), $atts));

  $content = preg_replace('#^<\/p>|<p>$#', '', $content);
  return '<div class="span' . $columns . '' . ( empty ($offset) ? '' : ' offset' . $offset ) . '">' . do_shortcode($content) . '</div>';
}

add_shortcode("span", "cuisine_span");  

function cuisine_one_fourth( $atts, $content = null ) {
   return '<div class="onefourth span3 column">' . do_shortcode(wpautop($content)) . '</div>';
}
add_shortcode('one_fourth', 'cuisine_one_fourth');
add_shortcode('column3', 'cuisine_one_fourth');

function cuisine_one_fourth_last( $atts, $content = null ) {
   return '<div class="onefourth column span3 nomargin">' . do_shortcode(wpautop($content)) . '</div><div class="clearboth"></div>';
}
add_shortcode('one_fourth_last', 'cuisine_one_fourth_last');

function cuisine_one_third( $atts, $content = null ) {
   return '<div class="onethird column span4">' . do_shortcode(wpautop($content)) . '</div>';
}
add_shortcode('one_third', 'cuisine_one_third');
add_shortcode('column4', 'cuisine_one_third');

function cuisine_one_third_last( $atts, $content = null ) {
   return '<div class="onethird column span4 nomargin">' . do_shortcode(wpautop($content)) . '</div><div class="clearboth"></div>';
}
add_shortcode('one_third_last', 'cuisine_one_third_last');

function cuisine_two_third( $atts, $content = null ) {
   return '<div class="twothird span8 column">' . do_shortcode(wpautop($content)) . '</div>';
}
add_shortcode('two_third', 'cuisine_two_third');
add_shortcode('column8', 'cuisine_two_third');

function cuisine_two_third_last( $atts, $content = null ) {
   return '<div class="twothird span8 column nomargin">' . do_shortcode(wpautop($content)) . '</div><div class="clearboth"></div>';
}
add_shortcode('two_third_last', 'cuisine_two_third_last');

function cuisine_one_half( $atts, $content = null ) {
   return '<div class="half span6 column">' . do_shortcode(wpautop($content)) . '</div>';
}
add_shortcode('one_half', 'cuisine_one_half');
add_shortcode('column6', 'cuisine_one_half');

function cuisine_one_half_last( $atts, $content = null ) {
   return '<div class="half span6 column nomargin">' . do_shortcode(wpautop($content)) . '</div><div class="clearboth"></div>';
}
add_shortcode('one_half_last', 'cuisine_one_half_last');

function cuisine_break( $atts, $content = null ){
	return '<hr class="content-break"/>';
}

add_shortcode('break', 'cuisine_break');


function cuisine_intro( $atts, $content = null ){
  $html = '<div class="intro">';

  $html .= do_shortcode( wpautop( $content ) );

  $html .= '</div>';

  return $html;
}

add_shortcode('intro', 'cuisine_intro');
?>