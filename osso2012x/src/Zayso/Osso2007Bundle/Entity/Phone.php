<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Phone
 */
class Phone
{
    /**
     * @var integer $phoneId
     */
    private $phoneId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var integer $phoneTypeId
     */
    private $phoneTypeId;

    /**
     * @var string $areaCode
     */
    private $areaCode;

    /**
     * @var string $num
     */
    private $num;

    /**
     * @var string $ext
     */
    private $ext;


    /**
     * Get phoneId
     *
     * @return integer 
     */
    public function getPhoneId()
    {
        return $this->phoneId;
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
     * Set phoneTypeId
     *
     * @param integer $phoneTypeId
     */
    public function setPhoneTypeId($phoneTypeId)
    {
        $this->phoneTypeId = $phoneTypeId;
    }

    /**
     * Get phoneTypeId
     *
     * @return integer 
     */
    public function getPhoneTypeId()
    {
        return $this->phoneTypeId;
    }

    /**
     * Set areaCode
     *
     * @param string $areaCode
     */
    public function setAreaCode($areaCode)
    {
        $this->areaCode = $areaCode;
    }

    /**
     * Get areaCode
     *
     * @return string 
     */
    public function getAreaCode()
    {
        return $this->areaCode;
    }

    /**
     * Set num
     *
     * @param string $num
     */
    public function setNum($num)
    {
        $this->num = $num;
    }

    /**
     * Get num
     *
     * @return string 
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set ext
     *
     * @param string $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * Get ext
     *
     * @return string 
     */
    public function getExt()
    {
        return $this->ext;
    }
}