<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\ZaysoBundle\Import\PhyTeamImport;

class ImportCommand extends ContainerAwareCommand
{
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }
    protected function configure()
    {
        $this
            ->setName('zayso:import')
            ->setDescription('Import From Spreadsheets')
            ->addArgument('file', InputArgument::OPTIONAL, 'Input File Name','../datax/Teams0498.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputFileName = $input->getArgument('file');

        $params = array
        (
            'projectId'     => 70,
            'inputFileName' => $inputFileName,
        );
        $import = new PhyTeamImport($this->getEntityManager());
        $results = $import->process($params);

        echo "Zayso Import $inputFileName {$results['msg']} \n";
        print_r($results);

    }
}
