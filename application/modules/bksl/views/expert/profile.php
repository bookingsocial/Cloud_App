<!-- Main content -->
        <section class="invoice">
          <!-- title row --> 
          <div class="row">  
            <div class="col-xs-12">
              <h2 class="page-header">
                <i class="fa fa-globe"></i> <?php echo $ExpertDetails->Name; ?>.
				 
					<?php   
				  	$datestring = "%l,%M %d at %h:%i %A";
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
				     Email: <?php echo $ExpertDetails->Email; ?>   
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
              <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-ban"></i> Deactivate profile</a>
              <a href="<?php echo base_url();?>bksl/editProfile" class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</a>
            </div>
          </div>
        </section><!-- /.content -->