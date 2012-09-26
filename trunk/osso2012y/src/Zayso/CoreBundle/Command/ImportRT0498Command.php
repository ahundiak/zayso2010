<?php

namespace Zayso\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportRT0498Command extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('core:importRT0498')
            ->setDescription('Import RT 0498')
        ;
    }
    protected function importSchedule()
    {
        $import = $this->getContainer()->get('zayso_core.scheduleRT0498.import');
        
        $params = array
        (
            'inputFileName' => '../datax/R0498RT20120915.xls',
            'projectId'     => 80,
        );
        $import->process($params);
        echo $import->getResultmessage() . "\n";
        
    }
  protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importSchedule();
    }
}
?>
