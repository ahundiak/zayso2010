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

        $projectId = 75; //array(70);
        $ages    = array('U16','U19',);
        $genders = array('B', 'C', 'G');
        
        $date1   = '20111112';
        $date2   = '20111112';

        $search = array(
            'projectId' => $projectId,
            'ages'      => $ages,
            'genders'   => $genders,
          //'regions'   => $regions,
            'date1'     => $date1,
            'date2'     => $date2
        );

        $games = $gameManager->queryGames($search);
        return $games;

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
    }
    protected function drawMatrix($page,$players)
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

        $x = $xLeft + $widthSlots + $widthSlots + 3;
        $y = $yTop - $heightRow - $heightRow + 3;
        $page->setFont($font,10);
        foreach($players as $player)
        {
            $name = $player->getLastName() . ', ' . $player->getFirstName();
            $name = substr($name,0,20);
            $page->drawText($name,$x,$y);

            $number = $player->getJersey();
            if ($number)
            {
                $page->drawText($umber,$x - $widthSlot,$y);
            }
            $y -= $heightRow;
        }
    }
    protected function drawTeamRow($page,$teamx,$team,$yTop)
    {
        $xLeft   =  40.0;

        $yHeight = 25;
        $yBot  = $yTop - $yHeight;
        $yText = $yBot + 4;

        $xTeam  = $xLeft + 45;

        $font1 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $font2 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_COURIER_BOLD);

        if ($teamx->getTeamType() == $team->getTeamType()) $page->setFont($font2,14);
        else                                               $page->setFont($font1,12);

        $page->drawText($team->getTeamType(),$xLeft + 3,$yText);

        // Coach Info
        $coach = $team->getHeadCoach();
        if ($coach) $name = $coach->getLastName();
        else        $name = '';
        $teamDesc = $team->getTeamKey() . ' ' . $name;

        $page->drawText($teamDesc,$xTeam + 3,$yText);
    }
    protected function drawTeams($page,$game,$team,$yTop)
    {
        $xLeft   =  40.0;
        $xRight  = 540.0;

        $yHeight = 25;
        $yBot  = $yTop - ($yHeight * 3);
        $yText = $yTop -  $yHeight + 4;

        $xTeam     = $xLeft     +  45;
        $xGoals    = $xTeam     + 150;
        $xCautions = $xGoals    +  50;
        $xSendoffs = $xCautions +  50;
        $xInjuries = $xSendoffs +  50;
        $xNotes    = $xInjuries +  50;

        $y = $yTop;
        for($i = 0; $i < 4; $i++)
        {
            $page->drawLine($xLeft,$y,$xRight,$y);
            $y -= $yHeight;
        }
        $xs = array($xLeft,$xTeam,$xGoals,$xCautions,$xSendoffs,$xInjuries,$xNotes,$xRight);
        $labels = array('','Team','Goals','Cautions','Sendoffs','Injuries','Notes','');
        foreach($xs as $i => $x)
        {
            $page->drawLine($x,$yTop,$x,$yBot);
            if ($labels[$i])
            {
                $page->drawText($labels[$i],$x+2,$yText);
            }
        }
        $font1 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $font2 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_COURIER_BOLD);

        $this->drawTeamRow($page,$team,$game->getHomeTeam(),$yTop - $yHeight);
        $this->drawTeamRow($page,$team,$game->getAwayTeam(),$yTop - $yHeight - $yHeight);
    }
    protected function processGameTeam($doc,$game,$team)
    {
        // New page
        $page = new \Zend_Pdf_Page(\Zend_Pdf_Page::SIZE_A4);
        $doc->pages[] = $page;

        $font1 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $font2 = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_COURIER_BOLD);

        $xLeft   =  40.0;
        $xRight  = 540.0;

        $yTop    = 800.0;
        $yDrop   = 4;

        $x1GameNum = $xLeft +  40;
        $x2GameNum = $xLeft +  40 + 40;

        $xLeftWork = $x2GameNum;
        $x0Date = $xLeftWork + 10;
        $x1Date = $xLeftWork + 10 + 40;
        $x2Date = $xLeftWork + 10 + 40 + 90;

        $xLeftWork = $x2Date;
        $x0Time = $xLeftWork + 10;
        $x1Time = $xLeftWork + 10 + 40;
        $x2Time = $xLeftWork + 10 + 40 + 80;

        $xLeftWork = $x2Time;
        $x0Field = $xLeftWork + 10;
        $x1Field = $xLeftWork + 10 + 40;
        $x2Field = $xLeftWork + 10 + 40 + 100;

        // Game number
        $page->setFont($font1,12);
        $page->drawText('Game: ',$xLeft,$yTop);

        $page->setFont($font2,14);
        $page->drawText($game->getEventNum(),$x1GameNum,$yTop);
        $page->drawLine($x1GameNum,$yTop-$yDrop,$x2GameNum,$yTop-$yDrop);

        // Date
        $date = $game->getEventDate();
        $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
        $date = date('D M d',$stamp);

        $page->setFont($font1,12);
        $page->drawText('Date: ',$x0Date,$yTop);

        $page->setFont($font2,14);
        $page->drawText($date,$x1Date,$yTop);
        $page->drawLine($x1Date,$yTop-$yDrop,$x2Date,$yTop-$yDrop);

        // Time
        $time = $game->getEventTime();
        $stamp = mktime(substr($time,0,2),substr($time,2,2));
        $time = date('h:i a',$stamp);

        $page->setFont($font1,12);
        $page->drawText('Time: ',$x0Time,$yTop);

        $page->setFont($font2,14);
        $page->drawText($time,$x1Time,$yTop);
        $page->drawLine($x1Time,$yTop-$yDrop,$x2Time,$yTop-$yDrop);

        // Field
        $page->setFont($font1,12);
        $page->drawText('Field: ',$x0Field,$yTop);

        $page->setFont($font2,14);
        $page->drawText($game->getFieldKey(),$x1Field,$yTop);
        $page->drawLine($x1Field,$yTop-$yDrop,$x2Field,$yTop-$yDrop);

        // Teams
        $page->setFont($font1,12);
        $this->drawTeams($page,$game,$team,$yTop - 25);
