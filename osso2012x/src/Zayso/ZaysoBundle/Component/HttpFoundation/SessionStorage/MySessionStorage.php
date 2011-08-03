<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zayso\ZaysoBundle\Component\HttpFoundation\SessionStorage;

use Symfony\Component\HttpFoundation\SessionStorage\NativeSessionStorage;

/**
 * PdoSessionStorage.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Michael Williams <michael.williams@funsational.com>
 */
class MySessionStorage extends NativeSessionStorage
{
  private $ts;
  private $name;
  
  private $db;
  private $dbOptions;
    
  /**
   * Constructor.
   *
   * @param \PDO  $db        A PDO instance
   * @param array $options   An associative array of session options
   * @param array $dbOptions An associative array of DB options
   *
   * @throws \InvalidArgumentException When "db_table" option is not provided
   *
   * @see NativeSessionStorage::__construct()
   * 
   * Keep dbOptions for now just as an example
   */
  public function __construct(\PDO $db, array $options = array(), array $dbOptions = array())
  {    
    $db->setAttribute(\PDO::ATTR_ERRMODE,            \PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);    
    $db->setAttribute(\PDO::ATTR_CASE,               \PDO::CASE_LOWER); 
    $db->setAttribute(\PDO::ATTR_ORACLE_NULLS,       \PDO::NULL_TO_STRING);
    $this->db = $db;
 
    $this->ts = date('YmdHis');
 
    if (isset($options['name'])) $this->name = $options['name'];
    else                         $this->name = 'zayso';
    
    // Parent saves the options
    parent::__construct($options);
  }
  public function read($key, $default = null)
  {
    //die('session.store.read ' . $key);
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
  }
  function write($key, $data)
  {
    $db = $this->db;
    
    // Assume an update
    $params = array
    (
      'sid'   => $this->getId(),
      'app'   => $this->name,
      'cat'   => $key,
      'datax' => serialize($data),
      'ts_created' => $this->ts,
      'ts_updated' => $this->ts,
    );
    
    // Delete if null
    if (!$data)
    {
      $sql = 'DELETE FROM session_data WHERE sid = :sid AND app = :app AND cat = :cat;';
      $stmt = $db->prepare($sql);
      $stmt->execute($params);
      return;
    }
    // Insert or update
    $sql = <<<EOT
INSERT INTO session_data (sid,app,cat,ts_created,ts_updated,datax)

VALUES(:sid,:app,:cat,:ts_created,:ts_updated,:datax)

ON DUPLICATE KEY UPDATE datax = VALUES(datax), ts_updated = VALUES(ts_updated);
EOT;
   $stmt = $db->prepare($sql);
   $stmt->execute($params);

    // print_r($params);
    //die('session.store.write ' . $key . '<br />');      
  }

    /**
     * Starts the session.
     */
    public function start()
    {
        if (self::$sessionStarted) {
            return;
        }

        // use this object as the session handler
        session_set_save_handler(
            array($this, 'sessionOpen'),
            array($this, 'sessionClose'),
            array($this, 'sessionRead'),
            array($this, 'sessionWrite'),
            array($this, 'sessionDestroy'),
            array($this, 'sessionGC')
        );

        parent::start();
    }

    /**
     * Opens a session.
     *
     * @param  string $path  (ignored)
     * @param  string $name  (ignored)
     *
     * @return Boolean true, if the session was opened, otherwise an exception is thrown
     */
    public function sessionOpen($path = null, $name = null)
    {
        return true;
    }

    /**
     * Closes a session.
     *
     * @return Boolean true, if the session was closed, otherwise false
     */
    public function sessionClose()
    {
        // do nothing
        return true;
    }

