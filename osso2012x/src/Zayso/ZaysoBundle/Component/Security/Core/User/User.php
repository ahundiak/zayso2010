<?php
/* 
 * User Component that knows about entity manager
 * Almost a service?
 */
namespace Zayso\ZaysoBundle\Component\Security\Core\User;

class User
{
    protected $em;
    protected $account = null;
    protected $member  = null; // Current user
    protected $person  = null;
    protected $projectId = 0;
    protected $projectPerson = null;

    protected $services;
    protected function getEntityManager() { return $this->services->get('doctrine')->getEntityManager(); }
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
            case '99437977': // Art H
            case '90001476': // David Holt
            case '98037803': // Jack Graham
            case '53319472': // Diane S
                    
                return true;
        }
        return false;
    }
}
?>
