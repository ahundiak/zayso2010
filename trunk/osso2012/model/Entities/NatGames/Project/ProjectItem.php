<?php

namespace NatGames\Project;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="NatGames\Project\ProjectRepo")
 * @Table(name="natgames.project")
 */
class ProjectItem extends \NatGames\EntityItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  protected $_id;

  /** @Column(type="string",name="desc1") */
  protected $_desc1 = '';

  /** @Column(type="string",name="status") */
  protected $_status = '';

  /**
   * @OneToMany(targetEntity="NatGames\Project\ProjectPersonItem", mappedBy="_project")
   */
  protected $_persons;

  public function __construct()
  {
    $this->_persons = new ArrayCollection();
  }

}

?>
