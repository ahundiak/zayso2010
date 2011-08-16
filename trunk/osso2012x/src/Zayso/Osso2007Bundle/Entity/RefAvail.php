<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\RefAvail
 */
class RefAvail
{
    /**
     * @var integer $refAvailId
     */
    private $refAvailId;

    /**
     * @var integer $groupId
     */
    private $groupId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var integer $divCrId
     */
    private $divCrId;

    /**
     * @var integer $divArId
     */
    private $divArId;

    /**
     * @var string $phoneHome
     */
    private $phoneHome;

    /**
     * @var string $phoneWork
     */
    private $phoneWork;

    /**
     * @var string $phoneCell
     */
    private $phoneCell;

    /**
     * @var string $emailHome
     */
    private $emailHome;

    /**
     * @var string $emailWork
     */
    private $emailWork;

    /**
     * @var integer $availDay1
     */
    private $availDay1;

    /**
     * @var integer $availDay2
     */
    private $availDay2;

    /**
     * @var integer $availDay3
     */
    private $availDay3;

    /**
     * @var integer $availDay4
     */
    private $availDay4;

    /**
     * @var integer $availDay5
     */
    private $availDay5;

    /**
     * @var integer $availDay6
     */
    private $availDay6;

    /**
     * @var integer $teamId1
     */
    private $teamId1;

    /**
     * @var integer $teamId2
     */
    private $teamId2;

    /**
     * @var integer $teamId3
     */
    private $teamId3;

    /**
     * @var string $modified
     */
    private $modified;

    /**
     * @var string $notes
     */
    private $notes;


    /**
     * Get refAvailId
     *
     * @return integer 
     */
    public function getRefAvailId()
    {
        return $this->refAvailId;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * Get groupId
     *
     * @return integer 
     */
    public function getGroupId()
    {
        return $this->groupId;
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
     * Set divCrId
     *
     * @param integer $divCrId
     */
    public function setDivCrId($divCrId)
    {
        $this->divCrId = $divCrId;
    }

    /**
     * Get divCrId
     *
     * @return integer 
     */
    public function getDivCrId()
    {
        return $this->divCrId;
    }

    /**
     * Set divArId
     *
     * @param integer $divArId
     */
    public function setDivArId($divArId)
    {
        $this->divArId = $divArId;
    }

    /**
     * Get divArId
     *
     * @return integer 
     */
    public function getDivArId()
    {
        return $this->divArId;
    }

    /**
     * Set phoneHome
     *
     * @param string $phoneHome
     */
    public function setPhoneHome($phoneHome)
    {
        $this->phoneHome = $phoneHome;
    }

    /**
     * Get phoneHome
     *
     * @return string 
     */
    public function getPhoneHome()
    {
        return $this->phoneHome;
    }

    /**
     * Set phoneWork
     *
     * @param string $phoneWork
     */
    public function setPhoneWork($phoneWork)
    {
        $this->phoneWork = $phoneWork;
    }

    /**
     * Get phoneWork
     *
     * @return string 
     */
    public function getPhoneWork()
    {
        return $this->phoneWork;
    }

    /**
     * Set phoneCell
     *
     * @param string $phoneCell
     */
    public function setPhoneCell($phoneCell)
    {
        $this->phoneCell = $phoneCell;
    }

    /**
     * Get phoneCell
     *
     * @return string 
     */
    public function getPhoneCell()
    {
        return $this->phoneCell;
    }

    /**
     * Set emailHome
     *
     * @param string $emailHome
     */
    public function setEmailHome($emailHome)
    {
        $this->emailHome = $emailHome;
    }

    /**
     * Get emailHome
     *
     * @return string 
     */
    public function getEmailHome()
    {
        return $this->emailHome;
    }

    /**
     * Set emailWork
     *
     * @param string $emailWork
     */
    public function setEmailWork($emailWork)
    {
        $this->emailWork = $emailWork;
    }

    /**
     * Get emailWork
     *
     * @return string 
     */
    public function getEmailWork()
    {
        return $this->emailWork;
    }

    /**
     * Set availDay1
     *
     * @param integer $availDay1
     */
    public function setAvailDay1($availDay1)
    {
        $this->availDay1 = $availDay1;
    }

    /**
     * Get availDay1
     *
     * @return integer 
     */
    public function getAvailDay1()
    {
        return $this->availDay1;
    }

    /**
     * Set availDay2
     *
     * @param integer $availDay2
     */
    public function setAvailDay2($availDay2)
    {
        $this->availDay2 = $availDay2;
    }

    /**
     * Get availDay2
     *
     * @return integer 
     */
    public function getAvailDay2()
    {
        return $this->availDay2;
    }

    /**
     * Set availDay3
     *
     * @param integer $availDay3
     */
    public function setAvailDay3($availDay3)
    {
        $this->availDay3 = $availDay3;
    }

    /**
     * Get availDay3
     *
     * @return integer 
     */
    public function getAvailDay3()
    {
        return $this->availDay3;
    }

    /**
     * Set availDay4
     *
     * @param integer $availDay4
     */
    public function setAvailDay4($availDay4)
    {
        $this->availDay4 = $availDay4;
    }

    /**
     * Get availDay4
     *
     * @return integer 
     */
    public function getAvailDay4()
    {
        return $this->availDay4;
    }

    /**
     * Set availDay5
     *
     * @param integer $availDay5
     */
    public function setAvailDay5($availDay5)
    {
        $this->availDay5 = $availDay5;
    }

    /**
     * Get availDay5
     *
     * @return integer 
     */
    public function getAvailDay5()
    {
        return $this->availDay5;
    }

    /**
     * Set availDay6
     *
     * @param integer $availDay6
     */
    public function setAvailDay6($availDay6)
    {
        $this->availDay6 = $availDay6;
    }

    /**
     * Get availDay6
     *
     * @return integer 
     */
    public function getAvailDay6()
    {
        return $this->availDay6;
    }

    /**
     * Set teamId1
     *
     * @param integer $teamId1
     */
    public function setTeamId1($teamId1)
    {
        $this->teamId1 = $teamId1;
    }

    /**
     * Get teamId1
     *
     * @return integer 
     */
    public function getTeamId1()
    {
        return $this->teamId1;
    }

    /**
     * Set teamId2
     *
     * @param integer $teamId2
     */
    public function setTeamId2($teamId2)
    {
        $this->teamId2 = $teamId2;
    }

    /**
     * Get teamId2
     *
     * @return integer 
     */
    public function getTeamId2()
    {
        return $this->teamId2;
    }

    /**
     * Set teamId3
     *
     * @param integer $teamId3
     */
    public function setTeamId3($teamId3)
    {
        $this->teamId3 = $teamId3;
    }

    /**
     * Get teamId3
     *
     * @return integer 
     */
    public function getTeamId3()
    {
        return $this->teamId3;
    }

    /**
     * Set modified
     *
     * @param string $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     * Get modified
     *
     * @return string 
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set notes
     *
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }
}