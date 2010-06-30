<?php
class UnitEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
       $request = $this->getRequest();
       $session = $this->context->session;
        
        if (isset($session->unitEditData)) $data = $session->unitEditData;
        else {
            $data = new SessionData;
            
            $data->unitId = 0;
            
            $session->unitEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->unitId = $id;

		$view = new UnitEditView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));
        
	    return;
	}
    public function processActionPost()
    {    
        $request  = $this->getRequest();
        $response = $this->getResponse();
        $model    = $this->context->models->UnitModel;
        
        $id = $request->getPost('unit_id');
        
        $submitDelete = $request->getPost('unit_submit_delete');
        $submitCreate = $request->getPost('unit_submit_create');
        $submitUpdate = $request->getPost('unit_submit_update');
        
        /* Process delete */
        if ($submitDelete) {
            $confirm = $request->getPost('unit_confirm_delete');
            if ($confirm && $id) {
                $model->delete($id);
                $response->setRedirect($this->link('unit_list'));
                return;
            }    
            $response->setRedirect($this->link('unit_edit',$id));
            return;
        }
        /* Process create or update */
        if ($submitCreate) $id = 0;
        $item = $model->find($id);
        if ($submitUpdate) {
            if (!$item->id) {
                // die('Trying to update region which no longer exists');
                return $response->setRedirect($this->link('unit_list'));
            }
        }
        $item->key        = $request->getPost('unit_key');
        $item->sort       = $request->getPost('unit_sort');
        $item->prefix     = $request->getPost('unit_prefix');
        $item->descPick   = $request->getPost('unit_desc_pick');
        $item->descLong   = $request->getPost('unit_desc_long');
        $item->unitTypeId = $request->getPost('unit_type_id');
        
        $id = $model->save($item);
        
        $data = new SessionData();
        $data->unitId = $id;
        $this->context->session->unitEditData = $data;
        
        /* Redirect */
        $response->setRedirect($this->link('unit_edit',$id));
    }
}
?>
