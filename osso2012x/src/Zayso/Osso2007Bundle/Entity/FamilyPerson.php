<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\FamilyPerson
 */
class FamilyPerson
{
    /**
     * @var integer $familyPersonId
     */
    private $familyPersonId;

    /**
     * @var integer $familyId
     */
    private $familyId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var integer $familyPersonTypeId
     */
    private $familyPersonTypeId;


    /**
     * Get familyPersonId
     *
     * @return integer 
     */
    public function getFamilyPersonId()
    {
        return $this->familyPersonId;
    }

    /**
     * Set familyId
     *
     * @param integer $familyId
     */
    public function setFamilyId($familyId)
    {
        $this->familyId = $familyId;
    }

    /**
     * Get familyId
     *
     * @return integer 
     */
    public function getFamilyId()
    {
        return $this->familyId;
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
     * Set familyPersonTypeId
     *
     * @param integer $familyPersonTypeId
     */
    public function setFamilyPersonTypeId($familyPersonTypeId)
    {
        $this->familyPersonTypeId = $familyPersonTypeId;
    }

    /**
     * Get familyPersonTypeId
     *
     * @return integer 
     */
    public function getFamilyPersonTypeId()
    {
        return $this->familyPersonTypeId;
    }
}