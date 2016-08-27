<?php
	$user['pagename'] = $page_name;
	$user['title'] = $title;
  $this->load->view($page_name,$data);  
	$user['allsearchResult'] = $data['allsearchResult'];  
  $user['profileTabs'] = $data['profileTabs'];  
  $user['userDetails'] = $data['userDetails'];   
 
 
  if($data['jsonDataListView'] != null){
   	 $user['jsonDataListView'] = $data['jsonDataListView'];
  } 

  if($showHeader){ 
      $this->load->view('includes/expert_header',$user);
      $this->load->view($page_name,$data);
  }

	//if($showFooter)
		  //$this->load->view('includes/expert_footer',$footer);

?>