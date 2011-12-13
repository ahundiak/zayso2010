<?php

namespace Zayso\ZaysoAreaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('area:user')
            ->setDescription('User Tests')
        ;
    }
    protected function test1()
    {
        $provider = $this->getContainer()->get('zayso_core.user.provider');
        $user = $provider->loadUserByUsername('ahundiak');
        echo 'User ' . get_class($user) . "\n";

    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test1();
    }
}
