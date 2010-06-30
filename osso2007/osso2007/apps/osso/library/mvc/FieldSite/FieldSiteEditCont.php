<?php
class FieldSiteEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->fieldSiteEditData)) $data = $session->fieldSiteEditData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData();
            
            $data->fieldSiteId = 0;
            $data->unitId      = $user->unitId; /* For new sites */
            
            $session->fieldSiteEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->fieldSiteId = $id;
        
		$view = new FieldSiteEditView();
        
        $response->setBody($view->process(clone $data));
        
	    return;
	}
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $model    = $this->context->models->FieldSiteModel;
        
        $id = $request->getPost('field_site_id');
                
        $submitDelete = $request->getPost('field_site_submit_delete');
        $submitCreate = $request->getPost('field_site_submit_create');
        $submitUpdate = $request->getPost('field_site_submit_update');
        
        if ($submitDelete) {
            $confirm = $request->getPost('field_site_confirm_delete');
            if ($confirm) {
                $model->delete($id);
                return $response->setRedirect($this->link('field_site_list'));
            }    
            $response->setRedirect($this->link('field_site_edit',$id));
            return;
        }
        if ($submitCreate) $id = 0;
        $item = $model->find($id);
        if ($submitUpdate) {
            if (!$item->id) {
                //die('Trying to update field_site which no longer exists');
                return $response->setRedirect($this->link('field_site_list'));
            }
        }
        $item->key    = $request->getPost('field_site_key');
        $item->sort   = $request->getPost('field_site_sort');
        $item->desc   = $request->getPost('field_site_desc');
        $item->unitId = $request->getPost('field_site_unit_id');
        
        $id = $model->save($item);
        
        $data = new SessionData();
        $data->fieldSiteId = $id;
        $data->unitId = $item->unitId;
        $this->context->session->fieldSiteEditData = $data;
        
        /* Redirect */
        $response->setRedirect($this->link('field_site_edit',$id));
    }
}
?>