/*
        $yHeight = 25;
        $yTop = $yTop - $yHeight;
        $yBot = $yTop - $yHeight - $yHeight;

        $y = $yTop;
        $page->drawLine($xLeft,$y,$xRight,$y);

        $y -= $yHeight;
        $page->drawLine($xLeft,$y,$xRight,$y);
        
        $y -= $yHeight;
        $page->drawLine($xLeft,$y,$xRight,$y);

        $page->drawLine($xLeft, $yTop,$xLeft, $yBot);
        $page->drawLine($xRight,$yTop,$xRight,$yBot);

        $xTeam = $xLeft + 40;
        $y = $yTop - $yHeight;

        $coach = $team->getHeadCoach();
        if ($coach) $name = $coach->getLastName();
        else        $name = '';
        $teamDesc = $team->getTeamKey() . ' ' . $name;

        $page->setFont($font1,12);
        $page->drawText($teamDesc,$xTeam,$y+$yDrop);

        $page->setFont($font1,12);
        $page->drawText($team->getTeamType(),$xLeft + 3,$y+$yDrop);
*/
       // The Monitored sub form
        $this->drawMatrix($page,$team->getSchTeam()->getPhyTeam()->getPlayers());
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get the games
        $games = $this->queryGames();
        
        // New document
        $doc = new \Zend_Pdf();

        $game = $games[0];
        //$this->processGameTeam($doc,$game,$game->getHomeTeam());
        //$this->processGameTeam($doc,$game,$game->getAwayTeam());

        foreach($games AS $game)
        {
            $this->processGameTeam($doc,$game,$game->getHomeTeam());
            $this->processGameTeam($doc,$game,$game->getAwayTeam());
        }

        // Finish up
        $doc->save('../datax/games.pdf');

        return;
        
        foreach($games AS $game)
        {
            $this->processGame($doc,$game,$game->getHomeTeam());
            $this->processGame($doc,$game,$game->getAwayTeam());
        }
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
}
