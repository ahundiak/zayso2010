<?php
namespace Zayso\ArbiterBundle\Command;

use Zayso\CoreBundle\Component\Debug;

use Zayso\ArbiterBundle\Schedule\LoadLesSchedule;
use Zayso\ArbiterBundle\Schedule\SaveArbiterSchedule;
use Zayso\ArbiterBundle\Schedule\SaveRefereeSchedule;

use Zayso\ArbiterBundle\Schedule\LoadArbiterSchedule;
use Zayso\ArbiterBundle\Schedule\CompareSchedules;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SchedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('arbiter:sched')
            ->setDescription('Schedule Compare')
        ;
    }
    protected function getManager()   { return $this->getContainer()->get('zayso_arbiter.ref_avail.process'); }
    protected function getProjectId() { return 77; }
    
    protected function test6()
    {
      //$importArbiter = new ImportArbiterSchedule();
      //$arbiterGames = $importArbiter->import('../datax/ClassicSchedule20120420.csv');
        
      //echo 'Arbiter Game Count: ' . count($arbiterGames) . "\n";
       
        $lesLoad = new LoadLesSchedule();
        $lesGames  = $lesLoad->load('../datax/KicksLes20121014.csv');
        
        echo 'Les     Game Count: ' . count($lesGames) . "\n";
        
        $lesSave = new SaveArbiterSchedule();
        $lesSave->save('../datax/KicksArbiter20121014.csv',$lesGames);
        
        return;
        
        $compare = new CompareSchedules();
        $compare->compare($arbiterGames,$lesGames);
    }
    protected function testPositions()
    {
        $arbiterLoad = new LoadArbiterSchedule();
        $arbiterGames = $arbiterLoad->load('../datax/KicksArbiter20121021.csv');
        
        echo 'Arbiter Game Count: ' . count($arbiterGames) . "\n";
     
        $arbSave = new SaveRefereeSchedule();
        $arbSave->save('../datax/KicksReferees20121021.csv',$arbiterGames);
        
        return;
    }
    protected function testCompare()
    {
        $arbiterLoad = new LoadArbiterSchedule();
        $arbiterGames = $arbiterLoad->load('../datax/KicksArbiter20121021.csv');
        
        echo 'Arbiter Game Count: ' . count($arbiterGames) . "\n";
       
        $lesLoad = new LoadLesSchedule();
        $lesGames  = $lesLoad->load('../datax/KicksLes20121017.csv');
        
        echo 'Les     Game Count: ' . count($lesGames) . "\n";
        
        $compare = new CompareSchedules();
        $compare->compare($arbiterGames,$lesGames);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
       
        $this->testCompare();
        $this->testPositions();
    }
}
?>
