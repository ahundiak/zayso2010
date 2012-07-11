<?php
/* ===========================================================
 * Needs to be persistable
 * Needs to know about roles and such
 * Don't really want it to hang on to entity manager
 *
 * Array (
 * [accountId] => 75
 * [userName] => Anna [userPass] => 32bytestring
 * [accountStatus] => Active [accountPersonId] => 75
 * [personId] => 80 [personFirstName] => Anna [personLastName] => Odom [personNickName] => Anna
 * [personOrgKey] => AYSOR0671 [aysoid] => AYSOV96411803
 * [dob] => 19580911 [gender] => F
 * [ref_badge] => Intermediate [ref_date] => 20080429 [safe_haven] => Coach [mem_year] => 2009 )
 */
namespace Zayso\ZaysoBundle\Component\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Role\Role;

class MyUser implements UserInterface
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function getData($name,$default = null)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return $default;
    }
    public function getUsername() { return $this->getData('userName'); }
  //function getUserName() { return $this->data['userName']; }

    public function getPassword() { return $this->getData('userPass'); }

    public function getRoles() 
    {
        $aysoid = $this->getData('aysoid');
        switch($aysoid)
        {
            case 'AYSOV99437977': // Art H
                return array(new Role('ROLE_SUPER_ADMIN'));
                
            case 'AYSOV90001476': // David Holt
            case 'AYSOV98037803': // Jack Graham
            case 'AYSOV53319472': // Diane S
            case 'AYSOV56063435': // Bob Deene
            case 'AYSOV96286066': // Tom B
            case 'AYSOV91000961': // Mike F
                return array(new Role('ROLE_ADMIN'));
        }
        return array(new Role('ROLE_USER'));
    }
    public function getPersonId()        { return $this->getData('personId'); }
    public function getAccountId()       { return $this->getData('accountId'); }
    public function getAccountPersonId() { return $this->getData('accountPersonId'); }
    
    public function setFirstName($value) { $this->data['personFirstName'] = $value; }
    public function setLastName ($value) { $this->data['personLastName' ] = $value; }
    public function setNickName ($value) { $this->data['personNickName' ] = $value; }
    
    public function getName()
    {
        $fname = $this->getData('personFirstName');
        $nname = $this->getData('personNickName');
        $lname = $this->getData('personLastName');

        if ($nname) $fname = $nname;

        return $fname . ' ' . $lname;
    }
    public function getAYSOCertsDescription()
    {
        $aysoid = substr($this->getData('aysoid'),4);
        if (!$aysoid) return 'AYSOID Not Found';

        $region = substr($this->getData('personOrgKey'),4);

        $memYear = 'MY' . $this->getData('mem_year');
        if ($memYear == 'MY')
        {
            return $region  . ', ' . $aysoid . ', ' . 'AYSO Information Not Yet Verified';
        }
        $refBadge = 'Referee Badge: ' . $this->getData('ref_badge');

        $safeHaven = $this->getData('safe_haven');
        if (($safeHaven == 'Coach') || ($safeHaven == 'Referee') || ($safeHaven == 'AYSO')) $safeHaven = 'Yes';
        else                                                                                $safeHaven = 'No';
        $safeHaven = 'Safe Haven: ' . $safeHaven;

        return $region  . ', ' . $aysoid . ', ' . $memYear . ', ' . $refBadge . ', ' . $safeHaven;
    }

    function getSalt()          { return null; }
    function eraseCredentials() { return; }
    
    // Thid does get called
    function equals(UserInterface $user) 
    { 
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        die('user.equals failed'); 
        return false;     
    }

}

class User
{
    protected $em;
    protected $account = null;
    protected $member  = null; // Current user
    protected $person  = null;
    protected $projectId = 0;
    protected $projectPerson = null;

    protected $services;
    protected function getEntityManager() { return $this->services->get('doctrine')->getEntityManager('osso2012'); }
    protected function getEaysoManager()  { return $this->services->get('eayso.manager'); }

    public function __construct($services)
    {
        $this->services = $services;
    }
    public function load($accountId, $memberId = 0, $projectId = 0)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('members');
        $qb->addSelect('person');
        $qb->addSelect('regs');

