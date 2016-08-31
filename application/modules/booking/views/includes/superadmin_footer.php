		
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 0.0.1
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://bookingsocial.com">Booking Social</a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar 
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs 
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

          <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>-->
        <!-- Tab panes 
		
        <div class="tab-content ">
			<?php $allSettings = getAllSettings($userDetails->Organization_Id);		
			?>
          <div class="tab-pane" id="control-sidebar-home-tab">
            
          </div> 
          <div class="tab-pane active" id="control-sidebar-settings-tab">
            <form method="post" id="AdminSettingsForm">
              <h3 class="control-sidebar-heading">Calendar Settings</h3>
			  <div class="form-group">
				<label class="control-sidebar-subheading">Appointment Status</label>
				<input type="text" class="form-control" name="<?php echo $allSettings['CALENDARAPPOINTEMENTSTATUS']->Id;?>" value="<?php echo $allSettings['CALENDARAPPOINTEMENTSTATUS']->Value1;?>" onBlur="doSubmit("<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>",this.value,"<?php echo $userDetails->Organization_Id;?>");"/>
			  </div>
			  <div class="form-group">
				<label class="control-sidebar-subheading">Customer Type</label>
				<select name="customer_type" class="form-control" name="<?php echo $allSettings['CALDEFAULTCLIENTTYPE']->Id;?>" onChange="doSubmit("<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>",this.value,"<?php echo $userDetails->Organization_Id;?>");">
				
					<option value="NEW" <?php if($allSettings["CALDEFAULTCLIENTTYPE"]->Value1 == "NEW"){echo 'selected="selected"';}?>>New</option>
					<option value="EXISTING" <?php if($allSettings["CALDEFAULTCLIENTTYPE"]->Value1 == "EXISTING"){echo 'selected="selected"';}?>>Existing</option>
				</select>
			  </div>
			  <div class="form-group">
				<label class="control-sidebar-subheading">Allow PastBooking 
					<?php if($allSettings['CALENDARALLOWPASTBOOKING']->Value1 == 'true'){?>
						<input type="checkbox" checked="checked" name="<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>" onChange="doSubmit("<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>",this.value);"/>
					<?php }else{ ?>
						<input type="checkbox"  name="<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>" onChange="doSubmit("<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>",this.value,"<?php echo $userDetails->Organization_Id;?>");"/>
					<?php } ?>
					
				</label>
			  </div>
			  <input type="hidden" name="OrgId" value="<?php echo $userDetails->Organization_Id;?>" />
			  <div class="form-group">
				<label class="control-sidebar-subheading">Popup Type</label>
				<select name="customer_type" class="form-control" name="<?php echo $allSettings['CALENDARPOPUPTYPE']->Id;?>" onChange="doSubmit("<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>",this.value,"<?php echo $userDetails->Organization_Id;?>");">
					<option value="Normal" <?php if($allSettings['CALENDARPOPUPTYPE']->Value1 == 'Normal'){echo 'selected="selected"';}?>>Normal</option>
					<option value="Loose" <?php if($allSettings['CALENDARPOPUPTYPE']->Value1 == 'Loose'){echo 'selected="selected"';}?>>Loose</option>
				</select>
			  </div>
			  <div class="form-group">
				<label class="control-sidebar-subheading">Start Time</label>
				<input type="text" class="form-control" name="<?php echo $allSettings['CALENDARSTARTTIME']->Id;?>" value="<?php echo $allSettings['CALENDARSTARTTIME']->Value1;?>" onBlur="doSubmit("<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>",this.value,"<?php echo $userDetails->Organization_Id;?>");"/>
			  </div>
			  <div class="form-group">
				<label class="control-sidebar-subheading">End Time	
				</label>
				<input type="text" class="form-control" name="<?php echo $allSettings['CALENDARENDTIME']->Id;?>" value="<?php echo $allSettings['CALENDARENDTIME']->Value1;?>" onBlur="doSubmit("<?php echo $allSettings['CALENDARALLOWPASTBOOKING']->Id;?>",this.value,"<?php echo $userDetails->Organization_Id;?>");"/>
			  </div>
            </form>
          </div>
        </div>
		
      </aside>
      Add the sidebar's background. This div must be placed
           immediately after the control sidebar
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url();?>js/lib/bootstrap.min.js" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="<?php echo base_url();?>js/lib/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url();?>js/lib/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url();?>js/lib/app.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		function doSubmit(Id,Value,OrgId){
			$.ajax({ 
			   type: "POST",
			   data: 'recordId="'+Id+'"&value="'+Value+'"&OrgId='+OrgId,
			   url: "<?php echo base_url();?>bksl/adminSettings",
			   success: function(data){        
			   }
			});
		}
	</script>
  </body>
</html>