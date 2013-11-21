<?php

	/**
	*	RETURN THE HTML FOR THE CUISINE OPTION PAGE
	*
 	* @access public
	* @return html
	*/

	function cuisine_show_options_page(){
			
		global $cuisine;

		$s = get_cuisine_setting( 'simple_view' );
		if( $cuisine->production_mode ){
			$prod_class = 'productionmode';
		}else{
			$prod_class = 'developmentmode';
		}

?>

<div class="wrap">
	<form action="<?php echo admin_url()?>admin.php?page=cuisine_options" method="POST">
	<?php
	
		global $cuisine;
		$cuisine->plugins->get_plugin_nonce();
		

?><h2><?php _e('Cuisine Options', 'cuisine');?></h2>
			
		<?php if( current_user_can( 'toggle_production_mode' ) ):?>
		<a class="cuisine_form_section cuisine_production_section <?php echo $prod_class;?>" href="<?php echo admin_url();?>admin.php?page=cuisine_options&toggle_production_mode=true">
			<h3><?php _e( 'Website Status', 'cuisine' );?></h3>
			<?php 

			if( $cuisine->production_mode ){
				echo '<p>'.__('website is functioning normally in', 'cuisine').' <strong>'.__('PRODUCTION MODE', 'cuisine').'</strong></p>';
				echo '<p class="warning_txt">'.__('Click this bar to toggle it back to development mode.', 'cuisine').'</p>';
			}else{
				echo '<p>'.__('website might be a bit unstable because it\'s in', 'cuisine').' <strong>'.__('DEVELOPMENT MODE').'</strong></p>';
				echo '<p class="warning_txt">'.__('Click this bar to toggle it to production mode.', 'cuisine').'</p>';
			}
			?>
		</a>
		<?php endif;?>

		<div class="cuisine_form_section">
			<h3><?php _e('First admin page', 'cuisine');?></h3>
		
			<label>
				<input type="checkbox" name="cuisine_show_pages" <?php checked( $s['show_pages_front'], true );?>/> <?php _e( 'Show all pages directly', 'cuisine' );?></label>

			<label><?php _e('Icon size', 'cuisine');?>
					<select name="cuisine_icon_size">
						<option value="50" <?php selected($s['icon_size'], '50');?>><?php _e('Small', 'cuisine');?></option>
						<option value="120" <?php selected($s['icon_size'], '120');?>><?php _e('Medium', 'cuisine');?></option>
						<option value="190" <?php selected($s['icon_size'], '190');?>><?php _e('Large', 'cuisine');?></option>
						<option value="250" <?php selected($s['icon_size'], '250');?>><?php _e('Extra large', 'cuisine');?></option>
					</select>
			</label>

			<label>
				<input type="checkbox" name="cuisine_edit_menus" <?php checked( $s['edit_menus'], true );?>/> <?php _e( 'Users can edit menu\'s', 'cuisine' );?></label>
		</div>

		<div class="cuisine_form_section">
			<h3><?php _e('Overview page', 'cuisine');?></h3>

			<label>
				<input type="checkbox" name="cuisine_add_pages" <?php checked( $s['add_pages'], true );?>/> <?php _e( 'Users can add new pages', 'cuisine' );?></label>

			<label>
				<input type="checkbox" name="cuisine_delete_pages" <?php checked( $s['delete_pages'], true );?>/> <?php _e( 'Users can delete pages', 'cuisine' );?></label>

			<label>
				<input type="checkbox" name="cuisine_back_button" <?php checked( $s['back_button'], true );?>/> <?php _e( 'Show \'Back button\'', 'cuisine' );?></label>
		</div>
		<div class="cuisine_form_section">
			<input type="submit" value="<?php _e( 'Submit','cuisine' );?>" class="primary">
		</div>
	</form>
</div>

<?php } 


	/**
	*	RETURN THE HTML WHEN THE OPTIONS FORM IS SAVED:
	*
 	* @access public
	* @return void
	*/

	function cuisine_options_saved(){
		?>
	<div class="wrap">
		<h2><?php _e('Form saved', 'chef_forms');?></h2>
		<div class="mainpart">
			<div class="form_saved">
				<?php _e('Your form has been saved.', 'cuisine');?>
				<br/><br/>
				<a href="<?php echo admin_url();?>admin.php?page=cuisine_options"><?php _e('Continue editting', 'cuisine')?></a>
			</div>
		</div>
	</div>
		<?php 
	}

?>