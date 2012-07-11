<?php
namespace Zayso\ArbiterBundle\Command;

use Zayso\CoreBundle\Component\Debug;

use Zayso\ArbiterBundle\Entity\PcastSociete as Company;
use Zayso\ArbiterBundle\Entity\ImUser       as User;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('arbiter:user')
            ->setDescription('ManyToMany')
        ;
    }
    protected function test1()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $company = new Company();
        $em->persist($company);
        
        $user = new User();
        $user->addSociete($company);
        $em->persist($user);
        
        $em->flush();
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test1();
    }    
}
?>
