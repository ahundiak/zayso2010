<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\CoreBundle\Component\Debug;

/**
 * @ORM\Entity
 * @ORM\Table(name="team_physical",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="project_key1", columns={"project_id", "key1"}),
         @ORM\UniqueConstraint(name="project_key2", columns={"project_id", "key2"}),
         @ORM\UniqueConstraint(name="project_key3", columns={"project_id", "key3"}),
         @ORM\UniqueConstraint(name="project_key4", columns={"project_id", "key4"})
   })
 * 
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"physical" = "TeamPhysical", "physical_eayso" = "TeamPhysicalEayso"})
 */
class TeamPhysical extends TeamBase
{
    /**  ORM\Column(type="string",name="discr",nullable=false) */
    protected $discr = 'physical';
    
    /**
     * @ORM\ManyToOne(targetEntity="TeamPhysical")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent = null;
    
    /* =========================================================
     * Standard getter/setter
     */    
    public function setTeamName($name) { $this->set('teamName',$name); }
    public function getTeamName()      { return $this->get('teamName'); }
    
    public function setTeamColors($colors) { $this->set('teamColors',$colors); }
    public function getTeamColors()        { return $this->get('teamColors'); }

}

?>
