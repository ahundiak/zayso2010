<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.phy_team_person",schema="osso2007")
 */
class PhyTeamPersonItem
{
  /**
   * @Id
   * @Column(type="integer",name="phy_team_person_id")
   * @GeneratedValue
   */
  private $id;

  /** * @Column(type="integer",name="phy_team_id") */
  private $phyTeamId;
  
  /**
   * @ManyToOne(targetEntity="OSSO\PhyTeamItem", inversedBy="persons")
   * @JoinColumn(name="phy_team_id", referencedColumnName="phy_team_id")
   */
  private $phyTeam;

  /** * @Column(type="integer",name="person_id") */
  private $personId;

  /**
   * @OneToOne(targetEntity="OSSO\PersonItem")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  private $person;

  /** * @Column(type="integer",name="vol_type_id") */
  private $typeId;

  public function getId() { return $this->id; }

  public function getTypeId ()  { return $this->typeId; }
  public function getPerson ()  { return $this->person; }
  public function getName()     { return $this->getPerson()->getName(); }
  public function getLastName() { return $this->getPerson()->getLastName(); }
  
}

?>
