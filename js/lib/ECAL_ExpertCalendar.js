<!-- UI Changes newly added functions -->

<!-- InLine Java scripts-->
sforce.connection.sessionId = $.modalsessionId;
var defaultCusType=$.modaldefaultCusType;
var popupButtons =$.modalpopupButtons;
$.fn.editable.defaults.mode = 'inline';
var QueryIcon = null;
if($.modalcalIconSet){
	if($.modalcalIconSet != null  && $.modalcalIconSet.length != 0 ){
			QueryIcon='Select Name,Id,';
			for(var i=0 ;i<$.modalcalIconSet.length;i++){
				if(i == ($.modalcalIconSet.length - 1))
					QueryIcon+= $.modalcalIconSet[i].field;
				else
					QueryIcon+= $.modalcalIconSet[i].field+',';
			}
				QueryIcon +=' FROM BKSL2__Service_Request__c where id=';
	}
}
//Global Variable declarations
var filterType="EXPERT";
var retToURL = $.modalretToURL;
var contactId= $.modalcontactId;
var serviceId= $.modalserviceId;
if(serviceId != null && serviceId != ''){
	getServiceDetailsById(serviceId);
}
var providerId= $.modalproviderId;
var AUTOLAUNCH= $.modalAUTOLAUNCH;
var fieldSetName=  $.modalfieldSetName;
var isfieldSetAvailable = false;
var allowPastDayBooking = false;
var CALENDARALLOWPASTBOOKING = $.modalAllowPastBooking;
if(CALENDARALLOWPASTBOOKING == 'TRUE'){
	allowPastDayBooking = true;
}
if(fieldSetName != '' && fieldSetName != null){
	isfieldSetAvailable = true;
}
if(isfieldSetAvailable == true)
   $('#submitButton').html('NEXT');
else
   $('#submitButton').html('SCHEDULE');
var expertId=$.modalexpertId;
var selectedContactName='';
var reScFlexiBlock = $('.isreFlexiTime');
var reScSlotBlock = $('.isNotreFlexiTime');
var reScheduleSlot;
var reScheduleService;
var reScheduleExpert;
var isClickToCallEnabled = $.modalisClickToCallEnabled;
var calendarGoToDate = new Date();
getCheckedExperts(providerId);
getAvailableExperts(providerId);
var eventType = 'EXPERTWORKINGHOUR';
var mouseX, mouseY, windowWidth, windowHeight;
var popupLeft, popupTop;
var defaultView ='agendaWeek';
var userType =$.modaluserType;
var selectedSlotStart;
var selectedEventStart;
var mapCheckedExpert;
var CUSTOMEROBJECT = $.modalCustomerObject;
appendStatus();
var eventColorCode ='#FFFFFF';
var expertIncl=[];
 var expId= $.modalexpId;
 if(expId != null && expId != '' && expId != undefined)
     expertIncl.push(expId);
var expertExclude=[];
var selectedExpert = [];
if($.modalEventSet.hasOwnProperty('color')){
	eventColorCode = $.modalEventSet.color;
}
var statusSource = [];
 if($.modalStatus){
 	for(key in $.modalStatus){
 		statusSource.push({
 			value : key,
 			text :key
 		});
 	}
}
var expertHolidayStart;
var expertHolidayEnd;
var expertHolidayId=[];
function appendStatus(){
	var statusHtml = ''
	for (var key in $.modalStatus) {
	  if ($.modalStatus.hasOwnProperty(key)) {
			   statusHtml+='<option value="'+key+'">'+key+'</option>';
	  }
	}
	$('#appSelStatus').html(statusHtml);
	$('#appMultiSelStatus').html(statusHtml);
}
function ajaxindicatorstart(text)
{
    if($('body').find('#resultLoading').attr('id') != 'resultLoading'){
    $('body').append('<div id="resultLoading" style="display:none"><div><img src="'+$.modalImagePath+'images/ajax-loader.gif"><div>'+text+'</div></div><div class="bg"></div></div>');
    }

    $('#resultLoading').css({
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'10000000',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto'
    });

    $('#resultLoading .bg').css({
        'background':'#000000',
        'opacity':'0.7',
        'width':'100%',
        'height':'100%',
        'position':'absolute',
        'top':'0'
    });

    $('#resultLoading>div:first').css({
        'width': '250px',
        'height':'75px',
        'text-align': 'center',
        'position': 'fixed',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'font-size':'16px',
        'z-index':'10',
        'color':'#ffffff'

    });

    $('#resultLoading .bg').height('100%');
       $('#resultLoading').fadeIn(300);
    $('body').css('cursor', 'wait');
}
function ajaxindicatorstop()
{
    $('#resultLoading .bg').height('100%');
       $('#resultLoading').fadeOut(300);
    $('body').css('cursor', 'default');
}
var contactLastName_V = new LiveValidation("LastName", {
    wait: 500,
    onlyOnSubmit: true,
    validMessage: ' '
});
contactLastName_V.add(Validate.Presence, {
    failureMessage: "Last Name is required."
});
var reScheduleSlot_V = new LiveValidation("reScheduleSlots", {
    wait: 500,
    onlyOnSubmit: true
});
reScheduleSlot_V.add(Validate.Presence, {
    failureMessage: "Please Select a Slot",
    validMessage: ' '
});
var enableAutolaunch = true;
 var reScheduleExp_V = new LiveValidation("reScheduleExperts", {
     wait: 500,
     onlyOnSubmit: true
 });
 reScheduleExp_V.add(Validate.Presence, {
     failureMessage: "Please Select Expert",
     validMessage: ' '
 });
var reScheduleDate_V = new LiveValidation("reScheduleDate", {
    wait: 500,
    onlyOnSubmit: true
});
reScheduleDate_V.add(Validate.Presence, {
    failureMessage: "Select Date",
    validMessage: ' '
});
 $(document).on('click','#editReSche', function(e) {
     var serReqId = $(this).attr('eventId');
     var serId = $(this).attr('serviceId');
     providerId = $(this).attr('providerid');
     var selExp = $(this).attr('expertId');
	 $('.tooltipevetn').hide();
     $('#serReqId').val(serReqId);
     $('#reScheduleDate').val($(this).attr('eventStart'));
     $('#nextSlot').html('');
     $('#prevSlot').html('');
	     if(serId != null && serId != undefined){
	     getExpertsByService(serId);
	      reScheduleExpert = selExp;
	           if(isExpflexiTime == true){
		 			var selectedDate = moment($('#reScheduleDate').datepicker('getDate')).format('YYYY-MM-DD');
		            getreScheduleExpertWorkingHour(selExp,selectedDate);
     			}else{
			      getreScheduleSlot(serId,eventType,providerId,selExp);
	   			}
	   		 $('#reScheduleModal').modal({
						    backdrop: 'static',
						    keyboard: false  // to prevent closing with Esc button (if you want this too)
						});
		 }
 });
var contactEmail_V = new LiveValidation("Email", {
    wait: 500,
    onlyOnSubmit: true,
    validMessage: ' '
});
contactEmail_V.add(Validate.Presence, {
    failureMessage: "Email is required."
});
contactEmail_V.add(Validate.Email, {
    validMessage: ' '
});
 var expert_V = new LiveValidation("listExperts", {
     wait: 500,
     onlyOnSubmit: true,
     validMessage: ' '
 });
 expert_V.add(Validate.Presence, {
     failureMessage: "Please Choose Expert",
     validMessage: ' '
 });
var service_V = new LiveValidation("listService", {
    wait: 500,
    onlyOnSubmit: true
});
service_V.add(Validate.Presence, {
    failureMessage: "Please Choose Service",
    validMessage: ' '
});
var serviceSlot_V = new LiveValidation("availableSlots", {
    wait: 500,
    onlyOnSubmit: true,
    validMessage: ' '
});
serviceSlot_V.add(Validate.Presence, {
    failureMessage: "Please Select Time",
    validMessage: ' '
});
var selectedDuration_V = new LiveValidation("selectedDuration", {
    wait: 500,
    onlyOnSubmit: true,
    validMessage: ' '
});
selectedDuration_V.add(Validate.Presence, {
    failureMessage: "Duration should be proper",
    validMessage: ' '
});
selectedDuration_V.add( Validate.Numericality, { onlyInteger: true,failureMessage: "Duration should be proper", } );
var reScheduleDuration_V = new LiveValidation("reScheduleDuration", {
    wait: 500,
    onlyOnSubmit: true,
    validMessage: ' '
});
reScheduleDuration_V.add(Validate.Presence, {
    failureMessage: "Duration should be proper",
    validMessage: ' '
});
reScheduleDuration_V.add( Validate.Numericality, { onlyInteger: true,failureMessage: "Duration should be proper", } );
//DateTime picker Utilities
$(document).ready(function() {
   //load Calender 
   loadSyncCalendar();
    if(allowPastDayBooking){
	     $('#reScheduleDate').datepicker({
	         dateFormat: ''+$.modalcaldateFormat+'',
	         onSelect: function(selectedDate) {
	         	if(isExpflexiTime == true){
	         		var selExp = $('#reScheduleExperts').val();
		        	var selectedDate = moment($('#reScheduleDate').datepicker('getDate')).format('YYYY-MM-DD');
		        	getreScheduleExpertWorkingHour(selExp,selectedDate);
		         }else{
			   			 handlereScheduleSlot(reScheduleSlot);
			   	 }
			  } 
	        })
	     $('#selectedDateTime').datepicker({
	         dateFormat: ''+$.modalcaldateFormat+'',
	         onSelect: function(selectedDate) {
	         	if(isExpflexiTime == true){
	         		var selExp = $('#listExperts').val();
		        	var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('YYYY-MM-DD');
		        	getExpertWorkingHour(selExp,selectedDate);
		         }else{
		         	var selService = $('#listService').val();
		         	if(selService  != '' && selService != null){
		         		$('#availableSlots').attr('disabled',true);
				 		$('#availableSlots').addClass('cascading-dropdown-loading');
		            	handleAvailableSlot(availableSlotsNew);
		            }
		         }
	          },
	     })
	}else{
		$('#reScheduleDate').datepicker({
	         dateFormat: ''+$.modalcaldateFormat+'',
	         minDate: $.modalallowedPastDays, 
	         onSelect: function(selectedDate) {
	         	if(isExpflexiTime == true){
	         		var selExp = $('#reScheduleExperts').val();
		        	var selectedDate = moment($('#reScheduleDate').datepicker('getDate')).format('YYYY-MM-DD');
		        	getreScheduleExpertWorkingHour(selExp,selectedDate);
		         }else{
			   			 handlereScheduleSlot(reScheduleSlot);
			   	 }
			  } 
	        })
	     $('#selectedDateTime').datepicker({
	         dateFormat: ''+$.modalcaldateFormat+'',
	         minDate: $.modalallowedPastDays, 
	         onSelect: function(selectedDate) {
	         	if(isExpflexiTime == true){
	         		var selExp = $('#listExperts').val();
		        	var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('YYYY-MM-DD');
		        	getExpertWorkingHour(selExp,selectedDate);
		         }else{
		         	var selService = $('#listService').val();
		         	if(selService  != '' && selService != null){
		         		$('#availableSlots').attr('disabled',true);
				 		$('#availableSlots').addClass('cascading-dropdown-loading');
		            	handleAvailableSlot(availableSlotsNew);
		            }
		         }
	          },
	     })
	}
    $('#submitButton').on('click', function(e) {
        e.preventDefault();
		if (areAllValid() != true) {
			$(this).attr('disabled',true);
			$(this).css("background-color", "#BCE0F5");
			doSubmit();
			}
		//$('#submitButton').html('SCHEDULING');
        //doSubmit();
    });
	
	$('#ExpertAccordion label').each(function() {
		var attrId =$(this).attr('for');
		//$(this).attr('for','');
		//$(this).attr('expertattrid',attrId);
		$(this).replaceWith( "<span expertattrid='"+attrId+"'>"+$(this).html()+"</span>" );
	});
	$('#ServiceAccordion label').each(function() {
		var attrId =$(this).attr('for');
		//$(this).attr('for','');
		//$(this).attr('expertattrid',attrId);
		$(this).replaceWith( "<span serviceattrid='"+attrId+"'>"+$(this).html()+"</span>" );
	});
});
function autoLaunch(){
    	if(AUTOLAUNCH == 'al' ){
    		debugger;
			 $('#createAppLoading').addClass('active');
             if(defaultCusType != 'EXISTING')
             	$('#existingCustomerName').attr('disabled',true);
             else
             	$('#existingCustomerName').attr('disabled',false);
			 
			$('[id$=selCusID]').val('');
			selectedSlotStart=new Date();
			$('[id$=criticalInformation]').html('');
			$('[id$=criticalInformation]').hide();
			slotblock.hide();
			flexiblock.hide();
			reScFlexiBlock.hide();
			reScSlotBlock.hide();
      		$("#createAppointmentForm").trigger('reset');
      		//$('#selectedDateTime').datepicker("setDate",new Date());
             var html = '<option value="">Please Select a Service</option>';
             $('#availableSlots').html(html);
             if(contactId != null && contactId != '' && contactId != undefined){
                if(CUSTOMEROBJECT == 'CUSTOMER'){
			        getCustomersById(contactId);
			 	}else if(CUSTOMEROBJECT == 'LEAD'){
			        getLeadsById(contactId);
			    }else{
			        getContactsById(contactId);
			    }
   			}
    		if(serviceId != '' && expId == '' ){
               getExpertsByURLService(serviceId);             
	    	}else if(serviceId == '' && expId != '' ){
	    		getAppBookDet(expId);
	            var selectedDate = moment($('#selectedDateTime').datepicker("getDate")).format('YYYY-MM-DD');
			    $('#listExperts').val(expId);
			    $('#listExperts').trigger('change');
	    	}
	    	 $('#listService').attr('disabled',true);
                   $('#availableSlots').attr('disabled',true);
                   $('#createEventModal').modal({
						    backdrop: 'static',
						    keyboard: false  // to prevent closing with Esc button (if you want this too)
						});
                   ajaxindicatorstop();
             }
             setTimeout(function(){
				$('#createAppLoading').removeClass('active');
				$(':radio[value="'+defaultCusType+'"]').prop('checked', true);
				}, 1000);
    	}
