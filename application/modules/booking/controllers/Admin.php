<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once(APPPATH . 'libraries/SforcePartnerClient.php');
class Admin extends MX_Controller  {
	 function __construct() {
        // then execute the parent constructor anyway
        parent::__construct();
		$this->load->model('expert_model');
    }
	public function index()
	{
		redirect('main/home');
		
	}
	public function contactSearch()
    {
        $term = $this->input->post('term', TRUE);
        $this->expert_model->autocomplete($term);
    }

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
}