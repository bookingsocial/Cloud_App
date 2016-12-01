	<style>
		#root .col-xs-12.col-md-6 {
			    padding: 8px 0px;
		}
		
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
		var allCss = [  
						"css/LiveInValid.css",
						"css/dataTables.bootstrap.css",
						"sass/component/_dynamicfields.scss"
					];

	</script> 
	
	<script type="text/javascript" src="<?php echo  base_url(); ?>bootstrap.js"></script> 
	
	<form  class="form-signin" action="<?php echo  base_url('bksl/saveRecord'); ?>" method="POST" name="saveRecord" id="saveRecord">
	
	<div class="box box-info">
		
		<div class="box-header with-border" style="text-align: -webkit-center;">
			<!-- <h3 class="box-title"><?php echo $title;?></h3>-->
				<?php 
					$modeVal =  $this->uri->segment(3);  
					$idval = $this->uri->segment(2);   
					$idvals = strlen($idval);
					
					$ObjId = substr($idval, 0, 3);
					
					if($modeVal == '' && $idvals == 15) { ?>
				
					<a type="button" href="<?php echo  base_url('bksl/'.$ObjId.'/d'); ?>" onClick="return doconfirm();" class="btn btn-primary" >Delete</a>
					<a type="button" href="<?php echo  base_url('bksl/'.$idval.'/e'); ?>" style="margin-left: 10px;" class="btn btn-primary">Edit</a>
				<?php } else { ?>
					<a type="button" href="<?php echo  base_url('bksl/'.$idval); ?>" class="btn btn-primary" >Cancel</a>
					<button type="submit" onclick="submitBtn()" class="btn btn-primary" id="button" style="margin-left: 10px;">Save</button>
				<?php } ?> 
		</div><!-- /.box-header --> 
		  
		<div id="root" class="box-body">
			
			<input type="hidden" name="urlId" value="<?php echo $this->uri->segment(2);?>"/>
			
		</div><!-- /.box-body -->
		<div class="box-footer with-border" style="text-align: -webkit-center;">
			<?php if($modeVal == '' && $idvals == 15) { ?>  
			
					<a type="button" href="<?php echo  base_url('bksl/'.$ObjId.'/d'); ?>" onClick="return doconfirm();" class="btn btn-primary" >Delete</a>
					<a type="button" href="<?php echo  base_url('bksl/'.$idval.'/e'); ?>" style="margin-left: 10px;" class="btn btn-primary">Edit</a>
				
			<?php } else { ?>   
			 
					<a type="button" href="<?php echo  base_url('bksl/'.$idval); ?>" class="btn btn-primary" >Cancel</a>
					<button type="submit" onclick="submitBtn()" class="btn btn-primary" id="button" style="margin-left: 10px;">Save</button>
				 
			<?php } ?>    
		</div>
	</div>
	  
	</form>  
<script> 
	$(document).ready(function(){ 
		var config = { 
		  "root": "root", 
		  "object" : '<?php echo $selObjectName;?>',
		  "orgId" : '<?php echo $userDetails->Organization_Id; ?>'
			};  
 		   
		var fields = <?php echo $jsondataFeild; ?>; 
		var Filters = <?php echo $jsondataFilters; ?>;
						
		processor_fields(config, fields ,Filters['sections']);
			 
	}); 
	  
	function submitBtn(){
		processor_validation_execute();
	}
	function doconfirm() 
	{
	    job=confirm("Are you sure to delete permanently?");
	    if(job!=true)
	    {
	        return false;
	    }
	}
	
	  
	
	
</script>






<!--Related List Sections Start-->
<?php if(isset($relatedObjtitle)){ ?>
<div class="relatedListView">
	

  
	<?php 
			$idval = $this->uri->segment(2); 
			$idvals = strlen($idval); ?>
	      
	<div id="relatedRoot" class="box box-info">    
		 
		<div class="box-header with-border" >
			<h3 class="box-title"><?php echo $relatedObjtitle; ?></h3>
			<a type="button" style="margin-left: 275px;" href="<?php echo  base_url('bksl/'.$idval.'/n'); ?>" class="btn btn-primary">New</a>
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
	  "root": "relatedRoot",
	};  
	
	var allDetailsById = <?php echo $allDetailsById; ?>;
	var dataListView = <?php echo $jsonDataRelListView;?>;  
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
		  
	/*	
	$('#listTable').DataTable(
	{ 
	    "order": [], 
		
		  "columnDefs": [ {
	      "targets"   : 'no-sort',
	      "orderable" : false, 
				"searching": false, 
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

<!--Related List Sections End-->
		
	
</div>
<?php } ?>
