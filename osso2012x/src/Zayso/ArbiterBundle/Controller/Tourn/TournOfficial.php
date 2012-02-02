<?php
namespace Zayso\ArbiterBundle\Controller\Tourn;

use Symfony\Component\Validator\Constraints as Assert;

class TournOfficial
{
    public $firstName;
    public $lastName;
    public $nickName;
    
    public $age    = 10;
    public $gender = 'M';
    
    public $email;
    public $cellPhone;
    public $homeState = 'AL';
    public $homeCity;
    public $travelingWith;
    
    public $assessmentRequest = 'None';
    public $lodgingRequest = 'None';
    public $lodgingWith;
    public $teamAff = 'None';
    public $teamAffDesc;
    
    public $ussfid;
    public $refBadge = 'Grade 8';
    public $refState = 'AL';
    public $refExp   = 0;
    
    public $levelToRef = 'Competitive';
    public $comfortLevelCenter    = 'U10B';
    public $comfortLevelAssistant = 'U10B';
    
    public $availFri = 'None';
    public $availSat = 'None';
    public $availSun = 'None';
    
    public $notes;
    
    /** @Assert\NotBlank(message="First Name cannot be blank.", groups={"create","edit"}) */
    public function getFirstName () { return $this->firstName; }

    /** @Assert\NotBlank(message="Last Name cannot be blank.", groups={"create","edit"}) */
    public function getLastName () { return $this->lastName; }

    public function getNickName () { return $this->nickName; }

    /**
     * @Assert\NotBlank(message="Email cannot be blank.",groups={"create","edit","add"})
     * @Assert\Email   (message="Email is not valid.",   groups={"create","edit","add"})
     */
    public function getEmail() { return $this->email; }
    
    /**
     *  Assert\NotBlank(message="USSF ID cannot be blank", groups={"create","edit","add"})
     * @Assert\Regex(
     *     groups={"create","edit","add"},
     *     pattern="/^((USSFR)?\d{16})?$/",
     *     message="USSF ID must be 16-digit number")
     * 
     * The ? near the end allows blanks to match otherwise must be valid
     */
    public function getUssfid() { return $this->ussfid;  }
    
    public function __get($name)
    {
        switch($name)
        {
            case 'lodgingFri': return $this->getLodgingFri();                 
            case 'lodgingSat': return $this->getLodgingSat();
        }
        return null;
    }
    public function getLodgingFri()
    {
        switch($this->lodgingRequest)
        {
            case 'Fri':  return 'Yes';
            case 'Both': return 'Yes';
        }
        return 'No';
    }
    public function getLodgingSat()
    {
        switch($this->lodgingRequest)
        {
            case 'Sat':  return 'Yes';
            case 'Both': return 'Yes';
        }
        return 'No';
    }
}
?>
