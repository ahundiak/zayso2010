<?php
class PersonEditCont extends Proj_Controller_Action 
{
	public function processAction()
	{
        $request = $this->getRequest();
        $session = $this->context->session;
        
        if (isset($session->personEditData)) $data = $session->personEditData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData;
            
            $data->personId     = 0;
            $data->unitId       = $user->unitId;
            $data->yearId       = $user->yearId;
            $data->seasonTypeId = $user->seasonTypeId;
            
            $data->volShowUnitId       = 0;
            $data->volShowYearId       = $user->yearId;
            $data->volShowSeasonTypeId = $user->seasonTypeId;
            
            $session->personEditData = $data;   
        }
        $id = $request->getParam('id');
        if ($id >= 0) $data->personId = $id;
             
		$view = new PersonEditView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));
        
	    return;
	}
    public function processActionPost()
    {    
        $response = $this->getResponse();
        $request  = $this->getRequest();
        $model    = $this->context->models->PersonModel;
        
        $id = $request->getPost('person_id');
        
        $submitDelete = $request->getPost('person_submit_delete');
        $submitClear  = $request->getPost('person_submit_clear');
        $submitCreate = $request->getPost('person_submit_create');
        $submitUpdate = $request->getPost('person_submit_update');

        if (!$this->context->user->isAdminx) $submitCreate = FALSE;
        
        $submitUpdatePositions = $request->getPost('person_submit_update_positions');
        $submitUpdateVolShow   = $request->getPost('person_submit_update_vol_show');
        
        /* Deal with delete, redirect if not confiremd */
        if ($submitDelete) {
            $confirm = $request->getPost('person_confirm_delete');
            if ($confirm) {
                $model->delete($id);
                // VolModel::deleteForPerson($db,$id);
                return $response->setRedirect($this->link('person_list'));
            }    
            return $response->setRedirect($this->link('person_edit',$id));
        }
        /* Deal with clear, just redirect with id of zero */
        if ($submitClear) {
            return $response->setRedirect($this->link('person_edit',0));
        }
        /* Deal with updating positions only */
        if ($submitUpdatePositions) {
            if ($id) $this->processVols($id);
            return $response->setRedirect($this->link('person_edit',$id));    
        }
        /* Deal with updating list of positions to show */
        if ($submitUpdateVolShow) {
            $data = $this->context->session->personEditData;
            $data->volShowUnitId       = $request->getPost('vol_show_unit_id');
            $data->volShowYearId       = $request->getPost('vol_show_year_id');
            $data->volShowSeasonTypeId = $request->getPost('vol_show_season_type_id');
            
            return $response->setRedirect($this->link('person_edit',$id));    
        }
        /* Update or create new person */
        if (!$submitCreate && !$submitUpdate) return $response->setRedirect($this->link('person_edit',$id));
        
        if ($submitCreate) $id = 0;
        $item = $model->find($id);
        
        if ($submitUpdate) {
            if (!$item->id) { /* Person being updated does not exist */
                return $response->setRedirect($this->link('person_list'));
            }    
        }
        $item->fname  = $request->getPost('person_fname');
        $item->lname  = $request->getPost('person_lname');
        $item->unitId = $request->getPost('person_unit_id');
		$item->aysoid = $request->getPost('person_aysoid');
		        
        $id = $model->save($item);
        
        /* Update Related Tables */
        $this->processPhones($id);
        $this->processEmails($id);
        $this->processVols  ($id);
            
        /* Store id in session */
        /* Bit different but I think it's okay */
        $data = $this->context->session->personEditData;
        $data->personId = $id;      
        
        /* Redirect back to Person Edit */        
        $response->setRedirect($this->link('person_edit',$id));
    }
    /* ------------------------------------------
     * Update the phones
     */
    protected function processPhones($personId)
    {
        $request = $this->getRequest();
        $model   = $this->context->models->PhoneModel;

        /* Just to be safe */
        if (!$personId) return;
                
        $phoneIds     = $request->getPost('phone_ids');
        $phoneNumbers = $request->getPost('phone_numbers');
        $phoneTypeIds = $request->getPost('phone_type_ids');
 
        foreach($phoneIds as $key => $phoneId) {
            
            $phoneNumber = $phoneNumbers[$key];
            $phoneTypeId = $phoneTypeIds[$key];
            
            $phoneInfo = PhoneValidate::validate($phoneNumber);
                
            /* Check for update */
            if ($phoneId > 0) {
                
                $phoneItem = $model->find($phoneId);
                
                if (!$phoneNumber) {
                    $phoneItem->areaCode = '';
                    $phoneItem->num = '';
                    $phoneItem->ext = '';
                    $model->save($phoneItem);
                }
                elseif (!isset($phoneInfo['error'])) {
                    $phoneItem->areaCode = $phoneInfo['area'];
                    $phoneItem->num      = $phoneInfo['phone'];
                    $phoneItem->ext      = $phoneInfo['remainder'];
                    $model->save($phoneItem);
                }
                else {
                    // Need to flag an invalid number some how */
                }
            }
            else {
                if (!isset($phoneInfo['error'])) {
                    $phoneItem = $model->find(0);
                    $phoneItem->areaCode    = $phoneInfo['area'];
                    $phoneItem->num         = $phoneInfo['phone'];
                    $phoneItem->ext         = $phoneInfo['remainder'];
                    $phoneItem->phoneTypeId = $phoneTypeId;
                    $phoneItem->personId    = $personId;
                    $model->save($phoneItem);
                } 
            }
        }
    }
    /* ------------------------------------------
     * Update the emails
     */
    protected function processEmails($personId)
    {
        $request = $this->getRequest();
        $model   = $this->context->models->EmailModel;
        
        /* Just to be safe */
        if (!$personId) return;
        
        $emailIds       = $request->getPost('email_ids');
        $emailAddresses = $request->getPost('email_addresses');
        $emailTypeIds   = $request->getPost('email_type_ids');
        
        foreach($emailIds as $key => $emailId) {
            
            $emailAddress = $emailAddresses[$key];
            $emailTypeId  = $emailTypeIds  [$key];
            
            /* Check for update */
            if ($emailId > 0) {
                $emailItem = $model->find($emailId);
                $emailItem->address = $emailAddress;
                $model->save($emailItem);
            }
            else {
                if ($emailAddress) {
                    $emailItem = $model->find(0);
                    $emailItem->address     = $emailAddress;
                    $emailItem->emailTypeId = $emailTypeId;
                    $emailItem->personId    = $personId;
                    $model->save($emailItem);
                } 
            }
        }
    }
    /* ------------------------------------------
     * Update the volunteer list
     */
    protected function processVols($personId)
    {
        $request = $this->getRequest();
        $model   = $this->context->models->VolModel;
        
        /* Just to be safe */
        if (!$personId) return;
        
        $volIds           = $request->getPost('vol_ids');
        $volTypeIds       = $request->getPost('vol_type_ids');
        $volUnitIds       = $request->getPost('vol_unit_ids');
        $volYearIds       = $request->getPost('vol_year_ids');
        $volNotes         = $request->getPost('vol_notes');
        $volSeasonTypeIds = $request->getPost('vol_season_type_ids');
        $volDivisionIds   = $request->getPost('vol_div_ids');
        
        foreach($volIds as $key => $volId) {
            
            $volTypeId       = $volTypeIds      [$key];
            $volUnitId       = $volUnitIds      [$key];
            $volYearId       = $volYearIds      [$key];
            $volNote         = $volNotes        [$key];
            $volSeasonTypeId = $volSeasonTypeIds[$key];
            $volDivisionId   = $volDivisionIds  [$key];
            
            /* Check for update */
            if ($volId > 0) {
                if (!$volTypeId) {
                    $model->delete($volId);
                }
                else {
                    $volItem = $model->find($volId);
                    $volItem->volTypeId    = $volTypeId;
                    $volItem->unitId       = $volUnitId;
                    $volItem->regYearId    = $volYearId;
                    $volItem->note         = $volNote;
                    $volItem->seasonTypeId = $volSeasonTypeId;
                    $volItem->divisionId   = $volDivisionId;
                    $model->save($volItem);
                }
            }
            else {
                if ($volTypeId) {
                    $volItem = $model->find(0);
                    $volItem->volTypeId    = $volTypeId;
                    $volItem->unitId       = $volUnitId;
                    $volItem->regYearId    = $volYearId;
                    $volItem->note         = $volNote;
                    $volItem->seasonTypeId = $volSeasonTypeId;
                    $volItem->divisionId   = $volDivisionId;
                    $volItem->personId     = $personId;
                    $model->save($volItem);
                } 
            }
        }
    }
}
?>
