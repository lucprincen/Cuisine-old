(function(jQuery){

    jQuery(document).ready(function(){
        
        jQuery('.media-title').before('<h2>Afbeeldingen toevoegen</h2>');
        uploader.bind('UploadComplete', addImportButton);

    });


    function addImportButton() {

        jQuery('.savebutton input[type="submit"]').after("<input type=\"button\" value=\"Toevoegen\" class=\"button tagadd\" id=\"add_to_object\" >");
        jQuery('#add_to_object').hide().fadeIn('slow');

        var btn = jQuery('#add_to_object').click(add_to_object);

    }


    function add_to_object() {
    	// construct images array..
    	var objects = [];
    	var i = 0;
    	jQuery('.media-item').each(function(){
    		var id = jQuery(this).find('.media-item-info').attr('id').substring(11);
        	objects[i] = [];
    		objects[i].id = id;
        	objects[i].url = jQuery('#thumbnail-head-'+id+' .thumbnail').attr('src').replace('-150x150', '');
        	i++;
    	});

    	var win = window.dialogArguments || opener || parent || top;
    	win.send_media_to_metabox(objects);

    }
    

})(jQuery);