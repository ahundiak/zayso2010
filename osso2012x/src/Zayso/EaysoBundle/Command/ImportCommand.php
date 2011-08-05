<?php

namespace Zayso\EaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\EaysoBundle\Import\VolunteerImport;
use Zayso\EaysoBundle\Import\CertificationImport;

class ImportCommand extends ContainerAwareCommand
{
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }
    protected function configure()
    {
        $this
            ->setName('eayso:import')
            ->setDescription('Import Eayso Volunteer/Certs')
            ->addArgument('file', InputArgument::OPTIONAL, 'Input File Name','../datax/Vols.csv')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputFileName = $input->getArgument('file');

        $params = array
        (
            'inputFileName' => $inputFileName,
        );
        $import = new VolunteerImport($this->getEntityManager());
        $results = $import->process($params);

        echo "Zayso Import $inputFileName {$results['msg']} \n";
        print_r($results);

        $params = array
        (
            'inputFileName' => '../datax/RefCerts.csv',
        );
        $import = new CertificationImport($this->getEntityManager());
        $results = $import->process($params);

        echo "Zayso Import $inputFileName {$results['msg']} \n";
        print_r($results);
    }
}
