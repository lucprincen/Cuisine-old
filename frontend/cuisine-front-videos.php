<?php

/**
 * Cuisine Front video functions
 * 
 * Handles the front video function.
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */


	//echo video embed
	function cuisine_the_video( $id, $type = 'youtube', $w = '560', $h = '315' ){
		echo cuisine_get_video( $id, $type, $w, $h );
	}


	//get video embed
	function cuisine_get_video( $id, $type = 'youtube', $width = '560', $height = '315' ){
		if( $type == 'youtube'){
			return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?wmode=transparent" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

		}else{
			return '<iframe src="http://player.vimeo.com/video/'.$id.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowfullscreen></iframe>';

		}

	}

?>