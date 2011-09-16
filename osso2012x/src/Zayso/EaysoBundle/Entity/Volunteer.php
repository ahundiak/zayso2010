<?php

namespace Zayso\EaysoBundle\Entity;

use Zayso\EaysoBundle\Repository\CertificationRepository as CertRepo;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;

/**
 * @ORM\Entity(repositoryClass="Zayso\EaysoBundle\Repository\VolunteerRepository")
 * @ORM\Table(name="volunteer")
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

    /** @ORM\Column(type="integer",name="mem_year",nullable=false) */
    protected $memYear = 0;

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
    
    /** @ORM\Column(type="string",name="registered",length=8) */
    protected $registered = '';
    
    /** @ORM\Column(type="string",name="changed",length=8) */
    protected $changed = '';
    

    /**
     *  @ORM\OneToMany(targetEntity="Certification", mappedBy="volunteer", indexBy="cat", cascade={"persist","remove"})
     */
    protected $certifications;

    private $listeners = array();
    private $modified = false;
    
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
        $this->modified = true;
        foreach ($this->listeners as $listener) 
        {
            $listener->propertyChanged($this, $propName, $oldValue, $newValue);
        }
    }
    public function isModified() { return $this->modified; }
    
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
    public function getRefereeBadgeCertification()
    {
        return $this->getCertification(CertRepo::TYPE_REFEREE_BADGE);
    }
    public function getCoachBadgeCertification()
    {
        return $this->getCertification(CertRepo::TYPE_COACH_BADGE);
    }
    public function getSafeHavenCertification()
    {
        return $this->getCertification(CertRepo::TYPE_SAFE_HAVEN);
    }
    public function getRefBadgeDesc()
    {
        $cert = $this->getCertification(CertRepo::TYPE_REFEREE_BADGE);
        if (!$cert) return 'No Ref Badge';
        return CertRepo::getDesc($cert->getType());
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

    public function setRegistered($date) { $this->onPropertySet('registered',$date); }
    public function getRegistered()      { return $this->registered; }

    public function setChanged($date) { $this->onPropertySet('changed',$date); }
    public function getChanged()      { return $this->changed; }

    public function setGender($gender) { $this->onPropertySet('gender',$gender); }
    public function getGender() { return $this->gender; }

    public function setStatus($status) { $this->onPropertySet('status',$status); }
    public function getStatus() { return $this->status; }
}