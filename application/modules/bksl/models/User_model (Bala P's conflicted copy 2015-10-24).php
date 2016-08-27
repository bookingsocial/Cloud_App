<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/SforcePartnerClient.php');
require_once (APPPATH . 'libraries/SforceHeaderOptions.php');
class User_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
	}
	
	function validate_Id($Salesforce_Id,$organizationId){
		
		$output[0] = substr($Salesforce_Id, 0, 2);
		
		if(c01 == $output[0]){
			
			$this->db->select('Id,Organization_Id,Name,Salesforce_Id,Do_Not_Distrub,Email,Phone,FirstName,LastName,Salutation,Mobile,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,Critical_Notification,Description	');
			$this->db->from('contacts');
			$this->db->where('Id', $Salesforce_Id);
			$this->db->where('Organization_Id', $organizationId);
			$this->db->limit(1);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			
		}else if(c02 == $output[0]){
			
		}else{
			
		}
		
		return $query->row();
	}
	
    function validate_userlogin($inputs){
		$this->db->select('Username, Password,Id, Email,Type,Expert_Id,Organization_Id,Contact_Id');
		$this->db->from('users');
		$this->db->where('Username', $inputs['uname']);
		$this->db->where('Password', $inputs['pwd']);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}
	function getContactDetails($ContactId,$organizationId){	
		$this->db->select('Id,Organization_Id,Name,Salesforce_Id,Do_Not_Distrub,Email,Phone,FirstName,LastName,Salutation,Mobile,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,Critical_Notification,Description	');
		$this->db->from('contacts');
		$this->db->where('Id', $ContactId);
		$this->db->where('Organization_Id', $organizationId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	
	function getSFContactDetails($ContactId,$organizationId){
		$this->db->select('Id,Organization_Id,Name,Salesforce_Id,Do_Not_Distrub,Email,Phone,FirstName,LastName,Salutation,Mobile,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,Critical_Notification,Description	');
		$this->db->from('contacts');
		$this->db->where('Salesforce_Id', $ContactId);
		$this->db->where('Organization_Id', $organizationId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getAppoitmentDetails($ContactId,$organizationId){
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Contact_Id', $ContactId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	
	
	function getallContactsByOrg($organizationId){
		$this->db->select('*');
		$this->db->from('contacts');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->LIMIT(20,0);
		$query = $this->db->get();
		$num_records = $query->num_rows();
		//echo $this->db->last_query();exit;
		return $query->result(); 
	}
			
	function getContactDetailsById($Salesforce_Id){	 
		$this->db->select('Id,Organization_Id,Name,Salesforce_Id,Do_Not_Distrub,Email,Phone,FirstName,LastName,Salutation,Mobile,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,Critical_Notification,Description');
		$this->db->from('contacts');
		$this->db->where('Salesforce_Id', $Salesforce_Id);
		//$this->db->where('Organization_Id', $organizationId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function cancelAppointment($appDetails){
		$records[0] = new stdclass();
		$records[0]->Id = $appDetails->Salesforce_Id;
		$records[0]->BKSL2__Status__c = 'Cancelled';
		$records[0]->type = 'BKSL2__Service_Request__c';
		ini_set("soap.wsdl_cache_enabled", "0");

		$sfdc = new SforcePartnerClient();

		$SoapClient = $sfdc->createConnection(PARTNERWSDL); 
		$location = $this->session->userdata('location');
		$sessionId = $this->session->userdata('sessionId');
		$loginResult = false; 
		$sfdc->setEndpoint($location);
		$sfdc->setSessionHeader($sessionId);
						
		$response = $sfdc->update($records);
		$updatedId = '';
		foreach ($response as $result) {
		    $updatedId = $result->id;
		}
		$data =array(
  				'Status' => 'Cancelled',
				//'Id' => $inputs['Id'],
  		);
		$this->db->where('Id', $appDetails->Id);
		$response = $this->db->update('appointments', $data);
		return $response;
		//return  $updatedId;
	}
	function getOrganizationDetails($organizationId){
		$this->db->select('*');
		$this->db->from('organization');
		$this->db->where('Salesforce_Id', $organizationId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getAllOrganizations(){
		$this->db->select('*');
		$this->db->from('organization');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	function getServiceRequestDetails($appId,$organizationId){
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where(array('Id' => $appId,'Organization_Id' => $organizationId));
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getExpertDetails($ContactId,$organizationId){	
		$this->db->select('*');
		$this->db->from('experts');
		$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	function getUserDetails($contactId,$organizationId){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Contact_Id', $contactId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getRelatedAppointmentDetails($ServiceId,$contactId,$organizationId,$appId){
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Contact_Id', $contactId);
		$this->db->where('Service_Id', $ServiceId);
		$this->db->where_not_in('Id', $appId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	function getAppointmentDetails($appId){
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where('Id', $appId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getServiceDetails($ContactId,$organizationId){	
		$this->db->select('*');
		$this->db->from('services');
		$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
    function getExpertByService($ServiceId,$organizationId){	
		$this->db->select('*');
		$this->db->from('expert_service');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Service_Id', $ServiceId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	function updateContact($inputs){
		$data =array(
  				'Name' => $inputs['LastName'].' '.$inputs['FirstName'],
				'Id' => $inputs['Id'],
  				'Email' => $inputs['Email'],
  				'LastName' => $inputs['LastName'],
  				'FirstName' => $inputs['FirstName'],
  				'MailingCountry' => $inputs['MailingCountry'],
  				'MailingPostalCode' => $inputs['MailingPostalCode'],
  				'MailingCity' => $inputs['MailingCity'],
				'MailingState' => $inputs['MailingState'],
  				'MailingStreet' => $inputs['MailingStreet'],
  		);
		$this->db->where('Id', $inputs['Id']);
		$response = $this->db->update('contacts', $data);
		return $response;
	}
	function updateContactSettings($inputs){
		$data =array(
				'Id' => $inputs['Id'],
  				'Do_Not_Distrub' => $inputs['Do_Not_Distrub'],
  		);
		$this->db->where('Id', $inputs['Id']);
		$response = $this->db->update('contacts', $data);
		return $response;
	}
	function updateUser($inputs){
		$data =array(
				'Username' => $inputs['Username'],
				'Id' => $inputs['Id'],
				'Password' => $inputs['Password']
		);
		$this->db->where('Id', $inputs['Id']);
		$response = $this->db->update('users', $data);
		return $response;
	}
	 
	function updateRecord($post, $sfIdVal){
	
		$this->db->where('Salesforce_Id', $sfIdVal);
		$this->db->set($post);
		$response = $this->db->update('contacts');
	   return $response;
	}
	
	function createRecord($post, $organizationId){
		
		$post = array(
			'organization_Id' => $organizationId,
		);
		
		//print_r($post); exit;
		
		$response = $this->db->insert('contacts', $post);
	   return $response;
	}
}
?>