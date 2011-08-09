<?php
namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @ORM\Table(name="project_seqn")
 *
 * The deferred explicit means that you have to do $em->persist after making changes
 * Not sure if it really helps here ot not since almost always update after retrieving
 */
class ProjectSeqn
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
    protected $project = null;

    /** @ORM\Column(type="string",name="name") */
    protected $name = '';

    /** @ORM\Column(type="integer",name="seqn") */
    protected $seqn = 1000;
    
    /** @ORM\Column(type="integer",name="version") @ORM\Version */
    protected $version;

    /* =========================================================
     * Generated Code
     */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set seqn
     *
     * @param integer $seqn
     */
    public function setSeqn($seqn)
    {
        $this->seqn = $seqn;
    }

    /**
     * Get seqn
     *
     * @return integer 
     */
    public function getSeqn()
    {
        return $this->seqn;
    }

    /**
     * Set version
     *
     * @param integer $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return integer 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set project
     *
     * @param Zayso\ZaysoBundle\Entity\Project $project
     */
    public function setProject(\Zayso\ZaysoBundle\Entity\Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return Zayso\ZaysoBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}