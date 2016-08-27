// ------------------------------------------------------------- 
// Filename:		processor/validation.js
// Description:		Creates the text type fields for dynamic forms
// -------------------------------------------------------------

(function(){ 
	this.validation__ = [];
	
	function processor_validation_add(validation){
		this.validation__.push(validation);
	}
	
	function processor_validation_execute(){
		var liveValidation = [];
		for(var pf in this.validation__){
			liveValidation.push(getLiveValidation(this.validation__[pf]));
		}
		return !LiveValidation.massValidate(liveValidation);
	} 
	
	function getLiveValidation(validation){
		var fieldValidation = new LiveValidation(validation.fieldname, {
            wait: 500,
            onlyOnSubmit: true,
            validMessage: ' '
        });
		
		if(validation.fieldname == 'Email__c'){
			//When validation says field is required
			if(validation.required == true){
				if(validation.hasOwnProperty('failureMessage')){
					fieldValidation.add(Validate.Presence , {failureMessage: validation.failureMessage});
					fieldValidation.add(Validate.Email, {validMessage: ' '});
				}else{
					fieldValidation.add( Validate.Presence ); 
				}
			}
		} else if(validation.fieldname == 'Phone__c'){
			//When validation says field is required
			if(validation.required == true){
				if(validation.hasOwnProperty('failureMessage')){
					fieldValidation.add(Validate.Presence , {failureMessage: validation.failureMessage});
					fieldValidation.add( Validate.Numericality );
				}else{
					fieldValidation.add( Validate.Presence );  
				} 
			}
		} else {
			//When validation says field is required
			if(validation.required == true){
				if(validation.hasOwnProperty('failureMessage'))
					fieldValidation.add(Validate.Presence , {failureMessage: validation.failureMessage});
				else
					fieldValidation.add( Validate.Presence ); 
				}
			
		}
		
		return fieldValidation;
	}
	
	window.processor_validation_add = processor_validation_add;
	window.processor_validation_execute = processor_validation_execute;
})();

// end of file