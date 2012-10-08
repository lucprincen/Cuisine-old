<?php

/**
 * Cuisine Front image functions
 * 
 * Handles the front image function.
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */


	//echo image src by post id
	function cuisine_the_thumb_src( $pid = null , $size = 'thumbnail' ){
		echo cuisine_get_thumbnail_src( $pid, $size );
	}


	//get image src by post id
	function cuisine_get_thumb_src( $pid = null , $size = 'thumbnail' ){
		
		if($pid == null){
			global $post;
			if( isset($post) ){
				$pid = $post->ID;
			}else{
				throw new Exception("You need to specify a post ID or place this function in the Loop.");
			}
		}

		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), $size );
		return $src[0];

	}


	function cuisine_the_img_src( $pid, $size = 'thumbnail' ){
		echo cuisine_get_img_src( $pid, $size );
	}

	//get image src by post id
	function cuisine_get_img_src( $pid = null , $size = 'thumbnail' ){
		
		if($pid == null){
			global $post;
			if( isset($post) ){
				$pid = $post->ID;
			}else{
				throw new Exception("You need to specify a post ID or place this function in the Loop.");
			}
		}

		$src = wp_get_attachment_image_src( $pid, $size );
		return $src[0];

	}

?>