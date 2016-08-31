	<style>
		#root .col-xs-12.col-md-6 {
			    padding: 8px 0px;
		}
	</style>
    <script>
		_baseURL = '<?php echo  base_url(); ?>';

		var allScripts = [ 	"js/lib/jquery-2.1.4.js", 
							"js/lib/mustache.js",
							"js/lib/LiveValidation.js"
						];  
		var allCss = [  
						"css/LiveInValid.css",
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

		
		var fields = <?php echo	$jsondataFeild; ?>;
		var Filters = <?php echo $jsondataFilters; ?>;

		//console.log(Filters['sections']); 
		//console.log(fields); 
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
