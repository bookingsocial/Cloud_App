<form action="<?php echo base_url();?>bksl/editProfile" method="post">
          <!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $ContactDetails->Name; ?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div><!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
					  <div class="form-group">
						<label >First name:</label>
						  <input class="form-control" type="text" name="FirstName" value="<?php echo $ContactDetails->FirstName; ?>">
					  </div>
					  <div class="form-group">
						<label>Last name:</label>
						  <input class="form-control" type="text" name="LastName" value="<?php echo $ContactDetails->LastName; ?>">
					  </div>
					  <div class="form-group">
						<label >Email:</label>
						  <input class="form-control" type="text" name="Email" value="<?php echo $ContactDetails->Email; ?>">
					  </div>
					  <div class="form-group">
						<label >Street:</label>
						  <input class="form-control" type="text" name="MailingStreet" value="<?php echo $ContactDetails->MailingStreet; ?>">
					  </div>
					  <div class="form-group">
						<label>City:</label>
						  <input class="form-control" type="text" name="MailingCity" value="<?php echo $ContactDetails->MailingCity; ?>">
					  </div>
					  <div class="form-group">
						<label>State:</label>
						  <input class="form-control" type="text" name="MailingState" value="<?php echo $ContactDetails->MailingState; ?>">
					  </div>
					  <div class="form-group">
						<label >Country:</label>
						  <input class="form-control" type="text" name="MailingCountry"  value="<?php echo $ContactDetails->MailingCountry; ?>">
					  </div>
					  <div class="form-group">
						<label >Postel code:</label>
						  <input class="form-control" type="text" name="MailingPostalCode"  value="<?php echo $ContactDetails->MailingPostalCode; ?>">
					  </div>
                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.box-body -->
            <div class="box-footer">
					<button type="submit" class="btn btn-primary btn-sm btn-flat">Update</button>
					<a href="<?php echo base_url();?>booking/" class="btn btn-default" >Cancel</a>
            </div>
          </div><!-- /.box -->
</form>