    /**
     * Destroys a session.
     *
     * @param  string $id  A session ID
     *
     * @return Boolean   true, if the session was destroyed, otherwise an exception is thrown
     *
     * @throws \RuntimeException If the session cannot be destroyed
     */
    public function sessionDestroy($id)
    {
      // Cleanup later
      if (1) return true;
      
        // get table/column
        $dbTable  = $this->dbOptions['db_table'];
        $dbIdCol = $this->dbOptions['db_id_col'];

        // delete the record associated with this id
        $sql = "DELETE FROM $dbTable WHERE $dbIdCol = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \RuntimeException(sprintf('PDOException was thrown when trying to manipulate session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * Cleans up old sessions.
     *
     * @param  int $lifetime  The lifetime of a session
     *
     * @return Boolean true, if old sessions have been cleaned, otherwise an exception is thrown
     *
     * @throws \RuntimeException If any old sessions cannot be cleaned
     */
    public function sessionGC($lifetime)
    {
      if (1) return true;
      
        // get table/column
        $dbTable    = $this->dbOptions['db_table'];
        $dbTimeCol = $this->dbOptions['db_time_col'];

        // delete the record associated with this id
        $sql = "DELETE FROM $dbTable WHERE $dbTimeCol < (:time - $lifetime)";

        try {
            $this->db->query($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \RuntimeException(sprintf('PDOException was thrown when trying to manipulate session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * Reads a session.
     *
     * @param  string $id  A session ID
     *
     * @return string      The session data if the session was read or created, otherwise an exception is thrown
     *
     * @throws \RuntimeException If the session cannot be read
     */
    public function sessionRead($id)
    {
      if (1) return '';
      
        // get table/columns
        $dbTable    = $this->dbOptions['db_table'];
        $dbDataCol = $this->dbOptions['db_data_col'];
        $dbIdCol   = $this->dbOptions['db_id_col'];

        try {
            $sql = "SELECT $dbDataCol FROM $dbTable WHERE $dbIdCol = :id";
// die($sql);
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_STR, 255);

            $stmt->execute();
            // it is recommended to use fetchAll so that PDO can close the DB cursor
            // we anyway expect either no rows, or one row with one column. fetchColumn, seems to be buggy #4777
            $sessionRows = $stmt->fetchAll(\PDO::FETCH_NUM);

            if (count($sessionRows) == 1) {
                return $sessionRows[0][0];
            }
die('create new session');
            // session does not exist, create it
            $this->createNewSession($id);

            return '';
        } catch (\PDOException $e) {
            throw new \RuntimeException(sprintf('PDOException was thrown when trying to manipulate session data: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * Writes session data.
     *
     * @param  string $id    A session ID
     * @param  string $data  A serialized chunk of session data
     *
     * @return Boolean true, if the session was written, otherwise an exception is thrown
     *
     * @throws \RuntimeException If the session data cannot be written
     */
    public function sessionWrite($id, $data)
    {
      // Hope this gets called even if SESSION is not used
      // Really should not get here?  Save is called directly
      // 
      if (1) return true;
      
      print_r($data);
      die('sessionWrite');
      
        // get table/column
        $dbTable   = $this->dbOptions['db_table'];
        $dbDataCol = $this->dbOptions['db_data_col'];
        $dbIdCol   = $this->dbOptions['db_id_col'];
        $dbTimeCol = $this->dbOptions['db_time_col'];

        $sql = ('mysql' === $this->db->getAttribute(\PDO::ATTR_DRIVER_NAME))
            ? "INSERT INTO $dbTable ($dbIdCol, $dbDataCol, $dbTimeCol) VALUES (:id, :data, :time) "
              ."ON DUPLICATE KEY UPDATE $dbDataCol = VALUES($dbDataCol), $dbTimeCol = CASE WHEN $dbTimeCol = :time THEN (VALUES($dbTimeCol) + 1) ELSE VALUES($dbTimeCol) END"
            : "UPDATE $dbTable SET $dbDataCol = :data, $dbTimeCol = :time WHERE $dbIdCol = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
            $stmt->bindParam(':data', $data, \PDO::PARAM_STR);
            $stmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $stmt->execute();

            if (!$stmt->rowCount()) {
                // No session exists in the database to update. This happens when we have called
                // session_regenerate_id()
                $this->createNewSession($id, $data);
            }
        } catch (\PDOException $e) {
            throw new \RuntimeException(sprintf('PDOException was thrown when trying to manipulate session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * Creates a new session with the given $id and $data
     *
     * @param string $id
     * @param string $data
     */
    private function createNewSession($id, $data = '')
    {
      if (1) return 1;
      
        // get table/column
        $dbTable    = $this->dbOptions['db_table'];
        $dbDataCol = $this->dbOptions['db_data_col'];
        $dbIdCol   = $this->dbOptions['db_id_col'];
        $dbTimeCol = $this->dbOptions['db_time_col'];

        $sql = "INSERT INTO $dbTable ($dbIdCol, $dbDataCol, $dbTimeCol) VALUES (:id, :data, :time)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_STR);
        $stmt->bindParam(':data', $data, \PDO::PARAM_STR);
        $stmt->bindValue(':time', time(), \PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }
}
