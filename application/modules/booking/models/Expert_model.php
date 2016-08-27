<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Expert_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
	} 
	function getExpertDetails($ExpertId,$organizationId){	
		$this->db->select('*');
		$this->db->from('experts');
		$this->db->where('Id', $ExpertId);
		$this->db->where('Organization_Id', $organizationId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getExpertDetailsByOrg($organizationId){
		$this->db->select('*');
		$this->db->from('experts');
		$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	function getAppointmentDetailsById($ExpertId,$organizationId,$AppId){
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Salesforce_Id', $AppId);
		$this->db->where('Expert_Id', $ExpertId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getAppointmentHistory($ContactId,$organizationId){
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Contact_Id', $ContactId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	function getContactDetailsById($ContactId,$organizationId){
		$this->db->select('*');
		$this->db->from('contacts');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Salesforce_Id', $ContactId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	function getAppoitmentDetails($ExpertId,$organizationId){
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where('Organization_Id', $organizationId);
		$this->db->where('Expert_Id', $ExpertId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
	public function autocomplete($term)
    {
        $query = $this->db->query("SELECT * FROM contacts
            WHERE Email LIKE '%".$term."%' OR FirstName LIKE '%".$term."%' OR LastName LIKE '%".$term."%'");
        echo json_encode($query->result_array());
    }
	function updateAllSettings($provider_Id,$Organization_Id,$response){
		//print_r($provider_Id);exit;
		$Allsettings = array();
		if(is_array($response)) {
			foreach ($response as $settings){
				$data= array(
				 'Key' => $settings->BKSL2__Key__c,
			     'Provider' => $provider_Id,
				 'Active' => $settings->BKSL2__Active__c,
				 'Name' => $settings->Name,
				 'Organization_Id' => $Organization_Id
				);
				if(property_exists($settings,'BKSL2__Extended_Value__c'))
					$data['Value4'] = json_encode($settings->BKSL2__Extended_Value__c);
				if(property_exists($settings,'BKSL2__Value__c'))
					$data['Value'] = $settings->BKSL2__Value__c;
				array_push($Allsettings,$data);
				$this->db->insert('settings', $data);
			}
		} else {
			echo 'data is not an array.';
		}
		//print_r($Allsettings);exit;
		
	}
	function updateAppointments($AppId,$organizationId,$Status){
		$data =array(
  				'Status' => $Status,
  		);
		$this->db->where('Salesforce_Id', $AppId);
		$this->db->where('Organization_Id', $organizationId);
		$response = $this->db->update('appointments', $data);
		//echo $this->db->last_query();exit;
		return $response;
	}
	function getServiceDetails($ExpertId,$organizationId){
		$this->db->select('services.Salesforce_Id,services.Name');
		$this->db->from('services'); // from Table1
		$this->db->join('expert_service','expert_service.Service_Id = services.Salesforce_Id','INNER'); // Join table1 with table2 based on the foreign key
		$this->db->where('expert_service.Expert_Id',$ExpertId); // Set Filter
		$this->db->where('services.Organization_Id', $organizationId);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	function getContactDetails($organizationId){
		$this->db->select('Id,Organization_Id,Name,Salesforce_Id,Email,Phone,FirstName,LastName,Salutation,Mobile,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,Critical_Notification,Description	');
		$this->db->from('contacts');
		$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->result();
	}
}
?>