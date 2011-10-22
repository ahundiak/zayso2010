<?php
/* ========================================================================
 * The basic idea is to encapsulate as much of this stuff as possible in a service
 */
namespace Zayso\NatGamesBundle\Service;

use Zayso\ZaysoBundle\Component\Debug;

use Doctrine\ORM\ORMException;

class AccountManager
{
    protected $em = null;
    protected $eaysoManager = null;
    
    protected function getEntityManager()
    {
        return $this->em;
    }

    public function __construct($em,$eaysoManager)
    {
        $this->em = $em;
        $this->eaysoManager = $eaysoManager;

        //$ids = $services->getServiceIds();
        //print_r($ids);
        //die(get_class($services));
    }
    public function getAccounts()
    {
        // Build query
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('account');
        $qb->addSelect('memberx');
        $qb->addSelect('person');
        $qb->addSelect('registered');
        $qb->addSelect('projectPerson');

        $qb->from('ZaysoBundle:Account','account');

        $qb->leftJoin('account.members',      'memberx');
        $qb->leftJoin('memberx.person',       'person');
        $qb->leftJoin('person.registereds',   'registered');
        $qb->leftJoin('person.projects',      'projectPerson');
        $qb->leftJoin('projectPerson.project','project');

        $query = $qb->getQuery();
      //die('DQL ' . $query->getSQL());
        return $query->getResult();
        
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
        try
        {
            $item = $query->getSingleResult();
        }
        catch (ORMException $e)
        {
            return null; // If none found
        }
        return $item;
    }
}
?>
