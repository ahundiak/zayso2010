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
        $qb->leftJoin('account.openids',         'openid');
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
        /* 
         * SQL 
         * SELECT 
         * a0_.id AS id0, a0_.account_relation AS account_relation1, a0_.verified AS verified2, a0_.status AS status3, 
         * a1_.id AS id4, a1_.user_name AS user_name5, a1_.user_pass AS user_pass6, a1_.status AS status7, a1_.reset AS reset8, 
         * p2_.id AS id9, p2_.first_name AS first_name10, p2_.last_name AS last_name11, p2_.nick_name AS nick_name12, 
         *                p2_.email AS email13, p2_.cell_phone AS cell_phone14, p2_.verified AS verified15, 
         *                p2_.status AS status16, p2_.datax AS datax17, 
         * a3_.id AS id18, a3_.identifier AS identifier19, a3_.provider AS provider20, a3_.status AS status21, 
         *                 a3_.display_name AS display_name22, a3_.user_name AS user_name23, a3_.email AS email24, 
         * p4_.id AS id25, p4_.reg_type AS reg_type26, p4_.reg_key AS reg_key27, p4_.verified AS verified28, p4_.datax AS datax29, 
         * o5_.id AS id30, o5_.desc1 AS desc131, o5_.desc2 AS desc232, o5_.city AS city33, o5_.state AS state34, 
         *                 o5_.status AS status35, o5_.datax AS datax36, 
         * p6_.id AS id37, p6_.status AS status38, p6_.datax AS datax39, 
         * p7_.id AS id40, p7_.type AS type41, p7_.priority AS priority42, p7_.datax AS datax43, 
         * t8_.id AS id44, t8_.type AS type45, t8_.source AS source46, 
         *                 t8_.key1 AS key147, t8_.key2 AS key248, t8_.key3 AS key349, t8_.key4 AS key450, 
         *                 t8_.desc1 AS desc151, t8_.desc2 AS desc252, t8_.age AS age53, t8_.gender AS gender54, 
         *                 t8_.level AS level55, t8_.status AS status56, t8_.datax AS datax57, 
         * a0_.person_id AS person_id58, a0_.account_id AS account_id59, 
         * p2_.org_key AS org_key60, 
         * a3_.account_person_id AS account_person_id61, 
         * p4_.person_id AS person_id62, 
         * o5_.parent_id AS parent_id63, 
         * p6_.project_id AS project_id64, p6_.person_id AS person_id65, 
         * p7_.person_id AS person_id66, p7_.team_id AS team_id67, 
         * t8_.project_id AS project_id68, t8_.parent_id AS parent_id69, t8_.org_id AS org_id70 
         * 
         * FROM account_person a0_ 
         * LEFT JOIN account a1_ ON a0_.account_id = a1_.id 
         * LEFT JOIN person p2_ ON a0_.person_id = p2_.id 
         * LEFT JOIN account_openid a3_ ON a0_.id = a3_.account_person_id 
         * LEFT JOIN person_registered p4_ ON p2_.id = p4_.person_id 
         * LEFT JOIN org o5_ ON p2_.org_key = o5_.id 
         * LEFT JOIN project_person p6_ ON p2_.id = p6_.person_id AND (p6_.project_id = 62) 
         * LEFT JOIN person_team_rel p7_ ON p2_.id = p7_.person_id 
         * LEFT JOIN team t8_ ON p7_.team_id = t8_.id AND (t8_.project_id = 62) 
         * WHERE a1_.id IN (1)
         */
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
    public function addOpenidToAccountPerson($accountPerson,$profile = array())
    {
        if (!is_object($accountPerson)) $accountPerson = $this->getAccountPersonReference($accountPerson);
               
        $openid = new AccountOpenid();
        $openid->setProfile($profile);
        $openid->setAccountPerson($accountPerson);

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
