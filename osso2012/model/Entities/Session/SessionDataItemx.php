<?php

namespace Session;

use Doctrine\ORM\EntityRepository;

/**
 * @Entity(repositoryClass="Session\SessionRepo")
 * @Table(name="session.session_data")
 */
class SessionDataItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  private $id;

  /** @Column(type="string",name="keyx") */
  private $key;

  /** @Column(type="string",name="name") */
  private $name;

  /** @Column(type="string",name="item") */
  private $item;

  /** @Column(type="string",name="ts_created") */
  private $ts_created;

  /** @Column(type="string",name="ts_updated") */
  private $ts_updated;

  public function setSessionId($value) { $this->key  = $value; }
  public function setName     ($value) { $this->name = $value; }
  public function setTsCreated($value) { $this->ts_created = $value; }
  public function setTsUpdated($value) { $this->ts_updated = $value; }

  public function getId()        { return $this->id; }
  public function getSessionId() { return $this->key; }
  public function getName()      { return $this->name; }
  public function getCreated()   { return $this->ts_created; }
  public function getUpdated()   { return $this->ts_updated; }

  protected $data = array();

  public function saveData()  { $this->item = serialize($this->data); }
  public function loadData()  { $this->data = unserialize($this->item); }
  public function setData($data = array()) { $this->data = $data; }

  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];
    return null;
  }
  public function __set($name,$value)
  {
    $this->data[$name] = $value;
  }
  public function __isset($name)
  {
    return isset($this->data[$name]);
  }
  public function isEmpty()
  {
    return (count($this->data)) ? false : true;
  }
}

class SessionRepo extends EntityRepository
{
  protected $sessionId = null;
  protected $ts = null;

  protected $datas = array();

  protected $listener = null;

  protected function getTimeStamp()
  {
    if (!$this->ts) $this->ts = date('YmdHis');
    return $this->ts;
  }
  public function getSessionId()
  {
    if (!$this->sessionId)
    {
      if ($this->listener) $this->sessionId = $this->listener->genSessionId();
      else                 $this->sessionId =  md5(uniqid());
    }
    return $this->sessionId;
  }
  public function setSessionId($sessionId)
  {
    $this->sessionId = $sessionId;
  }
  public function setListener($listener)
  {
    $this->listener = $listener;
  }
  public function load($name)
  {
    if (isset($this->datas[$name])) return $this->datas[$name];

    $sessionId = $this->getSessionId();

    $search = array('key' => $this->sessionId, 'name' => $name);

    $sessionData = $this->findOneby($search);

    if ($sessionData)
    {
      $sessionData->loadData();
    }
    else
    {
      $sessionData = new SessionDataItem();
    //$sessionData->setSessionId($this->sessionId); // Set when saved
      $sessionData->setName($name);
      $sessionData->setTsCreated($this->getTimeStamp());
      $sessionData->setTsUpdated($this->getTimeStamp());
    }

    $this->datas[$name] = $sessionData;

    return $sessionData;
  }
  public function save($sessionData)
  {
    $sessionData->saveData();
    $sessionData->setSessionId($this->getSessionId());
    $sessionData->setTsUpdated($this->getTimeStamp());
    $this->_em->persist($sessionData);
    $this->_em->flush();
  }
  public function search($search)
  {
    $names = array('account-signin');

    $em = $this->_em;
    $qb = $em->createQueryBuilder();
    $qb->addSelect('data');
    $qb->from('\Session\SessionDataItem','data');
    $qb->andWhere($qb->expr()->in('data.name',$names));
    $qb->addOrderBy('data.ts_updated','DESC');

    $query = $qb->getQuery();
    $items = $query->getResult();
    foreach($items as $item)
    {
      $item->loadData();
    }
    return $items;
  }
}
?>
