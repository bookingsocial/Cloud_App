// ------------------------------------------------------------- 
// Filename:		comp/fields/text.js
// Description:		Creates the text type fields for dynamic forms
// -------------------------------------------------------------
  
   
(function(){ 
	function comp_fields_text_init(config, field){
		$.get(_baseURL + '/layout/fields/text.mst', function(template) {
			var rendered = Mustache.render(template, field);
			$('#' + config.root).append(rendered);
		});
	}
	window.comp_fields_text_init = comp_fields_text_init;
	
	function comp_fields_textView_init(config, field){
		$.get(_baseURL + '/layout/fields/textView.mst', function(template) {
			var rendered = Mustache.render(template, field);
			$('#' + config.root).append(rendered);
		}); 
	}
	window.comp_fields_textView_init = comp_fields_textView_init;
	
	function comp_fields_picklist_init(config, field){
		
		$.get(_baseURL + '/layout/fields/picklist.mst', function(template) {
			var rendered_template = Mustache.to_html(template, {"list":field.value});
			$('#').html(rendered_template);
		}); 
	}
	window.comp_fields_picklist_init = comp_fields_picklist_init;
	  
	function comp_fields_DateTime_init(config, field){
		
		$.get(_baseURL + '/layout/fields/date.mst', function(template) {
			var rendered = Mustache.render(template, field);
			$('#' + config.root).append(rendered);
		});
				
	}    
	window.comp_fields_DateTime_init = comp_fields_DateTime_init;
	
	
	function comp_fields_listviewHeader_init(config, field){
		
		$('#ListViewHeader').html('');
		$.get(_baseURL + '/layout/fields/listviewHeader.mst', function(template) {
			var rendered_listview = Mustache.render(template, field);
			$('#ListViewHeader').append(rendered_listview);
		});
	}
	window.comp_fields_listviewHeader_init = comp_fields_listviewHeader_init;
	
	
})();

// end of file