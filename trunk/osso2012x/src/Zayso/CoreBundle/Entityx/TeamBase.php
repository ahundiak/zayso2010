<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zayso\CoreBundle\Component\Debug;

/** @ORM\MappedSuperclass */
class TeamBase
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
    */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /** @ORM\Column(type="string",name="key1",nullable=true) */
    protected $key1 = null;

    /** @ORM\Column(type="string",name="key2",nullable=true) */
    protected $key2 = null;
    
    /** @ORM\Column(type="string",name="key3",nullable=true) */
    protected $key3 = null;

    /** @ORM\Column(type="string",name="key4",nullable=true) */
    protected $key4 = null;
    
    /** @ORM\Column(type="string",name="desc1",nullable=true) */
    protected $desc1 = '';

    /** @ORM\Column(type="string",name="desc2",nullable=true) */
    protected $desc2 = '';
    
     /** @ORM\Column(type="string",name="age",length=8,nullable=false) */
    protected $age = '';

    /** @ORM\Column(type="string",name="gender",length=8,nullable=false) */
    protected $gender = '';

    /** @ORM\Column(type="string",name="level",length=20,nullable=false) */
    protected $level = '';
    
    /** @ORM\Column(type="string",name="status",length=20,nullable=false) */
    protected $status = 'Active';
    
    protected $discr = null;
    
    public function getId     () { return $this->id;      }
    public function getAge    () { return $this->age;     }
    public function getDiscr  () { return $this->discr;   }
    public function getDesc1  () { return $this->desc1;   }
    public function getDesc2  () { return $this->desc2;   }
    public function getLevel  () { return $this->level;   }
    public function getGender () { return $this->gender;  }
    public function getStatus () { return $this->status;  }
    public function getProject() { return $this->project; }
    
    public function setAge    ($value) { $this->age     = $value; }
    public function setDesc1  ($value) { $this->desc1   = $value; }
    public function setDesc2  ($value) { $this->desc2   = $value; }
    public function setLevel  ($value) { $this->level   = $value; }
    public function setGender ($value) { $this->gender  = $value; }
    public function setStatus ($value) { $this->status  = $value; }
    public function setProject($value) { $this->project = $value; }
    
    // Majic?
    public function setTeamKey($key) { $this->key1 = $key; }
    public function getTeamKey()     { return $this->key1; }
    
    public function setTeamKeyExpanded($key) { $this->key2 = $key; }
    public function getTeamKeyExpanded()     { return $this->key2; }

    /* ========================================================
     * Blob routines
     * Assume mostly readonly
     */
    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;
    protected $data  = null;
    
    public function get($name, $default = null)
    {
        // First time called after loading
        if (!$this->data) $this->data = unserialize($this->datax);

        if (isset($this->data[$name])) return $this->data[$name];
        return $default;
    }
    public function set($name,$value)
    {
        // First time called after loading
        if (!$this->data) $this->data = unserialize($this->datax);
        
        // Special for unsetting
        if ($value === null)
        {
            if (isset($this->data[$name])) unset($this->data[$name]);
            $this->datax = serialize($this->data);
            return;
        }
        
        // Only if changed
        if (isset($this->data[$name]) && $this->data[$name] == $value) return;

        $this->data[$name] = $value;
        
        // Inefficient but should be fine for mostly readonly
        $this->datax = serialize($this->data);
    }    
}
?>
