<?php

namespace Zayso\S5GamesBundle\Command;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\ExcelBaseImport;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('s5games:import')
            ->setDescription('Import Testing')
        ;
    }
    protected function testExport2011Schedule()
    {
        $export = $this->getContainer()->get('zayso_s5games.game2011.export');
        
        file_put_contents('../datax/S5Games2011.xls',$export->generate());
        
    }
    protected function testImport2011Schedule()
    {
        $import = $this->getContainer()->get('zayso_core.game.tourn.import');
        
        $params = array(
            'projectId'     => 61,
            'inputFileName' => '../datax/S5Games2011.xls',
            'sheetName'     => 'Schedule',
        );
        $results = $import->process($params);
        Debug::dump($results);
    }
    protected function testImport2012Teams()
    {
        $import = $this->getContainer()->get('zayso_core.game.teams.import');
        
        $params = array(
            'projectId'     => 62,
            'inputFileName' => '../datax/S5Games2012Teams20120515.xls',
            'sheetName'     => 'Teams',
        );
        $results = $import->process($params);
        Debug::dump($results);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->testImport2012Teams();
    }
}
