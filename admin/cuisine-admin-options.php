<?php


function cuisine_show_options_page(){

	$s = get_cuisine_setting( 'simple_view' );

?>

<div class="wrap">
	<form action="<?php echo admin_url()?>admin.php?page=cuisine_options" method="POST">
	<?php
	
		global $cuisine;
		$cuisine->plugins->get_plugin_nonce();
		

	?>	
	<h2><?php _e('Cuisine Options', 'cuisine');?></h2>
		
		<div class="cuisine_form_section">
			<h3><?php _e('First admin page', 'cuisine');?></h3>
		
			<label>
				<input type="checkbox" name="cuisine_show_pages" <?php if( $s['show_pages_front'] == true ) echo 'checked' ;?>/> <?php _e( 'Show all pages directly', 'cuisine' );?></label>

			<label><?php _e('Icon size', 'cuisine');?>
					<select name="cuisine_icon_size">
						<option value="50" <?php if($s['icon_size'] == '50') echo 'selected';?>><?php _e('Small', 'cuisine');?></option>
						<option value="120" <?php if($s['icon_size'] == '120') echo 'selected';?>><?php _e('Medium', 'cuisine');?></option>
						<option value="190" <?php if($s['icon_size'] == '190') echo 'selected';?>><?php _e('Large', 'cuisine');?></option>
						<option value="250" <?php if($s['icon_size'] == '250') echo 'selected';?>><?php _e('Extra large', 'cuisine');?></option>
					</select>
			</label>

			<label>
				<input type="checkbox" name="cuisine_edit_menus" <?php if( $s['edit_menus'] == true ) echo 'checked';?>/> <?php _e( 'Users can edit menu\'s', 'cuisine' );?></label>
		</div>

		<div class="cuisine_form_section">
			<h3><?php _e('Overview page', 'cuisine');?></h3>

			<label>
				<input type="checkbox" name="cuisine_add_pages" <?php if( $s['add_pages'] == true ) echo 'checked' ;?>/> <?php _e( 'Users can add new pages', 'cuisine' );?></label>

			<label>
				<input type="checkbox" name="cuisine_delete_pages" <?php if( $s['delete_pages'] == true ) echo 'checked' ;?>/> <?php _e( 'Users can delete pages', 'cuisine' );?></label>

			<label>
				<input type="checkbox" name="cuisine_back_button" <?php if( $s['back_button'] == true ) echo 'checked' ;?>/> <?php _e( 'Show \'Back button\'', 'cuisine' );?></label>
		</div>
		<div class="cuisine_form_section">
			<input type="submit" value="<?php _e( 'Submit','cuisine' );?>" class="primary">
		</div>
	</form>
</div>

<?php } 


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