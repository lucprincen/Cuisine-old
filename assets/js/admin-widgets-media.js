(function($){

    $(document).ready(function(){
        
        $('.media-title').before('<h2>Afbeelding uploaden</h2>');
        uploader.bind('UploadComplete', addImportButton);

    });


    function addImportButton() {

        $('.savebutton input[type="submit"]').after("<input type=\"button\" value=\"Toevoegen aan Widget\" class=\"button tagadd\" id=\"add_to_object\" >");
        $('#add_to_object').hide().fadeIn('slow');

        var btn = $('#add_to_object').click(add_to_object);

    }


    function add_to_object() {

    	// construct images array..
    	var object = [];
    	var id = jQuery('.media-item-info').attr('id').substring(11);
        var type = jQuery('#type-of-'+id).val();
    	object.id = id;
        object.url = jQuery('#thumbnail-head-'+id+' .thumbnail').attr('src').replace('-150x150', '');

     	var win = window.dialogArguments || opener || parent || top;
        win.send_media_to_widget(object);
    }
    




})(jQuery);