<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\ProjectOrgTeam
 */
class ProjectOrgTeam
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $projectOrgId
     */
    private $projectOrgId;

    /**
     * @var integer $teamId
     */
    private $teamId;

    /**
     * @var integer $typeId
     */
    private $typeId;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set projectOrgId
     *
     * @param integer $projectOrgId
     */
    public function setProjectOrgId($projectOrgId)
    {
        $this->projectOrgId = $projectOrgId;
    }

    /**
     * Get projectOrgId
     *
     * @return integer 
     */
    public function getProjectOrgId()
    {
        return $this->projectOrgId;
    }

    /**
     * Set teamId
     *
     * @param integer $teamId
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;
    }

    /**
     * Get teamId
     *
     * @return integer 
     */
    public function getTeamId()
    {
        return $this->teamId;
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
}