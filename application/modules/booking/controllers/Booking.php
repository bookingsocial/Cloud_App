<?php

class Booking extends MX_Controller  
{
	function __construct()
	{ 
		parent::__construct();
		$this->load->model('contact_model');
		$this->load->model('rest_model');
		$this->load->model('expert_model');
		$this->load->helper('date');
	    $this->load->library('pagination');
	    $this->load->library('base62');
		$this->load->model('admin_model');
	}
	
	function index()
	{
		modules::run('bksl/auth/is_logged_in');
		$userDetails = modules::run('bksl/auth/get_user_details');
		$related_to = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		$data['OrgDetails'] = $this->contact_model->getOrganizationDetails($organizationId);
		$data['userDetails'] = $userDetails;
		if($userDetails->user_type =='CONTACT'){
			$data['ContactDetails'] = $this->contact_model->getContactDetails($related_to,$organizationId);
			$data['appointmentDetails'] = $this->contact_model->getAppoitmentDetails($related_to,$organizationId);
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/contact.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileObject = $jsonProfiledtls['objects'];
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$jsonPrfs = $jsonProfileTabs;
			$data['profileTabs'] = $jsonPrfs; 
			
			$page = array();
			$page['showHeader'] = TRUE;
			$page['showFooter'] = TRUE;
			$page['page_name'] = 'contact/dashboard';
			$page['title'] = 'Dashboard';
			$page['data'] = $data;
			//print_r($data);exit;
			$this->load->view('contact_template', $page);
		}else if($userDetails->user_type =='EXPERT'){
			$data['ExpertDetails'] = $this->expert_model->getExpertDetails($related_to,$organizationId);
			$expertSFId = $data['ExpertDetails']->Salesforce_Id; 
			$data['serviceDetails'] = $this->expert_model->getServiceDetails($expertSFId,$organizationId);
			$data['contactDetails'] = $this->expert_model->getContactDetails($organizationId);
			$data['appointmentDetails'] = $this->expert_model->getAppoitmentDetails($expertSFId,$organizationId);
			//$data['settingsDetails'] = AjaxHandler->getAllSettings();
			
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$data['profileTabs'] = $jsonProfileTabs;
			
			$page = array();
			$page['showHeader'] = TRUE;
			$page['showFooter'] = TRUE;
			$page['page_name'] = 'expert/dashboard';
			$page['title'] = 'Dashboard';
			$page['data'] = $data;
			//echo $expertId;
			//print_r($data);exit;
			$this->load->view('expert_template',$page);
		}else if($userDetails->user_type =='SUPERADMIN'){ 
			$data['serviceDetails'] = $this->admin_model->getAllServiceDetails($organizationId);
			$data['ExpertDetails'] = $this->admin_model->getAllExpertDetails($organizationId);
			
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileTabs = $jsonProfiledtls['tabs'];   
			$data['profileTabs'] = $jsonProfileTabs;
			    
			$page = array(); 
			$page['showHeader'] = TRUE; 
			$page['showFooter'] = TRUE;
			$page['page_name'] = 'admin/dashboard';
			$page['title'] = 'Dashboard';
			$page['data'] = $data;
			//echo $expertId;
			//print_r($data);exit;
			$this->load->view('superadmin_template',$page);
		}
	}
	function RestPost($url, $fields)
	{
		$post_field_string = http_build_query($fields, '', '&');
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);
		
		curl_setopt($ch, CURLOPT_POST, true);
		
		$response = curl_exec($ch);
		
		curl_close ($ch);
		
