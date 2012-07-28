<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project_group")
 */
class ProjectGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** @ORM\Column(type="string",length="32",name="keyx",unique=true) */
    protected $key = '';

    /** @ORM\Column(type="string",length="80",name="descx",nullable=true) */
    protected $description = '';

    /** @ORM\Column(type="string",length="20",name="status") */
    protected $status = '';

    public function __construct()
    {
    }

    /* ================================================================================
     * Generated code
     */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setKey($key) { $this->key = $key; }
    public function getKey() { return $this->key; }

     /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

   /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}