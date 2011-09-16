<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\EaysoBundle\Service;

use Zayso\EaysoBundle\Component\Debug;

class EaysoManager
{
    protected $em = null;
    
    protected function getEntityManager()
    {
        return $this->em;
        return $this->services->get('doctrine')->getEntityManager('eayso');
    }

    public function __construct($em,$services)
    {
        $this->em = $em;
        $this->services = $services;

        //$ids = $services->getServiceIds();
        //print_r($ids);
        //die(get_class($services));
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
