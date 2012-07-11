<?php
namespace Zayso\CoreBundle\Command;

use Zayso\CoreBundle\Component\Debug;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoggerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('core:logger')
            ->setDescription('Logger Tests')
        ;
    }
    protected function test1()
    {
        $logger = $this->getContainer()->get('zayso_core.logger' ); 
        $logger->info('Got the logger');
        
        $context = array('user' => 'ahundiak');
        $logger->info('Some Context',$context);
        
        $sec = $this->getContainer()->get('security.context' );
        $token = $sec->getToken();
        if (is_object($token)) echo "is object\n";
        Debug::dump($token);
        //$user = $token->getUser();
        echo 'Class ' . get_class($token) . "\n";
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test1();
    }
}
?>
