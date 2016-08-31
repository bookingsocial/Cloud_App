<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/SforcePartnerClient.php');
require_once (APPPATH . 'libraries/SforceHeaderOptions.php');

class dynForm_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
	}
	function getContactDetalsById($Salesforce_Id){	
		$this->db->select('Id,Organization_Id,Name,Salesforce_Id,Do_Not_Distrub,Email,Phone,FirstName,LastName,Salutation,Mobile,MailingCity,MailingCountry,MailingPostalCode,MailingState,MailingStreet,Critical_Notification,Description	');
		$this->db->from('contacts');
		$this->db->where('Salesforce_Id', $Salesforce_Id);
		//$this->db->where('Organization_Id', $organizationId);
		$this->db->limit(1);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	
}
?>