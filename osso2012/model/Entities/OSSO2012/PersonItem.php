<?php

namespace OSSO2012;

/**
 * @Entity
 * @Table(name="osso2012.persons")
 */
class PersonItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  private $id;

  /** @Column(type="string",name="fname") */
  private $fname = '';

  /** @Column(type="string",name="lname") */
  private $lname = '';

  /** @Column(type="string",name="mname") */
  private $mname = '';

  /** @Column(type="string",name="nname") */
  private $nname = '';

  /** @Column(type="string",name="guid") */
  private $guid = '';

  /**
   * @OneToMany(targetEntity="OSSO2012\AccountPersonItem", mappedBy="person")
   */
  private $accounts;

  public function setFirstName($value) { $this->fname = $value; }
  public function setLastName ($value) { $this->lname = $value; }
  public function setNickName ($value) { $this->nname = $value; }
  public function setGuid     ($value) { $this->guid  = $value; }

  public function getId()        { return $this->id; }
  public function getFirstName() { return $this->fname; }
  public function getLastName()  { return $this->lname; }
  public function getNickName()  { return $this->nname; }
  public function getAysoid()    { return $this->guid; }

  public function getName()
  {
    $fname = $this->getFirstName();
    $lname = $this->getLastName();
    return $fname . ' ' . $lname;
  }
}

?>
