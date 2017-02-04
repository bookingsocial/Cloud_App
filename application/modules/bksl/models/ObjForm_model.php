<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/SforcePartnerClient.php');
require_once (APPPATH . 'libraries/SforceHeaderOptions.php');

class objForm_model extends CI_Model {

  
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
	}
     	 
	function getDetailsByRecordId($uId,$selObjectName){	 
		$this->db->select('*');
		$this->db->from($selObjectName);
		$this->db->where('uId', $uId); 
		//$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		return $query->row();
	}
	 function getDetailsBySalesforceId($record_Id,$selObjectName,$organizationId){	 
		$this->db->select('*');
		$this->db->from($selObjectName);
		$where = '(Salesforce_Id="'. $record_Id.'" or uId = "'. $record_Id.'")';
        $this->db->where($where);
		$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		return $query->row();
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
		
	function getAppointmentDtlsById($contactId){	 
		$this->db->select('*');
		$this->db->from('appointments');
		$this->db->where('Contact_Id', $contactId);
		//$this->db->where('Organization_Id', $organizationId);
		$query = $this->db->get();
		return $query->row();
	}
	function objectSearch($term,$object,$organizationId)
	{
		$query = $this->db->query("SELECT Id,Salesforce_Id,Name,uId FROM ".$object." WHERE Id LIKE `%".$term."%` OR Salesforce_Id LIKE `%".$term."%` OR Name LIKE `%".$term."%` AND Organization_Id =`".$organizationId."`");
		return $query->result();
	}
	function getRecordsById($organizationId,$selObjectName,$provider){
		$this->db->select('*'); 
		$this->db->from($selObjectName);
		$this->db->where('Organization_Id', $organizationId); 
		$this->db->where('Provider',$provider);
		$this->db->LIMIT(100,0);
		$query = $this->db->get();
		$num_records = $query->num_rows();
		return $query->result();
	}
	function getRelatedListObjects($organizationId,$relatedObjects,$provider){
		$result= array();
		foreach ($relatedObjects as $key => $value){
			$selectedFields ='Id,Salesforce_Id,uId,Name';
			$this->db->select($selectedFields);
			$this->db->from($key);
			$this->db->where('Organization_Id', $organizationId);
			$this->db->where('Provider',$provider);
			$query = $this->db->get();
			$num_records = $query->num_rows();
			$result[$key] = $query->result();
		}
		return $result;
	}
	function updateRecord($post, $uId, $selObjectName,$OrgDetails,$isSFMapping){
	   // echo $selObjectName; exit; 
		$this->db->where('uId', $uId);    
		$this->db->set($post);
		$result = $this->db->update($selObjectName);
		if($isSFMapping && $result){
			//print_r($result);exit;
			$this->upsertInSalesforce($OrgDetails,$uId,$selObjectName);
		}
	    return $result; 
	}      
	 
	function deleteRecord($OrgDetails,$uId, $selObjectName){
		//echo $selObjectName; exit;  
		$this->db->select('*');
		$this->db->from($selObjectName);
		$this->db->where('uId', $uId);
		$query = $this->db->get();
		$recordDetails = $query->row();
		$SforceConnection = new SforcePartnerClient();
		$SforceConnection->createConnection(PARTNERWSDL);
		$uname =  $OrgDetails->Integration_Username;
		$pwd = $OrgDetails->Integration_Password.$OrgDetails->Integration_Token;
		$SforceConnection->login($uname, $pwd);	
		$deleteResult = $SforceConnection->delete(array($recordDetails->Salesforce_Id));
		$this->db->where('uId', $uId);
		$response = $this->db->delete($selObjectName);
		return $response;
	}   
	 
	function createRecord($post,$prefix,$organizationId,$selObjectName,$OrgDetails,$isSFMapping){  
	
		$response = $this->db->insert($selObjectName, $post);
		$insert_id = $this->db->insert_id(); 
		$uId  = $prefix.$this->base62->convert($insert_id);
		//echo $selObjectName; exit; 
		$data=array('uId' => $uId, 'Organization_Id' => $organizationId);
		$this->db->where('Id', $insert_id);
		$result = $this->db->update($selObjectName, $data);
		//print_r($result);exit;
		if($isSFMapping && $result){
			//print_r($result);exit;
			$this->upsertInSalesforce($OrgDetails,$uId,$selObjectName);
		}
		return $uId;  
	}
	function upsertInSalesforce($OrgDetails,$record_id,$selObjectName){
		$recordDetails = $this->getDetailsByRecordId($record_id,$selObjectName);
		$mappingData = file_get_contents(base_url().'meta-data/mapping/'.$selObjectName.'.json');
		//print_r($mappingData);exit;
		if($mappingData != ''){
			$mappingArr = json_decode($mappingData, true);
			//print_r($mappingData);
			//print_r($mappingArr);
			try{
				$SforceConnection = new SforcePartnerClient();
				$SforceConnection->createConnection(PARTNERWSDL);
				$uname =  $OrgDetails->Integration_Username;
				$pwd = $OrgDetails->Integration_Password.$OrgDetails->Integration_Token;
				$SforceConnection->login($uname, $pwd);	
				$object = new SObject();
				$object->type = $mappingArr['sfApiName'];
				$fields = array();
				// Iterate through Properties and Values for table values
				foreach($recordDetails as $property => $value)  { 
					if(array_key_exists ( $property , $mappingArr['sfFields']) && $value != null){
						$fields[$mappingArr['sfFields'][$property]['apiName']] = $value;
					}
				}
				
				if(array_key_exists ( 'sfDefault' , $mappingArr)){
					// Iterate through Properties and Values for default values
					foreach($mappingArr['sfDefault'] as $property => $value)  { 
							$fields[$property] = $value;
					}
				}
				$object->fields = $fields;
				//print_r($mappingArr);
				//print_r($object);exit;
				if($recordDetails->Salesforce_Id == ''){
					$response = $SforceConnection->create(array($object));
					foreach ($response as $createResult) {
						$this->db->where('uId', $record_id);    
						$data=array('Salesforce_Id' => $createResult->id);
						$this->db->set($data);
						$result = $this->db->update($selObjectName);
					}
				}else{
					$response = $SforceConnection->update(array ($object));
				}
				//print_r($response);exit;
			} catch (Exception $e) {
				echo $SforceConnection->getLastRequest();
				echo $e->faultstring;
				exit;
			}

		}
		return null;
	}
	
}
?>
