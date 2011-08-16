<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Project
 */
class Project
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $typeId
     */
    private $typeId;

    /**
     * @var integer $eventNum
     */
    private $eventNum;

    /**
     * @var integer $memYear
     */
    private $memYear;

    /**
     * @var integer $calYear
     */
    private $calYear;

    /**
     * @var integer $seasonTypeId
     */
    private $seasonTypeId;

    /**
     * @var integer $adminOrgId
     */
    private $adminOrgId;

    /**
     * @var integer $sportTypeId
     */
    private $sportTypeId;

    /**
     * @var integer $sort1
     */
    private $sort1;

    /**
     * @var integer $status
     */
    private $status;

    /**
     * @var string $desc1
     */
    private $desc1;

    /**
     * @var string $dateBeg
     */
    private $dateBeg;

    /**
     * @var string $dateEnd
     */
    private $dateEnd;


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
     * Set typeId
     *
     * @param integer $typeId
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
    }

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->typeId;
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
     * Set memYear
     *
     * @param integer $memYear
     */
    public function setMemYear($memYear)
    {
        $this->memYear = $memYear;
    }

    /**
     * Get memYear
     *
     * @return integer 
     */
    public function getMemYear()
    {
        return $this->memYear;
    }

    /**
     * Set calYear
     *
     * @param integer $calYear
     */
    public function setCalYear($calYear)
    {
        $this->calYear = $calYear;
    }

    /**
     * Get calYear
     *
     * @return integer 
     */
    public function getCalYear()
    {
        return $this->calYear;
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
     * Set adminOrgId
     *
     * @param integer $adminOrgId
     */
    public function setAdminOrgId($adminOrgId)
    {
        $this->adminOrgId = $adminOrgId;
    }

    /**
     * Get adminOrgId
     *
     * @return integer 
     */
    public function getAdminOrgId()
    {
        return $this->adminOrgId;
    }

    /**
     * Set sportTypeId
     *
     * @param integer $sportTypeId
     */
    public function setSportTypeId($sportTypeId)
    {
        $this->sportTypeId = $sportTypeId;
    }

    /**
     * Get sportTypeId
     *
     * @return integer 
     */
    public function getSportTypeId()
    {
        return $this->sportTypeId;
    }

    /**
     * Set sort1
     *
     * @param integer $sort1
     */
    public function setSort1($sort1)
    {
        $this->sort1 = $sort1;
    }

    /**
     * Get sort1
     *
     * @return integer 
     */
    public function getSort1()
    {
        return $this->sort1;
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
     * Set dateBeg
     *
     * @param string $dateBeg
     */
    public function setDateBeg($dateBeg)
    {
        $this->dateBeg = $dateBeg;
    }

    /**
     * Get dateBeg
     *
     * @return string 
     */
    public function getDateBeg()
    {
        return $this->dateBeg;
    }

    /**
     * Set dateEnd
     *
     * @param string $dateEnd
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * Get dateEnd
     *
     * @return string 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }
}