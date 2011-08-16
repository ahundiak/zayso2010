<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Person
 */
class Person
{
    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var string $uname
     */
    private $uname;

    /**
     * @var string $upass
     */
    private $upass;

    /**
     * @var string $fname
     */
    private $fname;

    /**
     * @var string $lname
     */
    private $lname;

    /**
     * @var string $mname
     */
    private $mname;

    /**
     * @var string $nname
     */
    private $nname;

    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var integer $status
     */
    private $status;

    /**
     * @var string $aysoid
     */
    private $aysoid;


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
}