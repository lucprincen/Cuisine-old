<?php

/**
 * Cuisine Home Video widget
 * 
 * displays a youtube or vimeo video
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_video extends WP_Widget { 
	
	var $instance;

	function cuisine_widget_video() {

	// abort silently for the second instance of the widget
	if ( ! $this->instance ) {
		$this->instance = true;
    } else {
    	return;
    }

		/* Widget settings. */
		$widget_ops = array( 'description' => __('Youtube of Vimeo video') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_video' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_video', __('Video'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);

		echo $before_widget;
		echo '<h2 class="widgettitle">'.$instance['video_title'].'</h2>';
		echo '<div class="textwidget">'.$this->embedcode($instance).'</div>';
		echo $after_widget;
	}	

	function embedcode( $obj ){

		$html = '';
		if( $this->is_vimeo( $obj['video_url'] ) ){
			//it's a vimeo movie:
			$html = '<iframe src="http://player.vimeo.com/video/'.$obj['video_id'].'" width="'.$obj['video_width'].'" height="'.$obj['video_height'].'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';


		}else{
			//it's a youtube movie:
			$html = '<iframe width="'.$obj['video_width'].'" height="'.$obj['video_height'].'" src="http://www.youtube.com/embed/'.$obj['video_id'].'" frameborder="0" allowfullscreen></iframe>';

		}

		echo $html;
	}


	function is_vimeo( $string ){
		if( substr( $string, 0, 12 ) == 'http://vimeo' || substr( $string, 0, 13 ) == 'https://vimeo' || substr( $string, 0, 16 ) == 'http://www.vimeo' || substr( $string, 0, 17 ) == 'https://www.vimeo' ) return true;
		return false;
	}
	
	function get_id( $value ){
		if( $this->is_vimeo( $value ) ){
			$v = explode('vimeo.com/', $value);
			return $v[1];
		}else{
			$v = explode('?v=', $value);
			$v = explode('&', $v[1]);
			return $v[0];
		}
	}


	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 

		$instance['video_title'] = strip_tags( $new_instance['video_title'] );
		$instance['video_url'] = strip_tags( $new_instance['video_url'] );
		$instance['video_id'] = strip_tags( $this->get_id( $new_instance['video_url'] ) );
		$instance['video_width'] = strip_tags( $new_instance['video_width'] ); 
		$instance['video_height'] = strip_tags( $new_instance['video_height'] );

		return $instance;
	}
	
	function form($instance) {

		$defaults = array(
			'video_title' => 'Video',
			'video_url' => '',
			'video_id' => '',
			'video_width' => '560',
			'video_height' => '315'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

	 ?>
	 <p>
	 	<label for="video_title">Titel</label>
	 	<input type="text" id="video_title" name="<?php echo $this->get_field_name( 'video_title' ); ?>" value="<?php echo $instance['video_title']; ?>" style="width:100%;" />
	 </p>
	 <p>
	 	<label for="video_url">Url</label>
	 	<input type="text" id="video_url" name="<?php echo $this->get_field_name( 'video_url' ); ?>" style="width:100%;" value="<?php echo $instance['video_url']; ?>"/>
	 </p>
	 <p>
	 	<label for="video_width">Breedte</label>
	 	<input type="text" name="<?php echo $this->get_field_name( 'video_width' ); ?>" style="width:100%" value="<?php echo $instance['video_width']; ?>"/>
	 </p>
	 <p>
		<label for="video_width">Hoogte</label>
	 	<input type="text" name="<?php echo $this->get_field_name( 'video_height' ); ?>" style="width:100%" value="<?php echo $instance['video_height']; ?>"/>
	 </p>	
    <?php
	}
}

/**
*	Register the widget:
*/
function cuisine_widget_video_init(){

	register_widget('cuisine_widget_video');
}

add_action( 'widgets_init', 'cuisine_widget_video_init' );


?>