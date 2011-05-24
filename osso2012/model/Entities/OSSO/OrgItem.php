<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.unit",schema="osso2007")
 */
class OrgItem
{
    /**
     * @Id
     * @Column(type="integer",name="unit_id")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="string",name="keyx") */
    private $key;

    /** @Column(type="string",name="desc_pick") */
    private $desc1;

    /** @Column(type="string",name="desc_long") */
    private $desc2;

    public function getId()     { return $this->id; }
    public function getKey  ()  { return $this->key; }
    public function getDesc1()  { return $this->desc1; }
    public function getDesc2()  { return $this->desc2; }
}

?>
