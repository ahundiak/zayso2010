<?php
namespace OSSO2012;

/**
 * @Entity
 * @Table(name="osso2012.account_person")
 */
class AccountPersonItem
{
  /**
   * @Id
   * @Column(type="integer")
   * @GeneratedValue
   */
  private $id;

  /**
   * @ManyToOne(targetEntity="OSSO2012\AccountItem", inversedBy="persons")
   * @JoinColumn(name="account_id", referencedColumnName="id")
   */
  private $account;

  /**
   * @ManyToOne(targetEntity="OSSO2012\PersonItem", inversedBy="accounts")
   * @JoinColumn(name="person_id", referencedColumnName="id")
   */
  private $person;

  public function getId()      { return $this->id; }
  public function getAccount() { return $this->account; }
  public function getPerson()  { return $this->person; }

  public function setAccount($item) { $this->account = $item; }
  public function setPerson ($item) { $this->person  = $item; }

}
?>
