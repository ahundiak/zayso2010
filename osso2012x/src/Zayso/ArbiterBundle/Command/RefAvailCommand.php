<?php
namespace Zayso\ArbiterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RefAvailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('arbiter:avail')
            ->setDescription('Referee Availability')
        ;
    }
    protected function getManager()   { return $this->getContainer()->get('zayso_arbiter.ref_avail.process'); }
    protected function getProjectId() { return 77; }
    
    protected function test1()
    {
        $manager = $this->getManager();
        
        $manager->importCSV('../datax/RefAvailWeek04a.csv');
        $manager->exportCSV('../datax/RefAvailWeek04ax.csv');
      
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test1();
    }
}
?>
