<?php
	$user['pagename'] = $page_name;
	$user['title'] = $title;
	$user['ExpertDetails'] = $data['ExpertDetails']; 
 	$user['userDetails'] = $data['userDetails'];  
	$user['profileTabs'] = $data['profileTabs']; 
	//print_r($data);exit; 
    if($showHeader){
	   $this->load->view('includes/expert_header',$user);
	   $this->load->view($page_name,$data);
    }
		
	if($showFooter)
		$this->load->view('includes/expert_footer',$user);

?>