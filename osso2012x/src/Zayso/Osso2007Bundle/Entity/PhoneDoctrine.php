<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhoneDoctrine
 */
class PhoneDoctrine
{
    /**
     * @var bigint $phoneId
     */
    private $phoneId;

    /**
     * @var bigint $phoneTypeId
     */
    private $phoneTypeId;

    /**
     * @var bigint $personId
     */
    private $personId;

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
     * @return bigint 
     */
    public function getPhoneId()
    {
        return $this->phoneId;
    }

    /**
     * Set phoneTypeId
     *
     * @param bigint $phoneTypeId
     */
    public function setPhoneTypeId($phoneTypeId)
    {
        $this->phoneTypeId = $phoneTypeId;
    }

    /**
     * Get phoneTypeId
     *
     * @return bigint 
     */
    public function getPhoneTypeId()
    {
        return $this->phoneTypeId;
    }

    /**
     * Set personId
     *
     * @param bigint $personId
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    /**
     * Get personId
     *
     * @return bigint 
     */
    public function getPersonId()
    {
        return $this->personId;
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