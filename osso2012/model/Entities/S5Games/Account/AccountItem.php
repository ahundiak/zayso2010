<?php

namespace S5Games\Account;

/**
 * @Entity(repositoryClass="S5Games\Account\AccountRepo")
 * @Table(name="s5games.accounts")
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
  private $upass  = '';
  private $upass2 = '';

  /** @Column(type="string",name="fname") */
  private $fname = '';

  /** @Column(type="string",name="lname") */
  private $lname = '';

  /** @Column(type="string",name="email") */
  private $email = '';

  /** @Column(type="string",name="phonec") */
  private $phonec = '';

  /** @Column(type="string",name="aysoid") */
  private $aysoid = '';

  /** @Column(type="string",name="verified") */
  private $verified = '';

  private $errors = array();

  public function setId       ($value) { $this->id     = $value; }
  public function setUserName ($value) { $this->uname  = $value; }
  public function setUserPass ($value) { $this->upass  = $value; }
  public function setUserPass2($value) { $this->upass2 = $value; }

  public function setFirstName($value) { $this->fname = $value; }
  public function setLastName($value)  { $this->lname = $value; }

  public function setEmail($value)     { $this->email = $value; }
  public function setCellPhone($value) { $this->phonec = $value; }

  public function setAysoid($value)   { $this->aysoid   = $value; }
  public function setVerified($value) { $this->verified = $value; }

  public function setErrors($value) { $this->errors = $value; }

  public function getId()    { return $this->id; }

  public function getUserName()  { return $this->uname; }
  public function getUserPass()  { return $this->upass; }

}
class AccountItemx
{
  protected $item;

  public $pass2;
  public $errors;

  protected $map = array(
      'id'       => 'Id',
      'userName' => 'UserName'
  );
  public function __construct()
  {
    $this->account = new AccountItem;
  }
  public function __get($name)
  {
    if (isset($this->map[$name]))
    {
      $method = 'get' . $this->map[$name];
      return $this->item->$method();
    }
    return null;
  }
  public function __set($name,$value)
  {
    if (isset($this->map[$name]))
    {
      $method = 'set' . $this->map[$name];
      return $this->item->$method($value);
    }
    return null;
  }
}
?>
