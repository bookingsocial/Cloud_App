<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getExpertTotalAppointment'))
{
    function getExpertTotalAppointment($ExpertId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Expert_Id' => $ExpertId,'Organization_Id' => $organizationId));
			return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getExpertCompletedAppointment'))
{
    function getExpertCompletedAppointment($ExpertId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Expert_Id' => $ExpertId,'Organization_Id' => $organizationId));
			$ci->db->where('Status', 'Completed');
			return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getExpertUpcomingAppointment'))
{
    function getExpertUpcomingAppointment($ExpertId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Expert_Id' => $ExpertId,'Organization_Id' => $organizationId));
 			$ci->db->where("Start_Date >", 'CURDATE()', FALSE);			
		    return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getExpertCanceledAppointment'))
{
    function getExpertCanceledAppointment($ExpertId,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 

			$ci->db->where(array('Expert_Id' => $ExpertId,'Organization_Id' => $organizationId));
			$ci->db->where('Status', 'Canceled');
			$ci->db->where('Status', 'Cancelled');
			return $ci->db->count_all_results('appointments');
    }   
}
if ( ! function_exists('getSettingValueByKey'))
{
    function getSettingValueByKey($providerId,$key,$organizationId)
    {
			$ci=& get_instance();
			$ci->load->database(); 
			$ci->db->select('*');
			$ci->db->where(array('Provider_Id' => $providerId,'Organization_Id' => $organizationId,'Name' => $key));
			$ci->db->limit(1);
		    if($ci->db->count_all_results('settings') == 1){
				$ci->db->select('*');
				$ci->db->from('settings');
				$ci->db->where(array('Provider_Id' => $providerId,'Organization_Id' => $organizationId,'Name' => $key));
				$query = $ci->db->get();
				return $query->row();
			}else{
				$ci->load->database(); 
				$ci->db->select('*');
				$ci->db->from('settings');
				$ci->db->where(array('Provider_Id' => null,'Organization_Id' => null,'Name' => $key));
				$ci->db->limit(1);
				$query = $ci->db->get();
				return $query->row();
			}
    }   
}