<?php
/* ===================================================================
 * This seems to work but I think I can do it by hanging a metaClassListener on it?
 */
namespace Zayso\Osso2007Bundle\Component;

use Doctrine\ORM\EntityManager as EntityManagerBase;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMException;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;

class EntityManager extends EntityManagerBase
{
    protected $map = array
    (
        'eayso' => 'EaysoBundle:Volunteer'
    );
    public function getRepository($entityName)
    {
        if (isset($this->map[$entityName])) $entityName = $this->map[$entityName];
        return parent::getRepository($entityName);
    }
    /**
     * Factory method to create EntityManager instances.
     *
     * @param mixed $conn An array with the connection parameters or an existing
     *      Connection instance.
     * @param Configuration $config The Configuration instance to use.
     * @param EventManager $eventManager The EventManager instance to use.
     * @return EntityManager The created EntityManager.
     */
    /*
     * Needed to copy paste the entire method to allow changing the class
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, ($eventManager ?: new EventManager()));
        } else if ($conn instanceof Connection) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                 throw ORMException::mismatchedEventManager();
            }
        } else {
            throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new EntityManager($conn, $config, $conn->getEventManager());
    }
}

?>
