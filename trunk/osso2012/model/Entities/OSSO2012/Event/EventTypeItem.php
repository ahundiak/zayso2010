<?php

namespace OSSO2012\Event;

/**
 * @Entity
 * @Table(name="osso2012.event_type")
 */
class EventTypeItem
{
    /**
     * @Id
     * @Column(type="integer",name="id")
     * @GeneratedValue
     */
    public $id;

    /** @Column(type="string",name="key1") */
    private $key1;
    
    /** * @Column(type="integer",name="sort1") */
    private $sort1;

    /** @Column(type="string",name="desc1") */
    private $desc1;

    public function getId()     { return $this->id; }
    public function getKey1 ()  { return $this->key1; }
    public function getDesc1()  { return $this->desc1; }

}

?>
