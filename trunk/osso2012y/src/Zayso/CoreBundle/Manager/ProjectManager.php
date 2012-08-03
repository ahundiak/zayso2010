<?php
namespace Zayso\CoreBundle\Manager;

use Zayso\CoreBundle\Entity\Project;
use Zayso\CoreBundle\Entity\ProjectPerson;
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
    public function loadProjectPerson($projectId,$personId,$autoCreate = false)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->addSelect('projectPerson');
        
        $qb->from('ZaysoCoreBundle:ProjectPerson','projectPerson');
        
        $qb->andWhere($qb->expr()->eq('projectPerson.project',$projectId));
        $qb->andWhere($qb->expr()->eq('projectPerson.person', $personId ));
        
        $projectPerson = $qb->getQuery()->getOneOrNullResult();
        
        if ($projectPerson) return $projectPerson;
        
        if (!$autoCreate) return null;
        
        $projectPerson = new ProjectPerson();
        $projectPerson->setProject($this->getProjectReference($projectId));
        $projectPerson->setPerson ($this->getPersonReference ($personId));
        
        $this->persist($projectPerson);
        $this->flush();
        return $projectPerson;
    }
}

?>
