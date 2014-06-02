<?php

/**
 * Cuisine Front breadcrumbs
 * 
 * Handles the breadcrumbs with WP Seo
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */



	add_filter( 'wpseo_breadcrumb_links', 'cuisine_breadcrumb_nodes', 100, 1 );

	/**
	 * Prepend a singular page with the right post-type overview:
	 *
	 * @access public
	 * @param Array (array of posts)
	 * @return Array
	 */
	function cuisine_breadcrumb_nodes( $nodes ){

		global $cuisine;
		$registered = $cuisine->posttypes->registered;
		$keys = array_keys( $registered );

		if( is_single() && in_array( get_post_type(), $keys ) ){
			
			$pt = $registered[ get_post_type() ];

			if( count( $nodes ) == 2 ){

				$nodes[2] = $nodes[1];
				$nodes[1] = array(
								'text'	=> $pt['plural'],
								'url'	=> get_bloginfo('url').'/'.$pt['slug']
				);

			}

		}

		return $nodes;

	}
	

