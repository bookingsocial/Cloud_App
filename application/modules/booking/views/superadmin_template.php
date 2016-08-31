<?php
	$user['pagename'] = $page_name;
	$user['title'] = $title;
	$user['userDetails'] = $data['userDetails'];
	//$user['ExpertDetails'] = $data['ExpertDetails'];
	//$user['serviceDetails'] = $data['serviceDetails'];
	$user['profileTabs'] = $data['profileTabs'];
	//$user['contactDetails'] = $data['contactDetails'];   
	
   if($showHeader)
	   $this->load->view('includes/superadmin_header',$user);

	 $this->load->view($page_name,$data);

	if($showFooter)
		$this->load->view('includes/superadmin_footer');

?>