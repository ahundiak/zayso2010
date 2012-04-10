<?php

namespace Zayso\CoreBundle\Component\Manager;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\AccountOpenid;
use Zayso\CoreBundle\Entity\Account;
use Zayso\CoreBundle\Entity\AccountPerson;
use Zayso\CoreBundle\Entity\AccountPersonAyso;
use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Person;
use Zayso\CoreBundle\Entity\PersonRegistered;
use Zayso\CoreBundle\Entity\ProjectPerson;
use Zayso\CoreBundle\Entity\Project;

class AccountManager
{
    protected $em;
    protected $masterPassword;
    
    public function __construct($em,$masterPassword)
    {
        $this->em = $em;
        $this->masterPassword = $masterPassword;
    }
    public function getMasterPassword()  { return $this->masterPassword; }
    public function getEntityManager() { return $this->em; }
    public function clear() { $this->em->clear(); }
    public function flush() { $this->em->flush(); }
    
    /* ===========================================================
     * For creating new account
     */
    public function newAccountPersonAyso()
    {
        return new AccountPersonAyso();
    }
    public function createAccountFromAccountPersonAyso($accountPerson)
    {
        $em = $this->getEntityManager();

        // See if already have a person
        $personExisting = $this->loadPersonForAysoid($accountPerson->getAysoid());
        if ($personExisting)
        {
            // Make a new account for person already in system
            $personNew = $accountPerson->getPerson();
            $accountPerson->setPerson($personExisting);

            // Process any project info related to personNew
            foreach($personNew->getProjectPersons() as $projectPersonNew)
            {
                $projectIdNew = $projectPersonNew->getProject()->getId();

                // See if existing person is already attached to project
                $projectPersonExisting = $personExisting->getProjectPerson($projectIdNew);
                if (!$projectPersonExisting)
                {
                    // Load real project
                    $project = $em->getReference('ZaysoCoreBundle:Project',$projectIdNew);
                    $projectPersonNew->setProject($project);
                    $projectPersonNew->setPerson ($personExisting);
                }
            }
        }
        else
        {
            // Operate on new person
            $person = $accountPerson->getPerson();

            // For the new person need to add real project objects
            foreach($person->getProjectPersons() as $projectPerson)
            {
                // probably should through error if project not found
                $projectId = $projectPerson->getProject()->getId();
                $project = $em->getReference('ZaysoCoreBundle:Project',$projectId);
                $projectPerson->setProject($project);
            }

            // Done in RegionValidator
            // Need to see if have existing org
            //$org = $this->loadOrg($person->getOrgKey(),true);
            //if ($org) $person->setOrg($org);
        }

        // And save
        $em->persist($accountPerson->getAccountPerson()); // Everything cascades

        try
        {
            $em->flush();
        }
        catch (\Exception $e)
        {
            return 'Problem creating account';
            die('Create Account ' . $e->getMessage() . "\n");
        }
        return $accountPerson->getAccount();
    }
    
    /* ===========================================================
     * Person loading routines
     */
    public function loadPersonForAysoid($aysoid)
    {
        return $this->loadPerson(array('aysoid' => $aysoid));
    }
    public function loadPerson($params)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('registeredPerson');
        $qb->addSelect('projectPerson');
        $qb->addSelect('org');

        $qb->from('ZaysoCoreBundle:Person','person');

        $qb->leftJoin('person.registeredPersons','registeredPerson');
        $qb->leftJoin('person.projectPersons',   'projectPerson');
        $qb->leftJoin('projectPerson.project',   'project');
        $qb->leftJoin('person.org',              'org');

        if (isset($params['aysoid']))
        {
            $qb->andWhere($qb->expr()->eq('registeredPerson.regKey',$qb->expr()->literal($params['aysoid'])));
        }
        if (isset($params['projectId']))
        {
            $qb->andWhere($qb->expr()->in('project.id',$params['projectId']));
        }
        $query = $qb->getQuery();
        
        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }   
    /* =========================================================================
     * Some project person routines
     */
    public function loadProjectPerson($projectId,$personId)
    {
        // Allow ids or objects
        $em = $this->getEntityManager();        
        if (is_object($projectId)) $projectId = $projectId->getId();
        if (is_object($personId))  $personId  = $personId->getId();
        
        // Build query
        $qb = $em->createQueryBuilder();

        $qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:ProjectPerson','projectPerson');
        
        //$qb->leftJoin('projectPerson.person', 'person');
        //$qb->leftJoin('projectPerson.project','project');
    
        $qb->andWhere($qb->expr()->eq('projectPerson.project',':project'));
        $qb->andWhere($qb->expr()->eq('projectPerson.person', ':person' ));

        $params = array('project' => $projectId, 'person' => $personId);
        $qb->setParameters($params);
       
        $query = $qb->getQuery();

        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
    /* ============================================================
     * Called when a person signs in to a paroject
     */
    public function addProjectPerson($project,$person,$data = null)
    {
        // Allow ids or objects
        $em = $this->getEntityManager();        
        if (!is_object($project)) $project = $em->getReference('ZaysoCoreBundle:Project',$project);
        if (!is_object($person))  $person  = $em->getReference('ZaysoCoreBundle:Person', $person);
        
        // Check Dups
        $projectPerson = $this->loadProjectPerson($project,$person);
        if ($projectPerson) return $projectPerson;
        
        // Make a new one
        $projectPerson = new ProjectPerson();
        $projectPerson->setProject($project);
        $projectPerson->setPerson ($person);
        $projectPerson->setStatus('Active');
        
        // Handle data later
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
}
?>