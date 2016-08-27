        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 0.0.1
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://bookingsocial.com">Booking Social</a>.</strong> All rights reserved.
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs 
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
         <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li

          <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>-->
        <!-- Tab panes -->
        <div class="tab-content ">
          <!-- Home tab content 
          <div class="tab-pane" id="control-sidebar-home-tab">
            
          </div> /.tab-pane -->
          <!-- Settings tab content -->
          <div class="tab-pane active" id="control-sidebar-settings-tab">
            <form method="post" id="ExpertSettingsForm">
              <h3 class="control-sidebar-heading">General Settings</h3>
				<div class="form-group">
				<label class="control-sidebar-subheading">Date Format</label>
				<select class="form-control" onChange="doSubmit();" name="Date_Format">
				<?php if($ExpertDetails->Date_Format == 'dd/mm/yy'){?>
				  <option selected="selected" value="dd/mm/yy">dd/mm/yyyy</option>
				<?php }else{?>
				  <option  value="dd/mm/yy">dd/mm/yyyy</option>
				<?php }?>
				<?php if($ExpertDetails->Date_Format == 'mm/dd/yy'){?>
				  <option selected="selected" value="mm/dd/yy">mm/dd/yyyy</option>
				<?php }else{?>
				  <option  value="mm/dd/yy">mm/dd/yyyy</option>
				<?php }?>
				<?php if($ExpertDetails->Date_Format == 'yy/dd/mm'){?>
				  <option selected="selected" value="yy/dd/mm">yyyy/dd/mm</option>
				<?php }else{?>
				  <option value="yy/dd/mm">yyyy/dd/mm</option>
				<?php }?>
				</select>
			  </div><!-- /.form-group -->
			  <div class="form-group">
				<label class="control-sidebar-subheading">Email Notification
				<?php if(!$ExpertDetails->Email_Notification){?>
					<input type="checkbox"  data-layout="fixed" class="pull-right" name="Email_Notification" onChange="doSubmit();"/>
				<?php }else{?>
					<input type="checkbox"  data-layout="fixed" checked="checked" class="pull-right" name="Email_Notification" onChange="doSubmit();"/>
				<?php }?>
				</label>
			  </div><!-- /.form-group -->
			  <div class="form-group">
				<label class="control-sidebar-subheading">SMS Notification
				<?php if(!$ExpertDetails->SMS_Notification){?>
					<input type="checkbox"  data-layout="fixed" class="pull-right" name="SMS_Notification" onChange="doSubmit();"/>
				<?php }else{?>
					<input type="checkbox"  data-layout="fixed" checked="checked" class="pull-right" name="SMS_Notification" onChange="doSubmit();"/>
				<?php }?>				
			  </label>
			  </div><!-- /.form-group -->
			  <input type="hidden" name="OrgId" value="<?php echo $userDetails->Organization_Id;?>" />
			  <input type="hidden" name="Id" value="<?php echo $ExpertDetails->Id;?>" />
			  <div class="form-group">
				<label class="control-sidebar-subheading">Call Notification
				<?php if(!$ExpertDetails->Call_Notification){?>
					<input type="checkbox"  data-layout="fixed" class="pull-right" name="Call_Notification" onChange="doSubmit();"/>
				<?php }else{?>
					<input type="checkbox"  data-layout="fixed" checked="checked" class="pull-right" name="Call_Notification" onChange="doSubmit();"/>
				<?php }?>
				</label>
			  </div><!-- /.form-group -->
            </form>
          </div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
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
    <!-- AdminLTE for demo purposes 
    <script src="../../dist/js/demo.js" type="text/javascript"></script>-->
    <script type="text/javascript">
		function doSubmit(){
			$.ajax({ 
			   type: "POST",
			   data: $('#ExpertSettingsForm').serialize(),
			   url: "<?php echo base_url();?>bksl/expertSettings",
			   success: function(data){        
			   }
			});
		}
	</script>
  </body>
</html>