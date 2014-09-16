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
		$name = ''; $slug = '';

		if( in_array( get_post_type(), $keys ) ){
			
			$pt = $registered[ get_post_type() ];

			$name = $pt['plural'];
			$slug = $pt['slug'];

		}else if( get_post_type() == 'post' ){

			//get the rewrite rules:
			$rules = $cuisine->plugins->template_list;

			$slug = 'blog';
			$name = 'Blog';
			//get the slug out of the rewrite rules (default to blog)
			foreach( $rules as $rule ){
				if( $rule['object'] == 'post' ){

					$name = ucwords( $rule['slug'] );
					$slug = $rule['slug'];
					break;
				}
			}

		}

		$name = apply_filters( 'cuisine_breadcrumb_name', $name, $nodes );


		if( $name != '' && $slug != '' ){

			if( count( $nodes ) == 2 )
				$nodes[2] = $nodes[1];
			
			$nodes[1] = array(
							'text'	=> $name,
							'url'	=> get_bloginfo('url').'/'.$slug
			);

		}

		return $nodes;


	}
	

