<?php
/* ===================================================
 * Tuck all the stuff needed to pull accounts from 2007 here
 * Eventually it should probably extend from Account Manager
 *
 * It's kind of confusing because it also uses the actual osso2007 account manager
 * Needs a much better name
 */
namespace Zayso\ZaysoAreaBundle\Component\Manager;

use Zayso\ZaysoCoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\ZaysoCoreBundle\Entity\Account;
use Zayso\ZaysoCoreBundle\Entity\AccountPerson;
use Zayso\ZaysoCoreBundle\Entity\AccountOpenid;

use Zayso\ZaysoCoreBundle\Entity\Person;
use Zayso\ZaysoCoreBundle\Entity\PersonRegistered;

use Zayso\ZaysoCoreBundle\Entity\Project;
use Zayso\ZaysoCoreBundle\Entity\ProjectPerson;

class AccountManager2007
{
    protected $em                 = null; // Accounts em 2012
    protected $account2007Manager = null; // osso2007 account manager
    protected $account2012Manager = null; // osso2012 account manager
    protected $volEaysoManager    = null; // eayso 2007 volunteer manager

    public function __construct($em, $account2012Manager, $account2007Manager, $volEaysoManager)
    {
        $this->em = $em;
        $this->account2012Manager = $account2012Manager;
        $this->account2007Manager = $account2007Manager;
        $this->volEaysoManager    = $volEaysoManager;
    }
    protected function getEntityManager() { return $this->em; }
    
