<?php

namespace Zayso\EaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;

/**
 * @Entity
 * @ChangeTrackingPolicy("NOTIFY")
 */
class MyEntity implements NotifyPropertyChanged
{
    // ...

    private $_listeners = array();

    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
        $this->_listeners[] = $listener;
    }
}
/**
 * @ORM\Entity()
 *  ORM\Entity(repositoryClass="Zayso\ZaysoBundle\Repository\PersonRepository")
 * @ORM\Table(name="eayso.volunteer")
 * @ORM\HasLifecycleCallbacks
 * @ChangeTrackingPolicy("NOTIFY")
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
    protected $status = '';

    /**
     *   ORM\OneToMany(targetEntity="VolunteerCertification", mappedBy="volunyeer", cascade={"persist","remove"})
     */
    protected $certs;

    private $listeners = array();
    private $changed = false;
    
    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
        $this->listeners[] = $listener;
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
      $this->certs = new ArrayCollection();
    }
 
    /* ============================================================
     * Generated Code
     */

    /**
     * Set id
     *
     * @param string $id
     */
    public function setId($id)
    {
        if ($this->id != $id)
        {
            $this->onPropertyChanged('id',$this->id,$id);
            $this->id = $id;
        }
    }

    /**
     * Get id
     *
     * @return string 
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
        if ($this->firstName != $firstName)
        {
            $this->onPropertyChanged('firstName',$this->firstName,$firstName);
            $this->firstName = $firstName;
        }
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
     * Set middleName
     *
     * @param string $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set suffix
     *
     * @param string $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * Get suffix
     *
     * @return string 
     */
    public function getSuffix()
    {
        return $this->suffix;
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
     * Set homePhone
     *
     * @param string $homePhone
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;
    }

    /**
     * Get homePhone
     *
     * @return string 
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Set workPhone
     *
     * @param string $workPhone
     */
    public function setWorkPhone($workPhone)
    {
        $this->workPhone = $workPhone;
    }

    /**
     * Get workPhone
     *
     * @return string 
     */
    public function getWorkPhone()
    {
        return $this->workPhone;
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
     * Set region
     *
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }
}