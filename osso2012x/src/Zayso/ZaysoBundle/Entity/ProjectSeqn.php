<?php

namespace Zayso\ZaysoBundle\Entity;

/**
 * @Entity
 * @ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @Table(name="project_seqn")
 */
class ProjectSeqnItem
{
    /**
     * @Id
     * @Column(type="integer",name="id")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project = null;

    /** @Column(type="string",name="name") */
    protected $name = '';

    /** @Column(type="integer",name="seqn") */
    protected $seqn = 1000;
    
    /** @Column(type="integer",name="version") @Version */
    protected $version;


}

?>
