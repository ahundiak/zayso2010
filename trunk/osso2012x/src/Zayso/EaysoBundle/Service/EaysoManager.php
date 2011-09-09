<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\EaysoBundle\Service;

use Zayso\EaysoBundle\Component\Debug;

class EaysoManager
{
    protected $em = null;
    
    protected function getEntityManager() { return $this->em; }

    public function __construct($em)
    {
        $this->em = $em;
    }
    public function loadVolCerts($aysoid)
    {
        if (substr($aysoid,0,5) != 'AYSOV') $aysoid = 'AYSOV' . $aysoid;

        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('vol');
        $qb->addSelect('cert');

        $qb->from('EaysoBundle:Volunteer','vol');

        $qb->leftJoin('vol.certifications','cert');

        $qb->andWhere($qb->expr()->eq('vol.id',':aysoid'));
        $qb->setParameter('aysoid',$aysoid);

        $query = $qb->getQuery();
        $item = $query->getSingleResult();
        return $item;
    }
}
?>
