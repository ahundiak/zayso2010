<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\ProjectType
 *
 * @ORM\Table(name="project_type")
 * @ORM\Entity
 */
class ProjectType
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $key1
     *
     * @ORM\Column(name="key1", type="string", length=10, nullable=false)
     */
    private $key1;

    /**
     * @var string $desc1
     *
     * @ORM\Column(name="desc1", type="string", length=80, nullable=true)
     */
    private $desc1;

    /**
     * @var integer $class1
     *
     * @ORM\Column(name="class1", type="integer", nullable=true)
     */
    private $class1;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;



    /**
     * Set key1
     *
     * @param string $key1
     */
    public function setKey1($key1)
    {
        $this->key1 = $key1;
    }

    /**
     * Get key1
     *
     * @return string 
     */
    public function getKey1()
    {
        return $this->key1;
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
     * Set class1
     *
     * @param integer $class1
     */
    public function setClass1($class1)
    {
        $this->class1 = $class1;
    }

    /**
     * Get class1
     *
     * @return integer 
     */
    public function getClass1()
    {
        return $this->class1;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}