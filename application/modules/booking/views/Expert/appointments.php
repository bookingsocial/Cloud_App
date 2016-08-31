<link href="<?php echo  base_url(); ?>css/bootstrap-formhelpers.min.css" rel="stylesheet">
<link href="<?php echo  base_url(); ?>css/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo  base_url(); ?>css/screen.css" rel="stylesheet">
<style>
.list {
  font-family:sans-serif;
  margin:0;
  padding:20px 0 0;
}
.list > li {
  display:block;
  background-color: #eee;
  padding:10px;
  cursor:pointer;
  box-shadow: inset 0 1px 0 #fff;
}
.avatar {
  max-width: 150px;
}
img {
  max-width: 100%;
}
h3 {
  font-size: 16px;
  margin:0 0 0.3rem;
  font-weight: normal;
  font-weight:bold;
}
p {
  margin:0;
}
.sort {
  padding:8px 30px;
  border-radius: 6px;
  border:none;
  display:inline-block;
  color:#fff;
  text-decoration: none;
  background-color: #28a8e0;
  height:30px;
}
.sort:hover {
  text-decoration: none;
  background-color:#1b8aba;
}
.sort:focus {
  outline:none;
}
.sort:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid transparent;
  content:"";
  position: relative;
  top:-10px;
  right:-5px;
}
.sort.asc:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #fff;
  content:"";
  position: relative;
  top:13px;
  right:-5px;
}
.sort.desc:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid #fff;
  content:"";
  position: relative;
  top:-10px;
  right:-5px;
}
</style>
<div class="full col-sm-9 ">
	  <script type="text/javascript">
        $('#form').keydown(function() {
        var key = e.which;
        if (key == 13) {
        // As ASCII code for ENTER key is "13"
        $('#form').submit(); // Submit form code
        }
        });
    </script>
	<style>
		.button {
			cursor:pointer;
			border:none;
		}
		.button:focus,.button:active{
			border:none;
		}
		.LV_invalid{
			color:red;
		}
	</style>
<div class="page-title">
<div class="container body-content <?php echo $showContact;?>" id="contactListing">
<h4><span class="pull-left" style="line-height: 3;">SELECT CONTACT</span><span id="newContact" class="pull-right button"><i class="fa fa-plus"></i>&nbsp;New</span> </h4>
<div id="contact-list">
  <input class="search form-control" placeholder="Search" />
  <ul class="list">
     <?php foreach ($contactDetails as $item): ?>
	    <li>
	       <a href="<?php echo base_url();?>expert/create?contactId=<?=$item->Salesforce_Id;?>"><h3 class="name"><?=$item->Name;?></h3>
	       <p class="email"><?=$item->Email;?></p></a>
	    </li>
    <?php endforeach; ?>
    
  </ul>
</div>
</div>
<div class="container body-content <?php echo $showService;?>" id="serviceListing">
 <div class="row">
			 <!-- left column / left side -->
             <div class="col-sm-12 col-md-12 left-content" id="listService" >
                <!-- Title -->
                <h2><img src="<?php echo base_url();?>img/1.png" alt="Choose Appointment Type"> Choose Appointment Type: </h2><br/>
                <!-- Type -->
                <?php foreach ($serviceDetails as $item): ?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 appt-type">
                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                        <h3><?=$item->Name;?></h3>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <a href="javascript:scheduleNow('<?=$item->Service_Id;?>','<?=$item->Name;?>','<?= $item->Provider; ?>')"><div class="button">Schedule Now</div></a>
                    </div>
                </div>
    			<?php endforeach; ?>
             </div>
<!-- left column / left side -->

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 poweredby">
			<p>You will get to choose your date and time on the next screen.</p>
	</div>

   </div>
</div>
<div class="container body-content" id="dateandtime" style="display:none">
    <div class="row">

<div class="col-sm-12 col-md-12">
    <div class="alert alert-info" role="alert" id="choosenService"></div>
</div>
<!-- left column / left side -->
       <div class="col-sm-12 col-md-12 left-content">
            <form  accept-charset="utf-8" name="bookAppointment" class="form" role="form" id="bookAppointment">
                    <div class="row">
                    <h4><img src="<?php echo base_url(); ?>img/2.png" alt="Schedule a Date/Time"/> Schedule a Date/Time:</h4>

                        <div class="col-xs-12 col-md-6 form-group has-feedback">
                            <p>Choose a date:</p>
                            <input type="text" name="date" value="" id="datepicker" class="form-control input-lg"/>
                            <i class="form-control-feedback fa fa-calendar fa-2x"></i>

                        </div>
                        <div class="col-xs-12 col-md-6">
                            <p>Choose an available time slot:</p>
                            <!--<input type="text" name="time" value="" class="form-control input-lg"/>-->
							<select id="availableSlots" class="form-control input-lg">
                            </select>
                            <i class="form-control-feedback fa fa-clock-o fa-2x"></i>
                        </div>
                    </div>
           <br/>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 privacy-note" style="text-align:center;">
					<p><i class="fa fa-lock"></i> <b>Your privacy is important to us.</b>  The information above will only be used in regard to your appointment.</p>
					<button class="button" role="div" id="bookApp" type="submit" style="width:300px;margin:0 auto!important;border:none;">Schedule This Appointment</button>    
			</div>
			 </form>
             </div>
