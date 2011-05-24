<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.phy_team",schema="osso2007")
 */
class PhyTeamItem
{
  /**
   * @Id
   * @Column(type="integer",name="phy_team_id")
   * @GeneratedValue
   */
  private $id;

  /** * @Column(type="integer",name="unit_id") */
  private $orgId;

  /**
   * @OneToOne(targetEntity="OSSO\OrgItem")
   * @JoinColumn(name="unit_id", referencedColumnName="unit_id")
   */
  private $org;

  /** * @Column(type="integer",name="division_id") */
  private $divId;

  /**
   * @OneToOne(targetEntity="OSSO\DivItem")
   * @JoinColumn(name="division_id", referencedColumnName="division_id")
   */
  private $div;

  /** * @Column(type="integer",name="division_seq_num") */
  private $divSeqNum;

  /**
   * @OneToMany(targetEntity="OSSO\PhyTeamPersonItem", mappedBy="phyTeam")
   */
  private $persons;

  public function getId()        { return $this->id; }
  public function getOrg()       { return $this->org; }
  public function getDiv()       { return $this->div; }
  public function getDivSeqNum() { return $this->divSeqNum; }

  public function getPersons() { return $this->persons; }
  
  public function __get($name)
  {
    switch($name)
    {
      case 'id': return $this->getId(); break;
    }
  }
}
?>
