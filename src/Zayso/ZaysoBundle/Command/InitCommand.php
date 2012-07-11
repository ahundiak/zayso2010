<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;

use Zayso\ZaysoBundle\Component\Security\Core\User\User as User;

class InitCommand extends BaseCommandx
{
    protected function configure()
    {
        $this
            ->setName('zayso:init')
            ->setDescription('Loads initial database information')
        ;
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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initProject($output);

    }
}