var eventServiceId ='';
$(document).on('click','#newApp',function(){
		$('.tooltipevetn').hide();
		$('#exceptionPanel').html('');
		$('#createAppLoading').addClass('active');
    	var cusId = $(this).attr('conId');
    	eventServiceId = $(this).attr('serviceid');
    	var eventExpertId = $(this).attr('expertid');
    	var isPreExpert = $(this).attr('ispreferredexpert');
           $(':radio[value="'+defaultCusType+'"]').prop('checked', true);
	        if(defaultCusType != 'EXISTING')
	            $('#existingCustomerName').attr('disabled',true);
	        else
	            $('#existingCustomerName').attr('disabled',false);
           $('[id$=selCusID]').val('');
           $('[id$=criticalInformation]').html('');
	       $('[id$=criticalInformation]').hide();
	       $("#createAppointmentForm").trigger('reset');
	       if(selectedEventStart != undefined && selectedEventStart != null){
	          $('#selectedDateTime').datepicker("setDate",selectedEventStart);
	       }else{
	       	 $('#selectedDateTime').datepicker("setDate",new Date());
	       }
           var selectedExpert = $('[id$=expertList]').val();
            if(isPreExpert =='true' && eventServiceId != null && eventServiceId !=''&& eventServiceId != 'undefined'){
	       		getExpertsByURLService(eventServiceId);
           	}else{
   		    	eventServiceId = null;
				checkedExpertHTML();
	           /*if(selectedExpert != null && selectedExpert != ''){
	           		getAppBookDet(selectedExpert);
	           		var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('YYYY-MM-DD');
	        		getExpertWorkingHour(selectedExpert,selectedDate);
	           	}else{
	           		getAvailableExperts(providerId);
	           		var exphtml= '<option value="">Please Select a Expert</option>';
	                 $('#listService').html(exphtml);
	           	}*/
	          }
			$('#availableSlots').attr('disabled',true);
			$('#listService').attr('disabled',true);
           selectedSlotStart = null;
			var html = '<option value="">Please Select a Service</option>';
			$('#availableSlots').html(html);
			if(cusId != null && cusId != '' && cusId != undefined && cusId != 'undefined' && cusId != 'isMultiSlot' ){
				if(CUSTOMEROBJECT == 'CUSTOMER'){
			        getCustomersById(cusId);
			 	}else if(CUSTOMEROBJECT == 'LEAD'){
			        getLeadsById(cusId);
			    }else{
			        getContactsById(cusId);
			    }
			}else{
				$('#createAppLoading').removeClass('active');
				
			}
			 $("#createAppointmentForm").show();
       	 	 $("#additionalContactDetail").hide();
             $("#createEventModal .modal-footer").show();
			 debugger;
             $('#listExperts').val(selectedExpert);
	          $('#createEventModal').modal({
						    backdrop: 'static',
						    keyboard: false  // to prevent closing with Esc button (if you want this too)
						});
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
$(document).on('mouseenter', '.tooltipevetn', function(e) {
    $(this).show();
});
$(document).on('mouseleave', '.wc-cal-event,.wc-container,.wc-scrollable-grid', function(e) {
    $('.tooltipevetn').hide();
}); 
$(document).on('mouseenter', '.wc-container,.wc-scrollable-grid', function(e) {
   $('.tooltipevetn').hide();
});
//get Duration on change of service while create appoitment   
$(document).on('change', '#listService', function() {
	if(isExpflexiTime != true){
	    var serId = $(this).val();
	    $('#availableSlots').attr('disabled',true);
	    $('#availableSlots').addClass('cascading-dropdown-loading');
	     var html = '<option value="">Choose Date</option>';
	    $('#availableSlots').html(html);
	    var selExp =  $('#listExperts').val();
	    if(selExp != '' && selExp != null)
	    	getAvailableSlot(serId,eventType,providerId,selExp);
	    else
	    	$('#availableSlots').html('<option value="">Please Select Expert</option>');
	 }
});

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

    //Book Appointment function on Submit
    function doSubmit() {
        var serviceId = $('#listService').val();
        var selExpId = $('#listExperts').val();
        var serviceName = $('#listService option:selected').text();
        var validData = areAllValid();
        if (areAllValid() != true) {
            var selectedDate = moment($('#selectedDateTime').datepicker("getDate")).format('D_M_YYYY');
            if(isExpflexiTime == true){
            	var statTime = moment($('#selectedStartTime').timepicker("getTime")).format('hh:mm A');
            	if($('#selectedDuration').val() != null && $('#selectedDuration').val() != ''){
            		var endTime = moment($('#selectedStartTime').timepicker("getTime")).add(parseInt($('#selectedDuration').val()),'m').format('hh:mm A');
            	}else{
            		var endTime = moment($('#selectedEndTime').timepicker("getTime")).format('hh:mm A');
            	}
            	var selectedSlot = statTime+'_'+endTime;
            }else{
            	var selectedSlot = $('#availableSlots').val();
            }
            var objRequest = new Object();
            objRequest['serviceId'] = serviceId;
            objRequest['serviceName'] = serviceName;
            objRequest['provider'] = providerId;
            objRequest['SELECTEDDATE'] = selectedDate;
            if(selectedSlot == 'WAITINGLIST')
                objRequest['slot'] = 'WAITINGLIST';
            else
                objRequest['slot'] = perfectSlot(selectedSlot);
            objRequest['contactLastName'] = document.getElementById("LastName").value;
            objRequest['contactFirstName'] = document.getElementById("FirstName").value;
            objRequest['contactEmail'] = document.getElementById("Email").value;
            objRequest['contactPhone'] = document.getElementById("Phone").value;
            objRequest['contactCity'] = document.getElementById("City").value;
            objRequest['contactStreet'] = document.getElementById("Street").value;
            objRequest['contactState'] = document.getElementById("State").value;
            objRequest['contactZipCode'] = document.getElementById("ZipCode").value;
            objRequest['contactId'] = document.getElementById("selCusID").value;
            objRequest['ObjType'] = CUSTOMEROBJECT;
            objRequest['expertId'] = selExpId;
            objRequest['defaultstatus'] = $.modalDefaultStatus;
            bookAppointment(objRequest);
        }else{
            $('#submitButton').attr('disabled',false);
        }
        }
var expertsCleared = false;
// OnChange Event to load calender By selected Expert

//Appointment Form validation done here
function areAllValid() {
	if(isExpflexiTime == true)
    	return !LiveValidation.massValidate([contactLastName_V, contactEmail_V, service_V,expert_V,selectedDuration_V]);
    else
    	return !LiveValidation.massValidate([contactLastName_V, contactEmail_V, service_V,serviceSlot_V,expert_V]);
}

//Event Edit Click function (EDIT Button)
$(document).on('click', '#editDet,#viewDet', function() {
	$('#serReqUpdateErrorPanel').html('');
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
function refreshCalendar(){
		enableAutolaunch = false;
        calendarGoToDate = $('#calendar').fullCalendar( 'getDate' )._d;
        $('#calendar').fullCalendar( 'refetchEvents' );	
    }
//Functions to handle remote requests 

//bookAppointment Handle function
function handleBookResult(result,event) {
    	if(event.type === 'exception') {
    			   $('#submitButton').attr('disabled',false);
                   $('#exceptionPanel').html(event);
                   $('#createAppLoading').removeClass('active');
          }
          else if(event.result.success){
          		 $('#exceptionPanel').html('');
	    		 $('#submitButton').attr('disabled',false);
		        var  lastSerReqId = result.quickResponse;
		        if(isfieldSetAvailable && lastSerReqId != undefined){
		        	 $("#createAppointmentForm").hide();
		        	 $("#additionalContactDetail").show();
		        	 var src = "/apex/BKSL2__COMP_AddServiceRequestForm?sid="+lastSerReqId+'&fs='+fieldSetName+'&cObj='+CUSTOMEROBJECT;
                     $("[id$=addtionalInfoIframe]").attr('src',src);
                     $("#createEventModal .modal-footer").hide();
		        }else{
		        	if(retToURL != '' && retToURL != null)
                		window.location = retToURL;
			        $("#createAppointmentForm").trigger('reset');
			        $("#createEventModal").modal('hide');
			        refreshCalendar();
			    }
		    }else{
		    	  $('#submitButton').attr('disabled',false);
             	  $('#createAppLoading').removeClass('active');
                   if(result.message != undefined)
                   		$('#exceptionPanel').html(result.message);
             }
}
function loadSyncCalendar(){
		ajaxindicatorstart('loading data.. please wait..');
		//Loading Calender code done here 
        $('#calendar').fullCalendar('destroy');
        $('#expertPanel').show();
		if(userType == 'Admin'){
			$('[id$=expertForm] input').iCheck('check');
			$('[id$=serviceForm] input').iCheck('check');
			//$('#FilterType input').iCheck('check');
		}
		var events  = [];
        var calendar = $('#calendar').fullCalendar({
        defaultView: defaultView,
		dayRender: function (date, cell) {
							var CheckedCount = ($("#ExpertAccordion input:checked").length);
							var checkedExpertId;
							if(CheckedCount == 1 || userType == 'Expert'){
							if(userType == 'Admin'){
								$('#ExpertAccordion input:checked').each(function() {
								if($(this).val() != ''){
									checkedExpertId =	$(this).val();
								}
								});
								}else{
									checkedExpertId=expertId;
								}
								var selectedHoliday =moment(date).format('MM/DD/YYYY hh:mm A');
								Visualforce.remoting.Manager.invokeAction(
										$.remoteCheckExpertHolidays,checkedExpertId,selectedHoliday,
											function(result, event){
												if (result == true) {
													cell.css("background-color", "grey");
												}}, 
												{escape: true}
										);
								}
							},
            editable: false,
            events: function(start, end, timezone, callback) {
			         ajaxindicatorstart('loading data.. please wait..');
					var startStr =moment(start).format('MM/DD/YYYY hh:mm A');
					var endStr =moment(end).format('MM/DD/YYYY hh:mm A');
					if((userType != null || expertId != null) && (userType != '' || expertId != '') && filterType =="EXPERT"){
						if(userType != null  && userType != undefined){
							if(expId !=null && expId !='' && expId != undefined){
								Visualforce.remoting.Manager.invokeAction(
									$.remoteServiceReqByExpertIncl,expertIncl,providerId,startStr,endStr,
										function(result, event){
										console.log(result);
											if (event.status) {
												//all code after remote controller will be here
													var events = handleresult(result);
													callback(events);
													  ajaxindicatorstop();
											}}, 
											{escape: true}
									);
						}else{
							if (userType == 'Admin') {
								$('[id$=expertForm]').show();
								if(expId != null && expId != '' && expId != undefined){
									Visualforce.remoting.Manager.invokeAction(
									$.remoteServiceReqByExpertIncl,expertIncl,providerId,startStr,endStr,
										function(result, event){
										console.log(result);
											if (event.status) {
												//all code after remote controller will be here
													var events = handleresult(result);
													callback(events);
													  ajaxindicatorstop();
											}}, 
											{escape: true}
									);
								}else{
									var callExpertExclude = false;
									$('#ExpertAccordion input:checked').each(function() {
												if($(this).val() == ''){
													callExpertExclude = true;
												}
									});
									if(callExpertExclude){
									
										Visualforce.remoting.Manager.invokeAction(
										$.remoteServiceReqAllexpert,
													expertExclude,providerId,startStr,endStr,
											function(result, event){
											//remove BreakHours Service Request
											var sortResult =[];
											var serviceRequestME =[];
											var availability =[];
											var experts =[];
											var freeBusys =[];
												for(var i=0;i < result.serviceRequestME.length;i++){
													debugger;
													if(result.serviceRequestME[i].serName != 'Break Hours'){
														serviceRequestME.push(result.serviceRequestME[i]);
													}
												}
											sortResult.serviceRequestME = serviceRequestME;
											sortResult.availability = result.availability;
											sortResult.experts = result.experts;
											sortResult.freeBusys = result.freeBusys;
											sortResult.isNotesEnabled = result.isNotesEnabled;
											//sortResult.push(result.isNotesEnabled);
												if (event.status) {
													//all code after remote controller will be here
														var events = handleresult(sortResult);
														callback(events);
														  ajaxindicatorstop();
												}}, 
												{escape: true}
										);
									}else{
										Visualforce.remoting.Manager.invokeAction(
											$.remoteServiceReqByExpertIncl,expertIncl,providerId,startStr,endStr,
												function(result, event){
												console.log(result);
											var sortResult =[];
											var serviceRequestME =[];
											var availability =[];
											var experts =[];
											var freeBusys =[];
												for(var i=0;i < result.serviceRequestME.length;i++){
													debugger;
													if(result.serviceRequestME[i].serName != 'Break Hours'){
														serviceRequestME.push(result.serviceRequestME[i]);
													}
												}
											sortResult.serviceRequestME = serviceRequestME;
											sortResult.availability = result.availability;
											sortResult.experts = result.experts;
											sortResult.freeBusys = result.freeBusys;
											sortResult.isNotesEnabled = result.isNotesEnabled;
													if (event.status) {
														//all code after remote controller will be here
															var events = handleresult(sortResult);
															callback(events);
															  ajaxindicatorstop();
													}}, 
													{escape: true}
											);
									}
								}
							} else {
								$('[id$=expertForm]').hide();
								debugger;
								expertIncl.push(expertId);
								Visualforce.remoting.Manager.invokeAction(
									$.remoteServiceReqByExpertIncl,expertIncl,providerId,startStr,endStr,
										function(result, event){
										console.log(result);
											if (event.status) {
												//all code after remote controller will be here
													var events = handleresult(result);
													callback(events);
													  ajaxindicatorstop();
											}}, 
											{escape: true}
									);
							}
						}
						 }else{
							loadEmptyCalendar();
							$('.alert-message').show();
						 }
					  }
					  else if((userType != null || expertId != null) && (userType != '' || expertId != '') && filterType =="SERVICE"){
						var callServiceExclude = false;
						$('[id$=serviceForm] input:checked').each(function() {
									if($(this).val() == ''){
										callServiceExclude = true;
									}
						});
						if(callServiceExclude){
							Visualforce.remoting.Manager.invokeAction(
							$.remoteServiceReqExcludeService,
										serviceExclude,providerId,startStr,endStr,
								function(result, event){
								console.log(result);
											var sortResult =[];
											var serviceRequestME =[];
											var availability =[];
											var experts =[];
											var freeBusys =[];
												for(var i=0;i < result.serviceRequestME.length;i++){
													debugger;
													if(result.serviceRequestME[i].serName != 'Break Hours'){
														serviceRequestME.push(result.serviceRequestME[i]);
													}
												}
											sortResult.serviceRequestME = serviceRequestME;
											sortResult.availability = result.availability;
											sortResult.experts = result.experts;
											sortResult.freeBusys = result.freeBusys;
											sortResult.isNotesEnabled = result.isNotesEnabled;
									if (event.status) {
										//all code after remote controller will be here
											var events = handleresult(sortResult);
											callback(events);
											  ajaxindicatorstop();
									}}, 
									{escape: true}
							);
						}else{
							Visualforce.remoting.Manager.invokeAction(
								$.remoteServiceReqIncludeService,serviceIncl,providerId,startStr,endStr,
									function(result, event){
									console.log(result);
											var sortResult =[];
											var serviceRequestME =[];
											var availability =[];
											var experts =[];
											var freeBusys =[];
												for(var i=0;i < result.serviceRequestME.length;i++){
													debugger;
													if(result.serviceRequestME[i].serName != 'Break Hours'){
														serviceRequestME.push(result.serviceRequestME[i]);
													}
												}
											sortResult.serviceRequestME = serviceRequestME;
											sortResult.availability = result.availability;
											sortResult.experts = result.experts;
											sortResult.freeBusys = result.freeBusys;
											sortResult.isNotesEnabled = result.isNotesEnabled;
										if (event.status) {
											//all code after remote controller will be here
												var events = handleresult(sortResult);
												callback(events);
												  ajaxindicatorstop();
										}}, 
										{escape: true}
								);
						}
					}else{
						loadEmptyCalendar();
						$('.alert-message').show();
					  }
			},
            timezone : "local", 
  			minTime : $.modalCalendarStart,
            maxTime : $.modalCalendarEnd,
            height: 999999999,
            slotDuration: '00:15:00',
             firstDay :1,
            header: {
                right: '',
                 center:'today,prev,title,next',
				 left:'agendaWeek,agendaDay'
            },
             titleFormat: {
                    month: $.modalTitleFormat, // September 2009
                    week: $.modalTitleFormat, // Sep 13 2009
                    day: $.modalTitleFormat  // September 8 2009
                },
            columnFormat :{
				    month: 'ddd',    // Mon
				    week: $.modalColumnFormat, // Mon columnFormat
				    day: 'dddd'      // Monday
				},
			viewDisplay: function (view) {
		        alert('it works');
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
					if(event != undefined){
					//if(QueryIcon != null && event.hasMultiSlot != true){
	              		//var result = sforce.connection.query(QueryIcon+"'"+event.id+"'");
						 // var records = result.getArray("records");
						  var records = event;
						  //for (var j=0; j< records.length; j++) {
						  if(event.hasMultiSlot != true){		
						    for(var i=0;i<$.modalcalIconSet.length;i++){
		                  		if([$.modalcalIconSet[i].field]){
		                  			if(event[$.modalcalIconSet[i].field] == true){
		                  				$(element).find("div.fc-event-inner").prepend('<img style="float: left;padding: 2px;" src="'+$.modalImagePath+''+$.modalcalIconSet[i].icon+'" width="12" height="12">');
		                  			}
		                  		}
		                  	}
						  }
					 }
				}	 
           },
            eventMouseout: function(calEvent, jsEvent) {
                $('.tooltipevetn').hide();
            },
            selectable: true,
            select: function(start, end, allDay) {
            	
            	$('#exceptionPanel').html('');
            	var check = start.format('YYYY-MM-DD');
			    var today = moment(new Date()).format('YYYY-MM-DD');
			    if(!(check < today) || allowPastDayBooking)
			    {
					debugger;
			    	 var isFree = true;
			    	 var CheckedCount = ($("#ExpertAccordion input:checked").length);
			    	 var checkedExpert = '';
					if(CheckedCount == 1){
				    	 $('#ExpertAccordion input:checked').each(function() {
							if($(this).val() != ''){
								checkedExpert =	$(this).val();
							}
						 });
							for(key in freeBusysMap){
								if(key == checkedExpert){
									var freeBusysList = freeBusysMap[key];
									for(var h=0;h<freeBusysList.length;h++){
										if(freeBusysList[h] == check){
											isFree = false;break;
										}
									}
								}
							}
					}
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
	            	if(expertsCleared && (expertId == '' || expertId == null)){
	            		getAvailableExperts(providerId);
	            		 var exphtml= '<option value="">Please Select a Expert</option>';
		                 $('#listService').html(exphtml);
	            	}else{
					
					}
	            	$('#createAppLoading').addClass('active');
	                $('.LV_validation_message').each(function() {
	                    $(this).remove();
	
	                });
					$('#submitButton').css("background-color", "#1796BF");
		             $("#createAppointmentForm").show();
		       	 	 $("#additionalContactDetail").hide();
		             $("#createEventModal .modal-footer").show();
	               		slotblock.hide();
	               		flexiblock.hide();
	                  $('[id$=selCusID]').val('');
	                 var html= '<option value="">Please Select a Service</option>';
	                 $('#availableSlots').html(html);
	                 if(defaultCusType == 'NEW')
	                	$('#existingCustomerName').attr('disabled',true);
	                 else if(defaultCusType == 'EXISTING')
	                 	$('#existingCustomerName').attr('disabled',false);
	                $("#createAppointmentForm").trigger('reset');
				    if(contactId != null && contactId != '' && contactId != undefined){
					    if(CUSTOMEROBJECT == 'CUSTOMER'){
					        getCustomersById(contactId);
					 	}else if(CUSTOMEROBJECT == 'LEAD'){
					        getLeadsById(contactId);
					    }else{
					        getContactsById(contactId);
					    }
					}
					$('[id$=criticalInformation]').html('');
	              	$('[id$=criticalInformation]').hide();
					if(expertId != null && expertId != '' && expertId != undefined)
						var selectedExpert = expertId;
					else
						//var selectedExpert = $('[id$=expertList]').val();
				//document.getElementById('{!$Component.expertForm.expertList}').value;
	   		    selectedSlotStart=start._d;
	   		    $('#selectedDateTime').datepicker("setDate",start._d);
	   		    if(serviceId !='' && serviceId !=null && serviceId != undefined){
	   		    	getExpertsByURLService(serviceId);
	   		    }else{
					debugger;
					if(expertId != null && expertId != '' && expertId != undefined){
					var checkedExpHtml ='<option value="'+expertId+'">'+getCheckedExpert(expertId)+'</option>';
						$('#listExperts').html(checkedExpHtml);
						$('#listExperts').attr('disabled',true);
						getAppBookDet(expertId);
						}	else{
							checkedExpertHTML();
						}
				}
				$('#availableSlots').attr('disabled',true);
				//$('#listService').attr('disabled',true);
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
		    else
		    {
		        // Its a right date
		                // Do something
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
				if(calEvent.serviceName != 'Break Hours'){
          			$('#tooltipLoading').addClass('active');
                    if(calEvent.id != undefined && calEvent.hasMultiSlot == false){
                    			getAppointmentFieldSets(calEvent.id);
                    }else{
                    	$('#tooltipLoading').removeClass('active');
                    }
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
	                       if(popupButtons[i].type == 'standardreschedule' && calEvent.hasMultiSlot == false)     
	                       	buttons +=' <button class="action-button btn2 " title="Reschedule" eventStart="'+moment(calEvent.start).format(''+$.modaldateFormat+'')+'" expertId ="'+calEvent.expertId+'" id="editReSche" providerId="' + calEvent.provider + '" serviceId="' + calEvent.service + '" eventId="' + calEvent.id + '"><i class="fa fa-share"></i></button>';
	                       if(popupButtons[i].type == 'standardnew' && calEvent.hasMultiSlot == false)   
	                        buttons +=' <button class="action-button btn2 " title="New Appointment" id="newApp" conId="' + calEvent.cusId + '" userId="' + calEvent.userId + '" expertId ="'+calEvent.expertId+'" serviceId="' + calEvent.service + '" isPreferredExpert="'+calEvent.isPreferredExpert+'"><i class="fa fa-plus-circle"></i></button>';
	                       else if(popupButtons[i].type == 'standardnew' && calEvent.hasMultiSlot == true)  
	                        buttons +=' <button class="action-button btn2 " title="New Appointment" id="newApp" conId="isMultiSlot" expertId ="'+calEvent.expertId+'" isPreferredExpert="'+calEvent.isPreferredExpert+'" serviceId="' + calEvent.service + '"><i class="fa fa-plus"></i></button>';
	                       
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
                     getAppBookDet(calEvent.expertId);
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
						        Visualforce.remoting.Manager.invokeAction(
				                $.remoteupdateSerReqStatus,params.pk,params.value,
				                    function(result, event){
				                        if (event.status) {
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
				                        }else if (event.type === 'exception') {
											 return d.reject(event.message);
				                        } else {
				                        	 if(event.message)
											 	return d.reject(event.message);
											 else
											 	return d.reject('error in update');
				                        }
				                    }, 
				                    {escape: true}
				                );
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
				}else if(calEvent.serviceName == 'Break Hours'){
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
			    	}
            },
            viewRender : function( view, element ) {
                  defaultView = view.name;
                }
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
        calendar.fullCalendar( 'gotoDate', calendarGoToDate) ;
        $('#colorContent button').css("color",eventColorCode);
        ajaxindicatorstop();
       if($.modalAppointmentNotification){
	        $("#notification-block")
	        .appendTo(".fc-header-right");
	        $("#notification-block").show();
		}
        if(enableAutolaunch){
        	autoLaunch(); 
        }
}

function loadEmptyCalendar(){
		var dataObj= [];
        var calendar = $('#calendar').fullCalendar({
            defaultView: defaultView,
            editable: false,
            events: dataObj,
            height: 999999999,
            slotDuration: '00:15:00',
            firstDay :1,
            header: {
                right: 'agendaWeek,agendaDay',
                 center:'today,prev,title,next',
				 left:''
            },
            titleFormat: {
                month: $.modalTitleFormat, // September 2009
                week: $.modalTitleFormat, // Sep 13 2009
                day: $.modalTitleFormat  // September 8 2009
            }
        });
        calendar.fullCalendar( 'gotoDate', calendarGoToDate);
}
//getServiceDetails Handle function
function handleServiceDetails(result,event) {
    if (event.status) {
       	var serviceBanner = $('<button style="color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;font-size: 12px;cursor:default;font-family: ConfigFont;margin-bottom: 12px;"/>').addClass('button').html('SELECTED SERVICE:&nbsp;'+result.Name+'<i title="remove" class="fa fa-times-circle" id="deleteSelectedService" style="margin-top: -3px;float:right;cursor: pointer;"></i>');
    	$('#colorDetail').after(serviceBanner);
    }else{
    	//handle Exception
    }
}
var isExpflexiTime = false;
//getAppBookDet Handle function
function handleSerResult(result) {
    var html = '';
    isExpflexiTime = false;
    var serviceAvailable = false;
    if (result != null && result != undefined) {
    	if(result.length > 0){
	    	//providerId = result[0].serProvider;
	    	if(result[0].slotType == 'Flexible Time'){
	    		isExpflexiTime = true;
	    	}
    	}
        var selExpert = $('#listExperts').val();
        for (var i = 0; i < result.length; i++) {
            html += '<option value="' + result[i].serId + '">' + result[i].serName + '</option>';
            if(serviceId == result[i].serId){
                	serviceAvailable = true;
                }
        }
        if(isExpflexiTime == true){
        	flexiblock.show();
        	slotblock.hide();
        	reScFlexiBlock.show();
			reScSlotBlock.hide();
        }else{
        	flexiblock.hide();
        	slotblock.show();
        	reScFlexiBlock.hide();
			reScSlotBlock.show();
        }
        $('#listService').html(html);
        if(serviceId != null && serviceId != '' && serviceId != undefined && serviceAvailable == true)
        	$('#listService').val(serviceId);
        else if(eventServiceId != null && eventServiceId != '' && eventServiceId != undefined && eventServiceId != 'undefined')
        	$('#listService').val(eventServiceId);
        $('#listService').trigger('change');
        $('#listService').attr('disabled',false);
        $('#listService').removeClass('cascading-dropdown-loading');
    }
    $('#createAppLoading').removeClass('active');
}
//Appointment Form validation done here
function validateReScheduleForm() {
	if(isExpflexiTime == true)
		return !LiveValidation.massValidate([reScheduleDate_V,reScheduleExp_V,reScheduleDuration_V]);
	else
    	return !LiveValidation.massValidate([reScheduleSlot_V,reScheduleDate_V,reScheduleExp_V]);
}
$('#reScheduleBtn').on('click', function(e) {
    e.preventDefault();
	var selectedDate = moment($('#reScheduleDate').datepicker("getDate")).format(''+$.modaldateFormat+'');
		if(isExpflexiTime == true){
	            	var statTime = moment($('#reScheduleStartTime').timepicker("getTime")).format('hh:mm A');
	            	if($('#reScheduleDuration').val() != null && $('#reScheduleDuration').val() != ''){
	            		var endTime = moment($('#reScheduleStartTime').timepicker("getTime")).add(parseInt($('#reScheduleDuration').val()),'m').format('hh:mm A');
	            	}else{
	            		var endTime = moment($('#reScheduleEndTime').timepicker("getTime")).format('hh:mm A');
	            	}
	      	var selectedSlot = statTime+'_'+endTime;
	      }else{
			var selectedSlot = $('#reScheduleSlots').val();
	      }
           if(validateReScheduleForm() != true){
                  var splitSlot = selectedSlot.split('_');
                  var startGen = moment(selectedDate+' '+splitSlot[0], ""+$.modaldateFormat+" hh:mm A");
                  var endGen = moment(selectedDate+' '+splitSlot[1], ""+$.modaldateFormat+" hh:mm A");
                  var starttime = moment(startGen).format('MM/DD/YYYY hh:mm A');
                  var endtime = moment(endGen).format('MM/DD/YYYY hh:mm A');
                  var id = $('#serReqId').val();
                  var selExp = $('#reScheduleExperts').val();
                  updateAppointment(starttime,endtime,selExp,id,'RESCHEDULE');
             }
        });
var slotblock = $('.isNotFlexi');
var flexiblock = $('.isFlexiTime');
var freeBusysMap ={};
//getServiceRequest  and getAllServiceRequest Handle function
function handleresult(result) {
        var dataObj = [];
        var serReq = result.serviceRequestME;
        var serAvailability = result.availability;
        var freeBusys = [];
        if(result.hasOwnProperty('freeBusys')){
        	freeBusys =result.freeBusys;
        	for(var j = 0; j < (freeBusys.length); j++){
        		var holidayStartTime = freeBusys[j].holidayStart.substr(0,freeBusys[j].holidayStart.indexOf(" "));
        		if(freeBusysMap.hasOwnProperty(freeBusys[j].expertId)){
        			freeBusysMap[freeBusys[j].expertId].push(holidayStartTime);
        		}else{
        			freeBusysMap[freeBusys[j].expertId] = [];
        			freeBusysMap[freeBusys[j].expertId].push(holidayStartTime);
        		}
        	}
        }
        if(result.expertInfo){
            var html='';
            if(result.expertInfo.email)
                html+='<span ><i class="fa fa-envelope"></i>&nbsp;'+result.expertInfo.email+'</span><br/>';
            if(result.expertInfo.phone && isClickToCallEnabled != true) 
                html+='<span class="tel" style="display: block;"><img src="/s.gif" alt="" width="1" height="1" title="" onload="if (self.registerClickToDial) registerClickToDial(this.parentNode, false);"><i class="fa fa-phone"></i>&nbsp;'+result.expertInfo.phone+'<img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" title="Click to dial disabled"></span>';
            else if(result.expertInfo.phone && isClickToCallEnabled == true)
                html+='<a href="javascript:void(0);" onclick="disableClicked(this, "Click to dial disabled");sendCTIMessage("/CLICK_TO_DIAL?DN="+encodeURIComponent('+result.expertInfo.phone+')+"&amp;ID=0039000001Fwg9B&amp;ENTITY_NAME=Contact&amp;OBJECT_NAME="+encodeURIComponent("'+result.expertInfo.name+'"));return false;" title=""><i class="fa fa-phone"></i>&nbsp;'+result.expertInfo.phone+'<img src="/img/btn_dial_inline.gif" alt="Click to dial" width="16" height="10" title="Click to dial" style="display: inline;"><img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" style="display: none;" title="Click to dial disabled"></a>';
            if(result.expertInfo.skills){
                var skills =$("<div/>").html(result.expertInfo.skills).text();
                skills = JSON.parse(skills);
                html+='<div id="skills"><span><strong>Skills: </strong></span>';
                for(var i = 0 ;i < skills.length; i++){
                    html+='<span class="label">'+skills[i].label+'</span>';
                }
                html+='</div>';
            }   
            
            $('#expertContent').html(html);
            var name='';
            if(result.expertInfo.name)
                name+='&nbsp;'+result.expertInfo.name;
                $('#expertHeader').html(name);
            $('#expertDetail').show();
        }
        for (var i = 0; i < (serReq.length); i++) {
        	var hasMultiSlot = false;
        		hasMultiSlot = serReq[i].isMultiBookedSlot;
        	var bookedCount = 0;
        	if(serAvailability.hasOwnProperty(serReq[i].sId)){
        		bookedCount = serAvailability[serReq[i].sId];
        	}
        	if(hasMultiSlot == true &&  (serReq[i].status == 'Canceled' || serReq[i].status == 'Cancelled') ){
            	//do nothing
            }else{
            	dataObj.push({
                'id': serReq[i].sId,
                'title': serReq[i].cName,
                'start': serReq[i].starttime,
                'end': serReq[i].endtime,
                'allDay': '',
                'phone': serReq[i].phone,
                'status': serReq[i].status,
                'address': '',
                'email': serReq[i].email,
                'serviceName': serReq[i].serName,
                'service': serReq[i].serviceId,
                'provider': serReq[i].provider,
                'popurl': serReq[i].url,
                'Notification': serReq[i].Notification,
                'cusId': serReq[i].cusId,
                'expertId' : serReq[i].expertId,
                'hasMultiSlot' : hasMultiSlot,
                'availableCount' : serReq[i].availableCount,
                'bookedCount' : bookedCount,
                'isPreferredExpert' : serReq[i].isPreferredExpert
            });
			}
        }
       return dataObj;
    }
    
     $(document).on('change','input[name="existing"]',function() {
        var selected = $(this).val();
          $('[id$=selCusID]').val('');
          $('[id$=criticalInformation]').html('');
          $('[id$=criticalInformation]').hide();
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
    $('[id$=existingCustomerName]').autocomplete({
          minLength: 2,
          source: function (request, response) {
              if(CUSTOMEROBJECT == 'CUSTOMER'){
                  Visualforce.remoting.Manager.invokeAction(
                    $.remoteSearchCustomers,request.term, function (result, event) {
                      if (event.type == 'exception') {
                          alert(event.message);
                      } else {
                          if (result.length != 0) {
                              $('.ui-helper-hidden-accessible').hide()
                          } else {
                              $('.ui-helper-hidden-accessible').show()
                          }
                          for(var i=0;i< result.length ;i++){
                            var keyPrefix = result[i].Id.substring(0, 3);
					        if(keyPrefix == '003')
					            result[i]['objPrefix']='C';
					        else if(keyPrefix == '00Q')
					           result[i]['objPrefix']='L';
					        else
					           result[i]['objPrefix']='C';
                          }
                          response(result);
                      }
                  });
              }else if(CUSTOMEROBJECT == 'LEAD'){
                  Visualforce.remoting.Manager.invokeAction(
                    $.remoteSearchLeads,request.term, function (result, event) {
                      if (event.type == 'exception') {
                          alert(event.message);
                      } else {
                          if (result.length != 0) {
                              $('.ui-helper-hidden-accessible').hide()
                          } else {
                              $('.ui-helper-hidden-accessible').show()
                          }
                          for(var i=0;i< result.length ;i++){
                            var keyPrefix = result[i].Id.substring(0, 3);
					        if(keyPrefix == '003')
					            result[i]['objPrefix']='C';
					        else if(keyPrefix == '00Q')
					           result[i]['objPrefix']='L';
					        else
					           result[i]['objPrefix']='C';
                          }      
                          response(result);
                      }
                  });
              }else{
                Visualforce.remoting.Manager.invokeAction(
                    $.remoteSearchContacts,request.term, function (result, event) {
                      if (event.type == 'exception') {
                          alert(event.message);
                      } else {
                          if (result.length != 0) {
                              $('.ui-helper-hidden-accessible').hide()
                          } else {
                              $('.ui-helper-hidden-accessible').show()
                          }
                          for(var i=0;i< result.length ;i++){
                            var keyPrefix = result[i].Id.substring(0, 3);
					        if(keyPrefix == '003')
					            result[i]['objPrefix']='C';
					        else if(keyPrefix == '00Q')
					           result[i]['objPrefix']='L';
					        else
					           result[i]['objPrefix']='C';
                          }
                          response(result);
                      }
                  });
              }
          },
          focus: function (event, ui) {
            if(CUSTOMEROBJECT == 'CUSTOMER'){
                 $('[id$=existingCustomerName]').val(ui.item.BKSL2__LastName__c);
            }else if(CUSTOMEROBJECT == 'LEAD'){
                 $('[id$=existingCustomerName]').val(ui.item.LastName);
            }else{
                $('[id$=existingCustomerName]').val(ui.item.LastName);
            }
              return false;
          },
          select: function (event, ui) {
            if(CUSTOMEROBJECT == 'CUSTOMER'){
              $('[id$=FirstName]').val(ui.item.BKSL2__FirstName__c);
              $('[id$=LastName]').val(ui.item.BKSL2__LastName__c);
              $('[id$=Street]').val(ui.item.Street__c);
              $('[id$=City]').val(ui.item.City__c);
              $('[id$=Country]').val(ui.item.Country__c);
              $('[id$=State]').val(ui.item.State__c);
              $('[id$=ZipCode]').val(ui.item.Zip_Code__c);
              $('[id$=Email]').val(ui.item.BKSL2__Email__c);
              $('[id$=Phone]').val(ui.item.BKSL2__Phone__c);
              $('[id$=selCusID]').val(ui.item.id);
              if(ui.item.BKSL2__Critical_Notification__c != undefined && ui.item.BKSL2__Critical_Notification__c != null){
              		$('[id$=criticalInformation]').html(ui.item.BKSL2__Critical_Notification__c);
              		$('[id$=criticalInformation]').show();
              }else{
                $('[id$=criticalInformation]').html('');
              	$('[id$=criticalInformation]').hide();
              }
             }else if(CUSTOMEROBJECT == 'LEAD'){
              $('[id$=FirstName]').val(ui.item.FirstName);
              $('[id$=LastName]').val(ui.item.LastName);
              $('[id$=Street]').val(ui.item.Street);
              $('[id$=City]').val(ui.item.City);
              $('[id$=Country]').val(ui.item.Country);
              $('[id$=State]').val(ui.item.State);
              $('[id$=ZipCode]').val(ui.item.PostalCode);
              $('[id$=Email]').val(ui.item.Email);
              $('[id$=Phone]').val(ui.item.Phone);
              $('[id$=selCusID]').val(ui.item.Id);
              if(ui.item.BKSL2__Critical_Notification__c != undefined && ui.item.BKSL2__Critical_Notification__c != null){
              		$('[id$=criticalInformation]').html(ui.item.BKSL2__Critical_Notification__c);
              		$('[id$=criticalInformation]').show();
              }else{
                $('[id$=criticalInformation]').html('');
              	$('[id$=criticalInformation]').hide();
              }
             }else{
              $('[id$=FirstName]').val(ui.item.FirstName);
              $('[id$=LastName]').val(ui.item.LastName);
              $('[id$=Street]').val(ui.item.MailingStreet);
              $('[id$=City]').val(ui.item.MailingCity);
              $('[id$=Country]').val(ui.item.MailingCountry);
              $('[id$=State]').val(ui.item.MailingState);
              $('[id$=ZipCode]').val(ui.item.MailingPostalCode);
              $('[id$=Email]').val(ui.item.Email);
              $('[id$=Phone]').val(ui.item.Phone);
              $('[id$=selCusID]').val(ui.item.Id);
              if(ui.item.BKSL2__Critical_Notification__c != undefined && ui.item.BKSL2__Critical_Notification__c != null){
              		$('[id$=criticalInformation]').html(ui.item.BKSL2__Critical_Notification__c);
              		$('[id$=criticalInformation]').show();
              }else{
                $('[id$=criticalInformation]').html('');
              	$('[id$=criticalInformation]').hide();
              }
             }
              return false;
          },
      })
     .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        var firstName ='';
        var lastName = '';
        var email = '';
         if(CUSTOMEROBJECT == 'CUSTOMER'){
            if(item.BKSL2__FirstName__c)
                firstName = item.BKSL2__FirstName__c;
            if(item.BKSL2__LastName__c)
                lastName = item.BKSL2__LastName__c;
            if(item.BKSL2__Email__c)
                email = item.BKSL2__Email__c;
         }else if(CUSTOMEROBJECT == 'LEAD'){
            if(item.FirstName)
                firstName = item.FirstName;
            if(item.LastName)
                lastName = item.LastName;
            if(item.Email)
                email = item.Email;
         }else{
            if(item.FirstName)
                firstName = item.FirstName;
            if(item.LastName)
                lastName = item.LastName;
            if(item.Email)
                email = item.Email;
         }
          return $( "<li>" )
            .data( "item.autocomplete", item )
            .append( "" + firstName +" "+lastName+ "<br>" + email + "" )
            .append( "<span class='objLabel'>" + item.objPrefix+"</span>")
            .appendTo( ul );
    };
function handleContactDetails(result, event) {
	     if(event.type === 'exception') {

		   } else if(event.status) {
			   	if(CUSTOMEROBJECT == 'CUSTOMER'){
	              $('[id$=FirstName]').val(result.BKSL2__FirstName__c);
	              $('[id$=LastName]').val(result.BKSL2__LastName__c);
	              $('[id$=Street]').val(result.Street__c);
	              $('[id$=City]').val(result.City__c);
	              $('[id$=Country]').val(result.Country__c);
	              $('[id$=State]').val(result.State__c);
	              $('[id$=ZipCode]').val(result.Zip_Code__c);
	              $('[id$=Email]').val(result.BKSL2__Email__c);
	              $('[id$=Phone]').val(result.BKSL2__Phone__c);
	              $('[id$=selCusID]').val(result.id);
	              $('[id$=existingCustomerName]').val(result.BKSL2__LastName__c);
	              if(result.BKSL2__Critical_Notification__c != undefined && result.BKSL2__Critical_Notification__c != null){
	              		$('[id$=criticalInformation]').html(result.BKSL2__Critical_Notification__c);
	              		$('[id$=criticalInformation]').show();
	              }else{
	                $('[id$=criticalInformation]').html('');
	              	$('[id$=criticalInformation]').hide();
	              }
	             }else if(CUSTOMEROBJECT == 'LEAD'){
	              $('[id$=FirstName]').val(result.FirstName);
	              $('[id$=LastName]').val(result.LastName);
	              $('[id$=Street]').val(result.Street);
	              $('[id$=City]').val(result.City);
	              $('[id$=Country]').val(result.Country);
	              $('[id$=State]').val(result.State);
	              $('[id$=ZipCode]').val(result.PostalCode);
	              $('[id$=Email]').val(result.Email);
	              $('[id$=Phone]').val(result.Phone);
	              $('[id$=selCusID]').val(result.Id);
	              $('[id$=existingCustomerName]').val(result.LastName);
	              if(result.BKSL2__Critical_Notification__c != undefined && result.BKSL2__Critical_Notification__c != null){
	              		$('[id$=criticalInformation]').html(result.BKSL2__Critical_Notification__c);
	              		$('[id$=criticalInformation]').show();
	              }else{
	                $('[id$=criticalInformation]').html('');
	              	$('[id$=criticalInformation]').hide();
	              }
	             }else{
	              $('[id$=FirstName]').val(result.FirstName);
	              $('[id$=LastName]').val(result.LastName);
	              $('[id$=Street]').val(result.MailingStreet);
	              $('[id$=City]').val(result.MailingCity);
	              $('[id$=Country]').val(result.MailingCountry);
	              $('[id$=State]').val(result.MailingState);
	              $('[id$=ZipCode]').val(result.MailingPostalCode);
	              $('[id$=Email]').val(result.Email);
	              $('[id$=Phone]').val(result.Phone);
	              $('[id$=selCusID]').val(result.Id);
	              $('[id$=existingCustomerName]').val(result.LastName);
	              if(result.BKSL2__Critical_Notification__c != undefined && result.BKSL2__Critical_Notification__c != null){
	              		$('[id$=criticalInformation]').html(result.BKSL2__Critical_Notification__c);
	              		$('[id$=criticalInformation]').show();
	              }else{
	                $('[id$=criticalInformation]').html('');
	              	$('[id$=criticalInformation]').hide();
	              }
	             }
	          $(':radio[value="EXISTING"]').attr('checked', true);
              $('#existingCustomerName').attr('disabled',false);
              $('#createAppLoading').removeClass('active');
		   }else{
		   
		   }
}
// Remote functions to interact with Server
//get Appointment Details for mini popup
  function getAppointmentFieldSets(appId) {
      Visualforce.remoting.Manager.invokeAction(
          $.remoteAppointmentFieldSets,appId,handlAppFieldSets);
  }
  function handlAppFieldSets(result,event){
  	if(result != null){
      	var buildAppRec = '';
      	for( var c=0;c<result.length;c++ ){
           if(result[c].value != undefined && result[c].value != null){
      	        	buildAppRec+='<span >';
				    var fieldPath = result[c].fieldPath.toLowerCase(); // convert text to Lowercase
				    if(fieldPath.match('phone')) {
				         if(result[c].value && isClickToCallEnabled != true) 
							buildAppRec+='<img src="/s.gif" alt="" width="1" height="1" title="" onload="if (self.registerClickToDial) registerClickToDial(this.parentNode, false);">'+result[c].value+'<img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" title="Click to dial disabled">';
						 else if(result[c].value && isClickToCallEnabled == true)
							buildAppRec+='<a href="javascript:void(0);" onclick="disableClicked(this, "Click to dial disabled");sendCTIMessage("/CLICK_TO_DIAL?DN="+encodeURIComponent('+result[c].value+')+"&amp;ID=0039000001Fwg9B&amp;ENTITY_NAME=Contact&amp;OBJECT_NAME="+encodeURIComponent("'+selectedContactName+'"));return false;" title="">'+result[c].value+'<img src="/img/btn_dial_inline.gif" alt="Click to dial" width="16" height="10" title="Click to dial" style="display: inline;"><img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" style="display: none;" title="Click to dial disabled"></a>';
				    }else{ 
      	            	buildAppRec+=result[c].value;
      	            }
      	            buildAppRec+='</span>';
           }
          }
          $('.detailsPat span:last').after(buildAppRec);
          $('#tooltipLoading').removeClass('active');
      }
  }
//Get Service Request by Expert
function getAllExpertServiceRequest(expertExclude) {
    ajaxindicatorstart('loading data.. please wait..');
    Visualforce.remoting.Manager.invokeAction(
        $.remoteServiceReqAllexpert,expertExclude,providerId,
         handleresult);
}
//Get All Expert Service Request
function getServiceReqByExpertIncl(expertIncl) {
    ajaxindicatorstart('loading data.. please wait..');
    Visualforce.remoting.Manager.invokeAction(
        $.remoteServiceReqByExpertIncl,expertIncl,providerId,
        handleresult);
}
//get expert details 
function getExpertsByService(serId) {
	reScheduleService = serId;
    Visualforce.remoting.Manager.invokeAction(
        $.remoteExpertsByService,serId,
        handleExpertsByServiceDetails);
}
//get expert details 
function getExpertsByURLService(serId) {
	reScheduleService = serId;
    Visualforce.remoting.Manager.invokeAction(
        $.remoteExpertsByService,serId,
        handleExpertsByURLService);
}
function handleExpertsByURLService(result,event){
    		if(event.type === 'exception') {
            
            } else if(event.status) {
           	if(result != null ){
           		var html ='';
           		for(var i=0;i < result.length;i++){
           			html +='<option value="'+result[i].id+'">'+result[i].name+'</option>';
           		}
           		$('#listExperts').html(html);
           		
           		$('#listExperts').trigger('change');
           	}
           }else{
           
           }
    }
function handleExpertsByServiceDetails(result,event){
    		if(event.type === 'exception') {
            
            } else if(event.status) {
           	if(result != null ){
           		var html ='<option value="">Choose Expert</option>';
           		for(var i=0;i < result.length;i++){
           			html +='<option value="'+result[i].id+'">'+result[i].name+'</option>';
           		}
           		$('#reScheduleExperts').html(html);
           		$('#reScheduleExperts').val(reScheduleExpert);
           	}
           }else{
           
           }
    }
//Create new Appointment Request
function bookAppointment(bookinfo) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteBookAppointment,
        bookinfo, handleBookResult);
}
$(document).on('change', '#listExperts', function() {
        var selExp = $('#listExperts').val();
        $('#listService').attr('disabled',true);
        $('#availableSlots').attr('disabled',true);
        $('#listService').addClass('cascading-dropdown-loading');
        removeValidationMessages();
        $('#listService').html('<option value="">Select Expert</option>');
        if(selExp != null && selExp != ''){
        	getAppBookDet(selExp);
        	var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('YYYY-MM-DD');
        	getExpertWorkingHour(selExp,selectedDate);
        	$('#availableSlots').html('<option value="">Select service</option>');
        }else{
        	$('#availableSlots').html('<option value="">Select service</option>');
        }
});
$(document).on('change', '#reScheduleExperts', function() {
 if($(this).val() != null && $(this).val() != ''){
 	$('#reScheduleLoading').addClass('active');
 	var selExp = $(this).val();
 		getAppBookDet(selExp);
 		var selectedDate = moment($('#reScheduleDate').datepicker('getDate')).format('YYYY-MM-DD');
 		getreScheduleExpertWorkingHour(selExp,selectedDate);
		 if(reScheduleService != null && reScheduleService != undefined){
		     var serId = reScheduleService;
		     $('#nextSlot').html('');
		     $('#prevSlot').html('');
		     removeValidationMessages();
		     getreScheduleSlot(serId,eventType,providerId,selExp);
		  }
  }else{
  	$('#reScheduleSlots').html('<option value=""> Select Expert</option>');
  }
});
//get Provider Id and services details for appointment
function getAppBookDet(expertId) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteAppBookDet,
        expertId, handleSerResult);
}
function getExpertWorkingHour(expertId,selectedDateStr) {
	$('#selectedStartTime').attr('disabled',true);
	$('#selectedEndTime').attr('disabled',true);
    Visualforce.remoting.Manager.invokeAction(
        $.remoteGetExpertWorkingHour,
        expertId,selectedDateStr, handleWHResult);
}
function getreScheduleExpertWorkingHour(expertId,selectedDateStr) {
	$('#reScheduleStartTime').attr('disabled',true);
	$('#reScheduleEndTime').attr('disabled',true);
    Visualforce.remoting.Manager.invokeAction(
        $.remoteGetExpertWorkingHour,
        expertId,selectedDateStr, handlereScheduleWHResult);
}
//get contact details 
function getContactsById(conId) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteContactsById,conId,
        handleContactDetails);
}
//get Customers details 
function getCustomersById(conId) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteCustomersById,conId,
        handleContactDetails);
}
//get Lead details 
function getLeadsById(conId) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteLeadsById,conId,
        handleContactDetails);
}
//getServiceDetails like service duration 
function getServiceDetailsById(serId) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteServiceDetailsById, serId,
        handleServiceDetails);
}
var reSchService = null;
//get available Slots for service 
function getreScheduleSlot(serID,eventType,Provider,expId) {
	reSchService = serID;
    Visualforce.remoting.Manager.invokeAction(
        $.remoteGetAvailableSlot,serID,eventType,Provider,expId,
        handlereScheduleSlot);
}
//get available Slots for service  
function getAvailableSlot(serID,eventType,Provider,expId) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteGetAvailableSlot,serID,eventType,Provider,expId,
        handleAvailableSlot);
}
var callType = 'SCHEDULE';
//get available Slots for service by Selected Date  
function getAvailableSlotByDate(serID,eventType,Provider,expId,selectedDate,type) {
	callType = type;
    Visualforce.remoting.Manager.invokeAction(
        $.remoteExpertAvailableSlotByDate,serID,eventType,Provider,expId,selectedDate,
        handleAvailableSlotByDate);
}
function handleAvailableSlotByDate(result,event){
	if(event.status){
			if(result.length > 0){
				if(callType == 'SCHEDULE'){
					var html='';
					var isDateAvailable = false;
					var isSlotsAvailable = false;
					var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('D_M_YYYY');
					result = removeBookedSlot(result,selectedDate);
			             html += '<option value=""> Select a Slot</option>';
	                for (var k = 0; k < result.length; k++) {
	                	availableSlotsNew.availableSlots.push(result[k]);
	                    var slots = result[k].slots;
	                    if(result[k].strDate == selectedDate){
	                       isDateAvailable = true;
	                        for (var i = 0; i < slots.length; i++) {
	                        	isSlotsAvailable = true;
	                            html += '<option value="'+slots[i].slot+'">'+slots[i].slot.replace('_',' to ')+'</option>';
	                        }
	                    }
	                }
	                 if(isDateAvailable == true && isSlotsAvailable == false){
					        html ='';
					        html += '<option value="">Slots not available for the day</option>';
					  }else if(isDateAvailable == true){
					  	    html ='';
					        html += '<option value="WAITINGLIST">WAITING LIST</option>';
					  }
					  $('#availableSlots').html(html);
				}else if(callType == 'RESCHEDULE'){
							var html='';
							var isDateAvailable = false;
							var isSlotsAvailable = false;
							var selectedDate = moment($('#reScheduleDate').datepicker('getDate')).format('D_M_YYYY');
							result = removeBookedSlot(result,selectedDate);
					             html += '<option value=""> Select a Slot</option>';
			                for (var k = 0; k < result.length; k++) {
			                	reScheduleSlot.availableSlots.push(result[k]);
			                    var slots = result[k].slots;
			                    if(result[k].strDate == selectedDate){
			                       isDateAvailable = true;
			                        for (var i = 0; i < slots.length; i++) {
			                        	isSlotsAvailable = true;
			                            html += '<option value="'+slots[i].slot+'">'+slots[i].slot.replace('_',' to ')+'</option>';
			                        }
			                    }
			                }
			                 if(isDateAvailable == true && isSlotsAvailable == false){
							        html ='';
							        html += '<option value="">Slots not available for the day</option>';
							  }
							  $('#reScheduleSlots').html(html);
				}
			}else{
				var html = '<option value="">Slots not available for the day</option>';
				if(callType == 'SCHEDULE'){
				    $('#availableSlots').html(html);
				}else if(callType == 'RESCHEDULE'){
				    $('#reScheduleSlots').html(html);
				}
			}
		}
}
function handlereScheduleWHResult(result,event){
	if(event.status){
			$('#reScheduleStartTime').attr('disabled',false);
			$('#reScheduleEndTime').attr('disabled',false);
			var start =result.startTime.replace(' ','').toLowerCase();
			var end = result.endTime.replace(' ','').toLowerCase();
			$('#reScheduleStartTime').timepicker({
	            'scrollDefaultNow': 'true',
		            'minTime': start,
	    			'maxTime': end,
	                'closeOnWindowScroll': 'true',
	                'forceRoundTime': true,
	                'step' :15
	        }).on('changeTime', function(){
	            var new_end = $(this).timepicker('getTime');
	            new_end.setMinutes(new_end.getMinutes()+15);
	           $('#reScheduleEndTime').timepicker('setTime', new_end);
	        });
	        if(selectedEventStart != null && selectedEventStart !='Invalid Date'){
	        	var now = selectedEventStart;
		    	$('#reScheduleStartTime').timepicker('setTime', now);
		   	}else{
		   		var now = new Date();
		    	$('#reScheduleStartTime').timepicker('setTime', now);
		   	}
	        $('#reScheduleEndTime').timepicker({
	            'scrollDefaultNow': 'true',
	                'closeOnWindowScroll': 'true',
	                'minTime': start,
	    			'maxTime': end,
	                 'step' :15
	        }).on('changeTime', function(){
	            var new_start = moment($('#reScheduleStartTime').timepicker('getTime'));
	            var new_end = moment($(this).timepicker('getTime'));
	            var minutes = new_end.diff(new_start,'minutes');
	           $('#reScheduleDuration').val(minutes);
	        });
	        $('#reScheduleStartTime').trigger('changeTime');
	         $('#reScheduleEndTime').trigger('changeTime');
	}
	$('#reScheduleLoading').removeClass('active');
}
$(document).on('change paste keyup','#selectedDuration',function(){
	if($(this).val() != null && $(this).val() != '' && $(this).val() !='NaN'){
		var duration = parseInt($(this).val());
		var new_end = $('#selectedStartTime').timepicker('getTime');
		 new_end.setMinutes(new_end.getMinutes()+duration);
		$('#selectedEndTime').timepicker('setTime', new_end);
	}
});
$(document).on('change paste keyup','#reScheduleDuration',function(){
	if($(this).val() != null && $(this).val() != '' && $(this).val() !='NaN'){
		var duration = parseInt($(this).val());
		var new_end = $('#reScheduleStartTime').timepicker('getTime');
		 new_end.setMinutes(new_end.getMinutes()+duration);
		$('#reScheduleEndTime').timepicker('setTime', new_end);
	}
});
function handleWHResult(result,event){
	if(event.status){
		if(result.startTime && result.endTime){
			var start =result.startTime.replace(' ','').toLowerCase();
			var end = result.endTime.replace(' ','').toLowerCase();
			$('#selectedStartTime').attr('disabled',false);
			$('#selectedEndTime').attr('disabled',false);
			$('#selectedStartTime').timepicker({
	            'scrollDefaultNow': 'true',
		            'minTime': start,
	    			'maxTime': end,
	                'closeOnWindowScroll': 'true',
	                'forceRoundTime': true,
	                'step' :15
	        }).on('changeTime', function(){
	            var new_end = $(this).timepicker('getTime');
	            new_end.setMinutes(new_end.getMinutes()+15);
	           $('#selectedEndTime').timepicker('setTime', new_end);
	        });
	        if(selectedSlotStart != null && selectedSlotStart !='Invalid Date'){
	        	var now = selectedSlotStart;
		    	$('#selectedStartTime').timepicker('setTime', now);
		   	}else{
		   		var now = new Date();
		    	$('#selectedStartTime').timepicker('setTime', now);
		   	}
	        $('#selectedEndTime').timepicker({
	            'scrollDefaultNow': 'true',
	                'closeOnWindowScroll': 'true',
	                'minTime': start,
	    			'maxTime': end,
	                 'step' :15
	        }).on('changeTime', function(){
	            var new_start = moment($('#selectedStartTime').timepicker('getTime'));
	            var new_end = moment($(this).timepicker('getTime'));
	            var minutes = new_end.diff(new_start,'minutes');
	           $('#selectedDuration').val(minutes);
	        });
	        $('#selectedStartTime').trigger('changeTime');
	         $('#selectedEndTime').trigger('changeTime');
	    }
	}
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
	       }else{
		       	var selExp = $('#reScheduleExperts').val();
		        var selectedDate = moment($('#reScheduleDate').datepicker("getDate")).format('D_M_YYYY');
		        if(selExp != null && selExp != '' && selExp != undefined)
		       		 getAvailableSlotByDate(reSchService,eventType,providerId,selExp,selectedDate,'RESCHEDULE');
	       }
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
//get Provider Id and services details for appointment
function updateAppointment(starttime,endtime,expert,id,updateType) {
    Visualforce.remoting.Manager.invokeAction(
        $.remoteupdateAppointment,
        starttime,endtime,expert,id,updateType,$.modalDefaultStatus,handleUpdateResult);
}
function handleUpdateResult(result){
    $("#reScheduleModal").modal('hide');
    $("#reScheduleForm").trigger('reset');
    refreshCalendar();
}
var availableSlotsNew;
var serDuration
function handleAvailableSlot(result){
   availableSlotsNew = result;
   var selectedDate = moment($('#selectedDateTime').datepicker('getDate')).format('D_M_YYYY');
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
   if($('#selectedDateTime').val() == ''){
   		datenotSelected = true;
   }
   if(isSlotsAvailable == false  && isWaitingListAvailable == 0){
       html ='';
       if(datenotSelected == true){
       	html += '<option value="">Choose Date</option>';
       }else{
       	var serId = $('#listService').val();
	    $('#availableSlots').attr('disabled',true);
	    $('#availableSlots').addClass('cascading-dropdown-loading');
	     var html = '<option value="">Select a Service</option>';
	    $('#availableSlots').html(html);
	    var selExp =  $('#listExperts').val();
	    if(selExp != '' && selExp != null)
	    	getAvailableSlotByDate(serId,eventType,providerId,selExp,selectedDate,'SCHEDULE');
       }
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
       $('#availableSlots').html(html);
   }else{
       $('#availableSlots').html(html);
	       if(selectedSlotStart != undefined && selectedSlotStart != null){ 
		           var now = selectedSlotStart;
		           now.setMinutes(serDuration*Math.round(now.getMinutes()/serDuration));
		           var h12h = now.getHours();
		           var m12h = now.getMinutes();
		           var ampm;
		           if (h12h >= 0 && h12h < 12) {
		               if (h12h === 0) {
		                   h12h = 12;
		               }
		               ampm = "AM";
		           }
		           else {
		               if (h12h > 12) {
		                   h12h -= 12;
		               }
		
		               ampm = "PM";
		           }
		           if(h12h < 10){
		                   h12h ='0'+h12h;
		           }
		           var timeString = h12h + ":" + ("00" + m12h).slice(-2) + " " + ampm;
		           $('#availableSlots option[value^="'+timeString+'_"]').attr('selected', 'selected');
		   }
   		}
   		 $('#availableSlots').attr('disabled',false);
		 $('#availableSlots').removeClass('cascading-dropdown-loading');
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
     //Event update Click function (UPDATE Button)
    $(document).on('click', '#appUpdate', function() {
    		$('#serReqUpdateErrorPanel').html('');
            var status = $("#appSelStatus").val();
            var notes = $("#appNotes").val();
            var appId = $("#appId").val();
            if(appId != null && appId != undefined){
                var objSerReq={};
                objSerReq['BKSL2__Status__c'] = status;
                objSerReq['BKSL2__Notes__c'] = notes;
                objSerReq['Id'] = appId;
                updateObjSerRequest(objSerReq);
            }
    });
    $(document).on('change','#reScheduleSlots',function(){
      var selectedSlot = $(this).val();
    	if(selectedSlot != null && selectedSlot != '' && selectedSlot != undefined){
    	 var selectedDate = moment($('#reScheduleDate').datepicker("getDate")).format(''+$.modaldateFormat+'');
	     var splitSlot = selectedSlot.split('_');
	     var startGen = moment(selectedDate+' '+splitSlot[0], ""+$.modaldateFormat+" hh:mm A");
	     var starttime = moment(startGen).format('MM/DD/YYYY hh:mm A');
	     var serReqId = $('#serReqId').val();
    	 findPrevNextBookedSlot(serReqId,starttime);
    	}
    });
    //get prev Next slot Details
    function findPrevNextBookedSlot(serId,starttime) {
        Visualforce.remoting.Manager.invokeAction(
            $.remotefindPrevNextBookedSlot,serId,starttime,handlSlotDetails);
    }
    function handlSlotDetails(result){
    	if(result.hasOwnProperty('prevSlot')){
    		if(result.prevSlot.serName != null){
	    		if($.modalStatus.hasOwnProperty(result.prevSlot.status))
    				var style ='style="color:'+$.modalStatus[result.prevSlot.status].color+'"';
	    		var html = '';
	    		html+='<h2>'+result.prevSlot.serName+'&nbsp;<strong '+style+' >'+result.prevSlot.status+'</strong></h2>';
	    		html+='<p>'+result.prevSlot.startTime+'</p>';
	    		$('#prevSlot').html(html);
	    	}else{
	    		var html = '';
	    		$('#prevSlot').html(html);
	    	}
    	}
    	if(result.hasOwnProperty('nextSlot')){
    		if(result.nextSlot.serName != null){
	    		if($.modalStatus.hasOwnProperty(result.nextSlot.status))
    				var style ='style="color:'+$.modalStatus[result.nextSlot.status].color+'"';
	    		var htmlnext = '';
	    		htmlnext+='<h2>'+result.nextSlot.serName+'&nbsp;<strong '+style+'>'+result.nextSlot.status+'</strong></h2>';
	    		htmlnext+='<p>'+result.nextSlot.startTime+'</p>';
	    		$('#nextSlot').html(htmlnext);
	    	}else{
	    		var html = '';
	    		$('#nextSlot').html(html);
	    	}
    	}
    }
    //get Appointment Details
    function getAppointmentDetails(appId) {
        Visualforce.remoting.Manager.invokeAction(
            $.remoteAppointmentDetails,appId,handlAppDetails);
    }
    function handlAppDetails(result){
        if(result != null){
            if(result.serName != null && result.serName != undefined)
                $("#appServiceName").html(result.serName);
            if(result.startTime != null && result.startTime != undefined)
                $("#appStartDate").html(result.startTime.split(' ')[0]);
            if(result.startTime != null && result.startTime != undefined)
                $("#appStartTime").html(result.startTime.split(' ')[1]+' '+result.startTime.split(' ')[2]);
            if(result.endTime != null && result.endTime != undefined)
                $("#appEndTime").html(result.endTime.split(' ')[1]+' '+result.startTime.split(' ')[2]);
            $("#appSelStatus").val(result.status);
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
        		    	$("#appSelStatus").val(params.value);
        		    	    if($.modalStatus.hasOwnProperty(params.value)){
                     			$('.appStatusColor').css({
                                	background :''+ $.modalStatus[params.value].color+'',
                                	color : '#FFF',
  							   });
                     			$('#edit-button').removeClass('fa-chevron-up');
                     			$('#edit-button').addClass('fa-chevron-down');
                             }
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
        }
        
        /*if(result.ContactRec != null){
        	contactRec = result.ContactRec;
        	var buildContactRec = '';
        	for( var c=0;c<contactRec.length;c++ ){
	        	buildContactRec+='<div class="row">';
	            buildContactRec+='<div class="col-xs-6">'+contactRec[c].label+'</div>';
	            if(contactRec[c].label.toLowerCase().match('phone')){
	               if(contactRec[c].value && isClickToCallEnabled != true) 
					  buildContactRec+='<span class="tel" style="display: block;"><img src="/s.gif" alt="" width="1" height="1" title="" onload="if (self.registerClickToDial) registerClickToDial(this.parentNode, false);">'+contactRec[c].value+'<img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" title="Click to dial disabled"></span>';
				   else if(contactRec[c].value && isClickToCallEnabled == true)
					  buildContactRec+='<a href="javascript:void(0);" onclick="disableClicked(this, "Click to dial disabled");sendCTIMessage("/CLICK_TO_DIAL?DN="+encodeURIComponent('+contactRec[c].value+')+"&amp;ID=0039000001Fwg9B&amp;ENTITY_NAME=Contact&amp;OBJECT_NAME="+encodeURIComponent("'+selectedContactName+'"));return false;" title="">'+contactRec[c].value+'<img src="/img/btn_dial_inline.gif" alt="Click to dial" width="16" height="10" title="Click to dial" style="display: inline;"><img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" style="display: none;" title="Click to dial disabled"></a>';
	            }else{
		            if(contactRec[c].value != undefined && contactRec[c].value != null)
		            	buildContactRec+='<div class="col-xs-6" id="appConName">'+contactRec[c].value+'</div>';
		            else
		            	buildContactRec+='<div class="col-xs-6" id="appConName"></div>';
	           	}
	            buildContactRec+='</div>';
	            if((contactRec.length -1) != c)
	            	buildContactRec+='<hr/>';
            }
            $('#dynamicContactDetails').html(buildContactRec);
        }*/
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
                        if($.modalStatus.hasOwnProperty(serReqHistory[i].status)){
                            bgcolor = $.modalStatus[serReqHistory[i].status].color;
                        }
                    }
                }
                html+='<div class="timeline-icon bg-'+serReqHistory[i].status.replace(' ','-').toLowerCase()+'" style="background:'+bgcolor+'" >';
                html+='<i class="entypo-feather"></i>';
				html+='</div>';
				html+='<div class="timeline-label">';
				html+='<h2><a target="'+$.modalLinkTarget+'" href="/'+serReqHistory[i].serReqId+'">SERVICE:&nbsp;'+serReqHistory[i].serName+'</a></h2>';
        		if(serReqHistory[i].note != null && serReqHistory[i].note != undefined)
					html+='<p>'+serReqHistory[i].note+'</p>';
				html+='<p>Time:'+serReqHistory[i].starttime+'</p>';
				html+='</div></div></article>';
			}
			 $('#serReqTimeline').html(html);
        }
		}/*else{
			var html='';
			var serReqHistory = result.serReqHistory;
			html+='<article class="timeline-entry">';
				html+='<div class="timeline-entry-inner">';
				var bgcolor = '#000;';
                if($.modalStatus){
                    for(key in $.modalStatus){
                        if($.modalStatus.hasOwnProperty(serReqHistory[i].status)){
                            bgcolor = $.modalStatus[serReqHistory[i].status].color;
                        }
                    }
                }
                html+='<div class="timeline-icon bg-'+serReqHistory[i].status.replace(' ','-').toLowerCase()+'" style="background:'+bgcolor+'" >';
                html+='<i class="entypo-feather"></i>';
				html+='</div>';
				html+='<div class="timeline-label">';
				html+='<h2><a target="'+$.modalLinkTarget+'" href="/'+serReqHistory[i].serReqId+'">SERVICE:&nbsp;'+serReqHistory[i].serName+'</a></h2>';
					//html+='<p>' '</p>';
				html+='<p>Time:</p>';
				html+='</div></div></article>';
		}*/
            $('#customerRecord a:first').tab('show')
            $("#serReqDetails").modal({
						    backdrop: 'static',
						    keyboard: false  // to prevent closing with Esc button (if you want this too)
						});
    }
    //get Appointment Details
    function getMultiAppointmentDetails(appId) {
        Visualforce.remoting.Manager.invokeAction(
            $.remoteMultiAppointmentDetails,appId,handleMultiAppDetails);
    }
    function handleMultiAppDetails(result){
        if(result != null){
            if(result.serName != null && result.serName != undefined)
                $("#appMultiServiceName").html(result.serName);
            if(result.startTime != null && result.startTime != undefined)
                $("#appMultiStartDate").html(result.startTime.split(' ')[0]);
            if(result.startTime != null && result.startTime != undefined)
               $("#appMultiStartTime").html(result.startTime.split(' ')[1]+' '+result.startTime.split(' ')[2]);
            if(result.endTime != null && result.endTime != undefined)
                $("#appMultiEndTime").html(result.endTime.split(' ')[1]+' '+result.endTime.split(' ')[2]);
            $("#appMultiSelStatus").val(result.status);
            
            $("#appMultiId").val(result.id);
        }
        if(result.contactList != null){
        	var contactList = result.contactList;
        	$('#loadContacts').html('');
        	for(var i=0;i<contactList.length;i++){
        		var html='';
        		html+='<tr>';
				html+= '<td><a style="color: #015ba7;" target="'+$.modalLinkTarget+'" href="/'+contactList[i].Id+'">'+contactList[i].name+'</a></td>';
                html+= ' <td>';
                if(contactList[i].phone != null && contactList[i].phone != undefined){
                	if(contactList[i].phone && isClickToCallEnabled != true) 
					  html+='<span class="tel" ><img src="/s.gif" alt="" width="1" height="1" title="" onload="if (self.registerClickToDial) registerClickToDial(this.parentNode, false);">'+contactList[i].phone+'<img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" title="Click to dial disabled"></span>';
				   else if(contactList[i].phone && isClickToCallEnabled == true)
					  html+='<a href="javascript:void(0);" onclick="disableClicked(this, "Click to dial disabled");sendCTIMessage("/CLICK_TO_DIAL?DN="+encodeURIComponent('+contactList[i].phone+')+"&amp;ID=0039000001Fwg9B&amp;ENTITY_NAME=Contact&amp;OBJECT_NAME="+encodeURIComponent("'+contactList[i].name+'"));return false;" title="">'+contactList[i].phone+'<img src="/img/btn_dial_inline.gif" alt="Click to dial" width="16" height="10" title="Click to dial" style="display: inline;"><img src="/img/btn_nodial_inline.gif" alt="Click to dial disabled" width="16" height="10" style="display: none;" title="Click to dial disabled"></a>';
	            }
                html+= ' </td>';
                html+= ' <td>';
                if(contactList[i].email != null && contactList[i].email != undefined)
                	html+= contactList[i].email;
                html+= ' </td>';
                html+= ' <td>';
                html+= '<span class="multiStatusBlock'+i+'"><span class="multiAppStatusColor'+i+'"><i class="fa fa-check" ></i></span>&nbsp;<a href="#"  id="status'+i+'" data-type="select" data-pk="'+contactList[i].serReqId+'"  data-title="Select status"></a><span>';
                html+= ' </td>';
                html+= '</tr>';
               $('#loadContacts').append(html);
               if($.modalStatus.hasOwnProperty(contactList[i].serReqStatus)){
					$('.multiAppStatusColor'+i+'').css({
						background :''+ $.modalStatus[contactList[i].serReqStatus].color+'',
						color : '#FFF',
					 });
				 }
               var statusColorSelector = 'multiAppStatusColor'+i;
               $('#status'+i).editable({
				value: contactList[i].serReqStatus,  
				savenochange :true,
				showbuttons :false,
				url: function(params) {
					var d = new $.Deferred;
					if(params.pk === '' && params.pk === null && params.pk === undefined){
						return d.reject('error in update'); //returning error via deferred object
					} else {
						//async saving data in js model
						Visualforce.remoting.Manager.invokeAction(
						$.remoteupdateSerReqStatus,params.pk,params.value,
							function(result, event){
								if (event.status) {
									if($.modalStatus.hasOwnProperty(params.value)){
										$('.'+statusColorSelector).css({
											background :''+ $.modalStatus[params.value].color+'',
											color : '#FFF',
										 });
									 }
									 d.resolve();
								}else if (event.type === 'exception') {
									 return d.reject(event.message);
		                        } else {
		                        	 if(event.message)
									 	return d.reject(event.message);
									 else
									 	return d.reject('error in update');
		                        }
							}, 
							{escape: true}
						);
						return d.promise();
					}
				},
				source: statusSource
				});
			}
			
        }
            $('#multiCustomer a:first').tab('show')
            $("#serReqMultiDetails").modal({
						    backdrop: 'static',
						    keyboard: false  // to prevent closing with Esc button (if you want this too)
						});
    }
     //Update Appointment Details
    function updateObjSerRequest(objSerReq) {
        Visualforce.remoting.Manager.invokeAction(
            $.remoteupdateObjSerRequest,
            objSerReq, handleUpdateObjSerReq);
    }
    function handleUpdateObjSerReq(result,event){
        if(event.status){
        	$('#serReqUpdateErrorPanel').html('');
	        //Update Details
	        $("#serReqDetails").modal('hide');
	        refreshCalendar();
	     }else if(event.type == 'exception'){
	     	$('#serReqUpdateErrorPanel').html(event.message);
	     }else{
	     	if(event.message)
	     		$('#serReqUpdateErrorPanel').html(event.message);
	     	else
	     		$('#serReqUpdateErrorPanel').html('Error in update');
	     }
    }
    function removeBookedSlot(slotJson,selectedDate){
        for (var k = 0; k < slotJson.length; k++) {
      	 var avaiableSlot = [];
      	var selectedSlots = slotJson[k].slots;
        var bookedSlot = {};
      	 if (selectedDate == slotJson[k]['strDate']) {
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
		slotJson[k].slots = avaiableSlot;
	}
    }
    return slotJson;
    }
       //get details of available experts
    function getAvailableExperts(providerId) {
        Visualforce.remoting.Manager.invokeAction(
            $.remoteAvailableExperts,providerId,handleAvailableExperts);
    }
    function handleAvailableExperts(result,event){
     		if(event.type === 'exception') {
		    
		    } else if(event.status) {
		   		var optionHtml='<option value="">Choose Expert</option>';
		   		var optionSerHtml='<option value="">Select Expert</option>';
				$('#listService').html(optionSerHtml);
		   			var expertOption =[];
					if(result.length>0){
		          for(var k = 0; k < (result.length); k++){ 
           			optionHtml +='<option value="'+result[k].id+'">'+result[k].name+'</option>';
	          	  }
				  }else{
					loadEmptyCalendar();
					$('[id$=expertForm] input').iCheck('uncheck');
				  }
				  if(userType=='Admin'){
	          	  $('#listExperts').html(optionHtml);
	          	  }else if(userType=='Expert'){
					var checkedExpHtml ='<option value="'+expertId+'">'+getCheckedExpert(expertId)+'</option>';
						$('#listExperts').html(checkedExpHtml);
						$('#listExperts').attr('disabled',true);
						getAppBookDet(expertId);
						$('#listService').attr('disabled',false);
				  }
		   }else{
		   
		   }
    
    }
	//get details of available experts
    function getCheckedExperts(providerId) {
        Visualforce.remoting.Manager.invokeAction(
            $.remoteAvailableExperts,providerId,handleCheckedExperts);
    }
	function handleCheckedExperts(result,event){
     		if(event.type === 'exception') {
		    
		    } else if(event.status) {
				mapCheckedExpert =  new Object();
				for(var j = 0; j < (result.length); j++){
					mapCheckedExpert[result[j].id]=result[j].name;
			    }
		   }else{
		   
		   }
    }

	function getCheckedExpert(k){
				 return mapCheckedExpert[k];
				}
	function checkedExpertHTML(){
		debugger;
		var checkedExpHtml='<option value="">Choose Expert</option>';
		var optionSerHtml='<option value="">Select Expert</option>';
		$('#listService').html(optionSerHtml);
					$('#ExpertAccordion input:checked').each(function() {
							if($(this).val() != ''){
							var checkedExpertList=($(this).val());
							checkedExpHtml +='<option value="'+checkedExpertList+'">'+getCheckedExpert(checkedExpertList)+'</option>';
							}
					});
					$('#listExperts').html(checkedExpHtml);
					$('#listExperts').attr('disabled',false);
	}
   function removeValidationMessages(){
   				$('.LV_validation_message').each(function() {
                        $(this).remove();
    
                    });
   }
   $(document).on('click','button',function(){
        	if($(this).data('dismiss') == 'modal'){
        		if(retToURL != '' && retToURL != null)
                	window.location = retToURL;
        	}
        });
   function refreshAndClose(){
   		if(retToURL != '' && retToURL != null){
           window.location = retToURL;
        }else{
	         $("#createAppointmentForm").trigger('reset');
	         $("#createEventModal").modal('hide');
	         refreshCalendar();
        }
   } 
   $(document).on('click','#deleteSelectedService',function(){
   		$(this).parent().remove();
   		serviceId = '';
   });
   $(document).on('click','#customerRecord a',function(){
		var selectedTab = $(this).attr('href');if(selectedTab == '#ServiceView'){
				$('#serReqDetails .modal-footer button').show();
		}else{
				$('#serReqDetails .modal-footer button').hide();
		}
	});
	$(document).ready(function(){
//$('input').iCheck('check');
	  $('#expertPanel input').iCheck({
	    checkboxClass: 'icheckbox_square-grey',
	    radioClass: 'icheckbox_square-grey',
	    increaseArea: '20%' // optional
	  });
	  $('#FilterType input').iCheck({
	    checkboxClass: 'icheckbox_square-grey',
	    radioClass: 'iradio_square-grey',
	    increaseArea: '20%' // optional
	  });
	  
	});
	function refreshCheckBoxIcon(){
		$('#expertPanel input').iCheck({
	    checkboxClass: 'icheckbox_square-grey',
	    radioClass: 'iradio_square-grey',
	    increaseArea: '20%' // optional
	  });
	}
	var serviceIncl = [];
	var serviceExclude = [];
	$(document).on('ifChecked','[id$=serviceForm] input', function(event){
		serviceIncl=[];
	   var checkedValue = $(this).val();
		if(checkedValue == ''){
			//enableAutolaunch=false;
			serviceExclude=[];
		$('[id$=serviceForm] input').iCheck('check');
		}else{
		$('[id$=serviceForm] input:checked').each(function() {
			serviceIncl.push($(this).val());
		});
	}
	$('#calendar').fullCalendar( 'refetchEvents' );
	$('#calendar').fullCalendar('render');
	refreshCheckBoxIcon();
	});
	
	$(document).on('ifUnchecked','[id$=serviceForm] input', function(event){
		serviceIncl=[];
		enableAutolaunch=false;
	   var checkedValue = $(this).val();
	   if(checkedValue == ''){
	   $('[id$=serviceForm] input:checked').each(function() {
				$(this).prop('checked',false);
		});

	   }else{
	    $('[id$=serviceForm] input').each(function() {
				if($(this).val() == ''){
					$(this).prop('checked',false);
				}
				});
	   $('[id$=serviceForm] input:checked').each(function() {
			serviceIncl.push($(this).val());
		});
		}
		$('#calendar').fullCalendar( 'refetchEvents' );
		$('#calendar').fullCalendar('render');
		refreshCheckBoxIcon();
	});
	$(document).on('ifChecked','[id$=expertForm] input', function(event){
	expertIncl=[];
	   var checkedValue = $(this).val();
		if(checkedValue == ''){
		//enableAutolaunch=false;
		expertExclude=[];
		$('[id$=expertForm] input').iCheck('check');
		}else{
		$('[id$=expertForm] input:checked').each(function() {
			expertIncl.push($(this).val());
		});
	}
	$('#calendar').fullCalendar( 'refetchEvents' );
	$('#calendar').fullCalendar('render');
	refreshCheckBoxIcon();
	});
	
	$(document).on('ifUnchecked','[id$=expertForm] input', function(event){
	expertIncl=[];
	enableAutolaunch=false;
	   var checkedValue = $(this).val();
	   if(checkedValue == ''){
	   $('[id$=expertForm] input:checked').each(function() {
				$(this).prop('checked',false);
		});

	   }else{
	    $('[id$=expertForm] input').each(function() {
	   if($(this).val() == ''){
					$(this).prop('checked',false);
				}
				});
	   $('[id$=expertForm] input:checked').each(function() {
			expertIncl.push($(this).val());
		});
		}
		$('#calendar').fullCalendar( 'refetchEvents' );
		$('#calendar').fullCalendar('render');
		refreshCheckBoxIcon();
	});
	$('#edit-button').click(function(e) {
	    e.stopPropagation();
	    if($(this).hasClass('fa-chevron-down')){
	    	$(this).removeClass('fa-chevron-down');
	    	$(this).addClass('fa-chevron-up');
	    }else{
	    	$(this).addClass('fa-chevron-down');
	    	$(this).removeClass('fa-chevron-up');
	    }
	    $('#appointmentStatus').editable('toggle');
	});
	$(document).on('click','#miniEdit-button',function(e) {
	    e.stopPropagation();
	    if($(this).hasClass('fa-chevron-down')){
	    	$(this).removeClass('fa-chevron-down');
	    	$(this).addClass('fa-chevron-up');
	    }else{
	    	$(this).addClass('fa-chevron-down');
	    	$(this).removeClass('fa-chevron-up');
	    }
	    $('#status').editable('toggle');
	});
	$(document).on('click','.serReqFilter',function(e) {
	    e.stopPropagation();
		e.preventDefault();
		
		$('.serReqFilter').each(function() {
				if($(this).hasClass('active')){
					$(this).removeClass('active');
				}
		});
		
	    if($(this).data('type') == 'Service' && filterType != 'SERVICE'){
	    	filterType = 'SERVICE';
			$('#ServiceAccordion').collapse('show');
			$('#ServiceAccordionHeader').addClass('active');
			$('#ExpertAccordion').collapse('hide');
	    }else if($(this).data('type') == 'Expert' &&filterType != 'EXPERT'){
	    	filterType= 'EXPERT';
			$('#ExpertAccordion').collapse('show');
			$('#ExpertAccordionHeader').addClass('active');
			$('#ServiceAccordion').collapse('hide');
	    }else if($(this).data('type') == 'Service' && filterType == 'SERVICE'){
			$('#ExpertAccordion').collapse('show');
			$('#ExpertAccordionHeader').addClass('active');
			filterType= 'EXPERT';
		}
		else if($(this).data('type') == 'Expert' && filterType == 'EXPERT'){
			$('#ServiceAccordion').collapse('show');
			$('#ServiceAccordionHeader').addClass('active');
			 filterType= 'SERVICE';
		}
		
		  
		$('#calendar').fullCalendar( 'refetchEvents' );
		$('#calendar').fullCalendar('render');
	});
