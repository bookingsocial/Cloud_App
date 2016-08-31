<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
	}
	function getAllUsers($organizationId,$limit, $start,$userId)
	{
			$sql = 'select * from users where organization_Id ="'.$organizationId.'" and Id !="'.$userId.'" limit ' . $start . ', ' . $limit;
			$query = $this->db->query($sql);
		return $query->result();
	}
	function getAllExpertDetails($organizationId){
		$this->db->select('*');
		$this->db->from('experts');
		$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		return $query->result(); 
	}
	function getAllServiceDetails($organizationId){
		$this->db->select('*');
		$this->db->from('services');
		$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		return $query->result();
	}
	function getUserDetailsById($userId,$organizationId)
	{
		$sql = 'select * from users where organization_Id ="'.$organizationId.'" and Id !="'.$userId.'" limit 1';
		$query = $this->db->query($sql);
		$result['user'] = $query->row();
		if($result['user']->related_to && $result['user']->user_type == 'EXPERT'){
			$this->db->select('*');
			$this->db->from('experts');
			$this->db->where('Id', $result['user']->related_to);
			$this->db->where('Organization_Id', $organizationId);
			$this->db->limit(1);
			$query = $this->db->get();
			$result['expert'] = $query->row();
		}
		else if($result['user']->related_to && $result['user']->user_type == 'CONTACT'){
			$this->db->select('*');
			$this->db->from('contacts');
			$this->db->where('Id', $result['user']->related_to);
			$this->db->where('Organization_Id', $organizationId);
			$this->db->limit(1);
			$query = $this->db->get();
			$result['contact'] = $query->row();
		}
		return $result;
	}
}
?>