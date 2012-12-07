<?php
/**
 * Cuisine Admin
 * 
 * Handles the simple view UI
 *
 * @author 		Chef du Web
 * @category 	Admin
 * @package 	Cuisine
 */

	/**
	* Get the simple view UI:
	*/
	function cuisine_simple_ui_init(){
		
		// first get the cuisine settings:
		$settings = get_cuisine_setting( 'simple_view' );?>

		<div class="cuisine-simple-view-wrapper">

<?php
		// if cuisine is setup to show the pages first:
		if( $settings['show_pages_front'] == true ){
			
			//get the pages from the database:
			$pages = get_pages(array('sort_order' => 'ASC','sort_column' => 'menu_order'));

				//check if $pages isn't empty:
				if( !empty( $pages ) ){ ?>

					<div class="cuisine-collection">
						
						<h2 class="cuisine-collection-title"><?php echo __('Which page would you like to edit?', 'cuisine');?></h2>

						<?php foreach( $pages as $page ):?> 
							
							<a class="cuisine-item" href="<?php echo get_admin_url();?>post.php?post=<?php echo $page->ID;?>&action=edit&post_type=page" style="width:<?php echo $settings['icon_size']?>px !important">
								<div class="page-icon">
									<img src="<?php cuisine_simple_view_icon( $page );?>"/>
								</div>
								<strong><?php echo get_the_title( $page->ID );?></strong>
							</a>

						<?php endforeach;?>
		
					</div>
<?php			

				} // pages empty check
		}// pages on front check



		//then add the plugins:
		global $cuisine;
		$plugins = array();

		if($settings['show_pages_front'] == false){
			$plugins[] = array(
				'Title' => __('Pages', 'cuisine'),
				'post_type' => 'page',
				'icon'	=> $cuisine->asset_url.'/images/pages-icon.png'
			);			
		}	

		$plugins = apply_filters( 'register_cuisine_simple_view_plugin', $plugins );

			//check if there are any plugins:
			if( !empty( $plugins ) ){ ?>

				<div class="cuisine-collection">

					<h2 class="cuisine-collection-title"><?php echo __('What would you like to edit?', 'cuisine');?></h2>

					<?php foreach( $plugins as $plugin ):?> 
							
						<a class="cuisine-item" href="<?php cuisine_get_icon_link( $plugin );?>"  style="width:<?php echo $settings['icon_size']?>px !important">
							<div class="page-icon">
								<img src="<?php cuisine_simple_view_icon( $plugin );?>"/>
							</div>
							<strong><?php echo $plugin['Title']?></strong>
						</a>

					<?php endforeach;?>


				</div>

<?php 		} //plugins empty check ?>

		</div><!-- simple-view-wrapper -->
<?php

	}


	/**
	*	Get Icon link:
	*/
	
	function cuisine_get_icon_link( $plugin ){

		if( isset( $plugin['post_type'] ) ){
			echo get_admin_url().'edit.php?post_type='.$plugin['post_type'];

		}else if( isset( $plugin['link'] ) ){
			echo get_admin_url().$plugin['link'];

		}
	}


	/**
	* 	Get the simple view Button:
	*/

	function cuisine_simple_view_button(){

		//if simple view is active:
		if( cuisine_simple_view_is_active() ){

			echo '<a class="cuisine_toggle_button" href="'.admin_url().'?toggle-simple-view=true">'.__('Advanced view', 'cuisine').'</a>';

		}
			
	}


	/**
	*	Add the Simple view back button:
	*/

	function cuisine_simple_view_back_button(){

		global $pagenow,$cuisine;
		if( $pagenow != 'index.php' ){

			echo '<a class="cuisine_back_button" href="'.cuisine_simple_view_back_url().'"><img src="'.$cuisine->asset_url.'/images/arrow-back.png"/><span>'.__('Back', 'cuisine').'</span></a>';

		}
	}


	/**
	*	Generate the back url:
	*/

	function cuisine_simple_view_back_url(){

		global $pagenow, $cuisine;

		if( $pagenow == 'post-new.php'){

			if( isset( $_GET['post_type'] ) )
				return admin_url().'/edit.php?post_type='.$_GET['post_type'];

			return admin_url().'/edit.php?post_type=post';

		}else if( $pagenow == 'post.php'){

			$pid = cuisine_get_post_id();
			$pt = get_post_type( $pid );
			
			return admin_url().'/edit.php?post_type='.$pt;

		}else{

			return admin_url();

		}

	}


	/**
	*	Get icons for specific blocks:
	*/

	function cuisine_simple_view_icon( $obj ){

		//check if it's a page:
		if( isset( $obj->post_type ) && $obj->post_type == 'page' ){

			global $cuisine;
			//now we have to delve deeper:
			if( $obj->post_name == 'home' ){
				 echo $cuisine->asset_url.'/images/home-icon.png';
			
			}else if( $obj->post_name == 'contact' ){
				echo $cuisine->asset_url.'/images/contact-icon.png';

			}else{
				echo $cuisine->asset_url.'/images/page-icon.png';

			}


		}else{

			//get the icon:
			if( isset($obj['icon'] ) ){
				echo $obj['icon'];
			}
		}
	}



	/**
	*	Output the UI for the overview page:
	*/
	function cuisine_simple_ui_overview(){
		
		$settings = get_cuisine_setting( 'simple_view' );
		
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'page' ){
			echo '<style>';
	
				if($settings['add_pages']){
					echo '.add-new-h2{display:inline !important;}';
				}else{
					echo '.add-new-h2{display:none !important;}';
				}
	
				if($settings['delete_pages']){
					echo '.trash{display:inline !important;}';
				}else{
					echo '.trash{display:none !important;}';
					echo '.tablenav.top{display:none !important;}';
					echo 'input[type="checkbox"]{display:none}';
				}
	
			echo '</style>';
		}
	}


?>
