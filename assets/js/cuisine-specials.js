/**
 * Post Extras Cuisine JS Class
 *
 * Enabels all shortcode-input functions.
 *
 * @since Cuisine 1.2.1
 */

    var PostExtras = new function(){

        /**
        *   Sets all the event listeners + starts the default Post Extras:
        */
    	this.init = function(){

    		jQuery('#TB_ajaxContent').css({height: '100%'});

            //tab clicking:
    		jQuery('.cuisine_special_tab').click(function(){

    			//toggle active:
    			jQuery( '.cuisine_special_tab' ).removeClass('active');
    			jQuery( this ).addClass('active');

    			//toggle div:
    			var id = jQuery( this ).data('id');
    			jQuery( '.post-extra' ).removeClass('active');
    			jQuery( '#pe_'+id ).addClass('active');

    			//get the default value (if set)
    			PostExtras.setOutputValue( jQuery( this ).data('default') );
    		});


            //Send the output to the editor, when the insertBtn is clicked:
    		jQuery('#insertBtn').click( function(){
                var ret = jQuery('#post-extra-output').val();
    			PostExtras.setOutputValue( jQuery( '.cuisine_special_tab.active' ).data('default') );                
                var ret = jQuery('#post-extra-output').val();
                window.send_to_editor( ret );
    			

    		});


    		this.Columns.init();
            this.Buttons.init();
    	}


    	this.setOutputValue = function( def ){


    		if( def !== '' && def !== null && def !== undefined ){
    			if( typeof window[def] === "function" ){
    				jQuery('#post-extra-output').val( eval( def+'()' ) );
    			}else{
    				jQuery('#post-extra-output').val('');
    			}
    		}
    	}





    	/**
        *   Handle button creation (default post extra)
        */

        this.Buttons = new function(){

            var label = 'Button';
            var labelString = '';
            var icon = '';
            var iconString = '<span class="cuisine-no-icon"></span>';
            var iconCode = '';

            this.init = function(){


                this.updateLabel();

                jQuery( '#button-label' ).live( 'keyup blur', function(){

                    PostExtras.Buttons.updateLabel();

                });

                jQuery( '#button-icon' ).live( 'change', function(){
                    PostExtras.Buttons.updateLabel();
                })

            }

            this.updateLabel = function(  ){

                label = jQuery('#button-label').val();
                this.updateIcon();

                labelString = iconString + label;
                jQuery('#livepreviewbutton').html( labelString );

            }

            this.updateIcon = function(){

                icon = jQuery('#button-icon').val();

                if( icon === 'none' ){

                    iconString = '<span class="cuisine-no-icon"></span>';
                    iconCode = '';

                }else{

                    iconString = '<i class="'+icon+'"></i>';

                    _icon = icon.replace('icon-', '' );
                    iconCode = '[icon type="'+_icon+'"] ';

                }

            }


            this.output = function(){

                var link = jQuery('#button-link').val();
                var html = '[button link="'+link+'"]';

                if( icon !== 'none')
                    html += iconCode;

                html += label+'[/button]';


                return html;
            }
        }



        /**
        *   Handle column creation (default post extra)
        */
        this.Columns = new function(){


            this.init = function(){



                 jQuery( '.column-block.selectable' ).live( 'click', function(){
        
                     jQuery( this ).removeClass( 'selectable' );
                     jQuery( this ).addClass( 'selected' ); 
        
                 });
        
        
                 jQuery( '#combineColumnsButton' ).click(function( e ){
                     e.preventDefault();
        
                     PostExtras.Columns.create();
                 });

                 jQuery( '#resetColumnsButton' ).click(function( e ){

                    e.preventDefault();
                    PostExtras.Columns.reset();

                 })
        
            }
        
        
            this.calc = function(){
        
                var html = '';
                jQuery('.column-block').each(function(){
        
                    var amount = jQuery(this).data('amount');
                    if( amount != null && amount != undefined ){
        
                        html += '[column span="'+amount+'"] [/column]';
                
                    }else{
        
                        html += '[column span="1"] [/column]';
                    
                    }
        
                });
                return html;
        
            }
        
        
            this.create = function(){
        
                var classes = [];
                classes['1'] = 'single'; 
                classes['2'] = 'two';
                classes['3'] = 'three';
                classes['4'] = 'four';
                classes['5'] = 'five';
                classes['6'] = 'six';
                classes['7'] = 'seven';
                classes['8'] = 'eight';
                classes['9'] = 'nine';
                classes['10'] = 'ten';
                classes['11'] = 'eleven';
                classes['12'] = 'twelve';
        
                var amount = jQuery('.column-block.selected' ).length;
            

                var f = PostExtras.Columns.first();
                var last = f + ( amount- 1 );
        
                    if( amount > 1 ){
                        var range = f+' - '+last;
                    }else{
                        range = '1';
                    }

                var old = jQuery('.column-block.combined' ).clone();
                var html = '<div class="column-block combined '+classes[ amount ]+'" data-amount="'+amount+'">'+range+'</div>';
                 
                last++;
                for( last; last <= 12; last++ ){
        
                    html += '<div class="column-block single selectable">'+last+'</div>';
        
                }
        
                jQuery( '.columns-row' ).html('');
                jQuery( '.columns-row' ).append( old );
                jQuery( '.columns-row' ).append( html );
        
        
            }
        
            
            this.output = function(){

                var html = '[row]';

                    html += this.calc();

                html += '[/row]';

                PostExtras.Columns.reset();
                return html;
            }

            this.reset = function(){

                var html = '';

                for( var i = 1; i < 13; i++ ){

                    html += '<div class="column-block selectable single">'+i+'</div>'

                }

                jQuery( '.columns-row' ).html('');
                jQuery( '.columns-row' ).append( html );

                PostExtras.Columns.resetDragEvent();
            }

            this.resetDragEvent = function(){

                jQuery('#the-columns').dragToSelect({
                    selectables: 'div.selectable',
                    onHide: function(){

                        jQuery('.column-block.selected').each(function(){
                            jQuery(this).removeClass('selectable');
                        })
                
                    }
                });

            }


            this.first = function(){
        
                var f = 0;
        
                jQuery('.column-block.combined').each( function(){
        
                    f += parseInt( jQuery( this ).data('amount') );
        
                });
        
        
                f++;
                return f;
            }

        }

    }


  	PostExtras.init();




   /**
    *   Post-Extra default output functions:
    */

    /* Columns: */
    function cuisine_columns_output(){

        return PostExtras.Columns.output();

    }

    /* Buttons: */
    function cuisine_buttons_output(){

        return PostExtras.Buttons.output();

    }



