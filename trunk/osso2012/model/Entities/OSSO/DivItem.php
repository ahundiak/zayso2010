<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.division",schema="osso2007")
 */
class DivItem
{
    /**
     * @Id
     * @Column(type="integer",name="division_id")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="string",name="desc_pick") */
    private $desc1;

    /** @Column(type="string",name="desc_long") */
    private $desc2;

    public function getId()     { return $this->id; }
    public function getDesc1()  { return $this->desc1; }
    public function getDesc2()  { return $this->desc2; }
}

?>
