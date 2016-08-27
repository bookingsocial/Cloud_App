// ------------------------------------------------------------- 
// Filename:		registerForm.js
// Description:		Testing with Handling Appointment Page  
// -------------------------------------------------------------
 
(function(){
	function registerForm(config){
		
		var template = $('#sample_template').html();
		var output = Mustache.to_html(template, config);
		$('#register').html(output);
				
		
	}
	
	window.registerForm = registerForm;
})();
 

// end of file.