<?php


	/**
	*	Generate a fluid bootstrap row based on the current loop:
	*/
	function cuisine_row( $class = '' ){

		global $current_row_item, $current_total_item;

		if( !isset( $current_row_item ) && $current_total_item ){
			$GLOBALS['current_row_item'] = 0;
			$GLOBALS['current_total_item'] = 0;
			global $current_row_item, $current_total_item;
		}

		if( $current_row_item == 0 )
			echo '<div class="row-fluid '.$class.'">';

	}


	/**
	*	Generate a fluid bootstrap row based on the current loop:
	*/
	function cuisine_row_close( $items, $a_query = null ){
		
		$GLOBALS['current_row_item']++;
		$GLOBALS['current_total_item']++;
		global $current_row_item, $current_total_item, $wp_query;
		if( $a_query == null ) $a_query = $wp_query;

		if( $current_row_item == $items || $current_total_item == $a_query->found_posts ){
			$GLOBALS['current_row_item'] = 0;
			echo '</div>';
		}

	}


	
	/**
	*	Cleanup inline styles:
	*/
	add_filter( 'the_content', 'cuisine_no_inline_styles_dangit' );

	function cuisine_no_inline_styles_dangit( $string ){
		return preg_replace( '/(<[^>]+) style=".*?"/i', '$1', $string );
	}




	/**
	 * Clean up wp_head()
	 *
	 * Remove unnecessary <link>'s
	 * Remove inline CSS used by Recent Comments widget
	 * Remove inline CSS used by posts with galleries
	 * Remove self-closing tag and change ''s to "'s on rel_canonical()
	 * @access public
	 * @return void
	 */
	function cuisine_head_cleanup() {
		
		// Originally from http://wpengineer.com/1438/wordpress-header/
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		
		global $wp_widget_factory;
		
		if(isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
		}
	  
		if (!class_exists('WPSEO_Frontend')) {
			remove_action('wp_head', 'rel_canonical');
			add_action('wp_head', 'cuisine_rel_canonical');
		}
	}

	/**
	 * Show a cleaner version of the canonical code
	 * 
	 * @access public
	 * @return String
	 */
	function cuisine_rel_canonical() {

		global $wp_the_query;
		if ( !is_singular() ) {
			return;
		}

		if ( !$id = $wp_the_query->get_queried_object_id() ) {
			return;
		}

		$link = get_permalink( $id );

		echo "\t<link rel=\"canonical\" href=\"$link\">\n";
	}

	add_action('init', 'cuisine_head_cleanup');
	

	/**
	 * Remove the WordPress version from RSS feeds
	 */
	add_filter('the_generator', '__return_false');
	

	/**
	 * Clean up language_attributes() used in <html> tag
	 *
	 * Remove dir="ltr"
	 * @access public
	 * @return String
	 */
	function cuisine_language_attributes() {
		$attributes = array();
		
		if (is_rtl()) {
			$attributes[] = 'dir="rtl"';
		}
	  
	  	$lang = get_bloginfo('language');
	  
		if ($lang) {
	    	$attributes[] = "lang=\"$lang\"";
	  	}

		$output = implode(' ', $attributes);
		$output = apply_filters('cuisine_language_attributes', $output);
	  
	  	return $output;
	}

	add_filter('language_attributes', 'cuisine_language_attributes');
	

	/**
	 * Clean up output of stylesheet <link> tags
	 */
	function cuisine_clean_style_tag($input) {
	  preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
	  // Only display media if it is meaningful
	  $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
	  return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
	}
	add_filter('style_loader_tag', 'cuisine_clean_style_tag');


	/**
	 * Add and remove body_class() classes
	 */
	function cuisine_body_class($classes) {
		// Add post/page slug if not present and template slug
		if (is_single() || is_page() && !is_front_page()) {
			
			if (!in_array(basename(get_permalink()), $classes)) {
				$classes[] = basename(get_permalink());
			}
			
			$classes[] = str_replace('.php', '', basename(get_page_template()));
		}
		
		// Remove unnecessary classes
		$home_id_class = 'page-id-' . get_option('page_on_front');
		$remove_classes = array(
			'page-template-default',
			$home_id_class
		);
	  
	  	$classes = array_diff($classes, $remove_classes);
	  	return $classes;
	}

	add_filter( 'body_class', 'cuisine_body_class' );
	


	/**
	 * Wrap embedded media as suggested by Readability
	 *
	 * @link https://gist.github.com/965956
	 * @link http://www.readability.com/publishers/guidelines#publisher
	 */
	function cuisine_embed_wrap($cache) {
		return '<div class="entry-content-asset">' . $cache . '</div>';
	}
	add_filter( 'embed_oembed_html', 'cuisine_embed_wrap' );
	


	/**
	 * Remove unnecessary self-closing tags
	 */
	function remove_self_closing_tags( $input ) {
		return str_replace( ' />', '>', $input );
	}
	add_filter('get_avatar',          'cuisine_remove_self_closing_tags'); // <img />
	add_filter('comment_id_fields',   'cuisine_remove_self_closing_tags'); // <input />
	add_filter('post_thumbnail_html', 'cuisine_remove_self_closing_tags'); // <img />
	



	/**
	 * Don't return the default description in the RSS feed if it hasn't been changed
	 */
	function cuisine_remove_default_description( $bloginfo ) {
		$default_tagline = 'Just another WordPress site';
		return ($bloginfo === $default_tagline) ? '' : $bloginfo;
	}

	add_filter('get_bloginfo_rss', 'cuisine_remove_default_description');
	


	/**
	 * Fix for empty search queries redirecting to home page
	 *
	 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
	 * @link http://core.trac.wordpress.org/ticket/11330
	 */
	function cuisine_request_filter( $query_vars ) {
		if (isset($_GET['s']) && empty($_GET['s']) && !is_admin()) {
			$query_vars['s'] = ' ';
		}
		return $query_vars;
	}
	add_filter('request', 'cuisine_request_filter');
	
	
	
?>	