<?php

class Bksl extends CI_Controller {
	
	function __construct()
	{  
		parent::__construct(); 
		$this->load->model('Expert_model');
		$this->load->model('user_model');
		$this->load->model('objForm_model');
		$this->load->library('tank_auth');
		$this->load->helper('date'); 
		$this->load->library('base62');
		$this->load->model('admin_model');
		$this->load->model('expert_model');
		$this->load->library('upload');
		$this->load->library('image_lib');
		$this->load->helper('security');
		$this->load->library('pagination');
	}
	function index() 
	{ 
	    
		//for($i=100; $i < 1000; $i++){
			//echo $this->base62->convert($i);
			//echo '<br/>';
		//}
		echo 'Hai';
		die();
	}
	 
	function profile()
	{
		if($this->tank_auth->is_logged_in()) {	
			$userDetails = $this->tank_auth->get_user_by_id();
			$userType = $userDetails->user_type; 
			$data['userDetails'] = $userDetails;
			if($userDetails->user_type =='CONTACT'){
				$contactId = $userDetails->related_to;
				$organizationId = $userDetails->Organization_Id;
				$data['ContactDetails'] = $this->user_model->getContactDetails($contactId,$organizationId);
				$contactSFId = $data['ContactDetails']->Salesforce_Id;
				$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/contact.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$data['profileTabs'] = $jsonProfileTabs;
				 
				$page = array();
				$page['showHeader'] = TRUE;
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'Contact/Profile';
				$page['title'] = 'Profile';
				$page['data'] = $data;
				//print_r($data);exit;
				$this->load->view('contact_template', $page);
			}else if($userDetails->user_type =='EXPERT'){   
				$expertId = $userDetails->related_to; 
				$organizationId = $userDetails->Organization_Id;
				//echo $organizationId; exit;          
				$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($expertId,$organizationId);
				
				//print_r($data['ExpertDetails']); exit;
				//$expertSFId = $data['ContactDetails']->Salesforce_Id;
				$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;  
												  
				$page = array();
				$page['showHeader'] = TRUE;
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'expert/Profile';     
				$page['title'] = 'Profile';
				$page['data'] = $data;
				//print_r($data);exit;
				$this->load->view('expert_template', $page); 
			}else if($userDetails->user_type =='SUPERADMIN'){ 
				$data['serviceDetails'] = $this->Admin_model->getAllServiceDetails($organizationId);
				$data['ExpertDetails'] = $this->Admin_model->getAllExpertDetails($organizationId);
				
								    
				$page = array(); 
				$page['showHeader'] = TRUE; 
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'admin/Dashboard';
				$page['title'] = 'Dashboard';
				$page['data'] = $data;
				//echo $expertId;
				//print_r($data);exit;
				$this->load->view('superadmin_template',$page);
			}
		}else{
			echo $this->tank_auth->is_logged_in();
			die();
		}
	}
	
	function editProfile()
	{
		
		if($this->tank_auth->is_logged_in()) {	
			$userDetails = $this->tank_auth->get_user_by_id();   
			$userType = $userDetails->user_type;
			$data['userDetails'] = $userDetails;
			
			if($userDetails->user_type =='CONTACT'){
					$contactId = $userDetails->related_to;
					$UserName = $this->input->post('FirstName');
					if($UserName != ''){
						$Contact_Id = $contactId;
						$data =array(
								'Email' => $this->input->post('Email'),
								'Id' => $Contact_Id,
								'LastName' => $this->input->post('LastName'),
								'FirstName' => $this->input->post('FirstName'),
								'MailingCountry' => $this->input->post('MailingCountry'),
								'MailingState' => $this->input->post('MailingState'),
								'MailingPostalCode' => $this->input->post('MailingPostalCode'),
								'MailingCity' => $this->input->post('MailingCity'),
								'MailingStreet' => $this->input->post('MailingStreet'),
						);
						$result = $this->user_model->updateContact($data);
						redirect('/bksl/profile');
				}else{
						
					$organizationId = $userDetails->Organization_Id;
					$data['userDetails'] = $userDetails;
					$data['ContactDetails'] = $this->user_model->getContactDetails($contactId,$organizationId);
					$contactSFId = $data['ContactDetails']->Salesforce_Id;
					$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
					$page = array();
					$page['showHeader'] = TRUE;
					$page['showFooter'] = TRUE;
					$page['page_name'] = 'contact/editProfile';
					$page['title'] = 'Edit Profile';
					$page['data'] = $data;
					//print_r($data);exit;
					$this->load->view('contact_template', $page);
						
				}
			}else if($userDetails->user_type =='EXPERT'){ 
				
				$expertId = $userDetails->related_to;
				$Name = $this->input->post('Name'); 
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
			//	print_r($jsonProfileTabs); exit;
				if($Name != ''){
					$Expert_Id = $expertId;
					
					$data =array(
							'Email' => $this->input->post('Email'),
							'Id' => $Expert_Id,
						  'Profile_Pic' => $this->input->post('pic1_input'),
							'Name' => $this->input->post('LastName'),
					);
					
					print_r($data); exit; 
					$result = $this->user_model->updateExpert($data);
					redirect('/bksl/profile');
				}else{    
					$data['userDetails'] = $userDetails;
					$expertId = $userDetails->related_to; 
					$organizationId = $userDetails->Organization_Id;
				  
					$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($expertId,$organizationId);
				//	print_r($data['ExpertDetails']); 
					$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
					$page = array();
					$page['showHeader'] = TRUE;
					$page['showFooter'] = TRUE;
					$page['page_name'] = 'expert/editProfile';   
					$page['title'] = 'Edit Profile';     
					$page['data'] = $data;   
					//print_r($data);exit;
					$this->load->view('expert_template', $page);
				}
			}  
			
		}else{
			echo $this->tank_auth->is_logged_in();
			die();
		}
	}
	
