<link href="<?php echo  base_url(); ?>css/jquery.datetimepicker.css" rel="stylesheet">    
<link href="<?php echo  base_url(); ?>css/bootstrap-editable.css" rel="stylesheet">
<link href="<?php echo  base_url(); ?>css/fullcalendar.css" rel="stylesheet">
<link href="<?php echo  base_url(); ?>css/fullcalendar.print.css" rel="stylesheet">  
<link href="<?php echo  base_url(); ?>css/ECAL_ExpertCalendar.css" rel="stylesheet">
<style>
	.fc-header-title h2 {
    	font-size: 20px;
	}
	.info-box {
		display: block;
		min-height: 50px;
		background: #fff;
		width: 100%;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
		border-radius: 2px;
		margin-bottom: 0px;
		margin-right: 5px;
	}
	.info-box-icon {
		border-top-left-radius: 2px;
		border-top-right-radius: 0;
		border-bottom-right-radius: 0;
		border-bottom-left-radius: 2px;
		display: block;
		float: left;
		height: 57px;
		width: 50px;
		text-align: center;
		font-size: 45px;
		line-height: 90px;
		background: rgba(0, 0, 0, 0.2);
	}
	.info-box-content {
		padding: 2px 9px;
		margin-left: 50px;
	}
</style>
<div class="row">
<div class="col-md-12 contentBlock" >
	<div id="colorContent">
		<!-- Status Loads here -->  
	</div>
</div><!-- /.col -->
<div class="col-md-12">
		<div class="box box-default">                                
			<div class="box-body">
				<!-- THE CALENDAR -->
				<div id="calendar"></div>
			</div><!-- /.box-body -->
		</div><!-- /. box -->
	</div><!-- /.col -->
</div>	    
<div id="tevent" class="tooltipevetn Normal" style="display:none;">
		   <div id="des"></div>
		   <div class="ui  inverted dimmer" id="tooltipLoading">
		        <div class="ui text loader">Loading</div>
		  </div>
		 </div>
		</div>
		<div id="reScheduleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
          <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			    <h3 id="myModalLabel1" style="font-size:14px;">RESCHEDULE</h3>
			</div>
			<div class="modal-body">
	        <div class="row" >
	        <div class="col-md-12" >
            <label class="control-label" style="text-align:left;width: 100%;" ><b>Date</b></label>
                <br/>
                <div class="controls" style="margin-left:0px !important;">
                    <input type="text" class="" id="reScheduleDate" />
                    <input type="hidden" id="serReqId" />
                </div>
                <article class="kanban-entry grab isNotreFlexiTime" >
                     <div class="kanban-entry-inner">
                         <div class="kanban-label"  id="prevSlot">
                            
                         </div>
                     </div>  
             </article>
            <label class="control-label isNotreFlexiTime" style="text-align:left;width: 100%;" ><b>Select Slot</b></label>
            <br/>
            <div class="controls isNotreFlexiTime" style="margin-left:0px !important;">
                    <select id="reScheduleSlots">
                        <option value=""> Select a Service</option>
                    </select>
            </div>
            <article class="kanban-entry grab isNotreFlexiTime"  >
                  <div class="kanban-entry-inner">
                         <div class="kanban-label"  id="nextSlot">
                            
                         </div>
                  </div>      
             </article>
           <label class="control-label isreFlexiTime" style="text-align:left;width: 100%;" for="reScheduleStartTime">Start Time:</label>
           <br/>
           <div class="controls isreFlexiTime" style="margin-left:0px !important;">
                <input id="reScheduleStartTime" type="text" />
           </div>
           <label class="control-label isreFlexiTime" style="text-align:left;width: 100%;" for="reScheduleDuration">Duration:</label>
           <br/>
           <div class="controls isreFlexiTime" style="margin-left:0px !important;">
            <input id="reScheduleDuration" type="text" value="" placeholder="Duration in minutes"/>
           </div>
           <label class="control-label isreFlexiTime" style="text-align:left;width: 100%;" for="reScheduleEndTime">End Time:</label>
           <br/>
           <div class="controls isreFlexiTime" style="margin-left:0px !important;">
            <input id="reScheduleEndTime" type="text" value=""/>
           </div>
       </div>
      </div>
    </form>
    <div class="ui  inverted dimmer" id="reScheduleLoading">
        <div class="ui text loader">Loading</div>
      </div>
    </div>
    </div>
    </div>
    <div class="modal-footer">
        <button class="button" data-dismiss="modal" style="background:Transparent;border:none;color:#616467;padding:7px 12px 6px;" aria-hidden="true">CANCEL</button>
        <button type="submit" class="button" id="reScheduleBtn" style="padding:7px 12px 6px;">SCHEDULE</button>
    </div>
