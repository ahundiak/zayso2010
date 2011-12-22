<?php
/* =====================================================================
 * Sits in from of a real account person object and flattens things out
 * Suitable for creating, editing and perhaps reporting
 */
namespace Zayso\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class AccountPersonAyso
{
    protected $accountPerson = null;

    public function getAccountPerson()
    {
        if (!$this->accountPerson)
        {
            $this->accountPerson = new AccountPerson();
            $this->accountPerson->setAsPrimary();
        }
        return $this->accountPerson;
    }
    public function setAccountPerson($accountPerson)
    {
        $this->accountPerson = $accountPerson;
    }
    /* ==================================================================
     * Account Interface
     * Trick is to create the account if one is not needed
     * Assume all the default values are set correctly
     */
    public function getAccount()
    {
        $accountPerson = $this->getAccountPerson();

        $account = $accountPerson->getAccount();
        if ($account) return $account;

        $account = new Account();
        $accountPerson->setAccount($account); // Also calls account->setAccountPerson

        return $account;
    }
    /** @Assert\NotBlank(message="Account User Name cannot be blank.", groups={"create","edit"}) */
    public function getUserName () { return $this->getAccount()->getUserName(); }

    /** @Assert\NotBlank(message="Account User Password cannot be blank.", groups={"create","edit"}) */
    public function getUserPass () { return $this->getAccount()->getUserPass(); }

    // Setters are easy
    public function setUserName ($value) { $this->getAccount()->setUserName($value); }
    public function setUserPass ($value) { $this->getAccount()->setUserPass($value); }

    /* ==================================================================
     * Person Interface
     */
    public function getPerson()
    {
        $accountPerson = $this->getAccountPerson();

        $person = $accountPerson->getPerson();
        if ($person) return $person;

        $person = new Person();
        $accountPerson->setPerson($person); // Uni Directional

        return $person;
    }
    /** @Assert\NotBlank(message="First Name cannot be blank.", groups={"create","edit"}) */
    public function getFirstName () { return $this->getPerson()->getFirstName(); }

    /** @Assert\NotBlank(message="Last Name cannot be blank.", groups={"create","edit"}) */
    public function getLastName () { return $this->getPerson()->getLastName(); }

    public function getNickName () { return $this->getPerson()->getNickName(); }

    /**
     * @Assert\NotBlank(message="Email cannot be blank.",groups={"create","edit","add"})
     * @Assert\Email   (message="Email is not valid.",   groups={"create","edit","add"})
     */
    public function getEmail()     { return $this->getPerson()->getEmail();     }
    public function getDob()       { return $this->getPerson()->getDob();       }
    public function getGender()    { return $this->getPerson()->getGender();    }
    public function getCellPhone() { return $this->getPerson()->getCellPhone(); }

    // Setters
    public function setFirstName($value) { $this->getPerson()->setFirstName($value); }
    public function setLastName ($value) { $this->getPerson()->setLastName ($value); }
    public function setNickName ($value) { $this->getPerson()->setNickName ($value); }
    public function setEmail    ($value) { $this->getPerson()->setEmail    ($value); }
    public function setCellPhone($value) { $this->getPerson()->setCellPhone($value); }
    public function setDob      ($value) { $this->getPerson()->setDob      ($value); }
    public function setGender   ($value) { $this->getPerson()->setGender   ($value); }

    /* ================================================================
     * Region aka organization
     */
    public function getPersonOrg()
    {
        $person = $this->getPerson();
        
        $org = $person->getOrg();
        if ($org) return $org;

        $org = new Org();
        $person->setOrg($org); // Uni Directional

        return $org;
    }
    /**
     * @Assert\NotBlank(message="Region canot be blank", groups={"create","edit","add"})
     * @Assert\Regex(
     *     groups={"create","edit","add"},
     *     pattern="/^(AYSOR)?\d{4}$/",
     *     message="Region must be number, 1-9999.")
     */
    public function getRegion() { return $this->getPersonOrg()->getId();  }

    public function setRegion($value) { $this->getPersonOrg()->setId($value); }

    /* ================================================================
     * AYSO Registered Person
     */
    public function getRegisteredPersonAYSO()
    {
        $person = $this->getPerson();
        $registeredPerson = $person->getAysoRegisteredPerson();

        if ($registeredPerson) return $registeredPerson;

        $registeredPerson= new PersonRegistered();
        $registeredPerson->setRegType('AYSOV');
        $registeredPerson->setPerson($person); // BI Directional

        return $registeredPerson;
    }
    /**
     * @Assert\NotBlank(message="AYSOID cannot be blank", groups={"create","edit","add"})
     * @Assert\Regex(
     *     groups={"create","edit","add"},
     *     pattern="/^(AYSOV)?\d{8}$/",
     *     message="AYSOID must be 8-digit number")
     */
    public function getAysoid()    { return $this->getRegisteredPersonAYSO()->getRegKey();  }

    public function getRefBadge () { return $this->getRegisteredPersonAYSO()->getRefBadge();  }
    public function getRefDate  () { return $this->getRegisteredPersonAYSO()->getRefDate();   }
    public function getSafeHaven() { return $this->getRegisteredPersonAYSO()->getSafeHaven(); }
    public function getMemYear  () { return $this->getRegisteredPersonAYSO()->getMemYear();   }

    public function setAysoid   ($value) { $this->getRegisteredPersonAYSO()->setRegKey   ($value); }
    public function setRefBadge ($value) { $this->getRegisteredPersonAYSO()->setRefBadge ($value); }
    public function setRefDate  ($value) { $this->getRegisteredPersonAYSO()->setRefDate  ($value); }
    public function setSafeHaven($value) { $this->getRegisteredPersonAYSO()->setSafeHaven($value); }
    public function setMemYear  ($value) { $this->getRegisteredPersonAYSO()->setMemYear  ($value); }

    /* ==================================================================
     * Openid Interface
     */
    public function getFirstOpenid()
    {
        $accountPerson = $this->getAccountPerson();

        $openids = $accountPerson->getOpenids();
        if (count($openids)) return $openids[0];

        $openid = new AccountOpenid();
        $openid->setAccountPerson($accountPerson); // BI

        return $openid;
    }
    public function getOpenidDisplayName() { return $this->getFirstOpenid()->getDisplayName();  }
    public function getOpenidProvider   () { return $this->getFirstOpenid()->getProvider   (); }
    public function getOpenidIdentifier () { return $this->getFirstOpenid()->getIdentifier ();   }

    public function setOpenidDisplayName($value) { $this->getFirstOpenid()->setDisplayName($value); }
    public function setOpenidProvider   ($value) { $this->getFirstOpenid()->setProvider   ($value); }
    public function setOpenidIdentifier ($value) { $this->getFirstOpenid()->setIdentifier ($value); }

    /* ==================================================================
     * Project Data is different because really need a reference to the actual project
     * But maybe not, should be able to use the same trick as org
     */
    public function getFirstProjectPerson()
    {
        $person = $this->getPerson();

        $projectPersons = $person->getProjectPersons();
        if (count($projectPersons)) return $projectPersons[0]; // Exception if > 1?

        $project = new Project();
        $projectPerson = new ProjectPerson();
        $projectPerson->setProject($project); // UNI
        $projectPerson->setPerson ($person);  // BI

        return $projectPerson;
    }
    public function setProjectPersonData($projectId,$projectData = array())
    {
        $projectPerson = $this->getFirstProjectPerson();
        $projectPerson->getProject()->setId($projectId);
        foreach($projectData as $name => $value)
        {
            $projectPerson->set($name,$value);
        }
    }
}
?>
