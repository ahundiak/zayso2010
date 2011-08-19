<?php
/* ----------------------------------------------------------
 * Break out much of the functionality into this repo class
 */
class Osso2007_UserRepo
{
  protected $context = null;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function create($defaults)
  {
    $models = $this->context->models;
        
    /* Pull in a few descriptions */
    if (!isset($defaults['unit_desc']))        $defaults['unit_desc']        = $models->UnitModel->getDesc      ($defaults['unit_id']);
    if (!isset($defaults['reg_year_desc']))    $defaults['reg_year_desc']    = $models->YearModel->getDesc      ($defaults['reg_year_id']);
    if (!isset($defaults['season_type_desc'])) $defaults['season_type_desc'] = $models->SeasonTypeModel->getDesc($defaults['season_type_id']);
        
    $user = new Osso2007_User($this->context);
    $user->setDefaults($defaults);

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
    if ($defaults['unit_id']  != $unitId)
    {
      $defaults['unit_id']   = $unitId;
      $defaults['unit_desc'] = $models->UnitModel->getDesc($unitId);
    }
        
    /* Process any linked person */
    if ($memberItem->personId)
    {
      $personItem = $models->PersonModel->find($memberItem->personId);
    }
    else $personItem = NULL;

    /* And build it */        
    $user = $this->create($defaults);
    $user->setMember ($memberItem);
    $user->setAccount($accountItem);
    $user->setPerson ($personItem);

    return $user;
  }
  /* ------------------------------------------
   * Check to see if the user is an 'administrator' and
   * returns true or false accordingly
   */
  function isAdminx($user)
  {
    if (!$user->isPerson) return FALSE;

    switch($user->person->id)
    {
      case 1:       // Me
      
      case 611:     // Patrick Streeter
      case 652:     // Debbie F
      case 609:     // David S
      case 1674:    // Ray Cassell
      case 1603:    // Martin Draper
      case 808:     // Vernon Paulett
      case 609:     // David S
      case 1695:    // Jim Meehan
      case 1880:    // Paul Sapp
      case 1544:    // Gen Uhl
      case 1552:    // Bob Ellington 
        return TRUE;
    }
    return FALSE;
  }
  function isAdmin($user)
  {
    if (!$user->isPerson) return FALSE;

    switch($user->person->id)
    {
      case 1:       // Me
      case 611:     // Patrick Streeter
      case 652:     // Debbie F
      case 808:     // Vernon Paulett
      case 609:     // David S
      case 1695:    // Jim Meehan
      case 1880:    // Paul Sapp
      case 1544:    // Gen Uhl
      case 1552:    // Bob Ellington
      case 1603:    // Martin Draper
      case 1674:    // Ray Cassell    
        return TRUE;
    }
    return FALSE;
  }
  /* ------------------------------------------
   * Check to see if the user is a region referee
   * returns true or false accordingly
   * 
   * Have stuff in here to allow a referee to be part of multiple regions
   */
  function isRegionReferee($user,$unitId)
  {
    if (!$user->isPerson) return FALSE;

    $aysoid = $user->person->aysoid;
    if (!$aysoid) return FALSE;

    $sql = 'SELECT * FROM eayso.reg_view_info WHERE reg_num = :aysoid;';
    $rows = $this->context->db->fetchAll($sql,array('aysoid' => $aysoid));
    foreach($rows as $row)
    {
      if ($row['cert_cat'] == Eayso_Reg_Cert_RegCertRepo::TYPE_REFEREE_BADGE)
      {
        if (!$unitId) return TRUE;
        if ( $unitId == $row['org_id']) return TRUE;
      }
    }
    return FALSE;
  }
  function getRefereePickList($user)
  {
    if (!$user->account) return array();
    
    $accountId = $user->account->id;
    $sql = <<<EOT
SELECT
  person.fname     AS fname,
  person.lname     AS lname,
  person.nname     AS nname,
  person.person_id AS person_id,
  person.aysoid    AS aysoid,

  eayso.reg_cert.catx  AS cert_cat,
  eayso.reg_cert.typex AS cert_type

FROM member
LEFT JOIN person ON person.person_id = member.person_id
LEFT JOIN eayso.reg_cert ON eayso.reg_cert.reg_num = person.aysoid
WHERE account_id = :account_id
ORDER BY lname,fname;
EOT;
    $rows = $this->context->db->fetchRows($sql,array('account_id' => $accountId));
    $persons = array();
    foreach($rows as $row)
    {
      $personId = $row['person_id'];
      if (!isset($persons[$personId]))
      {
        $persons[$personId] = $row;
        $persons[$personId]['isReferee'] = false;
      }
      if ($row['cert_cat'] == 200) $persons[$personId]['isReferee'] = true;
    }
    // Cerad_Debug::dump($persons); die();
    $items = array();
    foreach($persons AS $person)
    {
      if ($person['isReferee'])
      {
        $nname = $person['nname'];
        $fname = $person['fname'];
        $name  = $person['lname'];
        if ($nname) $name .= ', ' . $nname;
        else        $name .= ', ' . $fname;

        $items[$person['person_id']] = $name;
      }
    }
    return $items;
    //Cerad_Debug::dump($items); die();
  }
  function getPersonIds($user)
  {
    if (!$user->account) return array();
        
    $accountId = $user->account->id;
        
    $select = new Proj_Db_Select($this->context->db);
        
    $select->distinct();
        
    $select->from ('member','member.person_id AS person_id');
        
    $select->where("member.account_id IN (?)",$accountId);
        
    $rows = $this->context->db->fetchAll($select);
        
    $items = array();
    foreach($rows as $row)
    {
      $items[$row['person_id']] = $row['person_id'];
    }        
    return $items;
  }
  function getCerts($user)
  {
    if (!$user->isPerson) return '';

    $aysoid = $user->person->aysoid;
    if (!$aysoid) return '';

    $sql = 'SELECT * FROM eayso.reg_view_user WHERE reg_numx = :aysoid;';
    $rows = $this->context->db->fetchAll($sql,array('aysoid' => $aysoid));
      
    $certs = NULL;
    $repo = new Eayso_Reg_Cert_RegCertRepo();
    $year = 0;

    foreach($rows as $row)
    {
      $cert = $repo->getDesc($row['cert_type']);
      if ($certs) $certs .= ', ' . $cert;
      else        $certs  =        $cert;

      $yearx = (int)$row['reg_year'];
      if ($yearx > $year) $year = $yearx;
    }
    $certs = 'MY' . $year . ', ' . $certs;
    return $certs;  
  }
}
?>
