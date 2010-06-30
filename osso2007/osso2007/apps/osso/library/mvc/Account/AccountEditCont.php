<?php
class AccountEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->accountEditData)) $data = $session->accountEditData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData();
            
            $data->accountId = 0;
            
            $session->accountEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->accountId = $id;
        
        $view = new AccountEditView();
        
        $response->setBody($view->process(clone $data));
        
        return;
	}
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $model    = $this->context->models->AccountModel;
        
        $id = $request->getPost('account_id');
                
        $submitDelete = $request->getPost('account_submit_delete');
        $submitCreate = $request->getPost('account_submit_create');
        $submitUpdate = $request->getPost('account_submit_update');
        
        if ($submitDelete) {
            $confirm = $request->getPost('account_confirm_delete');
            if ($confirm) {
                $model->delete($id);
                return $response->setRedirect($this->link('account_list'));
            }    
            return $response->setRedirect($this->link('account_edit',$id));
        }
        if ($submitCreate) $id = 0;
        $item = $model->find($id);
        if ($submitUpdate) {
            if (!$item->id) {
                //die('Trying to update field_site which no longer exists');
                return $response->setRedirect($this->link('account_list'));
            }
        }     
        $item->user   = $request->getPost('account_user');
        $item->name   = $request->getPost('account_name');
        $item->email  = $request->getPost('account_email');
        $item->status = $request->getPost('account_status');

        $pass1 = $request->getPost('account_pass1');
        $pass2 = $request->getPost('account_pass2');
        if ($pass1 && ($pass1 == $pass2)) {
            $item->pass = md5($pass1);
        }
        /* Need to do some error checking here */
        $id = $model->save($item);

        $data = new SessionData();
        $data->accountId = $id;
        $this->context->session->accountEditData = $data;
        
        /* Redirect */
        $response->setRedirect($this->link('account_edit',$id));
    }
}
?>
