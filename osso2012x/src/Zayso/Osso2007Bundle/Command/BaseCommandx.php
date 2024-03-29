<?php

namespace Zayso\Osso2007Bundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommandx extends ContainerAwareCommand
{
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager('osso2007');
    }
    protected function getGameManager()
    {
        return $this->getContainer()->get('game.manager');
    }
    protected function getTeamManager()
    {
        return $this->getContainer()->get('team.manager');
    }
}
