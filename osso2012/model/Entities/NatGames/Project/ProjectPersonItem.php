<?php

namespace NatGames\Project;

/** ==================================================
 * @Entity
 * @Table(name="natgames.project_person")
 * @HasLifecycleCallbacks
 *
 * In one sense it doesn't really make sense to explicitly link this to the project item
 * Really more of a stand alone item like seqn
 * But maybe not.  Try it this way
 */
class ProjectPersonItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  protected $_id;

  /**
   * @ManyToOne(targetEntity="NatGames\Project\ProjectItem", inversedBy="_persons")
   * @JoinColumn(name="project_id", referencedColumnName="id")
   */
  protected $_project;

  /**
   * @ManyToOne(targetEntity="NatGames\Person\PersonItem", inversedBy="_projects")
   * @JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $_person;
  
  /** @Column(type="string",name="datax") */
  protected $_datax = '';

  /** @Column(type="string",name="status") */
  protected $_status = '';

  protected $_data = array();

  /** @PrePersist */
  public function onPrePersist()
  {
    //die('prePersist');
    $this->_datax = serialize($this->_data);
  }
  /** @PreUpdate */
  public function onPreUpdate()
  {
    // die('preUpdate');
    $this->_datax = serialize($this->_data);
  }
  /** @PostLoad */
  public function onLoad()
  {
    $this->_data = unserialize($this->_datax);
  //$this->_datax = null;
  }
  public function __get($name)
  {
    switch($name)
    {
      case 'id':      return $this->id;       break;
      case 'project': return $this->_project; break;
      case 'person':  return $this->_person;  break;
      case 'status':  return $this->_status;  break;
    }
    if (isset($this->_data[$name])) return $this->_data[$name];

    return null;
  }
  public function __set($name,$value)
  {
    switch($name)
    {
      case 'id':      $this->id       = $value; return; break;
      case 'project': $this->_project = $value; return; break;
      case 'person':  $this->_person  = $value; return; break;
      case 'status':  $this->_status  = $value; return; break;
    }
    $this->_data[$name] = $value;
    $this->_datax = null;
  }
}

?>
