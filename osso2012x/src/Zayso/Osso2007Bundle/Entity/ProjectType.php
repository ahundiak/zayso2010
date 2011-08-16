<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\ProjectType
 */
class ProjectType
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $key1
     */
    private $key1;

    /**
     * @var string $desc1
     */
    private $desc1;

    /**
     * @var integer $class1
     */
    private $class1;

    /**
     * @var integer $status
     */
    private $status;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

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
}