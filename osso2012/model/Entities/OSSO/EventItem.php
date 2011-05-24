<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.event",schema="osso2007")
 */
class EventItem
{
  /**
   * @Id
   * @Column(type="integer",name="event_id")
   * @GeneratedValue
   */
  private $id;

  /** * @Column(type="integer",name="event_num") */
  private $num;

  /** * @Column(type="integer",name="project_id") */
  private $projectId;

  /** * @Column(type="integer",name="field_id") */
  private $fieldId;

  /**
   * @OneToOne(targetEntity="OSSO\FieldItem")
   * @JoinColumn(name="field_id", referencedColumnName="field_id")
   */
  private $field;

  /** @Column(type="string",name="event_date") */
  private $date;

  /** @Column(type="string",name="event_time") */
  private $time;

  public function getId()   { return $this->id; }
  public function getDate() { return $this->date; }
  public function getTime() { return $this->time; }

  public function getProjectId() { return $this->projectId; }
  public function getFieldId()   { return $this->fieldId; }

  public function getField()     { return $this->field; }
  public function getFieldDesc() { return $this->getField()->getDesc(); }
  public function getSiteDesc()  { return $this->getField()->getSite()->getDesc(); }

  /**
   * @OneToMany(targetEntity="OSSO\EventTeamItem", mappedBy="event")
   */
  private $teams;

  /**
   * @OneToMany(targetEntity="OSSO\EventPersonItem", mappedBy="event")
   */
  private $persons;

  public function __construct()
  {
    $this->teams   = new \Doctrine\Common\Collections\ArrayCollection();
    $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
  }
  public function getTeams() { return $this->teams; }
  public function getTeam($typeId)
  {
    $teams = $this->getTeams();
    foreach($teams as $team)
    {
      if ($team->getTypeId() == $typeId) return $team;
    }
    // Build empty team???
    return NULL;
  }
  public function getHomeTeam() { return $this->getTeam(1); }
  public function getAwayTeam() { return $this->getTeam(2); }

  public function getPersons() { return $this->persons; }
}

?>
