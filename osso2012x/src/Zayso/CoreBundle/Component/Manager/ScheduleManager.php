<?php
/* --------------------------------------------------------------------
 * Even though this is a base, move some of the functionality here for now
 * Later on want to refactor into a more specific manager
 * But have to many copies of stuff for now
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

class ScheduleManager extends BaseManager
{
    protected function getValues($search,$name,$default = null)
    {
        if (!isset($search[$name])) return $default;

        $values = $search[$name];
        if (!is_array($values)) return $values;

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
        $genders = $this->getValues($search,'genders');
        $regions = $this->getValues($search,'regions');
        
        // Build query
        $qb = $this->newQueryBuilder();

        $qb->addSelect('game');
        $qb->addSelect('field');
        $qb->addSelect('gameTeam');
        $qb->addSelect('schTeam');
        $qb->addSelect('phyTeam');
        $qb->addSelect('gamePerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:Event','game');
        $qb->leftJoin('game.field',       'field');
        $qb->leftJoin('game.teams',       'gameTeam');
        $qb->leftJoin('gameTeam.team',    'schTeam');
        $qb->leftJoin('schTeam.parent',   'phyTeam');
        $qb->leftJoin('game.persons',     'gamePerson');
        $qb->leftJoin('gamePerson.person','person');

        $qb->andWhere($qb->expr()->eq('game.project',$qb->expr()->literal($projectId)));
        
        if (isset($params['age']))    $qb->andWhere($qb->expr()->in('schTeam.age',   $params['age']));
        if (isset($params['gender'])) $qb->andWhere($qb->expr()->in('schTeam.gender',$params['gender']));
        
        if ($ages)    $qb->andWhere($qb->expr()->in('schTeam.age',   $ages));
        if ($genders) $qb->andWhere($qb->expr()->in('schTeam.gender',$genders));

        return $qb->getQuery()->getResult();
        
    }
  
 }
?>
