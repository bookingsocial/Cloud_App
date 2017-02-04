

//Text box validation

(function(){
	function header(config){
		var template = document.getElementById('sample_template').innerHTML;
		var output = Mustache.render(template, config);
		document.getElementById('person').innerHTML = output; 
	}
	window.header = header;
})();

// end of file

//Footer used in configuration footer

(function(){
	function footer(config){
		this.config = config;
	}
	
	window.footer = footer;
})();

// end of file
// ------------------------------------------------------------- 
// Filename:		comp/fields/text.js
// Description:		Creates the text type fields for dynamic forms
// -------------------------------------------------------------
     
(function(){ 
	
	var FieldVal;
	var count = 0; 
	
	function comp_fields_text_init(config, field){
		
		 $.get(_baseURL + '/layout/fields/text.mst', function(template) {
			 var rendered;
			 FieldVal = field;
			 if(count === 0){
				 rendered = Mustache.render(template, field);
			 }else{
				 rendered = Mustache.render(template, FieldVal);
			 }
			$('#' + config.root).append(rendered);			  
			 count++;
		}).pipe(function(p){
			return true;
		});
	}
	window.comp_fields_text_init = comp_fields_text_init;
	
	  
	function comp_fields_textView_init(config, field){
			if(field.visibleType == "boolean"){
				field.isBooleanType = true;
				if(field.value == 1 ){
					field.value = true;
				}else{
					field.value = false;
				}
			}else{
				field.isBooleanType = false;
			}
			$.get(_baseURL + '/layout/fields/textView.mst', function(template) {
				 var rendered;
				 FieldVal = field;
				 if(count === 0){
					 rendered = Mustache.render(template, field);
				 }else{
					 rendered = Mustache.render(template, FieldVal);
				 }
				
				$('#' + config.root).append(rendered);
			}).pipe(function(p){
				return true;
			});
	}
	window.comp_fields_textView_init = comp_fields_textView_init;
	
	function comp_fields_picklist_init(config, field){
		$.get(_baseURL + '/layout/fields/picklist.mst', function(template) {
			console.log(field);
			for(var i=0;i< field.pickval.length;i++){
				if(field.pickval[i].val === field.value)
					field.pickval[i].selected = field.value ? "selected" : "";
			}
			
			 var rendered;
			 FieldVal = field;
			 if(count === 0){
				 rendered = Mustache.render(template, field);
			 }else{
				 rendered = Mustache.render(template, FieldVal);
			 }
			
			$('#' + config.root).append(rendered);
		}).pipe(function(p){
			return true;
		});
	}      
	window.comp_fields_picklist_init = comp_fields_picklist_init;
	
	
	function comp_fields_lookup_init(config, field){
			$.get(_baseURL + '/layout/fields/lookup.mst', function(template) {
			var rendered_lookup = Mustache.render(template,field);
			$('#' + config.root).append(rendered_lookup);
			$("#"+field.fieldname).select2({
				  ajax: {
				    url: _baseURL+"bksl/ajaxHandler/objectSearch",
				    dataType: 'json',
				    delay: 250,
				    data: function (params) {
				      return {
				    	term: params.term, // search term
				    	object: field.relatedObject,
				    	orgId : config.orgId,
				    	page: params.page
				      };
				    },
				    processResults: function (data, params) {
				      // parse the results into the format expected by Select2
				      // since we are using custom formatting functions we do not need to
				      // alter the remote JSON data, except to indicate that infinite
				      // scrolling can be used
				      params.page = params.page || 1;
				 
				      return {
				        results: data.items,
				        pagination: {
				          more: (params.page * 20) < data.total_count
				        }
				      };
				    },
				    cache: true
				  },
				  minimumInputLength: 2,
				});
		}).pipe(function(p){
			return true;
		});
	}  
	window.comp_fields_lookup_init = comp_fields_lookup_init;

	function comp_fields_DateTime_init(config, field){
		$.get(_baseURL + '/layout/fields/date.mst', function(template) {
			var rendered_date = Mustache.render(template, field);
			$('#' + config.root).append(rendered_date);
			$('#'+field.fieldname).datepicker();
		}).pipe(function(p){
			return true;
		});  
	}  
	window.comp_fields_DateTime_init = comp_fields_DateTime_init;
	
	function comp_fields_Checkbox_init(config, field){
		if(field.type =="boolean"){
			if(field.value == 1 ){
				field.value = true;
			}else{
				field.value = false;
			}
		}
		$.get(_baseURL + '/layout/fields/checkbox.mst', function(template) {
			var rendered_checkbox = Mustache.render(template, field);
			$('#' + config.root).append(rendered_checkbox);
		}).pipe(function(p){
			return true;
		});
	}  
	window.comp_fields_Checkbox_init = comp_fields_Checkbox_init;
	
	function comp_fields_listviewHeader_init(config, field){
		$('#ListViewHeader').html('');
		$.get(_baseURL + '/layout/fields/listviewHeader.mst', function(template) {
			var rendered_listview = Mustache.render(template, field);
			$('#ListViewHeader').append(rendered_listview);
		}).pipe(function(p){
			return true;
		});
	}
	window.comp_fields_listviewHeader_init = comp_fields_listviewHeader_init;
	function comp_Section_Header_init(config, field){
		 $.get(_baseURL + '/layout/sectionHeader.mst', function(template) {
			var rendered_sectionHeader = Mustache.render(template, field);
			$('#' + config.root).append(rendered_sectionHeader);
		}).pipe(function(p){
			return true;
		});
	}
	window.comp_Section_Header_init = comp_Section_Header_init;
})();

