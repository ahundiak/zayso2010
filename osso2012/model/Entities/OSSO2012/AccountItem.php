<?php

namespace OSSO2012;

/**
 * @Entity
 * @Table(name="osso2012.accounts")
 */
class AccountItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  private $id;

  /** @Column(type="string",name="uname") */
  private $uname = '';

  /** @Column(type="string",name="upass") */
  private $upass = '';

  /**
   * @OneToMany(targetEntity="OSSO2012\AccountPersonItem", mappedBy="account")
   */
  private $persons;

  public function setName($value) { $this->uname = $value; }
  public function setPass($value) { $this->upass = $value; }

  public function getId()    { return $this->id; }
  public function getName()  { return $this->fname; }
  public function getPass()  { return $this->lname; }

}

?>
