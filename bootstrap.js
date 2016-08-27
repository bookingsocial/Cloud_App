
/**
 * This is a small utility method to load the client core library
 */ 
(function() {
    var allScripts = [ 	"js/lib/jquery-2.1.4.js", 
                       	"js/lib/jquery-ui.min.js",
						"js/lib/mustache.js",
						"js/lib/LiveValidation.js",
						"js/lib/plugins/select2/select2.full.min.js",
                       	"js/build/production.js",
						//"js/comp/fields/text.js",
						//"js/processor/fields.js",
						//"js/processor/validation.js",
						"js/lib/jquery.dataTables.js",
						"js/lib/dataTables.bootstrap.js"
    				];
      
    var allCss = [	
                  	"css/jquery-ui.min.css",
					"css/dataTables.bootstrap.css",
					//"css/bootstrap.css",
					//"css/bootstrap.min.css",
					"css/LiveInValid.css",
                  
    			];
    
    // get the load path           
    var scripts = document.getElementsByTagName('script');
    var script = "", match = null, path="";
    for (var i = 0; i < scripts.length; i++) {
        script = scripts[i].src;

        match = script.match(/bootstrap\.js$/);

        if (match) {
            path = script.substring(0, script.length - match[0].length);
            break;
        }
    }
	// end get load path
	
	// load all the scripts
    for(var j = 0; j < allScripts.length; j++){
    	var scriptToLoad = allScripts[j];
    	document.write('<script type="text/javascript" src="' + path + scriptToLoad + '"></script>');
    }
    // end of load scripts
    
    // load all css
    for(j = 0; j < allCss.length; j++){
    	var cssToLoad = allCss[j];
    	document.write("<link rel='stylesheet' type='text/css' href='" + path + cssToLoad +"'>");
    }
    // end of load css
    	
})();

// end of file