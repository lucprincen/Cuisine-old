<?php

/**
 * Cuisine Button widget
 * 
 * displays a button to a link
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_share extends WP_Widget { 
	

	function cuisine_widget_share() {

		/* Widget settings. */
		$widget_ops = array( 'description' => __('Adds sharing capability') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_share' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_share', __('Sharing'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);

		if( is_single() ){
		
		$socialcounts = get_post_meta( get_the_ID(), 'social_counts', true );
		if( empty( $socialcounts['tw'] ) ) $socialcounts['tw'] = 0;
		if( empty( $socialcounts['fb'] ) ) $socialcounts['fb'] = 0;

		$twlink = 'https://www.twitter.com/home?status='.get_the_title().' - '.urlencode( get_permalink() );
		$fblink = 'https://www.facebook.com/share.php?u='.urlencode( get_permalink() );

			echo $before_widget;

			if( isset( $instance['title'] ) && $instance['title'] != '' ) echo '<h2 class="widgettitle">'.$instance['title'].'</h2>';

			?>
			<div class='post-reactions'>
				<?php if( comments_open() ):?>
				<a class="post-counter post-social-counter post-comments" href="#comments">
					<i class="icon-comment"></i>
					<p><?php comments_number( '0', '1', '%' );?></p>
				</a>
				<?php endif;?>
				<a class="post-counter post-social-counter post-tweets" data-href="<?php echo $twlink;?>" target="_blank" data-postid="<?php the_ID();?>" data-count="<?php echo $socialcounts['tw'];?>"> 
					<i class="icon-twitter"></i>
					<p><?php echo $socialcounts['tw'];?></p>
				</a>

				<a class="post-counter post-social-counter post-fb" data-href="<?php echo $fblink;?>" target="_blank" data-postid="<?php the_ID();?>" data-count="<?php echo $socialcounts['fb'];?>">
					<i class="icon-facebook"></i>
					<p><?php echo $socialcounts['fb'];?></p>
				</a>
			</div>
			<?php
			echo $after_widget;
		}
	}	
	
	
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}
	
	function form($instance) {

		$defaults = array(
			'title' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );


	 ?>
	 <p>
	 	<label for="button_link">Title</label>
	 	<input type="text" id="button_link" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
	 </p>
    <?php
	}
}

/**
*	Register the widget:
*/
function cuisine_widget_share_init(){

	register_widget('cuisine_widget_share');
}

add_action( 'widgets_init', 'cuisine_widget_share_init' );


add_action( 'wp_ajax_social_count', 'cuisine_social_count' );
add_action( 'wp_ajax_nopriv_social_count', 'cuisine_social_count' );


	function cuisine_social_count(){

		$pid = $_POST['postid'];
		$type = $_POST['type'];

		$meta = get_post_meta( $pid, 'social_counts', true );
		if( empty( $meta['tw'] ) ) $meta['tw'] = 0;
		if( empty( $meta['fb'] ) ) $meta['fb'] = 0;

		if( $type == 'twitter' ){

			$meta['tw'] = $meta['tw'] + 1;

		}else{

			$meta['fb'] = $meta['fb'] + 1;

		}

		update_post_meta( $pid, 'social_counts', $meta );

		echo 'success';
		die();
	}

?>