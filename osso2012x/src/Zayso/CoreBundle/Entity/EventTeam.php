<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\CoreBundle\Component\Debug;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_team",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="game_team_type", columns={"event_id", "team_id", "type"})
   })
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class EventTeam extends BaseEntity
{   
    const TypeHome = 'Home';
    const TypeAway = 'Away';
    
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
    protected $project = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="teams")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    protected $team = null;
    
    /** 
     * home, away etc
     * @ORM\Column(type="string",name="type",length=20,nullable=false) 
     */
    protected $type = '';

    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;

    /* =========================================================
     * Custom code
     */
    
    /* =========================================================
     * Standard getter/setter
     */    
    public function getId() { return $this->id; }
    
    public function setGame($event) { $this->setEvent($event); }
    public function getGame()       { return $this->event;     }
    
    public function setEvent($event) { $this->onObjectPropertySet('event', $event); }
    public function getEvent()       { return $this->event;  }
    
    public function setTypeAsHome() { $this->setType(self::TypeHome); }
    public function setTypeAsAway() { $this->setType(self::TypeAway); }
    
    public function setType($type) { $this->onScalerPropertySet('type', $type); }
    public function getType()      { return $this->type;  }
    
    public function setTeam($team) { $this->onObjectPropertySet('team', $team); }
    public function getTeam()      { return $this->team;  }

    public function getTeamKey()
    {
        if (!$this->team) return null;
        return $this->team->getTeamKey();
    }
    public function setTeamKey($key) { return; }
 /*   
    public function getGoalsScored()   { return $this->team->getGoalsScored() ;  }
    public function getGoalsAllowed()  { return $this->team->getGoalsAllowed();  }
    public function getCautions()      { return $this->team->getCautions();      }
    public function getSendoffs()      { return $this->team->getSendoffs();      }
    public function getSportsmanship() { return $this->team->getSportsmanship(); }
    public function getFudgeFactor()   { return $this->team->getFudgeFactor();   }
    public function getPointsEarned()  { return $this->team->getPointsEarned();  }
    public function getPointsMinus()   { return $this->team->getPointsMinus();   }

    public function setGoalsScored  ($value) { $this->team->setGoalsScored  ($value); }
    public function setGoalsAllowed ($value) { $this->team->setGoalsAllowed ($value); }
    public function setCautions     ($value) { $this->team->setCautions     ($value); }
    public function setSendoffs     ($value) { $this->team->setSendoffs     ($value); }
    public function setSportsmanship($value) { $this->team->setSportsmanship($value); }
    public function setFudgeFactor  ($value) { $this->team->setFudgeFactor  ($value); }
    public function setPointsEarned ($value) { $this->team->setPointsEarned ($value); }
    public function setPointsMinus  ($value) { $this->team->setPointsMinus  ($value); }
    
    public function clearReportInfo()
    {
        $this->setPointsEarned(null);
        $this->setPointsMinus (null);
    }*/
    protected $teamReport = null;
    
    public function getReport()
    {
        if ($this->teamReport) return $this->teamReport;
        
        $data = $this->get('report');
        if (!is_array($data)) $data = array();
        
        $this->teamReport = new TeamReport();
        
        $this->teamReport->setData($data);
        
        return $this->teamReport;
    }
    public function saveReport($teamReport = null)
    {
        if (!$teamReport) $teamReport = $this->teamReport;
    
        if (!$teamReport) return;
        
        $data = $teamReport->getData();
        
        $this->set('report',$data);
    }
}
?>
