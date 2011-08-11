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

class QueryCommand extends BaseCommandx
{
    protected function configure()
    {
        $this
            ->setName('zayso:query')
            ->setDescription('Test Queries')
        ;
    }
    protected function queryGames()
    {
        $projectId = 70; //array(70);
        $ages    = array('U14','U16', 'U19',);
        $genders = array('B', 'C', 'G');
        $date1   = '20110810';
        $date2   = '20110830';

        $search = array(
            'projectId' => $projectId,
            'ages'      => $ages,
            'genders'   => $genders,
            'date1'     => $date1,
            'date2'     => $date2
        );

        $gameRepo = $this->getEntityManager()->getRepository('ZaysoBundle:Game');

        $games = $gameRepo->queryGames($search);
        $gameCount = count($games);
        echo "Game Count: {$gameCount}\n";

        $teams = $gameRepo->querySchTeamsPickList($search,$games);
        $teamCount = count($teams);
        echo "Team Count: {$teamCount}\n";
        //print_r($teams);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->queryGames($output);
    }
}
