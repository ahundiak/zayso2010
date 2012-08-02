<?php
namespace Zayso\CoreBundle\Manager;

use Zayso\CoreBundle\Entity\Project;
use Zayso\CoreBundle\Entity\ProjectGroup;

class ProjectManager extends BaseManager
{
    public function newProject() { return new Project; }
    
    public function loadProjectForId($projectId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('project');
        
        $qb->from('ZaysoCoreBundle:Project','project');
        
        $qb->andWhere($qb->expr()->eq('project.id',$projectId));
        
        return $qb->getQuery()->getOneOrNullResult();
    }
}

?>
