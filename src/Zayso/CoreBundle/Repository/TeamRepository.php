<?php
namespace Zayso\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/* =========================================================================
 * The repository
 */
class TeamRepository extends EntityRepository
{
    public function qbTeams()
    {
        $qb = $this->createQueryBuilder('team');
        $qb->addOrderBy('team.key1');
        return $qb;
    }
}
?>
