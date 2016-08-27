<?php
	$user['pagename'] = $page_name;
   if($showHeader)
		$this->load->view('includes/contact_header',$user);
	$this->load->view($page_name,$data);
	if($showFooter)
		$this->load->view('includes/contact_footer',$user);
?> 