<?php
class PersonListCont extends Proj_Controller_Action 
{
    /* ------------------------------------------
     * Basically a search function with all
     * parameters being extracted from the current session
     * 
     * All request parameters are ignored?
     * Posting is required to get the search parameters into the session
     */
    public function processAction()
    {
        $session = $this->context->session;

        if (isset($session->personListData)) $data = $session->personListData;
        else {
            $user = $this->context->user;
            
            $data = new SessionData;
            $data->unitId       = $user->unitId;  // The person, not the volunteer
            $data->yearId       = $user->yearId;
            $data->seasonTypeId = $user->seasonTypeId;
            
            $data->unitId       = 0;  // The person, not the volunteer
            $data->yearId       = 0;
            $data->seasonTypeId = 0;
            
            $data->volTypeId = 0;
            
            $session->personListData = $data;   
        }

        $view = new PersonListView();
        
        $response = $this->getResponse();
        
        $response->setBody($view->process(clone $data));
        
        return;
    }
    public function processActionPost()
    {            
        $data = new SessionData();
         
        $request = $this->getRequest();
        
        $data->personId     = $request->getPost('person_id');
        $data->fname        = $request->getPost('person_fname');
        $data->lname        = $request->getPost('person_lname');
        
        $data->unitId       = $request->getPost('person_unit_id');
        $data->yearId       = $request->getPost('person_year_id');
        $data->volTypeId    = $request->getPost('person_vol_type_id');
        $data->seasonTypeId = $request->getPost('person_season_type_id');
        
        $this->context->session->personListData = $data;
        
        $response = $this->getResponse();
        $response->setRedirect($this->link('person_list'));
        
        return;
    }
}
?>
