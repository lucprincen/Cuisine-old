<?php

/**
 * Cuisine Posts
 * 
 * All the post-related shortcodes;
 *
 * @author 		Chef du Web
 * @category 	Shortcodes
 * @package 	Cuisine
 */

function cuisine_latest_posts($atts, $content = null){

    $html = '';
    if( !isset( $atts['post_type'] ) ) $atts['post_type'] = 'post';
    if( !isset( $atts['amount'] ) ) $atts['amount'] = 6;
    if (!isset( $atts['class'] ) ) $atts['class'] = 'span2 post';
    if (!isset( $atts['meta'] ) ) $atts['meta'] = true;
    if( !isset( $atts['excerpt_length'] ) ) $atts['excerpt_length'] = 200;

    $q = new WP_Query( array( 'post_type' => $atts['post_type'], 'posts_per_page' => $atts['amount'] ) );

    $html = '<div class="row-fluid posts posts-shortcode">';
    if( $q->have_posts() ){

        while( $q->have_posts() ){
            $q->the_post();

            global $post;

            $html .= '<div class="'.$atts['class'].'">';

                $html .= '<h3>'.$post->post_title.'</h3>';

    /*                if( $atts['meta'] ){

                    $html .= '<p class="post-meta">';

                        $html .= '<span class="post-date">'.get_the_date().'</span> | ';
                        $html .= '<span class="post-author">'.get_the_author().'</span>';

                    $html .= '</p>';

                }
*/
                if( has_post_thumbnail() ){

                    $src = get_the_post_thumbnail( get_the_ID(), 'overview', array( 'class' => 'post_image' ) );
                    $html .= '<a href="'.get_permalink().'">';
                    $html .= $src;
                    $html .= '</a>';
                }


                $html .= apply_filters( 'the_content', cuisine_get_excerpt( $post->post_content, $atts['excerpt_length'] ) );

                $html .= '<br/><a href="'.get_permalink().'" class="button read-more-button">'.__('Read more', 'cuisine').'</a>'; 

            $html .= '</div>';
        }

    }

    //add the read more button.
    if( $content != null ){
        $html .= '<div class="row-fluid read-more-row">';
        
            $url = cuisine_site_url();
            if( $atts['post_type'] == 'post' ){
                $url .= '/blog';
            }else{
                $url .= '/'.$atts['post_type'];
            }
        
            $html .= '<a href="'.$url.'" class="button latest-posts-button">'.$content.'</a>';
        
        $html .= '</div>';
    }

    $html .= '</div>';

    return $html;

}

add_shortcode("latest-posts", "cuisine_latest_posts");
?>