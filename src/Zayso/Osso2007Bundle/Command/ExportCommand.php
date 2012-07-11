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

class ExportCommand extends BaseCommandx
{
    protected function configure()
    {
        $this
            ->setName('osso2007:export')
            ->setDescription('Export Schedules')
        ;
    }
    protected function queryGames()
    {
        $gameManager = $this->getGameManager();

        $projectId = 37; //array(70);
        //$ages    = array('U14','U16', 'U19',);
        //$genders = array('B', 'C', 'G');
        //$regions = array('R0894', 'R1174','R0160','R0498');
        
        //$date1   = '20110818';
        //$date2   = '20110818';

        $search = array(
            'projectId' => $projectId,
          //'ages'      => $ages,
          //'genders'   => $genders,
          //'regions'   => $regions,
          //'date1'     => $date1,
          //'date2'     => $date2
        );

        $games = $gameManager->queryGames($search);
        $gameCount = count($games);
        echo "Game Count: {$gameCount}\n";
        
        $fp = fopen('../datax/ScheduleWinter2011.csv','wt');
        $row = array('PID','Number','Date','Time','Field','Home','Home Coach','Away','Away Coach');
        fputs($fp,implode(',',$row) . "\n");
        
        foreach($games as $game)
        {
            $row = array();
            $row[] = $projectId;
            $row[] = $game->getEventNum();
            $row[] = $game->getEventDate();
            $row[] = $game->getEventTime();
            $row[] = $game->getFieldKey();
            
            $team = $game->getHomeTeam();
            if ($team)
            {
                $coach = $team->getHeadCoach();
                if ($coach) $name = $coach->getLastName();
                else        $name = '';
                $row[] = $team->getTeamKey();
                $row[] = $name;
            }
            $team = $game->getAwayTeam();
            if ($team)
            {
                $coach = $team->getHeadCoach();
                if ($coach) $name = $coach->getLastName();
                else        $name = '';
                $row[] = $team->getTeamKey();
                $row[] = $name;
              //echo "AWAY {$team->getTeamKey()} {$name}\n";
            }
            fputs($fp,implode(',',$row) . "\n");
        }
        fclose($fp);
    }
    protected function queryNextEventNum()
    {
        $gameManager = $this->getGameManager();
        $num = $gameManager->getNextGameNum(70);
        echo "Next Num: $num\n";
    }
    protected function queryTeams()
    {
        $teamManager = $this->getTeamManager();

        $projectId = 70; //array(70);
        $ages    = array('U14',);
        $genders = array('B', 'C');
        $regions = array('R0894');

        $search = array(
            'projectId' => $projectId,
            'ages'      => $ages,
            'genders'   => $genders,
            'regions'   => $regions,
        );
        $teams = $teamManager->queryPhyTeams($search);
        echo 'Team Count ' . count($teams) . "\n";
        foreach($teams as $team)
        {
            $coach = $team->getHeadCoach();
            echo $team->getDivisionSeqNum() . ' ';
            if ($coach) echo $coach->getLastName();
            echo "\n";
        }
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->queryGames($output);
        // $this->queryNextEventNum();
        // $this->queryTeams();
    }
}
