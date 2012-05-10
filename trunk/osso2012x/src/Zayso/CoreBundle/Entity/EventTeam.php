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
    
    public function getGoalsScored()   { return $this->get('goalsScored');   }
    public function getGoalsAllowed()  { return $this->get('goalsAllowed');  }
    public function getCautions()      { return $this->get('cautions');      }
    public function getSendoffs()      { return $this->get('sendoffs');      }
    public function getSportsmanship() { return $this->get('sportsmanship'); }
    public function getFudgeFactor()   { return $this->get('fudgeFactor');   }
    public function getPointsEarned()  { return $this->get('pointsEarned');  }
    public function getPointsMinus()   { return $this->get('pointsMinus');   }

    public function setGoalsScored  ($value) { $this->set('goalsScored',  $value); }
    public function setGoalsAllowed ($value) { $this->set('goalsAllowed', $value); }
    public function setCautions     ($value) { $this->set('cautions',     $value); }
    public function setSendoffs     ($value) { $this->set('sendoffs',     $value); }
    public function setSportsmanship($value) { $this->set('sportsmanship',$value); }
    public function setFudgeFactor  ($value) { $this->set('fudgeFactor',  $value); }
    public function setPointsEarned ($value) { $this->set('pointsEarned', $value); }
    public function setPointsMinus  ($value) { $this->set('pointsMinus',  $value); }
    
    public function clearReportInfo()
    {
        $this->setPointsEarned(null);
        $this->setPointsMinus (null);
    }
}
?>
