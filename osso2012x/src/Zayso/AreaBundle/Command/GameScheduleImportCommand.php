<?php
namespace Zayso\AreaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GameScheduleImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('area:game')
            ->setDescription('Game Schedule Import')
        ;
    }
    protected function getGameManager() { return $this->getContainer()->get('zayso_area.game.manager'); }
    protected function getProjectId()   { return 37; }
    
    protected function test1()
    {
        $gameManager = $this->getGameManager();
        
    }
    protected function test3()
    {
        $import = $this->getContainer()->get('zayso_area.game.schedule.import');
        
        $params = array();
      //$params['projectId'] = $this->getProjectId();
        $params['inputFileName'] = '../datax/SpringSchedule20120322.csv';
        
        $import->process($params);
        echo $import->getResultMessage() . "\n";
    }
    protected function testGetOfficialsForAccount()
    {
        $manager = $this->getContainer()->get('zayso_area.game.schedule.manager');
        $accountId = 1;

        $officials = $manager->getOfficialsForAccount(0,$accountId);
        echo 'Officials ' . count($officials) . "\n";
    }
    protected function testSendoffImport()
    {
        $import = $this->getContainer()->get('zayso_area.sendoff.import');
        
        $params = array();
        $params['projectId'] = 79; //$this->getProjectId();
        $params['inputFileName'] = '../datax/Sendoff20120525.xls';
        
        $import->process($params);
        echo $import->getResultMessage() . "\n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->testSendoffImport();
    }
}
?>
