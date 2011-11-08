<?php

namespace Zayso\Osso2007Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\Osso2007Bundle\Entity\PhyTeam;
use Zayso\Osso2007Bundle\Entity\PhyTeamPlayer;

use Zayso\Osso2007Bundle\Component\Import\PhyTeamImport;
use Zayso\Osso2007Bundle\Component\Import\PlayerImport;

class PlayersCommand extends BaseCommandx
{
    protected function configure()
    {
        $this->setName       ('osso2007:players');
        $this->setDescription('Import Players');
    }
    protected function importPhyTeam($fileName)
    {
        $import = new PhyTeamImport($this->getTeamManager());

        $params = array('projectId' => 70, 'inputFileName' => '../datax/teamsx/' . $fileName);
        $results = $import->process($params);
        echo $results['msg'] . "\n";
    }
    protected function importPlayer($fileName)
    {
        $import = new PlayerImport($this->getTeamManager());

        $params = array('projectId' => 70, 'inputFileName' => '../datax/teamsx/' . $fileName);
        $results = $import->process($params);
        echo $results['msg'] . "\n";
    }
     protected function execute(InputInterface $input, OutputInterface $output)
    {
      //$this->importPhyTeam('Teams0160.csv');
      //$this->importPhyTeam('Teams0498.csv');
      //$this->importPhyTeam('Teams0557.csv');
      //$this->importPhyTeam('Teams0622.csv');
      //$this->importPhyTeam('Teams0894.csv');
      //$this->importPhyTeam('Teams0914.csv');
      //$this->importPhyTeam('Teams0991.csv');
      //$this->importPhyTeam('Teams1174.csv');

      //$this->importPlayer ('Rosters0160.csv');
      //$this->importPlayer ('Rosters0498.csv');
      //$this->importPlayer ('Rosters0557.csv');
      //$this->importPlayer ('Rosters0622.csv');
      //$this->importPlayer ('Rosters0894.csv');
      //$this->importPlayer ('Rosters0914.csv');
      //$this->importPlayer ('Rosters0991.csv');
      //$this->importPlayer ('Rosters1174.csv');

        return;
        $import = new PlayerImport($this->getTeamManager());
        
        $params = array('projectId' => 70, 'inputFileName' => '../datax/teamsx/Rosters0894.csv');
        $results = $import->process($params);

        // $this->addPlayer();
        return;
        
    }
    protected function addPlayer()
    {
        $player = new PhyTeamPlayer();
        $player->setFirstName('Ethan');
        $player->setLastName ('Hundiak');
        $player->setAysoid('12345678');
        $player->setJersey(5);

        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2007');
        $em->persist($player);
        $em->flush();
    }
}
