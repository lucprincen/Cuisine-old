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

	function cuisine_post_media( $pid = null, $posttype = null ){

		if( $pid == null )
			$pid = cuisine_get_post_id();

		if( $posttype == null )
			$posttype = get_post_type( $pid );


		return get_post_meta( $pid, $posttype.'_media', true );
	}

	function cuisine_video_embedcode( $id, $url, $width = 560, $height = 315 ){
		
		$html = '';
		if( cuisine_is_vimeo( $url ) ){
			//it's a vimeo movie:
			$html = '<iframe src="http://player.vimeo.com/video/'.$id.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';


		}else{
			//it's a youtube movie:
			$html = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';

		}

		echo $html;

	}

	function cuisine_is_vimeo( $string ){
		if( substr( $string, 0, 12 ) == 'http://vimeo' || substr( $string, 0, 13 ) == 'https://vimeo' || substr( $string, 0, 16 ) == 'http://www.vimeo' || substr( $string, 0, 17 ) == 'https://www.vimeo' ) return true;
		return false;
	}

?>