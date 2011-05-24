<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.sch_team",schema="osso2007")
 */
class SchTeamItem
{
  /**
   * @Id
   * @Column(type="integer",name="sch_team_id")
   * @GeneratedValue
   */
  private $id;

  /** * @Column(type="integer",name="project_id") */
  private $projectId;

  /** * @Column(type="integer",name="phy_team_id") */
  private $phyTeamId;

  /**
   * @OneToOne(targetEntity="OSSO\PhyTeamItem")
   * @JoinColumn(name="phy_team_id", referencedColumnName="phy_team_id")
   */
  private $phyTeam;

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

  /** * @Column(type="integer",name="schedule_type_id") */
  private $typeId;

  /** @Column(type="string",name="desc_short") */
  private $desc;

  public function getId()      { return $this->id; }
  public function getOrg ()    { return $this->org; }
  public function getDiv ()    { return $this->div; }
  public function getDesc()    { return $this->desc; }

  public function getPhyTeamId()
  {
    return $this->phyTeamId;
  }
  public function getPhyTeam()
  {
    return $this->phyTeam;
  }

  public function __get($name)
  {
    switch($name)
    {
      case 'id': return $this->getId(); break;
    }
  }
}
?>
