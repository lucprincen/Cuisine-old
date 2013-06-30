/**
 * Cuisine's frontend Images class
 *
 *
 * @since Cuisine 1.2
 */

    var CuisineImages = new function(){


        /**
        *   The Load Images function changes all src attributes of the appropriate <img> tags.
        */
        this.loadImages = function( /* options, callback*/ ){
  
            //get classes

            var classes = this.getImgClasses();

            //loop through classes
            for( var i = 0; i < classes.length; i++ ){

                var cl = '.'+classes[i];
                if( jQuery( cl ).length > 0 ){

                    jQuery( cl ).each(function(){
                        //switch data-src to src value
                        jQuery( this ).attr( 'src', jQuery(this).data( 'src' ) );
                    });
                }

            }

        }


        /**
        *   Returns the class(es) of the images that need loading.
        */
        this.getImgClasses = function(){

            var r = [];

            if( jQuery(window).width() >= 300 ){
                r.push( 'mobile-load' );
            }

            if( jQuery(window).width() >= 500 ){
                r.push( 'tablet-load' );
            }

            if( jQuery(window).width() >= 800 ){
                r.push( 'desktop-load' );
            }

           
            //add 'alwaysRender classes'
            r.push( 'always-load' );

            //return the right class:
            return r;
            
        }


    }
