<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\SessionBack
 *
 * @ORM\Table(name="session_back")
 * @ORM\Entity
 */
class SessionBack
{
    /**
     * @var integer $sessionBackId
     *
     * @ORM\Column(name="session_back_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sessionBackId;

    /**
     * @var string $sessionId
     *
     * @ORM\Column(name="session_id", type="string", length=32, nullable=true)
     */
    private $sessionId;

    /**
     * @var string $createdTs
     *
     * @ORM\Column(name="created_ts", type="string", length=16, nullable=true)
     */
    private $createdTs;

    /**
     * @var string $accessedTs
     *
     * @ORM\Column(name="accessed_ts", type="string", length=16, nullable=true)
     */
    private $accessedTs;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=true)
     */
    private $name;

    /**
     * @var string $data1
     *
     * @ORM\Column(name="data1", type="string", length=250, nullable=true)
     */
    private $data1;

    /**
     * @var string $data2
     *
     * @ORM\Column(name="data2", type="string", length=250, nullable=true)
     */
    private $data2;



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
     * Set createdTs
     *
     * @param string $createdTs
     */
    public function setCreatedTs($createdTs)
    {
        $this->createdTs = $createdTs;
    }

    /**
     * Get createdTs
     *
     * @return string 
     */
    public function getCreatedTs()
    {
        return $this->createdTs;
    }

    /**
     * Set accessedTs
     *
     * @param string $accessedTs
     */
    public function setAccessedTs($accessedTs)
    {
        $this->accessedTs = $accessedTs;
    }

    /**
     * Get accessedTs
     *
     * @return string 
     */
    public function getAccessedTs()
    {
        return $this->accessedTs;
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
     * Set data1
     *
     * @param string $data1
     */
    public function setData1($data1)
    {
        $this->data1 = $data1;
    }

    /**
     * Get data1
     *
     * @return string 
     */
    public function getData1()
    {
        return $this->data1;
    }

    /**
     * Set data2
     *
     * @param string $data2
     */
    public function setData2($data2)
    {
        $this->data2 = $data2;
    }

    /**
     * Get data2
     *
     * @return string 
     */
    public function getData2()
    {
        return $this->data2;
    }

    /**
     * Get sessionBackId
     *
     * @return integer 
     */
    public function getSessionBackId()
    {
        return $this->sessionBackId;
    }
}