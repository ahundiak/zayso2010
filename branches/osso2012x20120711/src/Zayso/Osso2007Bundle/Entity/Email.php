<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity
 */
class Email
{
    /**
     * @var integer $emailId
     *
     * @ORM\Column(name="email_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emailId;

    /**
     * @var integer $emailTypeId
     *
     * @ORM\Column(name="email_type_id", type="integer", nullable=true)
     */
    private $emailTypeId;

   /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="person_id")
     */
    private $person;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", length=40, nullable=true)
     */
    private $address;



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

    /**
     * Get emailId
     *
     * @return integer 
     */
    public function getEmailId()
    {
        return $this->emailId;
    }
}