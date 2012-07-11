<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhoneDoctrine
 *
 * @ORM\Table(name="phone_doctrine")
 * @ORM\Entity
 */
class PhoneDoctrine
{
    /**
     * @var bigint $phoneId
     *
     * @ORM\Column(name="phone_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $phoneId;

    /**
     * @var bigint $phoneTypeId
     *
     * @ORM\Column(name="phone_type_id", type="bigint", nullable=true)
     */
    private $phoneTypeId;

    /**
     * @var bigint $personId
     *
     * @ORM\Column(name="person_id", type="bigint", nullable=true)
     */
    private $personId;

    /**
     * @var string $areaCode
     *
     * @ORM\Column(name="area_code", type="string", length=4, nullable=true)
     */
    private $areaCode;

    /**
     * @var string $num
     *
     * @ORM\Column(name="num", type="string", length=8, nullable=true)
     */
    private $num;

    /**
     * @var string $ext
     *
     * @ORM\Column(name="ext", type="string", length=8, nullable=true)
     */
    private $ext;



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

    /**
     * Get phoneId
     *
     * @return bigint 
     */
    public function getPhoneId()
    {
        return $this->phoneId;
    }
}