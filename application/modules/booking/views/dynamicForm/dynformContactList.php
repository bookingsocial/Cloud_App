
	<style>
	th {
		width: 80px;
	}
	 
	</style>
 
	<script>
		_baseURL = '<?php echo  base_url(); ?>';
		var allScripts = [ 	"js/lib/jquery-2.1.4.js", 
							"js/lib/mustache.js",
							"js/lib/LiveValidation.js"
						];  
		var allCss = [  "css/global.css", 
						"css/LiveInValid.css",
						"sass/component/_dynamicfields.scss" 
					];
					
			
	</script> 
	
	<script type="text/javascript" src="<?php echo  base_url(); ?>bootstrap.js"></script>
	<h3 style="text-align: -webkit-center;">List Of Contacts</h3>
	<div id="root" class="col-md-12">
		<select  style="  padding: 5px; border: 1px Solid rgb(221, 221, 221); color: black;" id="selectContact">
			<option value="All">All Contacts</option>
			<option value="Rec">Recently Viewed Contacts</option>
		</select>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="name">NAME</th>
					<th class="activeStatus">ACCOUNT NAME</th>
					<th class="durTime">PHONE</th>
					<th class="prefExpert">EMAIL</th>
					<th class="actions">ACTIONS</th>
				</tr> 
			</thead>
			<tbody style="display: table-row-group;"  id="serviceTable">
		</table>
	</div>
<script> 

	<?php foreach ($allContactsByOrg as $apps): ?>
	
	
	
	 
	
	$(document).ready(function(){
		var config = {
		  "root": "root",
		};
		
				var allContact = {
					"type": "object",
					"fields": [
					  {
						"name": "<?php echo $apps->Name;?>",
						"account": "<?php echo $apps->Organization_Id;?>",
						"phone": "<?php echo $apps->Mobile;?>",
						"email": "<?php echo $apps->Email;?>",
						"sfIdURL": "<?php echo $apps->Salesforce_Id;?>/e"
						
					  }
					]
				}
				processor_listview(config, allContact.fields);
			
	});
	 
	
	$( "#selectContact" ).change(function() { 
		var config = {
		  "root": "root", 
		};
                 var getSelected = $("#selectContact").val();
                 if(getSelected == 'All'){
                    var allContact = {
					"type": "object",
					"fields": [
					  {
						"name": "<?php echo $apps->Name;?>",
						"account": "<?php echo $apps->Organization_Id;?>",
						"phone": "<?php echo $apps->Mobile;?>",
						"email": "<?php echo $apps->Email;?>",
						"gender": "Male"
					  }
					   
					]
				}
				processor_listview(config, allContact.fields);
                } 
        });
		
	
	 <?php endforeach; ?>
</script>
