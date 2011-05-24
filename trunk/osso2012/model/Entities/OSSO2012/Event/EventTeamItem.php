<?php

namespace OSSO2012\Event;

/**
 * @Entity
 * @Table(name="osso2012.event_team")
 */
class EventTeamItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  public $id;

  /**
   * @ManyToOne(targetEntity="OSSO2012\Event\EventItem", inversedBy="teams")
   * @JoinColumn(name="event_id", referencedColumnName="id")
   */
  private $event;

  /**
   * @Column(type="string",name="typex")
   */
  private $type;

  /**
   * @Column(type="integer",name="indexx")
   */
  private $index = 0;

  /** @Column(type="string",name="league") */
  private $league = 'AYSO';

  /** @Column(type="string",name="gender") */
  private $gender = 'Boys';

  /** @Column(type="string",name="age") */
  private $age = 'U10';

  /** @Column(type="string",name="levelx") */
  private $level = 'Regular';

  public function getId()     { return $this->id;     }
  public function getType()   { return $this->type;   }
  public function getEvent()  { return $this->event;  }
  public function getIndex()  { return $this->index;  }

  public function setType  ($value) { $this->type   = $value; }
  public function setEvent ($value) { $this->event  = $value; }
  public function setIndex ($value) { $this->index  = $value; }

}
?>
