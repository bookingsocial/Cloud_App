

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