$(document).on('dblclick','#ExpertAccordion span',function(e){
	e.stopPropagation();
	var selectedId = $(this).attr('expertattrid');
		expertIncl=[];
		enableAutolaunch=false;
		   var checkedValue = $(document.getElementById(selectedId)).val();
		   if(checkedValue == ''){
			
		   }else{
				$('#ExpertAccordion input').not(document.getElementById(selectedId)).prop('checked',false);
				$(document.getElementById(selectedId)).prop('checked',true);
			}
		$('#calendar').fullCalendar( 'refetchEvents' );
		$('#calendar').fullCalendar('render');
			refreshCheckBoxIcon();
	
});
$(document).on('dblclick','#ServiceAccordion span', function(e){
	e.stopPropagation();
	serviceIncl=[];
	var selectedId = $(this).attr('serviceattrid');
	enableAutolaunch=false;
	var checkedValue = $(document.getElementById(selectedId)).val();
	if(checkedValue == ''){

	}else{
		$('#ServiceAccordion input').not(document.getElementById(selectedId)).prop('checked',false);
		$(document.getElementById(selectedId)).prop('checked',true);
	}
	$('#calendar').fullCalendar( 'refetchEvents' );
	$('#calendar').fullCalendar('render');
	refreshCheckBoxIcon();
});
$(document).on('ifChecked','#FilterType input', function(event){
	var value = $(this).val();
	if(value == 'EXPERT'){
		filterType= 'EXPERT';
		$('#ExpertAccordion').collapse('show');
		$('#ServiceAccordion').collapse('hide');

	}else{
		filterType = 'SERVICE';
		$('#ServiceAccordion').collapse('show');
		$('#ExpertAccordion').collapse('hide');
	}
	$('#calendar').fullCalendar( 'refetchEvents' );
	$('#calendar').fullCalendar('render');
});