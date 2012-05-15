<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\CoreBundle\Component\Debug;

/**
 * @ORM\Entity
 * @ORM\Table(name="person_team",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="person_team_type", columns={"person_id", "team_id", "type"})
   })
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class PersonTeamRel extends BaseEntity
{   
    const TypeHeadCoach  = 'HeadCoach';
    const TypeAsstCoach  = 'AsstCoach';
    const TypeManager    = 'Manager';
    
    const TypeParent   = 'Parent';
    const TypePlayer   = 'Player';
    const TypeSpec     = 'Spectator';
    
    const TypeConflict = 'Conflict';
    const TypeBlocked  = 'Blocked'; // ByPerson, ByTeam, ByAdmin
    const TypeBlockedByPerson  = 'BlockedByPerson'; // ByPerson, ByTeam, ByAdmin
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
    */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="teamRels")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="personRels")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    protected $team = null;
    
    /** 
     * Relation
     * @ORM\Column(type="string",name="type",length=20,nullable=false) 
     */
    protected $type = '';
    
    /** 
     * Priority for points system
     * @ORM\Column(type="integer",name="priority",nullable=false) 
     */
    protected $priority = 0;

    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;

    protected $delete = false;
    public function getDelete()        { return $this->delete; }
    public function setDelete($delete) { $this->delete = $delete; }
    
    /* =========================================================
     * Standard getter/setter
     */    
    public function getId()    { return $this->id; }
    public function setId($id) { return $this->id = $id; }
    
    public function setType($type) { $this->onScalerPropertySet('type', $type); }
    public function getType()      { return $this->type;  }
    
    public function setPriority($priority) { $this->onScalerPropertySet('priority', $priority); }
    public function getPriority()          { return $this->priority;  }
    
    public function setTeam($team) { $this->onObjectPropertySet('team', $team); }
    public function getTeam()      { return $this->team;  }
    
    public function setPerson($person) { $this->onObjectPropertySet('person', $person); }
    public function getPerson()        { return $this->person;  }

}
?>