        $qb->from('ZaysoBundle:Account',   'account');
        $qb->leftJoin('account.members',   'members');
        $qb->leftJoin('members.person',    'person');
        $qb->leftJoin('person.registereds','regs');

        $qb->andWhere($qb->expr()->eq('account.id',$accountId));

        // die($qb->getDQL());
    
        $query = $qb->getQuery();
        $accounts = $query->getResult();

        if (count($accounts) != 1)
        {
            return false;  // Account was deleted
        }
        $this->account = $accounts[0];

        if ($memberId) $this->member = $this->account->getMember($memberId);
        else           $this->member = $this->account->getPrimaryMember();

        $this->person = $this->member->getPerson();

        // Project specific info
        $this->projectId = $projectId;
        $this->projectPerson = null;
        return true;

    }
    public function getAccount() { return $this->account; }
    public function getAccountPerson() { return $this->member; }
    
    public function getAccountPersonId()
    {
        if (!$this->member) return 0;
        return $this->member->getId();
    }
    
    public function getProjectPerson($projectId = 0)
    {
        if (!$this->projectPerson)
        {
            if (!$projectId) $projectId = $this->projectId;
            $projectRepo = $this->getEntityManager()->getRepository('ZaysoBundle:Project');
            $this->projectPerson = $projectRepo->loadProjectPerson($projectId,$this->person);
        }
        return $this->projectPerson;
    }
    public function isSignedIn()
    {
        if ($this->person) return true;
        return false;
    }
    public function isGuest()
    {
        if ($this->person) return false;
        return true;
    }
    public function getName()
    {
        $person = $this->person;
        if (!$person) return 'Guest';

        $fname = $person->getFirstName();
        $nname = $person->getNickName();
        $lname = $person->getLastName();

        if ($nname) $fname = $nname;

        return $fname . ' ' . $lname;
    }
    public function getAYSOCertsDescription()
    {
        $person = $this->person;
        if (!$person) return '';

        $registeredPerson = $person->getAysoRegisteredPerson();
        if (!$registeredPerson) return 'Not registered in ayso';

        $aysoid = substr($registeredPerson->getRegKey(),4);
        if (!$aysoid) return 'AYSOID Not Found';

        $region = substr($person->getOrgKey(),4);

        $memYear = 'MY' . $registeredPerson->getMemYear();
        if ($memYear == 'MY')
        {
            return $region  . ', ' . $aysoid . ', ' . 'AYSO Information Not Yet Verified';
        }
        $refBadge = 'Referee Badge: ' . $registeredPerson->getRefBadge();

        $safeHaven = $registeredPerson->getSafeHaven();
        if (($safeHaven == 'Coach') || ($safeHaven == 'Referee') || ($safeHaven == 'AYSO')) $safeHaven = 'Yes';
        else                                                                                $safeHaven = 'No';
        $safeHaven = 'Safe Haven: ' . $safeHaven;

        return $region  . ', ' . $aysoid . ', ' . $memYear . ', ' . $refBadge . ', ' . $safeHaven;
        
        $manager = $this->getEaysoManager();
        
        $vol = $this->getEaysoManager()->loadVolCerts($aysoid);

        if (!$vol) return 'AYSO Record Not Yet Verified For ' . $aysoid;

        $out = substr($vol->getId(),5) . ', ' . substr($vol->getRegion(),4) . ', MY' . $vol->getMemYear();

        $cert = $vol->getRefereeBadgeCertification();
        if ($cert) $out .= ', ' . $cert->getDescription();

        $cert = $vol->getSafeHavenCertification();
        if ($cert) $out .= ', ' . $cert->getDescription();

        return $out;
    }
    public function isAdmin()
    {
        $person = $this->person;
        if (!$person) return false;
        
        $aysoid = $person->getAysoid();
        if (!$aysoid) return false;
        
        switch($aysoid)
        {
            case 'AYSOV99437977': // Art H
            case 'AYSOV90001476': // David Holt
            case 'AYSOV98037803': // Jack Graham
            case 'AYSOV53319472': // Diane S
            case 'AYSOV56063435': // Bob Deene
            case 'AYSOV96286066': // Tom B
                return true;
        }
        return false;
    }
}
?>