    /* =======================================================
     * Call this when checking to see if there is an account in 2007
     * And that the account has all the needed info to copy into 2012
     */
    public function checkAccount2007($userName,$userPass = null)
    {
        // First see if an account exists
        $account2007 = $this->account2007Manager->checkAccount($userName);
        if (!$account2007) return sprintf('Account does not exist %s.',$userName);

        // Check if the passwords match
        if ($userPass && ($userPass != $account2007->getAccountPass()))
        {
            return sprintf('Invalid password for %s.',$userName);
        }
        // Must have a primary member
        $accountPerson2007 = $account2007->getPrimaryMember();
        if (!$accountPerson2007)
        {
            return sprintf('No primary account person for %s.',$userName);
        }
        // Must have a person
        $person2007 = $accountPerson2007->getPerson();
        if (!$person2007)
        {
            return sprintf('No person for %s.',$userName);
        }
        // Need some sort of region
        $region = $person2007->getRegionKey();
        if (!$region)
        {
            return sprintf('No region for %s.',$userName);
        }

        // Person must have aysoid
        $aysoid = $person2007->getAysoid();
        if (!$aysoid)
        {
            return sprintf('Person does not have an AYSOID for %s.',$userName);
        }
        // Need volunteer information
        $vol2007 = $this->volEaysoManager->loadVolCerts($aysoid);
        if (!$vol2007)
        {
            return sprintf('Invalid AYSOID %s for %s.',$aysoid,$userName);
        }
        // Make sure primary account for aysoid does not already exist
        $account2012 = $this->account2012Manager->loadAccountForAysoid('AYSOV' . $aysoid);
        if ($account2012)
        {
            return sprintf('Already have account for aysoid %s, %s, %s.',$aysoid,$account2012->getUserName(),$userName);
        }
        // Make sure account does not already exist
        $account2012 = $this->account2012Manager->loadAccountForUserName($userName);
        if ($account2012)
        {
            return sprintf('Already have account for %s.',$userName);
        }

        // Everything seems hunky and or dory
        return $account2007;
    }
    /* =======================================================
     * Given an 2007 account, import it into 2012
     * Return account on success
     * Return error message on failure
     *
     * However, by the time this gets called, the check routine shuld already
     * have been run and so there really should not be any errors
     */
    protected function importAccountPerson2007($accountPerson2007,$account2012,$project)
    {
        $em = $this->getEntityManager();

        // Must have a person
        $person2007 = $accountPerson2007->getPerson();
        if (!$person2007) return null;

        // Person must have aysoid
        $aysoid = $person2007->getAysoid();
        if (!$aysoid) return null;

        // Need volunteer information
        $vol2007 = $this->volEaysoManager->loadVolCerts($aysoid);
        if (!$vol2007) return null;

        // Translate the account relation
        switch($accountPerson2007->getLevel())
        {
            case 1:  $relation = 'Primary'; break;
            case 2:  $relation = 'Family'; break;
            default: $relation = 'Unknown';
        }
        // Have enough for the account person
        $accountPerson2012 = new AccountPerson();
        $accountPerson2012->setAccountRelation($relation);
        $accountPerson2012->setVerified('2007');
        $accountPerson2012->setStatus('Active');
        $accountPerson2012->setAccount($account2012);
        $em->persist($accountPerson2012);

        // See if already have a person for this aysoid
        $person2012 = $this->account2012Manager->loadPersonForAysoid($aysoid);
        if ($person2012)
        {
            // Fool with project stuff
            return;
        }
        // New person
        $person2012 = new Person();
        $person2012->setStatus('Active');
        $person2012->setVerified('2007');
        $accountPerson2012->setPerson($person2012);

        $person2012->setFirstName($person2007->getFirstName());
        $person2012->setLastName ($person2007->getLastName());
        $person2012->setNickName ($person2007->getNickName());

        $person2012->setOrgKey   ('AYSO' . $person2007->getRegionKey());

        // Registered
        $registeredPerson2012 = new PersonRegistered();
        $registeredPerson2012->setRegType ('AYSOV');
        $registeredPerson2012->setRegKey  ('AYSOV' . $aysoid);
        $registeredPerson2012->setVerified('2007');
        $registeredPerson2012->setPerson($person2012);

        // Vol info
        $registeredPerson2012->setMemYear  ($vol2007->getMemYear());
        $registeredPerson2012->setRefBadge ($vol2007->getRefBadge());
        $registeredPerson2012->setRefDate  ($vol2007->getRefDate());
        $registeredPerson2012->setSafeHaven($vol2007->getSafeHaven());

      //$registeredPerson->setCoachBadge($vol->getCoachBadge());
      //$registeredPerson->setCoachDate ($vol->getSafeHaven());

        $person2012->setGender($vol2007->getGender());
        $person2012->setDob   ($vol2007->getDob());

        // Persist
        $em->persist($person2012);
        $em->persist($registeredPerson2012);

        // Project
        if ($project)
        {

        }
        // Done
        return $accountPerson2012;

    }
    public function importAccount2007($account2007,$project = null)
    {
        $em = $this->getEntityManager();

        // Account
        $account2012 = new Account();
        $account2012->setStatus('Active');
        $account2012->setUserName($account2007->getAccountUser());
        $account2012->setUserPass($account2007->getAccountPass());
        $em->persist($account2012);

        // Bring in each person
        foreach($account2007->getMembers() as $accountPerson2007)
        {
            $this->importAccountPerson2007($accountPerson2007,$account2012,$project);
        }
        // And save
        try
        {
            $em->flush();
        }
        catch (\Exception $e)
        {
            // QLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '123456789' for key 'aysoid'
            return $e->getMessage();
        }
        return $account2012;

        // Account Person
        $accountPerson = new AccountPerson();
        $accountPerson->setAccountRelation('Primary');
        $accountPerson->setVerified('No');
        $accountPerson->setStatus('Active');
        $accountPerson->setAccount($account);

        // Person
        $person = new Person();
        $person->setStatus('Active');
        $person->setVerified('No');
        $accountPerson->setPerson($person);

        $accountPerson2007 = $account2007->getPrimaryMember();
        $person2007 = $accountPerson2007->getPerson();

        $person->setFirstName($person2007->getFirstName());
        $person->setLastName ($person2007->getLastName());
        $person->setNickName ($person2007->getNickName());

        $person->setOrgKey   ('AYSO' . $person2007->getRegionKey());

        // Registered
        $aysoid = $person2007->getAysoid();
        $registeredPerson = new PersonRegistered();
        $registeredPerson->setRegType ('AYSOV');
        $registeredPerson->setRegKey  ('AYSOV' . $aysoid);
        $registeredPerson->setVerified('No');
        $registeredPerson->setPerson($person);

        // Vol into
        $vol = $this->volEaysoManager->loadVolCerts($aysoid);

        $registeredPerson->setMemYear  ($vol->getMemYear());
        $registeredPerson->setRefBadge ($vol->getRefBadge());
        $registeredPerson->setRefDate  ($vol->getRefDate());
        $registeredPerson->setSafeHaven($vol->getSafeHaven());

      //$registeredPerson->setCoachBadge($vol->getCoachBadge());
      //$registeredPerson->setCoachDate ($vol->getSafeHaven());

        $person->setGender($vol->getGender());
        $person->setDob   ($vol->getDob());

        // And save
        $em->persist($account);
        $em->persist($accountPerson);
        $em->persist($person);
        $em->persist($registeredPerson);
        //$em->flush();
        return $account;
    }

}
?>
