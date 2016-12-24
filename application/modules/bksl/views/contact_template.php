<?php
	$user['pagename'] = $page_name;
	$user['title'] = $title;
	$user['ContactDetails'] = $data['ContactDetails'];
	$user['userDetails'] = $data['userDetails'];
	$user['profileTabs'] = $data['profileTabs'];  
	//$user['ContactDetalsById'] = $data['ContactDetalsById'];
	//print_r($data);exit; 
   if($showHeader)
		$this->load->view('includes/contact_header',$user);
	$this->load->view($page_name,$data);
	if($showFooter)
		$this->load->view('includes/contact_footer',$user);
?> 