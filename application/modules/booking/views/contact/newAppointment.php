<link href="<?php echo  base_url(); ?>css/jquery-ui.min.css" rel="stylesheet">
<div class="box box-success" id="serviceListing">
	<div class="box-header" style="cursor: move;">
		<h3 class="box-title">Select Service</h3>
	</div>
	<div class="box-body chat" id="chat-box" >
		<!-- chat item -->
		<?php //print_r($serviceDetails);exit;?>
		<?php foreach ($serviceDetails as $item): ?>
		<div class="item">
			<div class="attachment" style="margin-left: 0px;">
				<h4><?=$item->name;?></h4>
				<p class="filename">
					<?=$item->duration;?>
				</p>
				<div class="pull-right">
					<button onclick="scheduleNow('<?=$item->_id;?>','<?=$item->name;?>','<?=$item->serviceId;?>')" class="btn btn-primary btn-sm btn-flat">Book Now</button>
				</div>
			</div><!-- /.attachment -->
		</div><!-- /.item -->
    	<?php endforeach; ?>
	</div>
</div>
<div class="box box-success" id="dateandtime" style="display:none;">
	<form id="bookAppointment">
	<div class="box-header">
		<h3 class="box-title">Choose Date and Time</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
					<div class="form-group">
						<div class="input-group">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="choosenService"></button>
							<ul class="dropdown-menu">
								<li><a href="javascript:showListing()">Change</a></li>
							</ul>
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
            	$.ajax({
            		  url: "<?php echo base_url();?>booking/getServiceAvailability?service="+$SelectedService,
            		  beforeSend: function( xhr ) {
            		  }
            		})
				  .done(function( data ) {
					if ( console && console.log ) {
					  console.log($.parseJSON(data));
					}
					data = $.parseJSON(data);
					console.log(data);
					//var result = {
							//availableSlots : data[Object.keys(data)[0]],
							//};
					handleAvailableSlot(data[0]);
				  });
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
            var availableSlot;
            function handleAvailableSlot(result){              
				availableSlot = result;
				var selectedDate = moment($('#datepicker').datepicker('getDate')).format('D_M_YYYY');
				var html ='';
				var currentDate = moment(new Date()).format('D_M_YYYY');
				var availability = result.availability;
				var dateSlots;
				if(availability.hasOwnProperty(selectedDate)){
					dateSlots = availability[selectedDate];
					for(var j=0;j < dateSlots.length ; j++){
						var splitSlot = dateSlots[j].slot.split('_');
						var buildDateTime = moment(selectedDate+' '+splitSlot[0], "DD_MM_YYYY hh:mm a");
						var now = moment();
						if (!(now > buildDateTime) && !dateSlots[j].isBookedSlot) {
							dateSlots[j].fullSlot = dateSlots[j].slot;
							html += '<option value="'+dateSlots[j].fullSlot+'">'+dateSlots[j].fullSlot.replace('_',' to ')+'</option>';
						}
					}
				}
				if(html == ''){
					html = '<option value="">Slots not available for the day</option>';
					$('#availableSlots').html(html);
				}else{
					$('#availableSlots').html(html);
				}
            }
        function buildSlots(result){
			var selectedDate = moment($('#datepicker').datepicker('getDate')).format('D_M_YYYY');
			var html ='';
			var currentDate = moment(new Date()).format('D_M_YYYY');
			var availability = result.availability;
			var dateSlots;
			if(availability.hasOwnProperty(selectedDate)){
				dateSlots = availability[selectedDate];
				for(var j=0;j < dateSlots.length ; j++){
					var splitSlot = dateSlots[j].slot.split('_');
					var buildDateTime = moment(selectedDate+' '+splitSlot[0], "DD_MM_YYYY hh:mm a");
					var now = moment();
					if (!(now > buildDateTime) && !dateSlots[j].isBookedSlot) {
						dateSlots[j].fullSlot = dateSlots[j].slot;
						html += '<option value="'+dateSlots[j].fullSlot+'">'+dateSlots[j].fullSlot.replace('_',' to ')+'</option>';
					}
				}
			}
			if(html == ''){
				html = '<option value="">Slots not available for the day</option>';
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
	  var $serviceId = '';
      function scheduleNow(selectedService,serviceName,serviceId){
		$SelectedService = selectedService;
		$serviceName = serviceName;
		$serviceId = serviceId;
		$('#serviceListing').hide();
		$('#dateandtime').show();
		$('#choosenService').html($serviceName+' <span class="fa fa-caret-down"></span>');
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
                objRequest['contactId'] = "<?php echo  $ContactDetails->Id;?>";
                if (objRequest['contactId'] != null) {
                    document.getElementById("bookApp").disabled = true;
                    var selectedDate = moment($('#datepicker').datepicker("getDate")).format('D_M_YYYY');
                    objRequest['serviceId'] = $serviceId;
                    objRequest['serviceName'] = serviceName;
                    objRequest['dateString'] = moment($('#datepicker').datepicker("getDate")).format('YYYY-MM-DD');
                    objRequest['SELECTEDDATE'] = selectedDate;
                    if(selectedSlot == 'WAITINGLIST')
                        objRequest['slot'] = 'WAITINGLIST';
                    else
                        objRequest['slot'] = perfectSlot(selectedSlot);
                    objRequest['appointmentStatus'] = 'Pending'; 
                    objRequest['EXPERT'] = '';
					objRequest['OrgId'] = '<?php echo $ContactDetails->Organization_Id; ?>';
                   bookAppointment(objRequest);
                }
            });
        //Create new Appointment Request
        function bookAppointment(objRequest) {
            $('#bookApp').attr('disabled',false);
            var postData = objRequest; 
            $.ajax({
      		  url: "<?php echo base_url();?>booking/bookAppointment",
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