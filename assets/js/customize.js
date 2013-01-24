jQuery(document).ready(function(){
	
	// when clicking the save-button, refresh the iframe:
	jQuery('#save').click(function(){

		jQuery('#customize-preview iframe')[0].contentWindow.location = _wpCustomizeSettings.url.preview;

	});


	//get all controls:	
	var _controls = controls_object.replace(/&quot;/g, '"');
	var Controls = jQuery.parseJSON( _controls );

	//loop through 'em:
	jQuery.each( Controls, function( i, item ){

		var c = item.control;

		//add a wp.customize reference with the proper id:
		wp.customize( 'cuisine_theme_options['+c.id+']' ,function( value ) {

			//bind the value-change function:
       		value.bind(function(to) {
       			if( to != '' ){
       				//change the element's css accordingly:
            		jQuery( c.element ).css( c.property , to );
        		}

        	});
    	}); // end of wp.customize
	}); //end of each
});