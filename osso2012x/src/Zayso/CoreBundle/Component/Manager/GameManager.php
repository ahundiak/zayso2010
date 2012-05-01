<?php
/* --------------------------------------------------------------------
 * Even though this is a base, move some of the functionality here for now
 * Later on want to refactor into a more specific manager
 * But have to many copies of stuff for now
 */
namespace Zayso\CoreBundle\Component\Manager;

use Zayso\CoreBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\CoreBundle\Entity\Org;
use Zayso\CoreBundle\Entity\Project;
use Zayso\CoreBundle\Entity\ProjectField;

use Zayso\CoreBundle\Entity\Team;
use Zayso\CoreBundle\Entity\Event;
use Zayso\CoreBundle\Entity\EventTeam;
use Zayso\CoreBundle\Entity\EventPerson;

class GameManager
{
    protected $em = null;
    
    public function getEntityManager() { return $this->em; }
    
    public function clear()        { $this->em->clear(); }
    public function flush()        { $this->em->flush(); }
    public function remove ($item) { $this->em->remove ($item); }
    public function detach ($item) { $this->em->detach ($item); }
    public function persist($item) { $this->em->persist($item); }
    public function refresh($item) { $this->em->refresh($item); }
    
    public function __construct($em) { $this->em = $em; }
    
    /* ========================================================================
     * Single event stuff
     * Verified used by game edit and referee assinging
     * Game Report
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
        
        return $qb->getQuery()->getOneOrNullResult();

    }
}
?>
