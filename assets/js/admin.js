jQuery(document).ready(function(){

  jQuery('#sortable').sortable({
    handle: '.pin',
    placeholder: 'mitem-placeholder',
    stop: function(){

      setPositions();
    }
  });

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


  jQuery('#addvideos').click(function(){
    jQuery('#cuisine-media-video-add').slideToggle();
  });


  jQuery('#addvid').click(function(){

    var val = jQuery('#vidurl').val();
    var amount = jQuery('.mitem').length;
    var pos = parseInt(amount + 1);

    if(val != '' && val != 'Plak hier het webadres van de video' && val.substring(0, 4) == 'http'){

      var video = [];
      if(val.substring(0, 12) == 'http://vimeo' || val.substring(0,16) == 'http://www.vimeo' || val.substring(0, 13) == 'https://vimeo' || val.substring(0,17) == 'https://www.vimeo'){
        var code = val.split('vimeo.com/');
        code = code[1];
        if(code != ''){
          //setup the video object:
          video.id = code;
          video.url = val;
          video.vidthumb = JSvars.asseturl+'/images/vimeo.jpg';
          video.vidtype = 'vimeo';
          video.typeOf = 'video';
        }


      }else if(val.substring(0, 12) == 'http://youtu' || val.substring(0,18) == 'http://www.youtube' || val.substring(0, 13 == 'https://youtu') || val.substring(0,19) == 'https://www.youtube'){
        var code = val.split('v=');

        if(code[1] != null && code[1] != ''){
          code = code[1].split('&');
          code = code[0];

          if(code != ''){

            video.id = code;
            video.url = val;
            video.vidthumb = 'http://img.youtube.com/vi/'+code+'/0.jpg';
            video.vidtype = 'youtube';
            video.typeOf = 'video';

          }
        }else{

          code = val.split('.be/');
          
          if( code[1] != null && code[1] != ''){

            code = code[1];
            video.id = code;
            video.url = val;
            video.vidthumb = 'http://img.youtube.com/vi/'+code+'/0.jpg';
            video.vidtype = 'youtube';
            video.typeOf = 'video';

          }
        }
      }       
      if( video.id != null ){
        jQuery('#cuisine-media-items ul').prepend( createItem( video, pos ) );
        jQuery('#vidurl').val('Plak hier het webadres van de video');
        setPositions();
      }

    }else{
      jQuery('#vidurl').focus();
    }
  });

});


function setPositions(){
  var i = 1;
  jQuery('.mitem').each(function(){
    jQuery(this).find('.mitem-position').val(i);
    i++;
  });
}


//triggers when the 'add' button is clicked. adds images to the metabox:
function send_media_to_metabox( images ) {
  tb_remove();

  var amount = jQuery('.mitem').length;

  for( var i = 0; i < images.length; i++ ){
    var pos = parseInt(amount + i + 1);
    var img = images[i];
    img.typeOf = 'image';
    
    jQuery('#cuisine-media-items ul').append( createItem( img, pos ) );
    
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

        html += '<label>Titel:</label><input type="text" name="'+post_type+'_media['+pos+'][title]" value="" class="mitem-title"/>';
        html += '<label>Bescrhijving:</label><textarea name="'+post_type+'_media['+pos+'][description]" class="mitem-description"></textarea>';
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




var current_widget_id;

function setActiveWidget(instance_id) {
    current_widget_id = instance_id;
}


function send_media_to_widget(object) {
    
   //remove thickbox
   tb_remove();

   var theid = '#image-'+current_widget_id;
   
   //create the post-image <img> if it doesn't exist:
   if(jQuery(theid).length <= 0){
       jQuery('#imagecontainer-'+current_widget_id).append('<img id="image-'+current_widget_id+'" class="cuisine_widget_image">')
   }

   //Change the image url:
   jQuery(theid).attr('src', object.url);

   //Fill in the inputs:
   jQuery('#imageurl-'+current_widget_id).val(object.url);
   jQuery('#imageid-'+current_widget_id).val(object.id);    
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
