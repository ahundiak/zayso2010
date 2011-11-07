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

use Zayso\Osso2007Bundle\Entity\PhyTeamPlayer;

class PdfCommand extends BaseCommandx
{
    protected function configure()
    {
        $this->setName       ('osso2007:pdf');
        $this->setDescription('PDF Generator');
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
    protected function drawMatrix($page)
    {
        $xLeft   = 40.0;
        $yBottom = 50.0;

        $widthName  = 110.0;
        $widthGoals =  40.0;
        $widthSlots =  18.0;
        $heightRow  =  18.0;
        $slots = 18; // 18 *5 = 90
        $rows  = 25;

        $widthTotal = $widthName + $widthGoals + (($slots + 2) * ($widthSlots));
        $heightTotal = $heightRow * $rows;

        $xRight = $xLeft + $widthTotal;
        $yTop = $yBottom + $heightTotal;
        $page->drawRectangle($xLeft,$yBottom,$xRight,$yTop,\Zend_Pdf_Page::SHAPE_DRAW_STROKE);

        for($i = 1; $i < $rows; $i++)
        {
            if (($i % 5) == 1) $xx = 3;
            else               $xx = 0;

            $y = $yTop - ($i * $heightRow);
            $page->drawLine($xLeft-$xx,$y,$xRight,$y);
        }

        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $page->setFont($font,12);

        $one = 12;
        $two = 16;
        $yTopText = $yTop - 14;

        $x = $xLeft+$widthSlots;
        $page->drawLine($x,$yBottom,$x,$yTop);
        $page->drawText('#',$x - $one,$yTopText);

        $x += $widthSlots;
        $page->drawLine($x,$yBottom,$x,$yTop);
        $page->drawText('#',$x - $one,$yTopText);

        $x += $widthName;
        $page->drawLine($x,$yBottom,$x,$yTop);
        $page->drawText('Player Name',$x - $widthName + 3,$yTopText);

        for($i = 0; $i < $slots; $i++)
        {
            $x += $widthSlots;
            $page->drawLine($x,$yBottom,$x,$yTop);
            $min = 5 * ($i + 1);
            if ($i == 0) $xx = $one;
            else         $xx = $two;
            $page->drawText($min,$x - $xx,$yTopText);
        }
        $page->drawText('Goals',$x + 5,$yTopText);

        $players = array('Hundiak, Ethan', 'McReynolds, Connor','Jones, Shadrach');
        $x = $xLeft + $widthSlots + $widthSlots + 3;
        $y = $yTop - $heightRow - $heightRow + 3;
        $page->setFont($font,10);
        foreach($players as $player)
        {
            $player = substr($player,0,20);
            $page->drawText($player,$x,$y);
            $y -= $heightRow;
        }
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->addPlayer();
        return;
        
        // New document
        $doc = new \Zend_Pdf();

        // A page
        $pageTemplate = $doc->newPage(\Zend_Pdf_Page::SIZE_A4);
 
        $pagex = new \Zend_Pdf_Page($pageTemplate);
        $doc->pages[] = $pagex;
        $this->drawMatrix($pagex);

        $page = new \Zend_Pdf_Page($pageTemplate);
        $doc->pages[] = $page;
        
        $width = $page->getWidth();
        $height = $page->getHeight();

        // Height: 842.00, Width: 595.00
        echo sprintf("Height: %.2f, Width: %f\n",$height,$width);

        $x1 =  50.0;
        $x2 = 100.0;
        $y1 = 100.0;
        $y2 = 250.0;

        $page->drawRectangle($x1,      $y1,$x2,      $y2); // Defaults to fill and stroke
        $page->drawRectangle($x1+ 75.0,$y1,$x2+ 75.0,$y2,\Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE);
        $page->drawRectangle($x1+150.0,$y1,$x2+150.0,$y2,\Zend_Pdf_Page::SHAPE_DRAW_STROKE); // Empty
        $page->drawRectangle($x1+225.0,$y1,$x2+225.0,$y2,\Zend_Pdf_Page::SHAPE_DRAW_FILL);

        $x =  50.0;
        $y = 700.0;
        $font1 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $page->setFont($font1,36);
        $page->drawText("Hello World",$x,$y);

        $font2 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_COURIER_BOLD);
        $page->setFont($font2,18);
        $page->drawText("Hello World",$x,$y-50.0);
        
        // Finish up
        $doc->save('../datax/cards.pdf',false);
    }
    protected function drawLine($page,$y)
    {
        $page->drawLine(100.,$y,300.,$y);

        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $page->setFont($font,36);
        $page->drawText('Hello',350.,$y);
    }
    protected function drawPages()
    {
        // New document
        $doc = new \Zend_Pdf();

        $page1 = new \Zend_Pdf_Page(\Zend_Pdf_Page::SIZE_A4);
        $doc->pages[] = $page1;

      //$page2 = new \Zend_Pdf_Page(\Zend_Pdf_Page::SIZE_A4);
      //$doc->pages[] = $page2;

        $this->drawMatrix($page1);

        $page2 = new \Zend_Pdf_Page(\Zend_Pdf_Page::SIZE_A4);
        $doc->pages[] = $page2;

        //$page1->drawLine(100.,100.,300.,100.);
        $page2->drawLine(100.,200.,500.,200.);

        //$this->drawLine($page1,300.);
        $this->drawLine($page2,400.);

        $doc->save('../datax/pages.pdf',false);

    }
    protected function addPlayer()
    {
        $player = new PhyTeamPlayer();
        $player->setFirstName('Ethan');
        $player->setLastName ('Hundiak');
        $player->setAysoid('12345678');
        $player->setJersey(5);

        $em = $this->getContainer()->get('doctrine')->getEntityManager('osso2007');
        $em->persist($player);
        $em->flush();
    }
}