		return $response;
	}
	function RestGet($url, $params=array()) 
	{	

		$url = $url.'?'.http_build_query($params, '', '&');
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
		$response = curl_exec($ch);
		
		curl_close($ch);
		
		return $response;
	}
	function getServiceAvailability()
	{
		$serId = $this->input->get('service');
		modules::run('bksl/auth/is_logged_in');
		$GetURL = CLOUDSYNCAPIURL.'api/availability/'.$serId;
		echo $this->RestGet($GetURL);
	}
	Public function bookAppointment(){
		//print_r($this->input->post());exit;
		$inputArray = $this->input->post();
		$Contact_Id = $inputArray['contactId'];
		$Organization_Id = $inputArray['OrgId'];
		$contactDetails = $this->contact_model->getContactDetails($Contact_Id,$Organization_Id);
		$response = $this->rest_model->bookAppointment($inputArray,$contactDetails,$Organization_Id);
	 	//print_r($response);exit;
		echo json_encode($response);
	}
	Public function bookCloudAppointment(){
		//print_r($this->input->post());exit;
		$inputArray = $this->input->post();
		$response = $this->rest_model->bookCloudAppointment($inputArray);
		header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		echo json_encode($response);
	}
	function newAppointment()
	{
		modules::run('bksl/auth/is_logged_in');
		$userDetails = modules::run('bksl/auth/get_user_details');
		$contactId = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		$provider = $userDetails->provider;
		$data['ContactDetails'] = $this->contact_model->getContactDetails($contactId,$organizationId);
		$contactSFId = $data['ContactDetails']->Salesforce_Id;
		$data['OrgDetails'] = $this->contact_model->getOrganizationDetails($organizationId);
		$data['userDetails'] = $userDetails;
		$GetURL = CLOUDSYNCAPIURL.'api/cloudapp/services/'.$organizationId.'/'.$provider;
		$data['serviceDetails'] = json_decode($this->RestGet($GetURL));
		//post('http://www.example.com', array('field1'=>'value1', 'field2'=>'value2'));
		//$data['serviceDetails'] = $this->contact_model->getServiceDetails($contactId,$organizationId);
		$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/contact.json');
		$jsonProfiledtls = json_decode($jsonProfiledata, true);
		$jsonProfileObject = $jsonProfiledtls['objects'];
		$jsonProfileTabs = $jsonProfiledtls['tabs'];
		$jsonPrfs = $jsonProfileTabs;
		$data['profileTabs'] = $jsonPrfs;
		
		$page = array();
		$page['showHeader'] = TRUE;
		$page['showFooter'] = TRUE;
		$page['page_name'] = 'contact/newAppointment';
		$page['title'] = 'New Appointment';
		$page['data'] = $data;  
		//print_r($data['OrgDetails']);exit;
		$this->load->view('contact_template', $page);
	}
	
	function formSearch(){ 
		if($this->tank_auth->is_logged_in()) {	
			$userDetails = $this->tank_auth->get_user_by_id();   
			$userType = $userDetails->user_type;
			$data['userDetails'] = $userDetails;
			$organizationId = $userDetails->Organization_Id;
			$searchVal = $this->input->post('searchVal'); 
						
			if($userDetails->user_type =='CONTACT'){
				
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/contact.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				//print_r($jsonProfileObject); exit;
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				$related_to = $userDetails->related_to;
				$numProfDtls = count($jsonProfiledtls['objects']);  
				$ExpertDetails	= $this->Expert_model->getExpertDetails($related_to,$organizationId);
				$data['ExpertDetails'] = $ExpertDetails;
				$searchResult  = [];
				$dataListView  = [];
				if($searchVal != ''){
					 
					foreach($jsonProfileObject as $row) {
						
						$jsonObjectFields = file_get_contents(base_url().'meta-data/object/'.$row['object']);
						$jsonObjectField = json_decode($jsonObjectFields, true);
						 
						$searchResult[$row['objectName']]  = $this->Rest_model->searchFormValues($jsonProfileObject[$j]['objectName'],$jsonObjectField,$searchVal);
					   
						$sellistView = $row['listView'];  
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
						
						//$allDetailsById = $this->objForm_model->getRecordsById($organizationId,$jsonProfileObject[0]['objectName']);
						$relatedObjects = [];
						//print_r($dataListView['fields']);exit;
						$objectFields = $dataListView['fields'];
						for($j=0;$j < count($objectFields); $j++){
							//print_r($objectFields[$j]);exit;
							if (array_key_exists('type', $objectFields[$j])) {
								if($objectFields[$j]['type'] === 'lookup'){
									$relatedObjects[$objectFields[$j]['relatedobject']] = $objectFields[$j];
								}
							} 
						}
						//print_r($relatedObjects);exit;
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								$relatedObjectData[$value[$k]->Salesforce_Id] = $value[$k]->Name;
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						
					}
				
				}  
				//print_r(json_encode($searchResult)); exit;
				$data['jsonDataListView'] = json_encode($dataListView);
				$data['allsearchResult'] = json_encode($searchResult);
				$page = array();  
				$page['showHeader'] = TRUE; 
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'searchForm/searchForm';
				$page['title'] = 'Search Result';
				$page['data'] = $data; 
				$this->load->view('contact_template',$page);
				
			}else if($userDetails->user_type =='EXPERT'){ 
				 
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				//print_r($jsonProfileObject); exit;
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				$related_to = $userDetails->related_to;
				$numProfDtls = count($jsonProfiledtls['objects']);  
				$ExpertDetails	= $this->Expert_model->getExpertDetails($related_to,$organizationId);
				$data['ExpertDetails'] = $ExpertDetails;
				$searchResult  = [];
				$dataListView  = [];

				if($searchVal != ''){
										
					foreach($jsonProfileObject as $row) {
						$jsonObjectFields = file_get_contents(base_url().'meta-data/object/'.$row['object']);
						$jsonObjectField = json_decode($jsonObjectFields, true);
						 
						$searchResult[$row['objectName']]  = $this->Rest_model->searchFormValues($row['objectName'],$jsonObjectField,$searchVal);
					   
						$sellistView = $row['listView']; 
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
						//$allDetailsById = $this->objForm_model->getRecordsById($organizationId,$jsonProfileObject[0]['objectName']);
						$relatedObjects = [];
					//	print_r($dataListViewdd['fields']);exit;
						$objectFields = $dataListViewdd['fields']; 
						for($j=0;$j < count($dataListViewdd['fields']); $j++){
							//print_r($objectFields[$j]);exit; 
							if (array_key_exists('type', $objectFields[$j])) {
								if($objectFields[$j]['type'] === 'lookup'){
									$relatedObjects[$objectFields[$j]['relatedobject']] = $objectFields[$j];
								}
							}
						}    
					//	print_r($searchResult);exit;
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								$relatedObjectData[$value[$k]->Salesforce_Id] = $value[$k]->Name;
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						
					}
				  
				}    
				//print_r($dataListView); exit;
				$data['jsonDataListView'] = json_encode($dataListView);
				$data['allsearchResult'] = json_encode($searchResult);
				$page = array();  
				$page['showHeader'] = TRUE; 
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'searchForm/searchForm';
				$page['title'] = 'Search Result';
				$page['data'] = $data;  
				$this->load->view('expert_template',$page);
			}else if($userDetails->user_type =='SUPERADMIN'){ 
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				//print_r($jsonProfileObject); exit;
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				$related_to = $userDetails->related_to;
				$numProfDtls = count($jsonProfiledtls['objects']);  
			 
				$searchResult  = [];
			  $dataListView  = [];
				if($searchVal != ''){ 
					 
					foreach($jsonProfileObject as $row) {
						
						$jsonObjectFields = file_get_contents(base_url().'meta-data/object/'.$row['object']);
						$jsonObjectField = json_decode($jsonObjectFields, true);
						 
						$searchResult[$row['objectName']]  = $this->Rest_model->searchFormValues($row['objectName'],$jsonObjectField,$searchVal);
					    
						$sellistView =$row['listView']; 
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
						//$allDetailsById = $this->objForm_model->getRecordsById($organizationId,$jsonProfileObject[0]['objectName']);
						$relatedObjects = [];
						//print_r($dataListView['fields']);exit;
						$objectFields = $dataListViewdd['fields'];
						for($j=0;$j < count($objectFields); $j++){
							//print_r($objectFields[$j]);exit;
							if (array_key_exists('type', $objectFields[$j])) {
								if($objectFields[$j]['type'] === 'lookup'){
									$relatedObjects[$objectFields[$j]['relatedobject']] = $objectFields[$j];
								}
							}
						}
						//print_r($relatedObjects);exit;
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								$relatedObjectData[$value[$k]->Salesforce_Id] = $value[$k]->Name;
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						
					} 
					
					
					
					
				 
				}  
				//print_r(json_encode($searchResult)); exit;
				$data['jsonDataListView'] = json_encode($dataListView);
				$data['allsearchResult'] = json_encode($searchResult);
				$page = array();  
				$page['showHeader'] = TRUE; 
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'searchForm/searchForm';
				$page['title'] = 'Search Result';
				$page['data'] = $data; 
				$this->load->view('superadmin_template',$page);
			}  
			
		}else{
			echo $this->tank_auth->is_logged_in();
			die();
		}
		
	}
	
	function calendar() 
	{
		modules::run('bksl/auth/is_logged_in');
		$userDetails = modules::run('bksl/auth/get_user_details');
		$related_to = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		$data['userDetails'] = $userDetails;
		$data['ExpertDetails'] = $this->expert_model->getExpertDetails($related_to,$organizationId);
		$data['OrgDetails'] = $this->contact_model->getOrganizationDetails($organizationId);
		$data['contactDetails'] = $this->expert_model->getContactDetails($organizationId);		
		
		$page = array();
		if($userDetails->user_type =='CONTACT'){
			$page['page_name'] = '';
			
			$page['showHeader'] = TRUE;
			$page['showFooter'] = TRUE;
			$page['title'] = 'Calendar';
			$page['data'] = $data;
			//print_r($data['OrgDetails']);exit;
			$this->load->view('contact_template', $page); 
			
		}else if($userDetails->user_type =='EXPERT'){
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$data['profileTabs'] = $jsonProfileTabs;
			$data['serviceDetails'] = $this->expert_model->getServiceDetails($data['ExpertDetails']->Salesforce_Id,$organizationId);
			$page['page_name'] = 'expert/calendar';
			
			$expertSFId = $data['ExpertDetails']->Salesforce_Id;
			
			$page['showHeader'] = TRUE;
			$page['showFooter'] = TRUE; 
			$page['title'] = 'Calendar';
			$page['data'] = $data;
			//print_r($data['OrgDetails']);exit;
			$this->load->view('expert_template', $page);
			
		}else if($userDetails->user_type =='SUPERADMIN'){
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$jsonPrfs = $jsonProfileTabs;  
			$data['profileTabs'] = $jsonPrfs;
			$data['serviceDetails'] = $this->admin_model->getAllServiceDetails($organizationId);
			$data['expertDetails'] = $this->admin_model->getAllExpertDetails($organizationId);
			$page['page_name'] = 'admin/calendar';
			
			$page['showHeader'] = TRUE; 
			$page['showFooter'] = TRUE;
			$page['title'] = 'Calendar';  
			$page['data'] = $data;
			//print_r($data['OrgDetails']);exit;
			$this->load->view('superadmin_template', $page);
		}
		
		
	}
	function cancel()
	{
		modules::run('bksl/auth/is_logged_in');
		$userDetails = modules::run('bksl/auth/get_user_details');
		if ($this->uri->segment(2))
		{
			$appId = $this->uri->segment(2);
			$this->contact_model->cancelApp($appId);
			redirect('booking/');
		}

	}
	function reschedule()
	{
		modules::run('bksl/auth/is_logged_in');
		$userDetails = modules::run('bksl/auth/get_user_details');
		if ($this->uri->segment(2))
		{
			$appId = $this->uri->segment(2);
			//echo $appId;
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/contact.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileObject = $jsonProfiledtls['objects'];
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$jsonPrfs = $jsonProfileTabs;
			$data['profileTabs'] = $jsonPrfs;
			$contactId = $userDetails->related_to;
			$organizationId = $userDetails->Organization_Id;
			$data['ContactDetails'] = $this->contact_model->getContactDetails($contactId,$organizationId);
			$data['userDetails'] = $userDetails;
			$contactSFId = $data['ContactDetails']->Salesforce_Id;
			$data['OrgDetails'] = $this->contact_model->getOrganizationDetails($organizationId);
			$data['app'] = $this->contact_model->getServiceRequestDetails($appId,$organizationId);
			$page = array();
			$page['showHeader'] = TRUE;
			$page['showFooter'] = TRUE;
			$page['page_name'] = 'contact/rescheduleAppointment';
			$page['title'] = 'Reschedule Appointment';
			$page['data'] = $data;
			//print_r($data['app']);exit;
			$this->load->view('contact_template', $page);
		}

	}
	
	public function getExpertAppointments()
	{
		$userDetails = modules::run('bksl/auth/get_user_details');
		$related_to = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		$data['OrgDetails'] = $this->contact_model->getOrganizationDetails($organizationId);
		$data['userDetails'] = $userDetails;
		$data['ExpertDetails'] = $this->expert_model->getExpertDetails($related_to,$organizationId);
		$expertSFId = $data['ExpertDetails']->Salesforce_Id;
		$appointmentDetails= $this->expert_model->getAppoitmentDetails($expertSFId,$organizationId);
		//$events[];
		foreach ($appointmentDetails as $app){
			$contactDetails = getContactDetailsById($organizationId,$app->Contact_Id);
			//echo $app->Contact_Id;exit;
			//print_r($contactDetails);exit;
			$events[] = array(
                'id'=> $app->Salesforce_Id,
                'title'=> $contactDetails->Name,
                'start'=> $app->Start_Date,
                'end'=> $app->End_Date,
                //'phone'=> serReq[i].phone,
                'status'=> $app->Status,
                //'email'=> serReq[i].email,
                'serviceName'=> $app->Name,
                'service'=> $app->Service_Id,
                //'provider'=> serReq[i].provider,
                'cusId'=> $app->Contact_Id,
                'expertId' => $expertSFId,
                'hasMultiSlot' => false,
                //'availableCount' => serReq[i].availableCount,
                //'bookedCount' => bookedCount,
                //'isPreferredExpert' => serReq[i].isPreferredExpert
             );
			//$events = array_merge($events,$event);
		}
 
	}
	Public function sync()
	{
		//echo $this->session->userdata('serviceid');exit;
		//if($this->session->userdata('sessionId')=='')
		//{
		$mySforceConnection = new SforcePartnerClient();
	
		$mySforceConnection->createConnection(PARTNERWSDL);
		$mySforceConnection->login('servicecube@bookingsocial.com', 'booking123HtCGx8LoWQ7eXet5gL4z1OSNa');
		$mySforceConnection->getLocation();
		//$query = "SELECT Id,Name FROM Contact";
		//$response = $mySforceConnection->query($query);
		//print_r($response);exit;
		$this->session->set_userdata('location',$mySforceConnection->getLocation());
		$this->session->set_userdata('sessionId',$mySforceConnection->getSessionId());
		$this->session->set_userdata('wsdl',PARTNERWSDL);
		//$this->rest_model->SyncContacts();
		$this->rest_model->SyncService();
		$this->rest_model->SyncExpert();
		$this->rest_model->SyncExpertService();
		//}else{
			
		//}
	}
}