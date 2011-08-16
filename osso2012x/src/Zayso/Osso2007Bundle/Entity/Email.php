<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Email
 */
class Email
{
    /**
     * @var integer $emailId
     */
    private $emailId;

    /**
     * @var integer $emailTypeId
     */
    private $emailTypeId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var string $address
     */
    private $address;


    /**
     * Get emailId
     *
     * @return integer 
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     * Set emailTypeId
     *
     * @param integer $emailTypeId
     */
    public function setEmailTypeId($emailTypeId)
    {
        $this->emailTypeId = $emailTypeId;
    }

    /**
     * Get emailTypeId
     *
     * @return integer 
     */
    public function getEmailTypeId()
    {
        return $this->emailTypeId;
    }

    /**
     * Set personId
     *
     * @param integer $personId
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    /**
     * Get personId
     *
     * @return integer 
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
}