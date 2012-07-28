<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="person_reg")
 * @ORM\ChangeTrackingPolicy("NOTIFY") 
 * 
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string", length=20)
 * @ORM\DiscriminatorMap({
 *     "BASE"  = "PersonRegistered", 
 *     "AYSOV" = "PersonRegAYSOV",
 *     "USSF"  = "PersonRegUSSF"
 * })
 */
class PersonRegistered extends BaseEntity
{
    const TypeAYSOV   = 'AYSOV'; // Volunteer
    const TypeAYSOP   = 'AYSOP'; // Player
    const TypeUSSF    = 'USSF';  // USSF
    const TypeNFHS    = 'NFHS';  // High School
    const TypeNISOA   = 'NISOA'; // College
    const TypeARBITER = 'ARBITER'; // Arbiter email
   
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="registeredPersons")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /** 
     * @ORM\Column(type="string",name="reg_type",length=20) 
     */
    protected $regType = 'BASE';

    /** @ORM\Column(type="string",name="reg_key",length=40,unique=true) */
    protected $regKey = null;
  
    /**
     * @ORM\ManyToOne(targetEntity="Org")
     * @ORM\JoinColumn(name="org_key", referencedColumnName="id", nullable=true)
     */
    protected $org = null;

    /** @ORM\Column(type="string",name="verified",length=20) */
    protected $verified = 'No';

    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;
    protected $data  = null;

    public function setPerson($person) { $this->onObjectPropertySet('person',$person); }
    public function getPerson()        { return $this->person; }
    
    public function setRefBadge($refBadge) { return $this->set('ref_badge',$refBadge); }
    public function getRefBadge()          { return $this->get('ref_badge'); }

    public function setRefDate($refDate)  { return $this->set('ref_date',$refDate); }
    public function getRefDate()          { return $this->get('ref_date'); }
    
    public function setMemYear($memYear)  { return $this->set('mem_year',$memYear); }
    public function getMemYear()          { return $this->get('mem_year'); }
    
    /* ==============================================================
     * Org might be optional for some types of certs
     */
    public function setOrg($org) { $this->onObjectPropertySet('org',$org); }
    
    protected $orgTemp = null;
    
    public function getOrg() 
    { 
        if ($this->org)     return $this->org; 
        if ($this->orgTemp) return $this->orgTemp; 
        
        $this->orgTemp = new Org();
        return $this->orgTemp; 
   }
   // Usefull because the org is (AYSOR0894) is a generated value
    public function getOrgKey()
    {
        if ($this->org) return $this->org->getId();
        return null;
    }
    // Fake to keep forms happy (for now)
    public function setOrgKey($key) { }
    
    public function getId() { return $this->id; }

    // regType is basically a constant
    //public function setRegType($regType) { $this->onScalerPropertySet('regType',$regType); }
    
    public function getRegType()         { return $this->regType; }
    
    public function setRegKey($regKey) { $this->onScalerPropertySet('regKey',$regKey); }
    public function getRegKey()        { return $this->regKey; }

    public function setVerified($verified) { $this->onScalerPropertySet('verified',$verified); }
    public function getVerified()          { return $this->verified; }
    
    public function isAYSOV() { return false; }
    public function isUSSF () { return false; }

}