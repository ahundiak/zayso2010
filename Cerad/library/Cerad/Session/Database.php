<?php
class Cerad_Session_Database extends Cerad_Session_Base
{
  protected $sessionId;
  protected $db;
  protected $ts;
  
  protected $app = 'osso2007';
  
  protected function init()
  {
    parent::init();

    // Grab the session id if it exists
    if (isset($_COOKIE[$this->sessionCookieName]))
    {
      $this->sessionId = $_COOKIE[$this->sessionCookieName];
    }
    $this->db = $this->context->dbSession;
    $this->ts = $this->context->getTimeStamp();

    $this->sessionCookieLifetime = time()+(3600*24*1000);
  }
  public function getSessionId() { return $this->sessionId; }
  
  // Return a new session if
  protected function genSessionId()
  {
    // $sessionId = md5(microtime().rand(1,9999999999999999999999999));
    $sessionId = md5(uniqid());
    return $sessionId;
  }
  // Returns NULL if not found
  public function get($name)
  {
    // die('get ' . $name . ' ' . $this->sessionId);
    
    if (isset($this->data[$name])) return $this->data[$name];

    // Get the session id
    $sessionId = $this->sessionId;
    if (!$sessionId) return NULL;

    // Load the record
    $search = array(
        'sid' => $sessionId,
        'app' => $this->app,
        'cat' => $name);
    
    $sql = 'SELECT datax FROM session_data WHERE sid = :sid AND app = :app AND cat = :cat;';
    $row = $this->db->fetchRow($sql,$search);
    if ($row === FALSE)
    {
      $this->data[$name] = NULL;
      return NULL;
    }

    // Back to object
    $item = unserialize($row['datax']);
    $this->data[$name] = $item;
    return $item;
  }
  public function set($name,$item)
  {
    // Always save in cache
    $this->data[$name] = $item;

    // Get the session id creating one of needed
    if (!$this->sessionId)
    {
      $this->sessionId = $this->genSessionId();
      setcookie($this->sessionCookieName,$this->sessionId,$this->sessionCookieLifetime,$this->sessionCookiePath);
    }
    $sessionId = $this->sessionId;

// die('set ' . $name . ' ' . $sessionId);

    // Delete if null
    if (!$item)
    {
      $params = array(
        'sid' => $sessionId,
        'app' => $this->app,
        'cat' => $name);
      $sql = 'DELETE FROM session_data WHERE sid = :sid AND app = :app AND cat = :cat;';
      $this->db->execute($sql,$params);
      return;
    }
    // Assume an update
    $params = array
    (
      'sid' => $sessionId,
      'app' => $this->app,
      'cat' => $name,
      'datax' => serialize($item),
      'ts_created' => $this->ts,
      'ts_updated' => $this->ts,
    );
    $sql = <<<EOT
INSERT INTO session_data (sid,app,cat,ts_created,ts_updated,datax)

VALUES(:sid,:app,:cat,:ts_created,:ts_updated,:datax)

ON DUPLICATE KEY UPDATE datax = VALUES(datax), ts_updated = VALUES(ts_updated);
EOT;
    $count = $this->db->execute($sql,$params);
    
  }
  public function has($name)
  {
    $item = $this->get($name);
    if ($item) return TRUE;
    return FALSE;
  }
}

?>
