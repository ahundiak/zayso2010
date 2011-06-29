<?php

namespace NatGames\Person;

/**
 * @Entity
 * @Table(name="natgames.person_reg")
 */
class PersonRegItem extends \NatGames\EntityItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  protected $_id;

  /**
   * @ManyToOne(targetEntity="NatGames\Person\PersonItem", inversedBy="_regs")
   * @JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $_person;

  /** @Column(type="string",name="reg_type") */
  protected $_regType = '';

  /** @Column(type="string",name="reg_key") */
  protected $_regKey = '';

  /** @Column(type="string",name="verified") */
  protected $_verified = '';

}

?>