</div>
<!-- Start:Model PopUp window to create serviceRequest -->
  <div class="modal fade" id="createEventModal">
  <div class="modal-dialog">
    <div class="modal-content" style="width: 132%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 id="myModalLabel1">CREATE APPOINTMENT</h4>
    </div>
    <div class="modal-body">
    <form id="createAppointmentForm" class="form-horizontal">
        <div class="row" >
        <div class="col-xs-6 col-sm-6 col-md-6" >
        <div class="form-group">
            <label class="control-label col-sm-4" for="existing">Customer</label>
            <div class="col-sm-3">
                <input type="radio" name="existing" value="NEW" checked="checked" /><span class="radio-label">New</span>&nbsp;
            </div>
            <div class="col-sm-5">
                <input type="radio" name="existing" value="EXISTING"/><span class="radio-label">Existing</span>
			</div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="existingCustomerName">Ex-Customer</label>
            <div class="col-sm-8">
              <input type="text" name="existingCustomerName" id="existingCustomerName" class="form-control" disabled="disabled" />
                <input type="hidden" name="selCusID" id="selCusID"  />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="existingCustomerName">FirstName:</label>
            <div class="col-sm-8">
                <input type="text" name="FirstName" id="FirstName"  class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="LastName">LastName:</label>
            <div class="col-sm-8">
                <input type="text" name="LastName" id="LastName"  class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="Email">Email:</label>
            <div class="col-sm-8">
                <input type="text" name="Email" id="Email"  class="form-control"/>
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-4 control-label" for="listService">Service:</label>
            <div class="col-sm-8">
                <select id="listService" class="form-control">
                		<option value="">Select Service</option>
                		<?php foreach ($serviceDetails as $item): ?>
                			<option value="<?=$item->Salesforce_Id;?>"><?=$item->Name;?></option>
                		<?php endforeach; ?>
            	</select>
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-4 control-label" for="selectedDateTime">Selected Date:</label>
            <div class="col-sm-8">
                <input id="selectedDateTime" type="text" class="form-control"/>
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-4 control-label" for="availableSlots">Select Time:</label>
            <div class="col-sm-8">
                 <select id="availableSlots" class="form-control">
	           		  <option value=""> Select a Service</option>
	            </select>
            </div>
        </div>               
       </div>
       <div class="col-xs-6 col-sm-6 col-md-6" >
		   <div class="form-group">
	            <label class="col-sm-4 control-label" for="Phone">Phone:</label>
	            <div class="col-sm-8">
	                <input name="Phone" type="text" id="Phone"  class="form-control"/>
	            </div>
	        </div>
	        <div class="form-group">
	            <label class="col-sm-4 control-label" for="Street">Mailing Street:</label>
	            <div class="col-sm-8">
	                <input name="Street" type="text" id="Street"  class="form-control"/>
	            </div>
	        </div>
	        <div class="form-group">
	            <label class="col-sm-4 control-label" for="City">Mailing City:</label>
	            <div class="col-sm-8">
	                <input name="City" type="text" id="City"  class="form-control"/>
	            </div>
	        </div>
	        <div class="form-group">
	            <label class="col-sm-4 control-label" for="ZipCode">Mailing State:</label>
	            <div class="col-sm-8">
	                <input name="State" type="text" id="State"  class="form-control"/>
	            </div>
	        </div>
	        <div class="form-group">
	            <label class="col-sm-4 control-label" for="ZipCode">Mailing PostalCode:</label>
	            <div class="col-sm-8">
	                <input name="ZipCode" type="text" id="ZipCode"  class="form-control"/>
	            </div>
	        </div>
       </div>
      </div>
      <span class="help-block" id="exceptionPanel" style="color:red"></span>
    </form>
    <div class="ui  inverted dimmer" id="createAppLoading">
        <div class="ui text loader">Loading</div>
      </div>
    </div>
    <div class="modal-footer">
        <button class="button" data-dismiss="modal" style="background:Transparent;border:none;color:#616467;padding:7px 12px 6px;" aria-hidden="true">CANCEL</button>
        <button type="submit" class="button" style="padding:7px 12px 6px;"  id="bookAppointment">SCHEDULE</button>
    </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- End: Model PopUp window to create serviceRequest -->
 <div class="modal fade" id="serReqDetails">
      <div class="modal-dialog">
    	<div class="modal-content" style="width: 120%;">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		        <h3 id="myModalLabel1" style="font-size:14px;">APPOINTMNET DETAILS</h3>
		    </div>
		    <div class="modal-body">
                      <ul class="nav nav-tabs" id="customerRecord">
                          <li class="active" id="showDetails"><a href="#ServiceView" data-toggle="tab">SERVICES</a></li>
                          <!--<li class="" id=""><a href="#CommunicationView" data-toggle="tab">CONTACT</a></li>-->
                          <li class="" id=""><a href="#historyView" data-toggle="tab">APPOINTMENT HISTORY</a></li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content" >
                          <div class="tab-pane active" id="ServiceView" >
                              <div class="row">
                                     
                                      <div class="row">
                                          <span class="col-xs-6">
                                           <b id="appStartDate"></b>&nbsp;
                                            <b id="appStartTime"></b>
                                                <b>-</b>
                                            <b  id="appEndTime"></b>
                                           </span>
                                           <div class="col-xs-6" id="appStatus">
                                                <select id="appSelStatus" style="float: right;margin-right: 45px;display:none;">
                                              
                                                </select>
                                                <span class="statusBlock">
                                                    <span class="appStatusColor">
                                                        <i class="fa fa-check" ></i>
                                                    </span>
                                                    <a href="#" id="appointmentStatus" data-type="select" ></a>
                                                </span>
                                            </div>
                                       </div>
                                      <div class="row">
                                          <div class="col-xs-6" id="appEndTime"></div>
                                      </div>
                                       <div class="row">
                                          <b class="col-xs-6" id="appContactName"></b>
                                      </div>
                                      <br/>
                                       <div class="row">
                                          <div class="col-xs-6" id="appServiceName"></div>
                                          <input type="hidden" value="" id="appId"/>
                                      </div>
                                      <br/>
                                      <br/>
                                      <div class="row">
                                          <div class="col-xs-6" >
                                                <label><b>Contact</b></label>
                                                <div id="contactAddress">
                                                    
                                                </div>
                                                <br/>
                                                <label id="contactPhone"></label>
                                                <label id="contactEmail"></label>
                                          </div>
                                          <div class="col-xs-6" >
                                                <label><b>Notes</b></label>
                                                <textarea id="appNotes" rows="4" style="width:310px"></textarea>
                                          </div>
                                      </div>
                                  </div>
                          </div>
                          <div class="tab-pane" id="CommunicationView">
                              <div class="row">
                                  <div class="container" style="width: 85%;" id="dynamicContactDetails">
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane" id="historyView">
                              <div class="row">
                                     <div class="timeline-centered" id="serReqTimeline">
                                        <!--  Timeline For Serice Request Append From Script -->
                                        
                                     </div>
                              </div>
                          </div>
                      </div>
                      <div class="ui  inverted dimmer" id="appDetLoading">
                        <div class="ui text loader">Loading</div>
                      </div>
                      <span id="serReqUpdateErrorPanel" style="color:red;"></span>
                  </div>
                  <div class="modal-footer">
                      <button type="button"  style="background:Transparent;border:none;color:#616467;padding:7px 12px 6px;" data-dismiss="modal" aria-hidden="true"  class="button" >CANCEL</button>
                      <button type="button"   style="padding:7px 12px 6px;" class="button" id="appUpdate">UPDATE</button>
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
      <!-- /.modal-dialog -->
      </div>
     <div class="modal hide" id="serReqMultiDetails">
          <div class="modal-dialog" style="left: auto;">
              <div class="modal-content">
                  <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                         <h3 id="myModalLabel1" style="font-size:14px;">APPOINTMENT DETAILS</h3>
                </div>
                  <div class="modal-body">
                      <ul class="nav nav-tabs" id="multiCustomer">
                          <li class="active" id="showmultiDetails"><a href="#multiServiceView" data-toggle="tab">SERVICES</a></li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content" >
                          <div class="tab-pane active" id="multiServiceView" style="padding: 20px;font-size:14px;">
                              <div class="row">
                                      <div class="row">
                                            <span class="col-xs-6">
                                                <b id="appMultiStartDate"></b>
                                                <b id="appMultiStartTime"></b>
                                                    <b>-</b>
                                                <b  id="appMultiEndTime"></b>
                                           </span>
                                      </div>
                                      <div class="row">
                                          <span  class="col-xs-6" id="appMultiServiceName"></span>
                                          <input type="hidden" value="" id="appMultiId"/>
                                      </div>
                                      <br/>
                                      <div class="row">
                                        <div class="col-xs-12">
                                            <label><b>Contacts</b></label>
                                            <table class="table table-striped custab">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="loadContacts">
                                                        
                                                </tbody>  
                                            </table> 
                                        </div>
                                      </div>
                                     <!-- <div class="container" style="width: 90%;" id="loadContacts">
                                      
                                    </div> --> 
                                  </div>
                          </div>
                      </div>
                      <div class="ui  inverted dimmer" id="appMultiDetLoading">
                        <div class="ui text loader">Loading</div>
                      </div>
                  </div>
                  <div class="modal-footer">
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
      <!-- /.modal-dialog -->
      </div>
      <div id="freeTime-confirm" title="Info" style="display:none;overflow:hidden;">
        Looks like you tried to add an Appointment on Holiday !
     </div>
	<script src="<?php echo  base_url(); ?>js/lib/moment.min.js"></script>
	<script src='<?php echo  base_url(); ?>js/lib/bootstrap.min.js'></script>
		<script type="text/javascript">
		//$.modalStatus = {"Pending": {"color": "#FECEA0"},"Completed": {"color": "#99CC94"},"Approved": {"color": "#65CDCE"},"No Show": {"color": "#D296D2"}};
			
			$.modalStatus = <?php echo getSettingValueByKey($ExpertDetails->Provider,'CALENDARSTATUSSETTINGS',$userDetails->Organization_Id)->Extended_Value; ?>;
			for (var key in $.modalStatus) {
		   if(!$.modalStatus[key].hasOwnProperty('count')){
					$.modalStatus[key]['count'] = 0;       
		   }
		}
	</script>
	<script src="<?php echo  base_url(); ?>js/lib/fullcalendar.js"></script>
	<script src='<?php echo  base_url(); ?>js/lib/jquery.timepicker.js'></script>
	<script src='<?php echo  base_url(); ?>js/lib/bootstrap-editable.js'></script>
	<script src='<?php echo  base_url(); ?>js/lib/jquery.datetimepicker.js'></script>
	<script src='<?php echo  base_url(); ?>js/lib/jquery-ui.min.js'></script>
	<script>
		var eventColorCode ='';
		$.modalEventSet = <?php echo getSettingValueByKey($ExpertDetails->Provider,'CALENDAREVENTSETTINGS',$userDetails->Organization_Id)->Extended_Value; ?>;
		if($.modalEventSet.hasOwnProperty('color')){
			eventColorCode = $.modalEventSet.color;
		}
		$.fn.editable.defaults.mode = 'inline';
		var popupButtons = <?php echo getSettingValueByKey($ExpertDetails->Provider,'CALPOPOVERBUTTONS',$userDetails->Organization_Id)->Extended_Value; ?>;
		var mouseX, mouseY, windowWidth, windowHeight;
		var popupLeft, popupTop;
		var allowPastDayBooking = <?php echo getSettingValueByKey($ExpertDetails->Provider,'CALENDARALLOWPASTBOOKING',$userDetails->Organization_Id)->Value1; ?>;
		var defaultCusType = '<?php echo getSettingValueByKey($ExpertDetails->Provider,'CALDEFAULTCLIENTTYPE',$userDetails->Organization_Id)->Value1; ?>';
		$.modalstatusIndicator == 'BORDER'
		var statusSource = [];
		 if($.modalStatus){
			for(key in $.modalStatus){
				statusSource.push({
					value : key,
					text :key
				});
			}
		}
		var dateFormat ='dd/mm/yy';
		var account= '<?php echo $ExpertDetails->Provider;?>';
		var eventType='WORKINGHOUR';
		var CUSTOMEROBJECT='CONTACT';
		var appStatus = '<?php echo getSettingValueByKey($ExpertDetails->Provider,'CALENDARAPPOINTEMENTSTATUS',$userDetails->Organization_Id)->Value1; ?>';
		var $SelectedService = '';
		var $serviceName='';  
		var $provider = '<?php echo $ExpertDetails->Provider;?>';
			var date = new Date();	  
			  var d = date.getDate();
			  var m = date.getMonth();
			  var y = date.getFullYear();

			  var calendar = $('#calendar').fullCalendar({
			   editable: true,
			   header: {
				left: 'agendaWeek,agendaDay',
				center: 'Today,prev,title,next',
				right: ''
			   },
			   height: 999999999,
				slotDuration: '00:15:00',
			   defaultView:'agendaWeek',
				events: '<?php echo base_url();?>booking/expert/getExpertAppointments',
				defaultDate: calendarGoToDate,
		   // Convert the allDay from string to boolean
		   eventRender: function(event, element, view) {
			if (event.allDay === 'true') {
			 event.allDay = true;
			} else {
			 event.allDay = false;
			}
		   },
		   selectable: true,
		   selectHelper: true,
		   select: function(start, end, allDay) {
				   $('#exceptionPanel').html('');
					var check = start.format('YYYY-MM-DD');
					var today = moment(new Date()).format('YYYY-MM-DD');
					if(!(check < today) || allowPastDayBooking)
					{
						debugger;
						 var isFree = true;
						if(!isFree){
							$( "#freeTime-confirm" ).dialog({
								  resizable: false,
								  height:140,
								  modal: true,
								  buttons: {
									"Ok": function() {
									  $( this ).dialog( "close" );
										return false;
									}
								  }
							  });
						}else{

						$('#createAppLoading').addClass('active');
						 if(defaultCusType == 'NEW')
							$('#existingCustomerName').attr('disabled',true);
						 else if(defaultCusType == 'EXISTING')
							$('#existingCustomerName').attr('disabled',false);
						$("#createAppointmentForm").trigger('reset');

						var selectedSlotStart=start._d;
						$('#selectedDateTime').datepicker("setDate",start._d);
					$('#createEventModal').modal({
									backdrop: 'static',
									keyboard: false  // to prevent closing with Esc button (if you want this too)
								});
					setTimeout(function(){
					 $('#createAppLoading').removeClass('active');
					 $(':radio[value="'+defaultCusType+'"]').prop('checked', true);
					 }, 1000);
				  }
				}
		   },
		   eventAfterRender: function(event, element, view ) {
				if(event.serviceName != 'Break Hours'){
					  if($.modalStatus.hasOwnProperty(event.status)){
									if($.modalStatus[event.status].hasOwnProperty('vc')){
										if($.modalStatus[event.status].vc == false){
											var eventId = [];
											eventId.push(event.id);
											$('#calendar').fullCalendar( 'removeEvents', eventId );
										}
						 }
					 }
					  if($.modalStatus.hasOwnProperty(event.status) && event.hasMultiSlot == false){
						  $(element).find('.fc-event-inner').css({
								borderTop:'10px solid '+ $.modalStatus[event.status].color+'',
							});
						 }
					 if($.modalEventSet.hasOwnProperty('UI')){
						if($.modalEventSet['UI'].hasOwnProperty('DATETIMELABEL')){
								var timeValue = $(element).find('.fc-event-inner .fc-event-time').html();
								$(element).find('.fc-event-inner .fc-event-time').html('');
								$(element).find('.fc-event-inner .fc-event-time').html($.modalEventSet['UI'].DATETIMELABEL+''+timeValue);
						}else{
							$(element).find('.fc-event-inner .fc-event-time').remove();
						}
						if($.modalEventSet['UI'].hasOwnProperty('CONTACTNAMELABEL')){
								var contactValue = $(element).find('.fc-event-inner .fc-event-title').html();
								$(element).find('.fc-event-inner .fc-event-title').html('');
								$(element).find('.fc-event-inner .fc-event-title').html($.modalEventSet['UI'].CONTACTNAMELABEL+''+contactValue);
						}else{
							$(element).find('.fc-event-inner .fc-event-title').remove();
						}
					}
					if(event.hasMultiSlot == true){
						$(element).find('.fc-event-inner .fc-event-title').remove();
						$(element).css({
									background :'#00781C',
									borderColor:'#00781C',
									color : eventColorCode,
								});
					}
					}
		   },
		   eventClick: function(calEvent, jsEvent, view) {
						$('#tooltipLoading').addClass('active');
						if(calEvent.title == undefined)
							calEvent.title = '';
						if(calEvent.email  == undefined)
							calEvent.email = '';
						selectedContactName = calEvent.title;
						selectedEventStart =  calEvent.start._d;
						 var buttons ='';
						 buttons +='<div class="ui-group-buttons">';
						 for(var i=0;i< popupButtons.length;i++){ 
						  if(popupButtons[i].hasOwnProperty('type')){
							   if(popupButtons[i].type == 'standardedit' && calEvent.hasMultiSlot == false)               
								 buttons +='<button class="action-button btn2 "  id="editDet" title="Edit" eventId="' + calEvent.id + '" eventURL="' + calEvent.popurl + '"><i class="fa fa-eye"></i></button>';
							   if(popupButtons[i].type == 'standardedit' && calEvent.hasMultiSlot == true)               
								 buttons +='<button class="action-button btn2 "  id="editMultiDet" eventId="' + calEvent.id + '" eventURL="' + calEvent.popurl + '"><i class="fa fa-eye"></i></button>';
							   //if(popupButtons[i].type == 'standardreschedule' && calEvent.hasMultiSlot == false)     
							   //	buttons +=' <button class="action-button btn2 " title="Reschedule" eventStart="'+moment(calEvent.start).format(''+$.modaldateFormat+'')+'" expertId ="'+calEvent.expertId+'" id="editReSche" providerId="' + calEvent.provider + '" serviceId="' + calEvent.service + '" eventId="' + calEvent.id + '"><i class="fa fa-share"></i></button>';
							   //if(popupButtons[i].type == 'standardnew' && calEvent.hasMultiSlot == false)   
								//buttons +=' <button class="action-button btn2 " title="New Appointment" id="newApp" conId="' + calEvent.cusId + '" userId="' + calEvent.userId + '" expertId ="'+calEvent.expertId+'" serviceId="' + calEvent.service + '" isPreferredExpert="'+calEvent.isPreferredExpert+'"><i class="fa fa-plus-circle"></i></button>';
							   //else if(popupButtons[i].type == 'standardnew' && calEvent.hasMultiSlot == true)  
								//buttons +=' <button class="action-button btn2 " title="New Appointment" id="newApp" conId="isMultiSlot" expertId ="'+calEvent.expertId+'" isPreferredExpert="'+calEvent.isPreferredExpert+'" serviceId="' + calEvent.service + '"><i class="fa fa-plus"></i></button>';

							   if(popupButtons[i].type == 'custom' && calEvent.status != 'Private') 
								buttons +='<a  target="'+popupButtons[i].target+'" title="'+popupButtons[i].title+'" href="'+popupButtons[i].launchURL+'?id='+calEvent.id+'"><button role="button" class="action-button btn2 "><i class="'+popupButtons[i].icon+'"></i></button></a>';
						  }
						 }
						  buttons +=' </div>';
						 if(calEvent.hasMultiSlot == false)
							  tooltip = '<div class="headTime"><span class="miniStatusBlock"><span class="miniAppStatusColor"><i class="fa fa-check" ></i></span>&nbsp;&nbsp;<a href="#" id="status" data-title="Change status" data-type="select" data-pk="'+calEvent.id+'"></a></span></div><div class="detailsPat"><span  ><b>'+calEvent.start.format('hh:mm a')+' - '+calEvent.end.format('hh:mm a')+'</b></span><span  ><a style="color:#08c;" target="'+$.modalLinkTarget+'" href="/'+calEvent.cusId+'">' + calEvent.title + '</a></span><span >'+calEvent.serviceName+'</span></div><div class="action-group">'+buttons+'</div>';
						 if(calEvent.hasMultiSlot == true)
							tooltip = '<div class="headTime"><b>' + calEvent.serviceName + '</b></div><div class="detailsPat"><span><b>'+calEvent.start.format('hh:mm a')+' - '+calEvent.end.format('hh:mm a')+'</b></span><span  style="display:inline-block;"><div class="panel booked-status panel-danger" style="margin-bottom:0px;"><div class="panel-heading" style="text-align:center;"><h1 class="panel-title">' + calEvent.availableCount + '</h1></div><div class="panel-body"><strong>AVAILABLE</strong></div></div></span><span style="display:inline-block;"><div class="panel booked-status panel-danger" stye="margin-bottom:0px;"><div class="panel-heading " style="text-align:center;"><h1 class="panel-title text-center">'+calEvent.bookedCount + '</h1></div><div class="panel-body"><strong>BOOKED</strong></div></div></span></div><div class="action-group">'+buttons+'</div>';				

						document.getElementById('des').innerHTML = tooltip;
						if($.modalStatus.hasOwnProperty(calEvent.status)){
							$('.miniAppStatusColor').css({
								background :''+ $.modalStatus[calEvent.status].color+'',
								color : '#FFF',
							 });
						 }
						 $('#status').editable({
							value: calEvent.status,  
							savenochange :true,
							showbuttons :false,
							url: function(params) {
								var d = new $.Deferred;
								if(params.pk === '' && params.pk === null && params.pk === undefined){
									return d.reject('error in update'); //returning error via deferred object
								} else {
									//async saving data in js model
									$.ajax({
									  url: "<?php echo base_url();?>booking/expert/changeAppoinmentStatus?appId="+params.pk+"&status="+params.value,
									  beforeSend: function( xhr ) {
										//xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
									  }
									})
									  .done(function( data ) {
										if ( console && console.log ) {
										  console.log( "Sample of data:", data.slice( 0, 100 ) );
										}
										data = $.parseJSON(data);
									   if(data.Success){
										 d.resolve();
										if($.modalStatus.hasOwnProperty(params.value)){
												$('.miniAppStatusColor').css({
													background :''+ $.modalStatus[params.value].color+'',
													color : '#FFF',
											 });
										 }
										 $('#miniEdit-button').removeClass('fa-chevron-up');
										 $('#miniEdit-button').addClass('fa-chevron-down');
										 refreshCalendar();
										  d.resolve();
										 }else{
											return d.reject('error in update'); //returning error via deferred object
										 }
									  });					       
									return d.promise();
								}
							},
							source: statusSource
						});
					$('.tooltipevetn').css('z-index', 10000);
					$('.tooltipevetn').fadeIn('500');
					$('.tooltipevetn').fadeTo('10', 1.9);
					var popupWidth = $('.tooltipevetn').outerWidth();
					var popupHeight = $('.tooltipevetn').outerHeight();

					if (mouseX + popupWidth > windowWidth)
						popupLeft = (mouseX - popupWidth);
					else
						popupLeft = mouseX;

					if (mouseY + popupHeight > windowHeight)
						popupTop = (mouseY - popupHeight) + 8;
					else
						popupTop = mouseY - 10;

					if (popupLeft < $(window).scrollLeft()) {
						popupLeft = $(window).scrollLeft();
					}

					if (popupTop < $(window).scrollTop()) {
						popupTop = $(window).scrollTop();
					}

					if (popupLeft < 0 || popupLeft == undefined)
						popupLeft = 0;
					if (popupTop < 0 || popupTop == undefined)
						popupTop = 0;

					$('.tooltipevetn').offset({
						top: popupTop,
						left: popupLeft
					});
					$('#tooltipLoading').removeClass('active');
				},
		   eventRender : function(event, element, view) {
				if(event.serviceName != 'Break Hours'){
					if($.modalEventSet.hasOwnProperty('UI')){
							if($.modalEventSet['UI'].hasOwnProperty('SERVICENAMELABEL')){
								$(element).find('.fc-event-inner').append('<div class="fc-event-service">'+$.modalEventSet['UI'].SERVICENAMELABEL+''+event.serviceName+'</div>');
							}
					}
					if($.modalStatus.hasOwnProperty(event.status)){
							 if($.modalstatusIndicator == 'BORDER'){
									$(element).css({
										background :'#FFF',
										borderColor:''+ $.modalStatus[event.status].color+'',
										color : '#000',
									});
							 }else{
								 $(element).css({
										background :''+ $.modalStatus[event.status].color+'',
										borderColor:''+ $.modalStatus[event.status].color+'',
										color : '#000',
									});
							 }
						}if($.modalStatus.hasOwnProperty(event.status)){
							$(element).css({
										background :'#B9B9B2 !important',
										borderColor:'black !important',
									});
								}
					}	 
		   },
			eventMouseout: function(calEvent, jsEvent) {
				$('.tooltipevetn').hide();
			},
		   editable: false,
		   allDaySlot:false	   
		  });
		  var calendarIcon='&nbsp;&nbsp;<span class="fc-button fc-button-calendar fc-state-default " unselectable="on" style="margin-left: -3px;border: 0px;"><span class="fa fa-sort-desc" style="margin: 0 .1em;font-size: 1.0em;margin-top: 4px;">&nbsp;</span></span>';
			$('.fc-header-title').after(calendarIcon);
			$('.fc-button-calendar').datetimepicker({
							format: 'Y/m/d',
							timepicker:false,
							closeOnDateSelect:true,
							defaultDate : calendarGoToDate,
							onChangeDateTime:function(dp,$input){
								var selDate = new Date($input.val());
							   if(selDate != "Invalid Date"){
									defaultDate = selDate;
									calendar.fullCalendar( 'gotoDate', selDate) ;;
							   }
							  }
						});
		  $( "#existingCustomerName" ).autocomplete({
				source: function(request, response) {
					$.ajax({
					url: "<?php echo base_url(); ?>booking/expert/contactSearch/",
					data: { term: $("#existingCustomerName").val()},
					dataType: "json",
					type: "POST",
					success: function(data){
						response( $.map(data, function(item){
							return item;
						}));
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {
				$("#existingCustomerName" ).val( ui.item.LastName );
				  $('[id$=FirstName]').val(ui.item.FirstName);
				  $('[id$=LastName]').val(ui.item.LastName);
				  $('[id$=Street]').val(ui.item.Street);
				  $('[id$=City]').val(ui.item.MailingCity);
				  $('[id$=Country]').val(ui.item.MailingCountry);
				  $('[id$=State]').val(ui.item.MailingState);
				  $('[id$=ZipCode]').val(ui.item.MailingPostalCode);
				  $('[id$=Email]').val(ui.item.Email);
				  $('[id$=Phone]').val(ui.item.Phone);
				  $('[id$=selCusID]').val(ui.item.Salesforce_Id);
				return false;
			  }
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
		  return $( "<li>" )
			.append( "<a>" + item.LastName + "<br>" + item.Email + "</a>" )
			.appendTo( ul );
		};
		$(document).on('change','input[name="existing"]',function() {
			var selected = $(this).val();
			  $('[id$=selCusID]').val('');
			if(selected == 'EXISTING')
			{
					$('#existingCustomerName').attr('disabled',false);
					$('#createAppointmentForm input[type=text],#createAppointmentForm textarea').not('#selectedDateTime').val('');
			}
			else if(selected == 'NEW'){
					$('#existingCustomerName').attr('disabled',true);
					$('#createAppointmentForm input[type=text],#createAppointmentForm textarea').not('#selectedDateTime').val('');
				}
		});
			  //Event Detail PopUp Functions
		$(document).mousemove(function(e) {
				mouseX = e.pageX;
				mouseY = e.pageY;
				//To Get the relative position
			if (this.offsetLeft != undefined)
				mouseX = e.pageX - this.offsetLeft;
			if (this.offsetTop != undefined)
				mouseY = e.pageY; - this.offsetTop;

			if (mouseX < 0)
				mouseX = 0;
			if (mouseY < 0)
				mouseY = 0;

			windowWidth = $(window).width() + $(window).scrollLeft();
			windowHeight = $(window).height() + $(window).scrollTop();
		});
			 if(window.$BKSL == undefined || window.$BKSL == null) window.$BKSL = {};
				 $BKSL.utils = {
					 getTimeValue : function(time){
						 var response = 0;
						 var strTime = time.split(" ");
						 var timeVal = strTime[0].split(":");
						 if(strTime[1] == 'PM' && timeVal[0] != '12')
							 response+=12*60;
						 else if(strTime[1] == 'AM' && timeVal[0] == '12')
							 timeVal[0] = 0;
						 response+=parseInt(timeVal[0])*60;
						 response+=parseInt(timeVal[1]);
						 return response;
					 },

					 timeinhhmm : function(time){
						 var arrSlot = time.split(/\s*[:_\s]\s*/);
						 if(arrSlot[0] != '12' && arrSlot[2] == 'PM')
							 arrSlot[0] = parseInt(arrSlot[0]) + 12;
						 if(arrSlot[3] != '12' && arrSlot[5] == 'PM')
							 arrSlot[3] = parseInt(arrSlot[3]) + 12;
						 return arrSlot[0] + '_' + arrSlot[1] + '_' + arrSlot[3] + '_' + arrSlot[4];
					 }
				 }
				function getAvailableSlot() {
					var $expert_Id = '<?php echo $ExpertDetails->Salesforce_Id;?>';
					$.ajax({
						  url: "<?php echo base_url();?>bksl/ajaxHandler/getExpertAvailability?ser="+$SelectedService+"&prv="+$provider+"&expert="+$expert_Id+"&org=<?php echo $userDetails->Organization_Id; ?>",
						  beforeSend: function( xhr ) {
						  }
						})
						  .done(function( data ) {
							if ( console && console.log ) {
							  //console.log($.parseJSON(data));
							}
							data = $.parseJSON(data);
							//console.log(data);
							var result = {
									availableSlots : data[Object.keys(data)[0]],
									duration :20
									};
							handleAvailableSlot(result);
						  });
				}
				  function isSlotAvailableforBooking(eventStart, eventEnd, slotStart, slotEnd) {
					 var eStart = $BKSL.utils.getTimeValue(eventStart), eEnd = $BKSL.utils.getTimeValue(eventEnd), sStart = $BKSL.utils.getTimeValue(slotStart), sEnd = $BKSL.utils.getTimeValue(slotEnd);
						 if(eventEnd == '12:00 AM')
							 eEnd = 1440;
						 if((eStart < sStart && eEnd > sStart) || (eStart < sEnd && eEnd > sEnd) || (eStart < sStart && eEnd > sEnd) || (eStart >= sStart && eEnd <= sEnd))
						 {
							 return false;
						 }
						 return true;
				  }
				   //Generate a perfect Slot for Appointment
					 function perfectSlot(time){
						 var arrSlot = time.split(/\s*[:_\s]\s*/);
						 if(arrSlot[0] != '12' && arrSlot[2] == 'PM')
							 arrSlot[0] = parseInt(arrSlot[0]) + 12;
						 if(arrSlot[3] != '12' && arrSlot[5] == 'PM')
							 arrSlot[3] = parseInt(arrSlot[3]) + 12;
						 if(arrSlot[0] == '12' && arrSlot[2] == 'AM')
							 arrSlot[0] = 00;
						 if(arrSlot[3] == '12' && arrSlot[5] == 'AM')
							 arrSlot[3] = 00;    
						 return arrSlot[0] + '_' + arrSlot[1] + '_' + arrSlot[3] + '_' + arrSlot[4];
					 }
				 function removeBookedSlot(slotJson,selectedDate){
					 debugger;
					 for (var k = 0; k < Object.keys(slotJson).length; k++) {
					  var avaiableSlot = [];
					 var selectedSlots =slotJson[Object.keys(slotJson)[k]];
					 var bookedSlot = {};
					  if (selectedDate == Object.keys(slotJson)[k]) {
						 if(selectedSlots != null)
						 {
						 for(index in selectedSlots) {
							 if(selectedSlots[index].isBookedSlot)
								 bookedSlot[selectedSlots[index].slot] = true;
						 }
						 for(index in selectedSlots) {
							 if(selectedSlots[index].slot && !selectedSlots[index].isBookedSlot)
							 {
								 var isSlotAvailable = true;
								 for(blockedindex in bookedSlot) {
									 var eventStart, eventEnd, slotStart, slotEnd;
									 var slotTime = (selectedSlots[index].slot).split("_");
									 var eventTime = (blockedindex).split("_");
									 if(!isSlotAvailableforBooking(eventTime[0], eventTime[1], slotTime[0], slotTime[1]))
									 {
										 isSlotAvailable = false;
										 break;
									 }
								 }
								 if(!isSlotAvailable){
										 continue;
								 }
								 avaiableSlot.push(selectedSlots[index]);
							 }
						 }
						 }
						 slotJson[Object.keys(slotJson)[k]] = avaiableSlot;
				 }
				 }
				 return slotJson;
				 }
				 var availableSlot;
				 function handleAvailableSlot(result){              
						  availableSlotsNew = result;
						  var isWaitingListAvailable = 0;
						 var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('D_M_YYYY');
						 var html ='';
						 var isSlotsAvailable = false;
						 if(result != null){
							 var availableSlots = result.availableSlots;
							 var serDuration = result.duration;
						   }
						 if(availableSlots !== null && availableSlots !== undefined){
							 availableSlots = removeBookedSlot(availableSlots,selectedDate);
								   html += '<option value="">Please Select a Slot</option>';
									  for (var k = 0; k < Object.keys(availableSlots).length; k++) {
										  var slots = availableSlots[Object.keys(availableSlots)[k]];
										  if(Object.keys(availableSlots)[k] == selectedDate){
											 isSlotsAvailable = true;
											 isWaitingListAvailable = slots.length;
											  for (var i = 0; i < slots.length; i++) {
												  html += '<option value="'+slots[i].slot+'">'+slots[i].slot.replace('_',' to ')+'</option>';
											  }
										  }
									  }
						 }
						 if(isSlotsAvailable == false  && isWaitingListAvailable == 0){
							 html ='';
							 html += '<option value="">Slots not available for the day</option>';
							 $('#availableSlots').html(html);
						 }else if(isSlotsAvailable == true  && isWaitingListAvailable == 0){
							 html ='';
							 html += '<option value="WAITINGLIST">WAITINGLIST</option>';
							 $('#availableSlots').html(html);
						 }else{
							 $('#availableSlots').html(html);
						  }
				 }
			 function buildSlots(result){
						 var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('D_M_YYYY');
						 var html ='';
						 var isSlotsAvailable = false;
						 if(result != null){
							 var availableSlots = result.availableSlots;
							 var serDuration = result.duration;
						 }
						 var isWaitingListAvailable = 0;
						 if(availableSlots != null && availableSlots != undefined){
							 availableSlots = removeBookedSlot(availableSlots,selectedDate);
								   html += '<option value="">Please Select a Slot</option>';
									  for (var k = 0; k < Object.keys(availableSlots).length; k++) {
										  var slots = availableSlots[Object.keys(availableSlots)[k]];
										  if(Object.keys(availableSlots)[k] == selectedDate){
											 isSlotsAvailable = true;
											 isWaitingListAvailable = slots.length;
											  for (var i = 0; i < slots.length; i++) {
												  html += '<option value="'+slots[i].slot+'">'+slots[i].slot.replace('_',' to ')+'</option>';
											  }
										  }
									  }
						 }
						 if(isSlotsAvailable == false  && isWaitingListAvailable == 0){
							 html ='';
							 html += '<option value="">Slots not available for the day</option>';
							 $('#availableSlots').html(html);
						 }else if(isSlotsAvailable == true  && isWaitingListAvailable == 0){
							 html ='';
							 html += '<option value="WAITINGLIST">WAITINGLIST</option>';
							 $('#availableSlots').html(html);
						 }else{
							 $('#availableSlots').html(html);
						 }
			 }
			function removeValidationMessages(){
				 $('.LV_validation_message').each(function() {
						 $(this).remove();

					 });
				 }
		   var availableSlotsNew='';
		   var reScheduleSlot = '';
		   $('#selectedDateTime').datepicker({
			     dateFormat : '<?php echo $ExpertDetails->Date_Format;?>',
				 onSelect: function(selectedDate) {
						var selService = $('#listService').val();
						if(selService  != '' && selService != null){
							$('#availableSlots').addClass('cascading-dropdown-loading');
							handleAvailableSlot(availableSlotsNew);
						}
				  },
			 })
			 $('#reScheduleDate').datepicker({
			     dateFormat : '<?php echo $ExpertDetails->Date_Format;?>',
				 onSelect: function(selectedDate) {
					handlereScheduleSlot(reScheduleSlot);
				  } 
				})
			 //get Duration on change of service while create appoitment   
			$(document).on('change', '#listService', function() {
					var serId = $(this).val();
					$('#availableSlots').addClass('cascading-dropdown-loading');
					 var html = '<option value="">Choose Date</option>';
					$('#availableSlots').html(html);
					  $SelectedService = serId;
					  $serviceName=$('#listService :selected').text();  
					  $provider = 'testdemo';
					if($SelectedService != '' && $SelectedService!=null)
						getAvailableSlot();
			});
			$(document).on('click','#bookAppointment',function(e){
					 e.preventDefault();
					 var serviceName = $serviceName;
					 var selectedSlot = $('#availableSlots').val();
					 var selslot = selectedSlot.replace('_',' to ')
					 $timeSlot = selslot;
					 var datepick = $('#selectedDateTime').val();
					 $appDate = datepick;  
					 var objRequest = {};
						objRequest['contactId'] = document.getElementById("selCusID").value;
						 //document.getElementById("bookApp").disabled = true;
						var selectedDate = moment($('#selectedDateTime').datepicker("getDate")).format('D_M_YYYY');
						objRequest['serviceId'] = $SelectedService;
						objRequest['serviceName'] = serviceName;
						objRequest['dateString'] = moment($('#selectedDateTime').datepicker("getDate")).format('YYYY-MM-DD');
						objRequest['provider'] = account;
						objRequest['LastName'] = document.getElementById("LastName").value;
						objRequest['FirstName'] = document.getElementById("FirstName").value;
						objRequest['Email'] = document.getElementById("Email").value;
						objRequest['Phone'] = document.getElementById("Phone").value;
						objRequest['City'] = document.getElementById("City").value;
						objRequest['Street'] = document.getElementById("Street").value;
						objRequest['State'] = document.getElementById("State").value;
						objRequest['ZipCode'] = document.getElementById("ZipCode").value;
						 objRequest['SELECTEDDATE'] = selectedDate;
						 if(selectedSlot == 'WAITINGLIST')
							 objRequest['slot'] = 'WAITINGLIST';
						 else
							 objRequest['slot'] = perfectSlot(selectedSlot);
						 objRequest['ObjType'] = 'CONTACT';
						 objRequest['OrgId'] = '<?php echo $userDetails->Organization_Id;?>';
						 objRequest['EXPERT'] = '<?php echo $ExpertDetails->Salesforce_Id;?>';
						 objRequest['appointmentStatus'] = appStatus; 
						bookAppointment(objRequest);
				 });
			 //Create new Appointment Request
			 function bookAppointment(objRequest) {
				 $('#bookAppointment').attr('disabled',false);
				 var postData = objRequest; 
				 $.ajax({
				  url: "<?php echo base_url();?>bksl/ajaxHandler/expertBookAppointment",
					type: 'post',
					dataType: 'json',
					data: postData,	  
				  beforeSend: function( xhr ) {
				  }
				})
				  .done(function( data ) {
					//if ( console && console.log ) {
					  //console.log($.parseJSON(data));
					 $("#createEventModal").modal('hide');
					  refreshCalendar();
					//}

				  });
			 }
			 //Event Edit Click function (EDIT Button)
			$(document).on('click', '#editDet,#viewDet', function() {
				$('.tooltipevetn').hide();
				var appId = $(this).attr('eventId');
				$('#serReqDetails .modal-footer button').show();
				if(appId != null && appId != undefined){
					$('#noteContent').html('');
					getAppointmentDetails(appId);
					//$('#status').editable('toggle');
				}
			});
			//Event Edit Click function (EDIT Button)
			$(document).on('click', '#editMultiDet', function() {
				$('.tooltipevetn').hide();
				var appId = $(this).attr('eventId');
				if(appId != null && appId != undefined){
					$('#noteMultiContent').html('');
					getMultiAppointmentDetails(appId);
				}
			});
			//get Appointment Details
		function getAppointmentDetails(appId) {
			$.ajax({
				  url: "<?php echo base_url();?>booking/expert/getAppointmentDetailsById?appId="+appId,
					type: 'get',
					dataType: 'json',
					beforeSend: function( xhr ) {

					  }
					})
				  .done(function( data ) {
						handlAppDetails(data);
				  });
		}
		function handlAppDetails(result){
			if(result != null){
				debugger;
				var StartTime = moment(result.startTime, "YYYY-MM-DD HH:MM:SS");
				StartTime = moment(StartTime._d);
				var endTime = moment(result.endTime, "YYYY-MM-DD HH:MM:SS");
				endTime = moment(endTime._d);
				if(result.serName != null && result.serName != undefined)
					$("#appServiceName").html(result.serName);
				if(result.startTime != null && result.startTime != undefined)
					$("#appStartDate").html(StartTime.format('DD/MM/YYYY'));
				if(result.startTime != null && result.startTime != undefined)
					$("#appStartTime").html(StartTime.format('hh:mm A'));
				if(result.endTime != null && result.endTime != undefined)
					$("#appEndTime").html(endTime.format('hh:mm A'));
				$("#appSelStatus").val(result.status);
				$("#appSelStatus").attr('title','Change status');
				$("#appSelStatus").attr('type','select');
				$("#appSelStatus").attr('pk',result.Id);
				$('#appointmentStatus').html(result.status);
				if($.modalStatus.hasOwnProperty(result.status)){
					$('.appStatusColor').css({
						background :''+ $.modalStatus[result.status].color+'',
						color : '#FFF',
					 });
				 }
				if(result.cName !=null && result.cName != undefined)
				  $('#appContactName').html('<a href="/'+result.cusId+'" >'+result.cName+'</a>');
				var address = '';
				if(result.cStreet != undefined && result.cStreet != null)
					address = result.cStreet +',';
				if(result.cCity != undefined && result.cCity != null)
						address = address+' '+result.cCity +'<br/>';
				if(result.cState != undefined && result.cState != null){
					if(result.cZipCode != '' && result.cZipCode != undefined)
						address = address+' '+result.cState +'-'+result.cZipCode;
					else
						address = address+' '+result.cState;
				}
				if(result.cEmail != undefined && result.cEmail != null)
					$('#contactEmail').html('<label>Email <a href="mailto:'+result.cEmail+'">'+result.cEmail+'</a></label>');
				if(result.cPhone != undefined && result.cPhone != null)
					$('#contactPhone').html('<label>Phone '+result.cPhone+'</label>');
			   $('#contactAddress').html(address);
				$('#appointmentStatus').editable({
					value: result.status,  
					savenochange :true,
					showbuttons :false,
					url: function(params) {
						var d = new $.Deferred;
						if(params.value === '' && params.value === null && params.value === undefined){
							return d.reject('error in update'); //returning error via deferred object
						} else {

								   $.ajax({
									  url: "<?php echo base_url();?>booking/expert/changeAppoinmentStatus?appId="+params.pk+"&status="+params.value,
									  beforeSend: function( xhr ) {
										//xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
									  }
									})
									  .done(function( data ) {
										if ( console && console.log ) {
										  console.log( "Sample of data:", data.slice( 0, 100 ) );
										}
										data = $.parseJSON(data);
										 if(data.Success){
											$("#appSelStatus").val(params.value);
											if($.modalStatus.hasOwnProperty(params.value)){
												$('.appStatusColor').css({
													background :''+ $.modalStatus[params.value].color+'',
													color : '#FFF',
											   });
												$('#edit-button').removeClass('fa-chevron-up');
												$('#edit-button').addClass('fa-chevron-down');
											 }
											 d.resolve();
											 refreshCalendar();
										 }else{
											return d.reject('error in update'); //returning error via deferred object
										 }

									  });					       
									return d.promise();
						}
					},
					source: statusSource
				});
				$('#appointmentStatus').editable('hide');
				$("#appId").val(result.id);
				if(result.note != null && result.note != undefined)
					$("#appNotes").val(result.note);
				else
					$("#appNotes").val('');
		  if(result != null){
			if(result.serReqHistory != null){
				var html='';
				var serReqHistory = result.serReqHistory;
				for(var i=0;i<serReqHistory.length;i++){
					html+='<article class="timeline-entry">';
					html+='<div class="timeline-entry-inner">';
					var bgcolor = '#000;';
					if($.modalStatus){
						for(key in $.modalStatus){
							if($.modalStatus.hasOwnProperty(serReqHistory[i].Status)){
								bgcolor = $.modalStatus[serReqHistory[i].Status].color;
							}
						}
					}
					html+='<div class="timeline-icon bg-'+serReqHistory[i].Status.replace(' ','-').toLowerCase()+'" style="background:'+bgcolor+'" >';
					html+='<i class="entypo-feather"></i>';
					html+='</div>';
					html+='<div class="timeline-label">';
					html+='<h2><a target="#" href="/'+serReqHistory[i].Salesforce_Id+'">SERVICE:&nbsp;'+serReqHistory[i].Name+'</a></h2>';
					if(serReqHistory[i].Critical_Notification != null && serReqHistory[i].Critical_Notification != undefined)
						html+='<p>'+serReqHistory[i].Critical_Notification+'</p>';
					html+='<p>Time:'+serReqHistory[i].Start_Date+'</p>';
					html+='</div></div></article>';
				}
				 $('#serReqTimeline').html(html);
			}
			}
				$('#customerRecord a:first').tab('show')
				$("#serReqDetails").modal({
								backdrop: 'static',
								keyboard: false  // to prevent closing with Esc button (if you want this too)
							});
			}
		   }
		   $(document).on('click','#editReSche', function(e) {
			 var serReqId = $(this).attr('eventId');
			 var serviceId = $(this).attr('serviceId');
			 providerId = $(this).attr('providerid');
			 $('.tooltipevetn').hide();
			 $('#serReqId').val(serReqId);
			 $('#reScheduleDate').val($(this).attr('eventStart'));
			 $('#nextSlot').html('');
			 $('#prevSlot').html('');
			$('#reScheduleDate').datepicker('setDate',new Date());

				 if(serviceId != null && serviceId != undefined){
					getreScheduleSlot(serviceId,providerId);
					 $('#reScheduleModal').modal({
									backdrop: 'static',
									keyboard: false  // to prevent closing with Esc button (if you want this too)
								});
				 }
		 });
		 function getreScheduleSlot(serviceId,provider) {
			var $expert_Id = '<?php echo $this->session->userdata('expertSFId');?>';
			$.ajax({
				  url: "<?php echo base_url();?>bksl/ajaxHandler/getExpertAvailability?service="+serviceId+"&provider="+provider+"&expert="+$expert_Id,
				  beforeSend: function( xhr ) {
				  }
				})
				  .done(function( data ) {
					if ( console && console.log ) {
					  //console.log($.parseJSON(data));
					}
					data = $.parseJSON(data);
					//console.log(data);
					var result = {
							availableSlots : data[Object.keys(data)[0]],
							duration :20
							};
					handlereScheduleSlot(result);
				  });
		}
		 function handlereScheduleSlot(result){
		   reScheduleSlot = result;
		   var selectedDate = moment($('#reScheduleDate').datepicker('getDate')).format('D_M_YYYY');
		   var html ='';
		   var isSlotsAvailable = false;
		   var isWaitingListAvailable=0;
		   var availableSlots = result.availableSlots;
		   serDuration = result.duration;
		   var waitingList = result.isWaitingListAvailable;
		   if(availableSlots != null && availableSlots != undefined){
			   availableSlots = removeBookedSlot(availableSlots,selectedDate);
					 html += '<option value=""> Select a Slot</option>';
						for (var k = 0; k < availableSlots.length; k++) {
							var slots = availableSlots[k].slots;
							if(availableSlots[k].strDate == selectedDate){

							   isSlotsAvailable = true;
							   isWaitingListAvailable = slots.length;
								for (var i = 0; i < slots.length; i++) {
									html += '<option value="'+slots[i].slot+'">'+slots[i].slot.replace('_',' to ')+'</option>';
								}
							}
						}
		   }
		   var datenotSelected = false;
		   if($('#reScheduleDate').val() == ''){
				datenotSelected = true;
		   }
		  if(isSlotsAvailable == false  && isWaitingListAvailable == 0){
			   html ='';
			   if(datenotSelected == true){
					html += '<option value="">Choose Date</option>';
			   }
			   /*else{
					var selExp = $('#reScheduleExperts').val();
					var selectedDate = moment($('#reScheduleDate').datepicker("getDate")).format('D_M_YYYY');
					if(selExp != null && selExp != '' && selExp != undefined)
						 //getAvailableSlotByDate(reSchService,eventType,providerId,selExp,selectedDate,'RESCHEDULE');
			   }*/
		 }else if(isSlotsAvailable == true  && isWaitingListAvailable == 0){
			   html ='';
				if(datenotSelected == true){
					html += '<option value="">Choose Date</option>';
				}else{
				   if(waitingList == true)
					 html += '<option value="WAITINGLIST">WAITINGLIST</option>';
				   else
					 html += '<option value="">Slots not available for the day</option>';
				}
			   $('#reScheduleSlots').html(html);
		   }else{
			   $('#reScheduleSlots').html(html);
			}
			 $('#reScheduleSlots').attr('disabled',false);
			 $('#reScheduleSlots').removeClass('cascading-dropdown-loading');
			$('#reScheduleLoading').removeClass('active');
	   }
	   $(document).on('mouseenter', '.tooltipevetn', function(e) {
			$(this).show();
		});
		$(document).on('mouseleave', '.wc-cal-event,.wc-container,.wc-scrollable-grid', function(e) {
			$('.tooltipevetn').hide();
		}); 
		$(document).on('mouseenter', '.wc-container,.wc-scrollable-grid', function(e) {
		   $('.tooltipevetn').hide();
		});
		var calendarGoToDate = new Date();
		function refreshCalendar(){
			calendarGoToDate = calendar.fullCalendar( 'getDate' )._d;
		   calendar.fullCalendar( 'refetchEvents' );	
			calendar.fullCalendar( 'gotoDate', calendarGoToDate);
		}
  	</script>
	</div><!-- /col-9 -->
</div><!-- /padding -->