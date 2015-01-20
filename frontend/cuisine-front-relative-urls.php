<?php

    /**
     * Cuisine relative URLs
     *
     * WordPress likes to use absolute URLs on everything - let's clean that up.
     * Inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
     *
     */
    function cuisine_relative_url($input) {
    
        preg_match( '|https?://([^/]+)(/.*)|i', $input, $matches );
 
        if ( !isset($matches[1]) || !isset( $matches[2] ) ) {
            return $input;
        
        } elseif ( ( $matches[1] === $_SERVER['SERVER_NAME'] ) || $matches[1] === $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] ) {
            return wp_make_link_relative($input);
        
        } else {
            return $input;
        
        }
    }


    function enable_cuisine_relative_urls() {
        return !( is_admin() || preg_match( '/sitemap(_index)?\.xml/', $_SERVER['REQUEST_URI'] ) || in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) );
    }


    function cuisine_make_relative_urls(){
        if ( enable_cuisine_relative_urls() ) {

            $tags = array(
                'bloginfo_url',
                'the_permalink',
                'wp_list_pages',
                'wp_list_categories',
                'the_content_more_link',
                'the_tags',
                'get_pagenum_link',
                'get_comment_link',
                'month_link',
                'day_link',
                'year_link',
                'term_link',
                'the_author_posts_link',
                'script_loader_src',
                'style_loader_src'
            );
    
            foreach($tags as $tag) {
                add_filter( $tag, 'cuisine_relative_url' );
            }
    
        }
    }

    cuisine_make_relative_urls();



