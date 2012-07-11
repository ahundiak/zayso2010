<?php
namespace Zayso\ZaysoBundle\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\SessionStorage\PdoSessionStorage;


class MySessionStorage extends PdoSessionStorage
{
    private $db;
    private $dbOptions;
    
    protected $app;
    
    public function __construct(\PDO $db, array $options = array(), array $dbOptions = array())
    {
        $this->db = $db;
        $this->dbOptions = $dbOptions;
        $this->ts = date('YmdHis');

        if (isset($options['name'])) $this->app = $options['name'];
        else                         $this->app = 'zayso';
//print_r($dbOptions); print_r($options);die('ss');
        parent::__construct($db,$options,$dbOptions);
    }
    public function sessionWritex($id, $data)
    {
        die('session.write ' . $id . ' ' . $data);
    }
    public function getDirect($key)
    {
        $db = $this->db; // Might not work because db is private

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
