<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.person",schema="osso2007")
 */
class PersonItem
{
    /**
     * @Id
     * @Column(type="integer",name="person_id")
     * @GeneratedValue
     */
    private $id;


    /** @Column(type="string",name="fname") */
    private $fname;

    /** @Column(type="string",name="lname") */
    private $lname;

    /** @Column(type="string",name="nname") */
    private $nname;

    /** @Column(type="string",name="aysoid") */
    private $aysoid;

    public function getId() { return $this->id; }
    public function getFirstName() { return $this->fname; }
    public function getLastName()  { return $this->lname; }
    public function getNickName()  { return $this->nname; }
    public function getAysoid()    { return $this->aysoid; }

    public function getName()
    {
      $fname = $this->getFirstName();
      $lname = $this->getLastName();
      return $fname . ' ' . $lname;
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
