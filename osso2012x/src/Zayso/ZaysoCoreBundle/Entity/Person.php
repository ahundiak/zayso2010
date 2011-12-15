<?php

namespace Zayso\ZaysoCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *  ORM\Entity()
 * @ORM\Entity(repositoryClass="Zayso\ZaysoBundle\Repository\PersonRepository")
 * @ORM\Table(name="person")
 * @ORM\HasLifecycleCallbacks
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** @ORM\Column(type="string",name="first_name",length=40) */
    protected $firstName = '';

    /** @ORM\Column(type="string",name="last_name",length=40) */
    protected $lastName = '';

    /** @ORM\Column(type="string",name="nick_name",length=40,nullable=true) */
    protected $nickName = '';

    /** @ORM\Column(type="string",name="email",length=60,nullable=true) */
    protected $email = '';

    /** @ORM\Column(type="string",name="cell_phone",length=20,nullable=true) */
    protected $cellPhone = '';

    /** @ORM\Column(type="string",name="verified",length=20) */
    protected $verified = '';

    /** @ORM\Column(type="string",name="status",length=20) */
    protected $status = '';

    /** @ORM\Column(type="string",name="org_key",length=20,nullable=true) */
    protected $orgKey = '';

    /**  ORM\Column(type="string",name="dob",length=8,nullable=true) */
    protected $dob = '';

    /**  ORM\Column(type="string",name="gender",length=8,nullable=true) */
    protected $gender = '';

    /**
     *  @ORM\OneToMany(targetEntity="PersonRegistered", mappedBy="person", indexBy="regType", cascade={"persist"})
     */
    protected $registereds;

    /**
     * @ORM\OneToMany(targetEntity="AccountPerson", mappedBy="person")
     */
    protected $members;

    /**
     * @ORM\OneToMany(targetEntity="ProjectPerson", mappedBy="person", cascade={"persist"})
     */
    protected $projects;

    /** @ORM\Column(type="text",name="datax") */
    protected $datax = '';
    protected $data = array();

    /** @ORM\PrePersist */
    public function onPrePersist() { $this->datax = serialize($this->data); }

    /** @ORM\PreUpdate */
    public function onPreUpdate()  { $this->datax = serialize($this->data); }

    /** @ORM\PostLoad */
    public function onLoad()       { $this->data = unserialize($this->datax); }

    public function get($name)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return null;
    }
    public function set($name,$value)
    {
        if ($value === null)
        {
            if (isset($this->data[$name])) unset($this->data[$name]);
            $this->datax = null;
            return;
        }
        if (isset($this->data[$name]) && $this->data[$name] == $value) return;

        $this->data[$name] = $value;
        $this->datax = null;
    }
  
    public function __construct()
    {
        $this->registereds = new ArrayCollection();
        $this->members     = new ArrayCollection();
        $this->projects    = new ArrayCollection();
    }
    public function addAccountPerson($member)
    {
        $this->members[] = $member;
    }
    public function addProjectPerson($person)
    {
        $this->projects[] = $person;
    }
    public function addRegisteredPerson($reg)
    {
        $this->registereds[$reg->getRegType()] = $reg;
    }
    public function getNatGamesProjectPerson()
    {
        foreach($this->projects as $projectPerson)
        {
            if ($projectPerson->getProject()->getId() == 52)
            {
                return $projectPerson;
            }
        }
        return new \Zayso\ZaysoBundle\Entity\ProjectPerson();        
    }
    public function getAysoid()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getRegKey();
        return null;
    }
    public function getRefBadge()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getRefBadge();
        return null;
    }
    public function getRefDate()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getRefDate();
        return null;
    }
    public function getSafeHaven()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getSafeHaven();
        return null;
    }
    public function getMemYear()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getMemYear();
        return null;
    }
    public function setAysoid($aysoid)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setRegKey($aysoid);
        return;
    }
    public function setRefBadge($badge)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setRefBadge($badge);
        return;
    }
    public function setRefDate($date)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setRefDate($date);
        return;
    }
     public function setSafeHaven($value)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setSafeHaven($value);
        return;
    }
     public function setMemYear($value)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setMemYear($value);
        return;
    }
    public function getAysoRegisteredPerson()
    {
        // die('Count: ' . count($this->_regs));

        if ($this->registereds['AYSOV']) return $this->registereds['AYSOV'];
        return null;
        // Should be able to use that key stuff
        foreach($this->registereds as $reg)
        {
            if ($reg->getRegType() == 'AYSOV') return $reg;
        }
        return null;
    }
    public function setDob($dob) { return $this->set('dob',$dob); }
    public function getDob()     { return $this->get('dob'); }
    
    public function setGender($gender) { return $this->set('gender',$gender); }
    public function getGender()        { return $this->get('gender'); }

    public function getPersonName()
    {
        $fname = $this->getFirstName();
        $lname = $this->getLastName();
        $nname = $this->getNickName();

        if ($nname) $name =  $nname . ' ' . $lname;
        else        $name =  $fname . ' ' . $lname;

        return $name;
    }
    /* ======================================================================
     * Generated code follows
     */

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
     * Set nickName
     *
     * @param string $nickName
     */
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;
    }

    /**
     * Get nickName
     *
     * @return string 
     */
    public function getNickName()
    {
        return $this->nickName;
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
     * Set cellPhone
     *
     * @param string $cellPhone
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;
    }

    /**
     * Get cellPhone
     *
     * @return string 
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * Set verified
     *
     * @param string $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * Get verified
     *
     * @return string 
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
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

    /**
     * Add registereds
     *
     * @param Zayso\ZaysoBundle\Entity\PersonRegistered $registereds
     */
    public function addRegistereds(\Zayso\ZaysoBundle\Entity\PersonRegistered $registereds)
    {
        $this->registereds[] = $registereds;
    }

    /**
     * Get registereds
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRegistereds()
    {
        return $this->registereds;
    }

    /**
     * Add members
     *
     * @param Zayso\ZaysoBundle\Entity\AccountPerson $members
     */
    public function addMembers(\Zayso\ZaysoBundle\Entity\AccountPerson $members)
    {
        $this->members[] = $members;
    }

    /**
     * Get members
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add projects
     *
     * @param Zayso\ZaysoBundle\Entity\ProjectPerson $projects
     */
    public function addProjects(\Zayso\ZaysoBundle\Entity\ProjectPerson $projects)
    {
        $this->projects[] = $projects;
    }

    /**
     * Get projects
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set datax
     *
     * @param text $datax
     */
    public function setDatax($datax)
    {
        $this->datax = $datax;
    }

    /**
     * Get datax
     *
     * @return text 
     */
    public function getDatax()
    {
        return $this->datax;
    }
}