<?php
	$user['pagename'] = $page_name;
	//print_r($data);exit; 
   if($showHeader)
		$this->load->view('user_header',$user);
	$this->load->view($page_name,$data);
	if($showFooter)
		$this->load->view('user_footer');

?>