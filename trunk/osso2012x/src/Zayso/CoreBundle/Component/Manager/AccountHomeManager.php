<?php
/* ==================================================================
 * Try and isolate the various routines needed once a user signs in and
 * starts to use the system
 */
namespace Zayso\CoreBundle\Component\Manager;

use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\ProjectPerson;
use Zayso\CoreBundle\Entity\AccountOpenid;

class AccountHomeManager extends BaseManager
{
    /* =========================================================================
     * Load account information, project independent for now
     * Includes all people and teams related to account
     * Or at least it will
     * 
     * Probably trying to do too much here, need loadPersonPersons($projectId,$personId)
     */
    public function loadAccountWithPersons($projectId = 0, $accountId)
    {
        // die('loadAccountPersons ' . $projectId);
        
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('accountPerson');
        $qb->addSelect('personPerson');
        $qb->addSelect('person');
        
        $qb->addSelect('personReg');
        $qb->addSelect('personRegOrg');
        $qb->addSelect('projectPerson');
        $qb->addSelect('openid');
        $qb->addSelect('teamRel');
        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:Account','account');

        $qb->leftJoin('account.openids',   'openid');
        
        $qb->leftJoin('account.person',             'accountPerson');
        $qb->leftJoin('accountPerson.personPersons','personPerson');
        $qb->leftJoin('personPerson.person2',       'person');
        
        
        $qb->leftJoin('person.registeredPersons','personReg');
        $qb->leftJoin('personReg.org',           'personRegOrg');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->leftJoin('person.teamRels','teamRel',
            Expr\Join::WITH, $qb->expr()->eq('teamRel.project', $projectId));
        
        $qb->leftJoin('teamRel.team','team');
        
        $qb->andWhere($qb->expr()->in('account.id',$accountId));
        
        return $qb->getQuery()->getOneOrNullResult(); 
    }
    /* =========================================================================
     * Loads a specific project person
     */
    public function loadProjectPerson($projectId,$personId)
    {
        // Allow ids or objects
        $em = $this->getEntityManager();        
        if (is_object($projectId)) $projectId = $projectId->getId();
        if (is_object($personId))  $personId  = $personId->getId();
        
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:ProjectPerson','projectPerson');
    
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$projectId));
        $qb->andWhere($qb->expr()->eq('projectPerson.person', $personId));

        return $qb->getQuery()->getOneOrNullResult();
    }
    /* ============================================================
     * Called when a person signs in to a project
     */
    public function addProjectPerson($project,$person, $data = null)
    {
        // Check Dups
        $projectPerson = $this->loadProjectPerson($project,$person);
        if ($projectPerson) return $projectPerson;
        
        // Allow ids or objects    
        if (!is_object($project)) $project = $this->getProjectReference($project);
        if (!is_object($person))  $person  = $this->getPersonReference ($person);
                
        // Make a new one
        $projectPerson = new ProjectPerson();
        $projectPerson->setProject($project);
        $projectPerson->setPerson ($person);
        $projectPerson->setStatus('Active');
        
        // Handle data later
        if ($data)
        {
            
        }
        // Still not completely sure about this
        $em = $this->getEntityManager();        
        try
        {
            $em->persist($projectPerson);
            $em->flush();
        }
        catch(\Exception $e)
        {
            die($e->getMessage());
            return 'Problem adding project person';
        }
        return $projectPerson;
    }
    /* =========================================================================
     * Use this to present list of people on home page
     */
    public function loadAccountPersons($accountId, $projectId = 0)
    {
        // die('loadAccountPersons ' . $projectId);
        
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('accountPerson');
        $qb->addSelect('account');
        $qb->addSelect('person');
        $qb->addSelect('registeredPersons');
        $qb->addSelect('org');
        $qb->addSelect('projectPerson');
        $qb->addSelect('openid');
        $qb->addSelect('teamRel');
        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');

        $qb->leftJoin('accountPerson.account',   'account');
        $qb->leftJoin('accountPerson.person',    'person');
        $qb->leftJoin('accountPerson.openids',   'openid');
        $qb->leftJoin('person.registeredPersons','registeredPersons');
        $qb->leftJoin('person.org',              'org');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->leftJoin('person.teamRels','teamRel',
            Expr\Join::WITH, $qb->expr()->eq('teamRel.project', $projectId));
        
        $qb->leftJoin('teamRel.team','team');
        
        $qb->andWhere($qb->expr()->in('account.id',$accountId));
        
        $query = $qb->getQuery();
        
        // die('SQL ' . $query->getSQL());
        return $query->getResult();  
    }
    /* =========================================================================
     * Use to load and edit an account person
     * Query needs to be combined with loadAccountPersons
     */
    public function loadAccountPerson($accountPersonId, $projectId = 0)
    {
        // die('loadAccountPerson ' . $projectId);
        
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('accountPerson');
        $qb->addSelect('account');
        $qb->addSelect('person');
        $qb->addSelect('registeredPersons');
        $qb->addSelect('org');
        $qb->addSelect('projectPerson');
        $qb->addSelect('openid');
        $qb->addSelect('teamRel');
        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');

        $qb->leftJoin('accountPerson.account',   'account');
        $qb->leftJoin('accountPerson.person',    'person');
        $qb->leftJoin('accountPerson.openids',   'openid');
        $qb->leftJoin('person.registeredPersons','registeredPersons');
        $qb->leftJoin('person.org',              'org');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->leftJoin('person.teamRels','teamRel',
            Expr\Join::WITH, $qb->expr()->eq('teamRel.project', $projectId));
        
        $qb->leftJoin('teamRel.team','team');
        
        $qb->andWhere($qb->expr()->in('accountPerson.id',$accountPersonId));
        
        return $qb->getQuery()->getOneOrNullResult(); 
    }
    /* =========================================================================
     * Mostly to deal with password reseting
     */
    public function loadPrimaryAccountPersonForAccount($accountId)
    {
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('accountPerson');
        $qb->addSelect('account');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:AccountPerson','accountPerson');
        
        $qb->leftJoin('accountPerson.account','account');
        $qb->leftJoin('accountPerson.person', 'person');
        
        $qb->andWhere($qb->expr()->eq('accountPerson.account',$accountId));
        $qb->andWhere($qb->expr()->eq('accountPerson.accountRelation',$qb->expr()->literal('Primary')));
        
        return $qb->getQuery()->getOneOrNullResult(); 
    }
    public function loadAccountForReset($reset)
    {
        // Build query
        $qb = $this->createQueryBuilder();

        $qb->addSelect('account');
        
        $qb->from('ZaysoCoreBundle:Account','account');
        
        $qb->andWhere($qb->expr()->eq('account.reset',$qb->expr()->literal($reset)));
        
        return $qb->getQuery()->getOneOrNullResult(); 
    }
    /* ========================================================
     * Check for exitence of openid
     */
    public function loadOpenidForIdentifier($identifier)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('openid');
      //$qb->addSelect('account');
      //$qb->addSelect('accountPerson');
      //$qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:AccountOpenid','openid');

      //$qb->leftJoin('openid.accountPerson', 'accountPerson');
      //$qb->leftJoin('accountPerson.account','account');
      //$qb->leftJoin('accountPerson.person', 'person');

        $qb->andWhere($qb->expr()->eq('openid.identifier',$qb->expr()->literal($identifier)));
 
        return $qb->getQuery()->getOneOrNullResult();
    }
    public function addOpenidToAccount($account,$profile = array())
    {
        if (!is_object($account)) $account = $this->getAccountReference($account);
               
        $openid = new AccountOpenid();
        $openid->setProfile($profile);
        $openid->setAccount($account);

        $this->persist($openid);

        return $openid;
    }
    public function deleteOpenid($id)
    {
        $openid = $this->getReference('AccountOpenid',$id);
        $this->remove($openid);
        return;
    }
}
?>
