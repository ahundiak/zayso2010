<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.event_person",schema="osso2007")
 */
class EventPersonItem
{
  /**
   * @Id
   * @Column(type="integer",name="event_person_id")
   * @GeneratedValue
   */
  private $id;

  /** * @Column(type="integer",name="event_id") */
  private $eventId;

  /** * @Column(type="integer",name="person_id") */
  // private $personId;

  /**
   * @OneToOne(targetEntity="OSSO\PersonItem")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  private $person;

  /** * @Column(type="integer",name="event_person_type_id") */
  private $typeId;

  public function getId() { return $this->id; }

  public function getTypeId ()  { return $this->typeId; }
  public function getPerson ()  { return $this->person; }
  public function getName()     { return $this->getPerson()->getName(); }
  public function getLastName() { return $this->getPerson()->getLastName(); }
  
  /**
   * @ManyToOne(targetEntity="OSSO\EventItem", inversedBy="persons")
   * @JoinColumn(name="event_id", referencedColumnName="event_id")
   */
  private $event;
}

?>
