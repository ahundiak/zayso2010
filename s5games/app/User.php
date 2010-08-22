<?php
class User
{
  protected $context = NULL;
  protected $data    = NULL;
  protected $vol     = NULL;
  protected $certs   = NULL;

  protected $certRepo = NULL;

  public $isAuth    = false;
  public $isAdmin   = false;
  public $isReferee = false;
  public $isInEayso = false;

  public function __construct($context)
  {
    $this->context = $context;
    $this->certRepo = new RegCertRepo($context);
  }
  public function loadEayso($aysoid)
  {
    if (!$aysoid) return FALSE;

    // Basic vol info
    $sql = <<<EOT
SELECT 
  reg_main.*,
  org.keyx AS region_keyx

FROM eayso.reg_main AS reg_main

LEFT JOIN eayso.reg_org AS reg_org ON reg_org.reg_num = reg_main.reg_num
LEFT JOIN osso.org      AS     org ON org.id = reg_org.org_id

WHERE reg_main.reg_num = :aysoid;

EOT;

    $params = array('aysoid' => $aysoid);
    $db  = $this->context->dbEayso;
    $rows= $db->fetchRows($sql,$params);
    if (count($rows)) $row = $rows[0];
    else              $row = NULL;

    if ($row) $row['region'] = (int)substr($row['region_keyx'],1);
    // Cerad_Debug::dump($row); die();
    $this->vol = $row;
    
    if (!$row) return FALSE;

    $this->isInEayso = true;
    $this->isAdmin = $this->getIsAdmin();

    // Pull in certifications
    $sql = 'SELECT * FROM eayso.reg_cert AS reg_cert WHERE reg_cert.reg_num = :aysoid;';
    $params = array('aysoid' => $aysoid);
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
    if (!isset($this->vol['reg_num'])) return FALSE; // Cerad_Debug::dump($this->vol); die();
    $aysoid = $this->vol['reg_num'];
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
    if (!isset($this->vol['reg_year'])) return FALSE;
    if ( $this->vol['reg_year'] < 2009) return FALSE;

    $badge = $this->getRefereeBadgeDesc();
    if (!$badge) return FALSE;
    
    return TRUE;
  }
  public function getRefereeBadgeDesc()
  {
    foreach($this->certs as $cert)
    {
      if ($cert['catx'] == 200)
      {
        $desc = $this->certRepo->getDesc($cert['typex']);
        return $desc;
      }
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

    // $vol['region'] = '';  // Need to add

    $desc = 'MY' . $vol['reg_year'] . ' ' . $vol['region'] . ' ' . $fname . ' ' . $vol['lname'];

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