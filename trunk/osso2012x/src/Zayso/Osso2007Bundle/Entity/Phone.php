<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Phone
 *
 * @ORM\Table(name="phone")
 * @ORM\Entity
 */
class Phone
{
    /**
     * @var integer $phoneId
     *
     * @ORM\Column(name="phone_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $phoneId;

    /**
     * @var integer $personId
     *
     * @ORM\Column(name="person_id", type="integer", nullable=true)
     */
    private $personId;

    /**
     * @var integer $phoneTypeId
     *
     * @ORM\Column(name="phone_type_id", type="integer", nullable=true)
     */
    private $phoneTypeId;

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

    /**
     * Get phoneId
     *
     * @return integer 
     */
    public function getPhoneId()
    {
        return $this->phoneId;
    }
}