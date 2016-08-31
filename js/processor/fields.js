// ------------------------------------------------------------- 
// Filename:		processor/fields.js
// Description:		Creates the text type fields for dynamic forms
// -------------------------------------------------------------

(function(){
	function processor_fields(config, fields, layout){

		for(var lay in layout){
			var layer = layout[lay];
			
			for(var pi in fields){
				var field = fields[pi];
				
				for(var layfield in layer.fields){
					if(field.fieldname == layer.fields[layfield]){
						if(field.type == 'text'){
							comp_fields_text_init(config, field);
						}
						if(field.type == 'picklist'){  
							comp_fields_picklist_init(config, field);
						}
						if(field.type == 'textView'){ 
							comp_fields_textView_init(config, field);
						} 
						if(field.type == 'DateTime'){    
							comp_fields_DateTime_init(config, field);
						}   
						   
						if(field.hasOwnProperty('validations')){
							for(var pf in field.validations){ 
								processor_validation_add(field.validations[pf]);
							}
						}
					}
				}
			}
		}
	}
	window.processor_fields = processor_fields;
	
	function processor_listview(config, fields){
		for(var pi in fields){
			var field = fields[pi]; 
				comp_fields_listview_init(config, field);
			
		}
		debugger;
	}
	window.processor_listview = processor_listview;
/*	
	function processor_picklistfields(config, courses){
		if(field.type == 'text'){
			comp_fields_picklist_init(config, courses);
		}
	}
	window.processor_picklistfields = processor_picklistfields;*/
	
})();

// end of file