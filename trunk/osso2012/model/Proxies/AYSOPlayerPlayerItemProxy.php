<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class AYSOPlayerPlayerItemProxy extends \AYSO\Player\PlayerItem implements \Doctrine\ORM\Proxy\Proxy
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

    public function getRegionId()
    {
        $this->_load();
        return parent::getRegionId();
    }

    public function getFirstName()
    {
        $this->_load();
        return parent::getFirstName();
    }

    public function getLastName()
    {
        $this->_load();
        return parent::getLastName();
    }

    public function getNickName()
    {
        $this->_load();
        return parent::getNickName();
    }

    public function getMiddleName()
    {
        $this->_load();
        return parent::getMiddleName();
    }

    public function getSuffix()
    {
        $this->_load();
        return parent::getSuffix();
    }

    public function getPhoneHome()
    {
        $this->_load();
        return parent::getPhoneHome();
    }

    public function getEmail()
    {
        $this->_load();
        return parent::getEmail();
    }

    public function getDOB()
    {
        $this->_load();
        return parent::getDOB();
    }

    public function getGender()
    {
        $this->_load();
        return parent::getGender();
    }

    public function getJerseySize()
    {
        $this->_load();
        return parent::getJerseySize();
    }

    public function getJerseyNumber()
    {
        $this->_load();
        return parent::getJerseyNumber();
    }

    public function setId($value)
    {
        $this->_load();
        return parent::setId($value);
    }

    public function setRegionId($value)
    {
        $this->_load();
        return parent::setRegionId($value);
    }

    public function setFirstName($value)
    {
        $this->_load();
        return parent::setFirstName($value);
    }

    public function setLastName($value)
    {
        $this->_load();
        return parent::setLastName($value);
    }

    public function setNickName($value)
    {
        $this->_load();
        return parent::setNickName($value);
    }

    public function setMiddleName($value)
    {
        $this->_load();
        return parent::setMiddleName($value);
    }

    public function setSuffix($value)
    {
        $this->_load();
        return parent::setSuffix($value);
    }

    public function setPhoneHome($value)
    {
        $this->_load();
        return parent::setPhoneHome($value);
    }

    public function setEmail($value)
    {
        $this->_load();
        return parent::setEmail($value);
    }

    public function setDOB($value)
    {
        $this->_load();
        return parent::setDOB($value);
    }

    public function setGender($value)
    {
        $this->_load();
        return parent::setGender($value);
    }

    public function setJerseySize($value)
    {
        $this->_load();
        return parent::setJerseySize($value);
    }

    public function setJerseyNumber($value)
    {
        $this->_load();
        return parent::setJerseyNumber($value);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'regionId', 'fname', 'lname', 'nname', 'mname', 'suffix', 'phoneHome', 'email', 'dob', 'gender', 'jerseySize', 'jerseyNumber', 'teams');
    }
}