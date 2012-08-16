<?php
namespace Zayso\CoreBundle\Manager;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event;
use Zayso\CoreBundle\Entity\EventTeam;
use Zayso\CoreBundle\Entity\EventPerson;

use Zayso\CoreBundle\Entity\ProjectField;

use Doctrine\ORM\Query\Expr;

class ScheduleManager extends BaseManager
{
    /* ------------------------------------------------------------------------------
     * Access Field
     */
    public function newProjectField($project = null) 
    { 
        if (!is_object($project)) $project = $this->getProjectReference($project);
        $field = new ProjectField();
        $field->setProject($project);
        return $field;
    }
    public function loadProjectFieldForKey($projectId,$key)
    {
        // Just because
        if (is_object($projectId)) $projectId = $projectId->getId();
        
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('projectField');

        $qb->from('ZaysoCoreBundle:ProjectField','projectField');
        $qb->leftJoin('projectField.project','project');

        $qb->andWhere($qb->expr()->eq('project.id',$qb->expr()->literal($projectId)));
        
        $qb->andWhere($qb->expr()->eq('projectField.key1',$qb->expr()->literal($key)));
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    /* ========================================================================
     * Used for schedule import
     */
    public function loadEventForProjectNum($projectId,$num)
    {
        // Just because
        if (is_object($projectId)) $projectId = $projectId->getId();

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('event');
        $qb->addSelect('eventTeam');
        $qb->addSelect('team');
        $qb->addSelect('field');

        $qb->from('ZaysoCoreBundle:Event','event');
        $qb->leftJoin('event.teams',      'eventTeam');
        $qb->leftJoin('eventTeam.team',   'team');
        $qb->leftJoin('event.project',    'project');
        $qb->leftJoin('event.field',      'field');

        $qb->andWhere($qb->expr()->eq('project.id',$qb->expr()->literal($projectId)));

        $qb->andWhere($qb->expr()->eq('event.num',$qb->expr()->literal($num)));
        
        return $qb->getQuery()->getOneOrNullResult();

    }
    /* ==============================================================
     * Make a new game with some teams
     */
    public function newGameWithTeams($project)
    {
        if (!$project) return null;
        
        if (!is_object($project)) $project = $this->getProjectReference($project);
        
        $game = new Event();
        $game->setProject($project);
        
        $team = new EventTeam();
        $team->setProject($project);
        $team->setTypeAsHome();
        $team->setGame($game);
        $game->addTeam($team);
        
        $team = new EventTeam();
        $team->setProject($project);
        $team->setTypeAsAway();
        $team->setGame($game);
        $game->addTeam($team);
        
        return $game;
    }
    public function newGamePerson($project)
    {
        if (!$project) return null;
        
        if (!is_object($project)) $project = $this->getProjectReference($project);
        
        $gamePerson = new EventPerson();
        $gamePerson->setProject($project);
        
        return $gamePerson;
    }
    /* ==============================================================
     * New protype team
     */
    public function newTeam($project) 
    { 
        if (!$project) return null;
        if (!is_object($project)) $project = $this->getProjectReference($project);
        
        $team = new Team();
        $team->setProject($project);
        
        return $team; 
        
    }
    /* ===================================================================
     * Loads team for a given main key
     */
    public function loadTeamForKey($projectId,$key)
    {
        // Just because
        if (is_object($projectId)) $projectId = $projectId->getId();
        
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('team');

        $qb->from('ZaysoCoreBundle:Team','team');
        $qb->leftJoin('team.project','project');

        $qb->andWhere($qb->expr()->eq('project.id',$qb->expr()->literal($projectId)));
        
        $qb->andWhere($qb->expr()->eq('team.key1',$qb->expr()->literal($key)));
        
        return $qb->getQuery()->getOneOrNullResult();  
    }
    /* ================================================
     * For nested queries
     * projectId is for possible future use and consistency
     * 
     * Used for schedule listing search form
     */
    public function qbPersonPersons($projectId, $personId1)
    {
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('distinct personPP.id');
        
        // Can't get this to work, really should not need the join
        // $qb->addSelect('distinct personPersonPP.person_id2');
        
        $qb->from('ZaysoCoreBundle:PersonPerson','personPersonPP');
        
        $qb->leftJoin('personPersonPP.person2','personPP');

        $qb->andWhere($qb->expr()->eq('personPersonPP.person1',$qb->expr()->literal($personId1)));
        
        return $qb;
    }
    public function loadPersonsForProjectPerson($projectId,$personId)
    {    
        $qbPersonPersons = $this->qbPersonPersons($projectId,$personId);
        
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('person');
        $qb->from('ZaysoCoreBundle:Person','person');
        
        // Project should also fit in here
        $qb->andWhere($qb->expr()->in('person.id', $qbPersonPersons->getDQL()));
        
        $qb->addOrderBy('person.nickName');
        $qb->addOrderBy('person.firstName');
        $qb->addOrderBy('person.lastName');
        
        return $qb->getQuery()->getResult();
    }
    public function loadTeamsForProjectPerson($projectId,$personId)
    {   
        $qbPersonPersons = $this->qbPersonPersons($projectId,$personId);
        
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('team');
        
        $qb->from('ZaysoCoreBundle:Team','team');
        
        $qb->leftJoin('team.personRels','personRel');
        
        $qb->andWhere($qb->expr()->eq('team.project',$qb->expr()->literal($projectId)));
        $qb->andWhere($qb->expr()->in('personRel.person',   $qbPersonPersons->getDQL()));
        
        $qb->addOrderBy('team.key1');
        
        return $qb->getQuery()->getResult();
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
        // if (!$projectId) return array();
        
        if (isset($search['teamIds'])) $teamIds = $search['teamIds'];
        else                           $teamIds = array();
        
        if (isset($search['personIds'])) $personIds = $search['personIds'];
        else                             $personIds = array();
    
        if (isset($search['dows' ])) $dates = $search['dows'];
        else                         $dates = array();
        
        if (isset($search['date1' ])) $date1 = $search['date1'];
        else                          $date1 = null;
        
        if (isset($search['date2' ])) $date2 = $search['date2'];
        else                          $date2 = null;
         
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
        // $qb->andWhere($qb->expr()->eq('gamex.project',$qb->expr()->literal($projectId)));
        
        // Date and time always needs to match
        //if (count($dates)) $qb->andWhere($qb->expr()->in('gamex.date',$dates));
        
        if ($date1) $qb->andWhere($qb->expr()->gte('gamex.date',$qb->expr()->literal($date1)));
        if ($date2) $qb->andWhere($qb->expr()->lte('gamex.date',$qb->expr()->literal($date2)));
        
        if ($time1) $qb->andWhere($qb->expr()->gte('gamex.time',$qb->expr()->literal($time1)));
        if ($time2) $qb->andWhere($qb->expr()->lte('gamex.time',$qb->expr()->literal($time2)));
        
        // Add in game/person
        $orx = $qb->expr()->orX();
        if (count($teamIds  )) $orx->add($qb->expr()->in('gameTeamx.id',$teamIds));
        if (count($teamIds  )) $orx->add($qb->expr()->in('phyTeamx.id', $teamIds));
        if (count($personIds)) $orx->add($qb->expr()->in('personx.id',  $personIds));
        
        $qb->andWhere($orx);
        
        $items = $qb->getQuery()->getArrayResult();
        $ids = array();
        foreach($items as $item) $ids[] = $item['id'];
        
        return $ids;
        print_r($ids); die('ids');
    }
    /* =========================================================================
     * Bunch of stuff for loading games
     */
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
        
        if (in_array('B',$genders) !== false) $genders[] = 'C';
        
        if (isset($search['regions'])) $regions = $search['regions'];
        else                           $regions = array();
        
        
        if (isset($search['date1' ])) $date1 = $search['date1'];
        else                          $date1 = null;
        
        if (isset($search['date2' ])) $date2 = $search['date2'];
        else                          $date2 = null;
         
        if (isset($search['time1'])) $time1 = $search['time1'];
        else                         $time1 = null;
        
        if (isset($search['time2'])) $time2 = $search['time2'];
        else                         $time2 = null;
        
      //if (!count($ages) && !count($genders)) return array();
       
        // Build query
        $qb = $this->createQueryBuilder();
        
        $qb->addSelect('distinct gamex.id');
        
        $qb->from('ZaysoCoreBundle:Event','gamex');
        $qb->leftJoin('gamex.teams',      'gameTeamRelx');
        $qb->leftJoin('gameTeamRelx.team','gameTeamx');  // Pool or Playoff
        
        // Project
        // $qb->andWhere($qb->expr()->eq('gamex.project',$qb->expr()->literal($projectId)));
       
        // Date and time always needs to match
        //if (count($dates)) $qb->andWhere($qb->expr()->in('gamex.date',$dates));
        
        if ($date1) $qb->andWhere($qb->expr()->gte('gamex.date',$qb->expr()->literal($date1)));
        if ($date2) $qb->andWhere($qb->expr()->lte('gamex.date',$qb->expr()->literal($date2)));
        
        if ($time1) $qb->andWhere($qb->expr()->gte('gamex.time',$qb->expr()->literal($time1)));
        if ($time2) $qb->andWhere($qb->expr()->lte('gamex.time',$qb->expr()->literal($time2)));
            
        if (count($ages))    $qb->andWhere($qb->expr()->in('gameTeamx.age',   $ages));
        if (count($genders)) $qb->andWhere($qb->expr()->in('gameTeamx.gender',$genders));
        if (count($regions)) $qb->andWhere($qb->expr()->in('gameTeamx.org',   $regions));
         
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
        
        if (isset($search['orderBy'])) $orderBy = $search['orderBy'];
        else                           $orderBy = 'date_time_field';
        
        switch($orderBy)
        {
            case 'playoffs':
                $qb->addOrderBy('game.date');
                $qb->addOrderBy('game.pool');
                
                $qb->addOrderBy('game.time');
                $qb->addOrderBy('field.key1');
                break;
            
            case 'print':
                $qb->addOrderBy('field.status'); // Venue
                $qb->addOrderBy('game.date');
                $qb->addOrderBy('game.time');
                $qb->addOrderBy('field.key1');
                break;
           
            default:
                $qb->addOrderBy('game.date');
                $qb->addOrderBy('game.time');
                $qb->addOrderBy('field.key1');
        }
        return $qb->getQuery()->getResult();
    }
    /* ========================================================================
     * Single event stuff
     * Verified used by game edit and referee assinging
     * Game Report
     */
    public function loadEventForId($id)
    {
        // Build query
        $qb = $this->createQueryBuilder();

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

        $qb->andWhere($qb->expr()->eq('game.id',$qb->expr()->literal($id)));
        
        return $qb->getQuery()->getOneOrNullResult();

    }
    /* ========================================================================
     * Returns a list of all the referees associated with a given account
     * and (eventually) project
     * Started from Area
     */
    public function loadOfficialsForPerson($projectId,$personId)
    {
        $qbPersonPersons = $this->qbPersonPersons($projectId,$personId);
       
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('personRegistered');
        
      //$qb->addSelect('projectPerson');

        $qb->from('ZaysoCoreBundle:Person',       'person');
        $qb->leftJoin('person.personPersons',     'personPerson');
        $qb->leftJoin('person.registeredPersons', 'personRegistered'); // Limit to ayso
        
      //$qb->leftJoin('person.projectPersons',    'projectPerson');

        $qb->andWhere($qb->expr()->in('person.id',$qbPersonPersons->getDQL()));
        
        $qb->addOrderBy('person.nickName');
        $qb->addOrderBy('person.firstName');
        $qb->addOrderBy('person.lastName');

        $items = $qb->getQuery()->getResult();
        
        return $items;
    }
}
?>
