<link rel="stylesheet" href="<?php echo base_url();?>js/lib/plugins/iCheck/all.css">
<style>
.profile 
{
    display: inline-block;
    }
figcaption.ratings
{
    margin-top:20px;
    }
figcaption.ratings a
{
    color:#f1c40f;
    font-size:11px;
    }
figcaption.ratings a:hover
{
    color:#f39c12;
    text-decoration:none;
    }
.divider 
{
    border-top:1px solid rgba(0,0,0,0.1);
    }
.emphasis 
{
    border-top: 4px solid transparent;
    }
.emphasis:hover 
{
    border-top: 4px solid #1abc9c;
    }
.emphasis h2
{
    margin-bottom:0;
    }
span.tags 
{
    background: #1abc9c;
    border-radius: 2px;
    color: #f5f5f5;
    font-weight: bold;
    padding: 2px 4px;
    }
.dropdown-menu 
{
    background-color: #34495e;    
    box-shadow: none;
    -webkit-box-shadow: none;
    width: 250px;
    margin-left: -125px;
    left: 50%;
    }
.dropdown-menu .divider 
{
    background:none;    
    }
.dropdown-menu>li>a
{
    color:#f5f5f5;
    }
.dropup .dropdown-menu 
{
    margin-bottom:10px;
    }
.dropup .dropdown-menu:before 
{
    content: "";
    border-top: 10px solid #34495e;
    border-right: 10px solid transparent;
    border-left: 10px solid transparent;
    position: absolute;
    bottom: -10px;
    left: 50%;
    margin-left: -10px;
    z-index: 10;
    }
.col-xs-12.col-md-6 {
		padding: 8px 0px;
		margin-bottom: 0px;height: 30px;
}
</style>

<?php 
  $userDet = $editUserDetails['user'];
  if($userDet->user_type  == 'EXPERT')
  	$expDet = $editUserDetails['expert'];
  else 
  	$conDet =$editUserDetails['contact'];
  	$datestring = "%l,%M %d at %h:%i %A";
?>
<div class="box">
	<div class="box-header with-border" style="text-align: -webkit-center;">
					<?php if($userDet->activated){?>
						<a href="<?php echo base_url('bksl/auth/a_deactivate/'.$userDet->id); ?>" class="btn btn-primary">De-Activate</a>
					<?php }else{?>
						<a href="<?php echo base_url('bksl/auth/a_activate/'.$userDet->id); ?>" class="btn btn-primary">Activate</a>
					<?php }?>
					<?php echo form_open(base_url('bksl/auth/forget_password'),array('style' => 'display: initial;')); ?>
						<input name="login" id="login" type="hidden" value="<?php echo $userDet->username; ?>"/>
						<button type="submit" class="btn btn-primary">Reset Password</button>
					<?php echo form_close(); ?>
	</div><!-- /.box-header --> 
    <div class="box-body">
		<div class="container">
		    	 
		                <div class="col-xs-12 col-sm-12">
		                    <?php if($userDet->user_type  == 'EXPERT'){?>
		                    		<h2><?php //=//$expDet->Name;?></h2>
		                    <?php }else if($userDet->user_type  == 'CONTACT'){?>
		                    <h2><?php //=$conDet->Name;?></h2>
		                    <?php }?>
												<div class=" col-xs-12 col-md-6" >
													<div class=" col-xs-4 col-md-4" style="text-align: -webkit-right;">
														<label for="Username">Username</label>
													</div>
													<div class=" col-xs-8 col-md-8">
															<p><?=$userDet->username;?></p>
													</div> 
												</div>
												<div class=" col-xs-12 col-md-6">
													<div class=" col-xs-4 col-md-4" style="text-align: -webkit-right;">
														<label for="Email">Email</label>
													</div>
													<div class=" col-xs-8 col-md-8">
															<p><?=$userDet->email;?></p>
													</div> 
												</div>
												<div class=" col-xs-12 col-md-6" >
													<div class=" col-xs-4 col-md-4" style="text-align: -webkit-right;">
														<label for="User Since">User Since</label>
													</div>
													<div class=" col-xs-8 col-md-8">
															<p><?=mdate($datestring, strtotime($userDet->created));?></p>
													</div> 
												</div>
												<div class=" col-xs-12 col-md-6">
													<div class=" col-xs-4 col-md-4" style="text-align: -webkit-right;">
														<label for="Last Modified Date">Last Modified Date</label>
													</div>
													<div class=" col-xs-8 col-md-8">
															<p><?=mdate($datestring, strtotime($userDet->modified));?></p>
													</div> 
												</div>
												<div class=" col-xs-12 col-md-6">
													<div class=" col-xs-4 col-md-4" style="text-align: -webkit-right;">
														<label for="Last Login">Last Login</label>
													</div>
													<div class=" col-xs-8 col-md-8">
															<p><?=mdate($datestring, strtotime($userDet->last_login));?></p>
													</div> 
												</div>
		                     <div class=" col-xs-12 col-md-6">
													<div class=" col-xs-4 col-md-4" style="text-align: -webkit-right;">
														<label for="Ip Address ">Ip Address </label>
													</div>
													<div class=" col-xs-8 col-md-8">
															<p><?=$userDet->last_ip;?></p>
													</div> 
												</div>
   										<br/>
		    </div>                 
		</div>
    </div>
</div>
<?php if(!$userDet->banned){?>
<!--<div class="box">
    <div class="box-body">
		<div class="col-xs-12">
               <label>Ban Reason:</label>
               <textarea class="form-control" rows="4"></textarea>
               <br/>			                    	
               <button class="btn btn-danger btn-block">Ban User</button>
        </div>
    </div>
</div>-->
<?php }?>

<!-- Select2 -->
<script src="<?php echo base_url();?>js/lib/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url();?>js/lib/plugins/iCheck/icheck.min.js"></script>
<script>
	  var editUserDetails = <?php echo json_encode($editUserDetails);?>;
</script>