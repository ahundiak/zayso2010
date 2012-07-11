<?php

namespace Zayso\NatGamesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\CoreBundle\Component\Debug;

class RefStatsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('NatGames:stats')
            ->setDescription('Ref Stats')
        ;
    }
    protected function stats1()
    {
        $manager = $this->getContainer()->get('zayso_natgames.ref_stats.manager');
        $manager->process();
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->stats1();
        
        return;        
    }
}
