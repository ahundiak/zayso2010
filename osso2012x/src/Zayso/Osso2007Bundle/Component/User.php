<?php
/* 
 * User Component that knows about entity manager
 * Almost a service?
 */
namespace Zayso\Osso2007Bundle\Component;

class User
{
    protected $em = null;

    protected $member   = null;
    protected $account  = null;
    protected $person   = null;
    protected $defaults = null;
    protected $projectId = 0;

    public function __construct($em,$data = array())
    {
        $this->em = $em;

        if (isset($data['member']))    $this->member    = $data['member'];
        if (isset($data['account']))   $this->account   = $data['account'];
        if (isset($data['person']))    $this->person    = $data['person'];
        if (isset($data['defaults']))  $this->defaults  = $data['defaults'];
        if (isset($data['projectId'])) $this->projectId = $data['projectId'];
    }
    public function isSignedIn()
    {
        if ($this->member) return true;
        return false;
    }
    public function isGuest()
    {
        if ($this->member) return false;
        return true;
    }
    public function getName()
    {
        if ($this->isGuest()) return 'Guest';
        if ($this->person)
        {
            $fname = $this->person->fname;
            $nname = $this->person->nname;
            $lname = $this->person->lname;
        }
        $person = $this->person;
        if (!$person) return 'Guest';

        if ($nname) $fname = $nname;

        return $fname . ' ' . $lname;
    }
    public function isAdmin()
    {
        if (!$this->isSignedIn()) return false;
        $aysoid = $this->person->aysoid;
        switch($aysoid)
        {
            case 99437977:  // Me
                return true;
        }
        return false;
    }
}
class User2012
{
    protected $em;
    protected $account = null;
    protected $member  = null; // Current user
    protected $person  = null;
    protected $projectId = 0;
    protected $projectPerson = null;

    public function __construct($em)
    {
        $this->em = $em;
    }
    public function load($accountId, $memberId = 0, $projectId = 0)
    {
        $em = $this->em;
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
            $projectRepo = $this->em->getRepository('ZaysoBundle:Project');
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
}
?>
