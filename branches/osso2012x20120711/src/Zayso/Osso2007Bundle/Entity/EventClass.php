<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventClass
 *
 * @ORM\Table(name="event_class")
 * @ORM\Entity
 */
class EventClass
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}