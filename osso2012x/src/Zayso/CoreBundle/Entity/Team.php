<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zayso\CoreBundle\Component\Debug;

/**
 * @ORM\Entity(repositoryClass="Zayso\CoreBundle\Repository\TeamRepository")
 * @ORM\Table(name="team",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="project_key1", columns={"project_id", "key1"}),
         @ORM\UniqueConstraint(name="project_key2", columns={"project_id", "key2"}),
         @ORM\UniqueConstraint(name="project_key3", columns={"project_id", "key3"}),
         @ORM\UniqueConstraint(name="project_key4", columns={"project_id", "key4"})
   })
 * @ORM\ChangeTrackingPolicy("NOTIFY") * 
 */
class Team extends BaseEntity
{
    const TypeSchedule = 'Schedule';
    const TypePhysical = 'Physical';
    
    const SourceEayso  = 'Eayso';
    const SourceAuto   = 'Auto'; // Automatically created by zayso
    
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

     /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="Org")
     * @ORM\JoinColumn(name="org_id", referencedColumnName="id", nullable=true)
     */
    protected $org = null;
    
    /** 
     * Try to avoid using but could be schedule,physical,permanent etc
     * @ORM\Column(type="string",name="type",length=20,nullable=false) 
     */
    protected $type = '';
    
    /** 
     * Try to avoid using but could be eayso etc
     * @ORM\Column(type="string",name="source",length=20,nullable=false) 
     */
    protected $source = '';
    
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
    
     /** @ORM\Column(type="string",name="age",length=20,nullable=false) */
    protected $age = '';

    /** @ORM\Column(type="string",name="gender",length=20,nullable=false) */
    protected $gender = '';

    /** @ORM\Column(type="string",name="level",length=20,nullable=false) */
    protected $level = '';
    
    /** @ORM\Column(type="string",name="status",length=20,nullable=false) */
    protected $status = 'Active';
    
    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;
    
    public function getId     () { return $this->id;      }
    public function getAge    () { return $this->age;     }
    public function getOrg    () { return $this->org;     }
    public function getType   () { return $this->type;    }
    public function getDesc   () { return $this->desc1;   }
    public function getDesc1  () { return $this->desc1;   }
    public function getDesc2  () { return $this->desc2;   }
    public function getLevel  () { return $this->level;   }
    public function getGender () { return $this->gender;  }
    public function getSource () { return $this->source;  }
    public function getStatus () { return $this->status;  }
    public function getParent () { return $this->parent;  }
    public function getProject() { return $this->project; }
    
    public function setAge    ($value) { $this->onScalerPropertySet('age',    $value); }
    public function setOrg    ($value) { $this->onObjectPropertySet('org',    $value); }
    public function setType   ($value) { $this->onScalerPropertySet('type',   $value); }
    public function setDesc   ($value) { $this->onScalerPropertySet('desc1',  $value); }
    public function setDesc1  ($value) { $this->onScalerPropertySet('desc1',  $value); }
    public function setDesc2  ($value) { $this->onScalerPropertySet('desc2',  $value); }
    public function setLevel  ($value) { $this->onScalerPropertySet('level',  $value); }
    public function setGender ($value) { $this->onScalerPropertySet('gender', $value); }
    public function setSource ($value) { $this->onScalerPropertySet('source', $value); }
    public function setStatus ($value) { $this->onScalerPropertySet('status', $value); }
    public function setParent ($value) { $this->onObjectPropertySet('parent', $value); }
    public function setProject($value) { $this->onObjectPropertySet('project',$value); }
    
    // Custom getter/setters
    public function setKey($key) { $this->onScalerPropertySet('key1',$key); }
    public function getKey()     { return $this->key1; }
    
    public function getKeyx()
    {
        if ($this->key1)   return $this->key1;
        if ($this->parent) return $this->parent->getKeyx();
        return null;
    }
    public function setKeyx($value) {}
    
    public function setTeamKey($key) { $this->onScalerPropertySet('key1',$key); }
    public function getTeamKey()     { return $this->key1; }
     
    public function setTeamKeyExpanded($key) { $this->onScalerPropertySet('key2',$key); }
    public function getTeamKeyExpanded()     { return $this->key2; }
    
    public function setEaysoTeamId($key) { $this->onScalerPropertySet('key3',$key); }
    public function getEaysoTeamId()     { return $this->key3; }
    
    public function setEaysoTeamDesig($key) { $this->onScalerPropertySet('key4',$key); }
    public function getEaysoTeamDesig()     { return $this->key4; }
    
    public function setTeamName($name) { $this->set('teamName',$name); }
    public function getTeamName()      { return $this->get('teamName'); }
    
    public function setTeamColors($colors) { $this->set('teamColors',$colors); }
    public function getTeamColors()        { return $this->get('teamColors'); }
    
    public function setOrgDesc($desc) {}
    public function getOrgDesc() 
    {
        if (!$this->org) return null;
        return $this->org->getDesc2();
    }
    public function getParentTeamKey()
    {
        if (!$this->parent) return null;
        return $this->parent->getTeamKey();
    }
    public function getParentForType($type)
    {
        if ($this->type == $type) return $this;
        if ($this->parent) return $this->parent->getParentForType($type);
        return null;
    }
    /* ========================================================
     * Always calculated on the fly
     */
    protected $pools;
    
    protected function setReportProp($name,$value)
    {
        $report = $this->get('report');
        $report[$name] = $value;
        $this->set('report',$report);
    }
    protected function addReportProp($name,$value)
    {
        $value += $this->getReportProp($name);
        $this->setReportProp($name,$value); 
    }
    protected function getReportProp($name)
    {
        $report = $this->get('report');
        if (isset($report[$name])) return $report[$name];
        return null;
    }
    
    public function setPointsEarned($value) { $this->setReportProp('pointsEarned',$value); }
    public function addPointsEarned($value) { $this->addReportProp('pointsEarned',$value); }
    public function getPointsEarned(){ return $this->getReportProp('pointsEarned'); }
    
    public function setPointsMinus($value) { $this->setReportProp('pointsMinus',$value); }
    public function addPointsMinus($value) { $this->addReportProp('pointsMinus',$value); }
    public function getPointsMinus(){ return $this->getReportProp('pointsMinus'); }
    
    public function setGoalsScored($value) { $this->setReportProp('goalsScored',$value); }
    public function addGoalsScored($value) { $this->addReportProp('goalsScored',$value); }
    public function getGoalsScored(){ return $this->getReportProp('goalsScored'); }
    
    public function setGoalsAllowed($value) { $this->setReportProp('goalsAllowed',$value); }
    public function addGoalsAllowed($value) { $this->addReportProp('goalsAllowed',$value); }
    public function getGoalsAllowed(){ return $this->getReportProp('goalsAllowed'); }
    
    public function setCautions($value) { $this->setReportProp('cautions',$value); }
    public function addCautions($value) { $this->addReportProp('cautions',$value); }
    public function getCautions() {return $this->getReportProp('cautions'); }
    
    public function setSendoffs($value) { $this->setReportProp('sendoffs',$value); }
    public function addSendoffs($value) { $this->addReportProp('sendoffs',$value); }
    public function getSendoffs(){ return $this->getReportProp('sendoffs'); }
    
    public function setCoachTossed($value) { $this->setReportProp('coachTossed',$value); }
    public function addCoachTossed($value) { $this->addReportProp('coachTossed',$value); }
    public function getCoachTossed(){ return $this->getReportProp('coachTossed'); }
    
    public function setSpecTossed($value) { $this->setReportProp('specTossed',$value); }
    public function addSpecTossed($value) { $this->addReportProp('specTossed',$value); }
    public function getSpecTossed(){ return $this->getReportProp('specTossed'); }
    
    public function setFudgeFactor($value) { $this->setReportProp('fudgeFactor',$value); }
    public function addFudgeFactor($value) { $this->addReportProp('fudgeFactor',$value); }
    public function getFudgeFactor(){ return $this->getReportProp('fudgeFactor'); }
    
    public function setSportsmanship($value) { $this->setReportProp('sportsmanship',$value); }
    public function addSportsmanship($value) { $this->addReportProp('sportsmanship',$value); }
    public function getSportsmanship(){ return $this->getReportProp('sportsmanship'); }
    
    public function setGamesPlayed($value) { $this->setReportProp('gamesPlayed',$value); }
    public function addGamesPlayed($value) { $this->addReportProp('gamesPlayed',$value); }
    public function getGamesPlayed(){ return $this->getReportProp('gamesPlayed'); }

}
?>
