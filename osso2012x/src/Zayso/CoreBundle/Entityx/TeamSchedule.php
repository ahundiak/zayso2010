<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\CoreBundle\Component\Debug;

/**
 * @ORM\Entity
 * @ORM\Table(name="team_schedule",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="project_key1", columns={"project_id", "key1"}),
         @ORM\UniqueConstraint(name="project_key2", columns={"project_id", "key2"}),
         @ORM\UniqueConstraint(name="project_key3", columns={"project_id", "key3"}),
         @ORM\UniqueConstraint(name="project_key4", columns={"project_id", "key4"})
   })
 * 
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"schedule" = "TeamSchedule", "schedule_rs" = "TeamScheduleRS", "schedule_tourn" = "TeamScheduleTourn"})
 */
class TeamSchedule extends TeamBase
{   
    // Need anything else? - Nope
    protected $discr = 'schedule';
    
    /**
     * @ORM\ManyToOne(targetEntity="TeamPhysical")
     * @ORM\JoinColumn(name="team_physical_id", referencedColumnName="id")
     */
    protected $teamPhysical = null;    

    /* =========================================================
     * Standard getter/setter
     */    
    public function setTeamPhysical($team) { $this->teamPhysical = $team; }
    public function getTeamPhysical()      { return $this->teamPhysical; }
    
}

?>
