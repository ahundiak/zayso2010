<?php
class FieldEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $session  = $this->context->session;
        
        if (isset($session->fieldEditData)) $data = $session->fieldEditData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData();
            
            $data->fieldId     = 0;
            $data->fieldSiteId = 0;
            $data->unitId      = $user->unitId; /* For new fields */
            
            $session->fieldEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->fieldId = $id;
        
        $id2 = $request->getParam('id2');
        if ($id2 >= 0) $data->fieldSiteId = $id2;
        
        $view = new FieldEditView();
        
        $response->setBody($view->process(clone $data));
        
        return;
	}
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $model    = $this->context->models->FieldModel;
        
        $id = $request->getPost('field_id');
                
        $submitDelete = $request->getPost('field_submit_delete');
        $submitCreate = $request->getPost('field_submit_create');
        $submitUpdate = $request->getPost('field_submit_update');
        
        if ($submitDelete) {
            $confirm = $request->getPost('field_confirm_delete');
            if ($confirm) {
                $model->delete($id);
                return $response->setRedirect($this->link('field_list'));
            }    
            return $response->setRedirect($this->link('field_edit',$id));
        }
        if ($submitCreate) $id = 0;
        $item = $model->find($id);
        if ($submitUpdate) {
            if (!$item->id) {
                //die('Trying to update field_site which no longer exists');
                return $response->setRedirect($this->link('field_list'));
            }
        }     
        $item->key         = $request->getPost('field_key');
        $item->sort        = $request->getPost('field_sort');
        $item->desc        = $request->getPost('field_desc');
        $item->unitId      = $request->getPost('field_unit_id');
        $item->fieldSiteId = $request->getPost('field_site_id');

        /* Need to do some error checking here */
        $id = $model->save($item);

        $data = new SessionData();
        $data->fieldId     = $id;
        $data->fieldSiteId = $item->fieldSiteId;
        $data->unitId      = $item->unitId;
        $this->context->session->fieldEditData = $data;
        
        /* Redirect */
        $response->setRedirect($this->link('field_edit',$id));
    }
}
?>
