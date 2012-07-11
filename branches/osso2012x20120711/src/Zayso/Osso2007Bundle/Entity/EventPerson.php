<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventPerson
 *
 * @ORM\Table(name="event_person")
 * @ORM\Entity
 */
class EventPerson
{
    /**
     * @var integer $eventPersonId
     *
     * @ORM\Column(name="event_person_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventPersonId;

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
     * @var integer $eventPersonTypeId
     *
     * @ORM\Column(name="event_person_type_id", type="integer", nullable=true)
     */
    private $eventPersonTypeId;

    /**
     * @var integer $regYearId
     *
     * @ORM\Column(name="reg_year_id", type="integer", nullable=true)
     */
    private $regYearId;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;



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
     * Set eventPersonTypeId
     *
     * @param integer $eventPersonTypeId
     */
    public function setEventPersonTypeId($eventPersonTypeId)
    {
        $this->eventPersonTypeId = $eventPersonTypeId;
    }

    /**
     * Get eventPersonTypeId
     *
     * @return integer 
     */
    public function getEventPersonTypeId()
    {
        return $this->eventPersonTypeId;
    }

    /**
     * Set regYearId
     *
     * @param integer $regYearId
     */
    public function setRegYearId($regYearId)
    {
        $this->regYearId = $regYearId;
    }

    /**
     * Get regYearId
     *
     * @return integer 
     */
    public function getRegYearId()
    {
        return $this->regYearId;
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
     * Get eventPersonId
     *
     * @return integer 
     */
    public function getEventPersonId()
    {
        return $this->eventPersonId;
    }
}