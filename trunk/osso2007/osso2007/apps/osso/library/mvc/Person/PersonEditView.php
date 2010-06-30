<?php
/* ----------------------------------------------
 * Not currently being used since picklists
 * are available for all fields
 */
class VolDisplay
{
    function __construct($db)
    {
        $this->db = $db;   
    }
    function year($vol)
    {
        return YearModel::getDesc($this->db,$vol->regYearId); 
    }
    function season($vol)
    {
        return SeasonTypeModel::getDesc($this->db,$vol->seasonTypeId); 
    }
    function position($vol)
    {
        return VolTypeModel::getDesc($this->db,$vol->volTypeId); 
    }
    function unit($vol)
    {
        return UnitModel::getDesc($this->db,$vol->unitId); 
    }
    function division($vol)
    {
        return DivisionModel::getDesc($this->db,$vol->divisionId); 
    }
}
class PersonEditView extends Proj_View
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Edit Person';
        $this->tplContent = 'PersonEditTpl';
    }
    function process($data)
    {
        $models = $this->context->models;
        
        /* Get the person item */
        $personId = $data->personId;
        
        $this->person = $person = $models->PersonModel->find($personId);
        $this->personEditData = $data;
        
        if (!$person->unitId) $person->unitId = $data->unitId;
        
        /* Searching */
        $search = new SearchData();
        $search->personId = $personId;
        $search->wantx    = TRUE;
        
        /* Lots of pick lists */
        $this->divPickList        = $models->DivisionModel  ->getDivisionPickList();
        $this->unitPickList       = $models->UnitModel      ->getPickList();
        $this->yearPickList       = $models->YearModel      ->getPickList();
        $this->seasonTypePickList = $models->SeasonTypeModel->getPickList();
        $this->volTypePickList    = $models->VolTypeModel   ->getPickList();
        
        /* Want a list of the three basic types of phone numbers */
        $phoneTypeIds = array(PhoneTypeModel::TYPE_HOME,PhoneTypeModel::TYPE_WORK,PhoneTypeModel::TYPE_CELL);
        $phoneId      = -1;
        foreach($phoneTypeIds as $phoneTypeId) {
            $phone = $models->PhoneModel->find(0);
            $phone->id            = $phoneId--;
            $phone->phoneTypeId   = $phoneTypeId;
            $phones[$phoneTypeId] = $phone;
        }
        if ($personId) $phonesExisting = $models->PhoneModel->search($search);
        else           $phonesExisting = array();
        foreach($phonesExisting as $phone) {
            $phones[$phone->phoneTypeId] = $phone;
        }
        $this->phones = $phones;
        $this->phoneTypePickList = $models->PhoneTypeModel->getPickList();
        
        /* Want a list of the two basic types of emails */
        $emailTypeIds = array(EmailTypeModel::TYPE_HOME,EmailTypeModel::TYPE_WORK);
        $emailId = -1;
        foreach($emailTypeIds as $emailTypeId) {
            $email = $models->EmailModel->find(0);
            $email->id            = $emailId--;
            $email->emailTypeId   = $emailTypeId;
            $emails[$emailTypeId] = $email;
        }
        if ($personId) $emailsExisting = $models->EmailModel->search($search);
        else           $emailsExisting = array();
        foreach($emailsExisting as $email) {
            $emails[$email->emailTypeId] = $email;
        }
        $this->emails = $emails;
        $this->emailTypePickList = $models->EmailTypeModel->getPickList();

        /* List of volunteer positions, with a couple of blanks */
        $searchVol = new SearchData();
        $searchVol->personId     = $personId;
        $searchVol->unitId       = $data->volShowUnitId;
        $searchVol->yearId       = $data->volShowYearId;
        $searchVol->seasonTypeId = $data->volShowSeasonTypeId;
        
        if ($personId) $vols = $models->VolModel->search($searchVol);
        else           $vols = array();
        
        $volIds = array(-1,-2);
        foreach($volIds as $volId) {
            $vol = $models->VolModel->find(0);
            $vol->id           = $volId;
            $vol->regYearId    = $data->yearId;
            $vol->seasonTypeId = $data->seasonTypeId;
            $vol->personId     = $person->personId;
            $vol->unitId       = $person->unitId;
            $vols[] = $vol;
        }
        $this->vols = $vols;
        //$this->displayVol = new VolDisplay($models);
        
        /* And render it  */      
        return $this->renderx();
    }
    function displayPhoneNumber($phone)
    {
        if ($phone->areaCode) $areaCode = $phone->areaCode;
        else                  $areaCode = 256;
       
        $number = "({$areaCode}) {$phone->num}";
    
        if ($phone->ext) $number .= ' ' . $phone->ext;
            
        return $this->escape($number);
    }
    function displayPhoneType($phone)
    {
        return $this->phoneTypePickList[$phone->phoneTypeId];
    }
    function displayEmailAddress($email)
    {
        return $this->escape($email->address);
    }
    function displayEmailType($email)
    {
        return $this->emailTypePickList[$email->emailTypeId];
    }
    function displayPersonName($person)
    {
        return $this->escape($person->name);
    }
}
?>
