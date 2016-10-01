<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getAllAppointments'))
{
    function getAllAppointments($organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Organization_Id' => $organizationId));
			return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getAllUsers'))
{
    function getAllUsers($organizationId,$current_userId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Organization_Id' => $organizationId,'Id !=' => $current_userId));			
			return $ci->db->count_all_results('users');
    }   
}
if ( ! function_exists('getAllServices'))
{
    function getAllServices($organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Organization_Id' => $organizationId));
			return $ci->db->count_all_results('services');
    }   
}
if ( ! function_exists('getAllExperts'))
{
    function getAllExperts($organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Organization_Id' => $organizationId));
			return $ci->db->count_all_results('experts');
    }   
}
if ( ! function_exists('getAllSettings'))
{
    function getAllSettings($organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 
			$ci->db->from('settings');
			$ci->db->where(array('Organization_Id' => $organizationId));
			$query = $ci->db->get();
			$response = array();
			foreach ($query->result() as $result) {
				$response[$result->Name] = $result;
			}
			return $response;
    }   
}