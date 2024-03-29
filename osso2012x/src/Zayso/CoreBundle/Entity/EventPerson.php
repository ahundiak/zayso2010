<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\CoreBundle\Component\Debug;

/* =======================================
 * Assigned  - Pending  - Published - Accepted
 * Requested - Approved - Published - Accepted
 * Changed   - Pending  - Published - Accepted
 *
 * On site
 * Performed
 * 
 */
/**
 * @ORM\Entity
 * @ORM\Table(name="event_person",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="event_person_type", columns={"event_id", "person_id", "type"})
   })
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class EventPerson extends BaseEntity
{   
    const TypeCR  = 'CR';
    const TypeCR2 = 'CR2';
    
    const Type4th = '4TH';
    const TypeObs = 'OBS';
    
    const TypeREF  = 'REF';
    const TypeREF1 = 'REF 1';
    const TypeREF2 = 'REF 2';
    
    const TypeAR1  = 'AR 1';
    const TypeAR2  = 'AR 2';

    static public $typeDescs = array
    (
        self::TypeCR  => 'Center',
        self::TypeCR2 => 'Center 2', // Dual

        self::TypeAR1 => 'Assistant 1', // DSC
        self::TypeAR2 => 'Assistant 2',
        
        self::TypeREF  => 'Referee',
        self::TypeREF1 => 'Referee 1', // Futsal
        self::TypeREF2 => 'Referee 2',

        self::Type4th => '4th Official',
        self::TypeObs => 'Observer',

    );
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
    */
    protected $id = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="persons")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event = null;
   
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person = null;
    
    /** 
     * @ORM\Column(type="string",name="type",length=20,nullable=false) 
     */
    protected $type = '';

    /**
     * Not sure if relly want this or not but probably doesn't hurt
     * @ORM\Column(type="integer",name="sortx",nullable=false)
     */
    protected $sort = 0;

    /**
     * Used for predefined positions that should never be deleted
     * @ORM\Column(type="integer",name="protected",nullable=false)
     */
    protected $protected = 0;

    /**
     * It is possible that this might be better stored in the datax
     * That would allow for multiple work flows etc
     *
     * @ORM\Column(type="string", name="state", length=40, nullable=true)
     */
    protected $state = null;

    /** @ORM\Column(type="datetime", name="modified_user", nullable=true) */
    protected $userModified = null;
    
    /** @ORM\Column(type="datetime", name="modified_admin", nullable=true) */
    protected $adminModified = null;
     
    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;

    
    /* =========================================================
     * Custom code
     */
    
    /* =========================================================
     * Standard getter/setter
     */    
    public function getId()    { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function setGame($event) { $this->setEvent($event); }
    public function getGame()       { return $this->event;     }
    
    public function setEvent($event) { $this->onObjectPropertySet('event', $event); }
    public function getEvent()       { return $this->event;  }
    
    public function setTypeAsCR  () { $this->setType(self::TypeCR );  }
    public function setTypeAsCR2 () { $this->setType(self::TypeCR2);  }
    public function setTypeAsAR1 () { $this->setType(self::TypeAR1);  }
    public function setTypeAsAR2 () { $this->setType(self::TypeAR2);  }
    public function setTypeAsRef () { $this->setType(self::TypeREF);  }
    public function setTypeAsRef1() { $this->setType(self::TypeREF1); }
    public function setTypeAsRef2() { $this->setType(self::TypeREF2); }
    public function setTypeAs4th () { $this->setType(self::Type4th);  }
    public function setTypeAsObs () { $this->setType(self::TypeObs);  }
    
    public function setType($type) 
    {
        $this->onScalerPropertySet('type', $type);
        switch($type)
        {
            case self::TypeCR :  $this->sort = 11; break;
            case self::TypeCR2:  $this->sort = 12; break;
            case self::TypeREF:  $this->sort = 13; break;
            case self::TypeREF1: $this->sort = 14; break;
            case self::TypeREF2: $this->sort = 15; break;
            case self::TypeAR1:  $this->sort = 21; break;
            case self::TypeAR2:  $this->sort = 22; break;
            case self::Type4th:  $this->sort = 31; break;
            case self::TypeObs:  $this->sort = 41; break;
            default:             $this->sort = 99;
        }
    }
    public function getType()      { return $this->type;  }
    public function getTypeDesc()
    {
        $type = $this->type;
        if (isset(self::$typeDescs[$type])) return self::$typeDescs[$type];
        return $type;
    }
    public function getTypeDescs() { return self::$typeDescs; }
    
    public function setState($state) { $this->onScalerPropertySet('state', $state); }
    public function getState()       { return $this->state;  }

    public function setPerson($person) { $this->onObjectPropertySet('person', $person); }
    public function getPerson()        { return $this->person;  }
    
    protected $personz = null;
    public function getPersonz()       
    { 
        if ( $this->person) return $this->person;
        if (!$this->personz)
        {
            $this->personz = new Person();
        }
        return $this->personz;  
    }

    public function getPersonName()
    {
        if (!$this->person) return null;
        return $this->person->getPersonName();
    }
    public function getPersonId()
    {
        if (!$this->person) return 0;
        return $this->person->getId();
    }
    protected $personIdx = null;
    
    public function setPersonId($personId)
    {
        $this->personIdx = $personId;
    }
    public function getPersonIdx() { return $this->personIdx; }
    
    // Real hack here, want to perserve state for form processing
    protected $statex = null;
    
    public function setStatex($state) { $this->statex = $state; }
    public function getStatex()       
    { 
        if ($this->statex) return $this->statex;
        return $this->state;
    }
    
    public function setProtected($value) { $this->protected = $value; }
    public function getProtected() { return $this->protected; }
    public function isProtected () { return $this->protected ? true : false; }
    
    // Date time stuff
    public function getUserModified()  { return $this->userModified; }
    public function getAdminModified() { return $this->adminModified; }
    
    public function setUserModified($dt = null) 
    {
        if (!$dt) $dt = new \DateTime('now');
        $this->onObjectPropertySetx('userModified', $dt);
    }
    public function setAdminModified($dt = null) 
    {   
        if (!$dt) $dt = new \DateTime('now');
        $this->onObjectPropertySetx('adminModified', $dt);
    }    
}
?>
