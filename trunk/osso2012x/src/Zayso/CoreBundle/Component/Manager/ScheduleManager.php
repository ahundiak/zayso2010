<?php
/* --------------------------------------------------------------------
 * Even though this is a base, move some of the functionality here for now
 * Later on want to refactor into a more specific manager
 * But have to many copies of stuff for now
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\Query\Expr;

class ScheduleManager extends GameManager
{
    protected function getValues($search,$name,$default = null)
    {
        if (!isset($search[$name])) return $default;
        $values = $search[$name];

        return $values;
        
        if (!is_array($values)) return $values;

        // Not sure why indexed
        $valuesIndexed = array();
        foreach($values as $value)
        {
            if ($value) $valuesIndexed[$value] = $value;
        }
        return $valuesIndexed;
    }
    public function loadGames($search)
    {
        $projectId = $this->getValues($search,'projectId');
        if (!$projectId) return array();
        
        $ages    = $this->getValues($search,'ages');
        $dates   = $this->getValues($search,'dows');
        $teams   = $this->getValues($search,'teams');
        $genders = $this->getValues($search,'genders');
        $regions = $this->getValues($search,'regions');
        
        $time1 = $this->getValues($search,'time1');
        $time2 = $this->getValues($search,'time2');
    
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('game');
        $qb->addSelect('field');
        $qb->addSelect('gameTeamRel');
        $qb->addSelect('gameTeam');
        $qb->addSelect('poolTeam');
        $qb->addSelect('phyTeam');
        $qb->addSelect('gamePerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:Event','game');
        $qb->leftJoin('game.field',       'field');
        $qb->leftJoin('game.teams',       'gameTeamRel');
        $qb->leftJoin('gameTeamRel.team', 'gameTeam');
        $qb->leftJoin('gameTeam.parent',  'poolTeam');
        $qb->leftJoin('poolTeam.parent',  'phyTeam');
        $qb->leftJoin('game.persons',     'gamePerson');
        $qb->leftJoin('gamePerson.person','person');

        $qb->andWhere($qb->expr()->eq('game.project',$qb->expr()->literal($projectId)));
        
        if ($dates) $qb->andWhere($qb->expr()->in ('game.date',$dates));
        if ($time1) $qb->andWhere($qb->expr()->gte('game.time',$qb->expr()->literal($time1)));
        if ($time2) $qb->andWhere($qb->expr()->lte('game.time',$qb->expr()->literal($time2)));
        
        if ($teams) $qb->andWhere($qb->expr()->in('phyTeam.id',   $teams));
        
        if ($ages)    $qb->andWhere($qb->expr()->in('gameTeam.age',   $ages));
        if ($genders) $qb->andWhere($qb->expr()->in('gameTeam.gender',$genders));
        
        $qb->addOrderBy('game.date');
        $qb->addOrderBy('game.time');
        $qb->addOrderBy('field.key1');

        return $qb->getQuery()->getResult();
        
    }
    public function qbRefereesForProject($projectId)
    {   
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('person');
        $qb->addSelect('cert');
        $qb->addSelect('org');
        
        $qb->from('ZaysoCoreBundle:Person','person');
        
        $qb->leftJoin('person.org','org');
        
        $qb->leftJoin('person.projectPersons','projectPerson');
         
        $qb->leftJoin('person.registeredPersons','cert',
            Expr\Join::WITH, $qb->expr()->eq('cert.regType', $qb->expr()->literal('AYSOV')));
        
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$projectId));
        
        $qb->addOrderBy('person.firstName,person.lastName');
        
        //die($qb->getDQL());

        return $qb;
    }
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
    public function loadPhyTeamsForProject($projectId)
    {
        $qb = $this->qbPhyTeamsForProject($projectId);
        return $qb->getQuery()->getResults();        
    }
 
}
?>
