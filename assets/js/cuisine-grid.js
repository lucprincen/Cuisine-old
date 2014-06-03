/**
 * Cuisine's frontend Grid class
 *
 *
 * @since Cuisine 1.3.1
 */

    var CuisineGrid = new function(){


    	this.equalize = function( object, container ){

    		if( container == null || container === undefined ){
    			this.equalizeOnce( object );
    	
    		}else{
  			 
  				jQuery( container ).each(function(){  
  			         
  			         var highestBox = 0;
  			         jQuery( object, this ).each(function(){
  			         
  			             if( jQuery(this).height() > highestBox ) 
  			                highestBox = jQuery(this).height(); 
  			         });  
  			         
  			         jQuery( object,this ).height( highestBox );
  			         
  			 	});    
       		}
    	}


    	this.equalizeOnce = function( object ){

    		var highestBox = 0;

			object.each( function(){ 
		
				if( ( jQuery(this).height() ) > highestBox ){
					highestBox = jQuery(this).height();
				}
		
			});
		
			object.height( highestBox );

    	}


    }