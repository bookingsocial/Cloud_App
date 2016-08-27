<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sfdc extends CI_Controller  {

	 function __construct() {
        // then execute the parent constructor anyway
        parent::__construct();
		$this->ci =& get_instance();
		$this->load->model('user_model');
    }
	public function refresh(){
		$AllOrgs = $this->user_model->getAllOrganizations();
		foreach($AllOrgs as $row){
			//if(getSessionStatus($row->Salesforce_Id)){
				$mySforceConnection = new SforcePartnerClient();
				$mySforceConnection->createConnection(PARTNERWSDL);
				$mySforceConnection->login($row->Integration_Username, $row->Integration_Password);				
				$data =array(
					'Session_Id' => $mySforceConnection->getSessionId(),
					'Location'	 => $mySforceConnection->getLocation(),
					'LastModifiedDateAndTime'	 => date('Y-m-d H:i:s')
				);
				$this->db->where('Id', $row->Id);
				$response = $this->db->update('organization', $data);
			//}
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */