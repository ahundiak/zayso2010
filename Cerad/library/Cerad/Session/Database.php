<?php
class Cerad_Session_Database extends Cerad_Session_Base
{
  protected $sessionId;
  protected $db;

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
    // Get the session id
    $sessionId = $this->sessionId;
    if (!$sessionId) return NULL;

    // Load the record
    $search = array('session_id' => $sessionId,'name' => $name);
    $sql = 'SELECT item FROM session_data WHERE keyx = :session_id AND name = :name;';
    $row = $this->db->fetchRow($sql,$search);
    if ($row === FALSE) return NULL;

    // Back to object
    $item = unserialize($row['item']);
    return $item;
  }
  public function set($name,$item)
  {
    // Get the session id creating one of needed
    if (!$this->sessionId)
    {
      $this->sessionId = $this->genSessionId();
      setcookie($this->sessionCookieName,$this->sessionId,$this->sessionCookieLifetime);
    }
    $sessionId = $this->sessionId;

    // Delete if null
    if (!$item)
    {
      $params = array('keyx' => $sessionId,'name' => $name);
      $sql = 'DELETE FROM session_data WHERE keyx = :keyx AND name = :name;';
      $this->db->execute($sql,$params);
      return;
    }
    // Assume an update
    $params = array
    (
      'keyx' => $sessionId,
      'name' => $name,
      'item' => serialize($item),
      'ts_created' => $this->ts,
      'ts_updated' => $this->ts,
    );
    $sql = <<<EOT
INSERT INTO session_data (keyx,name,item,ts_created,ts_updated)

VALUES(:keyx,:name,:item,:ts_created,:ts_updated)

ON DUPLICATE KEY UPDATE item = VALUES(item), ts_updated = VALUES(ts_updated);
EOT;
    $count = $this->db->execute($sql,$params);
    
  }
}

?>