// end of file
// ------------------------------------------------------------- 
// Filename:		processor/fields.js
// Description:		Creates the text type fields for dynamic forms
// -------------------------------------------------------------
(function() {
    function buildFieldArr(fields) {
        var fieldArr = {};
        for (var pi in fields) {
            fieldArr[fields[pi].fieldname] = fields[pi];
        }
        return fieldArr;
    }
    window.buildFieldArr = buildFieldArr;
 
    function processor_fields(config, fields, layout) {
        
        var fieldArr = buildFieldArr(fields);
        $.get(_baseURL + '/layout/objectLayout.mst', function(template) {
            for (var lay in layout) {
               
                var layer = layout[lay];
                var fieldsToRender = [];
                for (var i = 0, l = layer.fields.length; i < l; i++) { 
                    //for(var layfield in layer.fields){
                    //	for(var pi in fields){
                    //		var field = fields[pi];  
                    if (fieldArr.hasOwnProperty(layer.fields[i])) {
                        var field = fieldArr[layer.fields[i]];

                        if (field.type == 'text') {
                            field.type_text = true;
                            //comp_fields_text_init(config, field);
                        } else if (field.type == 'picklist') {
                                      
                            for (var p = 0; p < field.pickval[0].length; p++) {
                                if (field.pickval[p].val === field.value)
                                    field.pickval[p].selected = field.value ? "selected" : "";
                            }
                            field.type_picklist = true;
                            //comp_fields_picklist_init(config, field);
                        } else if (field.type == 'textView') { 
                           if (field.visibleType == "boolean") {
                                field.isBooleanType = true;
                                if (field.value == 1) {
                                    field.value = true;
                                } else {
                                    field.value = false;
                                }
                            } else {
                                field.isBooleanType = false;
                            }
                            field.type_textView = true;
                            //comp_fields_textView_init(config, field);
                        } else if (field.type == 'lookup') {
                            field.type_lookup = true;
                            //comp_fields_lookup_init(config, field);
                        } else if (field.type == 'DateTime') {
                            field.type_DateTime = true;
                            //comp_fields_DateTime_init(config, field);
                        } else if (field.type == 'boolean') {
                           
                            field.type_boolean = true;
                            //comp_fields_Checkbox_init(config,field);
                        }
                        /*if(field.hasOwnProperty('validations')){
                        	for(var pf in field.validations){ 
                        		processor_validation_add(field.validations[pf]);
                        	}
                        }*/
                        fieldsToRender.push(field);
                    }
                    //}
                }

                var rendered = Mustache.render(template, {
                    'fields': fieldsToRender,
                    'title': layer.title
                });

                $('#' + config.root).append(rendered);
                for (var k = 0; k < fieldsToRender.length; k++) {
                    var fieldType = fieldsToRender[k];
			
                    if (fieldType.type_lookup) {
			 var relatedObject = fieldType.relatedObject;
                        $("#" + fieldType.fieldname).select2({
                            ajax: {
                                url: _baseURL + "bksl/ajaxHandler/objectSearch",
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term, // search term
				    	object: relatedObject,
                                        orgId: config.orgId,
                                        page: params.page
                                    };
                                },
                                processResults: function(data, params) {
                                    // parse the results into the format expected by Select2
                                    // since we are using custom formatting functions we do not need to
                                    // alter the remote JSON data, except to indicate that infinite
                                    // scrolling can be used
                                    params.page = params.page || 1;

                                    return {
                                        results: data.items,
                                        pagination: {
                                            more: (params.page * 20) < data.total_count
                                        }
                                    };
                                },
                                cache: true
                            },
                            minimumInputLength: 2,
                        });
                    } else if (fieldType.type_DateTime) {
                        $('#' + fieldType.fieldname).datepicker();
                    }
                }
            }
        })

    }
    window.processor_fields = processor_fields;

    /*function processor_listviewBody(config, fieldVal, fieldLayout){
    	
    	for(var pi in fields){
    		var field = fields[pi]; 
    			comp_fields_listview_init(config, field);
    		
    	}
    }
    window.processor_listviewBody = processor_listviewBody;*/

    function processor_listviewHeader(config, dataListView) {

        for (var pi in dataListView) {
            var field = dataListView[pi];
            comp_fields_listviewHeader_init(config, field);

        }
    }
    window.processor_listviewHeader = processor_listviewHeader;


})();

// end of file
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
