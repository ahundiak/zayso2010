<?php
namespace Session;

/**
 * @Entity(repositoryClass="Session\SessionRepo")
 * @Table(name="session.session_data")
 * @HasLifecycleCallbacks
 */
class SessionDataItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  protected $id;

  /** @Column(type="string",name="sid") */
  protected $sid;

  /** @Column(type="string",name="app") */
  protected $app;

  /** @Column(type="string",name="cat") */
  protected $cat;

  /** @Column(type="string",name="ts_created") */
  protected $tsCreated;

  /** @Column(type="string",name="ts_updated") */
  protected $tsUpdated;

  /** @Column(type="string",name="datax") */
  protected $datax;

  /** @PrePersist @PreUpdate */
  public function onPreSave()
  {
    $this->datax = serialize($this->data);
  }
  /** @PostLoad */
  public function onPostLoad()
  {
    $this->data = unserialize($this->datax);
  }

  protected $data = array();

  public function __get($name)
  {
    if (isset($this->data[$name])) return $this->data[$name];

    switch($name)
    {
      case 'id':        return $this->id;   break;
      case 'sid':       return $this->sid;  break;
      case 'app':       return $this->app;  break;
      case 'cat':       return $this->cat;  break;
      case 'data':      return $this->data; break;
      case 'tsCreated': return $this->tsCreated; break;
      case 'tsUpdated': return $this->tsUpdated; break;
    }
    return null;
  }
  public function __set($name,$value)
  {
    switch($name)
    {
      case 'id':        $this->id        = $value; return;
      case 'sid':       $this->sid       = $value; return;
      case 'app':       $this->app       = $value; return;
      case 'cat':       $this->cat       = $value; return;
      case 'data':      $this->data      = $value; return;
      case 'tsCreated': $this->tsCreated = $value; return;
      case 'tsUpdated': $this->tsUpdated = $value; return;
    }
    $this->data[$name] = $value;
  }
  public function setData($data = array()) { $this->data = $data; }
}
?>
