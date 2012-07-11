<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Vol
 *
 * @ORM\Table(name="vol")
 * @ORM\Entity
 */
class Vol
{
    /**
     * @var integer $volId
     *
     * @ORM\Column(name="vol_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $volId;

    /**
     * @var integer $personId
     *
     * @ORM\Column(name="person_id", type="integer", nullable=true)
     */
    private $personId;

    /**
     * @var integer $volTypeId
     *
     * @ORM\Column(name="vol_type_id", type="integer", nullable=true)
     */
    private $volTypeId;

    /**
     * @var integer $regYearId
     *
     * @ORM\Column(name="reg_year_id", type="integer", nullable=true)
     */
    private $regYearId;

    /**
     * @var integer $seasonTypeId
     *
     * @ORM\Column(name="season_type_id", type="integer", nullable=true)
     */
    private $seasonTypeId;

    /**
     * @var integer $unitId
     *
     * @ORM\Column(name="unit_id", type="integer", nullable=true)
     */
    private $unitId;

    /**
     * @var integer $divisionId
     *
     * @ORM\Column(name="division_id", type="integer", nullable=true)
     */
    private $divisionId;

    /**
     * @var string $note
     *
     * @ORM\Column(name="note", type="string", length=40, nullable=true)
     */
    private $note;

    /**
     * @var integer $regForm
     *
     * @ORM\Column(name="reg_form", type="integer", nullable=true)
     */
    private $regForm;

    /**
     * @var integer $refresher
     *
     * @ORM\Column(name="refresher", type="integer", nullable=true)
     */
    private $refresher;

    /**
     * @var integer $certified
     *
     * @ORM\Column(name="certified", type="integer", nullable=true)
     */
    private $certified;

    /**
     * @var string $uniform
     *
     * @ORM\Column(name="uniform", type="string", length=40, nullable=true)
     */
    private $uniform;

    /**
     * @var string $equipment
     *
     * @ORM\Column(name="equipment", type="string", length=40, nullable=true)
     */
    private $equipment;



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
     * Set volTypeId
     *
     * @param integer $volTypeId
     */
    public function setVolTypeId($volTypeId)
    {
        $this->volTypeId = $volTypeId;
    }

    /**
     * Get volTypeId
     *
     * @return integer 
     */
    public function getVolTypeId()
    {
        return $this->volTypeId;
    }

    /**
     * Set regYearId
     *
     * @param integer $regYearId
     */
    public function setRegYearId($regYearId)
    {
        $this->regYearId = $regYearId;
    }

    /**
     * Get regYearId
     *
     * @return integer 
     */
    public function getRegYearId()
    {
        return $this->regYearId;
    }

    /**
     * Set seasonTypeId
     *
     * @param integer $seasonTypeId
     */
    public function setSeasonTypeId($seasonTypeId)
    {
        $this->seasonTypeId = $seasonTypeId;
    }

    /**
     * Get seasonTypeId
     *
     * @return integer 
     */
    public function getSeasonTypeId()
    {
        return $this->seasonTypeId;
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
     * Set divisionId
     *
     * @param integer $divisionId
     */
    public function setDivisionId($divisionId)
    {
        $this->divisionId = $divisionId;
    }

    /**
     * Get divisionId
     *
     * @return integer 
     */
    public function getDivisionId()
    {
        return $this->divisionId;
    }

    /**
     * Set note
     *
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set regForm
     *
     * @param integer $regForm
     */
    public function setRegForm($regForm)
    {
        $this->regForm = $regForm;
    }

    /**
     * Get regForm
     *
     * @return integer 
     */
    public function getRegForm()
    {
        return $this->regForm;
    }

    /**
     * Set refresher
     *
     * @param integer $refresher
     */
    public function setRefresher($refresher)
    {
        $this->refresher = $refresher;
    }

    /**
     * Get refresher
     *
     * @return integer 
     */
    public function getRefresher()
    {
        return $this->refresher;
    }

    /**
     * Set certified
     *
     * @param integer $certified
     */
    public function setCertified($certified)
    {
        $this->certified = $certified;
    }

    /**
     * Get certified
     *
     * @return integer 
     */
    public function getCertified()
    {
        return $this->certified;
    }

    /**
     * Set uniform
     *
     * @param string $uniform
     */
    public function setUniform($uniform)
    {
        $this->uniform = $uniform;
    }

    /**
     * Get uniform
     *
     * @return string 
     */
    public function getUniform()
    {
        return $this->uniform;
    }

    /**
     * Set equipment
     *
     * @param string $equipment
     */
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;
    }

    /**
     * Get equipment
     *
     * @return string 
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * Get volId
     *
     * @return integer 
     */
    public function getVolId()
    {
        return $this->volId;
    }
}