<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\FamilyPerson
 *
 * @ORM\Table(name="family_person")
 * @ORM\Entity
 */
class FamilyPerson
{
    /**
     * @var integer $familyPersonId
     *
     * @ORM\Column(name="family_person_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $familyPersonId;

    /**
     * @var integer $familyId
     *
     * @ORM\Column(name="family_id", type="integer", nullable=true)
     */
    private $familyId;

    /**
     * @var integer $personId
     *
     * @ORM\Column(name="person_id", type="integer", nullable=true)
     */
    private $personId;

    /**
     * @var integer $familyPersonTypeId
     *
     * @ORM\Column(name="family_person_type_id", type="integer", nullable=true)
     */
    private $familyPersonTypeId;



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

    /**
     * Get familyPersonId
     *
     * @return integer 
     */
    public function getFamilyPersonId()
    {
        return $this->familyPersonId;
    }
}