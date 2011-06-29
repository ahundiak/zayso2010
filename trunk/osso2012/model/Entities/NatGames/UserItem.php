<?php
/* 
 * Not really an entity, probably should live under App
 */
namespace NatGames;

class UserItem
{
  protected $services;

  public function __construct($services)
  {
    $this->services = $services;
    $this->account = null;
    $this->member  = null;
    $this->person  = null;
    $this->projectId = 0;
    $this->projectPerson = null;
  }
  public function load($accountId,$memberId,$projectId = 0)
  {
    $em = $this->services->userEm;
    $qb = $em->createQueryBuilder();

    $qb->addSelect('account');
    $qb->addSelect('members');
    $qb->addSelect('person');
    $qb->addSelect('regs');

    $qb->from('\NatGames\Account\AccountItem','account');
    $qb->leftJoin('account._members','members');
    $qb->leftJoin('members._person', 'person');
    $qb->leftJoin('person._regs',    'regs');

    $qb->andWhere($qb->expr()->eq('account._id',$accountId));

    // die($qb->getDQL());
    
    $query = $qb->getQuery();
    $accounts = $query->getResult();

    if (count($accounts) != 1)
    {
      return;  // Account was deleted
    }
    $this->account = $accounts[0];

    $this->member = $this->account->getMember($memberId);

    $this->person = $this->member->person;

    // Project specific info
    $this->projectId = $projectId;
    $this->projectPerson = null;
    
    // Might want to load this on demand
    $projectRepo = $this->services->projectRepo;
    $projectPerson = $projectRepo->loadProjectPerson($projectId,$this->person);

    $this->projectPerson = $projectPerson;

  //die('Loaded account ' . $this->person->fname . ' ' . $projectPerson->accountCreate->refBadge . ' ' . $this->person->aysoid);
  //die('Project id ' . $projectId . ' ' . $projectPerson->project->id);
  }
  public function getProjectPerson()
  {
    return $this->projectPerson;
  }
  public function saveProjectPerson($item)
  {
    $this->projectPerson = $item;
    $projectRepo = $this->services->projectRepo;
    $projectRepo->saveProjectPerson($item);
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

    $fname = $person->fname;
    $nname = $person->nname;
    $lname = $person->lname;

    if ($nname) $fname = $nname;

    return $fname . ' ' . $lname;
  }
}
?>
