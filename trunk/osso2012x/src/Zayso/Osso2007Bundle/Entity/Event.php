<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Event
 */
class Event
{
    /**
     * @var integer $eventId
     */
    private $eventId;

    /**
     * @var integer $eventNum
     */
    private $eventNum;

    /**
     * @var integer $projectId
     */
    private $projectId;

    /**
     * @var integer $regYearId
     */
    private $regYearId;

    /**
     * @var integer $seasonTypeId
     */
    private $seasonTypeId;

    /**
     * @var integer $scheduleTypeId
     */
    private $scheduleTypeId;

    /**
     * @var integer $eventTypeId
     */
    private $eventTypeId;

    /**
     * @var integer $classId
     */
    private $classId;

    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var integer $status
     */
    private $status;

    /**
     * @var integer $fieldId
     */
    private $fieldId;

    /**
     * @var string $eventDate
     */
    private $eventDate;

    /**
     * @var string $eventTime
     */
    private $eventTime;

    /**
     * @var integer $eventDuration
     */
    private $eventDuration;

    /**
     * @var integer $point1
     */
    private $point1;

    /**
     * @var integer $point2
     */
    private $point2;


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
     * Set eventNum
     *
     * @param integer $eventNum
     */
    public function setEventNum($eventNum)
    {
        $this->eventNum = $eventNum;
    }

    /**
     * Get eventNum
     *
     * @return integer 
     */
    public function getEventNum()
    {
        return $this->eventNum;
    }

    /**
     * Set projectId
     *
     * @param integer $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
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
     * Set seasonTypeId
     *
     * @param integer $seasonTypeId
     */
    public function setSeasonTypeId($seasonTypeId)
    {
        $this->seasonTypeId = $seasonTypeId;
    }

    /**
     * Get seasonTypeId
     *
     * @return integer 
     */
    public function getSeasonTypeId()
    {
        return $this->seasonTypeId;
    }

    /**
     * Set scheduleTypeId
     *
     * @param integer $scheduleTypeId
     */
    public function setScheduleTypeId($scheduleTypeId)
    {
        $this->scheduleTypeId = $scheduleTypeId;
    }

    /**
     * Get scheduleTypeId
     *
     * @return integer 
     */
    public function getScheduleTypeId()
    {
        return $this->scheduleTypeId;
    }

    /**
     * Set eventTypeId
     *
     * @param integer $eventTypeId
     */
    public function setEventTypeId($eventTypeId)
    {
        $this->eventTypeId = $eventTypeId;
    }

    /**
     * Get eventTypeId
     *
     * @return integer 
     */
    public function getEventTypeId()
    {
        return $this->eventTypeId;
    }

    /**
     * Set classId
     *
     * @param integer $classId
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;
    }

    /**
     * Get classId
     *
     * @return integer 
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * Set unitId
     *
     * @param integer $unitId
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    /**
     * Get unitId
     *
     * @return integer 
     */
    public function getUnitId()
    {
        return $this->unitId;
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
     * Set fieldId
     *
     * @param integer $fieldId
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;
    }

    /**
     * Get fieldId
     *
     * @return integer 
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * Set eventDate
     *
     * @param string $eventDate
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;
    }

    /**
     * Get eventDate
     *
     * @return string 
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set eventTime
     *
     * @param string $eventTime
     */
    public function setEventTime($eventTime)
    {
        $this->eventTime = $eventTime;
    }

    /**
     * Get eventTime
     *
     * @return string 
     */
    public function getEventTime()
    {
        return $this->eventTime;
    }

    /**
     * Set eventDuration
     *
     * @param integer $eventDuration
     */
    public function setEventDuration($eventDuration)
    {
        $this->eventDuration = $eventDuration;
    }

    /**
     * Get eventDuration
     *
     * @return integer 
     */
    public function getEventDuration()
    {
        return $this->eventDuration;
    }

    /**
     * Set point1
     *
     * @param integer $point1
     */
    public function setPoint1($point1)
    {
        $this->point1 = $point1;
    }

    /**
     * Get point1
     *
     * @return integer 
     */
    public function getPoint1()
    {
        return $this->point1;
    }

    /**
     * Set point2
     *
     * @param integer $point2
     */
    public function setPoint2($point2)
    {
        $this->point2 = $point2;
    }

    /**
     * Get point2
     *
     * @return integer 
     */
    public function getPoint2()
    {
        return $this->point2;
    }
}