<?php

/**
 * Cuisine Front pagination
 * 
 * Handles pagenation of any given query (but defaults to the main query)
 * Previously in a separate project (Chef-navigation)
 * 
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */

	/**
	 * Just a shortcut to @cuisine_navigation()
	 * 
	 */
	function cuisine_nav( $query = null, $echo = true ){
		return cuisine_navigation( $query, $echo );
	}


	/**
	 * Get's the query and figures out the amount of pages and which page is current
	 *
	 * @access public
	 * @param  Object $query (defaults to an empty array )
	 * @return String (html)
	 */
	function cuisine_navigation( $query = null, $echo = true ){

		if( $query == null ){

			global $wp_query;
			$query = $wp_query;
		
		}

		$ppp = $query->query_vars['posts_per_page'];
		$total = $query->found_posts;
		$current = $query->query_vars['paged'];

		if( $current == 0 )
			$current = 1;


		$pages = ceil( $total / $ppp );
		
		//we have pages:
		if( $pages > 1 ){

			$html = '';
			$html .= '<nav class="cuisine_navigation chef_navigation"><ul>';

				for( $i = 1; $i <= $pages; $i++ ){

					$html .= '<li class="nav_page">';

						if( $i == $current ){

							$html .= '<span>'.$current.'</span>';

						}else{

							$html .= '<a href="'.cuisine_navigation_base_link().'/page/'.$i.'">'.$i.'</a>';

						}

					$html .= '</li>';
				}

			$html .= '</ul></nav>';

			if( $echo )
				echo $html;

			return $html;

		//we don't have pages:
		}else{
			
			return false;
		}
	}


	/**
	 * Generate the base-link for a pagination block.
	 *
	 * @access public
	 * @return String (baselink)
	 */
	function cuisine_navigation_base_link(){

		$pageURL = 'http';
		
		if ( isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}

		$page = explode('/page', $pageURL );
		$pageURL = $page[0];
	
		if( substr( $pageURL, -1 ) == '/' )
			$pageURL = substr( $pageURL, 0, -1 ); //remove trailing slash

	 	return $pageURL;

	}



