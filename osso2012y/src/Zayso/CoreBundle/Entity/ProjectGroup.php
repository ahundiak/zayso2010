<?php
namespace Zayso\CoreBundle\Entity;

class ProjectGroup extends BaseEntity
{
    protected $id;

    protected $key = null;

    protected $description = null;

    protected $status = 'Active';

    public function __construct()
    {
    }
    
    public function getId() { return $this->id; }

    public function setKey($key)                 { $this->onScalerPropertySet('key',$key); }
    public function getKey()                     { return $this->key; }

    public function setDescription($description) { $this->onScalerPropertySet('description',$description); }
    public function getDescription()             { return $this->description; }

    public function setStatus($status)           { $this->onScalerPropertySet('status',$status); }
    public function getStatus()                  { return $this->status; }
}