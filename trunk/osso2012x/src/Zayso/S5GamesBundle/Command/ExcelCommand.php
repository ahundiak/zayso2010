<?php

namespace Zayso\S5GamesBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExcelCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('s5games:excel')
            ->setDescription('Spreadsheet Testing')
        ;
    }
    protected function test4()
    {
        $export = $this->getContainer()->get('zayso_s5games.game2011.export');
        
        file_put_contents('../datax/S5Games2011.xls',$export->generate());
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->test4();
    }
}
