<link rel="stylesheet" href="<?php echo base_url();?>js/lib/plugins/iCheck/all.css">
<div class="modal fade" id="alertModal">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span></button>
			<h4 class="modal-title">Warning</h4>
		</div>
		<div class="modal-body" id="errorMessage">
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<div class="box">
    <div class="box-body">
		 <div class="register-box" style="width: 60%;">
		  <!--<div class="row">
 			  <div class="col-md-3">
			   <Label>User Type:</Label>
			  </div>
			   <div class="col-md-3">
					<label>
					  <input type="radio" name="r3" class="flat-red" value="CONTACT" checked>
					</label>&nbsp;
					<label>CONTACT USER</label>
			   </div>
			   <div class="col-md-3">
					<label>
					  <input type="radio" name="r3" class="flat-red" value="EXPERT">
					</label>&nbsp;
					 <label>EXPERT USER</label>
			   </div>
			   <div class="col-md-3">
			  </div>
		</div>-->
		<br/>
		<div class="row" id="ContactBlock">
 			  <div class="col-md-3">
			   <Label>Select Contact:</Label>
			  </div>
			   <div class="col-md-9">
				<div class="form-group">
				<select class="form-control" style="width: 100%;" id="contactDropDown">
                      <option selected="selected" value="EMPTY">Select Contact</option>
                     
                 </select>
				</div>
			  </div>
		</div>
		<!--<div class="row" id="ExpertBlock" style="display:none;">
 			  <div class="col-md-3">
			   <Label>Select Expert:</Label>
			  </div>
			   <div class="col-md-9">
				<div class="form-group">
				<select class="form-control" style="width: 100%;" id="expertDropDown">
                      <option selected="selected" value="EMPTY">Select Expert</option>
                     
                 </select>
				</div>
			  </div>
		</div>-->
		<br/>
		<div class="row">
 			  <div class="col-md-3">
			   <Label>Username:</Label>
			  </div>
			   <div class="col-md-9">
				<div class="form-group">
					<div class="form-group has-feedback">
						<input type="text" class="form-control" id="username">
						<span class="glyphicon glyphicon-user form-control-feedback"></span>
					</div>
				</div>
			  </div>
		</div>
		<br/>
		<div class="row">
 			  <div class="col-md-3">
			   <Label>Password:</Label>
			  </div>
			   <div class="col-md-9">
				<div class="form-group">
					<div class="form-group has-feedback">
						<input type="password" class="form-control" id="password">
						<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
					</div>
				</div>
			  </div>
		</div>
		<br/>
		<div class="row">
 			  <div class="col-md-3">
			   <Label>Retype password:</Label>
			  </div>
			   <div class="col-md-9">
				<div class="form-group">
				<div class="form-group has-feedback">
						<input type="password" class="form-control">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
				</div>
			  </div>
		</div>
		<br/>
		<div class="row">
			<button class="btn btn-primary pull-right" id="createNewUser">
				CREATE
			</button>
		</div>
	  </div>
    </div>
</div>
<!-- Select2 -->
<script src="<?php echo base_url();?>js/lib/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url();?>js/lib/plugins/iCheck/icheck.min.js"></script>
<script>
	  var contactDetails = <?php echo json_encode($contactDetails);?>;
	  //var expertDetails = <?php echo json_encode($expertDetails);?>;
      $(function () {
          var selectBoxConData = [];
          var mapConData ={};
         for(var i=0;i< contactDetails.length;i++){
        	 selectBoxConData.push({id:contactDetails[i].Id,text:contactDetails[i].LastName});
			 mapConData[contactDetails[i].Id] = contactDetails[i];
         }
		var contactDropDown = $("#contactDropDown").select2({
				data:selectBoxConData
			});
		 /*var selectBoxExpData = [];
         var mapExpData ={};
         for(var i=0;i< expertDetails.length;i++){
        	 selectBoxExpData.push({id:expertDetails[i].Id,text:expertDetails[i].Name});
			 mapExpData[expertDetails[i].Id] = expertDetails[i];
         }
		var expertDropDown = $("#expertDropDown").select2({
				data:selectBoxExpData
			});
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });
		var selectedVal = 'CONTACT';
		$('input').on('ifChecked', function(event){
		   if($(this).val() == 'EXPERT'){
			    selectedVal = 'EXPERT';
			    $('#ContactBlock').hide();
				$('#ExpertBlock').show();
			   }else{
				 selectedVal = 'CONTACT';
				$('#ContactBlock').show();
				$('#ExpertBlock').hide();
		   }
		});*/
		$('#createNewUser').on('click',function(event){
			event.preventDefault();
			var userCreateURL = '<?php echo base_url();?>bksl/ajaxHandler/create_user'
			 var inputData = {};
			 inputData['user_type'] = 'CONTACT';
			 //if(selectedVal == 'EXPERT'){
			 	//inputData['related_to'] = expertDropDown.val();
				//if(mapExpData.hasOwnProperty(inputData['related_to']))
					//inputData['email'] = mapExpData[inputData['related_to']].Email; 
			 //}else{
				inputData['related_to'] =  contactDropDown.val();
				if(mapConData.hasOwnProperty(inputData['related_to']))
					inputData['email'] = mapConData[inputData['related_to']].Email; 
			 //}
			 inputData['Organization_Id'] = '<?php echo $userDetails->Organization_Id;?>';
			 inputData['password'] = $('#password').val();
			 inputData['username'] = $('#username').val();
			 inputData['provider'] = '<?php echo $userDetails->provider;?>';
			 $.ajax({
				   url: userCreateURL,
				   data: $.param(inputData),
				   error: function(error) {
						 var sHTML = $.parseHTML(error.responseText);
							$.each(sHTML, function (i, el) {
								 if(el.nodeName == 'div' || el.nodeName == 'Div')
										$('#errorMessage').html(el.nodeValue);
									//el.nodeValue = actual value of the node.
							});
							 	$('#alertModal').modal({
									keyboard: false
								});
				   },
				   success: function(data) {
					  console.log(data);
						 data = $.parseJSON(data);
						 if(data.hasOwnProperty('errors')){
							  var errors = '';
							  for(var a in data.errors){
									errors += '<p>'+data.errors[a]+'</p><br/>';
								}
							  $('#errorMessage').html(errors);
							 	$('#alertModal').modal({
									keyboard: false
								});
						 }
						
				   },
				   type: 'POST'
				});
		});
      });
    </script>
