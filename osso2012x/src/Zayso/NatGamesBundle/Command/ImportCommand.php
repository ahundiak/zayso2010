<?php

namespace Zayso\NatGamesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\NatGamesBundle\Component\Import\AccountImport;

use Zayso\ZaysoBundle\Component\Debug;

class ImportCommand extends ContainerAwareCommand
{
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager('osso2012');
    }
    protected function configure()
    {
        $this
            ->setName('natgames:import')
            ->setDescription('Import Stuff')
            ->addArgument('file', InputArgument::OPTIONAL, 'Input File Name','../datax/AccountImports20111223.csv')
        ;
    }
    protected function importAccounts($file)
    {
        $params = array(
            'inputFileName'  => $file,
            'projectId'      => 52);
        
        $import = $this->getContainer()->get('zayso.natgames.account.import');

        //$import->processArea('A07O-R0178 Aiea, HI');
        //return;
        
        $results = $import->process($params);

        echo "Zayso Import {$results['msg']} \n";
    }
    protected function getAccounts()
    {
        $accountManager = $this->getContainer()->get('account.manager');

        $params = array('accountId' => array(1), 'projectId' => 52);

        $accounts = $accountManager->getAccountPersons($params);
        
        echo 'Account Count ' . count($accounts) . "\n";
    }
    protected function importSchedule2010($fileName)
    {
        $params = array(
            'inputFileName'  => $fileName,
            'projectId'      => 50);

        $import = $this->getContainer()->get('schedule2010.import');
        $results = $import->process($params);
        echo "Zayso Import {$results['msg']} \n";
    }
    protected function importTeams()
    {
         $import = $this->getContainer()->get('zayso_natgames.team.import');
         $params = array
         (
            'inputFileName'  => '../datax/NatGamesTeams20120506.xls',
            'projectId'      => 52,
            'type'           => 'regular',
         );
        $results = $import->process($params);
        echo "Zayso Import {$results['msg']} \n";
       
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $inputFileName = $input->getArgument('file');

        // $this->importAccounts($inputFileName);

        // $this->importSchedule2010('../datax/Schedule2010.csv');
        
        $this->importTeams();
        

        return;        
    }
}
