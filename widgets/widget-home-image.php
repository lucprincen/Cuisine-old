<?php

/**
 * Cuisine Home Image widget
 * 
 * displays an image, adds image-edit functions on the homepage edit page.
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_home_image extends WP_Widget { 


	function cuisine_widget_home_image() {
           
		/* Widget settings. */
		$widget_ops = array( 'description' => __('Afbeelding (ook bewerkbaar vanaf de "home" editpagina') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_home_image' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_home_image', __('Homepage Afbeelding'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);
		$home = get_page_by_path( 'home' );
		$home_images = get_post_meta( $home->ID, 'home_widget_images', true );

		foreach($home_images as $img){

			if($img['image_widget_id'] == $instance['image_widget_id']){
				$title = apply_filters('widget_title', $img['title']);
				$src = cuisine_get_img_src( $img['image_id'], 'tile' );
				$link = $img['link'];
				$link_target = $img['link_target'];

			}

		}


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

		$this->update_home_array( $new_instance );


		return $instance;
	}


	function update_home_array( $instance = array() ){

		$home = get_page_by_path( 'home' );
		$home_images = get_post_meta( $home->ID, 'home_widget_images', true );

		$new_instances = array();
		
		//First clean the array if necessary:
		if(!empty($home_images)){
		foreach( $home_images as $img ){

			if( is_active_widget( false, $wid, 'cuisine_widget_home_image' ) ){
				$new_instances[] = $img;
			}

		}
		}

		//then the home_images are the new_instances:
		$home_images = $new_instances;

		if( !empty( $instance ) ){
			$not_in_array = false;
	
			//check if this widget is already in the array:
			$i = 0;
			if(!empty($home_images)){
			foreach($home_images as $img){
				if( $img['image_widget_id'] == $instance['image_widget_id'] ){
					$not_in_array = true;
					//we're editting this widget:
					$home_images[$i]['title'] = $instance['title'];
					$home_images[$i]['url'] = $instance['url'];
					$home_images[$i]['link'] = $instance['link'];
					$home_images[$i]['image_id'] = $instance['image_id'];
	
					break;
				}
				$i++;
			}
			}
	
			//if the widget isn't present in the array:
			if($not_in_array == false){
				$home_images[] = $instance;
			}
		}

		update_post_meta( $home->ID, 'home_widget_images', $home_images );
	}
	
	function form($instance) {

		$home = get_page_by_path( 'home' );
		$home_images = get_post_meta( $home->ID, 'home_widget_images', true );
		$defaults = array( 'title' => __('Afbeelding'), 'url' => '#', 'link' => '#', 'link_target' => '_blank', 'image_id' => '0', 'image_widget_id' => $this->id);
		$instance = wp_parse_args( (array) $instance, $defaults );


		//check if there have been alterations on the home edit screen:
		if(!empty($home_images)){
		foreach($home_images as $img){
			if($img['image_widget_id'] == $instance['image_widget_id']){
				if($img['title'] != $instance['title'] || $img['url'] != $instance['url'] || $img['link'] != $instance['link'] || $img['link_target'] != $instance['link_target']){
					$instance['title'] = $img['title'];
					$instance['url'] = $img['url'];
					$instance['link'] = $img['link'];
					$instance['image_id'] = $img['image_id'];
				}
			}
		}
		}


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
function cuisine_widget_home_image_init(){

	register_widget('cuisine_widget_home_image');
}

add_action( 'widgets_init', 'cuisine_widget_home_image_init' );



/**
*	Add the metabox on the 'home' editscreen:
*/

function cuisine_widget_home_image_meta(){

	if( is_active_widget( false, false, 'cuisine_widget_home_image' ) ){

		global $cuisine;

		$meta = array(
			'id'			=> 'cuisine_widget_home_image_meta',
			'title'			=> __('Homepage afbeeldingen'),
			'post'			=> 'home',
			'context'		=> 'normal',
			'data'			=> array(
				'key'		=>	'home_widget_images',
				'value'		=> 	'home_image_widget_array'
			)				
		);

		$cuisine->plugins->add_plugin_meta( $meta );


	}
}

/**
*	Add the html for the metabox:
*/

function cuisine_widget_home_image_meta_html(){

	$pid = cuisine_get_post_id();
	$home_images = get_post_meta( $pid, 'home_widget_images', true );
	$i = 0;

	//add the nonce:
	cuisine_get_nonce();

	foreach($home_images as $img):

		$wid = $img['image_widget_id'];
		$arrpos = 'home_image_widget_array['.$i.']';

?>

	<div class="cuisine_home_image_widget">
		<label class="cuisine_label"><p>Titel</p><input type="text" value="<?php echo $img['title']?>" name="<?php echo $arrpos;?>[title]"/></label>
		
		<p class="cuisine_widget_centralized">
		 	<div id="imagecontainer-<?php echo $wid;?>" class="cuisine_widget_centralized">
	 			<?php if($img['url'] != '#'):?>
	 				<img src="<?php echo $img['url']?>" id="image-<?php echo $wid;?>" class="cuisine_widget_image"/>
	 			<?php endif;?>
			 </div>
		</p>
		<p class="cuisine_widget_centralized">
			<a href="#" class="button-primary cuisine-widget-media" data-widget="<?php echo $wid;?>" style="text-decoration:none"><?php echo __('Afbeelding kiezen');?></a>
		</p>

		<label class="cuisine_label"><p>Link</p><input type="text" value="<?php echo $img['link']?>" name="<?php echo $arrpos;?>[link]"/></label>

		<input type="hidden" name="<?php echo $arrpos;?>[link_target]" value="<?php echo $img['link_target']?>"/>
		<input type="hidden" id="imageurl-<?php echo $wid;?>" name="<?php echo $arrpos;?>[url]" value="<?php echo $img['url'];?>" />
   		<input type="hidden" id="imageid-<?php echo $wid;?>" name="<?php echo $arrpos;?>[image_id]" value="<?php echo $img['image_id']?>" />
 		<input type="hidden" name="<?php echo $arrpos?>[image_widget_id]" value="<?php echo $wid?>" />


	</div>
<?php
	$i++;
	endforeach;
	echo '<div class="clearfix"></div>';

}
add_action( 'admin_init', 'cuisine_widget_home_image_meta' );
?>