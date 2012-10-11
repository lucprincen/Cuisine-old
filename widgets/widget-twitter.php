<?php

/**
 * Cuisine Twitter widget
 * 
 * displays tweets.
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_twitter extends WP_Widget { 

	function cuisine_widget_twitter() {
		/* Widget settings. */
		$widget_ops = array('description' => __('Display Your Tweets') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_twitter' );

		/* Create the widget. */
		$this->WP_Widget( 'setinstonetwitter', __('Set in Stone - Twitter'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
	
		extract($args);
	
		$title = apply_filters('widget_title', $instance['title']);
		$account = $instance['account'];
		$show = $instance['show'];
		$directory = get_bloginfo( template_url );
		
        // Output
		echo $before_widget;
		echo '<h3>'.$title.'</h3>';  
		echo '<ul id="twitter_update_list"><li>Oops Twitter isnt working at the moment</li></ul>';
		echo '<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>';
		echo '<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$account.'.json?callback=twitterCallback2&amp;count='.$show.'"></script>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show'] = strip_tags( $new_instance['show'] );
		$instance['account'] = strip_tags( $new_instance['account'] );

		return $instance;
	}

	function form($instance) {
		$defaults = array( 'title' => 'Follow Us on Twitter', 'show' => '4', 'account' => 'anteksiler' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'account' ); ?>">Twitter Account:</label>
			<input id="<?php echo $this->get_field_id( 'account' ); ?>" name="<?php echo $this->get_field_name( 'account' ); ?>" value="<?php echo $instance['account']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show' ); ?>">Number of Tweets:</label>
			<input id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo $instance['show']; ?>" style="width:100%;" />
		</p>
    <?php
	}
}

function cuisine_widget_twitter_init(){
	register_widget('cuisine_widget_twitter');

}

add_action('widgets_init', 'cuisine_widget_twitter_init');
