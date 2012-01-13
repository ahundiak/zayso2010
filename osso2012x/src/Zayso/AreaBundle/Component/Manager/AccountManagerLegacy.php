<?php
/* ===================================================
 * The legacy account manager takes care of copying data
 * from osso2007 into 2012
 */
namespace Zayso\AreaBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Entity\AccountOpenid;
use Zayso\CoreBundle\Entity\Account;
use Zayso\CoreBundle\Entity\AccountPerson;
use Zayso\CoreBundle\Entity\AccountPersonAyso;
use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\PersonRegistered;
use Zayso\CoreBundle\Entity\ProjectPerson;
use Zayso\CoreBundle\Entity\Project;

class AccountManagerLegacy
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
     * Call this to see if there is an openid in 2007
     */
    public function checkOpenid2007($identifier)
    {
        // See if even have one
        $openid2007 = $this->account2007Manager->checkOpenid($identifier);
        if (!$openid2007) return null;

        $userName = $openid2007->getAccount()->getAccountUser();

        // Verify that everything is hunky and dory
        $result = $this->checkAccount2007($userName);

        // All is well, account can be copied
        if (is_object($result)) return $result;

        /* ===============================================
         * Case where openid in 2007 but account in 2012 without openid
         * Also possible have account for aysoid in 2012
         *
         * Want to link openid to 2012 account
         * And possible copy over any other account people
         *
         * Only a few not worth the effort
         */
        return $result;

        die('TODO: Openid in 2007, account in 2012');
    }

    /* =======================================================
     * Call this when checking to see if there is an account in 2007
     * And that the account has all the needed info to copy into 2012
     */
    public function checkAccount2007($userName,$userPass = null)
    {
        // First see if an account exists
        $account2007 = $this->account2007Manager->checkAccount($userName);
        if (!$account2007) return sprintf('Account does not exist in old zayso: %s.',$userName);

        // Check if the passwords match
        if ($userPass && ($userPass != $account2007->getAccountPass()))
        {
            if ($userPass != $this->account2012Manager->getMasterPassword())
            {
                return sprintf('Invalid password for %s.',$userName);
            }
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
    protected function copyAccountPerson2007($accountPerson2007,$account2012)
    {
        $em = $this->getEntityManager();

        // Must have a person
        $person2007 = $accountPerson2007->getPerson();
        if (!$person2007) return null;

        // Person must have aysoid
        $aysoid = $person2007->getAysoid();
        if (!$aysoid) return null;
        $aysoidx = 'AYSOV' . $aysoid;
 
        // See if the 2012 account already has person attached (merge)
        foreach($account2012->getAccountPersons() as $accountPerson2012)
        {
            if ($aysoidx == $accountPerson2012->getAysoid()) return $accountPerson2012;
        }
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
        $person2012 = $this->account2012Manager->loadPersonForAysoid($aysoidx);
        if ($person2012)
        {
            // Project Person is up to the legacy controller
            $accountPerson2012->setPerson($person2012);
            //echo 'Existing Person ' . $person2012->getFirstName();
            return $accountPerson2012;
        }
        // New person
        $person2012 = new Person();
        $person2012->setStatus('Active');
        $person2012->setVerified('2007');
        $accountPerson2012->setPerson($person2012);

        $person2012->setFirstName($person2007->getFirstName());
        $person2012->setLastName ($person2007->getLastName());
        $person2012->setNickName ($person2007->getNickName());

        // Handle region, create new org if necessary
        $regionKey = 'AYSO' . $person2007->getRegionKey();
        $org = $this->account2012Manager->loadOrg($regionKey,true);
        $person2012->setOrg($org);

        // Registered
        $registeredPerson2012 = new PersonRegistered();
        $registeredPerson2012->setRegType ('AYSOV');
        $registeredPerson2012->setRegKey  ($aysoidx);
        $registeredPerson2012->setVerified('2007');
        $registeredPerson2012->setPerson($person2012);

        // Vol info
        $registeredPerson2012->setMemYear  ($vol2007->getMemYear());
        $registeredPerson2012->setRefBadge ($vol2007->getRefBadge());
        $registeredPerson2012->setRefDate  ($vol2007->getRefDate());
        $registeredPerson2012->setSafeHaven($vol2007->getSafeHaven());

      //$registeredPerson->setCoachBadge($vol->getCoachBadge());
      //$registeredPerson->setCoachDate ($vol->getSafeHaven());

        // Back to some person info
        $person2012->setGender($vol2007->getGender());
        $person2012->setDob   ($vol2007->getDob());
        
        $phoneTransformer = new PhoneTransformer();
        $phone = $phoneTransformer->reverseTransform($person2007->getPhone());
        if (!$phone)
        {
            $phone = $phoneTransformer->reverseTransform($vol2007->getPhone());
        }
        $person2012->setCellPhone($phone);
        
        $email = $person2007->getEmail();
        if (!$email) $email = $vol2007->getEmail();
        $person2012->setEmail($email);
        
        // Persist
        $em->persist($person2012);
        $em->persist($registeredPerson2012);

        // Done
        return $accountPerson2012;

    }
    public function copyAccount2007($account2007)
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
            $this->copyAccountPerson2007($accountPerson2007,$account2012);
        }
        // And save
        try
        {
            $em->flush();
        }
        catch (\Exception $e)
        {
            return 'Problem flushing copied 2007 account';
            // QLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '123456789' for key 'aysoid'
            return $e->getMessage();
        }
        return $account2012;
    }
    /* ===================================================================
     * Utility routine for manually merging two accounts
     */
    public function merge($userName2007,$userName2012,$projectId)
    {
        $em = $this->account2012Manager->getEntityManager();
        
        // Get the accounts
        $account2012 = $this->account2012Manager->loadAccountForUserName($userName2012);
        if (!is_object($account2012)) return null;
        
        $account2007 = $this->account2007Manager->checkAccount($userName2007);
        if (!is_object($account2007)) return $account2012;

        // Process each person
        foreach($account2007->getMembers() as $accountPerson2007)
        {
            $accountPerson2012 = $this->copyAccountPerson2007($accountPerson2007,$account2012);
            if (!$accountPerson2012) break;
            
            // Deal with project
            $person = $accountPerson2012->getPerson();
            $projectPerson = $person->getProjectPerson($projectId);
            if (!$projectPerson)
            {
                $project = $em->getReference('ZaysoCoreBundle:Project',$projectId);
                 
                $projectPerson = new ProjectPerson();
                $projectPerson->setProject($project);
                $projectPerson->setPerson ($person);
                
                $em->persist($projectPerson);
            }
        }
        $em->flush();
        
        // Done
        return $account2012;
    }
}
?>
