<?php
class MemberEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->memberEditData)) $data = $session->memberEditData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData();
            
            $data->memberId = 0;
            $data->unitId   = $user->unitId; /* For new members */
            
            $data->personLastName = NULL;
            $data->personUnitId   = 0;
            
            $session->memberEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->memberId = $id;
        
		$view = new MemberEditView();
        
        $response->setBody($view->process(clone $data));
        
	    return;
	}
    public function processAccountUpdateActionPost()
    {
        $request      = $this->getRequest();
        $response     = $this->getResponse();
        $accountModel = $this->context->models->AccountModel;
        
        $memberId  = $request->getPost('member_id');
        $accountId = $request->getPost('account_id');

        $user   = $request->getPost('account_user');
        $name   = $request->getPost('account_name');
        $email  = $request->getPost('account_email');
        $status = $request->getPost('account_status');
        $pass1  = $request->getPost('account_pass1');
        $pass2  = $request->getPost('account_pass2');
        
        $account = $accountModel->find($accountId);
        if (!$account->id) $response->setRedirect($this->link('member_edit',$memberId));
        
        $flag = FALSE;
        if (($account->user != $user) && ($user)) {
             $account->user  = $user;
             $flag = TRUE;
        }
        if (($account->name != $name) && ($name)) {
             $account->name  = $name;
             $flag = TRUE;
        }
        if ($account->email != $email) {
            $account->email  = $email;
            $flag = TRUE;
        }
        if ($account->status != $status) {
            $account->status  = $status;
            $flag = TRUE;
        }
        if (($pass1) && ($pass1 == $pass2) && ($account->pass != md5($pass1))) {
            $account->pass = md5($pass1);
            $flag = TRUE;
        }
        if (!$flag) $response->setRedirect($this->link('member_edit',$memberId));
        
        $accountModel->save($account);
        
        /* If the current user is being modified then reload the user
         * Have to make sure have an active member
         * Probably be better to just let them logout and log back in
         */
        $response->setRedirect($this->link('member_edit',$memberId));
    }
    public function processPersonSearchActionPost()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        $memberId  = $request->getPost('member_id');      
        
        $data = $this->context->session->memberEditData;    
        $data->personLastName = $request->getPost('person_lname');
        $data->personUnitId   = $request->getPost('person_unit_id');
        $this->context->session->memberEditData = $data;
        
        return $response->setRedirect($this->link('member_edit',$memberId));
        
    }
    public function processPersonLinkActionPost()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();
        
        $memberModel = $this->context->models->MemberModel;

        $memberId  = $request->getPost('member_id');
             
        $member = $memberModel->find($memberId);
        if ($member->id) {
            $member->personId = $request->getPost('person_link');
            $memberModel->save($member);
        }
        
        return $response->setRedirect($this->link('member_edit',$memberId));
        
    }
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        
        // See if it's an account update
        if ($request->getPost('account_submit_edit')) {
            return $this->processAccountUpdateActionPost();
        }
        // Volunteer Search
        if ($request->getPost('person_submit_search')) {
            return $this->processPersonSearchActionPost();
        }
        // Volunteer Link
        if ($request->getPost('person_submit_link')) {
            return $this->processPersonLinkActionPost();
        }
        
        $response = $this->getResponse();
        $model    = $this->context->models->MemberModel;
        $memberModel  = $this->context->models->MemberModel;
        
        $accountId = $request->getPost('account_id');
        $memberId  = $request->getPost('member_id');
                
        $submitDelete = $request->getPost('member_submit_delete');
        $submitCreate = $request->getPost('member_submit_create');
        $submitUpdate = $request->getPost('member_submit_update');
                  
        if ($submitDelete) {
            $confirm = $request->getPost('member_confirm_delete');
            if ($confirm) {
                $model->delete($memberId);
                return $response->setRedirect($this->link('account_index'));
            }    
            $response->setRedirect($this->link('member_edit',$memberId));
            return;
        }
        if ($submitCreate) $memberId = 0;
        $item = $model->find($memberId);
        if ($submitUpdate) {
            if (!$item->id) {
                return $response->setRedirect($this->link('account_index'));
            }
        }
        $item->accountId = $request->getPost('account_id');
        
        $item->name      = $request->getPost('member_name');
        $item->unitId    = $request->getPost('member_unit_id');      
        $item->level     = $request->getPost('member_level');
        $item->status    = $request->getPost('member_status');
        $aysoid          = $request->getPost('member_aysoid');

        if ($submitCreate) $item->level = 2;
        
        $id = $model->save($item);

        $this->processAysoid($id,$aysoid);

        $data = new SessionData();
        $data->memberId = $id;
        $data->unitId   = $item->unitId;
        $this->context->session->memberEditData = $data;
        
        /* Redirect */
        $response->setRedirect($this->link('member_edit',$id));
    }
    // Only reload the current (primary) user
    protected function loadUser($memberId)
    {
      if ($this->context->user->member->level != 1) return;

        $defaults = $this->context->config['user']; // ->user->toArray();
        $user = $this->context->models->UserModel->load($defaults,$memberId);
        $this->context->session->user = $user;
    }
    protected function processAysoid($memberId,$aysoid)
    {
      // See who is currently linked
      $memberModel  = $this->context->models->MemberModel;
      $member =  $memberModel->find($memberId);
      if (!$member->id) return 0;

      // See if have a aysoid
      if (!$aysoid)
      {
        if (!$member->personId) return 0;
        $member->personId = 0;
        $memberModel->save($member);
        $this->loadUser($member->id);
        return 0;
      }
      // Find it in person table
      $personModel  = $this->context->models->PersonModel;
      $search = new SearchData();
      $search->aysoid = $aysoid;
      $person = $personModel->searchOne($search);
      if ($person)
      {
        if ($member->personId == $person->id) return 0;

        $member->personId = $person->id;
        $memberModel->save($member);
        $this->loadUser($member->id);

        return 0;
      }
      // See if have a valid number
      error_reporting(E_ALL);
      $sql = 'SELECT * FROM eayso.reg_main WHERE reg_num = :aysoid';
      $row = $this->context->db->fetchRow($sql,array('aysoid' => $aysoid));
      if (!$row) return 1;

      // Add new person record
      $person = $personModel->newItem();
      $person->fname  = $row['fname'];
      $person->lname  = $row['lname'];
      $person->mname  = $row['mname'];
      $person->nname  = $row['nname'];
      $person->unitId = $member->unitId;
      $person->status = 2;
      $person->aysoid = $aysoid;

      $personId = $personModel->save($person);
      $member->personId = $personId;
      $memberModel->save($member);
      $this->loadUser($member->id);

      return 0;
    }
}
?>