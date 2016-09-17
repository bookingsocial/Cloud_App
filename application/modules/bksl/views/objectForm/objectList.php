
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
	  
	<div id="root" class="box box-info">            
		<div class="box-header with-border" style="text-align: -webkit-center;">
			<!-- <h3 class="box-title"><?php echo $title;?></h3>-->
				<a type="button" href="<?php echo  base_url('bksl/'.$idval.'/n'); ?>" class="btn btn-primary">New</a>
					 
		</div><!-- /.box-header -->  
		 
		<div class="box-body">
		  <div class="table-responsive" style="overflow-x: hidden ;">
			
			
			<table id="listTable"  class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead id="ListViewHeader"> 
					
				</thead>

				<tbody id="ListViewBody">
					
				</tbody>
			</table>
		  </div><!-- /.table-responsive -->
		  
		</div><!-- /.box-body -->
	</div>
	
	
<script type="text/javascript" language="javascript" class="init"> 
 
				
	$(document).ready(function(){ 
	
	var config = { 
	  "root": "root",
	};  
	
	var allDetailsById = <?php echo $allDetailsById; ?>;
	var dataListView = <?php echo $jsonDataListView;?>;  
	var fieldsToDisplay = dataListView.fields;
	var relatedObjectData = <?php echo $relatedObjectData; ?>;
	console.log(relatedObjectData);
	
	var buildListViewTableHeader ='<tr>';       
	buildListViewTableHeader +='<th class="no-sort">Action</th>'
	for(key in fieldsToDisplay){  
		buildListViewTableHeader +='<th>'+fieldsToDisplay[key].label+'</th>'
	}
	buildListViewTableHeader +='</tr>';
	$('#ListViewHeader').html(buildListViewTableHeader);
	
	 
	if(allDetailsById != null){
		var buildListViewTableBody ='';
		for(var i=0;i< allDetailsById.length;i++){ 
			
			buildListViewTableBody +='<tr>'; 
			var count;  
			buildListViewTableBody +='<td class="actions" style="vertical-align: initial !important;padding: 0px !important;"><a href="'+allDetailsById[i].uId+'/e" style="text-decoration: none;">&nbsp;&nbsp;Edit</a>&nbsp;|<a href="'+allDetailsById[i].uId+'/d" onClick="return doconfirm();" style="text-decoration: none;">&nbsp;Del&nbsp;</a></td>';
			for(key in fieldsToDisplay){
				if(allDetailsById[i][fieldsToDisplay[key].ApiName] == null){
					   buildListViewTableBody +='<td></td>' ;
				}else if(fieldsToDisplay[key].ApiName == 'Name'){
					 buildListViewTableBody +='<td><a href="'+_baseURL+'bksl/'+allDetailsById[i]['uId']+'">'+allDetailsById[i][fieldsToDisplay[key].ApiName]+'</a></td>' ;
				}else if(fieldsToDisplay[key].hasOwnProperty('type')){
					if(fieldsToDisplay[key].type === 'lookup'){
						buildListViewTableBody +='<td>'+checkForLookUp(allDetailsById[i][fieldsToDisplay[key].ApiName])+'</td>'
					}else if(fieldsToDisplay[key].type === 'boolean'){
						
						if(allDetailsById[i][fieldsToDisplay[key].ApiName] == 1 )
							buildListViewTableBody +='<td><input type="checkbox" checked="checked" disabled="disabled"/></td>'
						else
							buildListViewTableBody +='<td><input type="checkbox" disabled="disabled"/></td>'
					}
					else{
						buildListViewTableBody +='<td>'+allDetailsById[i][fieldsToDisplay[key].ApiName]+'</td>'
					}
				}else{
					buildListViewTableBody +='<td>'+allDetailsById[i][fieldsToDisplay[key].ApiName]+'</td>'
				}
				count = key;   
			} 
			buildListViewTableBody += '</tr>';
		}	
		$('#ListViewBody').html(buildListViewTableBody);
	}
    function checkForLookUp($key){
	   $result = '';
	   if(relatedObjectData.hasOwnProperty($key)){
				$result = relatedObjectData[$key];
			return '<a href="'+_baseURL+'bksl/'+$result.urlId+'">'+$result.value+'</a>';
		}
		return  $result;
    }	
	$('#listTable').DataTable(
	{ 
	    "order": [],
		  
		  "columnDefs": [ {
	      "targets"   : 'no-sort',
	      "orderable" : false, 
	    }]
	}); 
		  
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
-