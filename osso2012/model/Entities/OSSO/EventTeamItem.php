<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.event_team",schema="osso2007")
 */
class EventTeamItem
{
  /**
   * @Id
   * @Column(type="integer",name="event_team_id")
   * @GeneratedValue
   */
  private $id;

  /** * @Column(type="integer",name="event_id") */
  private $eventId;
  
  /**
   * @ManyToOne(targetEntity="OSSO\EventItem", inversedBy="teams")
   * @JoinColumn(name="event_id", referencedColumnName="event_id")
   *
   * Do I really need a reference back to the event here?
   */
  private $event;

  /** * @Column(type="integer",name="team_id") */
  private $schTeamId;

  /**
   * Putting eager triggers individual queries when event team is accessed
   * @OneToOne(targetEntity="OSSO\SchTeamItem",fetch="LAZY")
   * @JoinColumn(name="team_id", referencedColumnName="sch_team_id")
   */
  private $schTeam;

  /** * @Column(type="integer",name="event_team_type_id") */
  private $typeId;

  /** * @Column(type="integer",name="type_index") */
  private $typeIndex;

  /** * @Column(type="integer",name="division_id") */
  private $divId;

  /**
   * @OneToOne(targetEntity="OSSO\DivItem")
   * @JoinColumn(name="division_id", referencedColumnName="division_id")
   */
  private $div;

  public function getId() { return $this->id; }
  public function getTypeId () { return $this->typeId; }
  public function getDivId  () { return $this->divId;  }
  public function getDiv    () { return $this->div;    }
  public function getDivDesc() { return $this->getDiv()->getDesc1();  }

  public function getSchTeam()   { return $this->schTeam; }
  public function getSchTeamId() { return $this->schTeamId; }

  public function getDesc() { return $this->getSchTeam()->getDesc(); }
}

?>
