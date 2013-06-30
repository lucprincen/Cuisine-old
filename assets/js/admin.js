/**
 * Cuisine Admin / Media
 * 
 * Simple Cuisine Media functions
 *
 * @author      Chef du Web
 * @category    JS
 * @package     Cuisine
 */

    jQuery(document).ready(function(){
    
        resizeCollections();
    

        // Media sort function
        jQuery('#sortable').sortable({
          handle: '.pin',
          placeholder: 'mitem-placeholder',
          stop: function(){
      
            setPositions();
          }
        });
    

        // Media trash function
        jQuery('.trash').click(function(){
            
            if (confirm('Weet je zeker dat je dit wilt verwijderen?')) {
                
                var id = jQuery(this).attr('id').substring(6);
                
                jQuery('#mitem-'+id).fadeOut('slow', function(){
                    
                    jQuery('#mitem-'+id).remove();
      
                    if( jQuery('#sortable li').length == 0 ){
                        
                        var post_type = JSvars.post_type;
                        jQuery('#sortable').append('<input type="hidden" name="'+post_type+'_media" value=" " id="media_data_empty">');
            
                    }else{
                        setPositions();
      
                    }     
                })
            }
        });
      
      
        //adding text to the cuisine media items of this post:
        jQuery('#addtext').click(function(){
      
            var amount = jQuery('.mitem').length;
            var pos = parseInt(amount + 1);
            var txt = [];
            txt.id = 'txt'+pos;
            txt.url = '';
            txt.typeOf = 'text';
            jQuery('#cuisine-media-items ul').prepend( createItem( txt, pos ) );
            setPositions();
      
        });
      
      
        //toggle the video form for media items:
        jQuery('#addvideos').click(function(){
            jQuery('#cuisine-media-video-add').slideToggle();
        });
      
      
        //add a video to cuisine media items:
        jQuery('#addvid').click(function(){
      
          var val = jQuery('#vidurl').val();
          var amount = jQuery('.mitem').length;
          var pos = parseInt(amount + 1);
      
          if(val != '' && val != 'Plak hier het webadres van de video' && val.substring(0, 4) == 'http'){
      
            Cuisine.create_video_object( val, function( video ){
      
                if( video.id != null ){
                   jQuery('#cuisine-media-items ul').prepend( createItem( video, pos ) );
                   jQuery('#vidurl').val('Plak hier het webadres van de video');
                   setPositions();
                }
      
            });
      
          }else{
            
            jQuery('#vidurl').focus();
      
          }
        });
      
      
        /**
        *   Cuisine media-image upload:
        */
        jQuery('.cuisine-add-media').live( 'click', function( event ){
      
            event.preventDefault();
      
            var options = Cuisine.sanitize_uploader_options( jQuery( this ) );
            var image = Cuisine.uploader( options, addImagesToMediaList );
        });
      
          

        /**
        *   Uploading from widgets:
        */
        var widget_id = 0;
        
        jQuery('.cuisine-widget-media').live( 'click', function( event ){
      
            event.preventDefault();
      
            widget_id = jQuery(this).data('widget');
      
      
            var options = Cuisine.sanitize_uploader_options( jQuery( this ) );
            options.multiple = false;
      
            var uploader = Cuisine.uploader( options, function( image ){
                var theid = '#image-'+widget_id;
        
                if( jQuery( theid ).length == 0 ){
      
                    jQuery('#imagecontainer-'+widget_id).append('<img id="image-'+widget_id+'" class="cuisine_widget_image" />')
                
                } 
                
                //Change the image url:
                jQuery(theid).attr('src', image.url);
        
                //Fill in the inputs:
                jQuery('#imageurl-'+widget_id).val(image.url);
                jQuery('#imageid-'+widget_id).val(image.id);    
               
            });
        });


        if( jQuery('.cuisine_error').length > 0 ){

            var s = setTimeout( function(){ jQuery('.cuisine_error').fadeOut('fast');}, 4000 );

        }


    }); // <= end document.ready


    jQuery(window).resize(function(){
        resizeCollections();
    });

    function resizeCollections(){
        jQuery('.cuisine-collection.cuisine-plugins').css('height', jQuery(window).height()+'px')
    }
  
    
    /**
    *   Set the positions of media items:
    */
    function setPositions(){
        var i = 1;

        jQuery('.mitem').each(function(){
            
            jQuery(this).find('.mitem-position').val(i);
            i++;

        });
    }


    /**
    *   Process the uploaded images:
    */
    function addImagesToMediaList( attachments, options ){
                
        if( options.multiple === true ){
      
            for( var i = 0; i < attachments.length; i++ ){
      
                var amount = jQuery('.mitem').length;
                var pos = parseInt( amount + 1 );
      
                var img = [];
                img.id= attachments[i].id;
                img.url = attachments[i].url;
                img.typeOf = 'image';
                img.title = attachments[i].title;
      
                if( attachments[i].caption != '' ){
                  img.description = attachments[i].caption;
                }else{
                  img.description = attachments[i].description;
                }
                    
                jQuery('#cuisine-media-items ul').prepend( createItem( img, pos ) );
      
            }
      
        }else{
      
            var amount = jQuery('.mitem').length;
            var pos = parseInt( amount + 1 );
      
            var img = [];
            img.id = attachments.id;
            img.url = attachments.url;
            img.typeOf = 'image';
            img.title = attachments.title;
      
            if( attachments.caption != '' ){
              img.description = attachments.caption;
            }else{
              img.description = attachments.description;
            }
      
            jQuery('#cuisine-media-items ul').prepend( createItem( img, pos ) );
      
        }
      
    }

    
    /**
    * Create a mediaitem
    */
    function createItem ( item, pos ){
            
        if( jQuery('#media_data_empty').length > 0 ){
          jQuery('#media_data_empty').remove();
        }
    
        var post_type = JSvars.post_type;
    
        var html = '<li class="mitem" id="mitem-'+item.id+'">';
    
        html += '<div class="mitem-container">';
    
          if(item.typeOf == 'image'){
            html += '<img src="'+item.url+'" class="mitem-img"/>';
          }else if(item.typeOf == 'video'){
            html += '<img src="'+item.vidthumb+'" class="mitem-img"/>';
            html += '<input type="hidden" name="'+post_type+'_media['+pos+'][vidthumb]" value="'+item.vidthumb+'"/>';
            html += '<input type="hidden" name="'+post_type+'_media['+pos+'][vidtype]" value="'+item.vidtype+'"/>';
          }
    
          html += '<div class="mitem-body">';
            html += '<input type="hidden" name="'+post_type+'_media['+pos+'][id]" value="'+item.id+'"/>';
            html += '<input type="hidden" name="'+post_type+'_media['+pos+'][url]" value="'+item.url+'"/>';
            html += '<input type="hidden" name="'+post_type+'_media['+pos+'][position]" value="'+pos+'" class="mitem-position"/>';
            html += '<input type="hidden" name="'+post_type+'_media['+pos+'][type]" value="'+item.typeOf+'"/>';
            
            if( item.title === undefined || item.title === null ){
              item.title = '';
            }
  
            html += '<label>Titel:</label><input type="text" name="'+post_type+'_media['+pos+'][title]" value="'+item.title+'" class="mitem-title"/>';
            html += '<label>Bescrhijving:</label><textarea name="'+post_type+'_media['+pos+'][description]" class="mitem-description">';
  
            if( item.description !== undefined ){
  
                html += item.description;
  
            }
  
            html += '</textarea>';
            if(item.typeOf != 'video'){
              html += '<label>Link:</label><input type="text" name="'+post_type+'_media['+pos+'][link]" value="" class="mitem-link"/>';
            }
          html += '</div>';
      
    
          html += '<div class="mitem-controls">';
            html += '<div class="mitem-control pin" id="pin-'+item.id+'"></div>';
            html += '<div class="mitem-control trash" id="trash-'+item.id+'"></div>';
          html += '</div>';
    
    
        html += '<div class="clearfix"></div></div></li>';  
        return html;
    }
    
    
    
    function doSmartEmpty(id, string){
      if(jQuery(id).val() == string){
        jQuery(id).val('');
        jQuery(id).focus();
        jQuery(id).bind('blur', function(){
          if(jQuery(id).val() == ''){
            jQuery(id).val(string);
          }
        })
      }
    }


 


    