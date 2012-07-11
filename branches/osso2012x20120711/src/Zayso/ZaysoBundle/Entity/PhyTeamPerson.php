<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="phy_team_person")
 */
class PhyTeamPerson
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PhyTeam", inversedBy="persons")
     * @ORM\JoinColumn(name="phy_team_id", referencedColumnName="id")
     */
    protected $phyTeam = null;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person = null;

    /** @ORM\Column(type="string",name="type",length=20,nullable=true) */
    protected $type = '';
    
    /** @ORM\Column(type="string",name="org_key",length=20,nullable=true) */
    protected $orgKey = '';

    /** @ORM\Column(type="string",name="first_name",length=40) */
    protected $firstName = '';

    /** @ORM\Column(type="string",name="last_name",length=40) */
    protected $lastName = '';

    /** @ORM\Column(type="string",name="email",length=60,nullable=true) */
    protected $email = '';

    /** @ORM\Column(type="string",name="phone",length=20,nullable=true) */
    protected $phone = '';

    public function __construct()
    {
    }
    public function setPerson($person)
    {
        $this->person = $person;
    }
    public function setPhyTeam($phyTeam)
    {
        $this->phyTeam = $phyTeam;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get phyTeam
     *
     * @return Zayso\ZaysoBundle\Entity\PhyTeam 
     */
    public function getPhyTeam()
    {
        return $this->phyTeam;
    }

    /**
     * Get person
     *
     * @return Zayso\ZaysoBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set orgKey
     *
     * @param string $orgKey
     */
    public function setOrgKey($orgKey)
    {
        $this->orgKey = $orgKey;
    }

    /**
     * Get orgKey
     *
     * @return string 
     */
    public function getOrgKey()
    {
        return $this->orgKey;
    }
}