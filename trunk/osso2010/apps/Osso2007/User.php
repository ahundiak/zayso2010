<?php
/* ----------------------------------------------
 * The infamous user business object
 * Represents a logged in user with all kind of info
 */
class Osso2007_User
{
  protected $context = null;
  protected $data    = array();

  public function __construct($context = null, $data = null)
  {
    $this->context = $context;

    if ($data)
    {
      $this->data = $data;
      return;
    }
    // Move to init
    $this->data['member']   = NULL;
    $this->data['account']  = NULL;
    $this->data['person']   = NULL;
    
    $this->data['defaults'] = array();

    $this->init();
  }
  protected function init() {}

  public function __sleep() { return array('data'); }
  
  protected function getContext() { return $this->context; }

  public function setContext($context) { $this->context = $context; }

  public function __get($name)
  {
    switch($name)
    {
      case 'data': return $this->data;

      // The user model
      case 'repo':
        $this->repo = new Osso2007_UserRepo($this->context);
        return $this->repo;

      /* Cert information */
      case 'certs':
        return $this->repo->getCerts($this);

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
        return $this->repo->isAdmin($this);

      case 'isAdminx':
        return $this->repo->isAdminx($this);
            
      case 'isReferee': 
         return $this->repo->isRegionReferee($this,0);
                
      case 'isMadisonReferee': 
         return $this->repo->isRegionReferee($this,4);
                
      case 'isMonroviaReferee': 
         return $this->repo->isRegionReferee($this,1);
                
      case 'refereePickList': 
         return $this->repo->getRefereePickList($this);
                
      case 'personIds': 
         return $this->repo->getPersonIds($this);
             
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
  public function setDefaults($defaults = array())
  {
    $this->data['defaults'] = array_merge($this->data['defaults'],$defaults);
  }
  public function setMember ($item) { $this->data['member']   = $item; }
  public function setPerson ($item) { $this->data['person']   = $item; }
  public function setAccount($item) { $this->data['account']  = $item; }
  public function setIsAdmin($flag) { $this->data['is_admin'] = $flag; }
}

?>
