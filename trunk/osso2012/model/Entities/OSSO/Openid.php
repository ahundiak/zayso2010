<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.openid",schema="osso2007")
 */
class Openid
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string",name="display_name")
     */
    private $displayName; // type defaults to string

    /**
     * @Column(type="string",name="user_name")
     */
    private $userName; // type defaults to string

    public function getId() { return $this->id; }
    public function getDisplayName() { return $this->displayName; }
    public function getUserName()    { return $this->userName; }
}

?>
