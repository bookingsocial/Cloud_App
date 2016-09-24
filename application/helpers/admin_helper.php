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
if ( ! function_exists('getAppointmentReport'))
{
    function getAppointmentReport($organizationId)
    {
		$response = array();
		$curMonth= date('m');
        $curYear= date('Y');
        $curDate= date('d');
		$prevMonth = $curMonth - 1;
		$privateStatus = ['Private','Waiting List'];
		if($prevMonth == 0)
			$prevMonth = 12;
		$listMonthNum =[];
		$listMonthNames=array();
		for($i= -6;$i <= 0;$i++){
			$d=strtotime("+$i Months");
			$tmpDate = date("Y-m-d", $d);
			$listMonthNum[] = date("m", strtotime($tmpDate))+' '+date("Y", strtotime($tmpDate));
			$listMonthNames[]=date("M Y", strtotime($tmpDate));
		}
		$response['monthNames'] =$listMonthNames;
		$ci=& get_instance();
		$ci->load->database(); 
		$query= $ci->db->query('Select MONTH(DATE(Start_Date))month,YEAR(DATE(Start_Date))year,COUNT(Id)total,Status status  FROM appointments GROUP BY MONTH(DATE(Start_Date)),YEAR(DATE(Start_Date)),Status ');
		$CompletedCount = [];
        $NoShowCount = [];
        $cancelCount = [];
        $InProgressCount = [];
		$queryResult = $query->result();
		foreach ($listMonthNum as $monthYear)
	   	{
			$completed=0;
			$noshow=0;
			$canceled=0;
			$pending=0; 
			foreach($queryResult as $result){
				$resultMnthYear = $result->month.''.$result->year;
				if($resultMnthYear == $monthYear){
				if($result->status == 'Completed'){
					$completed = $result->total;
				}
				else if($result->status  == 'No Show'){
					$noshow = $result->total;
				}
				else if($result->status  == 'Canceled' || $result->status  == 'Cancelled'){
					$canceled = $result->total;
				}else{
					$pending = $pending + $result->total;
				}
				}
			}
			$CompletedCount[]=$completed;
			$NoShowCount[]=$noshow;
			$cancelCount[]=$canceled;
			$InProgressCount[]=$pending; 
	   }
		$mapStatusLine = [];
		$mapStatusLine['Completed'] = $CompletedCount;
        $mapStatusLine['No Show'] = $NoShowCount;
        $mapStatusLine['Canceled'] = $cancelCount;
        $mapStatusLine['In Progress'] = $InProgressCount;
        $response['statusCounts'] = $mapStatusLine;
		return json_encode($response);
    }   
}