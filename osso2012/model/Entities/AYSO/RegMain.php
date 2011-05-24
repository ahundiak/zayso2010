<?php

namespace AYSO;

/**
 * @Entity
 * @Table(name="eayso.reg_main",schema="eayso")
 */
class RegMain
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /** * @Column(type="integer",name="reg_type") */
    private $regType;

    /** * @Column(type="string",name="reg_num") */
    private $regNum;

    /**
     * @Column(type="string",name="fname")
     */
    private $fname;

    /**
     * @Column(type="string",name="lname")
     */
    private $lname;

    public function getId() { return $this->id; }
    public function getFirstName() { return $this->fname; }
    public function getLastName()  { return $this->lname; }
}

?>
