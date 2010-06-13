<?php
class User
{
  protected $context = NULL;
  protected $data    = NULL;
  protected $vol     = NULL;
  protected $certs   = NULL;

  public $isAuth    = false;
  public $isAdmin   = false;
  public $isReferee = false;
  public $isInEayso = false;

  public function __construct($context)
  {
    $this->context = $context;
  }
  public function loadEayso($aysoid)
  {
    // Basic vol info
    $sql = 'SELECT * from eayso_vols WHERE aysoid = :aysoid;';
    $params = array('aysoid' => $aysoid);
    $db  = $this->context->dbEayso;
    $row = $db->fetchRow($sql,$params);
  //Cerad_Debug::dump($row); die();
    $this->vol = $row;
    
    if (!$row) return FALSE;

    $this->isInEayso = true;
    $this->isAdmin = $this->getIsAdmin();

    // Pull in certifications
    $sql = 'SELECT * from eayso_vol_certs WHERE aysoid = :aysoid;';
    $params = array('aysoid' => $aysoid);
    $db  = $this->context->dbEayso;
    $rows = $db->fetchRows($sql,$params);

    $this->certs = $rows;
    $this->isReferee = $this->getIsReferee();
    
    return TRUE;
  }
  protected function getIsInEayso()
  {
    if ($this->vol) return TRUE;
    return FALSE;
  }
  protected function getIsAuthx()
  {
    if ($this->context->session->get('user_is_auth')) return TRUE;
    
    return FALSE;
  }
  protected function getIsAdmin()
  {
  //if (!$this->isAuth) return FALSE;
    if (!$this->vol)    return FALSE;

    $aysoid = $this->vol['aysoid'];
    switch($aysoid)
    {
      case '99437977':
        return TRUE;
      break;
    }
    return FALSE;
  }
  protected function getIsReferee()
  {
    if (!$this->vol)    return FALSE;
    if ( $this->vol['mem_year'] < 'FS2009') return FALSE;

    $badge = $this->getRefereeBadgeDesc();
    if (!$badge) return FALSE;
    
    return TRUE;
  }
  public function getRefereeBadgeDesc()
  {
    foreach($this->certs as $cert)
    {
      if ($cert['cert_cat'] == 200) return $cert['cert_desc'];
    }
    return NULL;
  }
  public function __get($name)
  {
    if (isset($this->vol[$name])) return $this->vol[$name];

    switch($name)
    {
      case 'desc':      return $this->getDesc();      break;
      case 'isAuthx':    return $this->getIsAuth();    break;
      case 'isAdminx':   return $this->getIsAdmin();   break;
      case 'isInEaysox': return $this->getIsInEayso(); break;
      case 'isRefereex': return $this->getIsAuth();    break;

      case 'fnamex':
        $nname = $this->nname;
        if ($nname) return $nname;
        return $this->fname;
        break;
    }
    return NULL;
  }
  protected function getDesc()
  {
    if (!$this->isAuth) return 'Guest';

    $vol = $this->vol;
    if (!$vol) return 'Guest';

    if ($vol['nname']) $fname = $vol['nname'];
    else               $fname = $vol['fname'];

    $desc = $vol['mem_year'] . ' ' . $vol['region'] . ' ' . $fname . ' ' . $vol['lname'];

    if ( $this->isReferee) 
    {
      $badge = $this->getRefereeBadgeDesc();
      $desc .= " ($badge)";
    }
    else $desc .= ' (NOT a certified AYSO referee)';
    
    if ( $this->isAdmin)   $desc .= '(Admin)';
    
    return $desc;
  }
}
?>