<?php

namespace Zayso\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\CoreBundle\Entity\PersonPerson;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('core:import')
            ->setDescription('Import Stuff')
        ;
    }
    protected function testImport1()
    {
        $excel = $this->getContainer()->get('zayso_core.excel.format');
        
        $file = '../datax/misc/Teams.csv';
        $ss = $excel->load($file);
        
    }
    protected function testImport2()
    {
        $import = $this->getContainer()->get('zayso_core.eayso.team.summary.import');
        
        $params = array
        (
            'inputFileName' => '../datax/misc/Teams.csv',
            'projectId'     => 80,
        );
        $import->process($params);
        echo $import->getResultmessage() . "\n";
        
    }
    protected function testImportSchedule()
    {
        $import = $this->getContainer()->get('zayso_core.schedule.import');
        
        $params = array
        (
            'inputFileName' => '../datax/AYSO2012FallGameScheduleM6R0498.xls',
            'projectId'     => 80,
        );
        $import->process($params);
        echo $import->getResultmessage() . "\n";
        
    }
  protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->testImportSchedule();
    }
}
?>
