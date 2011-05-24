<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class OSSOSchTeamItemProxy extends \OSSO\SchTeamItem implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function getId()
    {
        $this->_load();
        return parent::getId();
    }

    public function getOrg()
    {
        $this->_load();
        return parent::getOrg();
    }

    public function getDiv()
    {
        $this->_load();
        return parent::getDiv();
    }

    public function getDesc()
    {
        $this->_load();
        return parent::getDesc();
    }

    public function getPhyTeamId()
    {
        $this->_load();
        return parent::getPhyTeamId();
    }

    public function getPhyTeam()
    {
        $this->_load();
        return parent::getPhyTeam();
    }

    public function __get($name)
    {
        $this->_load();
        return parent::__get($name);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'projectId', 'phyTeamId', 'phyTeam', 'orgId', 'org', 'divId', 'div', 'typeId', 'desc');
    }
}