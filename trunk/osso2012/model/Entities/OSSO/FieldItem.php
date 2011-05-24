<?php

namespace OSSO;

/**
 * @Entity
 * @Table(name="osso2007.field",schema="osso2007")
 */
class FieldItem
{
    /**
     * @Id
     * @Column(type="integer",name="field_id")
     * @GeneratedValue
     */
    public $id;

    /** * @Column(type="integer",name="unit_id") */
    private $orgId;

    /** * @Column(type="integer",name="field_site_id") */
    private $siteId;

    /**
     * @OneToOne(targetEntity="OSSO\SiteItem")
     * @JoinColumn(name="field_site_id", referencedColumnName="field_site_id")
     */
    private $site;

    /** @Column(type="string",name="descx") */
    private $desc;

    public function getId()     { return $this->id; }
    public function getDesc()   { return $this->desc; }
    public function getSiteId() { return $this->siteId; }
    public function getSite()   { return $this->site; }

    public function something()
    {
      
    }
    public function setMe($value) {}
}

?>
