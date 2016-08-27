
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
          <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

          <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>-->
        <!-- Tab panes -->
        <div class="tab-content ">
          <!-- Home tab content 
          <div class="tab-pane" id="control-sidebar-home-tab">
            
          </div> /.tab-pane -->
          <!-- Settings tab content -->
          <div class="tab-pane active" id="control-sidebar-settings-tab">
            <form method="post" id="ContactSettingsForm">
              <h3 class="control-sidebar-heading">General Settings</h3>
              <div class="form-group">
			    <input type="hidden"  name="Id" value="<?php echo $ContactDetails->Id; ?>"/>
                <label class="control-sidebar-subheading" for="Do_Not_Distrub">
                  Activate do not distrub
					<?php 
						if($ContactDetails->Do_Not_Distrub == 1){ 
                  	?>
						<input type="checkbox" class="pull-right" checked="checked" name="Do_Not_Distrub" id="Do_Not_Distrub" onChange="doSubmit();" />
					<?php }else{ ?>
                  	    	<input type="checkbox" class="pull-right" name="Do_Not_Distrub" id="Do_Not_Distrub" onChange="doSubmit();"/>
					<?php } ?>
                </label>
                <p>
                  Some information about this general settings option
                </p>
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
			var data = $('form#ContactSettingsForm').serialize();
			$.post("<?php echo base_url();?>bksl/contactSett", data, function(response) {
				console.log(response);
			});
		}
	</script>
  </body>
</html>