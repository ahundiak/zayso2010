<?php
/* ----------------------------------------------
 * The infamous user business object
 * Represents a logged in user with all kind of info
 */
 
/* ----------------------------------------------
 * Calling this an item for now mainly for consistency
 * Does not have the CRUD interface and is not directly persistable
 * 
 * A brand new user should know that it is not authenticated
 * Should it also have defaults from the config object?
 * 
 * If the user is not a member then still nice to know a default unit, year and season,
 */
class UserItem
{
    protected $data = array();
    
    public function __construct($defaults = NULL)
    {
        $this->data['member']   = NULL;
        $this->data['account']  = NULL;
        $this->data['person']   = NULL;
        $this->data['is_admin'] = NULL;
        
        $this->data['defaults'] = array();
        $this->setDefaults($defaults);
    }
    protected function getContext()
    {
        return ProjectContext::getInstance('user');
    }
    public function __get($name)
    {
        switch($name) {

            /* Display Name */
            case 'name':
                $member = $this->member;
                if (!$member) return NULL;
                
                $account = $this->account;
                if (!$account) return NULL;
                
                return $member->name . ' ' . $account->name;
                
            /* Am I an authenticated member ? */
            case 'isAuth':
            case 'isMember':
                if (isset($this->data['member'])) return TRUE;
                return FALSE;
                
            case 'member':  return $this->data['member'];
            case 'account': return $this->data['account'];
                
            /* Am I a person */
            case 'isPerson':
                if (isset($this->data['person'])) return TRUE;
                return FALSE;
                
            case 'person': return $this->data['person'];
                      
            /* Am I an administrator? */
            case 'isAdmin':
                if ($this->data['is_admin'] === NULL) {
                    $this->data['is_admin'] = $this->getContext()->models->UserModel->isAdmin($this);
                } 
                return $this->data['is_admin'];
            
            case 'isReferee': 
                return $this->getContext()->models->UserModel->isRegionReferee($this,0);
                
            case 'isMadisonReferee': 
                return $this->getContext()->models->UserModel->isRegionReferee($this,4);
                
            case 'isMonroviaReferee': 
                return $this->getContext()->models->UserModel->isRegionReferee($this,1);
                
            case 'refereePickList': 
                return $this->getContext()->models->UserModel->getRefereePickList($this);
                
            case 'personIds': 
                return $this->getContext()->models->UserModel->getPersonIds($this);
             
            /* Defaults */
            case 'yearId':
            case 'regYearId':
            case 'defaultYearId':
            case 'defaultRegYearId':
                return $this->data['defaults']['reg_year_id'];
                
            case 'yearDesc':
            case 'regYearDesc':
            case 'defaultYearDesc':
            case 'defaultRegYearDesc':
                return $this->data['defaults']['reg_year_desc'];
                
            case 'seasonTypeId':
            case 'defaultSeasonTypeId':
                return $this->data['defaults']['season_type_id'];
                
            case 'seasonTypeDesc':
            case 'defaultSeasonTypeDesc':
                return $this->data['defaults']['season_type_desc'];
                
            case 'unitId':                
            case 'defaultUnitId':
                return $this->data['defaults']['unit_id'];
            
            case 'unitDesc':
            case 'defaultUnitDesc':
                return $this->data['defaults']['unit_desc'];            
        }
        die('User __get ' . $name);
    }
    /* ------------------------------------
     * defaults will be an array
     * reg_year_id
     * season_type_id
     * unit_id
     * unit_desc
     */
    public function setDefaults($defaults)
    {
        if (!$defaults) return;
        
        foreach($defaults as $key => $value) {   
            $this->data['defaults'][$key] = $value;
        }
    }
    public function setMember ($item) { $this->data['member']   = $item; }
    public function setPerson ($item) { $this->data['person']   = $item; }
    public function setAccount($item) { $this->data['account']  = $item; }
    public function setIsAdmin($flag) { $this->data['is_admin'] = $flag; }
}
class UserModel extends BaseModel
{
    static $instance = NULL;
    
