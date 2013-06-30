<?php

	/**
	*	Add the button above the editor:
	*/
	add_action('media_buttons', 'cuisine_form_button', 20);
	function cuisine_form_button(){
		global $pagenow;
       
        $is_post_edit_page = in_array( $pagenow, array('post.php', 'page.php', 'page-new.php', 'post-new.php'));
       
        if(!$is_post_edit_page)
            return;

		// display button matching new UI
		echo '<a href="#TB_inline?TB_inline=true&inlineId=cuisine_post_extras_tickbox" class="thickbox button gform_media_link" title="Speciale onderdelen"><span class="cuisine_media_icon "></span> Opmaak & Extra\'s</a>';
	}


	/**
	*	Add the specials thickbox content.
	*/
	cuisine_init_post_extras();
	function cuisine_init_post_extras(){
		global $pagenow;
        $is_post_edit_page = in_array( $pagenow, array('post.php', 'page.php', 'page-new.php', 'post-new.php'));

        if( $is_post_edit_page ){
			add_thickbox();
			add_action( 'admin_footer',  'cuisine_mce_popup' );
		}


        /* Register the Cuisine Post Extra defaults: */
        cuisine_register_post_extra(  
            'cuisine-columns',              // id
            __('Columns', 'cuisine'),       // label
            'cuisine_post_extras_columns',  // function
            array(),                        // js files needed
            null,                           // priority
            array()                         // arguments
        );

        cuisine_register_post_extra( 'cuisine-buttons', __('Buttons', 'cuisine'), 'cuisine_post_extras_buttons' );


	}
	


	/**
	*	Get the Specials thickbox html:
	*/
    function cuisine_mce_popup(){

       	$tabs = cuisine_get_post_extras_tabs();
        $i = 0;
        ?>
        <div id="cuisine_post_extras_tickbox" style="display:none;">
        	<div class="cuisine_post_extras">
        		<div class="cuisine_post_extras_tabs">
        			<ul class="cuisine_post_extras_tab_list">
        			<?php foreach( $tabs as $tab ):?>
        				<li class="cuisine_special_tab<?php if( $i == 0 ) echo ' active';?>" data-id="<?php echo $tab['id'];?>" data-default="<?php echo $tab['jsfunc'];?>">
        					<?php echo ucwords( $tab['title'] );?>
        				</li>
        			<?php $i++; endforeach;?>
        			</ul>
        		</div>

        		<?php do_action( 'cuisine_post_extras' );?>
        	</div>
            <div class="cuisine_post_extras_output">
                <input type="text" id="post-extra-output" value="">
                <input type="button" id="insertBtn" class="button  button-primary button-large" value="<?php _e( 'Insert', 'cuisine' );?>"/>
            </div>
        </div>
        <?php
    }



    /**
    *	Return the tabs:
    */
    function cuisine_get_post_extras_tabs(){

    	$tabs = array();
    	return apply_filters( 'cuisine_post_extras_tabs', $tabs );

    }

    function cuisine_post_extras_columns(){
        ?>
        <div class="column-top post-extra-row">
            <strong><?php _e( 'With this tool you can easily create columns for your post.', 'cuisine')?></strong>
            <p><?php _e( 'There\'s a default of 12 blocks, which you can devide up the way you want (so you can do two blocks of six, or three blocks of four, for instance)', 'cuisine' );?></p>
            <p><?php _e( 'Just select multiple columns and click "combine"','cuisine' );?></p>
        </div>
        <div class="column-top column-control post-extra-row">
            <a href="#" id="combineColumnsButton" class="button button-primary big-button">Combine!</a>
            <a href="#" id="resetColumnsButton">reset</a>
            <div class="clearfix"></div>
        </div>

        <div class="columns-row">
            <div class="column-block selectable single">1</div>
            <div class="column-block selectable single">2</div>
            <div class="column-block selectable single">3</div>
            <div class="column-block selectable single">4</div>
            <div class="column-block selectable single">5</div>
            <div class="column-block selectable single">6</div>
            <div class="column-block selectable single">7</div>
            <div class="column-block selectable single">8</div>
            <div class="column-block selectable single">9</div>
            <div class="column-block selectable single">10</div>
            <div class="column-block selectable single">11</div>
            <div class="column-block selectable single">12</div>
        </div>

        <?php
    }

    function cuisine_post_extras_buttons(){
        ?>
        <div class="post-extra-row">
            <strong><?php _e('Here you can easily add a button.', 'cuisine' );?></strong>
            <p><?php _e( 'Add a link, button-text and other stuff!', 'cuisine');?></p>

            <label><?php _e( 'Link URL', 'cuisine' );?></label>
            <input type="text" id="button-link"/>


            <label><?php _e( 'Button text', 'cuisine' );?></label>
            <input type="text" id="button-text" value="Button" />

            <hr/>
        </div>

        <!-- 
        <div class="post-extra-row">
            <strong><?php _e( 'Live preview','cuisine' );?></strong>

            <a href="#" class="button" id="livepreviewbutton"> 

        </div>-->
        <?php
    }



?>