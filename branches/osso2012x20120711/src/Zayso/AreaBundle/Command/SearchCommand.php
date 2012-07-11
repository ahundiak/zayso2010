<?php

namespace Zayso\AreaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SearchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('area:search')
            ->setDescription('Search Tests')
        ;
    }
    protected function test1()
    {
        $searchManager = $this->getContainer()->get('zayso_area.search.manager');
        $searchEntity = $searchManager->newSearchEntity();
        
        $searchEntity->lastName = 'Hun';
        $items = $searchManager->search($searchEntity);
        
        echo 'Count: ' . count($items) . "\n";
        foreach($items as $person)
        {
            echo sprintf("Person %s %s %s\n",
                $person->getLastName(),
                $person->getAysoid(),
                $person->getRefBadge()
            );
        }
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test1();
    }
}
