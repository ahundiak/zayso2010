<?php
/* =================================================================================
 * For 2.1 this was changed from a storage handler to a session handler
 * Takes advantage of SessionHandlerInterface introduced in php 5.4 and faked in 5.3
 * 
 * Not sure I even used this for the nat games.  Think I juse did get/set instead of get/set Direct
 * Should tweak a bit to get rid of sessions cleared by garbage collection
 */
namespace Zayso\CoreBundle\Session;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

class SessionHandler extends PdoSessionHandler
{
    private $db;
    private $dbOptions;
    
    protected $app;
    
    public function __construct(\PDO $db, array $dbOptions = array(), array $options = array())
    {
        $this->db = $db;
        $this->dbOptions = $dbOptions;
        $this->ts = date('YmdHis');

        if (isset($options['name'])) $this->app = $options['name'];
        else                         $this->app = 'zaysocore2012';

        parent::__construct($db,$dbOptions);
    }
    public function getDirect($key)
    {
        $db = $this->db;

        $params = array
        (
            'sid' => $this->getId(),
            'app' => $this->app,
            'cat' => $key,
        );
        $sql = 'SELECT datax FROM session_data WHERE sid = :sid AND app = :app AND cat = :cat;';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (count($rows) ==1) return unserialize($rows[0]['datax']);

        return null;
    }
    function setDirect($key, $data)
    {
        $db = $this->db;

        // Assume an update
        $params = array
        (
            'sid'   => $this->getId(),
            'app'   => $this->app,
            'cat'   => $key,
            'datax' => serialize($data),
            'ts_created' => $this->ts,
            'ts_updated' => $this->ts,
        );

        // Delete if null
        if (!$data)
        {
            $params = array
            (
                'sid'   => $this->getId(),
                'app'   => $this->app,
                'cat'   => $key,
            );
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
    }
}
?>
