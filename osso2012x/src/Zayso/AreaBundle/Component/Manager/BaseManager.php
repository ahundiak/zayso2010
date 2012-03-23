<?php
/* --------------------------------------------------------------------
 * Even though this is a base, move some of the functionality here for now
 * Later on want to refactor into a more specific manager
 * But have to many copies of stuff for now
 */
namespace Zayso\AreaBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Project;
use Zayso\CoreBundle\Entity\ProjectField;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event;
use Zayso\CoreBundle\Entity\EventTeam;
use Zayso\CoreBundle\Entity\EventPerson;

class BaseManager
{
    protected $em = null;
    
    public function getEntityManager() { return $this->em; }
    
    public function clear()        { $this->em->clear(); }
    public function flush()        { $this->em->flush(); }
    public function remove ($item) { $this->em->remove ($item); }
    public function detach ($item) { $this->em->detach ($item); }
    public function persist($item) { $this->em->persist($item); }
    public function refresh($item) { $this->em->refresh($item); }
    
    public function __construct($em)
    {
        $this->em = $em;
    }    
    public function getProjectReference($projectId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:Project',$projectId);
    }
    public function getRegionReference($orgId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:Org',$orgId);
    }
    public function getPersonReference($personId)
    {
        return $this->getEntityManager()->getReference('ZaysoCoreBundle:Person',$personId);
    }
    /* ==============================================================
     * New protype team
     * Verified being used
     */
    public function newGameWithTeams($project)
    {
        if (!$project) return null;
        
        if (!is_object($project)) $project = $this->getProjectReference($project);
        
        $game = new Event();
        $game->setProject($project);
        
        $team = new EventTeam();
        $team->setTypeAsHome();
        $team->setGame($game);
        $game->addTeam($team);
        
        $team = new EventTeam();
        $team->setTypeAsAway();
        $team->setGame($game);
        $game->addTeam($team);
        
        return $game;
    }
    /* =================================================================
     * Clone an existing validated game
     * Verified being used
     */
    public function cloneGame($game)
    {
        $gamex = $this->newGameWithTeams($game->getProject());
        
        $gamex->setField ($game->getField());
        $gamex->setOrg   ($game->getOrg());
        $gamex->setType  ($game->getType());
        $gamex->setDate  ($game->getDate());
        $gamex->setTime  ($game->getTime());
        $gamex->setPool  ($game->getPool());
        $gamex->setStatus('Active');
      
        $gamex->getHomeTeam()->setTeam($game->getHomeTeam()->getTeam());
        $gamex->getAwayTeam()->setTeam($game->getAwayTeam()->getTeam());
        
        $gamex->setNum($this->getNextGameNum($game->getProject()));
        
        foreach($game->getPersons() as $person)
        {
            $personx = new EventPerson();
            
            $personx->setEvent($gamex);
            $personx->setType     ($person->getType());
            $personx->setProtected($person->getProtected());
          //$personx->setState    ('Active');
            
            $gamex->addPerson($personx);
        }
        return $gamex;
    }
    /* ===================================================================
     * Get next available game number fo a given project
     * Verified being used
     */
    public function getNextGameNum($project)
    {
        if (!$project) return null;
        
        if (is_object($project)) $projectId = $project->getId();
        else                     $projectId = $project;
        
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->addSelect($qb->expr()->max('event.num'));

        $qb->from('ZaysoCoreBundle:Event','event');

        $qb->andWhere($qb->expr()->eq('event.project',$projectId));
        
        $query = $qb->getQuery();
      
        $num = $query->getSingleScalarResult();
        
        return $num + 1;
    }
    /* ========================================================================
     * Delete an individual game along with event teams and event persons
     * Not needed as long as cascade does not cause issues
     */
    public function deleteGame($game)
    {
        $em = $this->getEntityManager();
        //foreach($game->getEventTeams  () as $team)   { $em->remove($team);   }
        //foreach($game->getEventPersons() as $person) { $em->remove($person); }
        $em->remove($game);
   }
    /* ========================================================================
     * Single event stuff
     * Verified used by game edit and referee assinging
     */
    public function loadEventForId($id)
    {
        // Build query
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->addSelect('event');
        $qb->addSelect('field');
        $qb->addSelect('eventTeam');
        $qb->addSelect('team');
        $qb->addSelect('eventPerson');
        $qb->addSelect('person');

        $qb->from('ZaysoCoreBundle:Event', 'event');
        $qb->leftJoin('event.project',     'project');
        $qb->leftJoin('event.field',       'field');
        $qb->leftJoin('event.teams',       'eventTeam');
        $qb->leftJoin('eventTeam.team',    'team');
        $qb->leftJoin('event.persons',     'eventPerson');
        $qb->leftJoin('eventPerson.person','person');

        $qb->andWhere($qb->expr()->eq('event.id',$qb->expr()->literal($id)));
        
        $items = $qb->getQuery()->getResult();
        if (count($items) == 1) return $items[0];
        
        return null;
    }
    /* ========================================================================
     * Returns a list of all the referees associated with a given account
     * and (eventually) project
     * Verified used by referee assigning
     */
    public function getOfficialsForAccount($projectId,$accountId)
    {
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('person');
        $qb->addSelect('personRegistered');

        $qb->from('ZaysoCoreBundle:Person',       'person');
        $qb->leftJoin('person.accountPersons',    'accountPerson');
        $qb->leftJoin('person.registeredPersons', 'personRegistered');
        $qb->leftJoin('person.projectPersons',    'projectPerson');

        $qb->andWhere($qb->expr()->eq('accountPerson.account',$qb->expr()->literal($accountId)));

        $items = $qb->getQuery()->getResult();
        
        return $items;
    }
}
?>