<!-- left column / left side -->

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 poweredby">
        </div>

			</div>
</div>
<div class="container body-content" id="confirmation" style="display:none">
    <div class="row">
	<!-- left column / left side -->
	 <div class="col-sm-12 col-md-12 left-content" id="appointmentStatus">

		

	 </div>
<!-- left column / left side -->

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 poweredby">
<p>We've sent a confirmation email to your inbox. Remember to save this information to your calendar.</p>
</div>

</div>
</div>
</div>
	<script src='<?php echo base_url(); ?>js/lib/jquery-ui.min.js'></script>
    <script src='<?php echo base_url(); ?>js/lib/moment.min.js'></script>
    <script src="<?php echo base_url(); ?>js/lib/angular.min.js"></script>
    <script src="<?php echo base_url(); ?>js/lib/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js/lib/bootstrap-formhelpers.min.js"></script>
    <script src="<?php echo base_url(); ?>js/lib/apertureFormJS.min.js"></script>
    <script src="<?php echo base_url(); ?>js/lib/list.min.js"></script>
    <script>
    	var options = {
    	    valueNames: [ 'name', 'email' ]
    	};

    	var contactList = new List('contact-list', options);
    	 var account= 'phpint';
         var eventType='WORKINGHOUR';
         var CUSTOMEROBJECT='CONTACT';
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
              	var $expert_Id = '<?php echo $this->session->userdata('expertSFId');?>';
             	$.ajax({
             		  url: "<?php echo base_url();?>ajaxHandler/getExpertAvailability?service="+$SelectedService+"&provider="+$provider+"&expert="+$expert_Id,
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
 	  var $SelectedService = '';
 	  var $serviceName='';  
 	  var $provider = '';
       function scheduleNow(selectedService,serviceName,provider){
 		$SelectedService = selectedService;
 		$provider = provider;
 		account = $provider;
 		$serviceName = serviceName;
 		$('#serviceListing').hide();
 		$('#dateandtime').show();
 		$('#choosenService').html('<center>You Chose '+$serviceName+' <a href="javascript:showListing()"><u>Change</u></a></center>');
 		$('#datepicker').datepicker('setDate',new Date());
 		getAvailableSlot();
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
                 objRequest['contactId'] = "<?php echo  $this->input->get('contactId');?>";
                 if (objRequest['contactId'] != null) {
                     document.getElementById("bookApp").disabled = true;
                     var selectedDate = moment($('#datepicker').datepicker("getDate")).format('D_M_YYYY');
                     objRequest['serviceId'] = $SelectedService;
                     objRequest['serviceName'] = serviceName;
                     objRequest['dateString'] = moment($('#datepicker').datepicker("getDate")).format('YYYY-MM-DD');
                     objRequest['provider'] = account;
                     objRequest['SELECTEDDATE'] = selectedDate;
                     if(selectedSlot == 'WAITINGLIST')
                         objRequest['slot'] = 'WAITINGLIST';
                     else
                         objRequest['slot'] = perfectSlot(selectedSlot);
                     objRequest['ObjType'] = 'CONTACT';
                     objRequest['EXPERT'] = '<?php echo $this->session->userdata('expertSFId');?>';
                     objRequest['appointmentStatus'] = 'Pending'; 
                    bookAppointment(objRequest);
                 }
             });
         //Create new Appointment Request
         function bookAppointment(objRequest) {
             $('#bookApp').attr('disabled',false);
             var postData = objRequest; 
             $.ajax({
       		  url: "<?php echo base_url();?>ajaxHandler/bookAppointment",
       			type: 'post',
       			dataType: 'json',
       		    data: postData,	  
       		  beforeSend: function( xhr ) {
       		  }
       		})
       		  .done(function( data ) {
       		    //if ( console && console.log ) {
       		      //console.log($.parseJSON(data));
       		      window.location ='<?php echo base_url();?>main/home';
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
                   dateFormat: '<?php echo $OrgDetails->DateFormat; ?>',
                 	onSelect: function(dateText) {
                    	 buildSlots(availableSlot);
                    }
                // minDate: -{!allowedPastDays}, maxDate: +{!allowedFutureDays}
             });
             $('#datepicker').datepicker('setDate',new Date());
 		
         });
    </script>
</div>