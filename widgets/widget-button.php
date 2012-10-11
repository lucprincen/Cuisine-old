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

class cuisine_widget_button extends WP_Widget { 
	

	function cuisine_widget_button() {

		/* Widget settings. */
		$widget_ops = array( 'description' => __('Add a button') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_button' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_button', __('Button'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);

		echo $before_widget;
		echo '<a href="'.$instance['link'].'" class="button">'.$instance['text'].'</a>';
		echo $after_widget;
	}	
	
	
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 

		$instance['link'] = strip_tags( $new_instance['link'] );
		$instance['text'] = strip_tags( $new_instance['text'] );

		return $instance;
	}
	
	function form($instance) {

		$defaults = array(
			'link' => '#',
			'text' => 'button'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );


	 ?>
	 <p>
	 	<label for="button_link">Link</label>
	 	<input type="text" id="button_link" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" style="width:100%;" />
	 </p>
	 <p>
	 	<label for="button_text">Text</label>
	 	<input type="text" id="button_text" name="<?php echo $this->get_field_name( 'text' ); ?>" style="width:100%;" value="<?php echo $instance['text']; ?>"/>
	 </p>
    <?php
	}
}

/**
*	Register the widget:
*/
function cuisine_widget_button_init(){

	register_widget('cuisine_widget_button');
}

add_action( 'widgets_init', 'cuisine_widget_button_init' );


?>