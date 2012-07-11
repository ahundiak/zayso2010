<?php
namespace Zayso\ZaysoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\ProjectSeqn;
use Zayso\ZaysoBundle\Entity\ProjectPerson;

use Zayso\ZaysoBundle\Entity\Person;
use Zayso\ZaysoBundle\Entity\PersonRegistered;
use Zayso\ZaysoBundle\Entity\AccountPerson;

/* =========================================================================
 * The repository
 */
class GameRepository extends EntityRepository
{
    protected function getValues($search,$name,$default = null)
    {
        if (!isset($search[$name])) return $default;

        $values = $search[$name];
        if (!is_array($values)) return $values;

        if (isset($values['All'])) return $default;

        $valuesIndexed = array();
        foreach($values as $value)
        {
            if ($value) $valuesIndexed[$value] = $value;
        }
        return $valuesIndexed;
    }
    /* ==========================================================
     * Schedule Teams query
     */
    public function querySchTeams($search,$games = array())
    {
        // Pull params
        $ages    = $this->getValues($search,'ages');
        $regions = $this->getValues($search,'regions');
        $genders = $this->getValues($search,'genders');

        $projectId = $this->getValues($search,'projectId');

        // Add in anyting from the games themselves
        foreach($games as $game)
        {
            foreach($game->getGameTeams() as $team)
            {
                if ($ages)    $ages   [$team->getAge()]    = $team->getAge();
                if ($regions) $regions[$team->getOrgKey()] = $team->getOrgKey();
                if ($genders) $genders[$team->getGender()] = $team->getGender();
            }
        }
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('schTeam');

        $qb->from('ZaysoBundle:SchTeam','schTeam');

        if ($projectId) $qb->andWhere($qb->expr()->in('schTeam.project',$projectId));

        if (count($ages))    $qb->andWhere($qb->expr()->in('schTeam.age',   $ages));
        if (count($regions)) $qb->andWhere($qb->expr()->in('schTeam.orgKey',$regions));
        if (count($genders)) $qb->andWhere($qb->expr()->in('schTeam.gender',$genders));

        $qb->addOrderBy('schTeam.teamKey');

        $teams = $qb->getQuery()->getResult();
        return $teams;

    }
    public function querySchTeamsPickList($search,$games = array())
    {
        $teams = $this->querySchTeams($search,$games);
        $options = array();
        foreach($teams as $team)
        {
            $key = $team->getTeamKey();
            $desc = sprintf('%s-%s-%s %s',substr($key,0,5),substr($key,5,4),substr($key,9,2),substr($key,12));
           $options[$team->getId()] = $desc;
        }
        return $options;
    }
    /* ==========================================================
     * Game Schedule query
     */
    public function queryGames($search)
    {
        // Pull params
        $ages    = $this->getValues($search,'ages');
        $regions = $this->getValues($search,'regions');
        $genders = $this->getValues($search,'genders');

        $sortBy  = $this->getValues($search,'sortBy',1);
        $date1   = $this->getValues($search,'date1');
        $date2   = $this->getValues($search,'date2');

        $projectId = $this->getValues($search,'projectId');

        // Build query
        $em = $this->getEntityManager();
        $qbGameId = $em->createQueryBuilder();

        $qbGameId->addSelect('distinct gameGameId.id');

        $qbGameId->from('ZaysoBundle:Game','gameGameId');

        $qbGameId->leftJoin('gameGameId.gameTeams','gameTeamGameId');

        if ($projectId) $qbGameId->andWhere($qbGameId->expr()->in('gameGameId.project',$projectId));

        if ($date1) $qbGameId->andWhere($qbGameId->expr()->gte('gameGameId.date',$date1));
        if ($date2) $qbGameId->andWhere($qbGameId->expr()->lte('gameGameId.date',$date2));

        if (count($ages))    $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.age',   $ages));
        if (count($regions)) $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.orgKey',$regions));
        if (count($genders)) $qbGameId->andWhere($qbGameId->expr()->in('gameTeamGameId.gender',$genders));

        //$gameIds = $qbGameId->getQuery()->getArrayResult();
        //return $gameIds;

        // Games
        $qbGames = $em->createQueryBuilder();

        $qbGames->addSelect('game');
        $qbGames->addSelect('gameTeam');
        $qbGames->addSelect('person');

        $qbGames->from('ZaysoBundle:Game','game');

        $qbGames->leftJoin('game.gameTeams','gameTeam');
        $qbGames->leftJoin('game.persons',  'person');

        $qbGames->andWhere($qbGames->expr()->in('game.id',$qbGameId->getDQL()));

        switch($sortBy)
        {
            case 1:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('game.time');
                $qbGames->addOrderBy('game.fieldKey');
                break;
            case 2:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('game.fieldKey');
                $qbGames->addOrderBy('game.time');
                break;
            case 3:
                $qbGames->addOrderBy('game.date');
                $qbGames->addOrderBy('game.age');
                $qbGames->addOrderBy('game.time');
                break;
        }
        // Always get an array even if no records found
        $query = $qbGames->getQuery();
        $items = $query->getResult();
        
        return $items;
    }
    /* ========================================================================
     * Basic one game
     */
    public function loadGame($project,$num)
    {
        $em = $this->getEntityManager();
    
        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;
    
        $search = array('project' => $projectId, 'num' => $num);

        $item = $this->findOneBy($search);
        if ($item) return $item;

        return null;
    }
    /* ========================================================================
     * Schedule team
     */
    public function loadSchTeam($project,$key)
    {
        $em = $this->getEntityManager();

        // Search for existing
        $repo = $em->getRepository('ZaysoBundle:SchTeam');

        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;

        $search = array('project' => $projectId, 'teamKey' => $key);

        $item = $repo->findOneBy($search);
        if ($item) return $item;

        $search = array('project' => $projectId, 'teamKey2' => $key);
        $item = $repo->findOneBy($search);
        if ($item) return $item;

        return null;
    }
}
?>
