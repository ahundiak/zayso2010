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
    public function qbGameIds($search)
    {
        // Always need projectId for now
        if (isset($search['projectId'])) $projectId = $search['projectId'];
        else                             $projectId = null;
        if (!$projectId) return null;
        
        if (isset($search['ages']))  $ages = $search['ages'];
        else                         $ages = array();
        
        if (isset($search['genders'])) $genders = $search['genders'];
        else                           $genders = array();
        
        if (isset($search['teamIds'])) $teamIds = $search['teamIds'];
        else                           $teamIds = array();
        
        if (isset($search['personIds'])) $personIds = $search['personIds'];
        else                             $personIds = array();
    
        if (isset($search['dows' ])) $dates = $search['dows'];
        else                         $dates = array();
        
        if (isset($search['time1'])) $time1 = $search['time1'];
        else                         $time1 = null;
        
        if (isset($search['time2'])) $time2 = $search['time2'];
        else                         $time2 = null;
        
        if (isset($search['projectId'])) $projectId = $search['projectId'];
        else                             $projectId = null;
        
        // Build query
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('distinct gamex.id');
        
        $qb->from('ZaysoCoreBundle:Event','gamex');
        $qb->leftJoin('gamex.teams',      'gameTeamRelx');
        $qb->leftJoin('gameTeamRelx.team','gameTeamx');  // Pool or Playoff
        $qb->leftJoin('gameTeamx.parent', 'phyTeamx');
        $qb->leftJoin('gamex.persons',    'gamePersonRelx');
        $qb->leftJoin('gamePersonRelx.person','personx');
        
        $qb->andWhere($qb->expr()->eq('gamex.project',$qb->expr()->literal($projectId)));
       
        // Date and time always needs to match
        if (count($dates)) $qb->andWhere($qb->expr()->in('gamex.date',$dates));
        
        if ($time1) $qb->andWhere($qb->expr()->gte('gamex.time',$qb->expr()->literal($time1)));
        if ($time2) $qb->andWhere($qb->expr()->lte('gamex.time',$qb->expr()->literal($time2)));
        
        if (count($teamIds) || count($personIds))
        {   
            $orxTP = $qb->expr()->orX();
            
            if (count($teamIds  )) $orxTP->add($qb->expr()->in('phyTeamx.id',     $teamIds));
            if (count($personIds)) $orxTP->add($qb->expr()->in('personx.id',      $personIds));
        }
        else $orxTP = null;
        
        if (count($ages) || count($genders))
        {   
            $andxAG = $qb->expr()->andX();
            
            if (count($ages))      $andxAG->add($qb->expr()->in('gameTeamx.age',   $ages));
            if (count($genders))   $andxAG->add($qb->expr()->in('gameTeamx.gender',$genders));
        }
        else $andxAG = null;
        
        // If have both then wrap in orx
        if ($orxTP && $andxAG)
        {
            $orx = $qb->expr()->orX();
            $orx->add($orxTP);
            $orx->add($andxAG);
            $qb->addWhere($orx);
        }
        else
        {
            if ($orxTP)  $qb->andWhere($orxTP);
            if ($andxAG) $qb->andWhere($andxAG);
        }
      //print_r($qb->getQuery()->getResult()); die('qb');
        
        return $qb;
    }
    /* =========================================================================
     * Tried to do it the tricky way but can't seem to get or to work 
     * Research later, for now just grab the ids and move on
     */
    public function loadGameIdsForTeamsPersons($search)
    {   
        // Always need projectId for now
        if (isset($search['projectId'])) $projectId = $search['projectId'];
        else                             $projectId = null;
        if (!$projectId) return array();
        
        if (isset($search['teamIds'])) $teamIds = $search['teamIds'];
        else                           $teamIds = array();
        
        if (isset($search['personIds'])) $personIds = $search['personIds'];
        else                             $personIds = array();
    
        if (isset($search['dows' ])) $dates = $search['dows'];
        else                         $dates = array();
        
        if (isset($search['time1'])) $time1 = $search['time1'];
        else                         $time1 = null;
        
        if (isset($search['time2'])) $time2 = $search['time2'];
        else                         $time2 = null;
        
        if (!count($teamIds) && !count($personIds)) return array();
        
        // Build query
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('distinct gamex.id');
        
        $qb->from('ZaysoCoreBundle:Event','gamex');
        $qb->leftJoin('gamex.teams',      'gameTeamRelx');
        $qb->leftJoin('gameTeamRelx.team','gameTeamx');  // Pool or Playoff
        $qb->leftJoin('gameTeamx.parent', 'phyTeamx');
        $qb->leftJoin('gamex.persons',    'gamePersonRelx');
        $qb->leftJoin('gamePersonRelx.person','personx');
        
        // Project
        $qb->andWhere($qb->expr()->eq('gamex.project',$qb->expr()->literal($projectId)));
        
       // Date and time always needs to match
        if (count($dates)) $qb->andWhere($qb->expr()->in('gamex.date',$dates));
        
        if ($time1) $qb->andWhere($qb->expr()->gte('gamex.time',$qb->expr()->literal($time1)));
        if ($time2) $qb->andWhere($qb->expr()->lte('gamex.time',$qb->expr()->literal($time2)));
        
        // Add in game/person
        $orx = $qb->expr()->orX();
        if (count($teamIds  )) $orx->add($qb->expr()->in('phyTeamx.id',$teamIds));
        if (count($personIds)) $orx->add($qb->expr()->in('personx.id', $personIds));
        
        $qb->andWhere($orx);
        
        $items = $qb->getQuery()->getArrayResult();
        $ids = array();
        foreach($items as $item) $ids[] = $item['id'];
        
        return $ids;
        print_r($ids); die('ids');
    }
    public function loadGameIdsForAgesGenders($search)
    {   
        // Always need projectId for now
        if (isset($search['projectId'])) $projectId = $search['projectId'];
        else                             $projectId = null;
        if (!$projectId) return array();
        
        if (isset($search['ages']))  $ages = $search['ages'];
        else                         $ages = array();
        
        if (isset($search['genders'])) $genders = $search['genders'];
        else                           $genders = array();
        
        if (isset($search['dows' ])) $dates = $search['dows'];
        else                         $dates = array();
        
        if (isset($search['time1'])) $time1 = $search['time1'];
        else                         $time1 = null;
        
        if (isset($search['time2'])) $time2 = $search['time2'];
        else                         $time2 = null;
        
        if (!count($ages) && !count($genders)) return array();
       
        // Build query
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('distinct gamex.id');
        
        $qb->from('ZaysoCoreBundle:Event','gamex');
        $qb->leftJoin('gamex.teams',      'gameTeamRelx');
        $qb->leftJoin('gameTeamRelx.team','gameTeamx');  // Pool or Playoff
      //$qb->leftJoin('gameTeamx.parent', 'phyTeamx');
      //$qb->leftJoin('gamex.persons',    'gamePersonRelx');
      //$qb->leftJoin('gamePersonRelx.person','personx');
        
        // Project
        $qb->andWhere($qb->expr()->eq('gamex.project',$qb->expr()->literal($projectId)));
       
        // Date and time always needs to match
        if (count($dates)) $qb->andWhere($qb->expr()->in('gamex.date',$dates));
        
        if ($time1) $qb->andWhere($qb->expr()->gte('gamex.time',$qb->expr()->literal($time1)));
        if ($time2) $qb->andWhere($qb->expr()->lte('gamex.time',$qb->expr()->literal($time2)));
            
        if (count($ages))    $qb->andWhere($qb->expr()->in('gameTeamx.age',   $ages));
        if (count($genders)) $qb->andWhere($qb->expr()->in('gameTeamx.gender',$genders));
         
        $items = $qb->getQuery()->getArrayResult();
        $ids = array();
        foreach($items as $item) $ids[] = $item['id'];
        
        return $ids;
        print_r($ids); die('ids');
    }
    public function loadGames($search)
    {
        $gameIds = array_merge
        (
            $this->loadGameIdsForTeamsPersons($search),
            $this->loadGameIdsForAgesGenders ($search)
        );
        if (!count($gameIds)) return array();
        

        // Distinct list of ids
        // $qbGameIds = $this->qbGameIds($search);
        //if (!$qbGameIds) return array();
        
        // Build query
        $qb = $this->createQueryBuilder();

        $qb->addSelect('game');
        $qb->addSelect('field');
        $qb->addSelect('gameTeamRel');
        $qb->addSelect('gameTeam');
        $qb->addSelect('phyTeam');
        $qb->addSelect('gamePerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:Event','game');
        $qb->leftJoin('game.field',       'field');
        $qb->leftJoin('game.teams',       'gameTeamRel');
        $qb->leftJoin('gameTeamRel.team', 'gameTeam');
        $qb->leftJoin('gameTeam.parent',  'phyTeam');
        $qb->leftJoin('game.persons',     'gamePerson');
        $qb->leftJoin('gamePerson.person','person');
        
        $qb->andWhere($qb->expr()->in('game.id',$gameIds));
        
        $qb->addOrderBy('game.date');
        $qb->addOrderBy('game.time');
        $qb->addOrderBy('field.key1');

        return $qb->getQuery()->getResult();
        
    }
    public function loadGamesx($search)
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
    public function loadTeamsForProjectAccount($projectId,$accountId)
    {
        $qb = $this->createQueryBuilder();
        $qb->addSelect('team');
        $qb->from('ZaysoCoreBundle:Team','team');
        
        $qb->leftJoin('team.personRels','personRel');
        $qb->leftJoin('personRel.person','person');
        $qb->leftJoin('person.accountPersons','accountPerson');
           
        $qb->andWhere($qb->expr()->eq('team.project',          $qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->eq('accountPerson.account', $qb->expr()->literal($accountId)));
        
        $qb->addOrderBy('team.key1');
        
        return $qb->getQuery()->getResult();
    }
    public function loadPersonsForProjectAccount($projectId,$accountId)
    {
        $qb = $this->createQueryBuilder();
        $qb->addSelect('person');
        $qb->from('ZaysoCoreBundle:Person','person');
        
        $qb->leftJoin('person.accountPersons','accountPerson');
           
      //$qb->andWhere($qb->expr()->eq('team.project',          $qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->eq('accountPerson.account', $qb->expr()->literal($accountId)));
        
        $qb->addOrderBy('person.nickName');
        $qb->addOrderBy('person.firstName');
        $qb->addOrderBy('person.lastName');
        
        return $qb->getQuery()->getResult();
    }
}
?>
