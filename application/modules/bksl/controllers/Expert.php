<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once(APPPATH . 'libraries/SforcePartnerClient.php');
class Expert extends CI_Controller  {
	 function __construct() {
        // then execute the parent constructor anyway
        parent::__construct();
		$this->ci =& get_instance();
		  $this->load->helper('url');
		  $this->load->library('session');
		  $this->load->helper('date');
		  $this->load->model('rest_model');
		  $this->load->model('user_model');
		  $this->load->model('expert_model');
    }
	public function index()
	{
		redirect('main/home');
		
	}
	public function contactSearch()
    {
        $term = $this->input->post('term', TRUE);
        $this->expert_model->autocomplete($term);
    }
	public function changeAppoinmentStatus()
	{
		$AppId = $this->input->get('appId');
		$Status = $this->input->get('status');
		if($AppId != null && $AppId != ''){
		  	$organizationId = $this->session->userdata('OrganizationId');
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
		$organizationId = $this->session->userdata('OrganizationId');
		$expertSFId = $this->session->userdata('expertSFId');
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
		$expertId = $this->session->userdata('ExpertId');
		$organizationId = $this->session->userdata('OrganizationId');
		$expertSFId = $this->session->userdata('expertSFId');
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
		//print_r($events);exit;
		echo json_encode($events);
	}
	Public function create(){
		if($this->session->userdata('LoginStatus')){
			$expertId = $this->session->userdata('ExpertId');
			$expertSFId = $this->session->userdata('expertSFId');
			$organizationId = $this->session->userdata('OrganizationId');
			$expertSFId = $this->session->userdata('expertSFId');
			$data['ExpertDetails'] = $this->expert_model->getExpertDetails($expertId,$organizationId);
			$data['OrgDetails'] = $this->user_model->getOrganizationDetails($organizationId);
			if($this->input->get('contactId') != NULL && $this->input->get('contactId') != ''){
				$data['contactDetails'] = Array();
				$data['showContact'] = 'hidden';
				$data['showService'] = '';
				$data['serviceDetails'] = $this->expert_model->getServiceDetails($expertSFId,$organizationId);
			}else{
				$data['contactDetails'] = $this->expert_model->getContactDetails($organizationId);
				$data['serviceDetails'] = array();
				$data['showContact'] = '';
				$data['showService'] = 'hidden';
			}
			//print_r($data);exit;
			$page = array(
				'page_name' => 'appointments',
					'title' => 'Create Appointment',
				'data' =>$data
			);
			$this->load->view('expert_template',$page);
		}else{
			redirect('main/login');	
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */