<?php
namespace NatGames\Session;

/**
 * @Entity(repositoryClass="NatGames\Session\SessionRepo")
 * @Table(name="natgames.session_data")
 * @HasLifecycleCallbacks
 */
class SessionDataItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  private $id;

  /** @Column(type="string",name="app") */
  private $app;

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

  /** @PrePersist @PreUpdate */
  public function onPreSave()
  {
    $this->item = serialize($this->data);
  }
  /** @PostLoad */
  public function onPostLoad()
  {
    $this->data = unserialize($this->item);
  }

  public function setApp      ($value) { $this->app  = $value; }
  public function setName     ($value) { $this->name = $value; }
  public function setSessionId($value) { $this->key  = $value; }
  public function setTsCreated($value) { $this->ts_created = $value; }
  public function setTsUpdated($value) { $this->ts_updated = $value; }

  public function getId()        { return $this->id; }
  public function getSessionId() { return $this->key; }
  public function getApp()       { return $this->app; }
  public function getName()      { return $this->name; }
  public function getCreated()   { return $this->ts_created; }
  public function getUpdated()   { return $this->ts_updated; }

  protected $data = array();

  //public function saveData()  { $this->item = serialize  ($this->data); } // Only flat data for now?
  //public function loadData()  { $this->data = unserialize($this->item); }

  public function setData($data = array()) { $this->data = $data; }
  public function getData()                { return $this->data; }

  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];
    return null;
  }
  public function __set($name,$value)
  {
    $this->data[$name] = $value;
    $this->item = null;
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
?>
