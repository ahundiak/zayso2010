<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\ZaysoBundle\Component\Import\ProjectImport;

use Zayso\ZaysoBundle\Entity\Project;

use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;

class ProjectCommand extends BaseCommandx
{
    protected function configure()
    {
        $this
            ->setName('zayso:project')
            ->setDescription('Loads project information')
        ;
    }
    protected function exportOsso2007Projects()
    {
        $fp = fopen('../datax/projects.csv','wt');
        fputs($fp,"PID,Type,Description,Status,Date Begin,Date End,Mem Year,Cal Year,Season\n");

        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2007');
        $qb = $em->createQueryBuilder();

        $qb->addSelect('project');

        $qb->from('Osso2007Bundle:Project','project');

        $qb->addOrderBy('project.id');

        $query = $qb->getQuery();
        $projects = $query->getResult();
        foreach($projects as $project)
        {
            switch($project->getStatus())
            {
                case 1:  $status = 'Active'; break;
                case 2:  $status = 'Future'; break;
                case 3:  $status = 'Past';   break;
                default: $status = 'Unknown';
            }
            switch($project->getTypeId())
            {
                case 1:  $type = 'RS'; break;
                case 2:  $type = 'RT'; break;
                case 3:  $type = 'AT'; break;
                case 4:  $type = 'ST'; break;
                case 5:  $type = 'SG'; break;
                case 6:  $type = 'NG'; break;
                default: $type = 'Unknown';
            }
            switch($project->getSeasonTypeId())
            {
                case 1:  $seasonType = 'Fall';   break;
                case 2:  $seasonType = 'Winter'; break;
                case 3:  $seasonType = 'Spring'; break;
                case 4:  $seasonType = 'Summer'; break;
                default: $seasonType = 'Unknown';
            }
             $line = sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                    $project->getId(),
                    $type,
                    $project->getDesc1(),
                    $status,
                    $project->getDateBeg(),
                    $project->getDateEnd(),
                    $project->getMemYear(),
                    $project->getCalYear(),
                    $seasonType
            );
            fputs($fp,$line);
        }
        fclose($fp);
    }
    protected function initProject($output)
    {
        $em = $this->getEntityManager();

        // Still need this to avoid integrity contstraint
        $query = $em->createQuery('DELETE ZaysoBundle:ProjectPerson item');
        $query->getResult();

        $query = $em->createQuery('DELETE ZaysoBundle:Project item');
        $query->getResult();

        $project = new Project();
        $project->setId(52);
        $project->setDesc1('AYSO National Games 2012');
        $project->setStatus('Active');
        $em->persist($project);

        $project = new Project();
        $project->setId(70);
        $project->setDesc1('AYSO Area 5C/F Fall 2011');
        $project->setStatus('Active');
        $em->persist($project);

        $em->flush();

    }
    protected function importProjects()
    {
        $inputFileName = '../datax/projects.csv';
        $params = array
        (
            'inputFileName' => $inputFileName,
        );
        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2012');

        $import = $this->getContainer()->get('zayso.core.project.import');
        $results = $import->process($params);
        echo "Zayso Import {$results['msg']} \n";

    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      //$this->initProject($output);

      //$this->exportOsso2007Projects();
        $this->importProjects();
    }
}
