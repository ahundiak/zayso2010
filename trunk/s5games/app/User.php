<?php
class User
{
  protected $context = NULL;
  protected $data    = NULL;
  protected $vol     = NULL;

  public function __construct($context)
  {
    $this->context = $context;
  }
  public function loadEayso($aysoid)
  {
    $sql = 'SELECT * from eayso_vols WHERE aysoid = :aysoid;';
    $params = array('aysoid' => $aysoid);
    $db  = $this->context->dbEayso;
    $row = $db->fetchRow($sql,$params);
  //Cerad_Debug::dump($row); die();
    $this->vol = $row;

    if (!$row) return FALSE;

    return TRUE;
  }
  protected function getIsInEayso()
  {
    if ($this->vol) return TRUE;
    return FALSE;
  }
  protected function getIsAuth()
  {
    if ($this->context->session->get('user_is_auth')) return TRUE;
    
    return FALSE;
  }
  protected function getIsAdmin()
  {
    if (!$this->isAuth) return FALSE;
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
    if ($this->isAdmin) return TRUE;

    if (!$this->isAuth) return FALSE;
    if (!$this->vol)    return FALSE;
    if ( $this->vol['mem_year'] < 'FS2009') return FALSE;

    return TRUE;
  }
  public function __get($name)
  {
    if (isset($this->vol[$name])) return $this->vol[$name];

    switch($name)
    {
      case 'desc':      return $this->getDesc();      break;
      case 'isAuth':    return $this->getIsAuth();    break;
      case 'isAdmin':   return $this->getIsAdmin();   break;
      case 'isInEayso': return $this->getIsInEayso(); break;
      case 'isReferee': return $this->getIsAuth();    break;

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
    $vol = $this->vol;
    if (!$vol) return 'Guest';

    if ($vol['nname']) $fname = $vol['nname'];
    else               $fname = $vol['fname'];

    $desc = $vol['mem_year'] . ' ' . $vol['region'] . ' ' . $fname . ' ' . $vol['lname'];

    if (!$this->isAuth)    $desc .= '(Not logged in)';
    if ( $this->isReferee) $desc .= '(Referee)';
    if ( $this->isAdmin)   $desc .= '(Admin)';
    
    return $desc;
  }
}
?>