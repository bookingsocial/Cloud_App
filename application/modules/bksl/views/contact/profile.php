<!-- Main content -->
        <section class="invoice">
          <!-- title row -->
          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                <?php echo $ContactDetails->LastName.' '.$ContactDetails->FirstName; ?>
				  <?php 
				  	$datestring = "%M %d";
					$time = strtotime($userDetails->created); 
				  
				  ?>
                <small class="pull-right">User since: <?=mdate($datestring, $time)?></small>
              </h2>
            </div><!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
              <address>
                <strong></strong><br>
					<?php echo $ContactDetails->MailingStreet; ?><br>
					<?php echo $ContactDetails->MailingCity; ?><br>
					<?php echo $ContactDetails->MailingState; ?><br>
					<?php echo $ContactDetails->MailingCountry; ?> 
					<?php echo $ContactDetails->MailingPostalCode; ?><br/>
					<?php echo $ContactDetails->Email; ?>
              </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col"> 

            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">

            </div><!-- /.col -->
          </div><!-- /.row -->

         

          <!-- this row will not appear when printing -->
          <div class="row no-print">
            <div class="col-xs-12">
			  <a href="<?php echo base_url();?>bksl/auth/change_password" class="btn btn-default">Change Password</a>
              <a href="<?php echo base_url();?>bksl/deactivate" target="_blank" class="btn btn-default">Deactivate</a>
              <a href="<?php echo base_url();?>bksl/editProfile" class="btn btn-success pull-right">Edit</a>
            </div>
          </div>
        </section><!-- /.content -->