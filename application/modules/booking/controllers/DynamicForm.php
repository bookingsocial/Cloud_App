<?php

class dynamicForm extends MX_Controller  
{
	function __construct()
	{ 
		parent::__construct();
		$this->load->model('contact_model');
		$this->load->model('rest_model');
		$this->load->helper('date');
	}
	
	function dynuserform()
	{
		$Salesforce_Id =  $this->uri->segment(3); 
        $mode =  $this->uri->segment(4);
		
		modules::run('bksl/auth/is_logged_in');
		$userDetails = modules::run('bksl/auth/get_user_details');
		$contactId = $userDetails->related_to;
		$organizationId = $userDetails->Organization_Id;
		$data['ContactDetails'] = $this->contact_model->getContactDetails($contactId,$organizationId);
		$contactSFId = $data['ContactDetails']->Salesforce_Id;
		$data['OrgDetails'] = $this->contact_model->getOrganizationDetails($organizationId);
		$data['serviceDetails'] = $this->contact_model->getServiceDetails($contactId,$organizationId);
		$data['ContactDetalsById'] = $this->dynForm_model->getContactDetalsById($Salesforce_Id);
		
		$arr1 = str_split($Salesforce_Id, 3);
		$jsondata = file_get_contents(base_url().'meta-data/main/dynObject.json');
		$dynObjectData = json_decode($jsondata, true);
		$ObjectVal = $dynObjectData['object'];
		$idvals = strlen($Salesforce_Id);
		
		foreach($ObjectVal as $row)
			{
				if($arr1[0] == $row['id']){
					$selObject = $row['object'];
					$selFilter = $row['filter']; 
					if($Salesforce_Id == $row['id'] && $mode == ''){
						
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'dynamicForm/dynformContactList';
						$page['title'] = 'ContactDetails';
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('contact_template', $page);
						
						
					} else if($arr1[0] == $row['id'] && $mode == 'c'){
						$jsondataFeilds = file_get_contents(base_url().'meta-data/object/'.$selObject);
						//$dynFeildsData = json_decode($jsondataFeilds, true);
						$data['jsondataFeild'] = $jsondataFeilds;
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selFilter);
						//$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'dynamicForm/dynuserform';
						$page['title'] = 'ContactDetails';
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('contact_template', $page);
						
					} else if($mode == 'e') {
						
						$jsondataFeilds = file_get_contents(base_url().'meta-data/object/'.$selObject);
						//$dynFeildsData = json_decode($jsondataFeilds, true);
						$data['jsondataFeild'] = $jsondataFeilds;
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selFilter);
						//$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						 
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'dynamicForm/dynuserform';
						$page['title'] = 'ContactDetails';
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('contact_template', $page);
				
					}else if($idvals == 18) {
						
						$jsondataFeilds = file_get_contents(base_url().'meta-data/object/'.$selObject);
						//$dynFeildsData = json_decode($jsondataFeilds, true);
						$data['jsondataFeild'] = $jsondataFeilds;
						$jsondataFilter = file_get_contents(base_url().'meta-data/view/'.$selFilter);
						//$dynFilterData = json_decode($jsondataFilter, true);
						$data['jsondataFilters'] = $jsondataFilter;
						 
						$page = array();
						$page['showHeader'] = TRUE;
						$page['showFooter'] = TRUE;
						$page['page_name'] = 'dynamicForm/dynuserform';
						$page['title'] = 'ContactDetails';
						$page['data'] = $data;
						//print_r($data['OrgDetails']);exit;
						$this->load->view('contact_template', $page);
				
					}
				}
			}
	}
	
} 
