<?php

namespace Zayso\EaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\EaysoBundle\Import\VolunteerImport;
use Zayso\EaysoBundle\Import\CertificationImport;
use Zayso\EaysoBundle\Component\Debug;

class TestCommand extends ContainerAwareCommand
{
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }
    protected function configure()
    {
        $this
            ->setName('eayso:test')
            ->setDescription('Eayso Tests')
            ->addArgument('file', InputArgument::OPTIONAL, 'Input File Name','../datax/Vols.csv')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getEntityManager();
        $eaysoRepo = $em->getRepository('EaysoBundle:Volunteer');

        $aysoid = '99437977';
        $vol = $eaysoRepo->loadVolCerts($aysoid);
        die($vol->getLastName());
        Debug::dump($vol);

   }
}
