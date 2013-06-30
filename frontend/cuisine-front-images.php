<?php

/**
 * Cuisine Front image functions
 * 
 * Handles lazy loading images and some post-thumbnail stuff.
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */


	function cuisine_img( $url, $supports = array( 'desktop', 'tablet', 'mobile' ), $args = array() ){
		echo cuisine_get_img( $url, $supports, $args );
	}


	function cuisine_get_img( $url, $supports = array( 'desktop', 'tablet', 'mobile' ), $args = array() ){

		if( $url == null || $url == '' ) return false;

		global $cuisine;

		$class = 'lazy-img cuisine-img';
		if( isset( $args['class'] ) ) $class .= ' '.$args['class'];
		
		$class .= cuisine_get_img_class( $supports );

		$title = '';
		$alt = '';
		$extension = cuisine_get_img_extension( $url );

		$src = $cuisine->asset_url.'/images/0.gif';

		if( isset( $args['title'] ) ) $title = ' title="'.$args['title'].'"';
		if( isset( $args['alt'] ) ) $alt = ' alt="'.$args['alt'].'"';


		$html = '<img src="'.$src.'" data-src="'.$url.'" ';
		$html .= 'data-extension="'.$extension.'" ';
		$html .= 'class="'.$class.'" ';
		$html .= $title;
		$html .= $alt;
		$html .= '/>';

		return $html;

	}




	function cuisine_get_img_class( $supports ){
		$c = '';
		if( in_array( 'desktop', $supports ) )
			$c .= ' desktop-load';
	
		if( in_array( 'mobile', $supports ) )
			$c .= ' mobile-load';

		if( in_array( 'tablet', $supports ) )
			$c .= ' tablet-load';

		if( in_array( 'retina', $supports ) )
			$c .= ' retina-load';

		return $c;
	}

	function cuisine_get_img_extension( $url ){

		$ex = substr( $url, -4 );
		if( $ex == '.jpg' || 'jpeg' ){
			return 'jpg';
		}else if( $ex == '.png' ){
			return 'png';
		}else if( $ex == '.gif' ){
			return 'gif';
		}

		return 'unknown';

	}


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

	function cuisine_get_image_id_by_url( $image_url ){
	
		global $wpdb;
		$prefix = $wpdb->prefix;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $image_url )); 

        return $attachment[0]; 
        
	}

?>