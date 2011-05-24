<?php

namespace OSSO2012\Project;

/**
 * @Entity
 * @ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @Table(name="osso2012.project_seqn")
 */
class ProjectSeqnItem
{
    /**
     * @Id
     * @Column(type="integer",name="id")
     * @GeneratedValue
     */
    public $id;

    /** @Column(type="integer",name="version") @Version */
    private $version;

    /** @Column(type="string",name="key1") */
    private $key1;
    
    /** * @Column(type="integer",name="project_id") */
    private $projectId;

    /** @Column(type="integer",name="seqn") */
    private $seqn;

    public function getId()      { return $this->id; }
    public function getKey1()    { return $this->key1; }
    public function getSeqn()    { return $this->seqn; }
    public function getVersion() { return $this->version; }

    public function setProjectId($value) { $this->projectId = $value; }
    public function setKey1($value)      { $this->key1 = $value; }
    public function setSeqn($value)      { $this->seqn = $value; }

}

?>
