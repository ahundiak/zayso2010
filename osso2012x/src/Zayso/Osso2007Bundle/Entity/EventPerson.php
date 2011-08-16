<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventPerson
 */
class EventPerson
{
    /**
     * @var integer $eventPersonId
     */
    private $eventPersonId;

    /**
     * @var integer $eventId
     */
    private $eventId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var integer $eventPersonTypeId
     */
    private $eventPersonTypeId;

    /**
     * @var integer $regYearId
     */
    private $regYearId;

    /**
     * @var integer $status
     */
    private $status;


    /**
     * Get eventPersonId
     *
     * @return integer 
     */
    public function getEventPersonId()
    {
        return $this->eventPersonId;
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
}