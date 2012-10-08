<?php

/**
 * Cuisine Front excerpt functions
 * 
 * Handles the front excerpt functions.
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */

	
	function cuisine_set_excerpt( $args ){

		if( !empty( $args ) ){


			if( isset( $args['more'] ) ){
				$cuisine_excerpt_args['more'] = $args['more'];
				add_filter('excerpt_more', 'cuisine_excerpt_more');
			}

			if( isset( $args['length'] ) ){
				$cuisine_excerpt_args['length'] = $args['length'];
				add_filter( 'excerpt_length', 'cuisine_excerpt_length', 999 );
			}

			$GLOBALS['cuisine_excerpt_args'] = $cuisine_excerpt_args;
		}
	}




	// set custom excerpt end
	function cuisine_excerpt_more( $more ){
		global $cuisine_excerpt_args;
		return $cuisine_excerpt_args['more'];
	}

	// set custom excerpt length
	function cuisine_excerpt_length( $length ) {
		global $cuisine_excerpt_args;
		return $cuisine_excerpt_args['length'];
	}


	// create custom excerpt
	function cuisine_excerpt( $string, $limit = 0, $break = '.', $pad = '...' ){

		echo cuisine_get_excerpt( $string, $limit, $break, $pad );
	}


	// custom excerpt return:
	function cuisine_get_excerpt( $string, $limit = 0, $break = '.', $pad = '...' ){

		$string = strip_tags($string);
		
		if( $limit == 0 ){

			if( isset($cuisine_excerpt_args['length'] ) ){

				$limit = $cuisine_excerpt_args['length']; 

			}else{

				$limit = 120;

			}
		}

		// return with no change if string is shorter than $limit
		if(strlen($string) <= $limit) return $string;

		// is $break present between $limit and the end of the string?
		if( ( $breakpoint = strpos( $string, $break, $limit ) ) !== false ) {
		
			if( $breakpoint < strlen( $string ) - 1 ) {
				$string = substr( $string, 0, $breakpoint ) . $pad;
    		}
		}
		
		return $string;
	}



?>