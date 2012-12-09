jQuery(document).ready(function(){
	
	jQuery('#save').click(function(){

		refreshIframe();

	});


	function refreshIframe(){
		jQuery('#customize-preview iframe')[0].contentWindow.location = _wpCustomizeSettings.url.preview;
	}
	
	var _controls = controls_object.replace(/&quot;/g, '"');
	var Controls = jQuery.parseJSON( _controls );


	jQuery.each( Controls, function( i, item ){

		var c = item.control;

		wp.customize( 'cuisine_theme_options['+c.id+']' ,function( value ) {
       		value.bind(function(to) {
       			if( to != '' ){
            		jQuery( c.element ).css( c.property , to );
        		}
        	});
    	});


	});


});