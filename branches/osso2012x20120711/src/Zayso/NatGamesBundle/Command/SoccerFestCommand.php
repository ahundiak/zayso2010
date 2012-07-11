<?php

namespace Zayso\NatGamesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Zayso\CoreBundle\Component\Debug;

class SoccerFestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('natgames:soccerfest')
            ->setDescription('Soccer Fest')
        ;
    }
    protected function fest()
    {
        /* ======================================================
         * Field Usage
         *
         * U10B Ball Camp   3 Fields *
         * U10G Ball Camp   3 Fields
         * 
         * U12B Ball Camp   3 Fields # A1-3 v B1-3, C1-3 v D1-3, A4-6 v B4-6, C4-6 v D4-6
         * U12G US Cellular 3 Fields #
         * 
         * U14G US Cellular 3 fields #
         * U14B Ashe Park   3 fields
         * 
         * U16B/G Tarleton  6 fields *
         *
         * U19G Rifle Range 3 Fields *???
         * U19B Schumbert   3 Fields
         * 
         * Thursday GG BB GG BB
         * Friday   BB GG BB GG
         */
    
        //$manager = $this->getContainer()->get('zayso_natgames.ref_stats.manager');
        
        // $manager->process();
        $divs = array('U10B','U10G','U12B','U12G','U14B','U14G','U16B','U16G','U19B','U19G');
        $genders = array('B','G');
        $groups = array('A','B','C','D');
        
        $teams = array('A1','A2','A3','A4','A5','A6');
        
       //$divs = array('U10B');
        
        foreach($divs as $div)
        {
            $age    = substr($div,0,3);
            $gender = substr($div,3,1);
            $pool = $div . ' Soccerfest';
            
            echo sprintf("%s\n",$pool);
         
            foreach($groups as $group)
            {
                echo sprintf("%s %s\n",$pool,$group);
                for($i = 0; $i < 6; $i++)
                {
                    echo sprintf("%s %s%d\n",$pool,$group,$i+1);
                }
            }
        }
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->fest();
        
        return;        
    }
}
