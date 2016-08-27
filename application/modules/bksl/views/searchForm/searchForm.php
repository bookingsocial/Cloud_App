
	<style>
	th {
		width: 80px;
	}
	
	</style>   
	  
	<script>
		_baseURL = '<?php echo  base_url(); ?>';
		var allScripts = [ 	"js/lib/jquery-2.1.4.js", 
							"js/lib/mustache.js",
							"js/lib/LiveValidation.js",
							"js/lib/jquery.dataTables.js",
							"js/lib/dataTables.bootstrap.js"
						];  
		var allCss = [ // "css/global.css", 
						"css/LiveInValid.css",
						"css/dataTables.bootstrap.css",
						"sass/component/_dynamicfields.scss" 
					];
					
			
	</script>  
	
	
	
	
	<script type="text/javascript" src="<?php echo  base_url(); ?>bootstrap.js"></script>
	<?php 
			$idval = $this->uri->segment(2);
			$idvals = strlen($idval); ?>
	  
	<div id="searchResult">
		
	</div>
	
	
<script type="text/javascript" language="javascript" class="init"> 

				
	$(document).ready(function(){ 
		
	var config = { 
	  "root": "root",
	};   
	var searchResult = <?php echo $allsearchResult; ?>;
	var dataListView = <?php echo $jsonDataListView;?>;  
	var searchVal = <?php echo $searchVal;?>;
	
	console.log(dataListView); 
	
	var relatedObjectData = <?php echo $relatedObjectData; ?>;
	console.log(relatedObjectData);
	console.log(searchResult);
	
	if(searchResult != null){
		var tableBuilder = '';
		for(var objName in searchResult){
			 
			
			var fieldsToDisplay = dataListView[objName].fields;
			
			var buildListViewTableHeader ='<tr>';       
			buildListViewTableHeader +='<th class="no-sort">Action</th>'
			for(key in fieldsToDisplay){  
			buildListViewTableHeader +='<th>'+fieldsToDisplay[key].label+'</th>'
			}
			buildListViewTableHeader +='</tr>';

		var buildListViewTableBody ='';
		var splits = '';
		var allDetailsById = searchResult[objName];
		for(var i=0;i< allDetailsById.length;i++){ 
			
			
			var myString = allDetailsById[i].uId;
			splits = myString.substr(0, 3);  
 
			console.log(splits);
			
			
			buildListViewTableBody +='<tr>'; 
			var count;  
			buildListViewTableBody +='<td class="actions" style="vertical-align: initial !important;padding: 0px !important;"><a href="'+allDetailsById[i].uId+'/e" style="text-decoration: none;">&nbsp;&nbsp;Edit</a></td>';
			for(key in fieldsToDisplay){
				if(allDetailsById[i][fieldsToDisplay[key].ApiName] == null){
					   buildListViewTableBody +='<td></td>' 
				}else if(fieldsToDisplay[key].hasOwnProperty('type')){
					if(fieldsToDisplay[key].type === 'lookup'){
						buildListViewTableBody +='<td>'+checkForLookUp(allDetailsById[i][fieldsToDisplay[key].ApiName])+'</td>'
					}else{
						buildListViewTableBody +='<td>'+allDetailsById[i][fieldsToDisplay[key].ApiName]+'</td>'
					}
				}else{
					buildListViewTableBody +='<td>'+allDetailsById[i][fieldsToDisplay[key].ApiName]+'</td>'
				}
				count = key;   
			}    
			buildListViewTableBody += '</tr>';
		}	
					tableBuilder +='<div id="'+objName+'" class="box box-info">';
					tableBuilder +='<div class="box-header">';
					tableBuilder +='<b>'+objName.toUpperCase()+'</b>';
					tableBuilder +='</div>';
					tableBuilder +='<div class="box-body">'; 
					tableBuilder +='<div class="table-responsive" style="overflow-x: hidden ;">';
					tableBuilder +='<table class="table table-striped table-bordered" cellspacing="0" width="100%">';
					tableBuilder +='<thead id="ListViewHeader'+objName+'"> ';
					tableBuilder +=buildListViewTableHeader+'</thead>';
					tableBuilder +='<tbody id="ListViewBody'+objName+'">';
					tableBuilder +=buildListViewTableBody+'</tbody>';
					tableBuilder +='</table>';
					tableBuilder +='</div><!-- /.table-responsive -->';
					tableBuilder +='</div><!-- /.box-body -->';
			    tableBuilder +='<a href="'+splits+'/'+searchVal+'" style="text-decoration: none;">&nbsp;&nbsp;More...</a>';
					tableBuilder +='</div>';
		}
		$("#example_length").val("2"); 
		$('#searchResult').html(tableBuilder);
	}
    function checkForLookUp($key){
	   $result = '';
	   if(relatedObjectData.hasOwnProperty($key)){
		   $result = relatedObjectData[$key];
		}
		return $result;
    }	
/*	$('#example').DataTable(
	{
	    "order": [],
		  
		  "columnDefs": [ {
	      "targets"   : 'no-sort',
	      "orderable" : false,
	    }]
	}); */  
	
 	}); 
	function doconfirm()
	{
	    job=confirm("Are you sure to delete permanently?");
	    if(job!=true)
	    {
	        return false;
	    }
	}
</script>
