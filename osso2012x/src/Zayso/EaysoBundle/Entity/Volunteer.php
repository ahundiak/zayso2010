<?php

namespace Zayso\EaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;

/**
 * @ORM\Entity()
 *  ORM\Entity(repositoryClass="Zayso\ZaysoBundle\Repository\PersonRepository")
 * @ORM\Table(name="eayso.volunteer")
 * @ORM\HasLifecycleCallbacks
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class Volunteer implements NotifyPropertyChanged
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string",name="id",length="20")
     *  ORM\GeneratedValue
     */
    protected $id;
    
    /** @ORM\Column(type="string",name="region",length=20,nullable=true) */
    protected $region = '';

    /** @ORM\Column(type="string",name="mem_year",length=8,nullable=true) */
    protected $memYear = '';

    /** @ORM\Column(type="string",name="first_name",length=40) */
    protected $firstName = '';

    /** @ORM\Column(type="string",name="last_name",length=40) */
    protected $lastName = '';

    /** @ORM\Column(type="string",name="nick_name",length=40,nullable=true) */
    protected $nickName = '';
    
    /** @ORM\Column(type="string",name="middle_name",length=40) */
    protected $middleName = '';
    
    /** @ORM\Column(type="string",name="suffix",length=20) */
    protected $suffix = '';
    
    /** @ORM\Column(type="string",name="dob",length=8) */
    protected $dob = '';
    
    /** @ORM\Column(type="string",name="gender",length=2) */
    protected $gender = '';

    /** @ORM\Column(type="string",name="email",length=60,nullable=true) */
    protected $email = '';

    /** @ORM\Column(type="string",name="home_phone",length=20,nullable=true) */
    protected $homePhone = '';
    
    /** @ORM\Column(type="string",name="work_phone",length=20,nullable=true) */
    protected $workPhone = '';
    
    /** @ORM\Column(type="string",name="cell_phone",length=20,nullable=true) */
    protected $cellPhone = '';
    
    /** @ORM\Column(type="string",name="status",length=20) */
    protected $status = 'Active';

    /**
     *  @ORM\OneToMany(targetEntity="Certification", mappedBy="volunteer", indexBy="cat", cascade={"persist","remove"})
     */
    protected $certifications;

    private $listeners = array();
    private $changed = false;
    
    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
        $this->listeners[] = $listener;
    }
    protected function onPropertySet($name,$value)
    {
        if ($this->$name == $value) return;
        $this->onPropertyChanged($name,$this->$name,$value);
        $this->$name = $value;
    }
    protected function onPropertyChanged($propName, $oldValue, $newValue)
    {
        $this->changed = true;
        foreach ($this->listeners as $listener) 
        {
            $listener->propertyChanged($this, $propName, $oldValue, $newValue);
        }
    }
    public function isChanged() { return $this->changed; }
    
    public function __construct()
    {
      $this->certtifications = new ArrayCollection();
    }
    public function addCertification($cert)
    {
        $this->certifications[$cert->getCat()] = $cert;
    }
    public function getCertification($cat)
    {
        if (isset($this->certifications[$cat])) return $this->certifications[$cat];
        return null;
    }
    /* ============================================================
     * Generated Code
     */

    public function setId($id) { $this->onPropertySet('id',$id); }
    public function getId() { return $this->id; }

    public function setFirstName($firstName) { $this->onPropertySet('firstName',$firstName); }
    public function getFirstName() { return $this->firstName; }

    public function setLastName($lastName) { $this->onPropertySet('lastName',$lastName); }
    public function getLastName() { return $this->lastName; }

    public function setNickName($nickName) { $this->onPropertySet('nickName',$nickName); }
    public function getNickName() { return $this->nickName; }

    public function setMiddleName($middleName) { $this->onPropertySet('middleName',$middleName); }
    public function getMiddleName() { return $this->middleName; }

    public function setSuffix($suffix) { $this->onPropertySet('suffix',$suffix); }
    public function getSuffix() { return $this->suffix; }

    public function setEmail($email) { $this->onPropertySet('email',$email); }
    public function getEmail() { return $this->email; }

    public function setHomePhone($homePhone) { $this->onPropertySet('homePhone',$homePhone); }
    public function getHomePhone() { return $this->homePhone; }

    public function setWorkPhone($workPhone) { $this->onPropertySet('workPhone',$workPhone); }
    public function getWorkPhone() { return $this->workPhone; }

    public function setCellPhone($cellPhone) { $this->onPropertySet('cellPhone',$cellPhone); }
    public function getCellPhone() { return $this->cellPhone; }

    public function setRegion($region) { $this->onPropertySet('region',$region); }
    public function getRegion() { return $this->region; }

    public function setMemYear($memYear) { $this->onPropertySet('memYear',$memYear); }
    public function getMemYear() { return $this->memYear; }

    public function setDob($dob) { $this->onPropertySet('dob',$dob); }
    public function getDob() { return $this->dob; }

    public function setGender($gender) { $this->onPropertySet('gender',$gender); }
    public function getGender() { return $this->gender; }

    public function setStatus($status) { $this->onPropertySet('status',$status); }
    public function getStatus() { return $this->status; }
}