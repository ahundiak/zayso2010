<?php
namespace Zayso\ArbiterBundle\Command;

use Zayso\CoreBundle\Component\Debug;

use Zayso\ArbiterBundle\Component\ImportLesSchedule;
use Zayso\ArbiterBundle\Component\ImportArbiterSchedule;
use Zayso\ArbiterBundle\Component\CompareSchedules;

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
        $importArbiter = new ImportArbiterSchedule();
        $arbiterGames = $importArbiter->import('../datax/ClassicSchedule20120420.csv');
        
        echo 'Arbiter Game Count: ' . count($arbiterGames) . "\n";
        
        $importLes = new ImportLesSchedule();
        $lesGames  = $importLes->import('../datax/ClassicLed20120420.csv');
        
        echo 'Les     Game Count: ' . count($lesGames) . "\n";
        
        $compare = new CompareSchedules();
        $compare->compare($arbiterGames,$lesGames);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
       
        $this->test6();
    }
}
?>
