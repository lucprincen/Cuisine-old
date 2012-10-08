function doSmartEmpty(id, string){
	if($(id).val() == string){
		$(id).val('');
		$(id).focus();
		$(id).bind('blur', function(){
			if($(id).val() == ''){
				$(id).val(string);
			}
		})
	}
}
