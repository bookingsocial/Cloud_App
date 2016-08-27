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
    <div class="box-body">
		<div class="container">
		    	 <div class="profile">
		            <div class="col-sm-12">
		                <div class="col-xs-12 col-sm-8">
		                    <?php if($userDet->user_type  == 'EXPERT'){?>
		                    	<h2><?=$expDet->Name;?></h2>
		                    <?php }else if($userDet->user_type  == 'CONTACT'){?>
		                    <h2><?=$conDet->Name;?></h2>
		                    <?php }?>
							  <p><strong>Username: </strong><?=$userDet->username;?></p>
							  <p><strong>Email: </strong><?=$userDet->email;?></p>
		                    <p><strong>User Since: </strong><?=mdate($datestring, strtotime($userDet->created));?></p>
		                     <p><strong>Last Modified Date:</strong> <?=mdate($datestring, strtotime($userDet->modified));?></p>
		                     <p><strong>Last Login :</strong><?=mdate($datestring, strtotime($userDet->last_login));?></p>
   								<span class="tags">Ip Address : <?=$userDet->last_ip;?></span>
   							<br/>
		                </div>             
		                <div class="col-xs-12 col-sm-4">
		                    <div class="col-xs-12  text-center">
			                    <br/>
			                    <?php if($userDet->activated){?>
			                    	<button class="btn btn-warning btn-block">De-Activate User</button>
			                    <?php }else{?>
			                    	<button class="btn btn-success btn-block">Activate User</button>
			                    <?php }?>
			                </div>
			                <div class="col-xs-12  text-center">
			                	<br/>
			                    <?php if($userDet->banned){?>
			                    	<button class="btn btn-info btn-block">Unban User</button>
			                		<br/>
			                    <?php }?>
			                </div>
			                <div class="col-xs-12">
			                    <button class="btn btn-info btn-block">Reset Password</button>
			                </div>
		                </div>
		            </div>  
		    </div>                 
		</div>
    </div>
</div>
<?php if(!$userDet->banned){?>
<div class="box">
    <div class="box-body">
		<div class="col-xs-12">
               <label>Ban Reason:</label>
               <textarea class="form-control" rows="4"></textarea>
               <br/>			                    	
               <button class="btn btn-danger btn-block">Ban User</button>
        </div>
    </div>
</div>
<?php }?>

<!-- Select2 -->
<script src="<?php echo base_url();?>js/lib/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url();?>js/lib/plugins/iCheck/icheck.min.js"></script>
<script>
	  var editUserDetails = <?php echo json_encode($editUserDetails);?>;
</script>