    /* ------------------------------------------
     * Need this because sometimes the user item will want to
     * query the model after being stored in the session
     * No gaurentee that the UserModel has actually been created
     * though i suppose we could do so in the startup code
     */
    static function getInstance()
    {
        if (!self::$instance) {
            $context = Zend::registry('context'); // Hate to do this
            self::$instance = $context->models->UserModel;
        }
        return self::$instance;
    }
    function init()
    {
        self::$instance = $this;
    }
    function create($defaults)
    {
        $models = $this->context->models;
        
        /* Pull in a few descriptions */
        if (!isset($defaults['unit_desc']))        $defaults['unit_desc']        = $models->UnitModel->getDesc      ($defaults['unit_id']);
        if (!isset($defaults['reg_year_desc']))    $defaults['reg_year_desc']    = $models->YearModel->getDesc      ($defaults['reg_year_id']);
        if (!isset($defaults['season_type_desc'])) $defaults['season_type_desc'] = $models->SeasonTypeModel->getDesc($defaults['season_type_id']);
        
        $user = new UserItem($defaults);
        
        return $user;   
    }
    function load($defaults,$memberId)
    {
        $models = $this->context->models;
        
        /* Grab the member, account */
        $memberItem  = $models->MemberModel ->find($memberId);
        $accountItem = $models->AccountModel->find($memberItem->accountId);
        
        $memberItem ->pass = 'SUPPRESED';
        $accountItem->pass = 'SUPPRESED';
        
        /* Adjust the unit base on the member */
        $unitId = $memberItem->unitId;
        if ($defaults['unit_id']  != $unitId) {
            $defaults['unit_id']   = $unitId;
            $defaults['unit_desc'] = $models->UnitModel->getDesc($unitId);
        }
        
        /* Process any linked person */
        if ($memberItem->personId) {
            $personItem = $models->PersonModel->find($memberItem->personId);
        }
        else $personItem = NULL;

        /* And build it */        
        $user = $this->create($defaults);
        $user->setMember ($memberItem);
        $user->setAccount($accountItem);
        $user->setPerson ($personItem);
        
        /* 
         * For some queries want list of all member persons for the account
         * But I think we can wait until the query is actually needed and then
         * test to see if it has already been done
         * 
         * Same for associated teams and other such relations
         */
        return $user;
    }
    /* ------------------------------------------
     * Check to see if the user is an 'administrator' and
     * returns true or false accordingly
     */
    function isAdmin($user)
    {
        if (!$user->isPerson) return FALSE;
        
        $search = new SearchData();
        $search->personId     = $user->person->id;
        $search->unitId       = $user->unitId;
        $search->yearId       = $user->yearId;
        $search->seasonTypeId = $user->seasonTypeId;    // Limits to Fall 2008
        $search->volTypeId    = VolTypeModel::TYPE_ZADM;
        
        $models = $this->context->models;
        $volItems = $models->VolModel->search($search);
        if (count($volItems) < 1) return FALSE;

        return TRUE;
    }
    /* ------------------------------------------
     * Check to see if the user is an Madison referee
     * returns true or false accordingly
     * 
     * Have stuff in here to allow a referee to be part of multiple regions
     */
    function isRegionReferee($user,$unitId)
    {
        if (!$user->isPerson) return FALSE;

        $search = new SearchData();
        $search->personId     = $user->person->id;
//      $search->unitId       = $user->unitId;
        $search->yearId       = $user->yearId;
        $search->seasonTypeId = $user->seasonTypeId;
        $search->volTypeId    = array(VolTypeModel::TYPE_ADULT_REF,VolTypeModel::TYPE_YOUTH_REF);
        
        $models = $this->context->models;
        $volItems = $models->VolModel->search($search);
        
        if (count($volItems) < 1) return FALSE;
		foreach($volItems as $volItem) {
			if (!$unitId) return TRUE;
			if ($volItem->unitId == $unitId) return TRUE;
		}
        return FALSE;
    }
    function getRefereePickList($user)
    {
        $models = $this->context->models;
        $accountId = $user->account->id;
        
        $select = new Proj_Db_Select($this->db);
        $select->from ('member','member.member_id AS member_id');
        $select->joinleft(
            'person',
            'person.person_id = member.person_id',
            array(
                'person.person_id AS person_id',
                'person.fname AS person_fname',
                'person.lname AS person_lname',
        ));
        $select->joinleft(
            'vol',
            'vol.person_id = person.person_id',
            array(
                'vol.vol_type_id AS vol_type_id',
        ));
        $select->where("member.account_id IN (?)",$accountId);
        $select->where("vol.vol_type_id IN (?)",array($models->VolType->TYPE_ADULT_REF,$models->VolType->TYPE_YOUTH_REF));
        
        // Limits to current season
        $yearIds       = array(8,9,10);
        $seasonTypeIds = array(1,2,3);
		$select->where("vol.reg_year_id    IN (?)",$yearIds);
		$select->where("vol.season_type_id IN (?)",$seasonTypeIds);
		
        $rows = $this->db->fetchAll($select);
        // Zend_Debug::dump($rows); die();
        $items = array();
        foreach($rows as $row)
        {
            $name = $row['person_lname'] . ', ' . $row['person_fname'];
            $items[$row['person_id']] = $name;
        }        
        return $items;
    }
    function getPersonIds($user)
    {
        if (!$user->account) return array();
        
        $accountId = $user->account->id;
        
        $select = new Proj_Db_Select($this->db);
        
        $select->distinct();
        
        $select->from ('member','member.person_id AS person_id');
        
        $select->where("member.account_id IN (?)",$accountId);
        
        $rows = $this->db->fetchAll($select);
        
        $items = array();
        foreach($rows as $row)
        {
            $items[$row['person_id']] = $row['person_id'];
        }        
        return $items;
    }   
}
?>