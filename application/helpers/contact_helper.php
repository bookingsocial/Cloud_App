<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getTotalAppointment'))
{
    function getTotalAppointment($ContactId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Contact_Id' => $ContactId,'Organization_Id' => $organizationId));
			return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getCompletedAppointment'))
{
    function getCompletedAppointment($ContactId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Contact_Id' => $ContactId,'Organization_Id' => $organizationId));
			$ci->db->where('Status', 'Completed');
			return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getUpcomingAppointment'))
{
    function getUpcomingAppointment($ContactId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Contact_Id' => $ContactId,'Organization_Id' => $organizationId));
 			$ci->db->where("Start_Date >", 'CURDATE()', FALSE);			
		    return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getCanceledAppointment'))
{
    function getCanceledAppointment($ContactId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Contact_Id' => $ContactId,'Organization_Id' => $organizationId));
			$ci->db->where('Status', 'Canceled');
			$ci->db->where('Status', 'Cancelled');
			return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getSessionId'))
{
	function getSessionId($organizationId)
	{
		$ci=& get_instance();
		$ci->db->select('Session_Id');
		$ci->db->from('organization');
		$ci->db->where('Salesforce_Id', $organizationId);
		$ci->db->limit(1);
		$query = $ci->db->get();
		$Location = $query->row()->Session_Id;
		return $Location;
	}
}
if ( ! function_exists('getLocation'))
{
	function getLocation($organizationId)
	{
		$ci=& get_instance();
		$ci->db->select('Location');
		$ci->db->from('organization');
		$ci->db->where('Salesforce_Id', $organizationId);
		$ci->db->limit(1);
		$query = $ci->db->get();
		$Location = $query->row()->Location;
		return $Location;
	}
}
if ( ! function_exists('getContactDetailsById'))
{
	function getContactDetailsById($organizationId,$ContactId)
	{
		$ci=& get_instance();
		$ci->db->select('*');
		$ci->db->from('contacts');
		$ci->db->where('Salesforce_Id', $ContactId);
		$ci->db->where('Organization_Id', $organizationId);
		$ci->db->limit(1);
		$query = $ci->db->get();
		$ContactDetails = $query->row();
		return $ContactDetails;
	}
}
if ( ! function_exists('getSessionStatus'))
{
	function getSessionStatus($organizationId)
	{
		$ci=& get_instance();
		$status = FALSE;
		$ci->db->select('LastModifiedDateAndTime');
		$ci->db->from('organization');
		$ci->db->where('Salesforce_Id', $organizationId);
		$ci->db->limit(1);
		$query = $ci->db->get();
		//echo $this->db->last_query();exit;
		$lastModifiedDate = new DateTime($query->row()->LastModifiedDateAndTime);
		//print_r($lastModifiedDate);
		//print_r($query->row()->LastModifiedDateAndTime);exit;
		
		$currentDate = new DateTime(date('Y-m-d H:i:s'));
		//echo $currentDate;exit;
		$diff =date_diff($currentDate,$lastModifiedDate);
		$hours = $diff->h;
		if($hours < 2){
			$status = TRUE;
		}
		//echo $status;exit;
		return $status;
	}
}
if ( ! function_exists('getContactDetailsById'))
{
	function getContactDetailsById($organizationId,$ContactId)
	{
		$ci=& get_instance();
		$ci->db->select('*');
		$ci->db->from('contacts');
		$ci->db->where('Salesforce_Id', $ContactId);
		$ci->db->where('Organization_Id', $organizationId);
		$ci->db->limit(1);
		$query = $ci->db->get();
		$ContactDetails = $query->row();
		return $ContactDetails;
	}
}