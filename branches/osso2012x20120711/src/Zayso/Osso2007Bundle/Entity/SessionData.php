<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\SessionData
 *
 * @ORM\Table(name="session_data")
 * @ORM\Entity
 */
class SessionData
{
    /**
     * @var string $sessionId
     *
     * @ORM\Column(name="session_id", type="string", length=32, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sessionId;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $name;

    /**
     * @var string $data
     *
     * @ORM\Column(name="data", type="string", length=2000, nullable=true)
     */
    private $data;



    /**
     * Set data
     *
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set sessionId
     *
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}