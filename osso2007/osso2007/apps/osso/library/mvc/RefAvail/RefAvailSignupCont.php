<?php
class RefAvailSignupCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->refAvailSignupData)) $data = $session->refAvailSignupData;
        else {
            $data = new SessionData();
            $session->refAvailSignupData = $data;   
        }
        
        $view = new RefAvailSignupView();
        
        $response->setBody($view->process(clone $data));
        
        return;
	}
	protected $post;
	protected $repo;
	
	protected function getInt($id,$name)
	{
		return (int)$this->post[$name][$id];
		

	}
	protected function getTxt($id,$name)
	{
		$value = strip_tags($this->post[$name][$id]);
		
		return $value;
	}
	public function processOne($id)
	{
		$personId = $this->getInt($id,'ref_avail_person_id');
		$groupId  = $this->getInt($id,'ref_avail_group_id');
		
		$item = $this->repo->load($groupId,$personId);
		
		$item->divCR = $this->getInt($id,'ref_avail_div_cr');
		$item->divAR = $this->getInt($id,'ref_avail_div_ar');
		$item->day1  = $this->getInt($id,'ref_avail_day1');
		$item->day2  = $this->getInt($id,'ref_avail_day2');
		$item->day3  = $this->getInt($id,'ref_avail_day3');
		$item->day4  = $this->getInt($id,'ref_avail_day4');
		$item->day5  = $this->getInt($id,'ref_avail_day5');
		$item->day6  = $this->getInt($id,'ref_avail_day6');
		$item->team1 = $this->getInt($id,'ref_avail_team1');
		$item->team2 = $this->getInt($id,'ref_avail_team2');
		$item->team3 = $this->getInt($id,'ref_avail_team3');
												
		$item->phoneHome = $this->getTxt($id,'ref_avail_phone_home');
		$item->phoneWork = $this->getTxt($id,'ref_avail_phone_work');
		$item->phoneCell = $this->getTxt($id,'ref_avail_phone_cell');
		$item->emailHome = $this->getTxt($id,'ref_avail_email_home');
		$item->emailWork = $this->getTxt($id,'ref_avail_email_work');
		
		$item->notes = strip_tags($this->post['ref_avail_notes']);
		
		$this->repo->save($item);
		
		// Zend_Debug::dump($this->post); 
		// Zend_Debug::dump($item);
	}
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        $response = $this->getResponse();

        $this->repo = new Ref_RefAvailRepo($this->context);
        
        $this->post = $request->getPost();
        
        foreach($this->post['ref_avail_person_id'] as $id)
        {
        	$id = (int)$id;
        	$this->processOne($id);
        }
        
        /* Redirect */
        $response->setRedirect($this->link('ref_avail_signup'));
    }
}
?>
