<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/SforcePartnerClient.php');
require_once (APPPATH . 'libraries/SforceHeaderOptions.php');

class Rest_model extends CI_Model {

  
  function __construct(){
			// Call the Model constructor
		parent::__construct();
		$this->load->database();
	}
	function getContactDetails($Provider,$organizationId,$Email){	
		$this->db->select('Provider,Id,Organization_Id,Name,Salesforce_Id,Do_Not_Distrub,Email,Phone,FirstName,LastName,Salutation,Mobile,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,Critical_Notification,Description	');
		$this->db->from('contacts');
		$this->db->where('Provider', $Provider);
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Email', $Email);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function isOrganizationAvailable($organizationId){	
		$this->db->select('Name,Id,Salesforce_Id');
		$this->db->from('organization');
		$this->db->where('Salesforce_Id', $organizationId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->num_rows();
	}
	function bookCloudAppointment($inputArray){
		$result = array();
		if($this->isOrganizationAvailable($inputArray['orgId']) > 0 ){
			try { 
				$contactDetails = $this->getContactDetails($inputArray['Provider'],$inputArray['orgId'],$inputArray['Email']);
				if($contactDetails != null){
					$contact = array(
						'LastName' => $inputArray['LastName'],
						'FirstName' => $inputArray['FirstName'],
						'Email' => $inputArray['Email'],
						'Mobile' => $inputArray['Mobile'],
						'MailingCity' => $inputArray['MailingCity'],
						'MailingState' => $inputArray['MailingState'],
						'MailingPostalCode' => $inputArray['MailingPostalCode'],
						'Provider' => $inputArray['Provider'],
						'Organization_Id ' => $inputArray['orgId'],
						'Id' => $contactDetails->Id
					);
					$insert_id = $contactDetails->Id;
					$this->db->where('Id', $insert_id);
					$this->db->update('contacts',$contact);
				}else{
					$contact = array(
						'LastName' => $inputArray['LastName'],
						'FirstName' => $inputArray['FirstName'],
						'Email' => $inputArray['Email'],
						'Mobile' => $inputArray['Mobile'],
						'MailingCity' => $inputArray['MailingCity'],
						'MailingState' => $inputArray['MailingState'],
						'MailingPostalCode' => $inputArray['MailingPostalCode'],
						'Provider' => $inputArray['Provider'],
						'Organization_Id ' => $inputArray['orgId']
					);
					$this->db->insert('contacts', $contact);
					$insert_id = $this->db->insert_id();
					$uId  = '001'.$this->base62->convert($insert_id);
					$this->db->where('Id', $insert_id);
					$this->db->update('contacts',array('uId'=>$uId));
				}
				$slotArr =(array)explode("_",$inputArray['slot']);
				$slotArr = json_decode(json_encode($slotArr),TRUE);
				$start = "".$inputArray['dateString']." ".$slotArr[0].":".$slotArr[1].":00";
				$end = "".$inputArray['dateString']." ".$slotArr[2].":".$slotArr[3].":00";
				$data =array(
						'Name' =>$inputArray['serviceName'],
						'Organization_Id ' => $inputArray['orgId'],
						'Contact_Id' => $insert_id,
						'Service_Id' => $inputArray['serviceId'],
						'Start_Date' => date('Y-m-d H:i:s', strtotime($start)),
						'End_Date' => date('Y-m-d H:i:s', strtotime($end)),
						'Status' => 'Pending',
						'Provider' => $inputArray['Provider']
				);
				//print_r($data);exit;
				$this->db->insert('appointments', $data);
				$insert_id = $this->db->insert_id();
				$uId  = 'a08'.$this->base62->convert($insert_id);
				$this->db->where('Id', $insert_id);
				$this->db->update('appointments',array('uId'=>$uId));
				$result = array('status' => true, 'message' => 'Appointment created successfully');
			} catch (Exception $e) {
				//alert the user then kill the process
				$result = array('status' => false, 'message' => $e->getMessage());
			}
			
		}else{
			$result = array('status' => false, 'message' => 'Organization not configured properly, Please contact bookingsocial adminsistrator');
		}
		return  $result;
	}
    function bookAppointment($inputArray,$contactDetails){
		//echo json_encode($contactDetails);exit;
		$slotArr =(array)explode("_",$inputArray['slot']);
		$slotArr = json_decode(json_encode($slotArr),TRUE);
		//print_r($slotArr[0]);exit;
		$start = "".$inputArray['dateString']." ".$slotArr[0].":".$slotArr[1].":00";
		$end = "".$inputArray['dateString']." ".$slotArr[2].":".$slotArr[3].":00";
		$data =array(
				'Name' =>$inputArray['serviceName'],
				'Organization_Id ' => $inputArray['OrgId'],
				'Contact_Id' => $inputArray['contactId'],
				'Service_Id' => $inputArray['serviceId'],
				'Start_Date' => date('Y-m-d H:i:s', strtotime($start)),
				'End_Date' => date('Y-m-d H:i:s', strtotime($end)),
				'Status' => $inputArray['appointmentStatus'],
				'Provider' => $contactDetails->Provider
		);
		if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null)
			$data['Expert_Id'] = $inputArray['EXPERT'];
		//print_r($data);exit;
		$this->db->insert('appointments', $data);
		$insert_id = $this->db->insert_id();
		$uId  = 'a08'.$this->base62->convert($insert_id);
		$this->db->where('Id', $insert_id);
		$result = $this->db->update('appointments',array('uId'=>$uId));
		echo  json_encode($result);exit;
	}
	/*******************************************************
	
		Salesforce Related Code
	*********************************************************
	
	function getServiceAvailability($service_Id,$provider_Id){
		ini_set("soap.wsdl_cache_enabled", "0");

		$sfdc = new SforcePartnerClient();

		$SoapClient = $sfdc->createConnection(PARTNERWSDL); 
		$location = $this->session->userdata('location');
		$sessionId = $this->session->userdata('sessionId');
		$loginResult = false; 
		//$loginResult = $sfdc->login($userName, '#KS726san');
		$parsedURL = parse_url($location); 

		define ("_SFDC_SERVER_", substr($parsedURL['host'],0,strpos($parsedURL['host'], '.'))); 
		define ("_WS_NAME_", 'SETUP_Organization_WS'); 
		define ("_WS_WSDL_", BKSL2WEBSERVICE); 
		define ("_WS_ENDPOINT_", 'https://' . _SFDC_SERVER_ . '.salesforce.com/services/wsdl/class/BKSL2/' . _WS_NAME_); 
		define ("_WS_NAMESPACE_", 'http://soap.sforce.com/schemas/class/BKSL2/' . _WS_NAME_);  

		$client = new SoapClient(_WS_WSDL_); 
		$sforce_header = new SoapHeader(_WS_NAMESPACE_, "SessionHeader", array("sessionId" => $sessionId)); 
		$client->__setSoapHeaders(array($sforce_header));
				
		// Setup data to send into the web service
		$requestArray = array();
		$requestArray[] = array('key'=>'SELECTEDSERVICE','value'=>$service_Id ,'values' =>array($service_Id));
		$requestArray3  =array();
		$requestArray3[] = array('key'=>'PROVIDER','value'=>$provider_Id);
		$requestArray2 = array('eventType'=> 'WORKINGHOUR','valueMap'=>$requestArray,'BSLMap' => $requestArray3);
		$reqArr = array(
					'request'=>$requestArray2
					);
		
		// Call the web service
		$response = $client->INTF_GetDefaultSchedules_WS($reqArr);
		//$response = $client->__getFunctions();
		//print_r($response);
		echo  $response->result->quickResponse;exit;
  }
  function getExpertAvailability($expert_Id,$service_Id,$provider_Id){
  	ini_set("soap.wsdl_cache_enabled", "0");
  
  	$sfdc = new SforcePartnerClient();
  	$organizationId = $this->session->userdata('OrganizationId');
  	$SoapClient = $sfdc->createConnection(PARTNERWSDL);
  	$location = getLocation($organizationId);
  	$sessionId = getSessionId($organizationId);
  	$loginResult = false;
  	//$loginResult = $sfdc->login($userName, '#KS726san');
  	$parsedURL = parse_url($location);
  
  	define ("_SFDC_SERVER_", substr($parsedURL['host'],0,strpos($parsedURL['host'], '.')));
  	define ("_WS_NAME_", 'SETUP_Organization_WS');
  	define ("_WS_WSDL_", BKSL2WEBSERVICE);
  	define ("_WS_ENDPOINT_", 'https://' . _SFDC_SERVER_ . '.salesforce.com/services/wsdl/class/BKSL2/' . _WS_NAME_);
  	define ("_WS_NAMESPACE_", 'http://soap.sforce.com/schemas/class/BKSL2/' . _WS_NAME_);
  
  	$client = new SoapClient(_WS_WSDL_);
  	$sforce_header = new SoapHeader(_WS_NAMESPACE_, "SessionHeader", array("sessionId" => $sessionId));
  	$client->__setSoapHeaders(array($sforce_header));
  
  	// Setup data to send into the web service
  	$requestArray = array();
  	$requestArray[] = array('key'=>'SELECTEDSERVICE','value'=>$service_Id ,'values' =>array($service_Id));
  	$requestArray[] = array('key'=>'SELECTEDEXPERT','value'=>$expert_Id,'values' =>array($expert_Id));
  	$requestArray3  =array();
  	$requestArray3[] = array('key'=>'PROVIDER','value'=>$provider_Id);
  	$requestArray3[] = array('key'=>'CALLER','value'=>'CALENDAR');
  	$requestArray2 = array('eventType'=> 'EXPERTWORKINGHOUR','valueMap'=>$requestArray,'BSLMap' => $requestArray3);
  	$reqArr = array(
  			'request'=>$requestArray2
  	);
  
  	// Call the web service
  	$response = $client->INTF_GetDefaultSchedules_WS($reqArr);
  	//$response = $client->__getFunctions();
  	//print_r($reqArr);
  	echo  $response->result->quickResponse;exit;
  }
	
  function rescheduleAppointment($inputArray,$contactDetails){
  	ini_set("soap.wsdl_cache_enabled", "0");
  
  	$sfdc = new SforcePartnerClient();
  	$organizationId = $this->session->userdata('OrganizationId');
  	$SoapClient = $sfdc->createConnection(PARTNERWSDL);
  	$location = getLocation($organizationId);
  	$sessionId = getSessionId($organizationId);
  	$loginResult = false;
  	//$loginResult = $sfdc->login($userName, '#KS726san');
  	$parsedURL = parse_url($location);
  
  	define ("_SFDC_SERVER_", substr($parsedURL['host'],0,strpos($parsedURL['host'], '.')));
  	define ("_WS_NAME_", 'SETUP_Organization_WS');
  	define ("_WS_WSDL_", BKSL2WEBSERVICE);
  	define ("_WS_ENDPOINT_", 'https://' . _SFDC_SERVER_ . '.salesforce.com/services/wsdl/class/BKSL2/' . _WS_NAME_);
  	define ("_WS_NAMESPACE_", 'http://soap.sforce.com/schemas/class/BKSL2/' . _WS_NAME_);
  
  	$client = new SoapClient(_WS_WSDL_);
  	$sforce_header = new SoapHeader(_WS_NAMESPACE_, "SessionHeader", array("sessionId" => $sessionId));
  	$client->__setSoapHeaders(array($sforce_header));
  	// Setup data to send into the web service
  	//print_r($inputArray);exit;
  	 
  	$requestArray = array();
  	$requestArray[] = array('key'=>'SELECTEDDATE','value'=>$inputArray['SELECTEDDATE']);
  	$requestArray[] = array('key'=>'PROVIDER','value'=>$inputArray['provider']);
  	$requestArray[] = array('key'=>'SELECTEDSLOTS','value'=>$inputArray['slot']);
  	$objContact = array();
  	$objContact['Id'] =  $inputArray['contactId'];
  	//$objContact['Id'] =  'a01280000024OgP';
  	 
  	if($inputArray['ObjType'] == 'CUSTOMER'){
  		$objContact['BKSL2__LastName__c'] = $contactDetails->LastName;
  		$objContact['BKSL2__FirstName__c'] = $contactDetails->FirstName;
  		$objContact['BKSL2__Email__c'] = $contactDetails->Email;
  		$objContact['BKSL2__Phone__c'] = $contactDetails->Phone;
  		$requestArray[] = array('key'=>'CUSTOMER','objCustomer'=>$objContact);
  	}else if($inputArray['ObjType'] == 'CONTACT'){
  		$objContact['LastName'] = $contactDetails->LastName;
  		$objContact['FirstName'] = $contactDetails->FirstName;
  		$objContact['Email'] = $contactDetails->Email;
  		$objContact['Phone'] = $contactDetails->Phone;
  		$requestArray[] = array('key'=>'CONTACT','objContact'=>$objContact);
  	}else if ($inputArray['ObjType'] = 'LEAD'){
  		$objContact['LastName'] = $contactDetails->LastName;
  		$objContact['FirstName'] = $contactDetails->FirstName;
  		$objContact['Email'] = $contactDetails->Email;
  		$objContact['MobilePhone'] = $contactDetails->Phone;
  		$requestArray[] = array('key'=>'LEAD','objLead'=>$objContact);
  		 
  	}
  	$objServiceRequest['Name'] = $inputArray['serviceName'];
  	$objServiceRequest['BKSL2__Service__c'] = $inputArray['serviceId'];
  	//$objServiceRequest['Id'] = $inputArray['srqId'];
  	$objServiceRequest['BKSL2__Status__c'] = $inputArray['appointmentStatus'];
  	$requestArray[] = array('key'=>'RESCHEDULESERVICEREQUEST','value'=>$inputArray['srqId']);
  	$requestArray[] = array('key'=>'SERVICEREQUEST','objServiceRequest'=>$objServiceRequest);
  	$requestArray2 = array('valueMap'=>$requestArray);
  	$reqArr = array(
  			'request'=>$requestArray2
  	);
  	$slotArr =(array)explode("_",$inputArray['slot']);
  	$slotArr = json_decode(json_encode($slotArr),TRUE);
  	//print_r($slotArr[0]);exit;
  	$start = "".$inputArray['dateString']." ".$slotArr[0].":".$slotArr[1].":00";
  	$end = "".$inputArray['dateString']." ".$slotArr[2].":".$slotArr[3].":00";
  	//print_r($start);exit;
  	// Call the web service
  	$response = $client->INTF_BookAppointment_WS($reqArr);
  	//$response = $client->__getFunctions();
  	//print_r($response);exit;
  	if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null)
  		$requestArray[] = array('key'=>'EXPERT','value'=>$inputArray['EXPERT']);
  	if($response->result->success){
  		$data =array(
  				'Salesforce_Id' => $response->result->quickResponse,
  				'Name' =>$inputArray['serviceName'],
  				'Organization_Id ' => $organizationId,
  				'Contact_Id' => $inputArray['contactId'],
  				'Service_Id' => $inputArray['serviceId'],
  				'Start_Date' => date('Y-m-d H:i:s', strtotime($start)),
  				'End_Date' => date('Y-m-d H:i:s', strtotime($end)),
  				'Status' => $inputArray['appointmentStatus'],
  				'Provider' => $inputArray['provider']
  		);
  		$this->db->where(' Id', $inputArray['Id']);
  		$this->db->update('appointments', $data);
  	}
  	echo  json_encode($response->result->quickResponse);exit;
  }
	
  function bookAppointment($inputArray,$contactDetails){
  	ini_set("soap.wsdl_cache_enabled", "0");
  
  	$sfdc = new SforcePartnerClient();
  
  	$SoapClient = $sfdc->createConnection(PARTNERWSDL);
  	$location = $this->session->userdata('location');
  	$sessionId = $this->session->userdata('sessionId');
  	$loginResult = false;
  	//$loginResult = $sfdc->login($userName, '#KS726san');
  	$parsedURL = parse_url($location);
  
  	define ("_SFDC_SERVER_", substr($parsedURL['host'],0,strpos($parsedURL['host'], '.')));
  	define ("_WS_NAME_", 'SETUP_Organization_WS');
  	define ("_WS_WSDL_", BKSL2WEBSERVICE);
  	define ("_WS_ENDPOINT_", 'https://' . _SFDC_SERVER_ . '.salesforce.com/services/wsdl/class/BKSL2/' . _WS_NAME_);
  	define ("_WS_NAMESPACE_", 'http://soap.sforce.com/schemas/class/BKSL2/' . _WS_NAME_);
  
  	$client = new SoapClient(_WS_WSDL_);
  	$sforce_header = new SoapHeader(_WS_NAMESPACE_, "SessionHeader", array("sessionId" => $sessionId));
  	$client->__setSoapHeaders(array($sforce_header));
  	// Setup data to send into the web service
  	//print_r($inputArray);exit;
  	
  	$requestArray = array();
  	if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null)
  		$requestArray[] = array('key'=>'EXPERT','value'=>$inputArray['EXPERT']);
  	$requestArray[] = array('key'=>'SELECTEDDATE','value'=>$inputArray['SELECTEDDATE']);
  	$requestArray[] = array('key'=>'SELECTEDSERVICE','value'=>$inputArray['serviceId'] );
  	$requestArray[] = array('key'=>'PROVIDER','value'=>$inputArray['provider']);
  	$requestArray[] = array('key'=>'SELECTEDSLOTS','value'=>$inputArray['slot']);
  	$requestArray[] = array('key'=>'FINDEXISTINGCUSTOMER','value'=>TRUE);
  	$objContact = array();
  	//$objContact['Id'] =  $inputArray['contactId']; 
  	//$objContact['Id'] =  'a01280000024OgP';
  	
  	if($inputArray['ObjType'] == 'CUSTOMER'){
  		$objContact['BKSL2__LastName__c'] = $contactDetails->LastName;
  		$objContact['BKSL2__FirstName__c'] = $contactDetails->FirstName;
  		$objContact['BKSL2__Email__c'] = $contactDetails->Email;
  		$objContact['BKSL2__Phone__c'] = $contactDetails->Phone;
  		$requestArray[] = array('key'=>'CUSTOMER','objCustomer'=>$objContact);
  	}else if($inputArray['ObjType'] == 'CONTACT'){
  		$objContact['LastName'] = $contactDetails->LastName;
  		$objContact['FirstName'] = $contactDetails->FirstName;
  		$objContact['Email'] = $contactDetails->Email;
  		$objContact['Phone'] = $contactDetails->Phone;
  		$requestArray[] = array('key'=>'CONTACT','objContact'=>$objContact);
  	}else if ($inputArray['ObjType'] = 'LEAD'){
  		$objContact['LastName'] = $contactDetails->LastName;
  		$objContact['FirstName'] = $contactDetails->FirstName;
  		$objContact['Email'] = $contactDetails->Email;
  		$objContact['MobilePhone'] = $contactDetails->Phone;
  		$requestArray[] = array('key'=>'LEAD','objLead'=>$objContact);
  	
  	}
  	$objServiceRequest['Name'] = $inputArray['serviceName'];
  	$objServiceRequest['BKSL2__Service__c'] = $inputArray['serviceId'];
  	$objServiceRequest['BKSL2__Status__c'] = $inputArray['appointmentStatus'];
  	
  	$requestArray[] = array('key'=>'SERVICEREQUEST','objServiceRequest'=>$objServiceRequest);
  	$requestArray2 = array('valueMap'=>$requestArray);
  	$reqArr = array(
  			'request'=>$requestArray2
  	);
  	$slotArr =(array)explode("_",$inputArray['slot']);
  	$slotArr = json_decode(json_encode($slotArr),TRUE);
  	//print_r($slotArr[0]);exit;
  	$start = "".$inputArray['dateString']." ".$slotArr[0].":".$slotArr[1].":00";
  	$end = "".$inputArray['dateString']." ".$slotArr[2].":".$slotArr[3].":00";
  	//print_r($start);exit;
  	// Call the web service
  	$response = $client->INTF_BookAppointment_WS($reqArr);
  	//$response = $client->__getFunctions();
  	//print_r($response);exit;
  	$organizationId = $this->session->userdata('OrganizationId');
  	if($response->result->success){  		
  		$data =array(
  				'Salesforce_Id' => $response->result->quickResponse,
  				'Name' =>$inputArray['serviceName'],
  				'Organization_Id ' => $organizationId,
  				'Contact_Id' => $inputArray['contactId'],
  				'Service_Id' => $inputArray['serviceId'],
  				'Start_Date' => date('Y-m-d H:i:s', strtotime($start)),
  				'End_Date' => date('Y-m-d H:i:s', strtotime($end)),
  				'Status' => $inputArray['appointmentStatus'],
  				'Provider' => $inputArray['provider']
  		);
  		if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null)
  			$data['Expert_Id'] = $inputArray['EXPERT'];
  		//print_r($data);exit;
  		$this->db->insert('appointments', $data);
  		$insert_id = $this->db->insert_id();
  		$uId  = 'a08'.$this->base62->convert($insert_id);
  		$this->db->where('Id', $insert_id);
  		$this->db->update('appointments',array('uId'=>$uId));
  	}
  	echo  json_encode($response->result->quickResponse);exit;
  }
function expertBookAppointment($inputArray,$organizationId){
	  ini_set("soap.wsdl_cache_enabled", "0");
  
  	$sfdc = new SforcePartnerClient();
  	$SoapClient = $sfdc->createConnection(PARTNERWSDL);
  	$location = $this->session->userdata('location');
  	$sessionId = $this->session->userdata('sessionId');
  	$loginResult = false;
  	//$loginResult = $sfdc->login($userName, '#KS726san');
  	$parsedURL = parse_url($location);
  
  	define ("_SFDC_SERVER_", substr($parsedURL['host'],0,strpos($parsedURL['host'], '.')));
  	define ("_WS_NAME_", 'SETUP_Organization_WS');
  	define ("_WS_WSDL_", BKSL2WEBSERVICE);
  	define ("_WS_ENDPOINT_", 'https://' . _SFDC_SERVER_ . '.salesforce.com/services/wsdl/class/BKSL2/' . _WS_NAME_);
  	define ("_WS_NAMESPACE_", 'http://soap.sforce.com/schemas/class/BKSL2/' . _WS_NAME_);
  
  	$client = new SoapClient(_WS_WSDL_);
  	$sforce_header = new SoapHeader(_WS_NAMESPACE_, "SessionHeader", array("sessionId" => $sessionId));
  	$client->__setSoapHeaders(array($sforce_header));
  	// Setup data to send into the web service
  	echo json_encode($inputArray);exit;
  	
  	$requestArray = array();
  	if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null)
  		$requestArray[] = array('key'=>'EXPERT','value'=>$inputArray['EXPERT']);
  	$requestArray[] = array('key'=>'SELECTEDDATE','value'=>$inputArray['SELECTEDDATE']);
  	$requestArray[] = array('key'=>'SELECTEDSERVICE','value'=>$inputArray['serviceId'] );
  	$requestArray[] = array('key'=>'PROVIDER','value'=>$inputArray['provider']);
  	$requestArray[] = array('key'=>'SELECTEDSLOTS','value'=>$inputArray['slot']);
  	$requestArray[] = array('key'=>'FINDEXISTINGCUSTOMER','value'=>TRUE);
  	$objContact = array();
	if($inputArray['contactId'] != '' && $inputArray['contactId'] !=null)
  		$objContact['Id'] =  $inputArray['contactId']; 
  	
  	if($inputArray['ObjType'] == 'CUSTOMER'){
  		$objContact['BKSL2__LastName__c'] = $inputArray['LastName'];
  		$objContact['BKSL2__FirstName__c'] = $inputArray['FirstName'];
  		$objContact['BKSL2__Email__c'] = $inputArray['Email'];
  		$objContact['BKSL2__Phone__c'] = $inputArray['Phone'];
		$objContact['BKSL2__Street__c'] = $inputArray['Street'];
  		$objContact['BKSL2__City__c'] = $inputArray['City'];
  		$objContact['BKSL2__State__c'] = $inputArray['State'];
  		$objContact['BKSL2__Zip_Code__c'] = $inputArray['ZipCode'];
  		$requestArray[] = array('key'=>'CUSTOMER','objCustomer'=>$objContact);
  	}else if($inputArray['ObjType'] == 'CONTACT'){
  		$objContact['LastName'] = $inputArray['LastName'];
  		$objContact['FirstName'] = $inputArray['FirstName'];
  		$objContact['Email'] = $inputArray['Email'];
  		$objContact['Phone'] = $inputArray['Phone'];
		$objContact['MailingStreet'] = $inputArray['Street'];
  		$objContact['MailingCity'] = $inputArray['City'];
  		$objContact['MailingState'] = $inputArray['State'];
  		$objContact['MailingPostalCode'] = $inputArray['ZipCode'];
  		$requestArray[] = array('key'=>'CONTACT','objContact'=>$objContact);
  	}else if ($inputArray['ObjType'] = 'LEAD'){
  		$objContact['LastName'] = $inputArray['LastName'];
  		$objContact['FirstName'] = $inputArray['FirstName'];
  		$objContact['Email'] = $inputArray['Email'];
  		$objContact['MobilePhone'] = $inputArray['Phone'];
		$objContact['Street '] = $inputArray['Street'];
  		$objContact['City'] = $inputArray['City'];
  		$objContact['State  '] = $inputArray['State'];
  		$objContact['PostalCode '] = $inputArray['ZipCode'];
  		$requestArray[] = array('key'=>'LEAD','objLead'=>$objContact);
  	
  	}
	$contact = array(
		'LastName' => $inputArray['LastName'],
		'FirstName' => $inputArray['FirstName'],
		'Email' => $inputArray['Email'],
		'Mobile' => $inputArray['Phone'],
		'MailingCity' => $inputArray['City'],
		'MailingState' => $inputArray['State'],
		'MailingPostalCode' => $inputArray['ZipCode'],
		'Salesforce_Id' => $inputArray['contactId']
	);
  	$objServiceRequest['Name'] = $inputArray['serviceName'];
  	$objServiceRequest['BKSL2__Service__c'] = $inputArray['serviceId'];
  	$objServiceRequest['BKSL2__Status__c'] = $inputArray['appointmentStatus'];
  	
  	$requestArray[] = array('key'=>'SERVICEREQUEST','objServiceRequest'=>$objServiceRequest);
  	$requestArray2 = array('valueMap'=>$requestArray);
  	$reqArr = array(
  			'request'=>$requestArray2
  	);
  	$slotArr =(array)explode("_",$inputArray['slot']);
  	$slotArr = json_decode(json_encode($slotArr),TRUE);
  	//print_r($slotArr[0]);exit;
  	$start = "".$inputArray['dateString']." ".$slotArr[0].":".$slotArr[1].":00";
  	$end = "".$inputArray['dateString']." ".$slotArr[2].":".$slotArr[3].":00";
  	//print_r($start);exit;
  	// Call the web service
  	$response = $client->INTF_BookAppointment_WS($reqArr);
  	//$response = $client->__getFunctions();
  	if($response->result->success){  		
  		$data =array(
  				'Salesforce_Id' => $response->result->quickResponse,
  				'Name' =>$inputArray['serviceName'],
  				'Organization_Id ' => $organizationId,
  				'Contact_Id' => $response->result->value1,
  				'Service_Id' => $inputArray['serviceId'],
  				'Start_Date' => date('Y-m-d H:i:s', strtotime($start)),
  				'End_Date' => date('Y-m-d H:i:s', strtotime($end)),
  				'Status' => $inputArray['appointmentStatus'],
  				'Provider' => $inputArray['provider']
  		);
  		if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null)
  			$data['Expert_Id'] = $inputArray['EXPERT'];
  		//print_r($data);exit;
  		$this->db->insert('appointments', $data);
  		$insert_id = $this->db->insert_id();
  		$uId  = 'a08'.$this->base62->convert($insert_id);
  		$this->db->where('Id', $insert_id);
  		$this->db->update('appointments',array('uId'=>$uId));
		$this->db->select('Id,Salesforce_id');
		$this->db->from('contacts');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Salesforce_Id', $response->result->value1);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->row() != null){
			$this->db->where('Organization_Id', $organizationId);
			$this->db->where('Salesforce_Id', $response->result->value1);
			$this->db->update('contacts', $contact);
		}else{
			$this->db->insert('contacts', $contact);
			$insert_id = $this->db->insert_id();
			$uId  = '001'.$this->base62->convert($insert_id);
			$this->db->where('Id', $insert_id);
			$this->db->update('contacts',array('uId'=>$uId));
		}
		//echo $this->db->last_query();exit;
  	}
  	echo  json_encode($response->result->quickResponse);exit;
  }
  function SyncContacts()
  {
  	$location = $this->session->userdata('location');
  	$sessionId = $this->session->userdata('sessionId');
  	$mySforceConnection = new SforcePartnerClient();
  	$sforceSoapClient = $mySforceConnection->createConnection(PARTNERWSDL);
  	$mySforceConnection->setEndpoint($location);
  	$mySforceConnection->setSessionHeader($sessionId);
  	$query = "SELECT Birthdate,BKSL2__Critical_Notification__c,BKSL2__Facebook_Content__c,BKSL2__Facebook_ID__c,BKSL2__Gender__c,Description,Email,EmailBouncedDate,FirstName,HomePhone,Id,Languages__c,LastName,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,MobilePhone,Name,Phone,Title FROM Contact";
  	$response = $mySforceConnection->query($query);
  	$inputs = array();
  	for ($response->rewind(); $response->pointer < $response->size; $response->next()) {
  		$record = $response->current();
  		$data =array(
  				'Name' => $record->fields->Name,
  				'Salesforce_Id' => $record->Id,
  				'Email' => $record->fields->Email,
  				'Mobile' => $record->fields->MobilePhone,
  				'Phone' => $record->fields->Phone,
  				'LastName' => $record->fields->LastName,
  				'FirstName' => $record->fields->FirstName,
  				'MailingCountry' => $record->fields->MailingCountry,
  				'MailingPostalCode' => $record->fields->MailingPostalCode,
  				'MailingCity' => $record->fields->MailingCity,
  				'MailingStreet' => $record->fields->MailingStreet,
  				'Critical_Notification' => $record->fields->BKSL2__Critical_Notification__c,
  				'Description' => $record->fields->Description
  		);
  		$this->db->insert('contacts', $data);
  		$insert_id = $this->db->insert_id();
  		//echo $insert_id;exit;
  		$uId  = '001'.$this->base62->convert($insert_id);
  		$this->db->where('Id', $insert_id);
  		$this->db->update('contacts',array('uId'=>$uId));
  	}
  	return $response;
  }
  function SyncService()
  {
  	$location = $this->session->userdata('location');
  	$sessionId = $this->session->userdata('sessionId');
  	$mySforceConnection = new SforcePartnerClient();
  	$sforceSoapClient = $mySforceConnection->createConnection(PARTNERWSDL);
  	$mySforceConnection->setEndpoint($location);
  	$mySforceConnection->setSessionHeader($sessionId);
  	$query = "SELECT BKSL2__Access__c,BKSL2__Active__c,BKSL2__Additional_Information_Fieldset__c,BKSL2__Allow_Waitinglist__c,BKSL2__Assignment_Type__c,BKSL2__Availability_Count__c,BKSL2__Available_On__c,BKSL2__Break_Hours__c,BKSL2__Business_Hours__c,BKSL2__Cost__c,BKSL2__Description__c,BKSL2__Display_Type__c,BKSL2__Duration_Time__c,BKSL2__Duration_Unit__c,BKSL2__Email_Notification_Template__c,BKSL2__Initiate_Expert_Calculation__c,BKSL2__Is_Valid_Service__c,BKSL2__Mode_of_Notification__c,BKSL2__Next_Expert_Starts__c,BKSL2__Next_Preferred_Expert__c,BKSL2__Next_slot_start_after__c,BKSL2__Notification_Frequency__c,BKSL2__Picture__c,BKSL2__Preferred_Expert__c,BKSL2__Provider__c,BKSL2__Recalculate_Service_Expert_Now__c,BKSL2__Service_Calendar_Id__c,BKSL2__Service_Category__c,BKSL2__Service_Grouping_Unit__c,BKSL2__SMS_Confirmation_Template__c,BKSL2__SMS_Notification_Template__c,BKSL2__Working_Hours__c,CreatedById,CreatedDate,Id,IsDeleted,LastActivityDate,LastModifiedById,LastModifiedDate,Name,OwnerId,SystemModstamp FROM BKSL2__Service__c";
  	$response = $mySforceConnection->query($query);
  	$inputs = array();
  	for ($response->rewind(); $response->pointer < $response->size; $response->next()) {
  		$record = $response->current();
  		$data =array(
  				'Name' => $record->fields->Name,
  				'Salesforce_Id' => $record->Id,
  				'Provider' => $record->fields->BKSL2__Provider__c,
  				'DurationTime' => $record->fields->BKSL2__Duration_Unit__c,
  				'DurationUnit' => $record->fields->BKSL2__Duration_Time__c,
  				'DisplayType' => $record->fields->BKSL2__Display_Type__c,
  				'PreferredExpert' => $record->fields->BKSL2__Preferred_Expert__c,
  				'Description' => $record->fields->BKSL2__Description__c
  		);
  		$this->db->insert('services', $data);
  		$insert_id = $this->db->insert_id();
  		$uId  = 'a09'.$this->base62->convert($insert_id);
  		$this->db->where('Id', $insert_id);
  		$this->db->update('services',array('uId'=>$uId));
  	}
  	return $response;
  }
  function SyncExpert()
  {
  	$location = $this->session->userdata('location');
  	$sessionId = $this->session->userdata('sessionId');
  	$mySforceConnection = new SforcePartnerClient();
  	$sforceSoapClient = $mySforceConnection->createConnection(PARTNERWSDL);
  	$mySforceConnection->setEndpoint($location);
  	$mySforceConnection->setSessionHeader($sessionId);
  	$query = "SELECT BKSL2__Active__c,BKSL2__Business_Hours__c,BKSL2__Email__c,BKSL2__Is_Call_Notification__c,BKSL2__Is_Email_Notification__c,BKSL2__Is_SMS_Notification__c,BKSL2__Phone__c,BKSL2__Photo__c,BKSL2__Provider__c,BKSL2__Role__c,BKSL2__Salesforce_User__c,BKSL2__Skills__c,BKSL2__Slot_Type__c,BKSL2__Working_Hours__c,CreatedById,CreatedDate,Id,IsDeleted,LastActivityDate,LastModifiedById,LastModifiedDate,Name,OwnerId,SystemModstamp FROM BKSL2__Expert__c";
  	$response = $mySforceConnection->query($query);
  	$inputs = array();
  	for ($response->rewind(); $response->pointer < $response->size; $response->next()) {
  		$record = $response->current();
  		$data =array(
  				'Name' => $record->fields->Name,
  				'Salesforce_Id' => $record->Id,
  				'Provider' => $record->fields->BKSL2__Provider__c,
  				'Email' => $record->fields->BKSL2__Email__c,
  				'Phone' => $record->fields->BKSL2__Phone__c,
  		);
  		$this->db->insert('experts', $data);
  		$insert_id = $this->db->insert_id();
  		$uId  = 'a05'.$this->base62->convert($insert_id);
  		$this->db->where('Id', $insert_id);
  		$this->db->update('experts',array('uId'=>$uId));
  	}
  	return $response;
  }
  function SyncExpertService()
  {
  	$location = $this->session->userdata('location');
  	$sessionId = $this->session->userdata('sessionId');
  	$mySforceConnection = new SforcePartnerClient();
  	$sforceSoapClient = $mySforceConnection->createConnection(PARTNERWSDL);
  	$mySforceConnection->setEndpoint($location);
  	$mySforceConnection->setSessionHeader($sessionId);
  	$query = "SELECT BKSL2__Description__c,BKSL2__Expert__c,BKSL2__Service__c,CreatedById,CreatedDate,Id,IsDeleted,LastModifiedById,LastModifiedDate,Name,SystemModstamp FROM BKSL2__Expert_Service__c";
  	$response = $mySforceConnection->query($query);
  	$inputs = array();
  	for ($response->rewind(); $response->pointer < $response->size; $response->next()) {
  		$record = $response->current();
  		$data =array(
  				'ES_Name' => $record->fields->Name,
  				'Salesforce_Id' => $record->Id,
  				'Expert_Id' => $record->fields->BKSL2__Expert__c,
  				'Service_Id' => $record->fields->BKSL2__Service__c,
  		);
  	   $this->db->insert('expert_service', $data);
  	   $insert_id = $this->db->insert_id();
  	   $uId  = 'a04'.$this->base62->convert($insert_id);
  	   $this->db->where('Id', $insert_id);
  	   $this->db->update('expert_service',array('uId'=>$uId));
  	}
  	return $response;
  }
  public function getAllSettings($providerId){
  	ini_set("soap.wsdl_cache_enabled", "0");
  	
  	$sfdc = new SforcePartnerClient();
  	
  	$SoapClient = $sfdc->createConnection(PARTNERWSDL);
  	$location = $this->session->userdata('location');
  	$sessionId = $this->session->userdata('sessionId');
  	$loginResult = false;
  	//$loginResult = $sfdc->login($userName, '#KS726san');
  	$parsedURL = parse_url($location);
  	
  	define ("_SFDC_SERVER_", substr($parsedURL['host'],0,strpos($parsedURL['host'], '.')));
  	define ("_WS_NAME_", 'SETUP_Organization_WS');
  	define ("_WS_WSDL_", BKSL2WEBSERVICE);
  	define ("_WS_ENDPOINT_", 'https://' . _SFDC_SERVER_ . '.salesforce.com/services/wsdl/class/BKSL2/' . _WS_NAME_);
  	define ("_WS_NAMESPACE_", 'http://soap.sforce.com/schemas/class/BKSL2/' . _WS_NAME_);
  	
  	$client = new SoapClient(_WS_WSDL_);
  	$sforce_header = new SoapHeader(_WS_NAMESPACE_, "SessionHeader", array("sessionId" => $sessionId));
  	$client->__setSoapHeaders(array($sforce_header));
  	$requestArray = array('eventName'=> 'GETSETTINGS','value'=>$providerId);
  	$reqArr = array(
  			'request'=>$requestArray
  	);
  	
  	// Call the web service
  	$response = $client->INTF_BookingSocial_WS($reqArr);
  	//$response = $client->__getFunctions();
  	//print_r($response);exit;
  	return  $response->result->message;
  }
  
	**********************************************************************
	
		Salesforce related code
	*********************************************************************/
	
	function searchFormValues($objectName, $objectFields, $inputs){ 
 		
		$this->db->select('*');    
		$this->db->from($objectName);
		$like_conditions = $this->make_like_conditions($objectFields, $inputs);
		$this->db->where($like_conditions); 
		$query = $this->db->get();
		return $query->result();
	} 
	
	//To add parentheses around the LIKE clauses
	function make_like_conditions ($objectFields, $search_text) {
			$likes = array();
			for($j=0;$j<count($objectFields['fields']) ; $j++){
				  $filedName = $objectFields['fields'][$j]['fieldname'];
					$likes[] = " $filedName LIKE '%$search_text%'";
			}
			return '('.implode(' || ', $likes).')';
	}
	
}
?>