<?php
namespace Zayso\ArbiterBundle\Command;

use Zayso\CoreBundle\Component\Debug;

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
    protected function test2()
    {
        $em = $this->getContainer()->get('zayso_core.account.entity_manager');
        $qb = $em->createQueryBuilder();
        $qb->select('count(account.id)');
        $qb->from('ZaysoCoreBundle:Account','account');
        
        $count = $qb->getQuery()->getSingleScalarResult();
        
        Debug::dump($count);
    }
    protected function test3x($context)
    {
        $context['test'] = 'New value';
        echo $context['test'] . "\n";
    }
    protected function test3()
    {
        $context = array('test' => 'Initial Value');
        $this->test3x($context);
        echo $context['test'] . "\n";
    }
    protected function test4()
    {
        $imageDetails = getImageSize('icon-valid.gif');
        if ($imageDetails)
        {
            $imageDetailsIndexed = array_values($imageDetails);
            print_r($imageDetailsIndexed);
            
            list($width, $height, $type, $dimen, $bits, $channels, $mime) = array_values($imageDetailsIndexed);
                    
            echo 'Type: ' . $imageDetails['mime'] . ' ' . $mime . "\n";
        }
    }
    protected function test5()
    {
        $dql = <<< EOT
SELECT t FROM (AcmeBundle:Task 
    t1 JOIN t1.assignments a1 WHERE a1.member = :member2) 
    t  JOIN t.assignments  a  WHERE a.member = :member1
EOT;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //die(get_class($this->getContainer()));
        
        //$parameters = $this->getContainer()->parameters;
        //print_r($parameters);
        //die(get_class($this->getContainer()));
        
        $container = new \arbiterDevDebugProjectContainer();
        echo 'Count ' . count($container->parameters) . "\n";
        die(count($container->parameters));
        $this->test4();
    }
}
?>
