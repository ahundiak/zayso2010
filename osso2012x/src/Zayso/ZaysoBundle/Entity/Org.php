<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="org")
 */
class Org
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string",name="id")
     *  ORM\GeneratedValue
     *  GUID
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Org")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent = null;

    /** @ORM\Column(type="string",name="desc1",nullable=true) */
    protected $desc1 = '';

    /** @ORM\Column(type="string",name="desc2",nullable=true) */
    protected $desc2 = '';

    /** @ORM\Column(type="string",name="city",nullable=true) */
    protected $city = '';

    /** @ORM\Column(type="string",name="state",nullable=true) */
    protected $state = '';

    /** @ORM\Column(type="string",name="status") */
    protected $status = 'Active';

    /** @ORM\Column(type="text",name="datax") */
    protected $datax = '';
    protected $data = array();

    /** @ORM\PrePersist */
    public function onPrePersist() { $this->datax = serialize($this->data); }

    /** @ORM\PreUpdate */
    public function onPreUpdate()  { $this->datax = serialize($this->data); }

    /** @ORM\PostLoad */
    public function onLoad()       { $this->data = unserialize($this->datax); }

    public function get($name)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return null;
    }
    public function set($name,$value)
    {
        if ($value === null)
        {
            if (isset($this->data[$name])) unset($this->data[$name]);
            $this->datax = null;
            return;
        }
        if (isset($this->data[$name]) && $this->data[$name] == $value) return;

        $this->data[$name] = $value;
        $this->datax = null;
    }

    public function __construct()
    {
    }
    /* ============================================================
     * Generated code follows
     */

    /**
     * Set id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set desc1
     *
     * @param string $desc1
     */
    public function setDesc1($desc1)
    {
        $this->desc1 = $desc1;
    }

    /**
     * Get desc1
     *
     * @return string 
     */
    public function getDesc1()
    {
        return $this->desc1;
    }

    /**
     * Set desc2
     *
     * @param string $desc2
     */
    public function setDesc2($desc2)
    {
        $this->desc2 = $desc2;
    }

    /**
     * Get desc2
     *
     * @return string 
     */
    public function getDesc2()
    {
        return $this->desc2;
    }

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set parent
     *
     * @param Zayso\ZaysoBundle\Entity\Org $parent
     */
    public function setParent(\Zayso\ZaysoBundle\Entity\Org $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Zayso\ZaysoBundle\Entity\Org 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}