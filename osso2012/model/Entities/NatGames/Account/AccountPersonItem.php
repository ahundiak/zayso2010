<?php

namespace NatGames\Account;

/**
 * @Entity
 * @Table(name="natgames.account_person")
 */
class AccountPersonItem extends \NatGames\EntityItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  protected $_id;

  /** @Column(type="integer",name="rel_id") */
  protected $_relId;
  
  /**
   * @ManyToOne(targetEntity="NatGames\Person\PersonItem", inversedBy="_members")
   * @JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $_person;

  /**
   * @ManyToOne(targetEntity="NatGames\Account\AccountItem", inversedBy="_members")
   * @JoinColumn(name="account_id", referencedColumnName="id")
   */
  protected $_account;

  /** @Column(type="string",name="verified") */
  protected $_verified = '';

  /** @Column(type="string",name="status") */
  protected $_status = '';

  public function __set($name,$value)
  {
    switch($name)
    {
      case 'account': return $this->setAccount($value); break;
    }
    return parent::__set($name,$value);
  }
  public function setAccount($account)
  {
    $this->_account = $account;
    $account->addAccountPerson($this);
  }
}
?>