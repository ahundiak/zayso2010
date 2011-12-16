<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zayso\Osso2007Bundle\Service\GameManager as GameManager;
/**
 * Zayso\Osso2007Bundle\Entity\Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity
 */
class Person
{
    protected function noNull($value)
    {
        if ($value !== null) return $value;
        return '';
    }
    public function getNickName()  { return $this->noNull($this->getNName()); }
    public function getLastName()  { return $this->noNull($this->getLName()); }
    public function getFirstName() { return $this->noNull($this->getFName()); }
    public function getRegionKey()
    {
        return GameManager::getRegionKey($this->unitId);
    }

    /** =========================================================================
     * @var integer $personId
     *
     * @ORM\Column(name="person_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $personId;

    /**
     * @var string $uname
     *
     * @ORM\Column(name="uname", type="string", length=20, nullable=true)
     */
    private $uname;

    /**
     * @var string $upass
     *
     * @ORM\Column(name="upass", type="string", length=40, nullable=true)
     */
    private $upass;

    /**
     * @var string $fname
     *
     * @ORM\Column(name="fname", type="string", length=20, nullable=true)
     */
    private $fname;

    /**
     * @var string $lname
     *
     * @ORM\Column(name="lname", type="string", length=20, nullable=true)
     */
    private $lname;

    /**
     * @var string $mname
     *
     * @ORM\Column(name="mname", type="string", length=20, nullable=true)
     */
    private $mname;

    /**
     * @var string $nname
     *
     * @ORM\Column(name="nname", type="string", length=20, nullable=true)
     */
    private $nname;

    /**
     * @var integer $unitId
     *
     * @ORM\Column(name="unit_id", type="integer", nullable=true)
     */
    private $unitId;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var string $aysoid
     *
     * @ORM\Column(name="aysoid", type="string", length=20, nullable=true)
     */
    private $aysoid;



    /**
     * Set uname
     *
     * @param string $uname
     */
    public function setUname($uname)
    {
        $this->uname = $uname;
    }

    /**
     * Get uname
     *
     * @return string 
     */
    public function getUname()
    {
        return $this->uname;
    }

    /**
     * Set upass
     *
     * @param string $upass
     */
    public function setUpass($upass)
    {
        $this->upass = $upass;
    }

    /**
     * Get upass
     *
     * @return string 
     */
    public function getUpass()
    {
        return $this->upass;
    }

    /**
     * Set fname
     *
     * @param string $fname
     */
    public function setFname($fname)
    {
        $this->fname = $fname;
    }

    /**
     * Get fname
     *
     * @return string 
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     */
    public function setLname($lname)
    {
        $this->lname = $lname;
    }

    /**
     * Get lname
     *
     * @return string 
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * Set mname
     *
     * @param string $mname
     */
    public function setMname($mname)
    {
        $this->mname = $mname;
    }

    /**
     * Get mname
     *
     * @return string 
     */
    public function getMname()
    {
        return $this->mname;
    }

    /**
     * Set nname
     *
     * @param string $nname
     */
    public function setNname($nname)
    {
        $this->nname = $nname;
    }

    /**
     * Get nname
     *
     * @return string 
     */
    public function getNname()
    {
        return $this->nname;
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
     * Set aysoid
     *
     * @param string $aysoid
     */
    public function setAysoid($aysoid)
    {
        $this->aysoid = $aysoid;
    }

    /**
     * Get aysoid
     *
     * @return string 
     */
    public function getAysoid()
    {
        return $this->aysoid;
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
}