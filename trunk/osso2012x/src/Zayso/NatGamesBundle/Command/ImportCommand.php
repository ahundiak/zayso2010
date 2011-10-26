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
            ->setName('NatGames:import')
            ->setDescription('Import Accounts')
            ->addArgument('file', InputArgument::OPTIONAL, 'Input File Name','../datax/Accounts20111025.csv')
        ;
    }
    protected function importAccounts($file)
    {
        $params = array(
            'inputFileName'  => $file,
          //'clientFileName' => $file,
            'projectId'      => 52);

        $import = new AccountImport($this->getEntityManager(),$this->getContainer()->get('account.manager'));
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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputFileName = $input->getArgument('file');

        $this->getAccounts();

        $this->importAccounts($inputFileName);

        return;        
    }
}
