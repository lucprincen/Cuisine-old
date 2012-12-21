<?php

/**
 * Cuisine Image widget
 * 
 * displays an image which can link to a page.
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_image extends WP_Widget { 


	function cuisine_widget_image() {
           
		/* Widget settings. */
		$widget_ops = array( 'description' => __('Afbeelding toevoegen') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_image' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_image', __('Afbeelding'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$src = $instance['url'];
		$link = $instance['link'];
		$link_target = $instance['link_target'];

		$fulllink = '';
		$fulllinkend = '';
		if($link != '' && $link != '#'){
			$fulllink = '<a href="'.$link.'" target="'.$link_target.'">';
			$fulllinkend = '</a>';
		}

		echo $before_widget;
		echo '<div class="image-widget">';
			if($title != '' && $title != ' '){
				echo '<h2 class="widgettitle">'.$fulllink.$title.$fulllinkend.'</h2>';
			}
			echo $fulllink.'<img src="'.$src.'" class="widget-image"/>'.$fulllinkend;
		echo '</div>';
		echo $after_widget;

	}	
	
	
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['url'] = strip_tags( $new_instance['url'] );
		$instance['link'] = strip_tags( $new_instance['link'] );
		$instance['image_id'] = strip_tags( $new_instance['image_id'] ); 
		$instance['image_widget_id'] = strip_tags( $new_instance['image_widget_id'] );

		if( isset( $new_instance['link_target'] ) && $new_instance['link_target'] == 'on'){
			$link_target = '_blank';
		}else{
			$link_target = '_self';
		}

		$instance['link_target'] = strip_tags( $link_target );

		return $instance;
	}


	function form($instance) {

		$defaults = array( 'title' => __('Afbeelding'), 'url' => '#', 'link' => '#', 'link_target' => '_blank', 'image_id' => '0', 'image_widget_id' => $this->id);
		$instance = wp_parse_args( (array) $instance, $defaults );

		if($instance['link_target'] == '_blank'){
			$target_value = '1';
		}else{
			$target_value = '0';
		}

	 ?>
	 <div id="imagecontainer-<?php echo $this->id;?>" class="cuisine_widget_centralized">
	 	<?php if($instance['url'] != '#'):?>
	 		<img src="<?php echo $instance['url']?>" id="image-<?php echo $this->id;?>" class="cuisine_widget_image"/>
	 	<?php endif;?>
	 </div>

	<p class="cuisine_widget_centralized">
		<input type="button" value="Afbeeldingen toevoegen" class="button tagadd cuisine-widget-media" data-media-type="image" data-widget="<?php echo $this->id;?>" data-button="Voeg toe" />
	</p>

	 <p>
	 	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __('Titel')?></label>
	 	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
	 </p>

 	 <p>
	 	<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php echo __('Linkt naar')?></label>
	 	<input type="text" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" style="width:100%;" />
	 </p>

 	 <p>
	 	<label for="<?php echo $this->get_field_id( 'link_target' ); ?>"><?php echo __('Link openen in nieuw venster')?></label>
	 	<input type="checkbox" id="<?php echo $this->get_field_id( 'link_target' ); ?>" name="<?php echo $this->get_field_name( 'link_target' ); ?>" <?php if($target_value == '1') echo 'checked';?> />
	 </p>

	<input type="hidden" id="imageurl-<?php echo $this->id;?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url'];?>" />
   	<input type="hidden" id="imageid-<?php echo $this->id;?>" name="<?php echo $this->get_field_name( 'image_id' );?>" value="<?php echo $instance['image_id']?>" />
 	<input type="hidden" id="<?php echo $this->get_field_id( 'image-widget-id' );?>" name="<?php echo $this->get_field_name( 'image_widget_id' );?>" value="<?php echo $instance['image_widget_id']?>" />
    <?php
	}
}

/**
*	Register the widget:
*/
function cuisine_widget_image_init(){

	register_widget('cuisine_widget_image');
}

add_action( 'widgets_init', 'cuisine_widget_image_init' );
?>