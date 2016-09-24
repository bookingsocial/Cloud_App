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
				    	object: field.relatedobject,
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