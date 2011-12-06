<?php

namespace Zayso\ZaysoBundle\Component\Manager;

use Zayso\ZaysoBundle\Component\Debug;

use Doctrine\ORM\ORMException;

use Zayso\ZaysoBundle\Entity\ProjectPerson;
use Zayso\ZaysoBundle\Entity\Project;

class ProjectManager
{
    protected $em = null;

    public function getEntityManager() { return $this->em; }

    public function __construct($em) { $this->em = $em; }

    public function getProjectForId($projectId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->addSelect('project');

        $qb->from('ZaysoBundle:Project','project');
        $qb->andWhere($qb->expr()->eq('project.id',':projectId'));

        $query = $qb->getQuery();
        $query->setParameter('projectId',$projectId);

        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
    public function getProjectGroupForKey($key)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->addSelect('projectGroup');

        $qb->from('ZaysoBundle:ProjectGroup','projectGroup');
        $qb->andWhere($qb->expr()->eq('projectGroup.key',':key'));

        $query = $qb->getQuery();
        $query->setParameter('key',$key);

        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
    public function getOrgForKey($key)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->addSelect('org');

        $qb->from('ZaysoBundle:Org','org');
        $qb->andWhere($qb->expr()->eq('org.id',':orgId'));

        $query = $qb->getQuery();
        $query->setParameter('orgId',$key);

        $items = $query->getResult();

        if (count($items) == 1) return $items[0];

        return null;
    }
}
?>
