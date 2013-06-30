/**
 * Cuisine's validation class
 *
 *
 * @since Cuisine 1.2
 */

    var CuisineValidate = new function(){


        this.email = function( string ){

            var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            return reg.test( string );

        }


        this.empty = function( string ){
                
           if( string == undefined || string == null || string == ''){
                return true;
            }

            return false;

        }


        this.phonenumber = function( string ) {

            var stripped = string.replace(/[\(\)\.\-\ ]/g, '');     
        
            if (isNaN(parseInt(stripped))) {
                return false;
        
            }else if (!(stripped.length == 10)) {
                return 'length';
        
            }
        
            return true;
        }

     }