	function formTextSearch(){ 
		if($this->tank_auth->is_logged_in()) {	
			$userDetails = $this->tank_auth->get_user_by_id();   
			$userType = $userDetails->user_type;
			$data['userDetails'] = $userDetails;
			$organizationId = $userDetails->Organization_Id;
			
			$searchObject =  $this->uri->segment(2);
			$searchVal =  $this->uri->segment(3);
			
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
				$data['ContactDetails'] = $this->user_model->getContactDetails($contactId,$organizationId);
				$searchResult  = [];
				$dataListView  = [];
				if($searchVal != ''){
					 
					foreach($jsonProfileObject as $row) {
						
						if($row['objId'] == $searchObject){
							
						$jsonObjectFields = file_get_contents(base_url().'meta-data/object/'.$row['object']);
						$jsonObjectField = json_decode($jsonObjectFields, true);
						 
						$searchResult[$row['objectName']]  = $this->user_model->searchFormValues($row['objectName'],$jsonObjectField,$searchVal,$userDetails->provider);
					   
						$sellistView = $row['listView'];  
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
						
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
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] =array("value" => array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId),"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = $value[$k]->Name;
								
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						
					}
				 }
				}  
				//print_r(json_encode($searchResult)); exit;
				$data['jsonDataListView'] = json_encode($dataListView);
				$data['allsearchResult'] = json_encode($searchResult);
				$page = array();  
				$page['showHeader'] = TRUE; 
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'searchForm/searchSingleObjectForm';
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
						
						if($row['objId'] == $searchObject){
					
						$jsonObjectFields = file_get_contents(base_url().'meta-data/object/'.$row['object']);
						$jsonObjectField = json_decode($jsonObjectFields, true);
						 
						$searchResult[$row['objectName']]  = $this->user_model->searchFormValues($row['objectName'],$jsonObjectField,$searchVal,$userDetails->provider);
					   
						$sellistView = $row['listView']; 
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
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
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						
					}
				}
				}    
				//print_r($dataListView); exit;
				
				$data['searchVal'] = json_encode($searchVal);
				$data['jsonDataListView'] = json_encode($dataListView);
				$data['allsearchResult'] = json_encode($searchResult);
				$page = array();    
				$page['showHeader'] = TRUE; 
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'searchForm/searchSingleObjectForm';
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
						
						if($row['objId'] == $searchObject){ 
						
						$jsonObjectFields = file_get_contents(base_url().'meta-data/object/'.$row['object']);
						$jsonObjectField = json_decode($jsonObjectFields, true);
						 
						$searchResult[$row['objectName']]  = $this->user_model->searchFormValues($row['objectName'],$jsonObjectField,$searchVal,$userDetails->provider);
					    
						$sellistView =$row['listView']; 
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
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
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						
					} 
					}
				}  
				//print_r(json_encode($searchResult)); exit;
				$data['jsonDataListView'] = json_encode($dataListView);
				$data['allsearchResult'] = json_encode($searchResult);
				$page = array();  
				$page['showHeader'] = TRUE; 
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'searchForm/searchSingleObjectForm';
				$page['title'] = 'Search Result';
				$page['data'] = $data; 
				$this->load->view('superadmin_template',$page);
			}  
			
		}else{
			echo $this->tank_auth->is_logged_in();
			die();
		}
		
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
				$data['ContactDetails'] = $this->user_model->getContactDetails($contactId,$organizationId);
				$searchResult  = [];
				$dataListView  = [];
				if($searchVal != ''){
					 
					foreach($jsonProfileObject as $row) {
						
						$jsonObjectFields = file_get_contents(base_url().'meta-data/object/'.$row['object']);
						$jsonObjectField = json_decode($jsonObjectFields, true);
						 
						$searchResult[$row['objectName']]  = $this->user_model->searchFormValues($jsonProfileObject[$j]['objectName'],$jsonObjectField,$searchVal);
					   
						$sellistView = $row['listView'];  
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
						
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
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
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
						 
						$searchResult[$row['objectName']]  = $this->user_model->searchFormValues($row['objectName'],$jsonObjectField,$searchVal);
					   
						$sellistView = $row['listView']; 
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
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
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						
					}
				  
				}    
				//print_r($dataListView); exit;
				
				$data['searchVal'] = json_encode($searchVal);
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
						 
						$searchResult[$row['objectName']]  = $this->user_model->searchFormValues($row['objectName'],$jsonObjectField,$searchVal);
					    
						$sellistView =$row['listView']; 
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView[$row['objectName']] = json_decode($jsonDataListView, true);
						$dataListViewdd  = json_decode($jsonDataListView, true);
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
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
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
	
	function updateProfile()
	{
		if($this->tank_auth->is_logged_in()) {	
			$userDetails = $this->tank_auth->get_user_by_id();   
			$userType = $userDetails->user_type;
			$data['userDetails'] = $userDetails;
			$organizationId = $userDetails->Organization_Id;
			
			if($userDetails->user_type =='CONTACT'){
					$contactId = $userDetails->related_to;
					$UserName = $this->input->post('FirstName');
					if($UserName != ''){
						$Contact_Id = $contactId;
						$data =array(
								'Email' => $this->input->post('Email'),
								'Id' => $Contact_Id,
								'LastName' => $this->input->post('LastName'),
								'FirstName' => $this->input->post('FirstName'),
								'MailingCountry' => $this->input->post('MailingCountry'),
								'MailingState' => $this->input->post('MailingState'),
								'MailingPostalCode' => $this->input->post('MailingPostalCode'),
								'MailingCity' => $this->input->post('MailingCity'),
								'MailingStreet' => $this->input->post('MailingStreet'),
						);
						$result = $this->user_model->updateContact($data);
						redirect('/bksl/profile');
				  }
			}else if($userDetails->user_type =='EXPERT'){ 
				
				$expertId = $userDetails->related_to;
				$Name = $this->input->post('Name'); 
				 
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
					$config =  array(
								'upload_path'     => './profilePic/',
								'upload_url'      => base_url()."profilePic/",
								'allowed_types'   => "gif|jpg|png|jpeg",
								'overwrite'       => TRUE,
								'max_size'        => "500KB",
								'max_height'      => "768",
								'max_width'       => "1024", 
							);  
					$this->load->library('upload', $config);

					if(!$this->upload->do_upload()) {               
							$this->data['error'] = $this->upload->display_errors(); 
						
						  print_r($this->data['error']); exit;
						
					} else {
							echo "file upload success"; exit; 
						                                   
					}  
			
				
				if($Name != ''){ 
					$Expert_Id = $expertId; 
					$data =array( 
							'Email' => $this->input->post('Email'),
						  'Phone' => $this->input->post('Phone'),
							'Id' => $Expert_Id,
						  'Profile_Pic' => $this->input->post('pic1_input'),
							'Name' => $this->input->post('Name')
					);
 			  	$result = $this->user_model->updateExpert($data);
					redirect('/bksl/profile');
				}
			}  
			
		}else{
			echo $this->tank_auth->is_logged_in();
			die();
		}
	}
	
	
	function objectListView()
	{
		if($this->tank_auth->is_logged_in()) {
			$Salesforce_Id =  $this->uri->segment(2); 
			$userDetails = $this->tank_auth->get_user_by_id();
			$contactId = $userDetails->related_to;
			$organizationId = $userDetails->Organization_Id;
			$userType = $userDetails->user_type; 
			$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);	
			
			if($userDetails->user_type =='CONTACT'){ 
				//$jsondata = file_get_contents(base_url().'meta-data/main/dynObject.json');
				//$dynObjectData = json_decode($jsondata, true);
				//$ObjectVal = $dynObjectData['object'];
				//$idvals = strlen($Salesforce_Id);
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				
				for ( $i= 0; $i < $numProfDtls; $i++) {
					if($jsonProfileObject[$i]['objId'] == $Salesforce_Id){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$ContactDetails	= $this->user_model->getContactDetails($contactId,$organizationId);
						$data['ContactDetails'] = $ContactDetails;
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selFilter = $jsonProfileObject[$i]['layout']; 
						$sellistView = $jsonProfileObject[$i]['listView']; 
						
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView = json_decode($jsonDataListView, true);
						$data['jsonDataListView'] = $jsonDataListView;
						
						$data['allDetailsById'] = $this->objForm_model->getRecordsById($organizationId,$selObjectName,$userDetails->provider);
						$relatedObjects = [];
						//print_r($dataListView['fields']);exit;
						$objectFields = $dataListView['fields'];
						for($j=0;$j < count($objectFields) ; $j++){
							//print_r($objectFields[$j]);exit;
							if (array_key_exists('type', $objectFields[$j])) {
								if($objectFields[$j]['type'] === 'lookup'){
									$relatedObjects[$objectFields[$j]['relatedobject']] = $objectFields[$j];
								}
							}
						}
						//print_r($relatedObjects);exit;
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectList';
						$page['title'] = 'Contact details';
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('contact_template', $page); 
					}
				}
				
			}else if($userDetails->user_type =='EXPERT'){
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				
				for ( $i= 0; $i < $numProfDtls; $i++) {
					if($jsonProfileObject[$i]['objId'] == $Salesforce_Id){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$ExpertDetails	= $this->Expert_model->getExpertDetails($related_to,$organizationId);
						$data['ExpertDetails'] = $ExpertDetails;
						$ExpertSfId = $ExpertDetails->Salesforce_Id;
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selFilter = $jsonProfileObject[$i]['layout']; 
						$sellistView = $jsonProfileObject[$i]['listView']; 
						
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView = json_decode($jsonDataListView, true);
						$data['jsonDataListView'] = $jsonDataListView;
						
						$allDetailsById = $this->objForm_model->getRecordsById($organizationId,$selObjectName,$userDetails->provider);
						$relatedObjects = [];
						//print_r($dataListView['fields']);exit;
						$objectFields = $dataListView['fields'];
						for($j=0;$j < count($objectFields) ; $j++){
							//print_r($objectFields[$j]);exit;
							if (array_key_exists('type', $objectFields[$j])) {
								if($objectFields[$j]['type'] === 'lookup'){
									$relatedObjects[$objectFields[$j]['relatedobject']] = $objectFields[$j];
								}
							}
						}
						//print_r($relatedObjects);exit;
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						$data['allDetailsById'] = json_encode($allDetailsById);
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectList';
						$page['title'] = $dataListView['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit; 
						$this->load->view('expert_template', $page);
					}
				}
			}else if($userDetails->user_type =='SUPERADMIN'){  
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				
				$jsonProfileTabs = $jsonProfiledtls['tabs']; 
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				 
				$numProfDtls = count($jsonProfiledtls['objects']); 
				 
				for ( $i= 0; $i < $numProfDtls; $i++) {
					if($jsonProfileObject[$i]['objId'] == $Salesforce_Id){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						
						$data['ExpertDetails'] = $this->admin_model->getAllExpertDetails($organizationId);
						//$ExpertSfId = $ExpertDetails->Salesforce_Id;
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selFilter = $jsonProfileObject[$i]['layout']; 
						$sellistView = $jsonProfileObject[$i]['listView']; 
						
						$jsonDataListView= file_get_contents(base_url().'meta-data/view/'.$sellistView);
						$dataListView = json_decode($jsonDataListView, true);
						$data['jsonDataListView'] = $jsonDataListView;
						$allDetailsById = $this->objForm_model->getRecordsById($organizationId,$selObjectName,$userDetails->provider);
						$relatedObjects = [];
						//print_r($dataListView['fields']);exit;
						$objectFields = $dataListView['fields'];
						//print_r($objectFields);exit;
						for($j=0;$j < count($objectFields) ; $j++){
							//print_r($objectFields[$j]);exit;
							if (array_key_exists('type', $objectFields[$j])) {
								if($objectFields[$j]['type'] === 'lookup'){
									$relatedObjects[$objectFields[$j]['relatedobject']] = $objectFields[$j];
								}
							}
						}
						//print_r($relatedObjects);exit;
						$relatedListObjects = $this->objForm_model->getRelatedListObjects($organizationId,$relatedObjects,$userDetails->provider);
						$relatedObjectData = [];
						foreach ($relatedListObjects as $value) {
							for($k = 0; $k < count($value); $k++){
								//print_r($value[$k]);exit;
								if($value[$k]->Salesforce_Id != '' && $value[$k]->Salesforce_Id != NULL)
									$relatedObjectData[$value[$k]->Salesforce_Id] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
								else
									$relatedObjectData[$value[$k]->uId] = array("value" => $value[$k]->Name,"urlId"=>$value[$k]->uId);
							}
						}
						$data ['relatedObjectData'] = json_encode($relatedObjectData);
						$data['allDetailsById'] = json_encode($allDetailsById);
						$page = array();
						$page['showHeader'] = TRUE;   
						$page['showFooter'] = TRUE; 
						$page['page_name'] = 'objectForm/objectList';
						$page['title'] = $dataListView['title']; 
						$page['data'] = $data;
						$this->load->view('superadmin_template', $page);  
					} 
				}  
			}
		}
	} 
	
	function objectEdit()
	{
		
		if($this->tank_auth->is_logged_in()) {
			$uId =  $this->uri->segment(2); 
			
			$mode =  $this->uri->segment(3);  
			$userDetails = $this->tank_auth->get_user_by_id();
			$contactId = $userDetails->related_to;
			$organizationId = $userDetails->Organization_Id;
			$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
			
			$data['userDetails'] = $userDetails;
			$data['ContactDetails'] = $this->user_model->getContactDetails($contactId,$organizationId);
			$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($contactId,$organizationId);
			
			$arr1 = str_split($uId, 3);
			$jsondata = file_get_contents(base_url().'meta-data/main/dynObject.json');
			$dynObjectData = json_decode($jsondata, true);
			$ObjectVal = $dynObjectData['object'];
			$idvals = strlen($uId);
			
			
			if($userDetails->user_type =='CONTACT'){ 
				
				foreach($ObjectVal as $row)
				{
					if($arr1[0] == $row['id']){
						$selObject = $row['object'];
						$selFilter = $row['layout']; 
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selFilter);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						
						$numberOfFields = count($FeildsDt);
						for ( $i= 0; $i < $numberOfFields; $i++) {
							$FeildsDt[$i]['value'] = $ContactDetalsById->$FeildsDt[$i]['fieldname'];
							if($FeildsDt[$i]['type'] == "lookup"){
								$relatedObjData = $this->objForm_model->getDetailsBySalesforceId($FeildsDt[$i]['value'],$FeildsDt[$i]['relatedobject'],$organizationId); 
								if($relatedObjData !== null)
									$FeildsDt[$i]['lookupName'] = $relatedObjData->Name;
								
							}
						}
						$data['jsondataFeild'] =json_encode($FeildsDt);
						$data['selObjectName'] = $selObject;
						
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						
						$this->load->view('contact_template', $page);

					}

				}
				
			} else if($userDetails->user_type =='EXPERT'){ 
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				for ( $i= 0; $i<$numProfDtls; $i++) {
					
					if($jsonProfileObject[$i]['objId'] == $arr1[0]){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($related_to,$organizationId);
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selLayout = $jsonProfileObject[$i]['layout']; 
						
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						//print_r($jsondataFields); exit;
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selLayout);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
											
						$allDetailsByRecordId = $this->objForm_model->getDetailsByRecordId($uId,$selObjectName);
						$data['DetailsByRecordId'] = $allDetailsByRecordId;
						
						$numberOfFields = count($FeildsDt);
						for ( $i= 0; $i < $numberOfFields; $i++) {
							$FeildsDt[$i]['value'] = $allDetailsByRecordId->$FeildsDt[$i]['fieldname'];
							if($FeildsDt[$i]['type'] == "lookup"){
								$relatedObjData = $this->objForm_model->getDetailsBySalesforceId($FeildsDt[$i]['value'],$FeildsDt[$i]['relatedobject'],$organizationId); 
							 
								if($relatedObjData !== null)
									$FeildsDt[$i]['lookupName'] = $relatedObjData->Name;
								
							}
						}
						
						$data['jsondataFeild'] =json_encode($FeildsDt);
						//print_r($FeildsDt); exit;
						$data['selObjectName'] = $selObjectName;
						$page = array(); 
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('expert_template', $page);
											
					}
				}
			}else if($userDetails->user_type =='SUPERADMIN'){ 
				 
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				
				 
				for ( $i= 0; $i<$numProfDtls; $i++) {
					
					if($jsonProfileObject[$i]['objId'] == $arr1[0]){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($related_to,$organizationId);
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selLayout = $jsonProfileObject[$i]['layout']; 
						
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						//print_r($dynFeildsData); exit; 
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selLayout);
						$dynFilterData = json_decode($jsondataFilter, true);
						
						$data['jsondataFilters'] = $jsondataFilter; 
											
						$allDetailsByRecordId = $this->objForm_model->getDetailsByRecordId($uId,$selObjectName);
						$data['DetailsByRecordId'] = $allDetailsByRecordId;
						 					
						   
						$numberOfFields = count($FeildsDt);
						for ( $i= 0; $i < $numberOfFields; $i++) {
							$FeildsDt[$i]['value'] = $allDetailsByRecordId->$FeildsDt[$i]['fieldname'];
							if($FeildsDt[$i]['type'] == "lookup"){
								$relatedObjData = $this->objForm_model->getDetailsBySalesforceId($FeildsDt[$i]['value'],$FeildsDt[$i]['relatedobject'],$organizationId); 
								if($relatedObjData !== null)
									$FeildsDt[$i]['lookupName'] = $relatedObjData->Name;
								
							}
						}
						
						$data['jsondataFeild'] =json_encode($FeildsDt);
						$data['selObjectName'] = $selObjectName;
						$page = array(); 
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';  
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;  
						$this->load->view('superadmin_template', $page);
											
					}
				}
			}
		}
	}
	
	function objectDetailView()
	{
		if($this->tank_auth->is_logged_in()) {
			 
			$uId =  $this->uri->segment(2); 
			$userDetails = $this->tank_auth->get_user_by_id();
			$contactId = $userDetails->related_to;
			$organizationId = $userDetails->Organization_Id;
			$arr1 = str_split($uId, 3);
			$jsondata = file_get_contents(base_url().'meta-data/main/dynObject.json');
			$dynObjectData = json_decode($jsondata, true);
			$ObjectVal = $dynObjectData['object'];
			$idvals = strlen($uId);
			  
			if($userDetails->user_type =='CONTACT'){ 
				foreach($ObjectVal as $row)
				{
					if($arr1[0] == $row['id']){
						$selObject = $row['object'];
						$selFilter = $row['layout']; 
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selFilter);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						
						$numberOfFields = count($FeildsDt);
						for ( $i= 0; $i < $numberOfFields; $i++) {
							
							$FeildsDt[$i]['value'] = $ContactDetalsById->$FeildsDt[$i]['fieldname'];	
							if($FeildsDt[$i]['type'] == "lookup"){
								$relatedObjData = $this->objForm_model->getDetailsBySalesforceId($FeildsDt[$i]['value'],$FeildsDt[$i]['relatedobject'],$organizationId); 
								if($relatedObjData !== null){
									$FeildsDt[$i]['value'] = $relatedObjData->Name;
									$FeildsDt[$i]['lookupURL'] = base_url().'bksl/'.$relatedObjData->uId;
									$FeildsDt[$i]['isLookup'] = true;
								}
								
							}else{
								$FeildsDt[$i]['isLookup'] = false;
							}
							$FeildsDt[$i]['type'] = 'textView';
						}
						
						$data['jsondataFeild'] =json_encode($FeildsDt);
						$data['selObjectName'] = $selObject;
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('contact_template', $page);
					}
				}
				
			}else if($userDetails->user_type =='EXPERT'){
				
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				
				$jsonProfileObject = $jsonProfiledtls['objects'];
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				for ( $i= 0; $i < $numProfDtls; $i++) {
					
					if($jsonProfileObject[$i]['objId'] == $arr1[0]){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($related_to,$organizationId);
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selLayout = $jsonProfileObject[$i]['layout']; 
						
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selLayout);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						
						$allDetailsByRecordId = $this->objForm_model->getDetailsByRecordId($uId,$selObjectName);
						$data['DetailsByRecordId'] = $allDetailsByRecordId;
						
						$numberOfFields = count($FeildsDt);
						for ( $i= 0; $i < $numberOfFields; $i++) {
							$FeildsDt[$i]['value'] = $allDetailsByRecordId->$FeildsDt[$i]['fieldname'];
							if($FeildsDt[$i]['type'] == "lookup"){
								$relatedObjData = $this->objForm_model->getDetailsBySalesforceId($FeildsDt[$i]['value'],$FeildsDt[$i]['relatedobject'],$organizationId); 
								if($relatedObjData !== null){
									$FeildsDt[$i]['value'] = $relatedObjData->Name;
									$FeildsDt[$i]['lookupURL'] = base_url().'bksl/'.$relatedObjData->uId;
									$FeildsDt[$i]['isLookup'] = true;
								}
								
							}else{
								$FeildsDt[$i]['isLookup'] = false;
							}
							$FeildsDt[$i]['type'] = 'textView';
						}
						
						$data['jsondataFeild'] =json_encode($FeildsDt);
						$data['selObjectName'] = $selObjectName;
						$page = array(); 
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('expert_template', $page);
					}
				}
			}else if($userDetails->user_type =='SUPERADMIN'){ 
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				$jsonProfileObject = $jsonProfiledtls['objects'];
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				//print_r($jsonPrfs); exit;   
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				for ( $i= 0; $i<$numProfDtls; $i++) {
					
					if($jsonProfileObject[$i]['objId'] == $arr1[0]){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$data['expertDetails'] = $this->admin_model->getAllExpertDetails($organizationId);
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selLayout = $jsonProfileObject[$i]['layout'];  
						
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						//print_r($jsondataFields); exit;
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selLayout);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
											
						$allDetailsByRecordId = $this->objForm_model->getDetailsByRecordId($uId,$selObjectName);
						$data['DetailsByRecordId'] = $allDetailsByRecordId;
						$numberOfFields = count($FeildsDt);
						for ( $i= 0; $i < $numberOfFields; $i++) {
							$FeildsDt[$i]['value'] = $allDetailsByRecordId->$FeildsDt[$i]['fieldname'];
							if($FeildsDt[$i]['type'] == "lookup"){
								$relatedObjData = $this->objForm_model->getDetailsBySalesforceId($FeildsDt[$i]['value'],$FeildsDt[$i]['relatedobject'],$organizationId); 
								if($relatedObjData !== null){
									$FeildsDt[$i]['value'] = $relatedObjData->Name;
									$FeildsDt[$i]['lookupURL'] = base_url().'bksl/'.$relatedObjData->uId;
									$FeildsDt[$i]['isLookup'] = true;
								}
								
							}else{
								$FeildsDt[$i]['isLookup'] = false;
							}
							$FeildsDt[$i]['visibleType'] = $FeildsDt[$i]['type'];
							$FeildsDt[$i]['type'] = 'textView';
							 							
						}
						
						$data['jsondataFeild'] =json_encode($FeildsDt);
						$data['selObjectName'] = $selObjectName;
						$page = array(); 
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						
						$this->load->view('superadmin_template', $page);
											
					} 
				}
			}
		}	
	}
	 
	
	function objectNew()
	{
		if($this->tank_auth->is_logged_in()) {
			
			$Salesforce_Id =  $this->uri->segment(2); 
			$mode =  $this->uri->segment(3);
			$userDetails = $this->tank_auth->get_user_by_id();
			$contactId = $userDetails->related_to;
			$organizationId = $userDetails->Organization_Id;
			$data['userDetails'] = $userDetails;
			$data['ContactDetails'] = $this->user_model->getContactDetails($contactId,$organizationId);
		
			$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
			
			$arr1 = str_split($Salesforce_Id, 3);
			$jsondata = file_get_contents(base_url().'meta-data/main/dynObject.json');
			$dynObjectData = json_decode($jsondata, true);
			$ObjectVal = $dynObjectData['object'];
			$idvals = strlen($Salesforce_Id);
			
			if($userDetails->user_type =='CONTACT'){ 
			
				foreach($ObjectVal as $row)
				{
					if($arr1[0] == $row['id']){
						$selObject = $row['object'];
						$selFilter = $row['layout']; 

						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selFilter);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						$data['jsondataFeild'] =json_encode($FeildsDt);
						$data['selObjectName'] = $selObject;
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('contact_template', $page);
					}
				}
			}else if($userDetails->user_type =='EXPERT'){
				
				
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/expert.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				
				$jsonProfileObject = $jsonProfiledtls['objects'];
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				for ( $i= 0; $i < $numProfDtls; $i++) {
					
					if($jsonProfileObject[$i]['objId'] == $arr1[0]){
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($related_to,$organizationId);
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selLayout = $jsonProfileObject[$i]['layout']; 
						
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
					//	print_r($jsondataFields); exit; 
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selLayout);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						
						$allDetailsByRecordId = $this->objForm_model->getDetailsByRecordId($Salesforce_Id,$selObjectName);
						$data['DetailsByRecordId'] = $allDetailsByRecordId;
												
						$data['jsondataFeild'] =json_encode($FeildsDt);
 						$data['selObjectName'] = $selObjectName;
						$page = array(); 
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('expert_template', $page);
					}
				}
			}else if($userDetails->user_type =='SUPERADMIN'){ 
									
				$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
				$jsonProfiledtls = json_decode($jsonProfiledata, true);
				
				$jsonProfileObject = $jsonProfiledtls['objects'];
				$jsonProfileTabs = $jsonProfiledtls['tabs'];
				$jsonPrfs = $jsonProfileTabs;
				$data['profileTabs'] = $jsonPrfs;
				
				$numProfDtls = count($jsonProfiledtls['objects']);
				for ( $i= 0; $i < $numProfDtls; $i++) {
					
					if($jsonProfileObject[$i]['objId'] == $arr1[0]){  
						$related_to = $userDetails->related_to;
						$data['userDetails'] = $userDetails;
						$data['ExpertDetails'] = $this->Expert_model->getExpertDetails($related_to,$organizationId);
						$selObjectName = $jsonProfileObject[$i]['objectName'];
						$selObject = $jsonProfileObject[$i]['object'];
						$selLayout = $jsonProfileObject[$i]['layout']; 
						
						$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
						$dynFeildsData = json_decode($jsondataFields, true);
						$FeildsDt = $dynFeildsData['fields'];
						
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selLayout);
						$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						
						$allDetailsByRecordId = $this->objForm_model->getDetailsByRecordId($Salesforce_Id,$selObjectName);
						$data['DetailsByRecordId'] = $allDetailsByRecordId;
						
						$data['jsondataFeild'] =json_encode($FeildsDt);
						$data['selObjectName'] = $selObjectName;
						$page = array(); 
						$page['showHeader'] = TRUE; 
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'objectForm/objectForm';
						$page['title'] = $dynFilterData['title'];
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('superadmin_template', $page);
					}
				}
			}
		}
	} 
	
	public function saveRecord()
	{   
		//print_r($this->input->post());exit;
		$userDetails = $this->tank_auth->get_user_by_id();
		$organizationId = $userDetails->Organization_Id;
		$OrgDetails = $this->user_model->getOrganizationDetails($organizationId);
		$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
		$jsonProfiledtls = json_decode($jsonProfiledata, true);
		$jsonProfileObject = $jsonProfiledtls['objects'];
		$numProfDtls = count($jsonProfiledtls['objects']);
		$sfIdVal = $this->input->post('urlId');
		$arr1 = str_split($sfIdVal, 3); 
		$post = array();
		 
		for ( $i= 0; $i < $numProfDtls; $i++) {
			if($jsonProfileObject[$i]['objId'] == $arr1[0]){
				$selObject = $jsonProfileObject[$i]['object'];
				$selObjectName = $jsonProfileObject[$i]['objectName'];
				$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
				$dynFieldsData = json_decode($jsondataFields, true);
				//print_r($dynFieldsData['sfMapping']);exit;
				$isSFMapping =  false;
				if(array_key_exists ( 'sfMapping' , $dynFieldsData)){
					$isSFMapping = $dynFieldsData['sfMapping'];
				}
				if(strlen($sfIdVal) == 15){ 
					foreach ( $_POST as $key => $value )
					{
						$post[$key] = $this->input->post($key);
						//if($post[$key] == 'on')
							//$post[$key] = TRUE;
					}
					$post['Provider'] = $userDetails->provider;
					//var_dump($post);  
					//print_r($post);exit;
					$result = $this->objForm_model->updateRecord($post, $sfIdVal, $selObjectName,$OrgDetails,$isSFMapping);
					//print $result; exit;  
					redirect('bksl/'.$sfIdVal);  
					
				}else if(strlen($sfIdVal) == 3){
					
					foreach ( $_POST as $key => $value )
					{
						
						$post[$key] = $this->input->post($key);
						if($post[$key] == 'on')
							$post[$key] = TRUE;
					}
					//var_dump($post); 
					$post['Provider'] = $userDetails->provider;
					$result = $this->objForm_model->createRecord($post,$arr1[0],$organizationId,$selObjectName,$OrgDetails,$isSFMapping);
					redirect('bksl/'.$result);	 
					
				}
			}
		}
	}
	
	public function deleteRecord() 
	{
		
		$uId =  $this->uri->segment(2);
		$mode =  $this->uri->segment(3);
		
		$userDetails = $this->tank_auth->get_user_by_id();
		$organizationId = $userDetails->Organization_Id;
		$OrgDetails = $this->user_model->getOrganizationDetails($organizationId);
		$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
		$jsonProfiledtls = json_decode($jsonProfiledata, true);
		$jsonProfileObject = $jsonProfiledtls['objects'];
		$numProfDtls = count($jsonProfiledtls['objects']);
		$arr1 = str_split($uId, 3);
				 
		for ( $i= 0; $i < $numProfDtls; $i++) {
			  
			//echo $jsonProfileObject[$i]['objId']; exit;
			if($jsonProfileObject[$i]['objId'] == $arr1[0]){
			
				$selObject = $jsonProfileObject[$i]['object'];
				$selObjectName = $jsonProfileObject[$i]['objectName'];
				$jsondataFields = file_get_contents(base_url().'meta-data/object/'.$selObject);
				$dynFieldsData = json_decode($jsondataFields, true);
				$FeildsDt = $dynFieldsData['fields'];
			 					  
				$result = $this->objForm_model->deleteRecord($OrgDetails,$uId, $selObjectName);
				redirect('bksl/'.$arr1[0]);   
		}
	} 
	}
	
	public function expertSettings()
	{
		$inputArr =$this->input->post();
		$result=$this->Expert_model->updateExpertDetails($inputArr);
		echo json_encode($result);
	}
	public function adminSettings()
	{
		$inputArr =$this->input->post();
		$result=$this->admin_model->updateSettings($inputArr["OrgId"],$inputArr);
		echo json_encode($result);
	}
	public function contactSett()
	{
		$inputArr =$this->input->post();
		if($inputArr['Do_Not_Distrub'] == 'on')
			$inputArr['Do_Not_Distrub'] = true;
		else
			$inputArr['Do_Not_Distrub']= false;
		$result=$this->user_model->updateContactSettings($inputArr);
		echo json_encode($result);
	}
	
	public function uploadprofilepic()
	{
			
			$error = array();
			$field_name = 'userfile';
			$file_name =  do_hash($_FILES['userfile']['name'], 'md5'); // MD5;
			
			$upload_conf = array(
            'upload_path'   => realpath('./upload/profile/'),
            'allowed_types' => 'gif|jpg|png',
            'max_size'      => '30000',
						'file_name' => $file_name
            );
			$this->upload->initialize( $upload_conf );

            if ( ! $this->upload->do_upload($field_name))
            {
                // if upload fail, grab error 
                $error['upload'][] = $this->upload->display_errors();
            }
            else
            {
                // otherwise, put the upload datas here.
                // if you want to use database, put insert query in this loop
                $upload_data = $this->upload->data();
                
                // set the resize config
                $resize_conf = array(
                    // it's something like "/full/path/to/the/image.jpg" maybe
                    'source_image'  => $upload_data['full_path'], 
                    // and it's "/full/path/to/the/" + "thumb_" + "image.jpg
                    // or you can use 'create_thumbs' => true option instead
                    'new_image'     => $upload_data['file_path'].'thumb_'.$upload_data['file_name'],
                    'width'         => 160,
                    'height'        => 160
                    );

                // initializing
                $this->image_lib->initialize($resize_conf);

                // do it!
                if ( ! $this->image_lib->resize())
                {
                    // if got fail.
                    $error['resize'][] = $this->image_lib->display_errors();
                }
                else
                {
                    // otherwise, put each upload data to an array.
                    $success[] = $upload_data;
                }
            }

        // see what we get
        if(count($error) > 0 )
        {
            $data['error'] = $error;
        }
        else
        {
            $data['success'] = $upload_data;
						$userDetails = $this->tank_auth->get_user_by_id();
						//print_r($userDetails);exit;
						$inputs['Id'] = $userDetails->id;
						$inputs['profile'] = $upload_data['file_name'];
						$this->user_model->updateUserProfile($inputs);
        }
		
		echo json_encode($data);
	}
	
	function users()
	{
		if($this->tank_auth->is_logged_in()) {
			$userDetails = $this->tank_auth->get_user_by_id();
			$related_to = $userDetails->related_to;
			$organizationId = $userDetails->Organization_Id;
			$data['userDetails'] = $userDetails;
			$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
			 //pagination settings
					$config['base_url'] = site_url('booking/users');
					$config['total_rows'] = $this->db->count_all('users');
					$config['per_page'] = "10";
					$config["uri_segment"] = 3;
					$choice = $config["total_rows"] / $config["per_page"];
					$config["num_links"] = floor($choice);

					//config for bootstrap pagination class integration
					$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
					$config['full_tag_close'] = '</ul>';
					$config['first_link'] = false;
					$config['last_link'] = false;
					$config['first_tag_open'] = '<li>';
					$config['first_tag_close'] = '</li>';
					$config['prev_link'] = '&laquo';
					$config['prev_tag_open'] = '<li class="prev">';
					$config['prev_tag_close'] = '</li>';
					$config['next_link'] = '&raquo';
					$config['next_tag_open'] = '<li>';
					$config['next_tag_close'] = '</li>';
					$config['last_tag_open'] = '<li>';
					$config['last_tag_close'] = '</li>';
					$config['cur_tag_open'] = '<li class="active"><a href="#">';
					$config['cur_tag_close'] = '</a></li>';
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';

					$this->pagination->initialize($config);
					$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

					//call the model function to get the department data
					$data['usertList'] = $this->admin_model->getAllUsers($organizationId,$config["per_page"], $data['page'],$userDetails->id);           

					$data['pagination'] = $this->pagination->create_links();

			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$jsonPrfs = $jsonProfileTabs;  
			$data['profileTabs'] = $jsonPrfs;

			$page = array();
			$page['showHeader'] = TRUE;
			$page['showFooter'] = TRUE;
			$page['page_name'] = 'admin/users';
			$page['title'] = 'Users';
			$page['data'] = $data;
			//print_r($data['OrgDetails']);exit;
			$this->load->view('superadmin_template', $page);
		}
	}
	function newuser()
	{
		if($this->tank_auth->is_logged_in()) {
			$userDetails = $this->tank_auth->get_user_by_id();
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$jsonPrfs = $jsonProfileTabs;  
			$data['profileTabs'] = $jsonPrfs;
			$related_to = $userDetails->related_to;
			$organizationId = $userDetails->Organization_Id;
			//$organizationId = $userDetails->Organization_Id;
			$data['userDetails'] = $userDetails;
			$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
			$data['contactDetails'] = $this->expert_model->getContactDetails($organizationId);
			$data['expertDetails'] = $this->expert_model->getExpertDetailsByOrg($organizationId);
			$page = array();
			$page['showHeader'] = TRUE;
			$page['showFooter'] = TRUE;
			$page['page_name'] = 'admin/newuser';
			$page['title'] = 'New user';
			$page['data'] = $data;
			//print_r($data['OrgDetails']);exit;
			$this->load->view('superadmin_template', $page);
		}
	}
	function viewuser()
	{
		if($this->tank_auth->is_logged_in()) {
			$userDetails = $this->tank_auth->get_user_by_id();
			$jsonProfiledata = file_get_contents(base_url().'meta-data/profile/admin.json');
			$jsonProfiledtls = json_decode($jsonProfiledata, true);
			$jsonProfileTabs = $jsonProfiledtls['tabs'];
			$jsonPrfs = $jsonProfileTabs;  
			$data['profileTabs'] = $jsonPrfs;
			if($this->uri->segment(3)){
				$user_id =$this->uri->segment(3);
				$related_to = $userDetails->related_to;
				$organizationId = $userDetails->Organization_Id;
				$data['userDetails'] = $userDetails;
				$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
				$data['editUserDetails'] = $this->admin_model->getUserDetailsById($user_id,$organizationId);
				$page = array();
				$page['showHeader'] = TRUE;
				$page['showFooter'] = TRUE;
				$page['page_name'] = 'admin/viewuser';
				$page['title'] = 'User Details';
				$page['data'] = $data;
				//print_r($data['OrgDetails']);exit;
				$this->load->view('superadmin_template', $page);
			}else{
				echo 'invalid';
			}
		}
	}
}