
 
 
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
	
	<form  class="form-signin" action="<?php echo  base_url('main/createContact'); ?>" method="POST" style="margin-top: 70px;" name="createContact" id="createContact">
	<div class="row">
		<h4 style="margin-bottom: 0px;"><span style="border-bottom: 2px solid #7D8E9E;">Contact</span></h4>
	</div>
	<hr/>
	<div id="root" class="col-md-12">
		<input type="hidden" name="sfId" value="<?php echo $Sales_Id =  $this->uri->segment(3); ?>"></input>
		<?php $modeVal =  $this->uri->segment(4);
			  $idval = $this->uri->segment(3);
			  $idvals = strlen($idval);
			  
			  if($modeVal == 'e' || $idvals == 18) { ?>
			<input type="hidden" name="Id" value="<?php echo $Id =  $ContactDetalsById->Id; ?>"></input>
		<?php } ?>
		
		<input type="hidden" name="mode" value="<?php echo $modes =  $this->uri->segment(4);?>" ></input>
	</div>
	<div class="col-md-12" style="text-align: -webkit-center;">
		<button type="submit" onclick="submitbtn()" id="button">Submit</button>
	</div>
	 
	</form>
<script>
	
	
	
	
	$(document).ready(function(){ 
		var config = { 
		  "root": "root",
		};  
		  
		var fields = <?php echo	$jsondataFeild; ?>;
		var Filters = <?php echo $jsondataFilters; ?>;
		
		<?php if($modeVal == 'e' || $idvals == 18) { ?>
		
			foreach( $array as $value )
			{  
				 
			}		
			fields['fields'][0]['value'] = '<?php echo $ContactDetalsById->FirstName; ?>';
			fields['fields'][1]['value'] = '<?php echo $ContactDetalsById->LastName; ?>';
			fields['fields'][2]['value'] = '<?php echo $ContactDetalsById->Email; ?>';
			fields['fields'][3]['value'] = '<?php echo $ContactDetalsById->Mobile; ?>';
			fields['fields'][4]['value'] = '<?php echo $ContactDetalsById->MailingStreet; ?>'; 
			fields['fields'][5]['value'] = '<?php echo $ContactDetalsById->MailingCity; ?>';
			fields['fields'][6]['value'] = '<?php echo  $ContactDetalsById->MailingState; ?>';
			fields['fields'][7]['value'] = '<?php echo $ContactDetalsById->MailingCountry; ?>';
			fields['fields'][8]['value'] = '<?php echo $ContactDetalsById->MailingPostalCode; ?>';
			
			fields['fields'][0]['value'] = '<?php echo $ContactDetalsById->FirstName; ?>';
			
		<?php if($modeVal == '' && $idvals == 18) { ?>
			fields['fields'][0]['type'] = 'textView';
			fields['fields'][1]['type'] = 'textView';
			fields['fields'][2]['type'] = 'textView';
			fields['fields'][3]['type'] = 'textView';
			fields['fields'][4]['type'] = 'textView'; 
			fields['fields'][5]['type'] = 'textView';
			fields['fields'][6]['type'] = 'textView';
			fields['fields'][7]['type'] = 'textView';
			fields['fields'][8]['type'] = 'textView';
		<?php }  ?>
			
		<?php }  ?>
		
		processor_fields(config, fields['fields'] ,Filters['sections']);
			 
	}); 
	  
	function submitbtn(){
		processor_validation_execute();
	}
	
</script>
