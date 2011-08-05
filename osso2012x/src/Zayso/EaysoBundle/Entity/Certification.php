<?php

namespace Zayso\EaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;

/**
 * @ORM\Entity(repositoryClass="Zayso\EaysoBundle\Repository\CertificationRepository")
 * @ORM\Table(
 *   name="eayso.certification")
 *   uniqueConstraints={@ORM\UniqueConstraint(name="aysoid_cat", columns={"aysoid", "cert_cat"})}
 * )
 * @ORM\HasLifecycleCallbacks
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class Certification implements NotifyPropertyChanged
{
    /**
     *  ORM\Id
     *  ORM\Column(type="integer",name="id")
     *  ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string",name="aysoid",length=20,nullable=false) */
    protected $aysoid = '';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="cert_cat") */
    protected $cat = '';

    /** @ORM\Column(type="integer",name="cert_type") */
    protected $type = '';

    /** @ORM\Column(type="string",name="cert_date",length=8,nullable=true) */
    protected $date = '';

    /**
     * @ORM\ManyToOne(targetEntity="Volunteer", inversedBy="certifications")
     * @ORM\JoinColumn(name="aysoid", referencedColumnName="id", nullable=false)
     */
    protected $volunteer;

    private $listeners = array();
    private $changed   = false;
    
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
    
    /* ============================================================
     * Generated Code
     */
    public function getId() { return $this->id; }

    public function setAysoid($aysoid) { $this->onPropertySet('aysoid',$aysoid); }
    public function getAysoid() { return $this->aysoid; }

    public function setCat($value) { $this->onPropertySet('cat',$value); }
    public function getCat() { return $this->cat; }

    public function setType($value) { $this->onPropertySet('type',$value); }
    public function getType() { return $this->type; }

    public function setDate($value) { $this->onPropertySet('date',$value); }
    public function getDate() { return $this->date; }

    public function setVolunteer($item)
    {
        $this->volunteer = $item;
        $item->addCertification($this);
    }
}