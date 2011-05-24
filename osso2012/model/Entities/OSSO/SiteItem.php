<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.field_site",schema="osso2007")
 */
class SiteItem
{
    /**
     * @Id
     * @Column(type="integer",name="field_site_id")
     * @GeneratedValue
     */
    private $id;

    /** * @Column(type="integer",name="unit_id") */
    private $orgId;

    /** @Column(type="string",name="descx") */
    private $desc;

    public function getId()     { return $this->id; }
    public function getDesc()   { return $this->desc; }
}

?>
