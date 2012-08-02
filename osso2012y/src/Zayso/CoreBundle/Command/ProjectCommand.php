<?php

namespace Zayso\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\CoreBundle\Entity\PersonPerson;

class ProjectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('core:project')
            ->setDescription('Project Stuff')
        ;
    }
    protected $projectDatas = array
    (
        array('id' => 40, 'desc1' => 'Master Area 5C/5F',                                'status' => 'Master', 'parentId' => null, 'projectGroupId' => 1),

        array('id' => 70, 'desc1' => 'CY2011 Fall Regular Season Area 5C/5F',            'status' => 'Past',   'parentId' => 40, 'projectGroupId' => 1),
        array('id' => 71, 'desc1' => 'CY2011 Fall Regional Tournament R0894 Monrovia',   'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 72, 'desc1' => 'CY2011 Fall Regional Tournament R0498 Madison',    'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 73, 'desc1' => 'CY2011 Fall Regional Tournament R0160 Huntsville', 'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 74, 'desc1' => 'CY2011 Fall Regional Tournament R1174 NEMC',       'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 75, 'desc1' => 'CY2011 Fall Area Tournament Area 5C',              'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 76, 'desc1' => 'CY2011 Fall State Tournament Area 5C/5F',          'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 77, 'desc1' => 'CY2012 Winter Regular Season R0498 Madison',       'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 78, 'desc1' => 'CY2012 Spring Regular Season Area 5C/5F',          'status' => 'Past',   'parentId' => 70, 'projectGroupId' => 1),
        array('id' => 79, 'desc1' => 'CY2012 Spring Sendoff Tournament Area 5C/5F',      'status' => 'Past',   'parentId' => 78, 'projectGroupId' => 1),

        array('id' => 80, 'desc1' => 'CY2012 Fall Regular Season Area 5C/5F',            'status' => 'Active', 'parentId' => 40, 'projectGroupId' => 1),
        array('id' => 81, 'desc1' => 'CY2012 Fall Regional Tournament R0894 Monrovia',   'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 82, 'desc1' => 'CY2012 Fall Regional Tournament R0498 Madison',    'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 83, 'desc1' => 'CY2012 Fall Regional Tournament R0160 Huntsville', 'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 84, 'desc1' => 'CY2012 Fall Regional Tournament R1174 NEMC',       'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 85, 'desc1' => 'CY2012 Fall Area Tournament Area 5C',              'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 86, 'desc1' => 'CY2012 Fall State Tournament Area 5C/5F',          'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 87, 'desc1' => 'CY2013 Winter Regular Season R0498 Madison',       'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 88, 'desc1' => 'CY2013 Spring Regular Season Area 5C/5F',          'status' => 'Future', 'parentId' => 80, 'projectGroupId' => 1),
        array('id' => 89, 'desc1' => 'CY2013 Spring Sendoff Tournament Area 5C/5F',      'status' => 'Future', 'parentId' => 88, 'projectGroupId' => 1),
    );
    protected function updateProjects()
    {
        $manager = $this->getContainer()->get('zayso_core.project.manager');
        
        foreach($this->projectDatas as $projectData)
        {
            $id = (int)$projectData['id'];
            
            $project = $manager->loadProjectForId($id);
            if (!$project)
            {
                $project = $manager->newProject();
                $project->setId($id);
                $manager->persist($project);
            }
            $project->setDescription ($projectData['desc1']);
            $project->setStatus      ($projectData['status']);
            
            $parentId = $projectData['parentId'];
            
            if ($parentId) $parent = $manager->getProjectReference($parentId);
            else           $parent = null;
            
            $project->setParent($parent);
            
            // Always have a group
            $project->setProjectGroup($manager->getProjectGroupReference($projectData['projectGroupId']));
        }
        $manager->flush();
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateProjects();
    }
}
?>
