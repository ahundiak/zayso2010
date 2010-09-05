<?php
error_reporting(E_ALL);

class AccountCreateCont extends Proj_Controller_Action 
{
    public function processAction()
    {
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->accountCreateData)) $data = $session->accountCreateData;
        else {
            $user = $this->context->user;
            $data = new SessionData();
            
            $data->accountUser   = NULL;
            $data->accountName   = NULL;
            $data->accountEmail  = NULL;
            
            $data->memberName   = NULL;
            $data->memberUnitId = $user->unitId;
            $data->memberAysoid = NULL;
            
            $data->message = NULL;
            
            $session->accountCreateData = $data;   
        }
        $id = $request->getParam('id');
        
        $view = new AccountCreateView();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    protected function clean($value)
    {
        if (is_int($value)) return $value;
        return trim(strip_tags($value));
    }
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $redirect = $this->link('account_create');
        $models   = $this->context->models; 
               
        $submitCreate = $request->getPost('account_submit_create');
        
        $data = new SessionData();
        $this->context->session->accountCreateData = $data;
            
        $data->accountUser  = $this->clean($request->getPost('account_user'));
        $data->accountName  = $this->clean($request->getPost('account_name'));
        $data->accountEmail = $this->clean($request->getPost('account_email'));
            
        $data->memberName   = $this->clean($request->getPost('member_name'));
        $data->memberAysoid = $this->clean($request->getPost('member_aysoid'));
        $data->memberUnitId = $this->clean($request->getPost('member_unit_id'));
            
        $data->message = NULL;
        
        $pass1 = $this->clean($request->getPost('account_pass1'));
        $pass2 = $this->clean($request->getPost('account_pass2'));

        /* Lots of error checking */
        if (!$data->accountUser) {
            $data->message = 'User name is required';
            return $response->setRedirect($redirect);
        }
        if (!$data->memberName) {
            $data->message = 'First name is required';
            return $response->setRedirect($redirect);
        }
        if (!$data->accountName) {
            $data->message = 'Last name is required';
            return $response->setRedirect($redirect);
        }
        if (!$pass1) {
            $data->message = 'Password is required';
            return $response->setRedirect($redirect);
        }
        if ($pass1 != $pass2) {
            $data->message = 'Passwords did not match';
            return $response->setRedirect($redirect);
        }
        if (!$data->memberUnitId) {
            $data->message = 'Must select an organization';
            return $response->setRedirect($redirect);
        }

        // Check the aysoid
        $personId = $this->processAysoid($data);
        if ($data->message) return $response->setRedirect($redirect);
        
        /* Make the account */
        $account = $models->AccountModel->find(0);
        
        $account->user   = $data->accountUser;
        $account->name   = $data->accountName;
        $account->email  = $data->accountEmail;
        $account->status = 1;
        $account->pass   = md5($pass1);        
        
        try {
            $accountId = $models->AccountModel->save($account);
        }
        catch (Exception $e1) {
            if ($models->AccountModel->db->isDuplicateEntryError($e1)) {
                $data->message = 'User name already exists, please choose a different name';
                return $response->setRedirect($redirect);
            }
            $data->message = 'Problem creating new account';
            return $response->setRedirect($redirect);                    
        }
        
        /* Make the member */
        $member = $models->MemberModel->find(0);
        $member->accountId = $accountId;
        $member->name      = $data->memberName;
        $member->unitId    = $data->memberUnitId;
        $member->personId  = $personId;
        $member->level     = 1;
        $member->status    = 1;

        $memberId = $models->MemberModel->save($member);
        
        /* Login to it */
        $defaults = $this->context->config['user'];

      //$user = $models->UserModel->load($defaults,$memberId);

        $repo = new Osso2007_UserRepo($this->context);
        $user = $repo->load($defaults,$memberId);

        $this->context->session->user = $user;
        $response->setRedirect($this->link('account_index'));
               
        /* Redirect */
        // $response->setRedirect($this->link('account_created'));
    }
    protected function processAysoid($data)
    {
      // See if have one
      $aysoid = $data->memberAysoid;
      if (!$aysoid) return 0;

      // Check existing
      $directPerson = new Osso2007_Person_PersonDirect($this->context);
      $result = $directPerson->fetchRows(array('aysoid' => $aysoid));
      if ($result->rowCount)
      {
        // $data->message = 'Found Person ' . $result->rows[0]['person_id'];
        return $result->rows[0]['person_id'];
      }
 
      // Make sure it's valid
      $directEayso = new Eayso_Reg_Main_RegMainDirect($this->context);
      $result = $directEayso->fetchRow(array('reg_num' => $aysoid));
      if (!$result->row)
      {
        $data->message = 'AYSO ID Is invalid or the record has not been loaded into zayso yet';
        return 0;
      }
      $row = $result->row;

      // Add new person record
      $datax = array(
        'fname'   => $row['fname'],
        'lname'   => $row['lname'],
        'mname'   => $row['mname'],
        'nname'   => $row['nname'],
        'aysoid'  => $row['reg_num'],
        'unit_id' => $data->memberUnitId,
        'status'  => 3,
      );
      $result = $directPerson->insert($datax);
      $personId = $result->id;

      // $data->message = 'Added Person ' . $personId;

      return $personId;
    }
}
?>
