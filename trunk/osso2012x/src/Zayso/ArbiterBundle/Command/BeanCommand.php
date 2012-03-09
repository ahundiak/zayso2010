<?php
namespace Zayso\ArbiterBundle\Command;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BeanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('arbiter:bean')
            ->setDescription('Red Bean')
        ;
    }
    protected function testExample()
    {
        $bean = \R::dispense('leaflet');
        $bean->title = 'Hello World';

        //Store the bean
        $id = \R::store($bean);

        //Reload the bean
        $leaflet = \R::load('leaflet',$id);

        //Display the title
        echo $leaflet->title . "\n";

    }
    protected function testLoad()
    {
        $company = \R::dispense('company');
        $company->name = 'Company Name';
        
        $person1 = \R::dispense('personx');
        $person1->name = 'Person 1';
        $person1->company = $company;
        
        $person2 = \R::dispense('personx');
        $person2->name = 'Person 2';
        $person2->company = $company;
        
        \R::store($company);
        \R::store($person1);
        \R::store($person2);
        
    }
    protected function testQuery()
    {
        \R::debug(true);
        
        $persons = \R::find('personx');
        foreach($persons as $person)
        {
            //$person->company;
            
            if ($person instanceof \ArrayAccess && isset($person['company']))
            {
                echo 'Got Array' . "\n";
            }
            echo get_class($person) . ' ' . $person->name . ' ' . $person->company->name . "\n";
        }
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        require 'rb.php';
        \R::setup('mysql:host=localhost;dbname=osso','impd','impd894'); //mysql
        echo "Connected\n";
        
        $this->testQuery();
    }    
}
?>
