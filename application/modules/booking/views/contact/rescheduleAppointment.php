<link href="<?php echo  base_url(); ?>css/jquery-ui.min.css" rel="stylesheet">
<div class="box box-success" id="dateandtime">
	<form id="bookAppointment">
	<div class="box-header">
		<h3 class="box-title">Choose Date and Time</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="choosenService"></button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12">
					<div class="form-group">
						<label>Date</label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control pull-right" id="datepicker">
						</div><!-- /.input group -->
					</div>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
						<label>Time</label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-clock-o"></i>
							</div>
							<select class="form-control" id="availableSlots">

							</select>
						</div>
					</div>
				</div>
		</div>
	</div><!-- /.box-body -->
	<div class="box-footer">
		<center><button class="btn btn-primary btn-lg" id="bookApp" type="submit">Schedule Now</button></center>
	</div>
	</form>
</div>
	<script src='<?php echo base_url(); ?>js/lib/jquery-ui.min.js'></script>
    <script src='<?php echo base_url(); ?>js/lib/moment.min.js'></script>
<script>
        var eventType='WORKINGHOUR';
        var CUSTOMEROBJECT='CONTACT';
  	    var $SelectedService = '<?php echo $app->Service_Id; ?>';
  		var $serviceName='<?php echo $app->Name; ?>';  
  		var $provider = '<?php echo $app->Provider; ?>';
  		getAvailableSlot();
        var appStatus = '';
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
				$('#choosenService').html($serviceName);
            	$.ajax({
	           		  url: "<?php echo base_url();?>bksl/ajaxHandler/getServiceAvailability?ser="+$SelectedService+"&prv="+$provider+"&org=<?php echo $ContactDetails->Organization_Id; ?>",
            		  beforeSend: function( xhr ) {
            		  }
            		}).done(function( data ) {
            		    if ( console && console.log ) {
            		      //console.log($.parseJSON(data));
            		    }
            		    data = $.parseJSON(data);
            		    console.log(data);
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
                     availableSlot = result;
                     var isWaitingListAvailable = 0;
                    var selectedDate = moment($('#datepicker').datepicker('getDate')).format('D_M_YYYY');
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
                    var selectedDate = moment($('#datepicker').datepicker('getDate')).format('D_M_YYYY');
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
	  function showListing(){
		$('#serviceListing').show();
		$('#bookAppointment').trigger('reset');
		$('#datepicker').datepicker('setDate',new Date());
		$('#dateandtime').hide();
		removeValidationMessages();
	  }
	  var $timeSlot='';
	  var $appDate='';
	  //Book Appointment function on Submit
        $(document).on('submit','#bookAppointment',function(e){
                e.preventDefault();
                var serviceName = $serviceName;
                var selectedSlot = $('#availableSlots').val();
                var selslot = selectedSlot.replace('_',' to ')
                $timeSlot = selslot;
                var datepick = $('#datepicker').val();
                $appDate = datepick;  
                var objRequest = {};
                objRequest['contactId'] = "<?php echo  $this->session->userdata('contactSFId');?>";
                objRequest['Id'] =  "<?php echo  $app->Id;?>";
                objRequest['srqId'] =  "<?php echo  $app->Salesforce_Id;?>";
                if (objRequest['contactId'] != null) {
                    document.getElementById("bookApp").disabled = true;
                    var selectedDate = moment($('#datepicker').datepicker("getDate")).format('D_M_YYYY');
                    objRequest['serviceId'] = $SelectedService;
                    objRequest['serviceName'] = serviceName;
                    objRequest['dateString'] = moment($('#datepicker').datepicker("getDate")).format('YYYY-MM-DD');
                    objRequest['provider'] = $provider;
                    objRequest['SELECTEDDATE'] = selectedDate;
                    if(selectedSlot == 'WAITINGLIST')
                        objRequest['slot'] = 'WAITINGLIST';
                    else
                        objRequest['slot'] = perfectSlot(selectedSlot);
                    objRequest['ObjType'] = 'CONTACT';
                    objRequest['appointmentStatus'] = 'Pending'; 
                    objRequest['EXPERT'] = '';
					objRequest['OrgId'] = '<?php echo $ContactDetails->Organization_Id; ?>';
					objRequest['contactId'] = '<?php echo $ContactDetails->Salesforce_Id; ?>';
                   bookAppointment(objRequest);
                }
            });
        function bookAppointment(objRequest) {
            $('#bookApp').attr('disabled',false);
            var postData = objRequest; 
            $.ajax({
      		  url: "<?php echo base_url();?>bksl/ajaxHandler/rescheduleAppointment",
      			type: 'post',
      			dataType: 'json',
      		    data: postData,	  
      		  beforeSend: function( xhr ) {
      		  }
      		})
      		  .done(function( data ) {
      		    //if ( console && console.log ) {
      		      //console.log($.parseJSON(data));
      		      window.location ='<?php echo base_url();?>booking/';
      		    //}
      		    
      		  });
        }
        function handleBookResult(result,event){
            if(event.type === 'exception') {
                console.log("exception");
                console.log(event);   
				var html = '';
                    html +='<div class="alert alert-danger" role="alert"><center>'+event.message+'</center></div>';
					$('#appointmentStatus').html(html);
           } else if(event.status) {
                console.log(event);
                if(!result.success){
                    console.log(result);
					var html = '';
                    html +='<div class="alert alert-danger" role="alert"><center>'+result.message+'</center></div>';
					$('#appointmentStatus').html(html);
                }else{
                  var html = '';
                    html +='<div class="alert alert-success" role="alert"><center>Thank you. <span>Your Appointment is Scheduled!</span></center></div>';
					html +='<h2 class="thankyou-heading">Your Appointment Details:</h2>';
					html +='<div class="appt-info">';
					html +='<h6>Confirmation Number:<span> '+result.quickResponse+'</span></h6>';
					html +='<h6>Service:<span> '+$serviceName+'</span></h6>';
					html +='<h6>Scheduled Date:<span> '+$appDate+'</span></h6>';
					html +='<h6>Scheduled Time:<span> '+$timeSlot+'</span></h6>';
					html +='</div>';
					$('#appointmentStatus').html(html);
                }
           } else {
              console.log(event.message);
             var html = '';
				html +='<div class="alert alert-danger" role="alert"><center>'+event.message+'</center></div>';
				$('#appointmentStatus').html(html);
           }
		   	$('#serviceListing').hide();
			$('#dateandtime').hide();
			$('#confirmation').show();
        }
	  $(document).ready(function() {
            $('#datepicker').datepicker({
                  dateFormat: 'dd/mm/yy',
                	onSelect: function(dateText) {
                   	 buildSlots(availableSlot);
                   }
               // minDate: -{!allowedPastDays}, maxDate: +{!allowedFutureDays}
            });
            $('#datepicker').datepicker('setDate',new Date());
        });
    </script>