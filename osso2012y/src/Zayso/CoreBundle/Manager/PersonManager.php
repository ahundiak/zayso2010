<?php
namespace Zayso\CoreBundle\Manager;

use Zayso\CoreBundle\Entity\Person;

use Doctrine\ORM\Query\Expr;

class PersonManager extends BaseManager
{
    public function newPerson() { return new Person; }
    
    public function qbPhyTeamsForProject($projectId)
    {
        $qb = $this->createQueryBuilder();
        $qb->addSelect('team');
        $qb->from('ZaysoCoreBundle:Team','team');
        
        $qb->andWhere($qb->expr()->in('team.project', $projectId));
        $qb->andWhere($qb->expr()->in('team.type',    array('Physical','physical')));
        
        $qb->addOrderBy('team.key1');

        return $qb;
    }
     
    /* ========================================================
     * Currently used by person team controller
     * Merge with load person for edit later
     */
    public function loadPersonForProject($projectId,$personId)
    {
        $qb = $this->createQueryBuilder();

        $qb->addSelect('person, personReg, personRegOrg, projectPerson', 'teamRel', 'team');

        $qb->from('ZaysoCoreBundle:Person','person');
        $qb->andWhere($qb->expr()->eq('person.id',$qb->expr()->literal($personId)));
        
        $qb->leftJoin('person.registeredPersons','personReg');
        $qb->leftJoin('personReg.org','personRegOrg');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
        
        $qb->leftJoin('person.teamRels','teamRel',
             Expr\Join::WITH, $qb->expr()->eq('teamRel.project', $projectId));
        
        $qb->leftJoin('teamRel.team','team');
       
        //die($qb->getQuery()->getSQL());
        
        return $qb->getQuery()->getOneOrNullResult();      
    }
     
    public function loadPersonForEdit($projectId,$personId)
    {
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('person');
        $qb->addSelect('personReg');
        $qb->addSelect('personRegOrg');
        $qb->addSelect('projectPerson');
         
        $qb->from('ZaysoCoreBundle:Person','person');
        
        $qb->leftJoin('person.registeredPersons','personReg');
        $qb->leftJoin('personReg.org','personRegOrg');
        
        $qb->leftJoin('person.projectPersons','projectPerson', 
            Expr\Join::WITH, $qb->expr()->eq('projectPerson.project', $projectId));
       
        $qb->andWhere($qb->expr()->eq('person.id',$qb->expr()->literal($personId)));
        
        return $qb->getQuery()->getOneOrNullResult();      
    
    }
    /* ===========================================
     * Not sure if this should go here or not
     */
    public function savePerson($person)
    {
        // Deal with new ayso record
        // One type at a time for now since getRPs will not work for new records
        // Need to deal with the case of adding a new person with an existing ayso record
        $personRegs = array($person->getRegAYSOV(),$person->getRegUSSF());
        foreach($personRegs as $personReg)
        {
            if ($personReg->getRegKey()) 
            {
                // See if one already exists
                // Move to a validator?
                $personRegx = $this->loadPersonRegForKey($personReg->getRegKey());
                
                // Nothng existing means to just go ahead and create
                if (!$personRegx) $this->persist($personReg);
                else
                {
                    // No change is okay
                    if ($personReg->getId() == $personRegx->getId()) {}
                    else
                    {
                        // Punt for now
                        die('Trying to change to existing reg key');
                    }
                }
            }
            else
            {
                if ($personReg->getId())
                {
                    $this->remove($personReg);
                }
            }
        }
        // If no project then remove from flush
        $currentProjectPerson = $person->getCurrentProjectPerson();
        if (!$currentProjectPerson->getProject())
        {
            // This allows removeing a person from a project
            $this->remove($currentProjectPerson);// die('detached');
            
            // Need this otherwise an entity always gets added even with remove/detach
            // The cascade=persist was causing this when addProjectPerson was called by the form
            // $person->clearProjectPersons();
        }
        else $this->persist($currentProjectPerson);  // Only persist if have a project
        
        // Deal with new persons
        if (!$person->getId()) $this->persist($person);
        
        $this->flush();
    }    
    public function loadPersonRegForKey($personRegKey)
    {
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('personReg');
         
        $qb->from('ZaysoCoreBundle:PersonReg','personReg');
        
        $qb->andWhere($qb->expr()->eq('personReg.regKey',$qb->expr()->literal($personRegKey)));
        
        return $qb->getQuery()->getOneOrNullResult();      
    }
}

?>
