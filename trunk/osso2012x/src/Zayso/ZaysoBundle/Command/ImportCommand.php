<?php

namespace Zayso\ZaysoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zayso:import')
            ->setDescription('Import From Spreadsheets')
            ->addArgument('file', InputArgument::REQUIRED, 'File Name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputFileName = $input->getArgument('file');
        $output->writeln('Input FilE: ' . $inputFileName);
        $output->writeln('Input FilE: ' . $inputFileName);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $output->writeln('EM ' . get_class($em));
        
        return;
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }
}
