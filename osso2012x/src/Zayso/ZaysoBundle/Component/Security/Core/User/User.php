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
        $aysoid = $this->person->getAysoid();
        if (!$aysoid) return 'AYSOID Not Found';

        $manager = $this->getEaysoManager();

        $vol = $this->getEaysoManager()->loadVolCerts($aysoid);

        if (!$vol) return 'AYSO Record Not Found For ' . $aysoid;

        $out = $vol->getId() . ', ' . $vol->getRegion() . ', MY' . $vol->getMemYear();

        $cert = $vol->getRefereeBadgeCertification();
        if ($cert) $out .= ', ' . $cert->getDescription();

        $cert = $vol->getSafeHavenCertification();
        if ($cert) $out .= ', ' . $cert->getDescription();

        return $out;
    }
}
?>
