<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\Account;
use Zayso\ZaysoBundle\Entity\AccountPerson;

use Zayso\ZaysoBundle\Component\Security\Core\User\User as User;

class BaseCommandx extends ContainerAwareCommand
{
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getEntityManager();
    }
}
