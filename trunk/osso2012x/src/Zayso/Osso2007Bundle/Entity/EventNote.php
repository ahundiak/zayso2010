<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventNote
 */
class EventNote
{
    /**
     * @var integer $eventNoteId
     */
    private $eventNoteId;

    /**
     * @var integer $eventId
     */
    private $eventId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $ts
     */
    private $ts;

    /**
     * @var string $data1
     */
    private $data1;


    /**
     * Get eventNoteId
     *
     * @return integer 
     */
    public function getEventNoteId()
    {
        return $this->eventNoteId;
    }

    /**
     * Set eventId
     *
     * @param integer $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Get eventId
     *
     * @return integer 
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set personId
     *
     * @param integer $personId
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    /**
     * Get personId
     *
     * @return integer 
     */
    public function getPersonId()
    {
        return $this->personId;
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
     * Set ts
     *
     * @param string $ts
     */
    public function setTs($ts)
    {
        $this->ts = $ts;
    }

    /**
     * Get ts
     *
     * @return string 
     */
    public function getTs()
    {
        return $this->ts;
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
}