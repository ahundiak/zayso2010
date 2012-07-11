<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class TeamScheduleTourn extends TeamSchedule
{
    protected $discr = 'schedule_tourn';
    
    // Custom getter/setters
    //public function setEaysoTeamId($key) { $this->key3 = $key; }
    //public function getEaysoTeamId()     { return $this->key3; }
    
    //public function setEaysoTeamDesig($key) { $this->key4 = $key; }
    //public function getEaysoTeamDesig()     { return $this->key4; }

}
?>
