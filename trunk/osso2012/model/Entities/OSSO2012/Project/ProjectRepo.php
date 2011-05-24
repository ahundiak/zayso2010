<?php

namespace OSSO2012\Project;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/* =========================================================================
 * The repository
 */
class ProjectRepo extends EntityRepository
{
  protected $types    = array();
  protected $fields   = array();
  protected $classes  = array();
  protected $statuses = array();

  // Gets the next seqn for a given project
  public function addSeqn($projectId,$key,$seqn = 1000)
  {
    $item = new ProjectSeqnItem();
    $item->setProjectId($projectId);
    $item->setKey1     ($key);
    $item->setSeqn     ($seqn);

    $em = $this->_em;
    $em->persist($item);
    $em->flush();

    return $item;
  }
  public function getSeqnItem($projectId,$key)
  {
    $em = $this->_em;

    $params = array('projectId' => $projectId, 'key1' => $key);

    $item = $em->getRepository('OSSO2012\Project\ProjectSeqnItem')->findOneBy($params);

    return $item;
  }
  public function getNextSeqn($projectId,$key,$count = 1)
  {
    $em = $this->_em;

    $item = $this->getSeqnItem($projectId,$key);

    if (!$item) $item = $this->addSeqn($projectId,$key);

    $seqn = $item->getSeqn();

    $item->setSeqn($seqn + $count);
    $em->persist($item);

    // This needs to be surrounded by try/catch version exception
    $em->flush();

    return $seqn + 1;
  }
  // Really just want a partial class, use dql
  public function getClassForKey($key)
  {
    if (isset($this->classes[$key])) return $this->classes[$key];

    // $entity = $this->_em->getRepository('OSSO2012\Event\EventClassItem')->findOneBy($search);

    $dql = <<<EOT
SELECT partial class.{id,key1,desc1}
FROM \OSSO2012\Event\EventClassItem class
WHERE class.key1 = :key1
EOT;
    $params = array('key1' => $key);

    //$entities = $this->_em->createQuery($dql)->execute($params);

    //if (count($entities) == 1) $entity = $entities[0];
    //else                       $entity = NULL;

    $entity = $this->_em->getRepository('OSSO2012\Event\EventClassItem')->findOneBy($params);

    $this->classes[$key] = $entity;
    return $entity;
  }
  // ====================================================================
  // Really just want a partial class, use dql
  public function getTypeForKey($key)
  {
    if (isset($this->types[$key])) return $this->types[$key];

    $params = array('key1' => $key);

    $entity = $this->_em->getRepository('OSSO2012\Event\EventTypeItem')->findOneBy($params);

    $this->types[$key] = $entity;
    return $entity;
  }
  // ====================================================================
  // Really just want a partial class, use dql
  public function getStatusForKey($key)
  {
    if (isset($this->statuses[$key])) return $this->statuses[$key];

    $params = array('key1' => $key);

    $entity = $this->_em->getRepository('OSSO2012\Event\EventStatusItem')->findOneBy($params);

    $this->statuses[$key] = $entity;
    return $entity;
  }
  // ====================================================================
  // Really just want a partial class, use dql
  public function getFieldForKey($key)
  {
    if (isset($this->fields[$key])) return $this->fields[$key];

    $params = array('key1' => $key);

    $entity = $this->_em->getRepository('OSSO2012\Event\EventFieldItem')->findOneBy($params);

    $this->fields[$key] = $entity;
    return $entity;
  }
  /* =====================================================================
   * Returns a new initialized event
   */
  public function newEvent()
  {
    $event = new EventItem();

    $class = $this->getClassForKey('RG');
    $event->setClass($class);

    $type = $this->getTypeForKey('Game');
    $event->setType($type);

    $status = $this->getStatusForKey('Normal');
    $event->setStatus($status);

    $team = $this->newEventTeam();
    $team->setType('Home');
    $team->setIndex(1);
    $event->addTeam($team);

    $team = $this->newEventTeam();
    $team->setType('Away');
    $team->setIndex(2);
    $event->addTeam($team);

    return $event;
  }
  public function newEventTeam()
  {
    $team = new EventTeamItem();
    return $team;
  }
}
?>
