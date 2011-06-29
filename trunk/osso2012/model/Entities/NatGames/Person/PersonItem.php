<?php

namespace NatGames\Person;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="NatGames\Person\PersonRepo")
 * @Table(name="natgames.person")
 */
class PersonItem extends \NatGames\EntityItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  protected $_id;

  /** @Column(type="string",name="fname") */
  protected $_fname = '';

  /** @Column(type="string",name="lname") */
  protected $_lname = '';

  /** @Column(type="string",name="nname") */
  protected $_nname = '';

  /** @Column(type="string",name="email") */
  protected $_email = '';

  /** @Column(type="string",name="phonec") */
  protected $_phonec = '';

  /** @Column(type="string",name="verified") */
  protected $_verified = '';

  /** @Column(type="string",name="status") */
  protected $_status = '';

  /** @Column(type="string",name="org_key") */
  protected $_orgKey = '';

  /**
   * @OneToMany(targetEntity="NatGames\Person\PersonRegItem", mappedBy="_person", cascade={"persist","delete"})
   */
  protected $_regs;

  /**
   * @OneToMany(targetEntity="NatGames\Account\AccountPersonItem", mappedBy="_person", cascade={"persist","delete"})
   */
  protected $_members;

  /**
   * @OneToMany(targetEntity="NatGames\Project\ProjectPersonItem", mappedBy="_person")
   */
  protected $_projects;
  
  public function __construct()
  {
    $this->_regs     = new ArrayCollection();
    $this->_members  = new ArrayCollection();
    $this->_projects = new ArrayCollection();
  }
  public function __get($name)
  {
    switch($name)
    {
      case 'aysoid': return $this->getAysoid(); break;
    }
    return parent::__get($name);
  }
  public function addReg($reg)
  {
    $this->_regs[$reg->regType] = $reg;
  }
  public function getAysoid()
  {
    // die('Count: ' . count($this->_regs));

    // Should be able to use that key stuff
    foreach($this->_regs as $reg)
    {
      if ($reg->regType == 'AYSOV')
      {
        return substr($reg->regKey,-8);
      }
    }
    return null;
  }
}

?>
