<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class AjaxHandler extends CI_Controller  {

	 function __construct() {
      
        // then execute the parent constructor anyway
        parent::__construct();
				$this->ci =& get_instance();
				$this->load->helper('url');
				$this->load->helper('xml');
				$this->load->library('form_validation');
				$this->load->library('security');
				$this->load->model('rest_model');
				$this->load->model('user_model');
				$this->load->model('objForm_model');
				$this->load->library('tank_auth'); 
				$this->lang->load('tank_auth');
				$this->load->library('base62');

    }
	public function getExpertsByService()
	{
		$service_Id = $this->input->get('ser');
		$result = $this->user_model->getExpertByService($service_Id);
		echo json_encode($result);		
	}
	
	Public function getServiceAvailability(){
		$service_Id = $this->input->get('ser');
		$provider_Id = $this->input->get('prv');
		$Organization_Id = $this->input->get('org');
		//print_r($this->input->get());exit;
		$response = $this->rest_model->getServiceAvailability($service_Id,$provider_Id,$Organization_Id);
		echo json_encode($response);
	}
	Public function getExpertAvailability(){
		$service_Id = $this->input->get('ser');
		$provider_Id = $this->input->get('prv');
		$expert_Id = $this->input->get('expert');
		$Organization_Id = $this->input->get('org');
		//print_r($this->input->get());exit;
		$response = $this->rest_model->getExpertAvailability($expert_Id,$service_Id,$provider_Id,$Organization_Id);
		echo json_encode($response);
	}
	public function getAllSettings(){
		$provider_Id = $this->input->get('prv');
		$Organization_Id = $this->input->get('org');
		$response = $this->rest_model->getAllSettings($provider_Id);
		//echo 'Hai';exit;
		$allSettings=json_decode($response);
		//print_r($allSettings);exit;
		$this->expert_model->updateAllSettings($provider_Id,$Organization_Id,$allSettings);
		echo json_decode($response);
	}
	Public function expertBookAppointment(){
		//print_r($this->input->get());exit;
		$inputArray = $this->input->post();
		$organizationId = $inputArray['OrgId'];
		$response = $this->rest_model->expertBookAppointment($inputArray,$organizationId);
	 	//print_r($organizationId);exit;
		echo json_encode($response);
	}
	Public function bookAppointment(){
		//print_r($this->input->post());exit;
		$inputArray = $this->input->post();
		if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null)
			$Contact_Id = $inputArray['contactId'];
		else
			$Contact_Id = $inputArray['contactId'];
		$Organization_Id = $inputArray['OrgId'];
		if($inputArray['EXPERT'] != '' && $inputArray['EXPERT'] != null){
			$contactDetails = $this->user_model->getSFContactDetails($Contact_Id,$Organization_Id);
		}else{
			$contactDetails = $this->user_model->getSFContactDetails($Contact_Id,$Organization_Id);
		}
		
		$response = $this->rest_model->bookAppointment($inputArray,$contactDetails,$Organization_Id);
	 	//print_r($response);exit;
		echo json_encode($response);
	}
	Public function rescheduleAppointment(){
		//print_r($this->input->post());exit;
		$inputArray = $this->input->post();
		$Contact_Id = $inputArray['contactId'];
		$Organization_Id = $inputArray['OrgId'];
		$contactDetails = $this->user_model->getSFContactDetails($Contact_Id,$Organization_Id);
		$response = $this->rest_model->rescheduleAppointment($inputArray,$contactDetails,$Organization_Id);
		//print_r($response);exit;
		echo json_encode($response);
	}
	public function contactSearch()
    {
        $term = $this->input->post('term', TRUE);
        $this->expert_model->autocomplete($term);
    }
    public function objectSearch()
    {
    	$term = $this->input->get('term', TRUE);
    	$object = $this->input->get('object', TRUE);
    	$organizationId = $this->input->get('orgId', TRUE);
    	$result = $this->objForm_model->objectSearch($term,$object,$organizationId);
    	$response = array();
    	for ($i = 0; $i < count($result); $i++) {
    		$response[] = array(
    				"id" => ($result[$i]->Salesforce_Id != NULL && $result[$i]->Salesforce_Id != '') ? $result[$i]->Salesforce_Id : $result[$i]->uId,
    				"text" => $result[$i]->Name 
    		);  
    	}
    	$data['items'] = $response;
    	$data['total_count'] = count($response);
    	echo json_encode($data);	
    }
	public function changeAppoinmentStatus()
	{
		$AppId = $this->input->get('appId');
		$Status = $this->input->get('status');
		$userDetails = modules::run('bksl/auth/get_user_details');
		$related_to = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		if($AppId != null && $AppId != ''){
			$sessionId = getSessionId($organizationId);
			$mySforceConnection = new SforcePartnerClient();
			$mySforceConnection->createConnection(PARTNERWSDL);
			$location = getLocation($organizationId);
  			$sessionId = getSessionId($organizationId);
			$mySforceConnection->setEndpoint($location);
			$mySforceConnection->setSessionHeader($sessionId);
				$fieldsToUpdate = array (
				  'BKSL2__Status__c' => $Status,
				  );
				  $sObject = new SObject();
				  $sObject->fields = $fieldsToUpdate;
				  $sObject->Id = $AppId;
  				  $sObject->type = 'BKSL2__Service_Request__c';
				  $mySforceConnection->update(array ($sObject));
        			$this->expert_model->updateAppointments($AppId,$organizationId,$Status);
				  $response =array(
				  	'Success' => TRUE
				  );
		}else{
			$response = array(
				  	'Success' => FALSE
				  );;
		}
		echo json_encode($response);
		
	}
	public function getAppointmentDetailsById()
	{
		$AppId = $this->input->get('appId');
		$userDetails = modules::run('bksl/auth/get_user_details');
		$related_to = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		$data['ExpertDetails'] = $this->expert_model->getExpertDetails($related_to,$organizationId);
		$expertSFId = $data['ExpertDetails']->Salesforce_Id;
		if($AppId != null && $AppId != ''){
			$AppDetails = $this->expert_model->getAppointmentDetailsById($expertSFId,$organizationId,$AppId);
			//print_r($AppDetails);exit;
			$ConDetails = $this->expert_model->getContactDetailsById($AppDetails->Contact_Id,$organizationId);
			$AppHistory = $this->expert_model->getAppointmentHistory($AppDetails->Contact_Id,$organizationId);
		
			$response = array(
				'serName' =>$AppDetails->Name,
				'startTime' => $AppDetails->Start_Date,
				'endTime' => $AppDetails->End_Date,
				'status' => $AppDetails->Status,
				'cStreet' => $ConDetails->MailingStreet,
				'cCity' => $ConDetails->MailingCity,
				'cState' => $ConDetails->MailingState,
				'cZipCode' => $ConDetails->MailingPostalCode,
				'cEmail' => $ConDetails->Email,
				'cPhone' => $ConDetails->Phone,
				'id' => $AppDetails->Id,
				'note' => $ConDetails->Critical_Notification,
				'serReqHistory' => $AppHistory
			);
		}else{
			$response = array();
		}
		echo json_encode($response);
		
	}
	public function getExpertAppointments()
	{
		$userDetails = modules::run('bksl/auth/get_user_details');
		$expertId = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		$data['ExpertDetails'] = $this->expert_model->getExpertDetails($related_to,$organizationId);
		$expertSFId = $data['ExpertDetails']->Salesforce_Id;
		$appointmentDetails= $this->expert_model->getAppoitmentDetails($expertSFId,$organizationId);
		$events = array();
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
		//print_r($events);exit;
		echo json_encode($events);
	}
	Public function refreshSession(){
		$AllOrgs = $this->user_model->getAllOrganizations();
		foreach($AllOrgs as $row){
			//if(getSessionStatus($row->Salesforce_Id)){
				$mySforceConnection = new SforcePartnerClient();
				$mySforceConnection->createConnection(PARTNERWSDL);
				$mySforceConnection->login($row->Integration_Username, $row->Integration_Password);				
				$data =array(
					'Session_Id' => $mySforceConnection->getSessionId(),
					'Location'	 => $mySforceConnection->getLocation(),
					'LastModifiedDateAndTime'	 => date('Y-m-d H:i:s')
				);
				$this->db->where('Id', $row->Id);
				$response = $this->db->update('organization', $data);
			//}
		}
	}
	function create_user()
	{
		$this->db->trans_begin();  
		$email_activation = true;
		$data['errors'] = array();
		$data = $this->input->post();
		try{
			if (!is_null($data = $this->tank_auth->create_user($data,$email_activation))) {									// success
	
				$data['site_name'] = $this->config->item('website_name', 'tank_auth');
	
				if ($email_activation) {									// send "activate" email
					$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;
	
					$this->_send_email('activate', $data['email'], $data);
	
					unset($data['password']); // Clear password (just for any case)
	
					//$this->tank_auth->_show_message($this->lang->line('auth_message_registration_completed_1'));
	
				} else {
					if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email
	
						$this->_send_email('welcome', $data['email'], $data);
					}
					unset($data['password']); // Clear password (just for any case)
	
					//$this->tank_auth->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/bksl/auth/login/', 'Login'));
				}
				$this->db->trans_commit();  
			} else {
    			$this->db->trans_rollback();
				$errors = $this->tank_auth->get_error_message();
				foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				$data['errorType'] =  'JSON';
			}
		}catch(exception $e){
    		$this->db->trans_rollback();
			$data['errors'] = $e->getMessage(); 
			$data['errorType'] =  'HTML';		
		}
		echo json_encode($data);exit;
	}
	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */