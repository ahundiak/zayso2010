<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RegionCommand extends BaseCommandx
{
    protected function configure()
    {
        $this
            ->setName('zayso:region')
            ->setDescription('Loads region orgaization information')
        ;
    }
    protected function importRegions()
    {
        $inputFileName = '../datax/Regions.csv';
        $params = array
        (
            'inputFileName' => $inputFileName,
        );
        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2012');

        $import = $this->getContainer()->get('zayso.core.region.import');
        $results = $import->process($params);
        echo "Zayso Import {$results['msg']} \n";

    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importRegions();
    }
}
