<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventNote
 *
 * @ORM\Table(name="event_note")
 * @ORM\Entity
 */
class EventNote
{
    /**
     * @var integer $eventNoteId
     *
     * @ORM\Column(name="event_note_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventNoteId;

    /**
     * @var integer $eventId
     *
     * @ORM\Column(name="event_id", type="integer", nullable=true)
     */
    private $eventId;

    /**
     * @var integer $personId
     *
     * @ORM\Column(name="person_id", type="integer", nullable=true)
     */
    private $personId;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=true)
     */
    private $name;

    /**
     * @var string $ts
     *
     * @ORM\Column(name="ts", type="string", length=16, nullable=true)
     */
    private $ts;

    /**
     * @var string $data1
     *
     * @ORM\Column(name="data1", type="string", length=250, nullable=true)
     */
    private $data1;



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

    /**
     * Get eventNoteId
     *
     * @return integer 
     */
    public function getEventNoteId()
    {
        return $this->eventNoteId;
    }
}