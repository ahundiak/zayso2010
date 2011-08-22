<?php

namespace Zayso\Osso2007Bundle\Command;

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
            ->setName('osso2007:query')
            ->setDescription('Test Queries')
        ;
    }
    protected function queryGames()
    {
        $gameManager = $this->getGameManager();

        $projectId = 70; //array(70);
        $ages    = array('U14','U16', 'U19',);
        $genders = array('B', 'C', 'G');
        $regions = array('R0894', 'R1174','R0160','R0498');
        
        $date1   = '20110818';
        $date2   = '20110818';

        $search = array(
            'projectId' => $projectId,
            'ages'      => $ages,
            'genders'   => $genders,
            'regions'   => $regions,
            'date1'     => $date1,
            'date2'     => $date2
        );

        $games = $gameManager->queryGames($search);
        $gameCount = count($games);
        echo "Game Count: {$gameCount}\n";
        foreach($games as $game)
        {
            echo "{$game->getEventNum()} {$game->getEventDate()} {$game->getEventTime()} {$game->getFieldKey()}\n";
            $team = $game->getHomeTeam();
            if ($team)
            {
                $coach = $team->getHeadCoach();
                if ($coach) $name = $coach->getLastName();
                else        $name = '';
                echo "HOME {$team->getTeamKey()} {$name}\n";
            }
            $team = $game->getAwayTeam();
            if ($team)
            {
                $coach = $team->getHeadCoach();
                if ($coach) $name = $coach->getLastName();
                else        $name = '';
                echo "AWAY {$team->getTeamKey()} {$name}\n";
            }
        }
        
        $teams = $gameManager->querySchTeamsPickList($search,$games);
        $teamCount = count($teams);
        echo "Team Count: {$teamCount}\n";
        foreach($teams as $team)
        {
            ///echo "{$team->getRegionKey()} {$team->getDivisionDesc()} {$team->getTeamKey()} {$team->getPhyTeam()->getHeadCoach()->getLastName()}\n";
        }
        //print_r($teams);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->queryGames($output);
    }
}
