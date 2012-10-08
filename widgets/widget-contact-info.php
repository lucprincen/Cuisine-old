<?php

/**
 * Cuisine Home Text widget
 * 
 * displays a text message, adds editors on the homepage edit page.
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_contact_info extends WP_Widget { 
	
	var $instance;

	function cuisine_widget_contact_info() {

	// abort silently for the second instance of the widget
	if ( ! $this->instance ) {
		$this->instance = true;
    } else {
    	return;
    }


		/* Widget settings. */
		$widget_ops = array( 'description' => __('Text of HTML (ook bewerkbaar vanaf de "contact" editpagina') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_contact_info' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_contact_info', __('Contact info'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);

		$contact = get_page_by_path( 'contact' );
		$contact_info = get_post_meta( $contact->ID, 'contact_info_widget', true );
		if( empty( $contact_info ) ){
			$contact_info['contact_info_title'] = __('Titel');
			$contact_info['contact_info_body'] = '';
		}

		echo $before_widget;
		echo '<h2 class="widgettitle">'.$contact_info['contact_info_title'].'</h2>';
		echo '<div class="textwidget">'.apply_filters( 'the_content', $contact_info['contact_info_body'] ).'</div>';
		echo $after_widget;
	}	
	
	
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 

		$contact = get_page_by_path( 'contact' );
		update_post_meta( $contact->ID, 'contact_info_widget', array( 'contact_info_title' => $_POST['contact_info_title'], 'contact_info_body' => $_POST['contact_info_body'] ) );

		return $instance;
	}
	
	function form($instance) {

		$contact = get_page_by_path( 'contact' );
		$contact_info = get_post_meta( $contact->ID, 'contact_info_widget', true );
		if( empty( $contact_info ) ){
			$contact_info['contact_info_title'] = __('Titel');
			$contact_info['contact_info_body'] = '';
		}

	 ?>
	 <p>
	 	<label for="contact_info_title">Titel</label>
	 	<input id="contact_info_title" name="contact_info_title" value="<?php echo $contact_info['contact_info_title']; ?>" style="width:100%;" />
	 </p>
	 <p>
	 	<label for="contact_info_body">Body</label>
	 	<textarea id="contact_info_body" name="contact_info_body" style="width:100%;height:80px;"><?php echo $contact_info['contact_info_body'];?></textarea>
	 </p>
    <?php
	}
}

/**
*	Register the widget:
*/
function cuisine_widget_contact_info_init(){

	register_widget('cuisine_widget_contact_info');
}

add_action( 'widgets_init', 'cuisine_widget_contact_info_init' );


/**
*	Add the metabox on the 'home' editscreen:
*/

function cuisine_widget_contact_info_meta(){

	if( is_active_widget( false, false, 'cuisine_widget_contact_info' ) ){

		global $cuisine;

		$meta = array(
			'id'			=> 'cuisine_widget_contact_info_meta',
			'title'			=> __('Contactinformatie'),
			'post'			=> 'contact',
			'context'		=> 'normal',
			'data'			=> array(
				'key'		=>	'contact_info_widget',
				'value'		=> 	array( 'contact_info_title', 'contact_info_body' )
			)				
		);

		$cuisine->plugins->add_plugin_meta( $meta );
	}
}

/**
*	Add the html for the metabox:
*/

function cuisine_widget_contact_info_meta_html(){

	$pid = cuisine_get_post_id();
	$contact_info = get_post_meta( $pid, 'contact_info_widget', true );

	//add the nonce:
	cuisine_get_nonce();

?>
	<label class="cuisine_label clearfix">Titel: <input type="text" value="<?php echo $contact_info['contact_info_title'];?>" name="contact_info_title" class="cuisine_input" /></label>
	<label class="cuisine_label clearfix">Body:</label>
 	<textarea id="contact_info_body" name="contact_info_body" style="width:100%;height:150px;"><?php echo $contact_info['contact_info_body'];?></textarea>

<?php

}
add_action( 'admin_init', 'cuisine_widget_contact_info_meta' );

?>