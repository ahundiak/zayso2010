<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="person_person",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="person_person_idx", columns={"person_id1", "person_id2", "relation"})})
 */
class PersonPerson
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
  
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id1", referencedColumnName="id",nullable=false)
     */
    protected $person1;
    
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id2", referencedColumnName="id",nullable=false)
     */
    protected $person2;
    
    /** @ORM\Column(name="relation",type="string",length=20,nullable=false) */
    /* Primary Family Peer */
    protected $relation;

    /** @ORM\Column(name="verified",type="string",length=16,nullable=false) */
    protected $verified = 'No';

    /** @ORM\Column(name="status",type="string",length=16,nullable=false) */
    protected $status = 'Active';

    /* ============================================
     * Custom code
     */
    public function __construct()
    {
    }
    public function getId() { return $this->id; }
    
    public function setPerson1($person) { $this->person1 = $person; }
    public function getPerson1() { return $this->person1; }
    
    public function setPerson2($person) { $this->person2 = $person; }
    public function getPerson2() { return $this->person2; }

    public function setVerified($verified) { $this->verified = $verified; }
    public function getVerified()   { return $this->verified; }

    public function setStatus($status) { $this->status = $status; }
    public function getStatus() { return $this->status; }

    public function setRelation($value)  { $this->relation = $value; }
    public function getRelation() { return $this->relation; }

    public function isPrimary() { return $this->relation == 'Primary' ? true : false; }
    public function isFamily () { return $this->relation == 'Family'  ? true : false; }
    public function isPeer   () { return $this->relation == 'Peer'    ? true : false; }

    public function setAsPrimary() { $this->relation = 'Primary'; }
    public function setAsFamily () { $this->relation = 'Family'; }
    public function setAsPeer   () { $this->relation = 'Peer'; }

}