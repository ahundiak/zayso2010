<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\SessionData
 */
class SessionData
{
    /**
     * @var string $sessionId
     */
    private $sessionId;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $data
     */
    private $data;


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
}