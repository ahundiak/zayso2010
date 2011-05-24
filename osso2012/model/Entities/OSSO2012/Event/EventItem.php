<?php

namespace OSSO2012\Event;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="OSSO2012\Event\EventRepo")
 * @Table(name="osso2012.event")
 */
class EventItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  public $id;

  /**
   * @OneToOne(targetEntity="OSSO2012\Event\EventTypeItem")
   * @JoinColumn(name="type_id", referencedColumnName="id")
   */
  private $type;

  /**
   * @OneToOne(targetEntity="OSSO2012\Event\EventFieldItem")
   * @JoinColumn(name="field_id", referencedColumnName="id")
   */
  private $field;

  /**
   * @OneToOne(targetEntity="OSSO2012\Event\EventClassItem")
   * @JoinColumn(name="class_id", referencedColumnName="id")
   */
  private $class;

  /**
   * @OneToOne(targetEntity="OSSO2012\Event\EventStatusItem")
   * @JoinColumn(name="status_id", referencedColumnName="id")
   */
  private $status;

  /**
   * @Column(type="datetime",name="dt_beg")
   */
  private $dtBeg;

  /**
   * @Column(type="datetime",name="dt_end")
   */
  private $dtEnd;

  /**
   * @OneToMany(targetEntity="OSSO2012\Event\EventTeamItem", mappedBy="event", cascade={"persist", "remove"})
   */
  private $teams;

  public function __construct()
  {
    $this->teams = new ArrayCollection();
  }

  public function getId()     { return $this->id;     }
  public function getType()   { return $this->type;   }
  public function getField()  { return $this->field;  }
  public function getClass()  { return $this->class;  }
  public function getStatus() { return $this->status; }

  public function getDtBeg() { return $this->dtBeg; }
  public function getDtEnd() { return $this->dtEnd; }

  public function setType  ($value) { $this->type   = $value; }
  public function setField ($value) { $this->field  = $value; }
  public function setClass ($value) { $this->class  = $value; }
  public function setStatus($value) { $this->status = $value; }

  public function setDtBeg($value) { $this->dtBeg = $value; }
  public function setDtEnd($value) { $this->dtEnd = $value; }

  public function addTeam($team) 
  {
    $team->setEvent($this);
    $this->teams[] = $team;
  }
}
/* =========================================================================
 * The repository
 */
class EventRepo extends EntityRepository
{
  protected $types    = array();
  protected $fields   = array();
  protected $classes  = array();
  protected $statuses = array();

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
